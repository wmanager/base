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
class Actions_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
	}
	public function update_var($caller_type, $caller_id, $target_type, $target_id, $data_varval = NULL, $duty = NULL) {
		if (($caller_type != 'ACTIVITY' && $caller_type != 'THREAD' && $caller_type != '') || ($target_type != 'ACTIVITY' && $target_type != 'THREAD' && $target_type != '')) {
			return - 5;
		}
		
		$res = true;
		
		$id_thread = NULL;
		$id_activity = NULL;
		if ($caller_type == 'ACTIVITY' && $target_type == 'ACTIVITY') {
			$this->db->where ( 'id_activity', $target_id );
			$id_activity = $target_id;
		} else if ($caller_type == 'THREAD' && $target_type == 'ACTIVITY') {
			$this->db->where ( 'id_thread', $caller_id )->where ( 'id_activity', $target_id );
			$id_activity = $target_id;
			$id_thread = $caller_id;
		} else if ($caller_type == 'THREAD') {
			$this->db->where ( 'id_thread', $caller_id )->where ( 'id_activity', NULL );
			$id_thread = $caller_id;
		} else if ($target_type == 'THREAD') {
			$this->db->where ( 'id_thread', $target_id )->where ( 'id_activity', NULL );
			$id_thread = $target_id;
		}
		$var_data = $this->db->select ( 'key,id_thread' )->get ( 'vars' );
		
		$data_var = array ();
		foreach ( $var_data->result () as $each_var ) {
			$data_var [] = $each_var->key;
			if ($id_thread == NULL) {
				$id_thread = $each_var->id_thread;
			}
		}
		
		foreach ( $data_varval as $key_new => $new_value ) {
			if ($key_new == 'STATUS') {
				$check = $this->check_predef_values ( $id_thread, $id_activity, $key_new, $new_value );
				if (! $check) {
					return - 2;
				}
			} else if ($key_new == 'RESULT') {
				if ($new_value != 'OK' && $new_value != 'KO') {
					return - 2;
				}
			}
			if (! in_array ( $key_new, $data_var )) {
				
				$data_create ['id_thread'] = $id_thread;
				$data_create ['id_activity'] = $id_activity;
				$data_create ['key'] = $key_new;
				$data_create ['value'] = $new_value;
				$data_create ['created_by'] = $this->ion_auth->user ()->row ()->id;
				$data_create ['modified_by'] = $data_create ['created_by'];
				$this->db->insert ( 'vars', $data_create );
			}
		}
		$data_vars ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		foreach ( $data_varval as $key => $value ) {
			
			if ($caller_type == 'ACTIVITY' && $target_type == 'ACTIVITY') {
				$this->db->where ( 'id_activity', $target_id );
			} else if ($caller_type == 'THREAD' && $target_type == 'ACTIVITY') {
				$this->db->where ( 'id_thread', $caller_id )->where ( 'id_activity', $target_id );
			} else if ($caller_type == 'THREAD') {
				$this->db->where ( 'id_thread', $caller_id )->where ( 'id_activity', NULL );
			} else if ($target_type == 'THREAD') {
				$this->db->where ( 'id_thread', $target_id )->where ( 'id_activity', NULL );
			}
			$data_vars ['modified'] = date ( 'Y-m-d H:i:s' );
			$data_vars ['key'] = $key;
			$data_vars ['value'] = $value;
			$this->db->where ( 'key', $key )->update ( 'vars', $data_vars );
			if ($this->db->affected_rows () <= 0) {
				$res = false;
			}
		}
		if ($res) {
			if ($target_type == 'ACTIVITY') {
				$data_act ['modified_by'] = $this->ion_auth->user ()->row ()->id;
				$data_act ['modified'] = date ( 'Y-m-d H:i:s' );
				$this->db->where ( 'id', $target_id )->update ( 'activities', $data_act );
			} else if ($target_type == 'THREAD') {
				$data_thread ['modified_by'] = $this->ion_auth->user ()->row ()->id;
				$data_thread ['modified'] = date ( 'Y-m-d H:i:s' );
				$this->db->where ( 'id', $target_id )->update ( 'threads', $data_thread );
				$this->session->set_flashdata ( 'growl_success', 'Thread status updated.' );
				$this->session->set_flashdata ( 'growl_show', 'true' );
			}
			
			log_message ( 'DEBUG', $this->db->last_query () );
			// $this->session->set_flashdata('growl_success', $created_message );
			// $this->session->set_flashdata('growl_show', 'true' );
			return 0;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'Error' );
			return - 1;
		}
	}
	public function create_activity($caller_type, $caller_id, $target_type, $data_varval = NULL, $duty = NULL) {
		$id_thread = NULL;
		$id_activity = NULL;
		switch ($caller_type) {
			case 'ACTIVITY' :
				$thread_data = $this->get_thread_id ( $caller_id );
				if (isset ( $thread_data->id_thread )) {
					$id_thread = $thread_data->id_thread;
				}
				$query = $this->db->select ( 'setup_activities.*,setup_roles.parent_role' )->where ( 'threads.id', $id_thread )->join ( 'setup_processes', 'setup_processes.key = threads.type' )->join ( 'setup_activities', 'setup_activities.id_process = setup_processes.id' )->where ( 'setup_activities.key', $target_type )->join ( 'setup_roles', 'setup_roles.key = setup_activities.role' )->order_by ( 'setup_activities.id', 'ASC' )->get ( 'threads' );
				$next_act = $query->row ();
				break;
			case 'THREAD' :
				$query = $this->db->select ( 'setup_activities.*,setup_roles.parent_role' )->where ( 'threads.id', $caller_id )->join ( 'setup_processes', 'setup_processes.key = threads.type' )->join ( 'setup_activities', 'setup_activities.id_process = setup_processes.id' )->where ( 'setup_activities.key', $target_type )->join ( 'setup_roles', 'setup_roles.key = setup_activities.role' )->order_by ( 'setup_activities.id', 'ASC' )->get ( 'threads' );
				$next_act = $query->row ();
				break;
		}
		/*
		 * $system_vars = array('STATUS','RESULT','RESULT_NOTE');
		 * foreach($data_varval as $key => $value){
		 * if(in_array($key,$system_vars)){
		 * $data_createact[strtolower($key)] = $value;
		 * }
		 * }
		 */
		
		$query = $this->db->where ( 'domain', 'ACTIVITY' )->where ( 'disabled', 'f' )->get ( 'setup_default_vars' );
		$system_vars = $query->result ();
		
		switch ($caller_type) {
			case 'ACTIVITY' :
				$thread_data = $this->get_thread_id ( $caller_id );
				if (isset ( $thread_data->id_thread )) {
					$id_thread = $thread_data->id_thread;
				}
				$id_activity = $caller_id;
				$data_createact ['id_thread'] = $id_thread;
				
				break;
			case 'THREAD' :
				$id_thread = $caller_id;
				$data_createact ['id_thread'] = $caller_id;
				
				break;
		}
		
		// check BPM and avoid same actvity twice in case of automatic
		$bpm = $this->get_bpm ( $id_thread );
		
		if ($bpm == 'AUTOMATIC') {
			// check if activity already exist
			$check_repeat = $this->db->where ( "type = '$target_type'" )->where ( "id_thread", $id_thread )->get ( "activities" );
			$check_repeat_result = $check_repeat->result ();
			
			if (count ( $check_repeat_result ) > 0) {
				return - 4;
			}
		}
		
		$var_check = $this->check_variable ( $data_varval, $target_type );
		if ($var_check < 0) {
			return $var_check;
		}
		
		$query = $this->db->select ( 'setup_activities.*' )->where ( 'threads.id', $data_createact ['id_thread'] )->join ( 'setup_processes', 'setup_processes.key = threads.type' )->join ( 'setup_activities', 'setup_activities.id_process = setup_processes.id' )->where ( 'setup_activities.key', $target_type )->get ( 'threads' );
		$activity = $query->row ();
		
		$data_createact ['type'] = $target_type;
		$data_createact ['form_id'] = $activity->form_id;
		// $data_createact['deadline'] = $this->sla->calculate($activity->sla);
		$data_createact ['created'] = date ( 'Y-m-d H:i:s' );
		$data_createact ['created_by'] = $this->ion_auth->user ()->row ()->id;
		$data_createact ['id_contract'] = '1';
		$data_createact ['owner_company'] = '1';
		$data_createact ['creator_company'] = $this->ion_auth->user ()->row ()->id_company;
		$this->db->insert ( 'activities', $data_createact );
		$activity_id = $this->db->insert_id ();
		if ($duty == 'NONE') {
			log_message ( 'DEBUG', 'DUTY NOT ASSIGNED' );
			// DO NOTHING FOR NOW
		} else if ($duty === NULL) {
			
			// INSERT ACTIVITY ACL AUTOMATICALLY
			// $activity_data['duty_user'] = NULL;
			$query = $this->db->select ( 'd.company_id,c.key' )->where ( 'c.key', $next_act->role )->order_by ( "d.id", "asc" )->limit ( 1 )->from ( 'setup_company_roles d' )->join ( 'setup_roles c', 'c.id = d.role_key' )->get ();
			$company = $query->row ();
			$activity_data ['duty_company'] = $company->company_id;
			$activity_data ['duty_user'] = NULL;

			$activity_data ['activities_id'] = $activity_id;
			$activity_data ['role_key'] = $company->key;
			
			// add based on default duty_company in setup_activity
			$activity_setup_details = $this->get_setup_activity_based_thread ( $data_createact ['id_thread'], $data_createact ['type'] );
			if ($activity_setup_details->duty_company != FALSE) {
				$activity_data ['duty_company'] = $activity_setup_details->duty_company;
				$activity_data ['duty_user'] = NULL;
			}
			
			$this->db->insert ( 'activities_acl', $activity_data );

				
			
			
			$query = $this->db->where ( 'threads_id', $row->thread_id )->where ( 'duty_company', $activity_data ['duty_company'] )->where ( 'role_key', $activity_data ['role_key'] )->get ( 'threads_acl' );
			if ($query->num_rows () == 0) {
				$threads_acl ['duty_company'] = $activity_data ['duty_company'];
				$threads_acl ['duty_user'] = $activity_data ['duty_user'];
				$threads_acl ['role_key'] = $activity_data ['role_key'];
				$threads_acl ['threads_id'] = $row->thread_id;
				$threads_acl ['created'] = date ( 'Y-m-d H:i:s' );
				$threads_acl ['created_by'] = $this->ion_auth->user ()->row ()->id;
				$this->db->insert ( 'threads_acl', $threads_acl );
			}
		}

		
		// INSERT DEFAULT SYSTEM VARIABLES
		$data_vars = array ();
		$data_vars ['id_activity'] = $activity_id;
		$data_vars ['id_thread'] = $data_createact ['id_thread'];
		$data_vars ['created_by'] = $this->ion_auth->user ()->row ()->id;
		foreach ( $system_vars as $variable ) {
			$data_vars ['key'] = $variable->key;
			$data_vars ['value'] = NULL;
			if ($variable->key == 'STATUS') {
				$default_value = $this->get_status_default_value ( $target_type );
				if ($default_value)
					$data_vars ['value'] = $default_value;
			}
			$this->db->flush_cache ();
			$this->db->insert ( 'vars', $data_vars );
			$this->db->flush_cache ();
		}
		
		$res = true;
		if (! is_null ( $data_varval )) {
			foreach ( $data_varval as $key => $value ) {
				$data_act ['value'] = $value;
				$response = $this->db->where ( 'key', $key )->where ( 'id_thread', $data_createact ['id_thread'] )->where ( 'id_activity', $activity_id )->update ( 'vars', $data_act );
				if (! $response) {
					$res = false;
				}
			}
		}
		
		// INSERT MAGIC FORM VARIABLES
		$query = $this->db->query ( "select setup_processes.id as process_id,setup_activities.id as setup_act_id from activities join threads ON activities.id_thread=threads.id left join setup_processes ON threads.type=setup_processes.key left join setup_activities ON setup_processes.id= setup_activities.id_process WHERE activities.type = '" . $target_type . "' and setup_activities.key = '" . $target_type . "' AND activities.id = '" . $activity_id . "'" );
		$vars_result = $query->row ();
		// log_message('DEBUG',$this->db->last_query());
		if (count ( $vars_result ) > 0) {
			$query = $this->db->select ( 'key' )->where ( 'id_process', $vars_result->process_id )->where ( 'id_activity', $vars_result->setup_act_id )->where ( 'source', 'CUSTOM' )->where ( 'type', 'MAGIC_FORM' )->where ( 'disabled', 'f' )->get ( 'setup_vars' );
			$magic_vars = $query->result ();
			if (count ( $magic_vars ) > 0) {
				
				$data_vars = array ();
				$data_vars ['id_activity'] = $activity_id;
				$data_vars ['id_thread'] = $data_createact ['id_thread'];
				$data_vars ['created_by'] = $this->ion_auth->user ()->row ()->id;
				foreach ( $magic_vars as $variable ) {
					$data_vars ['key'] = $variable->key;
					$data_vars ['value'] = NULL;
					
					// $default_value = $this->get_status_default_value($target_type);
					// if($default_value) $data_vars['value'] = $default_value;
					
					$this->db->flush_cache ();
					$this->db->insert ( 'vars', $data_vars );
					$this->db->flush_cache ();
				}
			}
		}
		
		if ($res) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', 'Created Successfully' );
			return $activity_id;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'Error' );
			return - 1;
		}
	}
	public function update_duty($caller_type, $caller_id, $target_type, $target_id, $role, $duty_company, $duty_user) {
		$data_duty ['be_id'] = $target_id;
		$data_duty ['role_key'] = $role;
		$data_duty ['duty_company'] = $duty_company;
		$data_duty ['duty_user'] = $duty_user;
		$data_duty ['created_by'] = $this->ion_auth->user ()->row ()->id;
		
		if ($this->db->insert ( 'be_acl', $data_duty )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			return $this->db->insert_id ();
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			return - 1;
		}
	}
	public function add_history($caller_type, $caller_id, $target_type, $target_id, $data_varval = NULL, $action, $session, $response, $exit_code = NULL, $duty_company = NULL, $duty_user = NULL, $trouble_id = NULL) {
		log_message ( 'DEBUG', '==========================================' );
		log_message ( 'DEBUG', 'caller_type =' . $caller_type . ' caller_id=' . $caller_id . ' target_type=' . $target_type . ' target_id=' . $target_id );
		$id_thread = NULL;
		$id_activity = NULL;
		$caller_thread = NULL;
		$caller_activity = NULL;
		if ($caller_type == 'ACTIVITY' && $target_type == 'ACTIVITY') {
			$id_activity = $target_id;
			$caller_activity = $caller_id;
			$id_thread = $this->db->where ( 'id', $target_id )->get ( 'activities' )->row ()->id_thread;
			$caller_thread = $this->db->where ( 'id', $caller_id )->get ( 'activities' )->row ()->id_thread;
		} else if ($caller_type == 'THREAD' && $target_type == 'ACTIVITY') {
			$id_activity = $target_id;
			$id_thread = $this->db->where ( 'id', $id_activity )->get ( 'activities' )->row ()->id_thread;
			$caller_thread = $caller_id;
		} else if ($caller_type == 'THREAD') {
			if ($action == 'create_activity') {
				$id_activity = $target_id;
				$id_thread = $this->db->where ( 'id', $target_id )->get ( 'activities' )->row ()->id_thread;
			} else {
				if ($target_id != NULL)
					$id_thread = $target_id;
				else
					$id_thread = $caller_id;
			}
			$caller_thread = $caller_id;
		} else if ($target_type == 'THREAD') {
			$id_thread = $target_id;
			$caller_activity = $caller_id;
			$caller_thread = $this->db->where ( 'id', $caller_id )->get ( 'activities' )->row ();
			if (isset ( $caller_thread->id_thread ))
				$caller_thread = $caller_thread->id_thread;
			else
				$caller_thread = NULL;
		} else if ($caller_type == 'ACTIVITY') {
			$caller_activity = $caller_id;
			$caller_thread = $this->db->where ( 'id', $caller_id )->get ( 'activities' )->row ();
			if (isset ( $caller_thread->id_thread ))
				$caller_thread = $caller_thread->id_thread;
			else
				$caller_thread = NULL;
			if ($action == 'create_activity') {
				$id_activity = $target_id;
				$id_thread = $this->db->where ( 'id', $target_id )->get ( 'activities' )->row ();
				if (isset ( $id_thread->id_thread ))
					$id_thread = $id_thread->id_thread;
				else
					$id_thread = NULL;
			} else if ($action == 'ENGINE') {
				$id_activity = $caller_id;
				$id_thread = $this->db->where ( 'id', $caller_id )->get ( 'activities' )->row ();
				if (isset ( $id_thread->id_thread ))
					$id_thread = $id_thread->id_thread;
				else
					$id_thread = NULL;
			} else {
				if ($target_id != NULL) {
					$id_thread = $target_id;
				} else {
					$id_thread = $this->db->where ( 'id', $caller_id )->get ( 'activities' )->row ();
					if (isset ( $id_thread->id_thread ))
						$id_thread = $id_thread->id_thread;
					else
						$id_thread = NULL;
				}
			}
		}
		
		switch ($action) {
			case 'create_activity' :
			case 'create_thread' :
				$data_history ['type'] = $target_type;
				break;
		}
		
		switch ($action) {
			case 'create_thread' :
				if ($trouble_id)
					$data_history ['trouble_id'] = $trouble_id;
				break;
		}
		
		log_message ( 'DEBUG', '==========================================' );
		$data_history ['session'] = $session;
		$data_history ['id_activity'] = $id_activity;
		$data_history ['id_thread'] = $id_thread;
		$data_history ['caller_thread'] = $caller_thread;
		$data_history ['caller_activity'] = $caller_activity;
		$data_history ['action'] = $action;
		$data_history ['created_by'] = $this->ion_auth->user ()->row ()->id;
		if ($duty_company != NULL)
			$data_history ['duty_company'] = $duty_company;
		if ($duty_user != NULL)
			$data_history ['duty_user'] = $duty_user;
		log_message ( 'DEBUG', print_r ( $data_history, true ) );
		$CI = & get_instance ();
		$CI->load->helper ( 'catalog' );
		$note = error_catalog ( $response );
		$data_history ['note'] = $note;
		$data_history ['exit_scenario'] = $exit_code;
		$k = 0;
		if ($data_varval != NULL) {
			foreach ( $data_varval as $key => $value ) {
				$data_keyval [$k] = $data_history;
				$data_keyval [$k] ['key'] = $key;
				$data_keyval [$k] ['value'] = $value;
				$k ++;
			}
		} else {
			$data_keyval [$k] = $data_history;
		}
		$looptime = $this->config->item ( 'loop_check_period' );
		$maxrecord = $this->config->item ( 'loop_check_max_records' );
		$next = time () - $looptime;
		$currenttime = date ( "Y-m-d H:i:s" );
		$nexttime = date ( "Y-m-d H:i:s", $next );
		
		$totalhstory = $this->db->where ( 'id_thread', $id_thread )->where ( 'created >=', $nexttime )->where ( 'created <=', $currenttime )->where ( 'session', $session )->get ( 'history' )->num_rows ();
		if ($totalhstory < $maxrecord) {
			if ($this->db->insert_batch ( 'history', $data_keyval )) {
				log_message ( 'DEBUG', $this->db->last_query () );
				// $this->session->set_flashdata('growl_success', 'Inserted Successfully');
				return true;
			} else {
				log_message ( 'ERROR', $this->db->last_query () );
				$this->session->set_flashdata ( 'growl_error', 'Error' );
				return false;
			}
		} else {
			log_message ( 'ERROR', 'loop detected' );
			$this->session->set_flashdata ( 'growl_error', 'Error' );
			return false;
		}
	}
	public function get_activity_type($activity_id, $thread_id = NULL, $process_id = NULL) {
		/*
		 * if($thread_id <> NULL)
		 * $this->db->where('id_thread',$thread_id);
		 *
		 * if($process_id <> NULL)
		 * $this->db->where('setup_activities.id_process',$process_id);
		 *
		 * $query = $this->db->select('activities.type, setup_activities.id as activity_type')->join('setup_activities','setup_activities.key = activities.type')->where('activities.id',$activity_id)->get('activities');
		 * return $query->row();
		 */
		
		// this section gets thread id
		$query = $this->db->select ( 'id_thread' )->where ( 'id', $activity_id )->get ( 'activities' );
		$result = $query->row ();
		$thread_id = $result->id_thread;
		
		// get activity_type
		$query = $this->db->query ( 'select sa.id as activity_type from setup_activities sa JOIN setup_processes sp ON sa.id_process=sp.id JOIN threads t ON t.type=sp.key JOIN activities a ON a.id_thread=t.id where a.id=' . $activity_id . ' and t.id=' . $thread_id . ' and sa.key = (select aa.type from activities aa where aa.id= ' . $activity_id . ')' );
		return $query->row ();
	}
	public function get_thread_type($thread_id) {
		$query = $this->db->select ( 'threads.type, setup_processes.id as process_type' )->join ( 'setup_processes', 'setup_processes.key = threads.type' )->where ( 'threads.id', $thread_id )->get ( 'threads' );
		return $query->row ();
	}
	public function get_setup_vars($activity_type, $key, $value) {
		$query = $this->db->select ( 'setup_vars.id, setup_vars_values.key as pre_def_value' )->join ( 'setup_vars_values', 'setup_vars_values.id_var = setup_vars.id' )->where ( 'setup_vars.id_activity', $activity_type )->where ( 'setup_vars.type', $key )->where ( 'setup_vars.source', 'SYSTEM' )->where ( 'setup_vars_values.key', $value )->get ( 'setup_vars' );		
		return $query->num_rows ();
	}
	public function get_setup_thread_vars($thread_type, $key, $value) {
		$query = $this->db->select ( 'setup_vars.id, setup_vars_values.key as pre_def_value' )->join ( 'setup_vars_values', 'setup_vars_values.id_var = setup_vars.id' )->where ( 'setup_vars.id_activity', NULL )->where ( 'setup_vars.id_process', $thread_type )->where ( 'setup_vars.type', $key )->where ( 'setup_vars.source', 'SYSTEM' )->where ( 'setup_vars_values.key', $value )->get ( 'setup_vars' );
		return $query->num_rows ();
	}
	public function check_predef_values($thread_id, $activity_id, $key, $value) {
		if ($activity_id != NULL) {
			$activity_type = $this->get_activity_type ( $activity_id, $thread_id );
			if (isset ( $activity_type->activity_type )) {
				$pre_values_check = $this->get_setup_vars ( $activity_type->activity_type, $key, $value );
				if ($pre_values_check > 0) {
					return true;
				}
			}
			return false;
		} else {
			$thread_type = $this->get_thread_type ( $thread_id );
			if (isset ( $thread_type->process_type )) {
				$pre_values_check = $this->get_setup_thread_vars ( $thread_type->process_type, $key, $value );
				if ($pre_values_check > 0) {
					return true;
				}
			}
			return false;
		}
	}
	public function get_thread_id($activity_id) {
		$query = $this->db->where ( 'id', $activity_id )->get ( 'activities' );
		return $query->row ();
	}
	public function get_related_id($activity, $thread) {
		$query = $this->db->where ( 'type', $activity )->where ( 'id_thread', $thread )->get ( 'activities' );
		$act = $query->row ();
		return $act->id;
	}
	public function check_variable($data_varval = NULL, $type) {
		if (is_array ( $data_varval )) {
			foreach ( $data_varval as $key_new => $new_value ) {
				if ($key_new == 'STATUS' || $key_new == 'RESULT') {
					$setup_activity_data = $this->get_setup_activity ( $type );
					if (isset ( $setup_activity_data->id )) {
						$check_data = $this->get_setup_vars( $setup_activity_data->id, $key_new, $new_value );
						if ($check_data <= 0) {
							return - 2;
						}
					} else {
						return - 3;
					}
				}
			}
		}
		return 0;
	}
	public function get_setup_activity($type) {
		$query = $this->db->where ( 'key', $type )->get ( 'setup_activities' );
		return $query->row ();
	}
	public function get_exit_scenario($activity_id, $thread_id) {
		$query = $this->db->select ( 'setup_processes.id' )->where ( 'threads.id', $thread_id )->join ( 'setup_processes', 'setup_processes.key = threads.process' )->get ( 'threads' );
		$process = $query->row ();
		
		$activity_type = $this->get_activity_type ( $activity_id, NULL, $process->id );
		if (isset ( $activity_type->activity_type )) {
			$query = $this->db->where ( 'id_activity', $activity_type->activity_type )->get ( 'setup_activities_exits' );
			return $query->result ();
		}
		return - 1;
	}
	public function get_var_value($activity_id, $key) {
		$query = $this->db->select ( 'vars.value' )->where ( 'id_activity', $activity_id )->where ( 'key', $key )->get ( 'vars' );
		log_message ( 'DEBUG', '-----------------------------------------------------------------' );
		log_message ( 'DEBUG', $this->db->last_query () );
		log_message ( 'DEBUG', '-----------------------------------------------------------------' );
		return $query->row ();
	}
	public function get_var_value_thread($thread_id, $key) {
		$query = $this->db->select ( 'vars.value' )->where ( 'id_activity', NULL )->where ( 'id_thread', $thread_id )->where ( 'key', $key )->get ( 'vars' );
		log_message ( 'DEBUG', '-----------------------------------------------------------------' );
		log_message ( 'DEBUG', $this->db->last_query () );
		log_message ( 'DEBUG', '-----------------------------------------------------------------' );
		return $query->row ();
	}
	public function get_status_default_value($activity_type) {
		$setup_activity_data = $this->get_setup_activity ( $activity_type );
		if (isset ( $setup_activity_data->id )) {
			$query = $this->db->select ( 'setup_vars.id, setup_vars_values.key as pre_def_value' )->join ( 'setup_vars_values', 'setup_vars_values.id_var = setup_vars.id' )->where ( 'setup_vars.id_activity', $setup_activity_data->id )->where ( 'setup_vars.type', 'STATUS' )->where ( 'setup_vars.source', 'SYSTEM' )->where ( 'setup_vars_values.initial', 't' )->get ( 'setup_vars' );
			return $query->row ()->pre_def_value;
		}
	}
	public function get_setup_var($activity_id) {
		$vars_id = $this->db->where ( 'id_activity', $activity_id )->where ( 'key', 'STATUS' )->where ( 'type', 'STATUS' )->get ( 'setup_vars' )->row ()->id;
		return $vars_id;
	}
	public function get_setup_vars_values($key, $vars_id) {
		$query = $this->db->select ( 'initial,final,final_default' )->where ( 'key', $key )->where ( 'id_var', $vars_id )->get ( 'setup_vars_values' );
		return $query->row ();
	}
	public function check_exit_code($id_thread, $exit_code) {
		$query = $this->db->where ( 'id_thread', $id_thread )->where ( 'action', 'ENGINE' )->where ( 'exit_scenario', $exit_code )->get ( 'history' );
		return $query->num_rows ();
	}
	public function create_thread($process_key, $account_id, $be_id, $duty, $trouble_id = NULL, $draft) {
		$type = $this->get_setup_process ( $process_key );
		
		$process = $this->get_macro_single ( $type->id_mp );
		if ($trouble_id == '')
			$trouble_id = NULL;
		
		$sla = $this->sla->calculate ( $type->sla );
		$data = array (
				'customer' => $account_id,
				'be' => $be_id,
				// 'hard_deadline' => $sla,
				// 'deadline' => $sla,
				'title' => $type->title,
				'process' => $process->mp,
				'type' => $process_key,
				'created_by' => $this->ion_auth->user ()->row ()->id,
				'created' => date ( 'Y-m-d H:i:s' ),
				'trouble_id' => $trouble_id,
				'draft' => $draft
		);
		if ($be_id == '')
			$data ['be'] = NULL;
		
		if ($query = $this->db->insert ( 'threads', $data )) {
			$thread_id = $this->db->insert_id ();
			
			if ($duty != 'NONE') {
				// INSERT THREAD ACL
				if ($process_key == 'RICHIESTA_TEE') {
					$query = $this->db->select ( 'd.company_id,c.key' )->where ( 'c.key', 'PT-PERMITTING' )->order_by ( "d.id", "asc" )->from ( 'setup_company_roles d' )->join ( 'setup_roles c', 'c.id = d.role_key' )->get ();
				} else {
					$query = $this->db->select ( 'd.company_id,c.key' )->where ( 'c.key', 'CRM' )->order_by ( "d.id", "asc" )->from ( 'setup_company_roles d' )->join ( 'setup_roles c', 'c.id = d.role_key' )->get ();
				}
				$company = $query->row ();
				$threads_data ['threads_id'] = $thread_id;
				$threads_data ['role_key'] = $company->key;
				$threads_data ['duty_company'] = $company->company_id;
				$threads_data ['duty_user'] = NULL;
				$threads_data ['created_by'] = $this->ion_auth->user ()->row ()->id;
				$this->db->insert ( 'threads_acl', $threads_data );
			}
			
			$query = $this->db->where ( 'domain', 'PROCESS' )->where ( 'disabled', 'f' )->get ( 'setup_default_vars' );
			$system_vars = $query->result ();
			
			// INSERT DEFAULT SYSTEM VARIABLES
			$data_vars = array ();
			$data_vars ['id_activity'] = NULL;
			$data_vars ['id_thread'] = $thread_id;
			$data_vars ['created_by'] = $this->ion_auth->user ()->row ()->id;
			foreach ( $system_vars as $variable ) {
				$data_vars ['key'] = $variable->key;
				$data_vars ['value'] = NULL;
				if ($variable->key == 'STATUS') {
					$default_value = $this->get_status_default_value_thread ( $process_key );
					if ($default_value)
						$data_vars ['value'] = $default_value;
				}
				$this->db->flush_cache ();
				$this->db->insert ( 'vars', $data_vars );
				$this->db->flush_cache ();
			}
			return $thread_id;
		} else {
			return - 1;
		}
	}
	public function get_status_default_value_thread($process_type) {
		$setup_process_data = $this->get_setup_process ( $process_type );
		if (isset ( $setup_process_data->id )) {
			$query = $this->db->select ( 'setup_vars.id, setup_vars_values.key as pre_def_value' )->join ( 'setup_vars_values', 'setup_vars_values.id_var = setup_vars.id' )->where ( 'setup_vars.id_process', $setup_process_data->id )->where ( 'setup_vars.id_activity', NULL )->where ( 'setup_vars.type', 'STATUS' )->where ( 'setup_vars.source', 'SYSTEM' )->where ( 'setup_vars_values.initial', 't' )->get ( 'setup_vars' );
			return $query->row ()->pre_def_value;
		}
	}
	public function get_setup_process($type) {
		$query = $this->db->where ( 'key', $type )->get ( 'setup_processes' );
		return $query->row ();
	}
	public function get_macro_single($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'setup_mps' );
		return $query->row ();
	}
	public function checkActivity($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'activities' );
		if ($query->num_rows () > 0)
			return $id;
		else
			return false;
	}
	public function checkActivityType($type, $thread_id) {
		$query = $this->db->select ( 'a.id as act_id' )->where ( 'a.id_thread', $thread_id )->where ( 'a.type', $type )->order_by ( 'a.id', 'desc' )->limit ( 1, 0 )->get ( 'activities a' );
		if ($query->num_rows () > 0)
			return $query->row ()->act_id;
		else
			return false;
	}
	public function get_Beid($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'threads' );
		if ($query->num_rows () > 0) {
			log_message ( 'DEBUG', $this->db->last_query () );
			return $query->row ()->be;
		} else {
			return false;
		}
	}

	public function Set_Status_Be($BEID, $status, $status_detail) {
		$res = true;
		$data = array (
				'be_status' => $status,
				'modified' => date ( 'Y-m-d H:i:s' ) 
		);
		$response = $this->db->where ( 'id', $BEID )->update ( 'be', $data );
		log_message ( 'DEBUG', $this->db->last_query () );
		if (! $response) {
			$res = false;
		}
		if ($res) {
			$this->session->set_flashdata ( 'growl_success', 'Updated Successfully' );
			return 0;
		} else {
			$this->session->set_flashdata ( 'growl_error', 'Error' );
			return - 1;
		}
	}

	public function Set_Satus_Thread($THREADID, $status, $status_detail) {
		$res = true;
		$data = array (
				'status' => $status,
				'status_detail' => $status_detail,
				'status_modified' => date ( 'Y-m-d H:i:s' ) 
		);
		$response = $this->db->where ( 'id', $THREADID )->update ( 'threads', $data );
		if (! $response) {
			$res = false;
		}
		if ($res) {
			$this->session->set_flashdata ( 'growl_success', 'Updated Successfully' );
			return 0;
		} else {
			$this->session->set_flashdata ( 'growl_error', 'Error' );
			return - 1;
		}
	}
	public function Set_Status_Activity($ACTIVITYID, $status, $status_detail) {
		$res = true;
		$data = array (
				'status' => $status,
				'status_detail' => $status_detail,
				'status_modified' => date ( 'Y-m-d H:i:s' ) 
		);
		$response = $this->db->where ( 'id', $ACTIVITYID )->update ( 'activities', $data );
		if (! $response) {
			$res = false;
		}
		if ($res) {
			$this->session->set_flashdata ( 'growl_success', 'Updated Successfully' );
			return 0;
		} else {
			$this->session->set_flashdata ( 'growl_error', 'Error' );
			return - 1;
		}
	}
	public function Set_Status_Trouble($TROUBLEID, $status, $status_result = NULL) {
		$res = true;
		$data = array (
				'status' => $status,
				// 'status_detail' => $status_detail,
				'modified' => date ( 'Y-m-d H:i:s' ) 
		);
		
		if ($status == 'CANCELED') {
			$data ['result'] = 'KO';
		}
		if ($status_result) {
			$data ['result'] = $status_result;
		}
		$response = $this->db->where ( 'id', $TROUBLEID )->update ( 'troubles', $data );
		log_message ( 'DEBUG', $this->db->last_query () );
		if (! $response) {
			$res = false;
		}
		if ($res) {
			$this->session->set_flashdata ( 'growl_success', 'Updated Successfully' );
			return 0;
		} else {
			$this->session->set_flashdata ( 'growl_error', 'Error' );
			return - 1;
		}
	}


	public function add_note($thread_id, $activityID, $text, $date) {
		$add_data = array (
				'type' => 'FOLLOWUP',
				'title' => 'New Followup',
				'description' => $text 
		);
		
		if ($thread_id) {
			$add_data ['thread_id'] = $thread_id;
		}
		
		if ($activityID) {
			$add_data ['activity_id'] = $activityID;
		}
		
		if ($date) {
			$add_data ['start_day'] = $date;
		}
		
		if ($thread_id) {
			$query = $this->db->insert ( "memos", $add_data );
		}
		if ($query) {
			return $this->db->insert_id ();
		} else {
			return false;
		}
	}
	public function get_process_for_trouble_types($type) {
		$query = $this->db->where ( 'trouble_type', $type )->where ( 'autocreate', 'TRUE' )->get ( 'setup_troubles_types_2_processes_types' );
		
		return $query->result ();
	}
	public function get_activity_id($type) {
		$query = $this->db->select ( 'id' )->where ( 'key', $type )->get ( 'setup_activities' );
		// print_r($query->row());exit;
		$res = $query->row ();
		return $res->id;
	}
	public function get_thread_details($trouble_id = NULL) {
		$thread_fetch = $this->db->select ( "threads.id,threads.status,threads.process,threads.type" )->where ( "trouble_id", $trouble_id )->where ( "status = 'APERTO'" )->get ( "threads" );
		$res = $thread_fetch->result_array ();
		if (count ( $res ) > 0) {
			foreach ( $res as $key => $item ) {
				$get_act_details = $this->db->select ( "activities.id,activities.type,activities.status as master_status,vars.value as vars_status" )->join ( "vars", "vars.id_activity = activities.id" )->where ( "activities.id_thread", $item ['id'] )->where ( "vars.key", "STATUS" )->get ( "activities" );
				$res [$key] ['act_details'] = $get_act_details->result_array ();
			}
			return $res;
		} else {
			return false;
		}
	}
	public function get_setup_vars_initial_status($act_type = NULL, $thread_type = NULL) {
		if ($act_type == NULL || $thread_type == NULL) {
			return false;
		}
		// get vars details
		$get_vars = $this->db->select ( "setup_vars.id" )->join ( "setup_activities", "setup_activities.id = setup_vars.id_activity" )->join ( "setup_processes", "setup_processes.id = setup_vars.id_process" )->where ( "setup_vars.type", "STATUS" )->where ( "setup_vars.key", "STATUS" )->where ( "setup_activities.key", $act_type )->where ( "setup_processes.key", $thread_type )->get ( "setup_vars" );
		$result = $get_vars->row ();
		$vars_id = $result->id;
		
		if ($vars_id != '') {
			// get status from setup_vars_value
			$get_status = $this->db->select ( "key" )->where ( "id_var", $vars_id )->where ( "initial = 't'" )->get ( "setup_vars_values" );
			$result = $get_status->row ();
			$vars_status = $result->key;
			
			return $vars_status;
		} else {
			return FALSE;
		}
	}
	public function get_all_thread_details($thread_id = NULL) {
		$thread_fetch = $this->db->select ( "threads.id,threads.status,threads.process,threads.type,threads.trouble_id" )->where ( "id", $thread_id )->where ( "status = 'APERTO'" )->get ( "threads" );
		$res = $thread_fetch->row ();
		
		if (isset ( $res->id ) && $res->id != '') {
			$get_act_details = $this->db->select ( "activities.id,activities.type,activities.status as master_status,vars.value as vars_status" )->join ( "vars", "vars.id_activity = activities.id" )->where ( "activities.id_thread", $item->id )->where ( "vars.key", "STATUS" )->get ( "activities" );
			$res->act_details = $get_act_details->result_array ();
		}
		
		return $res;
	}
	public function auto_activity_master_update($activity_id = NULL) {
		
		// step 1: get thread and process
		$get_thread_details = $this->db->select ( "threads.id,threads.type as thread_type,vars.value as status,activities.type as act_type,setup_processes.id as process_id,setup_activities.id as act_type_id" )->join ( "threads", "threads.id = activities.id_thread" )->join ( "vars", "vars.id_activity = activities.id", "left" )->join ( "setup_processes", "setup_processes.key = threads.type" )->join ( "setup_activities", "setup_activities.id_process = setup_processes.id" )->where ( "setup_activities.key = activities.type" )->where ( "vars.key = 'STATUS'" )->where ( "activities.id", $activity_id )->get ( "activities" );
		
		$thread_details = $get_thread_details->row ();
		
		if (count ( $thread_details ) == 0) {
			return array (
					"result" => false,
					"errors" => "activity not found" 
			);
		}
		
		// step 2: get vars status from setup_vars_values
		$get_vars_details = $this->db->select ( "setup_vars.id,setup_vars_values.key,setup_vars_values.initial,setup_vars_values.final" )->join ( "setup_vars_values", "setup_vars_values.id_var = setup_vars.id" )->where ( "setup_vars.id_process", $thread_details->process_id )->where ( "setup_vars.id_activity", $thread_details->act_type_id )->where ( "setup_vars.key = 'STATUS'" )->where ( "setup_vars_values.key", $thread_details->status )->get ( "setup_vars" );
		
		$vars_details = $get_vars_details->row ();
		
		if (count ( $vars_details ) == 0) {
			return array (
					"result" => false,
					"errors" => "vars status not found" 
			);
		}
		
		// step 3: select status
		$status = '';
		if ($vars_details->initial == 't') {
			$status = 'APERTO';
		}
		
		if ($vars_details->final == 't') {
			$status = 'CHIUSO';
		}
		
		if ($vars_details->final != 't' && $vars_details->initial != 't') {
			$status = 'WIP';
		}
		
		// step 4: update
		$data_array = array (
				"status" => $status 
		);
		$update = $this->db->where ( "activities.status != 'CANCELED'" )->where ( "activities.id", $activity_id )->update ( "activities", $data_array );
		
		if ($update) {
			return array (
					"result" => true,
					"errors" => null 
			);
		} else {
			return array (
					"result" => false,
					"errors" => "update failed as cancelled" 
			);
		}
	}
	public function get_setup_activities($activity_id = NULL) {
		if ($activity_id == NULL) {
			return false;
		}
		
		$get_thread_details = $this->db->select ( "activities.id,activities.type,threads.type as thread_type,threads.id" )->join ( "threads", "threads.id = activities.id_thread" )->where ( "activities.id", $activity_id )->get ( "activities" );
		$thread_details = $get_thread_details->row ();
		
		$get_process_details = $this->db->select ( "setup_activities.*" )->join ( "setup_activities", "setup_activities.id_process = setup_processes.id" )->where ( "setup_activities.key = '$thread_details->type'" )->where ( "setup_processes.key = '$thread_details->thread_type'" )->get ( "setup_processes" );
		$setup_activities = $get_process_details->row ();
		
		if (count ( $setup_activities ) > 0) {
			return $setup_activities;
		} else {
			return FALSE;
		}
	}
	public function get_billing_details($activity_id = NULL) {
		if ($activity_id == NULL) {
			return FALSE;
		}
		
		$get_details = $this->db->select ( "activities.id,activities.id_thread,threads.be,threads.customer" )->join ( "threads", "threads.id = activities.id_thread" )->where ( "activities.id = $activity_id" )->get ( "activities" );
		$cust_details = $get_details->row ();
		
		return $cust_details;
	}
	public function get_setup_activity_based_thread($thread_id, $type) {
		
		// get process_id
		$get_process_id = $this->db->select ( "setup_processes.id" )->join ( "setup_processes", "setup_processes.key = threads.type" )->where ( "threads.id", $thread_id )->get ( "threads" );
		$process_id = $get_process_id->row ()->id;
		
		// get setup_activities
		$get_setup_activities = $this->db->where ( "id_process", $process_id )->where ( "key", $type )->get ( "setup_activities" );
		$setup_activities = $get_setup_activities->row ();
		
		if (count ( $setup_activities ) > 0) {
			return $setup_activities;
		} else {
			return FALSE;
		}
	}

	public function add_engine_history($caller, $act_id, $type, $session, $exit_code) {
		
		// get_thread values
		$thread_query = $this->db->select ( "*" )->where ( "id", $act_id )->get ( "activities" );
		$result = $thread_query->row ();
		
		$thread_id = $result->id_thread;
		
		$history_data = array (
				"session" => $session,
				"id_thread" => $thread_id,
				"id_activity" => $act_id,
				"caller_activity" => $act_id,
				"caller_thread" => $thread_id,
				"action" => $type,
				"created_by" => $this->ion_auth->user ()->row ()->id,
				"exit_scenario" => $exit_code 
		);
		
		if ($this->db->insert ( "history", $history_data )) {
			$insert_id = $this->db->insert_id ();
			return $insert_id;
		} else {
			return 0;
		}
	}
	public function get_last_engine_history($caller, $act_id = NULL, $exit_code = NULL) {
		// get_thread values
		$thread_query = $this->db->select ( "*" )->where ( "id", $act_id )->get ( "activities" );
		$result = $thread_query->row ();
		$thread_id = $result->id_thread;
		
		// get history id
		if ($thread_id != '') {
			$this->db->where ( "id_thread", $thread_id );
		}
		
		if ($exit_code != NULL) {
			$this->db->where ( "exit_scenario", $exit_code );
		}
		
		$query = $this->db->select ( "*" )->where ( "action = 'ENGINE'" )->where ( "id_activity", $act_id )->order_by ( "id", "DESC" )->get ( "history" );
		$result = $query->row ();
		
		if (count ( $result ) > 0) {
			return $result->id;
		} else {
			return 0;
		}
	}
	public function get_trouble_customer($customer_id, $be_id, $cmp_id) {
		$query = $this->db->select ( "troubles.status" )->where ( "customer_id", $customer_id )->where ( "be_id", $be_id )->where ( "campagna_id", $cmp_id )->get ( "troubles" );
		$result = $query->row ();
		return $result;
	}
	public function Set_Parent_Trouble_Status($THREADID, $status, $status_detail) {
		$result = $this->get_thread_trouble_details ( $THREADID );
		if (count ( $result ) == 0) {
			return array (
					'status' => 0,
					'message' => 'Not existing. No associated troubles found' 
			);
		}
		$trouble_id = $result->trouble_id;
		
		if ($status == 'CHIUSO')
			$status = 'DONE';
		if ($status == 'DONE') {
			$trouble = $this->get_trouble_details ( $THREADID, $trouble_id );
			if ($trouble) {
				$this->Set_Status_Trouble ( $trouble_id, $status );
				return array (
						'status' => 0,
						'message' => 'updated- parent trouble found and updated' 
				);
			} else {
				return array (
						'status' => 0,
						'message' => 'Not updated- parent trouble found but not updated' 
				);
			}
		} else {
			$this->Set_Status_Trouble ( $trouble_id, $status );
			return array (
					'status' => 0,
					'message' => 'updated- parent trouble found and updated' 
			);
		}
	}
	public function Set_Parent_Trouble_Result($THREADID, $status) {
		$result = $this->get_thread_trouble_details ( $THREADID );
		if (count ( $result ) == 0) {
			return array (
					'status' => 0,
					'message' => 'Not existing. No associated troubles found' 
			);
		}
		
		$trouble_id = $result->trouble_id;
		$trouble_status = $result->status;
		if ($trouble_status == 'DONE' || $trouble_status == 'ANNULATO') {
			$res = true;
			$data = array (
					'result' => $status,
					// 'status_detail' => $status_detail,
					'modified' => date ( 'Y-m-d H:i:s' ) 
			);
			
			if ($this->db->where ( 'id', $trouble_id )->update ( 'troubles', $data )) {
				return array (
						'status' => 0,
						'message' => 'Updated - Result updated' 
				);
			} else {
				return array (
						'status' => 0,
						'message' => 'Not updated - DB error' 
				);
			}
		} else {
			return array (
					'status' => 0,
					'message' => 'Not updated - Trouble is open' 
			);
		}
	}
	public function get_thread_trouble_details($thread_id) {
		$query = $this->db->select ( "threads.trouble_id, troubles.status" )->join ( "troubles", "troubles.id = threads.trouble_id" )->where ( "threads.id", $thread_id )->get ( "threads" );
		$result = $query->row ();
		if (count ( $result ) > 0) {
			return $result;
		} else {
			return array ();
		}
	}
	public function get_trouble_details($THREADID, $trouble_id) {
		$query = $this->db->select ( "threads.status" )->
		// ->where("threads.id !=",$THREADID)
		where ( "threads.status", 'OPEN' )->where ( "threads.trouble_id", $trouble_id )->get ( "threads" );
		$result = $query->row ();
		if (count ( $result ) > 0) {
			return false;
		} else {
			return true;
		}
	}
	public function get_bpm($thread_id = NULL) {
		if ($thread_id == NULL || $thread_id == 0) {
			return false;
		}
		
		$query = $this->db->select ( "bpm" )->join ( "setup_processes", "setup_processes.key = threads.type", "left" )->where ( "threads.id", $thread_id )->get ( "threads" );
		$result = $query->row ();
		
		if (count ( $result ) > 0) {
			return $result->bpm;
		} else {
			return false;
		}
	}

}