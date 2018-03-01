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
class Attachment extends CI_Model {
	public function list_types($form_id) {
		$query = $this->db->select ( 'at.id,at.title,fa.required, fa.conditions' )->join ( 'setup_forms_attachments fa', 'fa.id_attachment = at.id' )->where ( 'fa.form_id', $form_id )->where ( 'fa.use', 'UPLOAD' )->order_by ( "at.title", "asc" )->get ( 'setup_attachments at' );
		
		return $query->result ();
	}
	public function add_attachment($data_post, $thread_id = NULL, $activity_id = NULL, $trouble_id = NULL) {
		$data_attachments ['thread_id'] = $thread_id;
		$data_attachments ['activity_id'] = $activity_id;
		$data_attachments ['trouble_id'] = $trouble_id;

		$data_attachments ['description'] = $data_post ['description'];
		if ($trouble_id)
			$data_post ['attach_type'] = $this->config->item ( 'trouble_attach_type' );			
		$data_attachments ['attach_type'] = $data_post ['attach_type'];
		$data_attachments ['created_by'] = $this->ion_auth->user ()->row ()->id;

		if ($this->db->insert ( 'attachments', $data_attachments )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' Record has been inserted correctly.' );
			$insert_id = $this->db->insert_id ();
			return $insert_id;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', ' There was an error, please try again.' );
			return false;
		}
	}
	public function list_files($thread_id = NULL, $activity_id = NULL, $trouble_id = NULL) {		
		if ($thread_id) {
			$this->db->where ( 'thread_id', $thread_id );
		}
		
		if ($activity_id) {
			$this->db->where ( 'activity_id', $activity_id );
		}
		
		if ($trouble_id) {
			$this->db->where ( 'trouble_id', $trouble_id );
		}

		
		$query = $this->db->select ( 'attachments.*,
					setup_attachments.title as attachment_type, 
					users.first_name, users.last_name' )
			->join ( 'setup_attachments', 'setup_attachments.id = attachments.attach_type' )
			->join ( 'users', 'users.id = attachments.created_by' )
			->get ( 'attachments' );
		
		$result = $query->result ();
		
		$i = 0;
		foreach ( $result as $item ) {
			$link = '/common/attachments/download_file/' . crypt_params ( $item->id ) . '/' . crypt_params ( $item->thread_id ) . '/' . crypt_params ( $item->activity_id ) . '/' . crypt_params ( $item->trouble_id );
			$result [$i]->link = $link;
			$i ++;
		}		
		return $result;
	}
	public function get_single($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'attachments' );
		return $query->row ();
	}
	public function get_single_archive($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'attachments_archive' );
		return $query->row ();
	}
	public function delete_file($attachment_id, $thread_id, $activity_id = NULL) {
		$data = $this->get_single ( $attachment_id );
		if (count ( $data ) > 0) {
			$res = $this->delete_attachment ( $thread_id, $attachment_id, $data->filename);
			if ($res) {
				if ($this->db->where ( 'id', $attachment_id )->delete ( 'attachments' )) {
					log_message ( 'DEBUG', $this->db->last_query () );
					$this->session->set_flashdata ( 'growl_success', ' Deleted Sucessfully' );
					return true;
				} else {
					log_message ( 'ERROR', $this->db->last_query () );
					$this->session->set_flashdata ( 'growl_error', 'Error' );
					return false;
				}
			}
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'Non hai permessi sufficienti per rimuovere questo record.' );
			return false;
		}
	}
	public function delete_attachment($thread_id, $attachment_id, $filename) {
		$account_id = $this->get_account_id ( $thread_id, NULL);
		$upload_path = $this->config->item ( 'UPLOAD_DIR' );
		$path = $upload_path . '/' . $account_id . '/' . $attachment_id . '/' . $filename;
		
		unlink ( $path );
		
		if ($this->is_dir_empty ( $path )) {
			rmdir ( $path );
		}
		if ($this->is_dir_empty ( $upload_path . '/' . $account_id . '/' . $attachment_id )) {
			rmdir ( $upload_path . '/' . $account_id . '/' . $attachment_id );
		}
		if ($this->is_dir_empty ( $upload_path . '/' . $account_id )) {
			rmdir ( $upload_path . '/' . $account_id );
		}
		
		return $this->db->where ( 'id', $attachment_id )->delete ( 'attachments' );
		
		// return true;
	}
	function is_dir_empty($dir) {
		if (! is_readable ( $dir ))
			return NULL;
		$handle = opendir ( $dir );
		while ( false !== ($entry = readdir ( $handle )) ) {
			if ($entry != "." && $entry != "..") {
				return FALSE;
			}
		}
		return TRUE;
	}
	function get_attach_conf($attach_id) {
		$query = $this->db->select ( 'exts,max_size' )->where ( 'id', $attach_id )->get ( 'setup_attachments' );
		return $query->row ();
	}
	function get_account_id($thread_id = NULL, $trouble_id = NULL) {
		if ($thread_id != NULL) {
			$query = $this->db->select ( 'customer as account_id' )->where ( 'id', $thread_id )->get ( 'threads' );
		} else if ($trouble_id != NULL) {
			$query = $this->db->select ( 'customer_id as account_id' )->where ( 'id', $trouble_id )->get ( 'troubles' );
		}
		return $query->row ()->account_id;
	}
	function update_attachment($file_name, $file_path, $attachment_id) {
		$data_attachments ['filename'] = $file_name;
		$encode_name = base64_encode ( $file_name );
		$data_attachments ['url'] = $file_path . $encode_name;
		if ($this->db->where ( 'id', $attachment_id )->update ( 'attachments', $data_attachments )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' Record has been inserted correctly.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
			return false;
		}
	}
	function delete_attachment_record($attachment_id) {
		if ($this->db->where ( 'id', $attachment_id )->delete ( 'attachments' )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' Deleted Sucessfully' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'Error' );
			return false;
		}
	}
	function get_download_files($collection_id, $thread, $form_id) {
		
		// Get the account ID.
		$account_id = $this->get_account_id ( $thread );
		
		$query = $this->db->select ( 'setup_collections_files.id_attachment,setup_collections_list.title' )->join ( 'setup_collections_files', 'setup_collections_files.id_plico = setup_collections_list.id' )->where ( 'setup_collections_list.id', $collection_id )->get ( 'setup_collections_list' );
		$list = $query->row ();
		$upload_path = $this->config->item ( 'UPLOAD_DIR' );
		$data = array ();
		$data ['status'] = false;
		if ($list->id_attachment != '') {
			$attach_query = $this->db->query ( "SELECT att.*,setup_attachments.title as attachment_type,users.first_name, users.last_name FROM attachments AS att JOIN setup_attachments ON setup_attachments.id = att.attach_type JOIN users ON users.id = att.created_by WHERE attach_type IN ($list->id_attachment) AND thread_id = $thread " );
			$attachs = $attach_query->result ();
			
			if ($attach_query->num_rows () > 0) {
				$zip_files = array ();
				foreach ( $attachs as $attach ) {
					$path = $upload_path . '/' . $account_id . '/' . $attach->id . '/' . $attach->filename;
					$zip_files [] = $path;
				}
				$data ['status'] = true;
				$data ['files'] = $zip_files;
				$data ['filename'] = $list->title . '.zip';
			}
		}
		return $data;
	}
	public function get_global_account_id($type, $id) {
		$query = $this->db->select ( "customer_id" )->where ( "id", $id )->get ( $type );
		return $query->row ()->customer_id;
	}
	public function get_account_from_any($attachment_id = NULL) {
		if ($attachment_id == NULL) {
			return false;
		}
		
		$get_values = $this->db->where ( "id", $attachment_id )->get ( "attachments" );
		$values = $get_values->row ();
		
		// this is to get master account id
		if ($value->thread_id) {
			$query = $this->db->where ( "id", $values->thread_id )->get ( "threads" );
			$result = $query->row ();
			return $result->customer;
		} else if ($value->activity_id) {
			$query = $this->db->select ( "threads.customer" )->join ( "threads", "threads.id = activities.id_thread" )->where ( "activities.id", $values->activity_id )->get ( "activities" );
			$result = $query->row ();
			return $result->customer;
		} else if ($values->trouble_id) {
			$query = $this->db->where ( "id", $values->trouble_id )->get ( "troubles" );
			$result = $query->row ();
			return $result->customer_id;
		}  else {
			return false;
		}
	}

}
