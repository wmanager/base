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
class Product extends CI_Model {
	
	public function get(){
		$query = $this->db->select("*")->get("products");
		return $query->result_array();
	}
	
	public function delete_product($id){
		$query = $this->db->where("id",$id)->delete("products");
		
		return $query;
	}
	
	public function add_product($data){
		//clean data
		$insert_data = $this->clean_empty($data);
		
		//check product title already exist
		$check1 = $this->db->where("title = '".$data["title"]."'")->get("products");
		
		//check code already exists
		$check2 = $this->db->where("product_code = '".$data["product_code"]."'")->get("products");
		
		if($check1->num_rows() > 0){
			$return_array['status'] = FALSE;
			$return_array['message'] = 'Title already exists';
			return $return_array;
		}
		
		if($check2->num_rows() > 0){
			$return_array['status'] = FALSE;
			$return_array['message'] = 'Product code already exists';
			return $return_array;
		}
		
		
		
		$add = $this->db->insert("products",$insert_data);
		if($add){
			$return_array['status'] = TRUE;
			$return_array['message'] = 'Product added successfully';
		}else{
			$return_array['status'] = FALSE;
			$return_array['message'] = 'Failed to add product. Please try after sometime.';
		}
		
		return $return_array;
	}
	
	public function clean_empty($data){
		
		if(count($data)>0){
			
			foreach($data  as $key=>$item){
				if($item == '' || $item == NULL){
					unset($data[$key]);
				}
			}
			
			$data['modified'] = date("d-m-y H:i:s");
			$data['created'] = date("d-m-y H:i:s");
			$data['created_by'] = $this->ion_auth->user ()->row ()->id;
			$data['modified_by'] = $this->ion_auth->user ()->row ()->id;
			return $data;
		}else{
			return array();
		}
	}
	
	public function get_product_detail($id){
		
		if($id == NULL){
			return array();
		}
		
		$query = $this->db->where("id",$id)->get("products");
		return $query->row();
	}
	
	public function edit_product($id,$data){
		//clean data
		$update_data = $this->clean_empty($data);
		
		//check product title already exist
		$check1 = $this->db->where("id != $id")->where("title = '".$data["title"]."'")->get("products");
		
		//check code already exists
		$check2 = $this->db->where("id != $id")->where("product_code = '".$data["product_code"]."'")->get("products");
		
		if($check1->num_rows() > 0){
			$return_array['status'] = FALSE;
			$return_array['message'] = 'Title already used by another product.';
			return $return_array;
		}
		
		if($check2->num_rows() > 0){
			$return_array['status'] = FALSE;
			$return_array['message'] = 'Product code used by another product.';
			return $return_array;
		}
		
		
		$edit = $this->db->where("id",$id)->update("products",$update_data);
		if($edit){
			$return_array['status'] = TRUE;
			$return_array['message'] = 'Product added successfully.';
		}else{
			$return_array['status'] = FALSE;
			$return_array['message'] = 'Failed to add product. Please try after sometime.';
		}
		
		return $return_array;
	}
}