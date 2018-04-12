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

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_action extends CI_Model
{
	public function get_setup_process($type) {
		$query = $this->db->where ( 'key', $type )->get ( 'setup_processes' );
		return $query->row ();
	}
	
	public function get_macro_single($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'setup_mps' );
		return $query->row ();
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
	
	public function Set_Status_Trouble($TROUBLEID, $status, $status_result = NULL) {
		$res = true;
		$data = array (
				'status' => $status,
				// 'status_detail' => $status_detail,
				'modified' => date ( 'Y-m-d H:i:s' )
		);
	
		if ($status == 'CANCELLED') {
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
	
	public function get_related_id($activity, $thread) {
		$query = $this->db->where ( 'type', $activity )->where ( 'id_thread', $thread )->get ( 'activities' );
		$act = $query->row ();
		return $act->id;
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
		$thread_fetch = $this->db->select ( "threads.id,threads.status,threads.process,threads.type" )->where ( "trouble_id", $trouble_id )->where ( "status = 'OPEN'" )->get ( "threads" );
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
		$thread_fetch = $this->db->select ( "threads.id,threads.status,threads.process,threads.type,threads.trouble_id" )->where ( "id", $thread_id )->where ( "status = 'OPEN'" )->get ( "threads" );
		$res = $thread_fetch->row ();
	
		if (isset ( $res->id ) && $res->id != '') {
			$get_act_details = $this->db->select ( "activities.id,activities.type,activities.status as master_status,vars.value as vars_status" )->join ( "vars", "vars.id_activity = activities.id" )->where ( "activities.id_thread", $item->id )->where ( "vars.key", "STATUS" )->get ( "activities" );
			$res->act_details = $get_act_details->result_array ();
		}
	
		return $res;
	}
	
	
}