<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Resources_Details_dv extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
        $this->load->library("jedoxapi");
	}
	
	public function index()
	{
		$pagename = "proEO Efficiency Resources Details";
		$oneliner = "One-liner here for Efficiency Resources Details";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedox->page_permission($user_details['group_names'], "efficiency_resources_details") == FALSE)
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
            $cube_names = "Resource_Detail_Report,#_Version,#_Month,#_Report_Value,#_Account_Element,#_Sender,#_Receiver,#_Year";
			
			//quick chance was made to change resource to receiver
			
			// Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $resource = $this->input->post("resource");
			$version1 = $this->input->post("version1");
			$version2 = $this->input->post("version2");
			$version3 = $this->input->post("version3");
            
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
			//$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_report_detail_dimension_id[1]);
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
			
			// ATTRIBUTES //
            $sender_UoM_id = $this->jedoxapi->get_area($sender_alias_elements, "UoM");
            $cells_sender_attributes = $this->jedoxapi->cell_export($server_database['database'],$sender_alias_info['cube'],10000,"", $sender_UoM_id.",*");
			$resource_UoM_id = $this->jedoxapi->get_area($resource_alias_elements, "UoM");
            $cells_resource_attributes = $this->jedoxapi->cell_export($server_database['database'],$resource_alias_info['cube'],10000,"", $resource_UoM_id.",*");
			
			
			// FORM DATA //
            $form_year = $this->jedoxapi->array_element_filter($year_elements, "YA"); // all years
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            $form_resource = $this->jedoxapi->array_element_filter($resource_elements, "RP");
			$form_resource = $this->jedoxapi->set_alias($form_resource, $cells_resource_alias);
			
			//$form_version = $this->jedoxapi->array_element_filter($version_elements, "V_Production_Plan");
			//array_shift($form_version);
			$form_version = $this->jedoxapi->dimension_elements_base($version_elements);
			$form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias);
			
			/////////////
            // PRESETS //
            /////////////
            
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
			
			if($version1 == '')
			{
				$version1 = $form_version[0]['element'];
			}
			if($version2 == '')
			{
				$version2 = $form_version[1]['element'];
			}
			if($version3 == '')
			{
				$version3 = $form_version[2]['element'];
			}
			
			////////////
            // TABLES //
            ////////////
            
			//$version_pat = $this->jedoxapi->get_area($version_elements, "V001,V002,V003"); // Plan, Actual, Target
			//$version_paAP = $this->jedoxapi->get_area($version_elements, "V001,V002,A/P"); // Plan, Actual, A/P
			//$version_p = $this->jedoxapi->get_area($version_elements, "V001"); // Plan
			//$version_a = $this->jedoxapi->get_area($version_elements, "V002"); //actual
			//$version_t = $this->jedoxapi->get_area($version_elements, "V003"); //target
			//$version_AP = $this->jedoxapi->get_area($version_elements, "A/P"); //A/P
			
			$version_pat = $version1.":".$version2.":".$version3;
			//$version_pa = $version1.":".$version2; // variances will be dynamically computed on view
			
			
			$account_element_ce5 = $this->jedoxapi->get_area($account_element_elements, "CE_Primary"); // CE_5
			$account_element_dummy = $this->jedoxapi->get_area($account_element_elements, "DUMMY"); // dummy
			$account_element_set = $this->jedoxapi->array_element_filter($account_element_elements, "CE_Primary"); // CE_5
			array_shift($account_element_set);
			$account_element_set_alias = $this->jedoxapi->set_alias($account_element_set, $cells_account_element_alias);
			$account_element_set_area = $this->jedoxapi->get_area($account_element_set);
			
			$sender_dummy = $this->jedoxapi->get_area($sender_elements, "DUMMY"); // dummy
			$sender_as = $this->jedoxapi->get_area($sender_elements, "AS"); //AS
			$sender_set = array_merge($this->jedoxapi->array_element_filter($sender_elements, "BP"), $this->jedoxapi->array_element_filter($sender_elements, "RP"), $this->jedoxapi->array_element_filter($sender_elements, "SF") );
			$sender_set_alias = $this->jedoxapi->set_alias($sender_set, $cells_sender_alias);
			$sender_set_area = $this->jedoxapi->get_area($sender_set);
			
			$resource_ar = $this->jedoxapi->get_area($resource_elements, "AR"); // AR
			$resource_converted = $this->jedox->get_dimension_data_by_id($resource_elements, $resource);
			$resource_converted = $this->jedoxapi->get_area($sender_elements, $resource_converted['name_element']);
			$resource_set = $this->jedoxapi->array_element_filter($resource_elements, "AR");
			array_shift($resource_set);
			$resource_set_alias = $this->jedoxapi->set_alias($resource_set, $cells_resource_alias);
			$resource_set_area = $this->jedoxapi->get_area($resource_set);
			
			$report_value_rcp = $this->jedoxapi->get_area($report_value_elements, "PC"); // RCP
			$report_value_rcpf = $this->jedoxapi->get_area($report_value_elements, "PC01"); // RCPF
			$report_value_rcpp = $this->jedoxapi->get_area($report_value_elements, "PC02"); // RCPP
			$report_value_rcs = $this->jedoxapi->get_area($report_value_elements, "SC"); // RCS
			$report_value_rcsf = $this->jedoxapi->get_area($report_value_elements, "SCF"); // RCSF
			$report_value_rcsp = $this->jedoxapi->get_area($report_value_elements, "SCP"); // RCSP
			$report_value_rqs = $this->jedoxapi->get_area($report_value_elements, "QC"); // RQS
			
			$report_value_sc03 = $this->jedoxapi->get_area($report_value_elements, "SC03"); // sc03
			
			$report_value_rec = $this->jedoxapi->get_area($report_value_elements, "REC"); // rec
			$report_value_recf = $this->jedoxapi->get_area($report_value_elements, "RECF"); // recf
			$report_value_recp = $this->jedoxapi->get_area($report_value_elements, "RECP"); // recp
			$report_value_reco = $this->jedoxapi->get_area($report_value_elements, "RECO"); // recp 
			
			//areas
			
			$table1a_area = $version_pat.",".$year.",".$month.",".$report_value_rcp.",".$account_element_ce5.",".$sender_dummy.",".$resource;
			$table1b_area = $version_pat.",".$year.",".$month.",".$report_value_rcpf.",".$account_element_ce5.",".$sender_dummy.",".$resource;
			$table1c_area = $version_pat.",".$year.",".$month.",".$report_value_rcpp.",".$account_element_ce5.",".$sender_dummy.",".$resource;
			
			$table2a_area = $version_pat.",".$year.",".$month.",".$report_value_rcp.",".$account_element_set_area.",".$sender_dummy.",".$resource;
			$table2b_area = $version_pat.",".$year.",".$month.",".$report_value_rcpf.",".$account_element_set_area.",".$sender_dummy.",".$resource;
			$table2c_area = $version_pat.",".$year.",".$month.",".$report_value_rcpp.",".$account_element_set_area.",".$sender_dummy.",".$resource;
			
			$table3a_area = $version_pat.",".$year.",".$month.",".$report_value_rcs.",".$account_element_dummy.",".$sender_as.",".$resource;
			$table3b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$account_element_dummy.",".$sender_as.",".$resource;
			//$table3c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$account_element_dummy.",".$sender_as.",".$resource;
			$table3c_area = $version_pat.",".$year.",".$month.",".$report_value_sc03.",".$account_element_dummy.",".$sender_as.",".$resource;
			$table3d_area = $version_pat.",".$year.",".$month.",".$report_value_rqs.",".$account_element_dummy.",".$sender_as.",".$resource;
			
			$table4a_area = $version_pat.",".$year.",".$month.",".$report_value_rcs.",".$account_element_dummy.",".$sender_set_area.",".$resource;
			$table4b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$account_element_dummy.",".$sender_set_area.",".$resource;
			//$table4c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$account_element_dummy.",".$sender_set_area.",".$resource;
			$table4c_area = $version_pat.",".$year.",".$month.",".$report_value_sc03.",".$account_element_dummy.",".$sender_set_area.",".$resource;
			$table4d_area = $version_pat.",".$year.",".$month.",".$report_value_rqs.",".$account_element_dummy.",".$sender_set_area.",".$resource;
			
			//$table5a_area = $version_pat.",".$year.",".$month.",".$report_value_rcs.",".$account_element_dummy.",".$resource_converted.",".$resource_ar;
			//$table5b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$account_element_dummy.",".$resource_converted.",".$resource_ar;
			//$table5c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$account_element_dummy.",".$resource_converted.",".$resource_ar;
			//$table5d_area = $version_paAP.",".$year.",".$month.",".$report_value_rqs.",".$account_element_dummy.",".$resource_converted.",".$resource_ar;
			$table5a_area = $version_pat.",".$year.",".$month.",".$report_value_rec.",".$account_element_dummy.",".$resource_converted.",".$resource_ar;
			$table5b_area = $version_pat.",".$year.",".$month.",".$report_value_recf.",".$account_element_dummy.",".$resource_converted.",".$resource_ar;
			$table5c_area = $version_pat.",".$year.",".$month.",".$report_value_recp.",".$account_element_dummy.",".$resource_converted.",".$resource_ar;
			$table5d_area = $version_pat.",".$year.",".$month.",".$report_value_reco.",".$account_element_dummy.",".$resource_converted.",".$resource_ar;
			
			//$table6a_area = $version_pat.",".$year.",".$month.",".$report_value_rcs.",".$account_element_dummy.",".$resource_converted.",".$resource_set_area;
			//$table6b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$account_element_dummy.",".$resource_converted.",".$resource_set_area;
			//$table6c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$account_element_dummy.",".$resource_converted.",".$resource_set_area;
			//$table6d_area = $version_paAP.",".$year.",".$month.",".$report_value_rqs.",".$account_element_dummy.",".$resource_converted.",".$resource_set_area;
			$table6a_area = $version_pat.",".$year.",".$month.",".$report_value_rec.",".$account_element_dummy.",".$resource_converted.",".$resource_set_area;
			$table6b_area = $version_pat.",".$year.",".$month.",".$report_value_recf.",".$account_element_dummy.",".$resource_converted.",".$resource_set_area;
			$table6c_area = $version_pat.",".$year.",".$month.",".$report_value_recp.",".$account_element_dummy.",".$resource_converted.",".$resource_set_area;
			$table6d_area = $version_pat.",".$year.",".$month.",".$report_value_reco.",".$account_element_dummy.",".$resource_converted.",".$resource_set_area;
			
			//data
			
			$table1a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table1a_area, "", 1, "", "0");
			$table1b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table1b_area, "", 1, "", "0");
			$table1c_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table1c_area, "", 1, "", "0");
			
			$table2a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table2a_area, "", 1, "", "0");
			$table2b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table2b_area, "", 1, "", "0");
			$table2c_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table2c_area, "", 1, "", "0");
			
			$table3a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table3a_area, "", 1, "", "0");
			$table3b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table3b_area, "", 1, "", "0");
			$table3c_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table3c_area, "", 1, "", "0");
			$table3d_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table3d_area, "", 1, "", "0");
			
			$table4a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table4a_area, "", 1, "", "0");
			$table4b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table4b_area, "", 1, "", "0");
			$table4c_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table4c_area, "", 1, "", "0");
			$table4d_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table4d_area, "", 1, "", "0");
			
			$table5a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table5a_area, "", 1, "", "0");
			$table5b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table5b_area, "", 1, "", "0");
			$table5c_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table5c_area, "", 1, "", "0");
			$table5d_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table5d_area, "", 1, "", "0");
			
			$table6a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table6a_area, "", 1, "", "0");
			$table6b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table6b_area, "", 1, "", "0");
			$table6c_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table6c_area, "", 1, "", "0");
			$table6d_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table6d_area, "", 1, "", "0");
			
			////////////
			// CHARTS //
			////////////
			
			//$version_area_at = $this->jedoxapi->get_area($version_elements, "V002,V003"); // Actual, Target
            //$version_elements_at = $this->jedoxapi->dimension_elements_id($version_elements, "V002,V003");
            //$version_elements_at_alias = $this->jedoxapi->set_alias($version_elements_at, $cells_version_alias);
			
			//$version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            //$version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
			
			$version_area_12 = $version1.':'.$version2.":".$version3; 
			
			$version_area_at = $version_area_12;
			$version_elements_at = $version1.",".$version2.",".$version3;
			$version_elements_at_alias = $this->get_dimension_data_by_id_multi($form_version, $version_elements_at);
			
			$month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
			
			$chart1 = $chart2 = $chart3 = $chart4 =  $this->jedox->multichart_xml_categories($month_all_alias, 1);
			
			$current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year);
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
			
			//chart1
			
			$chart1_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rcp.",".$account_element_ce5.",".$sender_dummy.",".$resource;
			
			$chart1_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart1_area, "", 1, "", "0");
			
			$chart1 .= $this->jedox->multichart_xml_series($chart1_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			//chart2
			
			$chart2_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rcs.",".$account_element_dummy.",".$sender_as.",".$resource;
			
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart2_area, "", 1, "", "0");
			
			$chart2 .= $this->jedox->multichart_xml_series($chart2_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			//chart3
			
			$chart3_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rqs.",".$account_element_dummy.",".$sender_as.",".$resource;
			
			$chart3_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart3_area, "", 1, "", "0");
			
			$chart3 .= $this->jedox->multichart_xml_series($chart3_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			//chart4
			
			$chart4_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rec.",".$account_element_dummy.",".$resource_converted.",".$resource_ar;
			
			$chart4_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart4_area, "", 1, "", "0");
			
			$chart4 .= $this->jedox->multichart_xml_series($chart4_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			/*
			if($yearcheck != 0)
            {
            	//chart1
			
				$chart1_area = $version_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$report_value_rcp.",".$account_element_ce5.",".$sender_dummy.",".$resource;
				
				$chart1_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart1_area, "", 1, "", "0");
				
				$chart1 .= $this->jedox->multichart_xml_series($chart1_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$prev_year);
				
				//chart2
			
				$chart2_area = $version_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$report_value_rcs.",".$account_element_dummy.",".$sender_as.",".$resource;
			
				$chart2_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart2_area, "", 1, "", "0");
			
				$chart2 .= $this->jedox->multichart_xml_series($chart2_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$prev_year);
				
				//chart3
			
				$chart3_area = $version_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$report_value_rqs.",".$account_element_dummy.",".$sender_as.",".$resource;
			
				$chart3_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart3_area, "", 1, "", "0");
			
				$chart3 .= $this->jedox->multichart_xml_series($chart3_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$prev_year);
			}
			 * removed due to adjustments
			*/
			// Pass all data to send to view file
			$alldata = array(
				//regular vars here
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
				"table1c_data" => $table1c_data,
				"table2a_data" => $table2a_data,
				"table2b_data" => $table2b_data,
				"table2c_data" => $table2c_data,
				"table3a_data" => $table3a_data,
				"table3b_data" => $table3b_data,
				"table3c_data" => $table3c_data,
				"table3d_data" => $table3d_data,
				"table4a_data" => $table4a_data,
				"table4b_data" => $table4b_data,
				"table4c_data" => $table4c_data,
				"table4d_data" => $table4d_data,
				"table5a_data" => $table5a_data,
				"table5b_data" => $table5b_data,
				"table5c_data" => $table5c_data,
				"table5d_data" => $table5d_data,
				"table6a_data" => $table6a_data,
				"table6b_data" => $table6b_data,
				"table6c_data" => $table6c_data,
				"table6d_data" => $table6d_data,
				"version1" => $version1,
                "version2" => $version2,
                "version3" => $version3,
                "form_version" => $form_version,
				"cells_sender_attributes" => $cells_sender_attributes,
				"cells_resource_attributes" => $cells_resource_attributes,
				"account_element_set_alias" => $account_element_set_alias,
				"sender_set_alias" => $sender_set_alias,
				"resource_set_alias" => $resource_set_alias,
				"chart1" => $chart1,
				"chart2" => $chart2,
				"chart3" => $chart3,
				"chart4" => $chart4
			);
			// Pass data and show view
			$this->load->view("efficiency_resources_details_view_dv", $alldata);
		}
	}

	public function get_dimension_data_by_id_multi($array, $id)
	{
		$result_array = array();
        $namelist = explode(",", $id);
        foreach($namelist as $row)
        {
            foreach($array as $arow)
            {
                if($arow['element'] == $row)
                {
                    $result_array[] = $arow;
                }
            }
            
        }
        return $result_array;
		
	}

}