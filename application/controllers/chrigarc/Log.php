<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('chrigarc/Backend_log_model', 'backend_log', true);
	}

	public function index()
	{
		$page = $this->input->get('page') ?: '1';
		$perPage = $this->input->get('perPage') ? : '10';
		$sort = $this->input->get('sort') ? : 'asc';
		$field = $this->input->get('field')? : 'id';
		$result = $this->backend_log->paginate($page, $perPage, $field, $sort);
		$this->output->set_content_type('application/json')->set_output($result);
	}

	public function show($id)
	{
		try{
			$result = $this->backend_log->find_or_fail($id);
			$this->output->set_content_type('application/json')->set_output($result);
		}catch (Exception $ex){
			$this->output->set_content_type('application/json')->set_output(json_encode(array('exception' => $ex)), 404);
		}
	}
}
