<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Queue_job extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('chrigarc/job');
	}

	public function index()
	{
		$page = $this->input->get('page') ?: '1';
		$perPage = $this->input->get('perPage') ? : '10';
		$sort = $this->input->get('sort') ? : 'asc';
		$field = $this->input->get('field')? : 'id';
		$result = $this->job->paginate($page, $perPage, $field, $sort);
		$this->output->set_content_type('application/json')->set_output($result);
	}

	public function show($id)
	{
		try{
			$result = $this->job->find_or_fail($id);
			$this->output->set_content_type('application/json')->set_output($result);
		}catch (Exception $ex){
			$this->output->set_content_type('application/json')->set_output(json_encode(array('exception' => $ex)), 404);
		}
	}

	public function help()
	{
		$this->output->set_output("Hello world");
	}

	public function example()
	{
		$this->job->add_job(Example_job::class, ['message' => "Esta es un ejemplo de como se encola un Job"]);
		$this->output->set_output("Job registrado");
	}

	public function process()
	{
		$this->job->process_queue();
	}

	public function example_load()
	{
		require_once APPPATH.'jobs/Example_csv_load.php';
		$c = new Example_csv_load();
		$c->launch(array(
			'filename' => '/tmp/1.csv',
			'batch_size' => 1
		));
	}

	public function example_notification()
	{
		require_once APPPATH.'notifications/..php';
		$n = new Example_notification();
		$n->launch();
	}
}


