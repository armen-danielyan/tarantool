<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Taste Test Management</title>
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
    $("#tb2").detach().appendTo("#tablehere");
    var table3 = $('#tb3').tabelize({
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('taste_test_management'); ?>">
                        <div class="filter_div">
                            <select name="year" onchange="this.form.submit();" class="ddown1" title="Select Year">
                            <?php
                                foreach($form_year as $row)
                                {
                                    $depth = '';
                                    //for($i=0; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;';
                                    //}
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
                                foreach($form_month as $row)
                                {
                                    $depth = '';
                                    //for($i=0; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;';
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($month == $row['element']){ $n_month = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        <div class="filter_div">
                            
                            <select name="product"  onchange="this.form.submit();" class="ddown1" title="Select Product">
		                    <?php
		                        foreach($form_product as $row)
		                        {
		                            $depth = '';
		                            for($i=2; $i<$row['depth']; $i++)
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
                        	<select name="source"  onchange="this.form.submit();" class="ddown1" title="Select Source">
			                    <?php
			                        foreach($form_source as $row)
			                        {
			                            $depth = '';
			                            for($i=1; $i<$row['depth']; $i++)
			                            {
			                                $depth .= '&nbsp;&nbsp;';
			                            }
			                    ?>  
			                        <option value="<?php echo $row['element']; ?>" <?php if($source == $row['element']){ $n_source = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Taste Test Management</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
                &nbsp;
            </div>
            
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Malt Flavors</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"> chart 1 here</div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Hop Flavors</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer2" class="chart1"> chart 2 here</div></td>
                    	</tr>
                    </table>
                </div>
                <div class="tabbertab">
                    <h3>Fermentation Flavors</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer3" class="chart1"> chart 3 here</div></td>
                    	</tr>
                    </table>
                </div>
                <div class="tabbertab">
                    <h3>Carbonation</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer4" class="chart1"> chart 4 here</div></td>
                    	</tr>
                    </table>
                </div>
                <div class="tabbertab">
                    <h3>Body</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer5" class="chart1"> chart 5 here</div></td>
                    	</tr>
                    </table>
                </div>
                <div class="tabbertab">
                    <h3>Validation</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer6" class="chart1"> chart 6 here</div></td>
                    	</tr>
                    </table>
                </div>
            </div>
            
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Taste Table</h3>
                    <div id="tablehere"></div>
	                <table id="tb1" class="avtable_2">
	                	<tr >
							<td class="thead">Panelist</td>
							<td class="thead" colspan="3">Comment</td>
							<td class="thead">Pass 0</td>
							<td class="thead">Fail 0</td>
							<td class="thead">Blend 0</td>
							<td class="thead">True to Spec 0</td>
							<td class="thead">Not to Spec 0</td>
						</tr>
						<?php
							$btot = $ctot = $dtot = $etot = $ftot = 0;
							foreach($source_set_alias as $row)
							{
								$a = $b = $c = $d = $e = $f = ''; // comment
								
								foreach($table1_data as $drow1)
								{
									$paths = explode(",", $drow1['path']);
									if($paths[4] == $row['element'] && $paths[6] == $quality_test_qt_tt_c01)
									{
										$a = $drow1['value'];
									}
								}
								
								foreach($table2_data as $drow1)
								{
									$paths = explode(",", $drow1['path']);
									if($paths[4] == $row['element'] && $paths[6] == $quality_test_qt_tt_p01_01)
									{
										$b = round($drow1['value']);
									}
								}
								
								foreach($table2_data as $drow1)
								{
									$paths = explode(",", $drow1['path']);
									if($paths[4] == $row['element'] && $paths[6] == $quality_test_qt_tt_p01_02)
									{
										$c = round($drow1['value']);
									}
								}
								
								foreach($table2_data as $drow1)
								{
									$paths = explode(",", $drow1['path']);
									if($paths[4] == $row['element'] && $paths[6] == $quality_test_qt_tt_p01_03)
									{
										$d = round($drow1['value']);
									}
								}
								
								foreach($table2_data as $drow1)
								{
									$paths = explode(",", $drow1['path']);
									if($paths[4] == $row['element'] && $paths[6] == $quality_test_qt_tt_p07_01)
									{
										$e = round($drow1['value']);
									}
								}
								
								foreach($table2_data as $drow1)
								{
									$paths = explode(",", $drow1['path']);
									if($paths[4] == $row['element'] && $paths[6] == $quality_test_qt_tt_p07_02)
									{
										$f = round($drow1['value']);
									}
								}
								if($a == '' && $b == 0 && $c == 0 && $d == 0 && $e == 0 && $f == 0)
								{
									// means no data for panelist
								} else {
									// data exist. display data and add it up
									$btot += $b;
									$ctot += $c;
									$dtot += $d;
									$etot += $e;
									$ftot += $f;
						?>
						<tr>
							<td class="label"><?php echo $row['name_element']; ?></td>
							<td colspan="3" style="text-align: left;"><?php echo $a; ?></td>
							<td><?php echo $b; ?></td>
							<td><?php echo $c; ?></td>
							<td><?php echo $d; ?></td>
							<td><?php echo $e; ?></td>
							<td><?php echo $f; ?></td>
						</tr>
						<?php
								}
							}
						?>
						
	                </table>
	                <table id="tb2" class="avtable_2">
	                	<tr >
							<td class="thead" colspan="2">Verdict</td>
							<td class="thead" colspan="2">True to Spec</td>
						</tr>
						<tr>
							<td class="label">Pass</td>
							<td><?php echo $btot; ?></td>
							<td class="label">Yes</td>
							<td ><?php echo $etot; ?></td>
						</tr>
						<tr>
							<td class="label">Fail</td>
							<td><?php echo $ctot; ?></td>
							<td class="label">No</td>
							<td ><?php echo $ftot; ?></td>
						</tr>
						<tr>
							<td class="label">Blend</td>
							<td><?php echo $dtot; ?></td>
							<td class="label">&nbsp;</td>
							<td >&nbsp;</td>
						</tr>
						<tr class="tmain">
							<td class="label">Total:</td>
							<td><?php echo $btot+$ctot+$dtot; ?></td>
							<td class="label">&nbsp;</td>
							<td ><?php echo $etot+$ftot; ?></td>
						</tr>
						<tr>
							<td class="label" colspan="2">True To Spec Percentage</td>
							<td colspan="2">
								<?php
									if($etot > 0)
									{
										$perc = ( $etot / ($etot+$ftot) ) * 100;
										echo round($perc)."%";
									} else 
									{
										echo "0%";
									}
								?>
							</td>
						</tr>
						<tr>
							<td class="label" colspan="2">Pass Percentage to Proceed With Beer</td>
							<td colspan="2">
								<?php
									if($btot > 0)
									{
										$perc = ( $btot / ($btot+$ctot+$dtot) ) * 100;
										echo round($perc)."%";
									} else 
									{
										echo "0%";
									}
								?>
							</td>
						</tr>
						<tr>
							<td class="label" colspan="2">Recomendation to Blend Percentage</td>
							<td colspan="2">
								<?php
									if($dtot > 0)
									{
										$perc = ( $dtot / ($btot+$ctot+$dtot) ) * 100;
										echo round($perc)."%";
									} else 
									{
										echo "0%";
									}
								?>
							</td>
						</tr>
	                </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Product table</h3>
                    <table id="tb3" class="avtable_2 controller">
	                	<tr data-level="header" class="header">
							
	                    	<td class="thead">Products</td>
							<td class="thead">Totals Spec Values</td>
							<td class="thead">True to Spec</td>
							<td class="thead">Percentage</td>
							<td class="thead">Total Validation Values</td>
							<td class="thead">Pass</td>
							<td class="thead">Fail</td>
							<td class="thead">Blend</td>
							<td class="thead">Pass Percentage</td>
						</tr>
						<?php
							$depth_stack = array();
	                    	foreach($form_product as $row)
	                    	{
	                    		$a = $b = $c = $d = $e = $f = $g = $h =  0;
								$depth = '';
								$true_depth = 1;
								$sub_depth = 1;
								
	                            for($z=2; $z<$row['depth']; $z++)
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
								
								foreach($table3_data as $drow1)
								{
									$paths = explode(",", $drow1['path']);
									if($quality_test_qt_tt_p07 == $paths[6] && $row['element'] == $paths['5'])
									{
										$a = $drow1['value'];
									}
									if($quality_test_qt_tt_p07_01 == $paths[6] && $row['element'] == $paths['5'])
									{
										$b = $drow1['value'];
									}
									// c is computed
									if($quality_test_qt_tt_p01 == $paths[6] && $row['element'] == $paths['5'])
									{
										$d = $drow1['value'];
									}
									if($quality_test_qt_tt_p01_01 == $paths[6] && $row['element'] == $paths['5'])
									{
										$e = $drow1['value'];
									}
									if($quality_test_qt_tt_p01_02 == $paths[6] && $row['element'] == $paths['5'])
									{
										$f = $drow1['value'];
									}
									if($quality_test_qt_tt_p01_03 == $paths[6] && $row['element'] == $paths['5'])
									{
										$g = $drow1['value'];
									}
									//h is computed
								}
								
								// compute c and h
								if($a > 0)
								{
									$c = ($b/$a) * 100;
								}
								if($d > 0)
								{
									$h = ($e/$d) * 100;
								}
								
								if($a == 0 && $b == 0 && $c == 0 && $d == 0 && $e == 0 && $f == 0 && $g == 0 && $h == 0 )
								{
									//do nothing
								} 
								else
								{
						?>	
						<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
							<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
							<td ><?php echo number_format($a, 0, ".", ","); ?></td>
							<td ><?php echo number_format($b, 0, ".", ","); ?></td>
							<td ><?php echo number_format($c, 0, ".", ","); ?>%</td>
							<td ><?php echo number_format($d, 0, ".", ","); ?></td>
							<td ><?php echo number_format($e, 0, ".", ","); ?></td>
							<td ><?php echo number_format($f, 0, ".", ","); ?></td>
							<td ><?php echo number_format($g, 0, ".", ","); ?></td>
							<td ><?php echo number_format($h, 0, ".", ","); ?>%</td>
						</tr>	
						<?php
								}
							}
						?>
						
                    </table>
                </div>
            </div
            
        </td>
    </tr>
    <tr>
        <td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
    </tr>
</table>

<script type="text/javascript">
	var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/PowerCharts_XT/Charts/Radar.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
    myChart1.render("chartContainer1");
    
    var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/PowerCharts_XT/Charts/Radar.swf", "chartId_2", "600", "300", "0", "1");
    myChart2.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2; ?>"+"</chart>");
    myChart2.render("chartContainer2");
    
    var myChart3 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/PowerCharts_XT/Charts/Radar.swf", "chartId_3", "600", "300", "0", "1");
    myChart3.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart3; ?>"+"</chart>");
    myChart3.render("chartContainer3");
    
    var myChart4 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/PowerCharts_XT/Charts/Radar.swf", "chartId_4", "600", "300", "0", "1");
    myChart4.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart4; ?>"+"</chart>");
    myChart4.render("chartContainer4");
    
    var myChart5 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/PowerCharts_XT/Charts/Radar.swf", "chartId_5", "600", "300", "0", "1");
    myChart5.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart5; ?>"+"</chart>");
    myChart5.render("chartContainer5");
    
    var myChart6 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/PowerCharts_XT/Charts/Radar.swf", "chartId_6", "600", "300", "0", "1");
    myChart6.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart6; ?>"+"</chart>");
    myChart6.render("chartContainer6");
    
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