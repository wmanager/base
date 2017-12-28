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
class Setup_attach extends Admin_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'attach' );
		$this->breadcrumb->append ( 'Attach', '/admin/setup_attach/' );
	}
	public function index($image = NULL) {
		$this->get ();
	}
	public function get() {
		$data = array ();
		$data ['attach_type'] = $this->attach->get ( $this->config->item ( 'per_page' ), $this->uri->segment ( 5 ) );
		$config ['base_url'] = '/admin/setup_attach/get/page/';
		$config ['total_rows'] = $this->attach->total ();
		$this->pagination->initialize ( $config );
		$data ['content'] = $this->load->view ( 'admin/setup_attach/list', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function add() {
		$this->breadcrumb->append ( 'New Attachment Type', '/admin/setup_attach/add/' );
		if ($this->input->post ()) {
			if (! $this->input->post ( 'disabled' )) {
				$_POST ['disabled'] = 'f';
			} else {
				$_POST ['disabled'] = 't';
			}
			
			if ($this->attach->add ()) {
				redirect ( '/admin/setup_attach', 'refresh' );
			}
		}
		$ambits = $this->attach->get_ambit ();
		$ambita = array ('-'=>'Select Ambit');
		foreach ( $ambits as $ambit ) {
			$ambita [$ambit->id] = $ambit->title;
		}

		
		$array_form_attachment = array (
				array(/* Title */
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Attachment Type',
						'class' => '' 
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description' 
				),
				array(/* Title */
						'id' => 'ambit_id',
						'label' => 'Ambit',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $ambita 
				),
				array(/* Title */
						'id' => 'exts',
						'label' => 'Extension',
						'placeholder' => 'Comma separated values es. pdf,doc,jpg',
						'class' => '' 
				),
				array(/* Title */
						'id' => 'max_size',
						'label' => 'Max size',
						'placeholder' => 'Max size in KB',
						'class' => '' 
				),
				array(/* Title */
						'id' => 'attach_rename',
						'label' => 'Attachment Rename',
						'placeholder' => 'Effect happens when you attach to a mail',
						'class' => '' 
				),
				array(/* Active */
						'id' => 'disabled',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'default_value' => 'f',
						'label' => 'Disabled' 
				) 
		)
		;
		
		$data ['form_attachment'] = $this->form_builder->build_form_horizontal ( $array_form_attachment );
		$data ['content'] = $this->load->view ( 'admin/setup_attach/add', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function edit($id = NULL) {
		if ($id == NULL)
			redirect ( '/admin/setup_attach/', 'refresh' );
		
		if (! $attachment = $this->attach->get_single ( $id ))
			redirect ( '/admin/setup_attach/', 'refresh' );
		$this->breadcrumb->append ( $attachment->title, '' );
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'disabled' )) {
				$_POST ['disabled'] = 'f';
			} else {
				$_POST ['disabled'] = 't';
			}
			if ($this->attach->edit ( $id )) {
				redirect ( '/admin/setup_attach', 'refresh' );
			}
		}
		$ambits = $this->attach->get_ambit ();
		$ambita = array ('-'=>'Select Ambit');
		foreach ( $ambits as $ambit ) {
			$ambita [$ambit->id] = $ambit->title;
		}
		
		$data ['attachment'] = $attachment;
		if (! $data ['attachment'])
			redirect ( '/admin/setup_attach/', 'refresh' );
		
		$array_form_attachment = array (
				array(/* Title */
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Attachment Type',
						'class' => '' 
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description' 
				),
				array(/* Title */
						'id' => 'ambit_id',
						'label' => 'Ambit',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => $ambita 
				),
				array(/* Title */
						'id' => 'exts',
						'label' => 'Extension',
						'placeholder' => 'Attachment Type',
						'class' => '' 
				),
				array(/* Title */
						'id' => 'max_size',
						'label' => 'Max size',
						'placeholder' => 'Attachment Type',
						'class' => '' 
				),
				array(/* Title */
						'id' => 'attach_rename',
						'label' => 'Attachment Rename',
						'placeholder' => 'Effect happens when you attach to a mail',
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
		
		$data ['form_attachment'] = $this->form_builder->build_form_horizontal ( $array_form_attachment, $data ['attachment'] );
		$data ['content'] = $this->load->view ( 'admin/setup_attach/edit', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function delete($id = NULL) {
		if ($id == NULL)
			redirect ( '/admin/setup_attach/', 'refresh' );
		$this->attach->delete ( $id );
		redirect ( '/admin/setup_attach/', 'refresh' );
	}
}

