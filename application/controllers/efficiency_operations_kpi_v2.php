<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Operations_Kpi_V2 extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Efficiency Operations KPI";
        $oneliner = "One-liner here for Efficiency Operations KPI";
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
            $cube_names = "Manufacturing_KPI,#_Version,#_Receiver,#_Date,#_Man_KPI_Value";
            
            // Initialize post data //
            $version = $this->input->post("version");
            $Man_KPI_Value = $this->input->post("Man_KPI_Value");
			$Man_KPI_Value2 = $this->input->post("Man_KPI_Value2");
			$Man_KPI_Value3 = $this->input->post("Man_KPI_Value3");
            $receiver = $this->input->post("receiver");
			$receiver2 = $this->input->post("receiver2");
            $date_fyear = $this->input->post("date_fyear");
			
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
            $Manufacturing_KPI_id = $this->jedoxapi->get_dimension_id($database_cubes, "Manufacturing_KPI");
            $Manufacturing_KPI_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Manufacturing_KPI");
            
            ////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Manufacturing_KPI_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// RECEIVER //
            // Get dimension of receiver
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Manufacturing_KPI_id[1]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*");
			
			// DATE //
			// Get dimension of date
			$date_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Manufacturing_KPI_id[2]);
			// Get cube data of date alias
			$date_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Date");
			$date_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Date"); 
			// Export cells of month alias
			$date_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $date_dimension_id[0]);
			$date_alias_name_id = $this->jedoxapi->get_area($date_alias_elements, "Name");
			$cells_date_alias = $this->jedoxapi->cell_export($server_database['database'],$date_alias_info['cube'],10000,"", $date_alias_name_id.",*");
			
			// Man_KPI_Value //
			// Get dimension of Man_Stats_Value
			$Man_KPI_Value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Manufacturing_KPI_id[3]);
			// Get cube data of Man_Stats_Value alias
			$Man_KPI_Value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Man_KPI_Value");
			$Man_KPI_Value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Man_KPI_Value"); 
			// Export cells of month alias
			$Man_KPI_Value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Man_KPI_Value_dimension_id[0]);
			$Man_KPI_Value_alias_name_id = $this->jedoxapi->get_area($Man_KPI_Value_alias_elements, "Name");
			$cells_Man_KPI_Value_alias = $this->jedoxapi->cell_export($server_database['database'],$Man_KPI_Value_alias_info['cube'],10000,"", $Man_KPI_Value_alias_name_id.",*");
			
            // FORM DATA //
            
            $form_version = $this->jedoxapi->dimension_sort_by_name($version_elements, "V001,V002"); // plan, actual
            $form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias);
            
            $form_Man_KPI_Value = $this->jedoxapi->set_alias($Man_KPI_Value_elements, $cells_Man_KPI_Value_alias);
            
			$form_receiver = $this->jedoxapi->dimension_sort_by_name($receiver_elements, "North Sea,Scotland,Grangemouth,Grangemouth Line 1,Norway,Karmoy,Karmoy Line 1,Karmoy Line 2,Myre,Myre Line 1,Myre Line 2,Continental Europe,Denmark,Brande,Brande Line 1,Brande Line 2,Greece,Volos,Volos Line 1,France,Nersac,Nersac Line 1,Spain,Duenas,Duenas Line 1,Duenas Line 2,Americas,Chile,Pargua,Pargua Line 1,Pargua Line 2,Castro,Castro Line 1,Castro Line 2,Alitech,Alitech Line 1,Costa Rica,Guanacaste,Guanacaste Line 1");
            $form_receiver = $this->jedoxapi->set_alias($form_receiver, $cells_receiver_alias); // Set aliases
            
            $form_date_fyear = $this->jedoxapi->dimension_sort_by_name($date_elements, "2012,2013,2014,2015");
            
            /////////////
            // PRESETS //
            /////////////
            
            if($version == '')
            {
                $version = $this->jedoxapi->get_area($version_elements, "V002"); // Default set to Actual
            }
			
            if($receiver == '')
            {
                $receiver = $this->jedoxapi->get_area($receiver_elements, "North Sea");
            }
			if($receiver2 == '')
            {
                $receiver2 = $this->jedoxapi->get_area($receiver_elements, "North Sea");
            }
			
			if($Man_KPI_Value == '')
			{
				$Man_KPI_Value = $this->jedoxapi->get_area($Man_KPI_Value_elements, "All_KPI");
			}
			
			if($Man_KPI_Value2 == '')
			{
				$Man_KPI_Value2 = $this->jedoxapi->get_area($Man_KPI_Value_elements, "All_KPI");
			}
			
			if($Man_KPI_Value3 == '')
			{
				$Man_KPI_Value3 = $this->jedoxapi->get_area($Man_KPI_Value_elements, "All_KPI");
			}
			
			if($date_fyear == '')
			{
				$date_fyear = $this->jedoxapi->get_area($date_elements, "2013");
			}
            
            ////////////
            // TABLES //
            ////////////
            $dates_string = '';
            for ($i = 1; $i <= 12; $i++) {
				$dates_string .= date("Ym", strtotime( date( 'Y-m-01' )." -$i months")).",";
			}
            $dates_string = rtrim($dates_string, ",");
			$date = $this->jedoxapi->dimension_sort_by_name($date_elements, $dates_string);
			$date_area = $this->jedoxapi->get_area($date_elements, $dates_string);
            
			$receiver_filtered = $this->jedoxapi->dimension_sort_by_name($receiver_elements, "Grangemouth Line 1,Karmoy Line 1,Karmoy Line 2,Myre Line 1,Myre Line 2,Brande Line 1,Brande Line 2,Volos Line 1,Nersac Line 1,Duenas Line 1,Duenas Line 2,Pargua Line 1,Pargua Line 2,Castro Line 1,Castro Line 2,Alitech Line 1,Guanacaste Line 1");
			$receiver_filtered = $this->jedoxapi->set_alias($receiver_filtered, $cells_receiver_alias); // Set aliases
			
			$receiver_area = $this->jedoxapi->get_area($receiver_filtered);
			
            $table_area = $version.",".$receiver_area.",".$date_area.",".$Man_KPI_Value;
			$table_data = $this->jedoxapi->cell_export($server_database['database'], $Manufacturing_KPI_cube_info['cube'], 10000, "", $table_area, "", "1", "", "0");
			
			$fiscal_year_area = $version.",".$receiver_area.",".$date_fyear.",".$Man_KPI_Value;
			$fiscal_year_data = $this->jedoxapi->cell_export($server_database['database'], $Manufacturing_KPI_cube_info['cube'], 10000, "", $fiscal_year_area, "", "1", "", "0");
			
			////////////
			// CHARTS //
			////////////
			$version_area_pa = $this->jedoxapi->get_area($form_version);
			
			//chart 1 is generated off the data from the table.
			//chart 2 and 5
			
			$chart2_area = $version_area_pa.",".$receiver2.",".$date_area.",".$Man_KPI_Value2;
			$chart2_data = $this->jedoxapi->cell_export($server_database['database'], $Manufacturing_KPI_cube_info['cube'], 10000, "", $chart2_area, "", "1", "", "0");
			
			$chart5_area = $version_area_pa.",".$receiver2.",".$date_area.",".$Man_KPI_Value3;
			$chart5_data = $this->jedoxapi->cell_export($server_database['database'], $Manufacturing_KPI_cube_info['cube'], 10000, "", $chart5_area, "", "1", "", "0");
			
			//chart 3 is generated off the data from the table
			
			//chart 4
			$Man_KPI_Value_all = $this->jedoxapi->array_element_filter($Man_KPI_Value_elements, "All_KPI");
			array_shift($Man_KPI_Value_all);
			$Man_KPI_Value_all_alias = $this->jedoxapi->set_alias($Man_KPI_Value_all, $cells_Man_KPI_Value_alias);
			$Man_KPI_Value_all_area = $this->jedoxapi->get_area($Man_KPI_Value_all);
			
			$chart4_area = $version.",".$receiver.",".$date_area.",".$Man_KPI_Value_all_area;
			$chart4_data = $this->jedoxapi->cell_export($server_database['database'], $Manufacturing_KPI_cube_info['cube'], 10000, "", $chart4_area, "", "1", "", "0");
			
			
			
            //echo $table_area;
			
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "table_data" => $table_data,
                "form_version" => $form_version,
                "form_Man_KPI_Value" => $form_Man_KPI_Value,
                "form_receiver" => $form_receiver,
                "date_elements" => $date_elements,
                "receiver_filtered" => $receiver_filtered,
                "version" => $version,
                "receiver" => $receiver,
                "receiver2" => $receiver2,
                "Man_KPI_Value" => $Man_KPI_Value,
                "Man_KPI_Value2" => $Man_KPI_Value2,
                "Man_KPI_Value3" => $Man_KPI_Value3,
                "Man_KPI_Value_all_alias" => $Man_KPI_Value_all_alias,
                "chart4_data" => $chart4_data,
                "chart2_data" => $chart2_data,
                "chart5_data" => $chart5_data,
                "fiscal_year_data" => $fiscal_year_data,
                "date_fyear" => $date_fyear,
                "form_date_fyear" => $form_date_fyear
                //trace vars here
            );
            // Pass data and show view
            $this->load->view("efficiency_operations_kpi_view_v2", $alldata);
            
        }
    }
   
}
