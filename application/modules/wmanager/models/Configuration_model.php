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
class Configuration_model extends CI_Model {
	
	public function get_configs($module = null){
		
		if($module != null){
			$this->db->where("module = '$module'");
		}
		
		$query  = $this->db->select("*")->order_by("module","ASC")->order_by("id","ASC")->get("setup_config");
		$result = $query->result();
		return $result;
	}
	
	public function get_modules(){
		
		$query = $this->db->select("module")->group_by("module")->order_by("module","ASC")->get("setup_config");
		$result = $query->result();
		
		if($query->num_rows() > 0){
			$return_array = array();
			foreach($result as $item){
				$return_array[] = $item->module;
			}
			return $return_array;
		}else{
			return array();
		}
	}
	
	public function save_config($data){
		if(count($data)>0){
			foreach ($data as $key => $value){
				$update_data = array(
					"value" => $value	
				);
				$this->db->where("key",$key)->update("setup_config",$update_data);
			}
			return true;
		}else{
			return false;
		}
	}
	
	public function get_config_values($module){
		if($module != ''){
			$this->db->where("module = '$module'");
		}
		
		$query = $this->db->select("key,value")->get("setup_config");
		$result = $query->result();
		
		if($query->num_rows() > 0){
			$return_array = array();
			foreach ($result as $item){
				$key = $item->key;
				
				$return_array[$key] = $item->value;
			}
			return $return_array;
		}else{
			return array();
		}
	}
	
}	
?>	