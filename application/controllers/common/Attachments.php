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
class Attachments extends Common_Controller {
	public function __construct() {
		parent::__construct ();
		// LOAD HERE REQUIRED LIBRARIES/HELPERS
		$this->load->model ( 'attachment' );
	}
	public function index() {
		$activity_type_id = 1;
		$attch_types = $this->list_types ( $activity_type_id );
		$input = array (
				'attach_type' => $attch_types,
				'action' => 'common/attachments/upload/180/77' 
		);
		
		$data ['content'] = $this->load->view ( 'common/test_attachments/upload_form', $input, true );
		$this->load->view ( 'template', $data );
	}
	private function decrypt_url($attachment_id, $thread_id, $activity_id = NULL) {
	}
	
	/*
	 * * GET ATTACHMENTS TYPES
	 * * RETURN JSON OBJECT WITH TYPE TITLE AND REQUIRED FLAG
	 * *
	 * * INTEGER $activity_type_id
	 */
	public function list_types($activity_type_id) {
		// MAKE THE QUERY ON SETUP_FORMS_ATTACHMENTS TABLE FILTERING BY ID_ACTIVITY_TYPE
		$result = $this->attachment->list_types ( $activity_type_id );
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
	public function upload($thread_id = NULL, $activity_id = NULL, $trouble_id = NULL) {
		if ($thread_id == 'null')
			$thread_id = NULL;
		if ($activity_id == 'null')
			$activity_id = NULL;
		if ($trouble_id == 'null')
			$trouble_id = NULL;
			// Get the account ID.
		$account_id = $this->attachment->get_account_id ( $thread_id, $trouble_id);
		
		// CHECK IF USER HAS RIGHT TO UPLOAD FILES TO THIS THREAD AND/OR ACTIVITY (BASED ON ROLE)
		
		// GET $_FILES DATA AND SAVE IT TO FILESYSTEM (BEFORE PROCEED CREATE A CONFIG VARIABLE INSIDE CONFIG.PHP CALLED UPLOAD_DIR AND USE THIS AS PATH IN NEXT STEPS)
		// UPLOADED FILES SHOULD BE PLACED INSIDE A SUBFOLDER WITH THIS STRUCTURE /UPLOAD_DIR/CONTRACT_CODE/THREAD_ID/ACTIVITY_ID/
		// SAVE THE FILE WITH DESCRIPTION TAKEN FROM $_POST['description'] VARIABLE INTO ATTACHMENTS TABLE
		$data_post = $this->input->post ();
		$attachment_id = $this->attachment->add_attachment ( $data_post, $thread_id, $activity_id, $trouble_id);
		if ($attachment_id && $attachment_id > 0) {
			$upload_path = $this->config->item ( 'UPLOAD_DIR' );
			$config ['upload_path'] = $upload_path . '/' . $account_id . '/' . $attachment_id;
			
			// get config from table
			if ($trouble_id) {
				$attach_config = $this->attachment->get_attach_conf ( $this->config->item ( 'trouble_attach_type' ) );
			} else if (isset ( $_POST ['attach_type'] ) && is_numeric ( $_POST ['attach_type'] )) {
				$attach_config = $this->attachment->get_attach_conf ( $_POST ['attach_type'] );
			} else {
				$attach_config = $this->attachment->get_attach_conf ( $this->config->item ( 'trouble_attach_type' ) );
			}
			
			if (isset ( $attach_config->exts )) {
				$config ['allowed_types'] = str_replace ( ' ', '', str_replace ( ',', '|', $attach_config->exts ) );
			}
			
			if (isset ( $attach_config->max_size )) {
				$config ['max_size'] = $attach_config->max_size;
			}
			
			$this->load->library ( 'upload', $config );
			if (! is_dir ( $upload_path . '/' . $account_id . '/' . $attachment_id ))
				mkdir ( $upload_path . '/' . $account_id . '/' . $attachment_id, 0777, true );
			
			if (! $this->upload->do_upload ()) {
				$this->attachment->delete_attachment_record ( $attachment_id );
				$result = array (
						'response' => false,
						'error' => strip_tags ( $this->upload->display_errors () ) 
				);
				// RETURN THE RESULT AS JSON WITH ERRORS IF ANY {respone:false, errors: 'Unable lo upload the file..'}
				$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
			} else {
				$data_post = $this->input->post ();
				$data = $this->upload->data ();
				$file_name = $data ['file_name'];
				$file_path = '/' . $account_id . '/' . $attachment_id . '/';
				$result ['result'] = $this->attachment->update_attachment ( $file_name, $file_path, $attachment_id );
				$result ['id_thread'] = $thread_id;
				$result ['id_act'] = $activity_id;
				$result ['id_trouble'] = $trouble_id;
				$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
			}
		} else {
			$result = array (
					'response' => false,
					'error' => 'Could not save the attachment into database.' 
			);
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
		}
	}
	
	/*
	 * * GET ATTACHMENTS LIST
	 * * RETURN JSON OBJECT WITH QUERY RESULT
	 * *
	 * * INTEGER $thread_id
	 * * INTEGER $activity_id (optional)
	 */
	public function list_files($thread_id = NULL, $activity_id = NULL, $trouble_id = NULL) {
		if ($thread_id == 'null')
			$thread_id = NULL;
		if ($activity_id == 'null')
			$activity_id = NULL;
		if ($trouble_id == 'null')
			$trouble_id = NULL;
			// MAKE THE QUERY ON ATTACHMENTS TABLE FILTERING BY THREAD_ID AND ACTIVITY_ID IF NOT NULL
		$result ['result'] = $this->attachment->list_files ( $thread_id, $activity_id, $trouble_id);
		// OUTPUT JSON OBJECT WITH RESULT
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
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
		if ($thread_id == 'null')
			$thread_id = NULL;
		if ($activity_id == 'null')
			$activity_id = NULL;
			// MAKE THE QUERY ON ATTACHMENTS TABLE FILTERING BY ATTACHMENT ID
		$result ['result'] = $this->attachment->delete_file ( $attachment_id, $thread_id, $activity_id);
		// OUTPUT JSON OBJECT WITH RESULT
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function download_file($attachment_id, $thread_id = NULL, $activity_id = NULL, $trouble_id = NULL) {

		$this->load->library ( 'encrypt' );
		$attachment_id = $this->encrypt->decode ( $attachment_id );

		if ($thread_id != 'null')
			$thread_id = $this->encrypt->decode ( $thread_id );
		if ($activity_id && $activity_id != 'null')
			$activity_id = $this->encrypt->decode ( $activity_id );
		if ($trouble_id != 'null')
			$trouble_id = $this->encrypt->decode ( $trouble_id );
			// Get the account ID.
		if ($thread_id && $thread_id != 'null') {
			$account_id = $this->attachment->get_account_id ( $thread_id );
		}  else {
			$account_id = $this->attachment->get_account_from_any ( $attachment_id );
		}
		
		$this->load->helper ( 'download' );
		$data = $this->attachment->get_single ( $attachment_id );
		$filename = $data->filename;
		$upload_path = $this->config->item ( 'UPLOAD_DIR' );
		$path = $upload_path . $account_id . '/' . $attachment_id . '/' . $filename;
		
		$data = file_get_contents ( $path ); // Read the file's contents
		force_download ( $filename, $data );
	}
	public function download_file_archive($attachment_id) {
		$this->load->helper ( 'download' );
		$data = $this->attachment->get_single_archive ( $attachment_id );
		$filename = $data->filename;
		$path = $data->path;
		$upload_path = $this->config->item ( 'UPLOAD_ARCHIVE_DIR' );
		$path = $upload_path . '/' . $path . '/' . $filename;
		
		$data = file_get_contents ( $path ); // Read the file's contents
		force_download ( $filename, $data );
	}
	public function download_collection($collection_id, $thread, $form_id) {
		if (isset ( $this->ion_auth->user ()->row ()->id )) {
			$data = $this->attachment->get_download_files ( $collection_id, $thread, $form_id );
			if ($data ['status']) {
				$files_to_zip = $data ['files'];
				
				// if true, good; if false, zip creation failed
				$zip_path = $this->config->item ( 'zip_path' );
				$file_path = $zip_path . $data ['filename'];
				if (file_exists ( $file_path )) {
					unlink ( $file_path );
				}
				$result = $this->create_zip ( $files_to_zip, $file_path );
				$this->load->helper ( 'download' );
				if ($result) {
					$file_content = file_get_contents ( $file_path ); // Read the file's contents
					if (file_exists ( $file_path )) {
						unlink ( $file_path );
					}
					force_download ( $data ['filename'], $file_content );
				}
			}
		}
	}
	
	/* creates a compressed zip file */
	public function create_zip($files = array(), $destination = '', $overwrite = true) {
		// if the zip file already exists and overwrite is false, return false
		if (file_exists ( $destination ) && ! $overwrite) {
			return false;
		}
		// vars
		$valid_files = array ();
		// if files were passed in...
		if (is_array ( $files )) {
			// cycle through each file
			foreach ( $files as $file ) {
				// make sure the file exists
				if (file_exists ( $file )) {
					$valid_files [] = $file;
				}
			}
		}
		// if we have good files...
		if (count ( $valid_files )) {
			// create the archive
			$zip = new ZipArchive ();
			if ($zip->open ( $destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE ) !== true) {
				return false;
			}
			// add the files
			foreach ( $valid_files as $file ) {
				$name_array = explode ( '/', $file );
				$count = count ( $name_array );
				$zip->addFile ( $file, $name_array [$count - 1] );
			}
			
			// debug
			// echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
			
			// close the zip -- done!
			$zip->close ();
			// check to make sure the file exists
			return file_exists ( $destination );
		} else {
			return false;
		}
	}
}