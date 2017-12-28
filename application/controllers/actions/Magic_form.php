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
class Magic_form extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->library ( 'actions' );
	}
	
	/**
	 * Index Page for this controller.
	 */
	public function index() {
		$inputs = $_POST;
		
		$magic_fields = $inputs;
		
		// Removing the other fileds
		unset ( $magic_fields ['immobiliItemsDetail'] );
		unset ( $magic_fields ['description'] );
		unset ( $magic_fields ['status'] );
		unset ( $magic_fields ['activity'] );
		unset ( $magic_fields ['thread'] );
		unset ( $magic_fields ['errors'] );
		
		// Activity Update
		$update ['description'] = $inputs ['description'];
		$this->db->where ( 'id', $inputs ['activity'] )->update ( 'activities', $update );
		
		if ($inputs ['status'] == 'DONE')
			$data ['RESULT'] = 'OK';
		$data ['STATUS'] = $inputs ['status'];
		
		if (count ( $magic_fields ) > 0) {
			foreach ( $magic_fields as $key => $field ) {
				$data [$key] = $field;
			}
		}
		
		$this->core_actions->update_var ( 'ACTIVITY', $inputs ['activity'], 'ACTIVITY', $inputs ['activity'], $data, NULL );
		
		$result = true;
		$message = '';
		
		$return = array (
				'result' => $result,
				'error' => $message 
		);
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $return ) );
	}
}
