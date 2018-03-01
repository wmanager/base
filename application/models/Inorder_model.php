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
class Inorder_model extends CI_Model {
	
	public function check_client($pic,$account_type){
		$check_query = $this->db->select("id")->where("account_type",$account_type)->where("code",$pic)->get("accounts");
		if($check_query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function get_client_id($pic,$account_type){
		$check_query = $this->db->select("id")->where("account_type",$account_type)->where("code",$pic)->get("accounts");
		if($check_query->num_rows() > 0){
			return $check_query->row()->id;
		}else{
			return 0;
		}
	}
	
	public function create_client_contact($data, $account_id) {
		$data_array = array(
				'contact_type' => 'tel',
				'account_id' => $account_id
		);
		if(!$this->db->insert("contacts",$data_array)){
			return array(
					"status" => FALSE,
					"message" => "failed to create contact"
			);
		} else {
			return array(
					"status" => TRUE,
					"message" => "Created contact"
			);
		}
	}
	
	public function create_new_client($code,$type){
		
		//new account
		$account_data = array(
			"code" => $code,
			"account_type" => $type,
			"created" => date("Y-m-d H:i:s")	
		);
		
		if(!$this->db->insert("accounts",$account_data)){
			return array(
					"status" => FALSE,
					"message" => "failed to create account"
			);
		}
		
		$account_id = $this->db->insert_id();
		
		//new address
		$address_data = array(
			"type" => "CLIENT"	
		);
		if(!$this->db->insert("address",$address_data)){
			return array(
					"status" => FALSE,
					"message" => "failed to create address"
			);
		}
		
		
		$address_id = $this->db->insert_id();
		
		$account_data = array(
			"address_id" => $address_id,
			"modified" => date("Y-m-d H:i:s")	
		);
		
		if($this->db->where("id",$account_id)->update("accounts",$account_data)){
			return array(
				"status" => TRUE,
				"account_id" => $account_id
			);
		}else{
			return array(
					"status" => FALSE,
					"message" => "failed to update account"
			);
		}
	}
	
	public function create_be($data,$account_id){
		$address_id = $this->add_address("INVOICE");
		$be_data = array(
			"account_id" => $account_id,
			"be_status" => "ACTIVE",
			"be_code" => $data['vat'],
			"type" => "BUYER",
			"invoice_address" => $address_id, 	
			"created" => date("Y-m-d H:i:s")	
		);
		
		if($this->db->insert("be",$be_data)){
			return array(
				"status" => TRUE,
				"be_id" => $this->db->insert_id()	
			);
		}else{
			return array(
				"status" => FALSE,
				"massage" => "BE failed to create" 	
			);
		}
	}
	
	public function new_contract($data){
		$contract_data = array(
			"contract_code" => "WMAN".rand(10000,99999),
			"contract_type" => "BUYER",
			"d_sign" => date("Y-m-d"),
			"validity_start" => date("Y-m-d"),
			"validity_end" => date('Y-m-d', strtotime('+1 years')),
			"created" => date("Y-m-d H:i:s")	
		);
		
		if($this->db->insert("contracts",$contract_data)){
			return array(
				"status" => TRUE,
				"contract_id" => $this->db->insert_id()	
			);
		}else{
			return array(
				"status" => FALSE,
				"message" => "Failed to created contract"	
			);
		}
	}
	
	public function new_asset($data){
		$asset_data = array(
			"be_id" => $data['be_id'],
			"contract_id" => $data['contract_id'],
			"start_date" => date("Y-m-d"),
			"end_date" => date('Y-m-d', strtotime('+1 years')),
			"assets_type" => "PRODUCT",
			"created" => date("Y-m-d H:i:s")			
		);
		
		if($this->db->insert("assets",$asset_data)){
			$asset_id = $this->db->insert_id();
			
			return array(
					"status" => TRUE,
					"asset_id" => $asset_id
			);
		}else{
			return array(
					"status" => FALSE,
					"message" => "Failed to created asset"
			);
		}
	}
	
	public function add_address($type){
		//new address
		$address_data = array(
				"type" => $type
		);
		if($this->db->insert("address",$address_data)){
			return $this->db->insert_id();
		}else{
			return 0;
		}
	}
	
	public function get_accounts($id){
		
		$query = $this->db->select("*")->where("id",$id)->get("accounts");
		
		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
				return array();
			}
		
	}
	
	public function get_contact($id){
	
		$query = $this->db->select("value as tel")->where("account_id",$id)->where("contact_type","tel")->get("contacts");

		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return array();
		}
	
	}
	
	public function get_address($id,$asset_id){
		
		$return_array = array();
		
		//CLIENT
		$query = $this->db->select("address.*")
							->join("accounts","accounts.address_id = address.id","left")
							->where("accounts.id",$id)->where("type","CLIENT")->get("address");
		
		if($query->num_rows() > 0){
			$return_array['CLIENT'] =  $query->row_array();
		}else{
			$return_array['CLIENT'] = array();
		}
		
		
		//invoice
		$query = $this->db->select("address.*")
		->join("assets","assets.be_id = be.id","left")
		->join("address","address.id = be.invoice_address","left")
		->where("assets.id",$asset_id)->get("be");
		
		if($query->num_rows() > 0){
			$return_array['INVOICE'] =  $query->row_array();
		}else{
			$return_array['INVOICE'] = array();
		}
		
		
		return $return_array;
	}
	
	public function get_be($be_id){
		
		$query = $this->db->select("be.*")->where("id",$be_id)->get("be");
		
		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return array();
		}
	
	}
	
	public function get_asset($id){
		$query = $this->db->select("assets.*, contracts.product_id")
			->join("contracts", "contracts.id = assets.contract_id")
			->where("assets.id",$id)->get("assets");
		
		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return array();
		}
	}
	
	
	public function get_contracts($contract_id = NULL){
		if($contract_id <= 0 || $contract_id == ''){
			return array();
		}
		
		$query = $this->db->select("contracts.*")->where("id",$contract_id)->get("contracts");
		
		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return array();
		}
	}
	
	public function get_products(){
		
		$query = $this->db->select("id,title")->get("products");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return array();
		}
	}
	
	public function delete_client($account_id,$be_id){
		
		//check weather single or multiple BE
		$check_be = $this->check_be($account_id);
		
		if($check_be > 1){
			$delete = $this->delete_be($be_id);
		}else if($check_be == 1){
			$delete = $this->delete_account($account_id,$be_id);
		}
		
		return $delete;
	}
	
	
	public function check_be($account_id){
		
		$query = $this->db->select("count(id)")->where("account_id",$account_id)->get("be");
		$result = $query->row()->count;
		
		if($query->num_rows() > 0){
			return $result;
		}else{
			return 0;
		}
	}
	
	public function delete_account($account_id,$be_id){
		$delete_be = $this->delete_be($be_id);
		
		$delete_account = $this->db->where("id",$account_id)->delete("accounts");
		
		if($delete_account && $delete_be){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function delete_be($be_id){
		$delete_assets = $this->db->where("be_id",$be_id)->delete("assets");
		$delete_be = $this->db->where("id",$be_id)->delete("be");
		
		if($delete_assets && $delete_be){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	

	public function update_client($data){

		
		$accounts = $data['account'];
		$accounts =  (array) $accounts;

		

		//update accounts
		$account_data = array(
			"first_name" =>  $accounts['first_name'],
			"last_name" => $accounts['last_name'],
			"code" => $accounts["code"]	
		);
		
		$account_update = $this->db->where("id",$accounts['id'])->update("accounts",$account_data);
		
		$contact = $data['contact'];
		$contact =  (array) $contact;
		
		
		$contact_data = array(
				"value" => $contact['tel'],
				"contact_type" => 'tel'
		);
		// client contact
		
		$contact_update = $this->db->where("account_id",$accounts['id'])->update("contacts",$contact_data);
		
		//address client
		
		$address = $data['client_address'];
		$address =  (array) $address;
		$client_address = $this->update_address($accounts['address_id'],$address);
		
		//update BE
		$be = $data['be'];
		$be =  (array) $be;
		$be_data = array(
			"be_code" =>  $be['be_code'],
			"invoice_method" => $be['invoice_method']	
		);
		$be_update = $this->db->where("id",$be['id'])->update("be",$be_data);
		
		if($be['invoice_method'] == 'address'){
			$address = $data['invoice_address'];
			$address =  (array) $address;
			$invoice_address = $this->update_address($be['invoice_address'],$address);
		}
		
		//contract
		$contract_id = $data['asset']->contract_id;
		$product_id = $data['asset']->product_id;
		
		if($contract_id > 0){
			$this->db->where("id",$contract_id)->update("contracts",array("product_id"=>$product_id));
		}
		
		
		if($be_update && $client_address && $account_update){
			return array(
				"status" => TRUE,
				"message" => "Update of customer was successfull"		
			);
		}else{
			return array(
					"status" => FALSE,
					"message" => "Failed to update the customer"
			);
		}
	}
	
	public function update_address($id,$address){
		$address_data = array(
				"address" => $address['address'],
				"zip" => $address['zip'],
				"city" => $address['city'],
				"state" => $address['state'],
				"country" => $address['country'],
		);
		
		if($id > 0 && count($address)>0){
			return $this->db->where("id",$id)->update("address",$address_data);
		}else{
			return false;
		}
	}
	
}
