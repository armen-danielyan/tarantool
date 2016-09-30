<!--

<h3>Dashboard</h3>
<div>
	<ul class="ulnav">
		<li><span class="ui-icon ui-icon-home left" style="margin-right: .3em;"></span><?php echo anchor('dashboard', 'Home', array('title' => 'Go to Dashboard Home')); ?></li>
		<li><span class="ui-icon ui-icon-person left" style="margin-right: .3em;"></span><?php echo anchor('support', 'Support', array('title' => 'Get Online help')); ?></li>
		<li><span class="ui-icon ui-icon-power left" style="margin-right: .3em;"></span><?php echo anchor('logout', 'Logout', array('title' => 'Logout of ProEO')); ?></li>
	</ul>
</div>

-->
<h3>Efficiency <div class="minicon"> </div></h3>
<div>
	<ul class="ulnav">
<?php
if($this->jedoxapi->page_permission($jedox_user_details['group_names'], "efficiency_costs"))
{
	echo "        <li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('efficiency_costs', 'Costs', array('title' => 'View Efficiency Costs'))."</li>";  
}

if($this->jedoxapi->page_permission($jedox_user_details['group_names'], "efficiency_operations"))
{
	echo "        <li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('efficiency_operations_v2', 'Operations', array('title' => 'View Efficiency Operations'))."</li>";   
}

if($this->jedoxapi->page_permission($jedox_user_details['group_names'], "efficiency_operations"))
{
	if($this->session->userdata('jedox_db') == "ProEo_Client_4")
	{
		echo "        <li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
		echo anchor('efficiency_balanced_scorecard', 'Balanced Scorecard', array('title' => 'View Efficiency Balanced Scorecard'))."</li>";   
	}
}

if($this->jedoxapi->page_permission($jedox_user_details['group_names'], "efficiency_processes"))
{
	echo "        <li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('efficiency_processes_v2', 'Processes', array('title' => 'View Efficiency Processes'))."</li>";  
}

if($this->jedoxapi->page_permission($jedox_user_details['group_names'], "efficiency_products"))
{
	echo "       <li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('efficiency_products_v2', 'Products', array('title' => 'View Efficiency Products'))."</li>";   
}

if($this->jedoxapi->page_permission($jedox_user_details['group_names'], "efficiency_resources"))
{
	echo "       <li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('efficiency_resources_v2', 'Resources', array('title' => 'View Efficiency Resources'))."</li>";
}
?>
	</ul>
</div>

<h3>Profitability <div class="minicon"> </div></h3>
<div>
	<ul class="ulnav">
<?php
if($this->jedoxapi->page_permission($jedox_user_details['group_names'], "profitability"))
{
	echo "		<li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('profitability_geography', 'By Geography', array('title' => 'View Profitability by Geography'))."</li>";
}

if($this->jedoxapi->page_permission($jedox_user_details['group_names'], "profitability"))
{
	if($this->session->userdata('jedox_db') == "ProEo_Brewing_3")
	{
		echo "      <li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
		echo anchor('profitability_product_group_brewing', 'By Product Group', array('title' => 'View Profitability by Product Group'))."</li>";
	} else {
		echo "      <li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
		echo anchor('profitability_product_group', 'By Product Group', array('title' => 'View Profitability by Product Group'))."</li>";
	}
	
}
?>
	</ul>
</div>

<h3>Simulation <div class="minicon"> </div></h3>
<div>
	<ul class="ulnav">
		<li><span class="ui-icon ui-icon-document left" style="margin-right: .3em;"></span><?php echo anchor('sales_plan_back_flush', 'Sales Plan Back Flush', array('title' => 'View Sales Plan Back Flush')); ?></li>
		<li><span class="ui-icon ui-icon-document left" style="margin-right: .3em;"></span><?php echo anchor('profit_optimization_v2', 'Profit Optimization', array('title' => 'View Profit Optimization')); ?></li>
		<li><span class="ui-icon ui-icon-document left" style="margin-right: .3em;"></span><?php echo anchor('input_price_simulation', 'Input Price Simulation', array('title' => 'View Input Price Simulation')); ?></li>
		<li><span class="ui-icon ui-icon-document left" style="margin-right: .3em;"></span><?php echo anchor('profitability_product_group_dv', 'Compare Versions', array('title' => 'View Compare Versions')); ?></li>
	</ul>
</div>



<h3>Investment <div class="minicon"> </div></h3>
<div>
	<ul class="ulnav">
<?php
if($this->jedoxapi->page_permission($jedox_user_details['group_names'], "efficiency_operations"))
{
	echo "		<li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('improvement_areas', 'Strategic Initiatives', array('title' => 'View Strategic Initiatives'))."</li>\r\n";
	
	echo "		<li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('net_present_value_summary', 'Net Present Value', array('title' => 'View Net Present Value'))."</li>\r\n";
	
	echo "		<li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('investment_costs', 'Cost Overview', array('title' => 'View Cost Overview'))."</li>";
}
		?>
	</ul>
</div>

<h3>Advanced <div class="minicon"> </div></h3>
<div>
	<ul class="ulnav">
		<li><span class="ui-icon ui-icon-document left" style="margin-right: .3em;"></span><?php echo anchor('advance_r_calculations', 'Calculate Correlations', array('title' => 'View Advance R Calculations')); ?></li>
<?php
if($this->jedoxapi->page_permission($jedox_user_details['group_names'], "calculate_rates"))
{
	echo "		<li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('calculate_rates', 'Calculate Rates', array('title' => 'View Calculate Rates'))."</li>";
	
	echo "		<li><span class=\"ui-icon ui-icon-document left\" style=\"margin-right: .3em;\"></span>";
	echo anchor('calculate_target', 'Calculate Target', array('title' => 'View Calculate Target'))."</li>";
}
?>
		<li><span class="ui-icon ui-icon-document left" style="margin-right: .3em;"></span><?php echo anchor(base_url().'admin.php', 'Jedox Admin', array('title' => 'Jedox Admin', 'target' => '_blank')); ?></li>
		<li><span class="ui-icon ui-icon-document left" style="margin-right: .3em;"></span><?php echo anchor('data_loads', 'Data Loads', array('title' => 'Data Loads')); ?></li>
	</ul>
</div>