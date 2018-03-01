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
class Engine_debug extends Admin_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'engine' );
	}
	public function debug($thread_id = NULL, $activity_id = NULL) {
		if ($thread_id == NULL)
			redirect ( '/common/activities' );
		
		if ($this->input->post ()) {
			// $action = $this->engine->get_action();
			$action = $this->input->post ( 'comments' );
			$action_decode = $this->core_actions->decoding_action ( $action, $thread_id, $activity_id );
			if (count ( $action_decode ) > 0) {
				$key = 0;
				$error = false;
				foreach ( $action_decode as $each_action ) {
					if ($error) {
						break;
					}
					$each_action ['caller_type'] = 'ACTIVITY';
					$each_action ['caller_id'] = $activity_id;
					if (isset ( $each_action ['target_id_label'] ) && isset ( $$each_action ['target_id_label'] )) {
						$each_action ['target_id'] = $$each_action ['target_id_label'];
					}
					$action_method = $each_action ['function'];
					
					switch ($action_method) {
						case "Update_Var" :
							$response = $this->core_actions->update_var ( $each_action ['caller_type'], $each_action ['caller_id'], $each_action ['target_type'], $each_action ['target_id'], $each_action ['params_array'], $each_action ['duty'] );
							if ($response != 0) {
								$error = true;
								break;
							}
							$$each_action ['res'] = $response;
							break;
						case "Create_Activity" :
							$response = $this->core_actions->create_activity ( $each_action ['caller_type'], $each_action ['caller_id'], $each_action ['target_type'], $each_action ['params_array'], $each_action ['duty'] );
							if ($response <= 0) {
								$error = true;
								break;
							}
							$$each_action ['res'] = $response;
							
							break;
						case 'Update_Duty' :
							$response = $this->core_actions->update_duty ( $each_action ['caller_type'], $each_action ['caller_id'], $each_action ['target_type'], $each_action ['target_id'], $each_action ['new_duty_company'], $each_action ['new_duty_user'] );
							if ($response != 0) {
								$error = true;
								break;
							}
							$$each_action ['res'] = $response;
							break;
						case "Set_Status_Be" :
							$BEID = $this->actions->get_beid ( $each_action ['caller_id'] );
							$response = $this->core_actions->Set_Status_Be ( $BEID, $each_action ['target_type'] );
							if ($response <= 0) {
								$error = true;
								break;
							}
							$$each_action ['res'] = $response;
							break;						
						case "Set_Status_Thread" :
							$Threadid = $this->actions->get_Threadid ( $each_action ['caller_id'] );
							$response = $this->core_actions->Set_Satus_Thread ( $Threadid, $each_action ['target_type'] );
							if ($response <= 0) {
								$error = true;
								break;
							}
							$$each_action ['res'] = $response;
							break;
						case "Set_Status_Activity" :
							$response = $this->core_actions->Set_Status_Activity ( $each_action ['caller_id'], $each_action ['target_type'] );
							if ($response <= 0) {
								$error = true;
								break;
							}
							$$each_action ['res'] = $response;
							break;
					}
				}
			}
		}
		
		$thread_vars = $this->engine->get_thread_vars ( $thread_id, NULL );
		if ($activity_id != NULL) {
			$activity_vars = $this->engine->get_activity_vars ( $thread_id, $activity_id );
			$data_new ['activity_vars'] = $activity_vars;
		}
		
		$history_data = $this->engine->get_vars_history ( $thread_id, $activity_id );
		$data_new ['thread_vars'] = $thread_vars;
		$data_new ['history_data'] = $history_data;
		$data ['content'] = $this->load->view ( 'engine', $data_new, true );
		$this->load->view ( 'template', $data );
	}
}