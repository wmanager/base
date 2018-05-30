<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_description extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('activity');
		$this->load->model('thread');
	}
	
	
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		if($this->input->post()){
			$data = array();
			//$update['title'] = $this->input->post('title');
			$update['description'] = $this->input->post('description');
			$this->db->where('id',$this->input->post('activity'))->update('activities',$update);
			//$data['RESULT_NOTE'] = $this->input->post('result_note');
		}
		if($this->input->post('status') == 'DONE') $data['RESULT'] = 'OK';
		$data['STATUS'] = $this->input->post('status');
		
		$this->actions->update_var('ACTIVITY',$this->input->post('activity'),'ACTIVITY',$this->input->post('activity'),$data,NULL);

		$result = true;
		$message = '';
		
		$return = array(
			'result' => $result,
			'error' => $message
		);
		$this->output
    	->set_content_type('application/json')
    	->set_output(json_encode($return));
	}
}