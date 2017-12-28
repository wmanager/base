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
class  Form_array_builder{
	
	var $CI;
	public function __construct() {
		log_message ( 'debug', "Form array Class Initialized" );
	}
	
	public function create_form_array($module = NULL, $type = 'module'){
		$CI = & get_instance ();
		$CI->load->model ( 'form_array_data' );
		
		if($module == NULL && $type == "module"){
			return array();
		}
		
		if($type == 'module'){
			$form_fields = $CI->form_array_data->get_fields($module);
		}else{
			$form_fields = $module;
		}
		
		if(!is_array($form_fields) || count($form_fields)==0){
			return array();
		}
		
		$main_array = array();
		$i=0;
		foreach($form_fields as $item){
			if($item->type == ''){
				continue;
			}
			
			//get html type
			$field_type = $this->get_db2html($item->type);
			if($field_type === false){
				continue;
			}
			
			//get form array
			$field_array = $this->get_field_array($field_type,$item);
			
			
			if(count($field_array) == 0){
				continue;
			}
			
			$main_array[$i] = $field_array;
			$i++;
		}
		
		return $main_array;
	}
	
	public function get_db2html($type){
		
		$field_array = array(
			"integer" => "number",
			"dropdown" => "dropdown",
			"date" => "date",
			"text" => "text",
			"checkbox" => "checkbox",	
			"boolean" => "radio"	
		);
		
		if(isset($field_array[$type])){
			return $field_array[$type];
		}else{
			return false;
		}
		
	}
	
	public function get_field_array($type, $data){
		
		
		if($type == NULL || count($data)==0) return FALSE;
		
		$return_array = array();
		
		//set id
		$return_array['id'] = $data->key;
		
		//set type
		if($data->type != 'text')
		$return_array['type'] = $data->type;
		
		//set label
		$return_array['label'] = ucfirst(strtolower(str_replace("_"," ",$data->key)));
		
		//set placeholder
		$return_array['placeholder'] = "Enter ".ucfirst(strtolower(str_replace("_"," ",$data->key)));
		
		//set class
		if($type == 'date'){
			$return_array['class'] = "date_picker";
		}else if($type == 'dropdown'){
			$return_array['class'] = "form-control";
		}else{
			$return_array['class'] = "";
		}
		
		//for checkbox
		if($type == 'checkbox'){
			$return_array['default'] = 'f';
		}
		
		//set options
		if($type == 'dropdown'){
			$options = explode(",",trim($data->option,","));
			
			if(count($options) > 0){
				$option_array = array();
				foreach ($options as $option_item){
					$key = strtolower($option_item);
					$option_array[$key] = ucfirst($option_item); 
				}
				$return_array['options'] = $option_array;
			}else{
				$return_array['options'] = array("-"=>"SELECT");
			}
		}
		
		
		return $return_array;
		
	}
	
}