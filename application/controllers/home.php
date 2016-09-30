<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
		$this->load->library("jedoxapi");
	}
	
	public function index()
	{
		$pagename = "proEO home";
		$oneliner = "One-liner here for home";
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
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
            //$year = $this->input->post("year");
            //$month = $this->input->post("month");
            //$product = $this->input->post("product");
			//$customer = $this->input->post("customer");
            
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
			
			
			//vars
			$version_a = $this->jedoxapi->get_area($version_elements, "V002"); //actual		
			$version_a_element = $this->jedoxapi->dimension_sort_by_name($version_elements, "V002");
			$version_a_element_alias = $this->jedoxapi->set_alias($version_a_element, $cells_version_alias);	
			
            $now = now();
            $tnow = mdate("%Y", $now);
            $year = $this->jedoxapi->get_area($year_elements, $tnow);
        
            $month = $this->jedoxapi->get_area($month_elements, "MA");
       
			$product = $this->jedoxapi->get_area($product_elements, "FP");
		
			$customer = $this->jedoxapi->get_area($customer_elements, "CU");
			
			$customer_base = $this->jedoxapi->set_alias($customer_elements, $cells_customer_alias);
			//$customer_base = $this->jedoxapi->dimension_elements_base($form_customer);
			$customer_base_area = $this->jedoxapi->get_area($customer_base);
			
			$margin_value_mg03 = $this->jedoxapi->get_area($margin_value_elements, "MG03"); // MG03
			
			
			$chart2_area = $version_a.",".$year.",".$month.",".$margin_value_mg03.",".$product.",".$customer_base_area; 
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$chart2_area, "", 1, "", "0");
			//$this->jedoxapi->traceme($chart2_data);
			//$chart2 = $this->singlechart_xml($chart2_data, $customer_base, 5);
			
			$chart2 = $this->jedox->multichart_xml_categories($customer_base); // orig
			$chart2 .= $this->jedox->multichart_xml_series($chart2_data, $customer_base, $version_a_element_alias, 5, 0); // orig
			
			
			
			
			
			$alldata = array(
				"jedox_user_details" => $this->session->userdata('jedox_user_details'),
				"pagename" => $pagename,
				"oneliner" => $oneliner,
				"chart2" => $chart2
			);
			// Pass data and show view
			$this->load->view("home_view", $alldata);
		}
	} // end of index
	
	public function pin_chart()
	{
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else
		{
			// initialize post data.
			$jedox_user_details = $this->session->userdata('jedox_user_details');
			
			$database = $this->session->userdata('jedox_db');
			$owner = $jedox_user_details['name'];
			$chart_name = $this->input->post("chart_name");
			$chart_url = $this->input->post("chart_url");
			$chart_link = $this->input->post("chart_link");
			
			if($chart_name != '' && $chart_url != '' && $chart_link != '')
			{
				$this->proeo_model->pin_chart($owner, $database, $chart_name, $chart_url, $chart_link);
			} 
			
		}
	}
	
	public function unpin_chart()
	{
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else
		{
			// initialize post data.
			$jedox_user_details = $this->session->userdata('jedox_user_details');
			
			$database = $this->session->userdata('jedox_db');
			$owner = $jedox_user_details['name'];
			$id = $this->input->post("id");
			
			if($id != '')
			{
				$id = explode("_", $id);
				$this->proeo_model->unpin_chart($id[1], $owner, $database);
			} 
			
		}
	}
	
	public function order_chart()
	{
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else
		{
			// initialize post data.
			$jedox_user_details = $this->session->userdata('jedox_user_details');
			
			$database = $this->session->userdata('jedox_db');
			$owner = $jedox_user_details['name'];
			$ids = $this->input->post("ids");
			$count = 1;
			foreach ($ids as $row) {
				$id = explode("_", $row);
				$query = $this->proeo_model->order_chart($id[1], $owner, $database, $count);
				$count += 1;
			}
			
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
	
}