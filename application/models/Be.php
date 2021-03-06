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
class Be extends CI_Model {
	public function get($limit, $offset = 0) {
		$company = $this->ion_auth->user ()->row ()->id_company;
		
		if (trim($this->input->post ( 'cliente' )) != '') {
			$this->session->set_userdata ( 'filter_be_cliente', $this->input->post ( 'cliente' ) );
		} else if (isset ( $_POST ['cliente'] ) && $_POST ['cliente'] == '') {
			$this->session->unset_userdata ( 'filter_be_cliente' );
		}
		
		if (trim($this->input->post ( 'contratto' )) != '') {
			$this->session->set_userdata ( 'filter_be_contratto', $this->input->post ( 'contratto' ) );
		} else if (isset ( $_POST ['contratto'] ) && $_POST ['contratto'] == '') {
			$this->session->unset_userdata ( 'filter_be_contratto' );
		}
		
		if(trim($this->input->post('status')) && $this->input->post('status')!='') {
			$this->session->set_userdata('filter_be_status',$this->input->post('status'));
		} else if(isset($_POST['status']) && $_POST['status'] == ''){
			$this->session->unset_userdata('filter_be_status');
		}
			
		
		
		$filter1 = $this->session->userdata ( 'filter_be_cliente' );
		$filter2 = $this->session->userdata ( 'filter_be_contratto' );
		$filter3 = $this->session->userdata('filter_be_status');
		

		
		if ($offset == '')
			$offset = 0;
		
		$set_and = false;
		$sql = "SELECT be.*, 
							accounts.*, inst.name as installatore,
							contracts.*, contracts.created as data_contratto,
							inst.name as agenzia
							FROM be 
							LEFT JOIN accounts ON be.account_id = accounts.id					
							LEFT JOIN assets ON assets.be_id = be.id
							LEFT JOIN contracts ON contracts.id = assets.contract_id
							LEFT JOIN companies inst ON inst.id = accounts.company_id						
							";
		if($filter1 || $filter2 || $filter3) {
			$sql .= " WHERE";
				
		}
		
		
		if ($filter1) {
			$sql .= " (accounts.first_name ILIKE '%$filter1%' OR accounts.last_name ILIKE '%$filter1%'  OR accounts.code ILIKE '%$filter1%' )";
			$set_and = true;
		}
		
		if($set_and && $filter1){
			$sql .= ' AND ';
			$set_and = FALSE;
		}
		
		if ($filter2) {
			$sql .= " (contracts.contract_code ILIKE '%$filter3%')";
			$set_and = true;
		}
		
		if($set_and && $filter2){
			$sql .= ' AND ';
			$set_and = FALSE;
		}
		
		
		if($filter3){
			$sql .= " be.be_status = '$filter3'";
		}
		
		$sql .= " LIMIT $limit OFFSET $offset";
		
		$query = $this->db->query ( $sql );
		return $query->result ();
	}
	public function total() {
		$filter1 = $this->session->userdata ( 'filter_be_cliente' );
		$filter2 = $this->session->userdata ( 'filter_be_contratto' );
		$filter3 = $this->session->userdata('filter_be_status');

		$set_and = false;
		$sql = "SELECT be.*, 
							accounts.*, inst.name as installatore,
							contracts.*, contracts.created as data_contratto,
							inst.name as agenzia
							FROM be
							LEFT JOIN accounts ON be.account_id = accounts.id
							LEFT JOIN assets ON assets.be_id = be.id
							LEFT JOIN contracts ON contracts.id = assets.contract_id
							LEFT JOIN companies inst ON inst.id = accounts.company_id
							LEFT JOIN setup_master_status ON setup_master_status.key = be.be_status
							";
		
	
		if($filter1 || $filter2 || $filter3) {
			$sql .= " WHERE";
				
		}

		if ($filter1) {
			$sql .= " (accounts.first_name ILIKE '%$filter1%' OR accounts.last_name ILIKE '%$filter1%'  OR accounts.code ILIKE '%$filter1%' )";
			$set_and = true;
		}
		
		if($set_and && $filter1){
			$sql .= ' AND ';
			$set_and = FALSE;
		}
		
		if ($filter2) {
			$sql .= " (contracts.contract_code ILIKE '%$filter3%')";
			$set_and = true;
		}
		
		if($set_and && $filter2){
			$sql .= ' AND ';
			$set_and = FALSE;
		}
		
		if($filter3){
			$sql .= " be.be_status = '$filter3'";
		}
		
		$query = $this->db->query ( $sql );
		
		return $query->num_rows ();
	}
	public function detail($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'be' );
		return $query->row ();
	}

	public function get_master_statuses() {
		$query = $this->db->select ( '*' )->get ( 'setup_master_status' );
		return $query->result ();
	}
	public function export() {
		$filter1 = $this->session->userdata ( 'filter_be_cliente' );
		$filter2 = $this->session->userdata ( 'filter_be_contratto' );
		$filter3 = $this->session->userdata('filter_be_status');
		$sql = "SELECT be.be_code,
						be.be_status,
						be.type as be_type,
						accounts.id as account_id,
						accounts.account_type,
						accounts.first_name,
						accounts.last_name,
						accounts.code,
						inst.name as comapany,
						contracts.contract_code,
						contracts.contract_type,
						contracts.d_sign,
						contracts.validity_start,
						contracts.validity_end
							FROM be LEFT JOIN threads ON threads.be = be.id
							LEFT JOIN accounts ON be.account_id = accounts.id
							LEFT JOIN assets ON assets.be_id = be.id
							LEFT JOIN contracts ON contracts.id = assets.contract_id
							LEFT JOIN companies inst ON inst.id = accounts.company_id
							LEFT JOIN setup_master_status ON setup_master_status.key = be.be_status
							";		
		$set_and = false;
		if($filter1 || $filter2 || $filter3) {
			$sql .= " WHERE";
				
		}

		if ($filter1) {
			$sql .= " (accounts.first_name ILIKE '%$filter1%' OR accounts.last_name ILIKE '%$filter1%'  OR accounts.code ILIKE '%$filter1%' )";
			$set_and = true;
		}
		
		if($set_and && $filter1){
			$sql .= ' AND ';
			$set_and = FALSE;
		}
		
		if ($filter2) {
			$sql .= " (contracts.contract_code ILIKE '%$filter3%')";
			$set_and = true;
		}
		
		if($set_and && $filter2){
			$sql .= ' AND ';
			$set_and = FALSE;
		}
		
		if($filter3){
			$sql .= " be.be_status = '$filter3'";
		}
		
		$query = $this->db->query ( $sql );
		return $query->result_array ();
	}

}
