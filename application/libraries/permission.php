<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permission
{
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
            "efficiency_products_details"
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
}
	