<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/chrigarc/Notification_job.php';

class Backend_error_notification extends Notification_job
{
	protected $message;

	public function __construct($message = null)
	{
		parent::__construct();
		$this->message = $message;
	}

	public function via()
	{
		return array('discord');
	}

	public function get_discord_params()
	{
		return array(
			'message' => $this->message
		);
	}
}
