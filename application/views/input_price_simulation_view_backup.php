<?php
	error_reporting( E_ALL );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Input Price Simulation</title>
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

function gstat(pid, loc){
	
	var mydataset = {id: pid};
	
	$.ajax({
		url:"<?php echo site_url('input_price_simulation/gstatus'); ?>",
		type: "post",
	    data: mydataset,
		success:function(result){
		$(loc).html(result);
	}});
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

/*==================================================
  Cookie functions
  ==================================================*/
function setCookie(name, value, expires, path, domain, secure) {
    document.cookie= name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires.toGMTString() : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}

function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    } else {
        begin += 2;
    }
    var end = document.cookie.indexOf(";", begin);
    if (end == -1) {
        end = dc.length;
    }
    return unescape(dc.substring(begin + prefix.length, end));
}

function deleteCookie(name, path, domain) {
    if (getCookie(name)) {
        document.cookie = name + "=" +
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            "; expires=Thu, 01-Jan-70 00:00:01 GMT";
    }
}

function field_update( prefix, input_fld, value_tmp, sign )
{
	var l_field_pct = prefix + '_pct_' + input_fld;
	var l_field_val = prefix + '_val_' + input_fld;
	
	var l_value_pct = document.getElementById( l_field_pct ).value;
	var l_value_val = document.getElementById( l_field_val ).value;
	
	if( l_value_pct == "" ) { l_value_pct = 0; }
	if( l_value_val == "" ) { l_value_val = value_tmp; }
	
	if( sign == '+' )
	{
		l_value_pct++;
	} else {
		l_value_pct--;
	}
	
	if( prefix.substring( 0, 3 ) == 'pri')
	{
		l_value_val = Math.round( value_tmp * ( 100 + l_value_pct ) / 100 );
	} else {
		l_value_val = Math.round( value_tmp * ( 100 + l_value_pct ) ) / 100;
	}
		
	document.getElementById( l_field_pct ).value = l_value_pct;
	document.getElementById( l_field_val ).value = l_value_val;
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('input_price_simulation'); ?>">
                        <div class="filter_div">
							<select name="version" onchange="this.form.submit();" class="ddown1" title="Select Version">
<?php
foreach( $form_version as $row )
{
	$selected = "";
	if( $version == $row['element'] ) { $selected = " selected"; $version_name = $row['name_element']; }
	echo "<option value=\"".$row['element']."\"".$selected.">".$row['name_element']."</option>\r\n";
}
?>
							</select>
						</div>
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
foreach( $form_month as $row)
{
	$selected = "";
	if( $month == $row['element'] ) { $selected = " selected"; }
	echo "<option value=\"".$row['element']."\"".$selected.">".$row['name_element']."</option>\r\n";
}
?>
							</select>
                        </div>
					</form>
				</div>
			</div>
			</td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
<?php
$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Input Price Simulation</span>";
$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
?>
		<form id="form2" name="form2" method="post" action="<?php echo site_url('input_price_simulation'); ?>">
		<div class="tabber">
            <div class="tabbertab">
				<h3>Raw Material Prices</h3>
				<table class="avtable_2">
					<tr>
						<td  >&nbsp;</td>
						<td class="thead" ><?php echo $version_name; ?> Req. Qty</td>
						<td class="thead" ><?php echo $version_name; ?> Unit Price</td>
						<td class="thead" >Price Var. %</td>
						<td class="thead" >Simulation</td>
					</tr>
<?php
foreach( $raw_mat_data as $data )
{
	if( $data['quantity'] == 0 ) { continue; }
		//checks if value has been changed via post data and corrects it
		if($data['value'] != ${"raw_var_val_".$data['name_element']} && ${"raw_var_val_".$data['name_element']} != '')
		{
			$data['tempvalue'] = ${"raw_var_val_".$data['name_element']};
		} else {
			$data['tempvalue'] = $data['value'];
		}
	
	echo '<tr class="tmain">'."\r\n";
	echo '	<td class="label">'.$data['name_element'].' - '.$data['name'].'</td>'."\r\n";
	echo '	<td>'.$data['quantity'].' '.$data['uom'].'</td>'."\r\n";
	echo '	<td>$ '.number_format( $data['value'], 2, '.', ',' ).'</td>'."\r\n";
	echo '	<td class="center">'."\r\n";
	echo '		<img src="./assets/images/blue_up.png"   onclick="javascript:field_update( \'raw_var\',\''.$data['name_element'].'\',\''.$data['value'].'\',\'+\');" />'."\r\n";
	echo '		<img src="./assets/images/blue_down.png" onclick="javascript:field_update( \'raw_var\',\''.$data['name_element'].'\',\''.$data['value'].'\',\'-\');" />&nbsp;&nbsp;'."\r\n";
	echo '		<input type="text" id="raw_var_pct_'.$data['name_element'].'" name="raw_var_pct_'.$data['name_element'].'" size="1" class="text_right" value="'.${"raw_var_pct_".$data['name_element']}.'" />'."\r\n";
	echo '	</td>'."\r\n";
	echo '	<td class="center">'."\r\n";
	echo '		<input type="text" id="raw_var_val_'.$data['name_element'].'" name="raw_var_val_'.$data['name_element'].'" value="'.number_format( $data['tempvalue'], 2, '.', '' ).'" size="3" class="text_right"  />'."\r\n";
	echo '	</td>'."\r\n";
	echo '</tr>'."\r\n";
}
?>
				</table>
			</div>
			<div class="tabbertab">
				<h3>Primary Prices</h3>
				<table class="avtable_2">
					<tr>
						<td  >&nbsp;</td>
						<td class="thead" ><?php echo $version_name; ?> Quantity</td>
						<td class="thead" ><?php echo $version_name; ?> Unit Price</td>
						<td class="thead" >Price Var. (%)</td>
						<td class="thead" >Simulation</td>
					</tr>
<?php
foreach( $primary_data as $data )
{
	if( $data['quantity'] == 0 ) { continue; }
	//checks if value has been changed via post data and corrects it
		if($data['value'] != ${"pri_var_val_".$data['name_element']} && ${"pri_var_val_".$data['name_element']} != '')
		{
			$data['tempvalue'] = ${"pri_var_val_".$data['name_element']};
		} else {
			$data['tempvalue'] = $data['value'];
		}
	
	
	echo '<tr class="tmain">';
	echo '<td class="label">'.$data['name_element'].' - '.$data['name'].'</td>';
	echo '	<td>'.number_format( $data['quantity'], 0, '.', ',' ).' '.$data['uom'].'</td>'."\r\n";
	echo '	<td>$ '.number_format( $data['value'], 2, '.', ',' ).'</td>'."\r\n";
	echo '	<td class="center">'."\r\n";
	echo '		<img src="./assets/images/blue_up.png"   onclick="javascript:field_update( \'pri_var\',\''.$data['name_element'].'\',\''.$data['value'].'\',\'+\');" />'."\r\n";
	echo '		<img src="./assets/images/blue_down.png" onclick="javascript:field_update( \'pri_var\',\''.$data['name_element'].'\',\''.$data['value'].'\',\'-\');" />&nbsp;&nbsp;'."\r\n";
	echo '		<input type="text" id="pri_var_pct_'.$data['name_element'].'" name="pri_var_pct_'.$data['name_element'].'" size="1" class="text_right" value="'.${"pri_var_pct_".$data['name_element']}.'"/>'."\r\n";
	echo '	</td>'."\r\n";
	echo '	<td class="center">'."\r\n";
	echo '		<input type="text" id="pri_var_val_'.$data['name_element'].'" name="pri_var_val_'.$data['name_element'].'" value="'.number_format( $data['tempvalue'], 0, '.', '' ).'" size="3" class="text_right"  />'."\r\n";
	echo '	</td>'."\r\n";
	echo '</tr>'."\r\n";
}
?>
				</table>
            </div>
			<div class="tabbertab">
				<h3>Simulation</h3>
				<table class="avtable_2">
<?php

switch( $step )
{
	// STEP 1: VERSION
	case 1:
?>
					<tr>
						<td class="thead" colspan="2">Step 1 / 5: Version</th>
					</tr>
					<tr class="tmain">
						<td class="label">Reuse Simulation Version:</td>
						<td>
							<select name="version_sim_old" class="ddown1" title="Select Simulation Version">
								<option value="" selected>Select Version</option>
<?php
foreach( $form_version_sim as $row )
{
	$selected = "";
	if( $version == $row['element'] ) { $selected = " selected"; $version_name = $row['name_element']; }
	echo "<option value=\"".$row['element']."\"".$selected.">".$row['name_element']."</option>\r\n";
}
?>
							</select>
						</td>
					</tr>
					<tr class="tmain">
						<td class="label">Or create a new one:</td>
						<td>
							<input name="version_sim_new" type="text" id="version_sim_new" value="New Description">
						</td>
					</tr>
					<tr>
						<td class="center" colspan="2">
							<input name="Action"  type="submit" id="Action"  value="Select Version" class="obutton1" alt="Select Version" title="Select Version" />
						</td>
					</tr>
<?php
	break;
	
	// STEP 2: COPY 
	case 2:
?>
					<tr>
						<td class="thead" colspan="2">Step 2 / 5: Copy Base Data</th>
					</tr>
					<tr class="tmain">
						<td class="label">Version Source:</td>
						<td><?php echo $version_name; ?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Version Target:</td>
						<td><?php 
							// echo $form_version_sim[$version_sim]['element']." - ".$form_version_sim[$version_sim]['name_element']; //buggy
							foreach($form_version_sim as $row)
							{
								if($row['element'] == $version_sim)
								{
									echo $row['name_element'];
								}
							}
						?>
							<?php
								//tracers
								//$this->jedoxapi->traceme($version_sim, "version_sim");
								//$this->jedoxapi->traceme($form_version_sim, "form version sim");
							?>
						</td>
					</tr>
					<tr>
						<td class="center" colspan="2">
							<input name="Action"  type="submit" id="Action"  value="Copy Version Data" class="obutton1" alt="Copy Version Data" title="Copy Version Data" />
						</td>
					</tr>
<?php
	break;
	
	//show sone stuffs
	case 3:
?>		
					<tr>
						<td class="thead" colspan="3">Step 3 / 5: Copied Version Data</th>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td class="thead" >Primary Cost</th>
						<td class="thead" >Raw Materials Rate</th>
					</tr>
					<tr class="tmain">
						<td class="label"><?php echo $version_name; ?></td>
						<td>
							<?php
							foreach($table_data1 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?>
						</td>
						<td>
							<?php
							foreach($table_data2 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?>
						</td>
					</tr>
					<tr class="tmain">
						<td class="label"><?php 
							foreach($form_version_sim as $row)
							{
								if($row['element'] == $version_sim)
								{
									echo $row['name_element'];
								}
							}
						?></td>
						<td>
							<?php
							foreach($table_data1 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?>
						</td>
						<td>
							<?php
							foreach($table_data2 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?>
						</td>
					</tr>
					<tr>
						<td class="center" colspan="3">
							<input name="Action"  type="submit" id="Action"  value="Continue" class="obutton1" alt="Continue" title="Continue" />
						</td>
					</tr>
<?php
	break;
	
	// STEP 4: CALCULATE RATES FOR SECONDARY COSTS 
	case 4:
?>
					<tr>
						<td class="thead" colspan="4">Step 4 / 5: Calculate Rates for Secondary Costs </th>
					</tr>
					<tr>
						<td class="label" >Material Costs Changes</td>
						<td class="thead" ><?php echo $version_name; ?> Req. Qty</td>
						<td class="thead" ><?php echo $version_name; ?> Price</td>
						<td class="thead" ><?php 
							foreach($form_version_sim as $row)
							{
								if($row['element'] == $version_sim)
								{
									echo $row['name_element'];
								}
							}
						?> Price</td>
					</tr>
					<?php
		foreach( $raw_mat_data_change as $data )
		{
			if( $data['quantity'] == 0 ) { continue; }
			
			echo '<tr class="tmain">'."\r\n";
			echo '	<td class="label">'.$data['name_element'].' - '.$data['name'].'</td>'."\r\n";
			echo '	<td>'.$data['quantity'].' '.$data['uom'].'</td>'."\r\n";
			echo '	<td>$ '.number_format( $data['value'], 2, '.', ',' ).'</td>'."\r\n";
			echo '	<td>$ '.number_format( $data['value_change'], 2, '.', ',' ).'</td>'."\r\n";
			echo '</tr>'."\r\n";
		}
					?>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td class="label" >Primary Costs Changes</td>
						<td class="thead" ><?php echo $version_name; ?> Out. Qty</td>
						<td class="thead" ><?php echo $version_name; ?> Costs</td>
						<td class="thead" ><?php 
							foreach($form_version_sim as $row)
							{
								if($row['element'] == $version_sim)
								{
									echo $row['name_element'];
								}
							}
						?> Costs</td>
					</tr>
					<?php
		foreach( $primary_data_change as $data )
		{
			if( $data['quantity'] == 0 ) { continue; }
			
			echo '<tr class="tmain">'."\r\n";
			echo '	<td class="label">'.$data['name_element'].' - '.$data['name'].'</td>'."\r\n";
			echo '	<td>'.number_format( $data['quantity'], 0, '.', ',' ).' '.$data['uom'].'</td>'."\r\n";
			echo '	<td>$ '.number_format( $data['value'], 2, '.', ',' ).'</td>'."\r\n";
			echo '	<td>$ '.number_format( $data['value_change'], 2, '.', ',' ).'</td>'."\r\n";
			echo '</tr>'."\r\n";
		}
					?>
					<tr>
						<td class="center" colspan="3">
							<input name="Action"  type="submit" id="Action"  value="Continue" class="obutton1" alt="Continue" title="Continue" />
						</td>
					</tr>
<?php
	break;
	
	// STEP 5: CALCULATE RULES 
	case 5:
?>
					<tr>
						<td class="thead" colspan="2">Step 5 / 5: Calculate Rules</th>
					</tr>
					 <tr class="tmain">
						<td class="label">Version:</td>
						<td>
							<?php 
								echo $error; // show error if folder does not exist
								echo getcwd();
							?>
						</td>
					</tr>
					<tr>
						<td class="center" colspan="2">
							<input name="Action"  type="submit" id="Action"  value="Continue" class="obutton1" alt="Run ETL" title="Run ETL" />
						</td>
					</tr>
					<!--<tr>
						<td colspan="2"><iframe src="<?php echo base_url(); ?>assets/calculate_rates/index_input_price_simulation.php" style="margin: 10px 0; width: 600px; height: 600px" frameborder="0" seamless="seamless" scrolling="auto"></iframe></th>
					</tr>-->
<?php
	break;
	
	case 6:

?>
					<tr>
						<td class="thead" colspan="2">Step 6 / 6: Calculate ETL</th>
					</tr>
					 <tr class="tmain">
						<td class="label">
							<a href="#" onclick="gstat('<?php echo $id; ?>', '#etlstatus'); return false;" >Get Status</a>
							</td>
						<td>
							<div id="etlstatus">
								<?php 
								echo $return->status;
								?>
							</div>
							
						</td>
					</tr>
					

<?php
	break;
}
?>	
					<tr>
						<td class="center" colspan="2">
							<input name="year"        type="hidden" id="year"    value="<?php echo $year ?>" />
							<input name="month"       type="hidden" id="month"   value="<?php echo $month ?>" />
							<input name="version"     type="hidden" id="version" value="<?php echo $version ?>" />
							<input name="version_sim" type="hidden" id="version" value="<?php echo $version_sim ?>" />
							<input name="step"    type="hidden" id="step"    value="<?php echo $step + 1 ?>" />
						</td>
					</tr>
				</table>
			</div>
			<?php 
			if($step == 6)
			{
			?>
			<div class="tabbertab">
				<h3>Results</h3>
				<table class="avtable_2">
					<tr>
						<td class="thead" >&nbsp;</td>
						<td class="thead" ><?php echo $version_name; ?> Costs</td>
						<td class="thead" ><?php 
							foreach($form_version_sim as $row)
							{
								if($row['element'] == $version_sim)
								{
									echo $row['name_element'];
								}
							}
						?> Costs</td>
						<td class="thead" >Var. (%)</td>
					</tr>
			<?php
			$a = $b = $c = 0;
			foreach($product_base_fp_alias as $row)
			{
				foreach( $result_data as $row1 )
				{
					$path = explode(",", $row1['path']);
					if($version == $path[0] && $row['element'] == $path['4'])
					{
						$a = $row1['value'];
					}
					if($version_sim == $path[0] && $row['element'] == $path['4'])
					{
						$b = $row1['value'];
					}
				}
			?>	
				<tr>
					<td class="label" ><?php echo $row['name_element']; ?></td>
					<td  >$<?php echo number_format($a, 2, ".", ","); ?></td>
					<td  >$<?php echo number_format($b, 2, ".", ","); ?></td>
					<td  ><?php
						if($a != 0 && $b != 0)
						{
							$c = (($b - $a)/$a)*100; //variance
						}
						echo number_format($c, 2, ".", ",");
					?>%
					</td>
				</tr>
			<?php	
			}
			
			?>
				</table>
			</div>
			
			<?php	
			}
			?>
			
		</div>
		</form>
		</td>
    </tr>
    <tr>
        <td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
    </tr>
</table>

<?php
	//$this->jedoxapi->traceme($raw_mat_data_change, "raw mat data change");
	//$this->jedoxapi->traceme($result_data, "result data");
	//$this->jedoxapi->traceme($primary_data_change, "primary_data_change");
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