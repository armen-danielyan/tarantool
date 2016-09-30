<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profitability extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("jedox");
        $this->load->library("jedoxapi");
	}
	
	public function index()
	{
		$pagename = "proEO Profitability";
		$oneliner = "One-liner here for Profitability";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedox->page_permission($user_details['group_names'], "profitability") == FALSE)
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
			$cube_names = "Income,Margin,#_Version,#_Month,#_Income_Value,#_Account_Element,#_Receiver,#_Customer,#_Margin_Value";
			// Chart containers
			$chart1 = "";
			$chart2 = "";
			$chart3 = "";
			$chart4 = "";
			$chart5 = "";
			$chart6 = "";
			$chart7 = "";
			$chart8 = "";
			// Initialize post data //
			$year = $this->input->post("year");
			$month = $this->input->post("month");
			$receiver = $this->input->post("receiver");
			$customer = $this->input->post("customer");
			
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
			$margin_dimension_id = $this->jedox->get_dimension_id($database_cubes, "Margin");
			
			$income_cube_info = $this->jedox->get_cube_data($database_cubes, "Income");
			$margin_cube_info = $this->jedox->get_cube_data($database_cubes, "Margin");
			
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
			
			// MARGIN VALUE //
			// Get dimension of margin_value
			$margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[3]);
			// Get cube data of receiver alias
			$margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
			$margin_value_alias_info = $this->jedox->get_cube_data($database_cubes, "#_Margin_Value");
			// Export cells of receiver alias
			$margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
			$cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*"); 
			
			// FORM DATA //
			$form_year = $year_elements;
			
			//$form_months = $this->jedox->array_element_filter($month_elements, "MA"); // All Months
			$form_months = $this->jedox->set_alias($month_elements, $cells_month_alias); // Set aliases
			
			$receiver_fp_cont = $this->jedox->array_element_filter($receiver_elements, "FP"); // Finished Products
			$form_receiver = $this->jedox->set_alias($receiver_fp_cont, $cells_receiver_alias); 
			
			$form_customer = $this->jedox->set_alias($customer_elements, $cells_customer_alias);
			
			/////////////
			// PRESETS //
			/////////////
			
			if($year == '')
			{
				$now = now();
				$tnow = mdate("%Y", $now);
				$year = $this->jedox->get_area($year_elements, $tnow);
			}
			if($month == '')
			{
				$month = $this->jedox->get_area($month_elements, "MA"); // All Months
			}
			if($receiver == '')
			{
				$receiver = $this->jedox->get_area($receiver_elements, "FP"); // All Products
			}
			if($customer == '')
			{
				$customer = $this->jedox->get_area($customer_elements, "CU"); 
			}
			
			////////////
			// CHARTS //
			////////////
			
			$version_actual = $this->jedox->get_area($version_elements, "V002"); // Actual
			$version_target = $this->jedox->get_area($version_elements, "V003"); // Target
			$version_plan = $this->jedox->get_area($version_elements, "V001"); // Plan
			$version_at = $this->jedox->get_area($version_elements, "V002,V003"); // Actual,Target
			$income_value_sls = $this->jedox->get_area($income_value_elements, "SLS"); // Income
			$account_element_ce4 = $this->jedox->get_area($account_element_elements, "CE_4");
			$customer_cu = $this->jedox->get_area($customer_elements, "CU");
			$margin_mg03 = $this->jedox->get_area($margin_value_elements, "MG03");
			$margin_mg04 = $this->jedox->get_area($margin_value_elements, "MG04"); 
			$margin_mg04_perc = $this->jedox->get_area($margin_value_elements, "MG04%");
			$receiver_fp_childs = array_merge($this->jedox->array_element_filter($receiver_elements, "FP_100"), $this->jedox->array_element_filter($receiver_elements, "FP_200"), $this->jedox->array_element_filter($receiver_elements, "FP_300"), $this->jedox->array_element_filter($receiver_elements, "FP_400"));
			$receiver_fp_childs_a = $this->jedox->get_area($receiver_fp_childs);
			
			
			/////////////////////////////////////////////
			// CHART 1 - Revenue and Margin per Product //
			/////////////////////////////////////////////
			
			$chart1_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer.",".$receiver_fp_childs_a;
			$chart1_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer.",".$receiver_fp_childs_a;
			$chart1_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer.",".$receiver_fp_childs_a;
			
			$chart1_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart1_income_area, "", 1, "", "0");
			$chart1_income = $this->jedox->cell_export_setarray($chart1_income);
			
			$chart1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area, "", 1, "", "0");
			$chart1_margin = $this->jedox->cell_export_setarray($chart1_margin);
			
			$chart1_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area_b, "", 1, "", "0");
			$chart1_margin_b = $this->jedox->cell_export_setarray($chart1_margin_b);
			
			//combine data for xy chart.
			$chart1_array = array();
			foreach($receiver_fp_childs as $row)
			{
				foreach($chart1_income as $i)
				{
					if($i['value'] == '')
					{
						$i['value'] = 0;
					}
					$i_path = explode(",", $i['path']);
					if($i_path[6] == $row['element'] && $i_path[0] == $version_actual)
					{
						$row['Income'] = $i['value'];
					}
					if($i_path[6] == $row['element'] && $i_path[0] == $version_target)
					{
						$row['Income_t'] = $i['value'];
					}
				}
				foreach($chart1_margin as $j)
				{
					if($j['value'] == '')
					{
						$j['value'] = 0;
					}
					$j_path = explode(",", $j['path']);
					if($j_path[5] == $row['element'] && $j_path[0] == $version_actual)
					{
						$row['Margin'] = $j['value'];
					}
					if($j_path[5] == $row['element'] && $j_path[0] == $version_target)
					{
						$row['Margin_t'] = $j['value'];
					}
				}
				foreach($chart1_margin_b as $k)
				{
					if($k['value'] == '')
					{
						$k['value'] = 0;
					}
					$k_path = explode(",", $k['path']);
					if($k_path[5] == $row['element'] && $k_path[0] == $version_actual)
					{
						$row['MarginD'] = $k['value'];
					}
					if($k_path[5] == $row['element'] && $k_path[0] == $version_target)
					{
						$row['MarginD_t'] = $k['value'];
					}
				}
				$chart1_array[] = $row;
			}
			$chart1_array = $this->jedox->set_alias($chart1_array, $cells_receiver_alias);
			$chart1 = $this->xy_xml($chart1_array, $this->jedox->get_area($receiver_elements, "FP"));
			
			$table1_top10 = $this->gm_variance($chart1_array);
			usort($table1_top10, array($this,'custom_sort_desc_m'));
			$table1_top10_count = count($table1_top10);
			
			$table1_top10 = $this->single_xml_custom_array($table1_top10);
			
			
			///////////////////////////////////////////////
			// CHART 2 - ACTUAL GROSS MARGIN BY COSTUMER //
			///////////////////////////////////////////////
			$customer_base = $this->jedox->dimension_elements_base($customer_elements);
			$customer_base_alias = $this->jedox->set_alias($customer_base, $cells_customer_alias);
			$customer_base_area = $this->jedox->get_area($customer_base);
			$chart2_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer_base_area.",".$receiver;
			
			$chart2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart2_margin_area, "", 1, "", "0");
			$chart2_margin = $this->jedox->cell_export_setarray($chart2_margin);
			usort($chart2_margin, array($this,'custom_sort_asc'));
			$chart2 = $this->jedox->singlechart_xml($chart2_margin, $customer_base_alias, 4);
			
			//////////////////////////////////////////////
			// CHART 3 - ACTUAL GROSS MARGIN BY PRODUCT //
			//////////////////////////////////////////////
			$receiver_fp_childs_base = $this->jedox->dimension_elements_base($receiver_fp_childs);
			$receiver_fp_childs_base_a = $this->jedox->get_area($receiver_fp_childs_base);
			$receiver_fp_childs_base_alias = $this->jedox->set_alias($receiver_fp_childs_base, $cells_receiver_alias);
			$chart3_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer.",".$receiver_fp_childs_base_a;
			
			$chart3_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart3_margin_area, "", 1, "", "0");
			$chart3_margin = $this->jedox->cell_export_setarray($chart3_margin);
			usort($chart3_margin, array($this,'custom_sort_asc'));
			$chart3 = $this->jedox->singlechart_xml($chart3_margin, $receiver_fp_childs_base_alias, 5);
			
			//////////////////////////////////////////////
			// CHART 4 - Revenue and Margin by Customer //
			//////////////////////////////////////////////
			$customer_cu_childs = array_merge($this->jedox->array_element_filter($customer_elements, "CU_1"), $this->jedox->array_element_filter($customer_elements, "CU_2"), $this->jedox->array_element_filter($customer_elements, "CU_3"));
			$customer_cu_childs_a = $this->jedox->get_area($customer_cu_childs);
			
			$chart4_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer_cu_childs_a.",".$receiver;
			$chart4_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer_cu_childs_a.",".$receiver;
			$chart4_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer_cu_childs_a.",".$receiver;
			
			$chart4_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart4_income_area, "", 1, "", "0");
			$chart4_income = $this->jedox->cell_export_setarray($chart4_income);
			
			$chart4_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area, "", 1, "", "0");
			$chart4_margin = $this->jedox->cell_export_setarray($chart4_margin);
			
			$chart4_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area_b, "", 1, "", "0");
			$chart4_margin_b = $this->jedox->cell_export_setarray($chart4_margin_b);
			
			$chart4_array = array();
			foreach($customer_cu_childs as $row)
			{
				foreach($chart4_income as $i)
				{
					if($i['value'] == '')
					{
						$i['value'] = 0;
					}
					$i_path = explode(",", $i['path']);
					if($i_path[5] == $row['element'] && $i_path[0] == $version_actual)
					{
						$row['Income'] = $i['value'];
					}
					if($i_path[5] == $row['element'] && $i_path[0] == $version_target)
					{
						$row['Income_t'] = $i['value'];
					}
				}
				foreach($chart4_margin as $j)
				{
					if($j['value'] == '')
					{
						$j['value'] = 0;
					}
					$j_path = explode(",", $j['path']);
					if($j_path[4] == $row['element'] && $j_path[0] == $version_actual)
					{
						$row['Margin'] = $j['value'];
					}
					if($j_path[4] == $row['element'] && $j_path[0] == $version_target)
					{
						$row['Margin_t'] = $j['value'];
					}
				}
				foreach($chart4_margin_b as $k)
				{
					if($k['value'] == '')
					{
						$k['value'] = 0;
					}
					$k_path = explode(",", $k['path']);
					if($k_path[4] == $row['element'] && $k_path[0] == $version_actual)
					{
						$row['MarginD'] = $k['value'];
					}
					if($k_path[4] == $row['element'] && $k_path[0] == $version_target)
					{
						$row['MarginD_t'] = $k['value'];
					}
				}
				$chart4_array[] = $row;
			}
			$chart4_array = $this->jedox->set_alias($chart4_array, $cells_customer_alias);
			$chart4 = $this->xy_xml2($chart4_array, $this->jedox->get_area($customer_elements, "CU"));
			
			$table4_top10 = $this->gm_variance($chart4_array);
			usort($table4_top10, array($this,'custom_sort_desc_m'));
			$table4_top10_count = count($table4_top10);
			$table4_top10 = $this->single_xml_custom_array($table4_top10);
			
			
			///////////
			// TABLE //
			///////////
			$version_pac = $this->jedox->get_area($version_elements, "V001,V002,V003"); // Plan,Actual,Target
			$version_pac_elements = $this->jedox->dimension_elements_id($version_elements, "V001,V002,V003");
			$income_value_qty05 = $this->jedox->get_area($income_value_elements, "QTY05"); 
			$income_value_sls04 = $this->jedox->get_area($income_value_elements, "SLS04");
			$income_value_sls03 = $this->jedox->get_area($income_value_elements, "SLS03");
			$income_value_sls02 = $this->jedox->get_area($income_value_elements, "SLS02");
			$income_value_p001 = $this->jedox->get_area($income_value_elements, "P001");
			$margin_pc04 = $this->jedox->get_area($margin_value_elements, "PC04");
			$margin_sc04 = $this->jedox->get_area($margin_value_elements, "SC04");
			$margin_sc03 = $this->jedox->get_area($margin_value_elements, "SC03");
			$margin_scf = $this->jedox->get_area($margin_value_elements, "SCF");
			
			$sq_area = $version_pac.",".$year.",".$month.",".$income_value_qty05.",".$account_element_ce4.",".$customer.",".$receiver;
			$sq_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sq_area, "", 1, "", "0");
			$sq_income = $this->jedox->cell_export_setarray($sq_income);
			$sq = $this->custom_row($sq_income, $version_pac_elements, 0, ''); 
			
			// commented out till filter is added. //
			$sp_area = $version_pac.",".$year.",".$month.",".$income_value_p001.",".$account_element_ce4.",".$customer.",".$receiver;
			$sp_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sp_area, "", 1, "", "0");
			$sp_income = $this->jedox->cell_export_setarray($sp_income);
			$sp = $this->custom_row($sp_income, $version_pac_elements, 0, '$', 0, 1); 
			//$sp = "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			
			$gr_area = $version_pac.",".$year.",".$month.",".$income_value_sls04.",".$account_element_ce4.",".$customer.",".$receiver;
			$gr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$gr_area, "", 1, "", "0");
			$gr_income = $this->jedox->cell_export_setarray($gr_income);
			$gr = $this->custom_row($gr_income, $version_pac_elements, 0, '$');
			
			$dc_area = $version_pac.",".$year.",".$month.",".$income_value_sls03.",".$account_element_ce4.",".$customer.",".$receiver;
			$dc_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$dc_area, "", 1, "", "0");
			$dc_income = $this->jedox->cell_export_setarray($dc_income);
			$dc = $this->custom_row($dc_income, $version_pac_elements, 0, '$', 1);
			
			$nr_area = $version_pac.",".$year.",".$month.",".$income_value_sls02.",".$account_element_ce4.",".$customer.",".$receiver;
			$nr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$nr_area, "", 1, "", "0");
			$nr_income = $this->jedox->cell_export_setarray($nr_income);
			$nr = $this->custom_row($nr_income, $version_pac_elements, 0, '$');
			
			$rm1_area = $version_pac.",".$year.",".$month.",".$margin_pc04.",".$customer.",".$receiver;
			$rm1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm1_area, "", 1, "", "0");
			$rm1_margin = $this->jedox->cell_export_setarray($rm1_margin);
			$rm2_area = $version_pac.",".$year.",".$month.",".$margin_sc04.",".$customer.",".$receiver;
			$rm2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm2_area, "", 1, "", "0");
			$rm2_margin = $this->jedox->cell_export_setarray($rm2_margin);
			$rm_both = $this->jedox->add_cell_array($rm1_margin, $rm2_margin, 0, 1);
			$rm = $this->custom_row($rm_both, $version_pac_elements, 0, '$', 1);
			
			$pm_margin = $this->jedox->subtract_cell_array($nr_income, $rm_both, 0, 1);
			$pm = $this->custom_row($pm_margin, $version_pac_elements, 0, '$'); 
			
			$pc_area = $version_pac.",".$year.",".$month.",".$margin_sc03.",".$customer.",".$receiver;
			$pc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$pc_area, "", 1, "", "0");
			$pc_margin = $this->jedox->cell_export_setarray($pc_margin);
			$pc = $this->custom_row($pc_margin, $version_pac_elements, 0, '$', 1);
			
			$cm_margin = $this->jedox->subtract_cell_array($pm_margin, $pc_margin, 0, 1);
			$cm = $this->custom_row($cm_margin, $version_pac_elements, 0, '$');
			
			$fc_area = $version_pac.",".$year.",".$month.",".$margin_scf.",".$customer.",".$receiver;
			$fc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$fc_area, "", 1, "", "0");
			$fc_margin = $this->jedox->cell_export_setarray($fc_margin);
			$fc = $this->custom_row($fc_margin, $version_pac_elements, 0, '$', 1);
			
			$gm_margin = $this->jedox->subtract_cell_array($cm_margin, $fc_margin, 0, 1);
			$gm = $this->custom_row($gm_margin, $version_pac_elements, 0, '$');
			
			//////////////////////
			// WATERFALL CHARTS //
			//////////////////////
			
			$chart5 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_plan);
			
			$chart5 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Net Revenue' isSum='1'></set>";
			//$chart6 .= "<set label='Net Revenue' isSum='1'></set>";
			//$chart7 .= "<set label='Net Revenue' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Product Margin' isSum='1'></set>";
			//$chart6 .= "<set label='Product Margin' isSum='1'></set>";
			//$chart7 .= "<set label='Product Margin' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Contribution Margin' isSum='1'></set>";
			//$chart6 .= "<set label='Contribution Margin' isSum='1'></set>";
			//$chart7 .= "<set label='Contribution Margin' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Gross Margin' isSum='1'></set>";
			
			/////////////
			// CHART 8 //
			/////////////
			
			$chart8 .= $this->single_xml_custom("Gross Revenue", $gr_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Discounts", $dc_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Net Revenue", $nr_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Raw Material", $rm_both, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Product Margin", $pm_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Proportional Cost", $pc_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Contribution Margin", $cm_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Fixed Cost", $fc_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Gross Margin", $gm_margin, $version_actual, $version_target);
			
			
			//percentages
			
			$p_pm = $this->jedox->percentage_cell_array($pm_margin, $nr_income, 0, 1);
			$p_pm = $this->custom_row_p($p_pm, $version_pac_elements, 0, '%');
			
			$p_cm = $this->jedox->percentage_cell_array($cm_margin, $nr_income, 0, 1);
			$p_cm = $this->custom_row_p($p_cm, $version_pac_elements, 0, '%');
			
			$p_gm = $this->jedox->percentage_cell_array($gm_margin, $nr_income, 0, 1);
			$p_gm = $this->custom_row_p($p_gm, $version_pac_elements, 0, '%');
			
			// Pass all data to send to view file
			$alldata = array(
				//regular vars here
				"year" => $year,
				"month" => $month,
				"receiver" => $receiver,
				"customer" => $customer,
				"form_year" => $form_year,
				"form_months" => $form_months,
				"form_receiver" => $form_receiver,
				"form_customer" => $form_customer,
				"chart1" => $chart1,
				"chart2" => $chart2,
				"chart3" => $chart3,
				"chart4" => $chart4,
				"chart5" => $chart5,
				//"chart6" => $chart6,
				//"chart7" => $chart7,
				"chart8" => $chart8,
				"sq" => $sq,
				"sp" => $sp,
				"gr" => $gr,
				"dc" => $dc,
				"nr" => $nr,
				"rm" => $rm,
				"pm" => $pm,
				"pc" => $pc,
				"cm" => $cm,
				"fc" => $fc,
				"gm" => $gm,
				"p_pm" => $p_pm,
				"p_cm" => $p_cm,
				"p_gm" => $p_gm,
				"table1_top10" => $table1_top10,
				"table4_top10" => $table4_top10,
				"table1_top10_count" => $table1_top10_count,
				"table4_top10_count" => $table4_top10_count,
				"jedox_user_details" => $this->session->userdata('jedox_user_details'),
				"pagename" => $pagename,
				"oneliner" => $oneliner
				//trace vars here 
				//"chart1_array" => $chart1_array
				
			);
			// Pass data and show view
			$this->load->view("profitability_view", $alldata);
		
		}// end of login check else.
	}	
	
    public function info($year = '', $month = '', $customer = '', $receiver = '')
    {
        $pagename = "ProEo Profitability";
        $oneliner = "One-liner here for Profitability";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedox->page_permission($user_details['group_names'], "profitability") == FALSE)
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
            $cube_names = "Income,Margin,#_Version,#_Month,#_Income_Value,#_Account_Element,#_Receiver,#_Customer,#_Margin_Value";
            // Chart containers
            $chart1 = "";
            $chart2 = "";
            $chart3 = "";
            $chart4 = "";
            $chart5 = "";
            $chart6 = "";
            $chart7 = "";
            $chart8 = "";
            
            
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
            $margin_dimension_id = $this->jedox->get_dimension_id($database_cubes, "Margin");
            
            $income_cube_info = $this->jedox->get_cube_data($database_cubes, "Income");
            $margin_cube_info = $this->jedox->get_cube_data($database_cubes, "Margin");
            
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
			
			// MARGIN VALUE //
			// Get dimension of margin_value
			$margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[3]);
			// Get cube data of receiver alias
			$margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
			$margin_value_alias_info = $this->jedox->get_cube_data($database_cubes, "#_Margin_Value");
			// Export cells of receiver alias
			$margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
			$cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*"); 
            
            // FORM DATA //
            $form_year = $year_elements;
            
            //$form_months = $this->jedox->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedox->set_alias($month_elements, $cells_month_alias); // Set aliases
            
            $receiver_fp_cont = $this->jedox->array_element_filter($receiver_elements, "FP"); // Finished Products
            $form_receiver = $this->jedox->set_alias($receiver_fp_cont, $cells_receiver_alias); 
            
            $form_customer = $this->jedox->set_alias($customer_elements, $cells_customer_alias);
            
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
            if($month == '')
            {
                $month = $this->jedox->get_area($month_elements, "MA"); // All Months
            }
            else
            {
                $hrmonth = $this->jedox->set_alias($month_elements, $cells_month_alias);
                $month = $this->jedox->get_area($hrmonth, $month, TRUE);
            }
            if($receiver == '')
            {
                $receiver = $this->jedox->get_area($receiver_elements, "FP"); // All Products
            }
            else
            {
                $hrreceiver = $this->jedox->set_alias($receiver_elements, $cells_receiver_alias);
                $receiver = $this->jedox->get_area($hrreceiver, $receiver, TRUE);
            }
            if($customer == '')
            {
                $customer = $this->jedox->get_area($customer_elements, "CU"); 
            }
            else 
            {
                $hrcustomer = $this->jedox->set_alias($customer_elements, $cells_customer_alias);
                $customer = $this->jedox->get_area($hrcustomer, $customer, TRUE);    
            }
            
            ////////////
            // CHARTS //
            ////////////
            
            $version_actual = $this->jedox->get_area($version_elements, "V002"); // Actual
            $version_target = $this->jedox->get_area($version_elements, "V003"); // Target
            $version_plan = $this->jedox->get_area($version_elements, "V001"); // Plan
            $version_at = $this->jedox->get_area($version_elements, "V002,V003"); // Actual,Target
            $income_value_sls = $this->jedox->get_area($income_value_elements, "SLS"); // Income
            $account_element_ce4 = $this->jedox->get_area($account_element_elements, "CE_4");
            $customer_cu = $this->jedox->get_area($customer_elements, "CU");
            $margin_mg03 = $this->jedox->get_area($margin_value_elements, "MG03");
            $margin_mg04 = $this->jedox->get_area($margin_value_elements, "MG04"); 
            $margin_mg04_perc = $this->jedox->get_area($margin_value_elements, "MG04%");
            $receiver_fp_childs = array_merge($this->jedox->array_element_filter($receiver_elements, "FP_100"), $this->jedox->array_element_filter($receiver_elements, "FP_200"), $this->jedox->array_element_filter($receiver_elements, "FP_300"), $this->jedox->array_element_filter($receiver_elements, "FP_400"));
            $receiver_fp_childs_a = $this->jedox->get_area($receiver_fp_childs);
            
            
            /////////////////////////////////////////////
            // CHART 1 - Revenue and Margin per Product //
            /////////////////////////////////////////////
            
            $chart1_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer.",".$receiver_fp_childs_a;
            $chart1_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer.",".$receiver_fp_childs_a;
            $chart1_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer.",".$receiver_fp_childs_a;
            
            $chart1_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart1_income_area, "", 1, "", "0");
            $chart1_income = $this->jedox->cell_export_setarray($chart1_income);
            
            $chart1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area, "", 1, "", "0");
            $chart1_margin = $this->jedox->cell_export_setarray($chart1_margin);
            
            $chart1_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area_b, "", 1, "", "0");
            $chart1_margin_b = $this->jedox->cell_export_setarray($chart1_margin_b);
            
            //combine data for xy chart.
            $chart1_array = array();
            foreach($receiver_fp_childs as $row)
            {
                foreach($chart1_income as $i)
                {
                    if($i['value'] == '')
                    {
                        $i['value'] = 0;
                    }
                    $i_path = explode(",", $i['path']);
                    if($i_path[6] == $row['element'] && $i_path[0] == $version_actual)
                    {
                        $row['Income'] = $i['value'];
                    }
                    if($i_path[6] == $row['element'] && $i_path[0] == $version_target)
                    {
                        $row['Income_t'] = $i['value'];
                    }
                }
                foreach($chart1_margin as $j)
                {
                    if($j['value'] == '')
                    {
                        $j['value'] = 0;
                    }
                    $j_path = explode(",", $j['path']);
                    if($j_path[5] == $row['element'] && $j_path[0] == $version_actual)
                    {
                        $row['Margin'] = $j['value'];
                    }
                    if($j_path[5] == $row['element'] && $j_path[0] == $version_target)
                    {
                        $row['Margin_t'] = $j['value'];
                    }
                }
                foreach($chart1_margin_b as $k)
                {
                    if($k['value'] == '')
                    {
                        $k['value'] = 0;
                    }
                    $k_path = explode(",", $k['path']);
                    if($k_path[5] == $row['element'] && $k_path[0] == $version_actual)
                    {
                        $row['MarginD'] = $k['value'];
                    }
                    if($k_path[5] == $row['element'] && $k_path[0] == $version_target)
                    {
                        $row['MarginD_t'] = $k['value'];
                    }
                }
                $chart1_array[] = $row;
            }
            $chart1_array = $this->jedox->set_alias($chart1_array, $cells_receiver_alias);
            $chart1 = $this->xy_xml($chart1_array, $this->jedox->get_area($receiver_elements, "FP"));
            
            $table1_top10 = $this->gm_variance($chart1_array);
            usort($table1_top10, array($this,'custom_sort_desc_m'));
            $table1_top10_count = count($table1_top10);
            
            $table1_top10 = $this->single_xml_custom_array($table1_top10);
            
            
            ///////////////////////////////////////////////
            // CHART 2 - ACTUAL GROSS MARGIN BY COSTUMER //
            ///////////////////////////////////////////////
            $customer_base = $this->jedox->dimension_elements_base($customer_elements);
            $customer_base_alias = $this->jedox->set_alias($customer_base, $cells_customer_alias);
            $customer_base_area = $this->jedox->get_area($customer_base);
            $chart2_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer_base_area.",".$receiver;
            
            $chart2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart2_margin_area, "", 1, "", "0");
            $chart2_margin = $this->jedox->cell_export_setarray($chart2_margin);
            usort($chart2_margin, array($this,'custom_sort_asc'));
            $chart2 = $this->jedox->singlechart_xml($chart2_margin, $customer_base_alias, 4);
            
            //////////////////////////////////////////////
            // CHART 3 - ACTUAL GROSS MARGIN BY PRODUCT //
            //////////////////////////////////////////////
            $receiver_fp_childs_base = $this->jedox->dimension_elements_base($receiver_fp_childs);
            $receiver_fp_childs_base_a = $this->jedox->get_area($receiver_fp_childs_base);
            $receiver_fp_childs_base_alias = $this->jedox->set_alias($receiver_fp_childs_base, $cells_receiver_alias);
            $chart3_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer.",".$receiver_fp_childs_base_a;
            
            $chart3_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart3_margin_area, "", 1, "", "0");
            $chart3_margin = $this->jedox->cell_export_setarray($chart3_margin);
            usort($chart3_margin, array($this,'custom_sort_asc'));
            $chart3 = $this->jedox->singlechart_xml($chart3_margin, $receiver_fp_childs_base_alias, 5);
            
            //////////////////////////////////////////////
            // CHART 4 - Revenue and Margin by Customer //
            //////////////////////////////////////////////
            $customer_cu_childs = array_merge($this->jedox->array_element_filter($customer_elements, "CU_1"), $this->jedox->array_element_filter($customer_elements, "CU_2"), $this->jedox->array_element_filter($customer_elements, "CU_3"));
            $customer_cu_childs_a = $this->jedox->get_area($customer_cu_childs);
            
            $chart4_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer_cu_childs_a.",".$receiver;
            $chart4_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer_cu_childs_a.",".$receiver;
            $chart4_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer_cu_childs_a.",".$receiver;
            
            $chart4_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart4_income_area, "", 1, "", "0");
            $chart4_income = $this->jedox->cell_export_setarray($chart4_income);
            
            $chart4_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area, "", 1, "", "0");
            $chart4_margin = $this->jedox->cell_export_setarray($chart4_margin);
            
            $chart4_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area_b, "", 1, "", "0");
            $chart4_margin_b = $this->jedox->cell_export_setarray($chart4_margin_b);
            
            $chart4_array = array();
            foreach($customer_cu_childs as $row)
            {
                foreach($chart4_income as $i)
                {
                    if($i['value'] == '')
                    {
                        $i['value'] = 0;
                    }
                    $i_path = explode(",", $i['path']);
                    if($i_path[5] == $row['element'] && $i_path[0] == $version_actual)
                    {
                        $row['Income'] = $i['value'];
                    }
                    if($i_path[5] == $row['element'] && $i_path[0] == $version_target)
                    {
                        $row['Income_t'] = $i['value'];
                    }
                }
                foreach($chart4_margin as $j)
                {
                    if($j['value'] == '')
                    {
                        $j['value'] = 0;
                    }
                    $j_path = explode(",", $j['path']);
                    if($j_path[4] == $row['element'] && $j_path[0] == $version_actual)
                    {
                        $row['Margin'] = $j['value'];
                    }
                    if($j_path[4] == $row['element'] && $j_path[0] == $version_target)
                    {
                        $row['Margin_t'] = $j['value'];
                    }
                }
                foreach($chart4_margin_b as $k)
                {
                    if($k['value'] == '')
                    {
                        $k['value'] = 0;
                    }
                    $k_path = explode(",", $k['path']);
                    if($k_path[4] == $row['element'] && $k_path[0] == $version_actual)
                    {
                        $row['MarginD'] = $k['value'];
                    }
                    if($k_path[4] == $row['element'] && $k_path[0] == $version_target)
                    {
                        $row['MarginD_t'] = $k['value'];
                    }
                }
                $chart4_array[] = $row;
            }
            $chart4_array = $this->jedox->set_alias($chart4_array, $cells_customer_alias);
            $chart4 = $this->xy_xml2($chart4_array, $this->jedox->get_area($customer_elements, "CU"));
            
            $table4_top10 = $this->gm_variance($chart4_array);
            usort($table4_top10, array($this,'custom_sort_desc_m'));
            $table4_top10_count = count($table4_top10);
            $table4_top10 = $this->single_xml_custom_array($table4_top10);
            
            
            ///////////
            // TABLE //
            ///////////
            $version_pac = $this->jedox->get_area($version_elements, "V001,V002,V003"); // Plan,Actual,Target
            $version_pac_elements = $this->jedox->dimension_elements_id($version_elements, "V001,V002,V003");
            $income_value_qty05 = $this->jedox->get_area($income_value_elements, "QTY05"); 
            $income_value_sls04 = $this->jedox->get_area($income_value_elements, "SLS04");
            $income_value_sls03 = $this->jedox->get_area($income_value_elements, "SLS03");
            $income_value_sls02 = $this->jedox->get_area($income_value_elements, "SLS02");
            $income_value_p001 = $this->jedox->get_area($income_value_elements, "P001");
            $margin_pc04 = $this->jedox->get_area($margin_value_elements, "PC04");
            $margin_sc04 = $this->jedox->get_area($margin_value_elements, "SC04");
            $margin_sc03 = $this->jedox->get_area($margin_value_elements, "SC03");
            $margin_scf = $this->jedox->get_area($margin_value_elements, "SCF");
            
            $sq_area = $version_pac.",".$year.",".$month.",".$income_value_qty05.",".$account_element_ce4.",".$customer.",".$receiver;
            $sq_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sq_area, "", 1, "", "0");
            $sq_income = $this->jedox->cell_export_setarray($sq_income);
            $sq = $this->custom_row($sq_income, $version_pac_elements, 0, ''); 
            
            // commented out till filter is added. //
            $sp_area = $version_pac.",".$year.",".$month.",".$income_value_p001.",".$account_element_ce4.",".$customer.",".$receiver;
            $sp_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sp_area, "", 1, "", "0");
            $sp_income = $this->jedox->cell_export_setarray($sp_income);
            $sp = $this->custom_row($sp_income, $version_pac_elements, 0, '$', 0, 1); 
            //$sp = "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
            
            $gr_area = $version_pac.",".$year.",".$month.",".$income_value_sls04.",".$account_element_ce4.",".$customer.",".$receiver;
            $gr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$gr_area, "", 1, "", "0");
            $gr_income = $this->jedox->cell_export_setarray($gr_income);
            $gr = $this->custom_row($gr_income, $version_pac_elements, 0, '$');
            
            $dc_area = $version_pac.",".$year.",".$month.",".$income_value_sls03.",".$account_element_ce4.",".$customer.",".$receiver;
            $dc_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$dc_area, "", 1, "", "0");
            $dc_income = $this->jedox->cell_export_setarray($dc_income);
            $dc = $this->custom_row($dc_income, $version_pac_elements, 0, '$', 1);
            
            $nr_area = $version_pac.",".$year.",".$month.",".$income_value_sls02.",".$account_element_ce4.",".$customer.",".$receiver;
            $nr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$nr_area, "", 1, "", "0");
            $nr_income = $this->jedox->cell_export_setarray($nr_income);
            $nr = $this->custom_row($nr_income, $version_pac_elements, 0, '$');
            
            $rm1_area = $version_pac.",".$year.",".$month.",".$margin_pc04.",".$customer.",".$receiver;
            $rm1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm1_area, "", 1, "", "0");
            $rm1_margin = $this->jedox->cell_export_setarray($rm1_margin);
            $rm2_area = $version_pac.",".$year.",".$month.",".$margin_sc04.",".$customer.",".$receiver;
            $rm2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm2_area, "", 1, "", "0");
            $rm2_margin = $this->jedox->cell_export_setarray($rm2_margin);
            $rm_both = $this->jedox->add_cell_array($rm1_margin, $rm2_margin, 0, 1);
            $rm = $this->custom_row($rm_both, $version_pac_elements, 0, '$', 1);
            
            $pm_margin = $this->jedox->subtract_cell_array($nr_income, $rm_both, 0, 1); 
            $pm = $this->custom_row($pm_margin, $version_pac_elements, 0, '$'); 
            
            $pc_area = $version_pac.",".$year.",".$month.",".$margin_sc03.",".$customer.",".$receiver;
            $pc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$pc_area, "", 1, "", "0");
            $pc_margin = $this->jedox->cell_export_setarray($pc_margin);
            $pc = $this->custom_row($pc_margin, $version_pac_elements, 0, '$', 1);
            
            $cm_margin = $this->jedox->subtract_cell_array($pm_margin, $pc_margin, 0, 1);
            $cm = $this->custom_row($cm_margin, $version_pac_elements, 0, '$');
            
            $fc_area = $version_pac.",".$year.",".$month.",".$margin_scf.",".$customer.",".$receiver;
            $fc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$fc_area, "", 1, "", "0");
            $fc_margin = $this->jedox->cell_export_setarray($fc_margin);
            $fc = $this->custom_row($fc_margin, $version_pac_elements, 0, '$', 1);
            
            $gm_margin = $this->jedox->subtract_cell_array($cm_margin, $fc_margin, 0, 1);
            $gm = $this->custom_row($gm_margin, $version_pac_elements, 0, '$');
            
            //////////////////////
            // WATERFALL CHARTS //
            //////////////////////
            
            $chart5 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_actual);
            //$chart6 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_target);
            //$chart7 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_plan);
            
            $chart5 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_actual);
            //$chart6 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_target);
            //$chart7 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_plan);
            
            $chart5 .= "<set label='Net Revenue' isSum='1'></set>";
            //$chart6 .= "<set label='Net Revenue' isSum='1'></set>";
            //$chart7 .= "<set label='Net Revenue' isSum='1'></set>";
            
            $chart5 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_actual);
            //$chart6 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_target);
            //$chart7 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_plan);
            
            $chart5 .= "<set label='Product Margin' isSum='1'></set>";
            //$chart6 .= "<set label='Product Margin' isSum='1'></set>";
            //$chart7 .= "<set label='Product Margin' isSum='1'></set>";
            
            $chart5 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_actual);
            //$chart6 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_target);
            //$chart7 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_plan);
            
            $chart5 .= "<set label='Contribution Margin' isSum='1'></set>";
            //$chart6 .= "<set label='Contribution Margin' isSum='1'></set>";
            //$chart7 .= "<set label='Contribution Margin' isSum='1'></set>";
            
            $chart5 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_actual);
            //$chart6 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_target);
            //$chart7 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_plan);
            
            $chart5 .= "<set label='Gross Margin' isSum='1'></set>";
            
            /////////////
            // CHART 8 //
            /////////////
            
            $chart8 .= $this->single_xml_custom("Gross Revenue", $gr_income, $version_actual, $version_target);
            $chart8 .= $this->single_xml_custom("Discounts", $dc_income, $version_actual, $version_target);
            $chart8 .= $this->single_xml_custom("Net Revenue", $nr_income, $version_actual, $version_target);
            $chart8 .= $this->single_xml_custom("Raw Material", $rm_both, $version_actual, $version_target);
            $chart8 .= $this->single_xml_custom("Product Margin", $pm_margin, $version_actual, $version_target);
            $chart8 .= $this->single_xml_custom("Proportional Cost", $pc_margin, $version_actual, $version_target);
            $chart8 .= $this->single_xml_custom("Contribution Margin", $cm_margin, $version_actual, $version_target);
            $chart8 .= $this->single_xml_custom("Fixed Cost", $fc_margin, $version_actual, $version_target);
            $chart8 .= $this->single_xml_custom("Gross Margin", $gm_margin, $version_actual, $version_target);
            
            
            //percentages
            
            $p_pm = $this->jedox->percentage_cell_array($pm_margin, $nr_income, 0, 1);
            $p_pm = $this->custom_row_p($p_pm, $version_pac_elements, 0, '%');
            
            $p_cm = $this->jedox->percentage_cell_array($cm_margin, $nr_income, 0, 1);
            $p_cm = $this->custom_row_p($p_cm, $version_pac_elements, 0, '%');
            
            $p_gm = $this->jedox->percentage_cell_array($gm_margin, $nr_income, 0, 1);
            $p_gm = $this->custom_row_p($p_gm, $version_pac_elements, 0, '%');
            
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "year" => $year,
                "month" => $month,
                "receiver" => $receiver,
                "customer" => $customer,
                "form_year" => $form_year,
                "form_months" => $form_months,
                "form_receiver" => $form_receiver,
                "form_customer" => $form_customer,
                "chart1" => $chart1,
                "chart2" => $chart2,
                "chart3" => $chart3,
                "chart4" => $chart4,
                "chart5" => $chart5,
                //"chart6" => $chart6,
                //"chart7" => $chart7,
                "chart8" => $chart8,
                "sq" => $sq,
                "sp" => $sp,
                "gr" => $gr,
                "dc" => $dc,
                "nr" => $nr,
                "rm" => $rm,
                "pm" => $pm,
                "pc" => $pc,
                "cm" => $cm,
                "fc" => $fc,
                "gm" => $gm,
                "p_pm" => $p_pm,
                "p_cm" => $p_cm,
                "p_gm" => $p_gm,
                "table1_top10" => $table1_top10,
                "table4_top10" => $table4_top10,
                "table1_top10_count" => $table1_top10_count,
                "table4_top10_count" => $table4_top10_count,
                "jedox_user_details" => $this->session->userdata('jedox_user_details'),
                "pagename" => $pagename,
                "oneliner" => $oneliner
                //trace vars here 
                //"chart1_array" => $chart1_array
                
            );
            // Pass data and show view
            $this->load->view("profitability_view", $alldata);
        
        }// end of login check else.
    }   
    
    public function chart1()
    {
        $pagename = "proEO Profitability";
        $chart_name = "Revenue and Margin Per Product";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "profitability") == FALSE)
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
            $cube_names = "Income,Margin,#_Version,#_Month,#_Income_Value,#_Account_Element,#_Receiver,#_Customer,#_Margin_Value";
            // Chart containers
            $chart1 = "";
            // Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $receiver = $this->input->post("receiver");
            $customer = $this->input->post("customer");
            
            // Login. need to relogin to prevent timeout
            $server_login = $this->jedoxapi->server_login($this->session->userdata('jedox_user'), $this->session->userdata('jedox_pass'));
            
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
            $margin_dimension_id = $this->jedox->get_dimension_id($database_cubes, "Margin");
            
            $income_cube_info = $this->jedox->get_cube_data($database_cubes, "Income");
            $margin_cube_info = $this->jedox->get_cube_data($database_cubes, "Margin");
            
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
			
			// MARGIN VALUE //
			// Get dimension of margin_value
			$margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[3]);
			// Get cube data of receiver alias
			$margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
			$margin_value_alias_info = $this->jedox->get_cube_data($database_cubes, "#_Margin_Value");
			// Export cells of receiver alias
			$margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
			$cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*"); 
            
            // FORM DATA //
            $form_year = $year_elements;
            
            //$form_months = $this->jedox->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedox->set_alias($month_elements, $cells_month_alias); // Set aliases
            
            $receiver_fp_cont = $this->jedox->array_element_filter($receiver_elements, "FP"); // Finished Products
            $form_receiver = $this->jedox->set_alias($receiver_fp_cont, $cells_receiver_alias); 
            
            $form_customer = $this->jedox->set_alias($customer_elements, $cells_customer_alias);
            
            /////////////
            // PRESETS //
            /////////////
            
            if($year == '')
            {
                $now = now();
                $tnow = mdate("%Y", $now);
                $year = $this->jedox->get_area($year_elements, $tnow);
            }
            if($month == '')
            {
                $month = $this->jedox->get_area($month_elements, "MA"); // All Months
            }
            if($receiver == '')
            {
                $receiver = $this->jedox->get_area($receiver_elements, "FP"); // All Products
            }
            if($customer == '')
            {
                $customer = $this->jedox->get_area($customer_elements, "CU"); 
            }
            
            ////////////
            // CHARTS //
            ////////////
            
            $version_actual = $this->jedox->get_area($version_elements, "V002"); // Actual
            $version_target = $this->jedox->get_area($version_elements, "V003"); // Target
            $version_plan = $this->jedox->get_area($version_elements, "V001"); // Plan
            $version_at = $this->jedox->get_area($version_elements, "V002,V003"); // Actual,Target
            $income_value_sls = $this->jedox->get_area($income_value_elements, "SLS"); // Income
            $account_element_ce4 = $this->jedox->get_area($account_element_elements, "CE_4");
            $customer_cu = $this->jedox->get_area($customer_elements, "CU");
            $margin_mg03 = $this->jedox->get_area($margin_value_elements, "MG03");
            $margin_mg04 = $this->jedox->get_area($margin_value_elements, "MG04"); 
            $margin_mg04_perc = $this->jedox->get_area($margin_value_elements, "MG04%");
            $receiver_fp_childs = array_merge($this->jedox->array_element_filter($receiver_elements, "FP_100"), $this->jedox->array_element_filter($receiver_elements, "FP_200"), $this->jedox->array_element_filter($receiver_elements, "FP_300"), $this->jedox->array_element_filter($receiver_elements, "FP_400"));
            $receiver_fp_childs_a = $this->jedox->get_area($receiver_fp_childs);
            
            
            /////////////////////////////////////////////
            // CHART 1 - Revenue and Margin per Product //
            /////////////////////////////////////////////
            
            $chart1_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer.",".$receiver_fp_childs_a;
            $chart1_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer.",".$receiver_fp_childs_a;
            $chart1_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer.",".$receiver_fp_childs_a;
            
            $chart1_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart1_income_area, "", 1, "", "0");
            $chart1_income = $this->jedox->cell_export_setarray($chart1_income);
            
            $chart1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area, "", 1, "", "0");
            $chart1_margin = $this->jedox->cell_export_setarray($chart1_margin);
            
            $chart1_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area_b, "", 1, "", "0");
            $chart1_margin_b = $this->jedox->cell_export_setarray($chart1_margin_b);
            
            //combine data for xy chart.
            $chart1_array = array();
            foreach($receiver_fp_childs as $row)
            {
                foreach($chart1_income as $i)
                {
                    if($i['value'] == '')
                    {
                        $i['value'] = 0;
                    }
                    $i_path = explode(",", $i['path']);
                    if($i_path[6] == $row['element'] && $i_path[0] == $version_actual)
                    {
                        $row['Income'] = $i['value'];
                    }
                    if($i_path[6] == $row['element'] && $i_path[0] == $version_target)
                    {
                        $row['Income_t'] = $i['value'];
                    }
                }
                foreach($chart1_margin as $j)
                {
                    if($j['value'] == '')
                    {
                        $j['value'] = 0;
                    }
                    $j_path = explode(",", $j['path']);
                    if($j_path[5] == $row['element'] && $j_path[0] == $version_actual)
                    {
                        $row['Margin'] = $j['value'];
                    }
                    if($j_path[5] == $row['element'] && $j_path[0] == $version_target)
                    {
                        $row['Margin_t'] = $j['value'];
                    }
                }
                foreach($chart1_margin_b as $k)
                {
                    if($k['value'] == '')
                    {
                        $k['value'] = 0;
                    }
                    $k_path = explode(",", $k['path']);
                    if($k_path[5] == $row['element'] && $k_path[0] == $version_actual)
                    {
                        $row['MarginD'] = $k['value'];
                    }
                    if($k_path[5] == $row['element'] && $k_path[0] == $version_target)
                    {
                        $row['MarginD_t'] = $k['value'];
                    }
                }
                $chart1_array[] = $row;
            }
            $chart1_array = $this->jedox->set_alias($chart1_array, $cells_receiver_alias);
            $chart1 = $this->xy_xml($chart1_array, $this->jedox->get_area($receiver_elements, "FP"));
            
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "year" => $year,
                "month" => $month,
                "receiver" => $receiver,
                "customer" => $customer,
                "form_year" => $form_year,
                "form_months" => $form_months,
                "form_receiver" => $form_receiver,
                "form_customer" => $form_customer,
                "chart1" => $chart1,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "chart_name" => $chart_name
                
            );
            // Pass data and show view
            $this->load->view("profitability/chart1_view", $alldata);
            
        }
            
    }

	public function chart9()
	{
		$pagename = "proEO Profitability";
		$chart_name = "Revenue and Margin Per Product Ranking";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedox->page_permission($user_details['group_names'], "profitability") == FALSE)
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
			$cube_names = "Income,Margin,#_Version,#_Month,#_Income_Value,#_Account_Element,#_Receiver,#_Customer,#_Margin_Value";
			// Chart containers
			$chart1 = "";
			$chart2 = "";
			$chart3 = "";
			$chart4 = "";
			$chart5 = "";
			$chart6 = "";
			$chart7 = "";
			$chart8 = "";
			// Initialize post data //
			$year = $this->input->post("year");
			$month = $this->input->post("month");
			$receiver = $this->input->post("receiver");
			$customer = $this->input->post("customer");
			
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
			$margin_dimension_id = $this->jedox->get_dimension_id($database_cubes, "Margin");
			
			$income_cube_info = $this->jedox->get_cube_data($database_cubes, "Income");
			$margin_cube_info = $this->jedox->get_cube_data($database_cubes, "Margin");
			
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
			
			// MARGIN VALUE //
			// Get dimension of margin_value
			$margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[3]);
			// Get cube data of receiver alias
			$margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
			$margin_value_alias_info = $this->jedox->get_cube_data($database_cubes, "#_Margin_Value");
			// Export cells of receiver alias
			$margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
			$cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*"); 
			
			// FORM DATA //
			$form_year = $year_elements;
			
			//$form_months = $this->jedox->array_element_filter($month_elements, "MA"); // All Months
			$form_months = $this->jedox->set_alias($month_elements, $cells_month_alias); // Set aliases
			
			$receiver_fp_cont = $this->jedox->array_element_filter($receiver_elements, "FP"); // Finished Products
			$form_receiver = $this->jedox->set_alias($receiver_fp_cont, $cells_receiver_alias); 
			
			$form_customer = $this->jedox->set_alias($customer_elements, $cells_customer_alias);
			
			/////////////
			// PRESETS //
			/////////////
			
			if($year == '')
			{
				$now = now();
				$tnow = mdate("%Y", $now);
				$year = $this->jedox->get_area($year_elements, $tnow);
			}
			if($month == '')
			{
				$month = $this->jedox->get_area($month_elements, "MA"); // All Months
			}
			if($receiver == '')
			{
				$receiver = $this->jedox->get_area($receiver_elements, "FP"); // All Products
			}
			if($customer == '')
			{
				$customer = $this->jedox->get_area($customer_elements, "CU"); 
			}
			
			////////////
			// CHARTS //
			////////////
			
			$version_actual = $this->jedox->get_area($version_elements, "V002"); // Actual
			$version_target = $this->jedox->get_area($version_elements, "V003"); // Target
			$version_plan = $this->jedox->get_area($version_elements, "V001"); // Plan
			$version_at = $this->jedox->get_area($version_elements, "V002,V003"); // Actual,Target
			$income_value_sls = $this->jedox->get_area($income_value_elements, "SLS"); // Income
			$account_element_ce4 = $this->jedox->get_area($account_element_elements, "CE_4");
			$customer_cu = $this->jedox->get_area($customer_elements, "CU");
			$margin_mg03 = $this->jedox->get_area($margin_value_elements, "MG03");
			$margin_mg04 = $this->jedox->get_area($margin_value_elements, "MG04"); 
			$margin_mg04_perc = $this->jedox->get_area($margin_value_elements, "MG04%");
			$receiver_fp_childs = array_merge($this->jedox->array_element_filter($receiver_elements, "FP_100"), $this->jedox->array_element_filter($receiver_elements, "FP_200"), $this->jedox->array_element_filter($receiver_elements, "FP_300"), $this->jedox->array_element_filter($receiver_elements, "FP_400"));
			$receiver_fp_childs_a = $this->jedox->get_area($receiver_fp_childs);
			
			
			/////////////////////////////////////////////
			// CHART 1 - Revenue and Margin per Product //
			/////////////////////////////////////////////
			
			$chart1_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer.",".$receiver_fp_childs_a;
			$chart1_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer.",".$receiver_fp_childs_a;
			$chart1_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer.",".$receiver_fp_childs_a;
			
			$chart1_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart1_income_area, "", 1, "", "0");
			$chart1_income = $this->jedox->cell_export_setarray($chart1_income);
			
			$chart1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area, "", 1, "", "0");
			$chart1_margin = $this->jedox->cell_export_setarray($chart1_margin);
			
			$chart1_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area_b, "", 1, "", "0");
			$chart1_margin_b = $this->jedox->cell_export_setarray($chart1_margin_b);
			
			//combine data for xy chart.
			$chart1_array = array();
			foreach($receiver_fp_childs as $row)
			{
				foreach($chart1_income as $i)
				{
					if($i['value'] == '')
					{
						$i['value'] = 0;
					}
					$i_path = explode(",", $i['path']);
					if($i_path[6] == $row['element'] && $i_path[0] == $version_actual)
					{
						$row['Income'] = $i['value'];
					}
					if($i_path[6] == $row['element'] && $i_path[0] == $version_target)
					{
						$row['Income_t'] = $i['value'];
					}
				}
				foreach($chart1_margin as $j)
				{
					if($j['value'] == '')
					{
						$j['value'] = 0;
					}
					$j_path = explode(",", $j['path']);
					if($j_path[5] == $row['element'] && $j_path[0] == $version_actual)
					{
						$row['Margin'] = $j['value'];
					}
					if($j_path[5] == $row['element'] && $j_path[0] == $version_target)
					{
						$row['Margin_t'] = $j['value'];
					}
				}
				foreach($chart1_margin_b as $k)
				{
					if($k['value'] == '')
					{
						$k['value'] = 0;
					}
					$k_path = explode(",", $k['path']);
					if($k_path[5] == $row['element'] && $k_path[0] == $version_actual)
					{
						$row['MarginD'] = $k['value'];
					}
					if($k_path[5] == $row['element'] && $k_path[0] == $version_target)
					{
						$row['MarginD_t'] = $k['value'];
					}
				}
				$chart1_array[] = $row;
			}
			$chart1_array = $this->jedox->set_alias($chart1_array, $cells_receiver_alias);
			$chart1 = $this->xy_xml($chart1_array, $this->jedox->get_area($receiver_elements, "FP"));
			
			$table1_top10 = $this->gm_variance($chart1_array);
			usort($table1_top10, array($this,'custom_sort_desc_m'));
			$table1_top10_count = count($table1_top10);
			
			$table1_top10 = $this->single_xml_custom_array($table1_top10);
			
			
			///////////////////////////////////////////////
			// CHART 2 - ACTUAL GROSS MARGIN BY COSTUMER //
			///////////////////////////////////////////////
			$customer_base = $this->jedox->dimension_elements_base($customer_elements);
			$customer_base_alias = $this->jedox->set_alias($customer_base, $cells_customer_alias);
			$customer_base_area = $this->jedox->get_area($customer_base);
			$chart2_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer_base_area.",".$receiver;
			
			$chart2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart2_margin_area, "", 1, "", "0");
			$chart2_margin = $this->jedox->cell_export_setarray($chart2_margin);
			usort($chart2_margin, array($this,'custom_sort_asc'));
			$chart2 = $this->jedox->singlechart_xml($chart2_margin, $customer_base_alias, 4);
			
			//////////////////////////////////////////////
			// CHART 3 - ACTUAL GROSS MARGIN BY PRODUCT //
			//////////////////////////////////////////////
			$receiver_fp_childs_base = $this->jedox->dimension_elements_base($receiver_fp_childs);
			$receiver_fp_childs_base_a = $this->jedox->get_area($receiver_fp_childs_base);
			$receiver_fp_childs_base_alias = $this->jedox->set_alias($receiver_fp_childs_base, $cells_receiver_alias);
			$chart3_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer.",".$receiver_fp_childs_base_a;
			
			$chart3_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart3_margin_area, "", 1, "", "0");
			$chart3_margin = $this->jedox->cell_export_setarray($chart3_margin);
			usort($chart3_margin, array($this,'custom_sort_asc'));
			$chart3 = $this->jedox->singlechart_xml($chart3_margin, $receiver_fp_childs_base_alias, 5);
			
			//////////////////////////////////////////////
			// CHART 4 - Revenue and Margin by Customer //
			//////////////////////////////////////////////
			$customer_cu_childs = array_merge($this->jedox->array_element_filter($customer_elements, "CU_1"), $this->jedox->array_element_filter($customer_elements, "CU_2"), $this->jedox->array_element_filter($customer_elements, "CU_3"));
			$customer_cu_childs_a = $this->jedox->get_area($customer_cu_childs);
			
			$chart4_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer_cu_childs_a.",".$receiver;
			$chart4_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer_cu_childs_a.",".$receiver;
			$chart4_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer_cu_childs_a.",".$receiver;
			
			$chart4_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart4_income_area, "", 1, "", "0");
			$chart4_income = $this->jedox->cell_export_setarray($chart4_income);
			
			$chart4_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area, "", 1, "", "0");
			$chart4_margin = $this->jedox->cell_export_setarray($chart4_margin);
			
			$chart4_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area_b, "", 1, "", "0");
			$chart4_margin_b = $this->jedox->cell_export_setarray($chart4_margin_b);
			
			$chart4_array = array();
			foreach($customer_cu_childs as $row)
			{
				foreach($chart4_income as $i)
				{
					if($i['value'] == '')
					{
						$i['value'] = 0;
					}
					$i_path = explode(",", $i['path']);
					if($i_path[5] == $row['element'] && $i_path[0] == $version_actual)
					{
						$row['Income'] = $i['value'];
					}
					if($i_path[5] == $row['element'] && $i_path[0] == $version_target)
					{
						$row['Income_t'] = $i['value'];
					}
				}
				foreach($chart4_margin as $j)
				{
					if($j['value'] == '')
					{
						$j['value'] = 0;
					}
					$j_path = explode(",", $j['path']);
					if($j_path[4] == $row['element'] && $j_path[0] == $version_actual)
					{
						$row['Margin'] = $j['value'];
					}
					if($j_path[4] == $row['element'] && $j_path[0] == $version_target)
					{
						$row['Margin_t'] = $j['value'];
					}
				}
				foreach($chart4_margin_b as $k)
				{
					if($k['value'] == '')
					{
						$k['value'] = 0;
					}
					$k_path = explode(",", $k['path']);
					if($k_path[4] == $row['element'] && $k_path[0] == $version_actual)
					{
						$row['MarginD'] = $k['value'];
					}
					if($k_path[4] == $row['element'] && $k_path[0] == $version_target)
					{
						$row['MarginD_t'] = $k['value'];
					}
				}
				$chart4_array[] = $row;
			}
			$chart4_array = $this->jedox->set_alias($chart4_array, $cells_customer_alias);
			$chart4 = $this->xy_xml2($chart4_array, $this->jedox->get_area($customer_elements, "CU"));
			
			$table4_top10 = $this->gm_variance($chart4_array);
			usort($table4_top10, array($this,'custom_sort_desc_m'));
			$table4_top10_count = count($table4_top10);
			$table4_top10 = $this->single_xml_custom_array($table4_top10);
			
			
			///////////
			// TABLE //
			///////////
			$version_pac = $this->jedox->get_area($version_elements, "V001,V002,V003"); // Plan,Actual,Target
			$version_pac_elements = $this->jedox->dimension_elements_id($version_elements, "V001,V002,V003");
			$income_value_qty05 = $this->jedox->get_area($income_value_elements, "QTY05"); 
			$income_value_sls04 = $this->jedox->get_area($income_value_elements, "SLS04");
			$income_value_sls03 = $this->jedox->get_area($income_value_elements, "SLS03");
			$income_value_sls02 = $this->jedox->get_area($income_value_elements, "SLS02");
			$income_value_p001 = $this->jedox->get_area($income_value_elements, "P001");
			$margin_pc04 = $this->jedox->get_area($margin_value_elements, "PC04");
			$margin_sc04 = $this->jedox->get_area($margin_value_elements, "SC04");
			$margin_sc03 = $this->jedox->get_area($margin_value_elements, "SC03");
			$margin_scf = $this->jedox->get_area($margin_value_elements, "SCF");
			
			$sq_area = $version_pac.",".$year.",".$month.",".$income_value_qty05.",".$account_element_ce4.",".$customer.",".$receiver;
			$sq_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sq_area, "", 1, "", "0");
			$sq_income = $this->jedox->cell_export_setarray($sq_income);
			$sq = $this->custom_row($sq_income, $version_pac_elements, 0, ''); 
			
			// commented out till filter is added. //
			$sp_area = $version_pac.",".$year.",".$month.",".$income_value_p001.",".$account_element_ce4.",".$customer.",".$receiver;
			$sp_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sp_area, "", 1, "", "0");
			$sp_income = $this->jedox->cell_export_setarray($sp_income);
			$sp = $this->custom_row($sp_income, $version_pac_elements, 0, '$', 0, 1); 
			//$sp = "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			
			$gr_area = $version_pac.",".$year.",".$month.",".$income_value_sls04.",".$account_element_ce4.",".$customer.",".$receiver;
			$gr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$gr_area, "", 1, "", "0");
			$gr_income = $this->jedox->cell_export_setarray($gr_income);
			$gr = $this->custom_row($gr_income, $version_pac_elements, 0, '$');
			
			$dc_area = $version_pac.",".$year.",".$month.",".$income_value_sls03.",".$account_element_ce4.",".$customer.",".$receiver;
			$dc_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$dc_area, "", 1, "", "0");
			$dc_income = $this->jedox->cell_export_setarray($dc_income);
			$dc = $this->custom_row($dc_income, $version_pac_elements, 0, '$', 1);
			
			$nr_area = $version_pac.",".$year.",".$month.",".$income_value_sls02.",".$account_element_ce4.",".$customer.",".$receiver;
			$nr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$nr_area, "", 1, "", "0");
			$nr_income = $this->jedox->cell_export_setarray($nr_income);
			$nr = $this->custom_row($nr_income, $version_pac_elements, 0, '$');
			
			$rm1_area = $version_pac.",".$year.",".$month.",".$margin_pc04.",".$customer.",".$receiver;
			$rm1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm1_area, "", 1, "", "0");
			$rm1_margin = $this->jedox->cell_export_setarray($rm1_margin);
			$rm2_area = $version_pac.",".$year.",".$month.",".$margin_sc04.",".$customer.",".$receiver;
			$rm2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm2_area, "", 1, "", "0");
			$rm2_margin = $this->jedox->cell_export_setarray($rm2_margin);
			$rm_both = $this->jedox->add_cell_array($rm1_margin, $rm2_margin, 0, 1);
			$rm = $this->custom_row($rm_both, $version_pac_elements, 0, '$', 1);
			
			$pm_margin = $this->jedox->subtract_cell_array($nr_income, $rm_both, 0, 1);
			$pm = $this->custom_row($pm_margin, $version_pac_elements, 0, '$'); 
			
			$pc_area = $version_pac.",".$year.",".$month.",".$margin_sc03.",".$customer.",".$receiver;
			$pc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$pc_area, "", 1, "", "0");
			$pc_margin = $this->jedox->cell_export_setarray($pc_margin);
			$pc = $this->custom_row($pc_margin, $version_pac_elements, 0, '$', 1);
			
			$cm_margin = $this->jedox->subtract_cell_array($pm_margin, $pc_margin, 0, 1);
			$cm = $this->custom_row($cm_margin, $version_pac_elements, 0, '$');
			
			$fc_area = $version_pac.",".$year.",".$month.",".$margin_scf.",".$customer.",".$receiver;
			$fc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$fc_area, "", 1, "", "0");
			$fc_margin = $this->jedox->cell_export_setarray($fc_margin);
			$fc = $this->custom_row($fc_margin, $version_pac_elements, 0, '$', 1);
			
			$gm_margin = $this->jedox->subtract_cell_array($cm_margin, $fc_margin, 0, 1);
			$gm = $this->custom_row($gm_margin, $version_pac_elements, 0, '$');
			
			//////////////////////
			// WATERFALL CHARTS //
			//////////////////////
			
			$chart5 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_plan);
			
			$chart5 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Net Revenue' isSum='1'></set>";
			//$chart6 .= "<set label='Net Revenue' isSum='1'></set>";
			//$chart7 .= "<set label='Net Revenue' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Product Margin' isSum='1'></set>";
			//$chart6 .= "<set label='Product Margin' isSum='1'></set>";
			//$chart7 .= "<set label='Product Margin' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Contribution Margin' isSum='1'></set>";
			//$chart6 .= "<set label='Contribution Margin' isSum='1'></set>";
			//$chart7 .= "<set label='Contribution Margin' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Gross Margin' isSum='1'></set>";
			
			/////////////
			// CHART 8 //
			/////////////
			
			$chart8 .= $this->single_xml_custom("Gross Revenue", $gr_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Discounts", $dc_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Net Revenue", $nr_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Raw Material", $rm_both, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Product Margin", $pm_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Proportional Cost", $pc_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Contribution Margin", $cm_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Fixed Cost", $fc_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Gross Margin", $gm_margin, $version_actual, $version_target);
			
			
			//percentages
			
			$p_pm = $this->jedox->percentage_cell_array($pm_margin, $nr_income, 0, 1);
			$p_pm = $this->custom_row_p($p_pm, $version_pac_elements, 0, '%');
			
			$p_cm = $this->jedox->percentage_cell_array($cm_margin, $nr_income, 0, 1);
			$p_cm = $this->custom_row_p($p_cm, $version_pac_elements, 0, '%');
			
			$p_gm = $this->jedox->percentage_cell_array($gm_margin, $nr_income, 0, 1);
			$p_gm = $this->custom_row_p($p_gm, $version_pac_elements, 0, '%');
			
			// Pass all data to send to view file
			$alldata = array(
				//regular vars here
				"year" => $year,
				"month" => $month,
				"receiver" => $receiver,
				"customer" => $customer,
				"form_year" => $form_year,
				"form_months" => $form_months,
				"form_receiver" => $form_receiver,
				"form_customer" => $form_customer,
				"chart1" => $chart1,
				"chart2" => $chart2,
				"chart3" => $chart3,
				"chart4" => $chart4,
				"chart5" => $chart5,
				//"chart6" => $chart6,
				//"chart7" => $chart7,
				"chart8" => $chart8,
				"sq" => $sq,
				"sp" => $sp,
				"gr" => $gr,
				"dc" => $dc,
				"nr" => $nr,
				"rm" => $rm,
				"pm" => $pm,
				"pc" => $pc,
				"cm" => $cm,
				"fc" => $fc,
				"gm" => $gm,
				"p_pm" => $p_pm,
				"p_cm" => $p_cm,
				"p_gm" => $p_gm,
				"table1_top10" => $table1_top10,
				"table4_top10" => $table4_top10,
				"table1_top10_count" => $table1_top10_count,
				"table4_top10_count" => $table4_top10_count,
				"jedox_user_details" => $this->session->userdata('jedox_user_details'),
				"pagename" => $pagename,
				"chart_name" => $chart_name
				//trace vars here 
				//"chart1_array" => $chart1_array
				
			);
			// Pass data and show view
			$this->load->view("profitability/chart9_view", $alldata);
		
		}// end of login check else.
	}
    
    public function chart10()
	{
		$pagename = "proEO Profitability";
		$chart_name = "Revenue and Margin Per Customer Ranking";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedox->page_permission($user_details['group_names'], "profitability") == FALSE)
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
			$cube_names = "Income,Margin,#_Version,#_Month,#_Income_Value,#_Account_Element,#_Receiver,#_Customer,#_Margin_Value";
			// Chart containers
			$chart1 = "";
			$chart2 = "";
			$chart3 = "";
			$chart4 = "";
			$chart5 = "";
			$chart6 = "";
			$chart7 = "";
			$chart8 = "";
			// Initialize post data //
			$year = $this->input->post("year");
			$month = $this->input->post("month");
			$receiver = $this->input->post("receiver");
			$customer = $this->input->post("customer");
			
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
			$margin_dimension_id = $this->jedox->get_dimension_id($database_cubes, "Margin");
			
			$income_cube_info = $this->jedox->get_cube_data($database_cubes, "Income");
			$margin_cube_info = $this->jedox->get_cube_data($database_cubes, "Margin");
			
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
			
			// MARGIN VALUE //
			// Get dimension of margin_value
			$margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[3]);
			// Get cube data of receiver alias
			$margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
			$margin_value_alias_info = $this->jedox->get_cube_data($database_cubes, "#_Margin_Value");
			// Export cells of receiver alias
			$margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
			$cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*"); 
			
			// FORM DATA //
			$form_year = $year_elements;
			
			//$form_months = $this->jedox->array_element_filter($month_elements, "MA"); // All Months
			$form_months = $this->jedox->set_alias($month_elements, $cells_month_alias); // Set aliases
			
			$receiver_fp_cont = $this->jedox->array_element_filter($receiver_elements, "FP"); // Finished Products
			$form_receiver = $this->jedox->set_alias($receiver_fp_cont, $cells_receiver_alias); 
			
			$form_customer = $this->jedox->set_alias($customer_elements, $cells_customer_alias);
			
			/////////////
			// PRESETS //
			/////////////
			
			if($year == '')
			{
				$now = now();
				$tnow = mdate("%Y", $now);
				$year = $this->jedox->get_area($year_elements, $tnow);
			}
			if($month == '')
			{
				$month = $this->jedox->get_area($month_elements, "MA"); // All Months
			}
			if($receiver == '')
			{
				$receiver = $this->jedox->get_area($receiver_elements, "FP"); // All Products
			}
			if($customer == '')
			{
				$customer = $this->jedox->get_area($customer_elements, "CU"); 
			}
			
			////////////
			// CHARTS //
			////////////
			
			$version_actual = $this->jedox->get_area($version_elements, "V002"); // Actual
			$version_target = $this->jedox->get_area($version_elements, "V003"); // Target
			$version_plan = $this->jedox->get_area($version_elements, "V001"); // Plan
			$version_at = $this->jedox->get_area($version_elements, "V002,V003"); // Actual,Target
			$income_value_sls = $this->jedox->get_area($income_value_elements, "SLS"); // Income
			$account_element_ce4 = $this->jedox->get_area($account_element_elements, "CE_4");
			$customer_cu = $this->jedox->get_area($customer_elements, "CU");
			$margin_mg03 = $this->jedox->get_area($margin_value_elements, "MG03");
			$margin_mg04 = $this->jedox->get_area($margin_value_elements, "MG04"); 
			$margin_mg04_perc = $this->jedox->get_area($margin_value_elements, "MG04%");
			$receiver_fp_childs = array_merge($this->jedox->array_element_filter($receiver_elements, "FP_100"), $this->jedox->array_element_filter($receiver_elements, "FP_200"), $this->jedox->array_element_filter($receiver_elements, "FP_300"), $this->jedox->array_element_filter($receiver_elements, "FP_400"));
			$receiver_fp_childs_a = $this->jedox->get_area($receiver_fp_childs);
			
			
			/////////////////////////////////////////////
			// CHART 1 - Revenue and Margin per Product //
			/////////////////////////////////////////////
			
			$chart1_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer.",".$receiver_fp_childs_a;
			$chart1_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer.",".$receiver_fp_childs_a;
			$chart1_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer.",".$receiver_fp_childs_a;
			
			$chart1_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart1_income_area, "", 1, "", "0");
			$chart1_income = $this->jedox->cell_export_setarray($chart1_income);
			
			$chart1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area, "", 1, "", "0");
			$chart1_margin = $this->jedox->cell_export_setarray($chart1_margin);
			
			$chart1_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area_b, "", 1, "", "0");
			$chart1_margin_b = $this->jedox->cell_export_setarray($chart1_margin_b);
			
			//combine data for xy chart.
			$chart1_array = array();
			foreach($receiver_fp_childs as $row)
			{
				foreach($chart1_income as $i)
				{
					if($i['value'] == '')
					{
						$i['value'] = 0;
					}
					$i_path = explode(",", $i['path']);
					if($i_path[6] == $row['element'] && $i_path[0] == $version_actual)
					{
						$row['Income'] = $i['value'];
					}
					if($i_path[6] == $row['element'] && $i_path[0] == $version_target)
					{
						$row['Income_t'] = $i['value'];
					}
				}
				foreach($chart1_margin as $j)
				{
					if($j['value'] == '')
					{
						$j['value'] = 0;
					}
					$j_path = explode(",", $j['path']);
					if($j_path[5] == $row['element'] && $j_path[0] == $version_actual)
					{
						$row['Margin'] = $j['value'];
					}
					if($j_path[5] == $row['element'] && $j_path[0] == $version_target)
					{
						$row['Margin_t'] = $j['value'];
					}
				}
				foreach($chart1_margin_b as $k)
				{
					if($k['value'] == '')
					{
						$k['value'] = 0;
					}
					$k_path = explode(",", $k['path']);
					if($k_path[5] == $row['element'] && $k_path[0] == $version_actual)
					{
						$row['MarginD'] = $k['value'];
					}
					if($k_path[5] == $row['element'] && $k_path[0] == $version_target)
					{
						$row['MarginD_t'] = $k['value'];
					}
				}
				$chart1_array[] = $row;
			}
			$chart1_array = $this->jedox->set_alias($chart1_array, $cells_receiver_alias);
			$chart1 = $this->xy_xml($chart1_array, $this->jedox->get_area($receiver_elements, "FP"));
			
			$table1_top10 = $this->gm_variance($chart1_array);
			usort($table1_top10, array($this,'custom_sort_desc_m'));
			$table1_top10_count = count($table1_top10);
			
			$table1_top10 = $this->single_xml_custom_array($table1_top10);
			
			
			///////////////////////////////////////////////
			// CHART 2 - ACTUAL GROSS MARGIN BY COSTUMER //
			///////////////////////////////////////////////
			$customer_base = $this->jedox->dimension_elements_base($customer_elements);
			$customer_base_alias = $this->jedox->set_alias($customer_base, $cells_customer_alias);
			$customer_base_area = $this->jedox->get_area($customer_base);
			$chart2_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer_base_area.",".$receiver;
			
			$chart2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart2_margin_area, "", 1, "", "0");
			$chart2_margin = $this->jedox->cell_export_setarray($chart2_margin);
			usort($chart2_margin, array($this,'custom_sort_asc'));
			$chart2 = $this->jedox->singlechart_xml($chart2_margin, $customer_base_alias, 4);
			
			//////////////////////////////////////////////
			// CHART 3 - ACTUAL GROSS MARGIN BY PRODUCT //
			//////////////////////////////////////////////
			$receiver_fp_childs_base = $this->jedox->dimension_elements_base($receiver_fp_childs);
			$receiver_fp_childs_base_a = $this->jedox->get_area($receiver_fp_childs_base);
			$receiver_fp_childs_base_alias = $this->jedox->set_alias($receiver_fp_childs_base, $cells_receiver_alias);
			$chart3_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer.",".$receiver_fp_childs_base_a;
			
			$chart3_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart3_margin_area, "", 1, "", "0");
			$chart3_margin = $this->jedox->cell_export_setarray($chart3_margin);
			usort($chart3_margin, array($this,'custom_sort_asc'));
			$chart3 = $this->jedox->singlechart_xml($chart3_margin, $receiver_fp_childs_base_alias, 5);
			
			//////////////////////////////////////////////
			// CHART 4 - Revenue and Margin by Customer //
			//////////////////////////////////////////////
			$customer_cu_childs = array_merge($this->jedox->array_element_filter($customer_elements, "CU_1"), $this->jedox->array_element_filter($customer_elements, "CU_2"), $this->jedox->array_element_filter($customer_elements, "CU_3"));
			$customer_cu_childs_a = $this->jedox->get_area($customer_cu_childs);
			
			$chart4_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer_cu_childs_a.",".$receiver;
			$chart4_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer_cu_childs_a.",".$receiver;
			$chart4_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer_cu_childs_a.",".$receiver;
			
			$chart4_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart4_income_area, "", 1, "", "0");
			$chart4_income = $this->jedox->cell_export_setarray($chart4_income);
			
			$chart4_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area, "", 1, "", "0");
			$chart4_margin = $this->jedox->cell_export_setarray($chart4_margin);
			
			$chart4_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area_b, "", 1, "", "0");
			$chart4_margin_b = $this->jedox->cell_export_setarray($chart4_margin_b);
			
			$chart4_array = array();
			foreach($customer_cu_childs as $row)
			{
				foreach($chart4_income as $i)
				{
					if($i['value'] == '')
					{
						$i['value'] = 0;
					}
					$i_path = explode(",", $i['path']);
					if($i_path[5] == $row['element'] && $i_path[0] == $version_actual)
					{
						$row['Income'] = $i['value'];
					}
					if($i_path[5] == $row['element'] && $i_path[0] == $version_target)
					{
						$row['Income_t'] = $i['value'];
					}
				}
				foreach($chart4_margin as $j)
				{
					if($j['value'] == '')
					{
						$j['value'] = 0;
					}
					$j_path = explode(",", $j['path']);
					if($j_path[4] == $row['element'] && $j_path[0] == $version_actual)
					{
						$row['Margin'] = $j['value'];
					}
					if($j_path[4] == $row['element'] && $j_path[0] == $version_target)
					{
						$row['Margin_t'] = $j['value'];
					}
				}
				foreach($chart4_margin_b as $k)
				{
					if($k['value'] == '')
					{
						$k['value'] = 0;
					}
					$k_path = explode(",", $k['path']);
					if($k_path[4] == $row['element'] && $k_path[0] == $version_actual)
					{
						$row['MarginD'] = $k['value'];
					}
					if($k_path[4] == $row['element'] && $k_path[0] == $version_target)
					{
						$row['MarginD_t'] = $k['value'];
					}
				}
				$chart4_array[] = $row;
			}
			$chart4_array = $this->jedox->set_alias($chart4_array, $cells_customer_alias);
			$chart4 = $this->xy_xml2($chart4_array, $this->jedox->get_area($customer_elements, "CU"));
			
			$table4_top10 = $this->gm_variance($chart4_array);
			usort($table4_top10, array($this,'custom_sort_desc_m'));
			$table4_top10_count = count($table4_top10);
			$table4_top10 = $this->single_xml_custom_array($table4_top10);
			
			
			///////////
			// TABLE //
			///////////
			$version_pac = $this->jedox->get_area($version_elements, "V001,V002,V003"); // Plan,Actual,Target
			$version_pac_elements = $this->jedox->dimension_elements_id($version_elements, "V001,V002,V003");
			$income_value_qty05 = $this->jedox->get_area($income_value_elements, "QTY05"); 
			$income_value_sls04 = $this->jedox->get_area($income_value_elements, "SLS04");
			$income_value_sls03 = $this->jedox->get_area($income_value_elements, "SLS03");
			$income_value_sls02 = $this->jedox->get_area($income_value_elements, "SLS02");
			$income_value_p001 = $this->jedox->get_area($income_value_elements, "P001");
			$margin_pc04 = $this->jedox->get_area($margin_value_elements, "PC04");
			$margin_sc04 = $this->jedox->get_area($margin_value_elements, "SC04");
			$margin_sc03 = $this->jedox->get_area($margin_value_elements, "SC03");
			$margin_scf = $this->jedox->get_area($margin_value_elements, "SCF");
			
			$sq_area = $version_pac.",".$year.",".$month.",".$income_value_qty05.",".$account_element_ce4.",".$customer.",".$receiver;
			$sq_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sq_area, "", 1, "", "0");
			$sq_income = $this->jedox->cell_export_setarray($sq_income);
			$sq = $this->custom_row($sq_income, $version_pac_elements, 0, ''); 
			
			// commented out till filter is added. //
			$sp_area = $version_pac.",".$year.",".$month.",".$income_value_p001.",".$account_element_ce4.",".$customer.",".$receiver;
			$sp_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sp_area, "", 1, "", "0");
			$sp_income = $this->jedox->cell_export_setarray($sp_income);
			$sp = $this->custom_row($sp_income, $version_pac_elements, 0, '$', 0, 1); 
			//$sp = "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			
			$gr_area = $version_pac.",".$year.",".$month.",".$income_value_sls04.",".$account_element_ce4.",".$customer.",".$receiver;
			$gr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$gr_area, "", 1, "", "0");
			$gr_income = $this->jedox->cell_export_setarray($gr_income);
			$gr = $this->custom_row($gr_income, $version_pac_elements, 0, '$');
			
			$dc_area = $version_pac.",".$year.",".$month.",".$income_value_sls03.",".$account_element_ce4.",".$customer.",".$receiver;
			$dc_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$dc_area, "", 1, "", "0");
			$dc_income = $this->jedox->cell_export_setarray($dc_income);
			$dc = $this->custom_row($dc_income, $version_pac_elements, 0, '$', 1);
			
			$nr_area = $version_pac.",".$year.",".$month.",".$income_value_sls02.",".$account_element_ce4.",".$customer.",".$receiver;
			$nr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$nr_area, "", 1, "", "0");
			$nr_income = $this->jedox->cell_export_setarray($nr_income);
			$nr = $this->custom_row($nr_income, $version_pac_elements, 0, '$');
			
			$rm1_area = $version_pac.",".$year.",".$month.",".$margin_pc04.",".$customer.",".$receiver;
			$rm1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm1_area, "", 1, "", "0");
			$rm1_margin = $this->jedox->cell_export_setarray($rm1_margin);
			$rm2_area = $version_pac.",".$year.",".$month.",".$margin_sc04.",".$customer.",".$receiver;
			$rm2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm2_area, "", 1, "", "0");
			$rm2_margin = $this->jedox->cell_export_setarray($rm2_margin);
			$rm_both = $this->jedox->add_cell_array($rm1_margin, $rm2_margin, 0, 1);
			$rm = $this->custom_row($rm_both, $version_pac_elements, 0, '$', 1);
			
			$pm_margin = $this->jedox->subtract_cell_array($nr_income, $rm_both, 0, 1);
			$pm = $this->custom_row($pm_margin, $version_pac_elements, 0, '$'); 
			
			$pc_area = $version_pac.",".$year.",".$month.",".$margin_sc03.",".$customer.",".$receiver;
			$pc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$pc_area, "", 1, "", "0");
			$pc_margin = $this->jedox->cell_export_setarray($pc_margin);
			$pc = $this->custom_row($pc_margin, $version_pac_elements, 0, '$', 1);
			
			$cm_margin = $this->jedox->subtract_cell_array($pm_margin, $pc_margin, 0, 1);
			$cm = $this->custom_row($cm_margin, $version_pac_elements, 0, '$');
			
			$fc_area = $version_pac.",".$year.",".$month.",".$margin_scf.",".$customer.",".$receiver;
			$fc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$fc_area, "", 1, "", "0");
			$fc_margin = $this->jedox->cell_export_setarray($fc_margin);
			$fc = $this->custom_row($fc_margin, $version_pac_elements, 0, '$', 1);
			
			$gm_margin = $this->jedox->subtract_cell_array($cm_margin, $fc_margin, 0, 1);
			$gm = $this->custom_row($gm_margin, $version_pac_elements, 0, '$');
			
			//////////////////////
			// WATERFALL CHARTS //
			//////////////////////
			
			$chart5 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_plan);
			
			$chart5 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Net Revenue' isSum='1'></set>";
			//$chart6 .= "<set label='Net Revenue' isSum='1'></set>";
			//$chart7 .= "<set label='Net Revenue' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Product Margin' isSum='1'></set>";
			//$chart6 .= "<set label='Product Margin' isSum='1'></set>";
			//$chart7 .= "<set label='Product Margin' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Contribution Margin' isSum='1'></set>";
			//$chart6 .= "<set label='Contribution Margin' isSum='1'></set>";
			//$chart7 .= "<set label='Contribution Margin' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Gross Margin' isSum='1'></set>";
			
			/////////////
			// CHART 8 //
			/////////////
			
			$chart8 .= $this->single_xml_custom("Gross Revenue", $gr_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Discounts", $dc_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Net Revenue", $nr_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Raw Material", $rm_both, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Product Margin", $pm_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Proportional Cost", $pc_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Contribution Margin", $cm_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Fixed Cost", $fc_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Gross Margin", $gm_margin, $version_actual, $version_target);
			
			
			//percentages
			
			$p_pm = $this->jedox->percentage_cell_array($pm_margin, $nr_income, 0, 1);
			$p_pm = $this->custom_row_p($p_pm, $version_pac_elements, 0, '%');
			
			$p_cm = $this->jedox->percentage_cell_array($cm_margin, $nr_income, 0, 1);
			$p_cm = $this->custom_row_p($p_cm, $version_pac_elements, 0, '%');
			
			$p_gm = $this->jedox->percentage_cell_array($gm_margin, $nr_income, 0, 1);
			$p_gm = $this->custom_row_p($p_gm, $version_pac_elements, 0, '%');
			
			// Pass all data to send to view file
			$alldata = array(
				//regular vars here
				"year" => $year,
				"month" => $month,
				"receiver" => $receiver,
				"customer" => $customer,
				"form_year" => $form_year,
				"form_months" => $form_months,
				"form_receiver" => $form_receiver,
				"form_customer" => $form_customer,
				"chart1" => $chart1,
				"chart2" => $chart2,
				"chart3" => $chart3,
				"chart4" => $chart4,
				"chart5" => $chart5,
				//"chart6" => $chart6,
				//"chart7" => $chart7,
				"chart8" => $chart8,
				"sq" => $sq,
				"sp" => $sp,
				"gr" => $gr,
				"dc" => $dc,
				"nr" => $nr,
				"rm" => $rm,
				"pm" => $pm,
				"pc" => $pc,
				"cm" => $cm,
				"fc" => $fc,
				"gm" => $gm,
				"p_pm" => $p_pm,
				"p_cm" => $p_cm,
				"p_gm" => $p_gm,
				"table1_top10" => $table1_top10,
				"table4_top10" => $table4_top10,
				"table1_top10_count" => $table1_top10_count,
				"table4_top10_count" => $table4_top10_count,
				"jedox_user_details" => $this->session->userdata('jedox_user_details'),
				"pagename" => $pagename,
				"chart_name" => $chart_name
				//trace vars here 
				//"chart1_array" => $chart1_array
				
			);
			// Pass data and show view
			$this->load->view("profitability/chart10_view", $alldata);
		
		}// end of login check else.
	}

	public function chart5()
	{
		$pagename = "proEO Profitability";
		$chart_name = "Waterfall Analysis";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedox->page_permission($user_details['group_names'], "profitability") == FALSE)
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
			$cube_names = "Income,Margin,#_Version,#_Month,#_Income_Value,#_Account_Element,#_Receiver,#_Customer,#_Margin_Value";
			// Chart containers
			$chart1 = "";
			$chart2 = "";
			$chart3 = "";
			$chart4 = "";
			$chart5 = "";
			$chart6 = "";
			$chart7 = "";
			$chart8 = "";
			// Initialize post data //
			$year = $this->input->post("year");
			$month = $this->input->post("month");
			$receiver = $this->input->post("receiver");
			$customer = $this->input->post("customer");
			
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
			$margin_dimension_id = $this->jedox->get_dimension_id($database_cubes, "Margin");
			
			$income_cube_info = $this->jedox->get_cube_data($database_cubes, "Income");
			$margin_cube_info = $this->jedox->get_cube_data($database_cubes, "Margin");
			
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
			
			// MARGIN VALUE //
			// Get dimension of margin_value
			$margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[3]);
			// Get cube data of receiver alias
			$margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
			$margin_value_alias_info = $this->jedox->get_cube_data($database_cubes, "#_Margin_Value");
			// Export cells of receiver alias
			$margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
			$cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*"); 
			
			// FORM DATA //
			$form_year = $year_elements;
			
			//$form_months = $this->jedox->array_element_filter($month_elements, "MA"); // All Months
			$form_months = $this->jedox->set_alias($month_elements, $cells_month_alias); // Set aliases
			
			$receiver_fp_cont = $this->jedox->array_element_filter($receiver_elements, "FP"); // Finished Products
			$form_receiver = $this->jedox->set_alias($receiver_fp_cont, $cells_receiver_alias); 
			
			$form_customer = $this->jedox->set_alias($customer_elements, $cells_customer_alias);
			
			/////////////
			// PRESETS //
			/////////////
			
			if($year == '')
			{
				$now = now();
				$tnow = mdate("%Y", $now);
				$year = $this->jedox->get_area($year_elements, $tnow);
			}
			if($month == '')
			{
				$month = $this->jedox->get_area($month_elements, "MA"); // All Months
			}
			if($receiver == '')
			{
				$receiver = $this->jedox->get_area($receiver_elements, "FP"); // All Products
			}
			if($customer == '')
			{
				$customer = $this->jedox->get_area($customer_elements, "CU"); 
			}
			
			////////////
			// CHARTS //
			////////////
			
			$version_actual = $this->jedox->get_area($version_elements, "V002"); // Actual
			$version_target = $this->jedox->get_area($version_elements, "V003"); // Target
			$version_plan = $this->jedox->get_area($version_elements, "V001"); // Plan
			$version_at = $this->jedox->get_area($version_elements, "V002,V003"); // Actual,Target
			$income_value_sls = $this->jedox->get_area($income_value_elements, "SLS"); // Income
			$account_element_ce4 = $this->jedox->get_area($account_element_elements, "CE_4");
			$customer_cu = $this->jedox->get_area($customer_elements, "CU");
			$margin_mg03 = $this->jedox->get_area($margin_value_elements, "MG03");
			$margin_mg04 = $this->jedox->get_area($margin_value_elements, "MG04"); 
			$margin_mg04_perc = $this->jedox->get_area($margin_value_elements, "MG04%");
			$receiver_fp_childs = array_merge($this->jedox->array_element_filter($receiver_elements, "FP_100"), $this->jedox->array_element_filter($receiver_elements, "FP_200"), $this->jedox->array_element_filter($receiver_elements, "FP_300"), $this->jedox->array_element_filter($receiver_elements, "FP_400"));
			$receiver_fp_childs_a = $this->jedox->get_area($receiver_fp_childs);
			
			
			/////////////////////////////////////////////
			// CHART 1 - Revenue and Margin per Product //
			/////////////////////////////////////////////
			
			$chart1_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer.",".$receiver_fp_childs_a;
			$chart1_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer.",".$receiver_fp_childs_a;
			$chart1_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer.",".$receiver_fp_childs_a;
			
			$chart1_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart1_income_area, "", 1, "", "0");
			$chart1_income = $this->jedox->cell_export_setarray($chart1_income);
			
			$chart1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area, "", 1, "", "0");
			$chart1_margin = $this->jedox->cell_export_setarray($chart1_margin);
			
			$chart1_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area_b, "", 1, "", "0");
			$chart1_margin_b = $this->jedox->cell_export_setarray($chart1_margin_b);
			
			//combine data for xy chart.
			$chart1_array = array();
			foreach($receiver_fp_childs as $row)
			{
				foreach($chart1_income as $i)
				{
					if($i['value'] == '')
					{
						$i['value'] = 0;
					}
					$i_path = explode(",", $i['path']);
					if($i_path[6] == $row['element'] && $i_path[0] == $version_actual)
					{
						$row['Income'] = $i['value'];
					}
					if($i_path[6] == $row['element'] && $i_path[0] == $version_target)
					{
						$row['Income_t'] = $i['value'];
					}
				}
				foreach($chart1_margin as $j)
				{
					if($j['value'] == '')
					{
						$j['value'] = 0;
					}
					$j_path = explode(",", $j['path']);
					if($j_path[5] == $row['element'] && $j_path[0] == $version_actual)
					{
						$row['Margin'] = $j['value'];
					}
					if($j_path[5] == $row['element'] && $j_path[0] == $version_target)
					{
						$row['Margin_t'] = $j['value'];
					}
				}
				foreach($chart1_margin_b as $k)
				{
					if($k['value'] == '')
					{
						$k['value'] = 0;
					}
					$k_path = explode(",", $k['path']);
					if($k_path[5] == $row['element'] && $k_path[0] == $version_actual)
					{
						$row['MarginD'] = $k['value'];
					}
					if($k_path[5] == $row['element'] && $k_path[0] == $version_target)
					{
						$row['MarginD_t'] = $k['value'];
					}
				}
				$chart1_array[] = $row;
			}
			$chart1_array = $this->jedox->set_alias($chart1_array, $cells_receiver_alias);
			$chart1 = $this->xy_xml($chart1_array, $this->jedox->get_area($receiver_elements, "FP"));
			
			$table1_top10 = $this->gm_variance($chart1_array);
			usort($table1_top10, array($this,'custom_sort_desc_m'));
			$table1_top10_count = count($table1_top10);
			
			$table1_top10 = $this->single_xml_custom_array($table1_top10);
			
			
			///////////////////////////////////////////////
			// CHART 2 - ACTUAL GROSS MARGIN BY COSTUMER //
			///////////////////////////////////////////////
			$customer_base = $this->jedox->dimension_elements_base($customer_elements);
			$customer_base_alias = $this->jedox->set_alias($customer_base, $cells_customer_alias);
			$customer_base_area = $this->jedox->get_area($customer_base);
			$chart2_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer_base_area.",".$receiver;
			
			$chart2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart2_margin_area, "", 1, "", "0");
			$chart2_margin = $this->jedox->cell_export_setarray($chart2_margin);
			usort($chart2_margin, array($this,'custom_sort_asc'));
			$chart2 = $this->jedox->singlechart_xml($chart2_margin, $customer_base_alias, 4);
			
			//////////////////////////////////////////////
			// CHART 3 - ACTUAL GROSS MARGIN BY PRODUCT //
			//////////////////////////////////////////////
			$receiver_fp_childs_base = $this->jedox->dimension_elements_base($receiver_fp_childs);
			$receiver_fp_childs_base_a = $this->jedox->get_area($receiver_fp_childs_base);
			$receiver_fp_childs_base_alias = $this->jedox->set_alias($receiver_fp_childs_base, $cells_receiver_alias);
			$chart3_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer.",".$receiver_fp_childs_base_a;
			
			$chart3_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart3_margin_area, "", 1, "", "0");
			$chart3_margin = $this->jedox->cell_export_setarray($chart3_margin);
			usort($chart3_margin, array($this,'custom_sort_asc'));
			$chart3 = $this->jedox->singlechart_xml($chart3_margin, $receiver_fp_childs_base_alias, 5);
			
			//////////////////////////////////////////////
			// CHART 4 - Revenue and Margin by Customer //
			//////////////////////////////////////////////
			$customer_cu_childs = array_merge($this->jedox->array_element_filter($customer_elements, "CU_1"), $this->jedox->array_element_filter($customer_elements, "CU_2"), $this->jedox->array_element_filter($customer_elements, "CU_3"));
			$customer_cu_childs_a = $this->jedox->get_area($customer_cu_childs);
			
			$chart4_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer_cu_childs_a.",".$receiver;
			$chart4_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer_cu_childs_a.",".$receiver;
			$chart4_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer_cu_childs_a.",".$receiver;
			
			$chart4_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart4_income_area, "", 1, "", "0");
			$chart4_income = $this->jedox->cell_export_setarray($chart4_income);
			
			$chart4_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area, "", 1, "", "0");
			$chart4_margin = $this->jedox->cell_export_setarray($chart4_margin);
			
			$chart4_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area_b, "", 1, "", "0");
			$chart4_margin_b = $this->jedox->cell_export_setarray($chart4_margin_b);
			
			$chart4_array = array();
			foreach($customer_cu_childs as $row)
			{
				foreach($chart4_income as $i)
				{
					if($i['value'] == '')
					{
						$i['value'] = 0;
					}
					$i_path = explode(",", $i['path']);
					if($i_path[5] == $row['element'] && $i_path[0] == $version_actual)
					{
						$row['Income'] = $i['value'];
					}
					if($i_path[5] == $row['element'] && $i_path[0] == $version_target)
					{
						$row['Income_t'] = $i['value'];
					}
				}
				foreach($chart4_margin as $j)
				{
					if($j['value'] == '')
					{
						$j['value'] = 0;
					}
					$j_path = explode(",", $j['path']);
					if($j_path[4] == $row['element'] && $j_path[0] == $version_actual)
					{
						$row['Margin'] = $j['value'];
					}
					if($j_path[4] == $row['element'] && $j_path[0] == $version_target)
					{
						$row['Margin_t'] = $j['value'];
					}
				}
				foreach($chart4_margin_b as $k)
				{
					if($k['value'] == '')
					{
						$k['value'] = 0;
					}
					$k_path = explode(",", $k['path']);
					if($k_path[4] == $row['element'] && $k_path[0] == $version_actual)
					{
						$row['MarginD'] = $k['value'];
					}
					if($k_path[4] == $row['element'] && $k_path[0] == $version_target)
					{
						$row['MarginD_t'] = $k['value'];
					}
				}
				$chart4_array[] = $row;
			}
			$chart4_array = $this->jedox->set_alias($chart4_array, $cells_customer_alias);
			$chart4 = $this->xy_xml2($chart4_array, $this->jedox->get_area($customer_elements, "CU"));
			
			$table4_top10 = $this->gm_variance($chart4_array);
			usort($table4_top10, array($this,'custom_sort_desc_m'));
			$table4_top10_count = count($table4_top10);
			$table4_top10 = $this->single_xml_custom_array($table4_top10);
			
			
			///////////
			// TABLE //
			///////////
			$version_pac = $this->jedox->get_area($version_elements, "V001,V002,V003"); // Plan,Actual,Target
			$version_pac_elements = $this->jedox->dimension_elements_id($version_elements, "V001,V002,V003");
			$income_value_qty05 = $this->jedox->get_area($income_value_elements, "QTY05"); 
			$income_value_sls04 = $this->jedox->get_area($income_value_elements, "SLS04");
			$income_value_sls03 = $this->jedox->get_area($income_value_elements, "SLS03");
			$income_value_sls02 = $this->jedox->get_area($income_value_elements, "SLS02");
			$income_value_p001 = $this->jedox->get_area($income_value_elements, "P001");
			$margin_pc04 = $this->jedox->get_area($margin_value_elements, "PC04");
			$margin_sc04 = $this->jedox->get_area($margin_value_elements, "SC04");
			$margin_sc03 = $this->jedox->get_area($margin_value_elements, "SC03");
			$margin_scf = $this->jedox->get_area($margin_value_elements, "SCF");
			
			$sq_area = $version_pac.",".$year.",".$month.",".$income_value_qty05.",".$account_element_ce4.",".$customer.",".$receiver;
			$sq_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sq_area, "", 1, "", "0");
			$sq_income = $this->jedox->cell_export_setarray($sq_income);
			$sq = $this->custom_row($sq_income, $version_pac_elements, 0, ''); 
			
			// commented out till filter is added. //
			$sp_area = $version_pac.",".$year.",".$month.",".$income_value_p001.",".$account_element_ce4.",".$customer.",".$receiver;
			$sp_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sp_area, "", 1, "", "0");
			$sp_income = $this->jedox->cell_export_setarray($sp_income);
			$sp = $this->custom_row($sp_income, $version_pac_elements, 0, '$', 0, 1); 
			//$sp = "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			
			$gr_area = $version_pac.",".$year.",".$month.",".$income_value_sls04.",".$account_element_ce4.",".$customer.",".$receiver;
			$gr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$gr_area, "", 1, "", "0");
			$gr_income = $this->jedox->cell_export_setarray($gr_income);
			$gr = $this->custom_row($gr_income, $version_pac_elements, 0, '$');
			
			$dc_area = $version_pac.",".$year.",".$month.",".$income_value_sls03.",".$account_element_ce4.",".$customer.",".$receiver;
			$dc_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$dc_area, "", 1, "", "0");
			$dc_income = $this->jedox->cell_export_setarray($dc_income);
			$dc = $this->custom_row($dc_income, $version_pac_elements, 0, '$', 1);
			
			$nr_area = $version_pac.",".$year.",".$month.",".$income_value_sls02.",".$account_element_ce4.",".$customer.",".$receiver;
			$nr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$nr_area, "", 1, "", "0");
			$nr_income = $this->jedox->cell_export_setarray($nr_income);
			$nr = $this->custom_row($nr_income, $version_pac_elements, 0, '$');
			
			$rm1_area = $version_pac.",".$year.",".$month.",".$margin_pc04.",".$customer.",".$receiver;
			$rm1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm1_area, "", 1, "", "0");
			$rm1_margin = $this->jedox->cell_export_setarray($rm1_margin);
			$rm2_area = $version_pac.",".$year.",".$month.",".$margin_sc04.",".$customer.",".$receiver;
			$rm2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm2_area, "", 1, "", "0");
			$rm2_margin = $this->jedox->cell_export_setarray($rm2_margin);
			$rm_both = $this->jedox->add_cell_array($rm1_margin, $rm2_margin, 0, 1);
			$rm = $this->custom_row($rm_both, $version_pac_elements, 0, '$', 1);
			
			$pm_margin = $this->jedox->subtract_cell_array($nr_income, $rm_both, 0, 1);
			$pm = $this->custom_row($pm_margin, $version_pac_elements, 0, '$'); 
			
			$pc_area = $version_pac.",".$year.",".$month.",".$margin_sc03.",".$customer.",".$receiver;
			$pc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$pc_area, "", 1, "", "0");
			$pc_margin = $this->jedox->cell_export_setarray($pc_margin);
			$pc = $this->custom_row($pc_margin, $version_pac_elements, 0, '$', 1);
			
			$cm_margin = $this->jedox->subtract_cell_array($pm_margin, $pc_margin, 0, 1);
			$cm = $this->custom_row($cm_margin, $version_pac_elements, 0, '$');
			
			$fc_area = $version_pac.",".$year.",".$month.",".$margin_scf.",".$customer.",".$receiver;
			$fc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$fc_area, "", 1, "", "0");
			$fc_margin = $this->jedox->cell_export_setarray($fc_margin);
			$fc = $this->custom_row($fc_margin, $version_pac_elements, 0, '$', 1);
			
			$gm_margin = $this->jedox->subtract_cell_array($cm_margin, $fc_margin, 0, 1);
			$gm = $this->custom_row($gm_margin, $version_pac_elements, 0, '$');
			
			//////////////////////
			// WATERFALL CHARTS //
			//////////////////////
			
			$chart5 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_plan);
			
			$chart5 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Net Revenue' isSum='1'></set>";
			//$chart6 .= "<set label='Net Revenue' isSum='1'></set>";
			//$chart7 .= "<set label='Net Revenue' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Product Margin' isSum='1'></set>";
			//$chart6 .= "<set label='Product Margin' isSum='1'></set>";
			//$chart7 .= "<set label='Product Margin' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Contribution Margin' isSum='1'></set>";
			//$chart6 .= "<set label='Contribution Margin' isSum='1'></set>";
			//$chart7 .= "<set label='Contribution Margin' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Gross Margin' isSum='1'></set>";
			
			/////////////
			// CHART 8 //
			/////////////
			
			$chart8 .= $this->single_xml_custom("Gross Revenue", $gr_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Discounts", $dc_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Net Revenue", $nr_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Raw Material", $rm_both, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Product Margin", $pm_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Proportional Cost", $pc_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Contribution Margin", $cm_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Fixed Cost", $fc_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Gross Margin", $gm_margin, $version_actual, $version_target);
			
			
			//percentages
			
			$p_pm = $this->jedox->percentage_cell_array($pm_margin, $nr_income, 0, 1);
			$p_pm = $this->custom_row_p($p_pm, $version_pac_elements, 0, '%');
			
			$p_cm = $this->jedox->percentage_cell_array($cm_margin, $nr_income, 0, 1);
			$p_cm = $this->custom_row_p($p_cm, $version_pac_elements, 0, '%');
			
			$p_gm = $this->jedox->percentage_cell_array($gm_margin, $nr_income, 0, 1);
			$p_gm = $this->custom_row_p($p_gm, $version_pac_elements, 0, '%');
			
			// Pass all data to send to view file
			$alldata = array(
				//regular vars here
				"year" => $year,
				"month" => $month,
				"receiver" => $receiver,
				"customer" => $customer,
				"form_year" => $form_year,
				"form_months" => $form_months,
				"form_receiver" => $form_receiver,
				"form_customer" => $form_customer,
				"chart1" => $chart1,
				"chart2" => $chart2,
				"chart3" => $chart3,
				"chart4" => $chart4,
				"chart5" => $chart5,
				//"chart6" => $chart6,
				//"chart7" => $chart7,
				"chart8" => $chart8,
				"sq" => $sq,
				"sp" => $sp,
				"gr" => $gr,
				"dc" => $dc,
				"nr" => $nr,
				"rm" => $rm,
				"pm" => $pm,
				"pc" => $pc,
				"cm" => $cm,
				"fc" => $fc,
				"gm" => $gm,
				"p_pm" => $p_pm,
				"p_cm" => $p_cm,
				"p_gm" => $p_gm,
				"table1_top10" => $table1_top10,
				"table4_top10" => $table4_top10,
				"table1_top10_count" => $table1_top10_count,
				"table4_top10_count" => $table4_top10_count,
				"jedox_user_details" => $this->session->userdata('jedox_user_details'),
				"pagename" => $pagename,
				"chart_name" => $chart_name
				//trace vars here 
				//"chart1_array" => $chart1_array
				
			);
			// Pass data and show view
			$this->load->view("profitability/chart5_view", $alldata);
		
		}// end of login check else.
	}

	public function chart8()
	{
		$pagename = "proEO Profitability";
		$chart_name = "Waterfall Analysis Ranking";
		$user_details = $this->session->userdata('jedox_user_details');
		if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
		{
			$this->session->set_userdata('jedox_referer', current_url());
			redirect("/login/page");
		}
		else if($this->jedox->page_permission($user_details['group_names'], "profitability") == FALSE)
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
			$cube_names = "Income,Margin,#_Version,#_Month,#_Income_Value,#_Account_Element,#_Receiver,#_Customer,#_Margin_Value";
			// Chart containers
			$chart1 = "";
			$chart2 = "";
			$chart3 = "";
			$chart4 = "";
			$chart5 = "";
			$chart6 = "";
			$chart7 = "";
			$chart8 = "";
			// Initialize post data //
			$year = $this->input->post("year");
			$month = $this->input->post("month");
			$receiver = $this->input->post("receiver");
			$customer = $this->input->post("customer");
			
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
			$margin_dimension_id = $this->jedox->get_dimension_id($database_cubes, "Margin");
			
			$income_cube_info = $this->jedox->get_cube_data($database_cubes, "Income");
			$margin_cube_info = $this->jedox->get_cube_data($database_cubes, "Margin");
			
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
			
			// MARGIN VALUE //
			// Get dimension of margin_value
			$margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[3]);
			// Get cube data of receiver alias
			$margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
			$margin_value_alias_info = $this->jedox->get_cube_data($database_cubes, "#_Margin_Value");
			// Export cells of receiver alias
			$margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
			$cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*"); 
			
			// FORM DATA //
			$form_year = $year_elements;
			
			//$form_months = $this->jedox->array_element_filter($month_elements, "MA"); // All Months
			$form_months = $this->jedox->set_alias($month_elements, $cells_month_alias); // Set aliases
			
			$receiver_fp_cont = $this->jedox->array_element_filter($receiver_elements, "FP"); // Finished Products
			$form_receiver = $this->jedox->set_alias($receiver_fp_cont, $cells_receiver_alias); 
			
			$form_customer = $this->jedox->set_alias($customer_elements, $cells_customer_alias);
			
			/////////////
			// PRESETS //
			/////////////
			
			if($year == '')
			{
				$now = now();
				$tnow = mdate("%Y", $now);
				$year = $this->jedox->get_area($year_elements, $tnow);
			}
			if($month == '')
			{
				$month = $this->jedox->get_area($month_elements, "MA"); // All Months
			}
			if($receiver == '')
			{
				$receiver = $this->jedox->get_area($receiver_elements, "FP"); // All Products
			}
			if($customer == '')
			{
				$customer = $this->jedox->get_area($customer_elements, "CU"); 
			}
			
			////////////
			// CHARTS //
			////////////
			
			$version_actual = $this->jedox->get_area($version_elements, "V002"); // Actual
			$version_target = $this->jedox->get_area($version_elements, "V003"); // Target
			$version_plan = $this->jedox->get_area($version_elements, "V001"); // Plan
			$version_at = $this->jedox->get_area($version_elements, "V002,V003"); // Actual,Target
			$income_value_sls = $this->jedox->get_area($income_value_elements, "SLS"); // Income
			$account_element_ce4 = $this->jedox->get_area($account_element_elements, "CE_4");
			$customer_cu = $this->jedox->get_area($customer_elements, "CU");
			$margin_mg03 = $this->jedox->get_area($margin_value_elements, "MG03");
			$margin_mg04 = $this->jedox->get_area($margin_value_elements, "MG04"); 
			$margin_mg04_perc = $this->jedox->get_area($margin_value_elements, "MG04%");
			$receiver_fp_childs = array_merge($this->jedox->array_element_filter($receiver_elements, "FP_100"), $this->jedox->array_element_filter($receiver_elements, "FP_200"), $this->jedox->array_element_filter($receiver_elements, "FP_300"), $this->jedox->array_element_filter($receiver_elements, "FP_400"));
			$receiver_fp_childs_a = $this->jedox->get_area($receiver_fp_childs);
			
			
			/////////////////////////////////////////////
			// CHART 1 - Revenue and Margin per Product //
			/////////////////////////////////////////////
			
			$chart1_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer.",".$receiver_fp_childs_a;
			$chart1_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer.",".$receiver_fp_childs_a;
			$chart1_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer.",".$receiver_fp_childs_a;
			
			$chart1_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart1_income_area, "", 1, "", "0");
			$chart1_income = $this->jedox->cell_export_setarray($chart1_income);
			
			$chart1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area, "", 1, "", "0");
			$chart1_margin = $this->jedox->cell_export_setarray($chart1_margin);
			
			$chart1_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart1_margin_area_b, "", 1, "", "0");
			$chart1_margin_b = $this->jedox->cell_export_setarray($chart1_margin_b);
			
			//combine data for xy chart.
			$chart1_array = array();
			foreach($receiver_fp_childs as $row)
			{
				foreach($chart1_income as $i)
				{
					if($i['value'] == '')
					{
						$i['value'] = 0;
					}
					$i_path = explode(",", $i['path']);
					if($i_path[6] == $row['element'] && $i_path[0] == $version_actual)
					{
						$row['Income'] = $i['value'];
					}
					if($i_path[6] == $row['element'] && $i_path[0] == $version_target)
					{
						$row['Income_t'] = $i['value'];
					}
				}
				foreach($chart1_margin as $j)
				{
					if($j['value'] == '')
					{
						$j['value'] = 0;
					}
					$j_path = explode(",", $j['path']);
					if($j_path[5] == $row['element'] && $j_path[0] == $version_actual)
					{
						$row['Margin'] = $j['value'];
					}
					if($j_path[5] == $row['element'] && $j_path[0] == $version_target)
					{
						$row['Margin_t'] = $j['value'];
					}
				}
				foreach($chart1_margin_b as $k)
				{
					if($k['value'] == '')
					{
						$k['value'] = 0;
					}
					$k_path = explode(",", $k['path']);
					if($k_path[5] == $row['element'] && $k_path[0] == $version_actual)
					{
						$row['MarginD'] = $k['value'];
					}
					if($k_path[5] == $row['element'] && $k_path[0] == $version_target)
					{
						$row['MarginD_t'] = $k['value'];
					}
				}
				$chart1_array[] = $row;
			}
			$chart1_array = $this->jedox->set_alias($chart1_array, $cells_receiver_alias);
			$chart1 = $this->xy_xml($chart1_array, $this->jedox->get_area($receiver_elements, "FP"));
			
			$table1_top10 = $this->gm_variance($chart1_array);
			usort($table1_top10, array($this,'custom_sort_desc_m'));
			$table1_top10_count = count($table1_top10);
			
			$table1_top10 = $this->single_xml_custom_array($table1_top10);
			
			
			///////////////////////////////////////////////
			// CHART 2 - ACTUAL GROSS MARGIN BY COSTUMER //
			///////////////////////////////////////////////
			$customer_base = $this->jedox->dimension_elements_base($customer_elements);
			$customer_base_alias = $this->jedox->set_alias($customer_base, $cells_customer_alias);
			$customer_base_area = $this->jedox->get_area($customer_base);
			$chart2_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer_base_area.",".$receiver;
			
			$chart2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart2_margin_area, "", 1, "", "0");
			$chart2_margin = $this->jedox->cell_export_setarray($chart2_margin);
			usort($chart2_margin, array($this,'custom_sort_asc'));
			$chart2 = $this->jedox->singlechart_xml($chart2_margin, $customer_base_alias, 4);
			
			//////////////////////////////////////////////
			// CHART 3 - ACTUAL GROSS MARGIN BY PRODUCT //
			//////////////////////////////////////////////
			$receiver_fp_childs_base = $this->jedox->dimension_elements_base($receiver_fp_childs);
			$receiver_fp_childs_base_a = $this->jedox->get_area($receiver_fp_childs_base);
			$receiver_fp_childs_base_alias = $this->jedox->set_alias($receiver_fp_childs_base, $cells_receiver_alias);
			$chart3_margin_area = $version_actual.",".$year.",".$month.",".$margin_mg03.",".$customer.",".$receiver_fp_childs_base_a;
			
			$chart3_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart3_margin_area, "", 1, "", "0");
			$chart3_margin = $this->jedox->cell_export_setarray($chart3_margin);
			usort($chart3_margin, array($this,'custom_sort_asc'));
			$chart3 = $this->jedox->singlechart_xml($chart3_margin, $receiver_fp_childs_base_alias, 5);
			
			//////////////////////////////////////////////
			// CHART 4 - Revenue and Margin by Customer //
			//////////////////////////////////////////////
			$customer_cu_childs = array_merge($this->jedox->array_element_filter($customer_elements, "CU_1"), $this->jedox->array_element_filter($customer_elements, "CU_2"), $this->jedox->array_element_filter($customer_elements, "CU_3"));
			$customer_cu_childs_a = $this->jedox->get_area($customer_cu_childs);
			
			$chart4_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer_cu_childs_a.",".$receiver;
			$chart4_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer_cu_childs_a.",".$receiver;
			$chart4_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer_cu_childs_a.",".$receiver;
			
			$chart4_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart4_income_area, "", 1, "", "0");
			$chart4_income = $this->jedox->cell_export_setarray($chart4_income);
			
			$chart4_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area, "", 1, "", "0");
			$chart4_margin = $this->jedox->cell_export_setarray($chart4_margin);
			
			$chart4_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area_b, "", 1, "", "0");
			$chart4_margin_b = $this->jedox->cell_export_setarray($chart4_margin_b);
			
			$chart4_array = array();
			foreach($customer_cu_childs as $row)
			{
				foreach($chart4_income as $i)
				{
					if($i['value'] == '')
					{
						$i['value'] = 0;
					}
					$i_path = explode(",", $i['path']);
					if($i_path[5] == $row['element'] && $i_path[0] == $version_actual)
					{
						$row['Income'] = $i['value'];
					}
					if($i_path[5] == $row['element'] && $i_path[0] == $version_target)
					{
						$row['Income_t'] = $i['value'];
					}
				}
				foreach($chart4_margin as $j)
				{
					if($j['value'] == '')
					{
						$j['value'] = 0;
					}
					$j_path = explode(",", $j['path']);
					if($j_path[4] == $row['element'] && $j_path[0] == $version_actual)
					{
						$row['Margin'] = $j['value'];
					}
					if($j_path[4] == $row['element'] && $j_path[0] == $version_target)
					{
						$row['Margin_t'] = $j['value'];
					}
				}
				foreach($chart4_margin_b as $k)
				{
					if($k['value'] == '')
					{
						$k['value'] = 0;
					}
					$k_path = explode(",", $k['path']);
					if($k_path[4] == $row['element'] && $k_path[0] == $version_actual)
					{
						$row['MarginD'] = $k['value'];
					}
					if($k_path[4] == $row['element'] && $k_path[0] == $version_target)
					{
						$row['MarginD_t'] = $k['value'];
					}
				}
				$chart4_array[] = $row;
			}
			$chart4_array = $this->jedox->set_alias($chart4_array, $cells_customer_alias);
			$chart4 = $this->xy_xml2($chart4_array, $this->jedox->get_area($customer_elements, "CU"));
			
			$table4_top10 = $this->gm_variance($chart4_array);
			usort($table4_top10, array($this,'custom_sort_desc_m'));
			$table4_top10_count = count($table4_top10);
			$table4_top10 = $this->single_xml_custom_array($table4_top10);
			
			
			///////////
			// TABLE //
			///////////
			$version_pac = $this->jedox->get_area($version_elements, "V001,V002,V003"); // Plan,Actual,Target
			$version_pac_elements = $this->jedox->dimension_elements_id($version_elements, "V001,V002,V003");
			$income_value_qty05 = $this->jedox->get_area($income_value_elements, "QTY05"); 
			$income_value_sls04 = $this->jedox->get_area($income_value_elements, "SLS04");
			$income_value_sls03 = $this->jedox->get_area($income_value_elements, "SLS03");
			$income_value_sls02 = $this->jedox->get_area($income_value_elements, "SLS02");
			$income_value_p001 = $this->jedox->get_area($income_value_elements, "P001");
			$margin_pc04 = $this->jedox->get_area($margin_value_elements, "PC04");
			$margin_sc04 = $this->jedox->get_area($margin_value_elements, "SC04");
			$margin_sc03 = $this->jedox->get_area($margin_value_elements, "SC03");
			$margin_scf = $this->jedox->get_area($margin_value_elements, "SCF");
			
			$sq_area = $version_pac.",".$year.",".$month.",".$income_value_qty05.",".$account_element_ce4.",".$customer.",".$receiver;
			$sq_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sq_area, "", 1, "", "0");
			$sq_income = $this->jedox->cell_export_setarray($sq_income);
			$sq = $this->custom_row($sq_income, $version_pac_elements, 0, ''); 
			
			// commented out till filter is added. //
			$sp_area = $version_pac.",".$year.",".$month.",".$income_value_p001.",".$account_element_ce4.",".$customer.",".$receiver;
			$sp_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$sp_area, "", 1, "", "0");
			$sp_income = $this->jedox->cell_export_setarray($sp_income);
			$sp = $this->custom_row($sp_income, $version_pac_elements, 0, '$', 0, 1); 
			//$sp = "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			
			$gr_area = $version_pac.",".$year.",".$month.",".$income_value_sls04.",".$account_element_ce4.",".$customer.",".$receiver;
			$gr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$gr_area, "", 1, "", "0");
			$gr_income = $this->jedox->cell_export_setarray($gr_income);
			$gr = $this->custom_row($gr_income, $version_pac_elements, 0, '$');
			
			$dc_area = $version_pac.",".$year.",".$month.",".$income_value_sls03.",".$account_element_ce4.",".$customer.",".$receiver;
			$dc_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$dc_area, "", 1, "", "0");
			$dc_income = $this->jedox->cell_export_setarray($dc_income);
			$dc = $this->custom_row($dc_income, $version_pac_elements, 0, '$', 1);
			
			$nr_area = $version_pac.",".$year.",".$month.",".$income_value_sls02.",".$account_element_ce4.",".$customer.",".$receiver;
			$nr_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$nr_area, "", 1, "", "0");
			$nr_income = $this->jedox->cell_export_setarray($nr_income);
			$nr = $this->custom_row($nr_income, $version_pac_elements, 0, '$');
			
			$rm1_area = $version_pac.",".$year.",".$month.",".$margin_pc04.",".$customer.",".$receiver;
			$rm1_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm1_area, "", 1, "", "0");
			$rm1_margin = $this->jedox->cell_export_setarray($rm1_margin);
			$rm2_area = $version_pac.",".$year.",".$month.",".$margin_sc04.",".$customer.",".$receiver;
			$rm2_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$rm2_area, "", 1, "", "0");
			$rm2_margin = $this->jedox->cell_export_setarray($rm2_margin);
			$rm_both = $this->jedox->add_cell_array($rm1_margin, $rm2_margin, 0, 1);
			$rm = $this->custom_row($rm_both, $version_pac_elements, 0, '$', 1);
			
			$pm_margin = $this->jedox->subtract_cell_array($nr_income, $rm_both, 0, 1);
			$pm = $this->custom_row($pm_margin, $version_pac_elements, 0, '$'); 
			
			$pc_area = $version_pac.",".$year.",".$month.",".$margin_sc03.",".$customer.",".$receiver;
			$pc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$pc_area, "", 1, "", "0");
			$pc_margin = $this->jedox->cell_export_setarray($pc_margin);
			$pc = $this->custom_row($pc_margin, $version_pac_elements, 0, '$', 1);
			
			$cm_margin = $this->jedox->subtract_cell_array($pm_margin, $pc_margin, 0, 1);
			$cm = $this->custom_row($cm_margin, $version_pac_elements, 0, '$');
			
			$fc_area = $version_pac.",".$year.",".$month.",".$margin_scf.",".$customer.",".$receiver;
			$fc_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$fc_area, "", 1, "", "0");
			$fc_margin = $this->jedox->cell_export_setarray($fc_margin);
			$fc = $this->custom_row($fc_margin, $version_pac_elements, 0, '$', 1);
			
			$gm_margin = $this->jedox->subtract_cell_array($cm_margin, $fc_margin, 0, 1);
			$gm = $this->custom_row($gm_margin, $version_pac_elements, 0, '$');
			
			//////////////////////
			// WATERFALL CHARTS //
			//////////////////////
			
			$chart5 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Gross Revenue", $gr_income, '', 0, $version_plan);
			
			$chart5 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Discounts", $dc_income, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Net Revenue' isSum='1'></set>";
			//$chart6 .= "<set label='Net Revenue' isSum='1'></set>";
			//$chart7 .= "<set label='Net Revenue' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Raw Material", $rm_both, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Product Margin' isSum='1'></set>";
			//$chart6 .= "<set label='Product Margin' isSum='1'></set>";
			//$chart7 .= "<set label='Product Margin' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Proportional Cost", $pc_margin, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Contribution Margin' isSum='1'></set>";
			//$chart6 .= "<set label='Contribution Margin' isSum='1'></set>";
			//$chart7 .= "<set label='Contribution Margin' isSum='1'></set>";
			
			$chart5 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_actual);
			//$chart6 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_target);
			//$chart7 .= $this->jedox->waterfall_xml("Fixed Cost", $fc_margin, '-', 0, $version_plan);
			
			$chart5 .= "<set label='Gross Margin' isSum='1'></set>";
			
			/////////////
			// CHART 8 //
			/////////////
			
			$chart8 .= $this->single_xml_custom("Gross Revenue", $gr_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Discounts", $dc_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Net Revenue", $nr_income, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Raw Material", $rm_both, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Product Margin", $pm_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Proportional Cost", $pc_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Contribution Margin", $cm_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Fixed Cost", $fc_margin, $version_actual, $version_target);
			$chart8 .= $this->single_xml_custom("Gross Margin", $gm_margin, $version_actual, $version_target);
			
			
			//percentages
			
			$p_pm = $this->jedox->percentage_cell_array($pm_margin, $nr_income, 0, 1);
			$p_pm = $this->custom_row_p($p_pm, $version_pac_elements, 0, '%');
			
			$p_cm = $this->jedox->percentage_cell_array($cm_margin, $nr_income, 0, 1);
			$p_cm = $this->custom_row_p($p_cm, $version_pac_elements, 0, '%');
			
			$p_gm = $this->jedox->percentage_cell_array($gm_margin, $nr_income, 0, 1);
			$p_gm = $this->custom_row_p($p_gm, $version_pac_elements, 0, '%');
			
			// Pass all data to send to view file
			$alldata = array(
				//regular vars here
				"year" => $year,
				"month" => $month,
				"receiver" => $receiver,
				"customer" => $customer,
				"form_year" => $form_year,
				"form_months" => $form_months,
				"form_receiver" => $form_receiver,
				"form_customer" => $form_customer,
				"chart1" => $chart1,
				"chart2" => $chart2,
				"chart3" => $chart3,
				"chart4" => $chart4,
				"chart5" => $chart5,
				//"chart6" => $chart6,
				//"chart7" => $chart7,
				"chart8" => $chart8,
				"sq" => $sq,
				"sp" => $sp,
				"gr" => $gr,
				"dc" => $dc,
				"nr" => $nr,
				"rm" => $rm,
				"pm" => $pm,
				"pc" => $pc,
				"cm" => $cm,
				"fc" => $fc,
				"gm" => $gm,
				"p_pm" => $p_pm,
				"p_cm" => $p_cm,
				"p_gm" => $p_gm,
				"table1_top10" => $table1_top10,
				"table4_top10" => $table4_top10,
				"table1_top10_count" => $table1_top10_count,
				"table4_top10_count" => $table4_top10_count,
				"jedox_user_details" => $this->session->userdata('jedox_user_details'),
				"pagename" => $pagename,
				"chart_name" => $chart_name
				//trace vars here 
				//"chart1_array" => $chart1_array
				
			);
			// Pass data and show view
			$this->load->view("profitability/chart8_view", $alldata);
		
		}// end of login check else.
	}
    
    public function chart4()
    {
        $pagename = "proEO Profitability";
        $chart_name = "Revenue and Margin Per Customer";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "profitability") == FALSE)
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
            $cube_names = "Income,Margin,#_Version,#_Month,#_Income_Value,#_Account_Element,#_Receiver,#_Customer,#_Margin_Value";
            // Chart containers
            $chart4 = "";
            // Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $receiver = $this->input->post("receiver");
            $customer = $this->input->post("customer");
            
            // Login. need to relogin to prevent timeout
            $server_login = $this->jedoxapi->server_login($this->session->userdata('jedox_user'), $this->session->userdata('jedox_pass'));
            
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
            $margin_dimension_id = $this->jedox->get_dimension_id($database_cubes, "Margin");
            
            $income_cube_info = $this->jedox->get_cube_data($database_cubes, "Income");
            $margin_cube_info = $this->jedox->get_cube_data($database_cubes, "Margin");
            
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
			
			// MARGIN VALUE //
			// Get dimension of margin_value
			$margin_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_dimension_id[3]);
			// Get cube data of receiver alias
			$margin_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Margin_Value");
			$margin_value_alias_info = $this->jedox->get_cube_data($database_cubes, "#_Margin_Value");
			// Export cells of receiver alias
			$margin_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $margin_value_dimension_id[0]);
			$margin_value_alias_name_id = $this->jedoxapi->get_area($margin_value_alias_elements, "Name");
			$cells_margin_value_alias = $this->jedoxapi->cell_export($server_database['database'],$margin_value_alias_info['cube'],10000,"", $margin_value_alias_name_id.",*"); 
            
            // FORM DATA //
            $form_year = $year_elements;
            
            //$form_months = $this->jedox->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedox->set_alias($month_elements, $cells_month_alias); // Set aliases
            
            $receiver_fp_cont = $this->jedox->array_element_filter($receiver_elements, "FP"); // Finished Products
            $form_receiver = $this->jedox->set_alias($receiver_fp_cont, $cells_receiver_alias); 
            
            $form_customer = $this->jedox->set_alias($customer_elements, $cells_customer_alias);
            
            /////////////
            // PRESETS //
            /////////////
            
            if($year == '')
            {
                $now = now();
                $tnow = mdate("%Y", $now);
                $year = $this->jedox->get_area($year_elements, $tnow);
            }
            if($month == '')
            {
                $month = $this->jedox->get_area($month_elements, "MA"); // All Months
            }
            if($receiver == '')
            {
                $receiver = $this->jedox->get_area($receiver_elements, "FP"); // All Products
            }
            if($customer == '')
            {
                $customer = $this->jedox->get_area($customer_elements, "CU"); 
            }
            
            ////////////
            // CHARTS //
            ////////////
            
            $version_actual = $this->jedox->get_area($version_elements, "V002"); // Actual
            $version_target = $this->jedox->get_area($version_elements, "V003"); // Target
            $version_plan = $this->jedox->get_area($version_elements, "V001"); // Plan
            $version_at = $this->jedox->get_area($version_elements, "V002,V003"); // Actual,Target
            $income_value_sls = $this->jedox->get_area($income_value_elements, "SLS"); // Income
            $account_element_ce4 = $this->jedox->get_area($account_element_elements, "CE_4");
            $customer_cu = $this->jedox->get_area($customer_elements, "CU");
            $margin_mg03 = $this->jedox->get_area($margin_value_elements, "MG03");
            $margin_mg04 = $this->jedox->get_area($margin_value_elements, "MG04"); 
            $margin_mg04_perc = $this->jedox->get_area($margin_value_elements, "MG04%");
            $receiver_fp_childs = array_merge($this->jedox->array_element_filter($receiver_elements, "FP_100"), $this->jedox->array_element_filter($receiver_elements, "FP_200"), $this->jedox->array_element_filter($receiver_elements, "FP_300"), $this->jedox->array_element_filter($receiver_elements, "FP_400"));
            $receiver_fp_childs_a = $this->jedox->get_area($receiver_fp_childs);
            
            
            //////////////////////////////////////////////
            // CHART 4 - Revenue and Margin by Customer //
            //////////////////////////////////////////////
            $customer_cu_childs = array_merge($this->jedox->array_element_filter($customer_elements, "CU_1"), $this->jedox->array_element_filter($customer_elements, "CU_2"), $this->jedox->array_element_filter($customer_elements, "CU_3"));
            $customer_cu_childs_a = $this->jedox->get_area($customer_cu_childs);
            
            $chart4_income_area = $version_at.",".$year.",".$month.",".$income_value_sls.",".$account_element_ce4.",".$customer_cu_childs_a.",".$receiver;
            $chart4_margin_area = $version_at.",".$year.",".$month.",".$margin_mg04_perc.",".$customer_cu_childs_a.",".$receiver;
            $chart4_margin_area_b = $version_at.",".$year.",".$month.",".$margin_mg04.",".$customer_cu_childs_a.",".$receiver;
            
            $chart4_income = $this->jedox->cell_export($server_database['database'],$income_cube_info['cube'],10000,"",$chart4_income_area, "", 1, "", "0");
            $chart4_income = $this->jedox->cell_export_setarray($chart4_income);
            
            $chart4_margin = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area, "", 1, "", "0");
            $chart4_margin = $this->jedox->cell_export_setarray($chart4_margin);
            
            $chart4_margin_b = $this->jedox->cell_export($server_database['database'],$margin_cube_info['cube'],10000,"",$chart4_margin_area_b, "", 1, "", "0");
            $chart4_margin_b = $this->jedox->cell_export_setarray($chart4_margin_b);
            
            $chart4_array = array();
            foreach($customer_cu_childs as $row)
            {
                foreach($chart4_income as $i)
                {
                    if($i['value'] == '')
                    {
                        $i['value'] = 0;
                    }
                    $i_path = explode(",", $i['path']);
                    if($i_path[5] == $row['element'] && $i_path[0] == $version_actual)
                    {
                        $row['Income'] = $i['value'];
                    }
                    if($i_path[5] == $row['element'] && $i_path[0] == $version_target)
                    {
                        $row['Income_t'] = $i['value'];
                    }
                }
                foreach($chart4_margin as $j)
                {
                    if($j['value'] == '')
                    {
                        $j['value'] = 0;
                    }
                    $j_path = explode(",", $j['path']);
                    if($j_path[4] == $row['element'] && $j_path[0] == $version_actual)
                    {
                        $row['Margin'] = $j['value'];
                    }
                    if($j_path[4] == $row['element'] && $j_path[0] == $version_target)
                    {
                        $row['Margin_t'] = $j['value'];
                    }
                }
                foreach($chart4_margin_b as $k)
                {
                    if($k['value'] == '')
                    {
                        $k['value'] = 0;
                    }
                    $k_path = explode(",", $k['path']);
                    if($k_path[4] == $row['element'] && $k_path[0] == $version_actual)
                    {
                        $row['MarginD'] = $k['value'];
                    }
                    if($k_path[4] == $row['element'] && $k_path[0] == $version_target)
                    {
                        $row['MarginD_t'] = $k['value'];
                    }
                }
                $chart4_array[] = $row;
            }
            $chart4_array = $this->jedox->set_alias($chart4_array, $cells_customer_alias);
            $chart4 = $this->xy_xml2($chart4_array, $this->jedox->get_area($customer_elements, "CU"));
            
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "year" => $year,
                "month" => $month,
                "receiver" => $receiver,
                "customer" => $customer,
                "form_year" => $form_year,
                "form_months" => $form_months,
                "form_receiver" => $form_receiver,
                "form_customer" => $form_customer,
                "chart4" => $chart4,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "chart_name" => $chart_name
                
            );
            // Pass data and show view
            $this->load->view("profitability/chart4_view", $alldata);
            
        }
            
    }
    
	private function xy_xml($array, $parentid)
	{
		$xml = "";
		$temp = '';
		$xorder = array();
		$yorder = array();
		$xmin = '';
		$ymin = '';
		foreach($array as $row)
		{
			if($row['number_children'] > 0)
			{
				$xml .= "<set y='".round($row['Margin'])."' x='".round($row['Income'])."' z='".round($row['MarginD'])."'  name='".$row['name_element']."' link='newchart-xml-".$row['name_element']."' toolText='Product: ".$row['name_element']."{br}Values: Actual | Target | Variance{br}Revenue: $".number_format($row['Income'], 0, '.', ',')." | $".number_format($row['Income_t'], 0, '.', ',')." | $".number_format(($row['Income']-$row['Income_t']), 0, '.', ',')."{br}Gross Margin: $".number_format($row['MarginD'], 0, '.', ',')." | $".number_format($row['MarginD_t'], 0, '.', ',')." | $".number_format(($row['MarginD']-$row['MarginD_t']), 0, '.', ',')."{br}Gross Margin Rate: ".number_format($row['Margin'], 0, '.', ',')."% | ".number_format($row['Margin_t'], 0, '.', ',')."% | ".number_format(($row['Margin']-$row['Margin_t']), 0, '.', ',')."%' ></set>";
				$temp .= $this->xy_xml_child($array, $xml, $row['element'], $row['name_element']);
				$xorder[] = abs(round($row['Income']));
				$yorder[] = round($row['Margin']);
			}
			else if($row['number_children'] == 0)
			{
				$parents = explode(",", $row['parents']);
				foreach($parents as $prow)
				{
					if($parentid == $prow)
					{
						$xml .= "<set y='".round($row['Margin'])."' x='".round($row['Income'])."' z='".round($row['MarginD'])."'  name='".$row['name_element']."' toolText='Product: ".$row['name_element']."{br}Values: Actual | Target | Variance{br}Revenue: $".number_format($row['Income'], 0, '.', ',')." | $".number_format($row['Income_t'], 0, '.', ',')." | $".number_format(($row['Income']-$row['Income_t']), 0, '.', ',')."{br}Gross Margin: $".number_format($row['MarginD'], 0, '.', ',')." | $".number_format($row['MarginD_t'], 0, '.', ',')." | $".number_format(($row['MarginD']-$row['MarginD_t']), 0, '.', ',')."{br}Gross Margin Rate: ".number_format($row['Margin'], 0, '.', ',')."% | ".number_format($row['Margin_t'], 0, '.', ',')."% | ".number_format(($row['Margin']-$row['Margin_t']), 0, '.', ',')."%' link='JavaScript:ddown(".$row['element'].");' ></set>";
						$xorder[] = abs(round($row['Income']));
						$yorder[] = round($row['Margin']);
					}
					
				}
			}
		}
		sort($xorder);
		sort($yorder);
		if(count($xorder) == 1)
		{
			$xmin = "xAxisMinValue='0' xAxisMaxValue='".($xorder[0]*2)."'";
		}
		else
		{
			if(end($xorder) == $xorder[0])
			{
				$dif = $xorder[0]*0.20;
			}
			else
			{
				$dif = (end($xorder) - $xorder[0])*0.20;
			}
			$xmin = "xAxisMinValue='".($xorder[0]-$dif)."' xAxisMaxValue='".(end($xorder)+$dif)."'";
			//$xmin = "xAxisMinValue='0' xAxisMaxValue='1000000'";
		}
		
		if(count($yorder) == 1)
		{
			$ymin = "yAxisMinValue='0' yAxisMaxValue='".($yorder[0]*2)."'";
		}
		else
		{
			//$dif = end($yorder) * 1.20;
			//$ymin = "yAxisMinValue='".($dif*-1)."' yAxisMaxValue='".$dif."'";
			if(end($yorder) == $yorder[0])
			{
				$dif = $yorder[0]*0.20;
			}
			else
			{
				$dif = (end($yorder) - $yorder[0])*0.20;
			}
			$ymin = "yAxisMinValue='".($yorder[0]-$dif)."' yAxisMaxValue='".(end($yorder)+$dif)."'";
		}
		
		$xmlhead = "<chart caption='' baseFontColor='000000' is3D='1' bgColor='FFFFFF' showBorder='0' showPlotBorder='0' ".$xmin." ".$ymin."  xAxisName='Actual Revenue' yAxisName='Actual Gross Margin %' xNumberPrefix='$' yNumberSuffix='%' negativeColor='FF6600' paletteColors='0066FF' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0' canvasBorderAlpha='0' adjustDiv='0' numDivLines='3' adjustVDiv='0' numVDivlines='5' showAlternateHGridColor='0' showAlternateVGridColor='0' showZeroPlane='0' showVZeroPlane='0'><dataSet showValues='1'>";
		$xml .= "</dataSet>";
		$xml .= $temp;
		$xml .= "</chart>";
		$xml = $xmlhead.$xml;
		return $xml;
	}
	
	private function xy_xml_child($array, $xml, $id, $name)
	{
		$xml = '';
		$temp = '';
		$xorder = array();
		$yorder = array();
		$xmin = '';
		$ymin = '';
		foreach($array as $row)
		{
			$parents = explode(",", $row['parents']);
			foreach($parents as $prow)
			{
				if($row['number_children'] > 0 && $id == $prow)
				{
					$xml .= "<set y='".round($row['Margin'])."' x='".round($row['Income'])."' z='".round($row['MarginD'])."'  name='".$row['name_element']."' link='newchart-xml-".$row['name_element']."' toolText='Product: ".$row['name_element']."{br}Values: Actual | Target | Variance{br}Revenue: $".number_format($row['Income'], 0, '.', ',')." | $".number_format($row['Income_t'], 0, '.', ',')." | $".number_format(($row['Income']-$row['Income_t']), 0, '.', ',')."{br}Gross Margin: $".number_format($row['MarginD'], 0, '.', ',')." | $".number_format($row['MarginD_t'], 0, '.', ',')." | $".number_format(($row['MarginD']-$row['MarginD_t']), 0, '.', ',')."{br}Gross Margin Rate: ".number_format($row['Margin'], 0, '.', ',')."% | ".number_format($row['Margin_t'], 0, '.', ',')."% | ".number_format(($row['Margin']-$row['Margin_t']), 0, '.', ',')."%' ></set>";
					$temp .= $this->xy_xml_child($array, $xml, $row['element'], $row['name_element']);
					$xorder[] = abs(round($row['Income']));
					$yorder[] = round($row['Margin']);
					
				} 
				else if($row['number_children'] == 0 && $id == $prow)
				{
					$xml .= "<set y='".round($row['Margin'])."' x='".round($row['Income'])."' z='".round($row['MarginD'])."'  name='".$row['name_element']."' toolText='Product: ".$row['name_element']."{br}Values: Actual | Target | Variance{br}Revenue: $".number_format($row['Income'], 0, '.', ',')." | $".number_format($row['Income_t'], 0, '.', ',')." | $".number_format(($row['Income']-$row['Income_t']), 0, '.', ',')."{br}Gross Margin: $".number_format($row['MarginD'], 0, '.', ',')." | $".number_format($row['MarginD_t'], 0, '.', ',')." | $".number_format(($row['MarginD']-$row['MarginD_t']), 0, '.', ',')."{br}Gross Margin Rate: ".number_format($row['Margin'], 0, '.', ',')."% | ".number_format($row['Margin_t'], 0, '.', ',')."% | ".number_format(($row['Margin']-$row['Margin_t']), 0, '.', ',')."%' link='JavaScript:ddown(".$row['element'].");' ></set>";
					$xorder[] = abs(round($row['Income']));
					$yorder[] = round($row['Margin']);
				}
			}
			
		}
		sort($xorder);
		sort($yorder);
		if(count($xorder) == 1)
		{
			$xmin = "xAxisMinValue='0' xAxisMaxValue='".($xorder[0]*2)."'";
		}
		else
		{
			if(end($xorder) == $xorder[0])
			{
				$dif = $xorder[0]*0.20;
			}
			else
			{
				$dif = (end($xorder) - $xorder[0])*0.20;
			}
			$xmin = "xAxisMinValue='".($xorder[0]-$dif)."' xAxisMaxValue='".(end($xorder)+$dif)."'";
			//$xmin = "xAxisMinValue='0' xAxisMaxValue='1000000'";
		}
		
		if(count($yorder) == 1)
		{
			$ymin = "yAxisMinValue='0' yAxisMaxValue='".($yorder[0]*2)."'";
		}
		else
		{
			//$dif = end($yorder) * 1.20;
			//$ymin = "yAxisMinValue='".($dif*-1)."' yAxisMaxValue='".$dif."'";
			if(end($yorder) == $yorder[0])
			{
				$dif = $yorder[0]*0.20;
			}
			else
			{
				$dif = (end($yorder) - $yorder[0])*0.20;
			}
			$ymin = "yAxisMinValue='".($yorder[0]-$dif)."' yAxisMaxValue='".(end($yorder)+$dif)."'";
		}
		$xmlhead = "<linkeddata id='".$name."'><chart caption='' baseFontColor='000000' is3D='1' bgColor='FFFFFF' showBorder='0' showPlotBorder='0' subcaption='".$name."' ".$xmin." ".$ymin." adjustDiv='0' numDivLines='3' adjustVDiv='0' numVDivlines='5' xAxisName='Actual Revenue' yAxisName='Actual Gross Margin %'  xNumberPrefix='$' yNumberSuffix='%' negativeColor='FF6600' paletteColors='0066FF' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0' canvasBorderAlpha='0' showAlternateHGridColor='0' showAlternateVGridColor='0' showZeroPlane='0' showVZeroPlane='0'><dataSet showValues='1'>";
		
		$xml .= "</dataSet>";
		$xml .= "</chart></linkeddata>";
		$xml = $xmlhead.$xml;
		$xml .= $temp;
		return $xml;
	}
	
	private function custom_sort_asc ($a, $b)
	{
		return $a['value'] - $b['value'];
	}
	private function custom_sort_desc ($a, $b)
	{
		return $b['value'] - $a['value'];
	}
	
	private function custom_sort_asc_m ($a, $b)
	{
		return $a['MarginD'] - $b['MarginD'];
	}
	private function custom_sort_desc_m ($a, $b)
	{
		return $b['MarginD'] - $a['MarginD'];
	} 
	
	private function custom_row($array, $series, $col_id, $prefix = '', $switch = 0, $noswitch = 0)
	{
		$table = '';
		$v002 = 0;
		$v003 = 0;
		$at = 0;
		foreach($array as $row)
		{
			$path = explode(",", $row['path']);
			foreach($series as $srow)
			{
				if($srow['element'] == $path[$col_id])
				{
					if($row['value'] == '')
					{
						$row['value'] = 0;
					}
					$table .= "<td>".$prefix." ".number_format($row['value'], 0, '.', ',')."</td>";
					if($srow['name_element'] == "V002")
					{
						$v002 = $row['value'];
					} 
					else if($srow['name_element'] == "V003")
					{
						$v003 = $row['value'];
					}
				}
			}
		}
		if($noswitch == 0)
		{
			if($switch == 0)
			{
				$at = round($v002)-round($v003);
			} 
			else
			{
				$at = round($v003)-round($v002);
			}
			
			$table .= "<td>".$prefix." ".number_format($at, 0, '.', ',')."</td>";
		}
		else 
		{
			$table .= "<td>&nbsp;</td>";
		}
		
		return $table;
	}
	
	private function custom_row_p($array, $series, $col_id, $suffix = '')
	{
		$table = '';
		foreach($array as $row)
		{
			$path = explode(",", $row['path']);
			foreach($series as $srow)
			{
				if($row['value'] == '')
				{
					$row['value'] = 0;
				}
				if($srow['element'] == $path[$col_id])
				{
					$table .= "<td>".number_format($row['value'], 0, '.', ',')."".$suffix."</td>";
					
				}
			}
		}
		return $table;
	}
	
	private function xy_xml2($array, $parentid)
	{
		$xml = "";
		$temp = '';
		$xorder = array();
		$yorder = array();
		$xmin = '';
		$ymin = '';
		foreach($array as $row)
		{
			if($row['number_children'] > 0)
			{
				$xml .= "<set y='".round($row['Margin'])."' x='".round($row['Income'])."' z='".round($row['MarginD'])."'  name='".$row['name_element']."' link='newchart-xml-".$row['name_element']."' toolText='Customer: ".$row['name_element']."{br}Values: Actual | Target | Variance{br}Revenue: $".number_format($row['Income'], 0, '.', ',')." | $".number_format($row['Income_t'], 0, '.', ',')." | $".number_format(($row['Income']-$row['Income_t']), 0, '.', ',')."{br}Gross Margin: $".number_format($row['MarginD'], 0, '.', ',')." | $".number_format($row['MarginD_t'], 0, '.', ',')." | $".number_format(($row['MarginD']-$row['MarginD_t']), 0, '.', ',')."{br}Gross Margin Rate: ".number_format($row['Margin'], 0, '.', ',')."% | ".number_format($row['Margin_t'], 0, '.', ',')."% | ".number_format(($row['Margin']-$row['Margin_t']), 0, '.', ',')."%' ></set>";
				$temp .= $this->xy_xml2_child($array, $xml, $row['element'], $row['name_element']);
				$xorder[] = abs(round($row['Income']));
				$yorder[] = round($row['Margin']);
			}
			else if($row['number_children'] == 0)
			{
				$parents = explode(",", $row['parents']);
				foreach($parents as $prow)
				{
					if($parentid == $prow)
					{
						$xml .= "<set y='".round($row['Margin'])."' x='".round($row['Income'])."' z='".round($row['MarginD'])."'  name='".$row['name_element']."' toolText='Customer: ".$row['name_element']."{br}Values: Actual | Target | Variance{br}Revenue: $".number_format($row['Income'], 0, '.', ',')." | $".number_format($row['Income_t'], 0, '.', ',')." | $".number_format(($row['Income']-$row['Income_t']), 0, '.', ',')."{br}Gross Margin: $".number_format($row['MarginD'], 0, '.', ',')." | $".number_format($row['MarginD_t'], 0, '.', ',')." | $".number_format(($row['MarginD']-$row['MarginD_t']), 0, '.', ',')."{br}Gross Margin Rate: ".number_format($row['Margin'], 0, '.', ',')."% | ".number_format($row['Margin_t'], 0, '.', ',')."% | ".number_format(($row['Margin']-$row['Margin_t']), 0, '.', ',')."%' link='JavaScript:ddown1(".$row['element'].");' ></set>";
						$xorder[] = abs(round($row['Income']));
						$yorder[] = round($row['Margin']);
					}
					
				}
			}
		}
		sort($xorder);
		sort($yorder);
		if(count($xorder) == 1)
		{
			$xmin = "xAxisMinValue='0' xAxisMaxValue='".($xorder[0]*2)."'";
		}
		else
		{
			if(end($xorder) == $xorder[0])
			{
				$dif = $xorder[0]*0.20;
			}
			else
			{
				$dif = (end($xorder) - $xorder[0])*0.20;
			}
			$xmin = "xAxisMinValue='".($xorder[0]-$dif)."' xAxisMaxValue='".(end($xorder)+$dif)."'";
			//$xmin = "xAxisMinValue='0' xAxisMaxValue='1000000'";
		}
		
		if(count($yorder) == 1)
		{
			$ymin = "yAxisMinValue='0' yAxisMaxValue='".($yorder[0]*2)."'";
		}
		else
		{
			//$dif = end($yorder) * 1.20;
			//$ymin = "yAxisMinValue='".($dif*-1)."' yAxisMaxValue='".$dif."'";
			if(end($yorder) == $yorder[0])
			{
				$dif = $yorder[0]*0.20;
			}
			else
			{
				$dif = (end($yorder) - $yorder[0])*0.20;
			}
			$ymin = "yAxisMinValue='".($yorder[0]-$dif)."' yAxisMaxValue='".(end($yorder)+$dif)."'";
		}
		
		$xmlhead = "<chart caption='' is3D='1' baseFontColor='000000' bgColor='FFFFFF' showBorder='0' showPlotBorder='0' ".$xmin." ".$ymin." adjustDiv='0' numDivLines='3' adjustVDiv='0' numVDivlines='5' xAxisName='Actual Revenue' yAxisName='Actual Gross Margin %' xNumberPrefix='$' yNumberSuffix='%' negativeColor='FF6600' paletteColors='0066FF' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0' canvasBorderAlpha='0' showAlternateHGridColor='0' showAlternateVGridColor='0' showZeroPlane='0' showVZeroPlane='0'><dataSet showValues='1'>";
		$xml .= "</dataSet>";
		$xml .= $temp;
		$xml .= "</chart>";
		$xml = $xmlhead.$xml;
		return $xml;
	}
	
	private function xy_xml2_child($array, $xml, $id, $name)
	{
		$xml = '';
		$temp = '';
		$xorder = array();
		$yorder = array();
		$xmin = '';
		$ymin = '';
		foreach($array as $row)
		{
			$parents = explode(",", $row['parents']);
			foreach($parents as $prow)
			{
				if($row['number_children'] > 0 && $id == $prow)
				{
					$xml .= "<set y='".round($row['Margin'])."' x='".round($row['Income'])."' z='".round($row['MarginD'])."'  name='".$row['name_element']."' link='newchart-xml-".$row['name_element']."' toolText='Customer: ".$row['name_element']."{br}Values: Actual | Target | Variance{br}Revenue: $".number_format($row['Income'], 0, '.', ',')." | $".number_format($row['Income_t'], 0, '.', ',')." | $".number_format(($row['Income']-$row['Income_t']), 0, '.', ',')."{br}Gross Margin: $".number_format($row['MarginD'], 0, '.', ',')." | $".number_format($row['MarginD_t'], 0, '.', ',')." | $".number_format(($row['MarginD']-$row['MarginD_t']), 0, '.', ',')."{br}Gross Margin Rate: ".number_format($row['Margin'], 0, '.', ',')."% | ".number_format($row['Margin_t'], 0, '.', ',')."% | ".number_format(($row['Margin']-$row['Margin_t']), 0, '.', ',')."%' ></set>";
					$temp .= $this->xy_xml2_child($array, $xml, $row['element'], $row['name_element']);
					$xorder[] = abs(round($row['Income']));
					$yorder[] = round($row['Margin']);
					
				} 
				else if($row['number_children'] == 0 && $id == $prow)
				{
					$xml .= "<set y='".round($row['Margin'])."' x='".round($row['Income'])."' z='".round($row['MarginD'])."'  name='".$row['name_element']."' toolText='Customer: ".$row['name_element']."{br}Values: Actual | Target | Variance{br}Revenue: $".number_format($row['Income'], 0, '.', ',')." | $".number_format($row['Income_t'], 0, '.', ',')." | $".number_format(($row['Income']-$row['Income_t']), 0, '.', ',')."{br}Gross Margin: $".number_format($row['MarginD'], 0, '.', ',')." | $".number_format($row['MarginD_t'], 0, '.', ',')." | $".number_format(($row['MarginD']-$row['MarginD_t']), 0, '.', ',')."{br}Gross Margin Rate: ".number_format($row['Margin'], 0, '.', ',')."% | ".number_format($row['Margin_t'], 0, '.', ',')."% | ".number_format(($row['Margin']-$row['Margin_t']), 0, '.', ',')."%' link='JavaScript:ddown1(".$row['element'].");' ></set>";
					$xorder[] = abs(round($row['Income']));
					$yorder[] = round($row['Margin']);
				}
			}
			
		}
		sort($xorder);
		sort($yorder);
		if(count($xorder) == 1)
		{
			$xmin = "xAxisMinValue='0' xAxisMaxValue='".($xorder[0]*2)."'";
		}
		else
		{
			if(end($xorder) == $xorder[0])
			{
				$dif = $xorder[0]*0.20;
			}
			else
			{
				$dif = (end($xorder) - $xorder[0])*0.20;
			}
			$xmin = "xAxisMinValue='".($xorder[0]-$dif)."' xAxisMaxValue='".(end($xorder)+$dif)."'";
			//$xmin = "xAxisMinValue='0' xAxisMaxValue='1000000'";
		}
		
		if(count($yorder) == 1)
		{
			$ymin = "yAxisMinValue='0' yAxisMaxValue='".($yorder[0]*2)."'";
		}
		else
		{
			//$dif = end($yorder) * 1.20;
			//$ymin = "yAxisMinValue='".($dif*-1)."' yAxisMaxValue='".$dif."'";
			if(end($yorder) == $yorder[0])
			{
				$dif = $yorder[0]*0.20;
			}
			else
			{
				$dif = (end($yorder) - $yorder[0])*0.20;
			}
			$ymin = "yAxisMinValue='".($yorder[0]-$dif)."' yAxisMaxValue='".(end($yorder)+$dif)."'";
		}
		$xmlhead = "<linkeddata id='".$name."'><chart caption='' is3D='1' baseFontColor='000000' bgColor='FFFFFF' showBorder='0' subcaption='".$name."' ".$xmin." ".$ymin." adjustDiv='0' numDivLines='3' adjustVDiv='0' numVDivlines='5' xAxisName='Actual Revenue' yAxisName='Actual Gross Margin %'  xNumberPrefix='$' yNumberSuffix='%' negativeColor='FF6600' paletteColors='0066FF' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0' canvasBorderAlpha='0' showAlternateHGridColor='0' showAlternateVGridColor='0' showZeroPlane='0' showVZeroPlane='0'><dataSet showValues='1'>";
		
		$xml .= "</dataSet>";
		$xml .= "</chart></linkeddata>";
		$xml = $xmlhead.$xml;
		$xml .= $temp;
		return $xml;
	}
	
	private function single_xml_custom($label, $array, $ver_a, $ver_t)
	{
		$target = '';
		$actual = '';
		$color = '0066FF';
		foreach($array as $rows){
			$cat_identifier = explode(',',$rows['path']);
			if($ver_a == $cat_identifier[0])
			{
				$actual = round($rows['value']);
			} 
			else if($ver_t == $cat_identifier[0])
			{
				$target = round($rows['value']);
			}
		}
		$value = round($actual - $target);
		if($value <= 0)
		{
			$color = 'FF6600';
		}
		
		if($target == 0 || $actual == 0)
		{
			$varper = 0;
		}
		else
		{
			$varper = round($value/$actual*100);
		}
		$data = "<set label='".$label."' value='".$value."' color='".$color."' toolText='".$label."{br}Actual: $".number_format($actual, 0, '.', ',')."{br}Target: $".number_format($target, 0, '.', ',')."{br}Variance: $".number_format($value, 0, '.', ',')."{br}Variance %: ".$varper."%'></set>";
		return $data;
	}
	
	private function single_xml_custom_array($array)
	{
		$data = '';
		foreach($array as $row)
		{
			$color = '0066FF';
			if($row['MarginD'] <= 0)
			{
				$color = 'FF6600';
			}
			$data .= "<set label='".$row['name_element']."' color='".$color."' value='".round($row['MarginD'])."' toolText='".$row['name_element']."{br}Actual: $".number_format($row['MarginD'], 0, '.', ',')."{br}Target: $".number_format($row['MarginD_t'], 0, '.', ',')."{br}Variance: $".number_format($row['MarginD_v'], 0, '.', ',')."'></set>";
		}
		return $data;
	}
	
	private function gm_variance($array)
	{
		$data = array();
		foreach($array as $row)
		{
			$row['MarginD_v'] = $row['MarginD'] - $row['MarginD_t'];
			if($row['number_children'] == 0)
			{
				$data[] = $row;
			}
		}
		return $data;
	}
	
	private function gm_variance_table($array)
	{
		$table = '';
		$count = 0;
		$limit = 10;
		foreach($array as $row)
		{
			if($count < $limit)
			{
				//$table .= "<tr><td class='label2'>".$row['name_element']."</td><td>$".number_format($row['MarginD'], 0, '.', ',')."</td><td>$".number_format($row['MarginD_t'], 0, '.', ',')."</td><td>$".number_format($row['MarginD_v'], 0, '.', ',')."</td></tr>";
				$table .= "<tr><td class='label2'>".$row['name_element']."</td><td>$".number_format($row['MarginD'], 0, '.', ',')."</td><td>$".number_format($row['MarginD_v'], 0, '.', ',')."</td></tr>";
				$count += 1;
			}
		}
		return $table;
	}
	
}
