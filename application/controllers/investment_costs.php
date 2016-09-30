<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Investment_Costs extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
        $this->load->library("jedoxapi");
	}
	
	public function index()
	{
		$pagename = "proEO Investment Costs";
		$oneliner = "One-liner here for Investment Cost";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedox->page_permission($user_details['group_names'], "efficiency_costs") == FALSE)
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
            $cube_names = "Resource_Detail_Report,#_Version,#_Year,#_Month,#_Report_Value,#_Account_Element,#_Sender,#_Receiver";
			
			//quick change was did to change resource to receiver... 
			
			// Initialize post data //
			$version_asis = $this->input->post("version_asis");
			$version_tobe = $this->input->post("version_tobe");
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $resource = $this->input->post("resource"); 
            
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
            $resource_report_detail_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Resource_Detail_Report");
			
			$resource_report_detail_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Resource_Detail_Report");
			
			//////////////////////////// 
            // Get Dimension elements //
            ////////////////////////////
            
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_report_detail_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// YEAR //
			// Get dimension of year
			$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_report_detail_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
			
			// MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_report_detail_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
            
			// Report_Value //
            // Get dimension of report_value
            $report_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_report_detail_dimension_id[3]);
            // Get cube data of report_value alias
            $report_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Report_Value");
            $report_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Report_Value");
            // Export cells of report_value alias
            $report_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $report_value_dimension_id[0]);
			$report_value_alias_name_id = $this->jedoxapi->get_area($report_value_alias_elements, "Name");
            $cells_report_value_alias = $this->jedoxapi->cell_export($server_database['database'],$report_value_alias_info['cube'],10000,"", $report_value_alias_name_id.",*");
			
			// ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_report_detail_dimension_id[4]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*");
			
			// SENDER //
            // Get dimension of sender
            $sender_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_report_detail_dimension_id[5]);
            // Get cube data of sender alias
            $sender_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Sender");
            $sender_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Sender");
            // Export cells of sender value alias
            $sender_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $sender_dimension_id[0]);
			$sender_alias_name_id = $this->jedoxapi->get_area($sender_alias_elements, "Name");
            $cells_sender_alias = $this->jedoxapi->cell_export($server_database['database'],$sender_alias_info['cube'],10000,"", $sender_alias_name_id.",*");
			
			// resource //
            // Get dimension of resource
            $resource_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_report_detail_dimension_id[6]);
            // Get cube data of resource alias
            $resource_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
			
            $resource_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of resource alias
            $resource_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_dimension_id[0]);
			$resource_alias_name_id = $this->jedoxapi->get_area($resource_alias_elements, "Name");
            $cells_resource_alias = $this->jedoxapi->cell_export($server_database['database'],$resource_alias_info['cube'],10000,"", $resource_alias_name_id.",*");
			
			// FORM DATA //
			$form_version_asis = $this->jedoxapi->array_element_filter($version_elements, "V_Investment"); // as is
			$form_version_asis = $this->jedoxapi->dimension_elements_base($form_version_asis);
			$form_version_asis = $this->jedoxapi->set_alias($form_version_asis, $cells_version_alias);
			
			$form_version_tobe = $this->jedoxapi->array_element_filter($version_elements, "V_Investment"); // to be
			$form_version_tobe = $this->jedoxapi->dimension_elements_base($form_version_tobe);
			$form_version_tobe = $this->jedoxapi->set_alias($form_version_tobe, $cells_version_alias);
			
            //$form_year = $this->jedoxapi->dimension_elements_base($year_elements);
			$form_year = $this->jedoxapi->array_element_filter($year_elements, "YA");
            $form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
			
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            $form_resource = $this->jedoxapi->array_element_filter($resource_elements, "RP");
			//array_shift($form_resource);
			$form_resource = $this->jedoxapi->set_alias($form_resource, $cells_resource_alias);
			
			/////////////
            // PRESETS //
            /////////////
            
            if($version_asis == '')
            {
            	$version_asis = $this->jedoxapi->get_area($version_elements, "V010");
            }
			
			if($version_tobe == '')
			{
				$version_tobe = $this->jedoxapi->get_area($version_elements, "V011");
			}
            
            if($year == '')
            {
                $now = now();
                $tnow = mdate("%Y", $now);
                $year = $this->jedoxapi->get_area($year_elements, $tnow);
            }
            if($month == '')
            {
                $month = $this->jedoxapi->get_area($month_elements, "MA");
            }
			
			if($resource == '')
			{
				$resource = $this->jedoxapi->get_area($resource_elements, "RP");
			}
			
			////////////
            // TABLES //
            ////////////
            
            $report_value_tc = $this->jedoxapi->get_area($report_value_elements, "TC"); // TC
            $report_value_pc = $this->jedoxapi->get_area($report_value_elements, "PC"); // PC
            $report_value_sc = $this->jedoxapi->get_area($report_value_elements, "SC"); // SC
            
            $account_element_ce_primary = $this->jedoxapi->get_area($account_element_elements, "CE_Primary"); // CE_Primary
            $account_element_dummy = $this->jedoxapi->get_area($account_element_elements, "DUMMY"); // dummy
            $account_element_set = $this->jedoxapi->array_element_filter($account_element_elements, "CE_Primary"); 
			//array_shift($account_element_set);
			$account_element_set_alias = $this->jedoxapi->set_alias($account_element_set, $cells_account_element_alias);
			$account_element_set_area = $this->jedoxapi->get_area($account_element_set);
            
            $sender_dummy = $this->jedoxapi->get_area($sender_elements, "DUMMY"); // dummy
            $sender_as = $this->jedoxapi->get_area($sender_elements, "AS");
            $sender_set = $this->jedoxapi->array_element_filter($sender_elements, "AS");
			$sender_set_alias = $this->jedoxapi->set_alias($sender_set, $cells_sender_alias);
			$sender_set_area = $this->jedoxapi->get_area($sender_set);
            
			$table1a_area = $version_tobe.":".$version_asis.",".$year.",".$month.",".$report_value_pc.",".$account_element_ce_primary.",".$sender_dummy.",".$resource;
			
			$table1b_area = $version_tobe.":".$version_asis.",".$year.",".$month.",".$report_value_sc.",".$account_element_dummy.",".$sender_as.",".$resource; // additional 1 call
			
			$table2a_area = $version_tobe.":".$version_asis.",".$year.",".$month.",".$report_value_pc.",".$account_element_set_area.",".$sender_dummy.",".$resource;
			//$table2b_area = $version_asis.",".$year.",".$month.",".$report_value_pc.",".$account_element_set_area.",".$sender_dummy.",".$resource; 
			
			$table3a_area = $version_tobe.":".$version_asis.",".$year.",".$month.",".$report_value_sc.",".$account_element_dummy.",".$sender_set_area.",".$resource;
			//$table3b_area = $version_asis.",".$year.",".$month.",".$report_value_sc.",".$account_element_dummy.",".$sender_set_area.",".$resource;
			
			$table1a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table1a_area, "", 1, "", "0");
			$table1b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table1b_area, "", 1, "", "0");
			
			$table2a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table2a_area, "", 1, "", "0");
			//$table2b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table2b_area, "", 1, "", "0");
			
			$table3a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table3a_area, "", 1, "", "0");
			//$table3b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table3b_area, "", 1, "", "0");
			
			////////////
			// charts // 
			////////////
			
			$month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
			
			$chart1 = $this->jedox->multichart_xml_categories($month_all_alias, 1);
			
			$chart1a_area = $version_tobe.",".$year.",".$month_all_area.",".$report_value_pc.",".$account_element_ce_primary.",".$sender_dummy.",".$resource;
			$chart1b_area = $version_tobe.",".$year.",".$month_all_area.",".$report_value_sc.",".$account_element_dummy.",".$sender_as.",".$resource; // additional 1 call
			$chart1c_area = $version_asis.",".$year.",".$month_all_area.",".$report_value_pc.",".$account_element_ce_primary.",".$sender_dummy.",".$resource;
			$chart1d_area = $version_asis.",".$year.",".$month_all_area.",".$report_value_sc.",".$account_element_dummy.",".$sender_as.",".$resource;
			
			$chart1a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart1a_area, "", 1, "", "0");
			$chart1b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart1b_area, "", 1, "", "0");
			$chart1c_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart1c_area, "", 1, "", "0");
			$chart1d_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart1d_area, "", 1, "", "0");
			
			$chart1ab_data = $this->jedox->add_cell_array($chart1a_data, $chart1b_data, 0, 2);
			$chart1c_data = $this->jedox->add_cell_array($chart1c_data, $chart1d_data, 0, 2);
			
			$version_tobe_dim = array();
			$version_tobe_dim[] = $this->jedox->get_dimension_data_by_id($version_elements, $version_tobe);
			$version_tobe_alias = $this->jedoxapi->set_alias($version_tobe_dim, $cells_version_alias);
			
			$version_asis_dim = array();
			$version_asis_dim[] = $this->jedox->get_dimension_data_by_id($version_elements, $version_asis);
			$version_asis_alias = $this->jedoxapi->set_alias($version_asis_dim, $cells_version_alias);
			
			$chart1 .= $this->jedox->multichart_xml_series($chart1ab_data, $month_all, $version_tobe_alias, 2, 0, "", " ");
			$chart1 .= $this->jedox->multichart_xml_series($chart1c_data, $month_all, $version_asis_alias, 2, 0, "", " ");
			
			// Pass all data to send to view file
			$alldata = array(
				//regular vars here
				"form_version_asis" => $form_version_asis,
				"version_asis" => $version_asis,
				"form_version_tobe" => $form_version_tobe,
				"version_tobe" => $version_tobe,
				"form_year" => $form_year,
				"year" => $year,
				"form_months" => $form_months,
				"month" => $month,
				"form_resource" => $form_resource,
				"resource" => $resource,
				"jedox_user_details" => $this->session->userdata('jedox_user_details'),
				"pagename" => $pagename,
				"oneliner" => $oneliner,
				"table1a_data" => $table1a_data,
				"table1b_data" => $table1b_data,
				"table2a_data" => $table2a_data,
				//"table2b_data" => $table2b_data,
				"table3a_data" => $table3a_data,
				//"table3b_data" => $table3b_data,
				"account_element_set_alias" => $account_element_set_alias,
				"sender_set_alias" => $sender_set_alias,
				"chart1" => $chart1
			);
			// Pass data and show view
			$this->load->view("investment_costs_view", $alldata);
		}
	}
	/*
	private function set_alias($array, $alias)
    {
        $result_array = array();
        foreach($array as $row)
        {
            $found = 0;
            foreach($alias as $rows)
            {
                $path = explode(',', $rows['path']);
				$path['1'] += 1;
                if($row['element'] == $path['1'])
                {
                    $row['name_element'] = $rows['value'] ;
                    $result_array[] = $row;
                    $found = 1;
                }
            }
            if($found == 0)
            {
                //if not matching alias is found. return row value "as is"
                $result_array[] = $row;
            }
        }
        return $result_array;
    }
	*/
}