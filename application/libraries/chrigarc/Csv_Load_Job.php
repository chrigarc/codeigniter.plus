<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Csv_Load_Job  implements Runnable_Job
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

	public function __construct()
	{
		$this->CI =& get_instance();
		isset($this->CI->db) OR $this->CI->load->database();
		$this->_db = $this->CI->db;

		if (!$this->_db instanceof CI_DB_query_builder) {
			throw new Exception('Query Builder not enabled for the configured database. Aborting.');
		}
		$this->CI->load->library('job');
	}

	public final function launch($params = array())
	{
		if($this->_validate_params($params)){
			$this->_store($params);
		};
	}

	public final function run($params = array())
	{
		$csv = array_map('str_getcsv', file($params['filename']));
		$limit = $params['batch_size'] + $params['current_index'];
		for ($i = $params['current_index']; $i < count($csv) && $i < $limit;  $i++){
			$this->process_row($csv[$i]);
		}
		$this->_db->flush_cache();
		$limit++;
		if($limit < count($csv)){
			$params['current_index'] = $limit;
			$this->CI->job->add_job(get_class($this), $params);
			$this->_db->where('id', $params['insert_id']);
			$this->_db->update($this->_get_name_table(), array(
				'current_index' => $limit,
				'updated_at' => now(),
			));
		}else{
			$this->_db->where('id', $params['insert_id']);
			$this->_db->update($this->_get_name_table(), array(
				'updated_at' => now(),
				'finished_at' => now(),
			));
		}
	}

	abstract public function process_row($row);

	private function _store($params)
	{
		$this->_db->flush_cache();
		$data = array(
			'filename' => $params['filename'],
			'batch_size' => $params['batch_size']
		);
		$this->_db->insert($this->_get_name_table(), $data);
		$params['current_index'] = 0;
		$params['insert_id'] = $this->_db->insert_id();
		$this->CI->job->add_job(get_class($this), $params);
	}

	private function _validate_params($params)
	{
		return isset($params['filename']) &&
			isset($params['batch_size']);
	}

	private function _get_name_table()
	{
		return ($this->_schema ? $this->_schema . '.' : '') . "csv_load";
	}
}
