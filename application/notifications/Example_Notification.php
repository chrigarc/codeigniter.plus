<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/chrigarc/Notification_Job.php';

class Example_Notification extends Notification_Job
{

	public function via()
	{
		return array(
//			'dummy',
//			'mail'
			'discord'
		);
	}

	public function get_dummy_params()
	{
		return array(
			'message' => 'Hola mundo'
		);
	}

	public function get_mail_params()
	{
		return array(
			'to' => 'zurgcom@gmail.com',
			'from' => array(
				'email' => 'christian.garcia@labbe.io',
				'name' => 'Christian'
			),
			'subject' => 'Mensaje de prueba: ' . now(),
			'message' => "Hola Mundo"
		);
	}
}
