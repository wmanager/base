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
class Module_inorder extends Common_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->library("contracts");
		$this->load->model("inorder_model");
	}
	
	
	public function index() {
		$data = array ();
	
		$data ['content'] = $this->load->view ( 'common/module/create_contract', $data, true );
		$this->load->view ( 'template', $data );
	}
	
	public function check_client_exists(){
		$array = json_decode ( file_get_contents ( 'php://input' ) );
		$data =  (array) $array;
		$check = $this->contracts->check_client_exists($data['code'],$data['account_type']);
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $check ) );
	}
	
	public function create_new_contract(){
		$array = json_decode ( file_get_contents ( 'php://input' ) );
		$data =  (array) $array;
		$contract_response = $this->contracts->create_new_contract($data);
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $contract_response ) );
	}
	

	public function get_account_details($id = NULL,$be_id = NULL,$asset_id = NULL){
		if($id == NULL){
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array() ) );
		}
				
		$data = array(
			"account" => $this->inorder_model->get_accounts($id),
			"address" => $this->inorder_model->get_address($id,$asset_id),
			"be"	  => $this->inorder_model->get_be($be_id),
			"asset"   => $this->inorder_model->get_asset($asset_id),
		);
		$data["contract"] = $this->inorder_model->get_contracts($data['asset']['contract_id']);
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $data ) );
	}
	
	public function account_details($account_id,$be_id,$asset_id) {
		$data = array ();
		$data['account_id'] = $account_id;
		$data['be_id'] = $be_id;
		$data['asset_id'] = $asset_id;
		
		$data ['content'] = $this->load->view ( 'common/module/create_contract', $data, true );
		$this->load->view ( 'template', $data );
	}

	public function get_product_details(){
		$data = $this->inorder_model->get_products();
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $data ) );
	}
	

	public function delete_client($account_id,$be_id){
		$delete = $this->contracts->delete_client($account_id,$be_id);
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $delete ) );
	}
	
	public function update_client(){
		$array = json_decode ( file_get_contents ( 'php://input' ) );
		$data =  (array) $array;
		$update = $this->inorder_model->update_client($data);
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $update ) );
	}

}	