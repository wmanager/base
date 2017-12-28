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
class Engine extends CI_Model {
	public function fetch_scenario($activity_type) {
		$query = $this->db->where ( 'id_activity', $activity_type )->get ( 'setup_activities_exits' );
		return $query->result ();
	}
	public function get_thread_vars($thread_id, $activity_id) {
		$query = $this->db->where ( 'id_thread', $thread_id )->where ( 'id_activity', $activity_id )->get ( 'vars' );
		return $query->result ();
	}
	public function get_activity_vars($thread_id, $activity_id) {
		$query = $this->db->where ( 'id_thread', $thread_id )->where ( 'id_activity', $activity_id )->get ( 'vars' );
		return $query->result ();
	}
	public function get_action($thread_id, $activity_id, $status, $result) {
		// $query = $this->db->where
	}
	public function get_vars_history($thread_id, $activity_id) {
		$query = $this->db->where ( 'id_activity', $activity_id )->or_where ( 'caller_activity', $activity_id )->order_by ( 'created', 'desc' )->get ( 'history' );
		
		return $query->result ();
	}
	public function get_activity_type($thread_id, $activity_id) {
		// $query = $this->db->select('activities.*, setup_activities.id as activity_type')->join('setup_activities','setup_activities.key = activities.type')->where('id_thread',$thread_id)->where('id',$activity_id)->get('activities');
		$query = $this->db->query ( 'select a.*,sa.id as activity_type from setup_activities sa JOIN setup_processes sp ON sa.id_process=sp.id JOIN threads t ON t.type=sp.key JOIN activities a ON a.id_thread=t.id where a.id=' . $activity_id . ' and t.id=' . $thread_id . ' and sa.key = (select aa.type from activities aa where aa.id= ' . $activity_id . ')' );
		return $query->row ();
	}
}