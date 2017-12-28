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
class Troubles extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'trouble' );
		$this->load->model ( 'trouble_types' );
	}
	public function types() {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->trouble->get_types () ) );
	}
	public function status() {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->trouble->get_status () ) );
	}
	public function manual_types() {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->trouble->get_manual_types () ) );
	}
	public function automatic_trouble_types() {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->trouble->get_trouble_types_to_process_types () ) );
	}
	public function check_process_type($trouble_type_id) {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->trouble->check_trouble_types_to_process_types ( $trouble_type_id ) ) );
	}
	public function get_troubles_subtypes($trouble_type_id) {
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->trouble_types->get_all_troubles_subtypes ( $trouble_type_id ) ) );
	}
}
