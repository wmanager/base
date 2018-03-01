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

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Forum class.
 * 
 * @extends CI_Controller
 */
class Install extends CI_Controller {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->model('install_model');
		$this->load->model('version');
		$this->load->helper(array('form', 'cookie', 'url'));
		$this->load->library(array('form_validation', 'session'));
		
	}
	/**
	 * index function.
	 *
	 * @access public
	 * @return void
	 */
	public function index() {	
/*  		$step= 1;
		$this->install_model->reset_configs($step); */
		
		
		$data = (object)[];
		$data->version = $this->version->fetch_current_version();
		$data->content = $this->load->view ( 'install/install', $data, true );
		$this->load->view ( 'install/install_template', $data );
		return;
	}

	public function create_host() {
/*  		$step= 2;
		$this->install_model->reset_configs($step);  */
		$data = (object)[];
		
		// form validation
		$this->form_validation->set_rules('install_db_hostname', 'Hostname', 'trim|required');
		$this->form_validation->set_rules('install_db_username', 'Username', 'trim|required');
		$this->form_validation->set_rules('install_db_password', 'Password', 'trim|required');
		
		if ($this->form_validation->run() === false) {
		
			// form not ok, show errors to the user		
			$data->content = $this->load->view ( 'install/install_index', $data, true );
			$this->load->view ( 'install/install_template', $data );		
		
		} else {
			
			$hostname = $this->input->post('install_db_hostname');
			$username = $this->input->post('install_db_username');
			$password = $this->input->post('install_db_password');
		
			$_SESSION["install_db_host"] = $hostname;
			$_SESSION["install_db_username"] = $username;
			$_SESSION["install_db_password"] = $password;
			
			// replace hostname in the database.php config file
			$find    = "'hostname' =>";
			$replace = "\t" . "'hostname' => '" . $hostname . "'," . "\n";
			if ($this->install_model->edit_database_config_file($find, $replace) !== true) {
				
				$data->error = 'The hostname on your database config file cannot be replaced...';
				
				$data->content = $this->load->view ( 'install/install_index', $data, true );
				$this->load->view ( 'install/install_template', $data );
				
				return;
				
			}
			
			// replace username in the database.php config file
			$find    = "'username' =>";
			$replace = "\t" . "'username' => '" . $username . "'," . "\n";
			if ($this->install_model->edit_database_config_file($find, $replace) !== true) {
				
				$data->error = 'The username on your database config file cannot be replaced...';
				
				$data->content = $this->load->view ( 'install/install_index', $data, true );
				$this->load->view ( 'install/install_template', $data );				
				
				return;
				
			}
			
			// replace password in the database.php config file
			$find    = "'password' =>";
			$replace = "\t" . "'password' => '" . $password . "'," . "\n";
			if ($this->install_model->edit_database_config_file($find, $replace) !== true) {
				
				$data->error = 'The password on your database config file cannot be replaced...';
								
				$data->content = $this->load->view ( 'install/install_index', $data, true );
				$this->load->view ( 'install/install_template', $data );
				
				return;
				
			}
			
			// test the database connection with these new values
			if ($this->install_model->test_database_connexion($hostname, $username, $password) === true) {
								
				redirect('install/database_creation');
				
			} else {
				
				// database connection failed, the user must enter right values
				$data->error = 'Could not connect to PostgresSQL with values you entered. Please enter valid PostgresSQL hostname, username and password.';
				
				// reset the database.php config file
				$find    = "'hostname' =>";
				$replace = "\t" . "'hostname' => 'localhost'," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
				$find    = "'username' =>";
				$replace = "\t" . "'username' => ''," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
				$find    = "'password' =>";
				$replace = "\t" . "'password' => ''," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
				
				// reset variable values that where defined before
				$hostname = null;
				$username = null;
				$password = null;
				
				// send errors to the view
				$data->content = $this->load->view ( 'install/install_index', $data, true );
				$this->load->view ( 'install/install_template', $data );
				return;
			}
		
		}
		
	}
	
	/**
	 * database_creation function.
	 * 
	 * @access public
	 * @return void
	 */
	public function database_creation() {

/* 		$step= 3;
		$this->install_model->reset_configs($step); */
		$data = (object)[];
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		// form validation
		$this->form_validation->set_rules('database_name', 'Database name', 'trim|required|max_length[64]');
		
		if ($this->form_validation->run() === false) {
		
			// form not ok, show errors to the user			
			$data->content = $this->load->view ( 'install/install_database_creation', $data, true );
			$this->load->view ( 'install/install_template', $data );
		
		} else {

			$database_name = $this->input->post('database_name');
			if (isset($_COOKIE['db_name'])) {
				if($database_name == $_COOKIE['db_name'] && isset($_SESSION['table_creation'])) {
					// user try to create database which has already done
					$data->error = 'Please provide different database name since tables has been already created';
					$data->content = $this->load->view ( 'install/install_database_creation', $data, true );
					$this->load->view ( 'install/install_template', $data );
				}
			}
			setcookie('db_name', $database_name);
			$create_database = $this->install_model->create_database($database_name);
			if ($create_database['status'] === true) {
				
				// database creation ok, go to next install step
				redirect('install/tables_creation/'.$create_database['method']);
				
			} else {
				
				// create new database NOT ok: this should never happen
				$data->error = 'There was a problem creating the new database. Please try again.';
				$data->content = $this->load->view ( 'install/install_database_creation', $data, true );
				$this->load->view ( 'install/install_template', $data );
				
			}
		
		}
		
	}
	
	/**
	 * tables_creation function.
	 * 
	 * @access public
	 * @return void
	 */
	public function tables_creation($method = NULL) {

/* 		$step= 4;
		$this->install_model->reset_configs($step); */
		$data = (object)[];
		$data->method = $method;
		
		// form validation
		$this->form_validation->set_rules('db_name_cookie', 'Database name', 'trim|required|alpha_dash|max_length[64]');
		
		if ($this->form_validation->run() === false) {
		
			// form not ok, show errors to the user
			
			$data->content = $this->load->view ( 'install/install_tables_creation', $data, true );
			$this->load->view ( 'install/install_template', $data );
		
		} else {
			
			$database_name = $_COOKIE['db_name'];
			
			if ($this->install_model->create_tables($database_name) === true) {
				if($method == 'NEW') {
					if($this->install_model->update_database() === false) {
						$data->error = 'There was a problem to updating the date format in the databse';
						$data->content = $this->load->view ( 'install/install_tables_creation', $data, true );
						$this->load->view ( 'install/install_template', $data );
					} 
				}
				$_SESSION['table_creation'] = true;
				// database creation ok, go to next install step
				redirect('install/site_settings');
				
			} else {
				
				// create new database NOT ok: this should never happen
				$data->error = 'There was a problem generating tables in the database. Please try again.';
				
				$data->content = $this->load->view ( 'install/install_tables_creation', $data, true );
				$this->load->view ( 'install/install_template', $data );
				
			}
		
		}
	
	}
	
	/**
	 * site_settings function.
	 * 
	 * @access public
	 * @return void
	 */
	public function site_settings() {
/* 		$step= 5;
		$this->install_model->reset_configs($step); */
		
		$data = (object)[];
		
		// form validation
		$this->form_validation->set_rules('admin_username', 'Username', 'trim|required|alpha_numeric|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('admin_email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('admin_password', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('admin_password_confirm', 'Password Confirmation', 'trim|required|min_length[6]|matches[admin_password]');
		$this->form_validation->set_rules('company', 'Comapany', 'trim|max_length[255]');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[255]');
		
		if ($this->form_validation->run() === false) {
		
			// form not ok, show errors to the user
			$data->content = $this->load->view ( 'install/install_site_settings', $data, true );
			$this->load->view ( 'install/install_template', $data );
		
		} else {

			$config['upload_path']   = FCPATH."assets/img";
			$config['allowed_types'] = 'jpg|png';
			$config['max_size']      = '1000';
			$config['max_width']     = '300';
			$config['max_height']    = '88';
			$config['file_name'] = 'logo';
			$this->load->library('upload', $config);

			
			if(!empty($_FILES['logo']['name'])) {
				if(file_exists(FCPATH."assets/img/logo.png")) {					
					unlink(FCPATH."assets/img/logo.png");
				}	
			}
			
			if ( !$this->upload->do_upload('logo',FALSE)) {
				
				$data->error = $this->upload->display_errors();
					
				if($_FILES['logo']['error'] != 4)
				{
					$data->content = $this->load->view ( 'install/install_site_settings', $data, true );
					$this->load->view ( 'install/install_template', $data );
				}
					
			}

				// load the user model
				$this->load->model('user_model');
	
				
				// setup variables from the form inputs
				//$base_url    = $this->input->post('install_base_url');
				
				$username = $this->input->post('admin_username');
				$email    = $this->input->post('admin_email');
				$password = $this->input->post('admin_password');
				$first_name = $this->input->post('first_name');
				$last_name = $this->input->post('last_name');
				$company = $this->input->post('company');
				$group = "admin";
				
				$id_company = $this->user_model->create_company($company);
				
				$id_group = $this->user_model->create_groups($group);
				
				$id_role = $this->user_model->create_role();
				
				$post_data = array(					
						'company'     => $company,
						'id_company'  => $id_company,
						'group_id'  => $id_group,
						'role_id'  => $id_role
				);
				
				$additional_data = array(
						'first_name'   => $first_name,
						'last_name'   => $last_name,
						'company'     => $company,
						'id_company'  => $id_company,
						'role1' => 'CONTROLLER'
				);
				
				$user_id = $this->ion_auth->register ( $username, $password, $email, $additional_data);
				
				// create the admin user
				if ($this->user_model->create_admin_user($post_data, $user_id) !== true) {
					
					$data->error = 'There was a problem trying to create the admin user. Please try again...';
					
					$data->content = $this->load->view ( 'install/install_site_settings', $data, true );
					$this->load->view ( 'install/install_template', $data );
					
					return;
					
				}
				
				// replace default route in the routes.php config file
				$find    = '$route [\'default_controller\'] =';
				$replace = '$route [\'default_controller\'] = \'' . 'auth/login' . '\';' . "\n";
				if ($this->install_model->edit_routes_config_file($find, $replace) !== true) {
					
					$data->error = 'The default route on your routes config file cannot be replaced...';
					
					$data->content = $this->load->view ( 'install/install_site_settings', $data, true );
					$this->load->view ( 'install/install_template', $data );
					
					return;
					
				}	
	
				$_SESSION['site_settings'] = true;
				// forum settings ok, go to the final installation step
				redirect('install/finish');
			
			
		}
		
	}
	
	/**
	 * finish function.
	 * 
	 * @access public
	 * @return void
	 */
	public function finish() {
		
		$data = (object)[];		
		$data->content = $this->load->view ( 'install/install_finish', $data, true );
		$this->load->view ( 'install/install_template', $data );
	}
	
	/**
	 * delete_files function.
	 * 
	 * @access public
	 * @return void
	 */
	public function delete_files() {
		
		$data = (object)[];
		
		if($this->install_model->delete_installation_files()) {
			redirect('/', 'refresh');
			return;
		} else {
			echo 'Unable to delete installation files, please do it manually.';
		}
		
	}
	
	/**
	* System Check
	*
	*/
	Public function system_check(){
		$step= 1;
		$this->install_model->reset_configs($step);
		$checks = array();
		
		//Check PHP version
		$php_version = PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;
		if($php_version >= 5.5){
			$checks['php'] = 'OK';
		}else{
				$checks['php'] = 'FAIL';
			}
		
		//Check pqsql
		if  (in_array  ('pgsql', get_loaded_extensions())) {
			$checks['pgsql'] = 'OK';
		}
		else {
			$checks['pgsql'] = 'FAIL';
		}
		
		//Check Config
		if(ENVIRONMENT != ''){
			$config = FCPATH."application/config/".ENVIRONMENT."/config.php";
			$database = FCPATH."application/config/".ENVIRONMENT."/database.php";
		}else{
			$config = FCPATH."application/config/config.php";
			$database = FCPATH."application/config/database.php";
		}
		
		$config_folder = FCPATH."application/config";
		$routes = FCPATH."application/config/routes.php";
		$hooks = FCPATH."application/config/hooks.php";
		if(is_writable($config) && is_writable($config_folder) && is_writable($hooks) && is_writable($database) && is_writable($routes)){
			$checks['config_write'] = 'OK';
		}else{
			$checks['config_write'] = 'FAIL';
		}
		
		//logs folder permission
		$log_folder = FCPATH."application/logs";
		if(is_writable($log_folder)){
			$checks['log_write'] = 'OK';
		}else{
			$checks['log_write'] = 'FAIL';
		}
		
		//uploads folder
		$upload_folder = FCPATH."assets/uploads";
		if(is_writable($upload_folder)){
			$checks['upload_write'] = 'OK';
		}else{
			$checks['upload_write'] = 'FAIL';
		}
		
		//modules write
		$module_folder = FCPATH."application/modules";
		$cron_folder = FCPATH."application/controllers/cron";
		if(is_writable($module_folder) && is_writable($cron_folder)){
			$checks['module_write'] = 'OK';
		}else{
			$checks['module_write'] = 'FAIL';
		}
		
		//curl
		if  (in_array  ('curl', get_loaded_extensions())) {
			$checks['curl'] = 'OK';
		}
		else {
			$checks['curl'] = 'FAIL';
		}
		
		//session
		if  (in_array  ('session', get_loaded_extensions())) {
			$checks['session'] = 'OK';
		}
		else {
			$checks['session'] = 'FAIL';
		}
		
		//rewrite mod
		if(in_array('mod_rewrite', apache_get_modules())){
			$checks['rewrite_mod'] = 'OK';
		}else {
			$checks['rewrite_mod'] = 'FAIL';
		}
		
		//htaccess 
		if(file_exists(FCPATH."/.htaccess")){
			$checks['htaccess'] = 'OK';
		}else{
			$checks['htaccess'] = 'FAIL';
		}
		
		$data['checks'] = $checks;


		$data['content'] = $this->load->view("install/install_system_check",$data,true);
		$this->load->view("install/install_template",$data);

	}
	
	public function help($id){
		$message_array = array(
			"php" => "PHP >= 5.5 is needed to run WManager, Please install PHP version 5.5 or later.",
			"postgres" => "PostgresSQL is needed to install WManager. Please check postgres is installed properly.",
			"curl" => "cURL should be installed to run some useful process handling features. Therefore please install cURL before proceeding with the installation.",
			"session" => "PHP sessions. Wmanager requires sessions to work properly. Without it you won't be able to login to administration area nor finish the install process. If sessions doesn't work on your host, please Google or ask your hosting provider for help.",
			"rewrite" => "Apache mod_rewrite Apache mod rewrite enables you to use nice human readable and SEO friendly URLs. If you don't have this module working, you still can proceed and turn on nice URLs later from config.php file.",
			"htaccess" => ".htaccess file is needed for SEO friendly URLs. Most likely it might be missing because your Operating System hides all files starting with dot. If you are using Unix, likely you can see .htaccess file by pressing CTRL + H. Make sure you copy this file from downloaded archive.",
			"write_config" => "Please provide write permission for the particular folder <code>".FCPATH."application/config</code> in the directory where you have extracted the wmanager.",
			"log_write" => "Please Provide write permission to logs folder <code>".FCPATH."application/logs</code>.",
			"upload_write" => "Please Provide write permission to uploads folder <code>".FCPATH."assets/uploads</code>.",
			"module_write" => "Please Provide write permission to the following folders <code>".FCPATH."application/modules</code> and <code>".FCPATH."application/controllers/cron</code> to enable installation of extensions."
		);
		
		$heading_array = array(
			"php" => "PHP >= 5.5",
			"postgres" => "PostgresSQL",
			"curl" => "CURL",
			"session" => "Sessions",
			"rewrite" => "Apache mod_rewrite",
			"htaccess" => ".htaccess",
			"write_config" => "Configuration Rewrite",
			"log_write" => "Logs permission",
			"upload_write" => "Uploads",
			"module_write" => "Extensions"	
		);
		
		$data["heading"] = $heading_array[$id];
		$data["message"] = $message_array[$id];
		
		$data['content'] = $this->load->view ( 'install/install_help', $data, true );
		$this->load->view ( 'install/install_template', $data );
	}
	
	
}