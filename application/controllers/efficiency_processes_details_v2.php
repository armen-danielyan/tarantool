<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Processes_Details_v2 extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index ()
    {
        $pagename = "proEO Efficiency Processes Details";
        $oneliner = "One-liner here for Efficiency Processes Details";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_processes_details") == FALSE)
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
            $cube_names = "Process_Detail_Report,#_Version,#_Month,#_Report_Value,#_Sender,#_Receiver,#_Year";
			
			// Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $receiver = $this->input->post("receiver");
            
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
            $Process_Report_Detail_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Process_Detail_Report");
            
            $Process_Report_Detail_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Process_Detail_Report");
			
			////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
			
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Process_Report_Detail_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
            // YEAR //
			// Get dimension of year
			//$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Process_Report_Detail_dimension_id[1]);
			$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Process_Report_Detail_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
            
			// MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Process_Report_Detail_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
			
			// report_value //
            // Get dimension of report_value
            $report_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Process_Report_Detail_dimension_id[3]);
            // Get cube data of report_value alias
            $report_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Report_Value");
            $report_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Report_Value");
            // Export cells of report_value alias
            $report_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $report_value_dimension_id[0]);
			$report_value_alias_name_id = $this->jedoxapi->get_area($report_value_alias_elements, "Name");
            $cells_report_value_alias = $this->jedoxapi->cell_export($server_database['database'],$report_value_alias_info['cube'],10000,"", $report_value_alias_name_id.",*");
			
			// SENDER //
            // Get dimension of sender
            $sender_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Process_Report_Detail_dimension_id[4]);
            // Get cube data of sender alias
            $sender_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Sender");
            $sender_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Sender");
            // Export cells of sender alias
            $sender_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $sender_dimension_id[0]);
			$sender_alias_name_id = $this->jedoxapi->get_area($sender_alias_elements, "Name");
            $cells_sender_alias = $this->jedoxapi->cell_export($server_database['database'],$sender_alias_info['cube'],10000,"", $sender_alias_name_id.",*");
			
			// RECEIVER //
            // Get dimension of receiver
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Process_Report_Detail_dimension_id[5]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*");
			
			// ATTRIBUTES //
            $sender_UoM_id = $this->jedoxapi->get_area($sender_alias_elements, "UoM");
            $cells_sender_attributes = $this->jedoxapi->cell_export($server_database['database'],$sender_alias_info['cube'],10000,"", $sender_UoM_id.",*");
			$receiver_UoM_id = $this->jedoxapi->get_area($receiver_alias_elements, "UoM");
            $cells_receiver_attributes = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_UoM_id.",*");
			
			
			// FORM DATA //
            $form_year = $this->jedoxapi->array_element_filter($year_elements, "YA"); // all years
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            $form_month = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_month = $this->jedoxapi->set_alias($form_month, $cells_month_alias); // Set aliases
            
            $form_receiver = $this->jedox->array_element_filter($receiver_elements, "BP"); // business processes
            $form_receiver = $this->jedox->set_alias($form_receiver, $cells_receiver_alias);
            
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
            
            if($receiver == '')
            {
                $receiver = $this->jedox->get_area($receiver_elements, "BP");
            }
			
			////////////
            // TABLES //
            ////////////
			
			$version_patAT = $this->jedoxapi->get_area($version_elements, "V001,V002,V003,A/T"); // Plan, Actual, Target, A/T
			$version_paAP = $this->jedoxapi->get_area($version_elements, "V001,V002,A/P"); // Plan, Actual, A/P
			$version_pat = $this->jedoxapi->get_area($version_elements, "V001,V002,V003"); // Plan, Actual, Target
			$version_p = $this->jedoxapi->get_area($version_elements, "V001"); // Plan
			$version_a = $this->jedoxapi->get_area($version_elements, "V002"); // actual
			$version_t = $this->jedoxapi->get_area($version_elements, "V003"); // target
			$version_AT = $this->jedoxapi->get_area($version_elements, "A/T"); // A/T
			$version_AP = $this->jedoxapi->get_area($version_elements, "A/P"); // A/P
			$sender_as = $this->jedoxapi->get_area($sender_elements, "AS"); // AS
			$receiver_ar = $this->jedoxapi->get_area($receiver_elements, "AR"); // AR
			
			$report_value_rcs = $this->jedoxapi->get_area($report_value_elements, "SC"); // RCS
			$report_value_rcsf = $this->jedoxapi->get_area($report_value_elements, "SCF"); // RCSF
			$report_value_rcsp = $this->jedoxapi->get_area($report_value_elements, "SCP"); // RCSP
			$report_value_rqs = $this->jedoxapi->get_area($report_value_elements, "QC"); // RQS
			
			$report_value_sc03 = $this->jedoxapi->get_area($report_value_elements, "SC03"); // sc03
			
			$report_value_rec = $this->jedoxapi->get_area($report_value_elements, "REC"); // rec
			$report_value_recf = $this->jedoxapi->get_area($report_value_elements, "RECF"); // recf
			$report_value_recp = $this->jedoxapi->get_area($report_value_elements, "RECP"); // recp
			$report_value_reco = $this->jedoxapi->get_area($report_value_elements, "RECO"); // recp
			
			$sender_dd = array_merge($this->jedoxapi->array_element_filter($sender_elements, "BP"), $this->jedoxapi->array_element_filter($sender_elements, "RP"), $this->jedoxapi->array_element_filter($sender_elements, "SF"));
			$sender_dd_alias = $this->jedoxapi->set_alias($sender_dd, $cells_sender_alias);
			$sender_dd_area = $this->jedoxapi->get_area($sender_dd);
			
			$receiver_converted = $this->jedox->get_dimension_data_by_id($receiver_elements, $receiver);
			$receiver_converted = $this->jedoxapi->get_area($sender_elements, $receiver_converted['name_element']);
			//$this->jedoxapi->traceme($receiver_converted);
			
			$receiver_dd = array_merge($this->jedoxapi->array_element_filter($receiver_elements, "BP"), $this->jedoxapi->array_element_filter($receiver_elements, "OP"), $this->jedoxapi->array_element_filter($receiver_elements, "RP"), $this->jedoxapi->array_element_filter($receiver_elements, "SF"), $this->jedoxapi->array_element_filter($receiver_elements, "FP"));
			$receiver_dd_alias = $this->jedoxapi->set_alias($receiver_dd, $cells_receiver_alias);
			$receiver_dd_area = $this->jedoxapi->get_area($receiver_dd);
			
			// Areas
			
			$table1a_area = $version_patAT.",".$year.",".$month.",".$report_value_rcs.",".$sender_as.",".$receiver;
			$table1b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$sender_as.",".$receiver;
			//$table1c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$sender_as.",".$receiver;
			$table1c_area = $version_pat.",".$year.",".$month.",".$report_value_sc03.",".$sender_as.",".$receiver;
			$table1d_area = $version_paAP.",".$year.",".$month.",".$report_value_rqs.",".$sender_as.",".$receiver;
			
			$table2a_area = $version_patAT.",".$year.",".$month.",".$report_value_rcs.",".$sender_dd_area.",".$receiver;
			$table2b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$sender_dd_area.",".$receiver;
			//$table2c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$sender_dd_area.",".$receiver;
			$table2c_area = $version_pat.",".$year.",".$month.",".$report_value_sc03.",".$sender_dd_area.",".$receiver;
			$table2d_area = $version_paAP.",".$year.",".$month.",".$report_value_rqs.",".$sender_dd_area.",".$receiver;
			
			//$table3a_area = $version_patAT.",".$year.",".$month.",".$report_value_rcs.",".$receiver_converted.",".$receiver_ar;
			//$table3b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$receiver_converted.",".$receiver_ar;
			//$table3c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$receiver_converted.",".$receiver_ar;
			//$table3d_area = $version_paAP.",".$year.",".$month.",".$report_value_rqs.",".$receiver_converted.",".$receiver_ar;
			$table3a_area = $version_patAT.",".$year.",".$month.",".$report_value_rec.",".$receiver_converted.",".$receiver_ar;
			$table3b_area = $version_pat.",".$year.",".$month.",".$report_value_recf.",".$receiver_converted.",".$receiver_ar;
			$table3c_area = $version_pat.",".$year.",".$month.",".$report_value_recp.",".$receiver_converted.",".$receiver_ar;
			$table3d_area = $version_paAP.",".$year.",".$month.",".$report_value_reco.",".$receiver_converted.",".$receiver_ar;
			
			//$table4a_area = $version_patAT.",".$year.",".$month.",".$report_value_rcs.",".$receiver_converted.",".$receiver_dd_area;
			//$table4b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$receiver_converted.",".$receiver_dd_area;
			//$table4c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$receiver_converted.",".$receiver_dd_area;
			//$table4d_area = $version_paAP.",".$year.",".$month.",".$report_value_rqs.",".$receiver_converted.",".$receiver_dd_area;
			$table4a_area = $version_patAT.",".$year.",".$month.",".$report_value_rec.",".$receiver_converted.",".$receiver_dd_area;
			$table4b_area = $version_pat.",".$year.",".$month.",".$report_value_recf.",".$receiver_converted.",".$receiver_dd_area;
			$table4c_area = $version_pat.",".$year.",".$month.",".$report_value_recp.",".$receiver_converted.",".$receiver_dd_area;
			$table4d_area = $version_paAP.",".$year.",".$month.",".$report_value_reco.",".$receiver_converted.",".$receiver_dd_area;
			
			//curl calls
			
			$table1a_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table1a_area, "", 1, "", "0");
			$table1b_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table1b_area, "", 1, "", "0");
			$table1c_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table1c_area, "", 1, "", "0");
			$table1d_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table1d_area, "", 1, "", "0");
			
			$table2a_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table2a_area, "", 1, "", "0");
			$table2b_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table2b_area, "", 1, "", "0");
			$table2c_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table2c_area, "", 1, "", "0");
			$table2d_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table2d_area, "", 1, "", "0");
			
			$table3a_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table3a_area, "", 1, "", "0");
			$table3b_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table3b_area, "", 1, "", "0");
			$table3c_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table3c_area, "", 1, "", "0");
			$table3d_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table3d_area, "", 1, "", "0");
			
			$table4a_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table4a_area, "", 1, "", "0");
			$table4b_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table4b_area, "", 1, "", "0");
			$table4c_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table4c_area, "", 1, "", "0");
			$table4d_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$table4d_area, "", 1, "", "0");
			
			////////////
			// CHARTS //
			////////////
			
			$version_area_at = $this->jedoxapi->get_area($version_elements, "V002,V003"); // Actual, Target
            $version_elements_at = $this->jedoxapi->dimension_elements_id($version_elements, "V002,V003");
            $version_elements_at_alias = $this->jedoxapi->set_alias($version_elements_at, $cells_version_alias);
			
			$version_area_pa = $this->jedoxapi->get_area($version_elements, "V001,V002"); // plan, actual
            $version_elements_pa = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002");
            $version_elements_pa_alias = $this->jedoxapi->set_alias($version_elements_pa, $cells_version_alias);
			
			$version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
			$version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
			
			$current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year);
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
			
			$month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
			
			$chart1 = $chart2 = $chart3 = $this->jedox->multichart_xml_categories($month_all_alias, 1);
			
			$chart1_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rcs.",".$sender_as.",".$receiver;
			$chart1_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$chart1_area, "", 1, "", "0");
			
			$chart1 .= $this->jedox->multichart_xml_series($chart1_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			// Area for previous year based on selected. Detect if there is a year before the "selected year"
            if($yearcheck != 0)
            {
            	$chart1a_area = $version_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$report_value_rcs.",".$sender_as.",".$receiver;
				$chart1a_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$chart1a_area, "", 1, "", "0");
			
				$chart1 .= $this->jedox->multichart_xml_series($chart1a_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$prev_year);
			}
			
			$chart2_area = $version_area_pa.",".$year.",".$month_all_area.",".$report_value_rqs.",".$sender_as.",".$receiver;
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$chart2_area, "", 1, "", "0");
			
			$chart2 .= $this->jedox->multichart_xml_series($chart2_data, $month_all, $version_elements_pa_alias, 2, 0, "", " Consumption Qty");
			
			$chart3_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rec.",".$receiver_converted.",".$receiver_ar;
			$chart3_data = $this->jedoxapi->cell_export($server_database['database'],$Process_Report_Detail_cube_info['cube'],10000,"",$chart3_area, "", 1, "", "0");
			
			$chart3 .= $this->jedox->multichart_xml_series($chart3_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			// Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "jedox_user_details" => $this->session->userdata('jedox_user_details'),
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "year" => $year,
                "month" => $month,
                "receiver" => $receiver,
                "form_year" => $form_year,
                "form_month" => $form_month,
                "form_receiver" => $form_receiver,
                "version_p" => $version_p,
                "version_a" => $version_a,
                "version_t" => $version_t,
                "version_AP" => $version_AP,
                "version_AT" => $version_AT,
                "sender_dd_alias" => $sender_dd_alias,
                "receiver_dd_alias" => $receiver_dd_alias,
                "table1a_data" => $table1a_data,
                "table1b_data" => $table1b_data,
                "table1c_data" => $table1c_data,
                "table1d_data" => $table1d_data,
                "table2a_data" => $table2a_data,
                "table2b_data" => $table2b_data,
                "table2c_data" => $table2c_data,
                "table2d_data" => $table2d_data,
                "table3a_data" => $table3a_data,
                "table3b_data" => $table3b_data,
                "table3c_data" => $table3c_data,
                "table3d_data" => $table3d_data,
                "table4a_data" => $table4a_data,
                "table4b_data" => $table4b_data,
                "table4c_data" => $table4c_data,
                "table4d_data" => $table4d_data,
                "cells_sender_attributes" => $cells_sender_attributes,
                "cells_receiver_attributes" => $cells_receiver_attributes,
                "chart1" => $chart1,
                "chart2" => $chart2,
                "chart3" => $chart3
            );
            // Pass data and show view
            $this->load->view("efficiency_processes_details_view_v2", $alldata);
            
        }
    }

}