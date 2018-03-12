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
class Setup_form extends Admin_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'setup_forms' );
		$this->breadcrumb->append ( 'Form', '/admin/setup_form/' );
	}
	public function index() {
		$this->get ();
	}
	public function get() {
		$data = array ();
		$data ['form_data'] = $this->setup_forms->get ( $this->config->item ( 'per_page' ), $this->uri->segment ( 5 ) );
		$config ['base_url'] = '/admin/setup_form/get/page/';
		$config ['total_rows'] = $this->setup_forms->total ();
		$this->pagination->initialize ( $config );
		$data ['content'] = $this->load->view ( 'wmanager/setup_form/list', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function add() {
		$this->breadcrumb->append ( 'New Form', '/admin/setup_form/add/' );
		if ($this->input->post ()) {
			if (! $this->input->post ( 'disabled' )) {
				$_POST ['disabled'] = 'f';
			} else {
				$_POST ['disabled'] = 't';
			}
			if (! $this->input->post ( 'standard' )) {
				$_POST ['standard'] = 'f';
			} else {
				$_POST ['standard'] = 't';
			}
			if ($this->setup_forms->add ()) {
				redirect ( '/admin/setup_form', 'refresh' );
			}
		}
		$form_type = $this->setup_forms->get_form_type();
		$array_form_form = array (
				array(/* Type */
						'id' => 'type',
						'type' => 'dropdown',
						'label' => 'Form Type',
						'options' => $form_type 
				),
				array(/* Title */
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Form Title',
						'class' => '' 
				),
				array(/* Active */
						'id' => 'standard',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'default_value' => 'f',
						'label' => 'Standard' 
				),
				array(/* Description */
						'id' => 'url',
						'placeholder' => 'Form URL',
						'label' => 'URL' 
				),
				array(/* Description */
						'id' => 'model',
						'placeholder' => 'Enter related model name',
						'label' => 'Data Model' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'default_value' => 'f',
						'label' => 'Disabled' 
				),
				array(/* Active */
						'id' => 'sidebar',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'default_value' => 'f',
						'label' => 'Sidebar' 
				) 
		);
		
		$data ['form_form'] = $this->form_builder->build_form_horizontal ( $array_form_form );
		$data ['content'] = $this->load->view ( 'wmanager/setup_form/add', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function edit($id = NULL) {
		if ($id == NULL)
			redirect ( '/admin/setup_form/', 'refresh' );
		
		if (! $form = $this->setup_forms->get_single ( $id ))
			redirect ( '/admin/setup_form/', 'refresh' );
		$this->breadcrumb->append ( $form->title, '' );
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'disabled' )) {
				$_POST ['disabled'] = 'f';
			} else {
				$_POST ['disabled'] = 't';
			}
			if (! $this->input->post ( 'standard' )) {
				$_POST ['standard'] = 'f';
			} else {
				$_POST ['standard'] = 't';
			}
			if (! $this->input->post ( 'sidebar' )) {
				$_POST ['sidebar'] = 'f';
			} else {
				$_POST ['sidebar'] = 't';
			}
			if ($this->setup_forms->edit ( $id )) {
				redirect ( '/admin/setup_form', 'refresh' );
			}
		}
		
		$data ['form'] = $form;
		if (! $data ['form'])
			redirect ( '/admin/setup_form/', 'refresh' );
		$form_type = $this->setup_forms->get_form_type();
		$array_form_form = array (
				array(/* Type */
						'id' => 'type',
						'label' => 'Form Type',
						'type' => 'dropdown',
						'options' => $form_type 
				),
				array(/* Title */
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Form Title',
						'class' => '' 
				),
				array(/* Active */
						'id' => 'standard',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'default_value' => 'f',
						'label' => 'Standard' 
				),
				array(/* Description */
						'id' => 'url',
						'placeholder' => 'Form URL',
						'label' => 'URL' 
				),
				array(/* Description */
						'id' => 'model',
						'placeholder' => 'Enter related model name',
						'label' => 'Data Model' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'default_value' => 'f',
						'label' => 'Disabled' 
				),
				array(/* Active */
						'id' => 'sidebar',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'default_value' => 'f',
						'label' => 'Sidebar' 
				) 
		);
		
		$form_collections = $this->setup_forms->get_form_collections ( $id );
		$id_collections = array ();
		if (isset ( $form_collections->id_plico ))
			$id_collections = explode ( ',', $form_collections->id_plico );
		$collections = $this->setup_forms->get_collections ();
		$collections_checkbox = array ();
		foreach ( $collections as $collection ) {
			$check = false;
			if (in_array ( $collection->id, $id_collections )) {
				$check = true;
				;
			}
			$collections_checkbox [] = array (
					'id' => 'collection_' . $collection->id,
					'name' => 'collection[]',
					'type' => 'checkbox',
					'class' => 'checkbox',
					'label' => $collection->title,
					'value' => $collection->id,
					'default_value' => $collection->id,
					'column' => 'col-md-6',
					'checked' => $check 
			);
		}
		
		$array_form_collection = array (
				array(/* Attachments */
						'id' => 'bpm_radio',
						'type' => 'combine', /* use `combine` to put several input inside the same block */
						'label' => 'Select Collections',
						'elements' => $collections_checkbox 
				) 
		)
		;
		
		$attachment_data ['form_id'] = $id;
		$data ['unused_attachments'] = $this->setup_forms->get_unused_attachments ( $id );
		$data ['form_form'] = $this->form_builder->build_form_horizontal ( $array_form_form, $data ['form'] );
		$attachment_data ['attachment_data'] = $this->setup_forms->get_form_attachments ( $id );
		$data ['form_attachment'] = $this->load->view ( 'wmanager/setup_form/attachment_list', $attachment_data, true );
		$data ['form_collection'] = $this->form_builder->build_form_horizontal ( $array_form_collection, $data ['form'] );
		$data ['content'] = $this->load->view ( 'wmanager/setup_form/edit', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function delete($id = NULL) {
		if ($id == NULL)
			redirect ( '/admin/setup_form/', 'refresh' );
		$this->setup_forms->delete ( $id );
		redirect ( '/admin/setup_form/', 'refresh' );
	}
	public function get_unused_attachment($form_id = NULL) {
		$result = $this->setup_forms->get_unused_attachments ( $form_id );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function edit_attachment($form_id = NULL, $attach_id = NULL) {
		$attachment_types = $this->setup_forms->get_single_attachment ( $attach_id );
		if (isset ( $attachment_types->title )) {
			$hidden_required = 'f';
			$hidden_multi = 'f';
			if ($attachment_types->required == 't') {
				$hidden_required = 't';
			}
			if ($attachment_types->multi == 't') {
				$hidden_multi = 't';
			}
			$use_data = array (
					"UPLOAD" => "UPLOAD",
					"LIST" => "LIST" 
			);
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
					array(/* DROP DOWN  Use*/
						'id' => 'use',
							'label' => 'Use',
							'type' => 'dropdown',
							'class' => 'form-control',
							'options' => $use_data 
					),
					array(/* Required */
							'id' => 'multi',
							'type' => 'checkbox',
							'class' => 'process_checked',
							'label' => 'Multiple' 
					),
					array(/* Hidden required field */
							'id' => 'hidden_required',
							'type' => 'hidden',
							'value' => $hidden_required 
					),
					array(/* Hidden Multi field */
							'id' => 'hidden_multi',
							'type' => 'hidden',
							'value' => $hidden_multi 
					),
					array(/* Conditions */
						'id' => 'conditions',
							'type' => 'textarea',
							'class' => 'form-control',
							'label' => 'Conditions' 
					)
					// 'value' => $attachment_types->conditions,
					
					 
			);
			$data ['form_id'] = $form_id;
			$data ['attach_id'] = $attach_id;
			$data ['form_attachment'] = $this->form_builder->build_form_horizontal ( $array_form_attachment, $attachment_types );
		} else {
			$data ['error'] = true;
		}
		$this->load->view ( 'wmanager/setup_form/attachments', $data );
	}
	public function save_attachment() {
		$vardata = $this->input->post ();
		$result ['status'] = $this->setup_forms->edit_attachment ( $vardata );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	public function delete_attachments($form_id = NULL, $id = NULL) {
		if ($this->setup_forms->delete_attachment ( $id ))
			redirect ( "/admin/setup_form/edit/$form_id" );
	}
}

