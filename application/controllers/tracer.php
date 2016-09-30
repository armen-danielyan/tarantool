<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//define("DYNATRACE", "TRUE"); // dynamically traces pages

class Tracer extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
        $this->load->library("jedoxapi");
		$this->load->library("jedoxapi_new");
		$this->load->library("etlapi");
	}
	
	public function index()
	{
		echo $this->jedoxapi_new->theline(__LINE__);
	}
	
	public function check()
	{
		
	}
	
}