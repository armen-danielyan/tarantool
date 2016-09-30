<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL);

class Advance_R_Calculations extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
		$this->load->library("jedoxapi");
		$this->load->library("etlapi");
	}
	
	public function index()
	{
		//this page used to be "planning resource cost" but is now renamed. page permission is still the same as previous
		$pagename = "proEO Advance R Calculations";
		$oneliner = "One-liner here for Planning Resource Costs";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		//else if($this->jedoxapi->page_permission($user_details['group_names'], "advance_r_calculations") == FALSE)
		//{
		//	echo "Sorry, you have no permission to access this area.";
		//}
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
			$cube_names = "Operation,Correlation_Report,#_Run_Number,#_Version,#_Year_Month_Time,#_Operation_Value,#_Shift,#_Equipment,#_Product,#_Manufacturing_Kpi,#_Manufacturing_Kpi_Clone,#_Correlation_Value";
			
			// Initialize post data //
            $version    = $this->input->post("version");
            $equipment  = $this->input->post("equipment");
			$run_number = $this->input->post("run_number");
			$mfg_kpi_1  = $this->input->post("mfg_kpi_1");
			$mfg_kpi_2  = $this->input->post("mfg_kpi_2");
			$m_stat1    = $this->input->post("m_stat1");
			$m_stat2    = $this->input->post("m_stat2"); 
			$shift      = $this->input->post("shift");
			$product    = $this->input->post("product");
			
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
            $Correlation_id = $this->jedoxapi->get_dimension_id($database_cubes, "Correlation_Report");
            $Correlation_Report_info = $this->jedoxapi->get_cube_data($database_cubes, "Correlation_Report");
			
			$Operation_id = $this->jedoxapi->get_dimension_id($database_cubes, "Operation");
            $Operation_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Operation");
						
			////////////////////////////
            // Get Dimension Elements //
            ////////////////////////////
            
            // RUN NUMBER //
            // Get dimension of run number
            $run_number_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Correlation_id[0]);
            // Get cube data of version alias
            $run_number_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Run_Number");
            $run_number_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Run_Number");
            // Export cells of version alias
            $run_number_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $run_number_dimension_id[0]);
			$run_number_alias_name_id = $this->jedoxapi->get_area($run_number_alias_elements, "Name");
            $cells_run_number_alias = $this->jedoxapi->cell_export($server_database['database'],$run_number_alias_info['cube'],10000,"",$run_number_alias_name_id.",*");
			
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Correlation_id[1]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// EQUIPMENT //
            // Get dimension of equipment
            $equipment_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Correlation_id[2]);
            // Get cube data of equipment alias
            $equipment_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Equipment");
            $equipment_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Equipment");
            // Export cells of equipment alias
            $equipment_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $equipment_dimension_id[0]);
			$equipment_alias_name_id = $this->jedoxapi->get_area($equipment_alias_elements, "Name");
            $cells_equipment_alias = $this->jedoxapi->cell_export($server_database['database'],$equipment_alias_info['cube'],10000,"", $equipment_alias_name_id.",*"); 
			
			// MANUFACTURING KPI //
            // Get dimension of mfg_kpi_1
            $mfg_kpi_1_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Correlation_id[3]);
            // Get cube data of mfg_kpi_1 alias
            $mfg_kpi_1_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Manufacturing_Kpi");
            $mfg_kpi_1_alias_info   = $this->jedoxapi->get_cube_data($database_cubes, "#_Manufacturing_Kpi");
            // Export cells of mfg_kpi_1 alias
            $mfg_kpi_1_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $mfg_kpi_1_dimension_id[0]);
			$mfg_kpi_1_alias_name_id  = $this->jedoxapi->get_area($mfg_kpi_1_alias_elements, "Name");
            $cells_mfg_kpi_1_alias    = $this->jedoxapi->cell_export($server_database['database'],$mfg_kpi_1_alias_info['cube'],10000,"", $mfg_kpi_1_alias_name_id.",*"); 
			
			// MANUFACTURING KPI CLONE //
            // Get dimension of mfg_kpi_2
            $mfg_kpi_2_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Correlation_id[4]);
            // Get cube data of mfg_kpi_2 alias
            $mfg_kpi_2_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Manufacturing_Kpi_Clone");
            $mfg_kpi_2_alias_info   = $this->jedoxapi->get_cube_data($database_cubes, "#_Manufacturing_Kpi_Clone");
            // Export cells of mfg_kpi_2 alias
            $mfg_kpi_2_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $mfg_kpi_2_dimension_id[0]);
			$mfg_kpi_2_alias_name_id  = $this->jedoxapi->get_area($mfg_kpi_2_alias_elements, "Name");
            $cells_mfg_kpi_2_alias    = $this->jedoxapi->cell_export($server_database['database'],$mfg_kpi_2_alias_info['cube'],10000,"", $mfg_kpi_2_alias_name_id.",*");
			
			// CORRELATION_VALUE //
            // Get dimension of Correlation_Value
            $r_values_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Correlation_id[5]);
            // Get cube data of Correlation_Value alias
            $r_values_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Correlation_Value");
            $r_values_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Correlation_Value");
            // Export cells of Correlation_Value alias
            $r_values_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $r_values_dimension_id[0]);
			$r_values_alias_name_id = $this->jedoxapi->get_area($r_values_alias_elements, "Name");
            $cells_r_values_alias = $this->jedoxapi->cell_export($server_database['database'],$r_values_alias_info['cube'],10000,"", $r_values_alias_name_id.",*");

			// DATE //
			// Get dimension of date
			$date_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Operation_id[1]);
			// Get cube data of date alias
			$date_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year_Month_Time");
			$date_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year_Month_Time"); 
			// Export cells of month alias
			$date_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $date_dimension_id[0]);
			$date_alias_name_id = $this->jedoxapi->get_area($date_alias_elements, "Name");
			$cells_date_alias = $this->jedoxapi->cell_export($server_database['database'],$date_alias_info['cube'],10000,"", $date_alias_name_id.",*");
			
			// OPERATION VALUE //
			// Get dimension of Man_Stats_Value
			$Man_Stats_Value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Operation_id[2]);
			$Man_Stats_Value_elements = $this->jedoxapi->array_element_filter($Man_Stats_Value_elements, "OP_KPI"); // Only keep children of OP_KPI
			// Get cube data of Man_Stats_Value alias
			$Man_Stats_Value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Operation_Value");
			$Man_Stats_Value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Operation_Value"); 
			// Export cells of month alias
			$Man_Stats_Value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Man_Stats_Value_dimension_id[0]);
			$Man_Stats_Value_alias_name_id = $this->jedoxapi->get_area($Man_Stats_Value_alias_elements, "Name");
			$cells_Man_Stats_Value_alias = $this->jedoxapi->cell_export($server_database['database'],$Man_Stats_Value_alias_info['cube'],10000,"", $Man_Stats_Value_alias_name_id.",*");
			//$this->jedoxapi->traceme($cells_Man_Stats_Value_alias);
			// SHIFT //
			// Get dimension of Shift
			$Shift_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Operation_id[3]);
			// Get cube data of Shift alias
			$Shift_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Shift");
			$Shift_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Shift"); 
			// Export cells of Shift alias
			$Shift_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Shift_dimension_id[0]);
			$Shift_alias_name_id = $this->jedoxapi->get_area($Shift_alias_elements, "Name");
			$cells_Shift_alias = $this->jedoxapi->cell_export($server_database['database'],$Shift_alias_info['cube'],10000,"", $Shift_alias_name_id.",*");

			// PRODUCT //
			// Get dimension of Product
			$Product_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Operation_id[5]);
			// Get cube data of Product alias
			$Product_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Product");
			$Product_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Product"); 
			// Export cells of Product alias
			$Product_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Product_dimension_id[0]);
			$Product_alias_name_id = $this->jedoxapi->get_area($Product_alias_elements, "Name");
			$cells_Product_alias = $this->jedoxapi->cell_export($server_database['database'],$Product_alias_info['cube'],10000,"", $Product_alias_name_id.",*");
			
			/////////////
            // PRESETS //
            /////////////
            
            // ATTRIBUTES // !!!!!!!!!!!!!
            $run_number_attributes      = $this->jedoxapi->get_area($run_number_alias_elements, "Description,Run_Date,Run_Time,User,Date_From,Date_To,Version");
            $run_number_attributes_data = $this->jedoxapi->cell_export($server_database['database'],$run_number_alias_info['cube'],10000,"", $run_number_attributes.",".$run_number, '', '1', '', '0');
			
			//detect start and end dates...
			foreach ( $run_number_alias_elements as $attri ) {
    			foreach ( $run_number_attributes_data as $aval )
    			{
    				$path = explode(",", $aval['path']);
					if($attri['element'] == $path[0])
					{
						$nname = str_replace(' ', '_', $attri['name_element']);
						${$nname} = $aval['value'];
					}
    			}
				
			}
            
            if( $version == '' )
            {  
                $version = $this->jedoxapi->get_area($version_elements, "V002"); //Actual
            }
			
            if( $equipment == '' )
            {
                $equipment = $this->jedoxapi->get_area($equipment_elements, "EQ"); // All Equipments
            }
			
			if( $run_number == '' ) // Take last run
			{
				//$run_number = $this->jedoxapi->get_area($run_number_elements, "1");
				$run_number = $this->jedoxapi->get_area($run_number_elements);
				$r_ex = explode(":", $run_number);
				$run_number = end($r_ex);
			}
			
			if( $mfg_kpi_1 == '' )
			{
				$mfg_kpi_1 = $this->jedoxapi->get_area($mfg_kpi_1_elements, "OP_KPI"); // All Manufacturing Kpis
			}
			
			if( $mfg_kpi_2 == '' )
			{
				$mfg_kpi_2 = $this->jedoxapi->get_area($mfg_kpi_2_elements, "OP_KPI"); //  All Manufacturing Kpis
			}

			if( $m_stat1 == '')
			{
				$m_stat1 = $this->jedoxapi->get_area($Man_Stats_Value_elements, "OP_KPI");
			}
			
			if( $m_stat2 == '' )
			{
				$m_stat2 = $this->jedoxapi->get_area($Man_Stats_Value_elements, "OP_KPI");
			}
			
			if( $shift == '' )
			{
				$shift = $this->jedoxapi->get_area($Shift_elements, "SH");
			}

			if( $product == '' )
			{
				$product = $this->jedoxapi->get_area($Product_elements, "AP");
			}
			
			// FORM DATA //
			
            $form_version = $this->jedoxapi->dimension_sort_by_name($version_elements, "V002,V001");
			$form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias);
			
			//$r_perm = $this->jedoxapi->receiver_permission($user_details['group_names'], "BG");
			//$form_receiver = $this->jedoxapi->array_element_filter($equipment_elements, $r_perm); // get the main branch
			//$form_receiver = $this->getbase($form_receiver); // filter out and get only base
			
			$form_equipment = $this->jedoxapi->array_element_filter($equipment_elements, "EQ");
            $form_equipment = $this->jedoxapi->set_alias($form_equipment, $cells_equipment_alias); // Set aliases
			
			//$form_run_number = $this->jedoxapi->set_alias($run_number_elements, $cells_run_number_alias);
			$form_run_number = $this->jedoxapi->set_alias( $run_number_elements, $cells_run_number_alias );
			
			$form_mfg_kpi_1 = $this->jedoxapi->set_alias( $mfg_kpi_1_elements, $cells_mfg_kpi_1_alias );
			$form_mfg_kpi_2 = $this->jedoxapi->set_alias( $mfg_kpi_2_elements, $cells_mfg_kpi_2_alias );
			
			$form_m_stat1 = $this->jedoxapi->set_alias($Man_Stats_Value_elements, $cells_Man_Stats_Value_alias);
			$form_m_stat2 = $this->jedoxapi->set_alias($Man_Stats_Value_elements, $cells_Man_Stats_Value_alias);
			
			////////////
			// TABLES //
			////////////
			$mfg_kpi_1_name = $this->jedox->get_dimension_data_by_id($mfg_kpi_1_elements, $mfg_kpi_1);
			$mfg_kpi_1_dd = $this->jedoxapi->array_element_filter($mfg_kpi_1_elements, $mfg_kpi_1_name['name_element']);
			$mfg_kpi_1_area  = $this->jedoxapi->get_area($mfg_kpi_1_dd);
			$mfg_kpi_1_alias = $this->jedoxapi->set_alias($mfg_kpi_1_dd, $cells_mfg_kpi_1_alias);
			
			$mfg_kpi_2_name = $this->jedox->get_dimension_data_by_id($mfg_kpi_2_elements, $mfg_kpi_2);
			$mfg_kpi_2_dd = $this->jedoxapi->array_element_filter($mfg_kpi_2_elements, $mfg_kpi_2_name['name_element']);
			$mfg_kpi_2_area = $this->jedoxapi->get_area($mfg_kpi_2_dd);
			$mfg_kpi_2_alias = $this->jedoxapi->set_alias($mfg_kpi_2_dd, $cells_mfg_kpi_2_alias);
			
			$equipment_name = $this->jedox->get_dimension_data_by_id($equipment_elements, $equipment);
			$equipment_dd = $this->jedoxapi->array_element_filter($equipment_elements, $equipment_name['name_element']);
			$equipment_area = $this->jedoxapi->get_area($equipment_dd);
			$equipment_alias = $this->jedoxapi->set_alias($equipment_dd, $cells_equipment_alias);
			
			$r_number_data = $this->jedox->get_dimension_data_by_id($run_number_elements, $run_number);
			
			$r_values_P_Value_area = $this->jedoxapi->get_area($r_values_elements, "P_Value");
			
			$table1_area = $run_number.",".$version.",".$equipment_area.",".$mfg_kpi_1_area.",".$mfg_kpi_2_area.",".$r_values_P_Value_area;
			//$this->jedoxapi->traceme($table1_area, "Table data");
			$table1_data = $this->jedoxapi->cell_export($server_database['database'], $Correlation_Report_info['cube'], 10000, '', $table1_area, '', '1', '', '0');
			
			////////////
			// CHARTS // 
			////////////
			$date_base = $this->jedoxapi->dimension_elements_base($date_elements);
			
			$date_range = array();
			$date_count = 0;
			foreach( $date_base as $row)
			{
				if( $Date_From == $row['name_element'] )
				{
					$date_count = 1;
				}
				else if( $Date_To == $row['name_element'] )
				{
					$date_count = 0;
					$date_range[] = $row; // must add the final node...
				}
				
				if( $date_count == 1 )
				{
					$date_range[] = $row;
				}
			}
			
			$date_preset = $this->jedoxapi->get_area( $date_range );
			if( $date_preset == null ) { $date_preset = "*"; }
			
			$Man_Stats_Value_elements_alias = $this->jedoxapi->set_alias($Man_Stats_Value_elements, $cells_Man_Stats_Value_alias);
			
			$m_stat1_name = $this->jedox->get_dimension_data_by_id( $Man_Stats_Value_elements_alias, $m_stat1);
			//$this->jedoxapi->traceme($m_stat1_name);
			$m_stat1_name = $this->jedoxapi->dimension_elements_id( $Man_Stats_Value_elements_alias, $m_stat1_name['name_element']);
			//$this->jedoxapi->traceme($m_stat1_name);
			$m_stat2_name = $this->jedox->get_dimension_data_by_id( $Man_Stats_Value_elements_alias, $m_stat2);
			$m_stat2_name = $this->jedoxapi->dimension_elements_id( $Man_Stats_Value_elements_alias, $m_stat2_name['name_element']);
			
			$version_data = $this->jedox->get_dimension_data_by_id( $version_elements,         $version);
			
			$chart1xml = $this->jedox->multichart_xml_categories($date_range, 0);
			
			$chart_area1 = $version.",".$date_preset.",".$m_stat1.",".$shift.",".$equipment.",".$product;
			$chart_area2 = $version.",".$date_preset.",".$m_stat2.",".$shift.",".$equipment.",".$product;
			
			$chart_data1 = $this->jedoxapi->cell_export($server_database['database'], $Operation_cube_info['cube'], 10000, "", $chart_area1, "", "1", "", "0");
			$chart_data2 = $this->jedoxapi->cell_export($server_database['database'], $Operation_cube_info['cube'], 10000, "", $chart_area2, "", "1", "", "0");
			
			$chart1xml .= $this->multichart_xml_series($chart_data1, $date_range, $m_stat1_name, 1, 2);
			$chart1xml .= $this->multichart_xml_series($chart_data2, $date_range, $m_stat2_name, 1, 2);
			
			$datestring = "%Y-M%m-D%d";
			$time = time();

			$date_now = mdate($datestring, $time);

			// Pass all data to send to view file
            $alldata = array(
                //regular vars here
               	"form_version"       => $form_version,
                "form_equipment"     => $form_equipment,
                "form_run_number"    => $form_run_number,
                "form_mfg_kpi_1"     => $form_mfg_kpi_1,
                "form_mfg_kpi_2"     => $form_mfg_kpi_2,
                "version"            => $version,
                "equipment"          => $equipment,
                "run_number"         => $run_number,
				"Description"        => $Description,
				"Run_Date"           => $Run_Date,
				"Run_Time"           => $Run_Time,	
				"User"	             => $User,
				"Date_From"	         => $Date_From,
				"Date_To"	         => $Date_To,
				"Version"            => $Version,
                "mfg_kpi_1"          => $mfg_kpi_1,
                "mfg_kpi_2"          => $mfg_kpi_2,
                "jedox_user_details" => $user_details,
                "pagename"           => $pagename,
                "oneliner"           => $oneliner,
                "mfg_kpi_1_alias"    => $mfg_kpi_1_alias,
                "mfg_kpi_2_alias"    => $mfg_kpi_2_alias,
                "equipment_alias" => $equipment_alias,
                "table1_data"        => $table1_data,
                "r_number_data"      => $r_number_data,
                "run_number_alias_elements"  => $run_number_alias_elements,
                "run_number_attributes_data" => $run_number_attributes_data,
                "m_stat1"            => $m_stat1,
                "m_stat2"            => $m_stat2,
                "form_m_stat1"       => $form_m_stat1,
                "form_m_stat2"       => $form_m_stat2,
                "chart1xml"          => $chart1xml,
                "date_base"          => $date_base,
                "date_now" => $date_now
            );
			
            // Pass data and show view
            $this->load->view("advance_r_calculations_view", $alldata);
		}
	}
	
	public function multichart_xml_series($cells, $categories, $series, $category_path, $series_path)
	{
		$xml = '';
		foreach($series as $ser_rows)
		{
			if($ser_rows['name_element'] == "All KPI's")
			{
				$ser_rows['name_element'] = "All KPI";
			}
			
			$xml .= "<dataset seriesName='".$ser_rows['name_element']."'>";
			foreach($categories as $cat_rows)
			{
				foreach($cells as $cell_rows)
				{
					$path = explode(",", $cell_rows['path']);
					if($cat_rows['element'] == $path[$category_path] && $ser_rows['element'] == $path[$series_path])
					{
						if($cell_rows['value'] == '')
						{
							$xml .= "<set value='' />";
						}
						else
						{
							$xml .= "<set value='".round($cell_rows['value'])."' />";
						}
						
					}
				}
			}
			$xml .= "</dataset>";
		}
		return $xml;
	}
	
	public function execute()
	{
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		} else {
			$database_name = $this->session->userdata('jedox_db');
			$cube_names = "Operation,#_Version,#_Year_Month_Time,#_Operation_Value,#_Shift,#_Equipment,#_Product";
			//initialize port data
			//$R_Run = $this->input->post("R_Run");
			$User        = $this->session->userdata('jedox_user');
			$Version     = $this->input->post("version");
			$Date_From   = $this->input->post("Date_From");
			$Date_To     = $this->input->post("Date_To");
			$Description = $this->input->post("Description");
			
            // Get Database
            $server_database_list = $this->jedoxapi->server_databases();
            $server_database = $this->jedoxapi->server_databases_select($server_database_list, $database_name);
            
            // Get Cubes
            $database_cubes = $this->jedoxapi->database_cubes($server_database['database'], 1,0,1);
            
            // Dynamically load selected cubes based on names
            $cube_multiload = $this->jedoxapi->cube_multiload($server_database['database'], $database_cubes, $cube_names);
            
            // Get Dimensions ids.
			$Operation_id = $this->jedoxapi->get_dimension_id($database_cubes, "Operation");
            $Operation_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Operation");
			
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Operation_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// DATE //
			// Get dimension of date
			$date_elements = $this->jedoxapi->dimension_elements($server_database['database'], $Operation_id[1]);
			// Get cube data of date alias
			$date_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year_Month_Time");
			$date_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year_Month_Time"); 
			// Export cells of month alias
			$date_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $date_dimension_id[0]);
			$date_alias_name_id = $this->jedoxapi->get_area($date_alias_elements, "Name");
			$cells_date_alias = $this->jedoxapi->cell_export($server_database['database'],$date_alias_info['cube'],10000,"", $date_alias_name_id.",*");
			
			$Version_data = $this->jedox->get_dimension_data_by_id($version_elements, $Version);
			$Version = $Version_data['name_element'];
			
			$Date_From_data = $this->jedox->get_dimension_data_by_id($date_elements, $Date_From);
			$Date_To_data = $this->jedox->get_dimension_data_by_id($date_elements, $Date_To);
			
			$Date_From = $Date_From_data['name_element'];
			$Date_To   = $Date_To_data['name_element'];
			
			// Define Server
			$server_url = ''; // initialize variable
			if(base_url() == "http://demo.proeo.com/")
			{
				$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
			} else {
				$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
			}

			// Connect to Soap Server
			$server = new SoapClient($server_url, array('exceptions' => true, 'location' => $server_url));

			// Login Attempt on Soap Object
			$login_attempt = $server->login(array('user' => $this->session->userdata('jedox_user'), 'password' => $this->session->userdata('jedox_pass')))->return;
			$session = $login_attempt->result;

			// Soap Header
			$header = new SoapHeader('http://ns.jedox.com/ETL-Server/', 'etlsession', $session);    
			$server->__setSoapHeaders($header);
			
			// Prepare Variables
			$variables = array(
					//array('name' => 'R_Run','value' => $R_Run ), 
					array('name' => 'Database',    'value' => $this->session->userdata('jedox_db') ), 
					array('name' => 'User',        'value' => $User ), 
					array('name' => 'Version',     'value' => $Version ), 
					array('name' => 'Date_From',   'value' => $Date_From ), 
					array('name' => 'Date_To',     'value' => $Date_To ), 
					array('name' => 'Description', 'value' => $Description )
				);

			// Prepare Job Execution
			$locator    = "ProEo_Template.jobs.Job_Rul_Correlation_Groovy";
		    $response  = $server->AddExecution( array('locator' => $locator, 'variables' => $variables ) );
		    $return    = $response->return;
			$id = $return->id;

		    // Execute Job
		    $response  = $server->runExecution( array('id' => $id) );
		    $return    = $response->return;
		}
	}
	
}