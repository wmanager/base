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

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Menu_settings extends Admin_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model("menu_settings_model");
		$this->load->model("company");		
		$this->load->library("form_array_builder");
	}
	
	public function index() {
		$data ['menu'] = $this->menu_settings_model->get_all_menus();
		$data ['content'] = $this->load->view ( 'wmanager/menu_settings/list', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	public function delete_menu($id) {
		if($this->menu_settings_model->delete_menu($id)) {
			$this->session->set_flashdata('result_success', 'Deleted successfully');			
		} else {
			$this->session->set_flashdata('result_error', 'Something Went wrong');		
		}
		redirect('/admin/menu_settings/index/');
	}
	
	public function add_menu() {

		if($this->input->post()){
			if($result = $this->menu_settings_model->add_menu($_POST)){
				$this->session->set_flashdata('result_success', 'Added successfully');
			} else {
				$this->session->set_flashdata('result_error', 'Something Went wrong');
			}
			redirect('admin/menu_settings/index/');
		}
		$data = array();
		$users_group_list = $this->company->get_users_group_list();
		$array_form = array(
				array(
						'id' => 'label',
						'label' => 'Menu name',
						'placeholder' => 'Enter Menu name',
				),
				array(
						'id' => 'link',
						'label' => 'Link',
						'placeholder' => 'Enter link for menu',
				),
				array(
						'id' => 'icon',
						'label' => 'Icon',
						'placeholder' => 'Enter icon for menu',
						'help' => 'Please add the font awsome icon label. Eg.fa-user'
				)
				
		);
		$array_users_group= array();
		foreach ($users_group_list as $usergroup){
			$usergroups[$usergroup->id] = $usergroup->key;
			$users_group=array(
					'id' => $usergroup->key,
					'label' => $usergroup->name,
					'name' => 'access[]',
					'type' => 'checkbox',
					'value' => $usergroup->name,
					'default_value' => $usergroup->name,
					'column' => 'col-md-2',
			);
			array_push($array_users_group,$users_group);
		}
		$array_user_group =   array(
				'id' => 'user_groups',
				'type' => 'combine',
				'label' => 'Group',
				'elements' => $array_users_group );
		
		array_push($array_form,$array_user_group);
		
		$data['form_menu'] = $this->form_builder->build_form_horizontal($array_form);
		echo $this->load->view ( 'wmanager/menu_settings/add_model', $data );
	}
	
	public function edit_menu($user_id) {
		$id = $user_id;

		if($this->input->post()){
			if($result = $this->menu_settings_model->edit($_POST, $user_id)){
				$this->session->set_flashdata('result_success', 'Updated successfully');
			} else {
				$this->session->set_flashdata('result_error', 'Something Went wrong');
			}
			redirect('admin/menu_settings/index/');
		}
		$data = array();
		$menu_list = array();
		
		$data['edit_id'] = $user_id;
		$menu_list = $this->menu_settings_model->get_single_menu($id);		
		
		$users_group_list = $this->company->get_users_group_list();
		$array_form = array(
				array(
						'id' => 'label',
						'label' => 'Menu name',
						'placeholder' => 'Enter Menu name',
				),
				array(
						'id' => 'link',
						'label' => 'Link',
						'placeholder' => 'Enter link for menu',
				),
				array(
						'id' => 'icon',
						'label' => 'Icon',
						'placeholder' => 'Enter icon for menu',
						'help' => 'Please add the font awsome icon label. Eg.fa-user'
				)
	
		);

		$user_group = explode(',', $menu_list->access);
		$array_users_group = array ();
		foreach ( $users_group_list as $usergroup ) {
			$checked = false;
			if (is_array ( $user_group )) {
				for($i = 0; $i < count ( $user_group ); $i ++) {
					if ($usergroup->name == $user_group [$i]) {
						$checked = true;
					}
				}
			}

			$users_group = array (
					'id' => isset ( $usergroup->key ) ? $usergroup->key : NULL,
					'label' => $usergroup->name,
					'name' => 'access[]',
					'type' => 'checkbox',
					'value' => $usergroup->name,
					'default_value' => $usergroup->name,
					'column' => 'col-md-2',
					'checked' => $checked 
			);
			array_push ( $array_users_group, $users_group );
		}
		$array_user_group = array (
				'id' => 'user_groups',
				'type' => 'combine',
				'label' => 'Group',
				'elements' => $array_users_group 
		);
		
		array_push ( $array_form, $array_user_group );
	
		$data['form_menu'] = $this->form_builder->build_form_horizontal($array_form, $menu_list);
		echo $this->load->view ( 'wmanager/menu_settings/add_model', $data );
	}
	
	public function add_child_menu($id, $label = NULL) {
		if($this->input->post()){
			if($result = $this->menu_settings_model->add_child_menu($_POST, $id)){
				$this->session->set_flashdata('result_success', 'Added successfully');
			} else {
				$this->session->set_flashdata('result_error', 'Something Went wrong');
			}
			redirect('admin/menu_settings/index/');
		}
		$data = array();
		$data['parent_id'] = $id;
		$data['parent_name'] = $label;
		$users_group_list = $this->company->get_users_group_list();
		$array_form = array(
				array(
						'id' => 'label',
						'label' => 'Menu name',
						'placeholder' => 'Enter Menu name',
				),
				array(
						'id' => 'link',
						'label' => 'Link',
						'placeholder' => 'Enter link for menu',
				),
				array(
						'id' => 'icon',
						'label' => 'Icon',
						'placeholder' => 'Enter icon for menu',
						'help' => 'Please add the font awsome icon label. Eg.fa-user'
				)
		);
		$array_users_group= array();
		foreach ($users_group_list as $usergroup){
			$usergroups[$usergroup->id] = $usergroup->key;
			$users_group=array(
					'id' => $usergroup->key,
					'label' => $usergroup->name,
					'name' => 'access[]',
					'type' => 'checkbox',
					'value' => $usergroup->name,
					'default_value' => $usergroup->name,
					'column' => 'col-md-2',
			);
			array_push($array_users_group,$users_group);
		}
		$array_user_group =   array(
				'id' => 'user_groups',
				'type' => 'combine',
				'label' => 'Group',
				'elements' => $array_users_group );
		
		array_push($array_form,$array_user_group);
		
		$data['form_menu'] = $this->form_builder->build_form_horizontal($array_form);
		echo $this->load->view ( 'wmanager/menu_settings/add_model', $data );
	}
}	