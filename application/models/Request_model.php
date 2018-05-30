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
class Request_model extends CI_Model {
	
	public function get($limit, $offset = 0) {
	
		if($this->input->post('type')!='' && $this->input->post('type')!='-') {
			$this->session->set_userdata('filter_request_type',$this->input->post('type'));
		} else if($this->input->post('type') == '-'){
			$this->session->unset_userdata('filter_request_type');
		}
	
		if($this->input->post('client')!='') {
			$this->session->set_userdata('filter_request_client',$this->input->post('client'));
		} else if(isset($_POST['client']) && $_POST['client'] == ''){
			$this->session->unset_userdata('filter_request_client');
		}
	
		if($this->input->post('status') && $this->input->post('status')!='-'){
			$this->session->set_userdata('filter_request_status',$this->input->post('status'));
		}else if(!isset($_POST['status']) && $this->session->userdata('filter_request_status')==''){
			$this->session->set_userdata('filter_request_status','OPEN');
		}
	
		$filter1 = $this->session->userdata('filter_request_type');
		$filter2 = trim($this->session->userdata('filter_request_client'));
		$filter3 = $this->session->userdata('filter_request_status');
	
	
		if($filter1){
			$this->db->where("customer_requests_base.type",$filter1);
		}
	
		if($filter2){
			$filter2 = $this->db->escape_like_str($filter2);
			$this->db->where("(accounts.p_cognome ILIKE '%$filter2%' OR accounts.p_nome ILIKE '$filter2%' OR accounts.o_ragione_sociale ILIKE '%$filter2%')");
		}
	
		if($filter3 == 'CLOSED'){
			$this->db->where("(customer_requests_base.status = 'CLOSED')");
		}else if($filter3 == 'OPEN'){
			$this->db->where("(customer_requests_base.status != 'CLOSED' AND customer_requests_base.status != 'CANCELLED')");
		}else if($filter3 == 'CANCELLED'){
			$this->db->where("(customer_requests_base.status = 'CANCELLED')");
		}
	
		$query = $this->db->distinct("customer_requests_base.reqid")->select("customer_requests_base.*,accounts.po,accounts.p_nome,accounts.p_cognome,accounts.o_ragione_sociale,forniture.pod")
					->join("accounts","accounts.id = customer_requests_base.client_id","left")
					->join("forniture","forniture.be_id = customer_requests_base.be","left")
					->limit($limit,$offset)
					->order_by("id","desc")
					->get("customer_requests_base");
					$result = $query->result();
	
		if($query->num_rows() > 0){
			foreach ($result as $item){
				$get_details = $this->db->select("customer_requests_related_threads.*")
				->where("reqid",$item->reqid)
				->get('customer_requests_related_threads');
					
				$item->details = $get_details->result();
			}
		}
	
		return $result;
	
	
	}
}	