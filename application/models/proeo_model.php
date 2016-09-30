<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Proeo_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Checks if chart is already pinned on dashboard.
     * 
     * Returns 0 if chart not pinned. Returns values greater than 0 if already pinned.
     * If returned value is 2 or more then if mean you have duplicate entries for your pinned charts.
     */
    
    function check_chart($owner, $database, $chart_name, $chart_url)
    {
        $query = $this->db->get_where(
            'chartpin',
            array(
                'owner' => $owner,
                'database' => $database,
                'chart_name' => $chart_name,
                'chart_url' => $chart_url
            )
        );
        $data = $query->num_rows();
        return $data;
    }
    
    /**
     * Saves chart info to database. make sure to check if chart exist first before using this via check_chart to prevent multiple entries.
     * 
     * returns nothing.
     */
    
    function pin_chart($owner, $database, $chart_name, $chart_url, $chart_link)
    {
		//make sure chart does not exist on db yet. if it exist silently do nothing.
		$check = $this->check_chart($owner, $database, $chart_name, $chart_url);
		if($check == 0)
		{
			$data = array(
				'owner' => $owner,
				'database' => $database,
				'chart_name' => $chart_name,
				'chart_url' => $chart_url,
				'chart_link' => $chart_link
			);
			// data inserted but no "order" yet. inital "order" value should be equal to "id" to make sure it always ends up last.
			$query1 = $this->db->insert('chartpin', $data); 
			$order = $this->db->insert_id();
			// update newly inserted data so that initial value of "order" is equal to its "id"
			$query2 = $this->db->update('chartpin', array('order' => $order), array('id' => $order));
		}
		
    }
    
    /**
     * Delete chart info from database.
     * 
     * returns nothing.
     */
    
    function unpin_chart($id, $owner, $database)
    {
		// make sure chart still exist first before trying to delete.
		$query = $this->db->get_where(
            'chartpin',
            array(
                'id' => $id,
                'owner' => $owner,
                'database' => $database
            )
        );
        $data = $query->num_rows();
		if($data > 0)
		{
			$query1 = $this->db->delete('chartpin', array('id' => $id)); 
		}
		
    }
    
	
	/**
	 * Get chart info based on owner and database. 
	 * 
	 * use:
	 * $query->result_array();
	 * 
	 * to parse it into an array for processing
	 */
	
	function get_charts($owner, $database)
	{
		$this->db->order_by("order", "asc");
		$query = $this->db->get_where(
            'chartpin',
            array(
                'owner' => $owner,
                'database' => $database
            )
        );
		return $query;
	}
	
	/**
	 * Update order of charts. this will be used inside a loop
	 */
	
	function order_chart($id, $owner, $database, $order)
	{
		$query = $this->db->update('chartpin', 
			array('order' => $order), 
			array('id' => $id, 'owner' => $owner, 'database' => $database)
		);
	}
	
}