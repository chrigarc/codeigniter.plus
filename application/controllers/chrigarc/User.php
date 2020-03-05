<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('chrigarc/User_model', 'user', true);
	}

	public function index()
	{
		$page = $this->input->get('page') ?: '1';
		$perPage = $this->input->get('perPage') ?: '10';
		$sort = $this->input->get('sort') ?: 'asc';
		$field = $this->input->get('field') ?: 'id';
		$result = $this->user->paginate($page, $perPage, $field, $sort);
		$result = json_encode($result);
		$this->output->set_content_type('application/json')->set_output($result);
	}

	public function show($uuid)
	{
		try {
			$user = $this->user->find_uuid_or_fail($uuid);
			$result = json_encode($user);
			$this->output->set_content_type('application/json')->set_output($result);
		} catch (Exception $ex) {
			$this->output->set_content_type('application/json')
				->set_status_header(404)
				->set_output(json_encode(array(
					'message' => $ex->getMessage()
				)));
		}
	}

	public function store()
	{
		$this->load->library('form_validation');
		if ($this->form_validation->run('user.store') == FALSE) {
			$this->output->set_content_type('application/json')->set_status_header(403)->set_output(json_encode(array(
				'status' => false,
				'errors' => $this->form_validation->error_array()
			)));
		} else {
			$data = $this->input->post(array('email', 'name', 'password', 'last_name', 'other_name'), TRUE);
			$user = $this->user->first_or_create($data, true);
			$result = json_encode($user);
			$this->output->set_content_type('application/json')->set_output($result);
		}
	}

	public function update($uuid)
	{
		$this->load->library('form_validation');
		$user = $this->user->find_uuid_or_fail($uuid);
		if ($this->form_validation->run('user.update') == FALSE) {
			$this->output->set_content_type('application/json')->set_status_header(403)->set_output(json_encode(array(
				'status' => false,
				'errors' => $this->form_validation->error_array()
			)));
		} else {
			$data = $this->input->post(array('email', 'name', 'password', 'last_name', 'other_name'), TRUE);
			$this->user->update($user->id, $data);
			$result = json_encode(array('status' => true, 'message' => 'OK'));
			$this->output->set_content_type('application/json')->set_output($result);
		}
	}

	public function destroy($uuid)
	{
		$user = $this->user->find_uuid_or_fail($uuid);
		$this->user->delete($user->id);
		$result = json_encode(array('status' => true, 'message' => 'OK'));
		$this->output->set_content_type('application/json')->set_output($result);
	}

	public function load_back_office()
	{
		$this->load->library('chrigarc/Permission');
		$this->load->model('chrigarc/Module_model', 'module', true);
		$this->load->model('chrigarc/Role_model', 'role', true);
		$this->load->model('chrigarc/User_model', 'user_model', true);

		$users = [
			[
				'name' => 'Christian',
				'last_name' => 'Garcia',
				'email' => 'chrigarc@ciencias.unam.mx',
				'password' => 'Banana$123$.'
			],
		];

		$roles = [
			['name' => 'root'],
		];

		$modules = [
			[
				'name' => 'CSRF',
				'pattern' => 'api/csrf',
				'method' => 'GET',
				'auth' => false
			],
			[
				'name' => 'Login',
				'pattern' => 'api/login',
				'method' => 'POST',
				'auth' => false
			],
			[
				'name' => 'Logout',
				'pattern' => 'api/logout',
				'method' => 'POST',
				'auth' => true
			],
			[
				'name' => 'User Index',
				'pattern' => 'api/user',
				'method' => 'GET',
				'auth' => true
			],
		];

		$roles_load = [];
		$modules_load = [];
		$users_load = [];

		foreach ($users as $row) {
			$users_load [] = $this->user_model->first_or_create($row, true);
		}

		foreach ($roles as $row) {
			$roles_load [] = $this->role->first_or_create($row);
		}
		foreach ($modules as $row) {
			$modules_load [] = $this->module->first_or_create($row);
		}

		$this->user_model->add_role($users_load[0]->id, $roles_load[0]->id);

		$this->output->set_output('Finished');
	}

}
