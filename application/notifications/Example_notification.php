<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/chrigarc/Notification_job.php';

class Example_notification extends Notification_job
{

	public function via()
	{
		return array(
			'dummy',
			'mail',
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

	public function get_discord_params()
	{
		return array(
			'message' => 'Hola Extraño' . now()
		);
	}
}
