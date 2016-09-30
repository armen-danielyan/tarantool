<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Operations_v2 extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Efficiency Operations";
        $oneliner = "One-liner here for Efficiency Operations";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_operations") == FALSE)
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
            	$now = now();
                $tnow = mdate("%M %d\, %Y", $now);
				//$this->jedoxapi->traceme($tnow, "date");
				
            	//$year_month_time_temp = $this->jedoxapi->dimension_elements_base($form_year_month_time);
				//$year_month_time_temp = array_shift($year_month_time_temp);
				
				$year_month_time = $this->get_dimension_data_by_name($form_year_month_time, $tnow);
				//$this->jedoxapi->traceme($year_month_time, "ymt array");
				$year_month_time = $year_month_time['element'];
				
				//$this->jedoxapi->traceme($year_month_time, "ymt");
                //$year_month_time = $this->jedoxapi->get_area($year_month_time_elements, "2014-M01-D10");
            }
			
			if($shift == '')
			{
				$shift = $this->jedoxapi->get_area($shift_elements, "SH_1");
			}
			
			if($equipment == '')
            {
            	$temp_equipment = $this->jedoxapi->dimension_elements_base($equipment_elements);
				$temp_equipment_base = array_shift($temp_equipment);
				$equipment = $temp_equipment_base['element'];
                //$equipment = $this->jedoxapi->get_area($equipment_elements, "EQ");
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
			$operation_value_qp = $this->jedoxapi->get_area($operation_value_elements, "OP_QP");
			$operation_value_kq = $this->jedoxapi->get_area($operation_value_elements, "OP_KQ");
			$operation_value_cq = $this->jedoxapi->get_area($operation_value_elements, "OP_CQ");
			$operation_value_kt = $this->jedoxapi->get_area($operation_value_elements, "OP_KT");
			$operation_value_kt11 = $this->jedoxapi->get_area($operation_value_elements, "OP_KT11");
			$operation_value_ct = $this->jedoxapi->get_area($operation_value_elements, "OP_CT");
			
			$operation_value_oee = $this->jedoxapi->get_area($operation_value_elements, "OEE");
			$operation_value_oeea = $this->jedoxapi->get_area($operation_value_elements, "OEEA");
			$operation_value_oeep = $this->jedoxapi->get_area($operation_value_elements, "OEEP");
			$operation_value_oeeq = $this->jedoxapi->get_area($operation_value_elements, "OEEQ");
			
			/*
			$equipment_element_data = $this->jedox->get_dimension_data_by_id($form_equipment, $equipment);
			$equipment_data = $this->jedoxapi->array_element_filter($form_equipment, $equipment_element_data['name_element']);
			$equipment_base = $this->jedoxapi->dimension_elements_base($equipment_data);
			$equipment_base_alias = $this->jedoxapi->set_alias($equipment_base, $cells_equipment_alias);
			$equipment_base_area = $this->jedoxapi->get_area($equipment_base);
			*/
			
			$product_elements_data = $this->jedox->get_dimension_data_by_id($product_elements, $product);
			//$this->jedoxapi->traceme($product_elements_data);
			$product_data = $this->jedoxapi->array_element_filter($product_elements, $product_elements_data['name_element']);
			$product_base = $this->jedoxapi->dimension_elements_base($product_data);
			$product_base_alias = $this->jedoxapi->set_alias($product_base, $cells_product_alias);
			$product_base_area = $this->jedoxapi->get_area($product_base);
			
			$operation_value_areas = $operation_value_qp.":".$operation_value_kq.":".$operation_value_cq.":".$operation_value_kt.":".$operation_value_kt11.":".$operation_value_ct;
			$operation_value_areas1 = $operation_value_oee.":".$operation_value_oeea.":".$operation_value_oeep.":".$operation_value_oeeq;
			
			$table_area = $version_actual.",".$year_month_time.",".$operation_value_areas.",".$shift.",".$equipment.",".$product_base_area;
			$table_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $table_area, "", "1", "", "0");
			
			$table_area1 = $version_actual.",".$year_month_time.",".$operation_value_areas1.",".$shift.",".$equipment.",".$product;
			$table_data1 = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $table_area1, "", "1", "", "0");
			
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
			
			$chart1_area = $version_actual.",".$day_range_area.",".$operation_value_qp.",".$shift.",".$equipment.",".$product;
			$chart2_area = $version_actual.",".$day_range_area.",".$operation_value_kq.",".$shift.",".$equipment.",".$product;
			$chart3a_area = $version_actual.",".$day_range_area.",".$operation_value_kt.",".$shift.",".$equipment.",".$product;
			$chart3b_area = $version_actual.",".$day_range_area.",".$operation_value_kt11.",".$shift.",".$equipment.",".$product;
			
			$chart1_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart1_area, "", "1", "", "0");
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart2_area, "", "1", "", "0");
			$chart3a_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart3a_area, "", "1", "", "0");
			$chart3b_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart3b_area, "", "1", "", "0");
			
			$chart3_data = $this->jedox->subtract_cell_array($chart3a_data, $chart3b_data, 0, 1);
			
			$chart1 = $this->jedox->singlechart_xml($chart1_data, $day_range_alias, 1);
			$chart2 = $this->jedox->singlechart_xml($chart2_data, $day_range_alias, 1);
			$chart3 = $this->jedox->singlechart_xml($chart3_data, $day_range_alias, 1);
			
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
                "product_base_alias" => $product_base_alias,
                "operation_value_qp" => $operation_value_qp,
                "operation_value_kq" => $operation_value_kq,
                "operation_value_cq" => $operation_value_cq,
                "operation_value_kt" => $operation_value_kt,
                "operation_value_kt11" => $operation_value_kt11,
                "operation_value_ct" => $operation_value_ct,
                "operation_value_oee" => $operation_value_oee,
                "operation_value_oeea" => $operation_value_oeea,
                "operation_value_oeep" => $operation_value_oeep,
                "operation_value_oeeq" => $operation_value_oeeq,
                "table_data" => $table_data,
                "table_data1" => $table_data1,
                "chart1" => $chart1,
                "chart2" => $chart2,
                "chart3" => $chart3,
                "version" => $version,
                "form_version" => $form_version
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_operations_view_v2", $alldata);
            
        }
    }

	public function get_dimension_data_by_name($array, $id)
	{
		$result_array = '';
		foreach($array as $row)
		{
			if($row['name_element'] == $id)
			{
				$result_array = $row;
			}
		}
		return $result_array;
	}

}