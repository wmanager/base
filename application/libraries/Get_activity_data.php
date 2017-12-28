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

class Get_activity_data {
	var $CI;
	public function __construct() {
		$this->CI = & get_instance ();
		$this->CI->load->model ( 'activity' );
		log_message ( 'debug', "Actions Class Initialized" );
	}
	public function get_data($id, $activity) {
		$this->CI = & get_instance ();
		
		// activity object that returns same value with new value
		
		// NOTICE
		// OLD EXISTING FORM USES the case
		// NEW FORMS USE default
		$act_data = array ();
		switch ($activity->type) {
			default :
				if ($activity->fetch_model_name != '') {
					if (method_exists ( $activity->fetch_model_name, 'fetch_data' )) {
						$this->CI->load->model ( $activity->fetch_model_name, 'temp_model' );
						$tempo_data = $this->CI->temp_model->fetch_data ( $id, $activity );
						
						if (isset ( $tempo_data ['activity'] )) {
							$activity = $tempo_data ['activity'];
						}
						
						if (isset ( $tempo_data ['act_data'] )) {
							$act_data = $tempo_data ['act_data'];
						}
					}
				}
				break;
		}
		
		if (count ( $act_data ) > 0) {
			// merge with existing payload
			$payload = json_decode ( $activity->payload );
			$merged_data = ( object ) array_merge ( ( array ) $payload, ( array ) $act_data );
			$activity->payload = json_encode ( $merged_data );
		}
		return $activity;
	}
}	