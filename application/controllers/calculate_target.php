<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calculate_Target extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
		$this->load->library('session');
		$this->load->library("etlapi");
    }
    
    public function index ()
    {
        $pagename = "proEO Calculate Target";
        $oneliner = "One-liner here for Calculate Target";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_costs") == FALSE)
        {
            // currently using efficiency cost as permission
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
            //$cube_names = "Secondary,#_Version,#_Year,#_Month,#_Receiver,#_Secondary_Value,#_Sender";
            $cube_names = "Primary,Secondary,Raw_Material_Rate,Bom,Production,Margin,Capacity_Rate,Income,#_Version,#_Year,#_Month,#_Margin_Value,#_Product,#_Customer,#_Primary_Value,#_Account_Element,#_Receiver,#_Secondary_Value,#_Sender,#_Raw_Material_Rate_Value,#_Raw_Material,#_Bom_Value,#_Production_Value,#_Capacity_Rate_Value,#_Income_Value";
            
			// Initialize post data //
			
			$year    = $this->input->post("year");
            $month   = $this->input->post("month");
			
			$action = $this->input->post("Action");
			$step = $this->input->post("step");
			
			$status = '';
			
			
			
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
            $margin_dimension_id 			= $this->jedoxapi->get_dimension_id($database_cubes, "Margin");
            $margin_cube_info 				= $this->jedoxapi->get_cube_data($database_cubes, "Margin");
			
			$primary_dimension_id           = $this->jedoxapi->get_dimension_id( $database_cubes, "Primary");
            $primary_cube_info              = $this->jedoxapi->get_cube_data(    $database_cubes, "Primary");
			
			$secondary_dimension_id         = $this->jedoxapi->get_dimension_id( $database_cubes, "Secondary");
            $secondary_cube_info            = $this->jedoxapi->get_cube_data(    $database_cubes, "Secondary");
			
			$raw_material_rate_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "Raw_Material_Rate");
            $raw_material_rate_cube_info    = $this->jedoxapi->get_cube_data(    $database_cubes, "Raw_Material_Rate");
            
			$bom_dimension_id               = $this->jedoxapi->get_dimension_id( $database_cubes, "Bom");
            $bom_cube_info                  = $this->jedoxapi->get_cube_data(    $database_cubes, "Bom");
			
			$production_dimension_id        = $this->jedoxapi->get_dimension_id( $database_cubes, "Production");
            $production_cube_info           = $this->jedoxapi->get_cube_data(    $database_cubes, "Production");
			
			$capacity_rate_dimension_id        = $this->jedoxapi->get_dimension_id( $database_cubes, "Capacity_Rate");
            $capacity_rate_cube_info           = $this->jedoxapi->get_cube_data(    $database_cubes, "Capacity_Rate");
			
			$income_dimension_id               = $this->jedoxapi->get_dimension_id( $database_cubes, "Income");
            $income_cube_info                  = $this->jedoxapi->get_cube_data(    $database_cubes, "Income");
			
			////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
            // VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
            
            // YEAR //
            // Get dimension of year
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
			
			
            // MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
            
            // margin VALUE //
            // Get dimension of margin value
            $margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[3]);
            // Get cube data of margin value alias
            $margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
            $margin_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Margin_Value");
            // Export cells of margin value alias
            $margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
            $cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*");
            
            // product //
            // Get dimension of product
            $product_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[4]);
            // Get cube data of product alias
            $product_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Product");
            $product_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Product");
            // Export cells of product value alias
            $product_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_dimension_id[0]);
			$product_alias_name_id = $this->jedoxapi->get_area($product_alias_elements, "Name");
            $cells_product_alias = $this->jedoxapi->cell_export($server_database['database'],$product_alias_info['cube'],10000,"", $product_alias_name_id.",*"); 
            
			// customer //
            // Get dimension of customer
            $customer_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[5]);
            // Get cube data of customer alias
            $customer_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Customer");
            $customer_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Customer");
            // Export cells of customer alias
            $customer_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $customer_dimension_id[0]);
			$customer_alias_name_id = $this->jedoxapi->get_area($customer_alias_elements, "Name");
            $cells_customer_alias = $this->jedoxapi->cell_export($server_database['database'],$customer_alias_info['cube'],10000,"", $customer_alias_name_id.",*"); 
            
			// PRIMARY_VALUE //
            // Get dimension of primary value
			$primary_value_elements     = $this->jedoxapi->dimension_elements( $server_database['database'], $primary_dimension_id[3] );
            // Get cube data of primary value alias
			$primary_value_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Primary_Value");
			$primary_value_alias_info   = $this->jedoxapi->get_cube_data(    $database_cubes, "#_Primary_Value");
            // Export cells of primary value alias
			$primary_value_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $primary_value_dimension_id[0] );
			$primary_value_alias_name_id  = $this->jedoxapi->get_area( $primary_value_alias_elements, "Name" );
            $cells_primary_value_alias    = $this->jedoxapi->cell_export( $server_database['database'], $primary_value_alias_info['cube'], 10000, "", $primary_value_alias_name_id.",*" );
			
			// ACCOUNT_ELEMENT //
			$account_element_elements        = $this->jedoxapi->dimension_elements( $server_database['database'], $primary_dimension_id[4] );
			// Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*");
			
			// RECEIVER //
            // Get dimension of receiver
			$receiver_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $primary_dimension_id[5] );
            // Get cube data of receiver alias
			$receiver_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Receiver");
			$receiver_alias_info   = $this->jedoxapi->get_cube_data( $database_cubes, "#_Receiver");
			// Export cells of receiver alias
			$receiver_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $receiver_dimension_id[0] );
			$receiver_alias_name_id  = $this->jedoxapi->get_area( $receiver_alias_elements, "Name");
			$cells_receiver_alias = $this->jedoxapi->cell_export( $server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*"); 
			
			// SECONDARY_VALUE //
			$secondary_value_elements   = $this->jedoxapi->dimension_elements( $server_database['database'], $secondary_dimension_id[3] );
			// Get cube data of secondary_value alias
			$secondary_value_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Secondary_Value");
			$secondary_value_alias_info   = $this->jedoxapi->get_cube_data( $database_cubes, "#_Secondary_Value");
			// Export cells of secondary_value alias
			$secondary_value_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $secondary_value_dimension_id[0] );
			$secondary_value_alias_name_id  = $this->jedoxapi->get_area( $secondary_value_alias_elements, "Name");
			$cells_secondary_value_alias = $this->jedoxapi->cell_export( $server_database['database'],$secondary_value_alias_info['cube'],10000,"", $secondary_value_alias_name_id.",*"); 
			
			// SENDER // 
			// Get dimension of sender
			$sender_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $secondary_dimension_id[4] );
            // Get cube data of receiver alias
			$sender_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Sender");
			$sender_alias_info   = $this->jedoxapi->get_cube_data(    $database_cubes, "#_Sender");
			// Export cells of receiver alias
			$sender_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $sender_dimension_id[0] );
			$sender_alias_name_id  = $this->jedoxapi->get_area(    $sender_alias_elements, "Name");
			$cells_sender_alias    = $this->jedoxapi->cell_export( $server_database['database'],$sender_alias_info['cube'],10000,"", $sender_alias_name_id.",*"); 
			
			// RAW_MATERIAL_VALUE //
            // Get dimension of raw material value
			$raw_material_value_elements     = $this->jedoxapi->dimension_elements( $server_database['database'], $raw_material_rate_dimension_id[3] );
            // Get cube data of raw material value alias
			$raw_material_value_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Raw_Material_Rate_Value");
			$raw_material_value_alias_info   = $this->jedoxapi->get_cube_data(    $database_cubes, "#_Raw_Material_Rate_Value");
            // Export cells of raw material value alias
			$raw_material_value_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $raw_material_value_dimension_id[0] );
			$raw_material_value_alias_name_id  = $this->jedoxapi->get_area( $raw_material_value_alias_elements, "Name" );
            $cells_raw_material_value_alias    = $this->jedoxapi->cell_export( $server_database['database'], $raw_material_value_alias_info['cube'], 10000, "", $raw_material_value_alias_name_id.",*" );
            
            // RAW_MATERIAL //
            // Get dimension of raw material
 			$raw_material_elements     = $this->jedoxapi->dimension_elements( $server_database['database'], $raw_material_rate_dimension_id[4] );
            // Get cube data of raw material value alias
			$raw_material_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Raw_Material");
			$raw_material_alias_info   = $this->jedoxapi->get_cube_data(    $database_cubes, "#_Raw_Material");
            // Export cells of raw material value alias
			$raw_material_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $raw_material_dimension_id[0] );
			$raw_material_alias_name_id  = $this->jedoxapi->get_area( $raw_material_alias_elements, "Name" );
			$cells_raw_material_alias    = $this->jedoxapi->cell_export( $server_database['database'], $raw_material_alias_info['cube'], 10000, "", $raw_material_alias_name_id.",*" );
			
			// Bom_Value // 
			$bom_value_elements   = $this->jedoxapi->dimension_elements( $server_database['database'], $bom_dimension_id[3] );
			
			// PRODUCTION_VALUE //
			$production_value_elements    = $this->jedoxapi->dimension_elements( $server_database['database'], $production_dimension_id[3] );
			
			// Capacity_Rate_Value
			
			$capacity_rate_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $capacity_rate_dimension_id[3]);
			
			// Income Value
			
			$income_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[3]);
			
			
			
			//FORM DATA//
			
			$form_year = $this->jedoxapi->dimension_elements_base($year_elements, "YA");
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            $form_month = $this->jedoxapi->dimension_elements_base($month_elements, "MA"); // All Months
            $form_month = $this->jedoxapi->set_alias($form_month, $cells_month_alias); // Set aliases
			
			if($step == '')
			{
				$step = 1;
			}
			
			if( $year == '' )
            {
                $now = now();
                $tnow = mdate("%Y", $now);
                $year = $this->jedoxapi->get_area( $year_elements, $tnow);
            }
            if( $month == '' )
            {
                $now = now();
                $tnow = mdate("%m", $now);
                $month = $this->jedoxapi->get_area( $month_elements, "M".$tnow );
            }
			
			$pass_year= $this->get_dimension_name_by_id($year_elements, $year);
			$pass_month = $this->get_dimension_name_by_id($month_elements, $month);
			
			$primary_value_TQ_area  = $this->jedoxapi->get_area( $primary_value_elements, "TQ" );
			$primary_value_UPC_area  = $this->jedoxapi->get_area( $primary_value_elements, "UPC" );
			
			$account_element_CE_Primary_area = $this->jedoxapi->get_area( $account_element_elements, "CE_Primary" );
			
			$receiver_RP_area = $this->jedoxapi->get_area($receiver_elements, "RP");
			$receiver_AR_area = $this->jedoxapi->get_area( $receiver_elements, "AR" );
			
			$raw_material_value_P002_area = $this->jedoxapi->get_area( $raw_material_value_elements, "P002" );
			$raw_material_elements_rw_area = $this->jedoxapi->get_area($raw_material_elements, "RW");
			
			$secondary_value_QC_area    = $this->jedoxapi->get_area( $secondary_value_elements, "QC" );
			$secondary_value_QTY04_area = $this->jedoxapi->get_area( $secondary_value_elements, "QTY04" );
			$secondary_value_CF02_area = $this->jedoxapi->get_area( $secondary_value_elements, "CF02" );
			
			$sender_as_area = $this->jedoxapi->get_area($sender_elements, "AS");
			$sender_rp_area = $this->jedoxapi->get_area($sender_elements, "RP");
			
			$bom_value_QTY04_area  = $this->jedoxapi->get_area( $bom_value_elements, "QTY04" );
			
			$product_AP_area  = $this->jedoxapi->get_area( $product_elements, "AP" );
			
			$production_value_QTY03_area  = $this->jedoxapi->get_area( $production_value_elements, "QTY03" );
			$production_value_QTY07_area  = $this->jedoxapi->get_area( $production_value_elements, "QTY07" );
			
			$capacity_rate_y001_area = $this->jedoxapi->get_area($capacity_rate_value_elements, "Y001");
			
			$income_qty05 = $this->jedoxapi->get_area($income_value_elements, "QTY05");
			$income_p001 = $this->jedoxapi->get_area($income_value_elements, "P001");
			
			$customer_cu = $this->jedoxapi->get_area($customer_elements, "CU");
			
			$product_fp_set = $this->jedoxapi->array_element_filter($product_elements, "FP");
			$product_fp_set = $this->jedoxapi->dimension_elements_base($product_fp_set);
			$product_fp_set_area = $this->jedoxapi->get_area($product_fp_set);
			
			$version_sim = $this->jedoxapi->get_area($version_elements, "V003"); //target
			$version = $this->jedoxapi->get_area($version_elements, "V001"); //plan
			
			if($action == "Copy Target")
			{
				
					
				// Cube Primary: Copy Quantities
				$path = $year.",".$month.",".$primary_value_TQ_area.",".$account_element_CE_Primary_area.",".$receiver_RP_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $primary_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Primary: Copy UPC
				$path = $year.",".$month.",".$primary_value_UPC_area.",".$account_element_CE_Primary_area.",".$receiver_RP_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $primary_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Raw Material: Copy P002
				$path   = $year.",".$month.",".$raw_material_value_P002_area.",".$raw_material_elements_rw_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $raw_material_rate_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Secondary: Copy QC
				$path = $year.",".$month.",".$secondary_value_QC_area.",".$sender_as_area.",".$receiver_AR_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $secondary_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Secondary: Copy QTY04
				$path = $year.",".$month.",".$secondary_value_QTY04_area.",".$sender_as_area.",".$receiver_AR_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $secondary_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				// Cube Secondary: Copy CF02
				$path = $year.",".$month.",".$secondary_value_CF02_area.",".$sender_as_area.",".$receiver_AR_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $secondary_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Bom: Copy QTY04
				$path = $year.",".$month.",".$bom_value_QTY04_area.",".$raw_material_elements_rw_area.",".$product_AP_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $bom_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Production: Copy QTY03
				$path = $year.",".$month.",".$production_value_QTY03_area.",".$product_AP_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $production_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );

				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Production: Copy QTY07
				$path = $year.",".$month.",".$production_value_QTY07_area.",".$product_AP_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $production_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Capacity_Rates
				$path = $year.",".$month.",".$capacity_rate_y001_area.",".$sender_rp_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $capacity_rate_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				// cube income
				$path = $year.",".$month.",".$income_qty05.",".$product_AP_area.",".$customer_cu;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $income_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				$path = $year.",".$month.",".$income_p001.",".$product_AP_area.",".$customer_cu;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $income_cube_info['cube'], $version.",".$path, $version_sim.",".$path, 1 );
				
				// data are copied over
				
				$step = 2;
			}

			if($action == "Continue")
			{
				//echo "3rd step now";
				
				$step = 3;
			}
			
			if($action == "Calculate Material")
			{
				
				$step = 4;
			}
			
			
			
			// Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "jedox_user_details" => $user_details,
                "pagename"     => $pagename,
                "oneliner"     => $oneliner,
                "step" => $step,
                "status" => $status,
                
                "form_year"    => $form_year,
                "year"         => $year,
                "form_month"   => $form_month,
                "month"        => $month,
                
				"pass_year" => $pass_year,
				"pass_month" => $pass_month,
				
				"version_sim" => $version_sim
                
                //trace vars here
            );
			
            // Pass data and show view
            $this->load->view("calculate_target_view", $alldata);
			
			
			
		
		}
	}
	
	public function grun()
	{
		$year    = $this->input->post("Year");
        $month   = $this->input->post("Month");
		// execute etl
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) );
		$locator    = "ProEo_Template.jobs.Job_Cub_Secondary_Consumption_Factor";
		
		// Connect to Soap Server
		$server = new SoapClient($server_url, array('exceptions' => true, 'location' => $server_url));
		// Login Attempt on Soap Object
		$login_attempt = $server->login(array('user' => $this->session->userdata('jedox_user'), 'password' => $this->session->userdata('jedox_pass')))->return;
		$session = $login_attempt->result;
		
		// Soap Header
		$header = new SoapHeader('http://ns.jedox.com/ETL-Server/', 'etlsession', $session);    
		$server->__setSoapHeaders($header);
		//echo "Version Sim Name ".$version_sim_name;
		
		//$pass_year= $this->get_dimension_name_by_id($year_elements, $year);
		//$pass_month = $this->get_dimension_name_by_id($month_elements, $month);
		
		$variables = array( 
			array('name' => 'Database',    'value' => $this->session->userdata('jedox_db') ), 
			array('name' => 'User',        'value' => $this->session->userdata('jedox_user') ), 
			array('name' => 'Year',     'value' => $year ),
			array('name' => 'Month', 'value' => $month)
		);
		//print_r ($variables);
		
		
		
		$response  = $server->addExecution( array('locator' => $locator, 'variables' => $variables ) );
		$return    = $response->return;
		$id = $return->id;
		$this->session->set_userdata('etl_id', $id);
		// Execute job
		$response  = $server->runExecution( array('id' => $id) );
		$return    = $response->return;
		
		//echo "etl is running: </ br>";
		echo $return->status;
		
		//$status = $return->status;
	}

	public function grun1()
	{
		$year    = $this->input->post("Year");
        $month   = $this->input->post("Month");
		// execute etl
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) );
		$locator    = "ProEo_Template.jobs.Job_Loop_Groovy_Target";
		
		// Connect to Soap Server
		$server = new SoapClient($server_url, array('exceptions' => true, 'location' => $server_url));
		// Login Attempt on Soap Object
		$login_attempt = $server->login(array('user' => $this->session->userdata('jedox_user'), 'password' => $this->session->userdata('jedox_pass')))->return;
		$session = $login_attempt->result;
		
		// Soap Header
		$header = new SoapHeader('http://ns.jedox.com/ETL-Server/', 'etlsession', $session);    
		$server->__setSoapHeaders($header);
		//echo "Version Sim Name ".$version_sim_name;
		
		//$pass_year= $this->get_dimension_name_by_id($year_elements, $year);
		//$pass_month = $this->get_dimension_name_by_id($month_elements, $month);
		
		$variables = array( 
			array('name' => 'Database',    'value' => $this->session->userdata('jedox_db') ), 
			array('name' => 'User',        'value' => $this->session->userdata('jedox_user') ), 
			array('name' => 'Year',     'value' => $year ),
			array('name' => 'Month', 'value' => $month)
		);
		//print_r ($variables);
		
		
		
		$response  = $server->addExecution( array('locator' => $locator, 'variables' => $variables ) );
		$return    = $response->return;
		$id = $return->id;
		$this->session->set_userdata('etl_id', $id);
		// Execute job
		$response  = $server->runExecution( array('id' => $id) );
		$return    = $response->return;
		
		//echo "etl is running: </ br>";
		echo $return->status;
		
		//$status = $return->status;
	}
	
	public function grun2()
	{
		$year    = $this->input->post("Year");
        $month   = $this->input->post("Month");
		// execute etl
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) );
		$locator    = "ProEo_Template.jobs.Job_Cub_Primary_Target";
		
		// Connect to Soap Server
		$server = new SoapClient($server_url, array('exceptions' => true, 'location' => $server_url));
		// Login Attempt on Soap Object
		$login_attempt = $server->login(array('user' => $this->session->userdata('jedox_user'), 'password' => $this->session->userdata('jedox_pass')))->return;
		$session = $login_attempt->result;
		
		// Soap Header
		$header = new SoapHeader('http://ns.jedox.com/ETL-Server/', 'etlsession', $session);    
		$server->__setSoapHeaders($header);
		//echo "Version Sim Name ".$version_sim_name;
		
		//$pass_year= $this->get_dimension_name_by_id($year_elements, $year);
		//$pass_month = $this->get_dimension_name_by_id($month_elements, $month);
		
		$variables = array( 
			array('name' => 'Database',    'value' => $this->session->userdata('jedox_db') ), 
			array('name' => 'User',        'value' => $this->session->userdata('jedox_user') ), 
			array('name' => 'Year',     'value' => $year ),
			array('name' => 'Month', 'value' => $month)
		);
		//print_r ($variables);
		
		
		
		$response  = $server->addExecution( array('locator' => $locator, 'variables' => $variables ) );
		$return    = $response->return;
		$id = $return->id;
		$this->session->set_userdata('etl_id', $id);
		// Execute job
		$response  = $server->runExecution( array('id' => $id) );
		$return    = $response->return;
		
		//echo "etl is running: </ br>";
		echo $return->status;
		
		//$status = $return->status;
	}
	
	public function grun3()
	{
		$year    = $this->input->post("Year");
        $month   = $this->input->post("Month");
		// execute etl
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) );
		$locator    = "ProEo_Template.jobs.Job_Rul_Profit_Simulation";
		
		// Connect to Soap Server
		$server = new SoapClient($server_url, array('exceptions' => true, 'location' => $server_url));
		// Login Attempt on Soap Object
		$login_attempt = $server->login(array('user' => $this->session->userdata('jedox_user'), 'password' => $this->session->userdata('jedox_pass')))->return;
		$session = $login_attempt->result;
		
		// Soap Header
		$header = new SoapHeader('http://ns.jedox.com/ETL-Server/', 'etlsession', $session);    
		$server->__setSoapHeaders($header);
		//echo "Version Sim Name ".$version_sim_name;
		
		//$pass_year= $this->get_dimension_name_by_id($year_elements, $year);
		//$pass_month = $this->get_dimension_name_by_id($month_elements, $month);
		
		$variables = array( 
			array('name' => 'Database',    'value' => $this->session->userdata('jedox_db') ), 
			array('name' => 'User',        'value' => $this->session->userdata('jedox_user') ), 
			array('name' => 'Year',     'value' => $year ),
			array('name' => 'Month', 'value' => $month)
		);
		//print_r ($variables);
		
		
		
		$response  = $server->addExecution( array('locator' => $locator, 'variables' => $variables ) );
		$return    = $response->return;
		$id = $return->id;
		$this->session->set_userdata('etl_id', $id);
		// Execute job
		$response  = $server->runExecution( array('id' => $id) );
		$return    = $response->return;
		
		//echo "etl is running: </ br>";
		echo $return->status;
		
		//$status = $return->status;
	}
	
	
	public function gstatus()
	{
		$id = $this->session->userdata('etl_id');
		//echo $this->etlapi->getStatus($id);
		
		//$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/"  || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) );
		// Connect to Soap Server
		$server = new SoapClient($server_url, array('exceptions' => true, 'location' => $server_url));
		// Login Attempt on Soap Object
		$login_attempt = $server->login(array('user' => $this->session->userdata('jedox_user'), 'password' => $this->session->userdata('jedox_pass')))->return;
		$session = $login_attempt->result;
		
		// Soap Header
		$header = new SoapHeader('http://ns.jedox.com/ETL-Server/', 'etlsession', $session);    
		$server->__setSoapHeaders($header);
		
		$response = $server->getExecutionStatus(array('id' => $id, 'waitForTermination' => false));
		$return = $response->return;
  
		$edata = $return->status;
		echo $edata;
		
	}
	
	public function get_dimension_name_by_id($array, $id)
	{
		$result_array = '';
		foreach($array as $row)
		{
			if($row['element'] == $id)
			{
				$result_array = $row['name_element'];
			}
		}
		return $result_array;
	}
	

}