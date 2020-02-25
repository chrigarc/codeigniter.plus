<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'core/Chrigarc_model.php';

class Backend_Log_Model extends Chrigarc_model
{
	public function insert($params = array())
	{
		$this->db->insert('log', $params);
		return $this->db->insert_id();
	}


}
