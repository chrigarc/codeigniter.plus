<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/chrigarc/Csv_load_job.php';

class Example_csv_load extends Csv_load_job
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
