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
class Setup_acl extends CI_Model {
	
	public function get_roles(){
		$query = $this->db->select("setup_roles.*,(users.first_name ||' '|| users.last_name) as user_name")
						->join("users","users.id = setup_roles.modified_by",'left')
						->get('setup_roles');
		
		$result = $query->result_array();
		
		return $result;
	}
	
	public function get_parent_roles($id = null){
		if($id != null){
			$this->db->where("id != $id");
		}
		$query  = $this->db->select("*")
							->where("parent_role IS NULL")
							->get("setup_roles");
		
		$result['-'] = 'Select Role'; 
		if($query->num_rows() > 0){
			foreach ($query->result() as $item){
				$result[strtoupper($item->key)] = ucfirst(strtolower($item->key)); 
			}
		}
		
		return $result;
	}
	
	public function add_role($data){
	
		$data 		 = $this->clean_empty($data);
		$data['key'] = strtoupper($data['key']);
		
		$add = $this->db->insert("setup_roles",$data);
		
		if($add){
			$return['status'] = true;
			$return['message'] = 'Role was added successfully.';
		}else{
			$return['status'] = false;
			$return['message'] = 'Something went wrong. Failed to add role.';
		}
		return $return;
	}
	
	public function edit_role($id,$data){
		
		$data = $this->clean_empty($data);
		$data['key'] = strtoupper($data['key']);
		$edit = $this->db->where("id",$id)->update("setup_roles",$data);
		
		if($edit){
			$return['status'] = true;
			$return['message'] = 'Role was updated successfully.';
		}else{
			$return['status'] = false;
			$return['message'] = 'Something went wrong. Failed to update role.';
		}
		return $return;
	}
	
	public function delete_role($id = NULL){
		
		if($id  == NULL){
			return array(
				"status" => false,
				"message"=> 'ID was not provided for the delete' 	
			);
		}
		
		$delete = $this->db->where("id",$id)->delete("setup_roles");
		
		if($delete){
			$return['status'] = true;
			$return['message'] = 'Role was deleted successfully.';
		}else{
			$return['status'] = false;
			$return['message'] = 'Something went wrong. Failed to delete role.';
		}
		
		return $return;
	}
	
	public function get_role_detail($id){
		$query = $this->db->select('*')->where("id",$id)->get("setup_roles");
		return $query->row();
	}
	
	public function clean_empty($data,$add_others = true){
	
		if(count($data)>0){
				
			foreach($data  as $key=>$item){
				if($item == '' || $item == NULL || $item == '-'){
					unset($data[$key]);
				}
			}
			
			if($add_others){
				$data['modified'] = date("Y-m-d H:i:s");
				$data['created'] = date("Y-m-d H:i:s");
				$data['created_by'] = $this->ion_auth->user()->row ()->id;
				$data['modified_by'] = $this->ion_auth->user()->row ()->id;
			}
			return $data;
		}else{
			return array();
		}
	}
	
	public function get_groups(){
		$query = $this->db->select("groups.*")
						->get('groups');
		
		$result = $query->result_array();
		
		return $result;
	}
	
	public function add_group($data){
	
		$data = $this->clean_empty($data,false);
		$data['name'] = strtoupper($data['name']);
		$add = $this->db->insert("groups",$data);
	
		if($add){
			$return['status'] = true;
			$return['message'] = 'User group was added successfully.';
		}else{
			$return['status'] = false;
			$return['message'] = 'Something went wrong. Failed to add user group.';
		}
		return $return;
	}
	
	public function edit_group($id,$data){
	
		$data = $this->clean_empty($data,false);
		$edit = $this->db->where("id",$id)->update("groups",$data);
	
		if($edit){
			$return['status'] = true;
			$return['message'] = 'Group was updated successfully.';
		}else{
			$return['status'] = false;
			$return['message'] = 'Something went wrong. Failed to update group.';
		}
		return $return;
	}
	
	public function delete_group($id = NULL){
	
		if($id  == NULL){
			return array(
					"status" => false,
					"message"=> 'ID was not provided for the delete'
			);
		}
	
		$delete = $this->db->where("id",$id)->delete("groups");
	
		if($delete){
			$return['status'] = true;
			$return['message'] = 'User group was deleted successfully.';
		}else{
			$return['status'] = false;
			$return['message'] = 'Something went wrong. Failed to delete group.';
		}
	
		return $return;
	}
	
	public function get_group_detail($id){
		$query = $this->db->select('*')->where("id",$id)->get("groups");
		return $query->row();
	}
}