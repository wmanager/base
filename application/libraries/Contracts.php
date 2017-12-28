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

class Contracts{
	
	public function check_client_exists($pic=NULL,$type = NULL){
		$this->CI = & get_instance ();
		$this->CI->load->model("inorder_model");
		
		if($pic == NULL){
			return array(
				"status" => "FAIL",
				"message" => "Code entered is a null value"	
			);	
		}else if($type == NULL){
			return array(
					"status" => "FAIL",
					"message" => "Account type not selected"
			);
		}
		
		//check client exists
		$check = $this->CI->inorder_model->check_client($pic,$type);
		
		if($check == TRUE){
			return array(
				"status" => "YES",
				"message" => "Client already exists"	
			);
		}else if($check == FALSE){
			return array(
					"status" => "NO",
					"message" => "Client doesn't exist"
			);
		}
	}
	
	public function create_new_contract($data){

		$this->CI = & get_instance ();
		$this->CI->load->model("inorder_model");
		if(!is_array($data) || empty($data)){
			return array(
					"status" => "FAIL",
					"message" => "Post value is empty"
			);
		}
		
		//get account id
		if($data['account'] == 'NEW'){
			$account_id = $this->new_client($data);
		}else if($data['account'] == 'OLD'){
			$account_id = $this->CI->inorder_model->get_client_id($data['code'],$data['account_type']);
		}
		
		if($account_id <= 0){
			return array(
				"status" => FALSE,
				"message" => "Account creation failed"	
			);
		}
		
		//create New BE and constalation
		$final_data = $this->new_be($data,$account_id);
		
		return $final_data;
	}
	
	
	public function new_client($data){
		$this->CI = & get_instance ();
		$this->CI->load->model("inorder_model");
		
		$code = $data['code'];
		$type = $data['account_type'];
		
		$client = $this->CI->inorder_model->create_new_client($code,$type);
		if($client['status'] == TRUE){
			return $client['account_id'];
		}else{
			return 0;
		}
	}
	
	
	public function new_be($data,$account_id){
		$this->CI = & get_instance ();
		$this->CI->load->model("inorder_model");
		
		//create BE
		$create_be = $this->CI->inorder_model->create_be($data,$account_id);
		
		if($create_be['status'] == FALSE){
			return array(
				"status" => FALSE,
				"message" => "BE failed to created"	
			);	
		}
		$be_id = $create_be['be_id'];
		
		$data["account_id"] = $account_id;
		$data["be_id"] = $be_id;
		
		//new contract
		$contract = $this->CI->inorder_model->new_contract($data);
		
		if($contract['status'] == FALSE){
			return array(
					"status" => FALSE,
					"message" => "contract failed to created"
			);
		}
		$data['contract_id'] = $contract['contract_id'];
		
		//new asset
		$asset = $this->CI->inorder_model->new_asset($data);
		if($asset['status'] == FALSE){
			return array(
					"status" => FALSE,
					"message" => "contract failed to created"
			);
		}
		$data['asset_id'] = $asset['asset_id'];
		
		return array(
			"status" => TRUE,
			"data"	=> $data
		);
	}
	public function delete_client($account_id,$be_id){
		
		$this->CI = & get_instance ();
		$this->CI->load->model("inorder_model");
		
		if($account_id <= 0 || $be_id <= 0){
			return array(
				"status" => FALSE,
				"message" => "POST values are not proper."	
			);
		}
		
		
		$delete = $this->CI->inorder_model->delete_client($account_id,$be_id);
		
		if($delete == TRUE){
			return array(
				"status" => TRUE,
				"message" => "Client deleted successfully."	
			);
		}else{
			return array(
					"status" => FALSE,
					"message" => "Failed to delete client."
			);
		}
	}
}