<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Resources extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
		$pagename = "proEO Salary Planning";
        $oneliner = "One-liner here for Salary Planning";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_resources") == FALSE) // did not add separate permission yet. test page.
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
			
			// Initialize variables //
            $database_name = $this->session->userdata('jedox_db');
			
			// Comma delimited cubenames to load. Cube names with #_ prefix are aliases cubes. No spaces.
            $cube_names = "Labor,#_Month,#_Receiver,#_Account_Element,#_Production_Value";
            
			// Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $receiver = $this->input->post("receiver");
			
			
			// Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $receiver = $this->input->post("receiver");
			
		}
	}
}