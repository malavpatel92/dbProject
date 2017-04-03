<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test extends CI_Controller {

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

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session'); //Will need session to do login
		$this->load->model(['User', 'Locations']);
	}

	//This just "redirects" to login
	public function index()
	{
		$this->load->view('view-to-test');
	}

	//Route to register a user
	public function register()
	{
		$this->load->view('register');
	}

	//Performs the registration given in register()
	public function do_register()
	{
		$user = new User();

		$user->name = $this->input->post('name');
		$user->email = $this->input->post('email');
		$user->password = $this->input->post('password');

		$this->User->create_user($user);

		$this->load->view('login');
	}

	//Route for user to login
	public function login()
	{
		$this->session->sess_destroy();
		$this->load->view('welcome_message');
	}

	//Route for user to logout
	public function logout()
	{
		$this->session->sess_destroy(); //Remove user session and return to homepage
		$this->load->view('welcome_message');
	}

	//Maybe this page? Not sure if we want to implement this
	public function forgot_password()
	{
		$this->load->view('welcome_message');
	}
}
