<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'core/Chrigarc_model.php';

class Module_model extends Chrigarc_model
{
	public function has_uuid()
	{
		return true;
	}
}
