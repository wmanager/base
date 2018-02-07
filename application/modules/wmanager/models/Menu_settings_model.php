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
class Menu_settings_model extends CI_Model {
	
	public function get_all_menus(){		
		$query  = $this->db->select("*")
			->order_by("order","ASC")
			->get("setup_menu");
		$result = $query->result();
		
		return $result;
	}
	
	public function delete_menu($id) {
		if ($this->db->where ( 'id', $id )->delete ( 'setup_menu' )) {
			return true;
		} else {
			return false;
		}
	}
	
	public function add_menu($data) {
		$query = $this->db->select_max("order")
					->get("setup_menu");
		$result = $query->row_array();
		$data['order'] = $result['order'] + 1;
		$data['is_child'] = 'f';		
		$data['access'] = implode(',',$data['access']);
		if($this->db->insert('setup_menu', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function add_child_menu($data, $id) {
		$query = $this->db->select_max("child_order")
			->where('parent_id', $id)
			->get("setup_menu");
		
		$result = $query->row_array(); 
		if(count($result) == 0) {
			$data['child_order'] = 1;
		} else {
			$data['child_order'] = $result['child_order'] + 1;
		}

		$data['is_child'] = 't';
		$data['parent_id'] = $id;
		$data['access'] = implode(',',$data['access']);
		if($this->db->insert('setup_menu', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	
	public function get_single_menu($id) {
		$query  = $this->db->select("*")
		->order_by("order","ASC")
		->where("id", $id)
		->get("setup_menu");
		$result = $query->row();
		return $result;
	}
	
	public function edit($data, $id, $parent_id) {	

		
		if($parent_id)
			$data['is_child'] = 'f';
		$data['access'] = implode(',',$data['access']);

		if($this->db->where('id', $id)->update('setup_menu', $data)) {

			return TRUE;
		} else {
			return FALSE;
		}
	
	}
}	
?>	