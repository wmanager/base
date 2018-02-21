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
class Customer extends CI_Model {
	public function search($filter) {
		$this->db->where ( "(accounts.first_name ILIKE '%$filter%' OR accounts.last_name ILIKE '%$filter%' OR accounts.code ILIKE '%$filter%' OR contracts.contract_code ILIKE '%$filter%' OR (accounts.first_name || ' ' || accounts.last_name) ILIKE  '%$filter%')" );
		$this->db->join ( 'address', 'address.id = accounts.address_id', 'left' );
		$this->db->join ( 'be', 'be.account_id = accounts.id', 'left' );
		$this->db->join ( 'assets', 'assets.be_id = be.id', 'left' );
		$this->db->join ( 'contracts', 'contracts.id = assets.contract_id', 'left' );
		$this->db->where ( "((address.type = 'CLIENT') OR (address.type IS NULL))" );
		$this->db->distinct ()->select ( 'address.*, accounts.*' );
		$query = $this->db->get ( 'accounts' );
		
		return $query->result ();
	}
	public function contracts($user) {
		$query = $this->db->select ( 'be.id as be_id,be.be_code, be.be_status as status')
					->where ( 'be.account_id', $user )					
					->join ( 'assets', 'assets.be_id = be.id' )
					->get ( 'be' );
		$result = $query->result ();
		
		log_message ( 'DEBUG', $this->db->last_query () );
		return $result;
	}
	public function single_contract($id) {
		$query = $this->db->select ( 'be.id,be.be_status as status' )
					->where ( 'be.id', $id )
					->join ( 'assets', 'assets.be_id = be.id' )
					->get ( 'be' );
		$result = $query->row ();
		log_message ( 'DEBUG', $this->db->last_query () );
		return $result;
	}
	public function single($id) {
		$this->db->join ( 'address', 'address.id = accounts.address_id' );
		$query = $this->db->select ( 'accounts.*, address.*, accounts.id' )
			->where ( 'accounts.id', $id )
			->where ( 'address.type', 'CLIENT' )
			->get ( 'accounts' );
		return $query->row ();
	}
	public function get_contratti($be_id = null) {
		if ($be_id == NULL) {
			return array ();
		}
		
		$query = $this->db->select ( "contracts.contract_code as contract_codice,
						products.product_code as prodotti_codice,
						contracts.id" )
					->join ( "products", "products.id = contracts.product_id", "left" )
					->join ( "assets", "assets.contract_id = contracts.id", "left" )
					->where ( "be_id", $be_id )	
					->get ( "contracts" );
		$result = $query->result ();
		
		if (count ( $result ) > 0) {
			foreach ( $result as $item ) {
				$value = rtrim ( $item->contract_codice . "-" . $item->prodotti_codice);
				$item->value = $value;
			}
		} else {
			$result = array ();
		}
		
		return $result;
	}
}
