<?php

if ( ! function_exists('backend_log'))
{
	function backend_log($params = array())
	{
		$CI =& get_instance();
		if($CI){
			$CI->load->model('chrigarc/Backend_Log_model', 'backend_log', true);
			$CI->backed_log->insert($params);
		}else{
			throw new Exception('CI instance is requiered');
		}
	}
}

if ( ! function_exists('backend_error'))
{
	function backend_error($params = array())
	{
		require_once APPPATH.'notifications/Backend_error_notification.php';
		$CI =& get_instance();
		if($CI){
			$CI->load->model('chrigarc/Backend_Error_model', 'Backend_error', true);
			$backend_error = $CI->backend_error->insert($params);
			$notification = new Backend_error_notification("New Backend Error: {$params['model_type']}");
			$notification->launch();
		}else{
			throw new Exception('CI instance is requiered');
		}
	}
}
