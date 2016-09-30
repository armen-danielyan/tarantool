<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
		$this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Support";
        $oneliner = "One-liner here for Support";
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
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
            $alldata = array(
                "jedox_user_details" => $this->session->userdata('jedox_user_details'),
                "pagename" => $pagename,
                "oneliner" => $oneliner
            );
            // Pass data and show view
            $this->load->view("support_view", $alldata);
        }
    } // end of index
    
}