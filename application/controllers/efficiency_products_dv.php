<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Products_Dv extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Efficiency Products";
        $oneliner = "One-liner here for Efficiency Products";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_products") == FALSE)
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
            $cube_names = "Product_Report,#_Version,#_Month,#_Report_Value,#_Product,#_Year";
			
			// Initialize post data //
            $month = $this->input->post("month");
			$year = $this->input->post("year");
			$product = $this->input->post("product");
			$version1 = $this->input->post("version1");
			$version2 = $this->input->post("version2");
			$version3 = $this->input->post("version3");
			
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
            $product_report_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Product_Report");
			
			$product_report_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Product_Report");
			
			//////////////////////////// 
            // Get Dimension elements //
            ////////////////////////////
            
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// YEAR //
			// Get dimension of year
			//$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_dimension_id[1]);
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
            
			// MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
			
			// report_value //
            // Get dimension of report_value
            $report_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_dimension_id[3]);
            // Get cube data of report_value alias
            $report_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Report_Value");
            $report_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Report_Value");
            // Export cells of report_value alias
            $report_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $report_value_dimension_id[0]);
			$report_value_alias_name_id = $this->jedoxapi->get_area($report_value_alias_elements, "Name");
            $cells_report_value_alias = $this->jedoxapi->cell_export($server_database['database'],$report_value_alias_info['cube'],10000,"", $report_value_alias_name_id.",*");
			
			// product //
            // Get dimension of product
            $product_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_dimension_id[4]);
            // Get cube data of product alias
            $product_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Product");
            $product_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Product");
            // Export cells of product alias
            $product_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_dimension_id[0]);
			$product_alias_name_id = $this->jedoxapi->get_area($product_alias_elements, "Name");
            $cells_product_alias = $this->jedoxapi->cell_export($server_database['database'],$product_alias_info['cube'],10000,"", $product_alias_name_id.",*");
			
			// FORM DATA //
            $form_year = $this->jedoxapi->array_element_filter($year_elements, "YA"); // all years
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            //$form_product = $this->jedoxapi->dimension_sort_by_name($product_elements, "FP_PL11,FP_PL21,FP_PL31"); // manual entries
			$form_product = $this->jedoxapi->array_element_filter($product_elements, "FP");
			$form_product = $this->dimension_elements_notbase($form_product);
			$form_product = $this->jedoxapi->set_alias($form_product, $cells_product_alias);
			
			//$form_version = $this->jedoxapi->array_element_filter($version_elements, "V_Production_Plan");
			//array_shift($form_version);
			$form_version = $this->jedoxapi->dimension_elements_base($version_elements);
			$form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias);
			
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
			
			if($product == '')
			{
				
				$product = $this->jedoxapi->get_area($product_elements, "FP");
			}
			
			if($version1 == '')
			{
				$version1 = $form_version[0]['element'];
			}
			if($version2 == '')
			{
				$version2 = $form_version[1]['element'];
			}
			if($version3 == '')
			{
				$version3 = $form_version[2]['element'];
			}
			
			////////////
            // TABLES //
            ////////////
            
            //$version_patAT = $this->jedoxapi->get_area($version_elements, "V001,V002,V003,A/T"); // Plan, Actual, Target, A/T
            //$version_patAP = $this->jedoxapi->get_area($version_elements, "V001,V002,V003,A/P"); // Plan, Actual, Target, A/P
            //$version_p = $this->jedoxapi->get_area($version_elements, "V001"); // Plan
			//$version_a = $this->jedoxapi->get_area($version_elements, "V002"); // actual
			//$version_t = $this->jedoxapi->get_area($version_elements, "V003"); // target
			//$version_AT = $this->jedoxapi->get_area($version_elements, "A/T"); // A/T
			//$version_AP = $this->jedoxapi->get_area($version_elements, "A/P"); // A/P
			$version_area_all = $version1.':'.$version2.':'.$version3;
			
			
			$product_set = $this->jedoxapi->array_element_filter($product_elements, "FP");
			array_shift($product_set);
			
			$product_fp = $this->jedoxapi->get_area($product_elements, "FP");
			
			$product_set_area = $this->jedoxapi->get_area($product_set);
			$product_set_alias = $this->jedoxapi->set_alias($product_set, $cells_product_alias);
            
            $report_value_rc = $this->jedoxapi->get_area($report_value_elements, "TC"); // RC
			$report_value_pq = $this->jedoxapi->get_area($report_value_elements, "QTY03"); // PQ
			$report_value_uc = $this->jedoxapi->get_area($report_value_elements, "UPC"); // UC
			
			//$table1a_area = $version_patAT.",".$year.",".$month.",".$report_value_rc.",".$product_set_area;
			//$table1b_area = $version_patAP.",".$year.",".$month.",".$report_value_pq.",".$product_set_area;
			//$table1c_area = $version_patAT.",".$year.",".$month.",".$report_value_uc.",".$product_set_area;
			
			$table1a_area = $version_area_all.",".$year.",".$month.",".$report_value_rc.",".$product_set_area;
			$table1b_area = $version_area_all.",".$year.",".$month.",".$report_value_pq.",".$product_set_area;
			$table1c_area = $version_area_all.",".$year.",".$month.",".$report_value_uc.",".$product_set_area;
			
			$table2_area = $version_area_all.",".$year.",".$month.",".$report_value_rc.",".$product_fp; // total
			
			$table1a_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_cube_info['cube'],10000,"",$table1a_area, "", 1, "", "0");
			$table1b_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_cube_info['cube'],10000,"",$table1b_area, "", 1, "", "0");
			$table1c_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_cube_info['cube'],10000,"",$table1c_area, "", 1, "", "0");
			
			$table2_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_cube_info['cube'],10000,"",$table2_area, "", 1, "", "0"); // total
			
			////////////
			// CHARTS //
			////////////
			
			// readjusted and removed prev year from chart version
			
			$version_area_12 = $version1.':'.$version2.":".$version3; 
			//$version_area_23 = $version2.':'.$version3;
			
			//note: needed to change variable contents without changing var names too much. commented out old lines to show relationships from previous build.
			
			//$version_area_at = $this->jedoxapi->get_area($version_elements, "V002,V003"); // Actual, Target
            //$version_elements_at = $this->jedoxapi->dimension_elements_id($version_elements, "V002,V003");
            //$version_elements_at_alias = $this->jedoxapi->set_alias($version_elements_at, $cells_version_alias);
			
			$version_area_at = $version_area_12;
			$version_elements_at = $version1.",".$version2.",".$version3;
			$version_elements_at_alias = $this->get_dimension_data_by_id_multi($form_version, $version_elements_at);
			
			//$version_area_ap = $this->jedoxapi->get_area($version_elements, "V001,V002"); // Actual, plan
			//$version_elements_ap = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002");
            //$version_elements_ap_alias = $this->jedoxapi->set_alias($version_elements_ap, $cells_version_alias);
			
			//$version_area_ap = $version_area_12;
			//$version_elements_ap = $version1.",".$version2;
			//$version_elements_ap_alias = $this->get_dimension_data_by_id_multi($form_version, $version_elements_ap);
			
			//$version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            //$version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
			
			//$version_elements_a = $version2;
			//$version_elements_a_alias = $this->get_dimension_data_by_id_multi($form_version, $version_elements_a);
			
			$month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
			
			$chart1 = $chart2 = $this->jedox->multichart_xml_categories($month_all_alias, 1);
			
			$current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year);
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
			
			//$chart1_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rc.",".$product_fp;
			$chart1_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rc.",".$product_fp;
			$chart1_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_cube_info['cube'],10000,"",$chart1_area, "", 1, "", "0");
			
			//$chart1 .= $this->jedox->multichart_xml_series($chart1_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			$chart1 .= $this->jedox->multichart_xml_series($chart1_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			/*
			if($yearcheck != 0)
            {
            	//$chart1a_area = $version2.",".$prev_year_data[0]['element'].",".$month_all_area.",".$report_value_rc.",".$product_fp;
				$chart1a_area = $version1.",".$prev_year_data[0]['element'].",".$month_all_area.",".$report_value_rc.",".$product_fp;
				$chart1a_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_cube_info['cube'],10000,"",$chart1a_area, "", 1, "", "0");
			
				//$chart1 .= $this->jedox->multichart_xml_series($chart1a_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$prev_year);
				$chart1 .= $this->jedox->multichart_xml_series($chart1a_data, $month_all, $version_elements_ap_alias, 2, 0, "", " ".$prev_year);
			}
			 * removed due to adjustments
			*/
			
			$chart2_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_pq.",".$product_fp;
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_cube_info['cube'],10000,"",$chart2_area, "", 1, "", "0");
			
			$chart2 .= $this->jedox->multichart_xml_series($chart2_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			$product_data = $this->jedox->get_dimension_data_by_id($product_elements, $product);
			$product_base = $this->jedoxapi->array_element_filter($product_elements, $product_data['name_element']);
			$product_base = $this->jedoxapi->dimension_elements_base($product_base);
			$product_base_area = $this->jedoxapi->get_area($product_base);
			$product_base_alias = $this->jedoxapi->set_alias($product_base, $cells_product_alias);
			
			$chart3_area = $version_area_at.",".$year.",".$month.",".$report_value_uc.",".$product_base_area;
			$chart3_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_cube_info['cube'],10000,"",$chart3_area, "", 1, "", "0");
			
			$chart3 = $this->jedox->multichart_xml_categories($product_base_alias);
			$chart3 .= $this->jedox->multichart_xml_series($chart3_data, $product_base, $version_elements_at_alias, 4, 0, "", " Unit Cost");
			
			$alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "form_year" => $form_year,
                "form_months" => $form_months,
                "form_product" => $form_product,
                "form_version" => $form_version,
                "year" => $year,
                "month" => $month,
                "product" => $product,
                "product_set_alias" => $product_set_alias,
                //"version_p" => $version_p,
                //"version_a" => $version_a,
                //"version_t" => $version_t,
                //"version_AT" => $version_AT,
                //"version_AP" => $version_AP,
                "version1" => $version1,
                "version2" => $version2,
                "version3" => $version3,
                
                "table1a_data" => $table1a_data,
                "table1b_data" => $table1b_data,
                "table1c_data" => $table1c_data,
                "table2_data" => $table2_data,
                "chart1" => $chart1,
                "chart2" => $chart2,
                "chart3" => $chart3
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_products_view_dv", $alldata);
            
            
        }
    }

	public function dimension_elements_notbase($array)
    {
        $result_array = array();
        foreach($array as $row)
        {
            if($row['number_children'] > 0)
            {
                $result_array[] = $row;
            }
        }
        return $result_array;
    }

	public function get_dimension_data_by_id_multi($array, $id)
	{
		$result_array = array();
        $namelist = explode(",", $id);
        foreach($namelist as $row)
        {
            foreach($array as $arow)
            {
                if($arow['element'] == $row)
                {
                    $result_array[] = $arow;
                }
            }
            
        }
        return $result_array;
		
	}

}