<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Queue_Job extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('chrigarc/job');
	}

	public function help()
	{
		$this->output->set_output("Hello world");
	}

	public function example()
	{
		$this->job->add_job(Example_Job::class, ['message' => "Esta es un ejemplo de como se encola un Job"]);
		$this->output->set_output("Job registrado");
	}

	public function process()
	{
		$this->job->process_queue();
	}

	public function example_load()
	{
		require_once APPPATH.'jobs/Example_Csv_Load.php';
		$c = new Example_Csv_Load();
		$c->launch(array(
			'filename' => '/tmp/1.csv',
			'batch_size' => 1
		));
	}

	public function example_notification()
	{
		require_once APPPATH.'notifications/Example_Notification.php';
		$n = new Example_Notification();
		$n->launch();
	}
}


