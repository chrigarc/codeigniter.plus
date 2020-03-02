<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (isset($_SERVER) && isset($_SERVER['REQUEST_METHOD'])) {
			header('Access-Control-Allow-Origin: http://testp.site');
			header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
			$method = $_SERVER['REQUEST_METHOD'];
			if ($method == "OPTIONS") {
				die();
			}
		}
	}


	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 *        http://example.com/index.php/welcome
	 *    - or -
	 *        http://example.com/index.php/welcome/index
	 *    - or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function prueba1()
	{
		echo "sss" . ' - ' . session_id() . ' - ' . $this->session->userdata('item');
	}

	public function message($param)
	{
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'param' => $param,
				'item' => $this->session->userdata('item')
			)));
	}

	public function command()
	{
		$this->load->database();

//	    $this->db->insert('jobs', array('queue'=>'test', 'params' => '', 'created_at' => now()));

		$this->db->select("*");
		$this->db->from('jobs');
		$this->db->where('now() >= available_at');
		$result_set = $this->db->count_all('jobs');
		echo $result_set . "\n";
		/*
		foreach ($result_set->result() as $row)
		{
			echo json_encode($row)."\n";
		}
		*/
	}

	public function load()
	{
		$method = 'command';
		$this->{$method}();
	}

	public function helper()
	{
		$this->load->helper('chrigarc/log');
		try {
			throw new Exception('Hi');
		} catch (Exception $ex) {
			backend_error(array(
				'model_type' => get_class($this),
				'exception_trace' => $ex
			));
		}
	}

	public function uuid()
	{
		$this->load->library('chrigarc/Uuid', 'uuid');
		echo $this->uuid->v4() . "\n";
	}

	public function user()
	{
		$this->load->model('chrigarc/User_model', 'user_model', true);
		echo $this->user_model->first_or_create(array(
			'name' => 'Christian',
			'last_name' => 'Garcia',
			'email' => 'chrigarc@ciencias.unam.mx',
			'password' => 'kiqzer'
		));
	}

	public function test_user()
	{
		$this->load->library('session');
		$this->load->model('chrigarc/User_model', 'user_model', true);
		try {
			$user = $this->user_model->login('chrigarc@ciencias.unam.mx', 'kiqzer');
			echo json_encode($user) . "\n";
		} catch (Exception $ex) {
			echo $ex;
		}
	}

	public function get_session()
	{
		$this->load->library('session');
		return $this->output->set_content_type('application/json')
			->set_output(json_encode($this->session->get_userdata()));
	}

	public function backoffice_dummy()
	{
		$this->load->library('chrigarc/Permission');
		$this->load->model('chrigarc/Module_model', 'module', true);
		$this->load->model('chrigarc/Role_model', 'role', true);
		$this->load->model('chrigarc/User_model', 'user_model', true);

		$users = [
			[
				'name' => 'Christian',
				'last_name' => 'Garcia',
				'email' => 'chrigarc@ciencias.unam.mx',
				'password' => 'kiqzer'
			],
			[
				'name' => 'Juan',
				'last_name' => 'Perez',
				'email' => 'juan@mail.com',
				'password' => '12345678'
			],
			[
				'name' => 'Admin',
				'last_name' => 'Perez',
				'email' => 'admin@mail.com',
				'password' => '12345678'
			],
		];

		$roles = [
			['name' => 'root'],
			['name' => 'client'],
			['name' => 'admin']
		];

		$modules = [
			[
				'name' => 'Jobs',
				'pattern' => 'job/(:num)'
			],
			[
				'name' => 'Jobs',
				'pattern' => 'test'
			],
			[
				'name' => 'Admin',
				'pattern' => 'admin'
			]
		];

		$roles_load = [];
		$modules_load = [];
		$users_load = [];

		foreach ($users as $row){
			$users_load [] = $this->user_model->first_or_create($row, true);
		}

		foreach ($roles as $row) {
			$roles_load [] = $this->role->first_or_create($row);
		}
		foreach ($modules as $row) {
			$modules_load [] = $this->module->first_or_create($row);
		}

		$this->user_model->add_role($users_load[2]->id, $roles_load[2]->id);
		$this->user_model->add_role($users_load[0]->id, $roles_load[0]->id);
		$this->role->add_module($roles_load[0]->id, $modules_load[0]->id);
		$this->role->add_module($roles_load[2]->id, $modules_load[2]->id);
		$this->user_model->add_module($users_load[1]->id, $roles_load[1]->id);

		$has_access = $this->permission->is_allowed($users_load[0]->id, $modules_load[0]->id);
		echo "{$users_load[0]->email}, {$modules_load[0]->pattern} : ".($has_access ? 'true' : 'false') . "<br>";

		$has_access = $this->permission->is_allowed($users_load[1]->id, $modules_load[1]->id);
		echo "{$users_load[1]->email}, {$modules_load[1]->pattern} : ".($has_access ? 'true' : 'false') . "<br>";

		$has_access = $this->permission->is_allowed($users_load[2]->id, $modules_load[2]->id);
		echo "{$users_load[2]->email}, {$modules_load[2]->pattern} : ".($has_access ? 'true' : 'false') . "<br>";

		$has_access = $this->permission->is_allowed($users_load[2]->id, $modules_load[1]->id);
		echo "{$users_load[2]->email}, {$modules_load[1]->pattern} : ".($has_access ? 'true' : 'false') . "<br>";

		$has_access = $this->permission->is_allowed($users_load[1]->id, $modules_load[2]->id);
		echo "{$users_load[1]->email}, {$modules_load[2]->pattern} : ".($has_access ? 'true' : 'false') . "<br>";

		$this->output->set_output('Finished');
	}

	public function login()
	{
		$result = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		$result = json_encode($result);
		$this->output->set_content_type('application/json')->set_output($result);
	}

	public function loader_composer()
	{
		$this->load->add_package_path(APPPATH.'third_party/Snappy');
		$this->load->library('Snappy', null, 'pdf');
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="file.pdf"');
		echo $this->pdf->getOutputFromHtml('<p>Some content</p>');
	}

	public function pdf()
	{
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="file.pdf"');

		$this->load->add_package_path(APPPATH.'third_party/Snappy');
		$this->load->library('Snappy', null, 'pdf');
		$content = '';
		for($i=0;$i<1000;$i++){
			$content.='<p>Some content</p>';
		}
		echo $this->pdf->getOutputFromHtml($content);
	}
}
