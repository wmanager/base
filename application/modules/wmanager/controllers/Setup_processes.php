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
class Setup_processes extends Admin_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'process' );
		$this->breadcrumb->append ( 'Admin', '/admin/' );
		$this->breadcrumb->append ( 'Processes', '/admin/setup_processes/' );
	}
	public function index() {
		$this->get ();
	}
	public function get() {
		$data = array ();
		$data ['processes'] = $this->process->get ();
		
		$data ['content'] = $this->load->view ( 'wmanager/process/setup_processes', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	/**
	 * Add a new process
	 */
	public function add() {
		$this->breadcrumb->append ( 'New Process', '/admin/setup_processes/add/' );
		if ($this->input->post ()) {
			if (! $this->input->post ( 'disabled' )) {
				$_POST ['disabled'] = 'f';
			} else {
				$_POST ['disabled'] = 't';
			}
			if (! $this->input->post ( 'role_can_create' )) {
				$_POST ['role_can_create'] = NULL;
			} else {
				$_POST ['role_can_create'] = implode ( ",", $_POST ['role_can_create'] );
			}
						
			if ($this->process->add ()) {
				redirect ( '/admin/setup_processes', 'refresh' );
			}
		}
		
		$macro = $this->process->get_macro_processes ();
		$macro_processes = array(0 => 'Select process');
		foreach ( $macro as $key => $value ) {
			$macro_processes [$value->id] = $value->mp;
		}
		$array_form_general = array (
				array(/* DROP DOWN  default processes*/
			        'id' => 'id_mp',
						'label' => 'Macro Processes',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $macro_processes 
				),
				array(/* Key */
						'id' => 'key',
						'label' => 'Key',
						'placeholder' => 'New Key' 
				),
				array(/* Title */
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Process Title',
						'class' => '' 
				),
				array(/* Description */
			        'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description' 
				),
				array(/* BPM */
					'id' => 'bpm_radio',
						'type' => 'combine', /* use `combine` to put several input inside the same block */
					'label' => 'Select BPM',
						'elements' => array (
								array(/* MANUAL */
								'id' => 'bpm',
										'type' => 'radio',
										'label' => 'MANUAL',
										'default_value' => 'MANUAL',
										'column' => 'col-sm-2 col-md-2 col-lg-2' 
								),
								array(/* AUTOMATIC */
								'id' => 'bpm',
										'type' => 'radio',
										'label' => 'AUTOMATIC',
										'default_value' => 'AUTOMATIC',
										'column' => 'col-sm-2 col-md-2 col-lg-2' 
								) 
						) 
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
				array(/* wiki */
						'id' => 'wiki_url',
						'label' => 'Wiki',
						'placeholder' => 'Enter wiki link',
						'class' => '' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'default_value' => 'f',
						'label' => 'Disabled' 
				)
		);
		$autorize_data = $this->process->get_authorized_role ();
		$form_types = $this->process->get_form_types ();
		$form_type = array(0 => 'Select Form');
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
				array(/* Label for roles */
						'type' => 'label',
						'label' => 'Authorized roles',
						'value' => '(These roles can create a new thread for this process)' 
				) 
		);
		foreach ( $autorize_data as $data_item ) {
			$authorized_role = array(/* Authorized role*/
					'id' => 'autorize_' . $data_item->id,
					'name' => 'role_can_create[]',
					'type' => 'checkbox',
					'class' => 'checkbox',
					'label' => $data_item->key,
					'value' => $data_item->key,
					'default_value' => $data_item->key 
			);
			array_push ( $array_form_form, $authorized_role );
		}
		$status_data ['status_data'] = array ();
		$data ['form_general'] = $this->form_builder->build_form_horizontal ( $array_form_general );
		$data ['form_form'] = $this->form_builder->build_form_horizontal ( $array_form_form );
		$data ['form_status'] = $this->load->view ( 'wmanager/process/status_list', $status_data, true );
		$data ['content'] = $this->load->view ( 'wmanager/process/add', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	/**
	 * Edit a process
	 */
	public function edit($id = NULL) {
		if ($id == NULL)
			redirect ( '/admin/setup_processes/', 'refresh' );
		
		if (! $process = $this->process->get_single ( $id ))
			redirect ( '/admin/setup_processes/', 'refresh' );
		$this->breadcrumb->append ( $process->title, '' );
		
		if ($this->input->post ()) {

			if (! $this->input->post ( 'disabled' )) {
				$_POST ['disabled'] = 'f';
			} else {
				$_POST ['disabled'] = 't';
			}
			if (! $this->input->post ( 'role_can_create' )) {
				$_POST ['role_can_create'] = NULL;
			} else {
				$_POST ['role_can_create'] = implode ( ",", $_POST ['role_can_create'] );
			}
			
			if ($this->process->edit ( $id )) {
				redirect ( '/admin/setup_processes', 'refresh' );
			}
		}
		
		$data ['process'] = $process;
		if (! $data ['process'])
			redirect ( '/admin/setup_processes/', 'refresh' );
		
		$macro = $this->process->get_macro_processes ();
		$macro_processes = array(0 => 'Select process');
		foreach ( $macro as $key => $value ) {
			$macro_processes [$value->id] = $value->mp;
		}
		$checked_bpm = $data ['process']->bpm;
		if ($checked_bpm == 'MANUAL') {
			$mchecked = true;
			$pchecked = false;
		} else if ($checked_bpm == 'AUTOMATIC') {
			$pchecked = true;
			$mchecked = false;
		}
		
		$array_form_general = array (
				array(/* DROP DOWN  default processes*/
						'id' => 'id_mp',
						'label' => 'Macro Processes',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $macro_processes 
				),
				array(/* Key */
						'id' => 'key',
						'label' => 'Key',
						'placeholder' => 'New Key' 
				),
				array(/* Title */
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Process Title',
						'class' => '' 
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description' 
				),
				array(/* BPM */
		    		'id' => 'bpm_radio',
						'type' => 'combine', /* use `combine` to put several input inside the same block */
			        'label' => 'Select BPM',
						'elements' => array (
								array(/* MANUAL */
				        'id' => 'bpm',
										'type' => 'radio',
										'label' => 'MANUAL',
										'default_value' => 'MANUAL',
										'column' => 'col-sm-2 col-md-2 col-lg-2',
										'checked' => $mchecked 
								),
								array(/* AUTOMATIC */
				        'id' => 'bpm',
										'type' => 'radio',
										'label' => 'AUTOMATIC',
										'default_value' => 'AUTOMATIC',
										'column' => 'col-sm-2 col-md-2 col-lg-2',
										'checked' => $pchecked 
								) 
						) 
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
				array(/* wiki */
						'id' => 'wiki_url',
						'label' => 'Wiki',
						'placeholder' => 'Enter wiki link',
						'class' => '' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'default_value' => 'f',
						'label' => 'Disabled' 
				)
		);
		$autorize_data = $this->process->get_authorized_role ();
		$form_types = $this->process->get_form_types ();
		$form_type = array(0 => 'Select Form');
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
				array(/* Label for roles */
						'type' => 'label',
						'label' => 'Authorized roles',
						'value' => '(These roles can create a new thread for this process)' 
				) 
		);
		$role_can_create = explode ( ",", $process->role_can_create );
		
		foreach ( $autorize_data as $data_item ) {
			$checked = false;
			if (in_array ( $data_item->key, $role_can_create )) {
				$checked = true;
			}
			$authorized_role = array(/* Authorized role*/
					'id' => 'autorize_' . $data_item->id,
					'name' => 'role_can_create[]',
					'type' => 'checkbox',
					'class' => 'checkbox',
					'label' => $data_item->key,
					'value' => $data_item->key,
					'default_value' => $data_item->key,
					'checked' => $checked 
			);
			array_push ( $array_form_form, $authorized_role );
		}
		$status_data ['status_data'] = $this->process->get_status ( $id, 1, $vid = NULL );

		$other_data ['other_data'] = $this->process->get_other_variables ( $id, $source = 'CUSTOM' );
		$other_data ['process_id'] = $status_data ['process_id'] = $id;
		$data ['process_id'] = $id;
		$data ['form_general'] = $this->form_builder->build_form_horizontal ( $array_form_general, $data ['process'] );
		$data ['form_form'] = $this->form_builder->build_form_horizontal ( $array_form_form, $data ['process'] );
		$data ['form_status'] = $this->load->view ( 'wmanager/process/status_list', $status_data, true );
		$data ['form_variable'] = $this->load->view ( 'wmanager/process/variable_list', $other_data, true );
		$data ['content'] = $this->load->view ( 'wmanager/process/edit', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function delete_status($pid = NULL, $id = NULL) {
		if ($this->process->delete_status ( $id ))
			redirect ( "/admin/setup_processes/edit/$pid" );
	}
	public function add_other_variable($pid = NULL) {
		$variable_type = array (
				'STANDARD' => 'STANDARD',
				'STATUS' => 'STATUS' 
		);
		$array_form_variable = array (
				array(/* DROP DOWN  default processes*/
						'id' => 'type',
						'label' => 'Type',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $variable_type 
				),
				array(/* Key */
						'id' => 'key',
						'label' => 'Key',
						'placeholder' => 'New Key' 
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
						'default_value' => 't',
						'label' => 'Disabled' 
				),
				array(/* Process ID */
						'id' => 'processid',
						'type' => 'hidden',
						'value' => $pid 
				) 
		);
		$status_data ['status_data'] ['other_variable'] = '1';
		$data ['form_status'] = $this->load->view ( 'wmanager/process/status_list', $status_data, true );
		$data ['form_variable'] = $this->form_builder->build_form_horizontal ( $array_form_variable );
		$this->load->view ( 'wmanager/process/other_variable', $data );
	}
	public function add_variable() {
		$vardata = $this->input->post ();
		$result ['status'] = $this->process->add_variable ( $vardata );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function edit_other_variable($pid = NULL, $vid = NULL) {
		$other_var = $this->process->get_single_variable ( $vid );
		
		$variable_type = array (
				'STANDARD' => 'STANDARD',
				'STATUS' => 'STATUS' 
		);
		$array_form_variable = array (
				array(/* DROP DOWN  default processes*/
						'id' => 'type',
						'label' => 'Type',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $variable_type 
				),
				array(/* Key */
						'id' => 'key',
						'label' => 'Key',
						'placeholder' => 'New Key' 
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
						'default_value' => 't',
						'label' => 'Disabled' 
				) 
		);
		$status_data ['status_data'] = $this->process->get_status ( $pid, 2, $vid );
		$status_data ['status_data'] ['other_variable'] = '1';
		$status_data ['process_id'] = $pid;
		$data ['form_status'] = $this->load->view ( 'wmanager/process/status_list', $status_data, true );
		$data ['variable_id'] = $vid;
		$data ['form_variable'] = $this->form_builder->build_form_horizontal ( $array_form_variable, $other_var );
		$this->load->view ( 'wmanager/process/other_variable', $data );
	}
	public function edit_variable() {
		$vardata = $this->input->post ();
		$result ['status'] = $this->process->edit_variable ( $vardata );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function delete_variable($pid = NULL, $id = NULL) {
		if ($this->process->delete_variable ( $id ))
			redirect ( "/admin/setup_processes/edit/$pid" );
	}
	public function delete($process_id) {
		if ($check = $this->process->check_associated_activiites ( $process_id )) {
			$this->process->delete_process ( $process_id );
		}
		redirect ( '/admin/setup_processes/', 'refresh' );
	}
}
