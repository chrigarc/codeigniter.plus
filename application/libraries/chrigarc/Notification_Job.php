<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Notification_Job implements Runnable_Job
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

	public abstract function via();

	public function launch()
	{
		if ($this->_valid_via()) {
			foreach ($this->via() as $via) {
				$this->_enqueue_notification($via);
			}
		} else {
			throw new Exception("Invalid notification's via");
		}
	}

	private function _valid_via()
	{
		$result = true;
		foreach ($this->via() as $via) {
			if (!in_array($via, ['mail', 'dummy', 'discord', 'push'])) {
				$result = false;
			}
		}
		return true;
	}

	private function _enqueue_notification($via)
	{
		$method = "get_{$via}_params";
		$params = $this->$method();
		$params['via'] = $via;
		$this->CI->job->add_job(get_class($this), $params);
	}

	final public function run($params = array())
	{
		$method = "run_{$params['via']}";
		$this->_db->flush_cache();
		$this->$method($params);
		$this->_db->flush_cache();
	}

	final public function run_dummy($params = array())
	{
		print_r($params);
	}

	final public function run_mail($params = array())
	{
		if($this->_valid_email_params($params)){
			$this->CI->config->load('email');
			$this->CI->load->library('email');

			$this->CI->email->set_newline("\r\n");

			if(is_array($params['from'])){
				$this->CI->email->from($params['from']['email'],$params['from']['name']);
			}else{
				$this->CI->email->from($params['from']);
			}

			$this->CI->email->to($params['to']);
			if(isset($params['cc'])){
				$this->CI->email->cc($params['cc']);
			}

			if(isset($params['bcc'])){
				$this->CI->email->bcc($params['bcc']);
			}

			$this->CI->email->subject($params['subject']);
			$this->CI->email->message($params['message']);
//			$this->CI->email->send(FALSE);
			$this->CI->email->send();
		}else{
			throw new Exception("Invalid Email Params");
		}
//		echo $this->CI->email->print_debugger();
	}

	final public function run_discord($params = array())
	{
		$this->CI->config->load('discord');
		$webhookurl =  $this->CI->config->item('discord');
		if($webhookurl && isset($params['message'])  && $params['message']){
			$msg = $params['message'];

			$json_data = array ('content'=>"$msg");
			$make_json = json_encode($json_data);

			$ch = curl_init( $webhookurl );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			curl_setopt( $ch, CURLOPT_POST, 1);
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $make_json);
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt( $ch, CURLOPT_HEADER, 0);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

			$response = curl_exec( $ch );
		}else{
			throw new Exception("Invalid discord config");
		}
	}

	public function _valid_email_params($params)
	{
		return isset($params['to']) && isset($params['from']) && isset($params['subject']) && isset($params['message']);
	}

	public function get_dummy_params()
	{
		return array();
	}

	public function get_mail_params()
	{
		return array();
	}

	public function get_discord_params()
	{
		return array();
	}

	public function get_push_params()
	{
		return array();
	}
}
