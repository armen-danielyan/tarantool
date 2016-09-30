<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advance1 extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
	}
	
	public function index()
	{
		//$pagename = "ProEo Dashboard";
		//$oneliner = "One-liner here for dashboard";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedox->page_permission($user_details['group_names'], "advance1") == FALSE)
		{
			echo "Sorry, you have no permission to access this area.";
		}
		else
		{
			echo "welcome to advance1 page";
		}
	} // end of index
	
}