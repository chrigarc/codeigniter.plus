<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Example_job implements Runnable_job
{
	
	public function run($params = array())
	{
//		throw new Exception('fial');
		echo "Hola Mundo\n";
		echo "Params: ".json_encode($params)."\n";
	}
}
