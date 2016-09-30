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
class Input_Price_Simulation extends CI_Controller {

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
        $pagename = "proEO Input Price Simulation";
        $oneliner = "One-liner here for input price simulation";
        $user_details = $this->session->userdata('jedox_user_details');
        if( $this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if( $this->jedoxapi->page_permission( $user_details['group_names'], "efficiency_costs") == FALSE)
        {
            // currently using efficiency cost as permission
            echo "Sorry, you have no permission to access this area.";
        }
        else 
        {
        	if(isset( $_GET['trace'] ) && $_GET['trace'] == TRUE){
				// Profile the page, usefull for debugging and page optimization. Comment this line out on production or just set to FALSE.
				$this->output->enable_profiler(TRUE);
				$this->jedoxapi->set_tracer(TRUE);
        	} else {
				$this->jedoxapi->set_tracer(FALSE);
			}
			
            // Initialize variables //
            $database_name = $this->session->userdata('jedox_db');
            // Comma delimited cubenames to load. Cube names with #_ prefix are aliases cubes. No spaces.
            $cube_names = "Primary,Raw_Material_Rate,Bom,Secondary,Product_Report,#_Version,#_Year,#_Month,#_Primary_Value,#_Account_Element,#_Receiver,#_Raw_Material_Rate_Value,#_Raw_Material,#_Bom_Value,#_Product,#_Sender,#_Report_Value";
            
            // Initialize post data //
            $year    = $this->input->post("year");
            $month   = $this->input->post("month");
            $version = $this->input->post("version");
			$action  = $this->input->post("input_action");
			$step    = $this->input->post("step");
			$error = $return = $id = $result_data = $product_base_fp_alias = "";
			//$receiver = $this->input->post("receiver"); // to be added as per jan
			if( $step == '' ) { $step = 1; }

            // Login. need to relogin to prevent timeout
            $server_login = $this->jedoxapi->server_login( $this->session->userdata('jedox_user'), $this->session->userdata('jedox_pass'));
            
            // Get Database
            $server_database_list = $this->jedoxapi->server_databases();
            $server_database = $this->jedoxapi->server_databases_select( $server_database_list, $database_name);
            
            // Get Cubes
            $database_cubes = $this->jedoxapi->database_cubes( $server_database['database'], 1, 0, 1);
            
            // Dynamically load selected cubes based on names
            $cube_multiload = $this->jedoxapi->cube_multiload( $server_database['database'], $database_cubes, $cube_names);
            
            // Get Dimensions ids.
            $primary_dimension_id           = $this->jedoxapi->get_dimension_id( $database_cubes, "Primary");
            $primary_cube_info              = $this->jedoxapi->get_cube_data(    $database_cubes, "Primary");
			
			$secondary_dimension_id         = $this->jedoxapi->get_dimension_id( $database_cubes, "Secondary");
            $secondary_cube_info            = $this->jedoxapi->get_cube_data(    $database_cubes, "Secondary");
			
			$raw_material_rate_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "Raw_Material_Rate");
            $raw_material_rate_cube_info    = $this->jedoxapi->get_cube_data(    $database_cubes, "Raw_Material_Rate");

			$bom_dimension_id               = $this->jedoxapi->get_dimension_id( $database_cubes, "Bom");
            $bom_cube_info                  = $this->jedoxapi->get_cube_data(    $database_cubes, "Bom");
			
			$product_report_id				= $this->jedoxapi->get_dimension_id($database_cubes, "Product_Report");
			$product_report_cube_info		= $this->jedoxapi->get_cube_data($database_cubes, "Product_Report");
			
            ////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
            // VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $primary_dimension_id[0] );
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Version");
            $version_alias_info   = $this->jedoxapi->get_cube_data(    $database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $version_dimension_id[0] );
			$version_alias_name_id  = $this->jedoxapi->get_area( $version_alias_elements, "Name");
            $cells_version_alias    = $this->jedoxapi->cell_export( $server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
            
            // YEAR //
            // Get dimension of year
            $year_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $primary_dimension_id[1] );
            $year_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data( $database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $year_dimension_id[0] );
			$year_alias_name_id = $this->jedoxapi->get_area( $year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export( $server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
			
            // MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $primary_dimension_id[2] );
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Month");
            $month_alias_info   = $this->jedoxapi->get_cube_data( $database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $month_dimension_id[0] );
			$month_alias_name_id  = $this->jedoxapi->get_area( $month_alias_elements, "Name");
            $cells_month_alias    = $this->jedoxapi->cell_export( $server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
            
            // FORM DATA //
			$form_year = $this->jedoxapi->set_alias( $year_elements, $cells_year_alias);
			array_pop( $form_year);
            
            $form_month = $this->jedoxapi->dimension_elements_base( $month_elements, "MA"); // All Months
            $form_month = $this->jedoxapi->set_alias( $form_month, $cells_month_alias); // Set aliases
            
			$form_version = $this->jedoxapi->array_element_filter( $version_elements, "V_Operation" );
			$form_version = $this->jedoxapi->dimension_elements_base( $form_version );
			$form_version = $this->jedoxapi->set_alias( $form_version, $cells_version_alias );

			$form_version_sim = $this->jedoxapi->array_element_filter( $version_elements, "V_Input_Price");
			$form_version_sim = $this->jedoxapi->dimension_elements_base( $form_version_sim );
			$form_version_sim = $this->jedoxapi->set_alias( $form_version_sim, $cells_version_alias);
			
			/////////////
			// PRESETS //
			/////////////
            
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
            if( $version == '' )
            {
                $version = $this->jedoxapi->get_area( $version_elements, "V001");
            }
			
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
			
			$raw_material_uom_id         = $this->jedoxapi->get_area( $raw_material_alias_elements, "UoM" );
			$cells_raw_material_uom      = $this->jedoxapi->cell_export( $server_database['database'], $raw_material_alias_info['cube'], 10000, "", $raw_material_uom_id.",*" );
			
			// RAW MATERIAL RATE //
			$raw_material_value_P002_area = $this->jedoxapi->get_area( $raw_material_value_elements, "P002" );
			
			$p002_area = $version.",".$year.",".$month.",".$raw_material_value_P002_area.",*";
			$raw_material_rate_cells = $this->jedoxapi->cell_export( $server_database['database'], $raw_material_rate_cube_info['cube'], 10000, "", $p002_area );
			
			$raw_mat_data_tmp = array();
			foreach( $raw_material_rate_cells as $cell )
			{
				list( $version_tmp, $year_tmp, $month_tmp, $value_tmp, $raw_mat_tmp ) = explode( ",", $cell['path'] );
				$raw_mat_data_tmp[$raw_mat_tmp] = $cell['value'];
			}
			
			$raw_mat_data_change = array( ); // for step 4
			$primary_data_change = array( ); // for step 4
			
			// BOM QUANTITY // 
			$bom_value_elements   = $this->jedoxapi->dimension_elements( $server_database['database'], $bom_dimension_id[3] );
			$bom_value_qty06_area = $this->jedoxapi->get_area( $bom_value_elements, "QTY06" );

			// PRODUCT // 
			// Get dimension of product
			$product_elements     = $this->jedoxapi->dimension_elements( $server_database['database'], $bom_dimension_id[5] );
            // Get cube data of product alias
			$product_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Product");
			$product_alias_info   = $this->jedoxapi->get_cube_data(    $database_cubes, "#_Product");
            // Export cells of product alias
			$product_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $product_dimension_id[0] );
			$product_alias_name_id  = $this->jedoxapi->get_area( $product_alias_elements, "Name" );
			$cells_product_alias    = $this->jedoxapi->cell_export( $server_database['database'], $product_alias_info['cube'], 10000, "", $product_alias_name_id.",*" );
			
			$product_uom_id         = $this->jedoxapi->get_area( $product_alias_elements, "UoM" );
			$cells_product_uom      = $this->jedoxapi->cell_export( $server_database['database'], $product_alias_info['cube'], 10000, "", $product_uom_id.",*" );			
			
			// Report_Value //
            // Get dimension of Report_Value
            $report_value_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $product_report_id[3] );
            // Get cube data of report_value alias
            $report_value_dimension_id = $this->jedoxapi->get_dimension_id( $database_cubes, "#_Report_Value");
            $report_value_alias_info   = $this->jedoxapi->get_cube_data(    $database_cubes, "#_Report_Value");
            // Export cells of report_value alias
            $report_value_alias_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $product_report_id[3] );
			$report_value_alias_name_id  = $this->jedoxapi->get_area( $report_value_alias_elements, "Name");
            $cells_report_value_alias    = $this->jedoxapi->cell_export( $server_database['database'],$report_value_alias_info['cube'],10000,"",$report_value_alias_name_id.",*");
			
			// BOM CUBE DATA //
			$product_ap_area      = $this->jedoxapi->get_area( $product_elements, "AP" );
			
			$qty06_area = $version.",".$year.",".$month.",".$bom_value_qty06_area.",*,".$product_ap_area;
			$bom_cells = $this->jedoxapi->cell_export( $server_database['database'], $bom_cube_info['cube'], 10000, "", $qty06_area );
			
			$bom_data_tmp = array();
			foreach( $bom_cells as $cell )
			{
				list( $version_tmp, $year_tmp, $month_tmp, $value_tmp, $raw_mat_tmp, $product_tmp ) = explode( ",", $cell['path'] );
				$bom_data_tmp[$raw_mat_tmp] = number_format( $cell['value'], 0, '.', ',' );
			}
			
			$raw_mat_data = array( );
			foreach( $cells_raw_material_alias as $key => $row_alias )
			{
				$raw_mat_id = $raw_material_elements[$key];
				if( $raw_mat_id['number_children'] > 0 ) { continue; }
				
				$raw_mat_data[$key]['name']         = $row_alias['value'];
				$raw_mat_data[$key]['element']      = $raw_mat_id['element'];
				$raw_mat_data[$key]['name_element'] = $raw_mat_id['name_element'];
				
				if( isset( $cells_raw_material_uom[$key]['value'] ) )
				{
					$raw_mat_data[$key]['uom'] = $cells_raw_material_uom[$key]['value'];
				} else {
					$raw_mat_data[$key]['uom'] = '';
				}
				
				if( isset( $bom_data_tmp[$raw_mat_id['element']] ) )
				{
					$raw_mat_data[$key]['quantity'] = $bom_data_tmp[$raw_mat_id['element']];
				} else {
					$raw_mat_data[$key]['quantity'] = 0;
				}
				
				if( isset( $raw_mat_data_tmp[$raw_mat_id['element']] ) )
				{
					$raw_mat_data[$key]['value'] = $raw_mat_data_tmp[$raw_mat_id['element']];
				} else {
					$raw_mat_data[$key]['value'] = 0;
				}
				
			}
			
			// PRIMARY_VALUE //
			$primary_value_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $primary_dimension_id[3] );
            $primary_value_PC_area  = $this->jedoxapi->get_area( $primary_value_elements, "TQ" ); // FIX applied. this will replace all instances where PC is used to TQ.
			$primary_value_UPC_area  = $this->jedoxapi->get_area( $primary_value_elements, "UPC" );
			
			// ACCOUNT_ELEMENT //
			$account_element_elements        = $this->jedoxapi->dimension_elements( $server_database['database'], $primary_dimension_id[4] );
			// Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*");
			
			
            $account_element_CE_Primary_area = $this->jedoxapi->get_area( $account_element_elements, "CE_Primary" );
			
			$account_element_ce_primary_all = $this->jedoxapi->array_element_filter($account_element_elements, "CE_Primary");
			array_shift($account_element_ce_primary_all);
			$account_element_ce_primary_all_area = $this->jedoxapi->get_area($account_element_ce_primary_all);
			$account_element_ce_primary_all_alias = $this->jedoxapi->set_alias($account_element_ce_primary_all, $cells_account_element_alias);
			
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

			$sender_uom_id         = $this->jedoxapi->get_area(    $sender_alias_elements, "UoM" );
			$cells_sender_uom      = $this->jedoxapi->cell_export( $server_database['database'], $sender_alias_info['cube'], 10000, "", $sender_uom_id.",*" );
			
			$sender_as_area = $this->jedoxapi->get_area($sender_elements, "AS");
			
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
            
			$receiver_AR_area         = $this->jedoxapi->get_area( $receiver_elements, "AR" );
			
			// PRIMARY COSTS //
			//$pc_area = $version.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_CE_Primary_area.",*"; // old
			//$primary_cells = $this->jedoxapi->cell_export( $server_database['database'], $primary_cube_info['cube'], 10000, "", $pc_area ); // old
			
			$pc_area = $version.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_ce_primary_all_area.",".$receiver_AR_area;
			$primary_cells = $this->jedoxapi->cell_export( $server_database['database'], $primary_cube_info['cube'], 10000, "", $pc_area );
			
			$pc_area1 = $version.",".$year.",".$month.",".$primary_value_UPC_area.",".$account_element_ce_primary_all_area.",".$receiver_AR_area;
			$primary_cells1 = $this->jedoxapi->cell_export( $server_database['database'], $primary_cube_info['cube'], 10000, "", $pc_area1 );
			
			$primary_data_tmp = array();
			foreach( $primary_cells as $cell )
			{
				list( $version_tmp, $year_tmp, $month_tmp, $value_tmp, $account_element_tmp, $receiver_tmp ) = explode( ",", $cell['path'] );
				$primary_data_tmp[$account_element_tmp] = $cell['value'];
			}
			
			$primary_data_tmp1 = array();
			foreach( $primary_cells1 as $cell )
			{
				list( $version_tmp, $year_tmp, $month_tmp, $value_tmp, $account_element_tmp, $receiver_tmp ) = explode( ",", $cell['path'] );
				$primary_data_tmp1[$account_element_tmp] = $cell['value'];
			}
			
			
			
			// SECONDARY OUTPUT //
			//$receiver_AR_area         = $this->jedoxapi->get_area( $receiver_elements, "AR" ); // move to allow primary to use it
			
			
			// SECONDARY_VALUE //
			$secondary_value_elements = $this->jedoxapi->dimension_elements( $server_database['database'], $secondary_dimension_id[3] );
            $secondary_value_QC_area  = $this->jedoxapi->get_area( $secondary_value_elements, "QC" );
			
			// SECONDARY CUBE DATA
			$qc_area = $version.",".$year.",".$month.",".$secondary_value_QC_area.",*,".$receiver_AR_area;
			$secondary_cells = $this->jedoxapi->cell_export( $server_database['database'], $secondary_cube_info['cube'], 10000, "", $qc_area );
			
			$secondary_data_tmp = array();
			foreach( $secondary_cells as $cell )
			{
				list( $version_tmp, $year_tmp, $month_tmp, $value_tmp, $sender_tmp, $receiver_tmp ) = explode( ",", $cell['path'] );
				$secondary_data_tmp[$sender_tmp] = $cell['value'];
			}
			
			$primary_data = array( );
			foreach( $account_element_ce_primary_all_alias as $key => $row_alias )
			{
				$account_element_id = $account_element_ce_primary_all[$key];
				$primary_data[$key]['name']         = $row_alias['name_element'];
				$primary_data[$key]['element']      = $account_element_id['element'];
				$primary_data[$key]['name_element'] = $account_element_id['name_element'];
				
				$primary_data[$key]['uom']          = $cells_sender_uom[$key]['value'];
				//$primary_data[$key]['quantity'] 	= $primary_data_tmp[$account_element_id['element']];
				//$primary_data[$key]['value']        = $primary_data_tmp1[$account_element_id['element']];
				
				if( isset( $primary_data_tmp[$account_element_id['element']] ) )
				{
					$primary_data[$key]['quantity'] = $primary_data_tmp[$account_element_id['element']];
				} else {
					$primary_data[$key]['quantity'] = 0;
				}
				
				if( isset( $primary_data_tmp1[$account_element_id['element']] ) )
				{
					$primary_data[$key]['value'] = $primary_data_tmp1[$account_element_id['element']];
				} else {
					$primary_data[$key]['value'] = 0;
				}
				
				/* old codes
				$receiver_elements_id = $receiver_elements[$key];
				if( $receiver_id['number_children'] > 0 ) { continue; }
				if( substr( $receiver_id['name_element'], 0, 3 ) != 'RP_' ) { continue; };
				
				$primary_data[$key]['name']         = $row_alias['value'];
				$primary_data[$key]['element']      = $receiver_id['element'];
				$primary_data[$key]['name_element'] = $receiver_id['name_element'];
				$primary_data[$key]['value']        = $primary_data_tmp[$receiver_id['element']];
				$primary_data[$key]['uom']          = $cells_sender_uom[$key]['value'];

				if( isset( $primary_data_tmp[$raw_mat_id['element']] ) )
				{
					$primary_data[$key]['quantity'] = $primary_data_tmp[$receiver_id['element']];
				} else {
					$primary_data[$key]['quantity'] = 0;
				}
				
				if( isset( $secondary_data_tmp[$receiver_id['element']] ) )
				{
					$primary_data[$key]['value'] = $secondary_data_tmp[$receiver_id['element']];
				} else {
					$primary_data[$key]['value'] = 0;
				}
				*/
			}
			
			
			//result data is wrong
			/*
			$result_data = array();
			foreach( $cells_product_alias as $key => $row_alias )
			{
				$product_id = $product_elements[$key];
				if( $product_id['number_children'] > 0 ) { continue; }
				if( substr( $product_id['name_element'], 0, 3 ) != 'FP_' ) { continue; };
				
				$result_data[$key]['name']         = $row_alias['value'];
				$result_data[$key]['element']      = $product_id['element'];
				$result_data[$key]['name_element'] = $product_id['name_element'];
//				$result_data[$key]['value']        = $primary_data_tmp[$receiver_id['element']];

				if( isset( $cells_product_uom[$key]['value'] ) )
				{
					$result_data[$key]['uom'] = $cells_product_uom[$key]['value'];
				} else {
					$result_data[$key]['uom'] = '';
				}
				
				$result_data[$key]['quantity']     = 1000;
			}
			*/
			
			$product_elements_base = $this->jedoxapi->dimension_elements_base($product_elements);
			$product_elements_base_alias = $this->jedoxapi->set_alias($product_elements_base, $cells_product_alias);
			$product_elements_base_area = $this->jedoxapi->get_area($product_elements_base);
			
			
			
			// extra post data based on contents of tab 1 and 2.. exclude quntity 0 and below
			$update_rawmat_paths = '';
			$update_primary_paths = '';
			$update_rawmat_val = '';
			$update_primary_val = '';
			$all_data_sub1 = array();
			foreach($raw_mat_data as $row)
			{
				if( $row['quantity'] == 0 ) { continue; }
				$temp = "raw_var_val_".$row['name_element'];
				$temp1 = "raw_var_pct_".$row['name_element'];
				
				$update_rawmat_val .= $this->input->post($temp).":";
				//pass the post data back to view. array merge to all data in the end.
				$all_data_sub1[$temp] = $this->input->post($temp);
				$all_data_sub1[$temp1] = $this->input->post($temp1);
			}
			$update_rawmat_val = rtrim($update_rawmat_val, ":");
			
			foreach($primary_data as $row)
			{
				if( $row['quantity'] == 0 ) { continue; }
				$temp = "pri_var_val_".$row['name_element'];
				$temp1 = "pri_var_pct_".$row['name_element'];
				$update_primary_val .= $this->input->post($temp).":";
				//pass the post data back to view. array merge to all data in the end.
				$all_data_sub1[$temp] = $this->input->post($temp);
				$all_data_sub1[$temp1] = $this->input->post($temp1);
			}
			$update_primary_val = rtrim($update_primary_val, ":");
			//------------------------------------------------------------------------------------
			$receiver_rp_area = $this->jedoxapi->get_area($receiver_elements, "RP");
			$raw_material_elements_rw_area = $this->jedoxapi->get_area($raw_material_elements, "RW");
			$table_data1 = $table_data2 = '';
			// Perform actions based on Steps
			
			$version_sim = null;
			switch( $step )
			{
				case 2:
				$version_sim_old = $this->input->post("version_sim_old");
				$version_sim_new = $this->input->post("version_sim_new");

				// Create new version				
				if( $version_sim_new != "New Description" && $version_sim_new != "" )
				{
					// Find next version number
					$version_sim_new = url_title($version_sim_new, "_"); // fixes the space issue of space on string and preserves casing. 
					
					$version_simulation = $this->jedoxapi->dimension_elements_id( $version_elements, "V_Input_Price" );
					
					$version_elements_tmp = $this->jedoxapi->dimension_elements_base( $version_elements, "VA" );
					
					$version_list_tmp = null;
					foreach( $version_elements_tmp as $version_tmp )
					{
						if( $version_tmp['name_element'] == "V_Investment" ) { continue; }
						if( $version_list_tmp > $version_tmp['name_element'] ) { continue; }
						$version_list_tmp = $version_tmp['name_element'];
					}
					//$this->jedoxapi->traceme($version_list_tmp, "version list tmp");
					// Create new version name
					$version_list_tmp = substr( $version_list_tmp, 1, 3 ) + 1;
					$version_list_tmp = 'V'.str_pad( $version_list_tmp, 3, '0', STR_PAD_LEFT );
					
					// Create version in Jedox
					$version_sim_key = $this->jedoxapi->element_create( $server_database['database'], $primary_dimension_id[0], $version_list_tmp, $version_simulation[0]['element'] );
					
					// Set name of version in Jedox
					$path   = $version_alias_name_id.",".$version_sim_key;
					$result = $this->jedoxapi->cell_replace( $server_database['database'], $version_alias_info['cube'], $path, $version_sim_new );
					
					// Create a rule in Primary Cube to avoid recalculations
					$rule   = "['".$version_list_tmp."'] = STET()";
					//$result = $this->jedoxapi->rule_create( $server_database['database'], $primary_cube_info['cube'], $rule );
					
					// Update values for view
					$version_sim = $version_sim_key;
					//$form_version_sim[$version_sim]['element']      = $version_list_tmp;
					//$form_version_sim[$version_sim]['name_element'] = $version_sim_new;
					// note: this supposed to aout increment. using [] could have been better but needed 2 elements to be nested. used version_sim as [] to clearly separate it from other arrays and isolate data branch.
					$form_version_sim[$version_sim]['element']      = $version_sim;
					$form_version_sim[$version_sim]['name_element'] = $version_sim_new;
					
				} else {
					$version_sim = $version_sim_old;
				}
						
				//break; //merge
				
				//case 3: //merge
				
				// Copy values from old version to new version_compare
				//$version_sim = $this->input->post("version_sim"); //merge
				//$this->session->set_userdata('version_sim', $version_sim); //merge
				// Cube Primary
				
				$path_from = $version.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_CE_Primary_area.",".$receiver_rp_area;
				$path_to   = $version_sim.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_CE_Primary_area.",".$receiver_rp_area;
				$result = $this->jedoxapi->cell_copy( $server_database['database'], $primary_cube_info['cube'], $path_from, $path_to, 1 );
				
				// Cube Raw Material Costs
				// note: path based on line 189. added suffix "1" to path vars to distinguish them from th paths of primary.
				
				$path_from1 = $version.",".$year.",".$month.",".$raw_material_value_P002_area.",".$raw_material_elements_rw_area;
				$path_to1   = $version_sim.",".$year.",".$month.",".$raw_material_value_P002_area.",".$raw_material_elements_rw_area;
				$result1 = $this->jedoxapi->cell_copy( $server_database['database'], $raw_material_rate_cube_info['cube'], $path_from1, $path_to1, 1 );
				
				// secondary copy
				
				$path_from2 = $version.",".$year.",".$month.",".$secondary_value_QC_area.",".$sender_as_area.",".$receiver_AR_area;
				$path_to2   = $version_sim.",".$year.",".$month.",".$secondary_value_QC_area.",".$sender_as_area.",".$receiver_AR_area;
				$result2 = $this->jedoxapi->cell_copy( $server_database['database'], $secondary_cube_info['cube'], $path_from2, $path_to2, 1 );
				
				// UPC copy
				
				$path_from3 = $version.",".$year.",".$month.",".$primary_value_UPC_area.",".$account_element_CE_Primary_area.",".$receiver_rp_area;
				$path_to3   = $version_sim.",".$year.",".$month.",".$primary_value_UPC_area.",".$account_element_CE_Primary_area.",".$receiver_rp_area;
				$result3 = $this->jedoxapi->cell_copy( $server_database['database'], $primary_cube_info['cube'], $path_from3, $path_to3, 1 );
				
				//display
				$disp1 = $version.":".$version_sim.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_CE_Primary_area.",".$receiver_rp_area;
				$disp2 = $version.":".$version_sim.",".$year.",".$month.",".$raw_material_value_P002_area.",".$raw_material_elements_rw_area;
				
				$table_data1 = $this->jedoxapi->cell_export($server_database['database'],$primary_cube_info['cube'],10000,"",$disp1, "", 1, "", "0");
				$table_data2 = $this->jedoxapi->cell_export($server_database['database'],$raw_material_rate_cube_info['cube'],10000,"",$disp2, "", 1, "", "0");
				
				//break; //merge
				
				//case 4: //merge
					//update data based on 2 tabs. calculation of paths has to be done here for version_sim to be used.
					//$version_sim = $this->session->userdata('version_sim'); //merge
					
					foreach($raw_mat_data as $row)
					{
						if( $row['quantity'] == 0 ) { continue; }
						$rawmat_path = $version_sim.",".$year.",".$month.",".$raw_material_value_P002_area.",".$row['element'];
						$update_rawmat_paths .= $rawmat_path.":";
					}
					$update_rawmat_paths = rtrim($update_rawmat_paths, ":");
					
					foreach($primary_data as $row)
					{
						
						if( $row['quantity'] == 0 ) { continue; }
						//$pc_area = $version.",".$year.",".$month.",".$primary_value_UPC_area.",".$account_element_ce_primary_all_area.",".$receiver_AR_area;
						
						$primary_path = $version_sim.",".$year.",".$month.",".$primary_value_UPC_area.",".$row['element'].",".$receiver_AR_area;
						$update_primary_paths .= $primary_path.":";
					}
					$update_primary_paths = rtrim($update_primary_paths, ":");
					
					$update_rawmat = $this->jedoxapi->cell_replacebulk($server_database['database'], $raw_material_rate_cube_info['cube'], $update_rawmat_paths, $update_rawmat_val);
					$update_primary = $this->jedoxapi->cell_replacebulk($server_database['database'], $primary_cube_info['cube'], $update_primary_paths, $update_primary_val);
					
					//display
					//$disp1 = $version.":".$version_sim.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_CE_Primary_area.",".$receiver_rp_area; //redundant
					//$disp2 = $version.":".$version_sim.",".$year.",".$month.",".$raw_material_value_P002_area.",".$raw_material_elements_rw_area; // redundant
				
					//$table_data1 = $this->jedoxapi->cell_export($server_database['database'],$primary_cube_info['cube'],10000,"",$disp1, "", 1, "", "0"); // redundant
					//$table_data2 = $this->jedoxapi->cell_export($server_database['database'],$raw_material_rate_cube_info['cube'],10000,"",$disp2, "", 1, "", "0"); //redundant
					
					// detect and display those that changed only.
					
					$p002_area1 = $version_sim.",".$year.",".$month.",".$raw_material_value_P002_area.",*";
					$raw_material_rate_cells1 = $this->jedoxapi->cell_export( $server_database['database'], $raw_material_rate_cube_info['cube'], 10000, "", $p002_area1 );
					
					$raw_mat_data_tmp1 = array();
					foreach( $raw_material_rate_cells1 as $cell )
					{
						list( $version_tmp, $year_tmp, $month_tmp, $value_tmp, $raw_mat_tmp ) = explode( ",", $cell['path'] );
						$raw_mat_data_tmp1[$raw_mat_tmp] = $cell['value'];
					}
					
					foreach( $cells_raw_material_alias as $key => $row_alias )
					{
						$raw_mat_id = $raw_material_elements[$key];
						if( $raw_mat_id['number_children'] > 0 ) { continue; }
						if(!isset($raw_mat_data_tmp[$raw_mat_id['element']])) { continue; }
						if(!isset($raw_mat_data_tmp1[$raw_mat_id['element']])) { continue; }
						if(round( $raw_mat_data_tmp[$raw_mat_id['element']], 2) == round($raw_mat_data_tmp1[$raw_mat_id['element']], 2) ) { continue; } // if equal then no change occurred
						
						$raw_mat_data_change[$key]['name']         = $row_alias['value'];
						$raw_mat_data_change[$key]['element']      = $raw_mat_id['element'];
						$raw_mat_data_change[$key]['name_element'] = $raw_mat_id['name_element'];
						
						if( isset( $cells_raw_material_uom[$key]['value'] ) )
						{
							$raw_mat_data_change[$key]['uom'] = $cells_raw_material_uom[$key]['value'];
						} else {
							$raw_mat_data_change[$key]['uom'] = '';
						}
						
						if( isset( $bom_data_tmp[$raw_mat_id['element']] ) )
						{
							$raw_mat_data_change[$key]['quantity'] = $bom_data_tmp[$raw_mat_id['element']];
						} else {
							$raw_mat_data_change[$key]['quantity'] = 0;
						}
						
						if( isset( $raw_mat_data_tmp[$raw_mat_id['element']] ) )
						{
							$raw_mat_data_change[$key]['value'] = $raw_mat_data_tmp[$raw_mat_id['element']];
						} else {
							$raw_mat_data_change[$key]['value'] = 0;
						}
						
						if( isset( $raw_mat_data_tmp1[$raw_mat_id['element']] ) )
						{
							$raw_mat_data_change[$key]['value_change'] = $raw_mat_data_tmp1[$raw_mat_id['element']];
						} else {
							$raw_mat_data_change[$key]['value_change'] = 0;
						}
						
					}
					//$this->jedoxapi->traceme($raw_mat_data_change, "raw mat data change");
					$pc_area2 = $version_sim.",".$year.",".$month.",".$primary_value_UPC_area.",".$account_element_ce_primary_all_area.",".$receiver_AR_area;
					$primary_cells2 = $this->jedoxapi->cell_export( $server_database['database'], $primary_cube_info['cube'], 10000, "", $pc_area2 );
					//$this->jedoxapi->traceme($primary_cells1, 'primary cells 1');
					//$this->jedoxapi->traceme($primary_cells2, 'primary cells 2');
					$primary_data_tmp2 = array();
					foreach( $primary_cells2 as $cell )
					{
						list( $version_tmp, $year_tmp, $month_tmp, $value_tmp, $account_element_tmp, $receiver_tmp ) = explode( ",", $cell['path'] );
						$primary_data_tmp2[$account_element_tmp] = $cell['value'];
					}
					
					foreach( $account_element_ce_primary_all_alias as $key => $row_alias )
					{
						$account_element_id = $account_element_ce_primary_all[$key];
						//data fix for conditional trigger
						/*if( isset( $primary_data_tmp1[$account_element_id['element']] ) )
						{
							// do nothing if set
						} else 
						{
							$primary_data_tmp1[$account_element_id['element']] = 0;
						}
						
						if( isset( $primary_data_tmp2[$account_element_id['element']] ) )
						{
							// do nothing if set
						} else 
						{
							$primary_data_tmp2[$account_element_id['element']] = 0;
						}*/ // end data fix
						
						//if($primary_data_tmp1[$account_element_id['element']] == $primary_data_tmp2[$account_element_id['element']]) { continue; } // if equal then no change occurred
						$primary_data_change[$key]['name']         = $row_alias['name_element'];
						$primary_data_change[$key]['element']      = $account_element_id['element'];
						$primary_data_change[$key]['name_element'] = $account_element_id['name_element'];
						
						$primary_data_change[$key]['uom']          = $cells_sender_uom[$key]['value'];
						//$primary_data[$key]['quantity'] 	= $primary_data_tmp[$account_element_id['element']];
						//$primary_data[$key]['value']        = $primary_data_tmp1[$account_element_id['element']];
						
						if( isset( $primary_data_tmp[$account_element_id['element']] ) )
						{
							$primary_data_change[$key]['quantity'] = $primary_data_tmp[$account_element_id['element']];
						} else {
							$primary_data_change[$key]['quantity'] = 0;
						}
						
						if( isset( $primary_data_tmp1[$account_element_id['element']] ) )
						{
							$primary_data_change[$key]['value'] = $primary_data_tmp1[$account_element_id['element']];
						} else {
							$primary_data_change[$key]['value'] = 0;
						}
						
						if( isset( $primary_data_tmp2[$account_element_id['element']] ) )
						{
							$primary_data_change[$key]['value_change'] = $primary_data_tmp2[$account_element_id['element']];
						} else {
							$primary_data_change[$key]['value_change'] = 0;
						}
						
						if(round($primary_data_change[$key]['value'], 2) == round($primary_data_change[$key]['value_change'], 2))
						{
							unset($primary_data_change[$key]); // if values are equal. remove it from array
						}
						
					}
					//$this->jedoxapi->traceme($primary_data_tmp2);
				//break; //merge
				
				//case 5: //merge
					//$version_sim = $this->session->userdata('version_sim'); // repost to use it again. // merge
					//check if folder exist.
					$folder_base_path = "assets/calculate_rates/data/".$database_name;
					//$this->jedoxapi->traceme($folder_base_path, "folder base path"); //trace
					
					$source_version_name = $this->jedoxapi->get_name($version_elements, $version);
					$target_version_name = $this->jedoxapi->get_name($version_elements, $version_sim);
					
					$year_name = $this->jedoxapi->get_name($year_elements, $year);
					$month_name = $this->jedoxapi->get_name($month_elements, $month);
					
					$folder_source_path = "assets/calculate_rates/data/".$database_name."/Dataset_".$source_version_name."_".$year_name."_".$month_name;
					$folder_target_path = "assets/calculate_rates/data/".$database_name."/Dataset_".$target_version_name."_".$year_name."_".$month_name;
					
					//$this->jedoxapi->traceme($folder_source_path, "folder source path"); //trace
					//$this->jedoxapi->traceme($folder_target_path, "folder target path"); //trace
					
					//check if base path exist
					if($this->check_file($folder_base_path) == 1)
					{
						//check if source path exist
						if($this->check_file($folder_source_path) == 1)
						{
							//check if target path exist. if yes, delete it
							if($this->check_file($folder_target_path) == 1)
							{
								$this->deleteDir($folder_target_path);
							}
							
							//everything check. now we copy :D
							$this->recourse_copy($folder_source_path, $folder_target_path); // change this. copy folder and only specific files. r_calc.r r_consumption only and folder.
							chdir( $folder_target_path );
							// now the fun part
							//$p_version_id = $version_sim;
							//$p_year_id    = $year;
							//$p_month_id   = $month;
							$GLOBALS['p_version_id'] = $version_sim;
							$GLOBALS['p_year_id'] = $year;
							$GLOBALS['p_month_id'] = $month;
							generate_mapping();
							collect_primary();
							$content   = "";
							$row_names = "";
							$i = 0;
							// calc_rates start ----------------------------------------------------------------
							global $dimension;
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
								
							//	$name = $dimension['SENDER']['Name'][$key_s];
								$name = $value;
								if( $content != "" ) { $content .= ","; $row_names .= ","; }
								$content   .= number_format( $value_fix, 2, ".", "" ).",0,".number_format( $value_prop, 2, ".", "" );
								$row_names .= "\"".$name." - PC01\",\"".$name." - PC99\",\"".$name." - PC02\"";
							
								$i += 3;
							}
							
							$content = "V_new <- matrix(c(".$content."),".$i.",1,TRUE)".PHP_EOL;
							//$this->jedoxapi->traceme($content, "content variable");
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
								$login_details['message'] .= "Error: Unable to access results from r_results.r<br/>";
								$output = str_replace( PHP_EOL, "<br/>", $output );
								$output = str_replace( "\r", "", $output );
								$output = str_replace( "\n", "<br/>", $output );
								$login_details['message'] .= $output."<br/>";
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
									//$this->jedoxapi->traceme($path_1, "path");
									$result_1 = jedox_call( "cell/replace", "cube=".$cube['PRIMARY']['Id']."&path=".$path_1."&value=".$primary_cell."&splash=0" );
									//$this->jedoxapi->cell_replace($server_database['database'], $primary_cube_info['cube'], $path_1, $primary_cell, '', 0);
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
					
				//break; //merge
				
				//case 6: //merge
					// execute etl
					//$version_sim = $this->session->userdata('version_sim'); // repost to use it again.
					
					$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
					$server     = new SoapClient($server_url, array('exceptions' => true) );
					$locator    = "ProEo_Template.jobs.Job_Rul_Combined";
					
					$variables = array(
						//array('name' => 'R_Run','value' => $R_Run ), 
						array('name' => 'Database',    'value' => $this->session->userdata('jedox_db') ), 
						array('name' => 'User',        'value' => $this->session->userdata('jedox_user') ), 
						array('name' => 'Version',     'value' => $version_sim )
					);
					
					//$variables = array( array( 'name' => 'Description', 'value' => $_POST['description'] ) );
					$response  = $server->addExecution( array('locator' => $locator, 'variables' => $variables ) );
					$return    = $response->return;
					$id = $return->id;
					
					// Execute job
					$response  = $server->runExecution( array('id' => $id) );
					$return    = $response->return;
					
					//$this->jedoxapi->traceme($return, 'return var');
					
					// results tab
					$report_value_elements_tc_area = $this->jedoxapi->get_area($report_value_elements, "TC");
					$product_base_fp = $this->jedoxapi->array_element_filter($product_elements, "FP");
					$product_base_fp = $this->jedoxapi->dimension_elements_base($product_base_fp);
					//array_shift($product_base_fp);
					$product_base_fp_area = $this->jedoxapi->get_area($product_base_fp);
					$product_base_fp_alias = $this->jedoxapi->set_alias($product_base_fp, $cells_product_alias);
					
					$result_area = $version.":".$version_sim.",".$year.",".$month.",".$report_value_elements_tc_area.",".$product_base_fp_area;
					$result_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_cube_info['cube'],10000,"",$result_area, "", 1, "", "0");
					
					
				break;
			}
			
			
			// EXPORT //
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
				"action"       => $action,
                "form_year"    => $form_year,
                "year"         => $year,
                "form_month"   => $form_month,
                "month"        => $month,
                "form_version" => $form_version,
                "form_version_sim" => $form_version_sim,
				"version_sim"  => $version_sim,
                "version"      => $version,
                "step"         => $step,
                "error" => $error,
                "return" => $return,
                "id" => $id,
				"raw_mat_data" => $raw_mat_data,
				"raw_mat_data_change" => $raw_mat_data_change,
				"primary_data" => $primary_data,
				"primary_data_change" => $primary_data_change,
				"result_data"  => $result_data,
                "jedox_user_details" => $user_details,
                "pagename"           => $pagename,
                "oneliner"           => $oneliner,
                "table_data1" => $table_data1,
                "table_data2" => $table_data2,
                "product_base_fp_alias" => $product_base_fp_alias
                //trace vars here
            );
			$alldata = array_merge($alldata, $all_data_sub1);
            // Pass data and show view
            $this->load->view("input_price_simulation_view", $alldata);
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
	
	public function gstatus()
	{
		$id = $this->input->post("id");
		//echo $this->etlapi->getStatus($id);
		
		
		$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		$server     = new SoapClient($server_url, array('exceptions' => true) );
		//$id = $s->range("B15")->value; // getting hidden value to check status?
  
		// Am ende den Status holen und in die Zelle schreiben
		$response = $server->getExecutionStatus(array('id' => $id, 'waitForTermination' => false));
		$return = $response->return;
  
		//$s->range("D16")->value = $return->status; // return status to field??
		$edata = $return->status;
		echo $edata;
		
	}
	
}

chdir( $my_cwd );