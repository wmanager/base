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

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class User_model extends CI_Model {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	

	
	/**
	 * create_admin_user function.
	 * 
	 * @access public
	 * @param string $username
	 * @param string $email
	 * @param string $password
	 * @return bool
	 */
	public function create_admin_user($data, $user_id) {
	
		if($this->db->insert('setup_users_roles', array('user_id' => $user_id,'role_id' => $data['role_id']))) {
			$result = true;
		}
		if($this->db->insert('setup_company_roles', array('company_id' => $data['id_company'],'role_key' => $data['role_id']))) {
			$result = true;
		}
		if($this->db->insert('users_groups', array('user_id' => $user_id,'group_id' => $data['group_id']))) {
			$result = true;
		}
		return $result;
		
	}
	

	public function create_groups($group) {
		
		$data = array(			
			'name'   => $group,
			'description' => ''
		);		
		$this->db->insert('groups', $data);
		return $this->db->insert_id();
		
	}
	
	public function create_role() {
		$data = array(
				'key'   => 'ADMIN',
				'disabled' => 'f'
		);
		$this->db->insert('setup_roles', $data);	
		return $this->db->insert_id();
	}
	
	public function create_company($name) {
		$data = array(
				'name'   => $name
		);
		$this->db->insert('companies', $data);
		return $this->db->insert_id();
	}
	
	/**
	 * hash_password function.
	 * 
	 * @access private
	 * @param string $password
	 * @return string|bool could be a string on success, or bool false on failure
	 */
	private function hash_password($password) {
		
		return password_hash($password, PASSWORD_BCRYPT);
		
	}
	
	/**
	 * verify_password_hash function.
	 * 
	 * @access private
	 * @param string $password
	 * @param string $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash) {
		
		return password_verify($password, $hash);
		
	}

	
}
