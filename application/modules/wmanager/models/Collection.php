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

class Collection extends CI_Model
{
	public function get($limit, $offset = 0)
	{
		$query = $this->db->distinct('id')->limit($limit,$offset)->order_by('id','desc')->get('setup_collections_list');
		return $query->result();
	}

	public function total()
	{
		$query = $this->db->get('setup_collections_list');
		return $query->num_rows();
	}
	
	public function get_attachments()
	{
		$query = $this->db->where('disabled','f')->get('setup_attachments');
		return $query->result();
	}
	
	public function add()
	{
		$data = $this->input->post();
		$collection_data['title'] = $data['title'];
		$collection_data['description'] = $data['description'];
		$collection_data['created_by'] = $this->ion_auth->user()->row()->id;
		$collection_data['modified_by'] = $this->ion_auth->user()->row()->id;
	
		if($this->db->insert('setup_collections_list',$collection_data)){
			log_message('DEBUG',$this->db->last_query());
			$collection_id = $this->db->insert_id();
			/** INSERT ATTACHMETS TO setup_collections_files **/
			
			$attach_check = $this->insert_attachment($data['attach'],$collection_id);
			
			$this->session->set_flashdata('growl_success', ' è stata inserita correttamente.');
			return true;
		} else {
			log_message('ERROR',$this->db->last_query());
			$this->session->set_flashdata('growl_error', 'Si è verificato un errore, preghiamo di riprovare.');
			return false;
		}
	}
	
	public function get_single($id)
	{
		$query = $this->db->where('id',$id)->get('setup_collections_list');
		return $query->row();
	}
        public function get_collection_attachments($id)
	{
		$query = $this->db->where('id_plico',$id)->get('setup_collections_files');
		return $query->row();
	}
        
	
	public function edit($id)
	{
		$data = $this->input->post();
		$collection_data['title'] = $data['title'];
		$collection_data['description'] = $data['description'];
		$collection_data['modified_by'] = $this->ion_auth->user()->row()->id;
		$collection_data['modified'] = date('d/m/Y H:i:s');
		
		if($this->db->where('id',$id)->update('setup_collections_list',$collection_data)){
			log_message('DEBUG',$this->db->last_query());
			/** INSERT ATTACHMETS TO setup_collections_files **/
				
			$attach_check = $this->update_attachment($data['attach'],$id);
			
			$this->session->set_flashdata('growl_success', ' Record has been updated correctly.');
			return true;
		} else {
			log_message('ERROR',$this->db->last_query());
			$this->session->set_flashdata('growl_error', 'There was an error, please try again.');
			return false;
		}
	}
	
	public function delete($id)
	{
		$data = $this->get_single($id);
		if(count($data)>0){
	
			if($this->db->where('id',$id)->delete('setup_collections_list')){
				log_message('DEBUG',$this->db->last_query());
				
				/** DELETE setup_collections_files entry **/
				$this->delete_attachment($id);
				
				$this->session->set_flashdata('growl_success', 'The company '.$data->title.' has been removed.');
				return true;
			} else {
				log_message('ERROR',$this->db->last_query());
				$this->session->set_flashdata('growl_error', 'There was an error, it could not be removed'.$data->title.', please try again.');
				return false;
			}
		} else {
			log_message('ERROR',$this->db->last_query());
			$this->session->set_flashdata('growl_error', 'You do not have sufficient permissions to remove this record.');
			return false;
		}
	}
	
	public function insert_attachment($attachments, $collection_id){
		if(count($attachments) >0){
			$id_attachments = implode(",",$attachments);
			$insert_attachment['id_attachment'] = $id_attachments;
		}
		
		$insert_attachment['id_plico'] = $collection_id;
		$insert_attachment['created_by'] = $this->ion_auth->user()->row()->id;
		$insert_attachment['modified_by'] = $this->ion_auth->user()->row()->id;
		if($this->db->insert('setup_collections_files',$insert_attachment)){
			log_message('DEBUG',$this->db->last_query());
			return true;
		} else {
			log_message('ERROR',$this->db->last_query());
			return false;
		}
	}
	
	public function update_attachment($attachments, $collection_id){
		
		$query = $this->db->where('id_plico',$collection_id)->get('setup_collections_files');
		if($query->num_rows() >0){
			if(count($attachments) >0){
				$id_attachments = implode(",",$attachments);
			}else{
				$id_attachments = NULL;
			}
			
			$insert_attachment['id_attachment'] = $id_attachments;
			$insert_attachment['modified_by'] = $this->ion_auth->user()->row()->id;
			$insert_attachment['modified'] = date('d/m/Y H:i:s');
			if($this->db->where('id_plico',$collection_id)->update('setup_collections_files',$insert_attachment)){
				log_message('DEBUG',$this->db->last_query());
				return true;
			} else {
				log_message('ERROR',$this->db->last_query());
				return false;
			}
		}else{
			$this->insert_attachment($attachments, $collection_id);
			return true;
		}
	}
	
	public function delete_attachment($collection_id){
		$query = $this->db->where('id_plico',$collection_id)->get('setup_collections_files');
		if($query->num_rows() >0){
			if($this->db->where('id_plico',$collection_id)->delete('setup_collections_files')){
				log_message('DEBUG',$this->db->last_query());
				return true;
			}else{
				log_message('ERROR',$this->db->last_query());
				return false;
			}
		}
	}
	
}