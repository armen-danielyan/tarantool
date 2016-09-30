<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class improvement_areas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library("jedox");
        $this->load->library("jedoxapi");
    }
    
    public function index()
    { 
    	$pagename = "proEO Improvement Areas";
        $oneliner = "One-liner here for Improvement Areas";
        $user_details = $this->session->userdata('jedox_user_details');
        if($this->session->userdata('jedox_sid') == '' || $this->session->userdata('jedox_sid') == NULL)
        {
            $this->session->set_userdata('jedox_referer', current_url());
            redirect("/login/page");
        }
		/* permission system disabled
        else if($this->jedoxapi->page_permission($user_details['group_names'], "improvement_areas") == FALSE)
        {
            echo "Sorry, you have no permission to access this area.";
        }
		*/
        else 
        {
        	if(isset($_GET['trace']) && $_GET['trace'] == TRUE){
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
            $cube_names = "Improvement_Area,#_Account_Element,#_Process";
            
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
            $improvement_areas_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Improvement_Area");			
			$improvement_areas_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Improvement_Area");
			
			////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
			
			// ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $improvement_areas_dimension_id[0]);
			
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");

            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*");            
			

			
            // process //
            // Get dimension of process
            $process_elements = $this->jedoxapi->dimension_elements($server_database['database'], $improvement_areas_dimension_id[1]);
            // Get cube data of process alias
            $process_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Process");
            $process_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Process");
            // Export cells of process alias
            $process_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $process_dimension_id[0]);
			$process_alias_name_id = $this->jedoxapi->get_area($process_alias_elements, "Name");
            $cells_process_alias = $this->jedoxapi->cell_export($server_database['database'],$process_alias_info['cube'],10000,"", $process_alias_name_id.",*"); 
			
			///////////
			// table //
			///////////
			$account_element_CE_9010 = $this->jedoxapi->get_area($account_element_elements, "CE_9010"); // x coord
			$account_element_CE_9020 = $this->jedoxapi->get_area($account_element_elements, "CE_9020"); // y coord
			$account_element_CE_9090 = $this->jedoxapi->get_area($account_element_elements, "CE_9090"); // bubble size
			
			$account_element_set = $this->jedoxapi->dimension_sort_by_name($account_element_elements, "CE_9010,CE_9020,CE_9090");
			$account_element_set_area = $this->jedoxapi->get_area($account_element_set);
			$account_element_set_alias = $this->jedoxapi->set_alias($account_element_set, $cells_account_element_alias);
			
			$process_elements_set = $this->jedoxapi->array_element_filter($process_elements, "BP_Imp_Area");
			array_shift($process_elements_set);
			$process_elements_set_area = $this->jedoxapi->get_area($process_elements_set);
			$process_elements_set_alias = $this->jedoxapi->set_alias($process_elements_set, $cells_process_alias);
			
			$table1_area = $account_element_set_area.",".$process_elements_set_area;
			$table1_data = $this->jedoxapi->cell_export($server_database['database'], $improvement_areas_cube_info['cube'], 10000, "", $table1_area, "", "1", "", "0");
			
			////////////
			// CHARTS //
			////////////
			
			//$account_element_area = $this->jedoxapi->get_area($account_element_elements, "CE_90,CE_9020,CE_9030");
			//$account_element_CE_90 = $this->jedoxapi->get_area($account_element_elements, "CE_90"); // bubble size
			//$account_element_CE_9020 = $this->jedoxapi->get_area($account_element_elements, "CE_9020"); // x coord
			//$account_element_CE_9030 = $this->jedoxapi->get_area($account_element_elements, "CE_9030"); // y coord
			//$account_element_grp = $this->jedoxapi->dimension_sort_by_name($account_element_elements, "CE_90,CE_9020,CE_9030"); 
			
			//$process_ia_child = $this->jedoxapi->array_element_filter($process_elements, "BP_PC000_IA");
            //array_shift($process_ia_child);
			//$process_ia_child_area = $this->jedoxapi->get_area($process_ia_child);
			//$process_ia_child_alias = $this->jedoxapi->set_alias($process_ia_child, $cells_process_alias);
			
			//$chart1area = $process_ia_child_area.",".$account_element_area;
			
			//$chart1data = $this->jedoxapi->cell_export($server_database['database'], $improvement_areas_cube_info['cube'], 10000, "", $chart1area, "", "1", "", "0");
			
			$chart1 = $this->xyz_xml($table1_data, $process_elements_set_alias, $account_element_CE_9090, $account_element_CE_9010, $account_element_CE_9020);
			
			//echo "<pre>";
			//print_r($account_element_grp);
			//print_r($process_ia_child_alias);
			//print_r($chart1data);
			//echo ($chart1);
			//echo "</pre>";
			
			// Pass all data to send to view file
            $alldata = array(
            	"pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "chart1" => $chart1,
                "table1_data" => $table1_data,
                "process_elements_set_alias" => $process_elements_set_alias,
                "account_element_CE_9010" => $account_element_CE_9010,
                "account_element_CE_9020" => $account_element_CE_9020,
                "account_element_CE_9090" => $account_element_CE_9090
                
			);
			
			// Pass data and show view
            $this->load->view("improvement_areas_view", $alldata);
		}
	}
	
	private function xyz_xml($array, $process_array, $ce90, $ce9020, $ce9030)
	{
		$temp = "";
		$xml = '';
		$colorset = array("4f81bd", "c0504d", "9bbb59", "8064a2");
		$colorcode = 0;
		foreach ($process_array as $row)
		{
			$xval = '';
			$yval = '';
			$zval = '';
			foreach($array as $xrow)
			{
				$xpath = explode(",",$xrow['path']);
				if($xpath[1] == $row['element'] && $xpath[0] == $ce9020)
				{
					$xval = $xrow['value'];
				}
			}
			foreach($array as $yrow)
			{
				$ypath = explode(",",$yrow['path']);
				if($ypath[1] == $row['element'] && $ypath[0] == $ce9030)
				{
					$yval = $yrow['value'];
				}
			}
			foreach($array as $zrow)
			{
				$zpath = explode(",",$zrow['path']);
				if($zpath[1] == $row['element'] && $zpath[0] == $ce90)
				{
					$zval = $zrow['value'];
				}
			}
			
			
			$temp .= "<set x='".$xval."' y='".$yval."' z='".$zval."' color='".$colorset[$colorcode]."'  name='".$row['name_element']."' toolText='".$row['name_element']."{br}Initial Capital Investment: ".number_format($xval, 0, '.', ',')."{br}Net Financial Impact: ".number_format($yval, 0, '.', ',')."{br}Strategic Importance Number: ".number_format($zval, 0, '.', ',')."' />";
			$colorcode += 1;
			if($colorcode == 4)
			{
				$colorcode = 0;
			}
		}
		
		$xml .= "<chart caption='' is3D='1' baseFontColor='000000' bgColor='FFFFFF' showBorder='0' showPlotBorder='0' adjustDiv='0' numDivLines='3' adjustVDiv='0' numVDivlines='5' xAxisName='Initial Capital Investment' yAxisName='Net Financial Impact' xNumberPrefix='' yNumberSuffix='' negativeColor='FF6600' paletteColors='0066FF' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0' canvasBorderAlpha='0' showAlternateHGridColor='0' showAlternateVGridColor='0' showZeroPlane='0' showVZeroPlane='0' xAxisMinValue='-2000000' xAxisMaxValue='4000000' yAxisMinValue='-30000000' yAxisMaxValue='100000000' ><dataSet showValues='0'>";
		$xml .= $temp;
		$xml .= "</dataSet>";
		$xml .= "</chart>";
		return $xml;
	}
	
	
}