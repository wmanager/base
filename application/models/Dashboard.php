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
class Dashboard extends CI_Model {	
	public function get_open_troubles() {
		$query = $this->db->select ( "count(troubles.id)" )
					->where ( "troubles.status = 'NEW'" )					
					->group_by ( "troubles.id" )
					->get ( "troubles" );
		return $query->num_rows();
	}
	
	public function get_open_activities() {
		$query = $this->db->select ( "count(activities.id)" )
		->where ( "activities.status = 'OPEN'" )
		->group_by ( "activities.id" )
		->get ( "activities" );
		return $query->num_rows();
	}
	
	public function get_open_threads() {
		$query = $this->db->select ( "count(threads.id)" )
		->where ( "threads.status = 'OPEN'" )
		->group_by ( "threads.id" )
		->get ( "threads" );
		return $query->num_rows();
	}
	
	public function get_contract() {
		$query = $this->db->select ( "count(be.id)" )		
		->group_by ( "be.id" )
		->get ( "be" );
		return $query->num_rows();		
	}
	
	public function get_status() {
		$result = array();
		$query1 = $this->db->select ( "troubles.status, count(troubles.status)" )		
		->group_by ( "troubles.status" )
		->get ( "troubles" );
		$result['troubles'] = $query1->result();		
		return $result;
	}
	
	public function get_memos() {
		$query = $this->db->select ( 'memos.*, users.first_name, users.last_name' );
		$query = $this->db->where ( 'memos.type', 'FOLLOWUP' );		
		$query = $this->db->join ( 'users', "users.id = memos.created_by", 'left' );		
		$query = $this->db->order_by ( 'memos.id', 'ASC' )->get ( 'memos' );
	
		return $query->result ();
	}
	
	public function get_memos_last(){
		$deadline_date = date('Y-m-d', strtotime("+7 days"));
		
		//get threads
		$query = $this->db->select("memos.description, memos.created,memos.notification_date,
									activities.id as activity_id, activities.type as activity_type, troubles.id as trouble_id, setup_troubles_types.title as trouble_title,									
									memos.isdone, (users.first_name ||' '|| users.last_name) as user_name")
							->join("activities","activities.id = memos.activity_id",'left')
							->join("troubles",'memos.trouble_id = troubles.id','left')
							->join("setup_troubles_types",'troubles.type_id = setup_troubles_types.id','left')
							->join("users","memos.created_by = users.id","left")
							->where("((memos.created < '$deadline_date') OR (memos.modified < '$deadline_date'))")
							->limit(20)
							->get('memos');
		
		$result = $query->result_array();		
		return $result;
	}
}
