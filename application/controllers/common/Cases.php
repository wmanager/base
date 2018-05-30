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
class Cases extends Common_Controller {
	public function __construct() {
		ob_start ();
		parent::__construct ();
		$this->load->model ( 'thread' );		
		$this->load->model ( 'customer' );		
		$this->load->model ( 'wmanager/process' );
		$this->load->model ( 'activity' );
		$this->load->model ( 'followup' );
		$this->load->model ( 'attachment' );
		$this->breadcrumb->append ( 'Cases' );
	}
	
	/**
	 * Index Page for this controller.
	 */
	public function index() {
		$this->get ();
	}
	public function filter_type($type) {
		$this->session->set_userdata ( 'filter_thread_type', $type );
		
		redirect ( '/common/cases' );
	}
	public function get() {
		$data = array ();
		
		$data ['cases'] = $this->thread->get ( $this->config->item ( 'per_page' ), $this->uri->segment ( 5 ) );
		
		$config ['base_url'] = '/common/cases/get/page/';
		$config ['total_rows'] = $this->thread->total ();
		$data ['total_rows'] = $config ['total_rows'];
		$this->pagination->initialize ( $config );
		$data ['processes_types'] = $this->thread->get_thread_types ();
		$data ['macro_processes_types'] = $this->thread->get_macro_process ();
		$data ['master_statuses'] = $this->activity->get_master_statuses ();
		$data ['setup_mps'] = $this->thread->get_setup_mps ();
		
		$data ['visible'] = $this->ion_auth->in_group ( array (
				'admin' 
		) );
		$data ['content'] = $this->load->view ( 'common/cases/list', $data, true );
		$this->load->view ( 'template', $data );
	}
	public function create() {
		$this->breadcrumb->append ( 'New' );
		$data = array ();
		$data ['content'] = $this->load->view ( 'common/cases/create', $data, true );
		$this->load->view ( 'template', $data );
	}
	
	public function thread_create(){
		$thread_process_id = $this->actions->create_thread('TESTING', '1', '1' ,NULL, NULL,'f');
		echo $thread_process_id;
		$act = $this->core_actions->create_activity('THREAD',$thread_process_id,'ACT',array('STATUS'=>'NEW'),NULL);
		echo $act;
		exit;
	}
	public function edit($thread) {
		$this->breadcrumb->append ( 'Details' );
		$data = array ();
		$data ['thread'] = $thread;
		$data ['content'] = $this->load->view ( 'common/cases/edit', $data, true );
		$this->load->view ( 'template', $data );
	}
	
	public function get_thread($id) {
		$thread = $this->thread->single ( $id );		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $thread ) );
	}
	public function get_sla($id) {
		$thread = $this->thread->single ( $id );
		$remaining = $this->sla->time_remaining ( $thread->deadline );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array (
				'sla' => $thread->sla,
				'deadline' => $thread->deadline,
				'remaining' => $remaining 
		) ) );
	}
	public function get_customer($id) {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->customer->single ( $id ) ) );
	}

	public function get_process($process, $type) {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->process->get_single_key ( $process, $type ) ) );
	}
	public function get_activities($thread) {
		$activities = $this->activity->by_thread ( $thread );

		foreach ( $activities as &$act ) {
			$statuses = $this->activity->get_transition_status ( $act->type, NULL, $act->id_process, $act->id );
			$act->statuses = $statuses;
			$customer = $this->activity->get_customer ( $act->id_thread );	

			$act->indirizzi_cliente = $this->activity->get_indirizzi_cliente ( $customer->id );
			$act->company = ''; // $company;
			$act->customer = $customer;			
			$act->statuses = $statuses;						
			$act->be = $this->activity->get_be_details ( $act->be_id );			
			$act->contratti = $this->activity->get_contratti ( $act->be_id );
			$act->magic_variables = $this->activity->get_magic_fields ( $act->form_id, $act->type, $act->id );
		}
 
		$company = $this->activity->get_company_name ();
		$activities [0]->company = $company;

		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $activities ) );
	}
	public function get_rel_activities($thread) {
		$activities = $this->activity->thread_rel_activity ( $thread );
		foreach ( $activities as $activity ) {
				$activity->link = "/common/activities/detail/$activity->id";
			
		}
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $activities ) );
	}
	public function create_thread() {
		$this->load->library ( 'actions' );
		if ($id = $this->actions->create_thread ( $this->input->post ( 'type' ), $this->input->post ( 'customer' ), $this->input->post ( 'be' ), NULL )) {
			
			$type = $this->process->get_single_process_key ( $this->input->post ( 'type' ) );
			$sla = $this->sla->calculate ( $type->sla );
			$remaining = $this->sla->time_remaining ( $sla );
			
			$tee = array ();
			$province = array ();
			
			
			log_message ( 'DEBUG', 'thread_created' );
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array (
					'result' => true,
					'id' => $id,
					'sla' => $type->sla,
					'deadline' => $sla,
					'remaining' => $remaining,
					'form_id' => $type->form_id,
					'tee' => $tee,
					'province' => $province 
			) ) );
		} else {
			log_message ( 'DEBUG', 'thread_not_created' );
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array (
					'result' => false 
			) ) );
		}
	}

	public function activity_main() {
		$this->load->view ( 'common/cases/activities/main' );
	}
	public function activity_summary($id = NULL) {
		$data ['activity'] = $id;
		$this->load->view ( 'common/cases/activities/summary', $data );
	}
	public function activity_trouble() {
		$this->load->view ( 'common/cases/activities/troubles' );
	}
	public function activity_pending() {
		$this->load->view ( 'common/cases/activities/pending' );
	}
	public function activity_related() {
		$this->load->view ( 'common/cases/activities/related' );
	}
	public function activity_followup() {
		$this->load->view ( 'common/cases/activities/followup' );
	}

	public function process_list() {
		$this->load->view ( 'common/cases/activities/process' );
	}
	public function debug_user() {
		$this->load->view ( 'common/cases/activities/userdomain' );
	}
	public function activity_detail($process, $form) {
		$this->load->view ( 'common/cases/activities/forms/' . $process . '/' . $form );
	}
	public function save_activity_process($id) {
		$result = $this->activity->update_payload ( $id );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array (
				'result' => $result 
		) ) );
	}
	public function set_thread($thread_id) {
		$this->session->set_userdata ( 'filter_activities_thread', $thread_id );
		redirect ( '/common/activities' );
	}
	public function get_attachment($thread_id, $form_id) {
		$data = array ();
		$this->load->model ( 'activity' );
		$attachment = $this->activity->get_attachments ( $thread_id, $form_id );
		if (! $attachment)
			$attachment = new stdClass ();
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $attachment ) );
	}
	public function get_required_attach($form) {
		$response = array (
				'result' => $this->thread->get_required_attach ( $form ) 
		);
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $response ) );
	}
	function attach_types($form) {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->attachment->list_types ( $form ) ) );
	}
	public function cancel($id) {
		$data ['thread_id'] = $id;
		$data ['details'] = $this->thread->single ( $id );
		$data ['cancel_reasons'] = $this->thread->get_cancel_reasons ();
		$this->load->view ( 'common/cases/cancel', $data );
	}
	public function ajax_cancel_thread() {
		if ($_POST ['thread_id'] != '') {
			
			// Thread status change
			$this->core_actions->Set_Satus_Thread ( $_POST ['thread_id'], 'CANCELLED', $_POST ['reason'] );
			
			// Activities status change
			// if(isset($_POST['cancel_activities']) && $_POST['cancel_activities'] !=''){
			$activities = $this->activity->get_activities_for_cancel ( $this->input->post ( 'thread_id' ) );
			if (count ( $activities ) > 0) {
				foreach ( $activities as $activity ) {
					$this->core_actions->Set_Status_Activity ( $activity->id, 'CANCELLED', $this->input->post ( 'reason' ) );
				}
			}
			// }
			$this->session->set_flashdata ( 'msge_success', ' Record has been updated correctly.' );
		} else {
			$this->session->set_flashdata ( 'msge_error', 'There was an error, please try again.' );
		}
	}
	
	// Remove the draft status from thread
	public function draft($thread_id) {
		return $this->thread->draft ( $thread_id );
	}
	public function delete_draft($thread_id) {
		return $this->thread->delete_draft ( $thread_id );
	}
	public function get_by_customer($customer_id, $thread_id = NULL, $trouble_id = NULL) {
		if ($thread_id == 'null')
			$thread_id = NULL;
		$threads = $this->thread->get_by_customer ( $customer_id, $thread_id, $trouble_id );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $threads ) );
	}
	public function get_request_by_customer($customer_id, $thread_id = NULL, $trouble_id = NULL) {
		if ($thread_id == 'null')
			$thread_id = NULL;
		$threads = $this->thread->get_request_by_customer ( $customer_id, $thread_id, $trouble_id );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $threads ) );
	}
	public function pending($thread_id) {
		$input = json_decode ( file_get_contents ( 'php://input' ), true );
		if ($input ['set']) {
			$this->core_actions->Set_Satus_Thread ( $thread_id, 'PENDING', $input ['reason'] );
			if ($input ['related']) {
				$this->db->where ( 'id', $thread_id )->update ( 'threads', array (
						'pending_parent_thread' => $input ['related'] 
				) );
			}
		}
		if (! $input ['set']) {
			$this->core_actions->Set_Satus_Thread ( $thread_id, 'OPEN', '' );
			$this->db->where ( 'id', $thread_id )->update ( 'threads', array (
					'pending_parent_thread' => NULL 
			) );
		}
	}
	public function create_related_activity($thread_id = NULL, $trouble_id = NULL) {
		if ($thread_id == 'null')
			$thread_id = NULL;
		if ($this->input->post ()) {
			$related = $this->input->post ( 'related' );
			
			foreach ( $related as $item ) {
				if ($this->input->post ( 'thread' ) && $this->input->post ( 'thread' ) != '') {

				$this->core_actions->create_activity ( 'THREAD', $this->input->post ( 'thread' ), $item, array (
						'STATUS' => 'NEW' 
				), NULL );
					
				}
			}
			redirect ( '/common/cases/edit/' . $this->input->post ( 'thread' ) );
		}
		
		$data ['related'] = $this->thread->get_related_activities_by_thread ( $thread_id );
		$data ['thread'] = $thread_id;
		$data ['trouble'] = $trouble_id;
		$this->load->view ( 'common/cases/create_related_activity', $data );
	}
	public function savefollowup() {
		$this->load->library ( 'memos' );
		
		$data = array (
				'start_data' => $this->input->post ( 'day' ),
				'start_time' => $this->input->post ( 'time' ),
				'title' => 'Nuovo Followup',
				'description' => $this->input->post ( 'text' ),
				'end_data' => NULL,
				'end_time' => NULL 
		);
		
		if ($this->input->post ( 'scheduled' ) == 'false') {
			unset ( $data ['start_data'] );
			unset ( $data ['start_time'] );
		}
		if ($data ['start_time'] == '')
			unset ( $data ['start_time'] );
		if ($data ['start_date'] == '')
			unset ( $data ['start_date'] );
		
		$res = $this->memos->add ( 'FOLLOWUP', NULL, "THREAD", $this->input->post ( 'thread' ), $data );
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array (
				'result' => $res 
		) ) );
	}
	public function get_request_by_trouble_id($cust_id, $thread_id = NULL, $trouble_id = NULL) {
		if ($thread_id == 'null')
			$thread_id = NULL;
		$threads = $this->thread->get_request_for_trouble ( $customer_id, $thread_id, $trouble_id );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $threads ) );
	}

	public function get_process_list($thread) {
		$process = $this->activity->get_process_list ( $thread );
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $process ) );
	}
	public function check_process_creation_permission() {
		$process_key = $_POST ['process'];
		
		$check = $this->thread->check_process_creation_permission ( $process_key );
		
		echo $check;
		exit ();
	}
	
	/*
	 * thread_cancel
	 * function to load the cancel thread page with related activties and related integration
	 * $thread_id int
	 * Raghavendra Naik
	 */
	public function thread_cancel($thread_id) {
		$this->breadcrumb->append ( 'Thread cancel' );
		$roles = array (				
				'admin' 
		);
		$CI = & get_instance ();
		$data ['thread_id'] = $thread_id;
		$user_role = $CI->ion_auth->in_group ( $roles );
		$data = array ();
		if ($user_role) {
			$data ['related_activity'] = $this->activity->thread_rel_activity ( $thread_id );			
			$data ['content'] = $this->load->view ( 'common/cases/thread-cancel', $data, true );
			$this->load->view ( 'template', $data );
		} else {
			$data ['content'] = $this->load->view ( 'access_denied/access_denied', $data, true );
			$this->load->view ( 'template', $data );
		}
	}
	
	/*
	 * update_status
	 * function to load the cancel thread page with related activties and related integration
	 * $thread_id int
	 * Raghavendra Naik
	 */
	public function update_status($thread_id) {
		$roles = array (			
				'admin' 
		);

		$CI = & get_instance ();
		$user_role = $CI->ion_auth->in_group ( $roles );
		if ($user_role) {
			$data ['related_activity'] = $this->activity->thread_rel_activity ( $thread_id );
			if ((is_array ( $data ['related_activity'] )) && (count ( $data ['related_activity'] ) > 0)) {
				foreach ( $data ['related_activity'] as $row ) {
					if ($row->status != 'CLOSED') {
						$this->core_actions->Set_Status_Activity ( $row->id, 'CANCELLED', '' );
						$this->core_actions->Set_Satus_Thread ( $row->id_thread, 'CANCELLED', '' );						
					}
				}
			}
		}
		redirect ( "/common/cases/edit/$thread_id" );
	}

	public function export_thread() {
		$this->load->helper ( 'php-excel' );
		$filename = "thread_export-" . date ( 'd-m-Y-His' );
		
		// fetches the XLS details
		$export = $this->thread->get_export_data ();
		
		$field_array [] = array_keys ( $export [0] );
		$xls = new Excel_XML ();
		$xls->addArray ( $field_array );
		$xls->addArray ( $export );
		$xls->generateXML ( $filename );
		return false;
	}
	
	public function new_thread(){
		
		$data = array();
		
		$data['content'] = $this->load->view("/common/cases/add_thread",$data,true);
		$this->load->view ( 'template', $data );
	}
	
	public function create_new_thread(){
		$this->load->library ( "core/core_actions" );
		
		$thread_process_id = $this->actions->create_thread($_POST['process_key'], $_POST['customer_id'], $_POST['be_id'] ,NULL, NULL,'f');
		
		if($thread_process_id > 0){
			$act = $this->core_actions->create_activity('THREAD',$thread_process_id,$_POST['request_key'],array('STATUS'=>'NEW'),NULL);
		}else{
			$act = 0;
		}
		
		if($act > 0){
			$return =  array(
				"status" => true,
				"message" => "Activity created successfully",
				"act_id" => $act	
			);
		}else{
			$return = array(
				"status" => false,
				"message" => "Activity failed to create"	
			);
		}
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $return ) );
	}
}
