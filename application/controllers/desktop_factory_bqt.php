<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Desktop_Factory_Bqt extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "";
        $oneliner = "";
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
			$quality_test = $this->input->post("quality_test");
			$quality_value1 = $this->input->post("quality_value1");
			$quality_value2 = $this->input->post("quality_value2");
			$quality_value3 = $this->input->post("quality_value3");
			$quality_value4 = $this->input->post("quality_value4"); // comment
			
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
			
			$form_source1 = $this->jedoxapi->array_element_filter($source_elements, "QS");
			$form_source2 = $this->jedoxapi->array_element_filter($source_elements, "EQ");
			$form_source = array_merge($form_source1, $form_source2);
			$form_source = $this->jedoxapi->set_alias($form_source, $cells_source_alias);
			
			$form_product = $this->jedoxapi->array_element_filter($product_elements, "AP");
			//array_shift($form_product);
			$form_product = $this->jedoxapi->set_alias($form_product, $cells_product_alias);
			
			$form_quality_test1 = $this->jedoxapi->array_element_filter($quality_test_elements, "QT_WA");
			$form_quality_test2 = $this->jedoxapi->array_element_filter($quality_test_elements, "QT_FT");
			$form_quality_test3 = $this->jedoxapi->array_element_filter($quality_test_elements, "QT_BT");
			$form_quality_test4 = $this->jedoxapi->array_element_filter($quality_test_elements, "QT_PA");
			$form_quality_test = array_merge($form_quality_test1, $form_quality_test2, $form_quality_test3, $form_quality_test4);
			$form_quality_test = $this->jedoxapi->set_alias($form_quality_test, $cells_quality_test_alias);
			
			
			
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
			$quality_value_co_01 = $this->jedoxapi->get_area($quality_value_elements, "CO_01");
			$quality_value_qv_01 = $this->jedoxapi->get_area($quality_value_elements, "QV_01");
			$quality_value_qv_02 = $this->jedoxapi->get_area($quality_value_elements, "QV_02");
			$quality_value_qv_03 = $this->jedoxapi->get_area($quality_value_elements, "QV_03");
			
			
			// save entries //
			//echo $bf;
			if($bf != '')
			{
				//echo $bf;
				
				
				// save comment
				$quality_value4 = urlencode($quality_value4);
				$path1 = $version.",".$year.",".$month.",".$quality_value_co_01.",".$source.",".$product.",".$quality_test;
				$save1 = $this->jedoxapi->cell_replace($server_database['database'], $quality_cube_info['cube'], $path1, $quality_value4);
				
				//1
				$quality_value1 = urlencode($quality_value1);
				$path2 = $version.",".$year.",".$month.",".$quality_value_qv_01.",".$source.",".$product.",".$quality_test;
				$save2 = $this->jedoxapi->cell_replace($server_database['database'], $quality_cube_info['cube'], $path2, $quality_value1);
				
				//2
				$quality_value2 = urlencode($quality_value2);
				$path3 = $version.",".$year.",".$month.",".$quality_value_qv_02.",".$source.",".$product.",".$quality_test;
				$save3 = $this->jedoxapi->cell_replace($server_database['database'], $quality_cube_info['cube'], $path3, $quality_value2);
				
				//3
				$quality_value3 = urlencode($quality_value3);
				$path4 = $version.",".$year.",".$month.",".$quality_value_qv_03.",".$source.",".$product.",".$quality_test;
				$save4 = $this->jedoxapi->cell_replace($server_database['database'], $quality_cube_info['cube'], $path4, $quality_value3);
				
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
                "form_quality_test" => $form_quality_test,
                "year" => $year,
                "month" => $month,
                "source" => $source,
                "product" => $product,
                "quality_test" => $quality_test
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("desktop_factory_bqt_view", $alldata);
        }
    }


}