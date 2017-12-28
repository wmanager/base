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
class inorder_process_library {
	var $CI;
	public function __construct() {
		$this->CI = & get_instance ();
		log_message ( 'debug', "Live Inorder Process Class Initialized" );
	}
	public function ripensemento_process($input_data = NULL) {
		$CI = & get_instance ();
		$CI->load->model ( 'recesso_cliente' );
		$CI->load->model ( 'activity' );
		
		// Thread status change
		$thread_id = $CI->recesso_cliente->get_inorder_thread ( $input_data ['be_id'] );
		
		$CI->core_actions->Set_Satus_Thread ( $thread_id, 'CANCELED', 'RIPENSAMENTOCLIENTE' );
		$CI->core_actions->Set_Status_Be ( $input_data ['be_id'], 'CANCELED', 'RIPENSAMENTOCLIENTE' );
		$CI->core_actions->Set_Status_Impianto ( $input_data ['impianto_id'], 'CANCELED', 'RIPENSAMENTOCLIENTE' );
		
		// Activities status change
		$activities = $CI->activity->get_activities_for_cancel ( $thread_id );
		if (count ( $activities ) > 0) {
			foreach ( $activities as $activity ) {
				$CI->core_actions->Set_Status_Activity ( $activity->id, 'CANCELED', 'RIPENSAMENTOCLIENTE' );
			}
		}
		
		$return = array (
				'result' => TRUE,
				'error' => '' 
		);
		
		return $return;
	}
}
?>