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
class Company extends CI_Model {
	public function get($limit, $offset = 0) {
		if ($this->input->post ( 'status' ) && $this->input->post ( 'status' ) != '-') {
			$this->session->set_userdata ( 'filter_status', $this->input->post ( 'status' ) );
		} else if ($this->input->post ( 'status' ) && $this->input->post ( 'status' ) == '-') {
			$this->session->unset_userdata ( 'filter_status' );
		}
		if ($this->input->post ( 'company' ) != '') {
			$this->session->set_userdata ( 'filter_company', $this->input->post ( 'company' ) );
		} else if (isset ( $_POST ['company'] ) && $_POST ['company'] == '') {
			$this->session->unset_userdata ( 'filter_company' );
		}
		$this->db->flush_cache ();
		if ($this->session->userdata ( 'filter_company' )) {
			$this->db->where ( "(name ILIKE '%" . $this->session->userdata ( 'filter_company' ) . "%' OR contact ILIKE '%" . $this->session->userdata ( 'filter_company' ) . "%')" );
		}
		if ($this->session->userdata ( 'filter_status' )) {
			$this->db->where ( 'active', $this->session->userdata ( 'filter_status' ) );
		}
		$query = $this->db->select ( 'companies.*,(SELECT com.name from companies com where com.id=companies.parent_company) as parent_company_name' )->limit ( $limit, $offset )->order_by ( 'name', 'asc' )->get ( 'companies' );
		$result = $query->result ();
		$this->db->flush_cache ();
		return $result;
	}
	public function total() {
		if ($this->session->userdata ( 'filter_company' )) {
			$this->db->where ( "(name ILIKE '%" . $this->session->userdata ( 'filter_company' ) . "%' OR contact ILIKE '%" . $this->session->userdata ( 'filter_company' ) . "%')" );
		}
		if ($this->session->userdata ( 'filter_status' )) {
			$this->db->where ( 'active', $this->session->userdata ( 'filter_status' ) );
		}
		$query = $this->db->get ( 'companies' );
		return $query->num_rows ();
	}
	public function get_roles() {
		$query = $this->db->select ( 'key,id' )->where ( 'disabled', 'f' )->get ( 'setup_roles' );
		return $query->result ();
	}
	public function add() {
		$data = $this->input->post ();

		$sharings = $data ['sharing'];
		$roles = $data ['role'];
		$operatives = $data ['operative'];
		
		unset ( $data ['sharing'] );
		unset ( $data ['role'] );
		unset ( $data ['operative'] );
		
		$this->session->unset_userdata ( 'upload_errors' );
		
		if (isset ( $_FILES ['icon'] ['tmp_name'] ) && $_FILES ['icon'] ['tmp_name'] != '') {
			
			$this->load->helper ( 'string' );
			
			$config ['upload_path'] = './uploads/companies/';
			$config ['allowed_types'] = 'gif|jpg|png';
			$config ['max_size'] = '500';
			// $config['max_width'] = '800';
			// $config['max_height'] = '800';
			$config ['file_name'] = random_string ( 'unique' );
			
			$this->load->library ( 'upload', $config );
			
			if (! $this->upload->do_upload ( 'icon' )) {
				$this->session->set_userdata ( 'upload_errors', $this->upload->display_errors () );
				return false;
			} else {
				$uploaded = $this->upload->data ();
				
				$config ['image_library'] = 'gd2';
				$config ['source_image'] = $uploaded ['full_path'];
				$config ['create_thumb'] = FALSE;
				$config ['maintain_ratio'] = FALSE;
				$config ['width'] = 90;
				$config ['height'] = 90;
				
				$this->load->library ( 'image_lib', $config );
				
				$this->image_lib->resize ();
				
				$data ['icon'] = $uploaded ['file_name'];
			}
		}
		
		$data ['created_by'] = $this->ion_auth->user ()->row ()->id;
		$data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		
		unset ( $data ['holding_autocomplete'] );
		
		$data = clean_array_data ( $data );
		
		if ($this->db->insert ( 'companies', $data )) {
			$company_id = $this->db->insert_id ();
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', 'The company' . $data ['name'] . ' has been inserted correctly.' );
			/**
			 * Insert company_id to setup company roles table *
			 */
			foreach ( $roles as $key => $value ) {
				$operative = '';
				($operatives [$key]) ? $operative = $operatives [$key] : $operative = 'N';
				$data_role ['company_id'] = $company_id;
				$data_role ['role_key'] = $value;
				$data_role ['operative_yn'] = $operative;
				$data_role ['operators_sharing'] = $sharings [$key];
				$data_role ['created_by'] = $this->ion_auth->user ()->row ()->id;
				$data_role ['modified_by'] = $this->ion_auth->user ()->row ()->id;
				$this->db->insert ( 'setup_company_roles', $data_role );
			}
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'An error occurred, could not be inserted ' . $data ['name'] . ', please try again.' );
			return false;
		}
	}
	public function edit($id) {
		$data = $this->input->post ();

		$sharings = $data ['sharing'];
		$roles = $data ['role'];
		$operatives = $data ['operative'];
		
		unset ( $data ['sharing'] );
		unset ( $data ['role'] );
		unset ( $data ['operative'] );
		$this->session->unset_userdata ( 'upload_errors' );

		if (isset ( $_FILES ['icon'] ['tmp_name'] ) && $_FILES ['icon'] ['tmp_name'] != '') {
			
			$this->load->helper ( 'string' );
			
			$config ['upload_path'] = './uploads/companies/';
			$config ['allowed_types'] = 'gif|jpg|png';
			$config ['max_size'] = '500';
			// $config['max_width'] = '200';
			// $config['max_height'] = '200';
			$config ['file_name'] = random_string ( 'unique' );
			
			$this->load->library ( 'upload', $config );
			
			if (! $this->upload->do_upload ( 'icon' )) {
				$this->session->set_userdata ( 'upload_errors', $this->upload->display_errors () );
				return false;
			} else {
				$uploaded = $this->upload->data ();
				
				$config ['image_library'] = 'gd2';
				$config ['source_image'] = $uploaded ['full_path'];
				$config ['create_thumb'] = FALSE;
				$config ['maintain_ratio'] = FALSE;
				$config ['width'] = 90;
				$config ['height'] = 90;
				
				$this->load->library ( 'image_lib', $config );
				
				$this->image_lib->resize ();
				
				$data ['icon'] = $uploaded ['file_name'];
			}
		}
		
		$data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		$data ['modified'] = date ( 'Y-m-d H:i:s' );
		
		unset ( $data ['holding_autocomplete'] );

		$data = clean_array_data ( $data );

		if ($this->db->where ( 'id', $id )->update ( 'companies', $data )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', 'The company' . $data ['name'] . ' has been updated correctly.' );
			/**
			 * Update company role *
			 */
			$this->db->where ( 'company_id', $id )->delete ( 'setup_company_roles' );
			foreach ( $roles as $key => $value ) {
				$operative = '';
				($operatives [$key]) ? $operative = $operatives [$key] : $operative = 'N';
				$data_role ['company_id'] = $id;
				$data_role ['role_key'] = $value;
				$data_role ['operative_yn'] = $operative;
				$data_role ['operators_sharing'] = $sharings [$key];
				$data_role ['created_by'] = $this->ion_auth->user ()->row ()->id;
				$data_role ['modified_by'] = $this->ion_auth->user ()->row ()->id;
				$this->db->insert ( 'setup_company_roles', $data_role );
			}
			
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'An error occurred, could not be updated' . $data ['name'] . ', please try again.' );
			return false;
		}
	}
	public function get_single($id) {
		$query = $this->db->select ( 'companies.*, c.name as holding_autocomplete, cr.role_key as company_role' )->join ( 'companies c', 'c.id = companies.holding', 'left' )->join ( 'setup_company_roles cr', 'cr.company_id = companies.id', 'left' )->where ( 'companies.id', $id )->get ( 'companies' );
		$company = $query->row ();
		$query = $this->db->where ( 'company_id', $id )->get ( 'setup_company_roles' );
		$companyrole = $query->result ();
		foreach ( $companyrole as $role ) {
			$company->role [$role->role_key] = $role->role_key;
			$company->operative [$role->role_key] = $role->operative_yn;
			$company->sharing [$role->role_key] = $role->operators_sharing;
		}
		return $company;
	}
	public function delete($id) {
		$data = $this->get_single ( $id );
		if (count ( $data ) > 0) {
			
			if ($this->db->where ( 'id', $id )->delete ( 'companies' )) {
				log_message ( 'DEBUG', $this->db->last_query () );
				$this->db->where ( 'company_id', $id )->delete ( 'setup_company_roles' );
				$this->session->set_flashdata ( 'growl_success', 'The company ' . $data->name . ' has been removed.' );
				return true;
			} else {
				log_message ( 'ERROR', $this->db->last_query () );
				$this->session->set_flashdata ( 'growl_error', 'There was an error, it could not be removed ' . $data->name . ', please try again.' );
				return false;
			}
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'You do not have sufficient permissions to remove this record.' );
			return false;
		}
	}
	public function get_users($id) {
		$query = $this->db->select ( "users.*, users.first_name || ' ' || users.last_name as user_name" )->where ( 'id_company', $id )->get ( 'users' );
		return $query->result ();
	}
	public function get_company_group($id) {
		$query = $this->db->query ( 'SELECT DISTINCT  "s"."role_key","r"."key" FROM "setup_company_roles" s JOIN "setup_roles" r ON "s"."role_key" = "r"."id" WHERE "s"."company_id" = ' . $id );
		return $query->result ();
	}
	public function get_users_group_list($id = NULL) {
		$query = $this->db->from ( 'groups s' )->get ();
		return $query->result ();
	}
	public function add_user($id) {
		$user_roles = $this->input->post ( 'role' );
		$users_groups = $this->input->post ( 'usergroup' );
		$row_cnt = count ( $user_roles );
		$row_group_cnt = count ( $users_groups );
		$userrole = array ();
		if ($row_cnt > 0) {
			foreach ( $user_roles as $role ) {
				$userrole [] = $role;
			}
		}
		
		$usersgroups = array ();
		if ($row_group_cnt > 0) {
			foreach ( $users_groups as $group ) {
				$usersgroups [] = $group;
			}
		}
		$this->session->unset_userdata ( 'errors' );
		$query = $this->db->where ( 'id', $id )->get ( 'companies' );
		if ($query->num_rows () == 0)
			return false;
		
		$company = $query->row ();
		
		$data ['icon'] = NULL;
		
		if (isset ( $_FILES ['icon'] ['tmp_name'] ) && $_FILES ['icon'] ['tmp_name'] != '') {
			
			$this->load->helper ( 'string' );
			
			$config ['upload_path'] = './uploads/users/';
			$config ['allowed_types'] = 'gif|jpg|png';
			$config ['max_size'] = '500';
			$config ['max_width'] = '800';
			$config ['max_height'] = '800';
			$config ['file_name'] = random_string ( 'unique' );
			
			$this->load->library ( 'upload', $config );
			
			if (! $this->upload->do_upload ( 'icon' )) {
				$this->session->set_flashdata ( 'upload_errors', $this->upload->display_errors () );
				return false;
			} else {
				$uploaded = $this->upload->data ();
				
				$config ['image_library'] = 'gd2';
				$config ['source_image'] = $uploaded ['full_path'];
				$config ['create_thumb'] = FALSE;
				$config ['maintain_ratio'] = FALSE;
				$config ['width'] = 90;
				$config ['height'] = 90;
				
				$this->load->library ( 'image_lib', $config );
				
				$this->image_lib->resize ();
				
				$data ['icon'] = $uploaded ['file_name'];
			}
		}
		
		$username = $this->input->post ( 'username' );
		$password = $this->input->post ( 'password' );
		$email = $this->input->post ( 'email' );
		
		$additional_data = array (
				'first_name' => $this->input->post ( 'first_name' ),
				'last_name' => $this->input->post ( 'last_name' ),
				'phone' => $this->input->post ( 'phone' ),
				'mobile' => $this->input->post ( 'mobile' ),
				'role1' => $this->input->post ( 'role1' ),
				'active' => $this->input->post ( 'active' ),
				'id_company' => $id,
				'company' => $company->name,
				'created_by' => $this->ion_auth->user ()->row ()->id,
				'modified_by' => $this->ion_auth->user ()->row ()->id,
				'icon' => $data ['icon'] 
		);
		
		if ($user = $this->ion_auth->register ( $username, $password, $email, $additional_data, $userrole, $usersgroups )) {
			$this->session->set_flashdata ( 'growl_success', 'L\'utente ' . $this->input->post ( 'first_name' ) . ' ' . $this->input->post ( 'last_name' ) . ' è stata creato.' );
			return $user;
		} else {
			// $this->session->set_flashdata('growl_error', 'Si è verificato un errore, impossibile salvare l\'utente.');
			$this->session->set_userdata ( 'errors', $this->ion_auth->errors () );
			return $user;
		}
	}
	public function get_user_company_group($company, $id) {
		$query = $this->db->distinct ( 's.id' )->where ( 's.company_id', $company )->from ( 'setup_company_roles s' )->join ( 'setup_roles r', 's.role_key = r.id' )->join ( 'groups g', 'g.name = r.key' )->get ();
		return $query->result ();
	}
	public function get_user_group($id) {
		$query = $this->db->where ( 'user_id', $id )->get ( 'users_groups' );
		return $query->result ();
	}
	public function get_user_roles($id) {
		$query = $this->db->where ( 'user_id', $id )->get ( 'setup_users_roles' );
		return $query->result ();
	}
	public function edit_user($company, $id) {
		$user_roles = $this->input->post ( 'role' );
		$row_cnt = 0;
		$user_groups = $this->input->post ( 'usergroup' );
		$row_usergroup_cnt = 0;
		if ($user_roles) {
			$row_cnt = count ( $user_roles );
		}
		$userrole = array ();
		if ($row_cnt > 0) {
			foreach ( $user_roles as $role ) {
				$userrole [] = $role;
			}
		}
		if ($user_groups) {
			$row_usergroup_cnt = count ( $user_groups );
		}
		$usergroups = array ();
		if ($row_usergroup_cnt > 0) {
			foreach ( $user_groups as $role ) {
				$usergroups [] = $role;
			}
		}
		// Update groups
		if (! empty ( $usergroups )) {
			
			$query = $this->db->select ( 'group_id' )->where ( 'user_id', $id )->get ( 'users_groups' );
			$existgroup = $query->result ();
			
			// $row_cnt = $query->num_rows;
			
			$existgroups = array ();
			
			if (count ( $existgroup ) > 0) {
				foreach ( $existgroup as $role ) {
					$existgroups [] = $role->group_id;
				}
			}
			
			if (count ( $existgroups ) > 0) {
				if (count ( $existgroups ) != count ( $usergroups )) {
					$this->db->where ( 'user_id', $id )->delete ( 'users_groups' );
					foreach ( $usergroups as $group ) {
						if ($group > 0)
							$data = array (
									'group_id' => ( int ) $group,
									'user_id' => ( int ) $id 
							);
						$this->db->insert ( 'users_groups', $data );
					}
				} else {
					if (count ( array_diff ( $existgroups, $usergroups ) ) > 0) {
						$this->db->where ( 'user_id', $id )->delete ( 'users_groups' );
						foreach ( $usergroups as $group ) {
							if ($group > 0)
								$data = array (
										'group_id' => ( int ) $group,
										'user_id' => ( int ) $id 
								);
							$this->db->insert ( 'users_groups', $data );
						}
					}
				}
			} else {
				foreach ( $usergroups as $group ) {
					if ($group > 0)
						$data = array (
								'group_id' => ( int ) $group,
								'user_id' => ( int ) $id 
						);
					$this->db->insert ( 'users_groups', $data );
				}
			}
		} else {
			$this->db->where ( 'user_id', $id )->delete ( 'users_groups' );
		}
		// Update roles
		if (! empty ( $userrole )) {
			
			$query = $this->db->select ( 'role_id' )->where ( 'user_id', $id )->get ( 'setup_users_roles' );
			$existgroup = $query->result ();
			$row_cnt = $query->num_rows;
			$existgroups = array ();
			if ($row_cnt > 0) {
				foreach ( $existgroup as $role ) {
					$existgroups [] = $role->role_id;
				}
			}
			if (count ( $existgroups ) > 0) {
				if (count ( $existgroups ) != count ( $userrole )) {
					$this->db->where ( 'user_id', $id )->delete ( 'setup_users_roles' );
					foreach ( $userrole as $role ) {
						if ($role > 0)
							$data = array (
									'role_id' => ( int ) $role,
									'user_id' => ( int ) $id 
							);
						$this->db->insert ( 'setup_users_roles', $data );
					}
				} else {
					if (count ( array_diff ( $existgroups, $userrole ) ) > 0) {
						$this->db->where ( 'user_id', $id )->delete ( 'setup_users_roles' );
						foreach ( $userrole as $role ) {
							if ($role > 0)
								$data = array (
										'role_id' => ( int ) $role,
										'user_id' => ( int ) $id 
								);
							$this->db->insert ( 'setup_users_roles', $data );
						}
					}
				}
			} else {
				foreach ( $userrole as $role ) {
					if ($role > 0)
						$data = array (
								'role_id' => ( int ) $role,
								'user_id' => ( int ) $id 
						);
					$this->db->insert ( 'setup_users_roles', $data );
				}
			}
		} else {
			$this->db->where ( 'user_id', $id )->delete ( 'setup_users_roles' );
		}
		$query = $this->db->where ( 'id', $company )->get ( 'companies' );
		if ($query->num_rows () == 0)
			return false;
		
		$company = $query->row ();
		$data ['icon'] = NULL;
		
		if (isset ( $_FILES ['icon'] ['tmp_name'] ) && $_FILES ['icon'] ['tmp_name'] != '') {
			
			$this->load->helper ( 'string' );
			
			$config ['upload_path'] = './uploads/users/';
			$config ['allowed_types'] = 'gif|jpg|png';
			$config ['max_size'] = '500';
			$config ['max_width'] = '800';
			$config ['max_height'] = '800';
			$config ['file_name'] = random_string ( 'unique' );
			
			$this->load->library ( 'upload', $config );
			
			if (! $this->upload->do_upload ( 'icon' )) {
				$this->session->set_flashdata ( 'upload_errors', $this->upload->display_errors () );
				return false;
			} else {
				$uploaded = $this->upload->data ();
				
				$config ['image_library'] = 'gd2';
				$config ['source_image'] = $uploaded ['full_path'];
				$config ['create_thumb'] = FALSE;
				$config ['maintain_ratio'] = FALSE;
				$config ['width'] = 90;
				$config ['height'] = 90;
				
				$this->load->library ( 'image_lib', $config );
				
				$this->image_lib->resize ();
				
				$data ['icon'] = $uploaded ['file_name'];
			}
		}
		
		$username = $this->input->post ( 'username' );
		$password = $this->input->post ( 'password' );
		$email = $this->input->post ( 'email' );
		
		$additional_data = array (
				'first_name' => $this->input->post ( 'first_name' ),
				'last_name' => $this->input->post ( 'last_name' ),
				'phone' => $this->input->post ( 'phone' ),
				'mobile' => $this->input->post ( 'mobile' ),
				'role1' => $this->input->post ( 'role1' ),
				'active' => $this->input->post ( 'active' ),
				'modified_by' => $this->ion_auth->user ()->row ()->id,
				'modified' => date ( 'Y-m-d H:i:s' ),
				'icon' => $data ['icon'],
				'username' => $username,
				'password' => $password,
				'email' => $email 
		);
		
		if ($data ['icon'] == NULL)
			unset ( $additional_data ['icon'] );
		if ($this->input->post ( 'password' ) == '')
			unset ( $additional_data ['password'] );
		
		if ($user = $this->ion_auth->update ( $id, $additional_data )) {
			$this->session->set_flashdata ( 'growl_success', 'L\'utente ' . $this->input->post ( 'first_name' ) . ' ' . $this->input->post ( 'last_name' ) . ' è stato aggiornato.' );
			return $user;
		} else {
			$this->session->set_flashdata ( 'growl_error', 'Si è verificato un errore, impossibile aggiornare l\'utente.' );
			$this->session->set_flashdata ( 'errors', $this->ion_auth->errors () );
			return $user;
		}
	}
	public function search($param) {
		if ($this->input->post ( 'q' )) {
			$this->db->limit ( 20 );
		}
		$query = $this->db->select ( 'name, id as value, icon' )->where ( 'active', 't' )->where ( "name ILIKE '%" . $param . "%'" )->order_by ( 'name', 'asc' )->get ( 'companies' );
		$result = $query->result ();
		return $result;
	}
	public function search_contract($param, $contract) {
		if ($this->input->post ( 'q' )) {
			$this->db->limit ( 20 );
		}
		
		$query = $this->db->query ( "SELECT name, id as value, icon FROM companies WHERE (id IN(SELECT id_company FROM contracts2companies WHERE id_contract = $contract) OR id IN(SELECT id_company FROM contracts WHERE id = $contract)) AND name ILIKE '%$param%'" );
		$result = $query->result ();
		return $result;
	}


	public function add_key($customer) {
		$data = $this->input->post ();
		
		$data ['created_by'] = $this->ion_auth->user ()->row ()->id;
		$data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		$data ['id_company'] = $customer;
		
		$data = clean_array_data ( $data );
		
		if ($this->db->insert ( 'keys', $data )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', 'The key was created correctly.' );
			return $this->db->insert_id ();
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, the key could not be created, please try again.' );
			return false;
		}
	}
	public function edit_key($customer, $id) {
		$data = $this->input->post ();
		
		$data ['modified'] = 'NOW()';
		$data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		
		$data = clean_array_data ( $data );
		
		if ($this->db->where ( 'id', $id )->where ( 'id_company', $customer )->update ( 'keys', $data )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', 'The key has been changed successfully.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'There was an error, the key could not be changed, please try again.' );
			return false;
		}
	}
	public function get_single_key($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'keys' );
		return $query->row ();
	}
	public function get_by_role($role = NULL) {
		if ($role)
			$this->db->where ( 'setup_roles.key', $role );
		$query = $this->db->select ( 'companies.*' )->join ( 'setup_company_roles', 'setup_company_roles.company_id = companies.id' )->join ( 'setup_roles', 'setup_roles.id = setup_company_roles.role_key' )->get ( 'companies' );
		
		return $query->result ();
	}
	public function company_list() {
		$query = $this->db->get ( 'companies' );
		$cs = $query->result ();
		$arr_rs [0] = 'Select parent company';
		foreach ( $cs as $c ) {
			$arr_rs [$c->id] = $c->name;
		}
		return $arr_rs;
	}
	public function get_setup_roles() {
		$query = $this->db->select ( 'key,key' )->where ( 'disabled', 'f' )->order_by ( 'key' )->get ( 'setup_roles' );
		return $query->result ();
	}
	public function get_active_users($id_company, $role) {
		if ($role != '') {
			$query = $this->db->select ( 'id' )->where ( 'key', $role )->get ( 'setup_roles' );
			$role_id = $query->row ();
		}
		if ($role_id->id > 0) {
			$this->db->where ( 'setup_users_roles.role_id', $role_id->id );
		}
		$query = $this->db->distinct('users.id')->select ( "users.*, users.first_name || ' ' || users.last_name as user_name" );
		$query = $this->db->join ( 'setup_users_roles', 'users.id=setup_users_roles.user_id', 'left	' );
		$query = $this->db->where ( 'active', '1' )->where ( 'id_company', $id_company );
		$query = $this->db->order_by ( 'user_name' )->get ( 'users' );
		return $query->result ();
	}
}
