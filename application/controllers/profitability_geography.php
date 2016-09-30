<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profitability_Geography extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
        $this->load->library("jedoxapi");
	}
	
	public function index()
	{
		$pagename = "proEO Profitability by Geography";
		$oneliner = "One-liner here for Profitability by Geography";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedox->page_permission($user_details['group_names'], "profitability") == FALSE)
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
            $cube_names = "Margin_Geo_Report,#_Version,#_Month,#_Margin_Value,#_Product,#_Customer,#_Year";
			
			// Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $product = $this->input->post("product");
			$customer = $this->input->post("customer");
            
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
            $margin_geo_report_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Margin_Geo_Report");
			
			$margin_geo_report_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Margin_Geo_Report");
			
			//////////////////////////// 
            // Get Dimension elements //
            ////////////////////////////
            
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_geo_report_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// YEAR //
			// Get dimension of year
			//$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_geo_report_dimension_id[1]);
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_geo_report_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
            
            
			// MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_geo_report_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
			
			// Margin_Value //
            // Get dimension of Margin_Value
            $margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_geo_report_dimension_id[3]);
            // Get cube data of Margin_Value alias
            $margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
            $margin_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Margin_Value");
            // Export cells of Margin_Value alias
            $margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
            $cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*");
			
			// product //
            // Get dimension of product
            $product_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_geo_report_dimension_id[4]);
            // Get cube data of product alias
            $product_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Product");
            $product_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Product");
            // Export cells of product alias
            $product_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_dimension_id[0]);
			$product_alias_name_id = $this->jedoxapi->get_area($product_alias_elements, "Name");
            $cells_product_alias = $this->jedoxapi->cell_export($server_database['database'],$product_alias_info['cube'],10000,"", $product_alias_name_id.",*");
			
			// Customer //
            // Get dimension of Customer
            $customer_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_geo_report_dimension_id[5]);
            // Get cube data of Customer alias
            $customer_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Customer");
            $customer_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Customer");
            // Export cells of Customer alias
            $customer_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $customer_dimension_id[0]);
			$customer_alias_name_id = $this->jedoxapi->get_area($customer_alias_elements, "Name");
            $cells_customer_alias = $this->jedoxapi->cell_export($server_database['database'],$customer_alias_info['cube'],10000,"", $customer_alias_name_id.",*");
			
			
			// FORM DATA //
            $form_year = $this->jedoxapi->array_element_filter($year_elements, "YA"); // all years
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            $form_product = $this->jedoxapi->array_element_filter($product_elements, "FP");
			//$form_product = $this->dimension_elements_notbase($form_product);
			//array_shift($form_product);
			$form_product = $this->jedoxapi->set_alias($form_product, $cells_product_alias);
			
			$form_customer = $this->jedoxapi->array_element_filter($customer_elements, "CU");
			$form_customer = $this->jedoxapi->set_alias($form_customer, $cells_customer_alias);
			
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
				$product = $this->jedoxapi->get_area($product_elements, "FP_RG1");
			}
			
			if($customer == '')
			{
				$customer = $this->jedoxapi->get_area($customer_elements, "CU");
			}
			
			////////////
            // TABLES //
            ////////////
			
			$version_patAT = $this->jedoxapi->get_area($version_elements, "V001,V002,V003,A/T"); // Plan, Actual, Target A/T
			$version_p = $this->jedoxapi->get_area($version_elements, "V001"); // Plan
			$version_a = $this->jedoxapi->get_area($version_elements, "V002"); //actual
			$version_t = $this->jedoxapi->get_area($version_elements, "V003"); //target
			$version_AT = $this->jedoxapi->get_area($version_elements, "A/T"); //A/T
			
			$margin_value_qty05 = $this->jedoxapi->get_area($margin_value_elements, "QTY05"); // QTY05
			$margin_value_p001 = $this->jedoxapi->get_area($margin_value_elements, "P001"); // P001
			$margin_value_sls04 = $this->jedoxapi->get_area($margin_value_elements, "SLS04"); // SLS04
			$margin_value_sls03 = $this->jedoxapi->get_area($margin_value_elements, "SLS03"); // SLS03
			$margin_value_sls = $this->jedoxapi->get_area($margin_value_elements, "SLS"); // SLS
			$margin_value_craw = $this->jedoxapi->get_area($margin_value_elements, "CRAW"); // CRAW
			$margin_value_mg01 = $this->jedoxapi->get_area($margin_value_elements, "MG01"); // MG01
			$margin_value_sc03 = $this->jedoxapi->get_area($margin_value_elements, "SC03"); // SC03
			$margin_value_mg02 = $this->jedoxapi->get_area($margin_value_elements, "MG02"); // MG02
			$margin_value_scf = $this->jedoxapi->get_area($margin_value_elements, "SCF"); // SCF
			$margin_value_mg03 = $this->jedoxapi->get_area($margin_value_elements, "MG03"); // MG03
			
			// Areas
			
			$table1_area = $version_patAT.",".$year.",".$month.",".$margin_value_qty05.",".$product.",".$customer;
			$table2_area = $version_patAT.",".$year.",".$month.",".$margin_value_p001.",".$product.",".$customer;
			$table3_area = $version_patAT.",".$year.",".$month.",".$margin_value_sls04.",".$product.",".$customer;
			$table4_area = $version_patAT.",".$year.",".$month.",".$margin_value_sls03.",".$product.",".$customer;
			$table5_area = $version_patAT.",".$year.",".$month.",".$margin_value_sls.",".$product.",".$customer;
			$table6_area = $version_patAT.",".$year.",".$month.",".$margin_value_craw.",".$product.",".$customer;
			$table7_area = $version_patAT.",".$year.",".$month.",".$margin_value_mg01.",".$product.",".$customer;
			$table8_area = $version_patAT.",".$year.",".$month.",".$margin_value_sc03.",".$product.",".$customer;
			$table9_area = $version_patAT.",".$year.",".$month.",".$margin_value_mg02.",".$product.",".$customer;
			$table10_area = $version_patAT.",".$year.",".$month.",".$margin_value_scf.",".$product.",".$customer;
			$table11_area = $version_patAT.",".$year.",".$month.",".$margin_value_mg03.",".$product.",".$customer; 
			
			// Data
			
			$table1_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table1_area, "", 1, "", "0");
			$table2_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table2_area, "", 1, "", "0");
			$table3_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table3_area, "", 1, "", "0");
			$table4_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table4_area, "", 1, "", "0");
			$table5_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table5_area, "", 1, "", "0");
			$table6_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table6_area, "", 1, "", "0");
			$table7_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table7_area, "", 1, "", "0");
			$table8_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table8_area, "", 1, "", "0");
			$table9_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table9_area, "", 1, "", "0");
			$table10_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table10_area, "", 1, "", "0");
			$table11_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table11_area, "", 1, "", "0");
			
			////////////
			// CHARTS //
			////////////
			
			$version_area_at = $this->jedoxapi->get_area($version_elements, "V002,V003"); // Actual, Target
            $version_elements_at = $this->jedoxapi->dimension_elements_id($version_elements, "V002,V003");
            $version_elements_at_alias = $this->jedoxapi->set_alias($version_elements_at, $cells_version_alias);
			
			$version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            $version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
			
			$month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
            
			$customer_name = $this->jedoxapi->get_name($form_customer, $customer);
			$customer_base = $this->jedoxapi->array_element_filter($form_customer, $customer_name);
			//$customer_base = $this->jedoxapi->dimension_elements_base($form_customer);
			$customer_base_area = $this->jedoxapi->get_area($customer_base);
			
			$chart1 = $this->jedox->multichart_xml_categories($month_all_alias, 1);
			
			//chart1
			
			$chart1a_area = $version_a.",".$year.",".$month_all_area.",".$margin_value_sls.",".$product.",".$customer;
			
			$chart1b_area = $version_area_at.",".$year.",".$month_all_area.",".$margin_value_craw.",".$product.",".$customer; 
			$chart1c_area = $version_area_at.",".$year.",".$month_all_area.",".$margin_value_sc03.",".$product.",".$customer;
			$chart1d_area = $version_area_at.",".$year.",".$month_all_area.",".$margin_value_scf.",".$product.",".$customer;
			
			$chart1a_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$chart1a_area, "", 1, "", "0");
			$chart1b_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$chart1b_area, "", 1, "", "0");
			$chart1c_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$chart1c_area, "", 1, "", "0");
			$chart1d_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$chart1d_area, "", 1, "", "0");
			
			$chart1e_data = $this->jedox->add_cell_array($chart1b_data, $chart1c_data, 0, 2);
			$chart1e_data = $this->jedox->add_cell_array($chart1e_data, $chart1d_data, 0, 2);
			
			$chart1 .= $this->jedox->multichart_xml_series($chart1a_data, $month_all, $version_elements_a_alias, 2, 0, "", " Rev");
			$chart1 .= $this->jedox->multichart_xml_series($chart1e_data, $month_all, $version_elements_at_alias, 2, 0, "", " Cost");
			
			$chart2_area = $version_a.",".$year.",".$month.",".$margin_value_mg03.",".$product.",".$customer_base_area; 
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$chart2_area, "", 1, "", "0");
			//$this->jedoxapi->traceme($chart2_data);
			$chart2 = $this->singlechart_xml($chart2_data, $customer_base, 5);
			
			$alldata = array(
				//regular vars here
				"jedox_user_details" => $this->session->userdata('jedox_user_details'),
				"pagename" => $pagename,
				"oneliner" => $oneliner,
				"year" => $year,
				"month" => $month,
				"customer" => $customer,
				"product" => $product,
				"form_year" => $form_year,
				"form_months" => $form_months,
				"form_product" => $form_product,
				"form_customer" => $form_customer,
				"version_p" => $version_p,
				"version_a" => $version_a,
				"version_t" => $version_t,
				"version_AT" => $version_AT,
				"table1_data" => $table1_data,
				"table2_data" => $table2_data,
				"table3_data" => $table3_data,
				"table4_data" => $table4_data,
				"table5_data" => $table5_data,
				"table6_data" => $table6_data,
				"table7_data" => $table7_data,
				"table8_data" => $table8_data,
				"table9_data" => $table9_data,
				"table10_data" => $table10_data,
				"table11_data" => $table11_data,
				"chart1" => $chart1,
				"chart2" => $chart2
			);
			// Pass data and show view
			$this->load->view("profitability_geography_view", $alldata);
		}
	}

	public function singlechart_xml($cells, $series, $series_path, $color = '')
	{
		$series_xml = "";
		$setcolor = "";
		foreach($cells as $subrows)
		{
			
			foreach($series as $rows){
				$cat_identifier = explode(',',$subrows['path']);
				$series_ident = $rows['element'];
				if( $series_ident == $cat_identifier[$series_path] && $subrows['value'] != '' )
				{
					if($color != ''){
						$setcolor = "color='".$color."'";
					}
					$rows['name_element'] = str_replace("'", "", $rows['name_element']);
					$series_xml .= "<set label='".$rows['name_element']."' value='".round($subrows['value'])."' ".$setcolor." />";
				}
			}
			
		}
		
		return $series_xml;
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
	
}