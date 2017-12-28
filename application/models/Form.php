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
class Form extends CI_Model {
	public function get_list($process) {
		$this->db->join ( 'setup_forms', 'setup_forms.id = setup_processes.form_id' );
		$query = $this->db->select ( 'setup_processes.*,setup_forms.url' )->where ( 'setup_processes.disabled', 'f' )->where ( 'id_mp', $process )->order_by ( 'title', 'ASC' )->get ( 'setup_processes' );
		$result = $query->result ();
		
		log_message ( 'INFO', 'tipologie request' );
		log_message ( 'DEBUG', $this->db->last_query () );
		return $result;
	}
	public function get_activities($process) {
		$query = $this->db->select ( 'setup_activities.*' )->join ( 'setup_processes', 'setup_processes.id = setup_activities.id_process' )->where ( 'setup_processes.key', $process )->where ( 'is_request', 't' )->where ( 'setup_activities.disabled', 'f' )->order_by ( 'setup_activities.ordering', 'ASC' )->get ( 'setup_activities' );
		return $query->result ();
	}
	public function detail_activity($key) {
		$query = $this->db->where ( 'key', $key )->get ( 'setup_activities' );
		return $query->row ();
	}
}
