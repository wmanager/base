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
class Core_actions {
	var $CI;
	public function __construct() {
		$this->CI = & get_instance ();
		$this->CI->load->model ( 'core/actions_model' );
		log_message ( 'debug', "Core Actions Class Initialized" );
	}
	public function update_var($caller_type, $caller_id, $target_type, $target_id, $data_varval = NULL, $duty = NULL, $exit_code = NULL) {
		log_message ( 'DEBUG', 'caller_type =' . $caller_type . ' caller_id=' . $caller_id . ' target_type=' . $target_type . ' target_id=' . $target_id );
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$updatevar = $CI->actions_model->update_var ( $caller_type, $caller_id, $target_type, $target_id, $data_varval, $duty );
		$action = 'update_var';
		$session = $CI->session->userdata ( 'session_id' );
		log_message ( 'DEBUG', 'trigger engine 3' );
		
		$history = $CI->actions_model->add_history ( $caller_type, $caller_id, $target_type, $target_id, $data_varval, $action, $session, $updatevar, $exit_code );
		
		if ($updatevar == 0) {
			if ($target_type == 'ACTIVITY') {
				$id_activity = $target_id;
			} else if ($caller_type == 'ACTIVITY') {
				$id_activity = $caller_id;
			}
			if ($id_activity != NULL) {
				log_message ( 'DEBUG', '-----------------------------------------------------------------' );
				log_message ( 'DEBUG', 'trigger engine 123' );
				log_message ( 'DEBUG', '-----------------------------------------------------------------' );
				$this->engine ( $id_activity );
				// $this->autoActivityStatusUpdate($id_activity);
			}
		}
		return $updatevar;
	}
	public function create_activity($caller_type, $caller_id, $target_type, $data_varval, $duty = NULL, $exit_code = NULL) {

		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$activity_id = $CI->actions_model->create_activity ( $caller_type, $caller_id, $target_type, $data_varval, $duty );
		
		if ($activity_id > 0) {
			$setup_activities_details = $CI->actions_model->get_setup_activities ( $activity_id );
			
			if (count ( $setup_activities_details ) > 0) {
				$entry_scenario = $this->entry_scenario ( $activity_id, $setup_activities_details );
			}
		}
		
		// if($activity_id >0){
		$action = 'create_activity';
		$session = $CI->session->userdata ( 'session_id' );
		log_message ( 'DEBUG', 'trigger engine 2' );
		
		$history = $CI->actions_model->add_history ( $caller_type, $caller_id, $target_type, $activity_id, $data_varval, $action, $session, $activity_id, $exit_code );
		
		// }
		return $activity_id;
	}
	public function entry_scenario($activity_id = NULL, $setup_details = NULL) {
		if ($setup_details == NULL || $activity_id == NULL) {
			return FALSE;
		}
		
		if ($setup_details->entry_scenario == 'Create_Export_Billing') {
			$CI = & get_instance ();
			$CI->load->model ( 'core/actions_model' );
			
			$customer_details = $CI->actions_model->get_billing_details ( $activity_id );
			
			if (count ( $customer_details ) == 0) {
				return FALSE;
			}
			
			$tipo_richiesta = "ATTIVAZIONE";
			$d_decorrenza = date ( 'Y-m-d' );
			$this->export_billing ( $customer_details->customer, $customer_details->be, $activity_id, $tipo_richiesta, $d_decorrenza, $status = 'DONE_GROSSISTA' );
		} else if ($setup_details->entry_scenario == 'Create_Export_Billing_Sereno') {
			$CI = & get_instance ();
			$CI->load->model ( 'core/actions_model' );
			
			$customer_details = $CI->actions_model->get_billing_details ( $activity_id );
			
			if (count ( $customer_details ) == 0) {
				return FALSE;
			}
			
			$tipo_richiesta = "ATTIVAZIONE";
			
			$d_att = date ( "Y-m-d" );
			$d_decorrenza = date ( 'Y-m-01', strtotime ( "+1 months", strtotime ( $d_att ) ) );
			$this->export_billing ( $customer_details->customer, $customer_details->be, $activity_id, $tipo_richiesta, $d_decorrenza, $status = 'DONE_GROSSISTA' );
		}
		
		return TRUE;
	}
	public function update_duty($caller_type, $caller_id, $target_type, $target_id, $role, $duty_company, $duty_user, $data_varval = NULL, $exit_code = NULL) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$update_duty = $CI->actions_model->update_duty ( $caller_type, $caller_id, $target_type, $target_id, $role, $duty_company, $duty_user );
		$action = 'update_duty';
		$session = $CI->session->userdata ( 'session_id' );
		log_message ( 'DEBUG', 'trigger engine 4' );
		
		$history = $CI->actions_model->add_history ( $caller_type, $caller_id, $target_type, $target_id, $data_varval, $action, $session, $update_duty, NULL, $duty_company, $duty_user, $exit_code );
		
		return $update_duty;
	}
	public function decoding_action($actions, $thread_id, $activity_id) {
		$parse_data = array ();
		$key = 0;
		/**
		 * exploding actions by line *
		 */
		$action_array = explode ( PHP_EOL, $actions );
		
		
		
		if (count ( $action_array ) > 0) {
			foreach ( $action_array as $each_item ) {
				
				/**
				 * check for .
				 * for making sure it is an action *
				 */
				if (substr ( $each_item, 0, 1 ) == '$') {
					/**
					 * seperate the result and method section *
					 */
					
					//remove white spaces
					$each_item = trim ( preg_replace ( '/\s+/', ' ', str_replace ( ' ', '', $each_item ) ) );
					
					//res will contain nextid/res in array 
					$re1 = '/(\\$(?:[a-z][a-z0-9]+)=)/i'; // $RES=
					preg_match ( $re1, $each_item, $res ); // getting result section
					
					log_message ( 'DEBUG', '==========================================' );
					log_message ( 'DEBUG', $each_item );
					log_message ( 'DEBUG', print_r ( $res, true ) );
					
					//remove $res= and get method name alone
					$action_call = str_replace ( $res [1], '', $each_item ); // getting method section by removing $RES=
					
					if (isset ( $action_call )) {
						/**
						 * seperate the method name *
						 */
						$action_parts = explode ( '(', $action_call );
						
						$action_method = trim ( $action_parts [0] ); // getting action key name
						
						/**
						 * seperate arguments of the method *
						 */
						preg_match ( '/\((.*?)\)/', $action_call, $out );
						$arguments = $out [1]; // getting all arguments inside ()
						
						/**
						 * get each arguments and their value *
						 */
						$arguments_array = explode ( ';', $arguments );
						
						$parse_data [$key] ['res'] = trim ( str_replace ( '=', '', str_replace ( '$', '', $res [1] ) ) ); // removing = and $ from the result variable
						$parse_data [$key] ['function'] = $action_method;
						
						$parse_data [$key] ['target_type'] = $target_type = trim ( $arguments_array [0] );
						
						$flag = true;
						switch ($action_method) {
							
							case 'Update_Var' :
								// check for activity type and activity id or generic variable
								if (substr ( $arguments_array [1], 0, 1 ) == '$') {
									$parse_data [$key] ['target_id_label'] = $target_id_label = trim ( str_replace ( '$', '', $arguments_array [1] ) );
								} else if (is_numeric ( $arguments_array [1] )) {
									if ($act_id = $this->checkActivity ( trim ( $arguments_array [1] ) ))
										$flag = false;
									$parse_data [$key] ['target_id_label'] = $target_id_label = '';
									$parse_data [$key] ['target_id'] = $target_id = $act_id;
								} else {
									if ($act_id = $this->checkActivityType ( trim ( $arguments_array [1] ), $thread_id ))
										$flag = false;
									$parse_data [$key] ['target_id_label'] = $target_id_label = '';
									$parse_data [$key] ['target_id'] = $target_id = $act_id;
								}
								if ($flag) {
									switch ($target_type) {
										case 'ACTIVITY' :
											$parse_data [$key] ['target_id'] = $target_id = $activity_id;
											break;
										case 'THREAD' :
											$parse_data [$key] ['target_id'] = $target_id = $thread_id;
											break;
									}
								}
								
								
								$parse_data [$key] ['params'] = $params = trim ( $arguments_array [2] );
								if (isset ( $arguments_array [3] ) && $arguments_array [3] != '') {
									$parse_data [$key] ['duty'] = $duty = trim ( $arguments_array [3] );
								} else {
									$parse_data [$key] ['duty'] = $duty = NULL;
								}
								$params_array = explode ( '|', $params ); // creating params array
								$parse_data [$key] ['params_array'] = array ();
								foreach ( $params_array as $parm_item ) {
									$param_data = explode ( '=', $parm_item );
									$parse_data [$key] ['params_array'] [$param_data [0]] = $param_data [1];
								}
								
								$key ++;
								break;
								
							case 'Create_Activity' :
								if(isset($arguments_array[1]) && $arguments_array[1]!=NULL && strpos($arguments_array[1],'=')>=0){
									$parse_data [$key] ['params'] = $params = trim ( $arguments_array [1] );
									if (isset ( $arguments_array [2] ) && $arguments_array [2] != '') {
										$parse_data [$key] ['duty'] = $duty = trim ( $arguments_array [2] );
									} else {
										$parse_data [$key] ['duty'] = $duty = NULL;
									}
									$params_array = explode ( '|', $params ); // creating params array
									$parse_data [$key] ['params_array'] = array ();
									foreach ( $params_array as $parm_item ) {
										$param_data = explode ( '=', $parm_item );
										$parse_data [$key] ['params_array'] [$param_data [0]] = $param_data [1];
									}
								}else{
									$parse_data [$key] ['params'] = '';
									$parse_data [$key] ['params_array'] = array();
									
								}
								$key ++;
								break;
								
							case 'Update_Duty' :
								$parse_data [$key] ['target_id_label'] = $target_id_label = trim ( str_replace ( '$', '', $arguments_array [1] ) );
								switch ($target_type) {
									case 'ACTIVITY' :
										$parse_data [$key] ['target_id'] = $target_id = $activity_id;
										break;
									case 'THREAD' :
										$parse_data [$key] ['target_id'] = $target_id = $thread_id;
										break;
								}
								$parse_data [$key] ['new_duty_company'] = $new_duty_company = trim ( $arguments_array [2] );
								if (isset ( $arguments_array [3] ) && $arguments_array [3] != '' && $arguments_array [3] != 'NULL') {
									$parse_data [$key] ['new_duty_user'] = $new_duty_user = trim ( $arguments_array [3] );
								} else {
									$parse_data [$key] ['new_duty_user'] = $new_duty_user = NULL;
								}
								$key ++;
								break;
							
							case "Set_Status_Be" :
							case "Set_Status_Impianto" :
							case "Set_Status_Thread" :
							case "Set_Status_Activity" :
							case "Set_Parent_Trouble_Status" :
							case "Set_Parent_Trouble_Result" :
								$parse_data [$key] ['target_id'] = trim ( $arguments_array [1] );
								if(isset($arguments_array [1]) && $arguments_array [1]!= '' && strpos($arguments_array [1],'=')>=0){
									//save params
									$parse_data [$key] ['params'] = $params = trim ( $arguments_array [1] );
										
									//create param array
									$params_array = explode ( '|', $params ); // creating params array
									$parse_data [$key] ['params_array'] = array ();
									foreach ( $params_array as $parm_item ) {
										$param_data = explode ( '=', $parm_item );
										$parse_data [$key] ['params_array'] [$param_data [0]] = $param_data [1];
									}
								}
								$key ++;
								break;
								
							default:
								if(is_array($arguments_array) && count($arguments_array)>0){
									//save params
									$parse_data [$key] ['params'] = $arguments;
									$param_key = 0;
									foreach ($arguments_array as $each_argument){
										if(strpos($each_argument,'|') == ''){
											//$each_param = explode("=",$each_argument);
											$parse_data [$key] ['params_array'] [$param_key] = $each_argument;
										}else{
											$each_param_array = explode("|",$each_argument);
											if(is_array($each_param_array) && count($each_param_array)>0){
												$temp_param_array = array();
												foreach($each_param_array as $each_array_item){
													$each_param_temp = explode("=",$each_array_item);
													$temp_param_array[$each_param_temp[0]] = $each_param_temp[1];
												}
											}
											$parse_data [$key] ['params_array'] [$param_key] = $temp_param_array;	
										}
										$param_key++;
									}
								}
								$key ++;
								break;
								
						}
					}
				}
			}
		}
		
		return $parse_data;
	}
	public function decode_condition($condition) {
		$parse_data = array ();
		$key = 0;
		/**
		 * exploding actions by line *
		 */
		$condition_array = explode ( PHP_EOL, $condition );
		if (count ( $condition_array ) > 0) {
			foreach ( $condition_array as $each_item ) {
				/**
				 * check for .
				 * for making sure it is an action *
				 */
				if (substr ( $each_item, 0, 1 ) == '$') {
					
					$condition_parts = explode ( '=', $each_item );
					// print_r($condition_parts);
					$parse_key = str_replace ( '$', '', $condition_parts [0] ); // removing = and $ from the result variable
					$parse_data [$parse_key] = str_replace ( ';', '', $condition_parts [1] );
				}
			}
		}
		return $parse_data;
	}
	public function Set_Status_Be($BEID, $status, $status_detail, $thread_id = NULL, $act_id = NULL, $exit_code = NULL) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$updatestatus = $CI->actions_model->Set_Status_Be ( $BEID, $status, $status_detail );
		$action = 'update_status_be';
		$session = $CI->session->userdata ( 'session_id' );
		$parma ['STATUS'] = $status;
		log_message ( 'DEBUG', 'trigger engine 11' );
		$history = $CI->actions_model->add_history ( 'ACTIVITY', $act_id, 'THREAD', $thread_id, $parma, $action, $session, $updatestatus, $exit_code );
		return $updatestatus;
	}
	public function get_Beid($actid) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$threadid = $CI->actions_model->get_thread_id ( $actid )->id_thread;
		$Beid = $CI->actions_model->get_Beid ( $threadid );
		return $Beid;
	}
	public function get_Impiantoid($actid) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$threadid = $CI->actions_model->get_thread_id ( $actid )->id_thread;
		$Beid = $CI->actions_model->get_Beid ( $threadid );
		$Impiantoid = $CI->actions_model->get_Impiantoid ( $Beid );
		return $Impiantoid;
	}
	public function Set_Status_Impianto($IMPIANTOID, $status, $status_detail, $thread_id = NULL, $act_id = NULL, $exit_code = NULL) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$updatestatus = $CI->actions_model->Set_Status_Impianto ( $IMPIANTOID, $status, $status_detail );
		$action = 'update_status_impianto';
		$session = $CI->session->userdata ( 'session_id' );
		$parma ['STATUS'] = $status;
		log_message ( 'DEBUG', 'trigger engine 10' );
		$history = $CI->actions_model->add_history ( 'ACTIVITY', $act_id, 'THREAD', $thread_id, $parma, $action, $session, $updatestatus, $exit_code );
		return $updatestatus;
	}
	public function Set_Satus_Thread($THREADID, $status, $status_detail, $thread_id = NULL, $act_id = NULL, $exit_code = NULL) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$updatestatus = $CI->actions_model->Set_Satus_Thread ( $THREADID, $status, $status_detail );
		
		$action = 'update_status_thread';
		$session = $CI->session->userdata ( 'session_id' );
		$parma ['STATUS'] = $status;
		log_message ( 'DEBUG', 'trigger engine 9' );
		$history = $CI->actions_model->add_history ( 'ACTIVITY', $act_id, 'THREAD', $THREADID, $parma, $action, $session, $updatestatus, $exit_code );
		return $updatestatus;
	}
	public function Set_Status_Activity($ACTIVITYID, $status, $status_detail, $thread_id = NULL, $act_id = NULL, $exit_code = NULL) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$updatestatus = $CI->actions_model->Set_Status_Activity ( $ACTIVITYID, $status, $status_detail );
		$action = 'update_status_activity';
		$session = $CI->session->userdata ( 'session_id' );
		$parma ['STATUS'] = $status;
		log_message ( 'DEBUG', 'trigger engine 8' );
		$history = $CI->actions_model->add_history ( 'ACTIVITY', $act_id, 'ACTIVITY', $ACTIVITYID, $parma, $action, $session, $updatestatus, $exit_code );
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
	public function Set_Parent_Trouble_Result($THREADID, $status, $status_detail, $thread_id = NULL, $act_id = NULL, $exit_code = NULL) {
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		$updatestatus = $CI->actions_model->Set_Parent_Trouble_Result ( $THREADID, $status );
		
		$action = $updatestatus ['message'];
		$updatestatus = $updatestatus ['status'];
		$session = $CI->session->userdata ( 'session_id' );
		$parma ['STATUS'] = $status;
		log_message ( 'DEBUG', 'trigger engine 14' );
		$history = $CI->actions_model->add_history ( 'ACTIVITY', $act_id, 'THREAD', $THREADID, $parma, $action, $session, $updatestatus, $exit_code );
		return $updatestatus;
	}
	public function engine($act_id = NULL) {
		if ($act_id == NULL)
			return - 1;
		
		$CI = & get_instance ();
		$CI->load->model ( 'core/actions_model' );
		
		$thread_data = $CI->actions_model->get_thread_id ( $act_id );
		$thread_id = ($thread_data->id_thread) ? $thread_data->id_thread : NULL;
		$exits = $CI->actions_model->get_exit_scenario ( $act_id, $thread_id );
		$flag = false;
		if ($exits != - 1) {
			foreach ( $exits as $exit ) {
				if ($flag)
					break;
				
				$exit_code_check = $CI->actions_model->check_exit_code ( $thread_id, $exit->code );
				
				$condition = $exit->condition;
				$response = $this->decode_condition ( $condition );
				log_message ( 'DEBUG', 'conditions ' . print_r ( $response, true ) );
				$count = 0;
				foreach ( $response as $key => $item ) {
					
					$key_type = explode ( '.', $key );
					if (count ( $key_type ) > 1) {
						switch ($key_type [0]) {
							case 'ACTID' :
								$value = $CI->actions_model->get_var_value ( $act_id, $key_type [1] );
								break;
							case 'THREAD' :
								$value = $CI->actions_model->get_var_value_thread ( $thread_id, $key_type [1] );
								break;
						}
					} else {
						$value = $CI->actions_model->get_var_value ( $act_id, $key_type [0] );
					}
					log_message ( 'DEBUG', print_r ( $value, true ) );
					
					if (isset ( $value->value )) {
						if ($value->value == $item) {
							$count ++;
							if ($count == count ( $response )) {
								if ($exit_code_check != 0) {
									$history = $CI->actions_model->add_history ( 'ACTIVITY', $act_id, NULL, NULL, NULL, 'ENGINE_BREAK', $CI->session->userdata ( 'session_id' ), 0, $exit->code );
									break;
								}
								
								// ADDED NEW ENGINE FUNC $history = $CI->actions_model->add_history('ACTIVITY',$act_id,NULL,NULL,NULL,'ENGINE',$CI->session->userdata('session_id'),0,$exit->code);
								$history_id = $CI->actions_model->add_engine_history ( 'ACTIVITY', $act_id, 'ENGINE', $CI->session->userdata ( 'session_id' ), $exit->code );
								sleep ( 1 );
								
								$action = $exit->actions;
								$action_decode = $this->decoding_action ( $action, $thread_id, $act_id );
								log_message ( 'DEBUG', print_r ( $action_decode, true ) );
								
								$flag = true;
								
								/**
								 * * execute the action ***
								 */
								if (count ( $action_decode ) > 0) {
									
									// check weather 2 request are given
									$get_last_history = $CI->actions_model->get_last_engine_history ( "ACTIVITY", $act_id, $exit->code );
									if ($get_last_history != $history_id && $get_last_history != 0) {
										$interrupt_history_id = $CI->actions_model->add_engine_history ( 'ACTIVITY', $act_id, 'ENGINE_INTERRUPTED', $CI->session->userdata ( 'session_id' ), $exit->code );
										break;
									}
									
									$key = 0;
									$error = false;
									$growl_messages = '';
									foreach ( $action_decode as $each_action ) {
										if ($error) {
											break;
										}
										if (isset ( $each_action ['target_id_label'] ) && isset ( $$each_action ['target_id_label'] )) {
											$each_action ['target_id'] = $$each_action ['target_id_label'];
										}
										$action_method = $each_action ['function'];
										switch ($action_method) {
											case "Update_Var" :
												$response = $this->update_var ( 'ACTIVITY', $act_id, $each_action ['target_type'], $each_action ['target_id'], $each_action ['params_array'], $each_action ['duty'], $exit->code );
												if ($response != 0) {
													$error = true;
													break;
												}
												$$each_action ['res'] = $response;
												log_message ( 'DEBUG', 'trigger engine 5' );
												// $history = $CI->actions_model->add_history('ACTIVITY',$act_id,$each_action['target_type'],$act_id,NULL,'update_var',$CI->session->userdata('session_id'),0,$exit->code);
												break;
												
											case "Create_Activity" :
												$response = $this->create_activity ( 'ACTIVITY', $act_id, $each_action ['target_type'], $each_action ['params_array'], $each_action ['duty'], $exit->code );
												if ($response <= 0 && $response != - 4) {
													// -4 means prevented duplication therefore not error it should continue process
													$error = true;
													break;
												}
												
												if ($growl_messages != '')
													$growl_messages .= "<br>";
												$growl_messages .= "L'attività " . $each_action ['target_type'] . " è stata creata con successo.";
												
												$$each_action ['res'] = $response;
												log_message ( 'DEBUG', 'trigger engine 6' );
												// $history = $CI->actions_model->add_history('ACTIVITY',$act_id,$each_action['target_type'],$response,NULL,'create_activity',$CI->session->userdata('session_id'),0,$exit->code);
												break;
												
											case 'Update_Duty' :
												$response = $this->update_duty ( 'ACTIVITY', $act_id, $each_action ['target_type'], $each_action ['target_id'], $each_action ['new_duty_company'], $each_action ['new_duty_user'], $exit->code );
												if ($response != 0) {
													$error = true;
													break;
												}
												$$each_action ['res'] = $response;
												log_message ( 'DEBUG', 'trigger engine 7' );
												// $history = $CI->actions_model->add_history('ACTIVITY',$act_id,$each_action['target_type'],$act_id,NULL,'update_duty',$CI->session->userdata('session_id'),0,$exit->code);
												break;
												
											case "Set_Status_Be" :
												log_message ( 'DEBUG', 'THREAD:' . $thread_id );
												$BEID = $this->get_beid ( $act_id );
												log_message ( 'DEBUG', 'BEID:' . $BEID );
												$response = $this->Set_Status_Be ( $BEID, $each_action ['target_type'], $each_action ['target_id'], $thread_id, $act_id, $exit->code );
												if ($response != 0) {
													$error = true;
													break;
												}
												$$each_action ['res'] = $response;
												log_message ( 'DEBUG', 'trigger engine 8' );
												break;
												
											case "Set_Status_Impianto" :
												$Impiantoid = $this->get_Impiantoid ( $act_id );
												$response = $this->Set_Status_Impianto ( $Impiantoid, $each_action ['target_type'], $each_action ['target_id'], $thread_id, $act_id, $exit->code );
												if ($response != 0) {
													$error = true;
													break;
												}
												$$each_action ['res'] = $response;
												log_message ( 'DEBUG', 'trigger engine 9' );
												break;
												
											case "Set_Status_Thread" :
												
												log_message ( 'DEBUG', 'Set_Status_Thread' );
												log_message ( 'DEBUG', $exit->code );
												// $Threadid= $this->get_Threadid($each_action['caller_id']);
												$response = $this->Set_Satus_Thread ( $thread_id, $each_action ['target_type'], $each_action ['target_id'], $thread_id, $act_id, $exit->code );
												if ($response != 0) {
													$error = true;
													break;
												}
												$$each_action ['res'] = $response;
												log_message ( 'DEBUG', 'trigger engine 10' );
												break;
												
											case "Set_Status_Activity" :
												$response = $this->Set_Status_Activity ( $act_id, $each_action ['target_type'], $each_action ['target_id'], $thread_id, $act_id, $exit->code );
												if ($response != 0) {
													$error = true;
													break;
												}
												$$each_action ['res'] = $response;
												log_message ( 'DEBUG', 'trigger engine 11' );
												break;
												
											case "Set_Parent_Trouble_Status" :
												$response = $this->Set_Parent_Trouble_Status ( $thread_id, $each_action ['target_type'], $each_action ['target_id'], $thread_id, $act_id, $exit->code );
												if ($response != 0) {
													$error = true;
													break;
												}
												$$each_action ['res'] = $response;
												log_message ( 'DEBUG', 'trigger engine 12' );
												break;
												
											case "Set_Parent_Trouble_Result" :
												$response = $this->Set_Parent_Trouble_Result ( $thread_id, $each_action ['target_type'], $each_action ['target_id'], $thread_id, $act_id, $exit->code );
												if ($response != 0) {
													$error = true;
													break;
												}
												$$each_action ['res'] = $response;
												log_message ( 'DEBUG', 'trigger engine 13' );
												break;
												
											default:
												if(strpos($each_action['function'],'.')!=''){
													
													$module_array = explode('.',$each_action['function']);
													$module = strtolower($module_array[0]);
													$params = $each_action['params_array']; 
													$function_name = $module_array[1];
													
													if($module != 'origin'){
														$library_name = $module.'_actions';
														if(file_exists(APPPATH.'modules/'.$module."/libraries/".$module_array[0].'_actions.php')){
															$this->load->library('credit/'.$library_name);
														}
													}else{
														$library_name = 'actions';
													}
													
													if(method_exists($library_name,$function_name)){
														switch(count($params)){
															case 0: $response =  $this->$library_name->$function_name();
																	  break;
															case 1: $response =  $this->$library_name->$function_name($params[0]);
															  		  break;
															case 2: $response =  $this->$library_name->$function_name($params[0],$params[1]);
															  		  break;
															case 3: $response =  $this->$library_name->$function_name($params[0],$params[1],$params[2]);
															  		  break;
															case 4: $response =  $this->$library_name->$function_name($params[0],$params[1],$params[2],$params[3]);
															  		  break;
															case 5: $response =  $this->$library_name->$function_name($params[0],$params[1],$params[2],$params[3],$params[4]);
															  		  break;
														}			  			  
													}
													
												}else{
													$response = -6;
												}
												
												$$each_action['res'] = $response;
												log_message ( 'DEBUG', 'trigger engine 15' );
												break;
										}
									}
									if ($growl_messages != '') {
										$CI->session->set_flashdata ( 'growl_success', $growl_messages );
										$CI->session->set_flashdata ( 'growl_show', 'true' );
									}
								}
								
								break;
							}
						} else {
							break;
						}
					}
				}
			}
		}
		
		return 0;
	}
}