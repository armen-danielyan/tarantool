<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taste_Test_Management extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Taste Test Management";
        $oneliner = "One-liner here for Taste Test Management";
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
            $product = $this->input->post("product");
			$source = $this->input->post("source");
			
			
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
			
			$form_product = $this->jedoxapi->array_element_filter($product_elements, "SF");
			array_shift($form_product);
			$form_product = $this->jedoxapi->set_alias($form_product, $cells_product_alias);
			
			$form_source = $this->jedoxapi->array_element_filter($source_elements, "QS_TT");
			//array_shift($form_source);
			$form_source = $this->jedoxapi->set_alias($form_source, $cells_source_alias);
			
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
			
			if($product == '')
			{
				$product = $form_product[0]['element'];
			}
			
			if($source == '')
			{
				$source = $form_source[0]['element'];
			}
			
			$source_set = $this->jedoxapi->array_element_filter($source_elements, "QS_TT");
			array_shift($source_set);
			$source_set_area = $this->jedoxapi->get_area($source_set);
			$source_set_alias = $this->jedoxapi->set_alias($source_set, $cells_source_alias);
			
			$version = $this->jedoxapi->get_area($version_elements, "V002");
			$quality_value_co_01 = $this->jedoxapi->get_area($quality_value_elements, "CO_01"); // comment
			$quality_value_qv_01 = $this->jedoxapi->get_area($quality_value_elements, "QV_01");
			$quality_test_qt_tt_c01 = $this->jedoxapi->get_area($quality_test_elements, "QT_TT_C01");
			
			
			$quality_test_qt_tt_p01_01 = $this->jedoxapi->get_area($quality_test_elements, "QT_TT_P01_01");
			$quality_test_qt_tt_p01_02 = $this->jedoxapi->get_area($quality_test_elements, "QT_TT_P01_02");
			$quality_test_qt_tt_p01_03 = $this->jedoxapi->get_area($quality_test_elements, "QT_TT_P01_03");
			$quality_test_qt_tt_p07_01 = $this->jedoxapi->get_area($quality_test_elements, "QT_TT_P07_01");
			$quality_test_qt_tt_p07_02 = $this->jedoxapi->get_area($quality_test_elements, "QT_TT_P07_02");
			
			$quality_test_qt_tt_p01 = $this->jedoxapi->get_area($quality_test_elements, "QT_TT_P01");
			$quality_test_qt_tt_p07 = $this->jedoxapi->get_area($quality_test_elements, "QT_TT_P07");
			
			$qt_area = $this->jedoxapi->get_area($quality_test_elements, "QT_TT_P01_01,QT_TT_P01_02,QT_TT_P01_03,QT_TT_P07_01,QT_TT_P07_02");
			$qt_area1 = $this->jedoxapi->get_area($quality_test_elements, "QT_TT_P01_01,QT_TT_P01_02,QT_TT_P01_03,QT_TT_P07_01,QT_TT_P07,QT_TT_P01");
			
			$form_product_area = $this->jedoxapi->get_area($form_product);
			$source_qs = $this->jedoxapi->get_area($source_elements, "QS");
			// taste table start//
			// areas //
			
			//comment
			$path1 = $version.",".$year.",".$month.",".$quality_value_co_01.",".$source_set_area.",".$product.",".$quality_test_qt_tt_c01;
			//other data
			$path2 = $version.",".$year.",".$month.",".$quality_value_qv_01.",".$source_set_area.",".$product.",".$qt_area ;
			
			$table1_data = $this->jedoxapi->cell_export($server_database['database'],$quality_cube_info['cube'],10000,"",$path1, "", 1, "", "0");
			$table2_data = $this->jedoxapi->cell_export($server_database['database'],$quality_cube_info['cube'],10000,"",$path2, "", 1, "", "0");
			
			// taste table end //
			
			// product table start //
			// areas
			
			$path3 = $version.",".$year.",".$month.",".$quality_value_qv_01.",".$source_qs.",".$form_product_area.",".$qt_area1;
			$table3_data = $this->jedoxapi->cell_export($server_database['database'],$quality_cube_info['cube'],10000,"",$path3, "", 1, "", "0");
			
			// product table end //
			
			////////////
			// CHARTS //
			////////////
			
			$version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            $version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
			
			$quality_test_qt_tt_p02_set = $this->jedoxapi->array_element_filter($quality_test_elements, "QT_TT_P02");
			array_shift($quality_test_qt_tt_p02_set);
			$quality_test_qt_tt_p02_set_alias = $this->jedoxapi->set_alias($quality_test_qt_tt_p02_set, $cells_quality_test_alias);
			$quality_test_qt_tt_p02_set_area = $this->jedoxapi->get_area($quality_test_qt_tt_p02_set_alias);
			
			$quality_test_qt_tt_p03_set = $this->jedoxapi->array_element_filter($quality_test_elements, "QT_TT_P03");
			array_shift($quality_test_qt_tt_p03_set);
			$quality_test_qt_tt_p03_set_alias = $this->jedoxapi->set_alias($quality_test_qt_tt_p03_set, $cells_quality_test_alias);
			$quality_test_qt_tt_p03_set_area = $this->jedoxapi->get_area($quality_test_qt_tt_p03_set_alias);
			
			$quality_test_qt_tt_p04_set = $this->jedoxapi->array_element_filter($quality_test_elements, "QT_TT_P04");
			array_shift($quality_test_qt_tt_p04_set);
			$quality_test_qt_tt_p04_set_alias = $this->jedoxapi->set_alias($quality_test_qt_tt_p04_set, $cells_quality_test_alias);
			$quality_test_qt_tt_p04_set_area = $this->jedoxapi->get_area($quality_test_qt_tt_p04_set_alias);
			
			$quality_test_qt_tt_p05_set = $this->jedoxapi->array_element_filter($quality_test_elements, "QT_TT_P05");
			array_shift($quality_test_qt_tt_p05_set);
			$quality_test_qt_tt_p05_set_alias = $this->jedoxapi->set_alias($quality_test_qt_tt_p05_set, $cells_quality_test_alias);
			$quality_test_qt_tt_p05_set_area = $this->jedoxapi->get_area($quality_test_qt_tt_p05_set_alias);
			
			$quality_test_qt_tt_p06_set = $this->jedoxapi->array_element_filter($quality_test_elements, "QT_TT_P06");
			array_shift($quality_test_qt_tt_p06_set);
			$quality_test_qt_tt_p06_set_alias = $this->jedoxapi->set_alias($quality_test_qt_tt_p06_set, $cells_quality_test_alias);
			$quality_test_qt_tt_p06_set_area = $this->jedoxapi->get_area($quality_test_qt_tt_p06_set_alias);
			
			$quality_test_qt_tt_v_set = $this->jedoxapi->array_element_filter($quality_test_elements, "QT_TT_V");
			array_shift($quality_test_qt_tt_v_set);
			$quality_test_qt_tt_v_set_alias = $this->jedoxapi->set_alias($quality_test_qt_tt_v_set, $cells_quality_test_alias);
			$quality_test_qt_tt_v_set_area = $this->jedoxapi->get_area($quality_test_qt_tt_v_set_alias);
			
			$chart1 = $this->jedox->multichart_xml_categories($quality_test_qt_tt_p02_set_alias);
			$chart2 = $this->jedox->multichart_xml_categories($quality_test_qt_tt_p03_set_alias);
			$chart3 = $this->jedox->multichart_xml_categories($quality_test_qt_tt_p04_set_alias);
			$chart4 = $this->jedox->multichart_xml_categories($quality_test_qt_tt_p05_set_alias);
			$chart5 = $this->jedox->multichart_xml_categories($quality_test_qt_tt_p06_set_alias);
			$chart6 = $this->jedox->multichart_xml_categories($quality_test_qt_tt_v_set_alias);
			
			$chart1_area = $version.",".$year.",".$month.",".$quality_value_qv_01.",".$source.",".$product.",".$quality_test_qt_tt_p02_set_area; 
			$chart1_data = $this->jedoxapi->cell_export($server_database['database'],$quality_cube_info['cube'],10000,"",$chart1_area, "", 1, "", "0");
			
			$chart2_area = $version.",".$year.",".$month.",".$quality_value_qv_01.",".$source.",".$product.",".$quality_test_qt_tt_p03_set_area; 
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'],$quality_cube_info['cube'],10000,"",$chart2_area, "", 1, "", "0");
			
			$chart3_area = $version.",".$year.",".$month.",".$quality_value_qv_01.",".$source.",".$product.",".$quality_test_qt_tt_p04_set_area; 
			$chart3_data = $this->jedoxapi->cell_export($server_database['database'],$quality_cube_info['cube'],10000,"",$chart3_area, "", 1, "", "0");
			
			$chart4_area = $version.",".$year.",".$month.",".$quality_value_qv_01.",".$source.",".$product.",".$quality_test_qt_tt_p05_set_area; 
			$chart4_data = $this->jedoxapi->cell_export($server_database['database'],$quality_cube_info['cube'],10000,"",$chart4_area, "", 1, "", "0");
			
			$chart5_area = $version.",".$year.",".$month.",".$quality_value_qv_01.",".$source.",".$product.",".$quality_test_qt_tt_p06_set_area; 
			$chart5_data = $this->jedoxapi->cell_export($server_database['database'],$quality_cube_info['cube'],10000,"",$chart5_area, "", 1, "", "0");
			
			$chart6_area = $version.",".$year.",".$month.",".$quality_value_qv_01.",".$source.",".$product.",".$quality_test_qt_tt_v_set_area; 
			$chart6_data = $this->jedoxapi->cell_export($server_database['database'],$quality_cube_info['cube'],10000,"",$chart6_area, "", 1, "", "0");
			
			
			$chart1 .= $this->jedox->multichart_xml_series($chart1_data, $quality_test_qt_tt_p02_set, $version_elements_a_alias, 6, 0, "", " Total Test Values");
			$chart2 .= $this->jedox->multichart_xml_series($chart2_data, $quality_test_qt_tt_p03_set, $version_elements_a_alias, 6, 0, "", " Total Test Values");
			$chart3 .= $this->jedox->multichart_xml_series($chart3_data, $quality_test_qt_tt_p04_set, $version_elements_a_alias, 6, 0, "", " Total Test Values");
			$chart4 .= $this->jedox->multichart_xml_series($chart4_data, $quality_test_qt_tt_p05_set, $version_elements_a_alias, 6, 0, "", " Total Test Values");
			$chart5 .= $this->jedox->multichart_xml_series($chart5_data, $quality_test_qt_tt_p06_set, $version_elements_a_alias, 6, 0, "", " Total Test Values");
			$chart6 .= $this->jedox->multichart_xml_series($chart6_data, $quality_test_qt_tt_v_set, $version_elements_a_alias, 6, 0, "", " Total Test Values");
			
			
			// Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "form_year" => $form_year,
                "form_month" => $form_month,
                "form_product" => $form_product,
                "form_source" =>$form_source,
                "year" => $year,
                "month" => $month,
                "product" => $product,
                "source" => $source,
                "source_set_alias" => $source_set_alias,
                "quality_test_qt_tt_c01" => $quality_test_qt_tt_c01,
                "quality_test_qt_tt_p01_01" => $quality_test_qt_tt_p01_01,
                "quality_test_qt_tt_p01_02" => $quality_test_qt_tt_p01_02,
                "quality_test_qt_tt_p01_03" => $quality_test_qt_tt_p01_03,
                "quality_test_qt_tt_p07_01" => $quality_test_qt_tt_p07_01,
                "quality_test_qt_tt_p07_02" => $quality_test_qt_tt_p07_02,
                "quality_test_qt_tt_p01" => $quality_test_qt_tt_p01,
                "quality_test_qt_tt_p07" => $quality_test_qt_tt_p07,
                "table1_data" => $table1_data,
                "table2_data" => $table2_data,
                "table3_data" => $table3_data,
                "chart1" => $chart1,
                "chart2" => $chart2,
				"chart3" => $chart3,
				"chart4" => $chart4,
				"chart5" => $chart5,
				"chart6" => $chart6
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("taste_test_management_view", $alldata);
        }
    }


}