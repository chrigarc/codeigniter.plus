<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'core/Chrigarc_model.php';

class Role_module_model extends Chrigarc_model
{

	public function first_or_create($data = array(), $restore = true)
	{
		$result = parent::first_or_create($data);
		if(!$result->active && $restore){
			$this->update($result->id, [
				'active' => true
			]);
			$result->active = $restore;
		}
		return $result;
	}

}
