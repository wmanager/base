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
class Attach extends CI_Model {
	public function get($limit, $offset = 0) {
		$query = $this->db->distinct ( 'id' )->where ( 'disabled', 'f' )->limit ( $limit, $offset )->order_by ( 'id', 'desc' )->get ( 'setup_attachments' );
		return $query->result ();
	}
	public function total() {
		$query = $this->db->where ( 'disabled', 'f' )->get ( 'setup_attachments' );
		return $query->num_rows ();
	}
	public function add() {
		$attach_data = $this->input->post ();
		
		if($attach_data['ambit_id'] == '-'){
			unset($attach_data['ambit_id']);
		}
		
		$attach_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
		
		$attach_data = clean_array_data ( $attach_data );
		if ($this->db->insert ( 'setup_attachments', $attach_data )) {
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
		$query = $this->db->where ( 'id', $id )->get ( 'setup_attachments' );
		return $query->row ();
	}
	public function get_ambit() {
		$query = $this->db->get ( 'list_ambits' );
		return $query->result ();
	}
	public function edit($id) {
		$attach_data = $this->input->post ();
		
		if($attach_data['ambit_id'] == '-'){
			unset($attach_data['ambit_id']);
		}
		$attach_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		$attach_data ['modified'] = date ( 'Y-m-d H:i:s' );
		
		if ($this->db->where ( 'id', $id )->update ( 'setup_attachments', $attach_data )) {
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
			
			if ($this->db->where ( 'id', $id )->delete ( 'setup_attachments' )) {
				log_message ( 'DEBUG', $this->db->last_query () );
				$this->session->set_flashdata ( 'growl_success', 'The company' . $data->title . ' has been removed.' );
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
}