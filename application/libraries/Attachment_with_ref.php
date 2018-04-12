<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attachment_with_ref {

	public function upload($id,$key)
	{
		$CI =& get_instance();
		$CI->load->model('attachment_ref_model');
		//$CI->load->model('attachment');
		
		$data_post = $_POST;
		$attachment_id = $CI->attachment_ref_model->add_attachment($id,$key);
		
		//Get the account ID.
		$account_id = $_POST['client_id'];
		
		if($attachment_id && $attachment_id > 0)
		{
			$upload_path = $CI->config->item('UPLOAD_DIR');
			$config['upload_path'] =  $upload_path.'/'.$account_id.'/'.$attachment_id;
			$attach_config = $CI->attachment_ref_model->get_attach_conf($_POST['attach_type']);
				
		
			if(isset($attach_config->exts)){
				$config['allowed_types'] = str_replace(' ','',str_replace(',','|',$attach_config->exts));
			}
			if(isset($attach_config->max_size)){
				$config['max_size']	= $attach_config->max_size;
			}
		
			$CI->load->library('upload', $config);
			if (!is_dir($upload_path.'/'.$account_id.'/'.$attachment_id))
				mkdir($upload_path.'/'.$account_id.'/'.$attachment_id, 0777, true);
					
				if ( ! $CI->upload->do_upload())
				{
					$CI->attachment_ref_model->delete_attachment_record($attachment_id);
					return array(
						"status" => false,
						"error" => strip_tags($CI->upload->display_errors()),
						'message' => 'Failed to upload attachment'	
					);
				}
				else
				{
					$data = $CI->upload->data();					
					$file_name = $data['file_name'];
					$file_path = '/'.$account_id.'/'.$attachment_id.'/';
					$result['result'] = $CI->attachment_ref_model->update_attachment($file_name,$file_path,$attachment_id);
					
					return array(
							"status" => true,
							"attachment_id" => $attachment_id,
							'message' => 'Upload was successfull'
					);
				}
		}else{
			return array(
						"status" => false,
						"error" => 'Database error',
						'message' => 'Failed to upload attachment'	
					);
		}
	}
	
	public function get_last_attach($key,$id){
		$CI =& get_instance();
		$CI->load->model('attachment_ref_model');
		
		$data = $CI->attachment_ref_model->get_files_list($key,$id);
		
		if(!empty($data) && count($data)>0){
			foreach ($data as $item){
				return $item;
			}
		}else{
			return array();
		}
		
	}
	
}	