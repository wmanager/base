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
class Setup_activity extends CI_Model {
	public function get($process_id, $limit, $offset = 0) {
		if ($offset == '')
			$offset = 0;
		$query = $this->db->query ( "SELECT a.*, (SELECT COUNT(*) FROM setup_vars l WHERE l.id_activity = a.id AND l.disabled = 'f') AS count,(SELECT array_to_string(ARRAY_AGG(key),', ') FROM setup_vars_values where id_var = (SELECT id FROM setup_vars where id_activity = a.id AND type= 'STATUS')  ) As statuss,(SELECT title FROM setup_forms f WHERE f.id = a.form_id AND f.disabled = 'f') AS form_title, (SELECT COUNT(*) FROM setup_activities_exits e WHERE e.id_activity = a.id AND e.disabled = 'f') AS exit_count,(SELECT COUNT(*) FROM setup_vars_values where id_var = (SELECT id FROM setup_vars where type= 'STATUS' AND id_activity = a.id AND initial = 't')) as initial_count FROM setup_activities a WHERE a.id_process = $process_id ORDER BY a.ordering ASC LIMIT $limit OFFSET $offset" );
		return $query->result ();
	}
	public function total($process_id) {
		$query = $this->db->where ( 'disabled', 'f' )->where ( 'id_process', $process_id )->get ( 'setup_activities' );
		return $query->num_rows ();
	}
	public function add() {
		$data = $this->input->post ();
		
		$general_data ['id_process'] = $data ['id_process'];
		$general_data ['type'] = $data ['type'];
		$general_data ['key'] = $data ['key'];
		$general_data ['title'] = $data ['title'];
		$general_data ['description'] = $data ['description'];
		$general_data ['role'] = $data ['role'];
		$general_data ['weight'] = $data ['weight'];
		$general_data ['sla'] = $data ['sla'];
		$general_data ['help'] = $data ['help'];
		$general_data ['form_id'] = $data ['form_id'];
		$general_data ['disabled'] = $data ['disabled'];
		$general_data ['is_request'] = $data ['is_request'];
		$general_data ['be_required'] = $data ['be_required'];
		$general_data ['duty_company'] = $data ['duty_company'];
		
		$general_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
		$general_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		$general_data ['ordering'] = $this->total ( $data ['id_process'] ) + 1;
		
// 		/**
// 		 * onsite report setup
// 		 */
// 		$general_data ['is_workorder'] = $data ['is_workorder'];
// 		$general_data ['reportizzazione_avanzata'] = $data ['reportizzazione_avanzata'];
// 		$onsite_report_type_id_flag = false;
		
// 		if ($general_data ['is_workorder'] == 't') {
// 			if ($data ['onsite_report_type_id'] != '-') {
// 				$general_data ['onsite_report_type_id'] = $data ['onsite_report_type_id'];
// 			} else {
// 				$onsite_report_type_id_flag = true;
// 			}
// 		} else {
// 			$general_data ['onsite_report_type_id'] = NULL;
// 		}
		
		$general_data = clean_array_data ( $general_data );
		
		if ($onsite_report_type_id_flag == false) {
			if ($this->db->insert ( 'setup_activities', $general_data )) {
				
				$insert_id = $this->db->insert_id ();
				
				/* Fetch Default variables having domain ACTIVITY */
				$get_variables = $this->get_default_variables ();
				
				/* Insert Default variable to setup_vars table */
				$set_variables = $this->set_default_variables ( $get_variables, $insert_id, $data ['id_process'] );
				
				/* Get variable ID */
				$status_varid = $this->get_status_varid ( $insert_id );
				
				if (! empty ( $data ['status_key'] )) {
					/* Insert variable values to setup_vars_values table */
					$set_var_values = $this->set_var_values ( $data, $status_varid );
				} else {
					/**
					 * onsite report: workorder checking
					 */
					if ($data ['is_workorder'] == 't') {
						$this->setup_activity_default_status_workorder ( $status_varid );
					} else {
						$this->set_default_status_value ( $status_varid );
					}
				}
				
				log_message ( 'DEBUG', $this->db->last_query () );
				$this->session->set_flashdata ( 'growl_success', ' è stata inserita correttamente.' );
				return true;
			} else {
				log_message ( 'ERROR', $this->db->last_query () );
				$this->session->set_flashdata ( 'growl_error', 'Si è verificato un errore, preghiamo di riprovare.' );
				return false;
			}
		} else {
			$this->session->set_flashdata ( 'growl_error', 'Something went wrong, please check input fields' );
			return false;
		}
	}
	public function get_default_variables() {
		$query = $this->db->where ( 'domain', 'ACTIVITY' )->where ( 'disabled', 'f' )->get ( 'setup_default_vars' );
		return $query->result ();
	}
	public function set_default_variables($variables, $activityid, $id_process) {
		foreach ( $variables as $vars ) {
			$setup_vars ['id_activity'] = $activityid;
			$setup_vars ['id_process'] = $id_process;
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
	public function get_status_varid($activityid) {
		$query = $this->db->select ( 'id' )->where ( 'id_activity', $activityid )->where ( 'type', 'STATUS' )->where ( 'source', 'SYSTEM' )->where ( 'disabled', 'f' );
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
	public function get_authorized_role() {
		$query = $this->db->select ( 'key,id' )->where ( 'disabled', 'f' )->get ( 'setup_roles' );
		return $query->result ();
	}
	public function get_process_title($process_id) {
		$process_title = $this->db->where ( 'id', $process_id )->get ( 'setup_processes' )->row ()->title;
		return $process_title;
	}
	public function get_form_types() {
		$query = $this->db->select ( 'type,id,title' )->where ( 'type', 'ACTIVITY' )->where ( 'disabled', 'f' )->get ( 'setup_forms' );
		return $query->result ();
	}
	public function get_status($id_activity = NULL, $flag = NULL, $vid = NULL) {
		if ($flag == 1) {
			$source = 'SYSTEM';
			$type = 'STATUS';
		} else {
			$source = 'CUSTOM';
			$type = NULL;
		}
		$this->db->select ( 'v.*,m.id_process,m.id_activity' )->join ( 'setup_vars m', 'm.id = v.id_var', 'left' )->where ( 'm.source', $source )->order_by ( "v.ordering", "asc" );
		if ($id_activity != null)
			$this->db->where ( 'm.id_activity', $id_activity );
		if ($type != NULL)
			$this->db->where ( 'm.type', $type );
		if ($vid != NULL)
			$this->db->where ( 'v.id_var', $vid );
		$query = $this->db->get ( 'setup_vars_values v' );
		$result = $query->result ();
		
		if (count ( $result ) > 0) {
			foreach ( $result as $item ) {
				$get_transition_count = $this->db->select ( "count(dst_status_key)" )->where ( "src_status_key = '" . $item->key . "'" )->where ( "id_process_type", $item->id_process )->where ( "id_activity_type", $item->id_activity )->get ( "setup_status_transitions" );
				$item->transition_count = $get_transition_count->row ()->count;
			}
		}
		
		return $query->result ();
	}
	public function get_other_variables($id_activity = null, $source) {
		$this->db->where ( 'id_activity', $id_activity )->where ( 'source', $source )->order_by ( 'ordering', 'asc' );
		$query = $this->db->get ( 'setup_vars' );
		return $query->result ();
	}
	public function get_attachments() {
		$query = $this->db->where ( 'disabled', 'f' )->get ( 'setup_attachments' );
		return $query->result ();
	}
	public function get_single($id) {
		$query = $this->db->where ( 'setup_activities.id', $id )->get ( 'setup_activities' );
		return $query->row ();
	}
	public function edit($id) {
		$data = $this->input->post ();
		
		// echo $data['duty_company']; exit;
		$general_data ['type'] = $data ['type'];
		$general_data ['key'] = $data ['key'];
		$general_data ['title'] = $data ['title'];
		$general_data ['description'] = $data ['description'];
		$general_data ['weight'] = $data ['weight'];
		$general_data ['sla'] = $data ['sla'];
		$general_data ['help'] = $data ['help'];
		$general_data ['disabled'] = $data ['disabled'];
		$general_data ['is_request'] = $data ['is_request'];
		$general_data ['be_required'] = $data ['be_required'];
		$general_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		$general_data ['modified'] = date ( 'Y-m-d H:i:s' );
		
// 		/**
// 		 * onsite report setup
// 		 */
// 		$general_data ['is_workorder'] = $data ['is_workorder'];
// 		$general_data ['reportizzazione_avanzata'] = $data ['reportizzazione_avanzata'];
		
// 		$onsite_report_type_id_flag = false;
		
// 		if ($general_data ['is_workorder'] == 't') {
// 			if ($data ['onsite_report_type_id'] != '-') {
// 				$general_data ['onsite_report_type_id'] = $data ['onsite_report_type_id'];
// 			} else {
// 				$onsite_report_type_id_flag = true;
// 			}
// 		} else {
// 			$general_data ['onsite_report_type_id'] = NULL;
// 		}
		
		$general_data = clean_array_data ( $general_data );
		
		if ($general_data ['description'] != '') {
			$general_data ['description'] = $data ['description'];
		} else {
			$general_data ['description'] = NULL;
		}
		
		if ($general_data ['help'] != '') {
			$general_data ['help'] = $data ['help'];
		} else {
			$general_data ['help'] = NULL;
		}
		
		if ($data ['duty_company'] != '') {
			$general_data ['duty_company'] = $data ['duty_company'];
		} else {
			$general_data ['duty_company'] = NULL;
		}
		if ($onsite_report_type_id_flag == false) {
			if ($data ['form_id'] == '') {
				$general_data ['form_id'] = NULL;
			} else {
				$general_data ['form_id'] = $data ['form_id'];
			}
			if ($data ['role'] == '') {
				$general_data ['role'] = NULL;
			} else {
				$general_data ['role'] = $data ['role'];
			}
			
			if ($this->db->where ( 'id', $id )->update ( 'setup_activities', $general_data )) {
				
				/* Get variable ID */
				$varid = $this->get_status_varid ( $id );
				
				if (! empty ( $data ['status_key'] )) {
					
					/* Update variable values to setup_vars_values table */
					if ($data ['is_workorder'] == 't') {
						$this->setup_activity_default_status_workorder_edit ( $varid );
					} else {
						$update_var_values = $this->edit_var_values ( $data, $varid );
					}
				}
				
				if (! empty ( $data ['id_attachment'] )) {
					/* Update attachment values to setup_forms_attachments table */
					$update_attach_values = $this->update_attach_values ( $data, $id );
				}
				
				// Updating the ordering of other variables
				if (count ( $data ['other_vars_ids'] ) > 0) {
					foreach ( $data ['other_vars_ids'] as $key => $id ) {
						$var_ordering ['ordering'] = ($data ['var_ordering'] [$key]) ? $data ['var_ordering'] [$key] : 0;
						$this->db->where ( 'id', $id )->update ( 'setup_vars', $var_ordering );
					}
				}
				
				log_message ( 'DEBUG', $this->db->last_query () );
				$this->session->set_flashdata ( 'growl_success', ' Record has been updated correctly.' );
				
				return true;
			} else {
				log_message ( 'ERROR', $this->db->last_query () );
				$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
				return false;
			}
		} else {
			$this->session->set_flashdata ( 'growl_error', 'Something went wrong, please check input fields' );
			return false;
		}
	}
	
	/**
	 * setup_activity_default_status_workorder_edit
	 *
	 * @param number $id_var        	
	 */
	public function setup_activity_default_status_workorder_edit($id_var) {
		$query = $this->db->select ( '*' )->where ( 'id_var', $id_var )->get ( 'setup_vars_values' );
		
		$data = $query->result ();
		
		$status_order_array = array ();
		
		foreach ( $data as $key => $value ) {
			$status_order_array [$value->key] = $value->ordering;
		}
		
		$this->setup_activity_default_status_workorder ( $id_var, $status_order_array );
	
	/**
	 * onsite report updating setup activities status if it not exist
	 */
	}
	
	/**
	 * setup_activity_default_status_workorder
	 *
	 * @param number $id        	
	 */
	public function setup_activity_default_status_workorder($id_var, $status_order_array = array()) {
		$status_array = array (
				'NEW' => array (
						'ordering' => 0,
						'label' => 'new',
						'description' => 'Initial status',
						'initial' => 'f',
						'final' => 'f',
						'final_default' => 'f' 
				),
				'DA_PIANIFICARE' => array (
						'ordering' => 1,
						'label' => 'da pianificare',
						'description' => 'Ready for planning',
						'initial' => 't',
						'final' => 'f',
						'final_default' => 'f' 
				),
				'PIANIFICATO' => array (
						'ordering' => 2,
						'label' => 'pianificato',
						'description' => 'Scheduled',
						'initial' => 'f',
						'final' => 'f',
						'final_default' => 'f' 
				),
				'DA_REPORTIZZARE' => array (
						'ordering' => 3,
						'label' => 'da reportizzare',
						'description' => 'Waiting for BO-OPERATION',
						'initial' => 'f',
						'final' => 'f',
						'final_default' => 'f' 
				),
				'REPORTIZZATO_OK' => array (
						'ordering' => 4,
						'label' => 'reportizzato positivo',
						'description' => 'Onsite report successfull',
						'initial' => 'f',
						'final' => 'f',
						'final_default' => 'f' 
				),
				'REPORTIZZATO_KO' => array (
						'ordering' => 5,
						'label' => 'reportizzato negativo',
						'description' => 'Onsite report unsuccessfull',
						'initial' => 'f',
						'final' => 'f',
						'final_default' => 'f' 
				),
				'REPORTIZZATO_KO_TECNICO' => array (
						'ordering' => 6,
						'label' => 'reportizzato negativo - KO tecnico',
						'description' => 'Onsite report unsuccessfull',
						'initial' => 'f',
						'final' => 'f',
						'final_default' => 'f' 
				),
				'REPORTIZZATO_KO_COMMERCIALE' => array (
						'ordering' => 7,
						'label' => 'reportizzato negativo - KO commerciale',
						'description' => 'Onsite report unsuccessfull',
						'initial' => 'f',
						'final' => 'f',
						'final_default' => 'f' 
				),
				'DONE' => array (
						'ordering' => 8,
						'label' => 'done',
						'description' => 'Final status',
						'initial' => 'f',
						'final' => 't',
						'final_default' => 't' 
				) 
		);
		
		$count_status_order_array = count ( $status_order_array );
		
		if ($count_status_order_array == 0) {
			foreach ( $status_array as $key => $value ) {
				$save_data = array ();
				
				$save_data ['id_var'] = $id_var;
				$save_data ['key'] = $key;
				$save_data ['ordering'] = $value ['ordering'];
				$save_data ['label'] = $value ['label'];
				$save_data ['description'] = $value ['description'];
				$save_data ['initial'] = $value ['initial'];
				$save_data ['final'] = $value ['final'];
				$save_data ['final_default'] = $value ['final_default'];
				$save_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
				$save_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
				
				$this->db->insert ( 'setup_vars_values', $save_data );
			}
		} else {
			$i = 0;
			foreach ( $status_array as $key => $value ) {
				$order_array [] = $value->ordering;
				
				if (! array_key_exists ( $key, $status_order_array )) {
					$save_data = array ();
					$save_data ['id_var'] = $id_var;
					$save_data ['key'] = $key;
					$save_data ['ordering'] = $count_status_order_array + $i;
					$save_data ['label'] = $value ['label'];
					$save_data ['description'] = $value ['description'];
					$save_data ['initial'] = $value ['initial'];
					$save_data ['final'] = $value ['final'];
					$save_data ['final_default'] = $value ['final_default'];
					$save_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
					$save_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
					
					$this->db->insert ( 'setup_vars_values', $save_data );
					
					$i ++;
				} else {
					
					if ($key == 'NEW') {
						$update_data = array ();
						$update_data ['initial'] = 'f';
						
						$this->db->where ( 'id_var', $id_var )->where ( 'key', $key )->update ( 'setup_vars_values', $update_data );
					}
					
					if ($key == 'DA_PIANIFICARE') {
						$update_data = array ();
						$update_data ['initial'] = 't';
						
						$this->db->where ( 'id_var', $id_var )->where ( 'key', $key )->update ( 'setup_vars_values', $update_data );
					}
				}
			}
		}
	}
	public function add_variable($vardata) {
		if (! $vardata ['disabled']) {
			$var_data ['disabled'] = 'f';
		} else {
			$var_data ['disabled'] = 't';
		}
		$var_data ['id_activity'] = $vardata ['id_activity'];
		$var_data ['id_process'] = $vardata ['id_process'];
		$var_data ['type'] = $vardata ['type'];
		$var_data ['key'] = $vardata ['key'];
		$var_data ['var_label'] = $vardata ['var_label'];
		$var_data ['layout'] = $vardata ['layout'];
		$var_data ['ordering'] = 0;
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
		$var_data ['var_label'] = $vardata ['var_label'];
		$var_data ['layout'] = $vardata ['layout'];
		$var_data ['description'] = $vardata ['description'];
		$var_data ['modified'] = date ( 'Y-m-d H:i:s' );
		$var_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		if ($this->db->where ( 'id', $vid )->update ( 'setup_vars', $var_data )) {
			if (! empty ( $vardata ['status_key'] )) {
				$update_var_values = $this->edit_var_values ( $vardata, $vid );
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
	public function edit_var_values($data, $varid) {
		$keycount = count ( $data ['status_key'] );
		for($i = 0; $i < $keycount; $i ++) {
			if ($data ['ordering'] [$i] == '') {
				$values_data ['ordering'] = '0';
			} else {
				$values_data ['ordering'] = $data ['ordering'] [$i];
			}
			$valid = $data ['val_id'] [$i];
			
			if ($data ['status_key'] [$i] == 'NEW') {
				$values_data ['initial'] = 't';
			} elseif ($data ['status_key'] [$i] == 'DA_PIANIFICARE') {
				$values_data ['initial'] = 'f';
			} else {
				$values_data ['initial'] = $data ['hidden_initial'] [$i];
			}
			$values_data ['key'] = $data ['status_key'] [$i];
			$values_data ['label'] = $data ['label'] [$i];
			$values_data ['description'] = $data ['status_description'] [$i];
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
	public function get_unused_attachments($id_activity) {
		$query = $this->db->query ( "SELECT  l.id,l.title FROM setup_attachments l WHERE  NOT EXISTS (SELECT 1 FROM   setup_forms_attachments i WHERE  l.id = i.id_attachment AND i.form_id = '$id_activity')" );
		if ($query->num_rows () > 0) {
			$result ['status'] = TRUE;
			$result ['data'] = $query->result ();
		} else {
			$result ['status'] = FALSE;
		}
		return $result;
	}
	public function get_activity_attachments($id) {
		$query = $this->db->select ( 'l.*, a.title,a.id as attach_id' )->join ( 'setup_attachments a', 'a.id = l.id_attachment', 'left' )->where ( 'form_id', $id )->get ( 'setup_forms_attachments l' );
		return $query->result ();
	}
	public function delete_attachment($id) {
		if ($this->db->where ( 'id', $id )->delete ( 'setup_forms_attachments' )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' It has been deleted successfully' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'It was an error. Please try again.' );
			return false;
		}
	}
	public function update_attach_values($data, $id_activity) {
		$keycount = count ( $data ['id_attachment'] );
		for($i = 0; $i < $keycount; $i ++) {
			$valid = $data ['attach_id'] [$i];
			$attach_data ['form_id'] = $id_activity;
			$attach_data ['id_attachment'] = $data ['id_attachment'] [$i];
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
				$this->db->where ( 'form_id', $id_activity )->where ( 'id', $valid )->update ( 'setup_forms_attachments', $attach_data );
			}
		}
	}
	public function check_attach_duplicate($id_activity, $id_attachment) {
		$query = $this->db->where ( 'form_id', $id_activity )->where ( 'id_attachment', $id_attachment )->get ( 'setup_forms_attachments' );
		if ($query->num_rows () > 0) {
			return FALSE;
		}
		return TRUE;
	}
	public function get_single_attachment($id) {
		$query = $this->db->select ( 'l.*, a.title,a.id as attach_id' )->join ( 'setup_attachments a', 'a.id = l.id_attachment', 'left' )->where ( 'l.id', $id )->get ( 'setup_forms_attachments l' );
		return $query->row ();
	}
	public function edit_attachment($data) {
		$values_data ['required'] = $data ['hidden_required'];
		if ($this->db->where ( 'form_id', $data ['id_activity'] )->where ( 'id', $data ['id'] )->update ( 'setup_forms_attachments', $values_data )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' Record has been updated correctly.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
			return false;
		}
	}
	public function get_activity_exit_scenarios($id) {
		$query = $this->db->where ( 'id_activity', $id )->get ( 'setup_activities_exits' );
		return $query->result ();
	}
	public function add_scenario($vardata) {
		if (isset ( $vardata ['disabled'] )) {
			$var_data ['disabled'] = 't';
		} else {
			$var_data ['disabled'] = 'f';
		}
		$var_data ['code'] = $this->random_string ( 6 );
		$var_data ['id_activity'] = $vardata ['id_activity'];
		$var_data ['title'] = $vardata ['title'];
		$var_data ['condition'] = $vardata ['condition'];
		$var_data ['description'] = $vardata ['description'];
		$var_data ['actions'] = $vardata ['actions'];
		$var_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
		$var_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		if ($this->db->insert ( 'setup_activities_exits', $var_data )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', 'Record  has been inserted correctly.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
			return false;
		}
	}
	public function get_scenario($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'setup_activities_exits' );
		return $query->row ();
	}
	public function edit_scenario($vardata) {
		if (isset ( $vardata ['disabled'] )) {
			$var_data ['disabled'] = 't';
		} else {
			$var_data ['disabled'] = 'f';
		}
		$vid = $vardata ['sceneid'];
		$var_data ['title'] = $vardata ['title'];
		$var_data ['condition'] = $vardata ['condition'];
		$var_data ['description'] = $vardata ['description'];
		$var_data ['actions'] = $vardata ['actions'];
		$var_data ['modified'] = date ( 'Y-m-d H:i:s' );
		$var_data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		if ($this->db->where ( 'id', $vid )->update ( 'setup_activities_exits', $var_data )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' Record has been updated correctly.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, please try again.' );
			return false;
		}
	}
	public function delete_scenario($id) {
		if ($this->db->where ( 'id', $id )->delete ( 'setup_activities_exits' )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' It has been deleted successfully' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'It was an error. Please try again.' );
			return false;
		}
	}
	public function random_string($length) {
		$key = '';
		$keys = range ( 0, 9 );
		
		for($i = 0; $i < $length; $i ++) {
			$key .= $keys [array_rand ( $keys )];
		}
		return $key;
	}
	public function update_ordering($neworder, $actid) {
		$ordering ['ordering'] = $neworder;
		if ($this->db->where ( 'id', $actid )->update ( 'setup_activities', $ordering )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			return false;
		}
	}
	public function get_single_by_key($key) {
		$query = $this->db->where ( 'setup_activities.key', $key )->get ( 'setup_activities' );
		return $query->row ();
	}
	public function check_associated_activiites($act_id) {
		$act_data = $this->get_single ( $act_id );
		$query = $this->db->where ( 'activities.type', $act_data->key )->get ( 'activities' );
		if ($query->num_rows () > 0) {
			$this->session->set_flashdata ( 'growl_error', 'It was an error. Please try again.' );
			return false;
		}
		return true;
	}
	public function delete_activity($process_id, $act_id) {
		$this->delete_associated_vars ( $process_id, $act_id );
		if ($this->db->where ( 'id', $act_id )->delete ( 'setup_activities' )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', ' It has been deleted successfully' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'It was an error. Please try again.' );
			return false;
		}
	}
	public function delete_associated_vars($process_id, $act_id) {
		$query = $this->db->where ( 'id_process', $process_id )->where ( 'id_activity', $act_id )->get ( 'setup_vars' );
		$data = $query->result ();
		foreach ( $data as $item ) {
			$this->db->where ( 'id_var', $item->id )->delete ( 'setup_vars_values' );
		}
		
		$this->db->where ( 'id_process', $process_id )->where ( 'id_activity', $act_id )->delete ( 'setup_vars' );
	}
	
	/**
	 * set_workorder
	 *
	 * @param array $post        	
	 *
	 * @return boolean
	 *
	 * @author Sumesh
	 */
	public function set_workorder($post) {
		$is_workorder = $post ['is_workorder'];
		$report_type_id = $post ['report_type'];
		$setup_activty_id = $post ['setup_activty_id'];
		
		$workorder_data ['workorder'] = $is_workorder;
		$workorder_data ['onsite_report_id'] = $report_type_id;
		
		if ($this->db->where ( 'id', $setup_activty_id )->update ( 'setup_activities', $workorder_data )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * get_setup_activity_details
	 *
	 * @param integer $thread_id        	
	 * @param string $setup_activity_key        	
	 *
	 * @return string
	 *
	 * @author Sumesh
	 */
	public function get_setup_activity_details($thread_id, $setup_activity_key) {
		$query = $this->db->select ( 'setup_activities.is_workorder' )->join ( 'setup_processes', 'setup_processes.id = setup_activities.id_process' )->join ( 'threads', 'threads.type = setup_processes.key' )->where ( 'setup_activities.key', $setup_activity_key )->where ( 'threads.id', $thread_id )->get ( 'setup_activities' );
		
		$data = $query->result ();
		
		if (count ( $data ) > 0) {
			return $data [0]->is_workorder;
		} else {
			return 'f';
		}
	}
	
	// For getting the initails status of particular activity
	public function get_initial_status($id_activity = NULL, $flag = NULL, $vid = NULL) {
		if ($flag == 1) {
			$source = 'SYSTEM';
			$type = 'STATUS';
		} else {
			$source = 'CUSTOM';
			$type = NULL;
		}
		$this->db->select ( 'v.key' )->join ( 'setup_vars m', 'm.id = v.id_var', 'left' )->where ( 'm.source', $source )->order_by ( "v.ordering", "asc" );
		if ($id_activity != null)
			$this->db->where ( 'm.id_activity', $id_activity );
		if ($type != NULL)
			$this->db->where ( 'm.type', $type );
		if ($vid != NULL)
			$this->db->where ( 'v.id_var', $vid );
		$this->db->where ( 'v.initial', 't' );
		$query = $this->db->get ( 'setup_vars_values v' );
		
		return $query->row ();
	}
	public function insert_transition($data) {
		if ($data ["master_status"] == '' || $data ["act_id"] == '' || $data ["process_id"] == '') {
			return FALSE;
		}
		
		// get existing from transistion table
		$get_trans = $this->db->select ( "dst_status_key" )->where ( "src_status_key = '" . $data ["master_status"] . "'" )->where ( "id_process_type", $data ["process_id"] )->where ( "id_activity_type", $data ["act_id"] )->get ( "setup_status_transitions" );
		$result_trans = $get_trans->result ();
		$exist_array = array ();
		if (count ( $result_trans ) > 0) {
			foreach ( $result_trans as $item ) {
				$exist_array [] = $item->dst_status_key;
			}
		}
		
		// add in loop
		if (count ( $data ["status"] ) > 0) {
			foreach ( $data ["status"] as $item ) {
				if (! in_array ( $item, $exist_array )) {
					$input_array = array (
							"src_status_key" => $data ["master_status"],
							"id_process_type" => $data ["process_id"],
							"id_activity_type" => $data ["act_id"],
							"dst_status_key" => $item 
					);
					
					$this->db->insert ( "setup_status_transitions", $input_array );
				}
			}
		}
		
		// remove not there ones
		if (count ( $exist_array ) > 0) {
			foreach ( $exist_array as $item ) {
				if (! in_array ( $item, $data ['status'] )) {
					$this->db->where ( "src_status_key = '" . $data ["master_status"] . "'" )->where ( "id_process_type", $data ["process_id"] )->where ( "id_activity_type", $data ["act_id"] )->where ( "dst_status_key = '$item'" )->delete ( "setup_status_transitions" );
				}
			}
		}
		
		return true;
	}
	public function get_all_trasitions($act_id, $process_id, $status) {
		if ($act_id == NULL || $process_id == NULL || $status == NULL) {
			return false;
		}
		
		$get_trans = $this->db->select ( "dst_status_key" )->where ( "src_status_key = '" . $status . "'" )->where ( "id_process_type", $process_id )->where ( "id_activity_type", $act_id )->get ( "setup_status_transitions" );
		$result_trans = $get_trans->result ();
		$exist_array = array ();
		if (count ( $result_trans ) > 0) {
			foreach ( $result_trans as $item ) {
				$exist_array [] = $item->dst_status_key;
			}
		}
		
		return $exist_array;
	}
	public function get_duty_company() {
		$get_company = $this->db->order_by ( "name" )->get ( "companies" );
		$result = $get_company->result ();
		
		if (count ( $result ) > 0) {
			$companies_array = array ();
			$companies_array [""] = "Select duty company";
			foreach ( $result as $item ) {
				$companies_array [$item->id] = $item->name;
			}
			
			return $companies_array;
		} else {
			return array ();
		}
	}
}