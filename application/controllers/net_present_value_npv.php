<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Net_Present_Value_NPV extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Net Present Value";
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
            $cube_names = "Npv_Report,#_Version,#_Margin_Value,#_Receiver,Improvement_Area,#_Account_Element,#_Process";
			
			// Initialize post data //
            $version = $this->input->post("version");
			$npv = $this->input->post("npv");
			
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
            $npv_report_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Npv_Report");
            
            $npv_report_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Npv_Report");
			
			// Get Dimensions ids.
            $improvement_areas_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Improvement_Area");
			
			$improvement_areas_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Improvement_Area");
			
			////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $npv_report_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// YEAR //
			// Get dimension of year
			$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $npv_report_dimension_id[1]);
            
			// MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $npv_report_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
			
			// MARGIN VALUE //
            // Get dimension of margin_value
            $margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $npv_report_dimension_id[3]);
            // Get cube data of margin_value alias
            $margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
            $margin_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Margin_Value");
            // Export cells of margin_value alias
            $margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
            $cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*");
			
			// RECEIVER //
            // Get dimension of receiver
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $npv_report_dimension_id[4]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*");
			
			// ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $improvement_areas_dimension_id[0]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*");
            
            // process //
            // Get dimension of process
            $process_elements = $this->jedoxapi->dimension_elements($server_database['database'], $improvement_areas_dimension_id[1]);
            // Get cube data of process alias
            $process_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Process");
            $process_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Process");
            // Export cells of process alias
            $process_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $process_dimension_id[0]);
			$process_alias_name_id = $this->jedoxapi->get_area($process_alias_elements, "Name");
            $cells_process_alias = $this->jedoxapi->cell_export($server_database['database'],$process_alias_info['cube'],10000,"", $process_alias_name_id.",*"); 
			
			
			// FORM DATA //
            $form_version = $this->jedoxapi->dimension_sort_by_name($version_elements, "V011,V012,V013,V014"); // version. install production qc system, install wrapper in packaging
            $form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias); // Set aliases
			
			/////////////
            // PRESETS //
            /////////////
            
            if($version == '')
            {
                $version = $this->jedoxapi->get_area($version_elements, "V011");
            }
			if($npv == '')
			{
				$npv = 1.003;
			}
			
			////////////
            // TABLES //
            ////////////
			
			$version_v001 = $this->jedoxapi->get_area($version_elements, "V010");
			
			$version_v011 = $this->jedoxapi->get_area($version_elements, "V011");
			$version_v012 = $this->jedoxapi->get_area($version_elements, "V012");
			$version_v013 = $this->jedoxapi->get_area($version_elements, "V013");
			$version_v014 = $this->jedoxapi->get_area($version_elements, "V014");
			
			$year_range = $this->jedoxapi->get_area($year_elements, "2015,2016,2017");
			$year_0 = $this->jedoxapi->get_area($year_elements, "2014");
			$year_1 = $this->jedoxapi->get_area($year_elements, "2015");
			$year_2 = $this->jedoxapi->get_area($year_elements, "2016");
			$year_3 = $this->jedoxapi->get_area($year_elements, "2017");
			
			$month_range = $this->jedoxapi->get_area($month_elements, "M01,M02,M03,M04,M05,M06,M07,M08,M09,M10,M11,M12");
			$month_1 = $this->jedoxapi->get_area($month_elements, "M01");
			$month_2 = $this->jedoxapi->get_area($month_elements, "M02");
			$month_3 = $this->jedoxapi->get_area($month_elements, "M03");
			$month_4 = $this->jedoxapi->get_area($month_elements, "M04");
			$month_5 = $this->jedoxapi->get_area($month_elements, "M05");
			$month_6 = $this->jedoxapi->get_area($month_elements, "M06");
			$month_7 = $this->jedoxapi->get_area($month_elements, "M07");
			$month_8 = $this->jedoxapi->get_area($month_elements, "M08");
			$month_9 = $this->jedoxapi->get_area($month_elements, "M09");
			$month_10 = $this->jedoxapi->get_area($month_elements, "M10");
			$month_11 = $this->jedoxapi->get_area($month_elements, "M11");
			$month_12 = $this->jedoxapi->get_area($month_elements, "M12");
			
			$margin_pc = $this->jedoxapi->get_area($margin_value_elements, "PC");
			$margin_sc = $this->jedoxapi->get_area($margin_value_elements, "SC");
			$margin_qty05 = $this->jedoxapi->get_area($margin_value_elements, "QTY05");
			$margin_sls = $this->jedoxapi->get_area($margin_value_elements, "SLS");
			$margin_in = $this->jedoxapi->get_area($margin_value_elements, "IN");

			$margin_range = $this->jedoxapi->get_area($margin_value_elements, "CRAW,SC03,SCF");
			$margin_tc = $this->jedoxapi->get_area($margin_value_elements, "TC");
			
			$receiver_rp = $this->jedoxapi->get_area($receiver_elements, "RP");
			$receiver_fp = $this->jedoxapi->get_area($receiver_elements, "FP");
			
			
			
			//fixed
			$table_pc_area = $version_v001.",".$year_range.",".$month_range.",".$margin_pc.",".$receiver_rp;
			$table_sc_area = $version_v001.",".$year_range.",".$month_range.",".$margin_sc.",".$receiver_rp;
			$table_nu_area = $version_v001.",".$year_range.",".$month_range.",".$margin_qty05.",".$receiver_fp;
			$table_nr_area = $version_v001.",".$year_range.",".$month_range.",".$margin_sls.",".$receiver_fp;
			//$table_tpc_area = $version_v001.",".$year_range.",".$month_range.",".$margin_range.",".$receiver_fp;
			$table_tpc_area = $version_v001.",".$year_range.",".$month_range.",".$margin_tc.",".$receiver_fp;
			
			//variant
			$table_pc1_area = $version.",".$year_range.",".$month_range.",".$margin_pc.",".$receiver_rp;
			$table_sc1_area = $version.",".$year_range.",".$month_range.",".$margin_sc.",".$receiver_rp;
			$table_nu1_area = $version.",".$year_range.",".$month_range.",".$margin_qty05.",".$receiver_fp;
			$table_nr1_area = $version.",".$year_range.",".$month_range.",".$margin_sls.",".$receiver_fp;
			//$table_tpc1_area = $version.",".$year_range.",".$month_range.",".$margin_range.",".$receiver_fp;
			$table_tpc1_area = $version.",".$year_range.",".$month_range.",".$margin_tc.",".$receiver_fp;
			
			//fixed
			$table_pc_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $table_pc_area, "", "1", "", "0");
			$table_sc_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $table_sc_area, "", "1", "", "0");
			$table_nu_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $table_nu_area, "", "1", "", "0");
			$table_nr_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $table_nr_area, "", "1", "", "0");
			$table_tpc_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $table_tpc_area, "", "1", "", "0");
			
			//variant
			$table_pc1_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $table_pc1_area, "", "1", "", "0");
			$table_sc1_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $table_sc1_area, "", "1", "", "0");
			$table_nu1_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $table_nu1_area, "", "1", "", "0");
			$table_nr1_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $table_nr1_area, "", "1", "", "0");
			$table_tpc1_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $table_tpc1_area, "", "1", "", "0");
			
			//extra calls
			//$ec1_area = $version.",".$year_0.",".$month_12.",".$margin_pc.",".$receiver_rp;
			$ec1_area = $version.",".$year_0.",".$month_12.",".$margin_in.",".$receiver_rp;
			$ec2_area = $version_v001.",".$year_0.",".$month_12.",".$margin_pc.",".$receiver_rp;
			
			$ec1_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $ec1_area, "", "1", "", "0");
			$ec2_data = $this->jedoxapi->cell_export($server_database['database'], $npv_report_cube_info['cube'], 10000, "", $ec2_area, "", "1", "", "0");
			
			//for extra calls
			$account_element_CE_9020 = $this->jedoxapi->get_area($account_element_elements, "CE_9020");
			$process_elements_BP_TBM001 = $this->jedoxapi->get_area($process_elements, "BP_TBM001");
			$process_elements_BP_TBM002 = $this->jedoxapi->get_area($process_elements, "BP_TBM002");
			$process_elements_BP_TBM003 = $this->jedoxapi->get_area($process_elements, "BP_TBM003");
			$process_elements_BP_TBM004 = $this->jedoxapi->get_area($process_elements, "BP_TBM004");
			
			$new_area = "";
			
			if($version == $version_v011)
			{
				$new_area = $account_element_CE_9020.",".$process_elements_BP_TBM001;
			}
			if($version == $version_v012)
			{
				$new_area = $account_element_CE_9020.",".$process_elements_BP_TBM002;
			}
			if($version == $version_v013)
			{
				$new_area = $account_element_CE_9020.",".$process_elements_BP_TBM003;
			}
			if($version == $version_v014)
			{
				$new_area = $account_element_CE_9020.",".$process_elements_BP_TBM004;
			}
			
			// Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "form_version" => $form_version,
                "version" => $version,
                "npv" => $npv,
                "year_1" => $year_1,
                "year_2" => $year_2,
                "year_3" => $year_3,
                "month_1" => $month_1,
                "month_2" => $month_2,
                "month_3" => $month_3,
                "month_4" => $month_4,
                "month_5" => $month_5,
                "month_5" => $month_5,
                "month_6" => $month_6,
                "month_7" => $month_7,
                "month_8" => $month_8,
                "month_9" => $month_9,
                "month_10" => $month_10,
                "month_11" => $month_11,
                "month_12" => $month_12,
                "table_pc_data" => $table_pc_data,
                "table_sc_data" => $table_sc_data,
                "table_nu_data" => $table_nu_data,
                "table_nr_data" => $table_nr_data,
                "table_tpc_data" => $table_tpc_data,
				"table_pc1_data" => $table_pc1_data,
                "table_sc1_data" => $table_sc1_data,
                "table_nu1_data" => $table_nu1_data,
                "table_nr1_data" => $table_nr1_data,
                "table_tpc1_data" => $table_tpc1_data,
                "ec1_data" => $ec1_data,
                "ec2_data" => $ec2_data,
                //extra vars here for new code
                "database_1" => $server_database['database'],
                "cube_1" => $improvement_areas_cube_info['cube'],
                "new_area" => $new_area
            );
            // Pass data and show view
            $this->load->view("net_present_value_npv_view", $alldata);
			
			
			
		}
	}
	
	





}