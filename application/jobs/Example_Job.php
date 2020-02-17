<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Example_Job implements Runnable_Job
{
	
	public function run($params = array())
	{
//		throw new Exception('fial');
		echo "Hola Mundo\n";
		echo "Params: ".json_encode($params)."\n";
	}
}
