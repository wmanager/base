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
 * Install_model class.
 * 
 * @extends CI_Model
 */
class Install_model extends CI_Model {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		
	}
	
	public function reset_configs($step) {
		switch ($step) {
			case 1:
				if (isset($_SESSION['table_creation'])) {
					// empty value and expiration one hour before
					unset($_SESSION['table_creation']);
				}
				if (isset($_SESSION['site_settings'])) {
					// empty value and expiration one hour before
					unset($_SESSION['site_settings']);
				}
				//replace hook
				$find = '$hook["post_controller_constructor"] = ';
					
				$replace = '$hook["post_controller_constructor"] = array();';
					
				$this->edit_hook_file($find,$replace);

				// replace hostname in the database.php config file
				$find    = "'hostname' =>";
				$replace = "\t" . "'hostname' => ''," . "\n";
	
				$this->install_model->edit_database_config_file($find, $replace);
	
				$find    = "'username' =>";
				$replace = "\t" . "'username' => ''," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
	
				// replace password in the database.php config file
				$find    = "'password' =>";
				$replace = "\t" . "'password' => ''," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
				
				// replace database name in the database.php config file
				$find    = "'database' =>";
				$replace = "\t" . "'database' => 'postgres'," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
	
				// replace base url in the autoload.php config file
				$find    = "'ion_auth',";
				$replace = "//'ion_auth',". "\n";
				$this->edit_autoload_file($find, $replace);		
					
				// replace default route in the routes.php config file
				$find    = '$route [\'default_controller\'] =';
				$replace = '$route [\'default_controller\'] = \'' . 'install' . '\';' . "\n";
				$this->install_model->edit_routes_config_file($find, $replace);
			break;
			
			case 2: 
				if (isset($_SESSION['table_creation'])) {
					// empty value and expiration one hour before
					unset($_SESSION['table_creation']);
				}
				if (isset($_SESSION['site_settings'])) {
					// empty value and expiration one hour before
					unset($_SESSION['site_settings']);
				}
				// replace hostname in the database.php config file
				$find    = "'hostname' =>";
				$replace = "\t" . "'hostname' => ''," . "\n";
				
				$this->install_model->edit_database_config_file($find, $replace);
				
				$find    = "'username' =>";
				$replace = "\t" . "'username' => ''," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
				
				// replace password in the database.php config file
				$find    = "'password' =>";
				$replace = "\t" . "'password' => ''," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
				
				// replace base url in the autoload.php config file
				$find    = "'ion_auth',";
				$replace = "//'ion_auth',". "\n";
				$this->edit_autoload_file($find, $replace);
					
				// replace default route in the routes.php config file
				$find    = '$route [\'default_controller\'] =';
				$replace = '$route [\'default_controller\'] = \'' . 'install' . '\';' . "\n";
				$this->install_model->edit_routes_config_file($find, $replace);
			break;
			
			case 3:
				if(isset($_SESSION['site_settings'])) {
					$site_settings = $_SESSION['site_settings'];
					if ($site_settings)
					{
						redirect('install/finish');
					}
				}
				
				if(isset($_SESSION['table_creation'])) {
					$table_creation = $_SESSION['table_creation'];
					if ($table_creation)
					{
						redirect('install/site_settings');
					}
				}
				// replace database name in the database.php config file
				$find    = "'database' =>";
				$replace = "\t" . "'database' => 'postgres'," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
				//replace hook
				$find = '$hook["post_controller_constructor"] = ';
					
				$replace = '$hook["post_controller_constructor"] = array();';
					
				$this->edit_hook_file($find,$replace);
				
				// replace base url in the autoload.php config file
				$find    = "'ion_auth',";
				$replace = "//'ion_auth',". "\n";
				$this->edit_autoload_file($find, $replace);
			break;
			
			case 4:

				if(isset($_SESSION['site_settings'])) {
					$site_settings = $_SESSION['site_settings'];
					if ($site_settings)
					{
						redirect('install/finish');
					}
				}
				if(isset($_SESSION['table_creation'])) {
					$table_creation = $_SESSION['table_creation'];
					if ($table_creation)
					{
						redirect('install/site_settings');
					}
				}
				break;
				
			case 5:
				
				if(isset($_SESSION['site_settings'])) {
					$site_settings = $_SESSION['site_settings'];
					if ($site_settings)
					{
						redirect('install/finish');
					}
				}
				break;
				

		}
	}
	
	/**
	 * create_database function.
	 * 
	 * @access  public
	 * @param   string $database_name
	 * @return  bool
	 */
	public function create_database($database_name) {
		
		$this->load->database();
		$this->load->dbforge();
		$this->load->dbutil();
		
		if ($this->dbutil->database_exists($database_name))
		{
				$find    = "'database' =>";
				$replace = "\t" . "'database' => '" . $database_name . "'," . "\n";
				
				if ($this->edit_database_config_file($find, $replace) === true) {
					return array(
						"status" => true,
						"method" => "OLD",
						"message" => "done"	
					);
				}
		}else{
			if ($this->dbforge->create_database($database_name)) {
				$find    = "'database' => 'postgres'";
				$replace = "\t" . "'database' => '" . $database_name . "'," . "\n";
				
				if ($this->edit_database_config_file($find, $replace) === true) {
					return array(
						"status" => true,
						"method" => "NEW",
						"message" => "done"	
					);
				}
			}
		}
		return array(
						"status" => false,
						"method" => "NULL",
						"message" => "Failed"	
					);
		
	}
	
	/**
	 * create_tables function.
	 * 
	 * @access  public
	 * @param   string $database_name
	 * @return  bool
	 */
	public function create_tables($database_name) {
		
		$this->load->database();
		$this->load->dbforge();
		
		//replace with username provided
		$username = $_SESSION['install_db_username'];
		
		//read file query
		$file_path = FCPATH."sql/dump.sql";
		$file = fopen($file_path,'r');
		$query = fread($file,filesize($file_path));
		fclose($file);
		$query = str_replace("install_host_username",$username,$query);
		
		if($this->db->query($query)){
			
			// replace base url in the autoload.php config file
			$find    = "//'ion_auth',";
			$replace = "'ion_auth',"."\n";
			if ($this->edit_autoload_file($find, $replace) !== true) {
				$data->error = 'The autoload library on your autoload config file cannot be replaced...';
			}
			
			//replace hook
			$replace = '$hook["post_controller_constructor"] = array("class"    => "","function" => "load_config","filename" => "my_config.php","filepath" => "hooks");';
			
			$find = '$hook["post_controller_constructor"] = array();';
			
			$this->edit_hook_file($find,$replace);
			return true;
		}else{
			return false;
		}

	}

	/**
	 * edit_database_config_file function.
	 * 
	 * @access public
	 * @param string $find
	 * @param string $replace
	 * @return bool
	 */
	public function edit_database_config_file($find, $replace, $type = 1) {
		
		if(ENVIRONMENT != '' && $type == 1) {
			$reading = fopen(APPPATH . 'config/'.ENVIRONMENT.'/database.php', 'r');
			$writing = fopen(APPPATH . 'config/'.ENVIRONMENT.'/database.tmp', 'w');
			
		} else {
			$reading = fopen(APPPATH . 'config/database.php', 'r');
			$writing = fopen(APPPATH . 'config/database.tmp', 'w');
		}
		
		$replaced = false;
		
		while (!feof($reading)) {
			
			$line = fgets($reading);
			if (stristr($line, $find)) {
				$line = $replace;
				$replaced = true;
			}
			fputs($writing, $line);
			
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		
		
		if(ENVIRONMENT != '') {
			if ($replaced) {
				rename(APPPATH . 'config/'.ENVIRONMENT.'/database.tmp', APPPATH . 'config/'.ENVIRONMENT.'/database.php');
				return true;
			} else {
				unlink(APPPATH . 'config/'.ENVIRONMENT.'/database.tmp');
				return false;
			}
		} else {
			if ($replaced) {
				rename(APPPATH . 'config/database.tmp', APPPATH . 'config/database.php');
				return true;
			} else {
				unlink(APPPATH . 'config/database.tmp');
				return false;
			}	
		}
	}
	
	/**
	 * edit_main_config_file function.
	 * 
	 * @access public
	 * @param string $find
	 * @param string $replace
	 * @return bool
	 */
	public function edit_main_config_file($find, $replace) {

		if(ENVIRONMENT != '') {
			$reading = fopen(APPPATH . 'config/'.ENVIRONMENT.'/config.php', 'r');
			$writing = fopen(APPPATH . 'config/'.ENVIRONMENT.'/config.tmp', 'w');
		} else {
			$reading = fopen(APPPATH . 'config/config.php', 'r');
			$writing = fopen(APPPATH . 'config/config.tmp', 'w');
		}

		
		
		$replaced = false;

		while (!feof($reading)) {
			
			$line = fgets($reading);

			if (stristr($line, $find)) {
				
				$line = $replace;
				$replaced = true;
			}
			fputs($writing, $line);
			
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if(ENVIRONMENT != '') {
			if ($replaced) {
				rename(APPPATH . 'config/'.ENVIRONMENT.'/config.tmp', APPPATH . 'config/'.ENVIRONMENT.'/config.php');
				return true;
			} else {
				unlink(APPPATH . 'config/'.ENVIRONMENT.'/config.tmp');
				return false;
			}		
		} else {
			if ($replaced) {
				rename(APPPATH . 'config/config.tmp', APPPATH . 'config/config.php');
				return true;
			} else {
				unlink(APPPATH . 'config/config.tmp');
				return false;
			}
		}
		
	}
	
	/**
	 * edit_routes_config_file function.
	 * 
	 * @access public
	 * @param string $find
	 * @param string $replace
	 * @return bool
	 */
	public function edit_routes_config_file($find, $replace) {
		
		$reading = fopen(APPPATH . 'config/routes.php', 'r');
		$writing = fopen(APPPATH . 'config/routes.tmp', 'w');
		
		$replaced = false;
		
		while (!feof($reading)) {
			
			$line = fgets($reading);
			if (stristr($line, $find)) {
				$line = $replace;
				$replaced = true;
			}
			fputs($writing, $line);
			
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if ($replaced) {
			rename(APPPATH . 'config/routes.tmp', APPPATH . 'config/routes.php');
			return true;
		} else {
			unlink(APPPATH . 'config/routes.tmp');
			return false;
		}
		
	}
	
	/**
	 * test_database_connexion function.
	 * 
	 * @access public
	 * @param string $hostname
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public function test_database_connexion($hostname, $username, $password) {
		// create connection
 		$conn = pg_connect("host=$hostname dbname=postgres user=$username password=$password");
		// check connection
		if (!$conn) {
			return false;
		} 
		return true;
		
	}
	
	/**
	 * delete_installation_files function.
	 * 
	 * @access public
	 * @return true ???? MUST FIX
	 */
	public function delete_installation_files() {
		
		$installation_items = array(
			APPPATH . 'controllers/Install.php',
			APPPATH . 'views/install',
			APPPATH . 'models/Install_model.php',
			FCPATH . '/sql'	
		);
		
		foreach ($installation_items as $installation_item) {
			//$this->delete_files($installation_item);
		}
		
		return true;
		
	}
	
	/**
	 * delete_files function.
	 * 
	 * @access private
	 * @param string $target
	 * @return void
	 */
	private function delete_files($target) {
		
		if (is_dir($target)) {
			$files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
			foreach($files as $file) {
				$this->delete_files($file);
			}
			if(file_exists($target) && is_dir($target)) {
				rmdir($target);
			}
		} elseif (is_file($target)) {
			unlink( $target );
		}
		
	}
	
	public function edit_hook_file($find, $replace) {
	
		$reading = fopen(APPPATH . 'config/hooks.php', 'r');
		$writing = fopen(APPPATH . 'config/hooks.tmp', 'w');
	
		$replaced = false;
	
		while (!feof($reading)) {
				
			$line = fgets($reading);
			if (stristr($line, $find)) {
				$line = $replace;
				$replaced = true;
			}
			fputs($writing, $line);
				
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if ($replaced) {
			rename(APPPATH . 'config/hooks.tmp', APPPATH . 'config/hooks.php');
			return true;
		} else {
			unlink(APPPATH . 'config/hooks.tmp');
			return false;
		}
	
	}
	
	public function edit_autoload_file($find,$replace){
		$reading = fopen(APPPATH . 'config/autoload.php', 'r');
		$writing = fopen(APPPATH . 'config/autoload.tmp', 'w');
		
		$replaced = false;
		
		while (!feof($reading)) {
		
			$line = fgets($reading);
			if (stristr($line, $find)) {
				$line = $replace;
				$replaced = true;
			}
			fputs($writing, $line);
		
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if ($replaced) {
			rename(APPPATH . 'config/autoload.tmp', APPPATH . 'config/autoload.php');
			return true;
		} else {
			unlink(APPPATH . 'config/autoload.tmp');
			return false;
		}
	}
	
	public function update_configs() {
		$data = array(
				'UPLOAD_DIR' => FCPATH.'assets/uploads',
				'log_path' => APPPATH.'logs',
				'extension_source_folder' => FCPATH.'extension',
				'extension_manager' => FCPATH.'extension_manager'
		);
		$query = NULL;
		foreach($data as $key => $row) {
			$query = $this->db->where('key', $key)
							->update('setup_config', array('value' => $row));
		}
		
	}
	
	
	public function update_database() {
		if($this->db->query('ALTER DATABASE '.$_COOKIE['db_name'].' SET datestyle TO "SQL, DMY"')){
			// delete the cookie we have created before
			if (isset($_COOKIE['db_name'])) {
				// empty value and expiration one hour before
				setcookie('db_name', '', time() - 3600);
			}
			if (isset($_SESSION['table_creation'])) {
				// empty value and expiration one hour before
				unset($_SESSION['table_creation']);
			}
			if (isset($_SESSION['site_settings'])) {
				// empty value and expiration one hour before
				unset($_SESSION['site_settings']);
			}
			return true;
		} else {
			return false;
		}		
	}
	
	
	
}
