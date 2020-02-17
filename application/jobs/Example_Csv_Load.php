<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/chrigarc/Csv_Load_Job.php';

class Example_Csv_Load extends Csv_Load_Job
{
	public function __construct()
	{
		parent::__construct();
	}

	public function process_row($row)
	{
		print_r($row);
	}
}
