<?php
	error_reporting( E_ALL );
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$my_cwd = getcwd();
chdir( "assets/calculate_rates" );
include('process.php');
$p_version_id = '';
$p_year_id    = '';
$p_month_id   = '';

class Profit_Optimization extends CI_Controller {

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
        $pagename = "proEO Profit Optimization";
        $oneliner = "One-liner here for Profit Optimization";
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
            $cube_names = "Primary,Secondary,Raw_Material_Rate,Bom,Production,Margin,Capacity_Rate,Income,#_Version,#_Year,#_Month,#_Margin_Value,#_Product,#_Customer,#_Primary_Value,#_Account_Element,#_Receiver,#_Secondary_Value,#_Sender,#_Raw_Material_Rate_Value,#_Raw_Material,#_Bom_Value,#_Production_Value,#_Capacity_Rate_Value,#_Income_Value";
            
            // Initialize post data //
            //$yearf = $this->input->post("yearf");
            //$monthf = $this->input->post("monthf");
			//$yeart = $this->input->post("yeart");
            //$montht = $this->input->post("montht");
            $new_version = $this->input->post("new_version");
			
			$year    = $this->input->post("year");
            $month   = $this->input->post("month");
            $version = $this->input->post("version");
			$simulate = $this->input->post("simulate");
            $step    = $this->input->post("step");
			$error = '';
			
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
			
			///////////////////////////
			// creating new versions //
			///////////////////////////
			
			if($new_version != '' && $new_version != 'New Description')
			{
				// Find next version number
				$version_sim_new = url_title($new_version, "_"); // fixes the space issue of space on string and preserves casing. 
				
				$version_simulation = $this->jedoxapi->dimension_elements_id( $version_elements, "V_Production_Plan" );
				
				$version_elements_tmp = $this->jedoxapi->dimension_elements_base( $version_elements, "VA" );
				
				$version_list_tmp = null;
				foreach( $version_elements_tmp as $version_tmp )
				{
					if( $version_list_tmp > $version_tmp['name_element'] ) { continue; }
					$version_list_tmp = $version_tmp['name_element'];
				}
				//$this->jedoxapi->traceme($version_list_tmp, "version list tmp");
				// Create new version name
				$version_list_tmp = substr( $version_list_tmp, 1, 3 ) + 1;
				$version_list_tmp = 'V'.str_pad( $version_list_tmp, 3, '0', STR_PAD_LEFT );
				
				// Create version in Jedox
				$version_sim_key = $this->jedoxapi->element_create( $server_database['database'], $margin_dimension_id[0], $version_list_tmp, $version_simulation[0]['element'] );
				
				// Set name of version in Jedox
				$path   = $version_alias_name_id.",".$version_sim_key;
				$result = $this->jedoxapi->cell_replace( $server_database['database'], $version_alias_info['cube'], $path, $version_sim_new );
				
				// VERSION // reloading version data after the update.
	            // Get dimension of version
	            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[0]);
	            // Get cube data of version alias
	            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
	            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
	            // Export cells of version alias
	            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
				$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
	            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			}
			// end creating new versions.
			
			
			
            // FORM DATA //
            $form_year = $this->jedoxapi->dimension_elements_base($year_elements, "YA");
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            $form_months = $this->jedoxapi->dimension_elements_base($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
			$form_version = $this->jedoxapi->array_element_filter($version_elements, "V_Production_Plan");
			$form_version = $form_version_noalias = $this->jedoxapi->dimension_elements_base($form_version);
			$form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias);
			
			$form_version_1 = $this->jedoxapi->array_element_filter($version_elements, "V_Operation");
			$form_version_1 = $this->jedoxapi->dimension_elements_base($form_version_1);
			$form_version_1 = $this->jedoxapi->set_alias($form_version_1, $cells_version_alias);
            
			//$this->jedoxapi->traceme($form_version, "version");
			
			
			
			$cb = 0;
			$temp_version = array();
			$temp_version_noalias = '';
			$vercont = array();
			foreach($form_version as $row)
			{
				$cb += 1;
				${'ver'.$cb} = $this->input->post('ver'.$cb);
				//print_r (${'ver'.$cb});
				if(${'ver'.$cb} == 1){
					$temp_version[] = $row;
					$vercont['ver'.$cb] = 1;
				} else 
				{
					$vercont['ver'.$cb] = 0;
				}
			}
			
			$cb = 0;
			foreach($form_version_noalias as $row)
			{
				$cb += 1;
				${'ver'.$cb} = $this->input->post('ver'.$cb);
				//print_r (${'ver'.$cb});
				if(${'ver'.$cb} == 1){
					$temp_version_noalias .= $row['name_element'].",";
				}
			}
			$temp_version_noalias = rtrim($temp_version_noalias, ",");
			//$this->jedoxapi->traceme($temp_version_noalias, "the raw");
            /////////////
            // PRESETS //
            /////////////
            
            $now = now();
            if( $year == '' )
            {
                $tnow = mdate("%Y", $now);
                $year = $this->jedoxapi->get_area( $year_elements, $tnow);
            }
            if( $month == '' )
            {
                $tnow = mdate("%m", $now);
                $month = $this->jedoxapi->get_area( $month_elements, "M".$tnow );
            }
            if( $version == '' )
            {
                $version = $this->jedoxapi->get_area( $version_elements, "V001");
            }
			
			if($step == '')
			{
				$step = 1;
			}
            
			// Initializing variables
			
			$temp_version_area = $this->jedoxapi->get_area($temp_version);
			$temp_version_alias = $this->jedoxapi->set_alias($temp_version, $cells_version_alias);
			
			$primary_value_TQ_area  = $this->jedoxapi->get_area( $primary_value_elements, "TQ" );
			$primary_value_UPC_area  = $this->jedoxapi->get_area( $primary_value_elements, "UPC" );
			
			$account_element_CE_Primary_area = $this->jedoxapi->get_area( $account_element_elements, "CE_Primary" );
			
			$receiver_RP_area = $this->jedoxapi->get_area($receiver_elements, "RP");
			$receiver_AR_area = $this->jedoxapi->get_area( $receiver_elements, "AR" );
			
			$raw_material_value_P002_area = $this->jedoxapi->get_area( $raw_material_value_elements, "P002" );
			$raw_material_elements_rw_area = $this->jedoxapi->get_area($raw_material_elements, "RW");
			
			$secondary_value_QC_area    = $this->jedoxapi->get_area( $secondary_value_elements, "QC" );
			$secondary_value_QTY04_area = $this->jedoxapi->get_area( $secondary_value_elements, "QTY04" );
			
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
			///////////////
            // COPY DATA //
			///////////////
			
			// this will be triggered by the "simulate button"
			
			if($simulate == "Simulate" && count($temp_version) > 0 && $step == 1)
			{
				// start copying data selected version filter to selected simulation versions
				//echo count($temp_version);
				//$this->jedoxapi->traceme($temp_version_noalias, "the raw");
				//$this->jedoxapi->traceme($temp_version, "the temp");
				foreach($temp_version as $row)
				{
					$version_sim = $row['element']; // the id of the element
					
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
					
				}
				$step = 2; // step now 2
				// need to pass vars to next steps...
				
				$this->session->set_userdata('temp_version_noalias', $temp_version_noalias);
				$this->session->set_userdata('temp_version', $temp_version);
			}
			
			// end copy data
			
			/////////////////////
			// Calculate rates //
			/////////////////////
			
			else if($step == 2){
				$temp_version = $this->session->userdata('temp_version');
				
				//echo "im here";
				//$this->jedoxapi->traceme($temp_version, "temp version");
				
				foreach($temp_version as $row)
				{
					//echo "i got in!";
					$version_sim = $row['element']; // the id of the element
					//check if folder exist.
					//echo getcwd();
					$folder_base_path = "assets/calculate_rates/data/".$database_name;
						
					$source_version_name = $this->jedoxapi->get_name($version_elements, $version);
					$target_version_name = $this->jedoxapi->get_name($version_elements, $version_sim);
						
					$year_name = $this->jedoxapi->get_name($year_elements, $year);
					$month_name = $this->jedoxapi->get_name($month_elements, $month);
						
					$folder_source_path = "assets/calculate_rates/data/".$database_name."/Dataset_".$source_version_name."_".$year_name."_".$month_name;
					$folder_target_path = "assets/calculate_rates/data/".$database_name."/Dataset_".$target_version_name."_".$year_name."_".$month_name;
					
					//check if base path exist
					if($this->check_file($folder_base_path) == 1)
					{
						//check if source path exist
						//echo "base path ok";
						if($this->check_file($folder_source_path) == 1)
						{
							//echo "folder source path ok";
							//check if target path exist. if yes, delete it
							if($this->check_file($folder_target_path) == 1)
							{
								$this->deleteDir($folder_target_path);
							}
							//$this->jedoxapi->traceme($folder_source_path, "folder source path");
							//$this->jedoxapi->traceme($folder_target_path, "folder target path");
							
							//everything check. now we copy :D
							$this->recourse_copy($folder_source_path, $folder_target_path); // change this. copy folder and only specific files. r_calc.r r_consumption only and folder.
							chdir( $folder_target_path );
							
							$GLOBALS['p_version_id'] = $version_sim;
							$GLOBALS['p_year_id'] = $year;
							$GLOBALS['p_month_id'] = $month;
							global $dimension;
							generate_mapping();
							$primary_list = collect_primary();
							$output_list  = collect_output(); // Removes elements without Output
							$content   = "";
							$row_names = "";
							$i = 0;
							
							// calc_rates start ----------------------------------------------------------------
							foreach ( $dimension['SENDER']['Value'] as $key_s => $value )
							{	
								$key_r = $dimension['SENDER']['Receiver'][$key_s];
								
								if( isset( $primary_list[$key_r]["PC01"] ) )
								{
									$value_fix = $primary_list[$key_r]["PC01"];
								} else {
									$value_fix = 0;
								}
							
								if( isset( $primary_list[$key_r]["PC02"] ) )
								{
									$value_prop = $primary_list[$key_r]["PC02"];
								} else {
									$value_prop = 0;
								}		
									
								$name = $value;
								if( $content != "" ) { $content .= ","; $row_names .= ","; }
								$content   .= number_format( $value_fix, 2, ".", "" ).",0,".number_format( $value_prop, 2, ".", "" );
								$row_names .= "\"".$name." - PC01\",\"".$name." - PC99\",\"".$name." - PC02\"";
								
								$i += 3;
							}
	
								$content = "V_new <- matrix(c(".$content."),".$i.",1,TRUE)".PHP_EOL;
								$row_names = "rownames(V_new) <- c(".$row_names.")".PHP_EOL;
								$col_names = "colnames(V_new) <- c(\"Primary_Cost\")".PHP_EOL;
									
								$file_primary = @fopen( 'r_primary.r',     "w" );
								fputs( $file_primary, $content );
								fputs( $file_primary, $row_names );
								fputs( $file_primary, $col_names );
								fclose( $file_primary );
								
								@unlink('r_results.r');
								$output = shell_exec( 'Rscript r_calc.r 2>&1' ); // r_results.r
								
								$file_result = @fopen( 'r_results.r', "r" );
								if( $file_result )
								{
									//$html .= "<p>File r_results.r was created</p>\r\n";
								} else {
									$error .= "Error: Unable to access results from r_results.r<br/>";
									$output = str_replace( PHP_EOL, "<br/>", $output );
									$output = str_replace( "\r", "", $output );
									$output = str_replace( "\n", "<br/>", $output );
									$error .= $output."<br/>";
									
									//echo "error";
									//echo "<script type=\"text/javascript\">document.getElementById('check_calc').src='images/calc_red.png';</script>\r\n";
									//$error++;
								}
								
								if( $file_result )
								{
									global $p_version_id, $p_year_id, $p_month_id, $cube, $element;
									
									$output = explode( "\n", $output );
									array_pop( $output );
									unset( $output[0] );
								
									// Reset Rates
									$area = $p_version_id.",".$p_year_id.",".$p_month_id.",".$element["RT"]["Id"].",".$element["AS"]["Id"];
									$result = jedox_call( "cell/replace", "cube=".$cube["CAPACITY_RATE"]["Id"]."&path=".$area."&value=0" );
									
									// Upload New Rates
									$i = 0;
									$result_list = array();
									foreach( $output as $output_line )
									{
										
										$i++;
										list( $sender, $value ) = preg_split("/[\s,]+/", $output_line );
										$key = array_search( $sender, $dimension['SENDER']['Value'] );
										
										switch( $i )
										{
											case 1:
											$area = $element["RT01"]["Id"].",".$key;
											$result_list[$sender]["RT03"] = $value;
											
											break;
											
											case 2:
											$area = $element["RT02"]["Id"].",".$key;
											$result_list[$sender]["RT02"] = $value;
											break;
											
											case 3:
											$area = $element["RT03"]["Id"].",".$key;
											$result_list[$sender]["RT01"] = $value; 
											$i = 0;
											break;
										}
										
										$area = $p_version_id.",".$p_year_id.",".$p_month_id.",".$area;
										$result = jedox_call( "cell/replace", "cube=".$cube["CAPACITY_RATE"]["Id"]."&path=".$area."&value=".number_format( $value, 5, ".", "" ) );
										//$this->jedoxapi->traceme($area, "area");
									}
									
									
								}
								
								// calc_rates end ------------------------------------------------------------------
								
								// calc_rates_raw ------------------------------------------------------------------
								
								
								$GLOBALS['p_version_id'] = $version;
								$bom_list        = collect_bom();
								ksort( $bom_list );
								$production_list = collect_production();
								ksort( $production_list );
								$GLOBALS['p_version_id'] = $version_sim;
								$raw_pr_list     = collect_raw_pr();
								ksort( $raw_pr_list );
								$raw_ae_list     = collect_raw_ae();
								ksort( $raw_ae_list );
								
								//$this->jedoxapi->traceme($bom_list, "bom list");
								//$this->jedoxapi->traceme($production_list, "production list");
								//$this->jedoxapi->traceme($raw_pr_list, "raw pr list");
								
								$primary_value_list     = array();
								$primary_value_list_tmp = array();
								
								$i = 0;
								foreach( $production_list as $key_product => $production_line )
								{
									global $p_version_id, $p_year_id, $p_month_id, $cube, $element;
									if( !isset( $production_line['QTY03'] ) ) { continue; }
									if( !isset( $production_line['QTY07'] ) ) { continue; } 
									
									$value_qty03 = $production_line['QTY03']; // Qty Produced
									$value_qty07 = $production_line['QTY07']; // Lot Size
									
									$product_name = $dimension['PRODUCT']['Value'][$key_product];
									$key_receiver = array_search( $product_name, $dimension['RECEIVER']['Value'] );
									
									// From Bom Cube, Raw Materials to Semi-Finished and Finished
									foreach( $bom_list as $key_raw_mat => $bom_line )
									{
										
										foreach( $bom_line as $key_product_tmp => $bom_cell )
										{
											
											if( $key_product_tmp == $key_product )
											{
												
												if( $raw_pr_list[$key_raw_mat] != "" )
												{
													$value_p002 = $raw_pr_list[$key_raw_mat];					
												} else {
													$value_p002 = 0;
												}
												
												$value_qty04 = $bom_cell; // Req. Qty
												$value_qty06 = $value_qty03 * $value_qty04 / $value_qty07; // Total Req. Qty
												$value_craw  = $value_qty06 * $value_p002;
												$value_ae_name = $raw_ae_list[$key_raw_mat];
												
												if( $value_qty04 == 0 ) { continue; }
											
												if( isset( $primary_value_list_tmp[$product_name][$value_ae_name] ) )
												{
													$primary_value_list_tmp[$product_name][$value_ae_name] += $value_craw;
												} else {
													$primary_value_list_tmp[$product_name][$value_ae_name]  = $value_craw;
												}
											
												
											}
											
										}
									 
									}
									
								}
								
								$i = 0;
								foreach( $primary_value_list_tmp as $key_product => $primary_line )
								{
									foreach( $primary_line as $key_ae_name => $primary_cell )
									{
										$key_receiver = array_search( $key_product, $dimension['RECEIVER']['Value'] );
										$key_ae       = array_search( $key_ae_name, $dimension['ACCOUNT_ELEMENT']['Value'] );
										$primary_value_list[$key_ae][$key_receiver] = $primary_cell;
									
										$i++;
									}
								}
								
								$i = 0;
								foreach( $primary_value_list as $key_ae => $primary_line )
								{
									foreach( $primary_line as $key_receiver => $primary_cell )
									{
										$path_1   = $p_version_id.','.$p_year_id.','.$p_month_id.','.$element['PC04']['Id'].','.$key_ae.','.$key_receiver;
										$result_1 = jedox_call( "cell/replace", "cube=".$cube['PRIMARY']['Id']."&path=".$path_1."&value=".$primary_cell."&splash=0" );
										$i++; 
										
									}
								}
								
								// calc_rates_raw end --------------------------------------------------------------
								
								global $my_cwd;
								chdir( $my_cwd );
								/*
								// end */
						}
						else
						{
							$error = $this->check_file($folder_source_path); // pass error message to view
						}
					} 
					else 
					{
						$error = $this->check_file($folder_base_path); // pass error message to view
					}
					
				}
				$step = 3; //change step
			}
			
			// end calculate rates
			
            // TABLE DATA 
            
            /*
            $temp_version_area = $this->jedoxapi->get_area($temp_version);
			$temp_version_alias = $this->jedoxapi->set_alias($temp_version, $cells_version_alias);
            
			$month_dummy = $this->jedoxapi->get_area($month_elements, "MA");
            $year_dummy = $this->jedoxapi->get_area($year_elements, "YA");
			$margin_value_mg04 = $this->jedoxapi->get_area($margin_value_elements, "MG04");
			$product_fp = $this->jedoxapi->get_area($product_elements, "FP");
			$customer_cu = $this->jedoxapi->get_area($customer_elements, "CU");
			
			$table_area = $temp_version_area.",".$year_dummy.",".$month_dummy.",".$margin_value_mg04.",".$product_fp.",".$customer_cu;
            $table_data = array(); //empty container
			if(count($temp_version) != 0)
			{
				// do not execute call is no version is selected.
				$table_data = $this->jedoxapi->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$table_area, "", 1, "", "0");
			}
            */
            
            
			
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "form_year" => $form_year,
                //"yeart" => $yeart,
                //"yearf" => $yearf,
                "form_months" => $form_months,
                //"montht" => $montht,
                //"monthf" => $monthf,
                "month" => $month,
                "year" => $year,
                "form_version" => $form_version,
                "form_version_1" => $form_version_1,
                "version" => $version,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "temp_version_alias" => $temp_version_alias,
                "step" => $step,
                "error" => $error
                //"table_data" => $table_data
                //trace vars here
                
            );
			$alldata = array_merge($alldata, $vercont);
			//$this->jedoxapi->traceme($alldata);
            // Pass data and show view
            $this->load->view("profit_optimization_view", $alldata);
        }
    }
    
    private function check_file($path)
	{
		if( file_exists ($path) )
		{
			return 1;
		} else 
		{
			return $path." does not exist.";
		}
	}
	
	private function deleteDir($dir) {
		// open the directory
		$dhandle = opendir($dir);
		
		if ($dhandle) {
			// loop through it
			while (false !== ($fname = readdir($dhandle))) {
				// if the element is a directory, and
				// does not start with a '.' or '..'
				// we call deleteDir function recursively
				// passing this element as a parameter
				if (is_dir( "{$dir}/{$fname}" )) {
					if (($fname != '.') && ($fname != '..')) {
						//echo "<u>Deleting Files in the Directory</u>: {$dir}/{$fname} <br />";
						$this->deleteDir("$dir/$fname");
					}
				// the element is a file, so we delete it
				} else {
					//echo "Deleting File: {$dir}/{$fname} <br />";
					unlink("{$dir}/{$fname}");
				}
			}
			closedir($dhandle);
		}
		// now directory is empty, so we can use
		// the rmdir() function to delete it
		//echo "<u>Deleting Directory</u>: {$dir} <br />";
		rmdir($dir);
	}

	private function recourse_copy($src, $dst) {
	
		$dir = opendir($src);
		$result = ($dir === false ? false : true);
		
		if ($result !== false) {
			$result = @mkdir($dst);
		
			if ($result === true) {
				while(false !== ( $file = readdir($dir)) ) { 
					if (( $file != '.' ) && ( $file != '..' ) && $result) { 
						if ( is_dir($src . '/' . $file) ) { 
							$result = $this->recourse_copy($src . '/' . $file,$dst . '/' . $file); 
						} else { 
							$result = copy($src . '/' . $file,$dst . '/' . $file); 
						} 
					} 
				} 
				closedir($dir);
			}
		}
		return $result;
	}
	
	public function grun()
	{
		// execute etl
		$version_sim_name = $this->input->post("version_sim_name");
		//$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/")
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
		
		$variables = array( 
			array('name' => 'Database',    'value' => $this->session->userdata('jedox_db') ), 
			array('name' => 'User',        'value' => $this->session->userdata('jedox_user') ), 
			array('name' => 'Version',     'value' => $version_sim_name )
		);
		//print_r ($variables);
		
		$response  = $server->addExecution( array('locator' => $locator, 'variables' => $variables ) );
		$return    = $response->return;
		$id = $return->id;
		$this->session->set_userdata('etl_id', $id);
		// Execute job
		$response  = $server->runExecution( array('id' => $id) );
		$return    = $response->return;
		echo $return->status;
	}
	
	public function gstatus()
	{
		$id = $this->session->userdata('etl_id');
		//echo $this->etlapi->getStatus($id);
		
		//$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/")
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
	
}

chdir( $my_cwd );