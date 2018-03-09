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

if (! function_exists ( 'get_dependencies' )) {
	
	/**
	 *  get_dependencies
	 *  Provides an array containing the dependencies to be loaded into templates
	 */
	function get_dependencies($for,$type) {
		$CI = & get_instance ();
		
		//load models
		$CI->load->model("core/dependencies");
		$raw_data = $CI->dependencies->get($for,$type);
		
		if(count($raw_data) > 0){
			$return_array = array();
			foreach ($raw_data as $item){
				$path = rtrim(FCPATH,"/").$item['path'];
				if(!file_exists($path)){
				  continue;		
				}
				if($type == "css"){
					$return_array[] =  $item['path'];
				}else{
					$return_array[] = $item['path'];
				}
					
			}
			
			return $return_array;
		}else{
			return array();
		}
		
	}
}