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
class Activities extends Common_Controller {
	public function __construct() {
		parent::__construct ();
		ob_start ();
		$this->load->model ( 'activity' );
		$this->load->model ( 'be' );
		$this->breadcrumb->append ( 'Activity', '/common/activities/' );
	}
	
	/**
	 * Index Page for this controller.
	 */
	public function index() {
		$this->get ();
		$this->load->model ( 'activity' );
	}
	public function export() {
		ini_set ( "memory_limit", "-1" );
		set_time_limit ( 0 );
		$this->load->helper ( 'php-excel' );
		$filename = "activity_export-" . date ( 'd-m-Y-His' );
		
		// fetches the XLS details
		$export = $this->activity->export ();
		$field_array [] = array_keys ( $export [0] );
		$xls = new Excel_XML ();
		$xls->addArray ( $field_array );
		$xls->addArray ( $export );
		$xls->generateXML ( $filename );
		return false;
	}

	public function filter_type($type, $process) {
		$this->session->unset_userdata ( 'filter_activities_type' );
		$this->session->unset_userdata ( 'filter_activities_role' );
		$this->session->unset_userdata ( 'filter_activities_thread' );
		$this->session->unset_userdata ( 'filter_activities_status' );
		$this->session->unset_userdata ( 'filter_activities_cliente' );
		$this->session->unset_userdata ( 'filter_activities_pod' );
		$this->session->unset_userdata ( 'filter_activities_tipo_pratica' );
		$this->session->unset_userdata ( 'filter_activities_codice_contratto' );
		$this->session->unset_userdata ( 'filter_activities_duty' );
		
		$this->session->set_userdata ( 'filter_activities_type', $type );
		$this->session->set_userdata ( 'filter_activities_process', $process );
		$this->session->set_userdata ( 'filter_activities_status', 'APERTO' );
		redirect ( '/common/activities/' );
	}
	public function filter_duty($type) {
		$this->session->unset_userdata ( 'filter_activities_type' );
		$this->session->unset_userdata ( 'filter_activities_role' );
		$this->session->unset_userdata ( 'filter_activities_thread' );
		$this->session->unset_userdata ( 'filter_activities_status' );
		$this->session->unset_userdata ( 'filter_activities_cliente' );
		$this->session->unset_userdata ( 'filter_activities_pod' );
		$this->session->unset_userdata ( 'filter_activities_tipo_pratica' );
		$this->session->unset_userdata ( 'filter_activities_codice_contratto' );
		$this->session->unset_userdata ( 'filter_activities_duty' );
		
		$user = $this->ion_auth->user ()->row ()->id;
		$this->session->set_userdata ( 'filter_activities_duty', $user );
		$this->session->set_userdata ( 'filter_activities_type', $type );
		$this->session->set_userdata ( 'filter_activities_status', 'APERTO' );
		redirect ( '/common/activities/' );
	}
	public function filter_open() {
		$this->session->unset_userdata ( 'filter_activities_type' );
		$this->session->unset_userdata ( 'filter_activities_role' );
		$this->session->unset_userdata ( 'filter_activities_thread' );
		$this->session->unset_userdata ( 'filter_activities_status' );
		$this->session->unset_userdata ( 'filter_activities_cliente' );
		$this->session->unset_userdata ( 'filter_activities_pod' );
		$this->session->unset_userdata ( 'filter_activities_tipo_pratica' );
		$this->session->unset_userdata ( 'filter_activities_codice_contratto' );
		$this->session->unset_userdata ( 'filter_activities_duty' );
		
		$this->session->set_userdata ( 'filter_activities_status', 'APERTO' );
		redirect ( '/common/activities/' );
	}
	public function get() {
		$data = array ();
		
		$data ['activities'] = $this->activity->get ( $this->config->item ( 'per_page' ), $this->uri->segment ( 5 ) );
		
		$config ['base_url'] = '/common/activities/get/page/';
		$config ['total_rows'] = $this->activity->total ();
		
		$data ['total_rows'] = $config ['total_rows'];
		
		$data ['companies'] = $this->activity->get_companies ();
		
		$data ['master_statuses'] = $this->activity->get_master_statuses ();
		$data ['activities_types'] = $this->activity->get_activities_types ( true, $this->session->userdata ( 'filter_activities_process' ) );
		$data ['processes_types'] = $this->activity->get_processes_types ();
		
		$data ['roles'] = $this->activity->get_roles ();
		$this->pagination->initialize ( $config );
		
		$data ['content'] = $this->load->view ( 'common/activities/list', $data, true );
		$this->load->view ( 'template', $data );
	}
	public function types($q = NULL) {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->activity->get_activities_autocomplete ( $q ) ) );
	}
	public function filtered($listname = NULL) {
		$this->session->set_userdata ( 'filter_activities', $listname );
		redirect ( '/common/activities' );
	}
	function attach_types($form) {
		$this->load->library ( 'Attachments' );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->attachment->list_types ( $form ) ) );
	}
	public function detail($id = NULL) {
		$user_id = $this->ion_auth->user ()->row ()->id;
		
		$this->load->helper ( 'acl_helper' );
		
		$activity_result = check_activity_details_permission ( $this->activity, $user_id, $id );
		
		if (count ( $activity_result ) > 0) {
			$this->load->model ( 'wmanager/setup_activity' );
			$this->load->model ( 'attachment' );
			
			$data = array ();
			$activity = $activity_result;
			
			$activity_type_data = $this->setup_activity->get_single_by_key ( $activity->type );
			$data ['attach_type'] = $this->attachment->list_types ( $activity_type_data->form_id );
			$data ['activity'] = $activity;
			$this->session->set_userdata ( 'attach_type', $data ['attach_type'] );
			$data ['content'] = $this->load->view ( 'common/activities/detail', $data, true );
			$this->load->view ( 'template', $data );
		} else {
			$data ['content'] = $this->load->view ( 'access_denied/access_denied', $data, true );
			$this->load->view ( 'template', $data );
		}
	}
	public function get_activity($id) {
		$this->load->model ( 'wizard' );
		$activity = $this->activity->detail ( $id );
		
		$company = $this->activity->get_company_name ();
		$statuses = $this->activity->get_transition_status ( $activity->type, NULL, $activity->id_process, $id );
		$customer = $this->activity->get_customer ( $activity->id_thread );		
		$immobile = $this->activity->get_immobile ( $activity->id_thread );		
		$impianti = $this->activity->get_impianti ( $activity->id_thread );		
		$activity->indirizzi_cliente = $this->activity->get_indirizzi_cliente ( $impianti->be_id, $impianti->cliente_id );		
		$activity->be = $this->activity->get_be ( $activity->id_thread );
		$this->load->model ( 'account' );
		$activity->account = $this->account->detail ( $impianti->cliente_id );
		$activity->company = $company;
		$activity->customer = $customer;		
		$activity->immobile = $immobile;
		$activity->statuses = $statuses;		
		$activity->impianti = $impianti;
		$activity->contratti = $this->activity->get_contratti ( $activity->impianti->be_id );		
		$activity->magic_variables = $this->activity->get_magic_fields ( $activity->form_id, $activity->type, $activity->id );		

		// CHANGE FORM DATA BASED ON FORM/ACTIVITY TYPE
		$this->load->library ( "get_activity_data" );
		if ($id != null && isset ( $activity->type )) {
			$activity_lib_data = $this->get_activity_data->get_data ( $id, $activity );
			$activity = $activity_lib_data;
		}
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $activity ) );
	}
	public function get_product($id) {
		$product = $this->activity->get_product ( $id );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $product ) );
	}
	public function get_company_name() {
		$response = array (
				'result' => $this->activity->get_company_name () 
		);
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $response ) );
	}
	public function get_attachments() {
		$this->load->view ( 'common/activities/attach' );
	}
	public function get_attachment($thread_id, $form_id) {
		$data = array ();
		$attachment = $this->activity->get_attachments ( $thread_id, $form_id );
		if (! $attachment)
			$attachment = new stdClass ();
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $attachment ) );
	}
	public function get_single_activity($act_id) {
		$activity = $this->activity->detail ( $act_id );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $activity ) );
	}

	public function savefollowup() {
		$this->load->library ( 'memos' );
		
		$data = array (
				'start_data' => (isset ( $_POST ['day'] )) ? $this->input->post ( 'day' ) : '',
				'start_time' => (isset ( $_POST ['time'] )) ? $this->input->post ( 'time' ) : '',
				'end_data' => NULL,
				'end_time' => NULL,
				'title' => 'Nuovo Followup',
				'description' => (isset ( $_POST ['text'] )) ? $this->input->post ( 'text' ) : '' 
		);
		
		if ($this->input->post ( 'scheduled' ) == 'false') {
			unset ( $data ['start_data'] );
			unset ( $data ['start_time'] );
		}
		
		if ($data ['start_time'] == '')
			unset ( $data ['start_time'] );
		if ($data ['start_data'] == '')
			unset ( $data ['start_data'] );
		
		$res = $this->memos->add ( 'FOLLOWUP', NULL, "ACTIVITY", $this->input->post ( 'actid' ), $data );
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array (
				'result' => $res 
		) ) );
	}
	public function setdonefollowup($id) {
		$res = $this->db->where ( 'id', $id )->update ( 'memos', array (
				'isdone' => 't' 
		) );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array (
				'result' => $res 
		) ) );
	}
	
	public function test(){
		$actions = '$NEXTID=Create_Activity(BO_RECESSO_SWITCH;STATUS=DONE;);
$RES=Set_Status_Activity(CHIUSO;);
$RES=Credit.Send_Email(ALLACIO;TO;CC;NULL;NULL;TEMPLATE=ALLACIO|TYPE=HELLO)';
		
		$act_id = 22776;
		$thread = 7448;
		echo "<pre>";
		print_r($this->core_actions->decoding_action($actions,$act_id,$thread));
		exit;
	}

}
