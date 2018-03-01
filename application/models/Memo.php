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
class Memo extends CI_Model {
	public function get_memo($company = NULL, $field = 'soprall_company_id') {
		
		// $query = $this->db->where('activity_id',$act_id)->get('memos');
		$query = $this->db->distinct ( 'memos.id' )->select ( 'memos.*' )->join ( 'threads', 'threads.id = memos.thread_id' )->join ( 'be', 'be.id = threads.be' )->join ( 'users', "users.id_company = be.$field" )->where ( 'users.id_company', $company )->order_by ( 'id', 'ASC' )->get ( 'memos' );
		$result = $query->result ();
		// print $this->db->last_query();
		return $result;
		
		return false;
	}
	public function get_followup($activity = NULL, $thread = NULL, $trouble = NULL) {
		if (($activity) && ($thread)) {
			$query_activity = $this->db->select ( 'activities.type' )->where ( 'activities.id', $activity )->where ( 'activities.id_thread', $thread )->get ( 'activities' );
			$result = $query_activity->row_array ();
			if (count ( $result ) > 0) {
					if ($activity)
						$this->db->where ( 'memos.activity_id', $activity );
					if ($thread)
						$this->db->where ( 'memos.thread_id', $thread );
					if ($trouble)
						$this->db->where ( 'memos.trouble_id', $trouble );				
			}
		} else {
			if ($activity)
				$this->db->where ( 'memos.activity_id', $activity );
			if ($thread)
				$this->db->where ( 'memos.thread_id', $thread );
			if ($trouble)
				$this->db->where ( 'memos.trouble_id', $trouble );
		}
		
		$query = $this->db->select ( 'memos.*, users.first_name, users.last_name' );
		$query = $this->db->where ( 'memos.type', 'FOLLOWUP' );
		$query = $this->db->join ( 'users', "users.id = memos.created_by", 'left' );
		$query = $this->db->order_by ( 'memos.id', 'ASC' )->get ( 'memos' );
		
		return $query->result ();
	}
}
