<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if(isset($_SERVER) && isset($_SERVER['REQUEST_METHOD'])){
            header('Access-Control-Allow-Origin: http://testp.site');
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            $method = $_SERVER['REQUEST_METHOD'];
            if($method == "OPTIONS") {
                die();
            }
        }
	}


	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
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
		echo "sss".' - '.session_id().' - '.$this->session->userdata('item');
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
	    echo $result_set."\n";
	    /*
        foreach ($result_set->result() as $row)
        {
            echo json_encode($row)."\n";
        }
	    */
	}

	public function load()
	{
		new Example_Csv_Load(array(
			'filename' => '/tmp/1.csv',
			'batch_size' => 1
		));
	}
}
