<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('chrigarc/Role_model', 'role', true);
	}

	public function index()
	{
		$page = $this->input->get('page') ?: '1';
		$perPage = $this->input->get('perPage') ? : '10';
		$sort = $this->input->get('sort') ? : 'asc';
		$field = $this->input->get('field')? : 'id';
		$result = $this->role->paginate($page, $perPage, $field, $sort);
		$result = json_encode($result);
		$this->output->set_content_type('application/json')->set_output($result);
	}

	public function show($uuid)
	{
		$role = $this->role->find_uuid_or_fail($uuid);
		$result = json_encode($role);
		$this->output->set_content_type('application/json')->set_output($result);
	}

	public function store()
	{
		$this->load->library('form_validation');
		if ($this->form_validation->run('role.store') == FALSE)
		{
			$this->output->set_content_type('application/json')->set_status_header(403)->set_output(json_encode(array(
				'status' => false,
				'errors' => $this->form_validation->error_array()
			)));
		}else{
			$data = $this->input->post(array('name', 'description', 'active'), TRUE);
			$role = $this->role->first_or_create($data, true);
			$result = json_encode($role);
			$this->output->set_content_type('application/json')->set_output($result);
		}
	}

	public function update($uuid)
	{
		$this->load->library('form_validation');
		$role = $this->role->find_uuid_or_fail($uuid);
		if ($this->form_validation->run('role.update') == FALSE)
		{
			$this->output->set_content_type('application/json')->set_status_header(403)->set_output(json_encode(array(
				'status' => false,
				'errors' => $this->form_validation->error_array()
			)));
		}else{
			$data = $this->input->post(array('name', 'description', 'active'), TRUE);
			$this->role->update($role->id, $data);
			$result = json_encode(array('status' => true, 'message' => 'OK'));
			$this->output->set_content_type('application/json')->set_output($result);
		}
	}

	public function destroy($uuid)
	{
		$role = $this->role->find_uuid_or_fail($uuid);
		$this->role->delete($role->id);
		$result = json_encode(array('status' => true, 'message' => 'OK'));
		$this->output->set_content_type('application/json')->set_output($result);
	}
}
