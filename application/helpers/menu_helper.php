<?php
/**
 * WManager
 *
 * An open source application for business process management
 * and a process automation development framework
 *
 * This content is released under the MIT License (MIT)
 *
 * WManager
 * Copyright (c) 2017 JAMAIN SOCIAL AND SERVICES SRL (http://jamain.co)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     WManager
 * @author      Eng. Gianluca Pelliccioli and JAMAIN SOCIAL AND SERVICES SRL development team
 * @copyright   Copyright (c) 2017 JAMAIN SOCIAL AND SERVICES SRL (http://jamain.co)
 * @license     http://opensource.org/licenses/MIT      MIT License
 * @link        http://wmanager.org
 * @since       Version 1.0.0
 * @filesource
 */

if (! function_exists ( 'top_menu' )) {
	function top_menu($template='wmanager') {
		$CI = & get_instance ();
		
		$CI->load->model("core/dependencies");
		$menu_array = $CI->dependencies->get_menu($template);
		
		$user = $CI->session->userdata ( 'user' );
		$role_details = $CI->ion_auth->get_users_groups ( $user->id )->result ();
		$roles = array ();
		
		foreach ( $role_details as $key => $value ) {
			if ($value->name == 'admin') {
				$roles [] = $value->name;
			}
		}
		
		echo '<ul class="mainnav">'; // Open the menu container
		                             
		// go through each top level menu item
		foreach ( $menu_array as $item ) {
			
			$admin_group = 'admin';
			
			$item ['access'] = array_key_exists ( 'access', $item ) ? $item ['access'] : $admin_group;
			
			if ($CI->ion_auth->in_group ( $item ['access'] )) {
				if (array_key_exists ( 'children', $item )) {
					echo '<li class="dropdown ' . $item ['class'] . '"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><i class="fa ' . $item ['icon'] . '"></i><span>' . $item ['label'] . '</span><b class="caret"></b></a>';
				} else {
					
					if (count ( $roles ) != 2) {
						echo '<li class="' . $item ['class'] . '"><a href="' . $item ['link'] . '"><i class="fa ' . $item ['icon'] . '"></i><span>' . $item ['label'] . '</span></a>';
					}
				}
				// see if this menu has children
				if (array_key_exists ( 'children', $item )) {
					echo '<ul class="dropdown-menu">';
					// echo the child menu
					foreach ( $item ['children'] as $child ) {
						if ($CI->ion_auth->in_group ( $child ['access'] )) {
								echo '<li class="' . $child ['class'] . '"><a href="' . $child ['link'] . '">' . $child ['label'] . '</a></li>';
						}
					}
					echo '</ul>';
				}
				echo '</li>';
			} // end if
		} // end foreach
		
		echo '</ul>';
	}
	
	function menu_display() {
		$CI = & get_instance ();
	
		$CI->load->model("core/dependencies");
		$menu_array = $CI->dependencies->get_menu();
	
		$user = $CI->session->userdata ( 'user' );
		$role_details = $CI->ion_auth->get_users_groups ( $user->id )->result ();
		$roles = array ();
	
		foreach ( $role_details as $key => $value ) {
			if ($value->name == 'admin') {
				$roles [] = $value->name;
			}
		}
		echo '<nav class="navbar navbar-default">';
		echo '<div class="container-fluid">';
		echo '<ul class="nav navbar-nav">'; // Open the menu container
		 
		// go through each top level menu item
		foreach ( $menu_array as $item ) {
				
			$admin_group = 'admin';
				
			$item ['access'] = array_key_exists ( 'access', $item ) ? $item ['access'] : $admin_group;
				
			if ($CI->ion_auth->in_group ( $item ['access'] )) {
				if (array_key_exists ( 'children', $item )) {
					echo '<li class="text-center dropdown ' . $item ['class'] . '"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><i class="fa ' . $item ['icon'] . '"></i><br><span>' . $item ['label'] . '</span><div style="display: block;
    text-align: -webkit-center;"><b style="display:block;" class="caret"></b></div></a>';
				} else {
						
					if (count ( $roles ) != 2) {
						echo '<li class="text-center ' . $item ['class'] . '"><a href="javascript:void(0);"><i class="fa ' . $item ['icon'] . '"></i><br><span>' . $item ['label'] . '</span></a>';
					}
				}
				// see if this menu has children
				if (array_key_exists ( 'children', $item )) {
					echo '<ul class="dropdown-menu">';
					// echo the child menu
					foreach ( $item ['children'] as $child ) {
						if ($CI->ion_auth->in_group ( $child ['access'] )) {
								echo '<li class="' . $child ['class'] . '"><a href="javascript:void(0);">' . $child ['label'] . '</a></li>';
						}
					}
					echo '</ul>';
				}
				echo '</li>';
			} // end if
		} // end foreach
	
		echo '</ul>';
		echo '</div>';
		echo '</nav>';
	}
	
	function admin_menu(){
		$CI = & get_instance ();
		
		$CI->load->model("core/dependencies");
		$menu_array = $CI->dependencies->get_menu('admin');
		
		$user = $CI->session->userdata ( 'user' );
		$role_details = $CI->ion_auth->get_users_groups ( $user->id )->result ();
		$roles = array ();
		
		foreach ( $role_details as $key => $value ) {
			if ($value->name == 'admin') {
				$roles [] = $value->name;
			}
		}
		
		foreach ( $menu_array as $item ) {
				
			$admin_group = 'admin';
				
			$item ['access'] = array_key_exists ( 'access', $item ) ? $item ['access'] : $admin_group;
				
			if ($CI->ion_auth->in_group ( $item ['access'] )) {
				if (array_key_exists ( 'children', $item )) {
					echo '<li class="dropdown ' . $item ['class'] . '"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><span>' . $item ['label'] . '</span><b class="caret"></b></a>';
				} else {
						
					if (count ( $roles ) != 2) {
						echo '<li class="' . $item ['class'] . '"><a href="' . $item ['link'] . '"><span>' . $item ['label'] . '</span></a>';
					}
				}
				echo '</li>';
			} // end if
		} // end foreach
		
	}
}
