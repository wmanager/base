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
class Setup_roles extends Admin_Controller {
	
	public function __construct() {
		parent::__construct ();
		$this->load->model('setup_acl');
		$this->load->library("form_array_builder");
	}
	
	public function index() {
		$data["roles"] = $this->setup_acl->get_roles();
		$data ['content'] = $this->load->view ( 'wmanager/setup_roles/list', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	public function add() {
	
		if(count($_POST)!= 0){
			$add = $this->setup_acl->add_role($_POST);
				
			if($add['status']){
				$this->session->set_flashdata('growl_show', true);
				$this->session->set_flashdata('growl_success',$add['message']);
				redirect("/admin/setup_roles/");
			}else{
				$this->session->set_flashdata('growl_show', true);
				$this->session->set_flashdata('growl_error', $add['message']);
				redirect("/admin/setup_roles/");
			}
		}
		
		$parent_role		= $this->setup_acl->get_parent_roles();
		$form_array 		= $this->form_array_builder->table_structure('setup_roles');
		
		foreach ($form_array as $key => $item){
			if($item['id'] == 'parent_role'){
				$form_array[$key]['label']  = 'Parent Role';
				$form_array[$key]['type']  = 'dropdown';
				$form_array[$key]['class']  = 'form-control';
				$form_array[$key]['options'] = $parent_role;
			}
			
			if($item['id'] == 'key'){
				$form_array[$key]['required'] = true;
			}
		}
		
		$data ['form_add'] 	= $this->form_builder->build_form_horizontal ( $form_array );
		$data ['content'] 	= $this->load->view ( 'wmanager/setup_roles/add', $data, true );
		
		$this->load->view ( 'wmanager/admin_template', $data );
	
	}
	
	public function edit($id) {
	
		if(count($_POST)!= 0){
			$add = $this->setup_acl->edit_role($id,$_POST);
		
			if($add['status']){
				$this->session->set_flashdata('growl_show', true);
				$this->session->set_flashdata('growl_success',$add['message']);
				redirect("/admin/setup_roles/");
			}else{
				$this->session->set_flashdata('growl_show', true);
				$this->session->set_flashdata('growl_error',$add['message']);
				redirect("/admin/setup_roles/");
			}
		}
		$data['role_details'] = $this->setup_acl->get_role_detail($id);
		$parent_role			 = $this->setup_acl->get_parent_roles($id);
		$form_array 			 = $this->form_array_builder->table_structure('setup_roles');
		
		foreach ($form_array as $key => $item){
			if($item['id'] == 'parent_role'){
				$form_array[$key]['label']  = 'Parent Role';
				$form_array[$key]['type']  = 'dropdown';
				$form_array[$key]['class']  = 'form-control';
				$form_array[$key]['options'] = $parent_role;
			}
				
			if($item['id'] == 'key'){
				$form_array[$key]['required'] = true;
				$form_array[$key]['disabled'] = true;
			}
		}
		
		$data ['form_edit'] 	 = $this->form_builder->build_form_horizontal ($form_array,$data['role_details']);
		$data ['content'] 		 = $this->load->view ( 'wmanager/setup_roles/edit', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	
	}
	
	public function delete($id) {
		$delete = $this->setup_acl->delete_role($id);
		
		if($delete['status']){
			$this->session->set_flashdata('growl_show', true);
			$this->session->set_flashdata('growl_success',$delete['message']);
			redirect("/admin/setup_roles/");
		}else{
			$this->session->set_flashdata('growl_show', true);
			$this->session->set_flashdata('growl_error',$delete['message']);
			redirect("/admin/setup_roles/");
		}
	}
	
}	