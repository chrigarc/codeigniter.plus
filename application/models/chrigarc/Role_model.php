<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'core/Chrigarc_model.php';

class Role_model extends Chrigarc_model
{

	public function __construct()
	{
		parent::__construct();
		$this->CI->load->model('chrigarc/Role_module_model', 'role_module', true);
	}

	public function has_uuid()
	{
		return true;
	}

	public function add_module($role_id, $module_id)
	{
		$result = $this->CI->role_module->first_or_create([
			'role_id' => $role_id,
			'module_id' => $module_id
		]);
		return $result;
	}
}
