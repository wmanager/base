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
}
