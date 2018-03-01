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
class Setup_forms extends CI_Model {
	public function get($limit, $offset = 0) {
		$this->session->set_userdata ( 'filter_setup_form', $this->input->post ( 'filter_setup_form' ) );
		
		$filter = $this->session->userdata ( 'filter_setup_form' );
		
		if ($filter) {
			$filter = $this->db->escape_like_str ( $filter );
			$this->db->where ( "(setup_forms.title ILIKE '%$filter%')" );
		}
		
		$query = $this->db->distinct ( 'id' )->where ( 'disabled', 'f' )->limit ( $limit, $offset )->order_by ( 'id', 'desc' )->get ( 'setup_forms' );
		return $query->result ();
	}
	public function total() {
		$this->session->set_userdata ( 'filter_setup_form', $this->input->post ( 'filter_setup_form' ) );
		
		$filter = $this->session->userdata ( 'filter_setup_form' );
		
		if ($filter) {
			$filter = $this->db->escape_like_str ( $filter );
			$this->db->where ( "(setup_forms.title ILIKE '%$filter%')" );
		}
		
		$query = $this->db->where ( 'disabled', 'f' )->get ( 'setup_forms' );
		return $query->num_rows ();
	}
	public function add() {
		$form_data = $this->input->post ();
		$form_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
		
		$form_data = clean_array_data ( $form_data );
		if ($this->db->insert ( 'setup_forms', $form_data )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', 'Record has been inserted correctly.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
			return false;
		}
	}
	public function get_single($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'setup_forms' );
		return $query->row ();
	}
	public function edit($id) {
		$data = $this->input->post ();
		$form_data ['type'] = $data ['type'];
		$form_data ['title'] = $data ['title'];
		$form_data ['standard'] = $data ['standard'];
		$form_data ['url'] = $data ['url'];
		$form_data ['disabled'] = $data ['disabled'];
		$form_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		$form_data ['modified'] = date ( 'Y-m-d H:i:s' );
		$form_data ['model'] = $data ['model'];
		
		$form_data = clean_array_data ( $form_data );
		if ($this->db->where ( 'id', $id )->update ( 'setup_forms', $form_data )) {
			
			if (! empty ( $data ['id_attachment'] )) {
				
				$update_attach_values = $this->update_attach_values ( $data, $id );
			}
			
			/**
			 * SAVE COLLECTION *
			 */
			
			$collection_check = $this->save_collection ( $data, $id );
			
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' Record has been updated correctly.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
			return false;
		}
	}
	public function delete($id) {
		$data = $this->get_single ( $id );
		if (count ( $data ) > 0) {
			
			if ($this->db->where ( 'id', $id )->delete ( 'setup_forms' )) {
				log_message ( 'DEBUG', $this->db->last_query () );
				$this->session->set_flashdata ( 'growl_success', 'The company' . $data->title . 'has been removed.' );
				return true;
			} else {
				log_message ( 'ERROR', $this->db->last_query () );
				$this->session->set_flashdata ( 'growl_error', 'There was an error, it could not be removed  ' . $data->title . ', please try again.' );
				return false;
			}
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'You do not have sufficient permissions to remove this record.' );
			return false;
		}
	}
	public function get_form_attachments($id) {
		$query = $this->db->select ( 'l.*, a.title,a.id as attach_id' )->join ( 'setup_attachments a', 'a.id = l.id_attachment', 'left' )->where ( 'l.form_id', $id )->get ( 'setup_forms_attachments l' );
		return $query->result ();
	}
	public function get_unused_attachments($form_id) {
		$query = $this->db->query ( "SELECT l.id,l.title FROM setup_attachments l WHERE  NOT EXISTS (SELECT 1 FROM   setup_forms_attachments i WHERE  l.id = i.id_attachment AND i.form_id = '$form_id')" );
		if ($query->num_rows () > 0) {
			$result ['status'] = TRUE;
			$result ['data'] = $query->result ();
		} else {
			$result ['status'] = FALSE;
		}
		return $result;
	}
	public function update_attach_values($data, $form_id) {
		$keycount = count ( $data ['id_attachment'] );
		for($i = 0; $i < $keycount; $i ++) {
			$valid = $data ['attach_id'] [$i];
			$attach_data ['form_id'] = $form_id;
			$attach_data ['id_attachment'] = $data ['id_attachment'] [$i];
			$attach_data ['use'] = $data ['use'] [$i];
			$attach_data ['multi'] = $data ['hidden_multi'] [$i];
			$attach_data ['required'] = $data ['hidden_required'] [$i];
			$attach_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
			$attach_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
			$attach_data ['modified'] = date ( 'Y-m-d H:i:s' );
			
			if ($valid == '') {
				/* check for duplicate entry */
				if ($this->check_attach_duplicate ( $id_activity, $attach_data ['id_attachment'] )) {
					$this->db->insert ( 'setup_forms_attachments', $attach_data );
				}
			} else {
				$this->db->where ( 'form_id', $form_id )->where ( 'id', $valid )->update ( 'setup_forms_attachments', $attach_data );
			}
		}
	}
	public function check_attach_duplicate($form_id, $id_attachment) {
		$query = $this->db->where ( 'form_id', $form_id )->where ( 'id_attachment', $id_attachment )->get ( 'setup_forms_attachments' );
		if ($query->num_rows () > 0) {
			return FALSE;
		}
		return TRUE;
	}
	public function edit_attachment($data) {
		$values_data ['required'] = $data ['hidden_required'];
		$values_data ['multi'] = $data ['hidden_multi'];
		$values_data ['conditions'] = $data ['conditions'];
		$values_data ['use'] = $data ['use'];
		if ($this->db->where ( 'form_id', $data ['form_id'] )->where ( 'id', $data ['id'] )->update ( 'setup_forms_attachments', $values_data )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' Record has been updated correctly.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
			return false;
		}
	}
	public function get_single_attachment($id) {
		$query = $this->db->select ( 'l.*, a.title,a.id as attach_id' )->join ( 'setup_attachments a', 'a.id = l.id_attachment', 'left' )->where ( 'l.id', $id )->get ( 'setup_forms_attachments l' );
		return $query->row ();
	}
	public function delete_attachment($id) {
		if ($this->db->where ( 'id', $id )->delete ( 'setup_forms_attachments' )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' Record has been deleted successfully' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'It was an error. Please try again.' );
			return false;
		}
	}
	public function get_collections() {
		$query = $this->db->order_by ( 'title', 'asc' )->get ( 'setup_collections_list' );
		return $query->result ();
	}
	public function get_form_collections($id) {
		$query = $this->db->where ( 'form_id', $id )->get ( 'setup_forms_collections' );
		return $query->row ();
	}
	public function save_collection($data, $id) {
		if (count ( $data ['collection'] ) > 0) {
			$id_collections = implode ( ",", $data ['collection'] );
		} else {
			$id_collections = NULL;
		}
		
		$collection_data ['form_id'] = $id;
		$collection_data ['id_plico'] = $id_collections;
		$collection_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		
		$query = $this->db->where ( 'form_id', $id )->get ( 'setup_forms_collections' );
		if ($query->num_rows () > 0) {
			/**
			 * UPDATE *
			 */
			$collection_data ['modified'] = date ( 'd/m/Y H:i:s' );
			if ($this->db->where ( 'form_id', $id )->update ( 'setup_forms_collections', $collection_data )) {
				log_message ( 'DEBUG', $this->db->last_query () );
				return true;
			} else {
				log_message ( 'ERROR', $this->db->last_query () );
				return false;
			}
		} else {
			/**
			 * INSERT *
			 */
			$collection_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
			if ($this->db->insert ( 'setup_forms_collections', $collection_data )) {
				log_message ( 'DEBUG', $this->db->last_query () );
				return true;
			} else {
				log_message ( 'ERROR', $this->db->last_query () );
				return false;
			}
		}
	}
}