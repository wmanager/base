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
class Attachments {
	var $CI;
	public function __construct() {
		$this->CI = & get_instance ();
		$this->CI->load->model ( 'attachment' );
		log_message ( 'debug', "Actions Class Initialized" );
	}
	
	/*
	 * * GET ATTACHMENTS TYPES
	 * * RETURN JSON OBJECT WITH TYPE TITLE AND REQUIRED FLAG
	 * *
	 * * INTEGER $activity_type_id
	 */
	public function list_types($form_id) {
		// MAKE THE QUERY ON SETUP_FORMS_ATTACHMENTS TABLE FILTERING BY ID_ACTIVITY_TYPE
		$result = $this->CI->attachment->list_types ( $form_id );
		// OUTPUT JSON OBJECT WITH RESULT
		// $this->output->set_content_type('application/json')->set_output(json_encode($result));
		return $result;
	}
	
	/*
	 * * UPLOAD SINGLE FILE USIGN CODEIGNITER UPLOAD LIBRARIES
	 * * RETURN JSON OBJECT WITH UPLOAD RESULT AND ERRORS
	 * *
	 * * STRING $contract_code
	 * * INTEGER $thread_id
	 * * INTEGER $activity_id (optional)
	 */
	public function upload($thread_id, $activity_id = NULL) {
		// Get the account ID.
		$account_id = $this->CI->attachment->get_account_id ( $thread_id );
		
		// CHECK IF USER HAS RIGHT TO UPLOAD FILES TO THIS THREAD AND/OR ACTIVITY (BASED ON ROLE)
		
		// GET $_FILES DATA AND SAVE IT TO FILESYSTEM (BEFORE PROCEED CREATE A CONFIG VARIABLE INSIDE CONFIG.PHP CALLED UPLOAD_DIR AND USE THIS AS PATH IN NEXT STEPS)
		// UPLOADED FILES SHOULD BE PLACED INSIDE A SUBFOLDER WITH THIS STRUCTURE /UPLOAD_DIR/CONTRACT_CODE/THREAD_ID/ACTIVITY_ID/
		// SAVE THE FILE WITH DESCRIPTION TAKEN FROM $_POST['description'] VARIABLE INTO ATTACHMENTS TABLE
		
		$data_post = $this->CI->input->post ();
		$attachment_id = $this->CI->attachment->add_attachment ( $data_post, $thread_id, $activity_id );
		
		if ($attachment_id && $attachment_id > 0) {
			$upload_path = $this->CI->config->item ( 'UPLOAD_DIR' );
			$config ['upload_path'] = $upload_path . '/' . $account_id . '/' . $attachment_id;
			$attach_config = $this->CI->attachment->get_attach_conf ( $this->CI->input->post ( 'attach_type' ) );
			if (isset ( $attach_config->exts )) {
				$config ['allowed_types'] = str_replace ( ' ', '', str_replace ( ',', '|', $attach_config->exts ) );
			}
			if (isset ( $attach_config->max_size )) {
				$config ['max_size'] = $attach_config->max_size;
			}
			
			$this->CI->load->library ( 'upload', $config );
			if (! is_dir ( $upload_path . '/' . $account_id . '/' . $attachment_id ))
				mkdir ( $upload_path . '/' . $account_id . '/' . $attachment_id, 0777, true );
			
			if (! $this->CI->upload->do_upload ()) {
				$this->CI->attachment->delete_attachment_record ( $attachment_id );
				$result = array (
						'response' => false,
						'error' => $this->CI->upload->display_errors () 
				);
				return $result;
			} else {
				$data_post = $this->CI->input->post ();
				$data = $this->CI->upload->data ();
				$file_name = $data ['file_name'];
				$file_path = '/' . $account_id . '/' . $attachment_id . '/';
				$result ['result'] = $this->CI->attachment->update_attachment ( $file_name, $file_path, $attachment_id );
				return $result;
			}
		} else {
			$result = array (
					'response' => false,
					'error' => 'Could not save the attachment into database.' 
			);
			return $result;
		}
	}
	
	/*
	 * * GET ATTACHMENTS LIST
	 * * RETURN JSON OBJECT WITH QUERY RESULT
	 * *
	 * * INTEGER $thread_id
	 * * INTEGER $activity_id (optional)
	 */
	public function list_files($thread_id, $activity_id = NULL) {
		// MAKE THE QUERY ON ATTACHMENTS TABLE FILTERING BY THREAD_ID AND ACTIVITY_ID IF NOT NULL
		$result = $this->CI->attachment->list_files ( $thread_id, $activity_id );
		// OUTPUT JSON OBJECT WITH RESULT
		// $this->CI->output->set_content_type('application/json')->set_output(json_encode($result));
		return $result;
	}
	
	/*
	 * * DELETE FILE
	 * * RETURN JSON RESULT
	 * *
	 * * INTEGER $attachment_id
	 */
	public function delete_file($attachment_id, $thread_id, $activity_id = NULL) {
		/*
		 * $contract_code = 2;
		 * $thread_id = 3;
		 * $activity_id = 1;
		 */
		
		// MAKE THE QUERY ON ATTACHMENTS TABLE FILTERING BY ATTACHMENT ID
		$result = $this->CI->attachment->delete_file ( $attachment_id, $thread_id, $activity_id );
		// OUTPUT JSON OBJECT WITH RESULT
		return $result;
	}
	public function download_file($attachment_id, $thread_id, $activity_id = NULL) {
		// Get the account ID.
		$account_id = $this->CI->attachment->get_account_id ( $thread_id );
		
		$this->CI->load->helper ( 'download' );
		$data = $this->CI->attachment->get_single ( $attachment_id );
		$filename = $data->filename;
		$upload_path = $this->CI->config->item ( 'UPLOAD_DIR' );
		$path = $upload_path . '/' . $account_id . '/' . $attachment_id . '/' . $filename;
		
		$data = file_get_contents ( $path ); // Read the file's contents
		force_download ( $filename, $data );
	}
	public function download_archive_file($attachment_id) {
		$this->CI->load->helper ( 'download' );
		$data = $this->CI->attachment->get_single_archive ( $attachment_id );
		$filename = $data->filename;
		$path = $data->path;
		$upload_path = $this->CI->config->item ( 'UPLOAD_ARCHIVE_DIR' );
		$path = $upload_path . '/' . $path . '/' . $filename;
		$data = file_get_contents ( $path ); // Read the file's contents
		force_download ( $filename, $data );
	}
}