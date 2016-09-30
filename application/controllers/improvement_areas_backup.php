<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Improvement_Areas extends CI_Controller {

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
            $cube_names = "Improvement Areas,#_Account_Element,#_Receiver";
            
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
            $improvement_areas_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "Improvement Areas");
			
			$improvement_areas_cube_info = $this->jedoxapi->get_cube_data($database_cubes, "Improvement Areas");
			
			////////////////////////////
            // Get Dimension elements //
            ////////////////////////////
			
			// ACCOUNT ELEMENT //
            // Get dimension of account_element
            $account_element_elements = $this->jedoxapi->dimension_elements($server_database['database'], $improvement_areas_dimension_id[1]);
            // Get cube data of account_element alias
            $account_element_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Account_Element");
            $account_element_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Account_Element");
            // Export cells of account_element alias
            $account_element_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $account_element_dimension_id[0]);
			$account_element_alias_name_id = $this->jedoxapi->get_area($account_element_alias_elements, "Name");
            $cells_account_element_alias = $this->jedoxapi->cell_export($server_database['database'],$account_element_alias_info['cube'],10000,"", $account_element_alias_name_id.",*");
            
            // RECEIVER //
            // Get dimension of receiver
            $receiver_elements = $this->jedoxapi->dimension_elements($server_database['database'], $improvement_areas_dimension_id[0]);
            // Get cube data of receiver alias
            $receiver_dimension_id = $this->jedoxapi->get_dimension_id($database_cubes, "#_Receiver");
            $receiver_alias_info = $this->jedoxapi->get_cube_data($database_cubes, "#_Receiver");
            // Export cells of receiver alias
            $receiver_alias_elements = $this->jedoxapi->dimension_elements($server_database['database'], $receiver_dimension_id[0]);
			$receiver_alias_name_id = $this->jedoxapi->get_area($receiver_alias_elements, "Name");
            $cells_receiver_alias = $this->jedoxapi->cell_export($server_database['database'],$receiver_alias_info['cube'],10000,"", $receiver_alias_name_id.",*"); 
			
			////////////
			// CHARTS //
			////////////
			
			$account_element_area = $this->jedoxapi->get_area($account_element_elements, "CE_90,CE_9020,CE_9030");
			$account_element_CE_90 = $this->jedoxapi->get_area($account_element_elements, "CE_90"); // bubble size
			$account_element_CE_9020 = $this->jedoxapi->get_area($account_element_elements, "CE_9020"); // x coord
			$account_element_CE_9030 = $this->jedoxapi->get_area($account_element_elements, "CE_9030"); // y coord
			$account_element_grp = $this->jedoxapi->dimension_sort_by_name($account_element_elements, "CE_90,CE_9020,CE_9030"); 
			
			$receiver_ia_child = $this->jedoxapi->array_element_filter($receiver_elements, "BP_PC000_IA");
            array_shift($receiver_ia_child);
			$receiver_ia_child_area = $this->jedoxapi->get_area($receiver_ia_child);
			$receiver_ia_child_alias = $this->jedoxapi->set_alias($receiver_ia_child, $cells_receiver_alias);
			
			$chart1area = $receiver_ia_child_area.",".$account_element_area;
			
			$chart1data = $this->jedoxapi->cell_export($server_database['database'], $improvement_areas_cube_info['cube'], 10000, "", $chart1area, "", "1", "", "0");
			
			$chart1 = $this->xyz_xml($chart1data, $receiver_ia_child_alias, $account_element_CE_90, $account_element_CE_9020, $account_element_CE_9030);
			
			//echo "<pre>";
			//print_r($account_element_grp);
			//print_r($receiver_ia_child_alias);
			//print_r($chart1data);
			//echo ($chart1);
			//echo "</pre>";
			
			// Pass all data to send to view file
            $alldata = array(
            	"pagename" => $pagename,
                "oneliner" => $oneliner,
                "jedox_user_details" => $user_details,
                "chart1" => $chart1
			);
			
			// Pass data and show view
            $this->load->view("improvement_areas_view", $alldata);
		}
	}
	
	private function xyz_xml($array, $receiver_array, $ce90, $ce9020, $ce9030)
	{
		$temp = "";
		$xml = '';
		
		foreach ($receiver_array as $row)
		{
			$xval = '';
			$yval = '';
			$zval = '';
			foreach($array as $xrow)
			{
				$xpath = explode(",",$xrow['path']);
				if($xpath[0] == $row['element'] && $xpath[1] == $ce9020)
				{
					$xval = $xrow['value'];
				}
			}
			foreach($array as $yrow)
			{
				$ypath = explode(",",$yrow['path']);
				if($ypath[0] == $row['element'] && $ypath[1] == $ce9030)
				{
					$yval = $yrow['value'];
				}
			}
			foreach($array as $zrow)
			{
				$zpath = explode(",",$zrow['path']);
				if($zpath[0] == $row['element'] && $zpath[1] == $ce90)
				{
					$zval = $zrow['value'];
				}
			}
			
			
			$temp .= "<set x='".$xval."' y='".$yval."' z='".$zval."'  name='".$row['name_element']."' toolText='".$row['name_element']."{br}Complexity of Implementation: ".$xval."{br}Need of Prerequisites: ".$yval."{br}Improvement Area: $".number_format($zval, 0, '.', ',')."' />";
		}
		
		$xml .= "<chart caption='' is3D='1' baseFontColor='000000' bgColor='FFFFFF' showBorder='0' showPlotBorder='0' adjustDiv='0' numDivLines='3' adjustVDiv='0' numVDivlines='5' xAxisName='Complexity of Implementation' yAxisName='Need of Prerequisites' xNumberPrefix='' yNumberSuffix='' negativeColor='FF6600' paletteColors='0066FF' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0' canvasBorderAlpha='0' showAlternateHGridColor='0' showAlternateVGridColor='0' showZeroPlane='0' showVZeroPlane='0'><dataSet showValues='0'>";
		$xml .= $temp;
		$xml .= "</dataSet>";
		$xml .= "</chart>";
		return $xml;
	}
	
	
}