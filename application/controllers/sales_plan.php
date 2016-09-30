<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_Plan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
		$this->load->library("jedoxapi");
	}
	
	public function index()
	{
		$pagename = "proEO Sales Plan";
		$oneliner = "One-liner here for Sales Plan";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedox->page_permission($user_details['group_names'], "sales_plan") == FALSE)
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
			$cube_names = "Income,#_Version,#_Month,#_Income_Value,#_Account_Element,#_Customer,#_Receiver";
			
			// Initialize post data //
			$year = $this->input->post("year");
			$receiver = $this->input->post("receiver");
			$customer = $this->input->post("customer");
			$p_jan = $this->input->post("p_jan");
			$p_feb = $this->input->post("p_feb");
			$p_mar = $this->input->post("p_mar");
			$p_apr = $this->input->post("p_apr");
			$p_may = $this->input->post("p_may");
			$p_jun = $this->input->post("p_jun");
			$p_jul = $this->input->post("p_jul");
			$p_aug = $this->input->post("p_aug");
			$p_sep = $this->input->post("p_sep");
			$p_oct = $this->input->post("p_oct");
			$p_nov = $this->input->post("p_nov");
			$p_dec = $this->input->post("p_dec");
			
			// Login
			$server_login = $this->jedox->server_login($this->session->userdata('jedox_user'), $this->session->userdata('jedox_pass')); // relogin to preven server timeout
			$this->session->set_userdata('jedox_sid', $server_login[0]); //reset SID to prevent timeouts
			
			// Get Database
			$server_database = $this->jedox->server_databases();
			$server_database = $this->jedox->server_databases_setarray($server_database);
			$server_database = $this->jedox->server_databases_select($server_database, $database_name);
			
			// Get Cubes
			$database_cubes = $this->jedox->database_cubes($server_database['database'], 1,0,1);
			$database_cubes = $this->jedox->database_cubes_setarray($database_cubes);
			
			// Dynamically load selected cubes based on names
			$cube_multiload = $this->jedox->cube_multiload($server_database['database'], $database_cubes, $cube_names);
			
			// Get Dimensions ids.
			$income_dimension_id = $this->jedox->get_dimension_id($database_cubes, "Income");
			
			$income_cube_info = $this->jedox->get_cube_data($database_cubes, "Income");
			
			////////////////////////////
			// Get Dimension elements //
			////////////////////////////
			
			// VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// YEAR //
			// Get dimension of year
			$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[1]);
			
			// MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
			
			// INCOME VALUE //
			// Get dimension of income_value
			$income_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[3]);
			// Get cube data of income_value alias]
			$income_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Income_Value");
			$income_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Income_Value");
			// Export cells of income_value alias
			$income_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_value_dimension_id[0]);
			$income_value_alias_name_id = $this->jedoxapi->get_area($income_value_alias_elements, "Name");
			$cells_income_value_alias = $this->jedoxapi->cell_export($server_database['database'],$income_value_alias_info['cube'],10000,"", $income_value_alias_name_id.",*"); 
			
			// ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[4]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*");
			
			// CUSTOMER ELEMENT //
			// Get dimension of customer
			$customer_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[5]);
			// Get cube data of account_element alias
			$customer_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Customer");
			$customer_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Customer");
			// Export cells of account_element alias
			$customer_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $customer_dimension_id[0]);
			$customer_alias_name_id = $this->jedoxapi->get_area($customer_alias_elements, "Name");
			$cells_customer_alias = $this->jedoxapi->cell_export($server_database['database'],$customer_alias_info['cube'],10000,"", $customer_alias_name_id.",*"); 
			
			// RECEIVER //
            // Get dimension of receiver
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[6]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*");
			
			// FORM DATA //
			$form_year = $year_elements;
			
			$receiver_fp_cont = $this->jedox->array_element_filter($receiver_elements, "FP"); // Finished Products
			$receiver_fp_cont_base = $this->jedox->dimension_elements_base($receiver_fp_cont);
			$form_receiver = $this->jedox->set_alias($receiver_fp_cont_base, $cells_receiver_alias); 
			
			$customer_elements_base = $this->jedox->dimension_elements_base($customer_elements);
			$form_customer = $this->jedox->set_alias($customer_elements_base, $cells_customer_alias);
			
			/////////////
			// PRESETS //
			/////////////
			
			if($year == '')
			{
				$now = now();
				$tnow = mdate("%Y", $now);
				$year = $this->jedox->get_area($year_elements, $tnow);
			}
			
			if($receiver == '')
			{
				$receiver = $receiver_fp_cont_base[0]['element'];
			}
			if($customer == '')
			{
				$customer = $customer_elements_base[0]['element'];
			}
			
			$version_ap = $this->jedox->get_area($version_elements, "V001,V002"); // Plan, Actual
			$version_p = $this->jedox->get_area($version_elements, "V001"); // Plan, Actual
			$version_a_array = $this->jedox->array_element_filter($version_elements, "V002");
			$version_a_array_alias = $this->jedox->set_alias($version_a_array, $cells_version_alias);
			$version_p_array = $this->jedox->array_element_filter($version_elements, "V001");
			$version_p_array_alias = $this->jedox->set_alias($version_p_array, $cells_version_alias);
			$version_ap_array_alias = array_merge($version_a_array_alias, $version_p_array_alias);
			$income_value_qty05 = $this->jedox->get_area($income_value_elements, "QTY05");
			$income_value_sls04 = $this->jedox->get_area($income_value_elements, "SLS04");
			$account_element_ce4100 = $this->jedox->get_area($account_element_elements, "CE_4100");
			$account_element_ce4 = $this->jedox->get_area($account_element_elements, "CE_4");
			$month_base = $this->jedox->dimension_elements_base($month_elements);
			$month_base_alias = $this->jedox->set_alias($month_base, $cells_month_alias);
			$month_base_area = $this->jedox->get_area($month_base);
			
			//If post data exist. update values before getting data for charts. need to only test 1 to know which ones have values
			if($p_jan != '')
			{
				$month_jan = $this->jedox->get_area($month_base, "M01");
				$month_feb = $this->jedox->get_area($month_base, "M02");
				$month_mar = $this->jedox->get_area($month_base, "M03");
				$month_apr = $this->jedox->get_area($month_base, "M04");
				$month_may = $this->jedox->get_area($month_base, "M05");
				$month_jun = $this->jedox->get_area($month_base, "M06");
				$month_jul = $this->jedox->get_area($month_base, "M07");
				$month_aug = $this->jedox->get_area($month_base, "M08");
				$month_sep = $this->jedox->get_area($month_base, "M09");
				$month_oct = $this->jedox->get_area($month_base, "M10");
				$month_nov = $this->jedox->get_area($month_base, "M11");
				$month_dec = $this->jedox->get_area($month_base, "M12");
				
				$p_jan_area = $version_p.",".$year.",".$month_jan.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_feb_area = $version_p.",".$year.",".$month_feb.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_mar_area = $version_p.",".$year.",".$month_mar.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_apr_area = $version_p.",".$year.",".$month_apr.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_may_area = $version_p.",".$year.",".$month_may.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_jun_area = $version_p.",".$year.",".$month_jun.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_jul_area = $version_p.",".$year.",".$month_jul.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_aug_area = $version_p.",".$year.",".$month_aug.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_sep_area = $version_p.",".$year.",".$month_sep.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_oct_area = $version_p.",".$year.",".$month_oct.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_nov_area = $version_p.",".$year.",".$month_nov.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				$p_dec_area = $version_p.",".$year.",".$month_dec.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
				
				$p_jan_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_jan_area, $p_jan);
				$p_feb_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_feb_area, $p_feb);
				$p_mar_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_mar_area, $p_mar);
				$p_apr_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_apr_area, $p_apr);
				$p_may_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_may_area, $p_may);
				$p_jun_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_jun_area, $p_jun);
				$p_jul_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_jul_area, $p_jul);
				$p_aug_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_aug_area, $p_aug);
				$p_sep_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_sep_area, $p_sep);
				$p_oct_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_oct_area, $p_oct);
				$p_nov_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_nov_area, $p_nov);
				$p_dec_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_dec_area, $p_dec);
			}
			
			
			$chart1_income_area = $version_ap.",".$year.",".$month_base_area.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
			$chart1_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart1_income_area, "", 1, "", "0");
			$chart1_income = $this->jedox->cell_export_setarray($chart1_income);
			$chart1 = $this->xml_chart1($chart1_income, $month_base_alias, $version_a_array_alias, $version_p_array_alias);
			
			$gr_area = $version_ap.",".$year.",".$month_base_area.",".$income_value_sls04.",".$account_element_ce4.",".$customer.",".$receiver;
			$gr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$gr_area, "", 1, "", "0");
			$gr_income = $this->jedox->cell_export_setarray($gr_income);
			$chart2 = $this->jedox->multichart_xml_categories($month_base_alias, 3).$this->jedox->multichart_xml_series($gr_income, $month_base_alias, $version_ap_array_alias, 2, 0);
			
			$table1 = $this->xml_table($chart1_income, $month_base_alias, $version_a_array_alias, $version_p_array_alias);
			$table2 = $this->xml_table($gr_income, $month_base_alias, $version_a_array_alias, $version_p_array_alias);
			
			$alldata = array(
				"chart1" => $chart1,
				"chart2" => $chart2,
				"form_year" => $form_year,
				"form_receiver" => $form_receiver,
				"form_customer" => $form_customer,
				"year" => $year,
				"receiver" => $receiver,
				"customer" => $customer,
				"jedox_user_details" => $this->session->userdata('jedox_user_details'),
				"pagename" => $pagename,
				"oneliner" => $oneliner,
				"table1" => $table1,
				"table2" => $table2
				//trace vars here
				//"gr_income" => $gr_income,
				//"version_ap_array_alias" => $version_ap_array_alias
			);
			// Pass data and show view
			$this->load->view("sales_plan_view", $alldata);
		}// end of login else.
	}
	
    public function info($year = '', $customer = '', $receiver = '')
    {
        $pagename = "ProEo Sales Plan";
        $oneliner = "One-liner here for Sales Plan";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedox->page_permission($user_details['group_names'], "sales_plan") == FALSE)
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
            $cube_names = "Income,#_Version,#_Month,#_Income_Value,#_Account_Element,#_Customer,#_Receiver";
            
            // Initialize post data //
            
            $p_jan = $this->input->post("p_jan");
            $p_feb = $this->input->post("p_feb");
            $p_mar = $this->input->post("p_mar");
            $p_apr = $this->input->post("p_apr");
            $p_may = $this->input->post("p_may");
            $p_jun = $this->input->post("p_jun");
            $p_jul = $this->input->post("p_jul");
            $p_aug = $this->input->post("p_aug");
            $p_sep = $this->input->post("p_sep");
            $p_oct = $this->input->post("p_oct");
            $p_nov = $this->input->post("p_nov");
            $p_dec = $this->input->post("p_dec");
            
            // Login
            $server_login = $this->jedox->server_login($this->session->userdata('jedox_user'), $this->session->userdata('jedox_pass')); // relogin to preven server timeout
            $this->session->set_userdata('jedox_sid', $server_login[0]); //reset SID to prevent timeouts
            
            // Get Database
            $server_database = $this->jedox->server_databases();
            $server_database = $this->jedox->server_databases_setarray($server_database);
            $server_database = $this->jedox->server_databases_select($server_database, $database_name);
            
            // Get Cubes
            $database_cubes = $this->jedox->database_cubes($server_database['database'], 1,0,1);
            $database_cubes = $this->jedox->database_cubes_setarray($database_cubes);
            
            // Dynamically load selected cubes based on names
            $cube_multiload = $this->jedox->cube_multiload($server_database['database'], $database_cubes, $cube_names);
            
            // Get Dimensions ids.
            $income_dimension_id = $this->jedox->get_dimension_id($database_cubes, "Income");
            
            $income_cube_info = $this->jedox->get_cube_data($database_cubes, "Income");
            
            ////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
            // VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
			
			// YEAR //
			// Get dimension of year
			$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[1]);
			
			// MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
			
			// INCOME VALUE //
			// Get dimension of income_value
			$income_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[3]);
			// Get cube data of income_value alias]
			$income_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Income_Value");
			$income_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Income_Value");
			// Export cells of income_value alias
			$income_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_value_dimension_id[0]);
			$income_value_alias_name_id = $this->jedoxapi->get_area($income_value_alias_elements, "Name");
			$cells_income_value_alias = $this->jedoxapi->cell_export($server_database['database'],$income_value_alias_info['cube'],10000,"", $income_value_alias_name_id.",*"); 
			
			// ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[4]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*");
			
			// CUSTOMER ELEMENT //
			// Get dimension of customer
			$customer_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[5]);
			// Get cube data of account_element alias
			$customer_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Customer");
			$customer_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Customer");
			// Export cells of account_element alias
			$customer_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $customer_dimension_id[0]);
			$customer_alias_name_id = $this->jedoxapi->get_area($customer_alias_elements, "Name");
			$cells_customer_alias = $this->jedoxapi->cell_export($server_database['database'],$customer_alias_info['cube'],10000,"", $customer_alias_name_id.",*"); 
			
			// RECEIVER //
            // Get dimension of receiver
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $income_dimension_id[6]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*");
            
            // FORM DATA //
            $form_year = $year_elements;
            
            $receiver_fp_cont = $this->jedox->array_element_filter($receiver_elements, "FP"); // Finished Products
            $receiver_fp_cont_base = $this->jedox->dimension_elements_base($receiver_fp_cont);
            $form_receiver = $this->jedox->set_alias($receiver_fp_cont_base, $cells_receiver_alias); 
            
            $customer_elements_base = $this->jedox->dimension_elements_base($customer_elements);
            $form_customer = $this->jedox->set_alias($customer_elements_base, $cells_customer_alias);
            
            /////////////
            // PRESETS //
            /////////////
            
            if($year == '')
            {
                $now = now();
                $tnow = mdate("%Y", $now);
                $year = $this->jedox->get_area($year_elements, $tnow);
            }
            else
            {
                $year = $this->jedox->get_area($year_elements, $year);
            }
            
            if($receiver == '')
            {
                $receiver = $receiver_fp_cont_base[0]['element'];
            }
            else
            {
                $hrreceiver = $this->jedox->set_alias($receiver_elements, $cells_receiver_alias);
                $receiver = $this->jedox->get_area($hrreceiver, $receiver, TRUE);
            }
            if($customer == '')
            {
                $customer = $customer_elements_base[0]['element'];
            }
            else 
            {
                $hrcustomer = $this->jedox->set_alias($customer_elements, $cells_customer_alias);
                $customer = $this->jedox->get_area($hrcustomer, $customer, TRUE);    
            }
            
            
            $version_ap = $this->jedox->get_area($version_elements, "V001,V002"); // Plan, Actual
            $version_p = $this->jedox->get_area($version_elements, "V001"); // Plan, Actual
            $version_a_array = $this->jedox->array_element_filter($version_elements, "V002");
            $version_a_array_alias = $this->jedox->set_alias($version_a_array, $cells_version_alias);
            $version_p_array = $this->jedox->array_element_filter($version_elements, "V001");
            $version_p_array_alias = $this->jedox->set_alias($version_p_array, $cells_version_alias);
            $version_ap_array_alias = array_merge($version_a_array_alias, $version_p_array_alias);
            $income_value_qty05 = $this->jedox->get_area($income_value_elements, "QTY05");
            $income_value_sls04 = $this->jedox->get_area($income_value_elements, "SLS04");
            $account_element_ce4100 = $this->jedox->get_area($account_element_elements, "CE_4100");
            $account_element_ce4 = $this->jedox->get_area($account_element_elements, "CE_4");
            $month_base = $this->jedox->dimension_elements_base($month_elements);
            $month_base_alias = $this->jedox->set_alias($month_base, $cells_month_alias);
            $month_base_area = $this->jedox->get_area($month_base);
            
            //If post data exist. update values before getting data for charts. need to only test 1 to know which ones have values
            if($p_jan != '')
            {
                $month_jan = $this->jedox->get_area($month_base, "M01");
                $month_feb = $this->jedox->get_area($month_base, "M02");
                $month_mar = $this->jedox->get_area($month_base, "M03");
                $month_apr = $this->jedox->get_area($month_base, "M04");
                $month_may = $this->jedox->get_area($month_base, "M05");
                $month_jun = $this->jedox->get_area($month_base, "M06");
                $month_jul = $this->jedox->get_area($month_base, "M07");
                $month_aug = $this->jedox->get_area($month_base, "M08");
                $month_sep = $this->jedox->get_area($month_base, "M09");
                $month_oct = $this->jedox->get_area($month_base, "M10");
                $month_nov = $this->jedox->get_area($month_base, "M11");
                $month_dec = $this->jedox->get_area($month_base, "M12");
                
                $p_jan_area = $version_p.",".$year.",".$month_jan.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_feb_area = $version_p.",".$year.",".$month_feb.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_mar_area = $version_p.",".$year.",".$month_mar.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_apr_area = $version_p.",".$year.",".$month_apr.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_may_area = $version_p.",".$year.",".$month_may.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_jun_area = $version_p.",".$year.",".$month_jun.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_jul_area = $version_p.",".$year.",".$month_jul.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_aug_area = $version_p.",".$year.",".$month_aug.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_sep_area = $version_p.",".$year.",".$month_sep.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_oct_area = $version_p.",".$year.",".$month_oct.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_nov_area = $version_p.",".$year.",".$month_nov.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                $p_dec_area = $version_p.",".$year.",".$month_dec.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
                
                $p_jan_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_jan_area, $p_jan);
                $p_feb_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_feb_area, $p_feb);
                $p_mar_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_mar_area, $p_mar);
                $p_apr_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_apr_area, $p_apr);
                $p_may_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_may_area, $p_may);
                $p_jun_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_jun_area, $p_jun);
                $p_jul_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_jul_area, $p_jul);
                $p_aug_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_aug_area, $p_aug);
                $p_sep_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_sep_area, $p_sep);
                $p_oct_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_oct_area, $p_oct);
                $p_nov_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_nov_area, $p_nov);
                $p_dec_edit = $this->jedox->cell_replace($server_database['database'], $income_cube_info['cube'], $p_dec_area, $p_dec);
            }
            
            
            $chart1_income_area = $version_ap.",".$year.",".$month_base_area.",".$income_value_qty05.",".$account_element_ce4100.",".$customer.",".$receiver;
            $chart1_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart1_income_area, "", 1, "", "0");
            $chart1_income = $this->jedox->cell_export_setarray($chart1_income);
            $chart1 = $this->xml_chart1($chart1_income, $month_base_alias, $version_a_array_alias, $version_p_array_alias);
            
            $gr_area = $version_ap.",".$year.",".$month_base_area.",".$income_value_sls04.",".$account_element_ce4.",".$customer.",".$receiver;
            $gr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$gr_area, "", 1, "", "0");
            $gr_income = $this->jedox->cell_export_setarray($gr_income);
            $chart2 = $this->jedox->multichart_xml_categories($month_base_alias, 3).$this->jedox->multichart_xml_series($gr_income, $month_base_alias, $version_ap_array_alias, 2, 0);
            
			$table1 = $this->xml_table($chart1_income, $month_base_alias, $version_a_array_alias, $version_p_array_alias);
			$table2 = $this->xml_table($gr_income, $month_base_alias, $version_a_array_alias, $version_p_array_alias);
			
            $alldata = array(
                "chart1" => $chart1,
                "chart2" => $chart2,
                "form_year" => $form_year,
                "form_receiver" => $form_receiver,
                "form_customer" => $form_customer,
                "year" => $year,
                "receiver" => $receiver,
                "customer" => $customer,
                "jedox_user_details" => $this->session->userdata('jedox_user_details'),
                "pagename" => $pagename,
                "oneliner" => $oneliner,
				"table1" => $table1,
				"table2" => $table2
                //trace vars here
                //"gr_income" => $gr_income,
                //"version_ap_array_alias" => $version_ap_array_alias
            );
            // Pass data and show view
            $this->load->view("sales_plan_view", $alldata);
        }// end of login else.
    }
    
	private function xml_chart1($array, $montharray, $versionarray_a, $versionarray_p)
	{
		$xml = "<categories>";
		foreach($montharray as $row)
		{
			$xml .= "<category label='".substr($row['name_element'], 0, 3)."' />";
		}
		$xml .= "</categories>";
		
		//Actual
		$a_count = array("count" => 0, "lastval" => 0);
		foreach($versionarray_a as $vrow)
		{
			$xml .= "<dataset id='v_".$vrow['element']."' seriesName='".$vrow['name_element']."'>";
			foreach($montharray as $mrow)
			{
				foreach($array as $arow)
				{
					$path = explode(",", $arow['path']);
					if($path[0] == $vrow['element'] && $path[2] == $mrow['element'])
					{
						$show = '';
						$a_count['count'] += 1;
						if($arow['value'] == 0)
						{
							$show = "alpha='0'";
						}
						else
						{
							$a_count['lastval'] = $a_count['count'];
						}
						$xml .= "<set id='v_".$vrow['element']."-m_".$mrow['element']."' value='".$arow['value']."' ".$show." allowDrag='0' />";
					}
				}
			}
			$xml .= "</dataset>";
		}
		
		//Plan
		$p_count = 0;
		foreach($versionarray_p as $vrowp)
		{
			$xml .= "<dataset id='v_".$vrowp['element']."' seriesName='".$vrowp['name_element']."'>";
			foreach($montharray as $mrowp)
			{
				foreach($array as $arowp)
				{
					$path = explode(",", $arowp['path']);
					if($path[0] == $vrowp['element'] && $path[2] == $mrowp['element'])
					{
						$p_count += 1;
						$param = '';
						if($p_count > $a_count['lastval'])
						{
							$param = "dashed='1'";
						}
						else if($p_count == $a_count['lastval'])
						{
							$param = "dashed='1' allowDrag='0'";
						}
						else if($p_count < $a_count['lastval'])
						{
							$param = "allowDrag='0'";
						}
						
						
						$xml .= "<set id='v_".$vrowp['element']."-m_".$mrowp['element']."' ".$param." value='".$arowp['value']."' />";
					}
				}
			}
			$xml .= "</dataset>";
		}
		
		
		
		return $xml;
	}
	
	private function xml_table($array, $montharray, $versionarray_a, $versionarray_p)
	{
		$xml = "<tr><td>&nbsp;</td>";
		foreach($montharray as $row)
		{
			$xml .= "<td class='thead'>".substr($row['name_element'], 0, 3)."</td>";
		}
		$xml .= "</tr>";
		
		//Actual
		//$a_count = array("count" => 0, "lastval" => 0);
		foreach($versionarray_a as $vrow)
		{
			$xml .= "<tr><td class='label2'>".$vrow['name_element']."</td>";
			foreach($montharray as $mrow)
			{
				foreach($array as $arow)
				{
					$path = explode(",", $arow['path']);
					if($path[0] == $vrow['element'] && $path[2] == $mrow['element'])
					{
						//$show = '';
						//$a_count['count'] += 1;
						//if($arow['value'] == 0)
						//{
						//	$show = "alpha='0'";
						//}
						//else
						//{
						//	$a_count['lastval'] = $a_count['count'];
						//}
						$xml .= "<td>".number_format($arow['value'], 2, '.', ',')."</td>";
					}
				}
			}
			$xml .= "</tr>";
		}
		
		//Plan
		//$p_count = 0;
		foreach($versionarray_p as $vrowp)
		{
			$xml .= "<tr><td class='label2'>".$vrowp['name_element']."</td>";
			foreach($montharray as $mrowp)
			{
				foreach($array as $arowp)
				{
					$path = explode(",", $arowp['path']);
					if($path[0] == $vrowp['element'] && $path[2] == $mrowp['element'])
					{
						///$p_count += 1;
						//$param = '';
						//if($p_count > $a_count['lastval'])
						//{
						//	$param = "dashed='1'";
						//}
						//else if($p_count == $a_count['lastval'])
						//{
						//	$param = "dashed='1' allowDrag='0'";
						//}
						//else if($p_count < $a_count['lastval'])
						//{
						//	$param = "allowDrag='0'";
						//}
						
						
						$xml .= "<td>".number_format($arowp['value'], 2, '.', ',')."</td>";
					}
				}
			}
			$xml .= "</tr>";
		}
		
		
		
		return $xml;
	}
	
}