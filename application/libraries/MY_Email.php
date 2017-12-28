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

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class MY_Email extends CI_Email {
	// DB table
	private $table_email_queue = 'email_queue';
	
	// Main controller
	private $main_controller = 'sys/queue_email/send_pending_emails';
	
	// PHP Nohup command line
	private $phpcli = 'nohup php';
	private $expiration = NULL;
	
	// Status (pending, sending, sent, failed)
	private $status;
	
	/**
	 * Constructor
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
		
		log_message ( 'debug', 'Email Queue Class Initialized' );
		
		$this->expiration = 60 * 5;
		$this->CI = & get_instance ();
		
		$this->CI->load->database ( 'default' );
	}
	public function set_status($status) {
		$this->status = $status;
		return $this;
	}
	
	/**
	 * Get
	 *
	 * Get queue emails.
	 * 
	 * @return mixed
	 */
	public function get($limit = NULL, $offset = NULL) {
		if ($this->status != FALSE)
			$this->CI->db->where ( 'q.status', $this->status );
		
		$query = $this->CI->db->get ( "{$this->table_email_queue} q", $limit, $offset );
		
		return $query->result ();
	}
	
	/**
	 * Save
	 *
	 * Add queue email to database.
	 * 
	 * @return mixed
	 */
	public function send($skip_job = FALSE) {
		if ($skip_job === TRUE) {
			return parent::send ();
		}
		
		$date = date ( "Y-m-d H:i:s" );
		
		$to = is_array ( $this->_recipients ) ? implode ( ", ", $this->_recipients ) : $this->_recipients;
		$cc = implode ( ", ", $this->_cc_array );
		$bcc = implode ( ", ", $this->_bcc_array );
		
		$dbdata = array (
				'to' => $to,
				'cc' => $cc,
				'bcc' => $bcc,
				'message' => $this->_body,
				'headers' => serialize ( $this->_headers ),
				'status' => 'pending',
				'date' => $date 
		);
		$this->CI->db->insert ( $this->table_email_queue, $dbdata );
		return $this->CI->db->insert_id ();
	}
	
	/**
	 * Start process
	 *
	 * Start php process to send emails
	 * 
	 * @return mixed
	 */
	public function start_process() {
		$filename = FCPATH . 'index.php';
		$exec = shell_exec ( "{$this->phpcli} {$filename} {$this->main_controller} > /dev/null &" );
		
		return $exec;
	}
	
	/**
	 * Send queue
	 *
	 * Send queue emails.
	 * 
	 * @return void
	 */
	public function send_queue() {
		$this->set_status ( 'pending' );
		$emails = $this->get ();
		
		$this->CI->db->where ( 'status', 'pending' );
		$this->CI->db->set ( 'status', 'sending' );
		$this->CI->db->set ( 'date', date ( "Y-m-d H:i:s" ) );
		$this->CI->db->update ( $this->table_email_queue );
		
		foreach ( $emails as $email ) {
			$recipients = explode ( ", ", $email->to );
			
			$cc = ! empty ( $email->cc ) ? explode ( ", ", $email->cc ) : array ();
			$bcc = ! empty ( $email->bcc ) ? explode ( ", ", $email->bcc ) : array ();
			
			$this->_headers = unserialize ( $email->headers );
			
			if (ENVIRONMENT == 'production') {
				$this->to ( $recipients );
				$this->cc ( $cc );
				$this->bcc ( $bcc );
			} else {
				$recipients = $this->CI->config->item ( 'test_email_id' );
				$this->to ( $recipients );
				$this->cc ( array () );
				$this->bcc ( array () );
			}
			$this->message ( $email->message );
			
			// add attachechment
			$files = $this->add_attachment ( $email->id );
			
			if ($files != FALSE) {
				$rename_array = array ();
				foreach ( $files as $file ) {
					$dir = $this->CI->config->item ( 'UPLOAD_DIR' );
					$path_array = explode ( "/", $file->url );
					
					// check rename required
					if ((isset ( $file->attach_rename )) && (! empty ( $file->attach_rename ))) {
						// rename module
						$file_temp_folder = $dir . $path_array [1] . "/" . $path_array [2] . "/email_tmp";
						if (! is_dir ( $file_temp_folder )) {
							mkdir ( $file_temp_folder, 0777 );
						}
						
						// file extension
						$extension = substr ( $file->filename, strpos ( $file->filename, "." ) + 1 );
						$copy_file = $file_temp_folder . "/" . $file->filename;
						$renamed_file = $file_temp_folder . "/" . $file->attach_rename . "." . $extension;
						if (copy ( $loan_file, $copy_file )) {
							rename ( $copy_file, $renamed_file );
							$rename_array [] = $file_temp_folder;
							$loan_file = $renamed_file;
						}
					}				
				}
			}
			
			if ($this->send ( TRUE )) {
				$status = 'sent';
				
				// delete the files renamed
				if (count ( $rename_array ) > 0) {
					foreach ( $rename_array as $renamed_dir ) {
						$this->remove_tmp_files ( $renamed_dir );
					}
				}
			} else {
				$status = 'failed';
				log_message ( 'DEBUG', $this->print_debugger () );
			}
			
			$this->CI->db->where ( 'id', $email->id );
			
			$this->CI->db->set ( 'status', $status );
			$this->CI->db->set ( 'date', date ( "Y-m-d H:i:s" ) );
			$this->CI->db->update ( $this->table_email_queue );
		}
	}
	
	/**
	 * Retry failed emails
	 *
	 * Resend failed or expired emails
	 * 
	 * @return void
	 */
	public function retry_queue() {
		$expire = (time () - $this->expiration);
		$date_expire = date ( "Y-m-d H:i:s", $expire );
		
		$this->CI->db->set ( 'status', 'pending' );
		// $this->CI->db->where("(date < '{$date_expire}' AND status = 'sending')");
		$this->CI->db->or_where ( "status = 'failed'" );
		
		$this->CI->db->update ( $this->table_email_queue );
		
		$this->status ( 'pending' );
		$this->send ();
		
		log_message ( 'debug', 'Email queue retrying...' );
	}
	public function add_attachment($email_id = NULL) {
		$CI = & get_instance ();
		
		if ($email_id == NULL) {
			return false;
		}
		
		$get_files = $CI->db->select ( "attachments.*,setup_attachments.attach_rename" )->join ( "attachments", "attachments.id = email_queue_attachments.attachment_id", "left" )->join ( "setup_attachments", "setup_attachments.id = attachments.attach_type", "left" )->where ( "email_id", $email_id )->get ( "email_queue_attachments" );
		$files = $get_files->result ();
		
		return $files;
	}
	public function remove_tmp_files($dir_name = NULL) {
		if ($dir_name == NULL) {
			return false;
		}
		
		array_map ( 'unlink', glob ( "$dir_name/*.*" ) );
		if (is_dir ( $dirname )) {
			rmdir ( $dirname );
		}
		
		return true;
	}
}