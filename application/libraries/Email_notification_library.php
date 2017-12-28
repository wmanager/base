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
class email_notification_library {
	var $CI;
	public function __construct() {
		$this->CI = & get_instance ();
		log_message ( 'debug', "send email function Initialized" );
	}
	
	/**
	 * send
	 * Library to send the email
	 *
	 * @param $email_type email_type
	 *        	string
	 * @param $to to
	 *        	string
	 * @param $cc cc
	 *        	string
	 * @param $bcc bcc
	 *        	string
	 *        	
	 * @return integer
	 */
	public function send_email($email_type, $to, $cc = array(), $bcc = array(), $extra_mappings = NULL, $attachments = array()) {
		
		// Initializations
		$message = NULL;
		$path = NULL;
		$subject = NULL;
		$data = array ();
		$client_name = NULL;
		$CI = & get_instance ();
		
		// Loaders
		$CI->load->library ( 'email' );
		$CI->load->model ( 'email_template' );
		
		// template values
		$CI->data ['data'] = $extra_mappings ['template_values'];
		$queue_values = $extra_mappings ['queue_values'];
		
		// validations
		if (! $email_type) {
			$return = array (
					"status" => false,
					"message" => "Please enter email type" 
			);
			
			return $return;
		} else if ($to == null) {
			$return = array (
					"status" => false,
					"message" => "Please provide a TO address" 
			);
			
			return $return;
		}
		
		// GET EMAIL TEMPLATE
		$email_template_array = $this->get_email_template ( $email_type );
		$path = $email_template_array ['path'];
		
		// setting FROM
		$CI->email->from ( $CI->config->item ( 'email_from' ), $CI->config->item ( 'email_from_name' ) );
		
		// setting TO
		$CI->email->to ( $to );
		
		// setting CC
		if (count ( $cc ) > 0) {
			$CI->email->cc ( $cc );
		}
		
		// setting BCC
		if (count ( $bcc ) > 0) {
			$CI->email->bcc ( $bcc );
		}
		
		// setting subject
		if (isset ( $extra_mappings ['subject'] ) && ! empty ( $extra_mappings ['subject'] )) {
			$subject = $extra_mappings ['subject'];
		} else {
			$subject = $email_template_array ['subject'];
		}
		$CI->email->subject ( $subject );
		
		// Setting messsage
		$CI->email->message ( $CI->load->view ( "$path", $CI->data, true ) );
		
		// check weather already sent
		$check = $this->check_already_sent ( $email_type, $queue_values );
		
		if ($check == 'NO') {
			// SEND EMAIL -->returns queue ID
			$email_queue_id = $CI->email->send ();
		} else {
			$return = array (
					"status" => false,
					"message" => "Already Sent ONCE" 
			);
			
			return $return;
		}
		
		if (is_numeric ( $email_queue_id )) {
			// ADD queue details
			$CI->email_template->save_queue_details ( $email_queue_id, $queue_values );
			
			// ADD Attachments
			if (count ( $attachments ) > 0) {
				foreach ( $attachments as $item ) {
					
					$insert_attachemnt = array (
							"email_id" => $email_queue_id,
							"attachment_id" => $item ['id'] 
					);
					
					$email_template_array = $CI->email_template->email_queue_attachment ( $insert_attachemnt );
				}
			}
			
			$return = array (
					"status" => true,
					"message" => "Notified Successfully" 
			);
			
			return $return;
		} else {
			log_message ( 'ERROR', $CI->email->print_debugger () );
			
			$return = array (
					"status" => false,
					"message" => "Failed to SEND" 
			);
			
			return $return;
		}
	}
	
	/**
	 * email_templete
	 * Library get the email template
	 *
	 * @param $email_type email_type
	 *        	string
	 *        	
	 * @return array
	 */
	public function get_email_template($email_type) {
		$CI = & get_instance ();
		$path = NULL;
		$subject = NULL;
		$email_array = array ();
		if (! $email_type) {
			echo 'Email type is empty';
		} else {
			$email_template_array = $CI->email_template->get_email_template ( $email_type );
			if (count ( $email_template_array ) > 0) {
				if ((isset ( $email_template_array )) && (! empty ( $email_template_array ))) {
					$path = $email_template_array ['template_url'];
					$subject = $email_template_array ['subject'];
				}
			}
		}
		$email_array ['path'] = $path;
		$email_array ['subject'] = $subject;
		return $email_array;
	}
	
	/**
	 * template_data
	 * Library get the email template data
	 *
	 * @param $email_type email_type
	 *        	string
	 * @param $type_ids type_ids
	 *        	array
	 *        	
	 * @return array
	 */
	public function template_data($email_type, $type_ids) {
		$CI = & get_instance ();
		$CI->load->model ( 'email_template' );
		$email_array = array ();
		switch ($email_type) {
			case "LEGAL_NOTIFICATION" :
				$email_array = $CI->email_template->get_legal_notif_template_data ( $type_ids );
				break;
		}
		
		return $email_array;
	}
	public function check_already_sent($email_type, $queue_details) {
		$CI = & get_instance ();
		$CI->load->model ( 'email_template' );
		
		return $CI->email_template->check_already_sent ( $email_type, $queue_details );
	}
}
?>