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
class Email_template extends CI_Model {
	/**
	 * get_email_template
	 * function to get the email template details from the email_template table
	 *
	 * @param $email_type email_type
	 *        	string
	 * @return integer
	 */
	public function get_email_template($email_type) {
		$query = $this->db->select ( 'email_template.*' )->where ( 'email_template.email_type', $email_type )->get ( 'email_template' );
		return $query->row_array ();
	}
	
	/**
	 * get_order_detail
	 * function to get customer order details
	 *
	 * @param $email_type email_type
	 *        	string
	 * @return integer
	 */
	public function get_order_detail($order_id) {
		$query = $this->db->select ( "loans.*,accounts.p_nome,accounts.p_cognome,accounts.o_ragione_sociale,accounts.p_cf,accounts.o_piva" )->join ( "accounts", "accounts.id = loans.customer_id", "left" )->where ( "loans.order_id", $order_id )->get ( "loans" );
		return $query->row_array ();
	}
	
	/**
	 * get_email_queue
	 * function to get email queue details
	 *
	 * @param $to to
	 *        	string
	 * @return integer
	 */
	public function get_email_queue($to) {
		$query = $this->db->select ( "MAX(id) as id" )->where ( "to = '$to'" )->limit ( 1 )->get ( "email_queue" );
		return $query->row ()->id;
	}
	
	/**
	 * get_email_queue
	 * function to get email queue details
	 *
	 * @param $email_type email_type
	 *        	string
	 * @return integer
	 */
	public function email_queue_attachment($data) {
		$id = $this->db->insert ( "email_queue_attachments", $data );
	}
	
	/**
	 * get_email_queue
	 * function to get email queue details
	 *
	 * @param $type_ids email_type
	 *        	array
	 * @return integer
	 */
	public function get_template_data($type_ids) {
		$query = $this->db->select ( "orders.*,be.be_code, loans.loans_type,accounts.p_nome,accounts.p_cognome,accounts.o_ragione_sociale,accounts.p_cf,accounts.o_piva" );
		$query = $this->db->join ( "accounts", "accounts.id = orders.customer_id", "left" );
		$query = $this->db->join ( "loans", "loans.order_id = orders.id", "left" );
		$query = $this->db->join ( "be", "be.id = orders.be_id", "left" );
		if (count ( $type_ids ) > 0) {
			foreach ( $type_ids as $key => $id ) {
				$query = $this->db->where ( "orders.$key", $id );
			}
		}
		$query = $this->db->order_by ( "id", "DESC" );
		$query = $this->db->get ( "orders" );
		
		return $query->result_array ();
	}
	
	/**
	 * get_required_attachment
	 * function to get email attachment details
	 *
	 * @param $type_ids email_type
	 *        	array
	 * @return integer
	 */
	public function get_required_attachment($type_ids) {
		$loan = NULL;
		$company_id = '';
		$act_type = '';
		$attachments = '';
		$attachments_list = array ();
		// get_order_details
		if (isset ( $type_ids ['order_id'] )) {
			$get_loan = $this->db->where ( "order_id", $type_ids ['order_id'] )->get ( "loans" );
			$loan = $get_loan->row ();
		}
		
		if (isset ( $type_ids ['activity_id'] )) {
			// get activity type
			$activity_details = $this->db->select ( "*" )->where ( "id", $type_ids ['activity_id'] )->get ( "activities" );
			$act_type = $activity_details->row ()->type;
		}
		
		// loan company_id
		if (count ( $loan ) > 0) {
			$company_id = $loan->loan_company_id;
		}
		if ($company_id == '') {
			$company_id = 1;
		}
		
		$get = $this->db->select ( "*" )->where ( "id", $company_id )->get ( "list_loans_companies" );
		$company_details = $get->row ();
		if (count ( $company_details ) > 0) {
			if (is_numeric ( strpos ( $act_type, 'RETTIFICA_FINANZIAMENTO' ) )) {
				if ($company_details->rettifiche_attachment_id != '') {
					$attachments = explode ( ",", $company_details->rettifiche_attachment_id );
				}
			} else {
				if ($company_details->attachment_id != '') {
					$attachments = explode ( ",", $company_details->attachment_id );
				}
			}
		} else {
			return $attachments_list;
		}
		
		if (count ( $attachments ) > 0 && $attachments [0] != '') {
			// get attachment details
			if (isset ( $type_ids ['thread_id'] ))
				$query = $this->db->select ( "*" )->where ( "thread_id", $type_ids ['thread_id'] )->where_in ( "attach_type", $attachments )->get ( "attachments" );
			$attachments_list = $query->result_array ();
			;
		} else {
			$attachments_list = array ();
		}
		
		return $attachments_list;
	}
	public function save_queue_details($email_queue_id, $data_array) {
		if ($email_queue_id != '' && count ( $data_array ) > 0) {
			$update = $this->db->where ( "id", $email_queue_id )->update ( "email_queue", $data_array );
			return true;
		} else {
			return false;
		}
	}
	public function check_already_sent($type = NULL, $queue) {
		if($queue == NULL && $type = NULL) {
			return "NO";
		}
		if ((isset ( $queue ['memo_id'] )) && (! empty ( $queue ['memo_id'] ))) {
			return "NO";
		}
		
		if (isset ( $queue ['account_id'] )) {
			$this->db->where ( "account_id", $queue ['account_id'] );
		}
		
		if (isset ( $queue ['activity_id'] )) {
			$this->db->where ( "activity_id", $queue ['activity_id'] );
		}
		
		if (isset ( $queue ['thread_id'] )) {
			$this->db->where ( "thread_id", $queue ['thread_id'] );
		}
		
		if ($type != NULL) {
			$this->db->where ( "email_type = '$type'" );
		}
		
		$query = $this->db->get ( "email_queue" );
		
		$result = $query->result ();
		
		if (count ( $result ) > 0) {
			return "YES";
		} else {
			return "NO";
		}
	}
	public function get_template_final_response_data($order_id) {
		$get_loan = $this->db->select ( "loans.*,be.be_code,accounts.id as customer_id,accounts.p_nome,accounts.p_cognome,accounts.o_ragione_sociale,accounts.p_cf,accounts.o_piva" )->join ( "accounts", "accounts.id = loans.customer_id", "left" )->join ( "be", "be.id = loans.be_id", "left" )->where ( "loans.order_id", $order_id )->get ( "loans" );
		return $get_loan->row_array ();
	}
	public function get_legal_notif_template_data($date) {
		$query = $this->db->select ( 'legal_cases.id,
    								legal_cases.type,
									legal_cases.subtype,
									legal_cases.fase,
									legal_cases.customer,
    								legal_cases.be,
    								list_legal_status.label as status_key,
    								accounts.p_nome,
									accounts.p_cognome,
									accounts.o_ragione_sociale,
    								memos.id as memo_id,
    								memos.title,
    								memos.start_day,
    								memos.start_time,
    								memos.description' )->join ( 'be', 'be.id = legal_cases.be', 'left' )->join ( 'accounts', 'accounts.id = legal_cases.customer' )->join ( 'users', 'users.id = legal_cases.created_by', 'left' )->join ( 'list_legal_status', 'legal_cases.status = list_legal_status.key', 'left' )->join ( 'memos', 'memos.legal_id = legal_cases.id', 'left' )->where ( 'memos.notification_date', "$date" )->where ( 'memos.legal_id IS NOT NULL' )->order_by ( 'memos.id', 'DESC' )->get ( 'legal_cases' );
		$result = $query->result_array ();
		return $result;
	}
}
