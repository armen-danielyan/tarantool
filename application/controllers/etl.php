<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Etl extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
        $this->load->library("jedoxapi");
		$this->load->library("etlapi");
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
		$edata = '';
		//print_r($this->etlapi->GetProjects());
		
		$task_type = $this->input->post("task_type");
		$Project = $this->input->post("Project");
		$Job = $this->input->post("Job");
		
		if($Project != '' && $Job != ''){
			$edata = $this->etlapi->Start_Job($task_type, $Project, $Job);
		}
		$this->etlapi->displayFunctions();
		$alldata = array(
                "edata" => $edata,
                "Project" => $Project,
                "Job" => $Job
            );
		$this->load->view("etl_view", $alldata);
	}
	
	public function execute(){
		$edata = '';
		$task_type = $this->input->post("task_type");
		$Project = $this->input->post("Project");
		$Job = $this->input->post("Job");
		
		if($Project != '' && $Job != ''){
			$edata = $this->etlapi->Start_Job($task_type, $Project, $Job);
		}
		echo $edata;
	}
	
	public function gstatus()
	{
		echo $this->etlapi->getStatus();
		
	}
	
}
	