<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Products_Details_v2 extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    {
        $pagename = "proEO Efficiency Products Details";
        $oneliner = "One-liner here for Efficiency Products Details";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_products_details") == FALSE)
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
            $cube_names = "Product_Detail_Report,#_Version,#_Month,#_Report_Value,#_Account_Element,#_Sender,#_Product,#_Year";
			
			// Initialize post data //
            $month = $this->input->post("month");
			$year = $this->input->post("year");
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
            $product_report_detail_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Product_Detail_Report");
			
			$product_report_detail_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Product_Detail_Report");
			
			//////////////////////////// 
            // Get Dimension elements //
            ////////////////////////////
            
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_detail_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// YEAR //
			// Get dimension of year
			//$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_detail_dimension_id[1]);
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_detail_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
			
			// MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_detail_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
			
			// report_value //
            // Get dimension of report_value
            $report_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_detail_dimension_id[3]);
            // Get cube data of report_value alias
            $report_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Report_Value");
            $report_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Report_Value");
            // Export cells of report_value alias
            $report_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $report_value_dimension_id[0]);
			$report_value_alias_name_id = $this->jedoxapi->get_area($report_value_alias_elements, "Name");
            $cells_report_value_alias = $this->jedoxapi->cell_export($server_database['database'],$report_value_alias_info['cube'],10000,"", $report_value_alias_name_id.",*");
			
			// ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_detail_dimension_id[4]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*");
			
			// SENDER //
            // Get dimension of sender
            $sender_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_detail_dimension_id[5]);
            // Get cube data of sender alias
            $sender_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Sender");
            $sender_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Sender");
            // Export cells of sender value alias
            $sender_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $sender_dimension_id[0]);
			$sender_alias_name_id = $this->jedoxapi->get_area($sender_alias_elements, "Name");
            $cells_sender_alias = $this->jedoxapi->cell_export($server_database['database'],$sender_alias_info['cube'],10000,"", $sender_alias_name_id.",*");
			
			// product //
            // Get dimension of product
            $product_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_report_detail_dimension_id[6]);
            // Get cube data of product alias
            $product_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Product");
            $product_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Product");
            // Export cells of product alias
            $product_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $product_dimension_id[0]);
			$product_alias_name_id = $this->jedoxapi->get_area($product_alias_elements, "Name");
            $cells_product_alias = $this->jedoxapi->cell_export($server_database['database'],$product_alias_info['cube'],10000,"", $product_alias_name_id.",*");
			
			// ATTRIBUTES //
            $sender_UoM_id = $this->jedoxapi->get_area($sender_alias_elements, "UoM");
            $cells_sender_attributes = $this->jedoxapi->cell_export($server_database['database'],$sender_alias_info['cube'],10000,"", $sender_UoM_id.",*");
			$product_UoM_id = $this->jedoxapi->get_area($product_alias_elements, "UoM");
            $cells_product_attributes = $this->jedoxapi->cell_export($server_database['database'],$product_alias_info['cube'],10000,"", $product_UoM_id.",*");
			
			// FORM DATA //
            $form_year = $this->jedoxapi->array_element_filter($year_elements, "YA"); // all years
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            $form_product = array_merge($this->jedoxapi->array_element_filter($product_elements, "FP"), $this->jedoxapi->array_element_filter($product_elements, "SF") );
            //$form_product = $this->jedoxapi->dimension_sort_by_name($product_elements, "FPPL_11,FPPL_21");
			$form_product = $this->jedoxapi->set_alias($form_product, $cells_product_alias);
			
			/////////////
            // PRESETS //
            /////////////
            
            if($year == '')
            {
                $now = now();
                $tnow = mdate("%Y", $now);
                $year = $this->jedoxapi->get_area($year_elements, $tnow);
            }
            if($month == '')
            {
                $month = $this->jedoxapi->get_area($month_elements, "MA");
            }
			
			if($product == '')
			{
				$product = $this->jedoxapi->get_area($product_elements, "FP");
			}
			
			////////////
            // TABLES //
            ////////////
            
            $version_pat = $this->jedoxapi->get_area($version_elements, "V001,V002,V003"); // Plan, Actual, Target
			$version_paAP = $this->jedoxapi->get_area($version_elements, "V001,V002,A/P"); // Plan, Actual, A/P
			$version_p = $this->jedoxapi->get_area($version_elements, "V001"); // Plan
			$version_a = $this->jedoxapi->get_area($version_elements, "V002"); //actual
			$version_t = $this->jedoxapi->get_area($version_elements, "V003"); //target
			$version_AP = $this->jedoxapi->get_area($version_elements, "A/P"); //A/P
			
			$account_element_ce5 = $this->jedoxapi->get_area($account_element_elements, "CE_Primary"); // CE_5
			$account_element_dummy = $this->jedoxapi->get_area($account_element_elements, "DUMMY"); // dummy
			$account_element_set = $this->jedoxapi->array_element_filter($account_element_elements, "CE_Primary"); // CE_5
			array_shift($account_element_set);
			$account_element_set_alias = $this->jedoxapi->set_alias($account_element_set, $cells_account_element_alias);
			$account_element_set_area = $this->jedoxapi->get_area($account_element_set);
			
			$sender_dummy = $this->jedoxapi->get_area($sender_elements, "DUMMY"); // dummy
			$sender_as = $this->jedoxapi->get_area($sender_elements, "AS"); //AS
			$sender_set = array_merge($this->jedoxapi->array_element_filter($sender_elements, "BP"), $this->jedoxapi->array_element_filter($sender_elements, "RP"), $this->jedoxapi->array_element_filter($sender_elements, "SF") );
			$sender_set_alias = $this->jedoxapi->set_alias($sender_set, $cells_sender_alias);
			$sender_set_area = $this->jedoxapi->get_area($sender_set);
			
			$report_value_rcp = $this->jedoxapi->get_area($report_value_elements, "PC"); // RCP
			$report_value_rcpp = $this->jedoxapi->get_area($report_value_elements, "PC02"); // RCPP
			$report_value_rcs = $this->jedoxapi->get_area($report_value_elements, "SC"); //RCS
			$report_value_rcsf = $this->jedoxapi->get_area($report_value_elements, "SCF"); // RCSF
			$report_value_rcsp = $this->jedoxapi->get_area($report_value_elements, "SCP"); // RCSP
			$report_value_rqs = $this->jedoxapi->get_area($report_value_elements, "QC"); // RQS
			
			$report_value_pc04 = $this->jedoxapi->get_area($report_value_elements, "PC04"); // pc04
			$report_value_sc03 = $this->jedoxapi->get_area($report_value_elements, "SCP"); // sc03
			
			$report_value_rec = $this->jedoxapi->get_area($report_value_elements, "REC"); // rec
			$report_value_recf = $this->jedoxapi->get_area($report_value_elements, "RECF"); // recf
			$report_value_recp = $this->jedoxapi->get_area($report_value_elements, "RECP"); // recp
			$report_value_reco = $this->jedoxapi->get_area($report_value_elements, "RECO"); // recp
			
			$product_ar = $this->jedoxapi->get_area($product_elements, "AP"); // AR
			$product_fp = $this->jedoxapi->get_area($product_elements, "FP");
			$product_converted = $this->jedox->get_dimension_data_by_id($product_elements, $product);
			//$this->jedoxapi->traceme($product_converted);
			$product_converted = $this->jedoxapi->get_area($sender_elements, $product_converted['name_element']);
			//$this->jedoxapi->traceme($product_converted);
			//$product_set = array_merge($this->jedoxapi->array_element_filter($product_elements, "BP"), $this->jedoxapi->array_element_filter($product_elements, "OP"), $this->jedoxapi->array_element_filter($product_elements, "RP"), $this->jedoxapi->array_element_filter($product_elements, "SF"), $this->jedoxapi->array_element_filter($product_elements, "FP") );
			$product_set = $this->jedoxapi->array_element_filter($product_elements, "AP");
			array_shift($product_set);
			$product_set_alias = $this->jedoxapi->set_alias($product_set, $cells_product_alias);
			$product_set_area = $this->jedoxapi->get_area($product_set);
			
			//areas
			
			//$table1a_area = $version_pat.",".$year.",".$month.",".$report_value_rcp.",".$account_element_ce5.",".$sender_dummy.",".$product;
			//$table1c_area = $version_pat.",".$year.",".$month.",".$report_value_rcpp.",".$account_element_ce5.",".$sender_dummy.",".$product;
			$table1a_area = $version_pat.",".$year.",".$month.",".$report_value_pc04.",".$account_element_ce5.",".$sender_dummy.",".$product;
			$table1c_area = $version_pat.",".$year.",".$month.",".$report_value_pc04.",".$account_element_ce5.",".$sender_dummy.",".$product;
			
			//$table1a_dd_area = $version_pat.",".$year.",".$month.",".$report_value_rcp.",".$account_element_set_area.",".$sender_dummy.",".$product;
			//$table1c_dd_area = $version_pat.",".$year.",".$month.",".$report_value_rcpp.",".$account_element_set_area.",".$sender_dummy.",".$product;
			$table1a_dd_area = $version_pat.",".$year.",".$month.",".$report_value_pc04.",".$account_element_set_area.",".$sender_dummy.",".$product;
			$table1c_dd_area = $version_pat.",".$year.",".$month.",".$report_value_pc04.",".$account_element_set_area.",".$sender_dummy.",".$product;
			
			$table2a_area = $version_pat.",".$year.",".$month.",".$report_value_rcs.",".$account_element_dummy.",".$sender_as.",".$product;
			$table2b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$account_element_dummy.",".$sender_as.",".$product;
			//$table2c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$account_element_dummy.",".$sender_as.",".$product;
			$table2c_area = $version_pat.",".$year.",".$month.",".$report_value_sc03.",".$account_element_dummy.",".$sender_as.",".$product;
			$table2d_area = $version_paAP.",".$year.",".$month.",".$report_value_rqs.",".$account_element_dummy.",".$sender_as.",".$product;
			
			$table3a_area = $version_pat.",".$year.",".$month.",".$report_value_rcs.",".$account_element_dummy.",".$sender_set_area.",".$product;
			$table3b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$account_element_dummy.",".$sender_set_area.",".$product;
			//$table3c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$account_element_dummy.",".$sender_set_area.",".$product;
			$table3c_area = $version_pat.",".$year.",".$month.",".$report_value_sc03.",".$account_element_dummy.",".$sender_set_area.",".$product;
			$table3d_area = $version_paAP.",".$year.",".$month.",".$report_value_rqs.",".$account_element_dummy.",".$sender_set_area.",".$product; 
			
			$table4a_area = $version_pat.",".$year.",".$month.",".$report_value_rec.",".$account_element_dummy.",".$product_converted.",".$product_ar;
			$table4b_area = $version_pat.",".$year.",".$month.",".$report_value_recf.",".$account_element_dummy.",".$product_converted.",".$product_ar;
			$table4c_area = $version_pat.",".$year.",".$month.",".$report_value_recp.",".$account_element_dummy.",".$product_converted.",".$product_ar;
			$table4d_area = $version_paAP.",".$year.",".$month.",".$report_value_reco.",".$account_element_dummy.",".$product_converted.",".$product_ar;
			
			//$table5a_area = $version_pat.",".$year.",".$month.",".$report_value_rcs.",".$account_element_dummy.",".$product_converted.",".$product_set_area;
			//$table5b_area = $version_pat.",".$year.",".$month.",".$report_value_rcsf.",".$account_element_dummy.",".$product_converted.",".$product_set_area;
			//$table5c_area = $version_pat.",".$year.",".$month.",".$report_value_rcsp.",".$account_element_dummy.",".$product_converted.",".$product_set_area;
			//$table5d_area = $version_paAP.",".$year.",".$month.",".$report_value_rqs.",".$account_element_dummy.",".$product_converted.",".$product_set_area;
			
			//$table4a_area = $version_pat.",".$year.",".$month.",".$report_value_rec.",".$account_element_dummy.",".$product_converted.",".$product_fp;
			//$table4b_area = $version_pat.",".$year.",".$month.",".$report_value_recf.",".$account_element_dummy.",".$product_converted.",".$product_fp;
			//$table4c_area = $version_pat.",".$year.",".$month.",".$report_value_recp.",".$account_element_dummy.",".$product_converted.",".$product_fp;
			//$table4d_area = $version_paAP.",".$year.",".$month.",".$report_value_reco.",".$account_element_dummy.",".$product_converted.",".$product_fp;
			
			$table5a_area = $version_pat.",".$year.",".$month.",".$report_value_rec.",".$account_element_dummy.",".$product_converted.",".$product_set_area;
			$table5b_area = $version_pat.",".$year.",".$month.",".$report_value_recf.",".$account_element_dummy.",".$product_converted.",".$product_set_area;
			$table5c_area = $version_pat.",".$year.",".$month.",".$report_value_recp.",".$account_element_dummy.",".$product_converted.",".$product_set_area;
			$table5d_area = $version_paAP.",".$year.",".$month.",".$report_value_reco.",".$account_element_dummy.",".$product_converted.",".$product_set_area;
			
			//data
			
			$table1a_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table1a_area, "", 1, "", "0");
			$table1c_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table1c_area, "", 1, "", "0");
			
			$table1a_dd_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table1a_dd_area, "", 1, "", "0");
			$table1c_dd_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table1c_dd_area, "", 1, "", "0");
			
			$table2a_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table2a_area, "", 1, "", "0");
			$table2b_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table2b_area, "", 1, "", "0");
			$table2c_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table2c_area, "", 1, "", "0");
			$table2d_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table2d_area, "", 1, "", "0");
			
			$table3a_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table3a_area, "", 1, "", "0");
			$table3b_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table3b_area, "", 1, "", "0");
			$table3c_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table3c_area, "", 1, "", "0");
			$table3d_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table3d_area, "", 1, "", "0");
			
			$table4a_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table4a_area, "", 1, "", "0");
			$table4b_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table4b_area, "", 1, "", "0");
			$table4c_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table4c_area, "", 1, "", "0");
			$table4d_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table4d_area, "", 1, "", "0");
			
			$table5a_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table5a_area, "", 1, "", "0");
			$table5b_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table5b_area, "", 1, "", "0");
			$table5c_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table5c_area, "", 1, "", "0");
			$table5d_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$table5d_area, "", 1, "", "0");
			
			////////////
			// CHARTS //
			////////////
			
			$version_area_at = $this->jedoxapi->get_area($version_elements, "V002,V003"); // Actual, Target
            $version_elements_at = $this->jedoxapi->dimension_elements_id($version_elements, "V002,V003");
            $version_elements_at_alias = $this->jedoxapi->set_alias($version_elements_at, $cells_version_alias);
			
			$version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            $version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
			
			$month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
			
			$chart1 = $chart2 = $chart3 = $this->jedox->multichart_xml_categories($month_all_alias, 1);
			
			$current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year);
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
			
			//chart 1
			
			//$chart1a_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rcp.",".$account_element_ce5.",".$sender_dummy.",".$product;
			$chart1a_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_pc04.",".$account_element_ce5.",".$sender_dummy.",".$product;
			$chart1b_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rcs.",".$account_element_dummy.",".$sender_as.",".$product;
			
			//echo $chart1a_area;
			
			$chart1a_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$chart1a_area, "", 1, "", "0");
			$chart1b_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$chart1b_area, "", 1, "", "0");
			
			$chart1_data = $this->jedox->add_cell_array($chart1a_data, $chart1b_data, 0, 2);
			
			$chart1 .= $this->jedox->multichart_xml_series($chart1_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			if($yearcheck != 0)
            {
            	$chart1a_area = $version_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$report_value_rcp.",".$account_element_ce5.",".$sender_dummy.",".$product;
				$chart1b_area = $version_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$report_value_rcs.",".$account_element_dummy.",".$sender_as.",".$product;
			
				$chart1a_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$chart1a_area, "", 1, "", "0");
				$chart1b_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$chart1b_area, "", 1, "", "0");
			
				$chart1a_data = $this->jedox->add_cell_array($chart1a_data, $chart1b_data, 0, 2);
				
				$chart1 .= $this->jedox->multichart_xml_series($chart1a_data, $month_all, $version_elements_a_alias, 2, 0, "", " ".$prev_year);
			}
			
			//chart2
			$chart2a_area = $version_a.",".$year.",".$month_all_area.",".$report_value_rcp.",".$account_element_ce5.",".$sender_dummy.",".$product;
			$chart2b_area = $version_a.",".$year.",".$month_all_area.",".$report_value_rcs.",".$account_element_dummy.",".$sender_as.",".$product;
			
			$chart2a_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$chart2a_area, "", 1, "", "0");
			$chart2b_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$chart2b_area, "", 1, "", "0");
			
			$chart2 .= $this->jedox->multichart_xml_series($chart2a_data, $month_all, $version_elements_a_alias, 2, 0, "", " Material Cost");
			$chart2 .= $this->jedox->multichart_xml_series($chart2b_data, $month_all, $version_elements_a_alias, 2, 0, "", " Conversion Cost");
			
			// chart 3
			$chart3_area = $version_area_at.",".$year.",".$month_all_area.",".$report_value_rqs.",".$account_element_dummy.",".$sender_as.",".$product;
			$chart3_data = $this->jedoxapi->cell_export($server_database['database'],$product_report_detail_cube_info['cube'],10000,"",$chart3_area, "", 1, "", "0");
			
			$chart3 .= $this->jedox->multichart_xml_series($chart3_data, $month_all, $version_elements_at_alias, 2, 0, "", " ".$current_year);
			
			// Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "form_year" => $form_year,
                "form_months" => $form_months,
                "form_product" => $form_product,
                "year" => $year,
                "month" => $month,
                "product" => $product,
                "version_p" => $version_p,
                "version_a" => $version_a,
                "version_t" => $version_t,
                "version_AP" => $version_AP,
                "sender_set_alias" => $sender_set_alias,
                "product_set_alias" => $product_set_alias,
                "account_element_set_alias" => $account_element_set_alias,
                "table1a_data" => $table1a_data,
                "table1c_data" => $table1c_data,
                "table1a_dd_data" => $table1a_dd_data,
                "table1c_dd_data" => $table1c_dd_data,
                "table2a_data" => $table2a_data,
                "table2b_data" => $table2b_data,
                "table2c_data" => $table2c_data,
                "table2d_data" => $table2d_data,
                "table3a_data" => $table3a_data,
                "table3b_data" => $table3b_data,
                "table3c_data" => $table3c_data,
                "table3d_data" => $table3d_data,
                "table4a_data" => $table4a_data,
                "table4b_data" => $table4b_data,
                "table4c_data" => $table4c_data,
                "table4d_data" => $table4d_data,
				"table5a_data" => $table5a_data,
                "table5b_data" => $table5b_data,
                "table5c_data" => $table5c_data,
                "table5d_data" => $table5d_data,
				"chart1" => $chart1,
				"chart2" => $chart2,
				"chart3" => $chart3,
				"cells_sender_attributes" => $cells_sender_attributes,
				"cells_product_attributes" => $cells_product_attributes
				
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_products_details_view_v2", $alldata);
            
        }
    }


}