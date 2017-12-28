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
class Admin_dashboard extends CI_Model {
	
	public function get_engine_details(){
		$return_array = array();
		
		$today = date("Y-m-d");
		$yesterday = date('Y-m-d',strtotime("-1 days"));	
		
		//get engine calls
		$query = $this->db->select("count(id)")->where("action = 'ENGINE'")->get("history");
		$result = $query->row();
		$return_array['engine'] =$result->count;
		
		//engine break
		$query = $this->db->select("count(id)")->get("troubles");
		$result = $query->row();
		$return_array['total_trouble'] =$result->count;
		
		//total activities
		$query = $this->db->select("count(id)")->get("threads");
		$result = $query->row();
		$return_array['total_threads'] =$result->count;
		
		//total engine failed call
		$query = $this->db->select("count(id)")->get("activities");
		$result = $query->row();
		$return_array['total_activities'] =$result->count;
		
		return $return_array;
	}
	
	public function get_graph_details(){
		$return_array = array();
		$today = date("Y-m-d");
		$last_year = date('Y-m-d',strtotime("-11 months"));
		
		//get_activity
		$query = $this->db->select("count(id),EXTRACT(MONTH FROM created) as month,EXTRACT(YEAR FROM created) as year")->where("(created <= '$today'  AND created >= '$last_year')")->group_by("EXTRACT(MONTH FROM created)")->group_by("EXTRACT(YEAR FROM created)")->order_by("EXTRACT(YEAR  FROM created)",'ASC')->order_by("EXTRACT(MONTH  FROM created)",'ASC')->get("activities");
		$return_array['activities'] = $query->result();
		
		$query = $this->db->select("count(id),EXTRACT(MONTH FROM created) as month,EXTRACT(YEAR FROM created) as year")->where("(created <= '$today'  AND created >= '$last_year')")->group_by("EXTRACT(MONTH FROM created)")->group_by("EXTRACT(YEAR FROM created)")->order_by("EXTRACT(YEAR  FROM created)",'ASC')->order_by("EXTRACT(MONTH  FROM created)",'ASC')->get("threads");
		$return_array['threads'] = $query->result();
		
		$query = $this->db->select("count(id),EXTRACT(MONTH FROM created) as month,EXTRACT(YEAR FROM created) as year")->where("(created <= '$today'  AND created >= '$last_year')")->group_by("EXTRACT(MONTH FROM created)")->group_by("EXTRACT(YEAR FROM created)")->order_by("EXTRACT(YEAR  FROM created)",'ASC')->order_by("EXTRACT(MONTH  FROM created)",'ASC')->get("troubles");
		$return_array['troubles'] = $query->result();
		
		return $return_array;
		
	}
	
	public function get_engine_data(){
		
		$query = $this->db->select("*")->order_by("id","DESC")->limit(10)->get("history");
		return $query->result();
	}
}