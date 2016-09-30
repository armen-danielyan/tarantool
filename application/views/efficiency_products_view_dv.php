<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Efficiency Products</title>
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('efficiency_products_dv'); ?>">
                        
                        
                        
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
                                <option value="<?php echo $row['element']; ?>" <?php if($month == $row['element']){ $n_year = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        
                        <div class="filter_div">
                            <div class="left">
                                <a href="#" onclick="javascript:window.print();" title="Print This Page"><span class="ui-icon ui-icon-print left" ></span></a>
                            </div>
                            <!-- 
                            <div class="left">
                                <?php echo mailto("?to=&subject=&body=".site_url('efficiency_operations_v2')."/info/".$n_year."/".url_title($n_month, '_')."/".url_title($n_day, '_')."/".url_title($n_shift, '_')."/".url_title($n_version, '_')."/".url_title($n_receiver, '_')."", "<span class='ui-icon ui-icon-mail-closed left' ></span>", array("title" =>"Share this page via email")); ?>
                            </div>
                            -->
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
			anchor('profitability_geography_dv', 'By Geography', array('title' => 'View Profitability by Geography')).
			" > <span class='orange'>Products</span> > ".
			anchor('efficiency_products_details_dv', 'Products Details', array('title' => 'View Efficiency Products Details'))." > ".
			anchor('efficiency_resources_dv', 'Resources', array('title' => 'View Efficiency Resources'))." > ".
			anchor('efficiency_resources_details_dv', 'Resources Details', array('title' => 'View Efficiency Resources Details'));
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
                <?php 
                if($this->jedox->page_permission($jedox_user_details['group_names'], "efficiency_products"))
                {
                    //echo anchor("efficiency_products_details_v2", "<span class='ui-icon ui-icon-search right' ></span>", array('title' => 'View Efficiency Products Details')); 
                }
                ?>
            </div>
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Production Cost</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" ></div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Production Qty</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer2" ></div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Unit Cost</h3>
                    <table>
                    	<tr>
                    		<td>
                    			<div class="filter_div">
		                            <select name="product" onchange="this.form.submit();" class="ddown1" title="Select Product">
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
                    	</tr>
                    	<tr>
                    		
                    		<td><div id="chartContainer3" ></div></td>
                    	</tr>
                    </table>
                    
                </div>
                
            </div>
            
            <div class="content_div" >
                <table id="tb1" class="avtable_2 controller">
                	<tr data-level="header" class="header">
						<td>&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td class="center" colspan="4">Total Costs/Product</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td class="center" colspan="4">Production Quantity</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td class="center" colspan="4">Unit Cost</td>
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
						<td class="thead"><?php echo $n_version1." ".$n_version2; ?> Variance</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td class="thead"><?php echo $n_version1; ?></td>
						<td class="thead"><?php echo $n_version2; ?></td>
						<td class="thead"><?php echo $n_version3; ?></td>
						<td class="thead"><?php echo $n_version1." ".$n_version3; ?> Variance</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
                    	<td class="thead"><?php echo $n_version1; ?></td>
						<td class="thead"><?php echo $n_version2; ?></td>
						<td class="thead"><?php echo $n_version3; ?></td>
						<td class="thead"><?php echo $n_version1." ".$n_version2; ?> Variance</td>
					</tr>
					<?php
						$depth_stack = array();
						
						foreach ($product_set_alias as $row)
						{
							//arrow indicators
							$arrow1 = '';
							$arrow2 = '';
							$depth = '';
							$true_depth = 0; 
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
							
							$a = $b = $c = $d = $e = $f = $g = $h = $i = $j = $k = $l = 0;
							
							
							foreach($table1a_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($paths[0] == $version1 && $paths[4] == $row['element'])
								{
									$a = $drow1['value'];
								}
								if($paths[0] == $version2 && $paths[4] == $row['element'])
								{
									$b = $drow1['value'];
								}
								if($paths[0] == $version3 && $paths[4] == $row['element'])
								{
									$c = $drow1['value'];
								}
								//if($paths[0] == $version_AT && $paths[4] == $row['element'])
								//{
								//	$d = $drow1['value'];
								//}
							}
							
							$d = $b - $a;
							
							foreach($table1b_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($paths[0] == $version1 && $paths[4] == $row['element'])
								{
									$e = $drow1['value'];
								}
								if($paths[0] == $version2 && $paths[4] == $row['element'])
								{
									$f = $drow1['value'];
								}
								if($paths[0] == $version3 && $paths[4] == $row['element'])
								{
									$g = $drow1['value'];
								}
								//if($paths[0] == $version_AP && $paths[4] == $row['element'])
								//{
								//	$h = $drow1['value'];
								//}
								
							}
							
							$h = $e - $g;
							
							foreach($table1c_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($paths[0] == $version1 && $paths[4] == $row['element'])
								{
									$i = $drow1['value'];
								}
								if($paths[0] == $version2 && $paths[4] == $row['element'])
								{
									$j = $drow1['value'];
								}
								if($paths[0] == $version3 && $paths[4] == $row['element'])
								{
									$k = $drow1['value'];
								}
								//if($paths[0] == $version_AT && $paths[4] == $row['element'])
								//{
								//	$l = $drow1['value'];
								//}
							}
							
							$l = $j - $i;
							
							if($b != 0 && $d < 0)
							{
								$atest = abs(($d/$b));
								if($atest > 0.05 && $atest < 0.1)
								{
									$arrow1 = "<img src='".base_url()."assets/images/amber_up.png' />";
								}
								if($atest >= 0.1)
								{
									$arrow1 = "<img src='".base_url()."assets/images/red_up.png' />";
								}
							}
							
							if($g != 0 )
							{
								$btest = ($h/$g)*-1;
								if ($btest < 0)
								{
									$btest = abs($btest);
									if($btest > 0.05 && $btest < 0.1)
									{
										$arrow2 = "<img src='".base_url()."assets/images/amber_down.png' />";
									}
									if($btest >= 0.1)
									{
										$arrow2 = "<img src='".base_url()."assets/images/red_down.png' />";
									}
								}
								
							}
							
							// remove 0 lines
							if($a == 0 && $b == 0 && $c == 0 && $d == 0 && $e == 0 && $f == 0 && $g == 0 && $h == 0 && $i == 0 && $j == 0 && $k == 0 && $l == 0)
							{
								//do nothing
							} 
							else
							{
					?>
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>" >
						<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td><?php echo CUR_SIGN." ".number_format($a, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($b, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($c, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($d, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td><?php echo number_format($e, 0, ".", ","); ?></td>
						<td><?php echo number_format($f, 0, ".", ","); ?></td>
						<td><?php echo number_format($g, 0, ".", ","); ?></td>
						<td><?php echo number_format($h*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td><?php echo CUR_SIGN." ".number_format($i, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($j, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($k, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($l, 0, ".", ","); ?></td>
					</tr>
					<?php	
							}
					
					
						}
						$a = $b = $c = $d = 0; // reset value for total
						foreach($table2_data as $drow1)
						{
							$paths = explode(",", $drow1['path']);
							if($paths[0] == $version1 )
							{
								$a = $drow1['value'];
							}
							if($paths[0] == $version2 )
							{
								$b = $drow1['value'];
							}
							if($paths[0] == $version3 )
							{
								$c = $drow1['value'];
							}
							//if($paths[0] == $version_AT )
							//{
							//	$d = $drow1['value'];
							//}
						}
						
						$d = $b - $a;
						
					?>
					<tr class="tmain1 header" data-level="1" >
						<td class="label">Total</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td><?php echo CUR_SIGN." ".number_format($a, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($b, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($c, 0, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($d, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
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

var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_2", "600", "350", "0", "1");
myChart2.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='Month' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2; ?>"+"</chart>");
myChart2.render("chartContainer2");

var myChart3 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/ScrollColumn2D.swf", "chartId_3", "600", "350", "0", "1");
myChart3.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart3; ?>"+"</chart>");
myChart3.render("chartContainer3");
    
</script>
<?php
	//echo "<pre>";
	//print_r($cells_receiver_attributes);
	//print_r($receiver_set_alias);
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
<div id="dialog-message" title="Chart Pinned" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
		<span id="pnchart"></span> now pinned to your home.
	</p>
</div>
</body>
</html>