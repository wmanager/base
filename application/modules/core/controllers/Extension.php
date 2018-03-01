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
define ('TMP_DIR', sys_get_temp_dir()); // sys_get_temp_dir() PHP 5 >= 5.2.1
define ('TMP_DIR_PREFIX', 'tmpdir_');
define ('TMP_DIR_SUFFIX', '.d');
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Extension extends Common_Controller {
	
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'extension_model' );
	}
	
	public function index() {
		$data = array ();
		$data ['extensions'] = $this->extension_model->get_extensions ();
		
		$data ['content'] = $this->load->view ( 'extension/list', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}

	public function add() {
		$data = array ();	
		$data ['content'] = $this->load->view ( 'extension/add', $data, true );
		$this->load->view ( 'wmanager/admin_template', $data );
	}
 
	public function add_extention() {
		
		$_POST = json_decode(file_get_contents("php://input"), true);

		if(count($_POST['formData']) > 0) {
			$result = $this->extension_model->insert_extension($_POST['formData']);		
				
			if($result){
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array('result' => 'success', 'message'=>'Extention added successfully.')));
			}
				
		} else {
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('result' => 'failed', 'message'=>'Invalid Request')));
		}		
	} 

	// CREATING THE TEM DERITORY IN SYSTEM TEM FOLDER
	function createTmpDir() {
		$tmpFile = tempnam(TMP_DIR, TMP_DIR_PREFIX);
		$tmpDir = $tmpFile.TMP_DIR_SUFFIX;
		mkdir($tmpDir);
		return $tmpDir;
	}
	
	// REMOVING THE TEM DERITORY FROM SYSTEM TEM FOLDER
	function rmTmpDir($tmpDir) {
		$offsetSuffix = -1 * strlen(TMP_DIR_SUFFIX);
		assert(strcmp(substr($tmpDir, $offsetSuffix), TMP_DIR_SUFFIX) === 0);
		$tmpFile = substr($tmpDir, 0, $offsetSuffix);
	
		// Removes non-empty directory
		$command = "rm -rf $tmpDir/";
		exec($command);
		// rmdir($tmpDir);
	
		unlink($tmpFile);
	}
	
	public function extension_installer($local = NULL){

		ini_set('upload_max_filesize', '10M');
		$return_array  = array();

		if (isset($_FILES['file'])) {	
			// for extention
			$filename = $_FILES['file']['name'];
			$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1);
		
			if(isset($_POST['key'])) {
				$key = $_POST['key'];
	
			// Check for the extion if already installed
			if($local) {
				$ext_installed = $this->extension_model->check_extension_installed($key);
				if($ext_installed) {
					$this->session->set_flashdata ( 'message', array('result' => 'failed', 'message'=>'Failed to install extention.'));
					redirect ( "core/extension" );
				}
			}

			$key_file = $key.'.zip';
			if($key_file == $filename) {
				if($ext == '.zip') {
					$tempdir = $this->createTmpDir();		
					
					
					if(move_uploaded_file($_FILES['file']['tmp_name'], $tempdir.'/'.$_FILES['file']['name'])) {
						if(isset($_POST['key']) && !empty($_POST['key'])) {
							$installed_result = false;
							$folder = $tempdir;
						
							$zip_path = $folder.'/'.$filename;

							chmod($zip_path, 0777);
							$zip = new ZipArchive();
							$x = $zip->open($zip_path);

							if ($x === true) {
								//extract file to same folder
								$zip->extractTo($folder);
								$zip->close();
								$installed_result = true;
								$this->extension_model->add_install_log($key,"UNZIP","Unzipped Files","SUCCESS");
							}else{
								$installed_result = false;
								$this->extension_model->add_install_log($key,"UNZIP","File Issue","FAILED");
							}
								
							$ext_folder = substr($filename, 0, strrpos($filename, "."));
								
							$source_folder = $tempdir."/".$ext_folder."/";
								
								
							//Step2: read instruction
							$file_path = $tempdir."/".$ext_folder."/importer.txt";
							if(file_exists($file_path)){
								$installed_result = true;
								$this->extension_model->add_install_log($key,"READ_INSTRUCTION","Read File successfully","SUCCESS");
								$file = fopen($file_path,'r');
								$inport_string = fread($file,filesize($file_path));
								fclose($file);
							}else{
								$installed_result = false;
								$this->extension_model->add_install_log($key,"READ_INSTRUCTION","Failed to read instruction file","FAILED");
							}
								
							if(count($inport_string) == 0){
								$installed_result = false;
								$this->extension_model->add_install_log($key,"READ_INSTRUCTION","Instruction file empty","FAILED");
							}
								
							//Step3: instruction decode
							$instruction_array = $this->decode_instruction($inport_string);
							if(count($instruction_array)==0){
								$installed_result = false;
								$this->extension_model->add_install_log($key,"DECODE_INSTRUCTION","Instruction Decode fail","FAILED");
							}else{
								$this->extension_model->add_install_log($key,"DECODE_INSTRUCTION",serialize($instruction_array),"SUCCESS");
							}
							
							if($installed_result) {
								//step4: execute instruction
								$execution_result = $this->execute_instructions($instruction_array,$source_folder,$key);
								
								$extension_install_array = array(
										'name' => $_POST['name'],
										'key' => $_POST['key'],
										'file_name' => 'file_name'
								);
								$result = $this->extension_model->insert_extension($extension_install_array);
								//step5 : updating the extention table
								$result = $this->extension_model->updated_extension_details($key);
								
								if($local) {
									$this->session->set_flashdata ( 'message', array('result' => 'success', 'message'=>'Extention installed successfully'));
									redirect ( "core/extension" );
								}
								$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array('result' => 'success', 'message'=>'Extention installed successfully')));
							} else {
								if($local) {
									$this->session->set_flashdata ( 'message', array('result' => 'failed', 'message'=>'Failed to install extention'));
									redirect ( "core/extension" );
								}
								$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array('result' => 'failed', 'message'=>'Failed to install extention')));
							}
							
						} else {
							if($local) {
								$this->session->set_flashdata ( 'message', array('result' => 'failed', 'message'=>'Failed to install extention'));
								redirect ( "core/extension" );
							}
							$this->output
							->set_content_type('application/json')
							->set_output(json_encode(array('result' => 'failed', 'message'=>'Failed to install extention')));
						}
						$this->rmTmpDir($tempdir);
					}
				
				} else {
					if($local) {
						$this->session->set_flashdata ( 'message', array('result' => 'failed', 'message'=>'Invalid file'));
						redirect ( "core/extension" );
					}
					$this->output
					->set_content_type('application/json')
					->set_output(json_encode(array('result' => 'failed', 'message'=>'Invalid file')));
				}
			} else {
				if($local) {
					$this->session->set_flashdata ( 'message', array('result' => 'failed', 'message'=>'Invalid file'));
					redirect ( "core/extension" );
				}
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array('result' => 'failed', 'message'=>'Invalid file')));	
			}
			} else {
				if($local) {
					$this->session->set_flashdata ( 'message', array('result' => 'failed', 'message'=>'Invalid file'));
					redirect ( "core/extension" );
				}
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array('result' => 'failed', 'message'=>'Invalid file')));
			}
		} 		
	}
	
	public function decode_instruction($inport_string){
	
		//Step 1: Separate all instruction
		$instructions = explode("\n",$inport_string);
	
	
		//Step 2: Clean Instructions
		if(count($instructions)>0 && is_array($instructions)){
			$instruction_array = array();
			foreach($instructions as $item){
				if($item != ''){
					//trim brackets
					$temp = trim($item,'(');
					$temp = trim($temp,')');
						
					
					//form array
					$temp_array = explode(",",$temp);
					$instruction_array[] = $temp_array;
				}
			}
		}
	
		return $instruction_array;
	}
	
	public function execute_instructions($instruction_array,$source_folder,$key){
		
		if(!is_array($instruction_array) || count($instruction_array)==0){
			return false;
		}
		
		$execution_result = array();
		foreach ($instruction_array as $item){
			switch($item[0]){
				case "new": $execution_result[] = $this->import_new_file($item,$source_folder);
							break;
					
				case "sql": $execution_result[] = $this->execute_sql($item,$source_folder, $key);
							break;
							
				case "mkdir": $execution_result[] = $this->new_dir($item);
							break;
				
				case "copy_dir": $src = $source_folder.$item[1];									
								 $dst = FCPATH.$item[2];
								 $this->copy_dir($src,$dst);
								 break;
								 
				 case "cron_file": $src = $source_folder.$item[2];
				 $dst = APPPATH.'controllers/cron/'.$item[1];
				 $this->copy_cron_file($src,$dst);
				 break;								 
				default: break;
			}
		}
	
		return $execution_result;
	}
	
	public function new_dir($item){
		
		if($item[1] == ''){
			return array(
					"Instruction_type:"=>$item[0]."-".$item[1],
					"Status" => "FAILED",
					"dest_path" => $item[2],
					"message" => "folder name not exist"
			);
		}
		
		$folder_path = FCPATH.$item[2].$item[1];
		
		if(is_dir($folder_path)){
			
			return array(
					"Instruction_type:"=>$item[0]."-".$item[1],
					"Status" => "FAILED",
					"dest_path" => $item[2],
					"message" => "folder already exists"
			);
		}else{
			
			mkdir($folder_path);
			
			if(is_dir($folder_path)){
				chmod($folder_path,0777);
				return array(
						"Instruction_type:"=>$item[0]."-".$item[1],
						"Status" => "SUCCESS",
						"dest_path" => $item[2],
						"message" => "folder Created Successfully"
				);
			}
		}
		
	}
	
	public function import_new_file($file=array(),$source_folder){
	
		$dest_path = FCPATH.$file[2].$file[4];
		$src_path = $source_folder.$file[3].$file[4];
		
		copy($src_path, $dest_path);
		if(file_exists($dest_path)){			
			return array(
					"Instruction_type:"=>$file[0]."-".$file[1],
					"Status" => "Success",
					"dest_path" => $dest_path
			);
		}else{
			return array(
					"Instruction_type:"=>$file[0]."-".$file[1],
					"Status" => "FAILED",
					"dest_path" => $src_path
			);
		}
	
	}
	
	public function execute_sql($instruction,$source_folder, $key){
		 
		if(!is_array($instruction) || count($instruction)==0){
			return array(
					"Instruction_type:"=>$instruction[0]."-".$instruction[2],
					"Status" => "FAILED",
					"dest_path" => $instruction[1],
					"message" => "Instruction not Received properly"
			);
		}
		
		//check SQL file exists
		$file_path = $source_folder.$instruction[2].$instruction[1];
		
		if(!file_exists($file_path)){
			return array(
					"Instruction_type:"=>$instruction[0]."-".$instruction[2],
					"Status" => "FAILED",
					"dest_path" => $instruction[1],
					"message" => "File Not Found"
			);
		}else{
			$file = fopen($file_path,'r');
			$query = fread($file,filesize($file_path));
			fclose($file);
			
			if($query == ''){
				return array(
						"Instruction_type:"=>$instruction[0]."-".$instruction[2],
						"Status" => "FAILED",
						"dest_path" => $instruction[1],
						"message" => "SQL not found"
				);
			}else{
				$execute = $this->extension_model->execute_query($query);
				if($execute){
					$this->extension_model->add_install_log($key,"SQL_EXECUTION","Sql added successfully","SUCCESS");
					return array(
							"Instruction_type:" => $instruction[0]."-".$instruction[2],
							"Status" => "SUCCESS",
							"dest_path" => $instruction[1],
							"message" => "SQL Added Successfully"
					);

				}else{
					$this->extension_model->add_install_log($key,"SQL_EXECUTION","Sql execution failed","FAILED");
					return array(
							"Instruction_type:"=>$instruction[0]."-".$instruction[2],
							"Status" => "FAILED",
							"dest_path" => $instruction[1],
							"message" => "SQL Failed"
					);
					
				}
			}
		}
	}
	
	public function copy_dir($src,$dst)
	{	
	    if (is_dir($src)) {
	        mkdir($dst);
	        chmod($dst,0777);
	        foreach (scandir($src) as $file) {
	            if ($file != '.' && $file != '..') {
	            		$this->copy_dir("$src/$file", "$dst/$file");
	            }
	        }
	    } elseif (is_file($src)) {
	        copy($src, $dst);
	    }

	}

	public function copy_cron_file($src,$dst) {
		if (is_file($src)) {
			copy($src, $dst);
		}
	}

	public function get_all_extention() {
		echo json_encode($this->extension_model->get_extensions ());
	}
	

}	