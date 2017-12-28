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
class Process extends CI_Model {
	public function get() {
		$query = $this->db->query ( "SELECT DISTINCT p.*, m.mp as macro, (SELECT COUNT(*) FROM setup_activities WHERE setup_activities.id_process=p.id ) as act_count, (SELECT array_to_string(ARRAY_AGG(key),', ') FROM setup_vars_values where id_var = (SELECT id FROM setup_vars where id_activity IS NULL AND type= 'STATUS' AND id_process = p.id)  ) As statuss,(SELECT COUNT(*) FROM setup_vars_values where id_var = (SELECT id FROM setup_vars where id_activity IS NULL AND type= 'STATUS' AND id_process = p.id AND initial = 't')) as initial_count FROM setup_processes p LEFT JOIN setup_mps m ON m.id = p.id_mp" );
		return $query->result ();
	}
	public function total() {
		$query = $this->db->get ( 'setup_processes' );
		return $query->num_rows ();
	}
	public function get_macro_processes() {
		$query = $this->db->select ( 'mp,id' )->order_by ( 'mp', 'asc' )->get ( 'setup_mps' );
		return $query->result ();
	}
	public function add() {
		$data = $this->input->post ();
		$general_data ['id_mp'] = $data ['id_mp'];
		$general_data ['bpm'] = $data ['bpm'];
		$general_data ['key'] = $data ['key'];
		$general_data ['title'] = $data ['title'];
		$general_data ['description'] = $data ['description'];
		$general_data ['role_can_create'] = $data ['role_can_create'];
		$general_data ['weight'] = $data ['weight'];
		$general_data ['sla'] = $data ['sla'];
		$general_data ['wiki_url'] = $data ['wiki_url'];
		$general_data ['form_id'] = $data ['form_id'];
		$general_data ['disabled'] = $data ['disabled'];
		$general_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
		$general_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		
		/**
		 * onsite report : process blocking
		 */
		$general_data ['bloccante_credito'] = $data ['bloccante_credito'];
		$general_data ['bloccante_tecnico'] = $data ['bloccante_tecnico'];
		$general_data ['fast_thread'] = $data ['fast_thread'];
		
		$general_data = clean_array_data ( $general_data );
		$general_data ['fast_thread_view'] = $data ['fast_thread_view'];
		if ($this->db->insert ( 'setup_processes', $general_data )) {
			$insert_id = $this->db->insert_id ();
			
			/* Fetch Default variables having domain PROCESS */
			$get_variables = $this->get_default_variables ();
			
			/* Insert Default variable to setup_vars table */
			$set_variables = $this->set_default_variables ( $get_variables, $insert_id );
			
			/* Get variable ID */
			$status_varid = $this->get_status_varid ( $insert_id );
			
			if (! empty ( $data ['status_key'] )) {
				/* Insert variable values to setup_vars_values table */
				$set_var_values = $this->set_var_values ( $data, $status_varid );
			} else {
				$this->set_default_status_value ( $status_varid );
			}
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' è stata inserita correttamente.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'Si è verificato un errore, preghiamo di riprovare.' );
			return false;
		}
	}
	public function get_default_variables() {
		$query = $this->db->where ( 'domain', 'PROCESS' )->where ( 'disabled', 'f' )->get ( 'setup_default_vars' );
		return $query->result ();
	}
	public function set_default_variables($variables, $processid) {
		foreach ( $variables as $vars ) {
			$setup_vars ['id_process'] = $processid;
			$setup_vars ['source'] = 'SYSTEM';
			$setup_vars ['type'] = $vars->type;
			$setup_vars ['key'] = $vars->key;
			$setup_vars ['description'] = $vars->description;
			$setup_vars ['disabled'] = $vars->disabled;
			$setup_vars ['created_by'] = $this->ion_auth->user ()->row ()->id;
			$setup_vars ['modified_by'] = $this->ion_auth->user ()->row ()->id;
			$this->db->insert ( 'setup_vars', $setup_vars );
		}
		return true;
	}
	public function get_status_varid($processid) {
		$query = $this->db->select ( 'id' )->where ( 'id_process', $processid )->where ( 'type', 'STATUS' )->where ( 'source', 'SYSTEM' )->where ( 'disabled', 'f' );
		return $this->db->get ( 'setup_vars' )->row ()->id;
	}
	public function set_var_values($data, $id_var) {
		$keycount = count ( $data ['status_key'] );
		for($i = 0; $i < $keycount; $i ++) {
			
			if ($data ['ordering'] [$i] == '') {
				$values_data ['ordering'] = '0';
			} else {
				$values_data ['ordering'] = $data ['ordering'] [$i];
			}
			$values_data ['id_var'] = $id_var;
			$values_data ['key'] = $data ['status_key'] [$i];
			$values_data ['label'] = $data ['label'] [$i];
			$values_data ['description'] = $data ['status_description'] [$i];
			$values_data ['initial'] = $data ['hidden_initial'] [$i];
			$values_data ['final'] = $data ['hidden_final'] [$i];
			$values_data ['final_default'] = $data ['hidden_final_default'] [$i];
			$values_data ['disabled'] = $data ['hidden_status_disabled'] [$i];
			$values_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
			$values_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
			$this->db->insert ( 'setup_vars_values', $values_data );
		}
		return true;
	}
	public function set_default_status_value($id_var) {
		$data_status ['id_var'] = $id_var;
		$data_status ['disabled'] = 'f';
		$data_status ['created_by'] = $this->ion_auth->user ()->row ()->id;
		$data_status ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		
		$data_status_bundle [0] = $data_status;
		$data_status_bundle [0] ['ordering'] = 0;
		$data_status_bundle [0] ['key'] = 'NEW';
		$data_status_bundle [0] ['label'] = 'new';
		$data_status_bundle [0] ['description'] = 'Initial status';
		$data_status_bundle [0] ['initial'] = 't';
		$data_status_bundle [0] ['final'] = 'f';
		$data_status_bundle [0] ['final_default'] = 'f';
		
		$data_status_bundle [1] = $data_status;
		$data_status_bundle [1] ['ordering'] = 1;
		$data_status_bundle [1] ['key'] = 'DONE';
		$data_status_bundle [1] ['label'] = 'done';
		$data_status_bundle [1] ['description'] = 'Final status';
		$data_status_bundle [1] ['initial'] = 'f';
		$data_status_bundle [1] ['final'] = 't';
		$data_status_bundle [1] ['final_default'] = 't';
		
		$this->db->insert_batch ( 'setup_vars_values', $data_status_bundle );
	}
	public function get_single($id) {
		$query = $this->db->where ( 'setup_processes.id', $id )->get ( 'setup_processes' );
		return $query->row ();
	}
	public function edit($id) {
		$data = $this->input->post ();
		
		$general_data ['id_mp'] = $data ['id_mp'];
		$general_data ['bpm'] = $data ['bpm'];
		$general_data ['key'] = $data ['key'];
		$general_data ['title'] = $data ['title'];
		$general_data ['description'] = $data ['description'];
		$general_data ['role_can_create'] = $data ['role_can_create'];
		$general_data ['weight'] = $data ['weight'];
		$general_data ['sla'] = $data ['sla'];
		$general_data ['wiki_url'] = $data ['wiki_url'];
		$general_data ['form_id'] = $data ['form_id'];
		$general_data ['disabled'] = $data ['disabled'];
		$general_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		$general_data ['modified'] = date ( 'Y-m-d H:i:s' );
		
		/**
		 * onsite report : process blocking
		 */
		$general_data ['bloccante_credito'] = $data ['bloccante_credito'];
		$general_data ['bloccante_tecnico'] = $data ['bloccante_tecnico'];
		$general_data ['fast_thread'] = $data ['fast_thread'];
		
		$general_data = clean_array_data ( $general_data );
		$general_data ['fast_thread_view'] = $data ['fast_thread_view'];
		if ($this->db->where ( 'id', $id )->update ( 'setup_processes', $general_data )) {
			
			/* Get variable ID */
			$varid = $this->get_status_varid ( $id );
			if (! empty ( $data ['status_key'] )) {
				
				/* Update variable values to setup_vars_values table */
				$update_var_values = $this->edit_var_values ( $data, $varid );
			}
			
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' Record has been updated correctly.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
			return false;
		}
	}
	public function edit_var_values($data, $varid) {
		$keycount = count ( $data ['status_key'] );
		for($i = 0; $i < $keycount; $i ++) {
			
			if ($data ['ordering'] [$i] == '') {
				$values_data ['ordering'] = '0';
			} else {
				$values_data ['ordering'] = $data ['ordering'] [$i];
			}
			$valid = $data ['val_id'] [$i];
			$values_data ['key'] = $data ['status_key'] [$i];
			$values_data ['label'] = $data ['label'] [$i];
			$values_data ['description'] = $data ['status_description'] [$i];
			$values_data ['initial'] = $data ['hidden_initial'] [$i];
			$values_data ['final'] = $data ['hidden_final'] [$i];
			$values_data ['final_default'] = $data ['hidden_final_default'] [$i];
			$values_data ['disabled'] = $data ['hidden_status_disabled'] [$i];
			$values_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
			$values_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
			$values_data ['modified'] = date ( 'Y-m-d H:i:s' );
			
			if ($valid == '') {
				$values_data ['id_var'] = $varid;
				$this->db->insert ( 'setup_vars_values', $values_data );
			} else {
				$this->db->where ( 'id_var', $varid )->where ( 'id', $valid )->update ( 'setup_vars_values', $values_data );
			}
		}
		return true;
	}
	public function get_authorized_role() {
		$query = $this->db->select ( 'key,id' )->where ( 'disabled', 'f' )->get ( 'setup_roles' );
		return $query->result ();
	}
	public function get_form_types() {
		$query = $this->db->select ( 'type,id,title' )->where ( 'type', 'THREAD' )->where ( 'disabled', 'f' )->get ( 'setup_forms' );
		return $query->result ();
	}
	public function get_status($id_process = NULL, $flag = NULL, $vid = NULL) {
		if ($flag == 1) {
			$source = 'SYSTEM';
			$type = 'STATUS';
		} else {
			$source = 'CUSTOM';
			$type = NULL;
		}
		$this->db->select ( 'v.*' )->join ( 'setup_vars m', 'm.id = v.id_var', 'left' )->where ( 'm.source', $source )->order_by ( "v.ordering", "asc" );
		if ($id_process != NULL)
			$this->db->where ( 'm.id_process', $id_process );
		if ($type != NULL)
			$this->db->where ( 'm.type', $type );
		if ($vid != NULL)
			$this->db->where ( 'v.id_var', $vid );
		$this->db->where ( 'id_activity', NULL );
		$query = $this->db->get ( 'setup_vars_values v' );
		return $query->result ();
	}
	public function get_other_variables($id_process, $source) {
		$this->db->where ( 'id_process', $id_process )->where ( 'source', $source )->where ( 'id_activity IS NULL' )->order_by ( 'id', 'asc' );
		$query = $this->db->get ( 'setup_vars' );
		return $query->result ();
	}
	public function delete_status($id) {
		if ($this->db->where ( 'id', $id )->delete ( 'setup_vars_values' )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' It has been deleted successfully' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'It was an error. Please try again.' );
			return false;
		}
	}
	public function add_variable($vardata) {
		$var_data ['id_process'] = $vardata ['processid'];
		$var_data ['type'] = $vardata ['type'];
		$var_data ['key'] = $vardata ['key'];
		$var_data ['description'] = $vardata ['description'];
		$var_data ['source'] = 'CUSTOM';
		$var_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
		$var_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		
		if ($this->db->insert ( 'setup_vars', $var_data )) {
			$var_insert_id = $this->db->insert_id ();
			
			if (! empty ( $vardata ['status_key'] )) {
				$update_var_values = $this->edit_var_values ( $vardata, $var_insert_id );
			}
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' Record has been updated correctly.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
			return false;
		}
	}
	public function get_single_variable($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'setup_vars' );
		return $query->row ();
	}
	public function edit_variable($vardata) {
		if (! $vardata ['disabled']) {
			$var_data ['disabled'] = 'f';
		} else {
			$var_data ['disabled'] = 't';
		}
		$vid = $vardata ['varid'];
		$var_data ['type'] = $vardata ['type'];
		$var_data ['key'] = $vardata ['key'];
		$var_data ['description'] = $vardata ['description'];
		$var_data ['modified'] = date ( 'Y-m-d H:i:s' );
		$var_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		
		if ($this->db->where ( 'id', $vid )->update ( 'setup_vars', $var_data )) {
			
			if (! empty ( $vardata ['status_key'] )) {
				$update_var_values = $this->edit_var_values ( $vardata, $vid );
			}
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', 'Record has been updated correctly.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
			return false;
		}
	}
	public function delete_variable($id) {
		if ($this->db->where ( 'id', $id )->delete ( 'setup_vars' )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->db->where ( 'id_var', $id )->delete ( 'setup_vars_values' );
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' It has been deleted successfully' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'It was an error. Please try again.' );
			return false;
		}
	}
	public function get_single_key($process, $type) {
		$query = $this->db->select ( 'setup_processes.*, setup_forms.url' )->join ( 'setup_forms', 'setup_forms.id = setup_processes.form_id' )->join ( 'setup_mps', 'setup_mps.id = setup_processes.id_mp' )->where ( 'setup_mps.mp', $process )->where ( 'setup_processes.key', $type )->get ( 'setup_processes' );
		return $query->row ();
	}
	public function get_single_process_key($key) {
		$query = $this->db->where ( 'setup_processes.key', $key )->get ( 'setup_processes' );
		return $query->row ();
	}
	public function get_macro_single($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'setup_mps' );
		return $query->row ();
	}
	public function check_associated_activiites($process_id) {
		$query = $this->db->where ( 'setup_activities.id_process', $process_id )->get ( 'setup_activities' );
		if ($query->num_rows () > 0) {
			$this->session->set_flashdata ( 'growl_error', 'It was an error. Please try again.' );
			return false;
		}
		return true;
	}
	public function delete_process($process_id) {
		$this->delete_associated_vars ( $process_id );
		if ($this->db->where ( 'id', $process_id )->delete ( 'setup_processes' )) {
			
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' It has been deleted successfully' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'It was an error. Please try again.' );
			return false;
		}
	}
	public function delete_associated_vars($process_id) {
		$query = $this->db->where ( 'id_process', $process_id )->where ( 'id_activity', NULL )->get ( 'setup_vars' );
		$data = $query->result ();
		foreach ( $data as $item ) {
			$this->db->where ( 'id_var', $item->id )->delete ( 'setup_vars_values' );
		}
		
		$this->db->where ( 'id_process', $process_id )->where ( 'id_activity', NULL )->delete ( 'setup_vars' );
	}
	public function get_initial_status($key) {
		$data = $this->get_single_process_key ( $key );
		if (isset ( $data->id )) {
			$query = $this->db->select ( 'setup_vars_values.key' )->join ( 'setup_vars', 'setup_vars.id = setup_vars_values.id_var' )->where ( 'setup_vars_values.initial', 't' )->where ( 'setup_vars.id_process', $data->id )->where ( 'setup_vars.id_activity', NULL )->get ( 'setup_vars_values' );
			return $query->row ()->key;
		}
		return false;
	}
	public function get_process_type($id_process) {
		$query = $this->db->where ( 'id', $id_process )->get ( 'setup_processes' );
		return $query->row ();
	}
}