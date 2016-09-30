<?php
	error_reporting( E_ALL );
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//$my_cwd = getcwd();
//chdir( "assets/calculate_rates" );
//include('process.php');
$p_version_id = '';
$p_year_id    = '';
$p_month_id   = '';

class Profit_Optimization_v2 extends CI_Controller {

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
            $cube_names = "Primary,Secondary,Raw_Material_Rate,Bom,Production,Margin,Capacity_Rate,Income,Margin_Report,#_Version,#_Year,#_Month,#_Margin_Value,#_Product,#_Customer,#_Primary_Value,#_Account_Element,#_Receiver,#_Secondary_Value,#_Sender,#_Raw_Material_Rate_Value,#_Raw_Material,#_Bom_Value,#_Production_Value,#_Capacity_Rate_Value,#_Income_Value";
            
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
			$temp_version_set = $this->input->post("temp_version_set");
			$temp_version_set = unserialize($temp_version_set);
			$error = '';
			$chart2 = "";
			
			$version_sim_new = $this->input->post("version_sim_new"); // var name of created version
			
			$product = $this->input->post("product");
			
			$table1_data = "";
			$table2_data = "";
			$table3_data = "";
			$table4_data = "";
			$table5_data = "";
			$table6_data = "";
			$table7_data = "";
			$table8_data = "";
			$table9_data = "";
			$table10_data = "";
			$table11_data = "";
			
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
			
			$margin_report_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Margin_Report");
			$margin_report_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Margin_Report");
			
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
			
			$version_sim_key = '';
			
			if($simulate == "Create")
			{
				// Find next version number
				$version_sim_new = url_title($version_sim_new, "_"); // fixes the space issue of space on string and preserves casing. 
				
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
				
				//copy data into newly created version continued on next block with same "if" to load other vars
				
			}
			
			// end creating new versions.
			
			
			
            // FORM DATA //
            $form_year = $this->jedoxapi->dimension_elements_base($year_elements, "YA");
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            //$form_months = $this->jedoxapi->dimension_elements_base($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
			$form_version = $this->jedoxapi->array_element_filter($version_elements, "V_Production_Plan");
			$form_version = $form_version_noalias = $this->jedoxapi->dimension_elements_base($form_version);
			$form_version = $this->jedoxapi->set_alias($form_version, $cells_version_alias);
			
			$form_product = $this->jedoxapi->array_element_filter($product_elements, "FP");
				$form_product = $this->jedoxapi->set_alias($form_product, $cells_product_alias);
				
				// form string fix //
				$temp = array();
				foreach ($form_product as $row) {
					$row['name_element'] = trim($row['name_element']);
					$temp[] = $row;
				}
				$form_product = $temp;
			
			//$form_version_1 = $this->jedoxapi->array_element_filter($version_elements, "V_Operation");
			//$form_version_1 = $this->jedoxapi->dimension_elements_base($form_version_1);
			//$form_version_1 = $this->jedoxapi->set_alias($form_version_1, $cells_version_alias);
            
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
			
			/*$cb = 0;
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
			*/
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
			
			if($product == ''){
				$product = $this->jedoxapi->get_area($product_elements, "FP");
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
			
			if($simulate == "Create")
			{
				$version_sim = $version_sim_key; // the id of the element
					
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
			// this will be triggered by the "simulate button"
			/*
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
				//$step = 2; // step now 2
				// need to pass vars to next steps...
				
				//$this->session->set_userdata('temp_version_noalias', $temp_version_noalias);
				$this->session->set_userdata('temp_version', $temp_version);
			}
			*/
			// end copy data
			
			if($simulate == "Simulate" && count($temp_version) > 0 && $step == 1){
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
				$this->session->set_userdata('temp_version_noalias', $temp_version_noalias);
				$step = 2;
				
			} else if( $step == 2 || $step == 3)
			{
			/*
			 * include/import Profitability_Product_Group_dv as view in the end. only add 2nd chart.
			 * */
				//$this->jedoxapi->traceme($temp_version_set, "temp_version set");
				$version_v001 = $this->jedoxapi->dimension_sort_by_name($version_elements, "V001");
				$version_v001 = $this->jedoxapi->set_alias($version_v001, $cells_version_alias);
				$version_v001[0]['name_element'] = "Current Production";
				//$this->jedoxapi->traceme($version_v001);
				
				if($step == 2){
					$temp_version_set = array_merge($temp_version_set, $version_v001);
				}
				
				
				
				//$product = $this->jedoxapi->get_area($product_elements, "FP"); // product for this is always set to FP
				
				////////////
            	// TABLES // 
            	////////////
            	
            	
				
				$version_pat = $this->jedoxapi->get_area($temp_version_set); // versions selected
				
				
				
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
				
				/*
				$form_product = $this->jedoxapi->array_element_filter($product_elements, "FP");
				$form_product = $this->jedoxapi->set_alias($form_product, $cells_product_alias);
				
				// form string fix //
				$temp = array();
				foreach ($form_product as $row) {
					$row['name_element'] = trim($row['name_element']);
					$temp[] = $row;
				}
				$form_product = $temp;
				*/
				$product_set = $this->jedoxapi->get_name($form_product, $product);
				$product_set = $this->jedoxapi->array_element_filter($form_product, $product_set);
				
				if(count($product_set) > 1){
					array_shift($product_set);
				}
				//$product_set = $this->jedoxapi->dimension_elements_base($form_product);
				$product_set_area = $this->jedoxapi->get_area($product_set);
				
				// Areas
			
				$table1_area = $version_pat.",".$year.",".$month.",".$margin_value_qty05.",".$product;
				$table2_area = $version_pat.",".$year.",".$month.",".$margin_value_p001.",".$product;
				$table3_area = $version_pat.",".$year.",".$month.",".$margin_value_sls04.",".$product;
				$table4_area = $version_pat.",".$year.",".$month.",".$margin_value_sls03.",".$product;
				$table5_area = $version_pat.",".$year.",".$month.",".$margin_value_sls.",".$product;
				$table6_area = $version_pat.",".$year.",".$month.",".$margin_value_craw.",".$product;
				$table7_area = $version_pat.",".$year.",".$month.",".$margin_value_mg01.",".$product;
				$table8_area = $version_pat.",".$year.",".$month.",".$margin_value_sc03.",".$product;
				$table9_area = $version_pat.",".$year.",".$month.",".$margin_value_mg02.",".$product;
				$table10_area = $version_pat.",".$year.",".$month.",".$margin_value_scf.",".$product;
				$table11_area = $version_pat.",".$year.",".$month.",".$margin_value_mg03.",".$product; 
				
				//$this->jedoxapi->traceme($table1_area);
				
				$table1_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table1_area, "", 1, "", "0");
				$table2_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table2_area, "", 1, "", "0");
				$table3_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table3_area, "", 1, "", "0");
				$table4_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table4_area, "", 1, "", "0");
				$table5_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table5_area, "", 1, "", "0");
				$table6_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table6_area, "", 1, "", "0");
				$table7_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table7_area, "", 1, "", "0");
				$table8_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table8_area, "", 1, "", "0");
				$table9_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table9_area, "", 1, "", "0");
				$table10_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table10_area, "", 1, "", "0");
				$table11_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$table11_area, "", 1, "", "0");
				
				$chart2_area = $version_pat.",".$year.",".$month.",".$margin_value_mg03.",".$product_set_area; // orig
				$chart2_data = $this->jedoxapi->cell_export($server_database['database'],$margin_report_cube_info['cube'],10000,"",$chart2_area, "", 1, "", "0"); // orig
				
				$chart2 = $this->jedox->multichart_xml_categories($product_set); // orig
				$chart2 .= $this->jedox->multichart_xml_series($chart2_data, $product_set, $temp_version_set, 4, 0); // orig
			
				$step = 3;
			}
            
            
			
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
                //"form_version_1" => $form_version_1,
                "version" => $version,
                "form_product" => $form_product,
				"product" => $product,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "temp_version_alias" => $temp_version_alias,
                "step" => $step,
                "error" => $error,
                "temp_version" => $temp_version,
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
				"temp_version_set" => $temp_version_set,
				"chart2" => $chart2
                //"table_data" => $table_data
                //trace vars here
                
            );
			$alldata = array_merge($alldata, $vercont);
			//$this->jedoxapi->traceme($alldata);
            // Pass data and show view
            $this->load->view("profit_optimization_view_v2", $alldata);
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
		if(base_url() == "http://demo.proeo.com/" || base_url() == "http://proeodev.altaviacentral.com/")
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

//chdir( $my_cwd );