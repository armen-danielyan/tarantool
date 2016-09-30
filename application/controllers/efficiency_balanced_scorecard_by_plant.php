<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL);
class Efficiency_Balanced_Scorecard_By_Plant extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Efficiency Balance Scorecard";
        $oneliner = "One-liner here for Efficiency Balance Scorecard";
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
            
            $balance_scorecard_value = $this->input->post("balance_scorecard_value");
			$year = $this->input->post("year");
			$year1 = $this->input->post("year1");
			$year2 = $this->input->post("year2");
			//$version = $this->input->post("version");
            
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
            //$form_version = $this->jedoxapi->dimension_sort_by_name($version_elements, "V001,V002"); // plan actual
			//$form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias);
			
			//$form_year = $this->jedoxapi->array_element_filter($year_elements, "YA"); // all years
			$form_year = $this->jedoxapi->dimension_elements_base($year_elements); // all years only
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
			
			$form_balance_scorecard_value = $this->jedoxapi->array_element_filter($balance_scorecard_value_elements, "BSC");
			$form_balance_scorecard_value = $this->jedoxapi->set_alias($form_balance_scorecard_value, $cells_balance_scorecard_value_alias);
            
            /////////////
            // PRESETS //
            /////////////
			
			if($balance_scorecard_value == '')
			{
				$balance_scorecard_value = $this->jedoxapi->get_area($balance_scorecard_value_elements, "BSC_HSE_01"); // L! injuries
			}
			
			if($year == '')
            {
                $now = now();
                $tnow = mdate("%Y", $now);
                $year = $this->jedoxapi->get_area($year_elements, $tnow);
            }
			
			if($year1 == '')
			{
				//$year1_temp = $this->jedoxapi->dimension_elements_base($form_year);
				$year1_temp = $form_year[0];
				$year1 = $year1_temp['element'];
				//$year1 = $this->jedoxapi->get_area($year_elements, '2013');
			}
			
			if($year2 == '')
			{
				//$year2_temp = $this->jedoxapi->dimension_elements_base($form_year);
				$year2_temp = $form_year[1];
				$year2 = $year2_temp['element'];
				//$year2 = $this->jedoxapi->get_area($year_elements, '2014');
			}
			
			//if($version == '')
			//{
				//$version = $this->jedoxapi->get_area($version_elements, "V002"); // actual
			//}
            
            ////////////
            // TABLES //
            ////////////
            $monthMA = $this->jedoxapi->get_area($month_elements, "MA");
            $month1 = $this->jedoxapi->get_area($month_elements, "M01");
			$month2 = $this->jedoxapi->get_area($month_elements, "M02");
			$month3 = $this->jedoxapi->get_area($month_elements, "M03");
			$month4 = $this->jedoxapi->get_area($month_elements, "M04");
			$month5 = $this->jedoxapi->get_area($month_elements, "M05");
			$month6 = $this->jedoxapi->get_area($month_elements, "M06");
			$month7 = $this->jedoxapi->get_area($month_elements, "M07");
			$month8 = $this->jedoxapi->get_area($month_elements, "M08");
			$month9 = $this->jedoxapi->get_area($month_elements, "M09");
			$month10 = $this->jedoxapi->get_area($month_elements, "M10");
			$month11 = $this->jedoxapi->get_area($month_elements, "M11");
			$month12 = $this->jedoxapi->get_area($month_elements, "M12");
			
			$resource_elements_plants = $this->jedoxapi->dimension_sort_by_name($resource_elements, "CCG_PL180,CCG_PL450,CCG_PL500");
			$resource_elements_plants_area = $this->jedoxapi->get_area($resource_elements_plants);
			$resource_elements_plants_alias = $this->jedoxapi->set_alias($resource_elements_plants, $cells_resource_alias);
			
			$all_month_base_area = $month1.":".$month2.":".$month3.":".$month4.":".$month5.":".$month6.":".$month7.":".$month8.":".$month9.":".$month10.":".$month11.":".$month12;
            
			$version_p = $this->jedoxapi->get_area($version_elements, "V001"); //plan
			$version_a = $this->jedoxapi->get_area($version_elements, "V002"); //actual
			
			$table_area = $version_a.",".$year.",".$all_month_base_area.",".$balance_scorecard_value.",".$resource_elements_plants_area;
			$table_area_fy = $version_a.",".$year1.":".$year2.",".$monthMA.",".$balance_scorecard_value.",".$resource_elements_plants_area;
            $table_area_goal = $version_p.",".$year.",".$monthMA.",".$balance_scorecard_value.",".$resource_elements_plants_area;
			
            $table_data = $this->jedoxapi->cell_export($server_database['database'],$balance_scorecard_detail_cube_info['cube'],10000,"",$table_area, "", 1, "", "0");
			$table_data_fy = $this->jedoxapi->cell_export($server_database['database'],$balance_scorecard_detail_cube_info['cube'],10000,"",$table_area_fy, "", 1, "", "0");
			$table_data_goal = $this->jedoxapi->cell_export($server_database['database'],$balance_scorecard_detail_cube_info['cube'],10000,"",$table_area_goal, "", 1, "", "0");
			
            ////////////
			// CHARTS //
			////////////
            
            
			
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "form_year" => $form_year,
                //"form_version" => $form_version,
				"form_balance_scorecard_value" => $form_balance_scorecard_value,
				
                "balance_scorecard_value" => $balance_scorecard_value,
                "year" => $year,
                "year1" => $year1,
                "year2" => $year2,
                //"version" => $version,
                "table_data" => $table_data,
                "table_data_fy" => $table_data_fy,
                "table_data_goal" => $table_data_goal,
                
                "resource_elements_plants_alias" => $resource_elements_plants_alias,
                "month1" => $month1,
                "month2" => $month2,
                "month3" => $month3,
                "month4" => $month4,
                "month5" => $month5,
                "month6" => $month6,
                "month7" => $month7,
                "month8" => $month8,
                "month9" => $month9,
                "month10" => $month10,
                "month11" => $month11,
                "month12" => $month12
                
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_balanced_scorecard_by_plant_view", $alldata);
        }
    }
	
}