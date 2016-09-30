<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Operations_v3 extends CI_Controller {

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
        else if($this->jedoxapi->page_permission($user_details['group_names'], "operations_kpi") == FALSE)
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
			$version         = $this->input->post("version");
            $year_month_time = $this->input->post("year_month_time");
            $shift           = $this->input->post("shift");
            $equipment       = $this->input->post("equipment");
			$product         = $this->input->post("product");
			
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

            $form_year_month_time = $this->jedoxapi->array_element_filter($year_month_time_elements, "TA");
            $form_year_month_time = $this->jedoxapi->set_alias($form_year_month_time, $cells_year_month_time_alias);

            $form_shift = $this->jedoxapi->set_alias($shift_elements, $cells_shift_alias);
			
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
                foreach( $year_month_time_elements as $year_month_time_line )
                {
                    if( $year_month_time_line['number_children'] > 0 ) { continue; }
                    $year_month_time = $this->jedoxapi->get_area($year_month_time_elements, $year_month_time_line['name_element']);
                }
            }
			
            if($shift == '')
            {
                $shift = $this->jedoxapi->get_area($shift_elements, "SH");
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
			
            $version_actual = $version; //adjusted to dynamically change based on filter
            $operation_value_qp = $this->jedoxapi->get_area($operation_value_elements, "OP_QP");
            $operation_value_kq = $this->jedoxapi->get_area($operation_value_elements, "OP_KQ");
            $operation_value_cq = $this->jedoxapi->get_area($operation_value_elements, "OP_CQ");
            $operation_value_kt = $this->jedoxapi->get_area($operation_value_elements, "OP_KT");
            $operation_value_ct = $this->jedoxapi->get_area($operation_value_elements, "OP_CT");

            $operation_value_kp_1010 = $this->jedoxapi->get_area($operation_value_elements, "KP_1010_2"); // OEE
            $operation_value_kp_1020 = $this->jedoxapi->get_area($operation_value_elements, "KP_1020_2"); // TEEP
            $operation_value_kp_1030 = $this->jedoxapi->get_area($operation_value_elements, "KP_1030_2"); // Availability
            $operation_value_kp_1040 = $this->jedoxapi->get_area($operation_value_elements, "KP_1040_2"); // Performance
            $operation_value_kp_1050 = $this->jedoxapi->get_area($operation_value_elements, "KP_1050_2"); // Quality / Yield
			
			//echo $operation_value_kp_1010.":".$operation_value_kp_1020.":".$operation_value_kp_1030.":".$operation_value_kp_1040.":".$operation_value_kp_1050;
			//echo $operation_value_kt;
			
            $product_elements_data = $this->jedox->get_dimension_data_by_id($form_product, $product);
            $product_data = $this->jedoxapi->array_element_filter($form_product, $product_elements_data['name_element']);
            $product_base = $this->jedoxapi->dimension_elements_base($product_data);
            $product_base_alias = $this->jedoxapi->set_alias($product_base, $cells_product_alias);
            $product_base_area = $this->jedoxapi->get_area($product_base);
			
            $operation_value_areas = $operation_value_qp.":".$operation_value_kq.":".$operation_value_cq.":".$operation_value_kt.":".$operation_value_ct.":".$operation_value_kp_1010.":".$operation_value_kp_1020.":".$operation_value_kp_1030.":".$operation_value_kp_1040.":".$operation_value_kp_1050;
			
            //$table_area = $version_actual.",".$year_month_time.",*,".$shift.",".$equipment.",*";
			$table_area = $version_actual.",".$year_month_time.",".$operation_value_areas.",".$shift.",".$equipment.",".$product_base_area;
            $table_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $table_area, "", "1", "", "0");

            ////////////
            // CHARTS //
            ////////////
			
            $year_month_time_element_data = $this->jedox->get_dimension_data_by_id($year_month_time_elements, $year_month_time);
			
            $year_month_time_year  = substr($year_month_time_element_data['name_element'], 0, 4);
            $year_month_time_month = substr($year_month_time_element_data['name_element'], 6, 2);
            $year_month_time_day   = substr($year_month_time_element_data['name_element'], 10, 2);
			
            $daybase = mdate("%Y%m%d", strtotime($year_month_time_month."/".$year_month_time_day."/".$year_month_time_year));
			
            $day1 = mdate("%Y-M%m-D%d", strtotime($year_month_time_month."/".$year_month_time_day."/".$year_month_time_year));
            $day2 = mdate("%Y-M%m-D%d", strtotime($daybase."- 7 day"));
            $day3 = mdate("%Y-M%m-D%d", strtotime($daybase."- 14 day"));
            $day4 = mdate("%Y-M%m-D%d", strtotime($daybase."- 21 day"));
            $day5 = mdate("%Y-M%m-D%d", strtotime($daybase."- 28 day"));
            $day6 = mdate("%Y-M%m-D%d", strtotime($daybase."- 35 day"));

            $year_month_time_string = $day1.",".$day2.",".$day3.",".$day4.",".$day5.",".$day6;

            $day_range = $this->jedoxapi->dimension_sort_by_name($year_month_time_elements, $year_month_time_string);
            $day_range_alias = $this->jedoxapi->set_alias($day_range, $cells_year_month_time_alias);
            $day_range_area = $this->jedoxapi->get_area($day_range);

            $chart1_area = $version_actual.",".$day_range_area.",".$operation_value_qp.",".$shift.",".$equipment.",".$product;
            $chart2_area = $version_actual.",".$day_range_area.",".$operation_value_kq.",".$shift.",".$equipment.",".$product;
            $chart3_area = $version_actual.",".$day_range_area.",".$operation_value_kt.",".$shift.",".$equipment.",".$product;
			
            $chart1_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart1_area, "", "1", "", "0");
            $chart2_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart2_area, "", "1", "", "0");
            $chart3_data = $this->jedoxapi->cell_export($server_database['database'], $operations_cube_info['cube'], 10000, "", $chart3_area, "", "1", "", "0");

            $chart1 = $this->jedox->singlechart_xml($chart1_data, $day_range_alias, 1);
            $chart2 = $this->jedox->singlechart_xml($chart2_data, $day_range_alias, 1);
            $chart3 = $this->jedox->singlechart_xml($chart3_data, $day_range_alias, 1);
			
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename"                => $pagename,
                "oneliner"                => $oneliner,
                "jedox_user_details"      => $user_details,
                "form_year_month_time"    => $form_year_month_time,
                "form_shift"              => $form_shift,
                "form_equipment"          => $form_equipment,
                "form_product"            => $form_product,
                "year_month_time"         => $year_month_time,
                "shift"                   => $shift,
                "equipment"               => $equipment,
                "product"                 => $product,
                "product_base_alias"      => $product_base_alias,
                "operation_value_qp"      => $operation_value_qp,
                "operation_value_kq"      => $operation_value_kq,
                "operation_value_kt"      => $operation_value_kt,
                "operation_value_cq"      => $operation_value_cq,
                "operation_value_ct"      => $operation_value_ct,

                "operation_value_kp_1010" => $operation_value_kp_1010,
                "operation_value_kp_1020" => $operation_value_kp_1020,
                "operation_value_kp_1030" => $operation_value_kp_1030,
                "operation_value_kp_1040" => $operation_value_kp_1040,
                "operation_value_kp_1050" => $operation_value_kp_1050,

                "table_data"              => $table_data,
                "chart1"                  => $chart1,
                "chart2"                  => $chart2,
                "chart3"                  => $chart3,
                "version"                 => $version,
                "form_version"            => $form_version
                //trace vars here
                
            );

            // Pass data and show view
            $this->load->view("efficiency_operations_view_v3", $alldata);
            
        }
    }
}