<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_attachment extends Common_Controller {
	
	public function __construct()
	{
		parent::__construct();
		// LOAD HERE REQUIRED LIBRARIES/HELPERS
		$this->load->model('attachment_ref_model');
		$this->load->library('attachment_with_ref');
	}
	

	public function attachment_list($ref_id, $ref_key){
		$return = array();
		
		//files details
		$return['id'] 			= $ref_id;
		$return['key'] 			= $ref_key;
		$return['file_list'] 	= $this->attachment_ref_model->get_files_list($ref_key,$ref_id);
		
		//setup details
		$setup_list = $this->attachment_ref_model->get_setup_attach_list($ref_key,$ref_id);

		
		$return['setup_attach_hidden'] 		 = $setup_list['setup_attach_hidden'];
		$return['setup_attach_hidden_value'] = $setup_list['setup_attach_hidden_value'];
		$return['setup_attach_list'] 		 = $setup_list['setup_attach_list'];
		
		$this->output->set_content_type('application/json')
					 ->set_output(json_encode($return));
	}

	public function load_attament_template() {
		echo $this->load->view('common/attachment/add');
	}
	
	public function upload($key,$id){
		if($_POST['client_id'] != '' && $_POST['be_id'] != '' && $id != '' && $key != '' && $_POST['attach_type'] != ''){
			$upload = $this->attachment_with_ref->upload($id,$key);
		}else{
			$upload['status'] = false;
			$upload['error'] = 'Few required values are missing for uploading the file.';
		}
		
		$upload['ref_id'] = $id;
		$upload['ref_key'] = $key;
		$this->output->set_content_type('application/json')
					 ->set_output(json_encode($upload));
	}
	
	public function delete_file($attachment_id,$key, $id)
	{
		$result['result'] = $this->attachment_ref_model->delete_file($attachment_id);
		$this->output->set_content_type('application/json')->set_output(json_encode($result));
	}
	
	public function download_file($attachment_id,$key,$id)
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$this->load->library('encrypt');
		$attachment_id = $this->encrypt->decode($attachment_id);
		$this->load->helper('download');
		$data = $this->attachment_ref_model->get_single_attachment($attachment_id);
		$account_id = $data->client_id;
		$filename = $data->filename;
		$upload_path = $this->config->item('UPLOAD_DIR');
		$path = $upload_path.$account_id.'/'.$attachment_id.'/'.$filename;
	
		$data = file_get_contents($path); // Read the file's contents
		force_download($filename, $data);
	}

}