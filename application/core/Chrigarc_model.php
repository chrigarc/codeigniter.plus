<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chrigarc_model extends CI_Model
{
	protected $schema;
	public $CI;

	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->load->library('chrigarc/Uuid', 'uuid');
	}

	public function get_table()
	{
		$class = get_class($this);
		$table = '';
		switch ($class)
		{
			case 'Backend_error_model':
				$table = 'Backend_error';
				break;
			case 'Backend_log_model':
				$table = 'log';
				break;
			case 'User_model':
				$table = 'users';
				break;
			case 'User_role_model':
				$table = 'user_roles';
				break;
			case 'Role_model':
				$table = 'roles';
				break;
			case 'Module_model':
				$table = 'modules';
				break;
			case 'Role_module_model':
				$table = 'role_modules';
				break;
			case 'User_module_model':
				$table = 'user_modules';
				break;
		}
		return ($this->schema ? $this->schema . '.' : '') . $table;
	}

	public function has_uuid()
	{
		return false;
	}

	public function insert($data = array())
	{
		$this->db->flush_cache();
		if($this->has_uuid()){
			$data['uuid'] = $this->CI->uuid->v4();
		}
		$this->db->insert($this->get_table(), $data);
		return $this->find($this->db->insert_id());
	}

	public function paginate($page = 1, $per_page = 10, $field = 'id', $sort = 'asc', $filters = array())
	{
		$this->db->flush_cache();
		$this->db->start_cache();
		$this->db->from($this->get_table());
		$this->db->where($filters);
		$this->db->order_by($field, $sort);
		$this->db->stop_cache();
		$total = $this->db->count_all_results();
		$this->db->limit($per_page, ($page-1) * $per_page);
		$query = $this->db->get();

		$result = array(
			'data' => $query->result(),
			'total' => $total,
			'page' => $page,
		);

		$this->db->flush_cache();

		return $result;
	}

	public function get($filters = array(), $order_by = null)
	{
		$this->db->flush_cache();
		$this->db->start_cache();
		$this->db->from($this->get_table());
		$this->db->where($filters);
		$this->db->order_by($order_by);
		$this->db->stop_cache();
		$query = $this->db->get();
		$result = $query->result();
		$this->db->flush_cache();
		return $result;
	}

	public function find_or_fail($id)
	{
		$this->db->flush_cache();
		$this->db->from($this->get_table());
		$this->db->where('id', $id);
		$result = $this->db->get()->result();
		if(!$result){
			throw new Exception('Not found');
		}
		return $result[0];
	}

	public function find($id)
	{
		$this->db->flush_cache();
		$this->db->from($this->get_table());
		$this->db->where('id', $id);
		$result = $this->db->get()->result();
		if(count($result) > 0){
			$result = $result[0];
		}
		return $result;
	}

	public function find_uuid($uuid)
	{
		if(!$this->has_uuid()){
			throw new Exception("Model don't have uuid");
		}
		$this->db->flush_cache();
		$this->db->from($this->get_table());
		$this->db->where('uuid', $uuid);
		$result = $this->db->get()->result();
		if(count($result) > 0){
			$result = $result[0];
		}
		return $result;
	}

	public function find_uuid_or_fail($uuid)
	{
		if(!$this->has_uuid()){
			throw new Exception("Model don't have uuid");
		}
		$this->db->flush_cache();
		$this->db->from($this->get_table());
		$this->db->where('uuid', $uuid);
		$result = $this->db->get()->result();
		if(!$result){
			throw new Exception('Not found');
		}
		return $result[0];
	}

	public function update($key = array(), $data = array())
	{
		$this->db->flush_cache();
		if(is_array($key)) {
			$this->db->where($key);
		}else{
			$this->db->where('id', $key);
		}
		$data['updated_at'] = now();
		return $this->db->update($this->get_table(), $data);
	}

	public function first_or_create($data = array())
	{
		$result = $this->get($data);
		if($result) {
			$result = $result[0];
		}else{
			$result = $this->insert($data);
		}
		return $result;
	}

	public function delete($key = array())
	{
		$this->db->flush_cache();
		if(is_array($key)) {
			$this->db->where($key);
		}else{
			$this->db->where('id', $key);
		}
		return $this->db->delete($this->get_table());
	}
}
