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
class Troubles extends Common_Controller {
	public function __construct() {
		ob_start ();
		parent::__construct ();
		$this->load->model ( 'trouble' );		
		$this->load->model ( 'customer' );
		$this->breadcrumb->append ( 'Troubles' );
	}
	
	/**
	 * Index Page for this controller.
	 */
	public function index() {
		$this->get ();
	}
	public function get() {
		$data = array ();		
		$data ['troubles'] = $this->trouble->get ( $this->config->item ( 'per_page' ), $this->uri->segment ( 5 ) );
		$data ['trouble_types'] = $this->trouble->get_trouble_types ();
		$data ['trouble_sub_types'] = $this->trouble->get_trouble_sub_types ();
		$data ['trouble_status'] = $this->trouble->get_status ();
		$config ['base_url'] = '/common/troubles/get/page/';
		$config ['total_rows'] = $this->trouble->total ();
		$data ['total_rows'] = $config ['total_rows'];
		$this->pagination->initialize ( $config );
		
		$data ['content'] = $this->load->view ( 'common/troubles/list', $data, true );
		$this->load->view ( 'template', $data );
	}
	public function edit($id = null) {
		$user_id = $this->ion_auth->user ()->row ()->id;
		
		$this->load->helper ( 'acl_helper' );
		
		$data = array ();
		$this->load->model ( 'account' );	
		$data ['content'] = $this->load->view ( 'common/troubles/add', $data, true );
		$this->load->view ( 'template', $data );
	}
	public function add() {
		$data = array ();
		$data ['content'] = $this->load->view ( 'common/troubles/add', $data, true );
		$this->load->view ( 'template', $data );
	}
	public function create_trouble() {
		$input_data = json_decode ( trim ( file_get_contents ( 'php://input' ) ), true );
		
		if ($id = $this->trouble->add ( $input_data )) {
			$processes = $this->trouble->fetch_process ( $input_data ['type'] );
			
			if (count ( $processes ) > 0) {
				foreach ( $processes as $process ) {
					$this->actions->create_thread_tree ( $process->process_key, $input_data ['customer'] ['account_id'], $input_data ['contract'], $duty = NULL, $id, 'f', $process->request_key );
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array (
					'result' => $id 
			) ) );
		} else {
			return $this->output->set_content_type ( 'application/json' )->set_status_header ( 500 )->set_output ( json_encode ( array (
					'text' => 'Error 500',
					'type' => 'danger' 
			) ) );
		}
	}
	public function edit_trouble($id) {
		$input_data = json_decode ( trim ( file_get_contents ( 'php://input' ) ), true );
		if ($id = $this->trouble->edit ( $id, $input_data )) {
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array (
					'result' => $id 
			) ) );
		} else {
			
			return $this->output->set_content_type ( 'application/json' )->set_status_header ( 500 )->set_output ( json_encode ( array (
					'text' => 'Error 500',
					'type' => 'danger' 
			) ) );
		}
	}
	public function detail($id) {
		return $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->trouble->single ( $id ) ) );
	}

	public function get_customer($id) {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->customer->single ( $id ) ) );
	}
	public function get_contract($id) {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->contract->single ( $id ) ) );
	}
	public function related() {
		$this->load->view ( 'common/troubles/related' );
	}
	public function dashboard($customer, $trouble) {
		$this->load->model ( 'account' );
		$data ['customer'] = $customer;
		$data ['trouble'] = $trouble;
		$data ['contratti'] = $this->account->contratti ( $customer );
		$this->load->view ( 'common/accounts/dashboard', $data );
	}
	public function create_dashboard($customer, $type, $be, $trouble_dummy = null) {
		$data = array (
				'type' => $type,
				'description' => NULL,
				'deadline' => NULL,
				'status' => $trouble_dummy ? 'DRAFT' : 'NEW',
				'result' => NULL,
				'customer' => array (
						'id' => $customer 
				),
				'contract' => $be,
				'duty_company_crm' => NULL,
				'duty_user_crm' => NULL,
				'res_duty_company' => NULL,
				'res_duty_user' => NULL 
		);
		if ($id = $this->trouble->add ( $data )) {
			
			$processes = $this->trouble->fetch_process ( $type );
			
			if (count ( $processes ) > 0) {
				foreach ( $processes as $process ) {
					$this->actions->create_thread_tree ( $process->process_key, $customer, $be, $duty = NULL, $id, 'f', $process->request_key );
				}
			}
			
			redirect ( "/common/troubles/edit/$id" );
		}
	}
	public function savefollowup() {
		$this->load->library ( 'memos' );
		
		$data = array (
				'start_data' => $this->input->post ( 'day' ),
				'start_time' => $this->input->post ( 'time' ),
				'end_data' => NULL,
				'end_time' => NULL,
				'title' => 'Nuovo Followup',
				'description' => $this->input->post ( 'text' ) 
		);
		
		if ($this->input->post ( 'scheduled' ) == 'false') {
			unset ( $data ['start_data'] );
			unset ( $data ['start_time'] );
		}
		
		if ($data ['start_time'] == '')
			unset ( $data ['start_time'] );
		if ($data ['start_date'] == '')
			unset ( $data ['start_date'] );
		
		$res = $this->memos->add ( 'FOLLOWUP', NULL, "TROUBLE", $this->input->post ( 'trouble' ), $data );
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( array (
				'result' => $res 
		) ) );
	}

	public function save_dashboard_session($type = NULL) {
		if ($type != NULL) {
			$this->session->set_userdata ( "troubles_type_filter", $type );
		}
		
		redirect ( "/common/troubles" );
	}
	

	public function export_troubles() {
		
		$view_details = $this->trouble->export_trouble ();
		$field_array[] = array_keys($view_details[0]);
		$filename = "troubles-" . date ( 'd-m-Y-His' );
		$xls = new Excel_XML;
		$xls->addArray ($field_array);
		$xls->addArray ($view_details);
		$xls->generateXML ($filename);
		return false;
	}
	public function cancel_trouble($trouble_id) {
		if ($trouble_id) {
			$this->actions->cancel_trouble_tree ( $trouble_id );
			redirect ( "/common/troubles/edit/$trouble_id" );
		}
	}
}
