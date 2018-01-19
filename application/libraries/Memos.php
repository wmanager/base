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
class Memos {
	var $CI;
	public function __construct() {
		$this->CI = & get_instance ();
	}
	public function add($type, $parent_id, $source, $rel_id, $data) {
		$memo_data = array ();
		switch ($source) {
			case "ACTIVITY" :
				$memo_data ['activity_id'] = $rel_id;
				$query = $this->CI->db->where ( 'id', $rel_id )->get ( 'activities' );
				$act = $query->row ();
				$memo_data ['thread_id'] = $act->id_thread;
				$query = $this->CI->db->select ( "accounts.*, address.*
									, accounts.id" )
							->where ( 'threads.id', $act->id_thread )
							->join ( 'be', 'be.id = threads.be' )
							->join ( 'accounts', 'accounts.id = be.account_id' )
							->join ( 'address', 'address.id = accounts.address_id' )						
							->where ( "address.type = 'CLIENT'" )							
							->get ( 'threads' );
				$cliente = $query->row ();
				break;
			case "THREAD" :
				$memo_data ['thread_id'] = $rel_id;
				$query = $this->CI->db->select ( "accounts.*, 
									address.*" )
						->where ( 'threads.id', $rel_id )
						->join ( 'be', 'be.id = threads.be' )
						->join ( 'accounts', 'accounts.id = be.account_id' )
						->join ( 'address', 'address.id = accounts.address_id' )
						->where ( "address.type = 'CLIENT'" )						
						->get ( 'threads' );
				$cliente = $query->row ();
				break;
			case "TROUBLE" :
				$memo_data ['trouble_id'] = $rel_id;
				$query = $this->CI->db->select ( "accounts.*, address.*")
							->where ( 'troubles.id', $rel_id )
							->join ( 'be', 'be.id = troubles.be_id', 'left' )
							->join ( 'accounts', 'accounts.id = be.account_id' )
							->join ( 'address', 'address.id = accounts.address_id' )
							->where ( "address.type = 'CLIENT'" )							
							->get ( 'troubles' );
				$cliente = $query->row ();
				break;
				case "LEGAL":
					$memo_data['legal_id'] = $rel_id;
					$query = $this->CI->db->select("accounts.*, address.*")
					->where('legal_cases.id',$rel_id)
					->join('be','be.id = legal_cases.be','left')
					->join('accounts','accounts.id = be.account_id','left')
					->join ( 'address', 'address.id = accounts.address_id' )
					->where ( "address.type = 'CLIENT'" )	
					->get('legal_cases');
					$cliente = $query->row();
					break;				
		}
		
		$memo_data ['start_day'] = (isset ( $data ['start_data'] )) ? $data ['start_data'] : '';
		$memo_data ['end_day'] = $data ['end_data'];
		$memo_data ['start_time'] = (isset ( $data ['start_time'] )) ? $data ['start_time'] : '';
		$memo_data ['end_time'] = $data ['end_time'];
		$memo_data ['title'] = $data ['title'];
		$memo_data ['company'] = (isset ( $data ['company'] )) ? $data ['company'] : '';
		$memo_data ['description'] = $data ['description'];
		$memo_data ['notification_date'] = (isset ( $data ['notification_date'] )) ? $data ['notification_date'] : '';
		
		if (isset ( $memo_data ['duty_company'] ))
			$memo_data ['duty_company'] = $data ['duty_company'];
		
		$memo_data ['first_name'] = $cliente->first_name;
		$memo_data ['last_name'] = $cliente->last_name;						
		$memo_data ['address'] = $cliente->address;
		$memo_data ['city'] = $cliente->city;
		$memo_data ['state'] = $cliente->state;
		$memo_data ['country'] = $cliente->country;
		$memo_data ['zip'] = $cliente->zip;
		$memo_data ['province'] = $cliente->province;		
		$memo_data ['tel'] = $cliente->tel;
		$memo_data ['cell'] = $cliente->cell;
		$memo_data ['email'] = $cliente->email;
		
		$memo_data ['note'] = (isset ( $data ['note'] )) ? $data ['note'] : '';
		$memo_data ['type'] = $type;
		$memo_data ['parent_id'] = $parent_id;
		$memo_data ['customer_id'] = $cliente->id;
		// $memo_data['all_day'] = $data['all_day'];
		// $memo_data['modified'] = date('Y-m-d H:i:s');
		// $memo_data['modified_by'] = $this->ion_auth->user()->row()->id;
		
		$memo_data ['created'] = date ( 'Y-m-d H:i:s' );
		$memo_data ['created_by'] = $this->CI->ion_auth->user ()->row ()->id;
		
		foreach ( $memo_data as $k => $v ) {
			if ($v == '')
				unset ( $memo_data [$k] );
		}
		// print_r($memo_data);exit;
		return $this->CI->db->insert ( 'memos', $memo_data );
	}
	public function update($rel_id, $data) {
		$memo_data = array ();
		$memo_data ['start_day'] = ! empty ( $data ['start_data'] ) ? $data ['start_data'] : NULL;
		$memo_data ['start_time'] = ! empty ( $data ['start_time'] ) ? $data ['start_time'] : NULL;
		$memo_data ['description'] = $data ['description'];
		$memo_data ['notification_date'] = ! empty ( $data ['notification_date'] ) ? $data ['notification_date'] : NULL;
		$memo_data ['modified'] = date ( 'Y-m-d H:i:s' );
		$memo_data ['modified_by'] = $this->CI->ion_auth->user ()->row ()->id;
		
		if ($this->CI->db->where ( 'id', $rel_id )->update ( 'memos', $memo_data )) {
			return array (
					'status' => TRUE,
					'message' => 'Updated successfully' 
			);
		} else {
			return array (
					'status' => FALSE,
					'message' => 'Internal Server Error' 
			);
		}
	}
}