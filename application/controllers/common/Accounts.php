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
class Accounts extends Common_Controller {
	public function __construct() {
		parent::__construct ();
		
		$this->load->model ( 'account' );		
		$this->load->model ( 'trouble' );
		$this->breadcrumb->append ( 'Client', '/common/accounts/' );
	}
	
	/**
	 * Index Page for this controller
	 */
	public function index() {
		$this->get ();
	}
	
	/**
	 * Function to get the details of account list.
	 */
	public function get() {
		$data = array ();
		
		$data ['accounts'] = $this->account->get ( $this->config->item ( 'per_page' ), $this->uri->segment ( 5 ) );
		
		$config ['base_url'] = '/common/accounts/get/page/';
		$config ['total_rows'] = $this->account->total ();
		
		$this->pagination->initialize ( $config );
		
		$data ['content'] = $this->load->view ( 'common/accounts/list', $data, true );
		$this->load->view ( 'template', $data );
	}
	public function detail($id = NULL) {

		$data = array ();
		$data ['header'] = $this->account->header ( $id );
		$data ['be'] = $this->account->be ( $id );
		$data['contratti'] = $this->account->contratti($id);
		$data['indirizzi'] = $this->account->indirizzi($id);
		$data['attachments'] = $this->account->attachment($id);
		$data['troubles'] = $this->trouble->get_by_customer($id);
		$data['trouble_types'] = $this->trouble->get_manual_types();
		
		$data ['content'] = $this->load->view ( 'common/accounts/detail', $data, true );
		$this->load->view ( 'template', $data );
	}
}

