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
class Actions {
	
	var $CI;
	public function __construct() {
		$this->CI = & get_instance ();
		$this->CI->load->model ( 'core/actions_model' );
		log_message ( 'debug', "Actions Class Initialized" );
	}
	
	/**
	 * get_setup_activity_details
	 *
	 * call from controllers/common/case. function create_related_activity(). 
	 *
	 * @param integer $thread_id        	
	 * @param string $setup_activity_key        	
	 *
	 * @return string
	 *
	 * @author Sumesh
	 */
	public function get_setup_activity_details($thread_id, $setup_activity_key) {
		$CI = & get_instance ();
		$CI->load->model ( 'wmanager/setup_activity' );
		
		$result = $CI->setup_activity->get_setup_activity_details ( $thread_id, $setup_activity_key );
		
		return $result;
	}
	
	public function create_thread($process_key, $account_id, $be_id, $duty = NULL, $trouble_id = NULL, $draft = 't') {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$thread_id = $CI->actions_model->create_thread ( $process_key, $account_id, $be_id, $duty, $trouble_id, $draft );
		
		$action = 'create_thread';
		$session = $CI->session->userdata ( 'session_id' );
		log_message ( 'DEBUG', 'trigger engine 12' );
		$history = $CI->actions_model->add_history ( 'THREAD', $thread_id, 'THREAD', $thread_id, array (), $action, $session, $thread_id, 0 );
		
		return $thread_id;
	}
	
	public function create_trouble($type, $description = null, $status, $customer_id = null, $be_id = null, $cmp_id = null, $trouble_sub_type = null, $tro_role = null, $company = null, $users = null) {
		$CI = & get_instance ();
		$CI->load->model ('trouble');
		if ($cmp_id != null) {
			$cmp_id = $cmp_id;
		}
		
		$data = array (
				'type' => $type,
				'description' => $description,
				'status' => $status,
				'customer' => array (
						'id' => $customer_id 
				),
				'contract' => $be_id,
				'campagna_id' => $cmp_id,
				'subtype' => $trouble_sub_type,
				'duty_company_resolution' => $company,
				'duty_user_resolution' => $users,
				'res_roles' => $tro_role,
				'created_by' => ! (empty ( $CI->ion_auth->user ()->row ()->id )) ? $CI->ion_auth->user ()->row ()->id : '1' 
		);
		$trouble_id = $CI->trouble->add ( $data );
		
		return $trouble_id;
	}
	
	public function checkActivity($actId) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$check = $CI->actions_model->checkActivity ( $actId );
		return $check;
	}
	
	public function checkActivityType($actType, $thread_id) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$check = $CI->actions_model->checkActivityType ( $actType, $thread_id );
		return $check;
	}
	
	public function Set_Status_Trouble($TROUBLEID, $status, $thread_id = NULL, $act_id = NULL, $exit_code = NULL, $status_result = NULL) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$updatestatus = $CI->actions_model->Set_Status_Trouble ( $TROUBLEID, $status, $status_result );
		$action = 'update_status_trouble';
		$session = $CI->session->userdata ( 'session_id' );
		$parma ['STATUS'] = $status;
		log_message ( 'DEBUG', 'trigger engine 12' );
		$history = $CI->actions_model->add_history ( 'ACTIVITY', $act_id, 'THREAD', $thread_id, $parma, $action, $session, $updatestatus, $exit_code );
		return $updatestatus;
	}
	
	public function Set_Parent_Trouble_Status($THREADID, $status, $status_detail, $thread_id = NULL, $act_id = NULL, $exit_code = NULL) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$updatestatus = $CI->actions_model->Set_Parent_Trouble_Status ( $THREADID, $status, $status_detail );
		
		$action = $updatestatus ['message'];
		$updatestatus = $updatestatus ['status'];
		$session = $CI->session->userdata ( 'session_id' );
		$parma ['STATUS'] = $status;
		log_message ( 'DEBUG', 'trigger engine 13' );
		$history = $CI->actions_model->add_history ( 'ACTIVITY', $act_id, 'THREAD', $THREADID, $parma, $action, $session, $updatestatus, $exit_code );
		return $updatestatus;
	}
	
	public function get_Threadid($actid) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$threadid = $CI->actions_model->get_thread_id ( $actid )->id_thread;
		return $threadid;
	}
	
	public function save_data($type, $activity) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		return $CI->actions_model->save_data ( $type, $activity );
	}
	
	public function get_related_id($type, $thread) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$actid = $CI->actions_model->get_related_id ( $type, $thread );
		return $actid;
	}
	
	public function addNote($threadid, $activityID, $text, $date = null) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$note_id = $CI->actions_model->add_note ( $threadid, $activityID, $text, $date );
		return $note_id;
	}
	
	public function create_trouble_tree($type, $description, $status, $customer_id, $be_id) {
		$trouble_id = $this->create_trouble ( $type, $description, $status, $customer_id, $be_id );
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$autocreate_process = $CI->actions_model->get_process_for_trouble_types ( $type );
		
		if (count ( $autocreate_process ) > 0) {
			foreach ( $autocreate_process as $process ) {
				$thread_id = $this->create_thread_tree ( $process->process_key, $customer_id, $be_id, $duty = NULL, $trouble_id, 'f', $process->request_key );
			}
		}
		
		return $trouble_id;
	}
	
	public function create_thread_tree($process_key, $account_id, $be_id, $duty = NULL, $trouble_id = NULL, $draft = 'f', $request_key) {
		$thread_id = $this->create_thread ( $process_key, $account_id, $be_id, $duty, $trouble_id, $draft );

		$CI = & get_instance ();
		$CI->load->library ( "core/core_actions" );
		$CI->load->model ( 'core/actions_model' );
		$CI->load->model ( 'wmanager/setup_activity' );
		
		$request_activity_id = $CI->actions_model->get_activity_id ( $request_key );
		$initial_status_key = $this->get_activity_initial_status ( $request_key, $process_key );
		
		$vars = array (
				'STATUS' => $initial_status_key 
		);
		$CI->core_actions->create_activity ( 'THREAD', $thread_id, $request_key, $vars );
		
		return $thread_id;
	}
	
	public function cancel_trouble_tree($trouble_id) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$CI->load->library ( "core/core_actions" );
		
		// fetch threads for the trouble
		$thread_details = $CI->actions_model->get_thread_details ( $trouble_id );
		$i = 0;
		$return_array = array ();
		// check count of threads
		if (count ( $thread_details ) == 0 || $thread_details == '') {
			$trouble_reset = $this->Set_Status_Trouble ( $trouble_id, "CANCELLED" );
			$return_array [$i] ["trouble_id"] = $trouble_id;
			return $return_array;
		}
		
		foreach ( $thread_details as $thread ) {
			// check activity count for thread
			if (count ( $thread ["act_details"] ) == 0) {
				$thread_reset = $CI->core_actions->Set_Satus_Thread ( $thread ["id"], "CANCELLED", "Alarm was Fixed" );
				$trouble_reset = $this->Set_Status_Trouble ( $trouble_id, "CANCELLED" );
				
				$return_array [$i] ["thread_id"] = $thread ["id"];
				$return_array [$i] ["trouble_id"] = $trouble_id;
			}
			
			// if count of activity is greater than 1 then don't do anything
			if (count ( $thread ["act_details"] ) > 1) {
				$i ++;
				continue;
			}
			
			// if count of activity is 1
			if (count ( $thread ["act_details"] ) == 1) {
				// check weather it is in initial status
				$initial_status = $this->get_activity_initial_status ( $thread ["act_details"] [0] ["type"], $thread ["type"] );
				if ($initial_status == $thread ["act_details"] [0] ["vars_status"] && $thread ["act_details"] [0] ["master_status"] == 'OPEN') {
					// close the activity
					$act_reset = $CI->core_actions->Set_Status_Activity ( $thread ["act_details"] [0] ["id"], "CANCELLED", "Alarm was Fixed" );
					
					// close the thread
					$thread_reset = $CI->core_actions->Set_Satus_Thread ( $thread ["id"], "CANCELLED", "Alarm was Fixed" );
					
					// close the thread
					$trouble_reset = $this->Set_Status_Trouble ( $trouble_id, "CANCELLED" );
					
					$return_array [$i] ["thread_id"] = $thread ["id"];
					$return_array [$i] ["activity_id"] = $thread ["act_details"] [0] ["id"];
					$return_array [$i] ["trouble_id"] = $trouble_id;
				}
			}
			$i ++;
		}
		
		return $return_array;
	}
	
	public function get_activity_initial_status($activity_type = NULL, $thread_type = NULL) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		
		// get setup_vars value for process and actvity
		$vars = $CI->actions_model->get_setup_vars_initial_status ( $activity_type, $thread_type );
		
		return $vars;
	}
	
	public function Auto_Trouble_Status_Update($THREADID, $status) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		
		// get Thread Details
		$thread_details = $CI->actions_model->get_all_thread_details ( $THREADID );
		
		// check any trouble associated to it
		if ($thread_details->trouble_id == NULL || $thread_details->trouble_id == '') {
			return TRUE;
		}
		
		if ($thread_details->status == 'OPEN') {
			if (count ( $thread_details->act_details ) == 1) {
				$act_details = $thread_details->act_details;
				
				$initial_status = $this->get_activity_initial_status ( $act_details [0] ["type"], $thread->type );
				if ($initial_status != $act_details [0] ["vars_status"] && $act_details [0] ["master_status"] == 'OPEN') {
					$trouble_reset = $this->Set_Status_Trouble ( $thread_details->trouble_id, "WIP" );
				}
			} else if (count ( $thread_details->act_details ) > 1) {
				$trouble_reset = $this->Set_Status_Trouble ( $thread_details->trouble_id, "WIP" );
			}
		} else if ($thread_details->status == 'CLOSED') {
			// close the thread
			$trouble_reset = $this->Set_Status_Trouble ( $thread_details->trouble_id, "DONE" );
		} else if ($thread_details->status == 'CANCELLED') {
			// cancel the thread
			$trouble_reset = $this->Set_Status_Trouble ( $thread_details->trouble_id, "CANCELLED" );
		}
		
		return TRUE;
	}
}