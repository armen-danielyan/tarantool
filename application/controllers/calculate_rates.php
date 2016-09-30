<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calculate_Rates extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
		$this->load->library("jedoxapi");
	}
	
	public function index()
	{
		$pagename = "proEO Calculate Rates";
		$oneliner = "One-liner here for calculate rates";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedoxapi->page_permission($user_details['group_names'], "calculate_rates") == FALSE)
		{
			echo "Sorry, you have no permission to access this area.";
		}
		else
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
			
			// Login. need to relogin to prevent timeout
            $server_login = $this->jedoxapi->server_login($this->session->userdata('jedox_user'), $this->session->userdata('jedox_pass'));
			
			$alldata = array(
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
            );
            // Pass data and show view
            $this->load->view("calculate_rates_view", $alldata);
		}
	} // end of index
	
}