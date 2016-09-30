<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Resources_dv extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Efficiency Resources";
        $oneliner = "One-liner here for Efficiency Resources";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_resources") == FALSE)
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
            $cube_names = "Resource_Report,#_Version,#_Month,#_Resource,#_Report_value,#_Year";
            
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
            $resource_report_detail_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Resource_Report");
			
			$resource_report_detail_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Resource_Report");
            
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
            
			// report_Value //
            // Get dimension of report_value
            $report_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_report_detail_dimension_id[3]);
            // Get cube data of report_value alias
            $report_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Report_Value");
            $report_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Report_Value");
            // Export cells of Report_value alias
            $report_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $report_value_dimension_id[0]);
			$report_value_alias_name_id = $this->jedoxapi->get_area($report_value_alias_elements, "Name");
            $cells_report_value_alias = $this->jedoxapi->cell_export($server_database['database'],$report_value_alias_info['cube'],10000,"", $report_value_alias_name_id.",*");
            
			
            // resource //
            // Get dimension of resource
            $resource_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_report_detail_dimension_id[4]);
            // Get cube data of resource alias
            $resource_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Resource");
            $resource_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Resource");
            // Export cells of resource alias
            $resource_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_dimension_id[0]);
			$resource_alias_name_id = $this->jedoxapi->get_area($resource_alias_elements, "Name");
            $cells_resource_alias = $this->jedoxapi->cell_export($server_database['database'],$resource_alias_info['cube'],10000,"", $resource_alias_name_id.",*");
			
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
            
            //$version_patAT = $this->jedoxapi->get_area($version_elements, "V001,V002,V003,A/T"); // Plan, Actual, Target, A/T
			//$version_paAP = $this->jedoxapi->get_area($version_elements, "V001,V002,A/P"); // Plan, Actual, A/P
			//$version_pa = $this->jedoxapi->get_area($version_elements, "V001,V002"); // Plan, Actual
			//$version_p = $this->jedoxapi->get_area($version_elements, "V001"); // Plan
			//$version_a = $this->jedoxapi->get_area($version_elements, "V002"); //actual
			//$version_t = $this->jedoxapi->get_area($version_elements, "V003"); //target
			//$version_AP = $this->jedoxapi->get_area($version_elements, "A/P"); //A/P
            //$version_AT = $this->jedoxapi->get_area($version_elements, "A/T"); //A/T
            
            $version_pat = $version1.":".$version2.":".$version3;
			$version_pa = $version1.":".$version2; // variances will be dynamically computed on view
            
            $resource_set = $form_resource;
			array_shift($resource_set); // remove RP
			$resource_set_area = $this->jedoxapi->get_area($resource_set);
			$resource_rp = $this->jedoxapi->get_area($resource_elements, "RP");
            
            $report_value_rc = $this->jedoxapi->get_area($report_value_elements, "TC"); // RC
            $report_value_ro = $this->jedoxapi->get_area($report_value_elements, "Y002"); // RO
			$report_value_rk = $this->jedoxapi->get_area($report_value_elements, "Y001"); // RK
			$report_value_reiq = $this->jedoxapi->get_area($report_value_elements, "QEI"); // REIQ
			$report_value_reic = $this->jedoxapi->get_area($report_value_elements, "CEI"); // REIC
			
            //$table1a_area = $version_patAT.",".$year.",".$month.",".$report_value_rc.",".$resource_set_area;
            //$table1b_area = $version_paAP.",".$year.",".$month.",".$report_value_ro.",".$resource_set_area;
            //$table1c_area = $version_pa.",".$year.",".$month.",".$report_value_rk.":".$report_value_reiq.":".$report_value_reic.",".$resource_set_area;
            
			//$table2a_area = $version_patAT.",".$year.",".$month.",".$report_value_rc.",".$resource_rp;
			
			$table1a_area = $version_pat.",".$year.",".$month.",".$report_value_rc.",".$resource_set_area;
            $table1b_area = $version_pat.",".$year.",".$month.",".$report_value_ro.",".$resource_set_area;
            $table1c_area = $version_pat.",".$year.",".$month.",".$report_value_rk.":".$report_value_reiq.":".$report_value_reic.",".$resource_set_area;
            
			$table2a_area = $version_pat.",".$year.",".$month.",".$report_value_rc.",".$resource_rp;
			
            $table1a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table1a_area, "", 1, "", "0");
            $table1b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table1b_area, "", 1, "", "0");
			$table1c_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table1c_area, "", 1, "", "0");
			
            $table2a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$table2a_area, "", 1, "", "0");
            
            ////////////
			// CHARTS //
			////////////
            
            //$version_area_at = $this->jedoxapi->get_area($version_elements, "V002,V003"); // Actual, Target
            //$version_elements_at = $this->jedoxapi->dimension_elements_id($version_elements, "V002,V003");
            //$version_elements_at_alias = $this->jedoxapi->set_alias($version_elements_at, $cells_version_alias);
			
			//$version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            //$version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
			
			//$version_elements_pa = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002");
            //$version_elements_pa_alias = $this->jedoxapi->set_alias($version_elements_pa, $cells_version_alias);
			
			$version_area_12 = $version1.':'.$version2.":".$version3; 
			
			$version_area_at = $version_area_12;
			$version_elements_at = $version1.",".$version2.",".$version3;
			$version_elements_at_alias = $this->get_dimension_data_by_id_multi($form_version, $version_elements_at);
			
			$month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
			
			$chart1 = $chart2 = $chart3 = $this->jedox->multichart_xml_categories($month_all_alias, 1);
			
			$current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year);
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
			
			
			//chart1
			$chart1a_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rc.",".$resource;
            
			$chart1a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart1a_area, "", 1, "", "0");
            
            $chart1 .= $this->jedox->multichart_xml_series($chart1a_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			/*
			if($yearcheck != 0)
            {
				$chart1b_area = $version_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$report_value_rc.",".$resource;
            
				$chart1b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart1b_area, "", 1, "", "0");
            
            	$chart1 .= $this->jedox->multichart_xml_series($chart1b_data, $month_all, $version_elements_a_alias, 2, 0, "", " ".$prev_year);
			
			}
			* removed due to changes
			*/
			
			//chart2
			$chart2_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_ro.",".$resource;
			
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart2_area, "", 1, "", "0");
			
			$chart2 .= $this->jedox->multichart_xml_series($chart2_data, $month_all, $version_elements_at_alias, 2, 0, "", " Output Quantity");
			
			//chart3
			
			/* old chart
			 * 
			$chart3a_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_ro.",".$resource;
			$chart3b_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_reiq.",".$resource;
			
			$chart3a_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart3a_area, "", 1, "", "0");
			$chart3b_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart3b_area, "", 1, "", "0");
			
			$chart3 .= $this->jedox->multichart_xml_series($chart3a_data, $month_all, $version_elements_at_alias, 2, 0, "", " Output Quantity");
			$chart3 .= $this->jedox->multichart_xml_series($chart3b_data, $month_all, $version_elements_at_alias, 2, 0, "", " Excess/Idle Capacity");
			*/
			
			$chart3_area = $chart3a_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rk.",".$resource; // Y001
			
			$chart3_data = $this->jedoxapi->cell_export($server_database['database'],$resource_report_detail_cube_info['cube'],10000,"",$chart3_area, "", 1, "", "0");
			//$this->jedoxapi->traceme($chart3_data);
			$chart3 .= $this->jedox->multichart_xml_series($chart3_data, $month_all, $version_elements_at_alias, 2, 0, "", "");
			
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "form_year" => $form_year,
                "form_months" => $form_months,
                "form_resource" => $form_resource,
                "form_version" => $form_version,
                "year" => $year,
                "month" => $month,
                "resource" => $resource,
                "table1a_data" => $table1a_data,
                "table1b_data" => $table1b_data,
                "table1c_data" => $table1c_data,
                "table2a_data" => $table2a_data,
                "version1" => $version1,
                "version2" => $version2,
                "version3" => $version3,
                "resource_set" => $resource_set,
                "report_value_rk" => $report_value_rk,
                "report_value_reiq" => $report_value_reiq,
				"report_value_reic" => $report_value_reic,
				"cells_resource_attributes" => $cells_resource_attributes,
				"chart1" => $chart1,
				"chart2" => $chart2,
				"chart3" => $chart3
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_resources_view_dv", $alldata);
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