<?php defined('BASEPATH') OR exit('No direct script access allowed');

//Use this to send and retrieve data only. parsing should be done on controller.

//////////////////////
// DEFINE CONSTANTS //
//////////////////////

if(!defined('JEDOX_HOST'))
{
	define("JEDOX_HOST", "http://demo.altaviacentral.com"); // ryan local
	//define("JEDOX_HOST", "http://www.altaviasouthside.com"); //change this value as needed
}

//define("JEDOX_HOST", "http://www.altaviasouthside.com"); //change this value as needed
//define("JEDOX_HOST", "http://ec2-50-17-58-9.compute-1.amazonaws.com");
//define("JEDOX_HOST", "http://www.altaviasouthside.com");
if(!defined('JEDOX_PORT'))
{
	define("JEDOX_PORT", "7777");
}

class Jedox
{
	private $ci;
	var $encoding = ""; // - empty should auto detect.
	
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('session');
	}
	
	
	public function send($request = "")
	{
		$request = JEDOX_HOST.":".JEDOX_PORT.$request;
		$ch = curl_init($request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
		
		if( ! ini_get('safe_mode') && ! ini_get('open_basedir') )
		{
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		}
		//$this->ci->benchmark->mark('curl_start'); // Request speed tracer starting point. Comment out on production
		$response = curl_exec($ch);
		//$this->ci->benchmark->mark('curl_end'); // Request speed tracer end point. Comment out on production
		//Used to trace what request is being sent for development purposes only. Comment out on production.
			//$tracer = $this->ci->session->userdata('CurlRequest')."<br />".$request." <strong>Elapsed time: ".$this->ci->benchmark->elapsed_time('curl_start', 'curl_end')." secs.</strong>";
			//$this->ci->session->set_userdata('CurlRequest', $tracer);
		//end of tracer
		
		if($response === FALSE)
		{
			//Curl request failed. return error codes to diagnose problem.
			$error_message = array(
				"CURL_ERROR_NO" => curl_errno($ch),
				"CURL_ERROR_MESSAGE" => curl_error($ch)
			);
			curl_close($ch);
			$ch = NULL;
			return $error_message;
		}
		else 
		{
			//Curl request success
			//$response = $response.$request;
			curl_close($ch);
			$ch = NULL;
			$response = explode(";", $response);
			array_pop($response);
			return $response;
		}
		
	}
	
	////////////
	// SERVER //
	////////////
	
	/*
	SERVER LOGIN
	*/
	
	//public function server_login($user = "Admin", $password = "proE0!")
	//public function server_login($user = "Admin", $password = "admin")
	public function server_login($user, $password)
	{
		//Note: for development purposes, the user/pass and the setting of session is included here. edit this later.
		$request = "/server/login?user=".$user."&password=".md5($password);
		$response = $this->send($request);
		//$this->ci->session->set_userdata('jedox_sid', $response[0]);
		return $response;
	}
	
	/*
	SERVER DATABASES
	*/
	
	public function server_databases($show_normal = 1, $show_system = 0, $show_user_info = 0)
	{
		$request = "/server/databases?sid=".$this->ci->session->userdata('jedox_sid')."&show_normal=".$show_normal."&show_system=".$show_system."&show_user_info=".$show_user_info;
		$response = $this->send($request);
		return $response;
	}
	
	public function server_databases_setarray($array)
	{
		//This in an element setter. use this only on success of call.
		$count = 0;
		$result_array = array();
		foreach($array as $row)
		{
			if($count == 0)
			{
				$sub_array = array();
				$sub_array['database'] = trim($row);
				$count += 1;
			}
			else if($count == 1)
			{
				$sub_array['name_database'] = trim($row, '"');
				$count += 1;
			}
			else if($count == 2)
			{
				$sub_array['number_dimensions'] = $row;
				$count += 1;
			}
			else if($count == 3)
			{
				$sub_array['number_cubes'] = $row;
				$count += 1;
			}
			else if($count == 4)
			{
				$sub_array['status'] = $row;
				$count += 1;
			}
			else if($count == 5)
			{
				$sub_array['type'] = $row;
				$count += 1;
			}
			else if($count == 6)
			{
				$sub_array['database_token'] = $row;
				$result_array[] = $sub_array;
				$count = 0;
			}
		}
		return $result_array;
	}
	
	public function server_databases_select($array, $dbname)
	{
		//used to dynamically select database based on name
		$data = '';
		foreach($array as $row)
		{
			if($row['name_database'] == $dbname)
			{
				$data = $row;
			}
		}
		return $data;
	}
	
	/*
	SERVER INFO
	*/
	
	public function server_info()
	{
		$request = "/server/info";
		$response = $this->send($request);
		return $response;
	}
	
	/*
	SERVER LICENSE
	*/
	
	public function server_license()
	{
		$request = "/server/license";
		$response = $this->send($request);
		return $response;
	}
	
	/*
	SERVER LOAD
	*/
	
	public function server_load()
	{
		$request = "/server/load?sid=".$this->ci->session->userdata('jedox_sid');
		$response = $this->send($request);
		return $response;
	}
	
	/*
	SERVER LOGOUT
	*/
	
	public function server_logout()
	{
		$request = "/server/logout?sid=".$this->ci->session->userdata('jedox_sid');
		$response = $this->send($request);
		//when logging out you should also delete/unset session data of ci to prevent access to 'secured' pages.
		$this->ci->session->unset_userdata('jedox_sid');
		return $response;
	}
	
	/*
	SERVER CHANGE PASSWORD
	*/
	
	public function server_change_password($password)
	{
		//password has no default to prevent passing blank value when changing it as it will induce an error.
		$request = "/server/change_password?sid=".$this->ci->session->userdata('jedox_sid')."&password=".$password;
		$response = $this->send($request);
		return $response;
	}
	
	/*
	SERVER USER INFO
	*/
	
	public function server_user_info()
	{
		$request = "/server/user_info?sid=".$this->ci->session->userdata('jedox_sid');
		$response = $this->send($request);
		$count = 0;
		foreach($response as $row)
		{
			if($count == 0)
			{
				$sub_array = array();
				$sub_array['id'] = trim($row);
				$count += 1;
			}
			else if($count == 1)
			{
				$sub_array['name'] = trim($row, '"');
				$count += 1;
			}
			else if($count == 2)
			{
				$sub_array['groups'] = $row;
				$count += 1;
			}
			else if($count == 3)
			{
				$pre = explode(",", $row);
				$mid = array();
				foreach($pre as $irow)
				{
					$mid[] = trim($irow, '"');
				}
				$post = implode(',', $mid);
				$sub_array['group_names'] = $post;
				$count += 1;
			}
			else if($count == 4)
			{
				$sub_array['ttl'] = $row;
				$count = 0;
			}
		}
		
		return $sub_array;
	}
	
	
	//////////////
	// DATABASE //
	//////////////
	
	/*
	DATABASE CUBES
	*/
	
	public function database_cubes($database, $show_normal = 1, $show_system = 0, $show_attribute = 0, $show_info = 0, $show_gputype = 1)
	{
		$request = "/database/cubes?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&show_normal=".$show_normal."&show_system=".$show_system."&show_attribute=".$show_attribute."&show_info=".$show_info."&show_gputype=".$show_gputype;
		$response = $this->send($request);
		return $response;
	}
	
	public function database_cubes_setarray($array)
	{
		//This in an element setter. use this only on success of call.
		$count = 0;
		$result_array = array();
		foreach($array as $row)
		{
			if($count == 0)
			{
				$sub_array = array();
				$sub_array['cube'] = trim($row);
				$count += 1;
			}
			else if($count == 1)
			{
				$sub_array['name_cube'] = trim($row, '"');
				$count += 1;
			}
			else if($count == 2)
			{
				$sub_array['number_dimensions'] = $row;
				$count += 1;
			}
			else if($count == 3)
			{
				$sub_array['dimensions'] = $row;
				$count += 1;
			}
			else if($count == 4)
			{
				$sub_array['number_cells'] = $row;
				$count += 1;
			}
			else if($count == 5)
			{
				$sub_array['number_filled_cells'] = $row;
				$count += 1;
			}
			else if($count == 6)
			{
				$sub_array['status'] = $row;
				$count += 1;
			}
			else if($count == 7)
			{
				$sub_array['type'] = $row;
				$count += 1;
			}
			else if($count == 8)
			{
				$sub_array['cube_token'] = $row;
				$result_array[] = $sub_array;
				$count = 0;
			}
		}
		return $result_array;
	}
	
	/*
	DATABASE LOAD
	*/
	
	public function database_load($database)
	{
		$request = "/database/load?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database;
		$response = $this->send($request);
		return $response;
	}
	
	/*
	DATABASE CREATE
	*/
	
	public function database_create($new_name, $type = 0)
	{
		$request = "/database/create?sid=".$this->ci->session->userdata('jedox_sid')."&new_name=".$new_name."&type=".$type;
		$response = $this->send($request);
		return $response;
	}
	
	/*
	DATABASE DESTROY
	*/
	
	public function database_destroy($database)
	{
		$request = "/database/destroy?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database;
		$response = $this->send($request);
		return $response;
	}
	
	/*
	DATABASE DIMENSIONS
	*/
	
	public function database_dimensions($database, $show_normal = 1, $show_system = 0, $show_attribute = 0, $show_info = 0)
	{
		$request = "/database/dimensions?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&show_normal=".$show_normal."&show_system=".$show_system."&show_attribute=".$show_attribute."&show_info=".$show_info;
		$response = $this->send($request);
		return $response;
	}
	
	/*
	DATABASE INFO
	*/
	
	public function database_info($database)
	{
		$request = "/database/info?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database;
		$response = $this->send($request);
		return $response;
	}
	
	/*
	DATABASE RENAME
	*/
	
	public function database_rename($database, $new_name)
	{
		$request = "/database/rename?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&new_name=".$new_name;
		$response = $this->send($request);
		return $response;
	}
	
	/*
	DATABASE SAVE
	*/
	
	public function database_save($database)
	{
		$request = "/database/save?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database;
		$response = $this->send($request);
		return $response;
	}
	
	/*
	DATABASE UNLOAD
	*/
	
	public function database_unload($database)
	{
		$request = "/database/unload?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database;
		$response = $this->send($request);
		return $response;
	}
	
	///////////////
	// DIMENSION //
	///////////////
	
	public function dimension_elements($database, $dimension, $parent = '', $limit = '', $show_lock_info = '')
	{
		$request = "/dimension/elements?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&dimension=".$dimension;
		if($parent != '')
		{
			$request .= "&parent=".$parent;
		}
		if($limit != '')
		{
			$request .= "&limit=".$limit;
		}
		if($show_lock_info != '')
		{
			$request .= "&show_lock_info=".$show_lock_info;
		}
		
		$response = $this->send($request);
		
		return $response;
	}
	
	public function dimension_elements_setarray($array)
	{
		//This in an element setter. use this only on success of call.
		$count = 0;
		$result_array = array();
		foreach($array as $row)
		{
			if($count == 0)
			{
				$sub_array = array();
				$sub_array['element'] = trim($row);
				$count += 1;
			}
			else if($count == 1)
			{
				$sub_array['name_element'] = trim($row, '"');
				$count += 1;
			}
			else if($count == 2)
			{
				$sub_array['position'] = $row;
				$count += 1;
			}
			else if($count == 3)
			{
				$sub_array['level'] = $row;
				$count += 1;
			}
			else if($count == 4)
			{
				$sub_array['indent'] = $row;
				$count += 1;
			}
			else if($count == 5)
			{
				$sub_array['depth'] = $row;
				$count += 1;
			}
			else if($count == 6)
			{
				$sub_array['type'] = $row;
				$count += 1;
			}
			else if($count == 7)
			{
				$sub_array['number_parents'] = $row;
				$count += 1;
			}
			else if($count == 8)
			{
				$sub_array['parents'] = $row;
				$count += 1;
			}
			else if($count == 9)
			{
				$sub_array['number_children'] = $row;
				$count += 1;
			}
			else if($count == 10)
			{
				$sub_array['weights'] = $row;
				$count += 1;
			}
			else if($count == 11)
			{
				$sub_array['lock'] = $row;
				$result_array[] = $sub_array;
				$count = 0;
			}
		}
		return $result_array;
	}
	
	public function dimension_elements_base($array)
	{
		/*
			Use this only on dimension_elements success calls and only after calling dimension_elements_setarray.
			Removes elements with children 
		*/
		
		$result_array = array();
		foreach($array as $row)
		{
			if($row['number_children'] == 0)
			{
				$result_array[] = $row;
			}
		}
		return $result_array;
	}
	
	public function dimension_elements_top($array)
	{
		/*
			Use this only on dimension_elements success calls and only after calling dimension_elements_setarray.
			Removes child elements
		*/
		
		$result_array = array();
		$count = 0;
		foreach($array as $row)
		{
			if($row['number_parents'] == 0 && $count == 0)
			{
				$result_array[] = $row;
				$count += 1;
			}
		}
		
		
		return $result_array;
	}
	
	public function dimension_elements_id($array, $filter)
	{
		/*
			Use this only on dimension_elements success calls and only after calling dimension_elements_setarray.
		*/
		$result_array = array();
		$filter = explode(',', $filter);
		foreach($array as $row)
		{
			foreach($filter as $rowfilter)
			{
				if($rowfilter == $row['name_element']){
					$result_array[] = $row;
				}
			}
		}
		return $result_array;
	}
	
	public function dimension_info($database, $dimension)
	{
		$request = "/dimension/info?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&dimension=".$dimension;
		$response = $this->send($request);
		return $response;
	}
	
	//////////
	// CUBE //
	//////////
	
	public function cube_load($database, $cube)
	{
		$request = "/cube/load?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&cube=".$cube;
		$response = $this->send($request);
		return $response;
	}
	public function cube_multiload($database, $array, $cube_names)
	{
		//dynamically load multiple cubes based on name. returns array of cubes loaded and if it is successfull;
		$result_array = array();
		$names = explode(',', $cube_names);
		foreach($array as $row)
		{
			foreach($names as $rows)
			{
				if($row['name_cube'] == $rows)
				{
					$result_array[] = array("name_cube" => $rows, "load_state" => $this->cube_load($database, $row['cube']));
				}
			}
		}
		return $result_array;
	}
	
	//////////
	// CELL //
	//////////
	
	public function cell_export($database, $cube, $blocksize = '', $path = '', $area = '', $condition = '', $use_rules = 1, $base_only = '', $skip_empty = '', $type = '', $properties = '')
	{
		$request = "/cell/export?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&cube=".$cube;
		if($blocksize != ''){
			$request .= "&blocksize=".$blocksize;
		}
		if($path != ''){
			$request .= "&path=".$path;
		}
		if($area != ''){
			$request .= "&area=".$area;
		}
		if($condition != ''){
			$request .= "&condition=".$condition;
		}
		if($use_rules != ''){
			$request .= "&use_rules=".$use_rules;
		}
		if($base_only != ''){
			$request .= "&base_only=".$base_only;
		}
		if($skip_empty != ''){
			$request .= "&skip_empty=".$skip_empty;
		}
		if($type != ''){
			$request .= "&type=".$type;
		}
		if($properties != ''){
			$request .= "&properties=".$properties;
		}
		$response = $this->send($request);
		
		return $response;
	}
	
	public function cell_export_setarray($array, $properties = FALSE)
	{
		//use this only on succesfull calls of cell_export. element setter
		//Note: if you set "properties" on cell_export, set the $prop to TRUE
		$count = 0;
		$result_array = array();
		foreach($array as $row)
		{
			if($count == 0)
			{
				$sub_array = array();
				$sub_array['type'] = trim($row);
				$count += 1;
			}
			else if($count == 1)
			{
				$sub_array['exists'] = $row;
				$count += 1;
			}
			else if($count == 2)
			{
				$sub_array['value'] = trim($row, '"');
				if($sub_array['value'] == '' && $sub_array['type'] == 1)
				{
					$sub_array['value'] = 0;
				}
				$count += 1;
			}
			else if($count == 3)
			{
				$sub_array['path'] = $row;
				if($properties == FALSE)
				{
					$result_array[] = $sub_array;
					$count = 0;
				}
				else
				{
					$count += 1;
				}

			}
			else if($count == 4)
			{
				$sub_array['property_values'] = $row;
				$result_array[] = $sub_array;
				$count = 0;
			}
		}
		
		return $result_array;
	}
	
	public function cell_replace($database, $cube, $path, $value, $add = '', $splash = '')
	{
		$request = "/cell/replace?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&cube=".$cube."&path=".$path."&value=".$value;
		if($add != ''){
			$request .= "&add=".$add;
		}
		if($splash != ''){
			$request .= "&splash=".$splash;
		}
		$response = $this->send($request);
		return $response;
	}
	
	/////////////////////
	// OTHER FUNCTIONS //
	/////////////////////
	
	public function multichart_xml($cells, $categories, $series)
	{
		$category_xml = "<categories>";
		foreach($categories as $rows){
			$label = trim($rows['name_element'], '"');
			$category_xml .= "<category label='".$label."' />";
		}
		$category_xml .= "</categories>";
		
		$series_xml = "";
		foreach($series as $rows)
		{
			$seriesName = trim($rows['name_element'], '"');
			$series_xml .= "<dataset seriesName='".$seriesName."'>";
			
			foreach($cells as $subrows){
				$cat_identifier = explode(',',$subrows['path']);
				$series_ident = trim($rows['element']);
				if($series_ident == $cat_identifier[4])
				{
					$series_xml .= "<set value='".$subrows['value']."' />";
				}
				
				
			}
			
			$series_xml .= "</dataset>";
		}

		$data = $category_xml.$series_xml;
		
		return $data;
		
	}
	
	// multichart part renderer. categories
	public function multichart_xml_categories($categories, $cut = FALSE)
	{
		$category_xml = "<categories>";
		foreach($categories as $rows){
			$label = trim($rows['name_element'], '"');
			if($cut != FALSE)
			{
				$label = substr($label, 0, $cut);
			}
			$category_xml .= "<category label='".$label."' />";
		}
		$category_xml .= "</categories>";
		return $category_xml;
	}
	// multichart part renderer. series. needs to take blank areas into account just like table renderer.
	public function multichart_xml_series($cells, $categories, $series, $category_path, $series_path, $prefix = '', $suffix = '')
	{
		$xml = '';
		foreach($series as $ser_rows)
		{
			$xml .= "<dataset seriesName='".$prefix.$ser_rows['name_element'].$suffix."'>";
			foreach($categories as $cat_rows)
			{
				foreach($cells as $cell_rows)
				{
					$path = explode(",", $cell_rows['path']);
					if($cat_rows['element'] == $path[$category_path] && $ser_rows['element'] == $path[$series_path])
					{
						if($cell_rows['value'] == 0 || $cell_rows['value'] == '')
						{
							$xml .= "<set value='' />";
						}
						else
						{
							$xml .= "<set value='".round($cell_rows['value'])."' />";
						}
						
					}
				}
			}
			$xml .= "</dataset>";
		}
		return $xml;
	}
	
	
	public function singlechart_xml($cells, $series, $series_path, $color = '')
	{
		$series_xml = "";
		$setcolor = "";
		foreach($cells as $subrows)
		{
			
			foreach($series as $rows){
				$cat_identifier = explode(',',$subrows['path']);
				$series_ident = $rows['element'];
				if( $series_ident == $cat_identifier[$series_path] && $subrows['value'] != '' )
				{
					if($color != ''){
						$setcolor = "color='".$color."'";
					}
					
					$series_xml .= "<set label='".$rows['name_element']."' value='".round($subrows['value'])."' ".$setcolor." />";
				}
			}
			
		}
		
		return $series_xml;
	}
	
	public function to_table_column($column_array, $show_names = TRUE)
	{
		$table = "";
		if($show_names == TRUE)
		{
			$table = "<tr><td>&nbsp;</td>";
		}
		else
		{
			$table = "<tr>";
		}
		foreach($column_array as $row)
		{
			$table .= "<td class='thead'>".$row['name_element']."</td>";
		}
		$table .= "</tr>";
		return $table;
	} 
	
	public function to_table_row($cells, $column_array, $row_array, $col_id, $row_id, $filter = '', $number_format = FALSE, $remove_empty = TRUE, $show_names = TRUE, $customname = '', $customclass = 'label', $rowclass = '', $showdepth = FALSE, $depth_offset = 0)
	{
		$table = "";
		foreach( $row_array as $row )
		{
			$depthfill = "";
			if($customname != "")
			{
				$row['name_element'] = $customname;
			}
			
			if($rowclass != '')
			{
				$rowclass = "class='".$rowclass."'";
			}
			
			$row['depth'] = $row['depth'] - $depth_offset;
			for( $i=0; $i < $row['depth']; $i++ )
			{
				if($showdepth == TRUE)
				{
					$depthfill .= "&nbsp;&nbsp;";
				}
			}
			
			if( $filter == '' )
			{
				//start table
				$temp_table = "";
				if( $show_names == TRUE )
				{
					$temp_table = "<tr ".$rowclass."><td class='".$customclass."'>".$depthfill.$row['name_element']."</td>";
				}
				else 
				{
					$temp_table = "<tr ".$rowclass.">";
				}
				$count = 0;
				foreach($column_array as $mainrows)
				{
					$sub_table = '';
					foreach($cells as $subrows)
					{
						$cell_identifier = explode(',',$subrows['path']);
						$col_ident = $cell_identifier[$col_id];
						$row_ident = $cell_identifier[$row_id];
						
						if($row_ident == $row['element'] && $col_ident == $mainrows['element'])
						{
								if($subrows['value'] == '' || $subrows['value'] == 0){
									$subrows['value'] = 0;
								}
								else 
								{
									$count += 1; //this will be the flag to use whether to add the row or not.
								}
								$subrows['value'] = round($subrows['value']);
								if($number_format == TRUE)
								{
									$sub_table = "<td>$ ".number_format($subrows['value'], 0, '.', ',')."</td>";
								}
								else
								{
									$sub_table = "<td>$ ".$subrows['value']."</td>";	
								}
									
						}
						
					}
					
					if($sub_table == '')
					{
						$sub_table = "<td>$ 0</td>";
					}
					$temp_table .= $sub_table;
				}
				
				$temp_table .= "</tr>";
				
				if($remove_empty == TRUE)
				{
					if($count != 0)
					{
						$table .= $temp_table;
					}
				}
				else
				{
					$table .= $temp_table;
				}
				//end table
			}
			else
			{
				$filter1 = explode(',', $filter);
				foreach($filter1 as $rowfilter)
				{
					if($rowfilter == $row['name_element']){
						//start table
						$temp_table = "";
				if($show_names == TRUE)
				{
					$temp_table = "<tr ".$rowclass."><td class='".$customclass."'>".$depthfill.$row['name_element']."</td>";
				}
				else 
				{
					$temp_table = "<tr ".$rowclass.">";
				}
				$count = 0;
				foreach($column_array as $mainrows)
				{
					$sub_table = '';
					foreach($cells as $subrows)
					{
						$cell_identifier = explode(',',$subrows['path']);
						$col_ident = $cell_identifier[$col_id];
						$row_ident = $cell_identifier[$row_id];
						
						if($row_ident == $row['element'] && $col_ident == $mainrows['element'])
						{
								if($subrows['value'] == '' || $subrows['value'] == 0){
									$subrows['value'] = 0;
								}
								else 
								{
									$count += 1; //this will be the flag to use whether to add the row or not.
								}
								$subrows['value'] = round($subrows['value']);
								if($number_format == TRUE)
								{
									$sub_table = "<td>$ ".number_format($subrows['value'], 0, '.', ',')."</td>";
								}
								else
								{
									$sub_table = "<td>$ ".$subrows['value']."</td>";	
								}
									
						}
						
					}
					
					if($sub_table == '')
					{
						$sub_table = "<td>$ 0</td>";
					}
					$temp_table .= $sub_table;
				}
				
				$temp_table .= "</tr>";
				
				if($remove_empty == TRUE)
				{
					if($count != 0)
					{
						$table .= $temp_table;
					}
				}
				else
				{
					$table .= $temp_table;
				}
						//end table
					}
				}
			}
		}
		return $table;
	}
	
	public function to_table_row_filler($array, $offset = 1, $rowbase = '',  $rowclass = '')
	{
		$numrow = count($array) + $offset;
		$table = '';
		if($rowclass != '')
		{
			$rowclass = "class='".$rowclass."'";
		}
		if($rowbase != '')
		{
			foreach($rowbase as $rows)
			{
				$table .= "<tr ".$rowclass.">";
				$table .= "<td colspan='".$numrow."'>&nbsp;</td>";
				$table .= "</tr>";	
			}
		}
		else
		{
			$table .= "<tr ".$rowclass.">";
			$table .= "<td colspan='".$numrow."'>&nbsp;</td>";
			$table .= "</tr>";
		}
		
		return $table;
	}
	
	public function set_alias($array, $alias)
	{
		$result_array = array();
		foreach($array as $row)
		{
			foreach($alias as $rows)
			{
				$path = explode(',', $rows['path']);
				if($row['element'] == $path['1'])
				{
					$row['name_element'] = $rows['value'];
					$result_array[] = $row;
				}
			}
		}
		return $result_array;
	}
	
	public function get_area($array, $element_names = '', $url = FALSE)
	{
		$area = '';
		if($element_names == '')
		{
			foreach($array as $row)
			{
				$area .= $row['element'].":";
			}
		}
		else
		{
			$name = explode(",", $element_names);
			foreach($array as $row)
			{
				foreach($name as $nrow)
				{
					
					if($url == TRUE)
					{
						$temp = url_title($row['name_element'], '_');
						if($temp == $nrow)
						{
							$area .= $row['element'].":";
						}
					}
					else
					{
						if($row['name_element'] == $nrow)
						{
							$area .= $row['element'].":";
						}
					}
					
				}
			}
		}
		$area = rtrim($area, ":");
		return $area;
	}
	
	public function sort_value($a, $b)
	{
		return $b['name'] - $a['name'];
	}

	public function get_dimension_id($array, $cube_name)
	{
		$dimension = '';
		foreach($array as $row)
		{
			if($row['name_cube'] == $cube_name)
			{
				$dimension = explode(',', $row['dimensions']);
				
			}
		}
		return $dimension;
	}
	
	public function get_cube_data($array, $cubename)
	{
		$result_array = '';
		foreach($array as $row)
		{
			if($row['name_cube'] == $cubename)
			{
				$result_array = $row;
			}
		}
		return $result_array;
	}
	public function get_dimension_data_by_id($array, $id)
	{
		$result_array = '';
		foreach($array as $row)
		{
			if($row['element'] == $id)
			{
				$result_array = $row;
			}
		}
		return $result_array;
	}
	
	public function array_element_filter($array, $parentname)
	{
		$data = array();
		foreach($array as $row)
		{
			if($row['name_element'] == $parentname)
			{
				$data[] = $row;
				//get all child. recourse thru the array to get all child regardless of depth
				$data = $this->array_element_filter_child($array, $row['element'], $data);
			}
		}
		return $data;
	}
	
	public function array_element_filter_child($array, $parentid, $stack)
	{
		foreach($array as $row)
		{
			$parents = explode(",", $row['parents']);
			foreach($parents as $prow)
			{
				if($prow == $parentid)
				{
					$stack[] = $row;
					$stack = $this->array_element_filter_child($array, $row['element'], $stack);
				}
			}
			
		}
		return $stack;
	}
	
	public function add_cell_array($array1, $array2, $path1, $path2)
	{
		$result_array = array();
		foreach($array1 as $row)
		{
			foreach($array2 as $rows)
			{
				$paths1 = explode(",", $row['path']);
				$paths2 = explode(",", $rows['path']);
				if($paths1[$path1] == $paths2[$path1] && $paths1[$path2] == $paths2[$path2])
				{
					$row['value'] = $row['value'] + $rows['value'];
					$result_array[] = $row;
				}
			}
		}
		return $result_array;
	}
	public function subtract_cell_array($array1, $array2, $path1, $path2)
	{
		$result_array = array();
		foreach($array1 as $row)
		{
			foreach($array2 as $rows)
			{
				$paths1 = explode(",", $row['path']);
				$paths2 = explode(",", $rows['path']);
				if($paths1[$path1] == $paths2[$path1] && $paths1[$path2] == $paths2[$path2])
				{
					$row['value'] = $row['value'] - $rows['value'];
					$result_array[] = $row;
				}
			}
		}
		return $result_array;
	}
	
	public function percentage_cell_array($array1, $array2, $path1, $path2)
	{
		$result_array = array();
		foreach($array1 as $row)
		{
			foreach($array2 as $rows)
			{
				$paths1 = explode(",", $row['path']);
				$paths2 = explode(",", $rows['path']);
				if($paths1[$path1] == $paths2[$path1] && $paths1[$path2] == $paths2[$path2])
				{
					if($row['value'] == '' || $rows['value'] == '')
					{
						$row['value'] = 0;
					}
					else
					{
						$row['value'] = ($row['value'] / $rows['value'] * 100);
					}

					$result_array[] = $row;
				}
			}
		}
		return $result_array;
	}
	
	public function waterfall_xml($label, $array, $process = '', $akey, $key)
	{
		$data = '';
		foreach($array as $row)
		{
			$paths = explode(",", $row['path']);
			if($paths[$akey] == $key)
			{
				$data = "<set label='".$label."' value='".round($process.$row['value'])."' ></set>";
			}
		}
		return $data;
	}
	
	//////////////////////
	// PAGE PERMISSIONs //
	//////////////////////
	
	public function page_permission($group_name, $page_name)
	{
		$cond = FALSE;
		//Set pages allowed for group
		$admin_group = array(
		      "efficiency_resources_details", 
		      "profitability", 
		      "sales_plan", 
		      "calculate_rates", 
		      "efficiency_costs", 
		      "efficiency_operations",
		      "efficiency_operations_details",
		      "efficiency_processes",
		      "efficiency_processes_details",
		      "efficiency_products",
		      "efficiency_resources",
		      "efficiency_products_details"
        );
		$proeo_admin_group = array(
		      "efficiency_resources_details", 
		      "profitability", 
		      "sales_plan", 
		      "calculate_rates", 
		      "efficiency_costs", 
		      "efficiency_operations",
		      "efficiency_operations_details",
		      "efficiency_processes",
		      "efficiency_processes_details",
		      "efficiency_products",
		      "efficiency_resources",
		      "efficiency_products_details"
        );
		$proeo_user_group = array(
		      "efficiency_resources_details", 
		      "profitability", 
		      "sales_plan"
        );
		
		$group = explode(",", trim($group_name));
		foreach ($group as $row)
		{
			if($row == "admin")
			{
				if(in_array($page_name, $admin_group))
				{
					$cond = TRUE;
				}
				
			}
			if($row == "proeo_admin")
			{
				if(in_array($page_name, $proeo_admin_group))
				{
					$cond = TRUE;
				}
				
			}
			if($row == "proeo_user")
			{
				if(in_array($page_name, $proeo_user_group))
				{
					$cond = TRUE;
				}
			}
		}
		return $cond;
	}
	
}