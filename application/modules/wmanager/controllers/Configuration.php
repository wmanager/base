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
class Configuration extends Admin_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model("configuration_model");
		$this->load->library("form_array_builder");
	}
	
	public function index(){
		$data["modules"] = $this->configuration_model->get_modules();
		
		//save message
		if($this->session->flashdata("save_config_message") != ''){
			$message = $this->session->flashdata("save_config_message");
			if(strpos($message,"fail")>0){
				$status = "error";
			}else{
				$status = "success";
			}
			
			$data['message'] = array(
									"status" => $status,
									"message" => $message	
									);
		}else{
			$data['message'] = array();
		}
		
		//module filter
		if((isset($_POST['module']) && $_POST['module'] != '')){
			$mode   = 'SELECTED';
			$module = $_POST['module'];
			$this->session->set_userdata("module_filter",$module);
		}else if($this->session->userdata("module_filter") && !isset($_POST['module'])){
			$mode   = 'SELECTED';
			$module = $this->session->userdata("module_filter");
		}else{
			$this->session->set_userdata("module_filter",'');
		}
		
		
		
		if(count($data["modules"]) > 0 && $mode=="SELECTED"){
			$data["configs"] = $this->configuration_model->get_configs($module);
			if(count($data["configs"]) > 0){
				$data["form_fields"] = $this->form_array_builder->create_form_array($data["configs"],"array");
				$form_name = "form_".$module;
				$data["form_data"][$form_name] = $this->configuration_model->get_config_values($module);
				$data['forms'][$form_name] = $this->form_builder->build_form_horizontal ( $data["form_fields"],$data["form_data"][$form_name]);
			}
		}else{
			if(count($data["modules"]) > 0){
				foreach($data["modules"] as $item){
					$data["configs"][$item] = $this->configuration_model->get_configs($item);
					if(count($data["configs"][$item]) > 0){
						$data["form_fields"][$item] = $this->form_array_builder->create_form_array($data["configs"][$item],"array");
						$form_name = "form_".$item;
						$data["form_data"][$form_name] = $this->configuration_model->get_config_values($item);
						$data['forms'][$form_name] = $this->form_builder->build_form_horizontal ( $data["form_fields"][$item],$data["form_data"][$form_name]);
					}
				}
			}
		}
		
		$data ['content'] = $this->load->view ( 'wmanager/configuration/list', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	public function save_config(){
		if(isset($_POST) && count($_POST)>0){
			if($this->configuration_model->save_config($_POST)){
				$this->session->set_flashdata("save_config_message","Configuration changes saved successfully.");
				redirect("/admin/configuration/");
			}else{
				$this->session->set_flashdata("save_config_message","Failed to save the configuartion changes.");
				redirect("/admin/configuration/");
			}
		}
	}
}	