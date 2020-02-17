<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Queue_Process_Notification extends Queue_Job implements _RunnableJob
{
	protected $notificable;

	public function __construct($notificable)
	{
		parent::__construct();
		$this->notificable = $notificable;
	}

	public abstract function via();

	final public function run($params = array())
	{
		// TODO: Implement run() method.
	}

}
