<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Operations_Details_v2 extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Efficiency Operations Details";
        $oneliner = "One-liner here for Efficiency Operations Details";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_operations_details") == FALSE)
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
            $cube_names = "Operation,#_Version,#_Year_Month_Time,#_Operation_Value,#_Shift,#_Equipment,#_Product";
			
			// Initialize post data //
			$version = $this->input->post("version");
            $year_month_time = $this->input->post("year_month_time");
            $shift = $this->input->post("shift");
            $equipment = $this->input->post("equipment");
			$product = $this->input->post("product");
			
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
            $operations_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Operation");
            
            $operations_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Operation");
			
			////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $operations_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// year_month_time //
            // Get dimension of year_month_time
            $year_month_time_elements = $this->jedoxapi->dimension_elements($server_database['database'], $operations_dimension_id[1]);
            // Get cube data of year_month_time alias
            $year_month_time_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year_Month_Time");
            $year_month_time_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year_Month_Time");
            // Export cells of year_month_time alias
            $year_month_time_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_month_time_dimension_id[0]);
			$year_month_time_alias_name_id = $this->jedoxapi->get_area($year_month_time_alias_elements, "Name");
            $cells_year_month_time_alias = $this->jedoxapi->cell_export($server_database['database'],$year_month_time_alias_info['cube'],10000,"",$year_month_time_alias_name_id.",*");
			
			// operation VALUE //
            // Get dimension of operation_value
            $operation_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $operations_dimension_id[2]);
            // Get cube data of operation_value alias
            $operation_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Operation_Value");
            $operation_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Operation_Value");
            // Export cells of operation_value alias
            $operation_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $operation_value_dimension_id[0]);
			$operation_value_alias_name_id = $this->jedoxapi->get_area($operation_value_alias_elements, "Name");
            $cells_operation_value_alias = $this->jedoxapi->cell_export($server_database['database'],$operation_value_alias_info['cube'],10000,"",$operation_value_alias_name_id.",*");
			
			// SHIFT //
            // Get dimension of shift
            $shift_elements = $this->jedoxapi->dimension_elements($server_database['database'], $operations_dimension_id[3]);
            // Get cube data of shift alias
            $shift_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Shift");
            $shift_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Shift");
            // Export cells of shift alias
            $shift_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $shift_dimension_id[0]);
			$shift_alias_name_id = $this->jedoxapi->get_area($shift_alias_elements, "Name");
            $cells_shift_alias = $this->jedoxapi->cell_export($server_database['database'],$shift_alias_info['cube'],10000,"",$shift_alias_name_id.",*");
			
			// equipment //
            // Get dimension of equipment
            $equipment_elements = $this->jedoxapi->dimension_elements($server_database['database'], $operations_dimension_id[4]);
            // Get cube data of equipment alias
            $equipment_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Equipment");
            $equipment_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Equipment");
            // Export cells of equipment alias
            $equipment_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $equipment_dimension_id[0]);
			$equipment_alias_name_id = $this->jedoxapi->get_area($equipment_alias_elements, "Name");
            $cells_equipment_alias = $this->jedoxapi->cell_export($server_database['database'],$equipment_alias_info['cube'],10000,"",$equipment_alias_name_id.",*");
			
			// product //
            // Get dimension of product
            $product_elements = $this->jedoxapi->dimension_elements($server_database['database'], $operations_dimension_id[5]);
            // Get cube data of product alias
            $product_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Product");
            $product_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Product");
            // Export cells of product alias
            $product_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_dimension_id[0]);
			$product_alias_name_id = $this->jedoxapi->get_area($product_alias_elements, "Name");
            $cells_product_alias = $this->jedoxapi->cell_export($server_database['database'],$product_alias_info['cube'],10000,"",$product_alias_name_id.",*");
			
			// FORM DATA //
			
			$form_version = $this->jedoxapi->array_element_filter($version_elements, "A/P");
			array_shift($form_version);
			$form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias);
			
			//$form_year_month_time = $this->jedoxapi->dimension_elements_base($year_month_time_elements);
			//$form_year_month_time = $this->jedoxapi->set_alias($form_year_month_time, $cells_year_month_time_alias);
			$form_year_month_time = $this->jedoxapi->array_element_filter($year_month_time_elements, "TA");
			$form_year_month_time = $this->jedoxapi->set_alias($form_year_month_time, $cells_year_month_time_alias);
			
			//$form_shift = $this->jedoxapi->dimension_elements_base($shift_elements);
			//$form_shift = $this->jedoxapi->set_alias($form_shift, $cells_shift_alias);
			$form_shift = $this->jedoxapi->set_alias($shift_elements, $cells_shift_alias);
			
			//$form_equipment = $this->jedoxapi->dimension_sort_by_name($equipment_elements, "RPPL_11,CC_117000,RP_117500,CC_118000,RP_118200,RP_118400");
            $form_equipment = $this->jedoxapi->array_element_filter($equipment_elements, "EQ");
            $form_equipment = $this->jedoxapi->set_alias($form_equipment, $cells_equipment_alias); 
			
			$form_product = $this->jedoxapi->array_element_filter($product_elements, "FP");
			$form_product = $this->jedoxapi->set_alias($form_product, $cells_product_alias);
			
			
			/////////////
            // PRESETS //
            /////////////
            
            if($version == '')
            {
            	$version = $this->jedoxapi->get_area($version_elements, "V002"); // actual
            }
            
            if($year_month_time == '')
            {
                $year_month_time = $this->jedoxapi->get_area($year_month_time_elements, "2014-M01-D10");
            }
			
			if($shift == '')
			{
				$shift = $this->jedoxapi->get_area($shift_elements, "SH_1");
			}
			
			if($equipment == '')
            {
                $equipment = $this->jedoxapi->get_area($equipment_elements, "EQ");
            }
			
			if($product == '')
			{
				$product = $this->jedoxapi->get_area($product_elements, "FP");
			}
			
			////////////
            // TABLES //
            ////////////
			
			//$version_actual = $this->jedoxapi->get_area($version_elements, "V002"); // actual
			$version_actual = $version; //adjusted to dynamically change based on filter
			
			$operation_value_opkq = $this->jedoxapi->get_area($operation_value_elements, "OP_KQ");
			$operation_value_opkq01 = $this->jedoxapi->get_area($operation_value_elements, "OP_KQ01");
			$operation_value_opkq02 = $this->jedoxapi->get_area($operation_value_elements, "OP_KQ02");
			$operation_value_opkq03 = $this->jedoxapi->get_area($operation_value_elements, "OP_KQ03");
			
			$operation_value_opkt = $this->jedoxapi->get_area($operation_value_elements, "OP_KT");
			$operation_value_opkt13 = $this->jedoxapi->get_area($operation_value_elements, "OP_KT13");
			$operation_value_opkt14 = $this->jedoxapi->get_area($operation_value_elements, "OP_KT14");
			$operation_value_opkt15 = $this->jedoxapi->get_area($operation_value_elements, "OP_KT15");
			$operation_value_opkt16 = $this->jedoxapi->get_area($operation_value_elements, "OP_KT16");
			
			$operation_value_opcq = $this->jedoxapi->get_area($operation_value_elements, "OP_CQ");
			$operation_value_opcq01 = $this->jedoxapi->get_area($operation_value_elements, "OP_CQ01");
			$operation_value_opcq02 = $this->jedoxapi->get_area($operation_value_elements, "OP_CQ02");
			$operation_value_opcq03 = $this->jedoxapi->get_area($operation_value_elements, "OP_CQ03");
			
			$operation_value_opct = $this->jedoxapi->get_area($operation_value_elements, "OP_CT");
			$operation_value_opct13 = $this->jedoxapi->get_area($operation_value_elements, "OP_CT13");
			$operation_value_opct14 = $this->jedoxapi->get_area($operation_value_elements, "OP_CT14");
			$operation_value_opct15 = $this->jedoxapi->get_area($operation_value_elements, "OP_CT15");
			$operation_value_opct16 = $this->jedoxapi->get_area($operation_value_elements, "OP_CT16");
			
			$operation_value_data = $this->jedoxapi->set_alias($operation_value_elements, $cells_operation_value_alias);
			
			$product_element_data = $this->jedox->get_dimension_data_by_id($form_product, $product);
			$product_data = $this->jedoxapi->array_element_filter($form_product, $product_element_data['name_element']);
			//$product_base = $this->jedoxapi->dimension_elements_base($product_data);
			array_shift($product_data);
			$product_base_alias = $this->jedoxapi->set_alias($product_data, $cells_product_alias);
			$product_base_area = $this->jedoxapi->get_area($product_data);
			
			$operation_value_areas = $operation_value_opkq.":".$operation_value_opkq01.":".$operation_value_opkq02.":".$operation_value_opkq03
			.":".$operation_value_opkt.":".$operation_value_opkt13.":".$operation_value_opkt14.":".$operation_value_opkt15.":".$operation_value_opkt16
			.":".$operation_value_opcq.":".$operation_value_opcq01.":".$operation_value_opcq02.":".$operation_value_opcq03
			.":".$operation_value_opct.":".$operation_value_opct13.":".$operation_value_opct14.":".$operation_value_opct15.":".$operation_value_opct16;
			

			$table_area = $version_actual.",".$year_month_time.",".$operation_value_areas.",".$shift.",".$equipment.",".$product_base_area;
			$table_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $table_area, "", "1", "", "0");
			
			////////////
			// CHARTS //
			////////////
			
			$year_month_time_element_data = $this->jedox->get_dimension_data_by_id($year_month_time_elements, $year_month_time);
			//echo $year_month_time_element_data['name_element'];
			$year_month_time_year = substr($year_month_time_element_data['name_element'], 0, 4);
			$year_month_time_month = substr($year_month_time_element_data['name_element'], 6, 2);
			$year_month_time_day = substr($year_month_time_element_data['name_element'], 10, 2);
			
			$daybase = mdate("%Y%m%d", strtotime($year_month_time_month."/".$year_month_time_day."/".$year_month_time_year));
			
			$day1 = mdate("%Y-M%m-D%d", strtotime($year_month_time_month."/".$year_month_time_day."/".$year_month_time_year));
			$day2 = mdate("%Y-M%m-D%d", strtotime($daybase."- 1 day"));
			$day3 = mdate("%Y-M%m-D%d", strtotime($daybase."- 2 day"));
			$day4 = mdate("%Y-M%m-D%d", strtotime($daybase."- 3 day"));
			$day5 = mdate("%Y-M%m-D%d", strtotime($daybase."- 4 day"));
			$day6 = mdate("%Y-M%m-D%d", strtotime($daybase."- 5 day"));
			
			$year_month_time_string = $day1.",".$day2.",".$day3.",".$day4.",".$day5.",".$day6;
			
			$day_range = $this->jedoxapi->dimension_sort_by_name($year_month_time_elements, $year_month_time_string);
			$day_range_alias = $this->jedoxapi->set_alias($day_range, $cells_year_month_time_alias);
			$day_range_area = $this->jedoxapi->get_area($day_range);
			
			//-----------
			
			$operation_value_set1 = $this->jedoxapi->dimension_sort_by_name($operation_value_elements, "OP_KQ01,OP_KQ02,OP_KQ03");
			$operation_value_set1_alias = $this->jedoxapi->set_alias($operation_value_set1, $cells_operation_value_alias);
			$operation_value_set1_area = $this->jedoxapi->get_area($operation_value_set1);
			
			$operation_value_set2 = $this->jedoxapi->dimension_sort_by_name($operation_value_elements, "OP_CQ01,OP_CQ02,OP_CQ03");
			$operation_value_set2_alias = $this->jedoxapi->set_alias($operation_value_set2, $cells_operation_value_alias);
			$operation_value_set2_area = $this->jedoxapi->get_area($operation_value_set2);
			
			$operation_value_set3 = $this->jedoxapi->dimension_sort_by_name($operation_value_elements, "OP_KT13,OP_KT14,OP_KT15,OP_KT16");
			$operation_value_set3_alias = $this->jedoxapi->set_alias($operation_value_set3, $cells_operation_value_alias);
			$operation_value_set3_area = $this->jedoxapi->get_area($operation_value_set3);
			
			$operation_value_set4 = $this->jedoxapi->dimension_sort_by_name($operation_value_elements, "OP_CT13,OP_CT14,OP_CT15,OP_CT16");
			$operation_value_set4_alias = $this->jedoxapi->set_alias($operation_value_set4, $cells_operation_value_alias);
			$operation_value_set4_area = $this->jedoxapi->get_area($operation_value_set4);
			
			//-----------
			
			$chart1_area = $version_actual.",".$day_range_area.",".$operation_value_set1_area.",".$shift.",".$equipment.",".$product;
			$chart1_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart1_area, "", "1", "", "0");
			
			$chart1 = $this->jedox->multichart_xml_categories($operation_value_set1_alias);
			$chart1 .= $this->jedox->multichart_xml_series($chart1_data, $operation_value_set1_alias, $day_range_alias, 2, 1, "");
			
			$chart2_area = $version_actual.",".$day_range_area.",".$operation_value_set2_area.",".$shift.",".$equipment.",".$product;
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart2_area, "", "1", "", "0");
			
			$chart2 = $this->jedox->multichart_xml_categories($operation_value_set2_alias);
			$chart2 .= $this->multichart_xml_series($chart2_data, $operation_value_set2_alias, $day_range_alias, 2, 1, "");
			
			$chart3_area = $version_actual.",".$day_range_area.",".$operation_value_set3_area.",".$shift.",".$equipment.",".$product;
			$chart3_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart3_area, "", "1", "", "0");
			
			$chart3 = $this->jedox->multichart_xml_categories($operation_value_set3_alias);
			$chart3 .= $this->multichart_xml_series($chart3_data, $operation_value_set3_alias, $day_range_alias, 2, 1, "");
			
			$chart4_area = $version_actual.",".$day_range_area.",".$operation_value_set4_area.",".$shift.",".$equipment.",".$product;
			$chart4_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart4_area, "", "1", "", "0");
			
			$chart4 = $this->jedox->multichart_xml_categories($operation_value_set4_alias);
			$chart4 .= $this->multichart_xml_series($chart4_data, $operation_value_set4_alias, $day_range_alias, 2, 1, "");
			
			// Pass all data to send to view file 
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "form_year_month_time" => $form_year_month_time,
                "form_shift" => $form_shift,
                "form_equipment" => $form_equipment,
                "form_product" => $form_product,
                "year_month_time" => $year_month_time,
                "shift" => $shift,
                "equipment" => $equipment,
                "product" => $product,
                "table_data" => $table_data,
                "product_base_alias" => $product_base_alias,
                "operation_value_opkq" => $operation_value_opkq,
                "operation_value_opkq01" => $operation_value_opkq01,
                "operation_value_opkq02" => $operation_value_opkq02,
                "operation_value_opkq03" => $operation_value_opkq03,
                "operation_value_opkt" => $operation_value_opkt,
                "operation_value_opkt13" => $operation_value_opkt13,
                "operation_value_opkt14" => $operation_value_opkt14,
                "operation_value_opkt15" => $operation_value_opkt15,
                "operation_value_opkt16" => $operation_value_opkt16,
                "operation_value_opcq" => $operation_value_opcq,
                "operation_value_opcq01" => $operation_value_opcq01,
                "operation_value_opcq02" => $operation_value_opcq02,
                "operation_value_opcq03" => $operation_value_opcq03,
                "operation_value_opct" => $operation_value_opct,
                "operation_value_opct13" => $operation_value_opct13,
                "operation_value_opct14" => $operation_value_opct14,
                "operation_value_opct15" => $operation_value_opct15,
                "operation_value_opct16" => $operation_value_opct16,
                "chart1" => $chart1,
                "chart2" => $chart2,
                "chart3" => $chart3,
                "chart4" => $chart4,
                "operation_value_data" => $operation_value_data,
                "version" => $version,
                "form_version" => $form_version
            );
            // Pass data and show view
            $this->load->view("efficiency_operations_details_view_v2", $alldata);
            
        }
    }

	private function multichart_xml_series($cells, $categories, $series, $category_path, $series_path, $prefix = '', $suffix = '')
	{
		$xml = '';
		foreach($series as $ser_rows)
		{
			$xml .= "<dataset seriesName='".$prefix.$ser_rows['name_element'].$suffix."'>";
			foreach($categories as $cat_rows)
			{
				foreach($cells as $cell_rows)
				{
					$path = explode(",", $cell_rows['path']);
					if($cat_rows['element'] == $path[$category_path] && $ser_rows['element'] == $path[$series_path])
					{
						if($cell_rows['value'] == 0 || $cell_rows['value'] == '')
						{
							$xml .= "<set value='' />";
						}
						else
						{
							$xml .= "<set value='".number_format($cell_rows['value'], 2, '.', '')."' />";
						}
						
					}
				}
			}
			$xml .= "</dataset>";
		}
		return $xml;
	}

}