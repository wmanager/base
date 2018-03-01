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
class Trouble extends CI_Model {
	public function get($limit, $offset = 0) {
		
		// acl('troubles');
		$me = $this->ion_auth->user ()->row ()->id;
		
		if ($this->input->post ( 'clear' )) {
			foreach ( $_POST as $key => $post ) {
					unset ( $_POST [$key] );				
			}
			$array_items = array (
					'filter_trouble_reminder',
					'filter_trouble_created_from',
					'filter_trouble_created_to',
					'filter_accounts',					
					'filter_trouble_status',
					'filter_trouble_result',
					'troubles_type_filter',
					'troubles_sub_type_filter'
			);
			foreach ( $array_items as $item ) {
				$this->session->unset_userdata ( $item );
			}
		} else if ($this->input->post ( 'search' )) {
			$this->session->set_userdata ( 'filter_trouble_reminder', $this->input->post ( 'reminder' ) );
			$this->session->set_userdata ( 'filter_trouble_created_from', $this->input->post ( 'created_from' ) );
			$this->session->set_userdata ( 'filter_trouble_created_to', $this->input->post ( 'created_to' ) );
			$this->session->set_userdata ( 'filter_accounts', $this->input->post ( 'search_account' ) );

			$this->session->set_userdata ( 'filter_trouble_result', $this->input->post ( 'search_result' ) );			
		}
		$this->session->set_userdata ( 'filter_trouble_status', $this->input->post ( 'search_status' ) );

		
		if ($this->input->post ( 'filter_type' ) != '') {
			$this->session->set_userdata ( 'troubles_sub_type_filter', '' );
			$type = $this->input->post ( 'filter_type' );
			if ($this->input->post ( 'filter_type' ) == '-') {
				$type = '';
			}
			$this->session->set_userdata ( 'troubles_type_filter', $type );
		} else if ($this->session->userdata ( 'troubles_type_filter' ) == '') {
			$this->session->set_userdata ( 'troubles_type_filter', '' );
		}
		
		if ($this->input->post ( 'sub_type' ) != '') {
			$type = $this->input->post ( 'sub_type' );
			if ($this->input->post ( 'sub_type' ) == '-') {
				$type = '';
			}
			$this->session->set_userdata ( 'troubles_sub_type_filter', $type );
		} else if ($this->session->userdata ( 'troubles_sub_type_filter' ) == '') {
			$this->session->set_userdata ( 'troubles_sub_type_filter', '' );
		}
		
		$filter1 = $this->session->userdata ( 'filter_accounts' );
		
		if ($filter1) {
			$filter1 = $this->db->escape_like_str ( $filter1 );
			$this->db->where ( "(accounts.first_name ILIKE '%$filter1%' OR accounts.last_name ILIKE '%$filter1%' OR accounts.code ILIKE '%$filter1%')" );
		}


		$filter4 = $this->session->userdata ( 'troubles_type_filter' );
		
		if ($filter4) {
			$filter1 = $this->db->escape_like_str ( $filter4 );
			$this->db->where ( "troubles.type_id = $filter4" );
		}
		
		$filter5 = $this->session->userdata ( 'filter_trouble_reminder' );
		$date = str_replace ( "/", "-", $filter5 );
		$reminder = date ( "Y-m-d", strtotime ( $date ) );
		if ($filter5) {
			$this->db->where ( "memos.start_day = '$reminder'" );
		}
		
		if ($this->session->userdata ( 'filter_trouble_status' ) != '') {
			$filter1 = $this->db->escape_like_str ( $filter4 );
			$this->db->where ( "troubles.status = '" . $this->session->userdata ( 'filter_trouble_status' ) . "'" );
		}
		
		$filter6 = $this->session->userdata ( 'troubles_sub_type_filter' );
		
		if ($filter6) {
			$this->db->where ( "troubles.subtype = '$filter6'" );
		}
		
		$filter7 = $this->session->userdata ( 'filter_trouble_created_from' );
		$date = str_replace ( "/", "-", $filter7 );
		$created_from = date ( "Y-m-d", strtotime ( $date ) );
		if ($filter7) {
			$this->db->where ( "troubles.created >= '$created_from'" );
		}
		
		$filter8 = $this->session->userdata ( 'filter_trouble_created_to' );
		$date = str_replace ( "/", "-", $filter8 );
		$created_to = date ( "Y-m-d", strtotime ( $date ) );
		if ($filter8) {
			$this->db->where ( "troubles.created <= '$created_to'" );
		}
		
		if ($this->session->userdata ( 'filter_trouble_result' ) != '') {
			$filter9 = $this->session->userdata ( 'filter_trouble_result' );
			$filter9 = $this->db->escape_like_str ( $filter9 );
			$this->db->where ( "troubles.result = '" . $filter9 . "'" );
		}
		
		
		$this->db->where ( "troubles.status != 'DRAFT'" );
		
		$query = $this->db->distinct ( 'troubles.id' )
				->select ( "(SELECT COUNT(*) FROM memos WHERE trouble_id = troubles.id AND memos.type = 'FOLLOWUP') as followup,
							(SELECT MIN(start_day) FROM memos WHERE trouble_id = troubles.id AND isdone = 'f' AND 
							memos.type = 'FOLLOWUP') as reminder, 
							accounts.id as accounts_id, accounts.*, be.id, troubles.*, 
							setup_troubles_types.title as type, 
							res_company.name as resp_risoluzione_company, 
							res_user.first_name || ' ' || res_user.last_name as resp_risoluzione_user, 							
							creator.first_name || ' ' || creator.last_name as creator" )
				->join ( 'be', 'be.id = troubles.be_id' )				
				->join ( 'accounts', 'accounts.id = troubles.customer_id' )
				->join ( 'setup_troubles_types', 'setup_troubles_types.id = troubles.type_id' )
				->join ( 'companies res_company', 'res_company.id = troubles.res_duty_company', 'left' )
				->join ( 'users res_user', 'res_user.id = troubles.res_duty_user', 'left' )								
				->join ( 'users creator', 'creator.id = troubles.created_by', 'left' )
				->join ( 'memos', 'memos.trouble_id = troubles.id', 'left' )
				->limit ( $limit, $offset )
				->order_by ( 'troubles.id', 'DESC' )
				->get ( 'troubles' );
		
		$result = $query->result ();

		return $result;
	}

	public function total() {
		// acl('troubles');
		$me = $this->ion_auth->user ()->row ()->id;
		
		$filter1 = $this->session->userdata ( 'filter_accounts' );
		if ($filter1) {
			$filter1 = $this->db->escape_like_str ( $filter1 );
			$this->db->where ( "(accounts.first_name ILIKE '%$filter1%' OR accounts.last_name ILIKE '%$filter1%' OR accounts.code ILIKE '%$filter1%')" );
		}
		
		$filter4 = $this->session->userdata ( 'troubles_type_filter' );
		
		if ($filter4) {
			$filter1 = $this->db->escape_like_str ( $filter4 );
			$this->db->where ( "troubles.type_id = $filter4" );
		}
		
		$filter5 = $this->session->userdata ( 'filter_trouble_reminder' );
		$date = str_replace ( "/", "-", $filter5 );
		$reminder = date ( "Y-m-d", strtotime ( $date ) );
		if ($filter5) {
			$this->db->where ( "memos.start_day = '$reminder'" );
		}
		
		if ($this->session->userdata ( 'filter_trouble_status' ) != '') {
			$filter1 = $this->db->escape_like_str ( $filter4 );
			$this->db->where ( "troubles.status = '" . $this->session->userdata ( 'filter_trouble_status' ) . "'" );
		}
		
		$filter6 = $this->session->userdata ( 'troubles_sub_type_filter' );
		
		if ($filter6) {
			$this->db->where ( "troubles.subtype = '$filter6'" );
		}
		
		
		$filter8 = $this->session->userdata ( 'filter_trouble_created_from' );
		$date = str_replace ( "/", "-", $filter8 );
		$created_from = date ( "Y-m-d", strtotime ( $date ) );
		if ($filter8) {
			$this->db->where ( "troubles.created >= '$created_from'" );
		}
		
		$filter9 = $this->session->userdata ( 'filter_trouble_created_to' );
		$date = str_replace ( "/", "-", $filter9 );
		$created_to = date ( "Y-m-d", strtotime ( $date ) );
		if ($filter9) {
			$this->db->where ( "troubles.created <= '$created_to'" );
		}
		
		if ($this->session->userdata ( 'filter_trouble_result' ) != '') {
			$filter10 = $this->session->userdata ( 'filter_trouble_result' );
			$filter10 = $this->db->escape_like_str ( $filter10 );
			$this->db->where ( "troubles.result = '" . $filter10 . "'" );
		}
		
		
		$this->db->where ( "troubles.status != 'DRAFT'" );
		$query = $this->db->distinct ( 'troubles.id' )->select ( 'be.id, troubles.*' )->join ( 'be', 'be.id = troubles.be_id' )->join ( 'accounts', 'accounts.id = troubles.customer_id' )->join ( 'memos', 'memos.trouble_id = troubles.id', 'left' )->get ( 'troubles' );
		
		return $query->num_rows ();
	}
	public function get_by_customer($customer) {
				
		$this->db->where('troubles.customer_id',$customer);
		$query = $this->db->select("
						accounts.*,
						be.id, 
						troubles.*, 
						setup_troubles_types.title as type, res_company.name as resp_risoluzione_company, 
						res_user.first_name || ' ' || res_user.last_name as resp_risoluzione_user, 												
						creator.first_name || ' ' || creator.last_name as creator")
		->join('be','be.id = troubles.be_id')
		->join('accounts','accounts.id = troubles.customer_id')
		->join('setup_troubles_types','setup_troubles_types.id = troubles.type_id')
		->join('companies res_company','res_company.id = troubles.res_duty_company','left')
		->join('users res_user','res_user.id = troubles.res_duty_user','left')		
		->join('users creator','creator.id = troubles.created_by','left')
		->get('troubles');
		return $query->result();	
	}
	
	public function add($data) {
		$arr = array (
				'type_id' => $data ['type'],
				'subtype' => ! empty ( $data ['subtype'] ) ? $data ['subtype'] : '',
				'description' => ! empty ( $data ['description'] ) ? $data ['description'] : '',
				'deadline' => ! empty ( $data ['deadline'] ) ? $data ['deadline'] : '',
				'status' => ! empty ( $data ['status'] ) ? $data ['status'] : '',
				'result' => ! empty ( $data ['result'] ) ? $data ['result'] : '',
				'customer_id' => ! empty ( $data ['customer'] ['id'] ) ? $data ['customer'] ['id'] : '',
				'be_id' => ! empty ( $data ['contract'] ) ? $data ['contract'] : '',
				'created_by' => ! empty ( $data ['created_by'] ) ? $data ['created_by'] : '1',								
				'res_duty_company' => ! empty ( $data ['duty_company_resolution'] ) ? $data ['duty_company_resolution'] : '',
				'res_duty_user' => ! empty ( $data ['duty_user_resolution'] ) ? $data ['duty_user_resolution'] : '',
				'res_role' => ! empty ( $data ['res_roles'] ) ? $data ['res_roles'] : '',
				'contratti' => ! empty ( $data ['be_contratti'] ) ? $data ['be_contratti'] : ''
		);
		
		foreach ( $arr as $k => $v ) {
			if ($v == '')
				unset ( $arr [$k] );
		}
		
		if ($this->db->insert ( 'troubles', $arr )) {
			
			return $this->db->insert_id ();
		} else {
			return false;
		}
	}
	public function edit($id, $data) {
		$arr = array (
				'type_id' => $data ['type'],
				'description' => $data ['description'],
				'deadline' => $data ['deadline'],
				'status' => $data ['status'],
				'result' => $data ['result'],
				'customer_id' => $data ['customer'] ['id'],
				'be_id' => $data ['contract'],
				'created_by' => $this->ion_auth->user ()->row ()->id,
				'created' => date ( 'Y-m-d h:i:s' ),				
				'res_duty_company' => $data ['duty_company_resolution'],
				'res_duty_user' => $data ['duty_user_resolution'],
				'res_role' => $data ['res_roles'],
				'subtype' => $data ['subtype'],
				'contratti' => ! empty ( $data ['be_contratti'] ) ? $data ['be_contratti'] : '' 
		);
		
		foreach ( $arr as $k => $v ) {
			if ($v == '')
				unset ( $arr [$k] );
		}

		if ($res = $this->db->where ( 'id', $id )->update ( 'troubles', $arr )) {
			return $res;
		} else {
			return false;
		}
	}
	public function get_types() {
		$query = $this->db->where ( 'active', 't' )->order_by ( 'title', 'ASC' )->get ( 'setup_troubles_types' );
		return $query->result ();
	}
	public function get_status() {
		$query = $this->db->order_by ( 'ordering', 'ASC' )->get ( 'setup_troubles_status' );
		return $query->result ();
	}
	public function single($id) {
		$query = $this->db->where ( 'id', $id )->get ( 'troubles' );
		return $query->row ();
	}

	public function count_type($customer, $type) {
		$query = $this->db->where ( 'customer_id', $customer )->where ( 'type_id', $type )->where ( "status != 'DRAFT'" )->get ( 'troubles' );
		return $query->num_rows ();
	}
	public function count_related($id) {
		$query = $this->db->where ( 'trouble_id', $id )->get ( 'threads' );
		return $query->num_rows ();
	}
	
	/**
	 * get_trouble_types -returns list of types
	 *
	 * @param
	 *        	none
	 *        	
	 * @return array
	 *
	 * @author adharsh
	 */
	public function get_trouble_types() {
		$query = $this->db->select ( 'id,title' )->get ( 'setup_troubles_types' );
		return $query->result ();
	}
	
	/**
	 * fetch_process -returns list of process to be created
	 *
	 * @param
	 *        	trouble_id
	 *        	
	 * @return array
	 *
	 * @author adharsh
	 */
	public function fetch_process($type_id = NULL) {
		if ($type_id == NULL) {
			return array ();
		}
		$query = $this->db->select ( 'process_key,request_key' )->where ( "trouble_type", $type_id )->where ( "autocreate = 't'" )->get ( 'setup_troubles_types_2_processes_types' );
		return $query->result ();
	}
	public function get_manual_types() {
		// $query = $this->db->where('active','t')->where('manual','t')->order_by('title','ASC')->get('setup_troubles_types');
		// return $query->result();
		$query = $this->db->select ( 'setup_troubles_types.id,setup_troubles_types.title,setup_troubles_types_2_processes_types.process_key' )
			->join ( 'setup_troubles_types_2_processes_types', 'setup_troubles_types.id=setup_troubles_types_2_processes_types.trouble_type', 'left' )
			->where ( 'setup_troubles_types.active', 't' )
			->where ( 'setup_troubles_types.manual', 't' )			
			->order_by ( 'setup_troubles_types.title' )
			->get ( 'setup_troubles_types' );
		$types = $query->result ();
		$result = array ();
		if (count ( $types ) > 0) {
				
			foreach ( $types as $key => $type ) {
				$result [$key] = new stdClass ();
				$result [$key]->id = $type->id;
				if ($type->process_key != '') {
					$result [$key]->title = $type->title . ' (automatic)';
				} else {
					$result [$key]->title = $type->title;
				}
			}
		}
		return $result;
	}
	public function get_trouble_types_to_process_types() {
		$query = $this->db->select ( 'setup_troubles_types.id,setup_troubles_types.title,setup_troubles_types_2_processes_types.process_key' )->join ( 'setup_troubles_types_2_processes_types', 'setup_troubles_types.id=setup_troubles_types_2_processes_types.trouble_type', 'left' )->where ( 'setup_troubles_types.active', 't' )->order_by ( 'setup_troubles_types.title' )->get ( 'setup_troubles_types' );
		$types = $query->result ();
		$result = array ();
		if (count ( $types ) > 0) {
			
			foreach ( $types as $key => $type ) {
				$result [$key] = new stdClass ();
				$result [$key]->id = $type->id;
				if ($type->process_key != '') {
					$result [$key]->title = $type->title . ' (automatico)';
				} else {
					$result [$key]->title = $type->title;
				}
			}
		}
		return $result;
	}
	public function check_trouble_types_to_process_types($trouble_type_id) {
		$query = $this->db->where ( 'trouble_type', $trouble_type_id )->get ( 'setup_troubles_types_2_processes_types' );
		// $types = $query->result();
		// return $this->db->last_query();
		// $types = $query->result();
		return $query->num_rows ();
	}
	public function export_trouble($limit, $offset = 0) {
		
		// acl('troubles');
		$me = $this->ion_auth->user ()->row ()->id;
		
		$filter1 = $this->session->userdata ( 'filter_accounts' );
		
		if ($filter1) {
			$filter1 = $this->db->escape_like_str ( $filter1 );
			$this->db->where ( "(accounts.first_name ILIKE '%$filter1%' OR accounts.last_name ILIKE '%$filter1%' OR accounts.code ILIKE '%$filter1%')" );
		}

		$filter4 = $this->session->userdata ( 'troubles_type_filter' );
		
		if ($filter4) {
			$filter1 = $this->db->escape_like_str ( $filter4 );
			$this->db->where ( "troubles.type_id = $filter4" );
		}
		
		$filter5 = $this->session->userdata ( 'filter_trouble_reminder' );
		$date = str_replace ( "/", "-", $filter5 );
		$reminder = date ( "Y-m-d", strtotime ( $date ) );
		if ($filter5) {
			$this->db->where ( "memos.start_day = '$reminder'" );
		}
		
		$filter6 = $this->session->userdata ( 'troubles_sub_type_filter' );
		
		if ($filter6) {
			$this->db->where ( "troubles.subtype = '$filter6'" );
		}
		
		
		$filter8 = $this->session->userdata ( 'filter_trouble_created_from' );
		$date = str_replace ( "/", "-", $filter8 );
		$created_from = date ( "Y-m-d", strtotime ( $date ) );
		if ($filter8) {
			$this->db->where ( "troubles.created >= '$created_from'" );
		}
		
		$filter9 = $this->session->userdata ( 'filter_trouble_created_to' );
		$date = str_replace ( "/", "-", $filter9 );
		$created_to = date ( "Y-m-d", strtotime ( $date ) );
		if ($filter9) {
			$this->db->where ( "troubles.created <= '$created_to'" );
		}
		
		if ($this->session->userdata ( 'filter_trouble_status' ) != '') {
			$filter1 = $this->db->escape_like_str ( $filter4 );
			$this->db->where ( "troubles.status = '" . $this->session->userdata ( 'filter_trouble_status' ) . "'" );
		}
		
		if ($this->session->userdata ( 'filter_trouble_result' ) != '') {
			$filter10 = $this->session->userdata ( 'filter_trouble_result' );
			$filter10 = $this->db->escape_like_str ( $filter10 );
			$this->db->where ( "troubles.result = '" . $filter10 . "'" );
		}
		
		$this->db->where ( "troubles.status != 'DRAFT'" );
		$query = $this->db->distinct ( 'troubles.id' )
			->select ( "troubles.id as trouble_id,(SELECT MIN(start_day) FROM memos WHERE trouble_id = troubles.id AND isdone = 'f' AND memos.type = 'FOLLOWUP') as reminder,
						accounts.id as account_id,
						accounts.first_name || ' ' || accounts.last_name as client_name,
						accounts.code,
						be.id as be_id,						
						troubles.id as type_id,
						troubles.description as description,
						troubles.deadline as deadline,
						troubles.status as status,
						troubles.result as result,							
						setup_troubles_types.title as type,
						setup_troubles_subtypes.value as subtype, res_company.name as resp_risoluzione_company,
						res_user.first_name || ' ' || res_user.last_name as resp_risoluzione_user, 
						creator.first_name || ' ' || creator.last_name as creator" )
			->join ( 'be', 'be.id = troubles.be_id' )			
			->join ( 'accounts', 'accounts.id = troubles.customer_id' )
			->join ( 'setup_troubles_types', 'setup_troubles_types.id = troubles.type_id' )
			->join ( 'setup_troubles_subtypes', 'setup_troubles_subtypes.trouble_type = setup_troubles_types.id' )
			->join ( 'companies res_company', 'res_company.id = troubles.res_duty_company', 'left' )
			->join ( 'users res_user', 'res_user.id = troubles.res_duty_user', 'left' )
			->join ( 'users creator', 'creator.id = troubles.created_by', 'left' )
			->join ( 'memos', 'memos.trouble_id = troubles.id', 'left' )
			->order_by ( 'troubles.id', 'DESC' )
			->get ( 'troubles' );
		return $query->result_array ();
	}
	public function get_trouble_sub_types() {
		$trouble_type_id = ($this->session->userdata ( 'troubles_type_filter' )) ? $this->session->userdata ( 'troubles_type_filter' ) : '0';
		$query = $this->db->select ( 'id,key' )->where ( 'trouble_type', $trouble_type_id )->get ( 'setup_troubles_subtypes' );
		return $query->result ();
	}
	

	public function get_request_activities($id) {
		$query = $this->db->select('companies.name as company_name,threads.id as thread_id,set_pro.bpm as bpm,threads.type as thread_type, activities.*,users.first_name, users.last_name,duty.name as duty_company,(SELECT value FROM vars va WHERE va.id_thread = activities.id_thread AND va.id_activity = activities.id AND key = \'RESULT\') as result_value, (SELECT value FROM vars va WHERE va.id_thread = activities.id_thread AND va.id_activity = activities.id AND key = \'STATUS\') as status_value')
		->join('activities_acl','activities_acl.activities_id = activities.id','left')
		->join('companies','companies.id = activities.creator_company','left')
		->join('companies duty','duty.id = activities_acl.duty_company','left')
		->join('users','users.id = activities.created_by','left')
		->join('threads','threads.id = activities.id_thread')
		->join('setup_processes set_pro','set_pro.key = threads.type')
		->where('threads.trouble_id', $id)
		->get('activities');
		$result = $query->result();
		if($query->num_rows() > 0) {
			foreach($result as $key => $row) {
				$query_status = $this->db->select('setup_vars_values.label')
				->where('setup_vars.type','STATUS')
				->where('setup_vars.disabled','f')
				->where('setup_vars_values.key',$row->status_value)
				->join('setup_activities','setup_activities.id = setup_vars.id_activity')
				->join('setup_vars_values','setup_vars_values.id_var = setup_vars.id')
				->where('setup_activities.key',$row->type)
				->get('setup_vars');
				$status_result = $query_status->row();
				$result[$key]->result_status = $status_result;
				$result[$key]->request_activity = $this->get_request($row->thread_type,$row->thread_id);
					
			}
		}
	
		$final_array = array();
		if(isset($result)) {
			foreach($result as $key => $row) {
				$final_array[$row->thread_id][] = $row;
			}
		}
	
		return  $final_array;
	}
	
	public function get_all_setup_process($id){
	
		$query = $this->db->select('set_pro.id as process_id , set_pro.key as process_key, set_pro.title');
		$query = $this->db->where('set_pro.disabled','f');
		$query = $this->db->where('pt.trouble_type',$id);
		$query = $this->db->join('setup_processes set_pro','set_pro.key = pt.process_key');
		$query = $this->db->join('setup_troubles_types stt','stt.id = pt.trouble_type');
		$query = $this->db->order_by("set_pro.title","ASC")->get('setup_troubles_types_2_processes_types pt');
		$result = $query->result_array();
		if(count($result) > 0) {
			return $query->result_array();
		}
		$all_query = $this->db->select('set_pro.id as process_id , set_pro.key as process_key, set_pro.title');
		$all_query = $this->db->where('set_pro.disabled','f');
		$all_query = $this->db->order_by("set_pro.title","ASC")->get('setup_processes set_pro');
		return $all_query->result_array();
	}
	
	public function get_request($process_key,$thread_id=NULL) {
		$query = $this->db->select('set_pro.id as process_id , set_pro.key as process_key, set_act.id as set_act_id,set_act.title as act_title, set_act.key as act_key, set_pro.bpm as set_pro_bpm');
		$query = $this->db->join('setup_processes set_pro','set_pro.id=set_act.id_process');
		if($thread_id){
			$query = $this->db->where("(set_act.key NOT IN (SELECT type from activities where id_thread = $thread_id))");
			$query = $this->db->where("((set_act.is_request = 't' AND set_pro.bpm='AUTOMATIC') OR (set_pro.bpm='MANUAL'))");
		}else{
			$query = $this->db->where("set_act.is_request = 't'");
		}
		$query = $this->db->where('set_pro.key',$process_key);
	
		$query = $this->db->order_by("set_act.title","ASC")->get('setup_activities set_act');
		return $query->result_array();
	}
}
