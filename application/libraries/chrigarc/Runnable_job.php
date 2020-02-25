<?php

defined('BASEPATH') OR exit('No direct script access allowed');

interface Runnable_job
{

	public function run($params = array());

}
