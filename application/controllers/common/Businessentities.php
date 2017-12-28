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
class Businessentities extends Common_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'be' );
		$this->breadcrumb->append ( 'Business Entities' );
	}
	
	/**
	 * Index Page for this controller.
	 */
	public function index() {
		$this->get ();
	}
	public function get() {
		$data = array ();
		
		$data ['be'] = $this->be->get ( $this->config->item ( 'per_page' ), $this->uri->segment ( 5 ) );

		$config ['base_url'] = '/common/businessentities/get/page/';
		$config ['total_rows'] = $this->be->total ();
		
		$data ['master_statuses'] = $this->be->get_master_statuses ();
		
		$this->pagination->initialize ( $config );
		
		$data ['content'] = $this->load->view ( 'common/be/list', $data, true );
		$this->load->view ( 'template', $data );
	}
	
	public function export() {
		
		$view_details = $this->be->export();
		$field_array[] = array_keys($view_details[0]);
		 
		$filename = 'export_contract_-'.date('d-m-Y-His');
		$xls = new Excel_XML;
		$xls->addArray ($field_array);
		$xls->addArray ($view_details);
		$xls->generateXML ($filename);
		return false;
	
	}
}
