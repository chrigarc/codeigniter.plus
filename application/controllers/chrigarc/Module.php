<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Module extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('chrigarc/Module_model', 'module', true);
	}

	public function index()
	{
		$page = $this->input->get('page') ?: '1';
		$perPage = $this->input->get('perPage') ? : '10';
		$sort = $this->input->get('sort') ? : 'asc';
		$field = $this->input->get('field')? : 'id';
		$result = $this->module->paginate($page, $perPage, $field, $sort);
		$result = json_encode($result);
		$this->output->set_content_type('application/json')->set_output($result);
	}

	public function show($uuid)
	{
		$module = $this->module->find_uuid_or_fail($uuid);
		$result = json_encode($module);
		$this->output->set_content_type('application/json')->set_output($result);
	}

	public function store()
	{
		$this->load->library('form_validation');
		if ($this->form_validation->run('module.store') == FALSE)
		{
			$this->output->set_content_type('application/json')->set_status_header(403)->set_output(json_encode(array(
				'status' => false,
				'errors' => $this->form_validation->error_array()
			)));
		}else{
			$data = $this->input->post(array('name', 'description', 'active'), TRUE);
			$module = $this->module->first_or_create($data, true);
			$result = json_encode($module);
			$this->output->set_content_type('application/json')->set_output($result);
		}
	}

	public function update($uuid)
	{
		$this->load->library('form_validation');
		$module = $this->module->find_uuid_or_fail($uuid);
		if ($this->form_validation->run('module.update') == FALSE)
		{
			$this->output->set_content_type('application/json')->set_status_header(403)->set_output(json_encode(array(
				'status' => false,
				'errors' => $this->form_validation->error_array()
			)));
		}else{
			$data = $this->input->post(array('name', 'description', 'active'), TRUE);
			$this->module->update($module->id, $data);
			$result = json_encode(array('status' => true, 'message' => 'OK'));
			$this->output->set_content_type('application/json')->set_output($result);
		}
	}

	public function destroy($uuid)
	{
		$module = $this->module->find_uuid_or_fail($uuid);
		$this->module->delete($module->id);
		$result = json_encode(array('status' => true, 'message' => 'OK'));
		$this->output->set_content_type('application/json')->set_output($result);
	}
}
