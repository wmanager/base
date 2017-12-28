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

if (! function_exists ( 'acl' )) {
	function acl($table = NULL) {
		$CI = & get_instance ();

		
		if (! $CI->ion_auth->in_group ( 'admin' )) {
				
			$user_id = $CI->session->userdata ( 'user' )->id;
			$user_role = $CI->session->userdata ( 'user' )->role1;
			$roles = $CI->session->userdata ( 'userdomain' )->role;
			$sharing = $CI->session->userdata ( 'userdomain' )->sharing;
			$company = implode ( ',', $CI->session->userdata ( 'userdomain' )->company );
			$wheres = array ();
			if ($user_role == 'OPERATOR') {
				$where = ' (';
				for($s = 0; $s < count ( $roles ); $s ++) {
					if ($sharing [$s] == 0) {
						$where .= "((" . $table . "_acl.duty_user = $user_id OR $table.created_by = $user_id) AND role_key = '$roles[$s]')";
					} else if ($sharing [$s] == 1) {
						$where .= "((" . $table . "_acl.duty_user = $user_id OR " . $table . "_acl.duty_user IS NULL OR $table.created_by = $user_id) AND role_key = '$roles[$s]')";
					} else if ($sharing [$s] == 2) {
						$where .= "( role_key = '$roles[$s]' OR ($table.created_by = $user_id AND role_key = '$roles[$s]'))";
					}
					if (count ( $roles ) - 1 != $s)
						$where .= " OR ";
				}
				$where .= ' )';
				$wheres [] = $where;
				
				if ($CI->db->field_exists ( 'created_by', $table )) {
					$wheres [] = "(" . $table . "_acl.duty_company = $company OR $table.created_by = $user_id)";
				} else {
					$wheres [] = "(" . $table . "_acl.duty_company = $company )";
				}
			} else if ($user_role == 'CONTROLLER') {
				$wheres [] = $table . "_acl.duty_company IN ($company)";
			}
			
			$company = $CI->db->query ( 'SELECT ' . $table . '_id FROM ' . $table . '_acl LEFT JOIN ' . $table . ' ON ' . $table . '.id = ' . $table . '_acl.' . $table . '_id WHERE (' . implode ( " AND ", $wheres ) . ')' );
			$company = $company->result ();
			
			// echo $CI->db->last_query();
			$companier = array ();
			foreach ( $company as $com ) {
				if ($com->{$table . '_id'} == '')
					continue;
				$companier [] = $com->{$table . '_id'};
			}
			
			$companier = implode ( $companier, ',' );
			if ($companier) {
				return $CI->db->where ( "$table.id IN ($companier)" );
			} else {
				return $CI->db->where ( '(1*1)<>(1*1)' );
			}
		}
	}
	function iterate_acl(&$arr, $father, $contract) {
		$CI = & get_instance ();
		$query = $CI->db->where ( 'domain', get_domain () )->where ( 'id_father', $father )->where ( 'id_contract', $contract )->where ( 'active', 't' )->get ( 'contracts2companies' );
		if ($query->num_rows () > 0) {
			$list = $query->result ();
			foreach ( $list as $company ) {
				$arr [$contract] [] = $company->id_company;
				iterate_acl ( $arr, $company->id_company, $contract );
			}
		}
	}
	function get_holding($company) {
		$CI = & get_instance ();
		$query = $CI->db->where ( 'domain', get_domain () )->where ( 'id', $company )->get ( 'companies' );
		$result = $query->row ();
		return $result->holding;
	}
	function get_company_role() {
		$arr = array ();
		$CI = & get_instance ();
		$user = $CI->session->userdata ( 'user' );
		$CI->db->flush_cache ();
		$query = $CI->db->where ( 'company_id', $user->id_company )->join ( 'setup_roles', 'setup_roles.id = setup_company_roles.role_key' )->get ( 'setup_company_roles' );
		$list = $query->result ();
		foreach ( $list as $role ) {
			$arr [] = $role->key;
		}
		return $arr;
	}
	function acldetails($company, $role, $userid) {
		$CI = & get_instance ();
		$query = $CI->db->select ( 'role_id' )->where ( 'user_id', $userid )->get ( 'setup_users_roles' );
		$userroles = $query->result ();
		$activeroles = array ();
		foreach ( $userroles as $roless ) {
			$activeroles [] = $roless->role_id;
		}
		$activeroles = implode ( ",", $activeroles );
		if ($role == 'OPERATOR') {
			/*
			 * $CI->db->select("companies.id as company,array_to_string(array_agg(setup_roles.key),',') as role, setup_company_roles.operators_sharing as sharing");
			 * $CI->db->from('setup_company_roles');
			 * $CI->db->join('setup_roles', 'setup_roles.id = setup_company_roles.role_key"');
			 * $CI->db->join('companies', 'companies.id = setup_company_roles.company_id"');
			 * $CI->db->where('setup_company_roles.company_id',$company);
			 */
			$query = $CI->db->query ( "SELECT companies.id as company, array_to_string(array_agg(setup_roles.key),',') as role, setup_company_roles.operators_sharing as sharing FROM setup_company_roles JOIN setup_roles ON setup_roles.id = setup_company_roles.role_key JOIN companies ON companies.id = setup_company_roles.company_id WHERE setup_company_roles.company_id =  $company AND setup_company_roles.role_key IN ($activeroles) GROUP BY setup_company_roles.operators_sharing,companies.id " );
			$query->result ();
			if ($query->num_rows () > 0) {
				$lists = $query->result ();
				$roles = array ();
				$sharings = array ();
				foreach ( $lists as $lis ) {
					$role = explode ( ',', $lis->role );
					$roles = array_merge ( $roles, $role );
					for($a = 0; $a < count ( $role ); $a ++)
						$sharings [] = $lis->sharing;
				}
				$lists = array ();
				$lists [0]->company [] = $company;
				$lists [0]->role = $roles;
				$lists [0]->sharing = $sharings;
				$lists = $lists [0];
				if (! $lists) {
					return "Null";
				} else {
					return $lists;
				}
			}
		} else {
			// WITH RECURSIVE children AS (SELECT id, 1 AS depth FROM companies WHERE parent_company = 44 UNION ALL SELECT a.id, depth+1 FROM companies a JOIN children b ON(a.parent_company = b.id) )SELECT * FROM children;
			$all_id = array ();
			$asd = child_company ( $company, $all_id );
			$companiesid = array ();
			foreach ( $asd as $oneid ) {
				array_push ( $companiesid, $oneid ['id'] );
			}
			array_push ( $companiesid, $company );
			// $companiesid = implode(',',$companiesid);
			// $CI->db->select('companies.name as company,setup_roles.key as role, setup_company_roles.operators_sharing as sharing');
			// $CI->db->from('setup_company_roles');
			// $CI->db->join('setup_roles', 'setup_roles.id = setup_company_roles.role_key"');
			// $CI->db->join('companies', 'companies.id = setup_company_roles.company_id"');
			// $CI->db->where('setup_company_roles.company_id',$company);
			
			$query = $CI->db->query ( "SELECT companies.id as company, array_to_string(array_agg(setup_roles.key),',') as role, setup_company_roles.operators_sharing as sharing FROM setup_company_roles JOIN setup_roles ON setup_roles.id = setup_company_roles.role_key JOIN companies ON companies.id = setup_company_roles.company_id WHERE setup_company_roles.company_id =  $company AND setup_company_roles.role_key IN ($activeroles) GROUP BY setup_company_roles.operators_sharing,companies.id " );
			if ($query->num_rows () > 0) {
				$lists = $query->result ();
				$roles = array ();
				$sharings = array ();
				foreach ( $lists as $lis ) {
					$role = explode ( ',', $lis->role );
					$roles = array_merge ( $roles, $role );
					for($a = 0; $a < count ( $role ); $a ++)
						$sharings [] = $lis->sharing;
				}
				$lists = array ();
				if (count ( $companiesid ) > 0)
					$lists [0]->company = $companiesid;
				$lists [0]->role = $roles;
				$lists [0]->sharing = $sharings;
				$lists = $lists [0];
				if (! $lists) {
					return "Null";
				} else {
					return $lists;
				}
			}
		}
	}
	function child_company($c_id, &$all_id) {
		$CI = & get_instance ();
		$query = $CI->db->select ( 'id' )->get_where ( "companies", array (
				"parent_company" => $c_id 
		) );
		if ($query->num_rows () > 0) {
			foreach ( $query->result_array () as $oneid ) {
				if (is_array ( $all_id ))
					array_push ( $all_id, $oneid );
			}
			return child_company ( $oneid ["id"], $all_id );
		} else {
			return $all_id;
		}
	}
	function company_users($company = NULL) {
		$CI = & get_instance ();
		$user = $CI->session->userdata ( 'user' );
		$CI->db->flush_cache ();
		if ($company === NULL) {
			$query = $CI->db->where ( 'id_company', $user->id_company )->where ( 'active', '1' )->get ( 'users' );
		} else {
			$query = $CI->db->where ( 'id_company', $company )->where ( 'active', '1' )->get ( 'users' );
		}
		return $query->result ();
	}
	
	/**
	 * check_page_permission
	 *
	 * call handling from controllers/common/troubles/edit
	 * call handling from controllers/common/accounts/detail
	 *
	 * @param integer $user_id        	
	 * @return boolean
	 */
	function check_page_permission($user_id) {
		if ($user_id != null) {
			$CI = & get_instance ();
			
			$role_details = $CI->ion_auth->get_users_groups ( $user_id )->result ();
			
			if (count ( $role_details ) == 1) {
				if (strtoupper ( $role_details [0]->name ) == 'OPERATION') {
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * check_activity_details_permission
	 *
	 * call handling from controllers/common/activities/detail
	 *
	 * @param object $model_activity_obj        	
	 * @param integer $user_id        	
	 * @param integer $activity_id        	
	 *
	 * @return array
	 */
	function check_activity_details_permission($model_activity_obj, $user_id, $activity_id) {
		$result = array ();
		
		if (($user_id != null) && ($activity_id != null)) {
			
			$activity_details = $model_activity_obj->detail ( $activity_id );
			$activity_role = $activity_details->role;
			
			if ($activity_role == NULL) {
				return $activity_details;
			}
			
			$CI = & get_instance ();
			$role_details = $CI->ion_auth->get_users_groups ( $user_id )->result ();
			
			$user_role_array = array ();
			if (count ( $role_details ) > 0) {
				foreach ( $role_details as $item ) {
					$user_role_array [] = $item->name;
				}
			}
			
			if (($activity_role != null) && ($user_role_array != null)) {
				if (in_array ( $activity_role, $user_role_array ) || in_array ( 'admin', $user_role_array )) {
					return $activity_details;
				} else {
					return $result;
				}
			} else {
				return $result;
			}
		} else {
			return $result;
		}
	}
}
