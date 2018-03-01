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
class Setup_activities extends Admin_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'setup_activity' );
		$this->breadcrumb->append ( 'Admin', '/admin/' );
		$this->breadcrumb->append ( 'Process', '/admin/setup_processes/' );
		$this->breadcrumb->append ( 'Activities', '/admin/setup_activities/' . $this->uri->segment ( 4 ) );
	}
	public function index($process_id = NULL) {
		if ($process_id == NULL)
			redirect ( '/admin/setup_processes' );
		
		$this->get( $this->uri->segment ( 3) );
	}
	public function get($process_id) {
		$data = array ();
		$data ['process_id'] = $process_id;
		$data ['process_title'] = $this->setup_activity->get_process_title ( $process_id );
		$data ['activities'] = $this->setup_activity->get ( $process_id, 'ALL', $this->uri->segment ( 6 ) );
		
		// $config['base_url'] = '/admin/setup_activities/get/'.$process_id.'/page/';
		// $config['total_rows'] = $this->setup_activity->total($process_id);
		
		// $this->pagination->initialize($config);
		$data ['content'] = $this->load->view ( 'wmanager/activities/setup_activities', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function add($pid = NULL) {
		if ($pid == NULL)
			redirect ( '/admin/setup_processes' );
		$this->breadcrumb->append ( 'Add Activity', '/admin/setup_activities/add/' . $pid );
		if ($this->input->post ()) {
			
			if (! $this->input->post ( 'disabled' )) {
				$_POST ['disabled'] = 'f';
			} else {
				$_POST ['disabled'] = 't';
			}
			if (! $this->input->post ( 'be_required' )) {
				$_POST ['be_required'] = 'f';
			} else {
				$_POST ['be_required'] = 't';
			}
			if (! $this->input->post ( 'is_request' )) {
				$_POST ['is_request'] = 'f';
			} else {
				$_POST ['is_request'] = 't';
			}
			
			
			if ($this->setup_activity->add ()) {
				redirect ( '/admin/setup_activities/get/' . $pid, 'refresh' );
			}
		}
		
		$activity_type = array (
				'STANDARD' => 'STANDARD'
		);
		
		$duty_company_list = $this->setup_activity->get_duty_company ();
		

		
		$array_form_general = array (
				array(/* DROP DOWN  default processes*/
						'id' => 'type',
						'label' => 'Type',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $activity_type 
				),
				array(/* Key */
						'id' => 'key',
						'label' => 'Key',
						'required' => true,
						'placeholder' => 'New Key' 
				),
				array(/* Title */
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Activity Title',
						'required' => true,
						'class' => '' 
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description' 
				),
				array(/* Weight */
						'id' => 'weight',
						'label' => 'Default Weight',
						'placeholder' => 'Default Weight' 
				),
				array(/* SLA*/
						'id' => 'sla',
						'label' => 'Default SLA (hrs)',
						'placeholder' => 'Default SLA' 
				),
				array(/* DROP DOWN default duty company*/
						'id' => 'duty_company',
						'label' => 'Duty company',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $duty_company_list 
				),
				array(/* Help */
						'id' => 'help',
						'type' => 'textarea',
						'label' => 'Help' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Disabled',
						'default_value' => 'f' 
				),
				array(/* Active */
						'id' => 'be_required',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'BE required',
						'default_value' => 'f' 
				),
				array(/* Active */
						'id' => 'is_request',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Is a request?',
						'default_value' => 'f' 
				),
				array(/* process id */
						'id' => 'id_process',
						'type' => 'hidden',
						'value' => $pid 
				)
		)
		;
		$autorize_data = $this->setup_activity->get_authorized_role ();
		$autorize_role = array ();
		$authorized_role [''] = 'Select Role';
		foreach ( $autorize_data as $data_item ) {
			$authorized_role [$data_item->key] = $data_item->key;
		}
		$form_types = $this->setup_activity->get_form_types ();
		$form_type [''] = 'Select form type';
		foreach ( $form_types as $key => $value ) {
			$form_type [$value->id] = $value->title;
		}
		$array_form_form = array (
				array(/* DROP DOWN  Form Type*/
			        'id' => 'form_id',
						'label' => 'Form Type',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $form_type 
				),
				array(/* DROP DOWN  Roles*/
						'id' => 'role',
						'label' => 'Role',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $authorized_role 
				) 
		);
		
		$status_data ['status_data'] = array ();
		$other_data ['other_data'] = array ();
		$status_data ['id_process'] = $other_data ['id_process'] = $data ['id_process'] = $pid;
		$data ['form_general'] = $this->form_builder->build_form_horizontal ( $array_form_general );
		$data ['form_form'] = $this->form_builder->build_form_horizontal ( $array_form_form );
		$data ['form_status'] = $this->load->view ( 'wmanager/activities/status_list', $status_data, true );
		$data ['form_other'] = $this->load->view ( 'wmanager/activities/variable_list', $other_data, true );
		$data ['content'] = $this->load->view ( 'wmanager/activities/add', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	/**
	 * Edit a Activity
	 */
	public function edit($pid = NULL, $id = NULL) {

		if ($pid == NULL) {
			redirect ( '/admin/setup_processes' );
		} else if ($id == NULL)
			redirect ( '/admin/setup_activities/' . $pid, 'refresh' );
		
		if (! $activity = $this->setup_activity->get_single ( $id ))
			redirect ( '/admin/setup_activities/', 'refresh' );
		if ($activity->title == '') {
			$this->breadcrumb->append ( 'Title', '' );
		} else {
			$this->breadcrumb->append ( $activity->title, '' );
		}
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'disabled' )) {
				$_POST ['disabled'] = 'f';
			} else {
				$_POST ['disabled'] = 't';
			}
			if (! $this->input->post ( 'be_required' )) {
				$_POST ['be_required'] = 'f';
			} else {
				$_POST ['be_required'] = 't';
			}
			if (! $this->input->post ( 'is_request' )) {
				$_POST ['is_request'] = 'f';
			} else {
				$_POST ['is_request'] = 't';
			}
			
			
			if ($this->setup_activity->edit ( $id )) {
				redirect ( '/admin/setup_activities/edit/' . $pid . '/' . $id, 'refresh' );
			}
		}
		
		$data ['activity'] = $activity;
		if (! $data ['activity'])
			redirect ( '/admin/setup_activities/' . $pid, 'refresh' );
		$activity_type = array (
				'STANDARD' => 'STANDARD'
		);
		
		$duty_company_list = $this->setup_activity->get_duty_company ();
		
		
		$array_form_general = array (
				array(/* DROP DOWN  default processes*/
						'id' => 'type',
						'label' => 'Type',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $activity_type 
				),
				array(/* Key */
						'id' => 'key',
						'label' => 'Key',
						'required' => true,
						'placeholder' => 'New Key' 
				),
				array(/* Title */
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Activity Title',
						'required' => true,
						'class' => '' 
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description' 
				),
				array(/* Weight */
						'id' => 'weight',
						'label' => 'Default Weight',
						'placeholder' => 'Default Weight' 
				),
				array(/* SLA*/
						'id' => 'sla',
						'label' => 'Default SLA (hrs)',
						'placeholder' => 'Default SLA' 
				)
				// 'column' => 'col-md-12'
				,
				array(/* DROP DOWN default duty company*/
						'id' => 'duty_company',
						'label' => 'Duty company',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $duty_company_list 
				),
				array(/* Help */
						'id' => 'help',
						'type' => 'textarea',
						'label' => 'Help' 
				),
				array(/* Active */
						'id' => 'be_required',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'BE required',
						'default_value' => 'f' 
				),
				array(/* Active */
						'id' => 'is_request',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Is a request?',
						'default_value' => 'f' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Disabled',
						'default_value' => 'f' 
				)
		);
		$autorize_data = $this->setup_activity->get_authorized_role ();
		$autorize_role = array ();
		$authorized_role [''] = 'Select Role';
		foreach ( $autorize_data as $data_item ) {
			$authorized_role [$data_item->key] = $data_item->key;
		}
		$form_types = $this->setup_activity->get_form_types ();
		$form_type [''] = 'Select form type';
		foreach ( $form_types as $key => $value ) {
			$form_type [$value->id] = $value->title;
		}
		$array_form_form = array (
				array(/* DROP DOWN  Form Type*/
			        'id' => 'form_id',
						'label' => 'Form Type',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $form_type 
				),
				array(/* DROP DOWN  Roles*/
						'id' => 'role',
						'label' => 'Role',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $authorized_role 
				) 
		);
		
		$data ['unused_attachments'] = $this->setup_activity->get_unused_attachments ( $id );
		$status_data ['status_data'] = $this->setup_activity->get_status ( $id, 1, $vid = NULL );
		$other_data ['other_data'] = $this->setup_activity->get_other_variables ( $id, $source = 'CUSTOM' );
		$attachment_data ['attachment_data'] = $this->setup_activity->get_activity_attachments ( $id );
		$scenario_data ['scenario_data'] = $this->setup_activity->get_activity_exit_scenarios ( $id );
		$other_data ['activity_id'] = $scenario_data ['activity_id'] = $status_data ['activity_id'] = $id;
		$attachment_data ['activity_id'] = $data ['activity_id'] = $id;
		$other_data ['id_process'] = $data ['id_process'] = $status_data ['id_process'] = $other_data ['id_process'] = $pid;
		$attachment_data ['id_process'] = $scenario_data ['id_process'] = $pid;
		$data ['form_general'] = $this->form_builder->build_form_horizontal ( $array_form_general, $data ['activity'] );
		$data ['form_form'] = $this->form_builder->build_form_horizontal ( $array_form_form, $data ['activity'] );
		$data ['form_status'] = $this->load->view ( 'wmanager/activities/status_list', $status_data, true );
		$data ['form_other'] = $this->load->view ( 'wmanager/activities/variable_list', $other_data, true );
		$data ['form_attachment'] = $this->load->view ( 'wmanager/activities/attachment_list', $attachment_data, true );
		$data ['form_exit_scenarios'] = $this->load->view ( 'wmanager/activities/exit_scenarios', $scenario_data, true );
		$data ['content'] = $this->load->view ( 'wmanager/activities/edit', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function delete_status($pid = NULL, $aid = NULL, $id = NULL) {
		if ($this->setup_activity->delete_status ( $id ))
			redirect ( "/admin/setup_activities/edit/$pid/$aid" );
	}
	public function add_other_variable($pid = NULL, $aid = NULL) {
		$variable_type = array (
				'MAGIC_FORM' => 'MAGIC FORM',
				'STANDARD' => 'STANDARD',
				'STATUS' => 'STATUS' 
		);
		$layout_type = array (
				'text' => 'Text',
				'dropdown' => 'Dropdown',
				'date' => 'Date' 
		);
		$array_form_variable = array (
				array(/* DROP DOWN  default processes*/
						'id' => 'type',
						'label' => 'Type',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $variable_type 
				),
				array(/* Label */
						'id' => 'var_label',
						'label' => 'Label',
						'placeholder' => 'New Label' 
				),
				array(/* Key */
						'id' => 'key',
						'label' => 'Key',
						'placeholder' => 'New Key' 
				),
				array(/* Layout */
						'id' => 'layout',
						'label' => 'Layout',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $layout_type 
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Disabled',
						'default_value' => 'f'
				),
				array(/* Hidden activity id */
						'id' => 'id_activity',
						'type' => 'hidden',
						'value' => $aid 
				),
				array(/* Hidden process id */
						'id' => 'id_process',
						'type' => 'hidden',
						'value' => $pid 
				) 
		);
		$status_data ['status_data'] ['other_variable'] = 1;
		$status_data ['id_process'] = $pid;
		$data ['form_status'] = $this->load->view ( 'wmanager/activities/status_list', $status_data, true );
		$data ['form_variable'] = $this->form_builder->build_form_horizontal ( $array_form_variable );
		$this->load->view ( 'wmanager/activities/other_variable', $data );
	}
	public function add_variable() {
		$vardata = $this->input->post ();
		$result ['status'] = $this->setup_activity->add_variable ( $vardata );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function edit_other_variable($pid = NULL, $aid = NULL, $vid = NULL) {
		$other_var = $this->setup_activity->get_single_variable ( $vid );
		
		$variable_type = array (
				'MAGIC_FORM' => 'MAGIC FORM',
				'STANDARD' => 'STANDARD',
				'STATUS' => 'STATUS' 
		);
		
		$layout_type = array (
				'text' => 'Text',
				'dropdown' => 'Dropdown',
				'date' => 'Date' 
		);
		$array_form_variable = array (
				array(/* DROP DOWN  default processes*/
						'id' => 'type',
						'label' => 'Type',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $variable_type 
				),
				array(/* Label */
						'id' => 'var_label',
						'label' => 'Label',
						'placeholder' => 'New Label' 
				),
				array(/* Key */
						'id' => 'key',
						'label' => 'Key',
						'placeholder' => 'New Key' 
				),
				array(/* Layout */
						'id' => 'layout',
						'label' => 'Layout',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $layout_type 
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'default_value' => 'f',
						'label' => 'Disabled' 
				) 
		);
		$status_data ['status_data'] = $this->setup_activity->get_status ( $aid, 2, $vid );
		$status_data ['status_data'] ['other_variable'] = '1';
		$status_data ['activity_id'] = $aid;
		$status_data ['id_process'] = $pid;
		$data ['form_status'] = $this->load->view ( 'wmanager/activities/status_list', $status_data, true );
		$data ['variable_id'] = $vid;
		$data ['form_variable'] = $this->form_builder->build_form_horizontal ( $array_form_variable, $other_var );
		$this->load->view ( 'wmanager/activities/other_variable', $data );
	}
	public function edit_variable() {
		$vardata = $this->input->post ();
		$result ['status'] = $this->setup_activity->edit_variable ( $vardata );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function delete_variable($pid = NULL, $aid = NULL, $id = NULL) {
		if ($this->setup_activity->delete_variable ( $id ))
			redirect ( "/admin/setup_activities/edit/$pid/$aid" );
	}
	public function add_attachment() {
		$attachment_types = $this->setup_activity->get_attachments ();
		$attachment = array ();
		foreach ( $attachment_types as $attachment_type ) {
			$attachment [$attachment_type->id] = $attachment_type->title;
		}
		$array_form_attachment = array (
				array(/* DROP DOWN  default processes*/
						'id' => 'id_attachment',
						'label' => 'Type',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $attachment 
				),
				array(/* Required */
						'id' => 'required',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Required' 
				) 
		);
		$data ['form_attachment'] = $this->form_builder->build_form_horizontal ( $array_form_attachment );
		$this->load->view ( 'wmanager/activities/attachments', $data );
	}
	public function get_unused_attachment($id_activity = NULL) {
		$result = $this->setup_activity->get_unused_attachments ( $id_activity );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function delete_attachments($pid = NULL, $aid = NULL, $id = NULL) {
		if ($this->setup_activity->delete_attachment ( $id ))
			redirect ( "/admin/setup_activities/edit/$pid/$aid" );
	}
	public function edit_attachment($pid = NULL, $activity_id = NULL, $attach_id = NULL) {
		$attachment_types = $this->setup_activity->get_single_attachment ( $attach_id );
		if (isset ( $attachment_types->title )) {
			$hidden_required = 'f';
			if ($attachment_types->required == 't') {
				$hidden_required = 't';
			}
			$array_form_attachment = array (
					array(/* Label for roles */
							'type' => 'label',
							'label' => 'Type',
							'value' => $attachment_types->title 
					),
					array(/* Required */
							'id' => 'required',
							'type' => 'checkbox',
							'class' => 'process_checked',
							'label' => 'Required' 
					),
					array(/* Hidden required field */
							'id' => 'hidden_required',
							'type' => 'hidden',
							'value' => $hidden_required 
					) 
			);
			$data ['activity_id'] = $activity_id;
			$data ['attach_id'] = $attach_id;
			$data ['form_attachment'] = $this->form_builder->build_form_horizontal ( $array_form_attachment, $attachment_types );
		} else {
			$data ['error'] = true;
		}
		$this->load->view ( 'wmanager/activities/attachments', $data );
	}
	public function save_attachment() {
		$vardata = $this->input->post ();
		$result ['status'] = $this->setup_activity->edit_attachment ( $vardata );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function add_exit_scenario($aid = NULL) {
		$array_exit_scenario = array (
				array(/* Title*/
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Title' 
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description' 
				),
				array(/* Condition */
						'id' => 'condition',
						'label' => 'Condition',
						'type' => 'textarea',
						'placeholder' => 'Provide the Condition',
						'help' => 'Sample Condition : $ACTID.STATUS=DONE; $ACTID.RESULT=OK;' 
				),
				array(/* Action */
						'id' => 'actions',
						'label' => 'Action',
						'type' => 'textarea',
						'placeholder' => 'Provide the Action',
						'help' => 'Sample Action : $NEXTID=Create_Activity(BO_MODULO_VERIFICA;STATUS=DONE;); $RES=Update_Var(ACTIVITY;$NEXTID;STATUS=NEW;);' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Disabled',
						'default_value' => 'f'
				),
				array(/* Hidden activity id */
						'id' => 'id_activity',
						'type' => 'hidden',
						'value' => $aid 
				) 
		);
		$data ['form_scenario'] = $this->form_builder->build_form_horizontal ( $array_exit_scenario );
		$this->load->view ( 'wmanager/activities/new_scenario', $data );
	}
	public function add_scenario() {
		$scenario_data = $this->input->post ();
		$result ['status'] = $this->setup_activity->add_scenario ( $scenario_data );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function edit_scenario($pid = NULL, $aid = NULL, $vid = NULL) {
		$exit_scenario = $this->setup_activity->get_scenario ( $vid );
		
		$array_exit_scenario = array (
				array(/* Title*/
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Title' 
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description' 
				),
				array(/* Condition */
						'id' => 'condition',
						'label' => 'Condition',
						'type' => 'textarea',
						'placeholder' => 'Provide the Condition',
						'help' => 'Sample Condition : $ACTID.STATUS=DONE; $ACTID.RESULT=OK;
						' 
				),
				array(/* Action */
						'id' => 'actions',
						'label' => 'Action',
						'type' => 'textarea',
						'placeholder' => 'Provide the Action',
						'help' => 'Sample Action : $NEXTID=Create_Activity(BO_MODULO_VERIFICA;STATUS=DONE;); $RES=Update_Var(ACTIVITY;$NEXTID;STATUS=NEW;);' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Disabled',
						'default_value' => 'f'
				),
				array(/* Hidden activity id */
						'id' => 'id_activity',
						'type' => 'hidden',
						'value' => $aid 
				) 
		);
		$data ['scenario_id'] = $vid;
		$data ['form_scenario'] = $this->form_builder->build_form_horizontal ( $array_exit_scenario, $exit_scenario );
		$this->load->view ( 'wmanager/activities/new_scenario', $data );
	}
	public function edit_scenario_value() {
		$scenario_data = $this->input->post ();
		$result ['status'] = $this->setup_activity->edit_scenario ( $scenario_data );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function delete_scenario($pid = NULL, $aid = NULL, $sid = NULL) {
		if ($this->setup_activity->delete_scenario ( $sid ))
			redirect ( "/admin/setup_activities/edit/$pid/$aid" );
	}
	public function update_ordering() {
		$ordering = $this->input->post ();
		foreach ( $ordering ['data'] as $key => $value ) {
			$result ['status'] = $this->setup_activity->update_ordering ( $value, $key );
		}
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function delete($pid, $act_id) {
		if ($check = $this->setup_activity->check_associated_activiites ( $act_id )) {
			$this->setup_activity->delete_activity ( $pid, $act_id );
		}
		redirect ( '/admin/setup_activities/' . $pid, 'refresh' );
	}
	
	
	
	/**
	 * add_transition
	 *
	 * @return view
	 *
	 * @author adharsh
	 */
	public function add_transition($process_id = NULL, $act_id = NULL, $status = NULL) {
		$status_data ['status_data'] = $this->setup_activity->get_status ( $act_id, 1, $vid = NULL );
		$get_transition = $this->setup_activity->get_all_trasitions ( $act_id, $process_id, $status );
		if (count ( $status_data ['status_data'] ) > 0) {
			foreach ( $status_data ['status_data'] as $key => $item ) {
				if (in_array ( $item->key, $get_transition )) {
					$status_data ["status_data"] [$key]->transition = true;
				} else {
					$status_data ["status_data"] [$key]->transition = false;
				}
			}
		}
		$status_data ["activity_type_id"] = $act_id;
		$status_data ["process_id"] = $process_id;
		$status_data ["status"] = $status;
		$this->load->view ( 'wmanager/activities/transition', $status_data );
	}
	
	/**
	 * save_transition
	 *
	 * @return bool
	 *
	 * @author adharsh
	 */
	public function save_transition() {
		$data = $_POST;
		
		$insert = $this->setup_activity->insert_transition ( $data );
		
		echo $insert;
	}
}

