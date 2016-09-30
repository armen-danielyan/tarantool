<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Costs_Elements extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); 
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index ()
    {
        $pagename = "proEO Efficiency Costs Elements";
        $oneliner = "One-liner here for Efficiency costs elements";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_costs") == FALSE)
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
            $cube_names = "Primary,#_Version,#_Month,#_Primary_Value,#_Account_Element,#_Receiver";
            
            // Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $version = $this->input->post("version");
            
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
            //$secondary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Secondary");
            
            $primary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Primary");
            //$secondary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Secondary");
            
            ////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
            // VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
            
            // YEAR //
            // Get dimension of year
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[1]);
            
            // MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
            
            // PRIMARY VALUE //
            // Get dimension of primary_value
            $primary_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[3]);
            // Get cube data of primary_value alias
            $primary_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Primary_Value");
            $primary_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Primary_Value");
            // Export cells of primary_value alias
            $primary_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_value_dimension_id[0]);
			$primary_value_alias_name_id = $this->jedoxapi->get_area($primary_value_alias_elements, "Name");
            $cells_primary_value_alias = $this->jedoxapi->cell_export($server_database['database'],$primary_value_alias_info['cube'],10000,"", $primary_value_alias_name_id.",*"); 
            
            // ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[4]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*"); 
            
            // RECEIVER //
            // Get dimension of receiver 
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[5]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*"); 
            
            // FORM DATA //
            $form_year = $year_elements;
            
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            $form_version = $this->jedoxapi->dimension_sort_by_name($version_elements, "V001,V003"); 
            $form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias); // Set aliases  
            
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
            if($version == '')
            {
                $version = $this->jedoxapi->get_area($version_elements, "V001"); 
            }
			
			///////////
			// TABLE //
            ///////////
            
            $version_actual_area = $this->jedoxapi->get_area($version_elements, "V002"); 
			$version_name = $this->jedoxapi->set_alias($version_elements, $cells_version_alias);
			$primary_value_PC_area = $this->jedoxapi->get_area($primary_value_elements, "PC");
			
			//$r_perm = $this->jedoxapi->receiver_permission($user_details['group_names'], "RP"); // this cant work now as multiple groups exist.
			$r_perm = "RP";
			
			$receiver_RP_area = $this->jedoxapi->get_area($receiver_elements, $r_perm);
			$receiver_RP = $this->jedoxapi->dimension_sort_by_name($receiver_elements, $r_perm);
			$receiver_RP = $this->jedoxapi->set_alias($receiver_RP, $cells_receiver_alias);
			$account_element_ce2_area = $this->jedoxapi->get_area($account_element_elements, "CE_2");
			$account_element_ce2 = $this->jedoxapi->dimension_sort_by_name($account_element_elements, "CE_2");
			$account_element_ce2 = $this->jedoxapi->set_alias($account_element_ce2, $cells_account_element_alias);
			//$account_element_ce2_full_area = $this->jedoxapi->get_area($account_element_ce2_full);
			
            $table_area = $version_actual_area.":".$version.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_ce2_area.",".$receiver_RP_area;
			$table_base = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $table_area, "", "1", "", "0");
			
			////////////
			// CHARTS //
			////////////
			
			$month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
            $version_area_pa = $this->jedoxapi->get_area($version_elements, "V001,V002"); // Plan, Actual
            $version_elements_pa = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002");
            $version_elements_pa_alias = $this->jedoxapi->set_alias($version_elements_pa, $cells_version_alias);
            $version_area_a = $this->jedoxapi->get_area($version_elements, "V002"); // Actual
            $version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            $version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
			$primary_value_PC_area = $this->jedoxapi->get_area($primary_value_elements, "PC");
			$account_element_elements_CE_2_area = $this->jedoxapi->get_area($account_element_elements, "CE_2");
			$receiver = $this->jedoxapi->get_area($receiver_elements, "AR");
            
            $current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year);
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
            
            $chart1xml = $this->jedox->multichart_xml_categories($month_all_alias, 1);
            
            $chart1b_area = $version_area_pa.",".$year.",".$month_all_area.",".$primary_value_PC_area.",".$account_element_elements_CE_2_area.",".$receiver;
            
            $chart1b_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1b_area, "", "1", "", "0");
            
            $chart1xml .= $this->jedox->multichart_xml_series($chart1b_cells, $month_all, $version_elements_pa_alias, 2, 0, "", " ".$current_year);
            
			// Area for previous year based on selected. Detect if there is a year before the "selected year"
            if($yearcheck != 0)
            {
                $chart1e_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$primary_value_PC_area.",".$account_element_elements_CE_2_area.",".$receiver;
                
                $chart1e_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1e_area, "", "1", "", "0");
                
                $chart1xml .= $this->jedox->multichart_xml_series($chart1e_cells, $month_all, $version_elements_a_alias, 2, 0, "", " ".$prev_year);
            }
			
			
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "form_year" => $form_year,
                "year" => $year,
                "form_months" => $form_months,
                "month" => $month,
                "form_version" => $form_version,
                "version" => $version,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "table_base" => $table_base,
                "cells_version_alias" => $cells_version_alias,
                "base_load" => $account_element_ce2,
                "receiver_RP" => $receiver_RP,
                "version_actual_area" => $version_actual_area,
                "receiver_RP_area" => $receiver_RP_area,
                "version_name" => $version_name,
                "chart1xml" => $chart1xml
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_costs_elements_view", $alldata);
        }
    }
    
	public function get_ae()
	{
		$user_details = $this->session->userdata('jedox_user_details');
		$account_element = $this->input->post("account_element");
		$year = $this->input->post("year");
        $month = $this->input->post("month");
        $version = $this->input->post("version");
		//echo "hi ".$account_element." ".$year." ".$month." ".$version;
		
		$database_name = $this->session->userdata('jedox_db');
		// Comma delimited cubenames to load. Cube names with #_ prefix are aliases cubes. No spaces.
		$cube_names = "Primary,#_Version,#_Month,#_Primary_Value,#_Account_Element,#_Receiver";
		
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
		//$secondary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Secondary");
		
		$primary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Primary");
		//$secondary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Secondary");
		
		////////////////////////////
		// Get Dimension elements //
		////////////////////////////
		
		// VERSION //
		// Get dimension of version
		$version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[0]);
		// Get cube data of version alias
		$version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
		$version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
		// Export cells of version alias
		$version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
		$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
		$cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
		
		// YEAR //
		// Get dimension of year
		$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[1]);
		
		// MONTH //
		// Get dimension of month
		$month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[2]);
		// Get cube data of month alias
		$month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
		$month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
		// Export cells of month alias
		$month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
		$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
		$cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
		
		// PRIMARY VALUE //
		// Get dimension of primary_value
		$primary_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[3]);
		// Get cube data of primary_value alias
		$primary_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Primary_Value");
		$primary_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Primary_Value");
		// Export cells of primary_value alias
		$primary_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_value_dimension_id[0]);
		$primary_value_alias_name_id = $this->jedoxapi->get_area($primary_value_alias_elements, "Name");
		$cells_primary_value_alias = $this->jedoxapi->cell_export($server_database['database'],$primary_value_alias_info['cube'],10000,"", $primary_value_alias_name_id.",*"); 
		
		// ACCOUNT ELEMENT //
		// Get dimension of account_element
		$account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[4]);
		// Get cube data of account_element alias
		$account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
		$account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
		// Export cells of account_element alias
		$account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
		$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
		$cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*"); 
		
		// RECEIVER //
		// Get dimension of receiver
		$receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[5]);
		// Get cube data of receiver alias
		$receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
		$receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
		// Export cells of receiver alias
		$receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
		$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
		$cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*"); 
		
		///////////
		// TABLE //
        ///////////
        
        $version_actual_area = $this->jedoxapi->get_area($version_elements, "V002"); 
		$version_name = $this->jedoxapi->set_alias($version_elements, $cells_version_alias);
		$primary_value_PC_area = $this->jedoxapi->get_area($primary_value_elements, "PC");
		
		//$r_perm = $this->jedoxapi->receiver_permission($user_details['group_names'], "RP"); // this cant work now as multiple groups exist.
		$r_perm = "RP";
		
		$receiver_RP_area = $this->jedoxapi->get_area($receiver_elements, $r_perm);
		$receiver_RP = $this->jedoxapi->dimension_sort_by_name($receiver_elements, $r_perm);
		$receiver_RP = $this->jedoxapi->set_alias($receiver_RP, $cells_receiver_alias);
		
		//$account_element_alias = $this->jedoxapi->set_alias($account_element_elements, $cells_account_element_alias);
		$account_element_set = $this->jedoxapi->get_name($account_element_elements, $account_element);
		$account_element_set = $this->jedoxapi->array_element_filter_top($account_element_elements, $account_element_set);
		$removed = array_shift($account_element_set);
		$account_element_area = $this->jedoxapi->get_area($account_element_set);
		$account_element_set = $this->jedoxapi->set_alias($account_element_set, $cells_account_element_alias);
		
		//$account_element_ce2_full_area = $this->jedoxapi->get_area($account_element_ce2_full);
		
        $table_area = $version_actual_area.":".$version.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_area.",".$receiver_RP_area;
		$table_base = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $table_area, "", "1", "", "0");
	
		
		// Pass all data to send to view file
		$alldata = array(
			//regular vars here
			"year" => $year,
			"month" => $month,
			"version" => $version,
			"table_base" => $table_base,
			"cells_version_alias" => $cells_version_alias,
			"base_load" => $account_element_set,
			"receiver_RP" => $receiver_RP,
			"version_actual_area" => $version_actual_area,
			"receiver_RP_area" => $receiver_RP_area,
			"version_name" => $version_name,
			"account_element" => $account_element
			//trace vars here
		);
		// Pass data and show view
		$this->load->view("efficiency_costs_elements_view_ae", $alldata);
		
	}
	
	public function get_rp()
	{
		$user_details = $this->session->userdata('jedox_user_details');
		$account_element = $this->input->post("account_element");
		$year = $this->input->post("year");
        $month = $this->input->post("month");
        $version = $this->input->post("version");
		$receiver = $this->input->post("receiver");
		
		$database_name = $this->session->userdata('jedox_db');
		// Comma delimited cubenames to load. Cube names with #_ prefix are aliases cubes. No spaces.
		$cube_names = "Primary,#_Version,#_Month,#_Primary_Value,#_Account_Element,#_Receiver";
		
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
		//$secondary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Secondary");
		
		$primary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Primary");
		//$secondary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Secondary");
		
		////////////////////////////
		// Get Dimension elements //
		////////////////////////////
		
		// VERSION //
		// Get dimension of version
		$version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[0]);
		// Get cube data of version alias
		$version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
		$version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
		// Export cells of version alias
		$version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
		$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
		$cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
		
		// YEAR //
		// Get dimension of year
		$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[1]);
		
		// MONTH //
		// Get dimension of month
		$month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[2]);
		// Get cube data of month alias
		$month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
		$month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
		// Export cells of month alias
		$month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
		$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
		$cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
		
		// PRIMARY VALUE //
		// Get dimension of primary_value
		$primary_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[3]);
		// Get cube data of primary_value alias
		$primary_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Primary_Value");
		$primary_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Primary_Value");
		// Export cells of primary_value alias
		$primary_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_value_dimension_id[0]);
		$primary_value_alias_name_id = $this->jedoxapi->get_area($primary_value_alias_elements, "Name");
		$cells_primary_value_alias = $this->jedoxapi->cell_export($server_database['database'],$primary_value_alias_info['cube'],10000,"", $primary_value_alias_name_id.",*"); 
		
		// ACCOUNT ELEMENT //
		// Get dimension of account_element
		$account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[4]);
		// Get cube data of account_element alias
		$account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
		$account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
		// Export cells of account_element alias
		$account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
		$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
		$cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*"); 
		
		// RECEIVER //
		// Get dimension of receiver
		$receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[5]);
		// Get cube data of receiver alias
		$receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
		$receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
		// Export cells of receiver alias
		$receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
		$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
		$cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*"); 
		
		$receiver_alias_email_id = $this->jedoxapi->get_area($receiver_alias_elements, "Manager Email");
		$receiver_alias_manager_id = $this->jedoxapi->get_area($receiver_alias_elements, "Manager");
		
		$cells_receiver_email = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_email_id.",*"); 
		$cells_receiver_manager = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_manager_id.",*"); 
		
		///////////
		// TABLE //
        ///////////
        
        $version_actual_area = $this->jedoxapi->get_area($version_elements, "V002"); 
		$version_name = $this->jedoxapi->set_alias($version_elements, $cells_version_alias);
		$primary_value_PC_area = $this->jedoxapi->get_area($primary_value_elements, "PC");
		
		//$receiver_RP_area = $this->jedoxapi->get_area($receiver_elements, "RP");
		//$receiver_RP = $this->jedoxapi->dimension_sort_by_name($receiver_elements, "RP");
		//$receiver_RP = $this->jedoxapi->set_alias($receiver_RP, $cells_receiver_alias);
		$receiver_element_set = $this->jedoxapi->get_name($receiver_elements, $receiver);
		$receiver_element_set = $this->jedoxapi->array_element_filter_top($receiver_elements, $receiver_element_set);
		$removed_r = array_shift($receiver_element_set);
		$receiver_element_area = $this->jedoxapi->get_area($receiver_element_set);
		$receiver_element_set = $this->jedoxapi->set_alias($receiver_element_set, $cells_receiver_alias);
		
		
		//$account_element_alias = $this->jedoxapi->set_alias($account_element_elements, $cells_account_element_alias);
		$account_element_set = $this->jedoxapi->get_name($account_element_elements, $account_element);
		$account_element_set = $this->jedoxapi->dimension_sort_by_name($account_element_elements, $account_element_set);
		//$account_element_set = $this->jedoxapi->array_element_filter_top($account_element_elements, $account_element_set);
		//$removed = array_shift($account_element_set);
		//$account_element_area = $this->jedoxapi->get_area($account_element_set);
		$account_element_set = $this->jedoxapi->set_alias($account_element_set, $cells_account_element_alias);
		
		//$account_element_ce2_full_area = $this->jedoxapi->get_area($account_element_ce2_full);
		
        $table_area = $version_actual_area.":".$version.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element.",".$receiver_element_area;
		$table_base = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $table_area, "", "1", "", "0");
	
		$receiver_element_set = $this->attachnode("manager", $receiver_element_set, $cells_receiver_manager);
		$receiver_element_set = $this->attachnode("email", $receiver_element_set, $cells_receiver_email);
		
		$month_all_alias = $this->jedoxapi->set_alias($month_elements, $cells_month_alias);
		
		// Pass all data to send to view file
		$alldata = array(
			//regular vars here
			"year" => $year,
			"month" => $month,
			"version" => $version,
			"table_base" => $table_base,
			"cells_version_alias" => $cells_version_alias,
			"account_element_set" => $account_element_set,
			"base_load" => $receiver_element_set,
			"version_actual_area" => $version_actual_area,
			"receiver_element_area" => $receiver_element_area,
			"version_name" => $version_name,
			"account_element" => $account_element,
			"cells_receiver_email" => $cells_receiver_email,
			"cells_receiver_manager" => $cells_receiver_manager,
			"receiver" => $receiver,
			"year_elements" => $year_elements,
			"month_all_alias" => $month_all_alias,
			//trace vars here
		);
		// Pass data and show view
		$this->load->view("efficiency_costs_elements_view_rp", $alldata);
		
	}

	public function chart1 ()
    {
        $pagename = "proEO Efficiency Costs Elements";
        $oneliner = "One-liner here for Efficiency costs elements";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_costs") == FALSE)
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
            $cube_names = "Primary,#_Version,#_Month,#_Primary_Value,#_Account_Element,#_Receiver";
            
            // Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $version = $this->input->post("version");
            
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
            //$secondary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Secondary");
            
            $primary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Primary");
            //$secondary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Secondary");
            
            ////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
            // VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
            
            // YEAR //
            // Get dimension of year
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[1]);
            
            // MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
            
            // PRIMARY VALUE //
            // Get dimension of primary_value
            $primary_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[3]);
            // Get cube data of primary_value alias
            $primary_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Primary_Value");
            $primary_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Primary_Value");
            // Export cells of primary_value alias
            $primary_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_value_dimension_id[0]);
			$primary_value_alias_name_id = $this->jedoxapi->get_area($primary_value_alias_elements, "Name");
            $cells_primary_value_alias = $this->jedoxapi->cell_export($server_database['database'],$primary_value_alias_info['cube'],10000,"", $primary_value_alias_name_id.",*"); 
            
            // ACCOUNT ELEMENT // 
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[4]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*"); 
            
            // RECEIVER //
            // Get dimension of receiver
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[5]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*"); 
            
            // FORM DATA //
            $form_year = $year_elements;
            
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            $form_version = $this->jedoxapi->dimension_sort_by_name($version_elements, "V001,V003"); 
            $form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias); // Set aliases  
            
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
            if($version == '')
            {
                $version = $this->jedoxapi->get_area($version_elements, "V001"); 
            }
			
			///////////
			// TABLE //
            ///////////
            
            $version_actual_area = $this->jedoxapi->get_area($version_elements, "V002"); 
			$version_name = $this->jedoxapi->set_alias($version_elements, $cells_version_alias);
			$primary_value_PC_area = $this->jedoxapi->get_area($primary_value_elements, "PC");
			$receiver_RP_area = $this->jedoxapi->get_area($receiver_elements, "RP");
			$receiver_RP = $this->jedoxapi->dimension_sort_by_name($receiver_elements, "RP");
			$receiver_RP = $this->jedoxapi->set_alias($receiver_RP, $cells_receiver_alias);
			$account_element_ce2_area = $this->jedoxapi->get_area($account_element_elements, "CE_2");
			$account_element_ce2 = $this->jedoxapi->dimension_sort_by_name($account_element_elements, "CE_2");
			$account_element_ce2 = $this->jedoxapi->set_alias($account_element_ce2, $cells_account_element_alias);
			//$account_element_ce2_full_area = $this->jedoxapi->get_area($account_element_ce2_full);
			
            $table_area = $version_actual_area.":".$version.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_ce2_area.",".$receiver_RP_area;
			$table_base = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $table_area, "", "1", "", "0");
			
			////////////
			// CHARTS //
			////////////
			
			$month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
            $version_area_pa = $this->jedoxapi->get_area($version_elements, "V001,V002"); // Plan, Actual
            $version_elements_pa = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002");
            $version_elements_pa_alias = $this->jedoxapi->set_alias($version_elements_pa, $cells_version_alias);
            $version_area_a = $this->jedoxapi->get_area($version_elements, "V002"); // Actual
            $version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            $version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
			$primary_value_PC_area = $this->jedoxapi->get_area($primary_value_elements, "PC");
			$account_element_elements_CE_2_area = $this->jedoxapi->get_area($account_element_elements, "CE_2");
			$receiver = $this->jedoxapi->get_area($receiver_elements, "AR");
            
            $current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year); 
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
            
            $chart1xml = $this->jedox->multichart_xml_categories($month_all_alias, 1);
            
            $chart1b_area = $version_area_pa.",".$year.",".$month_all_area.",".$primary_value_PC_area.",".$account_element_elements_CE_2_area.",".$receiver;
            
            $chart1b_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1b_area, "", "1", "", "0");
            
            $chart1xml .= $this->jedox->multichart_xml_series($chart1b_cells, $month_all, $version_elements_pa_alias, 2, 0, "", " ".$current_year);
            
			// Area for previous year based on selected. Detect if there is a year before the "selected year"
            if($yearcheck != 0)
            {
                $chart1e_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$primary_value_PC_area.",".$account_element_elements_CE_2_area.",".$receiver;
                
                $chart1e_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1e_area, "", "1", "", "0");
                
                $chart1xml .= $this->jedox->multichart_xml_series($chart1e_cells, $month_all, $version_elements_a_alias, 2, 0, "", " ".$prev_year);
            }
			
			
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "form_year" => $form_year,
                "year" => $year,
                "form_months" => $form_months,
                "month" => $month,
                "form_version" => $form_version,
                "version" => $version,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "table_base" => $table_base,
                "cells_version_alias" => $cells_version_alias,
                "base_load" => $account_element_ce2,
                "receiver_RP" => $receiver_RP,
                "version_actual_area" => $version_actual_area,
                "receiver_RP_area" => $receiver_RP_area,
                "version_name" => $version_name,
                "chart1xml" => $chart1xml
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_costs_elements/chart1_view", $alldata);
        }
    }

	private function attachnode ($nodename, $basearray, $addedarray)
	{
		$data = array();
		foreach($basearray as $row){
			foreach ($addedarray as $mrow)
			{
				$path = explode(',', $mrow['path']);
				if($row['element'] == $path[1])
				{
					$row[$nodename] = $mrow['value'];
				}
			}
			$data[] = $row;
		}
		return $data;
	}
	
}