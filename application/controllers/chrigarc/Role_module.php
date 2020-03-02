<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Role_module extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Role_module_model', 'role_module', true);
		$this->load->library('form_validation');
	}

	public function store()
	{
		if ($this->form_validation->run('role_module.store') == FALSE) {
			$this->output->set_content_type('application/json')->set_status_header(403)->set_output(json_encode(array(
				'status' => false,
				'errors' => $this->form_validation->error_array()
			)));
		}else{
			$data = $this->input->post(array('role_id', 'module_id', 'active'), TRUE);
			$result = $this->role_module->first_or_create($data);
			$result = json_encode($result);
			$this->output->set_content_type('application/json')->set_output($result);
		}
	}

	public function update()
	{

	}

	public function destroy()
	{

	}

}
