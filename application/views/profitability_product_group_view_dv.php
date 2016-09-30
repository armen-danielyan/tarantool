<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Profitability by Product Group</title>
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('profitability_product_group_dv'); ?>">
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
                            <select name="product" onchange="this.form.submit();" class="ddown1" title="Select Product">
                            <?php
                                foreach($form_product as $row)
                                {
                                    $depth = '';
                                    $depth_fix = $row['depth'] - 2; // the value to subtact here depends on the depth of the first element when filtered
                                    for($i=0; $i<$depth_fix; $i++)
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home')).
			" > <span class='orange'>By Product Group</span> > ".
			anchor('profitability_geography_dv', 'By Geography', array('title' => 'View Profitability by Geography'))." > ".
			anchor('efficiency_products_dv', 'Products', array('title' => 'View Efficiency Products'))." > ".
			anchor('efficiency_products_details_dv', 'Products Details', array('title' => 'View Efficiency Products Details'))." > ".
			anchor('efficiency_resources_dv', 'Resources', array('title' => 'View Efficiency Resources'))." > ".
			anchor('efficiency_resources_details_dv', 'Resources Details', array('title' => 'View Efficiency Resources Details'));
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
			<div class="tabber">
				<div class="tabbertab">
					<h3>Revenue and Cost</h3>
					<table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"></div></td>
                    		
                    	</tr>
                    </table>
					
				</div>
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
				<table id="tb1" class="avtable_2">
					<tr>
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
						<td class="thead"><?php echo $n_version1." ".$n_version2; ?> Variance</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td class="thead"><?php echo $n_version1; ?> Mar.%</td>
						<td class="thead"><?php echo $n_version2; ?> Mar.%</td>
						<td class="thead"><?php echo $n_version3; ?> Mar.%</td>
						
					</tr>
					
					<?php
						$arrow = '';
						// Sales Quantity
						$a1 = $b1 = $c1 = $d1 = $e1 = $f1 = $g1 = 0;
						foreach($table1_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a1 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b1 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c1 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d1 = $row['value'];
							//}
						}
						$d1 = $b1 - $a1;
					?>
					<tr class="tmain">
						<td class="label">Sales Quantity</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo number_format($a1, 0, ".", ","); ?></td>
						<td ><?php echo number_format($b1, 0, ".", ","); ?></td>
						<td ><?php echo number_format($c1, 0, ".", ","); ?></td>
						<td ><?php echo number_format($d1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td >&nbsp;</td>
						<td >&nbsp;</td>
                    	<td >&nbsp;</td>
					</tr>
					
					<?php
						// Sales Price
						$a2 = $b2 = $c2 = $d2 = $e2 = $f2 = $g2 = 0;
						foreach($table2_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a2 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b2 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c2 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d2 = $row['value'];
							//}
						}
						
						$d2 = $b2 - $a2;
					?>
					<tr>
						<td class="label">Sales Price</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a2, 2, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b2, 2, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c2, 2, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d2, 2, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td >&nbsp;</td>
						<td >&nbsp;</td>
                    	<td >&nbsp;</td>
					</tr>
					
					<?php
						// Gross Revenue
						$a3 = $b3 = $c3 = $d3 = $e3 = $f3 = $g3 = 0;
						foreach($table3_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a3 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b3 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c3 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d3 = $row['value'];
							//}
						}
						$d3 = $b3 - $a3;
						
					?>
					<tr>
						<td class="label">Gross Revenue</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a3, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b3, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c3, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d3, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td >&nbsp;</td>
						<td >&nbsp;</td>
                    	<td >&nbsp;</td>
					</tr>
					
					<?php
						// Discounts
						$a4 = $b4 = $c4 = $d4 = $e4 = $f4 = $g4 = 0;
						foreach($table4_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a4 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b4 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c4 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d4 = $row['value'];
							//}
						}
						
						$d4 = $b4 - $a4;
					?>
					<tr>
						<td class="label">Discounts</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a4, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b4, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c4, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d4, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td >&nbsp;</td>
						<td >&nbsp;</td>
                    	<td >&nbsp;</td>
					</tr>
					
					<?php
						// Net Revenue
						$a5 = $b5 = $c5 = $d5 = $e5 = $f5 = $g5 = 0;
						foreach($table5_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a5 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b5 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c5 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d5 = $row['value'];
							//}
						}
						
						$d5 = $b5 - $a5;
					?>
					<tr class="tmain">
						<td class="label">Net Revenue</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a5, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b5, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c5, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d5, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td >&nbsp;</td>
						<td >&nbsp;</td>
                    	<td >&nbsp;</td>
					</tr>
					
					<?php
						// Raw Material
						$arrow = '';
						$a6 = $b6 = $c6 = $d6 = $e6 = $f6 = $g6 = 0;
						foreach($table6_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a6 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b6 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c6 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d6 = $row['value'];
							//}
						}
						
						$d6 = $b6 - $a6;
						
						if($b6 != 0 && $d6 < 0)
						{
							$atest = abs($d6/$b6);
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
					<tr >
						<td class="label">Raw Material</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a6, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b6, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c6, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d6, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td >&nbsp;</td>
						<td >&nbsp;</td>
                    	<td >&nbsp;</td>
					</tr>
					
					<?php
						// Product Margin
						$arrow = '';
						$a7 = $b7 = $c7 = $d7 = $e7 = $f7 = $g7 = 0;
						foreach($table7_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a7 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b7 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c7 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d7 = $row['value'];
							//}
						}
						
						$d7 = $b7 - $a7;
						
						if($a5 != 0)
						{
							$e7 = ($a7/$a5)*100;
						}
						if($b5 != 0)
						{
							$f7 = ($b7/$b5)*100;
						}
						if($c5 != 0)
						{
							$g7 = ($c7/$c5)*100;
						}
						
						if($b7 != 0 && $d7 > 0)
						{
							$atest = abs($d7/$b7);
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
					<tr class="tmain">
						<td class="label">Product Margin</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a7, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b7, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c7, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d7, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td ><?php echo number_format($e7, 0, ".", ","); ?>%</td>
						<td ><?php echo number_format($f7, 0, ".", ","); ?>%</td>
                    	<td ><?php echo number_format($g7, 0, ".", ","); ?>%</td>
					</tr>
					
					<?php
						// Proportional Cost
						$arrow = '';
						$a8 = $b8 = $c8 = $d8 = $e8 = $f8 = $g8 = 0;
						foreach($table8_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a8 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b8 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c8 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d8 = $row['value'];
							//}
						}
						
						$d8 = $b8 - $a8;
						
						if($b8 != 0 && $d8 < 0)
						{
							$atest = abs($d8/$b8);
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
					<tr >
						<td class="label">Proportional Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a8, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b8, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c8, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d8, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td >&nbsp;</td>
						<td >&nbsp;</td>
                    	<td >&nbsp;</td>
					</tr>
					
					<?php
						// Contribution Margin
						$arrow = '';
						$a9 = $b9 = $c9 = $d9 = $e9 = $f9 = $g9 = 0;
						foreach($table9_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a9 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b9 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c9 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d9 = $row['value'];
							//}
						}
						
						$d9 = $b9 - $a9;
						
						if($a5 != 0)
						{
							$e9 = ($a9/$a5)*100;
						}
						if($b5 != 0)
						{
							$f9 = ($b9/$b5)*100;
						}
						if($c5 != 0)
						{
							$g9 = ($c9/$c5)*100;
						}
						
						if($b9 != 0 && $d9 > 0)
						{
							$atest = abs($d9/$b9);
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
					<tr class="tmain">
						<td class="label">Contribution Margin</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a9, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b9, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c9, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d9, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td ><?php echo number_format($e9, 0, ".", ","); ?>%</td>
						<td ><?php echo number_format($f9, 0, ".", ","); ?>%</td>
                    	<td ><?php echo number_format($g9, 0, ".", ","); ?>%</td>
					</tr>
					
					<?php
						// Fixed Cost
						$arrow = '';
						$a10 = $b10 = $c10 = $d10 = $e10 = $f10 = $g10 = 0;
						foreach($table10_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a10 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b10 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c10 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d10 = $row['value'];
							//}
						}
						
						$d10 = $b10 - $a10;
						
						if($b10 != 0 && $d10 < 0)
						{
							$atest = abs($d10/$b10);
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
					<tr>
						<td class="label">Fixed Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a10, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b10, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c10, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d10, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td >&nbsp;</td>
						<td >&nbsp;</td>
                    	<td >&nbsp;</td>
					</tr>
					
					<?php
						// Gross Margin
						$arrow = '';
						$a11 = $b11 = $c11 = $d11 = $e11 = $f11 = $g11 = 0;
						foreach($table11_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version1 == $paths['0'])
							{
								$a11 = $row['value'];
							}
							if($version2 == $paths['0'])
							{
								$b11 = $row['value'];
							}
							if($version3 == $paths['0'])
							{
								$c11 = $row['value'];
							}
							//if($version_AT == $paths['0'])
							//{
							//	$d11 = $row['value'];
							//}
						}
						
						$d11 = $b11 - $a11;
						
						if($a5 != 0)
						{
							$e11 = ($a11/$a5)*100;
						}
						if($b5 != 0)
						{
							$f11 = ($b11/$b5)*100;
						}
						if($c5 != 0)
						{
							$g11 = ($c11/$c5)*100;
						}
						
						if($b11 != 0 && $d11 > 0)
						{
							$atest = abs($d11/$b11);
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
					<tr class="tmain1">
						<td class="label">Gross Margin</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td ><?php echo CUR_SIGN." ".number_format($a11, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b11, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c11, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d11, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td ><?php echo number_format($e11, 0, ".", ","); ?>%</td>
						<td ><?php echo number_format($f11, 0, ".", ","); ?>%</td>
                    	<td ><?php echo number_format($g11, 0, ".", ","); ?>%</td>
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
myChart1.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='Month' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
myChart1.render("chartContainer1");

var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/ScrollColumn2D.swf", "chartId_2", "600", "350", "0", "1");
myChart2.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2; ?>"+"</chart>");
myChart2.render("chartContainer2");


</script>
<?php
	//echo "<pre>";
	//print_r($table4_data);
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