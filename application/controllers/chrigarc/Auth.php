<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function csrf()
	{
		$result = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		$result = json_encode($result);
		$this->output->set_content_type('application/json')->set_output($result);
	}

	public function login()
	{
		$this->load->library('session');
		$this->load->library('chrigarc/Jwt', null, 'jwt');
		$this->load->model('chrigarc/User_model', 'user_model', true);
		$this->load->library('form_validation');
		if ($this->form_validation->run('auth.login') == FALSE)
		{
			$this->output->set_content_type('application/json')->set_status_header(403)->set_output(json_encode(array(
				'status' => false,
				'errors' => $this->form_validation->error_array()
			)));
		}else{
			$data = $this->input->post(array('email','password'), TRUE);
			try{
				$user = $this->user_model->login($data['email'], $data['password']);
				$result = json_encode(array(
					'user' => $user,
					'token' => $this->jwt->encode($user, hash('sha512', session_id()))
				));
				$this->output->set_content_type('application/json')->set_output($result);
			}catch (Exception $ex){
				$this->output->set_content_type('application/json')->set_status_header(403)->set_output(json_encode(array(
					'status' => false,
					'errors' => 'fail login',
					'exception' => $ex
				)));
			}
		}
	}

	public function logout()
	{

	}
}
