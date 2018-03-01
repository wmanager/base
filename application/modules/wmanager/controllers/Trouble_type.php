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
class Trouble_type extends Common_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'trouble_types' );
		$this->breadcrumb->append ( 'Trouble Types', '/admin/trouble_type/' );
	}
	
	/**
	 * index
	 * To list all troubles
	 *
	 * @author Shemil modified by Sumesh
	 */
	public function index() {
		// session message
		$flash_data = $this->session->flashdata ( "pod_imo_insert_msg" );
		if (count ( $flash_data ) > 0) {
			$data ['message'] = $flash_data ['msg'];
			$data ['class'] = $flash_data ['class'];
		}
		
		// data fetch
		$data ['trouble_type_list'] = $this->trouble_types->get_trouble_types ( $this->config->item ( 'per_page' ), $this->uri->segment ( 5 ) );
		
		if ($this->uri->segment ( 5 )) {
			$data ['page_number'] = $this->uri->segment ( 5 );
		} else {
			$data ['page_number'] = 0;
		}
		
		// pagination
		$config ['base_url'] = '/admin/trouble_type/index/page/';
		$config ['total_rows'] = $this->trouble_types->total ();
		$data ['total_rows'] = $config ['total_rows'];
		
		$this->pagination->initialize ( $config );
		
		// wmanager/admin_template
		$data ['content'] = $this->load->view ( 'wmanager/troubles/trouble_types', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	/**
	 * check_unique
	 *
	 * To check the uniqueness of trouble type key
	 *
	 * @author Sumesh
	 */
	function check_unique() {
		$key = $this->input->post ( 'key' );
		$id = $this->input->post ( 'id' );
		$result = $this->trouble_types->check_unique ( $key, $id );
		echo json_encode ( $result );
	}
	
	/**
	 * add
	 * To load the new troubles type form and save the same
	 *
	 * @author Sumesh
	 */
	public function add() {
		$this->breadcrumb->append ( 'Add Trouble Type', '/admin/trouble_type/add/' );
		
		// if post is set then save
		if ($this->input->post ()) {
			$post = $this->input->post ();
			$result = $this->trouble_types->add_trouble_type ( $post );
			
			if ($result == true) {
				$this->session->set_flashdata ( "growl_success", 'Trouble type successfully added' );
				redirect ( "/admin/trouble_type" );
			} else {
				$this->session->set_flashdata ( "growl_error", 'Something went wrong' );
			}
		}
		$data ['title'] = 'Wmanager | Add Trouble Type';
		$data ['content'] = $this->load->view ( 'wmanager/troubles/add_trouble_type', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	/**
	 * edit
	 * To load the new troubles type form and save the same
	 *
	 * @author Sumesh
	 */
	public function edit($id = null, $page_number = null) {
		$this->breadcrumb->append ( 'Edit Trouble Type', '/admin/trouble_type/edit/' );
		if (preg_match ( '/^\d+$/', $id ) && preg_match ( '/^\d+$/', $page_number )) {
			$result = $this->trouble_types->get_single_trouble_type ( $id );
			if ($result != false) {
				$data ['trouble_type'] = $result;
				$data ['page_number'] = $page_number;
				
				$data ['title'] = 'Wmanager | Edit Trouble Type';
				
				$related_process_data ['related_process_list'] = $this->trouble_types->get_all_related_process ( $id );
				$related_process_data ['related_process_setup_processes'] = $this->trouble_types->get_all_setup_processes ();
				$related_process_data ['page_number'] = $page_number;
				$related_process_data ['edit_id'] = $id;
				$data ['form_related_process'] = $this->load->view ( 'wmanager/troubles/related_process_list', $related_process_data, true );
				
				$troubles_subtypes_data ['troubles_subtypes_list'] = $this->trouble_types->get_all_troubles_subtypes ( $id );
				$troubles_subtypes_data ['page_number'] = $page_number;
				$troubles_subtypes_data ['edit_id'] = $id;
				$data ['form_troubles_subtypes'] = $this->load->view ( 'wmanager/troubles/troubles_subtypes_list', $troubles_subtypes_data, true );
				
				$data ['content'] = $this->load->view ( 'wmanager/troubles/edit_trouble_type', $data, true );
				
				$this->load->view ( 'wmanager/admin_template', $data );
			}
		}
	}
	
	/**
	 * update_quotation_order
	 *
	 * @author Sumesh
	 */
	public function update_trouble_type() {
		if ($this->input->post ()) {
			$post = $this->input->post ();
			
			$page_number = $post ['page_number'];
			
			$result = $this->trouble_types->update_trouble_type ( $post );
			
			if ($result == true) {
				if ($page_number > 0) {
					$this->session->set_flashdata ( "growl_success", 'Trouble type successfully updated' );
					redirect ( "/admin/trouble_type/index/page/" . $page_number );
				} else {
					$this->session->set_flashdata ( "growl_success", 'Trouble type successfully updated' );
					redirect ( "/admin/trouble_type" );
				}
			} else {
				$this->session->set_flashdata ( "growl_error", 'Something went wrong' );
				if ($page_number > 0) {
					redirect ( "/admin/trouble_type/index/page/" . $page_number );
				} else {
					redirect ( "/admin/trouble_type" );
				}
			}
		} else {
			$this->session->set_flashdata ( "growl_error", 'Something went wrong' );
			redirect ( "/admin/trouble_type" );
		}
	}
	
	/**
	 * update_relared_process
	 *
	 * @author Sumesh
	 */
	public function update_relared_process() {
		if ($this->input->post ()) {
			$post = $this->input->post ();
			
			$page_number = $post ['page_number'];
			
			$result = $this->trouble_types->update_relared_process ( $post );
			
			if ($result == 'saved_inserted') {
				if ($page_number > 0) {
					$this->session->set_flashdata ( "growl_success", 'Related process successfully added / updated' );
					redirect ( "/admin/trouble_type/index/page/" . $page_number );
				} else {
					$this->session->set_flashdata ( "growl_success", 'Related process successfully added / updated' );
					redirect ( "/admin/trouble_type" );
				}
			} else if ($result == 'saved') {
				if ($page_number > 0) {
					$this->session->set_flashdata ( "growl_success", 'Related process successfully updated' );
					redirect ( "/admin/trouble_type/index/page/" . $page_number );
				} else {
					$this->session->set_flashdata ( "growl_success", 'Related process successfully updated' );
					redirect ( "/admin/trouble_type" );
				}
			} else if ($result == 'inserted') {
				if ($page_number > 0) {
					$this->session->set_flashdata ( "growl_success", 'Related process successfully added' );
					redirect ( "/admin/trouble_type/index/page/" . $page_number );
				} else {
					$this->session->set_flashdata ( "growl_success", 'Related process successfully added' );
					redirect ( "/admin/trouble_type" );
				}
			} else {
				if ($page_number > 0) {
					$this->session->set_flashdata ( "growl_success", 'Related process successfully added / updated' );
					redirect ( "/admin/trouble_type/index/page/" . $page_number );
				} else {
					$this->session->set_flashdata ( "growl_success", 'Related process successfully added / updated' );
					redirect ( "/admin/trouble_type" );
				}
			}
		} else {
			$this->session->set_flashdata ( "growl_error", 'Something went wrong' );
			redirect ( "/admin/trouble_type" );
		}
	}
	
	/**
	 * delete
	 *
	 * To delete trouble based on the id specified
	 *
	 * @param integer $id        	
	 * @param integer $page_number        	
	 *
	 * @author Sumesh
	 */
	public function delete($id = null, $page_number = null) {
		if (preg_match ( '/^\d+$/', $id ) && preg_match ( '/^\d+$/', $page_number )) {
			$result = $this->trouble_types->delete_trouble_type ( $id );
			
			if ($result == true) {
				$this->session->set_flashdata ( 'growl_success', 'Trouble type Sucessfully deleted' );
			} else {
				$this->session->set_flashdata ( 'growl_error', ' Something went wrong' );
			}
		} else {
			$this->session->set_flashdata ( 'growl_error', ' Something went wrong' );
		}
		
		if ($page_number > 0) {
			redirect ( "/admin/trouble_type/index/page/" . $page_number );
		} else {
			redirect ( "/admin/trouble_type" );
		}
	}
	
	/**
	 * get_setup_activites
	 *
	 * To get all setup activites based on the process_id specified
	 *
	 * @param integer $process_id        	
	 *
	 * @author Sumesh
	 */
	public function get_setup_activites() {
		$process_id = $this->input->post ( 'process_id' );
		$result = $this->trouble_types->get_setup_activites ( $process_id );
		
		echo json_encode ( $result );
	}
	
	/**
	 * delete_relared_process
	 *
	 * To delete the related process based on the process_id specified
	 *
	 * @param integer $id        	
	 * @param integer $edit_id        	
	 * @param integer $page_number        	
	 *
	 * @author Sumesh
	 */
	public function delete_relared_process($id = null, $edit_id = null, $page_number = null) {
		$result = $this->trouble_types->delete_relared_process ( $id );
		
		if ($result == true) {
			$this->session->set_flashdata ( "growl_success", 'Related process successfully deleted' );
		} else {
			$this->session->set_flashdata ( "growl_error", 'Something went wrong' );
		}
		
		redirect ( "/admin/trouble_type/edit/" . $edit_id . "/" . $page_number );
	}
	
	/**
	 * update_troubles_subtypes
	 *
	 * @author Sumesh
	 */
	public function update_troubles_subtypes() {
		if ($this->input->post ()) {
			$post = $this->input->post ();
			
			$page_number = $post ['page_number'];
			
			$result = $this->trouble_types->update_troubles_subtypes ( $post );
			
			if ($result == 'saved_inserted') {
				if ($page_number > 0) {
					$this->session->set_flashdata ( "growl_success", 'Troubles subtype successfully added / updated' );
					redirect ( "/admin/trouble_type/index/page/" . $page_number );
				} else {
					$this->session->set_flashdata ( "growl_success", 'Troubles subtype successfully added / updated' );
					redirect ( "/admin/trouble_type" );
				}
			} else if ($result == 'saved') {
				if ($page_number > 0) {
					$this->session->set_flashdata ( "growl_success", 'Troubles subtype successfully updated' );
					redirect ( "/admin/trouble_type/index/page/" . $page_number );
				} else {
					$this->session->set_flashdata ( "growl_success", 'Troubles subtype successfully updated' );
					redirect ( "/admin/trouble_type" );
				}
			} else if ($result == 'inserted') {
				if ($page_number > 0) {
					$this->session->set_flashdata ( "growl_success", 'Troubles subtype successfully added' );
					redirect ( "/admin/trouble_type/index/page/" . $page_number );
				} else {
					$this->session->set_flashdata ( "growl_success", 'Troubles subtype successfully added' );
					redirect ( "/admin/trouble_type" );
				}
			} else {
				if ($page_number > 0) {
					$this->session->set_flashdata ( "growl_success", 'Troubles subtype successfully added / updated' );
					redirect ( "/admin/trouble_type/index/page/" . $page_number );
				} else {
					$this->session->set_flashdata ( "growl_success", 'Troubles subtype successfully added / updated' );
					redirect ( "/admin/trouble_type" );
				}
			}
		} else {
			$this->session->set_flashdata ( "growl_error", 'Something went wrong' );
			redirect ( "/admin/trouble_type" );
		}
	}
	
	/**
	 * delete_troubles_subtype
	 *
	 * To delete the subtype based on the subtype_id specified
	 *
	 * @param integer $id        	
	 * @param integer $edit_id        	
	 * @param integer $page_number        	
	 *
	 * @author Sumesh
	 */
	public function delete_troubles_subtype($id = null, $edit_id = null, $page_number = null) {
		$result = $this->trouble_types->delete_troubles_subtype ( $id );
		
		if ($result == true) {
			$this->session->set_flashdata ( "growl_success", 'Troubles subtype successfully deleted' );
		} else {
			$this->session->set_flashdata ( "growl_error", 'Something went wrong' );
		}
		
		redirect ( "/admin/trouble_type/edit/" . $edit_id . "/" . $page_number );
	}
	
	/**
	 * check_unique_subtype
	 *
	 * To check the uniqueness of troubles subtype key
	 *
	 * @author Sumesh
	 */
	function check_unique_subtype() {
		$key = $this->input->post ( 'key' );
		$id = $this->input->post ( 'id' );
		$result = $this->trouble_types->check_unique_subtype ( $key, $id );
		echo json_encode ( $result );
	}
	
	/**
	 * check_unique_related_process
	 *
	 * To check the uniqueness of related process
	 *
	 * @author Sumesh
	 */
	function check_unique_related_process() {
		$post = $this->input->post ();
		$result = $this->trouble_types->check_unique_related_process ( $post );
		
		echo json_encode ( $result );
	}
}
?>
