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

class Account extends CI_Model
{
	
	
	public function get($limit, $offset = 0)
	{
		$company = $this->ion_auth->user()->row()->id_company;
		
		if(ISSET($_POST['search'])){
			$this->session->set_userdata('filter_accounts',trim($this->input->post('search')));
		}

		$filter = $this->session->userdata('filter_accounts');

		if($filter){
			$filter = $this->db->escape_like_str($filter);
			$this->db->where("(accounts.first_name ILIKE '%$filter%' OR accounts.last_name ILIKE '%$filter%' OR accounts.code ILIKE '%$filter%')");
		}

		$query = $this->db->select('a.id, a.type,
									a.address,
									a.zip as zip,
									a.city as city,
									a.state as state,
									a.country as country,
									accounts.*,accounts.created, c.value as tel')
					->join('address a',"a.id = accounts.address_id AND a.type = 'CLIENT'",'left')
					->join('contacts c',"c.account_id = accounts.id AND c.contact_type = 'tel'",'left')
					->limit($limit,$offset)
					->get('accounts');
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
		

	}

	public function total()
	{
		$filter = $this->session->userdata('filter_accounts');

		if($filter){
			$filter = $this->db->escape_like_str($filter);
			$this->db->where("(accounts.first_name ILIKE '%$filter%' OR accounts.last_name ILIKE '%$filter%' OR accounts.code ILIKE '%$filter%')");
		}

		$query = $this->db->select('accounts.*')->join('address','address.id = accounts.address_id')->where('address.type','CLIENT')->get('accounts');
		return $query->num_rows();
	}

	public function detail($id)
	{
		$query = $this->db->where('id',$id)->get('accounts');
		return $query->row();
	}

	public function threads($id)
	{
		$company = $this->ion_auth->user()->row()->id_company;
		$query = $this->db->select('threads.*,users.first_name, users.last_name, users.company, accounts.p_nome, accounts.p_cognome')->join('accounts','accounts.id = threads.customer')->join('users','users.id = threads.created_by')->where('threads.customer',$id)->order_by('created','desc')->get('threads');
		return $query->result();
	}

	public function be($id)
	{

		$query = $this->db->select("accounts.code,
									be.id as be_id,
									contracts.contract_code,
									contracts.contract_type,				
									(select accounts.first_name ||' '|| accounts.last_name from accounts join be as be2 on be2.account_id=accounts.id) as client_name,
									companies.name as company_name,
									a.id, a.type as address_type,
									a.address as address,
									a.zip as zip,
									a.city as city,									
									a.state as state,
									a.country as country,
									accounts.id as id_cliente,
									(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'tel') as tel,
									(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'email') as email,
									(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'cell') as cell")
									->where('be.account_id',$id)					
									->join('accounts','accounts.id = be.account_id','left')
									->join('assets', 'assets.be_id = be.id','left')
									->join('contracts','contracts.id = assets.contract_id','left')
									->join('immobili', 'immobili.be_id = be.id','left')
									->join('address a',"a.id = immobili.address_id AND a.type = 'IMMOBILI'",'left')				
									->join("companies","companies.id = accounts.company_id","left")
									->order_by('be.id','ASC')
									->get('be');
		return $query->result();
	}

	public function contratti($id)
	{
		$query = $this->db->select("be.*,
									be.id as be_id,
									contracts.id as contract_id,
									contracts.contract_code,
									contracts.contract_type,
									contracts.d_sign as contract_d_sign,				
									products.product_type as prod_type,
									products.product_code as prod_code")
							->where('be.account_id',$id)
							->join('assets', 'assets.contract_id = contracts.id','left')
							->join('be','be.id = assets.be_id','left')
							->join('products','products.id = contracts.product_id','left')
							->order_by('assets.be_id','ASC')
							->get('contracts');
		return $query->result();
	}
	public function impianti($id)
	{
		$query = $this->db->select("be.*,
							a.id, a.type as address_type,
							a.address as address,
							a.zip as zip,
							a.city as city,							
							a.state as state,
							a.country as country,
							impianti.*")	
					->where('be.account_id',$id)
					->join('assets', 'assets.impianti_id = impianti.id','left')
					->join('be','be.id = assets.be_id','left')
					->join('accounts','accounts.id = be.account_id','left')					
					->join('address a',"a.id = impianti.address_id AND a.type = 'IMMOBILI'",'left')
					->join("companies","companies.id = accounts.company_id","left")
					->order_by('impianti.be_id','ASC')
					->get('impianti');
		return $query->result(); 
	}

	public function indirizzi($id)
	{    
	    $query = $this->db->select("accounts.*,
	    						address.*,
	    						be.id as be_id,
	    						(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'tel') as tel,
								(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'email') as email,
								(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'cell') as cell,
	    						(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'fax') as fax")
	                      ->distinct()
	                      ->join('be','be.account_id = accounts.id','left')
	                      ->join('address',"address.id = accounts.address_id",'left')
	                      ->where('accounts.id',$id)
	                      ->order_by('address.id','ASC')
	                      ->get('accounts');
	                   
		return  $query->result();
		
	}

	public function header($id)
	{
		$query = $this->db->select("accounts.*,
									a.id, a.type as address_type,
									a.address as address,
									a.zip as zip,
									a.city as city,
									a.state as state,
									a.country as country,
									accounts.id as id_cliente,
									(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'tel') as tel,
									(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'email') as email,
									(SELECT c.value  FROM contacts c LEFT JOIN accounts ON c.account_id = accounts.id where c.contact_type = 'cell') as cell")			
			->join('address a',"a.id = accounts.address_id AND a.type = 'CLIENT'",'left')
			->where('accounts.id',$id)
			->get('accounts');

		return $query->row();
	}

	public function activity($id){
		$query = $this->db->select('accounts.*, threads.process, threads.type, threads.title, be.code,be.created, be.status,be.title as contract')->where('activities.id',$id)->join('threads','threads.id = activities.id_thread')->join('accounts','accounts.id = threads.customer')->join('be','be.id = threads.be')->get('activities');
		return $query->row();
	}
	
	public function attachment($id){
		
		$query = $this->db->select("attachments.*,
									setup_attachments.description as attachment_description,
									setup_attachments.title as attachment_type")
							->where('be.account_id',$id)
							->join('threads',"attachments.thread_id=threads.id")
							->join("setup_attachments","attachments.attach_type = setup_attachments.id")
							->join('be','threads.be = be.id')
							->get('attachments');
		return $query->result();
		
	}
	
	public function get_master($pod){
		$query = $this->db->where('mpod',$pod)->get('master');
		return $query->result();
	}

	
	public function get_impianti($impianto_id){
		$query = $this->db->where('id',$impianto_id)->get('impianti');
		return $query->result();
	}
	
	public function get_be($be_id){
		$query = $this->db->where('id',$be_id)->get('be');
		return $query->result();
	}
	public function get_cliente($client_id){
		$query = $this->db->where('id',$client_id)->get('cliente');
		return $query->result();
	}
	

	static function count_actions($customer_id,$process){
		$CI =& get_instance();
		$query = $CI->db->where('customer',$customer_id)->where('activities.type',$process)->where('draft','f')->join('activities','activities.id_thread = threads.id')->get('threads');
		return $query->num_rows();
	}
	
	public function contact($id){
		//properietor
		$get_properietor = $this->db->select("accounts.*,proprieta.be_id as be_id,indirizzi.*")
									->join("accounts","accounts.id = proprieta.account_id")
									->join("indirizzi","indirizzi.account_id = accounts.id",'left')
									->where("proprieta.cliente_id",$id)
									->where("tipo_indirizzo = 'PROPRIETARIO'")
									->get("proprieta");
		$properietor 	= $get_properietor->result();
		
		//contatto for fast thread
		$get_contactto 	= $this->db->select("contatti.id as id_contatti, contatti.ruolo, contatti.disabled as contatto_disabled, accounts.id as acc_id, accounts.p_nome, accounts.p_cognome, accounts.o_ragione_sociale,be.id as be_id,indirizzi.*")
									->join("be","be.cliente_id = contatti.client_id",'left')
									->join("accounts","accounts.id = contatti.contatto_id",'left')
									->join("indirizzi","indirizzi.account_id = contatti.contatto_id",'left')
									->where("contatti.client_id",$id)
									->where("indirizzi.tipo_indirizzo = 'CONTATTO'")
									->order_by('created', 'DESC')
									->get("contatti");
		$contactti		= $get_contactto->result();	
		$final_array = array();
		
		if(count($properietor) > 0){
			foreach($properietor as $item){
				$final_array[] = $item;
			}
		}
		
		if(count($contactti) > 0){
			foreach($contactti as $item){
				$final_array[] = $item;
			}
		}
		
		
		
		return $final_array;
	}
	
	public function get_be_details($be_ids = array()){
		
		if(count($be_ids) == 0){
			return false;
		}
		
		$get_be = $this->db->select("be.id as be_id,be.cliente_id")->where_in("id",$be_ids)->get("be");
		
		return $get_be->result();
	}
	
}
