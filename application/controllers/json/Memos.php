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
class Memos extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'memo' );
	}
	public function get($company) {
		$memos_data = array (
				'result' => array (),
				'status' => false 
		);
		
		$memos_data ['status'] = true;
		$memos_data ['result'] = $this->memo->get_memo ( $company );
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $memos_data ) );
	}
	public function get_tecnico_allaccio($thread) {
		$this->load->model ( 'activity' );
		$memos_data = array (
				'result' => array (),
				'status' => false 
		);
		
		$memos_data ['status'] = true;
		$memos_data ['result'] = $this->activity->get_pt_tecnico_allaccio_memos ( $thread );
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $memos_data ) );
	}
	public function followup($thread = NULL, $activity = NULL, $trouble = NULL, $legal = NULL) {
		if ($thread == 'null')
			$thread = NULL;
		if ($activity == 'null')
			$activity = NULL;
		if ($trouble == 'null')
			$trouble = NULL;
		$memos_data = $this->memo->get_followup ( $activity, $thread, $trouble, $legal );
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $memos_data ) );
	}
}
