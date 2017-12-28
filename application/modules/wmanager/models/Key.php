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
class Key extends CI_Model {
	public function get($company) {
		$query = $this->db->select ( 'api_keys.*, companies.icon, companies.name' )->where ( 'api_keys.id_company', $company )->where ( 'api_keys.domain', get_domain () )->join ( 'companies', 'companies.id = api_keys.id_company' )->order_by ( 'api_keys.created', 'asc' )->get ( 'api_keys' );
		$result = $query->result ();
		$this->db->flush_cache ();
		return $result;
	}
	public function add() {
		$data = $this->input->post ();
		
		$data ['created_by'] = $this->ion_auth->user ()->row ()->id;
		$data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		$data ['domain'] = get_domain ();
		
		$data = clean_array_data ( $data );
		unset ( $data ['company_autocomplete'] );
		
		if ($this->db->insert ( 'api_keys', $data )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', 'La chiave è stata inserita correttamente.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'Si è verificato un errore, non è stato possibile inserire la chiave, ti preghiamo di riprovare.' );
			return false;
		}
	}
	public function get_single($id) {
		$query = $this->db->select ( 'api_keys.*, companies.name as company_autocomplete' )->where ( 'api_keys.domain', get_domain () )->where ( 'api_keys.id', $id )->join ( 'companies', 'companies.id = api_keys.id_company' )->get ( 'api_keys' );
		return $query->row ();
	}
	public function edit($id) {
		$data = $this->input->post ();
		
		$data ['modified_by'] = $this->ion_auth->user ()->row ()->id;
		$data ['modified'] = date ( 'Y-m-d H:i:s' );
		
		$data = clean_array_data ( $data );
		unset ( $data ['company_autocomplete'] );
		
		if ($this->db->where ( 'id', $id )->update ( 'api_keys', $data )) {
			log_message ( 'DEBUG', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_success', 'La chiave è stata modifica correttamente.' );
			return true;
		} else {
			log_message ( 'ERROR', $this->db->last_query () );
			$this->session->set_flashdata ( 'growl_error', 'Si è verificato un errore, non è stato possibile modificare la chiave, ti preghiamo di riprovare.' );
			return false;
		}
	}
}
