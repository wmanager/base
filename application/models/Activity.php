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
class Activity extends CI_Model {
	public function get($limit, $offset = 0) {
		if ($this->input->post ( 'clear' )) {
			$array_items = array (
					'filter_activities_process',
					'filter_activities_type',
					'filter_activities_role',
					'filter_activities_thread',
					'filter_activities_status',
					'filter_activities_cliente',
					'filter_activities_codice_contratto',
					'filter_activities_duty',
					'filter_activities_key',
					'filter_activities_role',
					'filter_reclamo' 
			);
			foreach ( $array_items as $item ) {
				$this->session->unset_userdata ( $item );
			}
		} else {
			if (isset ( $_POST ['activity_autocomplete'] ) && $this->input->post ( 'activity_autocomplete' ) != '') {
				$this->session->set_userdata ( 'filter_activities_label', $this->input->post ( 'activity_autocomplete' ) );
			} else if (isset ( $_POST ['activity_autocomplete'] ) && $_POST ['activity_autocomplete'] == '') {
				$this->session->unset_userdata ( 'filter_activities_label' );
			}
			
			if ($this->input->post ( 'process' ) != '' && $this->input->post ( 'process' ) != '-') {
				$this->session->set_userdata ( 'filter_activities_process', $this->input->post ( 'process' ) );
			} else if ($this->input->post ( 'process' ) == '-') {
				$this->session->unset_userdata ( 'filter_activities_process' );
			}
			
			if ($this->input->post ( 'type' ) != '' && $this->input->post ( 'type' ) != '-') {
				$this->session->set_userdata ( 'filter_activities_type', $this->input->post ( 'type' ) );
			} else if ($this->input->post ( 'type' ) == '-') {
				$this->session->unset_userdata ( 'filter_activities_type' );
			}
			
			if ($this->input->post ( 'activity' ) != '') {
				$this->session->set_userdata ( 'filter_activities_key', $this->input->post ( 'activity' ) );
			} else if (isset ( $_POST ['activity'] ) && $_POST ['activity'] == '') {
				$this->session->unset_userdata ( 'filter_activities_key' );
			}
			
			if ($this->input->post ( 'role' ) && $this->input->post ( 'role' ) != '-') {
				$this->session->set_userdata ( 'filter_activities_role', $this->input->post ( 'role' ) );
			} else if ($this->input->post ( 'role' ) && $this->input->post ( 'role' ) == '-') {
				$this->session->unset_userdata ( 'filter_activities_role' );
			}
			
			if ($this->input->post ( 'order' ) != '') {
				$this->session->set_userdata ( 'filter_activities_order', $this->input->post ( 'order' ) );
			} else if (isset ( $_POST ['order'] ) && $_POST ['order'] == '') {
				$this->session->unset_userdata ( 'filter_activities_order' );
			}
			
			if ($this->input->post ( 'id_thread' ) != '') {
				$this->session->set_userdata ( 'filter_activities_thread', $this->input->post ( 'id_thread' ) );
			} else if (isset ( $_POST ['id_thread'] ) && $_POST ['id_thread'] == '') {
				$this->session->unset_userdata ( 'filter_activities_thread' );
			}
			
			if ($this->input->post ( 'status' ) && $this->input->post ( 'status' ) != '' && $this->input->post ( 'status' ) != '-') {
				$this->session->set_userdata ( 'filter_activities_status', $this->input->post ( 'status' ) );
			} else if ($this->input->post ( 'status' ) == '-') {
				$this->session->unset_userdata ( 'filter_activities_status' );
				$this->session->set_userdata ( 'filter_activities_status_all', 'all' );
			}
			
			if (! $this->session->userdata ( 'filter_activities_status_all' ) && $this->session->userdata ( 'filter_activities_status' ) == '' && count ( $_POST ) == 0) {
				$this->session->set_userdata ( 'filter_activities_status', 'APERTO' );
				$this->session->unset_userdata ( 'filter_activities_status_all' );
			}
			
			if ($this->input->post ( 'cliente' ) != '') {
				$this->session->set_userdata ( 'filter_activities_cliente', $this->input->post ( 'cliente' ) );
			} else if (isset ( $_POST ['cliente'] ) && $_POST ['cliente'] == '') {
				$this->session->unset_userdata ( 'filter_activities_cliente' );
			}

			if ($this->input->post ( 'codice_contratto' ) != '') {
				$this->session->set_userdata ( 'filter_activities_codice_contratto', $this->input->post ( 'codice_contratto' ) );
			} else if (isset ( $_POST ['codice_contratto'] ) && $_POST ['codice_contratto'] == '') {
				$this->session->unset_userdata ( 'filter_activities_codice_contratto' );
			}
			
			
			if ($this->input->post ( 'duty' ) != '') {
				$user = $this->ion_auth->user ()->row ()->id;
				$this->session->set_userdata ( 'filter_activities_duty', $user );
			} else if (isset ( $_POST ['duty'] ) && $_POST ['duty'] == '') {
				$this->session->unset_userdata ( 'filter_activities_duty' );
			}
			
			if ($this->input->post ( 'reclamo' ) != '') {
				$this->session->set_userdata ( 'filter_reclamo', $this->input->post ( 'reclamo' ) );
			} else if (isset ( $_POST ['reclamo'] ) && $_POST ['reclamo'] == '') {
				$this->session->unset_userdata ( 'filter_reclamo' );
			}
			
			if ($this->input->post ( 'process' ) && $this->input->post ( 'process' ) != '-') {
				$this->session->unset_userdata ( 'filter_activities_label' );
				$this->session->unset_userdata ( 'filter_activities_key' );
			}
			
			if ($this->input->post ( 'reminder' ) != '') {
				$this->session->set_userdata ( 'filter_activities_reminder', 't' );
			} else if (isset ( $_POST ['reminder'] ) && $_POST ['reminder'] == '') {
				$this->session->unset_userdata ( 'filter_activities_reminder' );
			}
			
			if ($this->input->post ( 'search_esito' ) != '') {
				$this->session->set_userdata ( 'filter_esito_result', $this->input->post ( 'search_esito' ) );
			} else if (isset ( $_POST ['search_esito'] ) && $_POST ['search_esito'] == '') {
				$this->session->unset_userdata ( 'filter_esito_result' );
			}
		}
		
		$filter2 = $this->session->userdata ( 'filter_activities_process' );
		$filter3 = $this->session->userdata ( 'filter_activities_type' );
		$filter4 = $this->session->userdata ( 'filter_activities_role' );
		$filter5 = $this->session->userdata ( 'filter_activities_thread' );
		$filter6 = $this->session->userdata ( 'filter_activities_status' );
		$filter7 = $this->session->userdata ( 'filter_activities_cliente' );
		$filter10 = $this->session->userdata ( 'filter_activities_codice_contratto' );
		$filter11 = $this->session->userdata ( 'filter_activities_duty' );
		$filter12 = $this->session->userdata ( 'filter_activities_key' );
		$filter13 = $this->session->userdata ( 'filter_activities_role' );
		$filter15 = $this->session->userdata ( 'filter_activities_reminder' );
		$filter16 = $this->session->userdata ( 'filter_reclamo' );
		$filter17 = $this->session->userdata ( 'filter_esito_result' );
		
		$this->db->flush_cache ();
		// GET ACL HELPER
		acl ( 'activities' );
		
		if ($filter2) {
			$this->db->where ( "setup_activities.id_process", $filter2 );
		}
		
		if ($filter3) {
			$this->db->where ( "activities.type", $filter3 );
		}
		
		if ($filter6) {
			$this->db->where ( "activities.status", $filter6 );
		}
		
		if ($filter11) {
			$this->db->where ( "activities_acl.duty_user", $filter11 );
		}
		
		if ($filter16) {
			$this->db->where ( "threads.reclamo = true" );
		}
		
		if ($filter12) {
			$this->db->where ( "(activities.type ILIKE '$filter12')" );
		}
		
		if ($filter13) {
			$this->db->where ( "(setup_activities.role = '$filter13')" );
		}
		
		if ($filter17) {
			$this->db->where ( "r.value", $filter17 );
		}
		
		if ($filter7) {
			$filter7 = $this->db->escape_like_str ( $filter7 );
			$this->db->where ( "(accounts.first_name ILIKE '$filter7%' OR accounts.last_name ILIKE '$filter7%' OR accounts.code ILIKE '$filter7%')" );
		}

		
		if ($filter10) {
			$this->db->where ( "(contracts.contract_code ILIKE '$filter10')" );
		}

		
		if ($filter15) {
			$this->db->where ( "((SELECT COUNT(*) FROM memos WHERE activity_id = activities.id AND start_day IS NOT NULL AND memos.type = 'FOLLOWUP') > 0)" );
		}

		$this->db->flush_cache ();
		

		$query = $this->db->distinct ( 'activities.id' )
			->select ( '(SELECT COUNT(*) FROM memos WHERE activity_id = activities.id AND memos.type = \'FOLLOWUP\') as followup, (SELECT MIN(start_day) FROM memos WHERE activity_id = activities.id AND isdone = \'f\' AND memos.type = \'FOLLOWUP\') as reminder,						
						threads.trouble_id,threads.status as thread_status,
						setup_vars_values.label, setup_activities.role, setup_activities.title as activity_title,
						address.*, duty.name as duty_company,						
						contracts.created as data_inserimento, activities.status as activity_status, 
						be.*,be.id as be_table_id,
						be.be_status,contracts.contract_code,
						contracts.d_sign,setup_vars_values.final, activities.*, 
						accounts.first_name as client_first_name, accounts.last_name as client_last_name, accounts.code as client_code, 
						accounts.id as cliente, companies.name as company_name,
						companies.icon as company_icon,
						users.first_name, users.last_name, threads.title as thread,
						(SELECT value FROM vars va WHERE va.id_thread = activities.id_thread 
						AND va.id_activity = activities.id AND key = \'STATUS\') as status_value, 
						(SELECT value FROM vars va WHERE va.id_thread = activities.id_thread AND va.id_activity = activities.id AND key = \'RESULT\')
						as result_value, 
						(SELECT value FROM vars va WHERE va.id_thread = activities.id_thread AND va.id_activity = activities.id AND key = \'RESULT_NOTE\') as result_note_value,
						threads.reclamo, 
						impianti.installed_power, 
						impianti.pot_installable, 
						impianti.capacity,
						threads.id as thread_id, 
						threads.reclamo, 
						setup_activities.is_request' )
				->join ( 'activities_acl', 'activities_acl.activities_id = activities.id', 'left' )
				->join ( 'companies', 'companies.id = activities.creator_company', 'left' )
				->join ( 'companies duty', 'duty.id = activities_acl.duty_company', 'left' )	
				->join ( 'users', 'users.id = activities.created_by', 'left' )	
				->join ( 'threads', 'threads.id = activities.id_thread', 'left' )
				->join ( 'be', 'be.id = threads.be', 'left' )
				->join ( 'assets', "(assets.be_id = be.id", 'left' )				
				-> join ( 'immobili', 'immobili.id = assets.immobili_id', 'left' )
				->join ( 'address', 'address.id = immobili.address_id', 'left' )
				->join ( 'impianti', 'impianti.id = assets.impianti_id', 'left' )
				->join ( 'contracts', 'contracts.id = activities.id_contract', 'left' )
				->join ( 'accounts', 'accounts.id = threads.customer', 'left' )
				->join ( 'vars s', "s.id_activity = activities.id AND s.key = 'STATUS'", 'left' )
				->join ( 'vars r', "r.id_activity = activities.id AND r.key = 'RESULT'", 'left' )
				->join ( 'vars rn', "rn.id_activity = activities.id AND rn.key = 'RESULT_NOTE'", 'left' )
				->join ( 'setup_processes', 'setup_processes.key = threads.type', 'left' )
				->join ( 'setup_activities', 'setup_activities.key = activities.type AND setup_activities.id_process = setup_processes.id', 'left' )->join ( 'setup_vars', "setup_vars.id_activity = setup_activities.id AND setup_vars.type = 'STATUS'", 'left' )->join ( 'setup_vars_values', 'setup_vars_values.id_var = setup_vars.id AND setup_vars_values.key = s.value', 'left' )
				->where ( 'threads.draft', 'false' )
				->where ( 'activities_acl.role_key = setup_activities.role' )
				->where ( 'setup_activities.type', 'STANDARD' )
				->limit ( $limit, $offset )
				->order_by ( "activities.created", "DESC" )
				->get ( 'activities' );
		
		$result = $query->result ();
		
		return $result;
	}
	public function export() {
			$filter2 = $this->session->userdata ( 'filter_activities_process' );
		$filter3 = $this->session->userdata ( 'filter_activities_type' );
		$filter4 = $this->session->userdata ( 'filter_activities_role' );
		$filter5 = $this->session->userdata ( 'filter_activities_thread' );
		$filter6 = $this->session->userdata ( 'filter_activities_status' );
		$filter7 = $this->session->userdata ( 'filter_activities_cliente' );
		$filter10 = $this->session->userdata ( 'filter_activities_codice_contratto' );
		$filter11 = $this->session->userdata ( 'filter_activities_duty' );
		$filter12 = $this->session->userdata ( 'filter_activities_key' );
		$filter13 = $this->session->userdata ( 'filter_activities_role' );
		$filter15 = $this->session->userdata ( 'filter_activities_reminder' );
		$filter16 = $this->session->userdata ( 'filter_reclamo' );
		$filter17 = $this->session->userdata ( 'filter_esito_result' );
		
		$this->db->flush_cache ();
		// GET ACL HELPER
		acl ( 'activities' );
		
		if ($filter2) {
			$this->db->where ( "setup_activities.id_process", $filter2 );
		}
		
		if ($filter3) {
			$this->db->where ( "activities.type", $filter3 );
		}
		
		if ($filter6) {
			$this->db->where ( "activities.status", $filter6 );
		}
		
		if ($filter11) {
			$this->db->where ( "activities_acl.duty_user", $filter11 );
		}
		
		if ($filter16) {
			$this->db->where ( "threads.reclamo = true" );
		}
		
		if ($filter12) {
			$this->db->where ( "(activities.type ILIKE '$filter12')" );
		}
		
		if ($filter13) {
			$this->db->where ( "(setup_activities.role = '$filter13')" );
		}
		
		if ($filter17) {
			$this->db->where ( "r.value", $filter17 );
		}
		
		if ($filter7) {
			$filter7 = $this->db->escape_like_str ( $filter7 );
			$this->db->where ( "(accounts.first_name ILIKE '$filter7%' OR accounts.last_name ILIKE '$filter7%' OR accounts.code ILIKE '$filter7%')" );
		}

		
		if ($filter10) {
			$this->db->where ( "(contracts.contract_code ILIKE '$filter10')" );
		}

		
		if ($filter15) {
			$this->db->where ( "((SELECT COUNT(*) FROM memos WHERE activity_id = activities.id AND start_day IS NOT NULL AND memos.type = 'FOLLOWUP') > 0)" );
		}
		$this->db->flush_cache ();
		
		$query = $this->db->distinct ( 'activities.id' )
			->select ('					
						threads.trouble_id,threads.status as thread_status,
						setup_vars_values.label, setup_activities.role, setup_activities.title as activity_title,
						address.*, (SELECT COUNT(*) FROM memos WHERE activity_id = activities.id AND memos.type = \'FOLLOWUP\') as followup, (SELECT MIN(start_day) FROM memos WHERE activity_id = activities.id AND isdone = \'f\' AND memos.type = \'FOLLOWUP\') as reminder,duty.name as duty_company,						
						contracts.created as data_inserimento, activities.status as activity_status, 
						be.*,be.id as be_table_id,
						be.be_status,contracts.contract_code,
						contracts.d_sign,setup_vars_values.final, activities.*, 
						accounts.first_name as client_first_name, accounts.last_name as client_last_name, accounts.code as client_code, 
						accounts.id as cliente, companies.name as company_name,
						companies.icon as company_icon,
						users.first_name, users.last_name, threads.title as thread,
						(SELECT value FROM vars va WHERE va.id_thread = activities.id_thread 
						AND va.id_activity = activities.id AND key = \'STATUS\') as status_value, 
						(SELECT value FROM vars va WHERE va.id_thread = activities.id_thread AND va.id_activity = activities.id AND key = \'RESULT\')
						as result_value, 
						(SELECT value FROM vars va WHERE va.id_thread = activities.id_thread AND va.id_activity = activities.id AND key = \'RESULT_NOTE\') as result_note_value,
						threads.reclamo, 
						impianti.installed_power, 
						impianti.pot_installable, 
						impianti.capacity,
						threads.id as thread_id, 
						threads.reclamo, 
						setup_activities.is_request' )
				->join ( 'activities_acl', 'activities_acl.activities_id = activities.id', 'left' )
				->join ( 'companies', 'companies.id = activities.creator_company', 'left' )
				->join ( 'companies duty', 'duty.id = activities_acl.duty_company', 'left' )	
				->join ( 'users', 'users.id = activities.created_by', 'left' )	
				->join ( 'threads', 'threads.id = activities.id_thread', 'left' )
				->join ( 'be', 'be.id = threads.be', 'left' )
				->join ( 'assets', "(assets.be_id = be.id", 'left' )				
				-> join ( 'immobili', 'immobili.id = assets.immobili_id', 'left' )
				->join ( 'address', 'address.id = immobili.address_id', 'left' )
				->join ( 'impianti', 'impianti.id = assets.impianti_id', 'left' )
				->join ( 'contracts', 'contracts.id = activities.id_contract', 'left' )
				->join ( 'accounts', 'accounts.id = threads.customer', 'left' )
				->join ( 'vars s', "s.id_activity = activities.id AND s.key = 'STATUS'", 'left' )
				->join ( 'vars r', "r.id_activity = activities.id AND r.key = 'RESULT'", 'left' )
				->join ( 'vars rn', "rn.id_activity = activities.id AND rn.key = 'RESULT_NOTE'", 'left' )
				->join ( 'setup_processes', 'setup_processes.key = threads.type', 'left' )
				->join ( 'setup_activities', 'setup_activities.key = activities.type AND setup_activities.id_process = setup_processes.id', 'left' )->join ( 'setup_vars', "setup_vars.id_activity = setup_activities.id AND setup_vars.type = 'STATUS'", 'left' )->join ( 'setup_vars_values', 'setup_vars_values.id_var = setup_vars.id AND setup_vars_values.key = s.value', 'left' )
				->where ( 'threads.draft', 'false' )
				->where ( 'activities_acl.role_key = setup_activities.role' )
				->where ( 'setup_activities.type', 'STANDARD' )
				->limit ( $limit, $offset )
				->order_by ( "activities.created", "DESC" )
				->get ( 'activities' );
		
		$result = $query->result_array ();
		
		return $result;
	}
	public function total() {
		$filter2 = $this->session->userdata ( 'filter_activities_process' );
		$filter3 = $this->session->userdata ( 'filter_activities_type' );
		$filter4 = $this->session->userdata ( 'filter_activities_role' );
		$filter5 = $this->session->userdata ( 'filter_activities_thread' );
		$filter6 = $this->session->userdata ( 'filter_activities_status' );
		$filter7 = $this->session->userdata ( 'filter_activities_cliente' );
		$filter10 = $this->session->userdata ( 'filter_activities_codice_contratto' );
		$filter11 = $this->session->userdata ( 'filter_activities_duty' );
		$filter12 = $this->session->userdata ( 'filter_activities_key' );
		$filter13 = $this->session->userdata ( 'filter_activities_role' );
		$filter15 = $this->session->userdata ( 'filter_activities_reminder' );
		$filter16 = $this->session->userdata ( 'filter_reclamo' );
		$filter17 = $this->session->userdata ( 'filter_esito_result' );
		
		$this->db->flush_cache ();
		// GET ACL HELPER
		acl ( 'activities' );
		
		if ($filter2) {
			$this->db->where ( "setup_activities.id_process", $filter2 );
		}
		
		if ($filter3) {
			$this->db->where ( "activities.type", $filter3 );
		}
		
		if ($filter6) {
			$this->db->where ( "activities.status", $filter6 );
		}
		
		if ($filter11) {
			$this->db->where ( "activities_acl.duty_user", $filter11 );
		}
		
		if ($filter16) {
			$this->db->where ( "threads.reclamo = true" );
		}
		
		if ($filter12) {
			$this->db->where ( "(activities.type ILIKE '$filter12')" );
		}
		
		if ($filter13) {
			$this->db->where ( "(setup_activities.role = '$filter13')" );
		}
		
		if ($filter17) {
			$this->db->where ( "r.value", $filter17 );
		}
		
		if ($filter7) {
			$filter7 = $this->db->escape_like_str ( $filter7 );
			$this->db->where ( "(accounts.first_name ILIKE '$filter7%' OR accounts.last_name ILIKE '$filter7%' OR accounts.code ILIKE '$filter7%')" );
		}

		
		if ($filter10) {
			$this->db->where ( "(contracts.contract_code ILIKE '$filter10')" );
		}

		
		if ($filter15) {
			$this->db->where ( "((SELECT COUNT(*) FROM memos WHERE activity_id = activities.id AND start_day IS NOT NULL AND memos.type = 'FOLLOWUP') > 0)" );
		}

		$this->db->flush_cache ();
		

		$query = $this->db->select('count(activities.id)')
				->join ( 'activities_acl', 'activities_acl.activities_id = activities.id', 'left' )
				->join ( 'companies', 'companies.id = activities.creator_company', 'left' )
				->join ( 'companies duty', 'duty.id = activities_acl.duty_company', 'left' )	
				->join ( 'users', 'users.id = activities.created_by', 'left' )	
				->join ( 'threads', 'threads.id = activities.id_thread', 'left' )
				->join ( 'be', 'be.id = threads.be', 'left' )
				->join ( 'assets', "(assets.be_id = be.id", 'left' )				
				-> join ( 'immobili', 'immobili.id = assets.immobili_id', 'left' )
				->join ( 'address', 'address.id = immobili.address_id', 'left' )
				->join ( 'impianti', 'impianti.id = assets.impianti_id', 'left' )
				->join ( 'contracts', 'contracts.id = activities.id_contract', 'left' )
				->join ( 'accounts', 'accounts.id = threads.customer', 'left' )
				->join ( 'vars s', "s.id_activity = activities.id AND s.key = 'STATUS'", 'left' )
				->join ( 'vars r', "r.id_activity = activities.id AND r.key = 'RESULT'", 'left' )
				->join ( 'vars rn', "rn.id_activity = activities.id AND rn.key = 'RESULT_NOTE'", 'left' )
				->join ( 'setup_processes', 'setup_processes.key = threads.type', 'left' )
				->join ( 'setup_activities', 'setup_activities.key = activities.type AND setup_activities.id_process = setup_processes.id', 'left' )->join ( 'setup_vars', "setup_vars.id_activity = setup_activities.id AND setup_vars.type = 'STATUS'", 'left' )->join ( 'setup_vars_values', 'setup_vars_values.id_var = setup_vars.id AND setup_vars_values.key = s.value', 'left' )
				->where ( 'threads.draft', 'false' )
				->where ( 'activities_acl.role_key = setup_activities.role' )
				->where ( 'setup_activities.type', 'STANDARD' )				
				->get ( 'activities' );
		
		$result = $query->result ();
		
		return $query->row ()->count;
	}
	public function detail($id) {
		$this->db->join ( 'setup_forms', 'setup_forms.id = activities.form_id', 'left' );
		$query = $this->db->select ( 'activities.*,be.id as be_id,setup_activities.role, 
				setup_forms.model as fetch_model_name,setup_activities.id_process,
				setup_forms.url, setup_activities.title, setup_activities.description, 
				setup_activities.help, c.first_name as creator_first_name, 
				c.last_name as creator_last_name, m.first_name as modifier_first_name,
				m.last_name as modifier_last_name, d.first_name as duty_first_name,
				d.last_name as duty_last_name, s.value as status, 
				r.value as result, rn.value as result_note,				
				setup_processes.form_id as form_id_thread' )
		->join ( 'threads', 'threads.id = activities.id_thread', 'left' )
		->join ( 'be', 'be.id = threads.be', 'left' )
		->join ( 'setup_processes', 'setup_processes.key = threads.type', 'left' )
		->join ( 'setup_activities', 'setup_activities.key = activities.type and setup_activities.id_process = setup_processes.id', 'left' )
		->join ( 'users c', 'c.id = activities.created_by', 'left' )
		->join ( 'users m', 'm.id = activities.modified_by', 'left' )
		->join ( 'users d', 'd.id = activities.duty_operator', 'left' )
		->join ( 'vars s', "s.id_activity = activities.id AND s.key = 'STATUS'", 'left' )
		->join ( 'vars r', "r.id_activity = activities.id AND r.key = 'RESULT'", 'left' )
		->join ( 'vars rn', "rn.id_activity = activities.id AND rn.key = 'RESULT_NOTE'", 'left' )
		->where ( 'activities.id', $id )
		->get ( 'activities' );
		return $query->row ();
	}
	public function save($data = NULL, $role = NULL) {
		$query = $this->db->where ( 'id_contract', '5' )->where ( 'role', $role )->get ( 'contracts2companies' );
		$duty = $query->row ();
		
		$data ['duty_company'] = $duty->id_company;
		$data ['owner_company'] = $duty->id_father;
		$data ['id_contract'] = $duty->id_contract;
		
		return $this->db->insert ( 'activities', $data );
	}
	public function get_companies() {
		$this->db->flush_cache ();
		// GET ACL HELPER
		acl ( 'activities' );
		$query = $this->db->distinct ( 'companies.id' )->select ( 'companies.name, companies.id' )->join ( 'companies', 'companies.id = activities.owner_company' )->order_by ( 'companies.name', 'asc' )->get ( 'activities' );
		$result = $query->result ();
		$this->db->flush_cache ();
		return $result;
	}
	public function by_thread($thread) {
		$query = $this->db->select ( 'activities.*, threads.type as thread_type,setup_forms.url, setup_forms.sidebar, c.first_name as creator_first_name, c.last_name as creator_last_name, m.first_name as modifier_first_name, m.last_name as modifier_last_name, d.first_name as duty_first_name, d.last_name as duty_last_name, s.value as status, r.value as result, rn.value as result_note' )->join ( "activities", "activities.id_thread = threads.id" )->join ( 'setup_forms', 'setup_forms.id = activities.form_id', 'left' )->join ( 'users c', 'c.id = activities.created_by', 'left' )->join ( 'users m', 'm.id = activities.modified_by', 'left' )->join ( 'users d', 'd.id = activities.duty_operator', 'left' )->join ( 'vars s', 's.id_activity = activities.id', 'left' )->where ( 's.key', 'STATUS' )->join ( 'vars r', 'r.id_activity = activities.id', 'left' )->where ( 'r.key', 'RESULT' )->join ( 'vars rn', 'rn.id_activity = activities.id', 'left' )->where ( 'rn.key', 'RESULT_NOTE' )->where ( 'activities.id_thread', $thread )->where ( "threads.id", $thread )->order_by ( 'activities.status_modified', 'DESC' )->get ( 'threads' );
		$result = $query->result ();
		
		if (count ( $result ) > 0) {
			foreach ( $result as $item ) {
				$get_details = $this->db->select ( "setup_activities.title, setup_processes.id as process_id,setup_activities.description,setup_activities.role,setup_activities.is_request" )->join ( "threads", "threads.id = activities.id_thread" )->join ( "setup_processes", "setup_processes.key = threads.type" )->join ( "setup_activities", "setup_activities.id_process = setup_processes.id" )->where ( "activities.id", $item->id )->where ( "setup_activities.key", $item->type )->get ( "activities" );
				$result_details = $get_details->row ();
				
				$item->title = $result_details->title;
				$item->description = $result_details->description;
				$item->roles = $result_details->role;
				$item->role = $result_details->role;
				$item->is_request = $result_details->is_request;
				$item->id_process = $result_details->process_id;
			}
		}
		
		return $result;
	}
	public function get_statuses($id_activity, $Key = NULL, $process = NULL) {
		if ($Key != NULL) {
			$this->db->where ( 'setup_vars_values.key', $Key );
		}
		if ($process != NULL) {
			$this->db->where ( 'setup_activities.id_process', $process );
		}
		$query = $this->db->select ( 'setup_vars_values.*' )->where ( 'setup_vars.type', 'STATUS' )->where ( 'setup_vars.disabled', 'f' )->join ( 'setup_activities', 'setup_activities.id = setup_vars.id_activity' )->join ( 'setup_vars_values', 'setup_vars_values.id_var = setup_vars.id' )->where ( 'setup_activities.key', $id_activity )->order_by ( 'setup_vars_values.ordering', 'ASC' )->get ( 'setup_vars' );
		
		return $query->result ();
	}
	public function get_transition_status($id_activity, $Key = NULL, $process = NULL, $real_act_id = NULL) {
		if ($Key != NULL) {
			$this->db->where ( 'setup_vars_values.key', $Key );
		}
		if ($process != NULL) {
			$this->db->where ( 'setup_activities.id_process', $process );
		}
		
		if ($process == NULL || $process == '') {
			$get_process = $this->db->select ( "setup_processes.id" )->join ( "threads", "threads.id = activities.id_thread" )->join ( "setup_processes", "setup_processes.key = threads.type" )->where ( "activities.id", $real_act_id )->get ( "activities" );
			$process = $get_process->row ()->id;
		}
		
		$query = $this->db->select ( 'setup_vars_values.*' )->where ( 'setup_vars.type', 'STATUS' )->where ( 'setup_vars.disabled', 'f' )->join ( 'setup_activities', 'setup_activities.id = setup_vars.id_activity' )->join ( 'setup_vars_values', 'setup_vars_values.id_var = setup_vars.id' )->where ( 'setup_activities.key', $id_activity )->order_by ( 'setup_vars_values.ordering', 'ASC' )->get ( 'setup_vars' );
		$result = $query->result ();
		
		// get current status
		$current_status = $this->db->select ( "vars.value" )->where ( "key = 'STATUS'" )->where ( "id_activity", $real_act_id )->get ( "vars" );
		$status = $current_status->row ()->value;
		
		// get act_type_id
		$get_setup_act_id = $this->db->select ( "setup_activities.id" )->join ( "threads", "threads.id = activities.id_thread" )->join ( "setup_processes", "setup_processes.key = threads.type" )->join ( "setup_activities", "setup_activities.id_process = setup_processes.id" )->where ( "setup_activities.key", $id_activity )->where ( "activities.id", $real_act_id )->get ( "activities" );
		$act_type_id = $get_setup_act_id->row ()->id;
		
		// get transition
		$get_transition = $this->db->select ( "dst_status_key" )->where ( "src_status_key = '" . $status . "'" )->where ( "id_process_type", $process )->where ( "id_activity_type", $act_type_id )->get ( "setup_status_transitions" );
		$transition = $get_transition->result ();
		
		// check transition is used
		if (count ( $transition ) > 0) {
			$trans_array = array ();
			foreach ( $transition as $item ) {
				$trans_array [] = $item->dst_status_key;
			}
			
			if (count ( $result ) > 0) {
				$final_result = array ();
				foreach ( $result as $item ) {
					if ($item->key != $status) {
						// checks for in transition result
						if (in_array ( $item->key, $trans_array )) {
							$final_result [] = $item;
						}
					} else {
						// allows current status
						$final_result [] = $item;
					}
				}
				return $final_result;
			} else {
				return $result;
			}
		} else {
			// if current trnaisition is 0 then check for other status weather there is any transition
			$get_other_transtition = $this->db->select ( "dst_status_key" )->where ( "id_process_type", $process )->where ( "id_activity_type", $act_type_id )->get ( "setup_status_transitions" );
			$other_transition = $get_other_transtition->result ();
			
			if (count ( $other_transition ) > 0) {
				$final_result = array ();
				foreach ( $result as $item ) {
					if ($item->key == $status) {
						// checks for in transition result
						$final_result [] = $item;
					}
				}
				return $final_result;
			}
			return $result;
		}
	}
	public function update_payload($id) {
		$arr = $this->input->post ();
		if ($arr == false) {
			$arr = file_get_contents ( 'php://input' );
			$data = array (
					'payload' => $arr 
			);
		} else {
			unset ( $arr ['errors'] );
			$data = array (
					'payload' => json_encode ( $arr ) 
			);
		}
		
		if ($query = $this->db->where ( 'id', $id )->update ( 'activities', $data )) {
			return true;
		} else {
			return false;
		}
	}
	public function get_setup_activity($activity_id) {
		$query = $this->db->select ( 'setup_activities.key as activity_type_key' )->where ( 'activities.id', $activity_id )->join ( 'activities', 'setup_activities.key=activities.type' )->get ( 'setup_activities' );
		if (isset ( $query->row ()->activity_type_key )) {
			return $query->row ()->activity_type_key;
		}
		return false;
	}
	public function get_acl($activity_id, $role) {
		$query = $this->db->select ( 'duty_user' )->where ( 'role_key', $role )->where ( 'activities_id', $activity_id )->get ( 'activities_acl' );
		return $query->row ();
	}
	public function get_customer($thread_id) {
		$query = $this->db->select ( "accounts.*, address.*,
									(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'tel') as tel,
									(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'email') as email,
									(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'cell') as cell")
			->where ( 'threads.id', $thread_id )
			->join ( 'accounts', 'accounts.id = threads.customer' )
			->join ( 'address', 'address.id = accounts.address_id' )			
			->where ( 'address.type', 'CLIENT' )			
			->get ( 'threads' );
		return $query->row ();
	}

	public function get_visura($act_id) {
		$query = $this->db->select ( 'activities.id_thread, threads.customer,threads.be, be.immobile_id' )->join ( 'threads', 'threads.id = activities.id_thread' )->join ( 'be', 'threads.be = be.id', 'left' )->where ( 'activities.id', $act_id )->get ( 'activities' );
		$result = $query->row ();
		if ($result->immobile_id == NULL) {
			return false;
		} else {
			return $result->immobile_id;
		}
	}
	public function get_immobile($thread_id) {
		$query = $this->db->select ( 'immobili.*, address.*, 
					immobili.id' )
			->where ( 'threads.id', $thread_id )
			->join ( 'be', 'be.id = threads.be' )
			->join ( 'assets', 'assets.be_id = be.id' )
			->join ( 'immobili', 'immobili.id = assets.immobili_id' )			
			->join ( 'address', 'address.id = immobili.address_id' )
			->get ( 'threads' );
		return $query->row ();
	}

	public function get_visura_data($immobile_id) {
		$query = $this->db->where ( 'id', $immobile_id )->get ( 'immobili' );
		return $query->row ();
	}
	public function get_company_name() {
		$query = $this->db->select ( 'co.name' )->join ( 'setup_roles r', 'r.id = cr.role_key' )->join ( 'companies co', 'cr.company_id = co.id' )->where ( 'r.key', 'PT-SOPRALLUOGO' )->where ( 'cr.operative_yn', 'Y' )->get ( 'setup_company_roles cr' );
		return $query->result ();
	}
	public function get_attachments($thread_id, $form_id) {
		/*
		 * $forms= $this->db->select('a.form_id')->where('a.type',$activity_type)->where('a.id_thread',$thread_id)->get('activities a');
		 * $form= $forms->row();
		 */
		$result = array ();
		$result ['attachments'] = array ();
		$result ['collection'] = array ();
		$attachment_type = $this->db->query ( "SELECT array_to_string(array_agg(id_attachment), ',') as attachment_type FROM setup_forms_attachments AS sfa WHERE form_id =  $form_id AND use = 'LIST' " );
		$attachment_type = $attachment_type->row ();
		if ($attachment_type->attachment_type != '') {
			$attachements = $this->db->query ( "SELECT att.*,setup_attachments.title as attachment_type,users.first_name, users.last_name FROM attachments AS att JOIN setup_attachments ON setup_attachments.id = att.attach_type JOIN users ON users.id = att.created_by WHERE attach_type IN ($attachment_type->attachment_type) AND thread_id = $thread_id " );
			$result ['attachments'] = $attachements->result ();
			
			$i = 0;
			foreach ( $result ['attachments'] as $item ) {
				$link = '/common/attachments/download_file/' . crypt_params ( $item->id ) . '/' . crypt_params ( $item->thread_id ) . '/' . crypt_params ( $item->activity_id );
				$result ['attachments'] [$i]->link = $link;
				$i ++;
			}
		}
		$collection = $this->db->query ( "SELECT id_plico FROM setup_forms_collections WHERE form_id = $form_id" );
		$coll_data = $collection->row ();
		if ($coll_data->id_plico != '') {
			$id_collections = $coll_data->id_plico;
			$collection_query = $this->db->query ( "SELECT setup_collections_list.id as coll_id,setup_collections_files.id,setup_collections_list.title,setup_collections_files.id_attachment FROM setup_collections_list JOIN setup_collections_files ON setup_collections_files.id_plico = setup_collections_list.id WHERE setup_collections_list.id IN ($id_collections)GROUP BY setup_collections_list.id,setup_collections_files.id,setup_collections_list.title,setup_collections_files.id_attachment;" );
			$lists = $collection_query->result ();
			$collection_list = array ();
			foreach ( $lists as $list ) {
				if ($list->id_attachment != '') {
					$attach_query = $this->db->query ( "SELECT att.*,setup_attachments.title as attachment_type,users.first_name, users.last_name FROM attachments AS att JOIN setup_attachments ON setup_attachments.id = att.attach_type JOIN users ON users.id = att.created_by WHERE attach_type IN ($list->id_attachment) AND thread_id = $thread_id " );
					$attach = $attach_query->num_rows ();
					if ($attach > 0) {
						$plico_item = new stdClass ();
						$plico_item->title = $list->title;
						$plico_item->filename = $list->title . '.zip';
						$plico_item->thread = $thread_id;
						$plico_item->form_id = $form_id;
						$plico_item->collection_id = $list->coll_id;
						$collection_list [] = $plico_item;
					}
				}
			}
			$result ['collection'] = $collection_list;
			// echo $this->db->last_query();exit;
		}
		if (count ( $result ['collection'] ) == 0 && count ( $result ['attachments'] ) == 0) {
			return false;
		} else {
			return $result;
		}
	}

	public function get_utenti_installatori($company = NULL, $role = NULL) {
		if ($company != NULL)
			$this->db->where ( 'id_company', $company );
		if ($role != NULL) {
			$this->db->join ( 'setup_users_roles', 'setup_users_roles.user_id = users.id' );
			$this->db->join ( 'setup_roles', 'setup_roles.id = setup_users_roles.role_id' );
			$this->db->where ( 'setup_roles.key', $role );
		}
		$query = $this->db->select ( 'users.*' )->order_by ( 'first_name' )->get ( 'users' );
		return $query->result ();
	}

	public function get_impianti($thread_id) {
		$query = $this->db->select ( 'impianti.*' )
		->where ( 'threads.id', $thread_id )
		->join ( 'be', 'be.id = threads.be' )
		->join ( 'assets', 'assets.be_id = be.id' )
		->join ( 'impianti', 'impianti.id = assets.impianti_id' )
		->get ( 'threads' );
		return $query->row ();
	}
	

	public function get_company($user_id) {
		$query = $this->db->select ( 'id_company' )->where ( 'id', $user_id )->get ( 'users' );
		$company_data = $query->row ();
		$data = array ();
		if (isset ( $company_data->id_company )) {
			$data ['id_company'] = $company_data->id_company;
			$new_query = $this->db->query ( "SELECT companies.parent_company FROM companies JOIN setup_roles sr ON sr.key = 'PT' JOIN setup_company_roles scr ON scr.company_id = companies.parent_company WHERE companies.id =  '$company_data->id_company' AND scr.role_key = sr.id;" );
			$parent_company = $new_query->row (); // echo $this->db->last_query();exit;
			if (isset ( $parent_company->parent_company )) {
				$data ['parent_company'] = $parent_company->parent_company;
			}
		}
		
		return $data;
	}

	public function get_roles() {
		if ($this->session->userdata ( 'filter_activities_key' )) {
			$this->db->where ( 'key', $this->session->userdata ( 'filter_activities_key' ) );
		}
		$query = $this->db->distinct ( 'role' )->select ( 'role' )->where ( "role IS NOT NULL" )->get ( 'setup_activities' );
		return $query->result ();
	}
	
	public function get_master_statuses() {
		$query = $this->db->distinct ( 'status' )->select ( 'status' )->get ( 'activities' );
		return $query->result ();
	}
	public function get_activities_types($filter_process = false, $id_process = NULL) {
		// acl('activities');
		if ($filter_process && $filter_process != 0)
			$this->db->where ( 'setup_activities.id_process', $id_process );
		$query = $this->db->distinct ( 'setup_activities.key' )->select ( 'setup_activities.title, setup_activities.key, setup_activities.ordering, setup_activities.id_process' )->order_by ( 'setup_activities.id_process, setup_activities.ordering', 'ASC' )->get ( 'setup_activities' );
		return $query->result ();
	}
	public function get_activities_autocomplete($q) {
		// acl('activities');
		if ($q != NULL) {
			$this->db->where ( "setup_activities.title ILIKE '%$q%'" );
		}
		$query = $this->db->distinct ( 'setup_activities.key' )->select ( 'setup_activities.title, setup_activities.key, setup_activities.ordering, setup_activities.id_process' )->order_by ( 'setup_activities.id_process, setup_activities.ordering', 'ASC' )->get ( 'setup_activities' );
		return $query->result ();
	}
	public function get_processes_types() {
		// acl('activities');
		$query = $this->db->distinct ( 'setup_processes.title' )->select ( 'setup_processes.title, setup_processes.id' )->where ( 'disabled', 'f' )->order_by ( 'setup_processes.title', 'ASC' )->get ( 'setup_processes' );
		return $query->result ();
	}
	public function get_variable($thread, $type, $var) {
		$query = $this->db->where ( 'id_thread', $thread )->where ( 'type', $type )->get ( 'activities' );
		$act = $query->row ();
		
		$query = $this->db->where ( 'id_thread', $thread )->where ( 'id_activity', $act->id )->where ( 'key', $var )->get ( 'vars' );
		$var = $query->row ();
		
		return $var->value;
	}
	public function get_activities_for_cancel($thread_id) {
		$query = $this->db->select ( 'activities.id' )->where ( 'activities.id_thread', $thread_id )->where ( "(activities.status != 'CHIUSO' AND activities.status != 'CANCELED')" )->get ( 'activities' );
		return $query->result ();
	}

	public function get_pt_tecnico_allaccio($thread) {
		$query = $this->db->select ( 'c.pt_company_id' )->where ( 'd.id', $thread )->from ( 'threads d' )->join ( 'be c', 'c.id = d.be' )->get ();
		$pt = $query->row ();
		
		$parent = $pt->pt_company_id;
		$company_id = $parent;
		
		$count = 1;
		
		while ( $count > 0 ) {
			$query = $this->db->select ( 'companies.id, companies.name' )->join ( 'setup_company_roles', 'setup_company_roles.company_id = companies.id' )->join ( 'setup_roles', 'setup_roles.id = setup_company_roles.role_key' )->where ( 'setup_roles.key', 'PT-TECNICO-ALLACCIO' )->where ( "companies.parent_company", $parent )->where ( 'setup_company_roles.operative_yn', 'Y' )->order_by ( "companies.id", "asc" )->get ( 'companies' );
			$count = $query->num_rows ();
			
			if ($count == 0) {
				$company_id = $parent;
			} else {
				$parent = $query->row ()->id;
				$companies = $query->result ();
			}
		}
		
		$i = 0;
		if (is_array ( $companies ) && ! empty ( $companies )) {
			foreach ( $companies as $company ) {
				$query = $this->db->select ( 'users.*' )->join ( 'users', 'users.id = setup_users_roles.user_id' )->join ( 'setup_roles', 'setup_roles.id = setup_users_roles.role_id' )->where ( 'setup_roles.key', 'PT-TECNICO-ALLACCIO' )->where ( 'users.id_company', $company->id )->get ( 'setup_users_roles' );
				$companies [$i]->users = $query->result ();
				$i ++;
			}
		} else {
			$query = $this->db->where ( "companies.id", $company_id )->get ( 'companies' );
			$companies [0] = $query->row ();
			$query = $this->db->select ( 'users.*' )->join ( 'users', 'users.id = setup_users_roles.user_id' )->join ( 'setup_roles', 'setup_roles.id = setup_users_roles.role_id' )->where ( 'setup_roles.key', 'PT-TECNICO-ALLACCIO' )->where ( 'users.id_company', $company_id )->get ( 'setup_users_roles' );
			$companies [0]->users = $query->result ();
		}
		return $companies;
	}
	
	public function get_indirizzi_cliente($customer_id) {
		$query = $this->db->where ( 'accounts.id', $customer_id )
			->join('accounts', 'accounts.address_id = address.id')
			->where ( 'address.type', 'CLIENT' )
			->get ( 'address' );
		return $query->row_array ();
	}


	public function get_be_details($be_id) {
		$query = $this->db->select ( 'be.*' )->where ( 'be.id', $be_id )->get ( 'be' );
		return $query->row_array ();
	}



	public function get_contratti($beid = NULL) {
		if ($beid != NULL) {
			$query = $this->db->query ( "select * from contracts LEFT JOIN assets on assets.contract_id = contracts.id where assets.be_id='" . $beid . "'" );
			return $query->row ();
		} else {
			return array ();
		}
	}
	public function get_be($thread = NULL) {
		if ($thread != NULL) {
			$query = $this->db->select ( 'be.*' )->where ( 'threads.id', $thread )->join ( 'be', 'be.id = threads.be', 'left' )->get ( 'threads' );
			return $query->row ();
		} else {
			return array ();
		}
	}
	public function get_related_payload($key, $thread) {
		$query = $this->db->where ( 'type', $key )->where ( 'id_thread', $thread )->get ( 'activities' );
		$result = $query->row ();
		return json_decode ( $result->payload );
	}
	public function get_related_id($key, $thread) {
		$query = $this->db->where ( 'type', $key )->where ( 'id_thread', $thread )->get ( 'activities' );
		$result = $query->row ();
		return $result->id;
	}

	public function get_beid_from_activity($id_activity) {
		$query = $this->db->select ( 'threads.be' )->where ( 'activities.id', $id_activity )->JOIN ( 'threads', 'activities.id_thread=threads.id', 'left' )->get ( 'activities' );
		$result = $query->row ();
		return $result->be;
	}

	public function thread_rel_activity($thread) {
		$query = $this->db->select ( 'activities.*,setup_activities.title as setup_title' )->join ( 'threads', 'threads.id=activities.id_thread' )->join ( 'setup_processes', 'setup_processes.key = threads.type' )->join ( 'setup_activities', 'setup_activities.id_process=setup_processes.id' )->where ( 'id_thread', $thread )->where ( 'setup_activities.key = activities.type' )->order_by ( "activities.created" )->get ( 'activities' );
		return $query->result ();
	}
	public function get_integrations($thread) {
		// $query = $this->db->where('id_thread',$thread)->order_by('id','DESC')->get('export_billing');
/* 		$query1 = $this->db->query ( 'SELECT DISTINCT(threads.id), exb.tipologia, exb.status, exb.d_decorrenza, exb.gross_d_invio, exb.gross_esito, exb.billing_d_invio, exb.return_value, exb.billing_esito, exb.billing_esito_note, exb.created FROM export_billing exb LEFT JOIN threads ON threads.id=exb.id_thread WHERE threads.id = ' . $thread . ' ORDER BY exb.created DESC' );
		$query2 = $this->db->query ( 'SELECT DISTINCT(threads.id), exa.status as exa_status, exa.billing_d_invio as exa_billing_d_invio, exa.billing_esito as exa_billing_esito, exa.billing_esito_note as exa_billing_esito_note, exa.created as exa_created FROM export_autoletture exa LEFT JOIN threads ON threads.id=exa.thread_id  WHERE threads.id = ' . $thread . ' ORDER BY exa.created DESC' );
		$query3 = $this->db->query ( 'SELECT exr.created as exr_created, exr.status as exr_status, exr.causale as exr_causale FROM export_rettifiche exr LEFT JOIN threads ON threads.id = exr.thread_id WHERE threads.id = ' . $thread . ' ORDER BY exr.created DESC' );
		$result1 = $query1->result ();
		$result2 = $query2->result ();
		$result3 = $query3->result ();
		
		$newarray = array_merge_recursive ( $result1, $result2, $result3 );
		 */
		return NULL;
	}
	public function get_process_list($thread) {
		$query = $this->db->select ( "setup_activities.*" )->join ( "setup_processes", "setup_processes.key = threads.type" )->join ( "setup_activities", "setup_activities.id_process = setup_processes.id" )->where ( 'threads.id', $thread )->order_by ( 'setup_activities.id', 'ASC' )->get ( 'threads' );
		return $query->result ();
	}

	public function get_magic_fields($form_id, $act_type, $activity_id) {
		$query = $this->db->select ( "title" )->where ( 'id', $form_id )->get ( 'setup_forms' );
		$form_details = $query->row ();
		if ($form_details->title == 'MAGIC_FORM') {
			$query = $this->db->query ( "select setup_processes.id as process_id,setup_activities.id as setup_act_id from activities join threads ON activities.id_thread=threads.id left join setup_processes ON threads.type=setup_processes.key left join setup_activities ON setup_processes.id= setup_activities.id_process WHERE activities.type = '" . $act_type . "' and setup_activities.key = '" . $act_type . "' AND activities.id = '" . $activity_id . "'" );
			$vars_result = $query->row ();
			
			if (count ( $vars_result ) > 0) {
				$query = $this->db->select ( '*' )->where ( 'id_process', $vars_result->process_id )->where ( 'id_activity', $vars_result->setup_act_id )->where ( 'source', 'CUSTOM' )->where ( 'type', 'MAGIC_FORM' )->where ( 'disabled', 'f' )->order_by ( 'ordering' )->get ( 'setup_vars' );
				$magic_vars = $query->result ();
				if (count ( $magic_vars ) > 0) {
					foreach ( $magic_vars as &$vars ) {
						if ($vars->layout == 'dropdown') {
							$query = $this->db->select ( 'key,label' )->where ( 'disabled', 'f' )->where ( 'id_var', $vars->id )->order_by ( 'ordering' )->get ( 'setup_vars_values' );
							$vars->options = $query->result ();
						}
					}
				}
				return $magic_vars;
			}
		} else {
			return false;
		}
	}

}
