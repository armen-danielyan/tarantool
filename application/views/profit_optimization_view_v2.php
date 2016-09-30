<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Profit Optimization</title>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/FusionCharts.js"></script>
<link href='http://fonts.googleapis.com/css?family=Cuprum:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/smoothness/jquery-ui-1.9.1.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" media="screen" />

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
    
    $('.default-value').each(function() {
		var default_value = this.value;
		$(this).focus(function() {
			if(this.value == default_value) {
				this.value = '';
			}
		});
		$(this).blur(function() {
			if(this.value == '') {
				this.value = default_value;
			}
		});
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

function grun(version, loc){
	var myrunset = {version_sim_name: version};
	
	$.ajax({
		url:"<?php echo site_url('profit_optimization_v2/grun'); ?>",
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
		url:"<?php echo site_url('profit_optimization_v2/gstatus'); ?>",
		//type: "post",
	    //data: mydataset,
		success:function(result){
			$(loc).html(result);
			
			
			if(result == "Completed" || result == "Completed with Warnings")
			{
				document.getElementById("result_button").style.display = "";
				
			}
		}
	});
}

var tabberOptions = {

  'cookie':"altaviatabber", /* Name to use for the cookie */

  'onLoad': function(argsObj)
  {
    var t = argsObj.tabber;
    var i;

    /* Optional: Add the id of the tabber to the cookie name to allow
       for multiple tabber interfaces on the site.  If you have
       multiple tabber interfaces (even on different pages) I suggest
       setting a unique id on each one, to avoid having the cookie set
       the wrong tab.
    */
    if (t.id) {
      t.cookie = t.id + t.cookie;
    }

    /* If a cookie was previously set, restore the active tab */
    i = parseInt(getCookie(t.cookie));
    if (isNaN(i)) { return; }
    t.tabShow(i);
    //alert('getCookie(' + t.cookie + ') = ' + i);
  },

  'onClick':function(argsObj)
  {
    var c = argsObj.tabber.cookie;
    var i = argsObj.index;
    //alert('setCookie(' + c + ',' + i + ')');
    setCookie(c, i);
  }
};

function removeCommas(str) {
    while (str.search(",") >= 0) {
        str = (str + "").replace(',', '');
    }
    return str;
};

function getvariance(){
	var variance1 = document.getElementById("var1").value;
	var variance2 = document.getElementById("var2").value;
	//alert(variance1 +" "+ variance2)
	var v1 = parseInt( removeCommas( $("#tb1").find("tr").eq(1).find("td").eq(variance1).html() ) ) - parseInt( removeCommas( $("#tb1").find("tr").eq(1).find("td").eq(variance2).html() ) );
	$("#tb1").find("tr").eq(1).find("td:last").html(v1.toLocaleString());
	
	var v2 = parseFloat( removeCommas( $("#tb1").find("tr").eq(2).find("td").eq(variance1).html().substring(2) ) ).toFixed(2) - parseFloat( removeCommas( $("#tb1").find("tr").eq(2).find("td").eq(variance2).html().substring(2) ) ).toFixed(2);
	$("#tb1").find("tr").eq(2).find("td:last").html("<?php echo CUR_SIGN; ?> "+v2.toLocaleString());
	
	var v3 = parseInt( removeCommas( $("#tb1").find("tr").eq(3).find("td").eq(variance1).html().substring(2) ) ) - parseInt( removeCommas( $("#tb1").find("tr").eq(3).find("td").eq(variance2).html().substring(2) ) );
	$("#tb1").find("tr").eq(3).find("td:last").html("<?php echo CUR_SIGN; ?> "+v3.toLocaleString());
	
	var v4 = parseInt( removeCommas( $("#tb1").find("tr").eq(4).find("td").eq(variance1).html().substring(2) ) ) - parseInt( removeCommas( $("#tb1").find("tr").eq(4).find("td").eq(variance2).html().substring(2) ) );
	$("#tb1").find("tr").eq(4).find("td:last").html("<?php echo CUR_SIGN; ?> "+v4.toLocaleString());
	
	var v5 = parseInt( removeCommas( $("#tb1").find("tr").eq(5).find("td").eq(variance1).html().substring(2) ) ) - parseInt( removeCommas( $("#tb1").find("tr").eq(5).find("td").eq(variance2).html().substring(2) ) );
	$("#tb1").find("tr").eq(5).find("td:last").html("<?php echo CUR_SIGN; ?> "+v5.toLocaleString());
	
	var v6 = parseInt( removeCommas( $("#tb1").find("tr").eq(6).find("td").eq(variance1).html().substring(2) ) ) - parseInt( removeCommas( $("#tb1").find("tr").eq(6).find("td").eq(variance2).html().substring(2) ) );
	$("#tb1").find("tr").eq(6).find("td:last").html("<?php echo CUR_SIGN; ?> "+v6.toLocaleString());
	
	var v7 = (parseInt( removeCommas( $("#tb1").find("tr").eq(7).find("td").eq(variance1).html().substring(2) ) ) - parseInt( removeCommas( $("#tb1").find("tr").eq(7).find("td").eq(variance2).html().substring(2) ) ) ) *-1;
	$("#tb1").find("tr").eq(7).find("td:last").html("<?php echo CUR_SIGN; ?> "+v7.toLocaleString());
	
	var v8 = parseInt( removeCommas( $("#tb1").find("tr").eq(8).find("td").eq(variance1).html().substring(2) ) ) - parseInt( removeCommas( $("#tb1").find("tr").eq(8).find("td").eq(variance2).html().substring(2) ) );
	$("#tb1").find("tr").eq(8).find("td:last").html("<?php echo CUR_SIGN; ?> "+v8.toLocaleString());
	
	var v9 = (parseInt( removeCommas( $("#tb1").find("tr").eq(9).find("td").eq(variance1).html().substring(2) ) ) - parseInt( removeCommas( $("#tb1").find("tr").eq(9).find("td").eq(variance2).html().substring(2) ) ) ) *-1;
	$("#tb1").find("tr").eq(9).find("td:last").html("<?php echo CUR_SIGN; ?> "+v9.toLocaleString());
	
	var v10 = parseInt( removeCommas( $("#tb1").find("tr").eq(10).find("td").eq(variance1).html().substring(2) ) ) - parseInt( removeCommas( $("#tb1").find("tr").eq(10).find("td").eq(variance2).html().substring(2) ) );
	$("#tb1").find("tr").eq(10).find("td:last").html("<?php echo CUR_SIGN; ?> "+v10.toLocaleString());
	
	var v11 = (parseInt( removeCommas( $("#tb1").find("tr").eq(11).find("td").eq(variance1).html().substring(2) ) ) - parseInt( removeCommas( $("#tb1").find("tr").eq(11).find("td").eq(variance2).html().substring(2) ) ) ) *-1;
	$("#tb1").find("tr").eq(11).find("td:last").html("<?php echo CUR_SIGN; ?> "+v11.toLocaleString());
	
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
            <div id="filter_menu">
                <h3>Filters</h3>
				
                <div>
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('profit_optimization_v2'); ?>">
                        
                        <div class="filter_div">
                            <select name="year" onchange="this.form.submit();" class="ddown1" title="Select Year">
							<?php
							foreach( $form_year as $row)
							{
								$selected = "";
								if( $year == $row['element'] ) { $selected = " selected"; }
								echo "<option value=\"".$row['element']."\"".$selected.">".$row['name_element']."</option>\r\n";
							}
							?>
							</select>
                        </div>
                        <div class="filter_div">
                            <select name="month" onchange="this.form.submit();" class="ddown1" title="Select Month">
							<?php
							foreach( $form_months as $row)
							{
								$selected = "";
								$depth = '';
                                for($i=0; $i<$row['depth']; $i++)
                                {
                                    $depth .= '&nbsp;&nbsp;';
                                }
								if( $month == $row['element'] ) { $selected = " selected"; }
								echo "<option value=\"".$row['element']."\"".$selected.">".$depth.$row['name_element']."</option>\r\n";
							}
							?>
							</select>
                        </div>
                        
                        <div class="filter_div">
                            <select name="product" onchange="this.form.submit();" class="ddown1" title="Select Product">
							<?php
							foreach( $form_product as $row)
							{
								$selected = "";
								$depth = '';
                                for($i=0; $i<$row['depth']; $i++)
                                {
                                    $depth .= '&nbsp;&nbsp;';
                                }
								if( $product == $row['element'] ) { $selected = " selected"; }
								echo "<option value=\"".$row['element']."\"".$selected.">".$depth.$row['name_element']."</option>\r\n";
							}
							?>
							</select>
                        </div>
                        
                        <?php
                        	if($step == 3)
                        	{
                        ?>		
						<input type="hidden" name="step" value="<?php echo $step; ?>">
            				<input type="hidden" name="temp_version_set" value='<?php echo serialize($temp_version_set); ?>'>
						<?php		
                        	}
                        ?>
                        
					</form>
				</div>
			</div>
            
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Profit Optimization</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <?php
            if($step == 1){
            	// do/show only on step 1.
            
            ?>
            
            <div class="content_div">
            	<form id="form3" name="form3" method="post" action="<?php echo site_url('profit_optimization_v2'); ?>">
            		<table class="avtable_2">
	            		<tr>
	            			<td class="thead" colspan="2">Create New Version</td>
	            			
	            		</tr>
	            		<tr>
	            			<td>
	            				<input name="version_sim_new" type="text" id="version_sim_new" value="New Description">
	            			</td>
	            			<td>
	            				<input type="hidden" name="year" value="<?php echo $year; ?>">
            					<input type="hidden" name="product" value="<?php echo $product; ?>">
            					<input type="hidden" name="month" value="<?php echo $month; ?>">
	            				<input name="simulate" type="submit" id="bf" value="Create" class="obutton1" />
	            			</td>
	            		</tr>
            		</table>
            	</form>
            	
            </div>
            
            <div class="content_div">
            	<form id="form2" name="form2" method="post" action="<?php echo site_url('profit_optimization_v2'); ?>">
            	
            	<table class="avtable_2">
            		<tr>
            			<td class="thead" >Simulation Version (Select Multiple)</td>
            		</tr>
            		<tr >
            			
            			<td style="text-align: left !important;">
            				<?php
            					$cb = 0;
            					foreach($form_version as $row)
            					{
            						$cb += 1;
									$checked = '';
									if(${'ver'.$cb} == '1')
									{
										$checked = "checked";
									}
									
            				?>
            					<input type="checkbox" name="ver<?php echo $cb; ?>" id="verid_<?php echo $cb; ?>" value="1" <?php echo $checked; ?> > <?php echo $row['name_element'] ?> <br />
            					
            				<?php		
            					}
            				?>
            				
            			</td>
            		</tr>
            		<tr>
            			<td style="text-align: center !important;">
            				<input type="hidden" name="version" value="<?php echo $version; ?>">
            				<input type="hidden" name="year" value="<?php echo $year; ?>">
            				<input type="hidden" name="product" value="<?php echo $product; ?>">
            				<input type="hidden" name="month" value="<?php echo $month; ?>">
            				<input type="hidden" name="step" value="<?php echo $step; ?>">
            				<input name="simulate" type="submit" id="bf" value="Simulate" class="obutton1" />
            			</td>
            		</tr>
            	</table>
               
               </form>
               
               
               
            </div>
            
            
            
            <?php 
            } // end step 1
            ?>
            <?php
            if ($step == 2)
            {
            	// do/show only on step 2
            ?>
            <div class="content_div">
            	<form id="form3" name="form3" method="post" action="<?php echo site_url('profit_optimization_v2'); ?>">
				
				<table class="avtable_2">
					<tr>
						<td class="thead">Rates Calculation</td>
						<td class="thead" colspan="2">Status</td>
					</tr>
					<tr class="tmain">
						<td class="label">Raw Material Rates</td>
						<td class="label" colspan="2"><?php
						foreach($temp_version as $row)
						{
							$version_sim = $row['element'];
							$version_tname = $row['name_element'];
							echo $version_tname;
							echo "<br/>";
							echo "<iframe class='embed' src='../assets/calculate_rates/calc_raw_mat.php?version=".$version_sim."&year=".$year."&month=".$month."&verbose=0' style='height:40px;'></iframe>";
							echo "<br/>";
						}
							
						?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Secondary Rates</td>
						<td class="label" colspan="2"><?php 
						foreach($temp_version as $row)
						{
							$version_sim = $row['element'];
							$version_tname = $row['name_element'];
							echo $version_tname;
							echo "<br/>";
							echo "<iframe class='embed' src='../assets/calculate_rates/calc_rates.php?version=".$version_sim."&year=".$year."&month=".$month."&verbose=0' style='height:40px;'></iframe>";
							echo "<br/>";
						}
						
						?></td>
					</tr>
            		<tr class="tmain">
						<td class="label">ETL Rules</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<?php
							$versionpass = $this->session->userdata('temp_version_noalias');
							?>
							<a href="#" onclick="grun('[<?php echo $versionpass; ?>]', '#etlstatus'); return false;" id="r_etl" >Process Report</a>
							<a href="#" onclick="gstat('<?php //echo $id; ?>', '#etlstatus'); return false;" id="s_etl" style="display:none;" >Update Status</a>
							
						</td>
					</tr>
					<tr class="tmain">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							<input type="hidden" name="year" value="<?php echo $year; ?>">
            				<input type="hidden" name="month" value="<?php echo $month; ?>">
            				<input type="hidden" name="product" value="<?php echo $product; ?>">
            				<input type="hidden" name="step" value="<?php echo $step; ?>">
            				<input type="hidden" name="temp_version_set" value='<?php echo serialize($temp_version); ?>'>
            				<input name="Display Report" type="submit" id="dr" value="Display Report" class="obutton1" />
						</td>
					</tr>
            	</table>
            	</form>
            </div>
            <?php
            } // end step 2.
            ?>
            
            <?php
            if($step == 3){
            ?>
            <div class="tabber">
				<div class="tabbertab">
					<h3>Gross Margin by Product</h3>
					<table>
                    	<tr>
                    		<td><div id="chartContainer2" class="chart1"></div></td>
                    		
                    	</tr>
                    </table>
					
				</div>
				
			</div>
            
            <div class="content_div">
            	<form>
            	<?php
            	//$this->jedoxapi->traceme($temp_version_set);
				//$this->jedoxapi->traceme($table1_data);
            	?>
            	<table id="tb1" class="avtable_2">
					<tr>
						<td>&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<?php
							foreach($temp_version_set as $row){
						?>
						<td class="thead"><?php echo $row['name_element']; ?></td>
						<?php		
							}
						?>
						<?php
							if(count($temp_version_set) > 1)
							{
						?>		
						<td class="thead" style="text-align: left !important;">
								Variance
								<br />
								<select name="var1" id="var1" onchange="getvariance();"  >
								<?php
								$count = 2;
								foreach( $temp_version_set as $row)
								{
									echo "<option value='".$count."'>".$row['name_element']."</option>\r\n";
									$count += 1;
								}
								?>
								</select>
								<br />
								<select name="var2" id="var2" onchange="getvariance();" >
								<?php
								$count = 2;
								foreach( $temp_version_set as $row)
								{
									echo "<option value='".$count."'>".$row['name_element']."</option>\r\n";
									$count += 1;
								}
								?>
								</select>
								
						</td>	
						<?php
							}
						?>
            		</tr>
            		
            		<tr class="tmain">
						<td class="label">Sales Quantity</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
            			<?php
							foreach($temp_version_set as $row){
								foreach($table1_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo number_format($mrow['value'], 0, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							}
						?>
            		</tr>
            		
            		<tr style="display: none;">
						<td class="label">Sales Price</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<?php
							foreach($temp_version_set as $row){
								foreach($table2_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo CUR_SIGN." ".number_format($mrow['value'], 2, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							}
						?>
                    	
					</tr>
            		
            		<tr>
						<td class="label">Gross Revenue</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<?php
							foreach($temp_version_set as $row){
								foreach($table3_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo CUR_SIGN." ".number_format($mrow['value'], 0, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							}
						?>
					</tr>
            		
            		<tr>
						<td class="label">Discounts</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<?php
							foreach($temp_version_set as $row){
								foreach($table4_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo CUR_SIGN." ".number_format($mrow['value'], 0, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							}
						?>
					</tr>
            		
            		<tr class="tmain">
						<td class="label">Net Revenue</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<?php
							foreach($temp_version_set as $row){
								foreach($table5_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo CUR_SIGN." ".number_format($mrow['value'], 0, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							}
						?>
					</tr>
            		
            		<tr >
						<td class="label">Raw Material</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<?php
							foreach($temp_version_set as $row){
								foreach($table6_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo CUR_SIGN." ".number_format($mrow['value'], 0, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							}
						?>
					</tr>
            		
            		<tr class="tmain">
						<td class="label">Product Margin</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<?php
							foreach($temp_version_set as $row){
								foreach($table7_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo CUR_SIGN." ".number_format($mrow['value'], 0, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							}
						?>
					</tr>
            		
            		<tr >
						<td class="label">Proportional Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<?php
							foreach($temp_version_set as $row){
								foreach($table8_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo CUR_SIGN." ".number_format($mrow['value'], 0, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							}
						?>
					</tr>
            		
            		<tr class="tmain">
						<td class="label">Contribution Margin</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<?php
							foreach($temp_version_set as $row){
								foreach($table9_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo CUR_SIGN." ".number_format($mrow['value'], 0, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							} 
						?>
					</tr>
					
					<tr>
						<td class="label">Fixed Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<?php
							foreach($temp_version_set as $row){
								foreach($table10_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo CUR_SIGN." ".number_format($mrow['value'], 0, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							}
						?>
					</tr>
					
					<tr class="tmain1">
						<td class="label">Gross Margin</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<?php
							foreach($temp_version_set as $row){
								foreach($table11_data as $mrow){
									$paths = explode(",", $mrow['path']);
									if($row['element'] == $paths['0'])
									{
						?>
						<td><?php echo CUR_SIGN." ".number_format($mrow['value'], 0, ".", ","); ?></td>
						<?php
									}
								}
							}	
						?>
						<?php
							//variance
							if(count($temp_version_set) > 1)
							{
						?>
						<td>0</td>
						<?php
							}
						?>
					</tr>
            	</table>
            	</form>
            </div>
<script type="text/javascript">

var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/ScrollColumn2D.swf", "chartId_2", "600", "350", "0", "1");
myChart2.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2; ?>"+"</chart>");
myChart2.render("chartContainer2");

</script>
            <?php
			} // end step 3.
            ?>
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
<div id="dialog-message" title="Chart Pinned" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
		<span id="pnchart"></span> now pinned to your home.
	</p>
</div>
</body>
</html>