<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hook
{
	protected $CI;

	public function auth()
	{
		if (isset($_SERVER) && isset($_SERVER['REQUEST_METHOD'])) {
			$this->CI =& get_instance();
			$this->CI->load->library('session');
			$method = $this->CI->input->method(true);
			$pattern = $this->CI->uri->uri_string;
			$user_id = null;
			$session_data = $this->CI->session->get_userdata('application');
			$session_data = $session_data['application'];
			$this->CI->load->library('chrigarc/Jwt', null, 'jwt');
			try {
				if (isset($session_data['user']) &&
					$this->CI->input->get_request_header('Authorization') &&
					$this->CI->jwt->decode($this->CI->input->get_request_header('Authorization'), hash('sha512', session_id()))) {
					$user_id = $session_data['user']->id;
				}
				$this->CI->load->library('chrigarc/Permission');
				if(!$this->CI->permission->is_allowed($pattern, $method, $user_id)){
					throw new Exception('Not allowed');
				}
			} catch (Exception $ex) {
				$this->CI->output->set_content_type('application/json')
					->set_status_header(404)
					->set_output(json_encode(array(
						'message' => $ex->getMessage()
					)))
					->_display();
				exit;
			}
		}
	}

}
