<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/chrigarc/Runnable_Job.php';

class Job
{

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

	protected $_schema;

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

		log_message('info', 'Job Class Initialized');
	}

	/**
	 * Initialize the user preferences
	 *
	 * Accepts an associative array as input, containing display preferences
	 *
	 * @param array    config preferences
	 * @return    Job
	 */
	public function initialize($config = array())
	{
		foreach ($config as $key => $val) {
			if (isset($this->$key)) {
				$this->$key = $val;
			}
		}
		return $this;
	}

	public function add_job($name, $params = array(), $delay = 0)
	{
		$available_at = now();
		$data = array(
			'queue' => $name,
			'params' => json_encode($params),
			'available_at' => $available_at
		);
		$this->_db->insert($this->_get_name_table(), $data);
	}

	public function process_queue()
	{
		$this->_db->flush_cache();
		$this->_db->start_cache();
		$this->_db->where('active', false);
		$this->_db->where('now() >= available_at');
		$this->_db->where('finished_at is null');
		$this->_db->stop_cache();
		$count = $this->_db->count_all($this->_get_name_table());
		if($count > 0){
			$this->_db->from($this->_get_name_table());
			$this->_db->limit(1);
			$this->_db->order_by('available_at', 'ASC');
			$query = $this->_db->get();
			$this->_db->flush_cache();
			$job = $query->first_row();
			if($job){
				$this->_execute($job);
			}
		}
	}

	public function load_job($name)
	{
		require APPPATH.'/jobs/'.$name.".php";
		$instance = new $name();
		return $instance;
	}

	private function _execute($job)
	{
		$this->_db->where('id', $job->id);
		$this->_db->update($this->_get_name_table(), ['attempts' => $job->attempts + 1, 'active' => true]);
		try{
			$instance = $this->load_job($job->queue);
			$this->_db->flush_cache();
			$instance->run(json_decode($job->params, true));
			$this->_db->flush_cache();
			$this->_db->where('id', $job->id);
			$this->_db->update($this->_get_name_table(), array('finished_at' => now(), 'status' => true, 'active' => false));
		}catch (Exception $ex) {
			$this->_db->where('id', $job->id);
			$this->_db->update($this->_get_name_table(), ['finished_at' => now(), 'active' => false]);
			$this->_db->flush_cache();
		}
	}

	private function _get_name_table()
	{
		return ($this->_schema ? $this->_schema . '.' : '') . "jobs";
	}
}
