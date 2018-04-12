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
class Setup_products extends Admin_Controller {
	
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'product' );
		$this->load->library("form_array_builder");
	}
	
	public function index() {
		
		//message handling
		if($this->session->flashdata("product_message")){
			$data['message'] = $this->session->flashdata("product_message"); 
		}
		
		$data["products"] = $this->product->get();
		$data ['content'] = $this->load->view ( 'wmanager/products/list', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	public function delete($id = null){
		
		if($id == NULL){
			$this->session->set_flashdata("product_message","ID for delete was not provided.");
			redirect("/admin/setup_products/");
		}
		
		$delete = $this->product->delete_product($id);
		
		if($delete){
			$this->session->set_flashdata("product_message","Product removed successfully.");
			redirect("/admin/setup_products/");
		}else{
			$this->session->set_flashdata("product_message","unable to delete the product. Please try after some time.");
			redirect("/admin/setup_products/");
		}
	}
	
	public function add(){
		
		if(count($_POST)!= 0){
			$add = $this->product->add_product($_POST);
			
			if($add['status']){
				$this->session->set_flashdata("product_message",$add['message']);
				redirect("/admin/setup_products/");
			}else{
				$this->session->set_flashdata("product_message",$add['message']);
				redirect("/admin/setup_products/");
			}
		}
		
		$form_array 		= $this->form_array_builder->table_structure('products');
		$data ['form_add'] 	= $this->form_builder->build_form_horizontal ( $form_array );
		$data ['content'] 	= $this->load->view ( 'wmanager/products/add', $data, true );
		
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	public function edit($id){
		if(count($_POST)!= 0){
			$add = $this->product->edit_product($id,$_POST);
				
			if($add['status']){
				$this->session->set_flashdata("product_message",$add['message']);
				redirect("/admin/setup_products/");
			}else{
				$this->session->set_flashdata("product_message",$add['message']);
				redirect("/admin/setup_products/");
			}
		}
		$data['product_details'] = $this->product->get_product_detail($id);
		if($data['product_details']->selling_date != NULL){
			$var = $data['product_details']->selling_date;
			$data['product_details']->selling_date =  date("Y-m-d", strtotime(str_replace("/","-",$var)) );
		}
		
		if($data['product_details']->selling_end != NULL){
			$var = $data['product_details']->selling_end;
			$data['product_details']->selling_end =  date("Y-m-d", strtotime(str_replace("/","-",$var)) );
		}
		
		$form_array 			 = $this->form_array_builder->table_structure('products');
		$data ['form_edit'] 	 = $this->form_builder->build_form_horizontal ($form_array,$data['product_details']);
		$data ['content'] 	= $this->load->view ( 'wmanager/products/edit', $data, true );
		
		$this->load->view ( 'wmanager/admin_template', $data );
	}
}