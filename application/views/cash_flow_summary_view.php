<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Cash Flow Summary</title>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/FusionCharts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/tablelizer/jquery.tabelizer.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Cuprum:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/smoothness/jquery-ui-1.9.1.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" media="screen" />
<link href="<?php echo base_url(); ?>assets/tablelizer/tabelizer.css" rel="stylesheet">
<?php
	function NPV($rate, $values) {

		if (!is_array($values)) return null;
		
		$npv = 0.0;
		
		for ($i = 0; $i < count($values); $i++) {
		
		$npv += $values[$i] / pow(1 + $rate, $i + 1);
		
		}
		
		return (is_finite($npv) ? $npv: null);
		//return ($npv);

	}
?>
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
    //var table1 = $('#tb1').tabelize({
	// OPTIONS HERE
	//});
});

$(document).on('keyup', '.numeric-only', function(event) {
   var v = this.value;
   if($.isNumeric(v) === false) {
        //chop off the last char entered
        this.value = this.value.slice(0,-1);
   }
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('cash_flow_summary'); ?>">
                        
                        <div class="filter_div">
                            
                            <select name="customer" onchange="this.form.submit();" class="ddown1" title="Select Customer">
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
                        
                        <div class="filter_div">
                            
                            <select name="project" onchange="this.form.submit();" class="ddown1" title="Select Project">
                            <?php
                                foreach($form_project as $row)
                                {
                                    $depth = '';
                                    for($i=1; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($project == $row['element']){ $n_project = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
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
                    
                </div>
            </div>
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Cash Flow Summary</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
            </div>
            
            <div class="tabber">
            	<div class="tabbertab">
                    <h3>Cummulative Cash Flow</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"></div></td>
                    	</tr>
                    </table>
                    
                </div>
            </div>
	            
	            
            <div class="content_div" >
                
                
                
                <table id="tb1" class="avtable_2 controller">
                	<tr>
                		<td>&nbsp;</td>
                		<td class="thead">Project Cost</td>
                		<td class="thead">Software</td>
                		<td class="thead">Implementation Service</td>
                		<td class="thead">Maintenance</td>
                		<td class="thead">Validation Services</td>
                		<td class="thead">Hardware</td>
                		<td class="thead">Pre-Tax Savings</td>
                		<td class="thead">One-Time Savings</td>
                		<td class="thead">Recurring Savings</td>
                		<td class="thead">Post-Tax Savings</td>
                		<td class="thead">Net Savings</td>
                		<td class="thead">Cash Flow</td>
                		<td class="thead">Payback Calc</td>
                		
                	</tr>
                    <?php
                    	$depth_stack = array();
                    	$chart1 = "";
						$cashflow = array();
						$pbyears = array();
						
						$pcost = array();
						$pretax = array();
						$posttax = array();
						
                    	foreach($year_elements_set as $row)
                    	{
                    		$a = $b = $c = $d = $e = $f = $g = $h = $i = $j = $k = $l = $m = 0;
							$depth = '';
							$true_depth = 1;
							$sub_depth = 1;
							$sign = '';
							
                            for($z=1; $z<$row['depth']; $z++)
                            {
                                $depth .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                                $true_depth += 1;
                            }
							
							if($row['number_children'] != 0)
							{
								//$depth .= "<span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span> ";
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
							
							
							foreach($tc_cells as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($row['element'] == $paths['1'] && $value_element_ve_400_100 == $paths['2'])
								{
									$a = $drow1['value'];
									$pcost[] = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_101 == $paths['2'])
								{
									$b = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_102 == $paths['2'])
								{
									$c = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_103 == $paths['2'])
								{
									$d = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_104 == $paths['2'])
								{
									$e = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_105 == $paths['2'])
								{
									$f = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_200 == $paths['2'])
								{
									$g = $drow1['value'];
									$pretax[] = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_201 == $paths['2'])
								{
									$h = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_202 == $paths['2'])
								{
									$i = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_300 == $paths['2'])
								{
									$j = $drow1['value'];
									$posttax[] = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_400 == $paths['2'])
								{
									$k = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_500 == $paths['2'])
								{
									$l = $drow1['value'];
									$chart1 .= "<set label='".$row['name_element']."' value='".$drow1['value']."' />";
									$cashflow[] = $drow1['value'];
								}
								
								if($row['element'] == $paths['1'] && $value_element_ve_400_600 == $paths['2'])
								{
									$m = $drow1['value'];
									$pbyears[] = $drow1['value'];
								}
								
							}
							
							
							//add $ sign to specific rows
							
							$font = "";
							
							//if($a == 0)
							//{
								//do nothing
							//} 
							//else
							//{
					?>		
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
						<td class="label" <?php echo $font; ?> ><?php echo $depth."".$row['name_element']; ?></td>
						
                    	<td >
							<?php echo number_format($a, 2, ".", ","); ?>
                    	</td>
                    	<td >
							<input name="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_101; ?>" type="text" id="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_101; ?>" value="<?php echo number_format($b, 2, ".", ""); ?>" class="numeric-only text_right">
                    	</td>
                    	<td >
							<input name="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_102; ?>" type="text" id="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_102; ?>" value="<?php echo number_format($c, 2, ".", ""); ?>" class="numeric-only text_right">
                    	</td>
                    	<td >
							<input name="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_103; ?>" type="text" id="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_103; ?>" value="<?php echo number_format($d, 2, ".", ""); ?>" class="numeric-only text_right">
                    	</td>
                    	<td >
							<input name="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_104; ?>" type="text" id="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_104; ?>" value="<?php echo number_format($e, 2, ".", ""); ?>" class="numeric-only text_right">
                    	</td>
                    	<td >
							<input name="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_105; ?>" type="text" id="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_105; ?>" value="<?php echo number_format($f, 2, ".", ""); ?>" class="numeric-only text_right">
                    	</td>
                    	<td >
							<?php echo number_format($g, 2, ".", ","); ?>
                    	</td>
                    	<td >
							<input name="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_201; ?>" type="text" id="var_<?php echo $row['element']; ?>_<?php echo $value_element_ve_400_201; ?>" value="<?php echo number_format($h, 2, ".", ""); ?>" class="numeric-only text_right">
                    	</td>
                    	<td >
							<?php echo number_format($i, 2, ".", ","); ?>
                    	</td>
                    	<td >
							<?php echo number_format($j, 2, ".", ","); ?>
                    	</td>
                    	<td >
							<?php echo number_format($k, 2, ".", ","); ?>
                    	</td>
                    	<td >
							<?php echo number_format($l, 2, ".", ","); ?>
                    	</td>
                    	<td >
							<?php echo number_format($m, 2, ".", ","); ?>
                    	</td>
						
					</tr>
					<?php
							//}
                    	}
                    ?>
                    <tr>
                    	<td colspan="13">&nbsp;</td>
                    	<td>
                    		<input name="Update" type="submit" id="Update" value="Update" class="obutton1" />
                    		</td>
                    </tr>
                    	
                </table>
                
                </form>
                <table id="tb2" class="avtable_2 controller">
                	<tr>
                		<td class="label">IRR</td>
                		<td>
                			<?php
							echo number_format(($this->irrhelper->IRR($cashflow) * 100), 2, ".", ",")." %";
                			?>
                		</td>
                	</tr>
                	<tr>
                		<td class="label">ROI Pre-Tax</td>
                		<td>
                			<?php
                			$pcost = array_sum($pcost);
							$pretax = array_sum($pretax);
							$posttax = array_sum($posttax);
							
							$preval = $pretax/$pcost*100;
							
							echo number_format($preval, 0)." %";
                			?>
                		</td>
                	</tr>
                	<tr>
                		<td class="label">ROI Post-Tax</td>
                		<td>
                			<?php
                			$postval = $posttax/$pcost*100;
							echo number_format($postval, 0)." %";
                			?>
                		</td>
                	</tr>
                	<tr>
                		<td class="label">Payback Years</td>
                		<td>
                			<?php
                				//detect the closest to 0 then select the right pbyear
                				$tempflow = array();
                				foreach($cashflow as $row)
                				{
                					$tempflow[] = abs($row);
                				}
								
								$index = array_keys($tempflow, min($tempflow));
								echo number_format($pbyears[$index[0]], 2, ".", ",");
                			?>
                		</td>
                	</tr>
                	<tr>
                		<td class="label">NPV</td>
                		<td>
                			<?php
                			 //echo $npvrate[0]['value'];
                			 $npvarray = $cashflow;
							 $npv1 = array_shift($npvarray);
							 echo CUR_SIGN." ".number_format((NPV($npvrate[0]['value'], $npvarray) + $npv1), 2, ".", ",");
                			?>
                		</td>
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

    var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Line.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
    myChart1.render("chartContainer1");
    
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