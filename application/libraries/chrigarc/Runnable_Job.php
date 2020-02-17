<?php

defined('BASEPATH') OR exit('No direct script access allowed');

interface Runnable_Job
{

	public function run($params = array());

}
