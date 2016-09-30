<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Net_Present_Value_Summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Net Present Value Summary";
        $oneliner = "One-liner here for ";
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
			
			
			// TABLES //
			
			$version = $this->jedoxapi->dimension_sort_by_name($version_elements, "V011,V012,V013,V014"); // version. install production qc system, install wrapper in packaging
            $version = $this->jedoxapi->set_alias($version, $cells_version_alias); // Set aliases
            //$version_area = $this->jedoxapi->get_area($version);
			//detect the correct "process" data via comparing name_element from version 
			//$process_all = array();
			$process_alias = $this->jedoxapi->set_alias($process_elements, $cells_process_alias);
			$dnames = '';
			foreach($version as $row)
			{
				
				$dnames .= $row['name_element'].",";
			}
			$dnames = rtrim($dnames, ",");
			$process_all = $this->jedoxapi->dimension_sort_by_name($process_alias, $dnames);
			
			//$this->jedoxapi->traceme($version, "version");
			//$this->jedoxapi->traceme($process_all, "process");
			$process_area = $this->jedoxapi->get_area($process_all);
			
			$account_element_all = $this->jedoxapi->get_area($account_element_elements, "CE_9020,CE_9030,CE_9040");
			$account_element_ce_9020 = $this->jedoxapi->get_area($account_element_elements, "CE_9020"); // CE_9020
			$account_element_ce_9030 = $this->jedoxapi->get_area($account_element_elements, "CE_9030"); // CE_9030
			$account_element_ce_9040 = $this->jedoxapi->get_area($account_element_elements, "CE_9040"); // CE_9040
			
			$table_area = $account_element_all.",".$process_area;
			
			$table_data = $this->jedoxapi->cell_export($server_database['database'], $improvement_areas_cube_info['cube'], 10000, "", $table_area, "", "1", "", "0");
			
			// Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "process_all" => $process_all,
                "account_element_ce_9020" => $account_element_ce_9020,
                "account_element_ce_9030" => $account_element_ce_9030,
                "account_element_ce_9040" => $account_element_ce_9040,
                "table_data" => $table_data
            );
            // Pass data and show view
            $this->load->view("net_present_value_summary_view", $alldata);
			
		}
	}
	

}