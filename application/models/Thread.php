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
class Thread extends CI_Model {
	public function get($limit, $offset = 0) {
		$company = $this->ion_auth->user ()->row ()->id_company;
		
		if ($this->input->post ( 'type' ) != '') {
			$this->session->set_userdata ( 'filter_thread_type', $this->input->post ( 'type' ) );
		} else if (isset ( $_POST ['type'] ) && $_POST ['type'] == '') {
			$this->session->unset_userdata ( 'filter_thread_type' );
		}
		
		if ($this->input->post ( 'cliente' ) != '') {
			$this->session->set_userdata ( 'filter_threads_cliente', $this->input->post ( 'cliente' ) );
		} else if (isset ( $_POST ['cliente'] ) && $_POST ['cliente'] == '') {
			$this->session->unset_userdata ( 'filter_threads_cliente' );
		}
		
		if ($this->input->post ( 'reclamo' ) != '') {
			$this->session->set_userdata ( 'filter_threads_reclamo', $this->input->post ( 'reclamo' ) );
		} else if (isset ( $_POST ['reclamo'] ) && $_POST ['reclamo'] == '') {
			$this->session->unset_userdata ( 'filter_threads_reclamo' );
		}
		
		if ($this->input->post ( 'process' ) != '' && $this->input->post ( 'process' ) != '-') {
			$this->session->set_userdata ( 'filter_threads_process', $this->input->post ( 'process' ) );
		} else if ($this->input->post ( 'process' ) == '-') {
			$this->session->unset_userdata ( 'filter_threads_process' );
		}
		
		if ($this->input->post ( 'macro_process' ) != '' && $this->input->post ( 'macro_process' ) != '-') {
			$this->session->set_userdata ( 'filter_threads_macro_process', $this->input->post ( 'macro_process' ) );
		} else if ($this->input->post ( 'macro_process' ) == '-') {
			$this->session->unset_userdata ( 'filter_threads_macro_process' );
		}
		
		if ($this->input->post ( 'status' ) && $this->input->post ( 'status' ) != '' && $this->input->post ( 'status' ) != '-') {
			
			$this->session->set_userdata ( 'filter_threads_status', $this->input->post ( 'status' ) );
		} else if ($this->input->post ( 'status' ) == '-') {
			$this->session->unset_userdata ( 'filter_threads_status' );
			// $this->session->set_userdata('filter_threads_status_all','all');
			$this->session->set_userdata ( 'filter_threads_status', 'APERTO' );
		} else if ($this->input->post ( 'status' ) == '' && $this->session->userdata ( 'filter_activities_status' ) == '') {
			$this->session->set_userdata ( 'filter_threads_status', 'APERTO' );
		}
		
		if ($this->input->post ( 'search_esito' ) != '') {
			$this->session->set_userdata ( 'filter_esito_result', $this->input->post ( 'search_esito' ) );
		} else if (isset ( $_POST ['search_esito'] ) && $_POST ['search_esito'] == '') {
			$this->session->unset_userdata ( 'filter_esito_result' );
		}
		


		$filter1 = $this->session->userdata ( 'filter_thread_type' );
		$filter2 = $this->session->userdata ( 'filter_threads_cliente' );
		$filter4 = $this->session->userdata ( 'filter_threads_reclamo' );
		$filter5 = $this->session->userdata ( 'filter_threads_process' );
		$filter6 = $this->session->userdata ( 'filter_threads_status' );
		$filter7 = $this->session->userdata ( 'filter_threads_macro_process' );
		$filter8 = $this->session->userdata ( 'filter_esito_result' );
		
		
		if ($filter1) {
			$this->db->where ( 'threads.type', $filter );
		}
		if ($filter2) {
			$this->db->where ( "(accounts.first_name ILIKE '%$filter2%' OR accounts.last_name ILIKE '%$filter2%' OR accounts.code ILIKE '%$filter2%')" );
		}
		if ($filter4) {
			$this->db->where ( "threads.reclamo = true" );
		}
		if ($filter5) {
			$this->db->where ( "threads.type", $filter5 );
		}
		if ($filter6) {
			$this->db->where ( "threads.status", $filter6 );
		}
		if ($filter7) {
			$this->db->where ( "threads.process", $filter7 );
		}
		
		if ($filter8) {
			$this->db->where ( "r.value", $filter8 );
		}

		
		$query = $this->db->select ( 'setup_processes.title as new_title,(SELECT MIN(id) FROM 
						activities WHERE id_thread = threads.id) as request_id,threads.*,
						r.value as result, rn.value as result_note, 
						users.first_name, users.last_name, 
						users.company, accounts.first_name, 
						accounts.last_name, 
						accounts.id as user,(SELECT COUNT(*) FROM activities act WHERE act.id_thread = threads.id) as act_count,
						threads.created,setup_processes.wiki_url' )
				->join ( 'be', 'be.id = threads.be', 'left' )
				->join ( 'accounts', 'accounts.id = threads.customer' )
				->join ( 'users', 'users.id = threads.created_by', 'left' )
				->join ( 'vars s', 's.id_thread = threads.id', 'left' )
				->join ( "setup_processes", "setup_processes.key = threads.type" )
				->where ( 's.key', 'STATUS' )
				->where ( 's.id_activity IS NULL' )
				->join ( 'vars r', 'r.id_thread = threads.id', 'left' )
				->where ( 'r.key', 'RESULT' )
				->join ( 'vars rn', 'rn.id_thread = threads.id', 'left' )
				->where ( 'rn.key', 'RESULT_NOTE' )
				->where ( 'rn.id_activity IS NULL' )
				->where ( 'r.id_activity IS NULL' )
				->where ( 'threads.draft', 'false' )
				->limit ( $limit, $offset )
				->order_by ( 'threads.id', 'DESC' )
				->get ( 'threads' );
		
		$result = $query->result ();
		
		if (count ( $result ) > 0) {
			foreach ( $result as $key => $row ) {
				$d_created = $this->get_thread_integration ( $row->id );
				$result [$key]->d_decorrenza = $d_created->d_decorrenza ? $d_created->d_decorrenza : '';
				$result [$key]->exre_created = $d_created->exre_created ? $d_created->exre_created : '';
				$result [$key]->exauto_created = $d_created->exauto_created ? $d_created->exauto_created : '';
			}
		}
		
		return $result;
	}
	public function total() {
		$filter1 = $this->session->userdata ( 'filter_thread_type' );
		$filter2 = $this->session->userdata ( 'filter_threads_cliente' );		
		$filter4 = $this->session->userdata ( 'filter_threads_reclamo' );
		$filter5 = $this->session->userdata ( 'filter_threads_process' );
		$filter6 = $this->session->userdata ( 'filter_threads_status' );
		$filter7 = $this->session->userdata ( 'filter_threads_macro_process' );
		$filter8 = $this->session->userdata ( 'filter_esito_result' );
		
		acl ( 'threads' ); 
		
		if ($filter1) {
			$this->db->where ( 'threads.type', $filter );
		}
		if ($filter2) {
			$this->db->where ( "(accounts.first_name ILIKE '%$filter2%' OR accounts.last_name ILIKE '%$filter2%' OR accounts.code ILIKE '%$filter2%')" );
		}

		if ($filter4) {
			$this->db->where ( "threads.reclamo = true" );
		}
		if ($filter5) {
			$this->db->where ( "threads.type", $filter5 );
		}
		if ($filter6) {
			$this->db->where ( "threads.status", $filter6 );
		}
		if ($filter7) {
			$this->db->where ( "threads.process", $filter7 );
		}
		if ($filter8) {
			$this->db->where ( "r.value", $filter8 );
		}
		
		$query = $this->db->select ( 'threads.*, s.value as status, r.value as result, 
						rn.value as result_note, 	
						users.first_name, users.last_name, users.company, 
						accounts.first_name, accounts.last_name, 
						accounts.id as user,(SELECT COUNT(*) FROM activities act WHERE act.id_thread = threads.id) as act_count' )
				->join ( 'be', 'be.id = threads.be' )
				->join ( 'accounts', 'accounts.id = threads.customer' )
				->join ( 'users', 'users.id = threads.created_by', 'left' )
				->join ( 'vars s', 's.id_thread = threads.id', 'left' )
				->where ( 's.key', 'STATUS' )
				->where ( 's.id_activity IS NULL' )
				->join ( 'vars r', 'r.id_thread = threads.id', 'left' )
				->where ( 'r.key', 'RESULT' )
				->join ( 'vars rn', 'rn.id_thread = threads.id', 'left' )
				->where ( 'rn.key', 'RESULT_NOTE' )	
				->where ( 'rn.id_activity IS NULL' )
				->where ( 'r.id_activity IS NULL' )
				->where ( 'threads.draft', 'false' )
				->order_by ( 'created', 'desc' )
				->get ( 'threads' );
		
		return $query->num_rows ();
	}
	public function save($data = NULL) {
		if ($query = $this->db->insert ( 'threads', $data )) {
			$id = $this->db->insert_id ();
			$query = $this->db->where ( 'domain', 'PROCESS' )->where ( 'disabled', 'f' )->get ( 'setup_default_vars' );
			$arr = $query->result ();
			foreach ( $arr as $variable ) {
				$vars ['id_thread'] = $id;
				$vars ['key'] = $variable->key;
				$vars ['created_by'] = $this->ion_auth->user ()->row ()->id;
				$vars ['created'] = date ( 'Y-m-d H:i:s' );
				$this->db->flush_cache ();
				$this->db->insert ( 'vars', $vars );
			}
		}
		return $id;
	}
	public function single($id) {
		$query = $this->db->select ( 'threads.*,
						threads.status as master_status,
						threads.status_detail as master_status_detail, 
						s.value as status, r.value as result, setup_processes.form_id, 
						setup_processes.bpm, setup_processes.sla' )->where ( 'threads.id', $id )->join ( 'setup_processes', 'setup_processes.key = threads.type' )->join ( 'vars s', 's.id_thread = threads.id', 'left' )->where ( 's.key', 'STATUS' )->where ( 's.id_activity IS NULL' )->join ( 'vars r', 'r.id_thread = threads.id', 'left' )->where ( 'r.key', 'RESULT' )->where ( 'r.id_activity IS NULL' )->get ( 'threads' );
		$result_array = array ();
		$row = array ();
		$result1 = $query->row ();

		
		$result_array = $result1;

		return $result_array;
	}
	public function update($id, $data) {
		$data ['modified'] = date ( 'Y-m-d H:i:s' );
		$data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		return $this->db->where ( 'id', $id )->update ( 'threads', $data );
	}
	public function get_thread_statuses($id_thread) {
		$query = $this->db->select ( 'setup_vars_values.*' )->where ( 'setup_vars.type', 'STATUS' )->where ( 'setup_vars.disabled', 'f' )->join ( 'setup_processes', 'setup_processes.id = setup_vars.id_process' )->join ( 'setup_vars_values', 'setup_vars_values.id_var = setup_vars.id' )->where ( 'setup_vars_values.final_default', 't' )->where ( 'setup_processes.key', $id_thread )->get ( 'setup_vars' );
		return $query->result ();
	}

	public function get_setup_process($id_thread) {
		$query = $this->db->select ( 'setup_processes.key as thread_type_key' )->where ( 'threads.id', $id_thread )->join ( 'threads', 'setup_processes.key=threads.type' )->get ( 'setup_processes' );
		if (isset ( $query->row ()->thread_type_key )) {
			$final_status = $this->get_thread_statuses ( $query->row ()->thread_type_key );
			if (count ( $final_status ) > 0) {
				return $final_status [0]->key;
			}
		}
		return false;
	}
	public function get_required_attach($form) {
		$query = $this->db->where ( 'form_id', $form )->where ( 'required', 't' )->where ( 'use', 'UPLOAD' )->get ( 'setup_forms_attachments' );
		return $query->result ();
	}

	public function get_cancel_reasons() {
		$query = $this->db->select ( 'list_cause_annullamento.key' )->order_by ( 'list_cause_annullamento.key' )->get ( 'list_cause_annullamento' );
		return $query->result ();
	}
	
	// UPDATE DRAFT STATUS TO FALSE
	public function draft($thread_id) {
		$data = array ();
		$data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		$data ['modified'] = date ( 'Y-m-d H:i:s' );
		$data ['draft'] = 'false';
		return $this->db->where ( 'threads.id', $thread_id )->where ( 'threads.draft', 'true' )->update ( 'threads', $data );
	}
	
	// DELETE A DRAFT RECORD
	public function delete_draft($thread_id) {
		$query = $this->db->where ( 'id_thread', $thread_id )->get ( 'activities' );
		$activities = $query->result ();
		
		foreach ( $activities as $activity ) {
			$this->db->where ( 'activities_id', $activity->id )->delete ( 'activities_acl' );
			$this->db->where ( 'id', $activity->id )->delete ( 'activities' );
			$this->db->where ( 'id_activity', $activity->id )->delete ( 'activities_detail' );
		}
		
		$this->db->where ( 'id', $thread_id )->delete ( 'threads' );
		$this->db->where ( 'threads_id', $thread_id )->delete ( 'threads_acl' );
		$this->db->where ( 'id_thread', $thread_id )->delete ( 'history' );
		$this->db->where ( 'id_thread', $thread_id )->delete ( 'vars' );
		
		return true;
	}
	public function get_by_customer($customer_id, $thread_id, $trouble_id) {
		if ($thread_id)
			$this->db->where ( "id <> $thread_id" );
		if ($trouble_id)
			$this->db->where ( "trouble_id", $trouble_id );
		$query = $this->db->where ( 'customer', $customer_id )->get ( 'threads' );
		
		return $query->result ();
	}
	public function get_request_by_customer($customer_id, $thread_id, $trouble_id) {
		if ($thread_id)
			$this->db->where ( "id <> $thread_id" );
		if ($trouble_id)
			$this->db->where ( "threads.trouble_id", $trouble_id );
		
		$this->db->join ( 'setup_forms', 'setup_forms.id = activities.form_id', 'left' );
		$query = $this->db->distinct ( 'activities.id' )->select ( 'activities.*, threads.id as thread_id, setup_activities.role, setup_forms.url, setup_forms.sidebar, setup_activities.title, setup_activities.description, c.first_name as creator_first_name, c.last_name as creator_last_name, m.first_name as modifier_first_name, m.last_name as modifier_last_name, d.first_name as duty_first_name, d.last_name as duty_last_name, s.value as status, r.value as result, rn.value as result_note, setup_activities.is_request, threads.id as thread_id' )->join ( 'threads', 'threads.id = activities.id_thread' )->join ( 'setup_activities', 'setup_activities.key = activities.type' )->join ( 'users c', 'c.id = activities.created_by', 'left' )->join ( 'users m', 'm.id = activities.modified_by', 'left' )->join ( 'users d', 'd.id = activities.duty_operator', 'left' )->join ( 'vars s', 's.id_activity = activities.id', 'left' )->where ( 's.key', 'STATUS' )->join ( 'vars r', 'r.id_activity = activities.id', 'left' )->where ( 'r.key', 'RESULT' )->join ( 'vars rn', 'rn.id_activity = activities.id', 'left' )->where ( 'rn.key', 'RESULT_NOTE' )->where ( 'is_request', 't' )->where ( 'draft', 'f' )->get ( 'activities' );
		
		return $query->result ();
	}
	public function get_related_activities_by_thread($thread_id) {
		$query = $this->db->where ( 'id', $thread_id )->get ( 'threads' );
		$thread = $query->row ();
		
		$query = $this->db->where ( 'key', $thread->type )->get ( 'setup_processes' );
		$process = $query->row ();
		
		$query = $this->db->where ( 'id_process', $process->id )->get ( 'setup_activities' );
		return $query->result ();
	}
	public function get_request_for_trouble($customer_id, $thread_id, $trouble_id) {

		$query = $this->db->select ( "threads.id, threads.type, threads.created, threads.status,
					(accounts.first_name||' '||accounts.last_name) as cust_name, accounts.first_name, accounts.last_name" )
				->join ( 'accounts', 'accounts.id = threads.customer' )
				->where ( 'threads.trouble_id', $trouble_id )
				->get ( 'threads' );
		return $query->result ();
	}
	
	/**
	 * get_process_from_thread
	 *
	 * @param number $thread_id        	
	 *
	 * @return string
	 */
	public function get_type_from_thread($customer_id) {
		$query = $this->db->select ( 'type' )->where ( 'customer', $customer_id )->get ( 'threads' );
		
		$data = $query->result ();
		
		return $data [0]->type;
	}
	public function get_thread_types() {
		$query = $this->db->select ( 'DISTINCT(threads.type)' )->order_by ( 'threads.type', 'ASC' )->get ( 'threads' );
		return $query->result ();
	}
	public function get_macro_process() {
		$query = $this->db->select ( 'DISTINCT(threads.process)' )->where ( "process IS NOT NULL" )->order_by ( 'threads.process', 'ASC' )->get ( 'threads' );
		return $query->result ();
	}
	public function get_setup_mps() {
		$query = $this->db->select ( 'id,mp' )->order_by ( 'mp', 'ASC' )->get ( 'setup_mps' );
		return $query->result ();
	}
	public function check_process_creation_permission($process_key = NULL) {
		if ($process_key == NULL) {
			return false;
		}
		
		// get setup process roles
		$get_setup_process = $this->db->select ( "*" )->where ( "key = '$process_key'" )->get ( "setup_processes" );
		$setup_process = $get_setup_process->row ();
		
		$can_create = $setup_process->role_can_create;
		
		if ($can_create == '') {
			return true;
		}
		
		$can_create_array = explode ( ",", $can_create );
		
		// user roles
		$user_id = $this->ion_auth->user ()->row ()->id;
		$role_details = $this->ion_auth->get_users_groups ( $user_id )->result ();
		$user_role_array = array ();
		if (count ( $role_details ) > 0) {
			foreach ( $role_details as $item ) {
				$user_role_array [] = $item->name;
			}
		}
		
		// check weather done
		if (count ( $can_create_array ) > 0 && count ( $user_role_array ) > 0) {
			foreach ( $can_create_array as $role ) {
				if (in_array ( $role, $user_role_array ) || in_array ( "admin", $user_role_array )) {
					return TRUE;
				}
			}
		}
		
		return false;
	}
	
	/*
	 * update_status_exb_exa_exr
	 * function to update the status of export billing, export_autoletture and export_rettifiche table
	 * $act_id int, $thread_id int
	 * Raghavendra Naik
	 */
	
	/*
	 * get_thread_integration
	 * function to get the date from integrations
	 * $thread_id int
	 * Raghavendra Naik
	 */
	public function get_thread_integration($thread_id) {
		$query = $this->db->query ( 'select DISTINCT(threads.id) as tid				
					from threads left join activities on activities.id_thread = threads.id  					
					where threads.id = ' . $thread_id . '
					order by threads.id DESC LIMIT 1' );
		$result = $query->row ();
		return $result;
	}
	public function get_export_data() {
		$filter1 = $this->session->userdata ( 'filter_thread_type' );
		$filter2 = $this->session->userdata ( 'filter_threads_cliente' );
		$filter4 = $this->session->userdata ( 'filter_threads_reclamo' );
		$filter5 = $this->session->userdata ( 'filter_threads_process' );
		$filter6 = $this->session->userdata ( 'filter_threads_status' );
		$filter7 = $this->session->userdata ( 'filter_threads_macro_process' );
		$filter8 = $this->session->userdata ( 'filter_esito_result' );

		
		if ($filter1) {
			$this->db->where ( 'threads.type', $filter );
		}
		if ($filter2) {
			$this->db->where ( "(accounts.first_name ILIKE '%$filter2%' OR accounts.last_name ILIKE '%$filter2%' OR accounts.code ILIKE '%$filter2%')" );
		}
		if ($filter4) {
			$this->db->where ( "threads.reclamo = true" );
		}
		if ($filter5) {
			$this->db->where ( "threads.type", $filter5 );
		}
		if ($filter6) {
			$this->db->where ( "threads.status", $filter6 );
		}
		if ($filter7) {
			$this->db->where ( "threads.process", $filter7 );
		}
		if ($filter8) {
			$this->db->where ( "r.value", $filter8 );
		}
		
		$query = $this->db->select ( 'threads.*, s.value as status,
					r.value as result, rn.value as result_note,
					users.first_name, users.last_name, users.company, 
					accounts.first_name, accounts.last_name,
					accounts.id as user,(SELECT COUNT(*) FROM activities act WHERE act.id_thread = threads.id) as act_count' )
			->join ( 'be', 'be.id = threads.be' )
			->join ( 'accounts', 'accounts.id = threads.customer' )
			->join ( 'users', 'users.id = threads.created_by', 'left' )	
			->join ( 'vars s', 's.id_thread = threads.id', 'left' )
			->where ( 's.key', 'STATUS' )
			->where ( 's.id_activity IS NULL' )
			->join ( 'vars r', 'r.id_thread = threads.id', 'left' )
			->where ( 'r.key', 'RESULT' )
			->join ( 'vars rn', 'rn.id_thread = threads.id', 'left' )
			->where ( 'rn.key', 'RESULT_NOTE' )
			->where ( 'rn.id_activity IS NULL' )
			->where ( 'r.id_activity IS NULL' )
			->where ( 'threads.draft', 'false' )
			->order_by ( 'created', 'desc' )
			->get ( 'threads' );
		
		return $query->result_array ();
	}
}
