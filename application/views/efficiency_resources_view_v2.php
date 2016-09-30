<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Efficiency Resources</title>
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
        active: 0
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('efficiency_resources_v2'); ?>">
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
                                    for($i=1; $i<$row['depth']; $i++)
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
                            <div class="left">
                                <?php //echo mailto("?to=&subject=&body=".site_url('efficiency_resources')."/info/".$n_year."/".url_title($n_month, '_')."/".url_title($n_receiver, '_'), "<span class='ui-icon ui-icon-mail-closed left' ></span>", array("title" =>"Share this page via email")); 
                                ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Resources</span> > ".anchor('efficiency_resources_details_v2', 'Details', array('title' => 'View Efficiency Resources Details'));
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
                <?php
                if($this->jedox->page_permission($jedox_user_details['group_names'], "efficiency_resources_details"))
                {
                    //echo anchor("efficiency_resources_details_v2", "<span class='ui-icon ui-icon-search right' ></span>", array('title' => 'View Efficiency Resources Details')); 
                }
                ?>
            </div>
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Total Cost</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"></div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Output Quantities</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer2" class="chart1"></div></td>
                    	</tr>
                    </table>
                </div>
                <div class="tabbertab">
                    <h3>Capacity Used</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer3" class="chart1"></div></td>
                    	</tr>
                    </table>
                    
                </div>
            </div>
            
            <div class="content_div" >
                
                <table id="tb1" class="avtable_2 controller">
                	<tr data-level="header" class="header">
						<td>&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td class="center" colspan="4">Total Costs/Resource Pools</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="center" colspan="4">Output Quantity</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="center" colspan="5">Capacity Used</td>
					</tr>
                	<tr data-level="header" class="header">
						<td>&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead" style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td class="thead">Actual</td>
						<td class="thead">Target</td>
						<td class="thead">Plan</td>
						<td class="thead">Act. Tar. Variance</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead" style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead">Actual</td>
						<td class="thead">Target</td>
						<td class="thead">Act. Tar. Variance</td>
						<td class="thead">UoM</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead" style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td class="thead">Capacity</td>
						<td class="thead">Actual</td>
						<td class="thead">Plan</td>
						<td class="thead">Ex/Idle Qty</td>
						<td class="thead">EX/Idle $</td>
					</tr>
                    <?php
                    	$depth_stack = array();
                    	foreach($resource_set as $row)
                    	{
                    		$a = $b = $c = $d = $e = $f = $g = $h = $i = $j = $k = $l = 0;
							$depth = '';
							$true_depth = 1;
							$sub_depth = 1;
							$uom = "";
							$arrow1 = '';
							$arrow2 = '';
							$arrow3 = '';
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
							
							
                            
							
							
							foreach($table1a_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version_a == $paths[0] && $row['element'] == $paths['4'])
								{
									$a = $drow1['value'];
								}
								if($version_t == $paths[0] && $row['element'] == $paths['4'])
								{
									$b = $drow1['value'];
								}
								if($version_p == $paths[0] && $row['element'] == $paths['4'])
								{
									$c = $drow1['value'];
								}
								if($version_AT == $paths[0] && $row['element'] == $paths['4'])
								{
									$d = $drow1['value'];
								}
							}
							
							foreach($table1b_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version_a == $paths[0] && $row['element'] == $paths['4'])
								{
									$e = $drow1['value'];
								}
								if($version_p == $paths[0] && $row['element'] == $paths['4'])
								{
									$f = $drow1['value'];
								}
								if($version_AP == $paths[0] && $row['element'] == $paths['4'])
								{
									$g = $drow1['value'];
								}
							}
							
							foreach($table1c_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version_p == $paths[0] && $row['element'] == $paths['4'] && $report_value_rk == $paths['3'])
								{
									$h = $drow1['value'];
								}
								// i and j manually computed.
								if($version_a == $paths[0] && $row['element'] == $paths['4'] && $report_value_reiq == $paths['3'])
								{
									$k = $drow1['value'];
								}
								if($version_a == $paths[0] && $row['element'] == $paths['4'] && $report_value_reic == $paths['3'])
								{
									$l = $drow1['value'];
								}
							}
							
							if($h != 0)
							{
								$i = ($e/$h)* 100;
								$j = ($f/$h)* 100;
							}
							
							foreach($cells_resource_attributes as $drow)
							{
								$paths = explode(",", $drow['path']);
								if($row['element'] == $paths[1])
								{
									$uom = $drow['value'];
								}
							}
							
							if($b != 0 && $d < 0)
							{
								$atest = abs( $d/$b );
								if($atest > 0.05 && $atest < 0.1)
								{
									$arrow1 = "<img src='".base_url()."assets/images/amber_up.png' />";
								}
								if($atest >= 0.1)
								{
									$arrow1 = "<img src='".base_url()."assets/images/red_up.png' />";
								}
							}
							
							if($f != 0 && $g > 0)
							{
								$atest = abs( $g/$f );
								if($atest > 0.05 && $atest < 0.1)
								{
									$arrow2 = "<img src='".base_url()."assets/images/amber_down.png' />";
								}
								if($atest >= 0.1)
								{
									$arrow2 = "<img src='".base_url()."assets/images/red_down.png' />";
								}
							}
							
							if($h != 0 )
							{
								$atest = abs( $e/$h );
								if($atest > 0.9 && $atest < 0.95)
								{
									$arrow3 = "<img src='".base_url()."assets/images/amber_up.png' />";
								}
								if($atest >= 0.95)
								{
									$arrow3 = "<img src='".base_url()."assets/images/red_up.png' />";
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
						<td style="min-width:20px !important; text-align:center !important;"><?php echo $arrow1; ?></td>
                    	<td ><?php echo CUR_SIGN." ".number_format($a, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;"><?php echo $arrow2; ?></td>
						<td ><?php echo number_format($e, 0, ".", ","); ?></td>
						<td ><?php echo number_format($f, 0, ".", ","); ?></td>
						<td ><?php echo number_format($g, 0, ".", ","); ?></td>
						<td ><?php echo $uom; ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;"><?php echo $arrow3; ?></td>
                    	<td ><?php echo number_format($h, 0, ".", ","); ?></td>
						<td ><?php echo number_format($i, 0, ".", ","); ?>%</td>
						<td ><?php echo number_format($j, 0, ".", ","); ?>%</td>
						<td ><?php echo number_format($k, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($l, 0, ".", ","); ?></td>
					</tr>
					<?php
							}
                    	}
                    ?>
                    
                    <?php
                    	//total
                    	$a1 = $b1 = $c1 = $d1 = 0;
						
						foreach($table2a_data as $drow1)
						{
							$paths = explode(",", $drow1['path']);
							if($version_a == $paths[0])
							{
								$a1 = $drow1['value'];
							}
							if($version_t == $paths[0])
							{
								$b1 = $drow1['value'];
							}
							if($version_p == $paths[0])
							{
								$c1 = $drow1['value'];
							}
							if($version_AT == $paths[0])
							{
								$d1 = $drow1['value'];
							}
						}
                    ?>
                    <tr class="tmain1 header" data-level="1">	
                    	<td class="label">Total</td>
                    	<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($a1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($d1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
					</tr>	
                </table>
                
            </div>
            
        </td>
    </tr>
    <tr>
        <td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
    </tr>
</table>

<script type="text/javascript">

    var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
    myChart1.render("chartContainer1");
    
    var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_2", "600", "300", "0", "1");
    myChart2.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2; ?>"+"</chart>");
    myChart2.render("chartContainer2");
    
    var myChart3 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/StackedColumn2D.swf", "chartId_3", "600", "300", "0", "1");
    myChart3.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' yAxisName='' showValues='0' numberSuffix='' stack100Percent='1'>"+"<?php echo $chart3; ?>"+"</chart>");
    myChart3.render("chartContainer3");
</script>
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

</body>
</html>