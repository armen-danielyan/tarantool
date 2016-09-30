<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Desktop_Factory_Tpo extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Desktop Factory TPO";
        $oneliner = "One-liner here for tpo";
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
            $cube_names = "Quality,#_Version,#_Year,#_Month_Time,#_Quality_Value,#_Source,#_Product,#_Quality_Test";
			
			// Initialize post data
			$year = $this->input->post("year");
            $month = $this->input->post("month");
			$source = $this->input->post("source");
            $product = $this->input->post("product");
			
			$tpo = $this->input->post("tpo");
			
			$bf = $this->input->post("bf"); // form button
			
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
            $quality_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Quality");
			
			$quality_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Quality");
			
			//////////////////////////// 
            // Get Dimension elements //
            ////////////////////////////
            
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $quality_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// YEAR //
			// Get dimension of year
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $quality_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
            
			// MONTH_TIME //
            // Get dimension of month_time
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $quality_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month_Time");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month_Time");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name"); 
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
            
			// quality_Value //
            // Get dimension of quality_value
            $quality_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $quality_dimension_id[3]);
            // Get cube data of quality_value alias
            $quality_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Quality_Value");
            $quality_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Quality_Value");
            // Export cells of quality_value alias
            $quality_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $quality_value_dimension_id[0]);
			$quality_value_alias_name_id = $this->jedoxapi->get_area($quality_value_alias_elements, "Name");
            $cells_quality_value_alias = $this->jedoxapi->cell_export($server_database['database'],$quality_value_alias_info['cube'],10000,"", $quality_value_alias_name_id.",*");
			
			// source //
            // Get dimension of source
            $source_elements = $this->jedoxapi->dimension_elements($server_database['database'], $quality_dimension_id[4]);
            // Get cube data of source alias
            $source_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Source");
            $source_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Source");
            // Export cells of source alias
            $source_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $source_dimension_id[0]);
			$source_alias_name_id = $this->jedoxapi->get_area($source_alias_elements, "Name");
            $cells_source_alias = $this->jedoxapi->cell_export($server_database['database'],$source_alias_info['cube'],10000,"", $source_alias_name_id.",*");
			
			// product //
            // Get dimension of product
            $product_elements = $this->jedoxapi->dimension_elements($server_database['database'], $quality_dimension_id[5]);
            // Get cube data of product alias 
            $product_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Product");
            $product_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Product");
            // Export cells of product alias
            $product_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_dimension_id[0]);
			$product_alias_name_id = $this->jedoxapi->get_area($product_alias_elements, "Name");
            $cells_product_alias = $this->jedoxapi->cell_export($server_database['database'],$product_alias_info['cube'],10000,"", $product_alias_name_id.",*");
			
			// quality_test //
            // Get dimension of quality_test
            $quality_test_elements = $this->jedoxapi->dimension_elements($server_database['database'], $quality_dimension_id[6]);
            // Get cube data of quality_test alias
            $quality_test_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Quality_Test");
            $quality_test_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Quality_Test");
            // Export cells of quality_test alias
            $quality_test_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $quality_test_dimension_id[0]);
			$quality_test_alias_name_id = $this->jedoxapi->get_area($quality_test_alias_elements, "Name");
            $cells_quality_test_alias = $this->jedoxapi->cell_export($server_database['database'],$quality_test_alias_info['cube'],10000,"", $quality_test_alias_name_id.",*");
			
			// FORM DATA // 
            //$form_year = $this->jedoxapi->array_element_filter($year_elements, "YA"); // all years 
            $form_year = $this->jedoxapi->dimension_elements_base($year_elements);
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
			
			$form_month = $this->jedoxapi->dimension_elements_base($month_elements);
			$form_month = $this->jedoxapi->set_alias($form_month, $cells_month_alias);
			//$this->jedoxapi->traceme($form_month);
			
			$form_source = $this->jedoxapi->array_element_filter($source_elements, "EQ_PA_01");
			$form_source = $this->jedoxapi->set_alias($form_source, $cells_source_alias);
			
			$form_product = $this->jedoxapi->array_element_filter($product_elements, "FP");
			$form_product = $this->jedoxapi->set_alias($form_product, $cells_product_alias);
			
			
			
			/////////////
            // PRESETS //
            /////////////
            
            if($year == '')
            {
                $now = now();
                $tnow = mdate("%Y", $now);
				//$this->jedoxapi->traceme($tnow);
                $year = $this->jedoxapi->get_area($year_elements, $tnow);
            }
			if($month == '')
			{
				$now = now();
                $tnow = mdate("M%m-D%d", $now);
				//$this->jedoxapi->traceme($tnow);
				$month = $this->jedoxapi->get_area($month_elements, $tnow);
			}
			
			$version = $this->jedoxapi->get_area($version_elements, "V002");
			
			$quality_value_qv_01 = $this->jedoxapi->get_area($quality_value_elements, "QV_01");
			
			$quality_test_qt_pa_03 = $this->jedoxapi->get_area($quality_test_elements, "QT_PA_03");
			//$this->jedoxapi->traceme($quality_value_co_01);
			//$this->jedoxapi->traceme($quality_value_qv_01);
			// save entries //
			
			if($bf != '')
			{
				// save tpo
				//echo "tpo being saved! tpo is ".$tpo;
				$tpo = urlencode($tpo);
				$path1 = $version.",".$year.",".$month.",".$quality_value_qv_01.",".$source.",".$product.",".$quality_test_qt_pa_03;
				$save1 = $this->jedoxapi->cell_replace($server_database['database'], $quality_cube_info['cube'], $path1, $tpo);
				
			}
			
			
			// Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "form_year" => $form_year,
                "form_source" => $form_source,
                "form_month" => $form_month,
                "form_product" => $form_product,
                "year" => $year,
                "month" => $month,
                "source" => $source,
                "product" => $product
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("desktop_factory_tpo_view", $alldata);
        }
    }


}