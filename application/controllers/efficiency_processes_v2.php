<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Processes_v2 extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Efficiency Operations";
        $oneliner = "One-liner here for Efficiency Operations";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_operations") == FALSE)
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
            $cube_names = "Process_Report,#_Version,#_Month,#_Report_Value,#_Process,#_Year";
			
			// Initialize post data //
            $month = $this->input->post("month");
			$year = $this->input->post("year");
			
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
            $process_report_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Process_Report");
			
			$process_report_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Process_Report");
			
			//////////////////////////// 
            // Get Dimension elements //
            ////////////////////////////
            
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $process_report_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// YEAR //
			// Get dimension of year
			//$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $process_report_dimension_id[1]);
			$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $process_report_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
            
			// MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $process_report_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
			
			// report_value //
            // Get dimension of report_value
            $report_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $process_report_dimension_id[3]);
            // Get cube data of report_value alias
            $report_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Report_Value");
            $report_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Report_Value");
            // Export cells of report_value alias
            $report_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $report_value_dimension_id[0]);
			$report_value_alias_name_id = $this->jedoxapi->get_area($report_value_alias_elements, "Name");
            $cells_report_value_alias = $this->jedoxapi->cell_export($server_database['database'],$report_value_alias_info['cube'],10000,"", $report_value_alias_name_id.",*");
			
			
			// process //
            // Get dimension of process
            $process_elements = $this->jedoxapi->dimension_elements($server_database['database'], $process_report_dimension_id[4]);
            // Get cube data of process alias
            $process_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Process");
            $process_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Process");
            // Export cells of process alias
            $process_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $process_dimension_id[0]);
			$process_alias_name_id = $this->jedoxapi->get_area($process_alias_elements, "Name");
            $cells_process_alias = $this->jedoxapi->cell_export($server_database['database'],$process_alias_info['cube'],10000,"", $process_alias_name_id.",*");
			
			// ATTRIBUTES // !!!!!!!!!!!!!
            $process_UoM_id = $this->jedoxapi->get_area($process_alias_elements, "UoM");
            $cells_process_attributes = $this->jedoxapi->cell_export($server_database['database'],$process_alias_info['cube'],10000,"", $process_UoM_id.",*");
			
			// FORM DATA //
			$form_year = $this->jedoxapi->array_element_filter($year_elements, "YA"); // all years
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
			
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
			
			////////////
            // TABLES //
            ////////////
			
			$version_patAT = $this->jedoxapi->get_area($version_elements, "V001,V002,V003,A/T"); // Plan, Actual, Target, A/T
			$version_paAP = $this->jedoxapi->get_area($version_elements, "V001,V002,A/P"); // Plan, Actual, A/P
			$version_p = $this->jedoxapi->get_area($version_elements, "V001"); // Plan
			$version_a = $this->jedoxapi->get_area($version_elements, "V002"); // actual
			$version_t = $this->jedoxapi->get_area($version_elements, "V003"); // target
			$version_AT = $this->jedoxapi->get_area($version_elements, "A/T"); // A/T
			$version_AP = $this->jedoxapi->get_area($version_elements, "A/P"); // A/P
			
			$process_set = $this->jedoxapi->array_element_filter($process_elements, "BP");
			$process_set[] = array_shift($process_set);
			
			$process_set_area = $this->jedoxapi->get_area($process_set);
			$process_set_alias = $this->jedoxapi->set_alias($process_set, $cells_process_alias);

			$report_value_rc = $this->jedoxapi->get_area($report_value_elements, "TC"); // RC
			$report_value_ro = $this->jedoxapi->get_area($report_value_elements, "Y002"); // RO
			$report_value_rqs = $this->jedoxapi->get_area($report_value_elements, "QC"); // RQS
			
			$table1_area = $version_patAT.",".$year.",".$month.",".$report_value_rc.",".$process_set_area;
			$table2_area = $version_paAP.",".$year.",".$month.",".$report_value_ro.",".$process_set_area;
			
			$table1_data = $this->jedoxapi->cell_export($server_database['database'],$process_report_cube_info['cube'],10000,"",$table1_area, "", 1, "", "0");
			$table2_data = $this->jedoxapi->cell_export($server_database['database'],$process_report_cube_info['cube'],10000,"",$table2_area, "", 1, "", "0");
			
			////////////
			// CHARTS //
			////////////
			
			$version_area_at = $this->jedoxapi->get_area($version_elements, "V002,V003"); // Actual, Target
            $version_elements_at = $this->jedoxapi->dimension_elements_id($version_elements, "V002,V003");
            $version_elements_at_alias = $this->jedoxapi->set_alias($version_elements_at, $cells_version_alias);
			
			$version_area_ap = $this->jedoxapi->get_area($version_elements, "V001,V002"); // Actual, plan
			$version_elements_ap = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002");
            $version_elements_ap_alias = $this->jedoxapi->set_alias($version_elements_ap, $cells_version_alias);
			
			$version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            $version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
			
			$month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
			
			$process_bp_area = $this->jedoxapi->get_area($process_elements, "BP");
			
			$chart1 = $chart2 = $chart3 = $this->jedox->multichart_xml_categories($month_all_alias, 1);
			
			$current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year);
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
			
			$chart1_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rc.",".$process_bp_area;
			$chart1_data = $this->jedoxapi->cell_export($server_database['database'],$process_report_cube_info['cube'],10000,"",$chart1_area, "", 1, "", "0");
			
			$chart1 .= $this->jedox->multichart_xml_series($chart1_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			// Area for previous year based on selected. Detect if there is a year before the "selected year"
            if($yearcheck != 0)
            {
            	$chart1a_area = $version_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$report_value_rc.",".$process_bp_area;
				$chart1a_data = $this->jedoxapi->cell_export($server_database['database'],$process_report_cube_info['cube'],10000,"",$chart1a_area, "", 1, "", "0");
			
				$chart1 .= $this->jedox->multichart_xml_series($chart1a_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$prev_year);
			}
			
			$chart3_area = $version_area_ap.",".$year.",".$month_all_area.",".$report_value_ro.",".$process_bp_area;
			$chart3_data = $this->jedoxapi->cell_export($server_database['database'],$process_report_cube_info['cube'],10000,"",$chart3_area, "", 1, "", "0");
			
			$chart3 .= $this->jedox->multichart_xml_series($chart3_data, $month_all, $version_elements_ap_alias, 2, 0, "", " ".$current_year);
			
			$chart2_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rqs.",".$process_bp_area;
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'],$process_report_cube_info['cube'],10000,"",$chart2_area, "", 1, "", "0");
			
			$chart2 .= $this->jedox->multichart_xml_series($chart2_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			// Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "year" => $year,
                "month" => $month,
                "form_year" => $form_year,
                "form_months" =>$form_months,
                "version_p" => $version_p,
                "version_a" => $version_a,
                "version_t" => $version_t,
                "version_AT" => $version_AT,
                "version_AP" => $version_AP,
                "table1_data" => $table1_data,
                "table2_data" => $table2_data,
                "process_set_alias" => $process_set_alias,
                "cells_process_attributes" => $cells_process_attributes,
                "chart1" => $chart1,
                "chart2" => $chart2,
                "chart3" => $chart3
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_processes_view_v2", $alldata);
            
        }
    }

	

}