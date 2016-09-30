<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Efficiency_Costs extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index ()
    {
        $pagename = "proEO Efficiency Costs";
        $oneliner = "One-liner here for Efficiency costs";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_costs") == FALSE)
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
            $cube_names = "Primary,Secondary,#_Version,#_Month,#_Primary_Value,#_Account_Element,#_Receiver,#_Secondary_Value,#_Sender,#_Year";
            
            // Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $receiver = $this->input->post("receiver");
            
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
            $primary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Primary");
            $secondary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Secondary");
            
            $primary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Primary");
            $secondary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Secondary");
            
            ////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
            // VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
            
            // YEAR //
            // Get dimension of year
            //$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[1]);
			$year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[1]);
            $year_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Year");
            $year_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Year");
			$year_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $year_dimension_id[0]);
			$year_alias_name_id = $this->jedoxapi->get_area($year_alias_elements, "Name");
            $cells_year_alias = $this->jedoxapi->cell_export($server_database['database'],$year_alias_info['cube'],10000,"",$year_alias_name_id.",*");
            
            // MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
            
            // PRIMARY VALUE //
            // Get dimension of primary_value
            $primary_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[3]);
            // Get cube data of primary_value alias
            $primary_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Primary_Value");
            $primary_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Primary_Value");
            // Export cells of primary_value alias
            $primary_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_value_dimension_id[0]);
			$primary_value_alias_name_id = $this->jedoxapi->get_area($primary_value_alias_elements, "Name");
            $cells_primary_value_alias = $this->jedoxapi->cell_export($server_database['database'],$primary_value_alias_info['cube'],10000,"", $primary_value_alias_name_id.",*"); 
            
            // ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[4]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*"); 
            
            // RECEIVER //
            // Get dimension of receiver
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[5]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*"); 
            
            // SECONDARY VALUE //
            // Get dimension of secondary value
            $secondary_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $secondary_dimension_id[3]);
            // Get cube data of secondary value alias
            $secondary_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Secondary_Value");
            $secondary_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Secondary_Value");
            // Export cells of secondary value alias
            $secondary_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $secondary_value_dimension_id[0]);
			$secondary_value_alias_name_id = $this->jedoxapi->get_area($secondary_value_alias_elements, "Name");
            $cells_secondary_value_alias = $this->jedoxapi->cell_export($server_database['database'],$secondary_value_alias_info['cube'],10000,"", $secondary_value_alias_name_id.",*");
            
            // SENDER //
            // Get dimension of sender
            $sender_elements = $this->jedoxapi->dimension_elements($server_database['database'], $secondary_dimension_id[4]);
            // Get cube data of sender alias
            $sender_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Sender");
            $sender_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Sender");
            // Export cells of sender value alias
            $sender_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $sender_dimension_id[0]);
			$sender_alias_name_id = $this->jedoxapi->get_area($sender_alias_elements, "Name");
            $cells_sender_alias = $this->jedoxapi->cell_export($server_database['database'],$sender_alias_info['cube'],10000,"", $sender_alias_name_id.",*"); 
            
            // FORM DATA //
            $form_year = $this->jedoxapi->array_element_filter($year_elements, "YA"); // all years
			$form_year = $this->jedoxapi->set_alias($form_year, $cells_year_alias);
            
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            $form_reciever = $this->jedoxapi->array_element_filter($receiver_elements, "AR"); 
            $form_reciever = $this->jedoxapi->set_alias($form_reciever, $cells_receiver_alias); // Set aliases
            
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
            if($receiver == '')
            {
                $receiver = $this->jedoxapi->get_area($receiver_elements, "SF");
            }
            
            ////////////////
            // Total cost //
            ////////////////
            
            $version_area = $this->jedoxapi->get_area($version_elements, "V001,V002,V003"); // Plan, Actual, Target
            $version_p = $this->jedoxapi->get_area($version_elements, "V001"); // plan
            $version_a = $this->jedoxapi->get_area($version_elements, "V002"); // actual
            $version_t = $this->jedoxapi->get_area($version_elements, "V003"); // target
            $primary_value_PC04_area = $this->jedoxapi->get_area($primary_value_elements, "PC04");
            $primary_value_PC_area = $this->jedoxapi->get_area($primary_value_elements, "PC");
            $account_element_elements_CE_5_area = $this->jedoxapi->get_area($account_element_elements, "CE_Primary");
            $secondary_value_elements_SC_area = $this->jedoxapi->get_area($secondary_value_elements, "SC");
            $sender_elements_AS_area = $this->jedoxapi->get_area($sender_elements, "AS");
            
            $tc1_area = $version_area.",".$year.",".$month.",".$primary_value_PC04_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $tc2_area = $version_area.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $tc3_area = $version_area.",".$year.",".$month.",".$secondary_value_elements_SC_area.",".$sender_elements_AS_area.",".$receiver;
            
            $tc1_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $tc1_area, "", "1", "", "0");
            $tc2_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $tc2_area, "", "1", "", "0");
            $tc3_cells = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $tc3_area, "", "1", "", "0"); // 3rd part
            
            $tc_sum = $this->jedoxapi->add_cell_array($tc1_cells, $tc2_cells, 0, 1);
            $tc_sum = $this->jedoxapi->add_cell_array($tc_sum, $tc3_cells, 0, 1);
            
            $pc_sum = $this->jedoxapi->add_cell_array($tc1_cells, $tc2_cells, 0, 1); // 1st part. raw data
            ////////////////
            // Drill Down //
            ////////////////
            
            $account_element_elements_CE_5full = $this->jedoxapi->array_element_filter($account_element_elements, "CE_Primary");
            array_shift($account_element_elements_CE_5full);
            $account_element_elements_CE_5full_area = $this->jedoxapi->get_area($account_element_elements_CE_5full);
            $aecolumn_array = $this->jedoxapi->dimension_sort_by_name($version_elements, "V001,V002,V003");
            $account_element_elements_CE_5full_alias = $this->jedoxapi->set_alias($account_element_elements_CE_5full, $cells_account_element_alias);
            $sender_elements_child = $this->jedoxapi->array_element_filter($sender_elements, 'AS');
            array_shift($sender_elements_child);
            $sender_elements_area = $this->jedoxapi->get_area($sender_elements_child);
            $sender_elements_alias = $this->jedoxapi->set_alias($sender_elements_child, $cells_sender_alias);
            
            $ae1_area = $version_area.",".$year.",".$month.",".$primary_value_PC04_area.",".$account_element_elements_CE_5full_area.",".$receiver;
            $ae2_area = $version_area.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_elements_CE_5full_area.",".$receiver;
            
            $ae1_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $ae1_area, "", "1", "", "0");
            $ae2_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $ae2_area, "", "1", "", "0");
            
            $ae_sum = $this->jedoxapi->add_cell_array($ae1_cells, $ae2_cells, 0, 4); // 2nd part. raw data
            
            $ae_table = $this->jedox->to_table_row($ae_sum, $aecolumn_array, $account_element_elements_CE_5full_alias, 0, 4, "", TRUE, TRUE, TRUE, '', 'label2', '', TRUE);
            
            $se_area = $version_area.",".$year.",".$month.",".$secondary_value_elements_SC_area.",".$sender_elements_area.",".$receiver;
            
            $se = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $se_area, "", "1", "", "0"); // 4th part. raw data.
            $se_table = $this->jedox->to_table_row($se, $aecolumn_array, $sender_elements_alias, 0, 4, "", TRUE, TRUE, TRUE, '', 'label2', '', TRUE);
            
            ///////////
            // CHART //
            ///////////
            
            $month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
            $version_area_pa = $this->jedoxapi->get_area($version_elements, "V001,V002"); // Plan, Actual
            $version_elements_pa = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002");
            $version_elements_pa_alias = $this->jedoxapi->set_alias($version_elements_pa, $cells_version_alias);
            $version_area_a = $this->jedoxapi->get_area($version_elements, "V002"); // Actual
            $version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            $version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
            
            $current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year);
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
            
            $chart1xml = $this->jedox->multichart_xml_categories($month_all_alias, 1);
            
            $chart1a_area = $version_area_pa.",".$year.",".$month_all_area.",".$primary_value_PC04_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $chart1b_area = $version_area_pa.",".$year.",".$month_all_area.",".$primary_value_PC_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $chart1c_area = $version_area_pa.",".$year.",".$month_all_area.",".$secondary_value_elements_SC_area.",".$sender_elements_AS_area.",".$receiver;
            
            $chart1a_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1a_area, "", "1", "", "0");
            $chart1b_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1b_area, "", "1", "", "0");
            $chart1c_cells = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $chart1c_area, "", "1", "", "0");
            
            $chart_tc_sum = $this->jedoxapi->add_cell_array($chart1a_cells, $chart1b_cells, 0, 2);
            $chart_tc_sum = $this->jedoxapi->add_cell_array($chart_tc_sum, $chart1c_cells, 0, 2);
            
            $chart1xml .= $this->jedox->multichart_xml_series($chart_tc_sum, $month_all, $version_elements_pa_alias, 2, 0, "", " ".$current_year);
            
            // Area for previous year based on selected. Detect if there is a year before the "selected year"
            if($yearcheck != 0)
            {
                $chart1d_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$primary_value_PC04_area.",".$account_element_elements_CE_5_area.",".$receiver;
                $chart1e_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$primary_value_PC_area.",".$account_element_elements_CE_5_area.",".$receiver;
                $chart1f_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$secondary_value_elements_SC_area.",".$sender_elements_AS_area.",".$receiver;
            
                $chart1d_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1d_area, "", "1", "", "0");
                $chart1e_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1e_area, "", "1", "", "0");
                $chart1f_cells = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $chart1f_area, "", "1", "", "0");
                
                $chart_tc_sum1 = $this->jedoxapi->add_cell_array($chart1d_cells, $chart1e_cells, 0, 2);
                $chart_tc_sum1 = $this->jedoxapi->add_cell_array($chart_tc_sum1, $chart1f_cells, 0, 2);
                
                $chart1xml .= $this->jedox->multichart_xml_series($chart_tc_sum1, $month_all, $version_elements_a_alias, 2, 0, "", " ".$prev_year);
            }
            
            
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "form_year" => $form_year,
                "year" => $year,
                "form_months" => $form_months,
                "month" => $month,
                "form_reciever" => $form_reciever,
                "receiver" => $receiver,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "tc_sum" => $tc_sum,
                "ae_table" => $ae_table,
                "se_table" => $se_table,
                "tc3_cells" => $tc3_cells,
                "pc_sum" => $pc_sum,
                "ae_sum" => $ae_sum,
                "se" => $se,
                "version_p" => $version_p,
                "version_a" => $version_a,
                "version_t" => $version_t,
                "chart1xml" => $chart1xml,
                "account_element_elements_CE_5full_alias" => $account_element_elements_CE_5full_alias,
                "sender_elements_alias" => $sender_elements_alias
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_costs_view", $alldata);
        }
    }
    
    public function info ($year ='', $month ='', $receiver = '')
    {
        $pagename = "proEO Efficiency Costs";
        $oneliner = "One-liner here for Efficiency costs";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_costs") == FALSE)
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
            $cube_names = "Primary,Secondary,#_Version,#_Month,#_Primary_Value,#_Account_Element,#_Receiver,#_Secondary_Value,#_Sender";
            
            
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
            $primary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Primary");
            $secondary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Secondary");
            
            $primary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Primary");
            $secondary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Secondary");
            
            ////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
            // VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
            
            // YEAR //
            // Get dimension of year
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[1]);
            
            // MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
            
            // PRIMARY VALUE //
            // Get dimension of primary_value
            $primary_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[3]);
            // Get cube data of primary_value alias
            $primary_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Primary_Value");
            $primary_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Primary_Value");
            // Export cells of primary_value alias
            $primary_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_value_dimension_id[0]);
			$primary_value_alias_name_id = $this->jedoxapi->get_area($primary_value_alias_elements, "Name");
            $cells_primary_value_alias = $this->jedoxapi->cell_export($server_database['database'],$primary_value_alias_info['cube'],10000,"", $primary_value_alias_name_id.",*"); 
            
            // ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[4]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*"); 
            
            // RECEIVER //
            // Get dimension of receiver
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[5]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*"); 
            
            // SECONDARY VALUE //
            // Get dimension of secondary value
            $secondary_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $secondary_dimension_id[3]);
            // Get cube data of secondary value alias
            $secondary_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Secondary_Value");
            $secondary_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Secondary_Value");
            // Export cells of secondary value alias
            $secondary_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $secondary_value_dimension_id[0]);
			$secondary_value_alias_name_id = $this->jedoxapi->get_area($secondary_value_alias_elements, "Name");
            $cells_secondary_value_alias = $this->jedoxapi->cell_export($server_database['database'],$secondary_value_alias_info['cube'],10000,"", $secondary_value_alias_name_id.",*");
            
            // SENDER //
            // Get dimension of sender
            $sender_elements = $this->jedoxapi->dimension_elements($server_database['database'], $secondary_dimension_id[4]);
            // Get cube data of sender alias
            $sender_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Sender");
            $sender_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Sender");
            // Export cells of sender value alias
            $sender_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $sender_dimension_id[0]);
			$sender_alias_name_id = $this->jedoxapi->get_area($sender_alias_elements, "Name");
            $cells_sender_alias = $this->jedoxapi->cell_export($server_database['database'],$sender_alias_info['cube'],10000,"", $sender_alias_name_id.",*"); 
            
            // FORM DATA //
            $form_year = $year_elements;
            
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            $form_reciever = $this->jedoxapi->array_element_filter($receiver_elements, "AR"); 
            $form_reciever = $this->jedoxapi->set_alias($form_reciever, $cells_receiver_alias); // Set aliases
            
            /////////////
            // PRESETS //
            /////////////
            
            if($year == '')
            {
                $now = now();
                $tnow = mdate("%Y", $now);
                $year = $this->jedoxapi->get_area($year_elements, $tnow);
            }
            else
            {
                $year = $this->jedoxapi->get_area($year_elements, $year);
            }
            
            if($month == '')
            {
                $month = $this->jedoxapi->get_area($month_elements, "MA");
            }
            else
            {
                $hrmonth = $this->jedoxapi->set_alias($month_elements, $cells_month_alias);
                $month = $this->jedoxapi->get_area($hrmonth, $month, TRUE);
            }
            
            if($receiver == '')
            {
                $receiver = $this->jedoxapi->get_area($receiver_elements, "SF");
            }
            else
            {
                $hrreceiver = $this->jedoxapi->set_alias($receiver_elements, $cells_receiver_alias);
                $receiver = $this->jedoxapi->get_area($hrreceiver, $receiver, TRUE);
            }
            
            ////////////////
            // Total cost //
            ////////////////
            
            $version_area = $this->jedoxapi->get_area($version_elements, "V001,V002,V003"); // Plan, Actual, Target
            $primary_value_PC04_area = $this->jedoxapi->get_area($primary_value_elements, "PC04");
            $primary_value_PC_area = $this->jedoxapi->get_area($primary_value_elements, "PC");
            $account_element_elements_CE_5_area = $this->jedoxapi->get_area($account_element_elements, "CE_5");
            $secondary_value_elements_SC_area = $this->jedoxapi->get_area($secondary_value_elements, "SC");
            $sender_elements_AS_area = $this->jedoxapi->get_area($sender_elements, "AS");
            
            $tc1_area = $version_area.",".$year.",".$month.",".$primary_value_PC04_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $tc2_area = $version_area.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $tc3_area = $version_area.",".$year.",".$month.",".$secondary_value_elements_SC_area.",".$sender_elements_AS_area.",".$receiver;
            
            $tc1_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $tc1_area, "", "1", "", "0");
            $tc2_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $tc2_area, "", "1", "", "0");
            $tc3_cells = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $tc3_area, "", "1", "", "0");
            
            $tc_sum = $this->jedoxapi->add_cell_array($tc1_cells, $tc2_cells, 0, 1);
            $tc_sum = $this->jedoxapi->add_cell_array($tc_sum, $tc3_cells, 0, 1);
            
            $pc_sum = $this->jedoxapi->add_cell_array($tc1_cells, $tc2_cells, 0, 1);
            ////////////////
            // Drill Down //
            ////////////////
            
            $account_element_elements_CE_5full = $this->jedoxapi->array_element_filter($account_element_elements, "CE_5");
            array_shift($account_element_elements_CE_5full);
            $account_element_elements_CE_5full_area = $this->jedoxapi->get_area($account_element_elements_CE_5full);
            $aecolumn_array = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002,V003");
            $account_element_elements_CE_5full_alias = $this->jedoxapi->set_alias($account_element_elements_CE_5full, $cells_account_element_alias);
            $sender_elements_child = $this->jedoxapi->array_element_filter($sender_elements, 'AS');
            array_shift($sender_elements_child);
            $sender_elements_area = $this->jedoxapi->get_area($sender_elements_child);
            $sender_elements_alias = $this->jedoxapi->set_alias($sender_elements_child, $cells_sender_alias);
            
            $ae1_area = $version_area.",".$year.",".$month.",".$primary_value_PC04_area.",".$account_element_elements_CE_5full_area.",".$receiver;
            $ae2_area = $version_area.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_elements_CE_5full_area.",".$receiver;
            
            $ae1_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $ae1_area, "", "1", "", "0");
            $ae2_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $ae2_area, "", "1", "", "0");
            
            $ae_sum = $this->jedoxapi->add_cell_array($ae1_cells, $ae2_cells, 0, 4);
            
            $ae_table = $this->jedox->to_table_row($ae_sum, $aecolumn_array, $account_element_elements_CE_5full_alias, 0, 4, "", TRUE, TRUE, TRUE, '', 'label2', '', TRUE);
            
            $se_area = $version_area.",".$year.",".$month.",".$secondary_value_elements_SC_area.",".$sender_elements_area.",".$receiver;
            
            $se = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $se_area, "", "1", "", "0");
            $se_table = $this->jedox->to_table_row($se, $aecolumn_array, $sender_elements_alias, 0, 4, "", TRUE, TRUE, TRUE, '', 'label2', '', TRUE);
            
            ///////////
            // CHART //
            ///////////
            
            $month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
            $version_area_pa = $this->jedoxapi->get_area($version_elements, "V001,V002"); // Plan, Actual
            $version_elements_pa = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002");
            $version_elements_pa_alias = $this->jedoxapi->set_alias($version_elements_pa, $cells_version_alias);
            $version_area_a = $this->jedoxapi->get_area($version_elements, "V002"); // Actual
            $version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            $version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
            
            $current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year);
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
            
            $chart1xml = $this->jedox->multichart_xml_categories($month_all_alias, 1);
            
            $chart1a_area = $version_area_pa.",".$year.",".$month_all_area.",".$primary_value_PC04_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $chart1b_area = $version_area_pa.",".$year.",".$month_all_area.",".$primary_value_PC_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $chart1c_area = $version_area_pa.",".$year.",".$month_all_area.",".$secondary_value_elements_SC_area.",".$sender_elements_AS_area.",".$receiver;
            
            $chart1a_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1a_area, "", "1", "", "0");
            $chart1b_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1b_area, "", "1", "", "0");
            $chart1c_cells = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $chart1c_area, "", "1", "", "0");
            
            $chart_tc_sum = $this->jedoxapi->add_cell_array($chart1a_cells, $chart1b_cells, 0, 2);
            $chart_tc_sum = $this->jedoxapi->add_cell_array($chart_tc_sum, $chart1c_cells, 0, 2);
            
            $chart1xml .= $this->jedox->multichart_xml_series($chart_tc_sum, $month_all, $version_elements_pa_alias, 2, 0, "", " ".$current_year);
            
            // Area for previous year based on selected. Detect if there is a year before the "selected year"
            if($yearcheck != 0)
            {
                $chart1d_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$primary_value_PC04_area.",".$account_element_elements_CE_5_area.",".$receiver;
                $chart1e_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$primary_value_PC_area.",".$account_element_elements_CE_5_area.",".$receiver;
                $chart1f_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$secondary_value_elements_SC_area.",".$sender_elements_AS_area.",".$receiver;
            
                $chart1d_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1d_area, "", "1", "", "0");
                $chart1e_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1e_area, "", "1", "", "0");
                $chart1f_cells = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $chart1f_area, "", "1", "", "0");
                
                $chart_tc_sum1 = $this->jedoxapi->add_cell_array($chart1d_cells, $chart1e_cells, 0, 2);
                $chart_tc_sum1 = $this->jedoxapi->add_cell_array($chart_tc_sum1, $chart1f_cells, 0, 2);
                
                $chart1xml .= $this->jedox->multichart_xml_series($chart_tc_sum1, $month_all, $version_elements_a_alias, 2, 0, "", " ".$prev_year);
            }
            
            
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "form_year" => $form_year,
                "year" => $year,
                "form_months" => $form_months,
                "month" => $month,
                "form_reciever" => $form_reciever,
                "receiver" => $receiver,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "oneliner" => $oneliner,
                "tc_sum" => $tc_sum,
                "ae_table" => $ae_table,
                "se_table" => $se_table,
                "tc3_cells" => $tc3_cells,
                "pc_sum" => $pc_sum,
                "chart1xml" => $chart1xml
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_costs_view", $alldata);
        }
    }
    
    public function chart1 ()
    {
        $pagename = "proEO Efficiency Costs";
        $chart_name = "Total Costs";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
        else if($this->jedoxapi->page_permission($user_details['group_names'], "efficiency_costs") == FALSE)
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
            $cube_names = "Primary,Secondary,#_Version,#_Month,#_Primary_Value,#_Account_Element,#_Receiver,#_Secondary_Value,#_Sender";
            
            // Initialize post data //
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $receiver = $this->input->post("receiver");
            
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
            $primary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Primary");
            $secondary_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Secondary");
            
            $primary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Primary");
            $secondary_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Secondary");
            
            ////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
            
            // VERSION //
            // Get dimension of version
            $version_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[0]);
            // Get cube data of version alias
            $version_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Version");
            $version_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Version");
            // Export cells of version alias
            $version_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $version_dimension_id[0]);
			$version_alias_name_id = $this->jedoxapi->get_area($version_alias_elements, "Name");
            $cells_version_alias = $this->jedoxapi->cell_export($server_database['database'],$version_alias_info['cube'],10000,"",$version_alias_name_id.",*");
            
            // YEAR //
            // Get dimension of year
            $year_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[1]);
            
            // MONTH //
            // Get dimension of month
            $month_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[2]);
            // Get cube data of month alias
            $month_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Month");
            $month_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Month");
            // Export cells of month alias
            $month_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $month_dimension_id[0]);
			$month_alias_name_id = $this->jedoxapi->get_area($month_alias_elements, "Name");
            $cells_month_alias = $this->jedoxapi->cell_export($server_database['database'],$month_alias_info['cube'],10000,"", $month_alias_name_id.",*");
            
            // PRIMARY VALUE //
            // Get dimension of primary_value
            $primary_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[3]);
            // Get cube data of primary_value alias
            $primary_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Primary_Value");
            $primary_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Primary_Value");
            // Export cells of primary_value alias
            $primary_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_value_dimension_id[0]);
			$primary_value_alias_name_id = $this->jedoxapi->get_area($primary_value_alias_elements, "Name");
            $cells_primary_value_alias = $this->jedoxapi->cell_export($server_database['database'],$primary_value_alias_info['cube'],10000,"", $primary_value_alias_name_id.",*"); 
            
            // ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[4]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*"); 
            
            // RECEIVER //
            // Get dimension of receiver
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $primary_dimension_id[5]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*"); 
            
            // SECONDARY VALUE //
            // Get dimension of secondary value
            $secondary_value_elements = $this->jedoxapi->dimension_elements($server_database['database'], $secondary_dimension_id[3]);
            // Get cube data of secondary value alias
            $secondary_value_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Secondary_Value");
            $secondary_value_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Secondary_Value");
            // Export cells of secondary value alias
            $secondary_value_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $secondary_value_dimension_id[0]);
			$secondary_value_alias_name_id = $this->jedoxapi->get_area($secondary_value_alias_elements, "Name");
            $cells_secondary_value_alias = $this->jedoxapi->cell_export($server_database['database'],$secondary_value_alias_info['cube'],10000,"", $secondary_value_alias_name_id.",*");
            
            // SENDER //
            // Get dimension of sender
            $sender_elements = $this->jedoxapi->dimension_elements($server_database['database'], $secondary_dimension_id[4]);
            // Get cube data of sender alias
            $sender_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Sender");
            $sender_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Sender");
            // Export cells of sender value alias
            $sender_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $sender_dimension_id[0]);
			$sender_alias_name_id = $this->jedoxapi->get_area($sender_alias_elements, "Name");
            $cells_sender_alias = $this->jedoxapi->cell_export($server_database['database'],$sender_alias_info['cube'],10000,"", $sender_alias_name_id.",*"); 
            
            // FORM DATA //
            $form_year = $year_elements;
            
            $form_months = $this->jedoxapi->array_element_filter($month_elements, "MA"); // All Months
            $form_months = $this->jedoxapi->set_alias($form_months, $cells_month_alias); // Set aliases
            
            $form_reciever = $this->jedoxapi->array_element_filter($receiver_elements, "AR"); 
            $form_reciever = $this->jedoxapi->set_alias($form_reciever, $cells_receiver_alias); // Set aliases
            
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
            if($receiver == '')
            {
                $receiver = $this->jedoxapi->get_area($receiver_elements, "SF");
            }
            
            ////////////////
            // Total cost //
            ////////////////
            
            $version_area = $this->jedoxapi->get_area($version_elements, "V001,V002,V003"); // Plan, Actual, Target
            $primary_value_PC04_area = $this->jedoxapi->get_area($primary_value_elements, "PC04");
            $primary_value_PC_area = $this->jedoxapi->get_area($primary_value_elements, "PC");
            $account_element_elements_CE_5_area = $this->jedoxapi->get_area($account_element_elements, "CE_5");
            $secondary_value_elements_SC_area = $this->jedoxapi->get_area($secondary_value_elements, "SC");
            $sender_elements_AS_area = $this->jedoxapi->get_area($sender_elements, "AS");
            
            $tc1_area = $version_area.",".$year.",".$month.",".$primary_value_PC04_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $tc2_area = $version_area.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $tc3_area = $version_area.",".$year.",".$month.",".$secondary_value_elements_SC_area.",".$sender_elements_AS_area.",".$receiver;
            
            $tc1_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $tc1_area, "", "1", "", "0");
            $tc2_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $tc2_area, "", "1", "", "0");
            $tc3_cells = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $tc3_area, "", "1", "", "0");
            
            $tc_sum = $this->jedoxapi->add_cell_array($tc1_cells, $tc2_cells, 0, 1);
            $tc_sum = $this->jedoxapi->add_cell_array($tc_sum, $tc3_cells, 0, 1);
            
            $pc_sum = $this->jedoxapi->add_cell_array($tc1_cells, $tc2_cells, 0, 1);
            ////////////////
            // Drill Down //
            ////////////////
            
            $account_element_elements_CE_5full = $this->jedoxapi->array_element_filter($account_element_elements, "CE_5");
            array_shift($account_element_elements_CE_5full);
            $account_element_elements_CE_5full_area = $this->jedoxapi->get_area($account_element_elements_CE_5full);
            $aecolumn_array = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002,V003");
            $account_element_elements_CE_5full_alias = $this->jedoxapi->set_alias($account_element_elements_CE_5full, $cells_account_element_alias);
            $sender_elements_child = $this->jedoxapi->array_element_filter($sender_elements, 'AS');
            array_shift($sender_elements_child);
            $sender_elements_area = $this->jedoxapi->get_area($sender_elements_child);
            $sender_elements_alias = $this->jedoxapi->set_alias($sender_elements_child, $cells_sender_alias);
            
            $ae1_area = $version_area.",".$year.",".$month.",".$primary_value_PC04_area.",".$account_element_elements_CE_5full_area.",".$receiver;
            $ae2_area = $version_area.",".$year.",".$month.",".$primary_value_PC_area.",".$account_element_elements_CE_5full_area.",".$receiver;
            
            $ae1_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $ae1_area, "", "1", "", "0");
            $ae2_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $ae2_area, "", "1", "", "0");
            
            $ae_sum = $this->jedoxapi->add_cell_array($ae1_cells, $ae2_cells, 0, 4);
            
            $ae_table = $this->jedox->to_table_row($ae_sum, $aecolumn_array, $account_element_elements_CE_5full_alias, 0, 4, "", TRUE, TRUE, TRUE, '', 'label2', '', TRUE);
            
            $se_area = $version_area.",".$year.",".$month.",".$secondary_value_elements_SC_area.",".$sender_elements_area.",".$receiver;
            
            $se = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $se_area, "", "1", "", "0");
            $se_table = $this->jedox->to_table_row($se, $aecolumn_array, $sender_elements_alias, 0, 4, "", TRUE, TRUE, TRUE, '', 'label2', '', TRUE);
            
            ///////////
            // CHART //
            ///////////
            
            $month_all = $this->jedoxapi->dimension_elements_base($month_elements);
            $month_all_alias = $this->jedoxapi->set_alias($month_all, $cells_month_alias);
            $month_all_area = $this->jedoxapi->get_area($month_all);
            $version_area_pa = $this->jedoxapi->get_area($version_elements, "V001,V002"); // Plan, Actual
            $version_elements_pa = $this->jedoxapi->dimension_elements_id($version_elements, "V001,V002");
            $version_elements_pa_alias = $this->jedoxapi->set_alias($version_elements_pa, $cells_version_alias);
            $version_area_a = $this->jedoxapi->get_area($version_elements, "V002"); // Actual
            $version_elements_a = $this->jedoxapi->dimension_elements_id($version_elements, "V002");
            $version_elements_a_alias = $this->jedoxapi->set_alias($version_elements_a, $cells_version_alias);
            
            $current_year = $this->jedox->get_dimension_data_by_id($year_elements, $year);
            $current_year = $current_year['name_element'];
            $prev_year = $current_year - 1;
            $prev_year_data = $this->jedoxapi->dimension_elements_id($year_elements, $prev_year);
            $yearcheck = count($prev_year_data);
            
            $chart1xml = $this->jedox->multichart_xml_categories($month_all_alias, 1);
            
            $chart1a_area = $version_area_pa.",".$year.",".$month_all_area.",".$primary_value_PC04_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $chart1b_area = $version_area_pa.",".$year.",".$month_all_area.",".$primary_value_PC_area.",".$account_element_elements_CE_5_area.",".$receiver;
            $chart1c_area = $version_area_pa.",".$year.",".$month_all_area.",".$secondary_value_elements_SC_area.",".$sender_elements_AS_area.",".$receiver;
            
            $chart1a_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1a_area, "", "1", "", "0");
            $chart1b_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1b_area, "", "1", "", "0");
            $chart1c_cells = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $chart1c_area, "", "1", "", "0");
            
            $chart_tc_sum = $this->jedoxapi->add_cell_array($chart1a_cells, $chart1b_cells, 0, 2);
            $chart_tc_sum = $this->jedoxapi->add_cell_array($chart_tc_sum, $chart1c_cells, 0, 2);
            
            $chart1xml .= $this->jedox->multichart_xml_series($chart_tc_sum, $month_all, $version_elements_pa_alias, 2, 0, "", " ".$current_year);
            
            // Area for previous year based on selected. Detect if there is a year before the "selected year"
            if($yearcheck != 0)
            {
                $chart1d_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$primary_value_PC04_area.",".$account_element_elements_CE_5_area.",".$receiver;
                $chart1e_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$primary_value_PC_area.",".$account_element_elements_CE_5_area.",".$receiver;
                $chart1f_area = $version_area_a.",".$prev_year_data[0]['element'].",".$month_all_area.",".$secondary_value_elements_SC_area.",".$sender_elements_AS_area.",".$receiver;
            
                $chart1d_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1d_area, "", "1", "", "0");
                $chart1e_cells = $this->jedoxapi->cell_export($server_database['database'], $primary_cube_info['cube'], 10000, "", $chart1e_area, "", "1", "", "0");
                $chart1f_cells = $this->jedoxapi->cell_export($server_database['database'], $secondary_cube_info['cube'], 10000, "", $chart1f_area, "", "1", "", "0");
                
                $chart_tc_sum1 = $this->jedoxapi->add_cell_array($chart1d_cells, $chart1e_cells, 0, 2);
                $chart_tc_sum1 = $this->jedoxapi->add_cell_array($chart_tc_sum1, $chart1f_cells, 0, 2);
                
                $chart1xml .= $this->jedox->multichart_xml_series($chart_tc_sum1, $month_all, $version_elements_a_alias, 2, 0, "", " ".$prev_year);
            }
            
            
            // Pass all data to send to view file
            $alldata = array(
                //regular vars here
                "form_year" => $form_year,
                "year" => $year,
                "form_months" => $form_months,
                "month" => $month,
                "form_reciever" => $form_reciever,
                "receiver" => $receiver,
                "jedox_user_details" => $user_details,
                "pagename" => $pagename,
                "chart_name" => $chart_name,
                "tc_sum" => $tc_sum,
                "ae_table" => $ae_table,
                "se_table" => $se_table,
                "tc3_cells" => $tc3_cells,
                "pc_sum" => $pc_sum,
                "chart1xml" => $chart1xml
                //trace vars here
                
            );
            // Pass data and show view
            $this->load->view("efficiency_costs/chart1_view", $alldata);
        }
    }
	
}