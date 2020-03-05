<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Permission
{

	protected $schema;

	/**
	 * CI Singleton
	 *
	 * @var object
	 */
	protected $CI;

	/**
	 * DB object
	 *
	 * @var    object
	 */
	protected $_db;

	/**
	 * Class constructor
	 *
	 * Loads the calendar language file and sets the default time reference.
	 *
	 * @param array $config Calendar options
	 * @return    void
	 * @uses    CI_Lang::$is_loaded
	 *
	 */
	public function __construct($config = array())
	{
		$this->CI =& get_instance();
		isset($this->CI->db) OR $this->CI->load->database();
		$this->_db = $this->CI->db;

		if (!$this->_db instanceof CI_DB_query_builder) {
			throw new Exception('Query Builder not enabled for the configured database. Aborting.');
		}

		empty($config) OR $this->initialize($config);

		log_message('info', 'Permission Class Initialized');
	}

	public function is_allowed($module_pattern, $method = 'GET', $user_id = null)
	{
		if ($user_id && $this->_is_root($user_id)) {
			$result = true;
		} else {
			$this->CI->load->model('chrigarc/Module_model', 'module', true);
			$module = null;
			$results = $this->CI->module->get(array(
					'pattern' => $module_pattern,
					'method' => $method
				)
			);

			if ($results) {
				$module = $results[0];
			} else {
				throw new Exception("Not found");
			}

			$result = !$this->_require_auth($module->id) ||
				$this->_role_has_access_module($user_id, $module->id) ||
				$this->_user_has_access_module($user_id, $module->id);
		}
		return $result;
	}

	private function _require_auth($module_id)
	{
		$this->_db->flush_cache();
		$this->_db->from($this->_get_table('modules'));
		$this->_db->where('active', true);
		$this->_db->where('id', $module_id);
		$query = $this->_db->get();
		$result = $query->result();
		return $result[0]->auth;
	}

	private function _is_root($user_id)
	{
		$result = false;
		$this->_db->flush_cache();
		$this->_db->from($this->_get_table('users').' users');
		$this->_db->join($this->_get_table('user_roles').' ur', 'users.id = ur.user_id');
		$this->_db->join($this->_get_table('roles').' roles', 'roles.id = ur.role_id');
		$this->_db->where('users.id', $user_id);
		$this->_db->where('roles.name', 'root');
		$count = $this->_db->count_all_results();
		$result = $count > 0;
		return $result;
	}

	private function _user_has_access_module($user_id, $module_id)
	{
		$result = false;
		$this->_db->flush_cache();
		$this->_db->from($this->_get_table('users'));
		$this->_db->join($this->_get_table('user_modules').' um', 'um.user_id = users.id');
		$this->_db->join($this->_get_table('modules').' mo', 'mo.id = um.module_id');
		$this->_db->where('users.active', true);
		$this->_db->where('mo.active', true);
		$this->_db->where('um.active', true);
		$this->_db->where('mo.id', $module_id);
		$this->_db->where('users.id', $user_id);
		$count = $this->_db->count_all_results();
		$result = $count > 0;
		return $result;
	}

	private function _role_has_access_module($user_id, $module_id)
	{
		$result = false;
		$this->_db->flush_cache();
		$this->_db->from($this->_get_table('users'));
		$this->_db->join($this->_get_table('user_roles').' ur', 'on ur.user_id = users.id');
		$this->_db->join($this->_get_table('role_modules').' rm', 'rm.role_id = ur.role_id');
		$this->_db->join($this->_get_table('modules').' mo', 'mo.id = rm.module_id');
		$this->_db->join($this->_get_table('roles').' ro', 'ro.id = rm.role_id');
		$this->_db->where('users.active', true);
		$this->_db->where('ur.active', true);
		$this->_db->where('rm.active', true);
		$this->_db->where('mo.active', true);
		$this->_db->where('ro.active', true);
		$this->_db->where('mo.id', $module_id);
		$this->_db->where('users.id', $user_id);
		$count = $this->_db->count_all_results();
		$result = $count > 0;
		return $result;
	}

	/**
	 * @param $name
	 * @return string
	 */
	private function _get_table($name)
	{
		return ($this->schema ? $this->schema . '.' : '') . $name;
	}
}
