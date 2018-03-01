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
class Trouble_types extends CI_Model {
	/**
	 * get_trouble_types
	 * To fetch all troubles list
	 *
	 * @param integer $limit        	
	 * @param integer $offset        	
	 *
	 * @author Shemil
	 */
	public function get_trouble_types($limit, $offset = 0) {
		if ($this->input->post ( 'pod_imo_list_search' ) && $this->input->post ( 'pod_imo_list_search' ) != '') {
			$this->session->set_userdata ( 'pod_imo_list_search', $this->input->post ( 'pod_imo_list_search' ) );
		} else if (isset ( $_POST ['pod_imo_list_search'] ) && $_POST ['pod_imo_list_search'] == '') {
			$this->session->unset_userdata ( 'pod_imo_list_search' );
		}
		
		if ($this->input->post ( 'pod_imo_list_unassociated' ) != '') {
			$this->session->set_userdata ( 'pod_imo_list_unassociated', $this->input->post ( 'pod_imo_list_unassociated' ) );
		} else if (isset ( $_POST ['pod_imo_list_unassociated'] ) && $_POST ['pod_imo_list_unassociated'] == '') {
			$this->session->unset_userdata ( 'pod_imo_list_unassociated' );
		}
		
		$filter1 = $this->session->userdata ( 'pod_imo_list_unassociated' );
		$filter2 = $this->session->userdata ( 'pod_imo_list_search' );
		
		if ($filter1) {
			$offset = 0;
			$this->db->where ( "be_id IS NULL" );
		}
		
		if ($filter2) {
			$offset = 0;
			$this->db->where ( "(pod ILIKE '%$filter2%') OR (imo ILIKE '%$filter2%')" );
		}
		// fetch pod_imo's
		$get_data = $this->db->select ( "*" )->limit ( $limit, $offset )->order_by ( "id" )->get ( "setup_troubles_types" );
		$lettura = $get_data->result_array ();
		
		return $lettura;
	}
	
	/**
	 * total
	 * function to get the total numbers
	 *
	 * @return object array
	 *        
	 * @author Sumesh
	 */
	public function total() {
		$query = $this->db->get ( 'setup_troubles_types' );
		return $query->num_rows ();
	}
	
	/**
	 * check_unique
	 * To check the uniqueness of trouble type key
	 *
	 * @param string $key        	
	 * @param number $id        	
	 *
	 * @return integer
	 *
	 * @author Sumesh
	 */
	public function check_unique($key, $id) {
		$this->db->select ( 'key' );
		$this->db->where ( 'key', str_replace ( ' ', '_', trim ( strtoupper ( $key ) ) ) );
		if (! empty ( $id )) {
			$this->db->where ( 'id !=', $id );
		}
		$query = $this->db->get ( 'setup_troubles_types' );
		
		return $query->num_rows ();
	}
	
	/**
	 * add_trouble_type
	 * To add new trouble type
	 *
	 * @param
	 *        	array
	 * @return boolean
	 *
	 * @author Sumesh
	 */
	public function add_trouble_type($data) {
		$insert_data ["title"] = $data ['trouble_type_title'];
		$insert_data ["description"] = $data ['trouble_type_description'];
		if ($data ['trouble_type_check_manual'] == 't') {
			$insert_data ["manual"] = 't';
		} else {
			$insert_data ["manual"] = 'f';
		}
		if ($data ['trouble_type_check_active'] == 't') {
			$insert_data ["active"] = 't';
		} else {
			$insert_data ["active"] = 'f';
		}
		$insert_data ["severity"] = $data ['trouble_type_severity'];
		$insert_data ["key"] = str_replace ( ' ', '_', trim ( strtoupper ( $data ['trouble_type_key'] ) ) );
		
		if ($insert_data ["title"] != '' && $insert_data ["key"] != '' && $insert_data ["severity"] != '') {
			if ($this->db->insert ( "setup_troubles_types", $insert_data )) {
				$insert_id = $this->db->insert_id ();
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * get_single_trouble_type
	 *
	 * @param integer $item_id        	
	 *
	 * @return object array / boolean
	 *        
	 * @author Sumesh
	 */
	public function get_single_trouble_type($id) {
		if ($id != null) {
			$query = $this->db->select ( '*' )->where ( "id", $id )->get ( 'setup_troubles_types' );
			
			$data = $query->result ();
			
			return $data [0];
		} else {
			return false;
		}
	}
	
	/**
	 * update_trouble_type
	 *
	 * @param array $post        	
	 *
	 * @return string
	 *
	 * @author Sumesh
	 */
	public function update_trouble_type($post) {
		if ($post ['trouble_type_title']) {
			$update_data ["title"] = $post ['trouble_type_title'];
		}
		if ($post ['trouble_type_description']) {
			$update_data ["description"] = $post ['trouble_type_description'];
		}
		if ($post ['trouble_type_check_manual'] == 't') {
			$update_data ["manual"] = 't';
		} else {
			$update_data ["manual"] = 'f';
		}
		if ($post ['trouble_type_check_active'] == 't') {
			$update_data ["active"] = 't';
		} else {
			$update_data ["active"] = 'f';
		}
		if ($post ['trouble_type_severity']) {
			$update_data ["severity"] = $post ['trouble_type_severity'];
		}
		if ($post ['trouble_type_key']) {
			$update_data ["key"] = $post ['trouble_type_key'];
		}
		
		$trouble_type_id = str_replace ( ' ', '_', trim ( strtoupper ( $post ['trouble_type_id'] ) ) );
		
		if ($trouble_type_id != '' && $update_data ["title"] != '' && $update_data ["key"] != '' && $update_data ["severity"] != '') {
			if ($this->db->where ( 'id', $trouble_type_id )->update ( 'setup_troubles_types', $update_data )) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * delete_trouble_type
	 * To add new trouble type
	 *
	 * @author Sumesh
	 */
	public function delete_trouble_type($id = null) {
		if ($id != null) {
			return $this->db->where ( 'id', $id )->delete ( 'setup_troubles_types' );
		} else {
			return false;
		}
	}
	
	/**
	 * get_all_related_process
	 * To fetch all related process with the mapped trouble id
	 * 
	 * @param integer $id        	
	 *
	 * @author Sumesh
	 */
	public function get_all_related_process($id = null) {
		if ($id != null) {
			$query = $this->db->select ( '*' )->where ( "trouble_type", $id )->get ( 'setup_troubles_types_2_processes_types' );
			
			$data = $query->result ();
			
			return $data;
		} else {
			return array ();
		}
	}
	
	/**
	 * update_relared_process
	 *
	 * @param array $post        	
	 *
	 * @return string
	 *
	 * @author Sumesh
	 */
	public function update_relared_process($post) {
		$save_update_data = array ();
		$save_insert_data = array ();
		$process_key = $post ['process_key'];
		$request_key = $post ['request_key'];
		$hidden_related_process_auto_create = $post ['hidden_related_process_auto_create'];
		$val_id = $post ['val_id'];
		$trouble_type_id_for_related_process = $post ['trouble_type_id_for_related_process'];
		
		$i = 1;
		foreach ( $val_id as $key => $value ) {
			if ($value != null) {
				$save_update_data [$value] ['process_key'] = $this->explode_process_key ( $process_key [$key] );
				$save_update_data [$value] ['request_key'] = $request_key [$key];
				$save_update_data [$value] ['autocreate'] = ($hidden_related_process_auto_create [$key] == 't') ? 't' : 'f';
			} else {
				$save_insert_data [$i] ['process_key'] = $this->explode_process_key ( $process_key [$key] );
				$save_insert_data [$i] ['request_key'] = $request_key [$key];
				$save_insert_data [$i] ['autocreate'] = ($hidden_related_process_auto_create [$key] == 't') ? 't' : 'f';
				
				$i ++;
			}
		}
		
		$save_count = 0;
		if (count ( $save_update_data ) > 0) {
			
			foreach ( $save_update_data as $key => $value ) {
				$update_data = array ();
				$update_data ['process_key'] = $value ['process_key'];
				$update_data ['request_key'] = $value ['request_key'];
				$update_data ['autocreate'] = $value ['autocreate'];
				
				if ($update_data ['process_key'] !== '' && $update_data ['request_key'] != '') {
					if ($this->db->where ( 'id', $key )->update ( 'setup_troubles_types_2_processes_types', $update_data )) {
						$save_count ++;
					}
				}
			}
		}
		
		$insert_count = 0;
		if (count ( $save_insert_data ) > 0) {
			foreach ( $save_insert_data as $key => $value ) {
				$insert_data = array ();
				$insert_data ['trouble_type'] = $trouble_type_id_for_related_process;
				$insert_data ['process_key'] = $value ['process_key'];
				$insert_data ['request_key'] = $value ['request_key'];
				$insert_data ['autocreate'] = $value ['autocreate'];
				$insert_data ['created'] = date ( "Y/m/d h:i:s" );
				
				if ($insert_data ['process_key'] !== '' && $insert_data ['request_key'] != '') {
					if ($this->db->insert ( "setup_troubles_types_2_processes_types", $insert_data )) {
						$insert_count ++;
					}
				}
			}
		}
		
		if ($save_count > 0 && $insert_count > 0) {
			return 'saved_inserted';
		} else if ($save_count > 0 && $insert_count == 0) {
			return 'saved';
		} else if ($insert_count > 0 && $save_count == 0) {
			return 'inserted';
		}
	}
	
	/**
	 * explode_process_key
	 *
	 * @return string
	 *
	 * @author Sumesh
	 */
	function explode_process_key($data) {
		$explode_string = explode ( '|', $data );
		
		return $explode_string [1];
	}
	
	/**
	 * get_all_setup_processes
	 *
	 * @return array
	 *
	 * @author Sumesh
	 */
	public function get_all_setup_processes() {
		$query = $this->db->select ( 'id, key' )->get ( 'setup_processes' );
		
		return $query->result ();
	}
	
	/**
	 * get_setup_activites
	 *
	 * To get all setup activites based on the process_id specified
	 *
	 * @param integer $process_id        	
	 *
	 * @author Sumesh
	 */
	public function get_setup_activites($process_id) {
		$query = $this->db->select ( 'key' )->where ( "id_process", $process_id )->get ( 'setup_activities' );
		
		return $query->result ();
	}
	
	/**
	 * delete_relared_process
	 *
	 * To delete the related process based on the process_id specified
	 *
	 * @param integer $id        	
	 *
	 * @author Sumesh
	 */
	public function delete_relared_process($id = null) {
		if ($id != null) {
			return $this->db->where ( 'id', $id )->delete ( 'setup_troubles_types_2_processes_types' );
		} else {
			return false;
		}
	}
	
	/**
	 * get_all_troubles_subtypes
	 * To fetch all trouble subtypes with the mapped trouble id
	 * 
	 * @param integer $id        	
	 *
	 * @author Sumesh
	 */
	public function get_all_troubles_subtypes($id = null) {
		if ($id != null) {
			$query = $this->db->select ( '*' )->where ( "trouble_type", $id )->get ( 'setup_troubles_subtypes' );
			
			$data = $query->result ();
			
			return $data;
		} else {
			return array ();
		}
	}
	public function update_troubles_subtypes($post) {
		$save_update_data = array ();
		$save_insert_data = array ();
		$subtype_key = $post ['subtype_key'];
		$subtype_value = $post ['subtype_value'];
		$val_id = $post ['val_id'];
		$trouble_type_id_for_troubles_subtypes = $post ['trouble_type_id_for_troubles_subtypes'];
		
		/*
		 * echo '<pre>';
		 * print_r($post);
		 * die;
		 */
		
		$i = 1;
		foreach ( $val_id as $key => $value ) {
			if ($value != null) {
				$save_update_data [$value] ['subtype_key'] = str_replace ( ' ', '_', trim ( strtoupper ( $subtype_key [$key] ) ) );
				$save_update_data [$value] ['subtype_value'] = $subtype_value [$key];
			} else {
				$save_insert_data [$i] ['subtype_key'] = str_replace ( ' ', '_', trim ( strtoupper ( $subtype_key [$key] ) ) );
				$save_insert_data [$i] ['subtype_value'] = $subtype_value [$key];
				
				$i ++;
			}
		}
		
		/*
		 * echo '<pre>';
		 * print_r($save_update_data);
		 * echo '<pre>';
		 * print_r($save_insert_data);
		 * die;
		 */
		
		$save_count = 0;
		if (count ( $save_update_data ) > 0) {
			
			foreach ( $save_update_data as $key => $value ) {
				$update_data = array ();
				$update_data ['key'] = $value ['subtype_key'];
				$update_data ['value'] = $value ['subtype_value'];
				
				if ($update_data ['key'] != '' && $update_data ['value'] != '') {
					if ($this->db->where ( 'id', $key )->update ( 'setup_troubles_subtypes', $update_data )) {
						$save_count ++;
					}
				}
			}
		}
		
		$insert_count = 0;
		if (count ( $save_insert_data ) > 0) {
			foreach ( $save_insert_data as $key => $value ) {
				$insert_data = array ();
				$insert_data ['trouble_type'] = $trouble_type_id_for_troubles_subtypes;
				$insert_data ['key'] = $value ['subtype_key'];
				$insert_data ['value'] = $value ['subtype_value'];
				
				if ($insert_data ['key'] != '' && $insert_data ['value'] != '') {
					if ($this->db->insert ( "setup_troubles_subtypes", $insert_data )) {
						$insert_count ++;
					}
				}
			}
		}
		
		if ($save_count > 0 && $insert_count > 0) {
			return 'saved_inserted';
		} else if ($save_count > 0 && $insert_count == 0) {
			return 'saved';
		} else if ($insert_count > 0 && $save_count == 0) {
			return 'inserted';
		}
	}
	
	/**
	 * delete_troubles_subtype
	 *
	 * To delete the related process based on the process_id specified
	 *
	 * @param integer $id        	
	 *
	 * @author Sumesh
	 */
	public function delete_troubles_subtype($id = null) {
		if ($id != null) {
			return $this->db->where ( 'id', $id )->delete ( 'setup_troubles_subtypes' );
		} else {
			return false;
		}
	}
	
	/**
	 * check_unique_subtype
	 * To check the uniqueness of trouble subtype key
	 *
	 * @param string $key        	
	 * @param number $id        	
	 *
	 * @return integer
	 *
	 * @author Sumesh
	 */
	public function check_unique_subtype($key, $id) {
		$this->db->select ( 'key' );
		$this->db->where ( 'key', str_replace ( ' ', '_', trim ( strtoupper ( $key ) ) ) );
		if (! empty ( $id )) {
			$this->db->where ( 'id !=', $id );
		}
		$query = $this->db->get ( 'setup_troubles_subtypes' );
		
		return $query->num_rows ();
	}
	
	/**
	 * check_unique_related_process
	 * To check the uniqueness of trouble subtype key
	 *
	 * @param string $key        	
	 * @param integer $id        	
	 * @param integer $trouble_type_id        	
	 *
	 * @return integer
	 *
	 * @author Sumesh
	 */
	public function check_unique_related_process($post) {
		$key = $post ['key'];
		$id = $post ['id'];
		$trouble_type_id = $post ['trouble_type_id'];
		
		$process_key = $this->explode_process_key ( $key );
		
		$this->db->select ( 'process_key' );
		$this->db->where ( 'process_key', str_replace ( ' ', '_', trim ( strtoupper ( $process_key ) ) ) );
		if (! empty ( $id )) {
			$this->db->where ( 'id !=', $id );
		}
		$this->db->where ( 'trouble_type', $trouble_type_id );
		$query = $this->db->get ( 'setup_troubles_types_2_processes_types' );
		
		return $query->num_rows ();
	}
}
?>
