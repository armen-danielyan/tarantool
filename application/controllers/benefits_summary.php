<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL);

class Benefits_Summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index ()
    {
        $pagename = "proEO Benefits Summary";
        $oneliner = "One-liner here for Benefits Summary";
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
            $cube_names = "Benefit,#_Project,#_Year,#_Value_Element,#_Customer,#_Benefit_Element,#_Cost_of_Goods";
            
            // Initialize post data //
            $year = $this->input->post("year");
            $customer = $this->input->post("customer");
            $project = $this->input->post("project");
            
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
            $benefit_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Benefit");
            
            $benefit_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Benefit"); 
            
            ////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
            // Project //
            // Get dimension of project
            $project_elements = $this->jedoxapi->dimension_elements($server_database['database'], $benefit_dimension_id[0]);
            // Get cube data of project alias
            $project_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Project");
            $project_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Project");
            // Export cells of project alias
            $project_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $project_dimension_id[0]);
			$project_alias_name_id = $this->jedoxapi->get_area($project_alias_elements, "Name");
            $cells_project_alias = $this->jedoxapi->cell_export($server_database['database'],$project_alias_info['cube'],10000,"",$project_alias_name_id.",*");
            
            // YEAR //
            // Get dimension of year
            //$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $benefit_dimension_id[1]);
			$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $benefit_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
            
            // Value_Element //
            // Get dimension of Value_Element
            $value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $benefit_dimension_id[2]);
            // Get cube data of Value_Element alias
            $value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Value_Element");
            $value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Value_Element");
            // Export cells of Value_Element alias
            $value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $value_dimension_id[0]);
			$value_alias_name_id = $this->jedoxapi->get_area($value_alias_elements, "Name");
            $cells_value_alias = $this->jedoxapi->cell_export($server_database['database'],$value_alias_info['cube'],10000,"", $value_alias_name_id.",*");
            
            // Customer //
            // Get dimension of Customer
            $customer_elements = $this->jedoxapi->dimension_elements($server_database['database'], $benefit_dimension_id[3]);
            // Get cube data of Customer alias
            $customer_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Customer");
            $customer_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Customer");
            // Export cells of Customer alias
            $customer_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $customer_dimension_id[0]);
			$customer_alias_name_id = $this->jedoxapi->get_area($customer_alias_elements, "Name");
            $cells_customer_alias = $this->jedoxapi->cell_export($server_database['database'],$customer_alias_info['cube'],10000,"", $customer_alias_name_id.",*"); 
            
            // Benefit_Element //
            // Get dimension of Benefit_Element
            $benefit_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $benefit_dimension_id[4]);
            // Get cube data of Benefit_Element alias
            $benefit_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Benefit_Element");
            $benefit_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Benefit_Element");
            // Export cells of Benefit_Element alias
            $benefit_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $benefit_element_dimension_id[0]);
			$benefit_element_alias_name_id = $this->jedoxapi->get_area($benefit_element_alias_elements, "Name");
            $cells_benefit_element_alias = $this->jedoxapi->cell_export($server_database['database'],$benefit_element_alias_info['cube'],10000,"", $benefit_element_alias_name_id.",*"); 
            
            // Cost_of_Goods //
            // Get dimension of Cost_of_Goods
            $cost_of_goods_elements = $this->jedoxapi->dimension_elements($server_database['database'], $benefit_dimension_id[5]);
            // Get cube data of Cost_of_Goods alias
            $cost_of_goods_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Cost_of_Goods");
            $cost_of_goods_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Cost_of_Goods");
            // Export cells of cost_of_goods alias
            $cost_of_goods_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $cost_of_goods_dimension_id[0]);
			$cost_of_goods_alias_name_id = $this->jedoxapi->get_area($cost_of_goods_alias_elements, "Name");
            $cells_cost_of_goods_alias = $this->jedoxapi->cell_export($server_database['database'],$cost_of_goods_alias_info['cube'],10000,"", $cost_of_goods_alias_name_id.",*"); 
            
            
            // FORM DATA //
            $form_year = $this->jedoxapi->array_element_filter($year_elements, "YA"); // all years
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            $form_customer = $this->jedoxapi->array_element_filter($customer_elements, "CA"); // All customer
            $form_customer = $this->jedoxapi->set_alias($form_customer, $cells_customer_alias); // Set aliases
            
            $form_project = $this->jedoxapi->array_element_filter($project_elements, "PA"); 
            $form_project = $this->jedoxapi->set_alias($form_project, $cells_project_alias); // Set aliases
            
            //$this->jedoxapi->traceme($form_customer, "customer");
			//$this->jedoxapi->traceme($form_project, "project");
			
			
            
            /////////////
            // PRESETS //
            /////////////
            
            if($year == '')
            {
                $now = now();
                $tnow = mdate("%Y", $now);
                $year = $this->jedoxapi->get_area($year_elements, $tnow);
            }
            if($customer == '')
            {
                $customer = $this->jedoxapi->get_area($customer_elements, "CA");
            }
            if($project == '')
            {
                $project = $this->jedoxapi->get_area($project_elements, "PA");
            }
            
            ////////////////
            // table data //
            ////////////////
            
			$value_elements_ve_300 = $this->jedoxapi->get_area($value_elements, "VE_300");
			$value_elements_ve_900 = $this->jedoxapi->get_area($value_elements, "VE_900");
			
			$benefit_element_set = $this->jedoxapi->array_element_filter($benefit_element_elements, "BE");
			$benefit_element_set_area = $this->jedoxapi->get_area($benefit_element_set);
			$benefit_element_set_alias = $this->jedoxapi->set_alias($benefit_element_set, $cells_benefit_element_alias);
			
			$cost_of_goods_elements_set = $this->jedoxapi->array_element_filter($cost_of_goods_elements, "CG");
			$cost_of_goods_elements_set_area = $this->jedoxapi->get_area($cost_of_goods_elements_set);
			$cost_of_goods_elements_set_alias = $this->jedoxapi->set_alias($cost_of_goods_elements_set, $cells_cost_of_goods_alias);
			//array_shift($cost_of_goods_elements_set_alias);
			
			$cost_of_goods_elements_cg = $this->jedoxapi->get_area($cost_of_goods_elements, "CG");
			
			//additional post data
			
			$Update = $this->input->post("Update");
			
			if($Update == "Update")
			{
				echo "update";
			}
			
            $tc_area = $project.",".$year.",".$value_elements_ve_300.",".$customer.",".$benefit_element_set_area.",".$cost_of_goods_elements_set_area;
            
            $tc_cells = $this->jedoxapi->cell_export($server_database['database'], $benefit_cube_info['cube'], 10000, "", $tc_area, "", "1", "", "0");
            
			// for dropdowns
			$tc_area2 = $project.",".$year.",".$value_elements_ve_900.",".$customer.",".$benefit_element_set_area.",".$cost_of_goods_elements_set_area;
            
            $tc_cells2 = $this->jedoxapi->cell_export($server_database['database'], $benefit_cube_info['cube'], 10000, "", $tc_area2, "", "1", "", "0");
			
			//$this->jedoxapi->traceme($tc_cells);
			
			//echo $value_elements_ve_900;
			
            ///////////
            // CHART //
            ///////////
            
            
            
            
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "form_year" => $form_year,
                "year" => $year,
                "form_customer" => $form_customer,
                "customer" => $customer,
                "form_project" => $form_project,
                "project" => $project,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "tc_cells" => $tc_cells,
                "tc_cells2" => $tc_cells2,
                "benefit_element_set_alias" => $benefit_element_set_alias,
                "cost_of_goods_elements_set_alias" => $cost_of_goods_elements_set_alias,
                "cost_of_goods_elements_cg" => $cost_of_goods_elements_cg
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("benefits_summary_view", $alldata);
        }
    }
    
	
	public function gupdate()
	{
		
		// Initialize variables //
        $database_name = $this->session->userdata('jedox_db');
        // Comma delimited cubenames to load. Cube names with #_ prefix are aliases cubes. No spaces. 
        $cube_names = "Benefit,#_Project,#_Year,#_Value_Element,#_Customer,#_Benefit_Element,#_Cost_of_Goods";
		
		$ddvalue = $this->input->post("ddvalue");
		$ddpath = $this->input->post("ddpath");
		
		// Login. need to relogin to prevent timeout
        $server_login = $this->jedoxapi->server_login($this->session->userdata('jedox_user'), $this->session->userdata('jedox_pass'));
            
        // Get Database
        $server_database_list = $this->jedoxapi->server_databases();
        $server_database = $this->jedoxapi->server_databases_select($server_database_list, $database_name);
		
		// Get Cubes
        $database_cubes = $this->jedoxapi->database_cubes($server_database['database'], 1,0,1);
		
		// Get Dimensions ids.
        $benefit_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Benefit");
            
        $benefit_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Benefit"); 
		
		$result = $this->jedoxapi->cell_replace( $server_database['database'], $benefit_cube_info['cube'], $ddpath, $ddvalue );
		//print_r($ddvalue."---".$ddpath);
	}
	
	
}