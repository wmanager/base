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
class Extension_model extends CI_Model {
	public function get_extensions() {
		$query = $this->db->select ( "*" )->get ( "extensions" );
		$result = $query->result ();
		
		return $result;
	}
	
	public function add_install_log($extension_id,$instruction,$message,$status){
		
		$data_array = array(
				"extension_id" => $extension_id,
				"instruction"  => $instruction,
				"message"	   => $message,
				"status"		=> $status,
		);
		
		if($this->db->insert("extension_installer_log",$data_array)){
			return true;
		}else{
			return false;
		}
	}
	
	public function get_extension_details($id){
		
		$query = $this->db->where("status = 'downloaded'")->where("id",$id)->get("extensions");
		
		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return FALSE;
		}
	}
	
	public function updated_extension_details($id){
		$data = array(
				'status' => 'installed'
		);
		$this->db->where("id",$id);
		$this->db->update('extensions',$data);
		return true;
	}
	
	public function execute_query($query){
		if($query == ''){
			return false;
		}
		
		if($this->db->query($query)){
			return true;
		}else{
			return false;
		}
	}
	
	public function insert_extension($data) {
		$data_array = array(
				"description" => $data['description'],
				"status"  => 'downloaded',
				"key" => $data['key'],
				"file_name" => $data['file_name'],
				"module_name" => $data['name'],
				"created" => date('Y-m-d')
		);
		$query = $this->db->select("key")->where("key", $data['key'])->get("extensions");
		$result = $query->row_array();
		if(count($result) == 0) {
			if($this->db->insert("extensions",$data_array)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
}