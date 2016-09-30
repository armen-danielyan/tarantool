<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_loads extends CI_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
		$this->load->library("jedoxapi");
		$this->load->library("etlapi");
    }
	
	public function index()
	{
		$pagename = "proEO Data Load";
        $oneliner = "One-liner here for Data Load";
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
			
			$database_name = $this->session->userdata('jedox_db');
			$cube_names = "Primary";
			
			// Login. need to relogin to prevent timeout
            $server_login = $this->jedoxapi->server_login($this->session->userdata('jedox_user'), $this->session->userdata('jedox_pass'));
			
			// Get Database
            $server_database_list = $this->jedoxapi->server_databases();
            $server_database = $this->jedoxapi->server_databases_select($server_database_list, $database_name);
            
            // Get Cubes
            $database_cubes = $this->jedoxapi->database_cubes($server_database['database'], 1,0,1);
            
            // Dynamically load selected cubes based on names
            $cube_multiload = $this->jedoxapi->cube_multiload($server_database['database'], $database_cubes, $cube_names);
            
            // Get Dimensions ids.
            $primary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Primary");
			
			$primary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Primary");
			
			// YEAR //
            // Get dimension of year
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[1]);
            
			
            $alldata = array(
                "jedox_user_details" => $this->session->userdata('jedox_user_details'),
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "year_elements" => $year_elements
            );
            // Pass data and show view
            $this->load->view("data_loads_view", $alldata);
        }
	}

	public function execute()
	{
		$account_elements = $this->input->post("account_elements");
		$senders_receivers = $this->input->post("senders_receivers");
		$plan_cap_rates = $this->input->post("plan_cap_rates");
		$plan_pri_cost = $this->input->post("plan_pri_cost");
		$plan_sec_con = $this->input->post("plan_sec_con");
		$year = $this->input->post("year");
		$month = $this->input->post("month");
		$act_prim_gp = $this->input->post("act_prim_gp");
		$act_rev_gp = $this->input->post("act_rev_gp");
		$act_con_sf = $this->input->post("act_con_sf");
		$act_con_ssr = $this->input->post("act_con_ssr");
		$act_con_ch = $this->input->post("act_con_ch");
		$task_type = "Execute ETL Job";
		$database_name = $this->session->userdata('jedox_db');
		// containers of status responses
		$edata1 = "";
		$edata2 = "";
		$edata3 = "";
		$edata4 = "";
		$edata5 = "";
		$edata6 = "";
		$edata7 = "";
		$edata8 = "";
		$edata9 = "";
		$edata10 = "";
		$edata11 = "";
		// containers of ids
		$eid1 = "";
		$eid2 = "";
		$eid3 = "";
		$eid4 = "";
		$eid5 = "";
		$eid6 = "";
		$eid7 = "";
		$eid8 = "";
		$eid9 = "";
		$eid10 = "";
		$eid11 = "";
		
		if($account_elements == 1){
			$edata1 = $this->etlapi->Start_Job($task_type, "ProEo", "Job_Dim_Account_Element");
			$eid1 = $this->session->userdata('etlid');
			//$edata1 = "sdf";
			//$eid1 = "345";
		}
		if($senders_receivers == 1){
			$edata2 = $this->etlapi->Start_Job($task_type, "ProEo", "Job_Dim_Receiver");
			$eid2 = $this->session->userdata('etlid');
			$edata3 = $this->etlapi->Start_Job($task_type, "ProEo", "Job_Dim_Sender");
			$eid3 = $this->session->userdata('etlid');
		}
		if($plan_cap_rates == 1){
			$edata4 = $this->etlapi->Start_Job($task_type, "ProEo", "Job_Cube_Capacity_Rates");
			$eid4 = $this->session->userdata('etlid');
			//$edata4 = "hjkhjkh";
			//$eid4 = "567575";
		}
		if($plan_pri_cost == 1){
			$edata5 = $this->etlapi->Start_Job($task_type, "ProEo", "Job_Cube_Primary");
			$eid5 = $this->session->userdata('etlid');
		}
		if($plan_sec_con == 1){
			$edata6 = $this->etlapi->Start_Job($task_type, "ProEo", "Job_Cube_Secondary");
			$eid6 = $this->session->userdata('etlid');
		}
		if($act_prim_gp == 1){
			// HOW TO ADD VARIABLES??? Variables: Date_From, Date_To, Year, Month
			// prepare variables
			$date_from = date("Y-m-d",mktime(0, 0, 0, $month, 1, $year) );
			$date_to   = date("Y-m-d",mktime(0, 0, 0, $month + 1, 0, $year) );
			$cvars = array(
				array('name' => 'Database','value' => $database_name ), 
				array('name' => 'Version','value' => 'V002' ), 
				array('name' => 'Year','value' => $year ), 
				array('name' => 'Month','value' => 'M'.$month ), 
				array('name' => 'Date_To','value' => $date_to ), 
				array('name' => 'Date_From','value' => $date_from )
			); 
			$edata7 = $this->etlapi->Start_Job($task_type, "Dynamics_GP", "Job_Primary_GP", $cvars);
			$eid7 = $this->session->userdata('etlid');
		}
		
		if($act_rev_gp == 1){
			// no job set yet
			//$edata8 = $this->etlapi->Start_Job($task_type, "Dynamics_GP", "");
			//$eid8 = $this->session->userdata('etlid');
		}
		if($act_con_sf == 1){
			// HOW TO ADD VARIABLES??? Variables: Year, Month
			// need to call salesforce script then save the files to a directory below webroot without using open_base_dir. if this is set jedox will fail.
			// find a way to import this via etl call
			//$edata9 = $this->etlapi->Start_Job($task_type, "SalesForce", "Job_Cube_SF");
			//$eid9 = $this->session->userdata('etlid');
		}
		
		if($act_con_ssr == 1){
			// no job set yet
			//$edata10 = $this->etlapi->Start_Job($task_type, "SSR_Oracle", "");
			//$eid10 = $this->session->userdata('etlid');
		}
		if($act_con_ch == 1){
			// no job set yet
			//$edata11 = $this->etlapi->Start_Job($task_type, "Clearinghouse_Direct", "");
			//$eid11 = $this->session->userdata('etlid');
		}
		
		
		//if($edata1 != '' && $eid1 != ''){
			//echo "Load Master Data from Load Sheets: Account Elements - <span id='e1'>".$edata1."</span> <a href='#' onclick=\"gstat('".$eid1."', '#e1'); return false;\" >Get Status</a>";
			
		//}
		$dset = array(
			"edata1" => $edata1,
			"eid1" => $eid1,
			"edata2" => $edata2,
			"eid2" => $eid2,
			"edata3" => $edata3,
			"eid3" => $eid3,
			"edata4" => $edata4,
			"eid4" => $eid4,
			"edata5" => $edata5,
			"eid5" => $eid5,
			"edata6" => $edata6,
			"eid6" => $eid6,
			"edata7" => $edata7,
			"eid7" => $eid7,
			"edata8" => $edata8,
			"eid8" => $eid8,
			"edata9" => $edata9,
			"eid9" => $eid9,
			"edata10" => $edata10,
			"eid10" => $eid10,
			"edata11" => $edata11,
			"eid11" => $eid11,
		);
		echo json_encode($dset);
		
	}
	
	public function gstatus()
	{
		$id = $this->input->post("id");
		echo $this->etlapi->getStatus($id);
	}
	
}