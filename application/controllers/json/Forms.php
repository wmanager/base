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
class Forms extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'form' );
	}
	public function types($process = NULL) {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->form->get_list ( $process ) ) );
	}
	public function activities() {
		$res = $this->form->get_activities ( $this->input->get ( 'type' ) );
		$activities = array ();
		foreach ( $res as $activity ) {
			$activities [$activity->key] = $activity->title;
		}
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $activities ) );
	}
	public function be($account) {
		if (! $this->input->get ( 'activity' ))
			return;
		$activity = $this->form->detail_activity ( $this->input->get ( 'activity' ) );
		
		if ($activity->be_required == 't') {
			$this->load->model ( 'customer' );
			$res = $this->customer->contracts ( $account );
			$activities = array ();
		} else {
			$be = array ();
		}
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $be ) );
	}
	public function check_request() {
		$this->load->model ( 'be' );
		
		$be = $this->be->detail ( $this->input->post ( 'be' ) );
		$query = $this->db->where ( 'request_key', $this->input->post ( 'request' ) )->where ( "(contract_type = '$be->tipo_contratto' OR contract_type = '*')" )->get ( 'list_request2contracts' );
		if ($query->num_rows () > 0) {
			$result = 'true';
		} else {
			$result = 'false';
		}
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
}
