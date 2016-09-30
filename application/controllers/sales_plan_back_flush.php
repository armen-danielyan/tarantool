<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_Plan_Back_Flush extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index ()
    {
        $pagename = "proEO Simulation Sales Plan Back Flush";
        $oneliner = "One-liner here for sales plan back flush";
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
            $cube_names = "Primary,Secondary,Raw_Material_Rate,Bom,Production,Margin,Capacity_Rate,Income,Margin_Geo_Report,#_Version,#_Year,#_Month,#_Margin_Value,#_Product,#_Customer,#_Primary_Value,#_Account_Element,#_Receiver,#_Secondary_Value,#_Sender,#_Raw_Material_Rate_Value,#_Raw_Material,#_Bom_Value,#_Production_Value,#_Capacity_Rate_Value,#_Income_Value";
            
            // Initialize post data //
            $yearf = $this->input->post("yearf");
            $monthf = $this->input->post("monthf");
			
			$yeart = $this->input->post("yeart");
            $montht = $this->input->post("montht");
            
            $version = $this->input->post("version");
			
			
			$action = $this->input->post("action");
			$step = $this->input->post("step");
			
			$product = $this->input->post("product");
			$customer = $this->input->post("customer");
            
			$month_range = '';
			$year_range = '';
			
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
			
			$margin_geo_report_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Margin_Geo_Report");
			$margin_geo_report_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Margin_Geo_Report");
			
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
			
			
			
            // FORM DATA //
            $form_year = $this->jedoxapi->dimension_elements_base($year_elements, "YA");
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
			
            
            $form_months = $this->jedoxapi->dimension_elements_base($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            $form_version = $this->jedoxapi->array_element_filter($version_elements, "V_Sales_Plan");
			$form_version = $this->jedoxapi->dimension_elements_base($form_version);
			$form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias);
			
            $form_product = $this->jedoxapi->array_element_filter($product_elements, "FP");
			$form_product = $this->jedoxapi->set_alias($form_product, $cells_product_alias);
			
			$form_customer = $this->jedoxapi->array_element_filter($customer_elements, "CU");
			$form_customer = $this->jedoxapi->set_alias($form_customer, $cells_customer_alias);
            /////////////
            // PRESETS //
            /////////////
            
            $month_range = '';
			$year_range = '';
			$version_name = '';
            
            if($step == '')
			{
				$step = 1;
			}
			$now = now();
            $tnow = mdate("%Y", $now);
			$tnow2 = "M".mdate("%m", $now);
			if($yearf == '')
            {
                $yearf = $this->jedoxapi->get_area($year_elements, $tnow);
            }
			if($yeart == '')
            {
                $yeart = $this->jedoxapi->get_area($year_elements, $tnow);
            }
			if($monthf == '')
            {
                $monthf = $this->jedoxapi->get_area($month_elements, $tnow2);
            }
			if($montht == '')
            {
                $montht = $this->jedoxapi->get_area($month_elements, $tnow2);
            }
			
			
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
            $sender_bp_area = $this->jedoxapi->get_area($sender_elements, "BP");
			
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
			
			//$version_sim = $this->jedoxapi->get_area($version_elements, "V003"); //target
			$version_source = $this->jedoxapi->get_area($version_elements, "V001"); //plan
			
			$month_all = $this->jedoxapi->get_area($month_elements, "MA");
			$year_all = $this->jedoxapi->get_area($year_elements, "YA");
			
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
			$margin_value_c005 = $this->jedoxapi->get_area($margin_value_elements, "C005"); // C005
			$margin_value_c006 = $this->jedoxapi->get_area($margin_value_elements, "C006"); // C006
			$margin_value_c007 = $this->jedoxapi->get_area($margin_value_elements, "C007"); // C007
			
			$table1_data = '';
			$table2_data = '';
			$table3_data = '';
			$table4_data = '';
			$table5_data = '';
			$table6_data = '';
			$table7_data = '';
			$table8_data = '';
			$table9_data = '';
			$table10_data = '';
			$table11_data = '';
			$table12_data = '';
			$table13_data = '';
			$table14_data = '';
			
			if($product == ''){
				$product = $this->jedoxapi->get_area($product_elements, "FP");
			}
			if($customer == '')
			{
				$customer = $this->jedoxapi->get_area($customer_elements, "CU");
			}
            
			// contents //
			
            if($action == "Continue")
            {
            	//echo "do copy now with range!";
				//get name of to and from by ID
				$monthf_name = $this->get_dimension_name_by_id($month_elements, $monthf);
				$montht_name = $this->get_dimension_name_by_id($month_elements, $montht);
				
				$yearf_name = $this->get_dimension_name_by_id($year_elements, $yearf);
				$yeart_name = $this->get_dimension_name_by_id($year_elements, $yeart);
				
				$version_name = $this->get_dimension_name_by_id($version_elements, $version);
				
				//date display
				
				$monthf_name_true = $this->get_dimension_name_by_id($form_months, $monthf);
				$montht_name_true = $this->get_dimension_name_by_id($form_months, $montht);
				
				$display_range = $monthf_name_true.", ".$yearf_name." - ".$montht_name_true.", ".$yeart_name;
				$this->session->set_userdata('display_range', $display_range);
				
				// range array
				$range_array = array();
				$temp = 0;
				
				// note: the file must be created 1st.
				$myfile = fopen("assets/etlrange.csv", "w") or die("Unable to open file! create the file 1st!");
				$txt = "Month.Year\n";
				fwrite($myfile, $txt);
				
				foreach($form_year as $row)
				{
					foreach($form_months as $row1)
					{
						$temp_year = $this->get_dimension_name_by_id($year_elements, $row['element']);
						$temp_month = $this->get_dimension_name_by_id($month_elements, $row1['element']);
						
						if($yearf == $row['element'] && $monthf == $row1['element'])
						{
							$range_array[] = array("year" => $row['element'], "month" => $row1['element']);
							
							$txt = $temp_month.".".$temp_year."\n";
							fwrite($myfile, $txt);
						} 
						else 
						{
							if( count($range_array) != 0 )
							{
								$range_array[] = array("year" => $row['element'], "month" => $row1['element']);
								$txt = $temp_month.".".$temp_year."\n";
								fwrite($myfile, $txt);
							}
						}
						if($yeart == $row['element'] && $montht == $row1['element'])
						{
							$temp = 1;
							break;
						}
					}
					
					if($temp == 1){
						break;
					}
					
				}
				
				fclose($myfile);
				//echo count($range_array);
				//$this->jedoxapi->traceme($range_array);
				// range array end
				
				$this->session->set_userdata('ver_new', $version); // id of selected version
				$this->session->set_userdata('monthf', $monthf);
				$this->session->set_userdata('montht', $montht);
				$this->session->set_userdata('yearf', $yearf);
				$this->session->set_userdata('yeart', $yeart);
				
				$this->session->set_userdata("range_array", $range_array);
				
				$year_range = $yearf_name.",".$yeart_name;
				
				//compute range for etl months
				if($yearf_name != $yeart_name)
				{
					$month_range = "M01,M12"; // preset to take all months
				} else {
					$month_range = $monthf_name.",".$montht_name;
				}
				
				$this->session->set_userdata('year_range', $year_range);
				$this->session->set_userdata('month_range', $month_range);
				
				$step = 2;
            }
            
            if($action == "Copy Values")
            {
            	// adjust for month/year range
            	//echo "data should be copied now";
            	
				$version_sim = $this->session->userdata('ver_new');
				//reload vars for etl:
				
				$version_name = $this->get_dimension_name_by_id($version_elements, $version_sim);
				$year_range = $this->session->userdata('year_range');
				$month_range = $this->session->userdata('month_range');
				
				// Cube Primary: Copy Quantities
				$path = $year_all.",".$month_all.",".$primary_value_TQ_area.",".$account_element_CE_Primary_area.",".$receiver_RP_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $primary_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Primary: Copy UPC
				$path = $year_all.",".$month_all.",".$primary_value_UPC_area.",".$account_element_CE_Primary_area.",".$receiver_RP_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $primary_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Raw Material: Copy P002
				$path   = $year_all.",".$month_all.",".$raw_material_value_P002_area.",".$raw_material_elements_rw_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $raw_material_rate_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Secondary: Copy QC
				$path = $year_all.",".$month_all.",".$secondary_value_QC_area.",".$sender_as_area.",".$receiver_AR_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $secondary_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Secondary: Copy QTY04
				$path = $year_all.",".$month_all.",".$secondary_value_QTY04_area.",".$sender_as_area.",".$receiver_AR_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $secondary_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
				// Cube Secondary: Copy CF02
				$path = $year_all.",".$month_all.",".$secondary_value_CF02_area.",".$sender_as_area.",".$receiver_AR_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $secondary_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Bom: Copy QTY04
				$path = $year_all.",".$month_all.",".$bom_value_QTY04_area.",".$raw_material_elements_rw_area.",".$product_AP_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $bom_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Production: Copy QTY03
				$path = $year_all.",".$month_all.",".$production_value_QTY03_area.",".$product_AP_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $production_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );

				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Production: Copy QTY07
				$path = $year_all.",".$month_all.",".$production_value_QTY07_area.",".$product_AP_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $production_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
				//$this->jedoxapi->traceme($path, "path");
				
				// Cube Capacity_Rates
				$path = $year_all.",".$month_all.",".$capacity_rate_y001_area.",".$sender_rp_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $capacity_rate_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
                $path = $year_all.",".$month_all.",".$capacity_rate_y001_area.",".$sender_bp_area;
                $result = $this->jedoxapi->cell_copy( $server_database['database'], $capacity_rate_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
                
				// cube income
				$path = $year_all.",".$month_all.",".$income_qty05.",".$product_AP_area.",".$customer_cu;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $income_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
				$path = $year_all.",".$month_all.",".$income_p001.",".$product_AP_area.",".$customer_cu;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $income_cube_info['cube'], $version_source.",".$path, $version_sim.",".$path, 1 );
				
				// data are copied over
				
				$step = 3;
            }
			
			if($action == "Next" && $step == 3)
            {
            	//echo "code is now in step 4";
				$version_sim = $this->session->userdata('ver_new');
				//reload vars for etl:
				
				$version_name = $this->get_dimension_name_by_id($version_elements, $version_sim);
				$year_range = $this->session->userdata('year_range');
				$month_range = $this->session->userdata('month_range');
				
				$step = 4;
			} else
			
			if($action == "Next" && $step == 4)
			{
				//echo "code is now in step 4";
				$version_sim = $this->session->userdata('ver_new');
				//reload vars for etl:
				
				$version_name = $this->get_dimension_name_by_id($version_elements, $version_sim);
				$year_range = $this->session->userdata('year_range');
				$month_range = $this->session->userdata('month_range');
				
				$step = 7;
			} else
			
			if($action == "Next" && $step == 5)
			{
				//echo "this is now step 6";
				$version_sim = $this->session->userdata('ver_new');
				//reload vars for etl:
				
				$version_name = $this->get_dimension_name_by_id($version_elements, $version_sim);
				$year_range = $this->session->userdata('year_range');
				$month_range = $this->session->userdata('month_range');
				
				$step = 6;
			} else 
				
			if($action == "Next" && $step == 6)
			{
				//echo "this is now step 7";
				$version_sim = $this->session->userdata('ver_new');
				//reload vars for etl:
				
				$version_name = $this->get_dimension_name_by_id($version_elements, $version_sim);
				$year_range = $this->session->userdata('year_range');
				$month_range = $this->session->userdata('month_range');
				
				$step = 7;
			} else
				
			if($action == "Next" && $step == 7)
			{
				//echo "this is now step 8";
				$version_sim = $this->session->userdata('ver_new');
				//reload vars for etl: 
				
				$version_name = $this->get_dimension_name_by_id($version_elements, $version_sim);
				$year_range = $this->session->userdata('year_range');
				$month_range = $this->session->userdata('month_range');
				
				$step = 8;
			} else 
				
			//if($action == "Next" && $step == 8)
			if($step == 8 || $step == 9)
			{
				//echo "this is now step 9";
				// Areas
				
				$version_sim = $this->session->userdata('ver_new');
				$year_all_base = $this->jedoxapi->dimension_elements_base($year_elements);
				$year_all_base_area = $this->jedoxapi->get_area($year_all_base);
				
				$month_all_base = $this->jedoxapi->dimension_elements_base($month_elements);
				$month_all_base_area = $this->jedoxapi->get_area($month_all_base);
				
				//product and customer are via forms.
				
				$table1_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_qty05.",".$product.",".$customer;
				$table2_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_p001.",".$product.",".$customer;
				$table3_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_sls04.",".$product.",".$customer;
				$table4_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_sls03.",".$product.",".$customer;
				$table5_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_sls.",".$product.",".$customer;
				$table6_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_craw.",".$product.",".$customer;
				$table7_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_mg01.",".$product.",".$customer;
				$table8_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_sc03.",".$product.",".$customer;
				$table9_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_mg02.",".$product.",".$customer;
				$table10_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_scf.",".$product.",".$customer;
				$table11_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_mg03.",".$product.",".$customer; 
				
				$table12_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_c005.",".$product.",".$customer; 
				$table13_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_c006.",".$product.",".$customer; 
				$table14_area = $version_source.":".$version_sim.",".$year_all_base_area.",".$month_all_base_area.",".$margin_value_c007.",".$product.",".$customer; 
				
				
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
				
				$table12_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table12_area, "", 1, "", "0");
				$table13_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table13_area, "", 1, "", "0");
				$table14_data = $this->jedoxapi->cell_export($server_database['database'],$margin_geo_report_cube_info['cube'],10000,"",$table14_area, "", 1, "", "0");
				
				$step = 9; // step is kept at 8 since this is a reapeating step that has its own form.
			}
            //echo $action; 
            
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "form_year" => $form_year,
                "year_range" => $year_range,
                "form_months" => $form_months,
                "month_range" => $month_range,
                "version_name" => $version_name,
                "form_version" => $form_version,
                "version" => $version,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "step" => $step,
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
                "table12_data" => $table12_data,
                "table13_data" => $table13_data,
                "table14_data" => $table14_data,
                "margin_value_qty05" => $margin_value_qty05,
	            "margin_value_p001" => $margin_value_p001,
				"margin_value_sls04" => $margin_value_sls04,
				"margin_value_sls03" => $margin_value_sls03,
				"margin_value_sls" => $margin_value_sls,
				"margin_value_craw" => $margin_value_craw,
				"margin_value_mg01" => $margin_value_mg01,
				"margin_value_sc03" => $margin_value_sc03,
				"margin_value_mg02" => $margin_value_mg02,
				"margin_value_scf" => $margin_value_scf,
				"margin_value_mg03" => $margin_value_mg03,
				"margin_value_c005" => $margin_value_c005,
				"margin_value_c006" => $margin_value_c006,
				"margin_value_c007" => $margin_value_c007,
				"version_source" => $version_source,
				"product" => $product,
				"customer" => $customer,
				"form_product" => $form_product,
				"form_customer" => $form_customer,
				"yearf" => $yearf,
				"yeart" => $yeart,
				"monthf" => $monthf,
				"montht" => $montht
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("sales_plan_back_flush_view", $alldata);
        }
    }
    
    public function grun()
	{
		$year    = $this->input->post("Year");
        $month   = $this->input->post("Month");
		$version = $this->input->post("Version");
		// execute etl
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) );
		$locator    = "ProEo_Template.jobs.Job_Cub_Secondary_Consumption_Factor_MP";
		
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
			array('name' => 'Year_Range',     'value' => $year ),
			array('name' => 'Month_Range', 'value' => $month ),
			array('name' => 'Sales_Version', 'value' => $version)
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
		$version = $this->input->post("Version");
		// execute etl
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) );
		$locator    = "ProEo_Template.jobs.Job_Cub_Income_New_Sales";
		
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
			array('name' => 'Year_Range',     'value' => $year ),
			array('name' => 'Month_Range', 'value' => $month ),
			array('name' => 'Version', 'value' => $version)
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
		$version = $this->input->post("Version");
		// execute etl
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) );
		//$locator    = "ProEo_Template.jobs.Job_Rul_Production_New_Sales";
		$locator    = "ProEo_Template.jobs.Job_Run_Sales_Back_Flush";
		
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
			array('name' => 'Year_Range',     'value' => $year ),
			array('name' => 'Month_Range', 'value' => $month ),
			array('name' => 'Sales_Version', 'value' => $version)
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
		$version = $this->input->post("Version");
		// execute etl
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) );
		//$locator    = "ProEo_Template.jobs.Job_Loop_Groovy_VSales";
		//$locator    = "ProEo_Template.jobs.Job_Loop_Job_Loop_Groovy_VSales";
		$locator    = "ProEo_Template.jobs.Job_Cub_Secondary_VSales_Combined";
		
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
			array('name' => 'Year_Range',     'value' => $year ),
			array('name' => 'Month_Range', 'value' => $month ),
			array('name' => 'Sales_Version', 'value' => $version)
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

	public function grun4()
	{
		$year    = $this->input->post("Year");
        $month   = $this->input->post("Month");
		$version = $this->input->post("Version");
		// execute etl
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) );
		$locator    = "ProEo_Template.jobs.Job_Cub_Primary_New_Sales";
		
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
			array('name' => 'Year_Range',     'value' => $year ),
			array('name' => 'Month_Range', 'value' => $month ),
			array('name' => 'Sales_Version', 'value' => $version)
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
	
	public function grun5()
	{
		$year    = $this->input->post("Year");
        $month   = $this->input->post("Month");
		$version = $this->input->post("Version");
		// execute etl
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		
		//$server     = new SoapClient($server_url, array('exceptions' => true) ); 
		$locator    = "ProEo_Template.jobs.Job_Loop_Job_Rul_Profit_Simulation";
		
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
			array('name' => 'Year_Range',     'value' => $year ),
			array('name' => 'Month_Range', 'value' => $month ),
			array('name' => 'Sales_Version', 'value' => $version)
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