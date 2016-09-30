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

function grun(version, loc){
	var myrunset = {version_sim_name: version};
	
	$.ajax({
		url:"<?php echo site_url('input_price_simulation/grun'); ?>",
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
		url:"<?php echo site_url('input_price_simulation/gstatus'); ?>",
		//type: "post",
	    //data: mydataset,
		success:function(result){
			$(loc).html(result);
			
			if(result == "Completed" || result == "Completed with Warnings" || result == "Completed successfully")
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
    if($("#thistab").length == 0) {
		t.tabShow(i);
	} else {
		i += 1;
		t.tabShow(i);
	}
    
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
	
	l_value_val = value_tmp * ( 100 + l_value_pct ) / 100;
	l_value_val = l_value_val.toFixed(2);
		
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
		<div class="tabber" id="mytab1" name="mytab1">
            <div class="tabbertab">
				<h3>Raw Material Prices</h3>
				<table class="avtable_2">
					<tr>
						<td  >&nbsp;</td>
						<td class="thead" ><?php echo $version_name; ?> Quantity</td>
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
	echo '	<td>'.CUR_SIGN.' '.number_format( $data['value'], 2, '.', ',' ).'</td>'."\r\n";
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
	echo '	<td>'.CUR_SIGN.' '.number_format( $data['value'], 2, '.', ',' ).'</td>'."\r\n";
	echo '	<td class="center">'."\r\n";
	echo '		<img src="./assets/images/blue_up.png"   onclick="javascript:field_update( \'pri_var\',\''.$data['name_element'].'\',\''.$data['value'].'\',\'+\');" />'."\r\n";
	echo '		<img src="./assets/images/blue_down.png" onclick="javascript:field_update( \'pri_var\',\''.$data['name_element'].'\',\''.$data['value'].'\',\'-\');" />&nbsp;&nbsp;'."\r\n";
	echo '		<input type="text" id="pri_var_pct_'.$data['name_element'].'" name="pri_var_pct_'.$data['name_element'].'" size="1" class="text_right" value="'.${"pri_var_pct_".$data['name_element']}.'"/>'."\r\n";
	echo '	</td>'."\r\n";
	echo '	<td class="center">'."\r\n";
	echo '		<input type="text" id="pri_var_val_'.$data['name_element'].'" name="pri_var_val_'.$data['name_element'].'" value="'.number_format( $data['tempvalue'], 2, '.', '' ).'" size="3" class="text_right"  />'."\r\n";
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
						<td class="thead" colspan="2">Simulation Version</td>
					</tr>
					<tr class="tmain">
						<td class="label">Reuse Simulation Version</td>
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
						<td class="label">Or Create a New Version</td>
						<td>
							<input name="version_sim_new" type="text" id="version_sim_new" value="New Description">
						</td>
					</tr>
					<tr>
						<td class="center" colspan="2">
							<input name="Action"  type="submit" id="Action"  value="Perform Simulation" class="obutton1" alt="Perform Simulation" title="Perform Simulation" />
						</td>
					</tr>
<?php
	break;
	
	// STEP 2: COPY 
	case 2:
	 
		foreach($form_version_sim as $row)
		{
			if($row['element'] == $version_sim)
			{
				$version_sim_name = $row['name_element'];
			}
		}
?>
					<tr>
						<td class="label" colspan="3"></th>
					</tr>
					<tr>
						<td class="thead" >Base Data (Copied)</td>
						<td class="thead" width="150"><?php echo $version_name; ?></td>
						<td class="thead" width="150"><?php echo $version_sim_name; ?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Raw Materials Prices</td>
						<td><?php
							foreach($table_data2 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
						<td><?php
							foreach($table_data2 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Bom Quantities</td>
						<td><?php
							foreach($table_data5 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
						<td><?php
							foreach($table_data5 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Production Lot Sizes</td>
						<td><?php
							foreach($table_data6 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
						<td><?php
							foreach($table_data6 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Production Volumes</td>
						<td><?php
							foreach($table_data8 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
						<td><?php
							foreach($table_data8 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Secondary Production Quantities</td>
						<td><?php
							foreach($table_data7 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
						<td><?php
							foreach($table_data7 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Primary Quantities</td>
						<td><?php
							foreach($table_data1 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
						<td><?php
							foreach($table_data1 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Primary Prices</td>
						<td><?php
							foreach($table_data3 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
						<td><?php
							foreach($table_data3 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Secondary Consumption Quantities</td>
						<td><?php
							foreach($table_data4 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
						<td><?php
							foreach($table_data4 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
					</tr>
					<tr>
						<td class="label" colspan="3"><br/></th>
					</tr>
					<tr>
						<td class="thead" >Raw Material Prices (Changes)</td>
						<td class="thead" ><?php echo $version_name; ?> Unit Price</td>
						<td class="thead" ><?php echo $version_sim_name; ?> Unit Price</td>
					</tr>
					<?php
		foreach( $raw_mat_data_change as $data )
		{
			if( $data['quantity'] == 0 ) { continue; }
			
			echo '<tr class="tmain">'."\r\n";
			echo '	<td class="label">'.$data['name_element'].' - '.$data['name'].'</td>'."\r\n";
			echo '	<td>'.CUR_SIGN.' '.number_format( $data['value'], 2, '.', ',' ).'</td>'."\r\n";
			echo '	<td>'.CUR_SIGN.' '.number_format( $data['value_change'], 2, '.', ',' ).'</td>'."\r\n";
			echo '</tr>'."\r\n";
		}
		
		if( count( $raw_mat_data_change ) == 0 )
		{
					?><tr class="tmain">
						<td class="label" colspan="3">No changes were made</td>
					</tr><?php
		}
					?><tr><td colspan="3"><br/></td></tr>
					<tr>
						<td class="thead" >Primary Price (Changes)</td>
						<td class="thead" ><?php echo $version_name; ?> Unit Price</td>
						<td class="thead" ><?php echo $version_sim_name; ?> Unit Price</td>
					</tr>
					<?php
		foreach( $primary_data_change as $data )
		{
			if( $data['quantity'] == 0 ) { continue; }
			
			echo '<tr class="tmain">'."\r\n";
			echo '	<td class="label">'.$data['name_element'].' - '.$data['name'].'</td>'."\r\n";
			echo '	<td>'.CUR_SIGN.' '.number_format( $data['value'], 2, '.', ',' ).'</td>'."\r\n";
			echo '	<td>'.CUR_SIGN.' '.number_format( $data['value_change'], 2, '.', ',' ).'</td>'."\r\n";
			echo '</tr>'."\r\n";
		}

		if( count(  $primary_data_change ) == 0 )
		{
					?><tr class="tmain">
						<td class="label" colspan="3">No changes were made</td>
					</tr><?php
		}
					?><tr><td colspan="3"><br/></td></tr>
					<tr>
						<td class="thead">Rates Calculation</td>
						<td class="thead" colspan="2">Status</td>
					</tr>
					<tr class="tmain">
						<td class="label">Raw Material Rates</td>
						<td class="label" colspan="2"><?php
						if( $error != "" )
						{
							echo $error;
						} else {
							echo "Successful";
						}
						
						//	echo "<iframe class=\"embed\" src=\"../assets/calculate_rates/calc_raw_mat.php?version=".$version_sim."&year=".$year."&month=".$month."&verbose=0\"></iframe></td>\r\n";
						?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Secondary Rates</td>
						<td class="label" colspan="2"><?php 
						if( $error != "" )
						{
							echo $error;
						} else {
							echo "Successful";
						}
						
			//				echo "<iframe class=\"embed\" src=\"../assets/calculate_rates/calc_rates.php?version=".$version_sim."&year=".$year."&month=".$month."&verbose=0&calc_only=1\"></iframe></td>\r\n";
						?></td>
					</tr>
					<tr class="tmain">
						<td class="label">ETL Rules</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a href="#" onclick="grun('<?php echo $version_sim_name1; ?>', '#etlstatus'); return false;" id="r_etl" >Run ETL</a>
							<a href="#" onclick="gstat('<?php //echo $id; ?>', '#etlstatus'); return false;" id="s_etl" style="display:none;" >Update Status</a>
						</td>
					</tr>
					<tr id="result_button" style="display: none;" > <!--   -->
						<td class="center" colspan="3"><input name="Action"  type="submit" id="Action"  value="Show Results" class="obutton1" alt="Show Results" title="Show Results" /></td>
					</tr><?php
	break;
	
	case 3:
		foreach($form_version_sim as $row)
		{
			if($row['element'] == $version_sim)
			{
				$version_sim_name = $row['name_element'];
			}
		}
	?>	
				<tr><td class="label" colspan="3"></td></tr>
				<tr>
					<td class="thead" >Base Data (Copied)</td>
					<td class="thead" width="150"><?php echo $version_name; ?></td>
					<td class="thead" width="150"><?php echo $version_sim_name; ?></td>
				</tr>
				<tr class="tmain">
					<td class="label">Raw Materials Prices</td>
					<td><?php
		foreach($table_data2 as $row)
		{
			$path = explode(",", $row['path']);
			if($path[0] == $version)
			{
				echo number_format($row['value'], 2, ".", ",");
			}
		}
?></td>
						<td><?php
							foreach($table_data2 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Primary Prices</td>
						<td><?php
							foreach($table_data1 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
						<td><?php
							foreach($table_data1 as $row)
							{
								$path = explode(",", $row['path']);
								if($path[0] == $version_sim)
								{
									echo number_format($row['value'], 2, ".", ",");
								}
							}
							?></td>
					</tr>
					<tr>
						<td class="label" colspan="3"><br/></th>
					</tr>
					<tr>
						<td class="thead" >Raw Material Prices (Changes)</td>
						<td class="thead" ><?php echo $version_name; ?> Unit Price</td>
						<td class="thead" ><?php echo $version_sim_name; ?> Unit Price</td>
					</tr>
					<?php
		foreach( $raw_mat_data_change as $data )
		{
			if( $data['quantity'] == 0 ) { continue; }
			
			echo '<tr class="tmain">'."\r\n";
			echo '	<td class="label">'.$data['name_element'].' - '.$data['name'].'</td>'."\r\n";
			echo '	<td>'.CUR_SIGN.' '.number_format( $data['value'], 2, '.', ',' ).'</td>'."\r\n";
			echo '	<td>'.CUR_SIGN.' '.number_format( $data['value_change'], 2, '.', ',' ).'</td>'."\r\n";
			echo '</tr>'."\r\n";
		}
		
		if( count( $raw_mat_data_change ) == 0 )
		{
					?><tr class="tmain">
						<td class="label" colspan="3">No changes were made</td>
					</tr><?php
		}
					?><tr><td colspan="3"><br/></td></tr>
					<tr>
						<td class="thead" >Primary Price (Changes)</td>
						<td class="thead" ><?php echo $version_name; ?> Unit Price</td>
						<td class="thead" ><?php echo $version_sim_name; ?> Unit Price</td>
					</tr>
					<?php
		foreach( $primary_data_change as $data )
		{
			if( $data['quantity'] == 0 ) { continue; }
			
			echo '<tr class="tmain">'."\r\n";
			echo '	<td class="label">'.$data['name_element'].' - '.$data['name'].'</td>'."\r\n";
			echo '	<td>'.CUR_SIGN.' '.number_format( $data['value'], 2, '.', ',' ).'</td>'."\r\n";
			echo '	<td>'.CUR_SIGN.' '.number_format( $data['value_change'], 2, '.', ',' ).'</td>'."\r\n";
			echo '</tr>'."\r\n";
		}

		if( count(  $primary_data_change ) == 0 )
		{
					?><tr class="tmain">
						<td class="label" colspan="3">No changes were made</td>
					</tr><?php
		}
					?><tr><td colspan="3"><br/></td></tr>
					<tr>
						<td class="thead">Rates Calculation</td>
						<td class="thead" colspan="2">Status</td>
					</tr>
					<tr class="tmain">
						<td class="label">Raw Material Rates</td>
						<td class="label" colspan="2">Successful <img src="../assets/calculate_rates/images/calc_green.png" width="16" height="16" /></td>
					</tr>
					<tr class="tmain">
						<td class="label">Secondary Rates</td>
						<td class="label" colspan="2">Successful <img src="../assets/calculate_rates/images/calc_green.png" width="16" height="16" /><br/><?php echo $error;?></td>
					</tr>
<?php		
	break;
	
}
?>					<tr>
						<td class="center" colspan="2">
							<input name="year"        type="hidden" id="year"    value="<?php echo $year ?>" />
							<input name="month"       type="hidden" id="month"   value="<?php echo $month ?>" />
							<input name="version"     type="hidden" id="version" value="<?php echo $version ?>" />
							<input name="version_sim" type="hidden" id="version" value="<?php echo $version_sim ?>" />
							<input name="step"        type="hidden" id="step"    value="<?php echo $step + 1 ?>" />
						</td>
					</tr>
					
				</table>
			</div>
<?php 
if( $step == 3 )
{
?>
			<div class="tabbertab" id="thistab">
				
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
					<td  ><?php echo CUR_SIGN." ".number_format($a, 2, ".", ","); ?></td>
					<td  ><?php echo CUR_SIGN." ".number_format($b, 2, ".", ","); ?></td>
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