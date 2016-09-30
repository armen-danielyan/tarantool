<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Sales Plan Back Flush</title>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/FusionCharts.js"></script>
<link href='http://fonts.googleapis.com/css?family=Cuprum:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/smoothness/jquery-ui-1.9.1.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" media="screen" />
<?php
function get_dimension_name_by_id($array, $id)
{
	$result_array = '';
	foreach($array as $row)
	{
		if($row['element'] == $id)
		{
			$result_array = $row['name_element'];
		}
	}
	return $result_array;
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $( "#sidebar_menu" ).accordion({
        heightStyle: "content",
        collapsible: false,
        active: 2
    });
    $( "#filter_menu" ).accordion({
        heightStyle: "content",
        collapsible: false
    });
    
    $( document ).tooltip({
        track: true
    });
    
});

function tshowhide()
{
	if(document.getElementById("tsidebar").style.display == "none"){
		document.getElementById("tsidebar").style.display = "";
		document.getElementById("tsidebarf").style.display = "";
		document.getElementById("togme").src = "<?php echo base_url(); ?>assets/images/bar1.png";
	} else {
		document.getElementById("tsidebar").style.display = "none";
		document.getElementById("tsidebarf").style.display = "none";
		document.getElementById("togme").src = "<?php echo base_url(); ?>assets/images/bar2.png";
	}
}

function grun(year, month, version, loc){
	var myrunset = {Year: year, Month: month, Version: version};
	
	$.ajax({
		url:"<?php echo site_url('sales_plan_back_flush/grun'); ?>",
		type: "post",
	    data: myrunset,
		success:function(result){
			$(loc).html(result);
			
			//if(result == "Completed" || result == "Completed with Warnings")
			//{
			document.getElementById("r_etl").style.display = "none";
			document.getElementById("s_etl").style.display = "";
			//}
			//alert(result);
		}
	});
}

function grun1(year, month, version, loc){
	var myrunset = {Year: year, Month: month, Version: version};
	
	$.ajax({
		url:"<?php echo site_url('sales_plan_back_flush/grun1'); ?>",
		type: "post",
	    data: myrunset,
		success:function(result){
			$(loc).html(result);
			
			//if(result == "Completed" || result == "Completed with Warnings")
			//{
			document.getElementById("r_etl").style.display = "none";
			document.getElementById("s_etl").style.display = "";
			//}
			//alert(result);
		}
	});
}

function grun2(year, month, version, loc){
	var myrunset = {Year: year, Month: month, Version: version};
	
	$.ajax({
		url:"<?php echo site_url('sales_plan_back_flush/grun2'); ?>",
		type: "post",
	    data: myrunset,
		success:function(result){
			$(loc).html(result);
			
			//if(result == "Completed" || result == "Completed with Warnings")
			//{
			document.getElementById("r_etl").style.display = "none";
			document.getElementById("s_etl").style.display = "";
			//}
			//alert(result);
		}
	});
}

function grun3(year, month, version, loc){
	var myrunset = {Year: year, Month: month, Version: version};
	
	$.ajax({
		url:"<?php echo site_url('sales_plan_back_flush/grun3'); ?>",
		type: "post",
	    data: myrunset,
		success:function(result){
			$(loc).html(result);
			
			//if(result == "Completed" || result == "Completed with Warnings")
			//{
			document.getElementById("r_etl").style.display = "none";
			document.getElementById("s_etl").style.display = "";
			//}
			//alert(result);
		}
	});
}

function grun4(year, month, version, loc){
	var myrunset = {Year: year, Month: month, Version: version};
	
	$.ajax({
		url:"<?php echo site_url('sales_plan_back_flush/grun4'); ?>",
		type: "post",
	    data: myrunset,
		success:function(result){
			$(loc).html(result);
			
			//if(result == "Completed" || result == "Completed with Warnings")
			//{
			document.getElementById("r_etl").style.display = "none";
			document.getElementById("s_etl").style.display = "";
			//}
			//alert(result);
		}
	});
}

function grun5(year, month, version, loc){
	var myrunset = {Year: year, Month: month, Version: version};
	
	$.ajax({
		url:"<?php echo site_url('sales_plan_back_flush/grun5'); ?>",
		type: "post",
	    data: myrunset,
		success:function(result){
			$(loc).html(result);
			
			//if(result == "Completed" || result == "Completed with Warnings")
			//{
			document.getElementById("r_etl").style.display = "none";
			document.getElementById("s_etl").style.display = "";
			//}
			//alert(result);
		}
	});
}

function gstat(pid, loc){
	
	//var mydataset = {id: pid};
	
	$.ajax({
		url:"<?php echo site_url('sales_plan_back_flush/gstatus'); ?>",
		//type: "post",
	    //data: mydataset,
		success:function(result){
			$(loc).html(result);
			
			if(result == "Completed" || result == "Completed with Warnings" || result == "Completed successfully")
			{
				document.getElementById("action").style.display = "";
			}
		}
	});
}

</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tabber.js"></script>
</head>

<body>
<table class="content_block" cellpadding="0" cellspacing="0">
    <tr>
        <td id="tsidebar">
            <div class="aibanner">
				<?php $this->load->view("logo_view"); ?>
			</div>
            <div id="sidebar_menu">
                <?php $this->load->view("accordion_view"); ?>
                
            </div>
            
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Sales Plan Back Flush</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
			<?php
                if($step == 9)
                {
                ?>
			<div class="tabber">
				<div class="tabbertab">
					<h3>Net Margin</h3>
					<table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"></div></td>
                    	</tr>
                    </table>
					
				</div>
			</div>
			<?php
				}
			?>
            <div class="content_div">
            	<?php
                if($step == 1)
                {
                ?>
            	<form id="form2" name="form2" method="post" action="<?php echo site_url('sales_plan_back_flush'); ?>">
            	
            	<table class="avtable_2">
            		<tr>
            			<td class="thead" >From: Month/Year</td>
            			<td class="thead" >To: Month/Year</td>
            			<td class="thead" >Version (Select One)</td>
            		</tr>
            		<tr >
            			
            			<td >
            				<select name="monthf" title="Select Month">
                            <?php
                                foreach($form_months as $row)
                                {
                                    $depth = '';
                                    //for($i=0; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;';
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($monthf == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                            <select name="yearf" title="Select Year">
                            <?php
                                foreach($form_year as $row)
                                {
                                    $depth = '';
                                    //for($i=0; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;';
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($yearf == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
            			</td>
            			<td >
            				<select name="montht" title="Select Month">
                            <?php
                                foreach($form_months as $row)
                                {
                                    $depth = '';
                                    //for($i=0; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;';
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($montht == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                            <select name="yeart" title="Select Year">
                            <?php
                                foreach($form_year as $row)
                                {
                                    $depth = '';
                                    //for($i=0; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;';
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($yeart == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
            			</td>
            			<td style="text-align: left !important;">
            				
            				
            				<select name="version" title="Select Version">
                            <?php
                                foreach($form_version as $row)
                                {
                                    $depth = '';
                                    //for($i=0; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;';
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>"  ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
            				
            			</td>
            		</tr>
            		<tr>
            			<td colspan="3" style="text-align: center !important;">
            				<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
            				<input name="action"  type="submit" id="action"  value="Continue" class="obutton1" alt="Continue" title="Continue" />
            			</td>
            		</tr>
            	</table>
               
               </form>
               
               <?php
                }
                ?>
               
               <?php
                if($step == 2)
                {
                ?>
                <?php
                	echo $this->session->userdata('display_range');
                ?>
                
                <form id="form2" name="form2" method="post" action="<?php echo site_url('sales_plan_back_flush'); ?>">
				<table id="tb1" class="avtable_2">
					<tr class="tmain">
						<td class="label">Prepare for back flush</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a id="r_etl" onclick="grun('<?php echo "[".$year_range."]"; ?>', '<?php echo "[".$month_range."]"; ?>', '<?php echo $version_name; ?>', '#etlstatus'); return false;" href="#">Run ETL</a>
							<a style="display:none;" id="s_etl" onclick="gstat('', '#etlstatus'); return false;" href="#">Update Status</a>
							
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
							<input name="action" style="display:none;" type="submit" id="action"  value="Copy Values" class="obutton1" alt="Copy Values" title="Copy Values" />
						</td>
					</tr>
				</table>
				
				</form>
				<?php
                }
                ?>
               
               <?php
                if($step == 3)
                {
                ?>
                <?php
                	echo $this->session->userdata('display_range');
                ?>
                
               <form id="form2" name="form2" method="post" action="<?php echo site_url('sales_plan_back_flush'); ?>">
				<table id="tb1" class="avtable_2">
					<tr class="tmain">
						<td class="label">Import new sales volume from load sheet</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a id="r_etl" onclick="grun1('<?php echo "[".$year_range."]"; ?>', '<?php echo "[".$month_range."]"; ?>', '<?php echo $version_name; ?>', '#etlstatus'); return false;" href="#">Run ETL</a>
							<a style="display:none;" id="s_etl" onclick="gstat('', '#etlstatus'); return false;" href="#">Update Status</a>
							
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
							<input name="action" style="" type="submit" id="action"  value="Next" class="obutton1" alt="Next" title="Next" />
						</td>
					</tr>
				</table>
				<strong>Note:</strong> Skip this routine if you opt to capture the sales manually in a paste view. Capture new sales before proceeding to the next step. <br />
				
				</form>
               
               <?php
                }
                ?>
               
               <?php
                if($step == 4)
                {
                ?>
                
                <?php
                	echo $this->session->userdata('display_range');
                ?>
                
               <form id="form2" name="form2" method="post" action="<?php echo site_url('sales_plan_back_flush'); ?>">
				<table id="tb1" class="avtable_2">
					<tr class="tmain">
						<td class="label">Run Back Flush</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a id="r_etl" onclick="grun2('<?php echo "[".$year_range."]"; ?>', '<?php echo "[".$month_range."]"; ?>', '<?php echo $version_name; ?>', '#etlstatus'); return false;" href="#">Run ETL</a>
							<a style="display:none;" id="s_etl" onclick="gstat('', '#etlstatus'); return false;" href="#">Update Status</a>
							
						</td>
					</tr>
					
					<tr>
						<td colspan="3">
							<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
							<input name="action" style="display:none;" type="submit" id="action"  value="Next" class="obutton1" alt="Next" title="Next" />
						</td>
					</tr>
					
				</table>
				
				</form>
               <?php
                }
                ?>
               
               <?php
                if($step == 5)
                {
                ?>
                
                <?php
                	echo $this->session->userdata('display_range');
                ?>
                
               <form id="form2" name="form2" method="post" action="<?php echo site_url('sales_plan_back_flush'); ?>">
				<table id="tb1" class="avtable_2">
					<tr class="tmain">
						<td class="label">Run Back Flush</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a id="r_etl" onclick="grun3('<?php echo "[".$year_range."]"; ?>', '<?php echo "[".$month_range."]"; ?>', '<?php echo $version_name; ?>', '#etlstatus'); return false;" href="#">Run ETL</a>
							<a style="display:none;" id="s_etl" onclick="gstat('', '#etlstatus'); return false;" href="#">Update Status</a>
							
						</td>
					</tr>
					
					<tr>
						<td colspan="3">
							<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
							<input name="action" style="display:none;" type="submit" id="action"  value="Next" class="obutton1" alt="Next" title="Next" />
						</td>
					</tr>
					
				</table>
				
				</form>
               <?php
                }
                ?>
               
               <?php
                if($step == 6)
                {
                ?>
                
                <?php
                	echo $this->session->userdata('display_range');
                ?>
                
               <form id="form2" name="form2" method="post" action="<?php echo site_url('sales_plan_back_flush'); ?>">
				<table id="tb1" class="avtable_2">
					<tr class="tmain">
						<td class="label">Update Primary Requirements</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a id="r_etl" onclick="grun4('<?php echo "[".$year_range."]"; ?>', '<?php echo "[".$month_range."]"; ?>', '<?php echo $version_name; ?>', '#etlstatus'); return false;" href="#">Run ETL</a>
							<a style="display:none;" id="s_etl" onclick="gstat('', '#etlstatus'); return false;" href="#">Update Status</a>
							
						</td>
					</tr>
					
					<tr>
						<td colspan="3">
							<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
							<input name="action" style="display:none;" type="submit" id="action"  value="Next" class="obutton1" alt="Next" title="Next" />
						</td>
					</tr>
					
				</table>
				
				</form>
               
               
               <?php
                }
                ?>
               
               <?php
                if($step == 7)
                {
                ?>
                
                <?php
                	echo $this->session->userdata('display_range');
                ?>
                
               <form id="form2" name="form2" method="post" action="<?php echo site_url('sales_plan_back_flush'); ?>">
				<table id="tb1" class="avtable_2">
					<tr class="tmain">
						<td class="label" colspan="2">Raw Material Rates</td>
						
					</tr>
					<?php
					$range_array = $this->session->userdata('range_array');
					$version = $this->session->userdata('ver_new');
					$year_name = '';
					$month_name = '';
					foreach($range_array as $row)
					{
						$month_name = get_dimension_name_by_id($form_months, $row['month']);
						$year_name = get_dimension_name_by_id($form_year, $row['year']);
					?>	
					<tr>	
						<td><?php echo $month_name.", ".$year_name ?></td>
						<td>
							<?php
							echo "<iframe class=\"embed\" src=\"../assets/calculate_rates/calc_raw_mat.php?version=".$version."&year=".$row['year']."&month=".$row['month']."&verbose=0\"></iframe>";
							?>
						</td>
					</tr>	
					<?php	
					}
					?>
					
				</table>
				
				<table id="tb2" class="avtable_2">
					<tr class="tmain">
						<td class="label" colspan="2">Secondary Rates</td>
						
					</tr>
					<?php
					$year_name = '';
					$month_name = '';
					foreach($range_array as $row)
					{
						$month_name = get_dimension_name_by_id($form_months, $row['month']);
						$year_name = get_dimension_name_by_id($form_year, $row['year']);
					?>	
					<tr>	
						<td><?php echo $month_name.", ".$year_name ?></td>
						<td>
							<?php
							echo "<iframe class=\"embed\" src=\"../assets/calculate_rates/calc_rates.php?version=".$version."&year=".$row['year']."&month=".$row['month']."&verbose=0&calc_only=1\"></iframe>";
							?>
						</td>
					</tr>	
					<?php	
					}
					?>
					
					<tr>
						<td colspan="2">
							<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
							<input name="action" style="" type="submit" id="action"  value="Next" class="obutton1" alt="Next" title="Next" />
						</td>
					</tr>
					
				</table>
				
				
				</form>
               <?php
                }
                ?>
               
               <?php
                if($step == 8)
                {
                ?>
                
                <?php
                	echo $this->session->userdata('display_range');
                ?>
                
               <form id="form2" name="form2" method="post" action="<?php echo site_url('sales_plan_back_flush'); ?>">
				<table id="tb1" class="avtable_2">
					<tr class="tmain">
						<td class="label">Prepare Reports</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a id="r_etl" onclick="grun5('<?php echo "[".$year_range."]"; ?>', '<?php echo "[".$month_range."]"; ?>', '<?php echo $version_name; ?>', '#etlstatus'); return false;" href="#">Run ETL</a>
							<a style="display:none;" id="s_etl" onclick="gstat('', '#etlstatus'); return false;" href="#">Update Status</a>
							
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
							<input name="action" style="display:none;" type="submit" id="action"  value="Next" class="obutton1" alt="Next" title="Next" />
						</td>
					</tr>
				</table>
				
				</form>
               
               <?php
                }
                ?>
               
               <?php
                if($step == 9)
                {
                ?>
                
                <?php
                	echo $this->session->userdata('display_range');
                ?>
                
               <form id="form2" name="form2" method="post" action="<?php echo site_url('sales_plan_back_flush'); ?>">
				<table id="tb2" class="avtable_2">
					<tr>
						<td>
							<div class="filter_div">
	                            <select name="customer"  class="ddown1" title="Select Customer">
	                            <?php
	                                foreach($form_customer as $row)
	                                {
	                                    $depth = '';
	                                    for($i=0; $i<$row['depth']; $i++)
	                                    {
	                                        $depth .= '&nbsp;&nbsp;';
	                                    }
	                            ?>  
	                                <option value="<?php echo $row['element']; ?>" <?php if($customer == $row['element']){ $n_customer = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
	                            <?php   
	                                }
	                            ?>
	                            </select>
	                        </div>
						</td>
						<td>
							<div class="filter_div">
	                            <select name="product"  class="ddown1" title="Select Product">
	                            <?php
	                                foreach($form_product as $row)
	                                {
	                                    $depth = '';
	                                    for($i=1; $i<$row['depth']; $i++)
	                                    {
	                                        $depth .= '&nbsp;&nbsp;';
	                                    }
	                            ?>  
	                                <option value="<?php echo $row['element']; ?>" <?php if($product == $row['element']){ $n_product = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
	                            <?php   
	                                }
	                            ?>
	                            </select>
	                        </div>
						</td>
						<td>
							<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
							<input name="action"  type="submit" id="action"  value="Update Information" class="obutton1" alt="Update Information" title="Update Information" />
						</td>
					
					</tr>
				</table>	
				
				<?php
					//bulk processes :)
					$range_array = $this->session->userdata('range_array');
					$version = $this->session->userdata('ver_new');
					
					$a1 = $a2 = 0; //sales quantity
					$b1 = $b2 = 0; //sales price
					$c1 = $c2 = 0; //gross revenue
					$d1 = $d2 = 0; //discounts
					$e1 = $e2 = 0; //net revenue
					$f1 = $f2 = 0; //raw material
					$g1 = $g2 = 0; //product margin
					$h1 = $h2 = 0; //proportional cost
					$i1 = $i2 = 0; //contibution margin
					$j1 = $j2 = 0; //fixed cost
					$k1 = $k2 = 0; //gross margin
					$l1 = $l2 = 0; //invoicing
					$m1 = $m2 = 0; //warranties
					$n1 = $n2 = 0; //distribution
					$o1 = $o2 = $o3 = 0; //net margin. not on table data
					$chart1 = '';
					
					foreach($range_array as $row)
					{
						//table1 data
						foreach($table1_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$a1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$a2 += $row1['value'];
							}
							
						}
						
						//table2 data
						foreach($table2_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$b1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$b2 += $row1['value'];
							}
							
						}
						
						//table3 data
						foreach($table3_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$c1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$c2 += $row1['value'];
							}
							
						}
						//----------
						//table4 data
						foreach($table4_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$d1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$d2 += $row1['value'];
							}
							
						}
						
						//table5 data
						foreach($table5_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$e1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$e2 += $row1['value'];
							}
							
						}
						
						//table6 data
						foreach($table6_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$f1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$f2 += $row1['value'];
							}
							
						}
						
						//table7 data
						foreach($table7_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$g1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$g2 += $row1['value'];
							}
							
						}
						
						//table8 data
						foreach($table8_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$h1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$h2 += $row1['value'];
							}
							
						}
						
						//table9 data
						foreach($table9_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$i1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$i2 += $row1['value'];
							}
							
						}
						
						//table10 data
						foreach($table10_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$j1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$j2 += $row1['value'];
							}
							
						}
						
						//table11 data
						foreach($table11_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$k1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$k2 += $row1['value'];
							}
							
						}
						
						//table12 data
						foreach($table12_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$l1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$l2 += $row1['value'];
							}
							
						}
						
						//table13 data
						foreach($table13_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$m1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$m2 += $row1['value'];
							}
							
						}
						
						//table14 data
						foreach($table14_data as $row1)
						{
							$paths = explode(",", $row1['path']);
							if($version == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$n1 += $row1['value'];
							}
							if($version_source == $paths[0] && $row['year'] == $paths[1] && $row['month'] == $paths[2])
							{
								$n2 += $row1['value'];
							}
							
						}
						
						
					}
					
				?>
				
				<table id="tb1" class="avtable_2">
					<tr >
						<td></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead">New Simulated</td>
						<td class="thead">Current Plan</td>
						<td class="thead">Variance</td>
						<td class="thead">New Margin %</td>
					</tr>
					<tr class="tmain">
						<td class="label">Sales Quantity</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo number_format($a1, 0, ".", ","); ?></td>
						<td><?php echo number_format($a2, 0, ".", ","); ?></td>
						<td><?php echo number_format( ($a1-$a2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					<tr >
						<td class="label">Sales Price</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($b1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($b2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($b1-$b2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					<tr >
						<td class="label">Gross Revenue</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($c1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($c2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($c1-$c2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					
					<tr >
						<td class="label">Discounts</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($d1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($d2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($d1-$d2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					
					<tr class="tmain">
						<td class="label">Net Revenue</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($e1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($e2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($e1-$e2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					
					<tr >
						<td class="label">Raw Material</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($f1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($f2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($f1-$f2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					
					<tr class="tmain">
						<td class="label">Product Margin</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($g1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($g2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($g1-$g2), 0, ".", ","); ?></td>
						<td>
							<?php
							$g3 = ($g1/$e1)*100;
							echo number_format($g3, 0, ".", ",")."%";
							?>
						</td>
					</tr>
					
					<tr >
						<td class="label">Proportional Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($h1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($h2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($h1-$h2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					
					<tr class="tmain">
						<td class="label">Contribution Margin</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($i1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($i2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($i1-$i2), 0, ".", ","); ?></td>
						<td>
							<?php
							$i3 = ($i1/$e1)*100;
							echo number_format($i3, 0, ".", ",")."%";
							?>
						</td>
					</tr>
					
					<tr >
						<td class="label">Fixed Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($j1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($j2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($j1-$j2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					
					<tr class="tmain">
						<td class="label">Gross Margin</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($k1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($k2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($k1-$k2), 0, ".", ","); ?></td>
						<td>
							<?php
							$k3 = ($k1/$e1)*100;
							echo number_format($k3, 0, ".", ",")."%";
							?>
						</td>
					</tr>
					
					<tr><td colspan="6">&nbsp;</td></tr>
					
					<tr class="tmain">
						<td class="label" colspan="6">Cost to Serve</td>
					</tr>
					
					<tr >
						<td class="label">Invoicing</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($l1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($l2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($l1-$l2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					
					<tr >
						<td class="label">Warranties</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($m1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($m2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($m1-$m2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					
					<tr >
						<td class="label">Distribution</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($n1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($n2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( ($n1-$n2), 0, ".", ","); ?></td>
						<td></td>
					</tr>
					
					<?php
						$o1 = $k1 - $l1 - $m1 - $n1;
						$o2 = $k2 - $l2 - $m2 - $n2;
						$o3 = ($k1-$k2) - ($l1-$l2) - ($m1-$m2) - ($n1-$n2);
						
						$o1 = round($o1);
						$o2 = round($o2);
						
						$chart1 = "<set label='Simulated' value='".$o1."' /><set label='Current' value='".$o2."' />";
					?>
					
					<tr class="tmain">
						<td class="label">Net Margin</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($o1, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($o2, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format( $o3, 0, ".", ","); ?></td>
						<td>
							<?php
							$o4 = ($o1/$e1)*100;
							echo number_format($o4, 0, ".", ",")."%";
							?>
						</td>
					</tr>
					
				</table>
				
				</form>
               
               <?php
                }
                ?>
            </div>
            
            
            
        </td>
    </tr>
    <tr>
        <td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td> 
    </tr>
</table>

<?php
	if(isset($_GET['trace']) && $_GET['trace'] == TRUE){
		echo "<pre>";
		echo $this->session->userdata('CurlRequest');
		$this->session->unset_userdata('CurlRequest');
		echo "</pre>";
	} else {
		$this->session->unset_userdata('CurlRequest');
	}
?>
<script type="text/javascript">

<?php
if($step == 9)
{
?>

var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_1", "600", "350", "0", "1");
myChart1.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
myChart1.render("chartContainer1");

<?php
}
?>

</script>
</body>
</html>