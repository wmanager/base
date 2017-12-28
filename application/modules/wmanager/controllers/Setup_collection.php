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

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_collection extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('collection');
		$this->breadcrumb->append('Collection', '/admin/setup_collection/');
	}

	public function index($image=NULL)
	{
		$this->get();	
	}
	
	public function get()
	{
		$data = array();
		$data['collections'] = $this->collection->get($this->config->item('per_page'), $this->uri->segment(5));
		$config['base_url'] = '/admin/setup_collection/get/page/';
		$config['total_rows'] = $this->collection->total();
		$this->pagination->initialize($config);
		$data['content'] = $this->load->view('wmanager/setup_collection/list',$data,true);
		$this->load->view('wmanager/admin_template',$data);
	}
	
	public function add()
	{
		$this->breadcrumb->append('New Collection', '/admin/setup_collection/add/');
		if($this->input->post()){
			//print_r($this->input->post());exit;
			
			if($this->collection->add()){
				redirect('/admin/setup_collection','refresh');
			}
		}

		$attachments = $this->collection->get_attachments();
		$attach_checkbox = array();
		foreach ($attachments as $attach){
			$attach_checkbox[] = array(
					'id' => 'attach_'.$attach->id,
					'name' => 'attach[]',
					'type' => 'checkbox',
					'class' => 'checkbox',
					'label' => $attach->title,
					'value' => $attach->id,
					'default_value' => $attach->id,
					'column' => 'col-md-6',
			);
		}                       
                 
		$array_form_collection = array(
				array(/* Title */
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Collection name',
						'class' => ''
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description'
				),
				array(/* Attachments */
						'id' => 'bpm_radio',
						'type' => 'combine', /* use `combine` to put several input inside the same block */
						'label' => 'Select Attachments',
						'elements' => $attach_checkbox
				),
                                
		);
		
		
		
		
		$data['form_collection'] = $this->form_builder->build_form_horizontal($array_form_collection);
		$data['content'] = $this->load->view('wmanager/setup_collection/add',$data,true);
		$this->load->view('wmanager/admin_template',$data);
	}
	
	public function edit($id=NULL)
	{
		if($id==NULL) redirect('/admin/setup_collection/','refresh');
	
		if(!$collection = $this->collection->get_single($id)) redirect('/admin/setup_collection/','refresh');
		$this->breadcrumb->append($collection->title, '');
	
		if($this->input->post()){
			
			if($this->collection->edit($id)){
				redirect('/admin/setup_collection','refresh');
			}
		}
		$collection_attachments = $this->collection->get_collection_attachments($id);
		$attachments = $this->collection->get_attachments();
		$id_attachments = array();
		if(isset($collection_attachments->id_attachment))
			$id_attachments = explode(',',$collection_attachments->id_attachment);
		$attach_checkbox = array();
		foreach ($attachments as $attach){
			$check = false;
			if(in_array($attach->id,$id_attachments)){
				$check = true;;
			}
			$attach_checkbox[] = array(
					'id' => 'attach_'.$attach->id,
					'name' => 'attach[]',
					'type' => 'checkbox',
					'class' => 'checkbox',
					'label' => $attach->title,
					'value' => $attach->id,
					'default_value' => $attach->id,
					'column' => 'col-md-6',
					'checked'     => $check,
			);
		} 
	
		$data['collection'] = $collection;
		if(!$data['collection']) redirect('/admin/setup_collection/','refresh');
		
	
		$array_form_collection = array(
				array(/* Title */
						'id' => 'title',
						'label' => 'Title',
						'placeholder' => 'Collection name',
						'class' => ''
				),
				array(/* Description */
						'id' => 'description',
						'type' => 'textarea',
						'label' => 'Description'
				),
				array(/* Attachments */
						'id' => 'bpm_radio',
						'type' => 'combine', /* use `combine` to put several input inside the same block */
						'label' => 'Select Attachments',
						'elements' => $attach_checkbox
				),
                                
		);
		
		$data['form_collection'] = $this->form_builder->build_form_horizontal($array_form_collection,$data['collection']);
		$data['content'] = $this->load->view('wmanager/setup_collection/edit',$data,true);
		$this->load->view('wmanager/admin_template',$data);
	}
	
	public function delete($id=NULL)
	{
		if($id == NULL) redirect('/admin/setup_collection/','refresh');
		$this->collection->delete($id);
		redirect('/admin/setup_collection/','refresh');
	}

}

