<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
        $this->load->library("jedoxapi");
	}
	
	public function index()
	{
		if(isset($_GET['trace']) && $_GET['trace'] == TRUE){
			// Profile the page, usefull for debugging and page optimization. Comment this line out on production or just set to FALSE.
			$this->output->enable_profiler(TRUE);
			$this->jedoxapi->set_tracer(TRUE);
    	}
		else
		{
			$this->jedoxapi->set_tracer(FALSE);
		}
		// Initialize post data //
		$user = $this->input->post("user");
		$password = $this->input->post("password");
        $database = $this->input->post("database");
		
		$this->form_validation->set_rules('user', 'Username', 'trim|required|min_length[2]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required||min_length[5]');
		
		if ($this->form_validation->run() == FALSE)
		{
			$alldata = array(
				"error" => ""
			);
			// Pass data and show view
			$this->load->view("login_view", $alldata);
		}
		else
		{
			$server_login = $this->jedoxapi->server_login($user, $password);
			if(isset($server_login['ERROR']))
			{
				$alldata = array(
					"error" => "Login Failed. Please check username and password."
				);
				// Pass data and show view
				//print_r($server_login);
				$this->load->view("login_view", $alldata);
			}
			else
			{
				//user exist. load data to CI session to be used later. this can be used to deny access to pages that require login.
				//$this->session->set_userdata('jedox_sid', $server_login[0]);
				$this->session->set_userdata('jedox_user', $user);
				$this->session->set_userdata('jedox_pass', $password);
				$this->session->set_userdata('jedox_user_details', $this->jedox->server_user_info($server_login[0]));
				$this->session->set_userdata('jedox_db', $database);
				
				redirect("/home");
			}
		}
	}
	
	public function page()
	{
		if(isset($_GET['trace']) && $_GET['trace'] == TRUE){
			// Profile the page, usefull for debugging and page optimization. Comment this line out on production or just set to FALSE.
			$this->output->enable_profiler(TRUE);
			$this->jedoxapi->set_tracer(TRUE);
    	}
		else
		{
			$this->jedoxapi->set_tracer(FALSE);
		}
		// Initialize post data //
		$user = $this->input->post("user");
		$password = $this->input->post("password");
		$database = $this->input->post("database");
		
		$this->form_validation->set_rules('user', 'Username', 'trim|required|min_length[2]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required||min_length[5]');
		
		if ($this->form_validation->run() == FALSE)
		{
			$alldata = array(
				"error" => ""
			);
			// Pass data and show view
			$this->load->view("login_view", $alldata);
		}
		else
		{
			$server_login = $this->jedoxapi->server_login($user, $password);
			if(isset($server_login['ERROR']))
			{
				$alldata = array(
					"error" => "Login Failed. Please check username and password."
				);
				// Pass data and show view
				$this->load->view("login_view", $alldata);
			}
			else
			{
				//user exist. load data to CI session to be used later. this can be used to deny access to pages that require login.
				//$this->session->set_userdata('jedox_sid', $server_login[0]);
				$this->session->set_userdata('jedox_user', $user);
				$this->session->set_userdata('jedox_pass', $password);
				$this->session->set_userdata('jedox_user_details', $this->jedox->server_user_info($server_login[0]));
                $this->session->set_userdata('jedox_db', $database);
				redirect($this->session->userdata('jedox_referer'));
                
			}
		}
	}
	
	public function logout()
	{
		$this->session->unset_userdata('jedox_sid');
		$this->session->unset_userdata('jedox_user');
		$this->session->unset_userdata('jedox_pass');
        $this->session->unset_userdata('jedox_db');
		$this->session->unset_userdata('jedox_user_details');
		redirect("/login");
	}
	
}