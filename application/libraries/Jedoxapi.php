<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Jedox api Class for Codeigniter.
 * 
 * This API uses the value of Codeigniters Session Library. Dont forget to load it.
 * 
 * @author Ryan Patrick Carabuena
 */

//define("JEDOX_HOST", "http://jedoxdev.com"); // ryan's local spoofed dns.
//define("JEDOX_HOST", "http://demo.proeo.net"); //Jedox host server
//define("JEDOX_HOST", "http://localhost"); //Jedox host server
//define("JEDOX_PORT", "7777");
define("DATA_ERRORS", TRUE); // bool. show or hide array error trace of curl calls. set to FALSE on production.

class Jedoxapi
{
    private $ci;
	public $data_trace = FALSE; //bool. show hide curl call trace and benchmarkers. use to debug calls.
    var $encoding = ""; // - empty should auto detect.
    
	function __construct()
	{
		$this->ci =& get_instance();
        $this->ci->load->library('session');
	}
    
    /**
     * Sends request to Jedox server.
     * The request can be built manually using $request as uri string.
     * 
     * If request returns an error, check 'HTTP_STATUS_CODE', if its 0 then its means that the request failed at 'curl level' so check both 'CURL_ERROR_NO' and 'CURL_ERROR_MESSAGE' for more info, if its >= 400 then it means that the request was successful but an error occurred at 'jedox level' so check the 'RESPONSE' array
     * 
     * Usage:
     * 
     * $this->jedoxapi->send([uri, string, required]);
     * ex: $this->jedoxapi->send("/server/login?user=admin&password=123456");
     * 
     * @access  public
     * @param   string
     * @return  array
     */
    public function send($request = "")
    {
        $request = JEDOX_HOST.":".JEDOX_PORT.$request;
		//echo $request."<br/>";
        $ch = curl_init($request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
        
        if( ! ini_get('safe_mode') && ! ini_get('open_basedir') )
        {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        }
		if($this->data_trace)
		{
			/* Request speed tracer starting point. Comment out on production */
			$this->ci->benchmark->mark('curl_start'); 
		}
        $response = curl_exec($ch);
		if($this->data_trace)
		{
			/* Request speed tracer end point. Comment out on production */
			$this->ci->benchmark->mark('curl_end'); 
			/* Used to trace what request is being sent for development purposes only. Comment out on production. */
			$tracer = $this->ci->session->userdata('CurlRequest')."<br />".$request." <strong>Elapsed time: ".$this->ci->benchmark->elapsed_time('curl_start', 'curl_end')." secs.</strong>";
			$this->ci->session->set_userdata('CurlRequest', $tracer);
			/* end of tracer */
		}
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($http_status >= 400 || $http_status == FALSE)
        {
            //Curl request failed. return error codes to diagnose problem.
            $response = explode(";", $response);
            array_pop($response);
            $error_message = array(
                "ERROR" => TRUE,
                "REQUEST" => $request,
                "RESPONSE" => $response,
                "HTTP_STATUS_CODE" => $http_status,
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
            curl_close($ch);
            $ch = NULL;
            $response = explode(";", $response);
            array_pop($response);
            return $response;
        }
    }
    /**
     * Logs in to the Jedox server. Session identifier will automaticaly be set in Codeigniters Session class.
     * 
     * Usage:
     * 
     * $this->jedoxapi->server_login([user, string, required], [password, string, required])
     * ex: $this->jedoxapi->server_login("admin", "password")
     * 
     * Returns:
     * 
     * array(
     *      0 => sid (Session identifier for a server connection)
     *      1 => ttl (Timeout intervall in seconds. If no request is made within this intervall the session becomes inactive)
     *      2 => optional (List of optional features server has licenses for)
     * )
     * 
     * @access  public
     * @param   string
     * @return  array
     */
    public function server_login($user = "", $password = "")
    {
        $request = "/server/login?user=".$user."&password=".md5($password);
        $response = $this->send($request);
        if( !isset($response['ERROR']) )
        {
            $this->ci->session->set_userdata('jedox_sid', $response[0]);
        }
        else
        {
            $this->ci->session->unset_userdata('jedox_sid');
        }
        return $response;
    }
    
    /**
     * Logs out of Jedox server. Session identifier will be unset.
     * 
     * Usage:
     * 
     * $this->jedoxapi->server_logout();
     * 
     * Returns:
     * 
     * array(
     *      "OK" => 1
     * )
     * 
     * @access  public
     * @param   none
     * @return  array
     */
    
    public function server_logout()
    {
        $request = "/server/logout?sid=".$this->ci->session->userdata('jedox_sid');
        $response = $this->send($request);
        //when logging out you should also delete/unset session data of ci to prevent access to 'secured' pages.
        $this->ci->session->unset_userdata('jedox_sid');
        return $response;
    }
    
    /**
     * Show databases of specified Jedox server.
     * 
     * Usage:
     * 
     * $this->jedoxapi->server_database([show_normal, bool, optional], [show_system, bool, optional], [show_user_info, bool, optional]);
     * ex: $this->jedoxapi->server_database(1, 0, 0);
     * 
     * @access  public
     * @param   bool
     * @return  array
     */
    
    public function server_databases($show_normal = 1, $show_system = 0, $show_user_info = 0)
    {
        $request = "/server/databases?sid=".$this->ci->session->userdata('jedox_sid')."&show_normal=".$show_normal."&show_system=".$show_system."&show_user_info=".$show_user_info;
        $response = $this->send($request);
        $count = 0;
        $result_array = array();
        if( !isset($response['ERROR']) )
        {
            // If no error occurs
            foreach($response as $row)
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
        else 
        {
            if(DATA_ERRORS){
                echo "<pre>";
                print_r($response);
                echo "</pre>";
            }
            exit("Sorry, an error has occurred that prevents the system from continuing.");
        }
    }

    /**
     * Selects a database based on name from an array of databases generated via server_databases() and returns database data as an array.
     * 
     * Usage:
     * 
     * $this->jedoxapi->server_databases_select([database_array, array, required], [database_name, string, required])
     * 
     * @access  public
     * @param   array
     * @param   string
     * @return  array
     */
    
    public function server_databases_select($array, $dbname)
    {
        //used to dynamically select database based on name
        $data = "";
        foreach($array as $row)
        {
            if($row['name_database'] == $dbname)
            {
                $data = $row;
            }
        }
        if($data == "")
        {
            exit("Sorry, the database you selected doesnt exist");
        }
        else 
        {
           return $data; 
        }
    }
    
    /**
     * Show cubes of selected database.
     * 
     * @access  public
     * @param   string
     * @param   bool
     * @return  array
     */
    
    public function database_cubes($database, $show_normal = 1, $show_system = 0, $show_attribute = 0, $show_info = 0, $show_gputype = 1)
    {
        $request = "/database/cubes?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&show_normal=".$show_normal."&show_system=".$show_system."&show_attribute=".$show_attribute."&show_info=".$show_info."&show_gputype=".$show_gputype;
        $response = $this->send($request);
        $count = 0;
        $result_array = array();
        if( !isset($response['ERROR']) )
        {
            foreach($response as $row)
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
        else 
        {
            if(DATA_ERRORS){
                echo "<pre>";
                print_r($response);
                echo "</pre>";
            }
            exit("Sorry, an error has occurred that prevents the system from continuing.");
        }
    }
    
    /**
     * Load a database from disk based on its identifier.
     * Warning: This reloads the Database so all unsaved data will be deleted.
     * 
     * Returns:
     * 
     * array(
     *      "OK" => 1
     * )
     * 
     * @access  public
     * @param   int
     * @return  array
     */
     
    public function database_load($database)
    {
        $request = "/database/load?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database;
        $response = $this->send($request);
        if( !isset($response['ERROR']) )
        {
            return $response;
        }
        else
        {
            if(DATA_ERRORS){
                echo "<pre>";
                print_r($response);
                echo "</pre>";
            }
            exit("Sorry, an error has occurred that prevents the system from continuing.");
        }
    }
    
    /**
     * Loads specific cube based on identifiers.
     * 
     * Usage:
     * 
     * $this->jedoxapi->cube_load([database identifier, int, required], [cube identifier, int, required]);
     * 
     * array(
     *      "OK" => 1
     * )
     * 
     * @access  public
     * @param   int
     * @return  array
     */
     
    public function cube_load($database, $cube)
    {
        $request = "/cube/load?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&cube=".$cube;
        $response = $this->send($request);
        if( !isset($response['ERROR']) )
        {
            return $response;
        }
        else
        {
            if(DATA_ERRORS){
                echo "<pre>";
                print_r($response);
                echo "</pre>";
            }
            exit("Sorry, an error has occurred that prevents the system from continuing.");
        }
        
    }
    
    /**
     * Loads multiple cubes based on database identifier, array list of cubes and cube names.
     * 
     * Usage:
     * 
     * $this->jedoxapi->cube_multiload([database identifier, int, required], [cube array, array, required], [comma delimited cube names, string, required]);
     * 
     * @access  public
     * @param   int
     * @param   array
     * @param   string
     * @return  array
     */
    public function cube_multiload($database, $array, $cube_names)
    {
        //dynamically load multiple cubes based on name. returns array of cubes loaded and if it is successfull;
        /*
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
		*/
		$data = "Function is currently disabled";
		return $data;
    }
    
    /**
     * Get Dimension ID's of cube based on cube names
     * 
     * Usage:
     * 
     * $this->jedoxapi->get_dimension_id([array of cubes, array, required], [name of cube, string, required])
     * 
     * @access  public
     * @param   array
     * @param   string
     * @return  array
     */
    
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
    
	/**
	 * Get element name using id.
	 * @access  public
     * @param   array
     * @param   string
     * @return  string
	 */
	public function get_name($array, $id){
		$name = '';
		foreach ($array as $row)
		{
			if($row['element'] == $id){
				$name = $row['name_element'];
			}
		}
		return $name;
	}
    /**
     * Get cube data from array based on name
     * 
     * Usage:
     * 
     * $this->jedoxapi->get_cube_data([array of cubes, array, required], [name of cube, string, required]);
     * 
     * @access  public
     * @param   array
     * @param   string
     * @return  array
     */
    
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
    
    /**
     * Shows all elements of a dimension
     * 
     * Usage:
     * 
     * $this->jedoxapi->dimension_elements([database identifier, int, required], [dimension identifier, int, required], [Identifier of parent for element filtering, int, optional], [Comma delimited offset, string, optional], [additional information about the element lock, bool, optional]);
     * 
     * @access  public
     * @param   int
     * @param   string
     * @param   bool
     * @return  array
     */
    
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
        $count = 0;
        $result_array = array();
        
        if( !isset($response['ERROR']) )
        {
            foreach($response as $row)
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
        else
        {
            if(DATA_ERRORS){
                echo "<pre>";
                print_r($response);
                echo "</pre>";
            }
            exit("Sorry, an error has occurred that prevents the system from continuing.");
        }
    }
    /**
     * Get dimension elements based on comma delimited string filter.
     * 
     * @access  public
     * @param   array
     * @param   string
     * @return  array
     */
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
    
    /**
     * Exports values of cube cells
     * 
     * Usage:
     * 
     * $this->jedoxapi->cell_export(
     *      [database identifier, int, required],
     *      [cube identifier, int, required],
     *      [blocksize(default is 1000), int, optional],
     *      [Begin export after the path (default is to start with first path), int, optional],
     *      [area (Comma separated list of element identifiers list), string, optional],
     *      [condition(Condition on the value of numeric or string cells), string, optional],
     *      [use rules(If 1, then export rule based cell values. default is 1), bool, optional],
     *      [base only(If 1, then export only base cells. default is 0), bool, optional], 
     *      [skip empty(If 0, then export empty cells as well. default is 1), bool, optional],
     *      [type(Type of exported cells. 0=numeric and string, 1=only numeric, 2=only string.default is 0), int, optional],
     *      [properties(Comma separated list of cell property ids.), string, optional]
     * );
     * 
     * @access  public
     * @param   int
     * @param   string
     * @param   bool
     * @return  array
     */

	public function element_create($database, $dimension, $element, $parent = '')
	{
		$request = "/element/create?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&dimension=".$dimension."&new_name=".$element."&type=1";
		$response = $this->send($request);
		
		$element = $response[0];
		
		if( $parent != '' )
		{
			$request = "/element/append?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&dimension=".$dimension."&element=".$parent."&children=".$element;
			$response = $this->send($request);
		}
		
		return $element;
	}
	
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
        $count = 0;
        $result_array = array();
        
        if( !isset($response['ERROR']) )
        {    
            foreach($response as $row)
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
                    if($properties == "")
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
        else 
        {
            if(DATA_ERRORS){
                echo "<pre>";
                print_r($response);
                echo "</pre>";
            }
            exit("Sorry, an error has occurred during cell export that prevents the system from continuing.");
        }
    }

    /**
     * Make values from cell export negative
     * 
     * @access  public
     * @param   array
     * @return  array
     */
     
     public function cell_turn_negative($array)
     {
         $result_array = array();
         foreach($array as $row)
         {
             $row['value'] = $row['value']*-1;
             $result_array[] = $row;
         }
         return $result_array;
     }
    
    /**
     * Recourse an array using parent name and get all child.
     * 
     * Usage:
     * 
     * $this->jedoxapi->array_element_filter([array data, required], [parent name(name_element), string, required]);
     * 
     * @access  public
     * @param   array
     * @param   string
     * @return  array
     */
     
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
    
    private function array_element_filter_child($array, $parentid, $stack)
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
    /**
     * Sets alias.
     * 
     * Usage:
     * 
     * $this->jedoxapi->set_alias([element_array, array, required], [alias_array, array, required]);
     * 
     * @access  public
     * @param   array
     * @return  array
     */
    public function set_alias($array, $alias)
    {
        $result_array = array();
        foreach($array as $row)
        {
            $found = 0;
            foreach($alias as $rows)
            {
                $path = explode(',', $rows['path']);
                if($row['element'] == $path['1'])
                {
                    $row['name_element'] = $rows['value'];
                    $result_array[] = $row;
                    $found = 1;
                }
            }
            if($found == 0)
            {
                //if not matching alias is found. return row value "as is"
                $result_array[] = $row;
            }
        }
        return $result_array;
    }
    
    /**
     * Gets area based on params.
     * 
     * Usage:
     * 
     * Get area of supplied array: 
     * $this->jedoxapi->get_area([element array, array, required]);
     * Get area only of specified elements names: 
     * $this->jedoxapi->get_area([element array, array, required], [comma delimited element names, string, optional]);
     * Get area only of specified elements names and compare using url string: 
     * $this->jedoxapi->get_area([element array, array, required], [comma delimited element names, string, optional], TRUE);
     * 
     * @access  public
     * @param   array
     * @param   string
     * @param   bool
     * @return  string
     */
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
    
    /**
     * Add values of arrays by path
     * 
     * @access  public
     * @param   array
     * @param   string
     * @return  array
     */
    
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
    
    /**
     * Subtract values of array by path
     * 
     * @access  public
     * @param   array
     * @param   int
     * @return  array
     */
    
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
    
    /**
     * Divide values of array by path then computes percentage.
     * 
     * @access  public
     * @param   array
     * @param   int
     * @return  array
     */
    
    public function divide_cell_array($array1, $array2, $path1, $path2)
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
                    if($row['value'] == 0 || $rows['value'] == 0)
                    {
                        $row['value'] = 0;
                        $result_array[] = $row;
                    }
                    else
                    {
                        $row['value'] = ($row['value'] / $rows['value'])*100;
                        $result_array[] = $row;
                    }
                    
                    
                }
            }
        }
        return $result_array;
    }
    
    /**
     * Removes dimension element with children and reurns only "base elements".
     * 
     * Usage:
     * 
     * $this->jeodxapi->dimension_elements_base([dimension elements, array, required]);
     * 
     * @access  public
     * @param   array
     * @return  array
     */
    public function dimension_elements_base($array)
    {
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
    
    /**
     * Sort array by name based on order of string provided. also removes elements not included in names.
     * 
     * Usage:
     * 
     * $this->jedoxapi->dimension_sort_by_name([dimension array, array, required], [comma delimited string, string, required]);
     * 
     * @access  public
     * @param   array
     * @param   string
     * @return  array
     */
    
    public function dimension_sort_by_name($array, $names='')
    {
        $result_array = array();
        $namelist = explode(",", $names);
        foreach($namelist as $row)
        {
            foreach($array as $arow)
            {
                if($arow['name_element'] == $row)
                {
                    $result_array[] = $arow;
                }
            }
            
        }
        return $result_array;
    }
    
    
    
    /**
     * Custom print_r to trace values
     * 
     * Usage:
     * 
     * $this->jedoxapi->traceme([mixed data, mixed, required],[heading/title, string, optional"];
     * 
     * @access  public
     * @param   mixed
     * @param   string
     * @return  mixed data
     */
    
    public function traceme($data = '', $heading = '')
    {
        echo "<div><h1>".$heading."</h1><pre>";
        print_r($data);
        echo "</pre></div>";
    }
    
    /**
     * Permission settings. Add values as needed
     * 
     * Usage:
     * $this->jedoxapi->page_permission([group_name, string, required], [page_name, string, required]);
     * 
     * Returns:
     * 
     * bool = TRUE, if allowed access. FALSE, if not allowed access.
     * 
     * @access  public
     * @param   string
     * @return  bool
     */
    public function page_permission($group_name, $page_name)
    {
        $cond = FALSE;
        //Set pages allowed for group
        $admin_group = array(
            "efficiency_resources",
            "efficiency_resources_details", 
            "efficiency_costs",
            "profitability", 
            "sales_plan",
            "efficiency_operations",
            "calculate_rates",
            "efficiency_operations_details",
            "efficiency_processes",
            "efficiency_processes_details",
            "efficiency_products",
            "efficiency_products_details",
            "operations_kpi"
        );
        $proeo_admin_group = array(
            "efficiency_resources",
            "efficiency_resources_details", 
            "efficiency_costs",
            "profitability", 
            "sales_plan",
            "efficiency_operations",
            "calculate_rates",
            "efficiency_operations_details",
            "efficiency_processes",
            "efficiency_processes_details",
            "efficiency_products",
            "efficiency_products_details"
        );
        $proeo_user_group = array(
            "efficiency_resources_details", 
            "profitability", 
            "sales_plan"
        );
        
		$client_4001_group = array(
            "efficiency_resources",
            "efficiency_resources_details", 
            "efficiency_costs",
            "profitability", 
            "sales_plan",
            "efficiency_operations",
            "calculate_rates",
            "efficiency_operations_details",
            "efficiency_processes",
            "efficiency_processes_details",
            "efficiency_products",
            "efficiency_products_details"
        );
		
		$client_4002_group = array(
            "efficiency_resources",
            "efficiency_resources_details", 
            "efficiency_costs",
            "profitability", 
            "sales_plan",
            "efficiency_operations",
            "calculate_rates",
            "efficiency_operations_details",
            "efficiency_processes",
            "efficiency_processes_details",
            "efficiency_products",
            "efficiency_products_details"
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
            
			if($row == "client_4001")
            {
                if(in_array($page_name, $client_4001_group))
                {
                    $cond = TRUE;
                }
            }
			if($row == "client_4002")
            {
                if(in_array($page_name, $client_4002_group))
                {
                    $cond = TRUE;
                }
            }
        }
        return $cond;
    }
    
    public function set_tracer($value = FALSE)
    {
    	$this->data_trace = $value;
    }
	
	/*
	 * auto adjust receiver element based on group permission
	 */
	 
	 public function receiver_permission($group, $base_receiver)
	 {
		$data = '';
		if($group == 'admin' || $group == 'proeo_admin' || $group == 'proeo_user' || $group == 'SSI_Manager')
		{
			$data = $base_receiver;
		}
		else
		{
			$data = $group;
		}
		return $data;
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
	
	public function cell_replacebulk($database, $cube, $path, $value, $add = '', $splash = '')
	{
		$request = "/cell/replace_bulk?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&cube=".$cube."&paths=".$path."&values=".$value;
		if($add != ''){
			$request .= "&add=".$add;
		}
		if($splash != ''){
			$request .= "&splash=".$splash;
		}
		$response = $this->send($request);
		return $response;
	}
	
	public function rule_create($database, $cube, $rule)
	{
		$rule = urlencode($rule);
		$request = "/rule/create?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&cube=".$cube."&definition=".$rule."&activate=1"."&position=1";
		$response = $this->send($request);
		return $response;
	}
	
	public function cell_copy($database, $cube, $path, $path_to, $use_rules = '1')
	{
		$request = "/cell/copy?sid=".$this->ci->session->userdata('jedox_sid')."&database=".$database."&cube=".$cube."&path=".$path."&path_to=".$path_to."&use_rules=".$use_rules;
		$response = $this->send($request);
		return $response;
	}
	
	
	
}
