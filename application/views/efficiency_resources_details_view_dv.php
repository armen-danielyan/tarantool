<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Efficiency Resources Details</title>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/FusionCharts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/tablelizer/jquery.tabelizer.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Cuprum:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/smoothness/jquery-ui-1.9.1.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" media="screen" />
<link href="<?php echo base_url(); ?>assets/tablelizer/tabelizer.css" rel="stylesheet">

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
	
	var table1 = $('#tb1').tabelize({
	// OPTIONS HERE
	});
	
	//$('tr.tmain td').attr('title', 'Click to Expand/Collapse');
	//$('tr.tmain td.label').each(function (){
    //	var new_label = "<span class='ui-icon ui-icon-squaresmall-plus left'></span> "+$(this).html();
    //	$(this).html(new_label);
    //});
    
    //$('#tb1 tr.tmain').click( function() {
    //    var wcell = this.rowIndex;
    //    $("#tb1 tr:eq("+wcell+")").nextAll('tr').each( function() {
    //        if ($(this).hasClass('tmain') || $(this).hasClass('tmain1')) {
    //            return false;
    //        }
    //        $(this).toggle();
    //    });
    //});
    
    //$('#tb1 tr.tmain').each(function() {
    //    $(this).nextAll('tr').each( function() {
    //        if ($(this).hasClass('tmain') || $(this).hasClass('tmain1')) {
    //            return false;
    //        }
    //        $(this).toggle();
    //    });
    //});
	
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('efficiency_resources_details_dv'); ?>">
                        <div class="filter_div">
                            
                            <select name="year" onchange="this.form.submit();" class="ddown1" title="Select Year">
                            <?php
                                foreach($form_year as $row)
                                {
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($year == $row['element']){ $n_year = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        <div class="filter_div">
                            
                            <select name="month" onchange="this.form.submit();" class="ddown1" title="Select Month">
                            <?php
                                foreach($form_months as $row)
                                {
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($month == $row['element']){ $n_month = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        <div class="filter_div">
                            <select name="resource" onchange="this.form.submit();" class="ddown1" title="Select Resource">
                            <?php
                                foreach($form_resource as $row)
                                {
                                    $depth = '';
                                    $depth_fix = $row['depth'] - 1; // the value to subtact here depends on the depth of the first element when filtered
                                    for($i=0; $i<$depth_fix; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($resource == $row['element']){ $n_resource = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        <div class="filter_div">
                            <div class="left">
                                <a href="#" onclick="javascript:window.print();" title="Print This Page"><span class="ui-icon ui-icon-print left" ></span></a> 
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    
                </div>
			</div>
		</td>
		<td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
			<img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
		</td>
		<td class="tcontent" rowspan="2">
			<?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > ".
			anchor('profitability_product_group_dv', 'By Product Group', array('title' => 'View Profitability by Product Group'))." > ".
			anchor('profitability_geography_dv', 'By Geography', array('title' => 'View Profitability by Geography'))." > ".
			anchor('efficiency_products_dv', 'Products', array('title' => 'View Efficiency Products'))." > ".
			anchor('efficiency_products_details_dv', 'Products Details', array('title' => 'View Efficiency Products Details'))." > ".
			anchor('efficiency_resources_dv', 'Resources', array('title' => 'View Efficiency Resources')).
			" > <span class='orange'>Resources Details</span>";
			
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
			<div class="tabber">
				<div class="tabbertab">
					<h3>Total Cost</h3>
					<table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"></div></td>
                    		<td><div id="chartContainer2" class="chart1"></div></td>
                    	</tr>
                    </table>
					
				</div>
				<div class="tabbertab">
					<h3>Quantities Consumed</h3>
					<table>
                    	<tr>
                    		<td><div id="chartContainer3" class="chart1"></div></td>
                    	</tr>
                    </table>
					
				</div>
				<div class="tabbertab">
					<h3>Cost Recovery</h3>
					<table>
                    	<tr>
                    		<td><div id="chartContainer4" class="chart1"></div></td>
                    	</tr>
                    </table>
					
				</div>
			</div>
			
			<div class="content_div">	
				<table id="tb1" class="avtable_2 controller">
					<tr data-level="header" class="header">
						<td>&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td class="center" colspan="3">Total Costs</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td class="center" colspan="3">Fixed Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td class="center" colspan="3">Proportional Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td class="center" colspan="4">Consumption/Output</td>
					</tr>
					<tr data-level="header" class="header">
						<td>&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td class="thead">
                    		<select name="version1" onchange="this.form.submit();" class="" title="Select Version">
                            <?php
                                foreach($form_version as $row)
                                {
                                    $depth = '';
                                    //for($i=1; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;'; 
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($version1 == $row['element']){ $n_version1 = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                    	</td>
						<td class="thead">
							<select name="version2" onchange="this.form.submit();" class="" title="Select Version">
                            <?php
                                foreach($form_version as $row)
                                {
                                    $depth = '';
                                    //for($i=1; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;'; 
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($version2 == $row['element']){ $n_version2 = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
						</td>
						<td class="thead">
							<select name="version3" onchange="this.form.submit();" class="" title="Select Version">
                            <?php
                                foreach($form_version as $row)
                                {
                                    $depth = '';
                                    //for($i=1; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;'; 
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($version3 == $row['element']){ $n_version3 = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
						</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td class="thead"><?php echo $n_version1; ?></td>
						<td class="thead"><?php echo $n_version2; ?></td>
						<td class="thead"><?php echo $n_version3; ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td class="thead"><?php echo $n_version1; ?></td>
						<td class="thead"><?php echo $n_version2; ?></td>
						<td class="thead"><?php echo $n_version3; ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td class="thead"><?php echo $n_version1; ?></td>
						<td class="thead"><?php echo $n_version2; ?></td>
						<td class="thead"><?php echo $n_version3; ?></td>
						<td class="thead">UoM</td>
					</tr>
					<?php
						$depth_stack = array();
						//manual entries for preset stuffs
						$depth_stack[] = "1_1";
						$depth_stack[] = "1_2";
						$depth_stack[] = "1_3";
						// primary cost
						$a1 = $b1 = $c1 = $d1 = $e1 = $f1 = $g1 = $h1 = $i1 = $j1 = $k1 = $l1 = 0;
						$arrow = '';
						foreach($table1a_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$a1 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$b1 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$c1 = $row['value'];
							}
							
						}
						foreach($table1b_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$d1 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$e1 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$f1 = $row['value'];
							}
							
						}
						foreach($table1c_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$g1 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$h1 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$i1 = $row['value'];
							}
							
						}
						if($b1 != 0 && ($b1 - $a1) < 0)
						{
							$atest = abs( ( ($b1-$a1)/$b1 ) );
							if($atest > 0.05 && $atest < 0.1)
							{
								$arrow = "<img src='".base_url()."assets/images/amber_up.png' />";
							}
							if($atest >= 0.1)
							{
								$arrow = "<img src='".base_url()."assets/images/red_up.png' />";
							}
						}
					?>
					<tr class="tmain" data-level="1" id="level_1_1">
						<td class="label"><span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span> Primary Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td ><?php echo CUR_SIGN." ".number_format($d1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($e1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($f1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($g1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($h1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($i1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
					</tr>
					<?php
						//primary cost drilldown
						foreach($account_element_set_alias as $row)
						{
							$arrow = '';
							$a = $b = $c = $d = $e = $f = $g = $h = $i = $j = $k = $l = 0;
							$depth = '';
							$true_depth = 1; 
							$sub_depth = 1;
                            for($z=1; $z<$row['depth']; $z++)
                            {
                                $depth .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                                $true_depth += 1;
                            }
                            
                            if($row['number_children'] != 0)
							{
								$depth .= "<span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span> ";
							}
							else
							{
								$depth .= '&nbsp;&nbsp;&nbsp;&nbsp;';
							}	
							
							while(in_array($true_depth."_".$sub_depth, $depth_stack))
							{
								$sub_depth += 1;
							}
							
							$depth_stack[] = $true_depth."_".$sub_depth;
							
							foreach($table2a_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['4'])
								{
									$a = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['4'])
								{
									$b = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['4'])
								{
									$c = $drow1['value'];
								}
							}
							foreach($table2b_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['4'])
								{
									$d = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['4'])
								{
									$e = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['4'])
								{
									$f = $drow1['value'];
								}
							}
							foreach($table2c_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['4'])
								{
									$g = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['4'])
								{
									$h = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['4'])
								{
									$i = $drow1['value'];
								}
							}
							if($b != 0 && ($b - $a) < 0)
							{
								$atest = abs( ( ($b-$a)/$b ) );
								if($atest > 0.05 && $atest < 0.1)
								{
									$arrow = "<img src='".base_url()."assets/images/amber_up.png' />";
								}
								if($atest >= 0.1)
								{
									$arrow = "<img src='".base_url()."assets/images/red_up.png' />";
								}
							}
							
							if($a == 0 && $b == 0 && $c == 0 && $d == 0 && $e == 0 && $f == 0 && $g == 0 && $h == 0 && $i == 0 && $j == 0 && $k == 0 && $l == 0)
							{
								//do nothing
							} 
							else
							{
					?>		
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
						<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td ><?php echo CUR_SIGN." ".number_format($d, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($e, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($f, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($g, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($h, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($i, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
					</tr>
					<?php
							}
						}
						
					?>
					
					<?php
						// secondary cost
						$arrow = '';
						$a2 = $b2 = $c2 = $d2 = $e2 = $f2 = $g2 = $h2 = $i2 = $j2 = $k2 = $l2 = 0;
						foreach($table3a_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$a2 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$b2 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$c2 = $row['value'];
							}
						}
						foreach($table3b_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$d2 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$e2 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$f2 = $row['value'];
							}
						}
						foreach($table3c_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$g2 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$h2 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$i2 = $row['value'];
							}
						}
						foreach($table3d_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$j2 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$k2 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$l2 = $row['value'];
							}
						}
						
						if($b2 != 0 && ($b2 - $a2) < 0)
						{
							$atest = abs( ( ($b2-$a2)/$b2 ) );
							if($atest > 0.05 && $atest < 0.1)
							{
								$arrow = "<img src='".base_url()."assets/images/amber_up.png' />";
							}
							if($atest >= 0.1)
							{
								$arrow = "<img src='".base_url()."assets/images/red_up.png' />";
							}
						}
						
					?>
					<tr class="tmain" data-level="1" id="level_1_2">
						<td class="label"><span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span> Secondary Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a2, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b2, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c2, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td ><?php echo CUR_SIGN." ".number_format($d2, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($e2, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($f2, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($g2, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($h2, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($i2, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo number_format($j2, 0, ".", ","); ?></td>
						<td ><?php echo number_format($k2, 0, ".", ","); ?></td>
						<td ><?php echo number_format($l2, 0, ".", ","); ?></td>
						<td >&nbsp;</td>
					</tr>
					
					<?php
						// secondary cost dropdown
						foreach($sender_set_alias as $row)
						{
							$a = $b = $c = $d = $e = $f = $g = $h = $i = $j = $k = $l = 0;
							$depth = '';
							$true_depth = 1; 
							$sub_depth = 1;
							$uom = '';
							$arrow = '';
                            for($z=0; $z<$row['depth']; $z++)
                            {
                                $depth .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                                $true_depth += 1;
                            }
							
							if($row['number_children'] != 0)
							{
								$depth .= "<span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span> ";
							}
							else
							{
								$depth .= '&nbsp;&nbsp;&nbsp;&nbsp;';
							}	
							
							while(in_array($true_depth."_".$sub_depth, $depth_stack))
							{
								$sub_depth += 1;
							}
							
							$depth_stack[] = $true_depth."_".$sub_depth;
							
							foreach($table4a_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['5'])
								{
									$a = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['5'])
								{
									$b = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['5'])
								{
									$c = $drow1['value'];
								}
							}
							foreach($table4b_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['5'])
								{
									$d = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['5'])
								{
									$e = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['5'])
								{
									$f = $drow1['value'];
								}
							}
							foreach($table4c_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['5'])
								{
									$g = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['5'])
								{
									$h = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['5'])
								{
									$i = $drow1['value'];
								}
							}
							foreach($table4d_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['5'])
								{
									$j = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['5'])
								{
									$k = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['5'])
								{
									$l = $drow1['value'];
								}
							}
							
							foreach($cells_sender_attributes as $drow)
							{
								$paths = explode(",", $drow['path']);
								if($row['element'] == $paths[1])
								{
									$uom = $drow['value'];
								}
							}
							
							if($b != 0 && ($b - $a) < 0)
							{
								$atest = abs( ( ($b-$a)/$b ) );
								if($atest > 0.05 && $atest < 0.1)
								{
									$arrow = "<img src='".base_url()."assets/images/amber_up.png' />";
								}
								if($atest >= 0.1)
								{
									$arrow = "<img src='".base_url()."assets/images/red_up.png' />";
								}
							}
							
							if($a == 0 && $b == 0 && $c == 0 && $d == 0 && $e == 0 && $f == 0 && $g == 0 && $h == 0 && $i == 0 && $j == 0 && $k == 0 && $l == 0)
							{
								//do nothing
							} 
							else
							{
					?>	
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
						<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td ><?php echo CUR_SIGN." ".number_format($d, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($e, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($f, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($g, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($h, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($i, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo number_format($j, 0, ".", ","); ?></td>
						<td ><?php echo number_format($k, 0, ".", ","); ?></td>
						<td ><?php echo number_format($l, 0, ".", ","); ?></td>
						<td ><?php echo $uom; ?></td>
					</tr>
					<?php
							}
						}	
					?>
					
					<?php
						// Recovery
						$a3 = $b3 = $c3 = $d3 = $e3 = $f3 = $g3 = $h3 = $i3 = $j3 = $k3 = $l3 = 0;
						$arrow = '';
						foreach($table5a_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$a3 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$b3 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$c3 = $row['value'];
							}
						}
						foreach($table5b_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$d3 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$e3 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$f3 = $row['value'];
							}
						}
						foreach($table5c_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$g3 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$h3 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$i3 = $row['value'];
							}
						}
						foreach($table5d_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths[0])
							{
								$j3 = $row['value'];
							}
							if($version2 == $paths[0])
							{
								$k3 = $row['value'];
							}
							if($version3 == $paths[0])
							{
								$l3 = $row['value'];
							}
						}
						if($b3 != 0)
						{
							$atest = abs( ( ($b3-$a3)/$b3 ) );
							if($atest > 0.05 && $atest < 0.1)
							{
								$arrow = "<img src='".base_url()."assets/images/amber_down.png' />";
							}
							if($atest >= 0.1)
							{
								$arrow = "<img src='".base_url()."assets/images/red_down.png' />";
							}
						}
					?>
					<tr class="tmain" data-level="1" id="level_1_3">
						<td class="label"><span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span> Recovery</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a3*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b3*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c3*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td ><?php echo CUR_SIGN." ".number_format($d3*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($e3*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($f3*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($g3*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($h3*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($i3*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo number_format($j3*-1, 0, ".", ","); ?></td>
						<td ><?php echo number_format($k3*-1, 0, ".", ","); ?></td>
						<td ><?php echo number_format($l3*-1, 0, ".", ","); ?></td>
						<td >&nbsp;</td>
					</tr>
					
					<?php
						// recovery dropdown
						foreach($resource_set_alias as $row)
						{
							$a = $b = $c = $d = $e = $f = $g = $h = $i = $j = $k = $l = 0;
							$depth = '';
							$true_depth = 1;
							$sub_depth = 1;
							$uom = '';
							$arrow = '';
                            for($z=0; $z<$row['depth']; $z++)
                            {
                                $depth .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                                $true_depth += 1;
                            }
							
							if($row['number_children'] != 0)
							{
								$depth .= "<span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span> ";
							}
							else
							{
								$depth .= '&nbsp;&nbsp;&nbsp;&nbsp;';
							}	
							
							while(in_array($true_depth."_".$sub_depth, $depth_stack))
							{
								$sub_depth += 1;
							}
							
							$depth_stack[] = $true_depth."_".$sub_depth;
							
							foreach($table6a_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['6'])
								{
									$a = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['6'])
								{
									$b = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['6'])
								{
									$c = $drow1['value'];
								}
							}
							foreach($table6b_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['6'])
								{
									$d = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['6'])
								{
									$e = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['6'])
								{
									$f = $drow1['value'];
								}
							}
							foreach($table6c_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['6'])
								{
									$g = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['6'])
								{
									$h = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['6'])
								{
									$i = $drow1['value'];
								}
							}
							foreach($table6d_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version1 == $paths[0] && $row['element'] == $paths['6'])
								{
									$j = $drow1['value'];
								}
								if($version2 == $paths[0] && $row['element'] == $paths['6'])
								{
									$k = $drow1['value'];
								}
								if($version3 == $paths[0] && $row['element'] == $paths['6'])
								{
									$l = $drow1['value'];
								}
							}
							
							foreach($cells_resource_attributes as $drow)
							{
								$paths = explode(",", $drow['path']);
								if($row['element'] == $paths[1])
								{
									$uom = $drow['value'];
								}
							}
							
							if($b != 0)
							{
								$atest = abs( ( ($b-$a)/$b ) );
								if($atest > 0.05 && $atest < 0.1)
								{
									$arrow = "<img src='".base_url()."assets/images/amber_down.png' />";
								}
								if($atest >= 0.1)
								{
									$arrow = "<img src='".base_url()."assets/images/red_down.png' />";
								}
							}
							
							if($a == 0 && $b == 0 && $c == 0 && $d == 0 && $e == 0 && $f == 0 && $g == 0 && $h == 0 && $i == 0 && $j == 0 && $k == 0 && $l == 0)
							{
								//do nothing
							} 
							else
							{
					?>	
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
						<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td ><?php echo CUR_SIGN." ".number_format($d*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($e*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($f*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($g*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($h*-1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($i*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo number_format($j*-1, 0, ".", ","); ?></td>
						<td ><?php echo number_format($k*-1, 0, ".", ","); ?></td>
						<td ><?php echo number_format($l*-1, 0, ".", ","); ?></td>
						<td ><?php echo $uom; ?></td>
					</tr>
					<?php
							}
						}	
					?>
					
					<tr class="tmain1 header" data-level="1" >
						<td class="label">Under/Over Recovery</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a1+$a2-$a3, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b1+$b2-$b3, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c1+$c2-$c3, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td ><?php echo CUR_SIGN." ".number_format($d1+$d2-$d3, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($e1+$e2-$e3, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($f1+$f2-$f3, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($g1+$g2-$g3, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($h1+$h2-$h3, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($i1+$i2-$i3, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
					</tr>
					
					
					
				</table>
				</form>
			</div>
			
		</td>
	</tr>
	<tr>
		<td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
	</tr>
</table>
<script type="text/javascript">

var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_1", "600", "350", "0", "1");
myChart1.setXMLData("<chart caption='Primary Cost' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='Month' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
myChart1.render("chartContainer1");

var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_2", "600", "350", "0", "1");
myChart2.setXMLData("<chart caption='Secondary Cost' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='Month' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2; ?>"+"</chart>");
myChart2.render("chartContainer2");

var myChart3 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_3", "600", "350", "0", "1");
myChart3.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='Month' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart3; ?>"+"</chart>");
myChart3.render("chartContainer3");

var myChart4 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_4", "600", "350", "0", "1");
myChart4.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='Month' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart4; ?>"+"</chart>");
myChart4.render("chartContainer4");
    
</script>
<?php
	//echo "<pre>";
	//print_r($table5a_data);
	//print_r($table1a_data);
	//echo "</pre>";
	if(isset($_GET['trace']) && $_GET['trace'] == TRUE){
		echo "<pre>";
		echo $this->session->userdata('CurlRequest');
		$this->session->unset_userdata('CurlRequest');
		echo "</pre>";
	} else {
		$this->session->unset_userdata('CurlRequest');
	}
?>

</body>
</html>