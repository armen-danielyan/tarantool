<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL);
class Efficiency_Balanced_Scorecard_Input extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Efficiency Balance Scorecard Input";
        $oneliner = "One-liner here for Efficiency Balance Scorecard Input";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_resources") == FALSE) //no specific perm yet
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
            $cube_names = "Balanced_Scorecard,#_Version,#_Year,#_Month,#_Balanced_Scorecard_Value,#_Resource";
			
			// Initialize post data //
			$version = $this->input->post('version');
			$year = $this->input->post('year');
			$month = $this->input->post('month');
			$balance_scorecard_value = $this->input->post('balance_scorecard_value');
			$resource = $this->input->post('resource');
			
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
            $balance_scorecard_detail_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Balanced_Scorecard");
			
			$balance_scorecard_detail_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Balanced_Scorecard");
            
            //////////////////////////// 
            // Get Dimension elements //
            ////////////////////////////
            
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $balance_scorecard_detail_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// YEAR //
			// Get dimension of year
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $balance_scorecard_detail_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
            
			// MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $balance_scorecard_detail_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
            
			// Balance_Scorecard_Value //
            // Get dimension of balance_scorecard_value
            $balance_scorecard_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $balance_scorecard_detail_dimension_id[3]);
            // Get cube data of balance_scorecard_value alias
            $balance_scorecard_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Balanced_Scorecard_Value");
            $balance_scorecard_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Balanced_Scorecard_Value");
            // Export cells of balance_scorecard_value alias
            $balance_scorecard_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $balance_scorecard_value_dimension_id[0]);
			$balance_scorecard_value_alias_name_id = $this->jedoxapi->get_area($balance_scorecard_value_alias_elements, "Name");
            $cells_balance_scorecard_value_alias = $this->jedoxapi->cell_export($server_database['database'],$balance_scorecard_value_alias_info['cube'],10000,"", $balance_scorecard_value_alias_name_id.",*");
            
			
            // resource //
            // Get dimension of resource
            $resource_elements = $this->jedoxapi->dimension_elements($server_database['database'], $balance_scorecard_detail_dimension_id[4]);
            // Get cube data of resource alias
            $resource_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Resource");
            $resource_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Resource");
            // Export cells of resource alias
            $resource_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $resource_dimension_id[0]);
			$resource_alias_name_id = $this->jedoxapi->get_area($resource_alias_elements, "Name");
            $cells_resource_alias = $this->jedoxapi->cell_export($server_database['database'],$resource_alias_info['cube'],10000,"", $resource_alias_name_id.",*");
			
			// FORM DATA //
			
			$form_version = $this->jedoxapi->dimension_sort_by_name($version_elements, 'V001,V002'); // plan actual
			$form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias);
			
			$form_year = $this->jedoxapi->dimension_elements_base($year_elements); // all years only
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
			
			$form_month = $this->jedoxapi->dimension_elements_base($month_elements); // base months
			$form_month = $this->jedoxapi->set_alias($form_month, $cells_month_alias);
			
			$form_balance_scorecard_value = $this->jedoxapi->dimension_sort_by_name($balance_scorecard_value_elements, "BSC_HSE,BSC_PEO,BSC_PRO,BSC_REL,BSC_QUA,BSC_TEC"); // no cost control
			$form_balance_scorecard_value = $this->jedoxapi->set_alias($form_balance_scorecard_value, $cells_balance_scorecard_value_alias);
			
			$form_resource = $this->jedoxapi->array_element_filter($resource_elements, "RP");
			//$form_resource = $this->jedoxapi->dimension_sort_by_name($resource_elements, "RP,CCG_RG1,CCG_PL11,CCG_RG2,CCG_PL21,CCG_PL22"); // limit to 3 plants
			$form_resource = $this->dimension_elements_notbase($form_resource);
			$form_resource = $this->jedoxapi->set_alias($form_resource, $cells_resource_alias);
			
			/////////////
            // PRESETS //
            /////////////  
			
			if($resource == '')
			{
				$resource = $this->jedoxapi->get_area($resource_elements, "RP");
			}
			
			if($year == '')
            {
                $now = now();
                $tnow = mdate("%Y", $now);
                $year = $this->jedoxapi->get_area($year_elements, $tnow);
            }
			
			if($version == '')
			{
				$version = $this->jedoxapi->get_area($version_elements, "V002"); // actual
			}
			
			if($month == '')
			{
				$month = $this->jedoxapi->get_area($month_elements, "M01");
			}
			
			if($balance_scorecard_value == '')
			{
				$balance_scorecard_value = $this->jedoxapi->get_area($balance_scorecard_value_elements, "BSC_HSE");
			}
			
			////////////
            // TABLES //
            ////////////
            
            $balanced_scorecard_base = $this->jedoxapi->get_name($balance_scorecard_value_elements, $balance_scorecard_value);
			$balanced_scorecard_base = $this->jedoxapi->array_element_filter($balance_scorecard_value_elements, $balanced_scorecard_base);
			$balanced_scorecard_base = $this->jedoxapi->dimension_elements_base($balanced_scorecard_base);
			$balanced_scorecard_base_area = $this->jedoxapi->get_area($balanced_scorecard_base);
			$balanced_scorecard_base_alias = $this->jedoxapi->set_alias($balanced_scorecard_base, $cells_balance_scorecard_value_alias);
			
            
            $table_area = $version.",".$year.",".$month.",".$balanced_scorecard_base_area.",".$resource;
			
			$table_data = $this->jedoxapi->cell_export($server_database['database'],$balance_scorecard_detail_cube_info['cube'],10000,"",$table_area, "", 1, "", "0");
			
			// Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "version" => $version,
                "year" => $year,
                "month" => $month,
                "balance_scorecard_value" => $balance_scorecard_value,
                "resource" => $resource,
                "form_version" => $form_version,
                "form_year" => $form_year,
                "form_month" => $form_month,
                "form_balance_scorecard_value" => $form_balance_scorecard_value,
                "form_resource" => $form_resource,
                "table_data" => $table_data,
                "balanced_scorecard_base_alias" => $balanced_scorecard_base_alias
                
                
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_balanced_scorecard_input_view", $alldata);
        }
    }
	
	function grun()
	{
		// Initialize variables //
        $database_name = $this->session->userdata('jedox_db');
        // Comma delimited cubenames to load. Cube names with #_ prefix are aliases cubes. No spaces.
        $cube_names = "Balanced_Scorecard,#_Version,#_Year,#_Month,#_Balanced_Scorecard_Value,#_Resource";
        
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
        $balance_scorecard_detail_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Balanced_Scorecard");
		
		$balance_scorecard_detail_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Balanced_Scorecard");
		
		$path = $this->input->post('path');
		$val = $this->input->post('val');
		
		//echo "path= ".$path." val= ".$val;
		
		$result = $this->jedoxapi->cell_replace($server_database['database'], $balance_scorecard_detail_cube_info['cube'], $path, $val);
		
		print_r ($result);
		
	}
	public function dimension_elements_notbase($array)
    {
        $result_array = array();
        foreach($array as $row)
        {
            if($row['number_children'] != 0)
            {
                $result_array[] = $row;
            }
        }
        return $result_array;
    }
}