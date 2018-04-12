<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attachment_ref_model extends CI_Model
{
	public function add_attachment($id,$key)
	{
		$post_data = $_POST;
		$file_data = $_FILES['userfile'];
		
		$data_attachments['client_id'] = $post_data['client_id'];
		$data_attachments['be_id'] = $post_data['be_id'];
		$data_attachments['description'] = ($post_data['description'])?$post_data['description']:'';	
		$data_attachments['entity_key'] = $key;
		$data_attachments['entity_id'] = $id;		
		$data_attachments['attach_type'] = $post_data['attach_type'];
		$data_attachments['created_by'] = $this->ion_auth->user()->row()->id;
		$data_attachments['created'] = date("d-m-Y H:i:s");

		if($this->db->insert('attachments',$data_attachments)){
			log_message('DEBUG',$this->db->last_query());
			$insert_id = $this->db->insert_id();
			return $insert_id;
		} else {
			log_message('ERROR',$this->db->last_query());
			return false;
		}
	}
	
	public function update_attachment($file_name,$file_path,$attachment_id){
		$data_attachments['filename'] = $file_name;
		$encode_name = base64_encode($file_name);
		$data_attachments['url'] = $file_path.$encode_name;
		
		if($this->db->where('id',$attachment_id)->update('attachments',$data_attachments)){
			log_message('DEBUG',$this->db->last_query());
			return true;
		} else {
			log_message('ERROR',$this->db->last_query());
			return false;
		}
	}
	
	public function get_attach_conf($attach_id){
		$query = $this->db->select('exts,max_size')->where('id',$attach_id)->get('setup_attachments');
		return $query->row();
	}
	
	public function delete_attachment_record($attachment_id){
		if($this->db->where('id',$attachment_id)->delete('attachments')){
			log_message('DEBUG',$this->db->last_query());
			$this->session->set_flashdata('growl_success', ' Deleted Sucessfully');
			return true;
		} else {
			log_message('ERROR',$this->db->last_query());
			$this->session->set_flashdata('growl_error', 'Error');
			return false;
		}
	}
	
	public function get_files_list($key,$id){

		if($key == '' || $id == ''){
			return array();
		}
		
		//GET THE THREAD RELATED ACTIVITY	
		if($key == 'THREAD') {
			$query1 = $this->db->select ( 'activities.id' )
						->join ( 'threads', 'threads.id=activities.id_thread' )
						->where ( 'id_thread', $id )
						->order_by ( "activities.created" )
						->get ( 'activities' );
			$result1 = $query1->result_array ();
			$act_id = array();
			if(count($result1) > 0) {
				foreach($result1 as $row) {
					array_push($act_id, $row['id']);
				}
			}
			$this->db->where('entity_key','ACTIVITY');
			$this->db->where_in('entity_id',$act_id);
			$this->db->or_where('entity_key','THREAD');
			$this->db->or_where('entity_id',$id);
		} else {
			$this->db->where('entity_key',$key);
			$this->db->where('entity_id',$id);
		}
		
		$query = $this->db->select('attachments.*, setup_attachments.title as attachment_type, users.first_name, users.last_name')
		->join('setup_attachments','setup_attachments.id = attachments.attach_type')
		->join('users','users.id = attachments.created_by')
		->order_by('attachments.id','DESC')
		->get('attachments');
		$result = $query->result();
		
		foreach($result as $key => $item){
			$link = '/common/new_attachment/download_file/'.crypt_params($item->id).'/'.$item->entity_key.'/'.crypt_params($item->entity_id);
			$result[$key]->link = $link;
		}
		
		return $result;
	}
	
	public function get_single_attachment($id){
		$query = $this->db->where('id',$id)->get('attachments');
		return $query->row();
	}
	
	public function get_setup_attach_list($key,$id=NULL){

		$return = array();
		if($key == 'ACTIVITY' || $key == 'THREAD'){
			
			if($key == 'THREAD') {
				// GET THE FORM ID BASED ON THE THREAD ID
				$query1 = $this->db->select('activities.form_id')
				->where("activities.id_thread",$id)
				->get('activities');
			} else {
				// GET THE FORM ID BASED ON THE ACTIVITY ID
				$query1 = $this->db->select('activities.form_id')
				->where("activities.id",$id)
				->get('activities');
			}
			

			$result = $query1->row_array();
			$form_id = $result['form_id'];
			
			// CHECK IF COLLECTION IS SET FOR THE FORM 			
			$query2 = $this->db->where ( 'form_id', $form_id )->get ( 'setup_forms_collections' );
			if($query2->num_rows() > 0) {
 				$query3 = $this->db->select('setup_forms_collections.id_plico')
				->where("setup_forms_collections.form_id = $form_id")
				->get('setup_forms_collections');
				$collection_id = $query3->row_array();
				$collection_id = $collection_id['id_plico'];
	
				$query4 = $this->db->select('setup_collections_files.id_attachment')
				->join("setup_collections_list","setup_collections_list.id = setup_collections_files.id_plico")
				->where("setup_collections_files.id_plico IN ($collection_id)")
				->get('setup_collections_files');

				$attachment = $query4->result_array();
				
				$result_array =array();

				if(count($attachment) > 0) {
					foreach($attachment as $att_id) {
						$id_att = $att_id['id_attachment'];
						$query = $this->db->select('setup_attachments.*')						
							->where("setup_attachments.id IN ($id_att)")
							->get('setup_attachments');
							$num_rows = $query->num_rows();
							if($num_rows) {
								$res = $query->result_array();
								foreach ($res as $val) {
									array_push($result_array, $val);									
								}
								$result_array = array_unique($result_array, SORT_REGULAR);
								
							}
					}
					
					if(count($result_array) > 1){
						$return['setup_attach_hidden']	=  FALSE;
					}else{
						$return['setup_attach_hidden']	=  TRUE;
					}
						
					if($num_rows > 0){
						$return['setup_attach_hidden_value'] = $query->row()->id;
						$return['setup_attach_list'] = $result_array;
					}else{
						$return['setup_attach_hidden_value'] = '';
						$return['setup_attach_list'] = array();
					}
					
					
				}
			} else {
				$query5 = $this->db->select ( 'l.*, a.title,a.id as attach_id' )->join ( 'setup_attachments a', 'a.id = l.id_attachment', 'left' )->where ( 'l.form_id', $form_id )->get ( 'setup_forms_attachments l' );
				$result_array = $query5->result ();
				$num_rows = $query5->num_rows();		
				if(count($result_array) > 1){
					$return['setup_attach_hidden']	=  FALSE;
				}else{
					$return['setup_attach_hidden']	=  TRUE;
				}
					
				if($num_rows > 0){
					$return['setup_attach_hidden_value'] = $query5->row()->id;
					$return['setup_attach_list'] = $result_array;
				}else{
					$return['setup_attach_hidden_value'] = '';
					$return['setup_attach_list'] = array();
				}
				
			}
			
		}else{
			$query = $this->db->select('setup_attachments.*')
							->join('list_ambits','list_ambits.id = setup_attachments.ambit_id')
							->where("list_ambits.entity_key = '$key'")
							->get('setup_attachments');
			
			$return   = array();
			$num_rows = $query->num_rows();
			
			if($num_rows > 1){
				$return['setup_attach_hidden']	=  FALSE;
			}else{
				$return['setup_attach_hidden']	=  TRUE;
			}
			
			if($num_rows > 0){
				$return['setup_attach_hidden_value'] = $query->row()->id;
				$return['setup_attach_list'] = $query->result();
			}else{
				$return['setup_attach_hidden_value'] = '';
				$return['setup_attach_list'] = array();
			}
		}
		
		
		return $return;
	}
	
	public function delete_file($attachment_id)
	{
		$query = $this->db->where('id',$attachment_id)->get('attachments');
		$data =  $query->row();
		if(count($data)>0){
			$res = $this->delete_attachment($attachment_id,$data->client_id, $data->filename);
			if($res)
				return true;
			else 
				return false;
		} else {		
			return false;
		}
	}
	
	public function delete_attachment($attachment_id,$account_id, $filename)
	{

		$upload_path = $this->config->item('UPLOAD_DIR');
		$path = $upload_path.'/'.$account_id.'/'.$attachment_id.'/'.$filename;
	
		unlink($path);
	
		if ($this->is_dir_empty($path)) {
			rmdir($path);
		}
		if ($this->is_dir_empty($upload_path.'/'.$account_id.'/'.$attachment_id)) {
			rmdir($upload_path.'/'.$account_id.'/'.$attachment_id);
		}
		if ($this->is_dir_empty($upload_path.'/'.$account_id)) {
			rmdir($upload_path.'/'.$account_id);
		}
	
		return $this->db->where('id',$attachment_id)->delete('attachments');
	}
	
	private function is_dir_empty($dir) {
		if (!is_readable($dir)) return NULL;
		$handle = opendir($dir);
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				return FALSE;
			}
		}
		return TRUE;
	}
		
	public function get_account_id($id){		
		$query = $this->db->select('customer_id as account_id')->where('id',$id)->get('orders');
		return $query->row()->account_id;
	}
	
}