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
class Companies extends Admin_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'company' );
		$this->load->model ( 'key' );
		$this->breadcrumb->append ( 'Admin', '/admin/' );
		$this->breadcrumb->append ( 'Companies', '/admin/companies/' );
	}
	
	/**
	 * Index Page for this controller.
	 */
	public function index() {
		$this->get ();
	}
	public function get() {
		$data = array ();
		
		$data ['companies'] = $this->company->get ( $this->config->item ( 'per_page' ), $this->uri->segment ( 5 ) );
		$config ['base_url'] = '/admin/companies/get/page/';
		$config ['total_rows'] = $this->company->total ();
		
		$this->pagination->initialize ( $config );
		
		$data ['content'] = $this->load->view ( 'wmanager/companies/list', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	/**
	 * Add a new company
	 */
	public function add() {
		$this->breadcrumb->append ( 'New Company', '/admin/companies/add/' );
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'active' )) {
				$_POST ['active'] = 'f';
			} else {
				$_POST ['active'] = 't';
			}
			if ($this->company->add ()) {
				redirect ( '/admin/companies', 'refresh' );
			}
		}
		
		$this->load->helper ( 'form' );
		$this->load->library ( 'form_builder' );
		$all_roles = $this->company->get_roles ();
		$roles = array ();
		$array_company_role = array ();
		$sharing = array (
				'0' => 'Creato o assegnato',
				'1' => 'Creato, assegnato o non assegnato',
				'2' => 'Vede tutto' 
		);
		$company_list = $this->company->company_list ();
		$array_form_general = array (
				array(/* Holding autocomplete*/
		        'id' => 'holding_autocomplete',
						'label' => 'Holding',
						'placeholder' => 'Holding company',
						'class' => 'typeahead' 
				),
				array(/* Holding value */
		        'id' => 'holding',
						'type' => 'hidden' 
				),
				array(/* Name */
		        'id' => 'name',
						'label' => 'Name',
						'placeholder' => 'Company Name',
						'required' => 'required' 
				),
				array(/* Contract */
					'id' => 'parent_company',
						'type' => 'dropdown',
						'label' => 'Parent company',
						'options' => $company_list 
				),
				array(/* VAT */
		        'id' => 'vat_code',
						'label' => 'IVA',
						'placeholder' => 'IVA',
						'class' => '' 
				),
				array(/* Contact */
		        'id' => 'contact',
						'label' => 'Ref. Contact',
						'placeholder' => 'First Name and Last Name',
						'class' => 'required' 
				),
				array(/* Phone1 */
		        'id' => 'phone1',
						'label' => 'Tel',
						'placeholder' => 'tel' 
				),
				array(/* Phone2*/
		        'id' => 'phone2',
						'label' => 'Tel (2)',
						'placeholder' => 'tel' 
				),
				array(/* Email1*/
		        'id' => 'email1',
						'label' => 'Email',
						'placeholder' => 'Email',
						'type' => 'email' 
				),
				array(/* Email2*/
		        'id' => 'email2',
						'label' => 'Email (2)',
						'placeholder' => 'Email',
						'type' => 'email' 
				),
				array(/* Fax*/
		        'id' => 'fax',
						'label' => 'Fax',
						'placeholder' => 'Number Fax' 
				),
				array(/* Active */
		        'id' => 'active',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Active',
						'default_value' => 't',
						'checked' => true 
				) 
		);
		$a = 0;
		foreach ( $all_roles as $role ) {
			$roles [$role->id] = $role->key;
			$array_company_role = array (
					'id' => 'company_role',
					'type' => 'combine',
					'label' => 'Ruolo',
					'elements' => array (
							
							array (
									'id' => $role->key,
									'label' => $role->key,
									'name' => 'role[' . $a . ']',
									'type' => 'checkbox',
									'value' => $role->id,
									'default_value' => $role->id,
									'column' => 'col-md-4' 
							),
							
							array (
									'id' => 'operative',
									'label' => 'Operative',
									'name' => 'operative[' . $a . ']',
									'type' => 'checkbox',
									'value' => 'Y',
									'default_value' => 'Y',
									'column' => 'col-md-4 ' 
							),
							array (
									'id' => 'sharing',
									'label' => '',
									'name' => 'sharing[' . $a . ']',
									'type' => 'dropdown',
									'class' => 'form-control',
									'options' => $sharing,
									'column' => 'col-md-4' 
							) 
					) 
			);
			array_push ( $array_form_general, $array_company_role );
			$a ++;
		}
		$array_form_billing = array (
				array(/* Billing address */
		        'id' => 'billing_address_street',
						'label' => 'Address',
						'placeholder' => 'Address' 
				),
				array(/* Billing city */
		        'id' => 'billing_address_city',
						'label' => 'City',
						'placeholder' => 'City' 
				),
				array(/* Billing province */
		        'id' => 'billing_address_province',
						'label' => 'Province',
						'placeholder' => 'Province' 
				),
				array(/* Billing state */
		        'id' => 'billing_address_state',
						'label' => 'Regione',
						'placeholder' => 'Regione' 
				),
				array(/* Billing country */
		        'id' => 'billing_address_country',
						'label' => 'Nation',
						'placeholder' => 'Nation' 
				),
				array(/* Billing zip */
		        'id' => 'billing_address_zip',
						'label' => 'zip',
						'placeholder' => 'zip' 
				) 
		);
		
		$array_form_shipping = array (
				array(/* Shipping address */
		        'id' => 'shipping_address_street',
						'label' => 'Address',
						'placeholder' => 'Address' 
				),
				array(/* Shipping city */
		        'id' => 'shipping_address_city',
						'label' => 'City',
						'placeholder' => 'City' 
				),
				array(/* Shipping province */
		        'id' => 'shipping_address_province',
						'label' => 'Province',
						'placeholder' => 'Province' 
				),
				array(/* Shipping state */
		        'id' => 'shipping_address_state',
						'label' => 'Regione',
						'placeholder' => 'Regione' 
				),
				array(/* Shipping country */
		        'id' => 'shipping_address_country',
						'label' => 'Nation',
						'placeholder' => 'Nation' 
				),
				array(/* Shipping zip */
		        'id' => 'shipping_address_zip',
						'label' => 'zip',
						'placeholder' => 'zip' 
				),
				array(/* Copy */
		        'id' => 'copy_billing',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Copy by billing',
						'value' => 't',
						'name' => '' 
				) 
		);
		
		$data ['form_general'] = $this->form_builder->build_form_horizontal ( $array_form_general );
		$data ['form_billing'] = $this->form_builder->build_form_horizontal ( $array_form_billing );
		$data ['form_shipping'] = $this->form_builder->build_form_horizontal ( $array_form_shipping );
		
		$data ['content'] = $this->load->view ( 'admin/companies/add', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	/**
	 * Edit a company
	 */
	public function edit($id = NULL) {
		if ($id == NULL)
			redirect ( '/admin/companies/', 'refresh' );
		
		if (! $company = $this->company->get_single ( $id ))
			redirect ( '/admin/companies/', 'refresh' );
		$this->breadcrumb->append ( $company->name, '' );
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'active' )) {
				$_POST ['active'] = 'f';
			} else {
				$_POST ['active'] = 't';
			}
			if ($this->company->edit ( $id )) {
				redirect ( '/admin/companies', 'refresh' );
			}
		}
		
		$this->load->helper ( 'form' );
		$this->load->library ( 'form_builder' );
		
		$data ['company'] = $company;
		if (isset ( $company->role ))
			$data ['role'] = $company->role;
		$data ['users'] = $this->company->get_users ( $id );
		if (! $data ['company'])
			redirect ( '/admin/companies/', 'refresh' );
		$company_list = $this->company->company_list ();
		$data ['keys'] = $this->key->get ( $id );
		
		$data ['cleaning_keys'] = $this->company->get_keys ( $id );
		
		$all_roles = $this->company->get_roles ();
		$roles = array ();
		$array_company_role = array ();
		$sharing = array (
				'0' => 'Creato o assegnato',
				'1' => 'Creato, assegnato o non assegnato',
				'2' => 'Vede tutto' 
		);
		$array_form_general = array (
				array(/* Holding autocomplete*/
		        'id' => 'holding_autocomplete',
						'label' => 'Holding',
						'placeholder' => 'Azienda holding',
						'class' => 'typeahead selected' 
				),
				array(/* Holding value */
		        'id' => 'holding',
						'type' => 'hidden' 
				),
				array(/* Name */
		        'id' => 'name',
						'label' => 'Nome',
						'placeholder' => 'Nome azienda',
						'required' => 'required' 
				),
				array(/* Contract */
					'id' => 'parent_company',
						'type' => 'dropdown',
						'label' => 'Parent company',
						'options' => $company_list 
				),
				array(/* VAT */
		        'id' => 'vat_code',
						'label' => 'IVA',
						'placeholder' => 'IVA',
						'class' => '' 
				),
				array(/* Contact */
		        'id' => 'contact',
						'label' => 'Contatto di riferimento',
						'placeholder' => 'Nome e cognome',
						'class' => 'required' 
				),
				array(/* Phone1 */
		        'id' => 'phone1',
						'label' => 'Telefono',
						'placeholder' => 'Numero telefonico' 
				),
				array(/* Phone2*/
		        'id' => 'phone2',
						'label' => 'Telefono (2)',
						'placeholder' => 'Numero telefonico' 
				),
				array(/* Email1*/
		        'id' => 'email1',
						'label' => 'Email',
						'placeholder' => 'Email',
						'type' => 'email' 
				),
				array(/* Email2*/
		        'id' => 'email2',
						'label' => 'Email (2)',
						'placeholder' => 'Email',
						'type' => 'email' 
				),
				array(/* Fax*/
		        'id' => 'fax',
						'label' => 'Fax',
						'placeholder' => 'Numero Fax' 
				),
				
				array(/* Active */
		        'id' => 'active',
						'type' => 'checkbox',
						'class' => 'checkbox',
						'label' => 'Attiva',
						'default_value' => 't' 
				) 
		);
		$a = 0;
		$sharings = 0;
		foreach ( $all_roles as $role ) {
			$roles [$role->id] = $role->key;
			$checked = false;
			$checkedd = false;
			if (isset ( $company->role )) {
				if (in_array ( $role->id, $company->role )) {
					$checked = true;
				}
			}
			if (isset ( $company->operative [$role->id] )) {
				if ($company->operative [$role->id] == 'Y') {
					$checkedd = true;
				}
			}
			if (isset ( $company->sharing [$role->id] )) {
				($company->sharing [$role->id]) ? $sharings = $company->sharing [$role->id] : $sharings = 0;
			}
			
			$array_company_role = array (
					'id' => 'company_role',
					'type' => 'combine',
					'label' => 'Ruolo',
					'elements' => array (
							
							array (
									'id' => $role->key,
									'label' => $role->key,
									'name' => 'role[' . $a . ']',
									'type' => 'checkbox',
									'value' => $role->id,
									'default_value' => $role->id,
									'column' => 'col-md-4',
									'checked' => $checked 
							),
							
							array (
									'id' => 'operative',
									'label' => 'Operative',
									'name' => 'operative[' . $a . ']',
									'type' => 'checkbox',
									'value' => 'Y',
									'default_value' => 'Y',
									'column' => 'col-md-4 ',
									'checked' => $checkedd 
							),
							array (
									'id' => 'sharing',
									'label' => '',
									'name' => 'sharing[' . $a . ']',
									'type' => 'dropdown',
									'class' => 'form-control',
									'options' => $sharing,
									'column' => 'col-md-4',
									'value' => $sharings 
							) 
					) 
			);
			array_push ( $array_form_general, $array_company_role );
			$a ++;
		}
		
		$array_form_billing = array (
				array(/* Billing address */
		        'id' => 'billing_address_street',
						'label' => 'Indirizzo',
						'placeholder' => 'Indirizzo' 
				),
				array(/* Billing city */
		        'id' => 'billing_address_city',
						'label' => 'Città',
						'placeholder' => 'Città' 
				),
				array(/* Billing province */
		        'id' => 'billing_address_province',
						'label' => 'Provincia',
						'placeholder' => 'Provincia' 
				),
				array(/* Billing state */
		        'id' => 'billing_address_state',
						'label' => 'Regione',
						'placeholder' => 'Regione' 
				),
				array(/* Billing country */
		        'id' => 'billing_address_country',
						'label' => 'Nazione',
						'placeholder' => 'Nazione' 
				),
				array(/* Billing zip */
		        'id' => 'billing_address_zip',
						'label' => 'CAP',
						'placeholder' => 'CAP' 
				) 
		);
		
		$array_form_shipping = array (
				array(/* Shipping address */
		        'id' => 'shipping_address_street',
						'label' => 'Indirizzo',
						'placeholder' => 'Indirizzo' 
				),
				array(/* Shipping city */
		        'id' => 'shipping_address_city',
						'label' => 'Città',
						'placeholder' => 'Città' 
				),
				array(/* Shipping province */
		        'id' => 'shipping_address_province',
						'label' => 'Provincia',
						'placeholder' => 'Provincia' 
				),
				array(/* Shipping state */
		        'id' => 'shipping_address_state',
						'label' => 'Regione',
						'placeholder' => 'Regione' 
				),
				array(/* Shipping country */
		        'id' => 'shipping_address_country',
						'label' => 'Nazione',
						'placeholder' => 'Nazione' 
				),
				array(/* Shipping zip */
		        'id' => 'shipping_address_zip',
						'label' => 'CAP',
						'placeholder' => 'CAP' 
				) 
		);
		
		if ($data ['company']->holding_autocomplete == '') {
			$array_form_general [0] ['class'] = 'typeahead';
		}
		
		$data ['form_general'] = $this->form_builder->build_form_horizontal ( $array_form_general, $data ['company'] );
		$data ['form_billing'] = $this->form_builder->build_form_horizontal ( $array_form_billing, $data ['company'] );
		$data ['form_shipping'] = $this->form_builder->build_form_horizontal ( $array_form_shipping, $data ['company'] );
		
		$data ['content'] = $this->load->view ( 'admin/companies/edit', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function delete($id = NULL) {
		if ($id == NULL)
			redirect ( '/admin/companies/', 'refresh' );
		
		$this->company->delete ( $id );
		redirect ( '/admin/companies/', 'refresh' );
	}
	public function add_user($id) {
		$this->session->unset_userdata ( 'errors' );
		if (! $company = $this->company->get_single ( $id ))
			redirect ( '/admin/companies/edit/' . $id, 'refresh' );
		
		$this->breadcrumb->append ( $company->name, '/admin/companies/edit/' . $id );
		$this->breadcrumb->append ( 'Utenti', '/admin/companies/edit/' . $id . '/#users' );
		$this->breadcrumb->append ( 'Nuovo utente', '' );
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'active' )) {
				$_POST ['active'] = '1';
			} else {
				$_POST ['active'] = '0';
			}
			
			if ($result = $this->company->add_user ( $id )) {
				redirect ( '/admin/companies/edit/' . $id . '/#users', 'refresh' );
			}
		}
		
		$this->load->helper ( 'form' );
		$this->load->library ( 'form_builder' );
		$company_group = $this->company->get_company_group ( $id );
		$users_group_list = $this->company->get_users_group_list ( $id );
		$roles = array ();
		$array_user_role = array ();
		
		$array_form = array (
				array(/* First Name */
		        'id' => 'first_name',
						'label' => 'Nome',
						'placeholder' => 'Nome',
						'required' => 'required' 
				),
				array(/* Last Name */
		        'id' => 'last_name',
						'label' => 'Cognome',
						'placeholder' => 'Cognome',
						'required' => 'required' 
				),
				array(/* Nickname */
		        'id' => 'username',
						'label' => 'Nickname',
						'placeholder' => 'Nickname',
						'required' => 'required' 
				),
				array(/* Phone */
		        'id' => 'phone',
						'label' => 'Telefono',
						'placeholder' => 'Telefono' 
				),
				array(/* Mobile */
		        'id' => 'mobile',
						'label' => 'Cellulare',
						'placeholder' => 'Cellulare' 
				),
				array(/* DROP DOWN */
		        'id' => 'role1',
						'label' => 'Ruolo',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => array (
								'OPERATOR' => 'Operatore',
								'CONTROLLER' => 'Controller' 
						) 
				),
				array(/* Email */
		        'id' => 'email',
						'label' => 'Username',
						'placeholder' => 'Username',
						'required' => 'required',
						'type' => 'email' 
				),
				array(/* Password */
		        'id' => 'password',
						'label' => 'Password',
						'placeholder' => 'Password',
						'required' => 'required',
						'type' => 'password' 
				) 
		);
		$array_users_roles = array ();
		foreach ( $company_group as $role ) {
			$users_role = array (
					'id' => $role->key,
					'label' => $role->key,
					'name' => 'role[]',
					'type' => 'checkbox',
					'value' => $role->role_key,
					'default_value' => $role->role_key,
					'column' => 'col-md-4' 
			);
			array_push ( $array_users_roles, $users_role );
		}
		$array_user_role = array (
				'id' => 'user_role',
				'type' => 'combine',
				'label' => 'Ruolo',
				'elements' => $array_users_roles 
		);
		array_push ( $array_form, $array_user_role );
		
		$array_users_group = array ();
		foreach ( $users_group_list as $usergroup ) {
			$usergroups [$usergroup->id] = $usergroup->name;
			$users_group = array (
					'id' => $usergroup->name,
					'label' => $usergroup->name,
					'name' => 'usergroup[]',
					'type' => 'checkbox',
					'value' => $usergroup->id,
					'default_value' => $usergroup->id,
					'column' => 'col-md-2' 
			);
			array_push ( $array_users_group, $users_group );
		}
		$array_user_group = array (
				'id' => 'user_groups',
				'type' => 'combine',
				'label' => 'Group',
				'elements' => $array_users_group 
		);
		
		array_push ( $array_form, $array_user_group );
		$data ['form'] = $this->form_builder->build_form_horizontal ( $array_form );
		
		$data ['content'] = $this->load->view ( 'admin/companies/users/add', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function edit_user($company_id, $id) {
		$this->session->unset_userdata ( 'errors' );
		if (! $company = $this->company->get_single ( $company_id ))
			redirect ( '/admin/companies/edit/' . $company_id, 'refresh' );
		$data ['user'] = $this->ion_auth->user ( $id )->row ();
		
		$this->breadcrumb->append ( $company->name, '/admin/companies/edit/' . $company_id );
		$this->breadcrumb->append ( 'Utenti', '/admin/companies/edit/' . $company_id . '/#users' );
		$this->breadcrumb->append ( $data ['user']->username, '' );
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'active' )) {
				$_POST ['active'] = '1';
			} else {
				$_POST ['active'] = '0';
			}
			if ($this->company->edit_user ( $company_id, $id )) {
				redirect ( '/admin/companies/edit/' . $company_id . '/#users', 'refresh' );
			}
		}
		$this->load->helper ( 'form' );
		$this->load->library ( 'form_builder' );
		$company_group = $this->company->get_company_group ( $company_id );
		$user_roles = $this->company->get_user_roles ( $id );
		$users_group_list = $this->company->get_users_group_list ( $id );
		$user_group = $this->company->get_user_group ( $id );
		
		$roles = array ();
		$array_user_role = array ();
		
		$array_form = array (
				array(/* First Name */
		        'id' => 'first_name',
						'label' => 'Nome',
						'placeholder' => 'Nome',
						'required' => 'required' 
				),
				array(/* Last Name */
		        'id' => 'last_name',
						'label' => 'Cognome',
						'placeholder' => 'Cognome',
						'required' => 'required' 
				),
				array(/* Nickname */
		        'id' => 'username',
						'label' => 'Nickname',
						'placeholder' => 'Nickname',
						'required' => 'required' 
				),
				array(/* Phone */
		        'id' => 'phone',
						'label' => 'Telefono',
						'placeholder' => 'Telefono' 
				),
				array(/* Mobile */
		        'id' => 'mobile',
						'label' => 'Cellulare',
						'placeholder' => 'Cellulare' 
				),
				array(/* DROP DOWN */
		        'id' => 'role1',
						'label' => 'Ruolo',
						'type' => 'dropdown',
						'class' => 'form-control',
						'options' => array (
								'OPERATOR' => 'Operatore',
								'CONTROLLER' => 'Controller' 
						) 
				),
				array(/* Email */
		        'id' => 'email',
						'label' => 'Username',
						'placeholder' => 'Username',
						'required' => 'required',
						'type' => 'email' 
				),
				array(/* Password */
		        'id' => 'password',
						'label' => 'Nuova password',
						'placeholder' => 'Password',
						'type' => 'password' 
				) 
		);
		$array_users_roles = array ();
		foreach ( $company_group as $role ) {
			$checked = false;
			if (is_array ( $user_roles )) {
				for($i = 0; $i < count ( $user_roles ); $i ++) {
					if ($role->role_key == $user_roles [$i]->role_id) {
						$checked = true;
					}
				}
			}
			
			$users_role = array (
					'id' => $role->key,
					'label' => $role->key,
					'name' => 'role[]',
					'type' => 'checkbox',
					'value' => $role->role_key,
					'default_value' => $role->role_key,
					'column' => 'col-md-4',
					'checked' => $checked 
			);
			array_push ( $array_users_roles, $users_role );
		}
		$array_user_role = array (
				'id' => 'user_role',
				'type' => 'combine',
				'label' => 'Ruolo',
				'elements' => $array_users_roles 
		);
		array_push ( $array_form, $array_user_role );
		
		$array_users_group = array ();
		foreach ( $users_group_list as $usergroup ) {
			$checked = false;
			if (is_array ( $user_group )) {
				for($i = 0; $i < count ( $user_group ); $i ++) {
					if ($usergroup->id == $user_group [$i]->group_id) {
						$checked = true;
					}
				}
			}
			$users_group = array (
					'id' => isset ( $usergroup->key ) ? $usergroup->key : NULL,
					'label' => $usergroup->name,
					'name' => 'usergroup[]',
					'type' => 'checkbox',
					'value' => $usergroup->id,
					'default_value' => $usergroup->id,
					'column' => 'col-md-2',
					'checked' => $checked 
			);
			array_push ( $array_users_group, $users_group );
		}
		$array_user_group = array (
				'id' => 'user_groups',
				'type' => 'combine',
				'label' => 'Group',
				'elements' => $array_users_group 
		);
		
		array_push ( $array_form, $array_user_group );
		
		unset ( $data ['user']->password );
		
		$data ['form'] = $this->form_builder->build_form_horizontal ( $array_form, $data ['user'] );
		
		$data ['content'] = $this->load->view ( 'admin/companies/users/edit', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	public function add_api($company) {
		$this->breadcrumb->append ( 'Nuovo API key', '' );
		$redirect = NULL;
		if ($this->input->post ( 'redirect' )) {
			$redirect = $this->input->post ( 'redirect' );
		}
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'active' )) {
				$_POST ['active'] = 'f';
			} else {
				$_POST ['active'] = 't';
			}
			if ($id = $this->key->add ()) {
				redirect ( '/admin/companies/edit/' . $company . '/#api_keys', 'refresh' );
			}
		}
		
		$this->load->helper ( 'form' );
		$this->load->library ( 'form_builder' );
		
		$array_form_general = array (
				
				array(/* Owner value */
		        'id' => 'id_company',
						'type' => 'hidden',
						'value' => $company 
				),
				array(/* Key */
		        'id' => 'key',
						'label' => 'API-Key',
						'placeholder' => '',
						'required' => 'required',
						'value' => random_string ( 'unique' ),
						'readonly' => true 
				),
				array(/* Active */
			        'id' => 'active',
						'type' => 'checkbox',
						'label' => 'Attiva',
						'default_value' => 't',
						'checked' => true 
				) 
		);
		
		$data ['form_general'] = $this->form_builder->build_form_horizontal ( $array_form_general );
		
		$data ['content'] = $this->load->view ( 'wmanager/api_keys/add', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	/**
	 * Edit contract
	 */
	public function edit_api($company = NULL, $id = NULL) {
		if ($id == NULL)
			redirect ( '/admin/companies/#api_keys', 'refresh' );
		
		if (! $key = $this->key->get_single ( $id ))
			redirect ( '/admin/companies/edit/' . $company . '/#api_keys', 'refresh' );
		$data ['key'] = $key;
		$this->breadcrumb->append ( 'Modifica API-Key', '' );
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'active' )) {
				$_POST ['active'] = 'f';
			} else {
				$_POST ['active'] = 't';
			}
			if ($this->key->edit ( $id )) {
				redirect ( '/admin/companies/edit/' . $company . '/#api_keys', 'refresh' );
			}
		}
		
		$this->load->helper ( 'form' );
		$this->load->library ( 'form_builder' );
		
		$array_form_general = array (
				array(/* Owner value */
		        'id' => 'id_company',
						'type' => 'hidden',
						'value' => $company 
				),
				array(/* Key */
		        'id' => 'key',
						'label' => 'API-Key',
						'placeholder' => '',
						'required' => 'required',
						'readonly' => true 
				),
				array(/* Active */
			    'id' => 'active',
						'type' => 'checkbox',
						'label' => 'Attiva',
						'default_value' => 't' 
				) 
		);
		
		$data ['form_general'] = $this->form_builder->build_form_horizontal ( $array_form_general, $data ['key'] );
		
		$data ['content'] = $this->load->view ( 'wmanager/api_keys/edit', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	/**
	 * Add a new key
	 */
	public function add_key($customer = NULL) {
		if ($customer == NULL)
			redirect ( '/admin/companies/', 'refresh' );
		
		$this->breadcrumb->append ( $contract->title, '/admin/companies/edit/' . $customer );
		$this->breadcrumb->append ( 'Nuova chiave', '' );
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'active' )) {
				$_POST ['active'] = 'f';
			} else {
				$_POST ['active'] = 't';
			}
			if ($id = $this->company->add_key ( $customer )) {
				redirect ( '/admin/companies/edit/' . $customer . '/#cleaning_users', 'refresh' );
			}
		}
		
		$this->load->helper ( 'form' );
		$this->load->library ( 'form_builder' );
		
		$array_form_general = array (
				array(/* company */
		        'id' => 'id_company',
						'type' => 'hidden',
						'value' => $customer 
				),
				array(/* Product */
				'id' => 'product',
						'type' => 'dropdown',
						'label' => 'Prodotto',
						'options' => array (
								'BLANCCO-BMC1.5' => 'BLANCCO-BMC1.5',
								'BLANCCO-MC3' => 'BLANCCO-MC3' 
						) 
				),
				array(/* Username */
		        'id' => 'username',
						'label' => 'Username',
						'required' => 'required' 
				),
				array(/* Password */
		        'id' => 'password',
						'type' => 'password',
						'label' => 'Password',
						'required' => 'required' 
				),
				array(/* Status */
				'id' => 'active',
						'type' => 'checkbox',
						'label' => 'Attiva',
						'default_value' => 't',
						'checked' => 'checked' 
				) 
		);
		
		$data ['form_general'] = $this->form_builder->build_form_horizontal ( $array_form_general );
		
		$data ['content'] = $this->load->view ( 'wmanager/contracts/keys/add', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
	
	/**
	 * Edit a key
	 */
	public function edit_key($customer = NULL, $id = NULL) {
		if ($customer == NULL || $id == NULL)
			redirect ( '/admin/companies/', 'refresh' );
		
		$this->breadcrumb->append ( $contract->title, '/admin/companies/edit/' . $customer );
		$this->breadcrumb->append ( 'Modifica chiave', '' );
		
		$key = $this->company->get_single_key ( $id );
		
		if ($this->input->post ()) {
			if (! $this->input->post ( 'active' )) {
				$_POST ['active'] = 'f';
			} else {
				$_POST ['active'] = 't';
			}
			if ($id = $this->company->edit_key ( $customer, $id )) {
				redirect ( '/admin/companies/edit/' . $customer . '/#cleaning_users', 'refresh' );
			}
		}
		
		$this->load->helper ( 'form' );
		$this->load->library ( 'form_builder' );
		
		$array_form_general = array (
				array(/* company */
		        'id' => 'id_company',
						'type' => 'hidden',
						'value' => $customer 
				),
				array(/* Role */
				'id' => 'product',
						'type' => 'dropdown',
						'label' => 'Prodotto',
						'options' => array (
								'BLANCCO-BMC1.5' => 'BLANCCO-BMC1.5',
								'BLANCCO-MC3' => 'BLANCCO-MC3' 
						) 
				),
				array(/* Username */
		        'id' => 'username',
						'label' => 'Username',
						'required' => 'required' 
				),
				array(/* Password */
		        'id' => 'password',
						'type' => 'password',
						'label' => 'Password',
						'required' => 'required' 
				),
				array(/* Status */
				'id' => 'active',
						'type' => 'checkbox',
						'label' => 'Attiva',
						'default_value' => 't' 
				) 
		);
		
		$data ['form_general'] = $this->form_builder->build_form_horizontal ( $array_form_general, $key );
		
		$data ['content'] = $this->load->view ( 'wmanager/contracts/keys/edit', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */