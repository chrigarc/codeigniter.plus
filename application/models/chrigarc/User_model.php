<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'core/Chrigarc_model.php';

class User_model extends Chrigarc_model
{
	protected $select_fields = array(
		'id',
		'uuid',
		'email',
		'name',
		'last_name',
		'other_name',
		'active',
		'created_at',
		'updated_at',
	);

	protected $algo = 'sha512';

	public function __construct()
	{
		parent::__construct();
		$this->CI->load->model('chrigarc/User_role_model', 'user_role', true);
		$this->CI->load->model('chrigarc/User_module_model', 'user_module', true);
	}

	public function find($id)
	{
		$this->db->flush_cache();
		$this->db->from($this->get_table());
		$this->db->select($this->select_fields);
		$this->db->where('id', $id);
		$result = $this->db->get()->result();
		if(count($result) > 0){
			$result = $result[0];
		}
		return $result;
	}

	public function find_or_fail($id)
	{
		$this->db->flush_cache();
		$this->db->from($this->get_table());
		$this->db->select($this->select_fields);
		$this->db->where('id', $id);
		$result = $this->db->get()->result();
		if(!$result){
			throw new Exception('Not found');
		}
		return $result;
	}

	public function insert($data = array())
	{
		$data['token'] = now();
		if(isset($data['password'])){
			$data['password'] = hash($this->algo, md5($data['token']).$data['password'].md5($data['token']));
		}
		return parent::insert($data); // TODO: Change the autogenerated stub
	}

	public function update($key = array(), $data = array())
	{
		if(isset($data['password'])){
			$data['token'] = now();
			$data['password'] = hash($this->algo, md5($data['token']).$data['password'].md5($data['token']));
		}
		return parent::update($key, $data); // TODO: Change the autogenerated stub
	}

	public function has_uuid()
	{
		return true;
	}

	public function get($filters = array(), $order_by = null)
	{
		unset($filters['password']);
		$this->db->flush_cache();
		$this->db->start_cache();
		$this->db->select($this->select_fields);
		$this->db->from($this->get_table());
		$this->db->where($filters);
		$this->db->order_by($order_by);
		$this->db->stop_cache();
		$query = $this->db->get();
		$result = $query->result();
		$this->db->flush_cache();
		return $result;

	}

	public function login($email, $password)
	{
		$this->db->flush_cache();
		$data_secure = $this->get_encrypt_keys($email);
		$hash = hash($this->algo, md5($data_secure->token).$password.md5($data_secure->token));
		if($hash !== $data_secure->password) {
			throw new Exception("Invalid credentials");
		}
		$user = $this->get(array(
			'email' => $email,
			'active' => true
		));
		if($this->CI->session) {
			$this->CI->session->set_userdata('application', ['user' => $user]);
		}
		return $user;
	}

	private function get_encrypt_keys($email)
	{
		$this->db->start_cache();
		$this->db->select(array('token', 'password'));
		$this->db->from($this->get_table());
		$this->db->where('email', $email);
		$this->db->stop_cache();

		$count = $this->db->count_all_results();
		if($count < 1) {
			throw new Exception("not found");
		}

		$query = $this->db->get();
		$this->db->flush_cache();
		$result = $query->result()[0];
		return $result;
	}

	public function add_role($user_id, $role_id)
	{
		$result = $this->CI->user_role->first_or_create([
			'user_id' => $user_id,
			'role_id' => $role_id
		], true);
		return $result;
	}

	public function add_module($user_id, $module_id)
	{
		$result = $this->CI->user_module->first_or_create([
			'user_id' => $user_id,
			'module_id' => $module_id
		], true);
		return $result;
	}
}