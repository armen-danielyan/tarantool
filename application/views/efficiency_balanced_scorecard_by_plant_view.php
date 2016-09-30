<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Efficiency Balanced Scorecard by Plant</title>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/FusionCharts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/tablelizer/jquery.tabelizer.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/fixtable/jquery.fixedTblHdrLftCol.js"></script>
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
    //$('#tb1').tabelize({
	// OPTIONS HERE
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
<?php
function numformat($val, $name_element, $case)
{
	// formats number for table by detecting specific nodes manually and detecting type or case.
	$data = '';
	if(	$name_element == "Production vs Budget (%)" && $case == "m" )
	{
		$data = number_format($val*100, 1, ".", ",");
		$data = $data."%";
	}
	else if($name_element == "Cost Per LB vs. Budget (%)" && $case == "y")
	{
		$data = number_format($val, 3, ".", ",");
	}
	else if($name_element == "Cost Per LB vs. Budget (%)" && $case == "m")
	{
		$data = number_format($val*100, 1, ".", ",");
		$data = $data."%";
	} 
	else if($name_element == "Utilities Cost Per LB")
	{
		$data = number_format($val, 3, ".", ",");
	}
	else if($name_element == "Material Efficiency" || $name_element == "OT%" || $name_element == "OEE" || $name_element == "OTIF")
	{
		$data = number_format($val*100, 1, ".", ",");
		$data = $data."%";
	}
	else
	{
		if($val === '') // need to use === for absolute comparison since '' and 0 is treated the same IF using only ==
		{
			//$data = "im empty"; do nothing
		}
		else
		{
			$data = number_format($val, 0, ".", ",");
		}
	}
	
	return $data;
}
?>

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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('efficiency_balanced_scorecard_by_plant'); ?>">
                        
                        
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
                            
                            <select name="balance_scorecard_value" onchange="this.form.submit();" class="ddown1" title="Select Balance Scorecard Value">
                            <?php
                                foreach($form_balance_scorecard_value as $row)
                                {
                                    $depth = '';
									$disable = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
									if($row['number_children'] != 0)
									{
										$disable = "disabled='disabled'";
									}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php echo $disable; ?> <?php if($balance_scorecard_value == $row['element']){ $n_balance_scorecard_value = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > ".anchor('efficiency_balanced_scorecard', 'Balanced Scorecard', array('title' => 'View Efficiency Balanced Scorecard'))." > <span class='orange'>By Plant</span> > ".anchor('efficiency_balanced_scorecard_input', 'Input', array('title' => 'View Efficiency Balanced Scorecard Input'));
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Plants</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1">chart1 here</div></td>
                    		
                    	</tr>
                    	
                    </table>
                    
                </div>
                
            </div>
            
            <div class="content_div" >
                
                <table id="tb1" class="avtable_2 controller">
                	<tr data-level="header" class="header">
						<td class="label" >&nbsp;</td>
                    	<td class="thead">FY
                    		<select name="year1" onchange="this.form.submit();"  title="Select Year">
                            <?php
                                foreach($form_year as $row)
                                {
                                    $depth = '';
                                    //for($i=0; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;';
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($year1 == $row['element']){ $nyear1 = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                    	</td>
						<td class="thead">FY
							<select name="year2" onchange="this.form.submit();"  title="Select Year">
                            <?php
                                foreach($form_year as $row)
                                {
                                    $depth = '';
                                    //for($i=0; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;';
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($year2 == $row['element']){ $nyear2 = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
						</td>
						<td class="thead">Nov</td>
						<td class="thead">Dec</td>
						<td class="thead">Jan</td>
						<td class="thead">Feb</td>
						<td class="thead">Mar</td>
						<td class="thead">Apr</td>
						<td class="thead">May</td>
						<td class="thead">Jun</td>
						<td class="thead">Jul</td>
						<td class="thead">Aug</td>
						<td class="thead">Sep</td>
						<td class="thead">Oct</td>
						<td class="thead">YTD</td>
						<td class="thead">Goal</td>
					</tr>
                    <?php
                    	//$depth_stack = array();
						//multi series chart initializer
						$chart1 = "<categories><category label='FY".$nyear1."' /><category label='FY".$nyear2."' /><category label='Nov' /><category label='Dec' /><category label='Jan' /><category label='Feb' /><category label='Mar' /><category label='Apr' /><category label='May' /><category label='Jun' /><category label='Jul' /><category label='Aug' /><category label='Sep' /><category label='Oct' /><category label='YTD' /><category label='Goal' /></categories>";
						
                    	foreach($resource_elements_plants_alias as $row)
                    	{
                    		$fy1 = $fy2 = $m01 = $m02 = $m03 = $m04 = $m05 = $m06 = $m07 = $m08 = $m09 = $m10 = $m11 = $m12 =  $ytd = $goal = '';
                    		
							$arrow1 = '';
							$arrow2 = '';
							$arrow3 = '';
                            
							foreach($table_data_fy as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($row['element'] == $paths['4'] && $year1 == $paths['1'])
								{
									$fy1 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $year2 == $paths['1'])
								{
									$fy2 = $drow1['value'];
								}
							}
							
							foreach($table_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($row['element'] == $paths['4'] && $month1 == $paths['2'])
								{
									$m01 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month2 == $paths['2'])
								{
									$m02 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month3 == $paths['2'])
								{
									$m03 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month4 == $paths['2'])
								{
									$m04 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month5 == $paths['2'])
								{
									$m05 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month6 == $paths['2'])
								{
									$m06 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month7 == $paths['2'])
								{
									$m07 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month8 == $paths['2'])
								{
									$m08 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month9 == $paths['2'])
								{
									$m09 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month10 == $paths['2'])
								{
									$m10 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month11 == $paths['2'])
								{
									$m11 = $drow1['value'];
								}
								if($row['element'] == $paths['4'] && $month12 == $paths['2'])
								{
									$m12 = $drow1['value'];
								}
								
							}
							
							foreach($table_data_goal as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($row['element'] == $paths['4'])
								{
									$goal = $drow1['value'];
								}
							}
							
							//ytd formulas are different in all. manually detect based on element name of filter
							if
							(
								$n_balance_scorecard_value == "L1 Injuries" ||
								$n_balance_scorecard_value == "L1 Spills" ||
								$n_balance_scorecard_value == "L1 PSM Incidents" ||
								$n_balance_scorecard_value == "L1 Fires" ||
								$n_balance_scorecard_value == "L1 Regulatory Inspections/Odor Complaint" ||
								
								$n_balance_scorecard_value == "L2 Injuries" ||
								$n_balance_scorecard_value == "L2 Spills" ||
								$n_balance_scorecard_value == "L2 PSM Incidents" ||
								$n_balance_scorecard_value == "L2 Fires" ||
								$n_balance_scorecard_value == "L2 Regulatory Inspections/Odor Complaint" ||
								
								$n_balance_scorecard_value == "L3 Events" ||
								
								$n_balance_scorecard_value == "Open Action Items" ||
								$n_balance_scorecard_value == "Overdue Action Items" ||
								
								$n_balance_scorecard_value == "Training Topics" ||
								$n_balance_scorecard_value == "Overdue Training Topics" ||
								
								$n_balance_scorecard_value == "MOCs Initiated" ||
								
								$n_balance_scorecard_value == "Total Cost vs Budget" ||
								$n_balance_scorecard_value == "Customer Complaints" ||
								$n_balance_scorecard_value == "L1 Valtrack" ||
								$n_balance_scorecard_value == "L2 Valtrack" ||
								$n_balance_scorecard_value == "L3 Valtrack" ||
								$n_balance_scorecard_value == "Overdue Quality Investigations" ||
								$n_balance_scorecard_value == "Process Control WO Completed" ||
								$n_balance_scorecard_value == "Process Control WO Backlog" ||
								$n_balance_scorecard_value == "Scale Up Completed" ||
								$n_balance_scorecard_value == "Formula Feedback Implemented" ||
								$n_balance_scorecard_value == "Capital Projects"
							)
							{
								$ytd = $m01 + $m02 + $m03 + $m04 + $m05 + $m06 + $m07 + $m08 + $m09 + $m10 + $m11 + $m12;
							}
							
							if
							(
								$n_balance_scorecard_value == "Production vs Budget (%)" ||
								$n_balance_scorecard_value == "Cost Per LB vs. Budget (%)" ||
								$n_balance_scorecard_value == "Utilities Cost Per LB" ||
								$n_balance_scorecard_value == "Material Efficiency" ||
								
								$n_balance_scorecard_value == "Total Headcount" ||
								$n_balance_scorecard_value == "OT%" ||
								
								$n_balance_scorecard_value == "Lb/ManHr" ||
								
								$n_balance_scorecard_value == "OTIF"
							)
							{
								$sum1 = $m01 + $m02 + $m03 + $m04 + $m05 + $m06 + $m07 + $m08 + $m09 + $m10 + $m11 + $m12;
								$div1 = 0;
								if($m01 != 0)
								{
									$div1 += 1;
								}
								if($m02 != 0)
								{
									$div1 += 1;
								}
								if($m03 != 0)
								{
									$div1 += 1;
								}
								if($m04 != 0)
								{
									$div1 += 1;
								}
								if($m05 != 0)
								{
									$div1 += 1;
								}
								if($m06 != 0)
								{
									$div1 += 1;
								}
								if($m07 != 0)
								{
									$div1 += 1;
								}
								if($m08 != 0)
								{
									$div1 += 1;
								}
								if($m09 != 0)
								{
									$div1 += 1;
								}
								if($m10 != 0)
								{
									$div1 += 1;
								}
								if($m11 != 0)
								{
									$div1 += 1;
								}
								if($m12 != 0)
								{
									$div1 += 1;
								}
								
								if($sum1 != 0 && $div1 != 0)
								{
									$ytd = $sum1/$div1;
								} else {
									$ytd = 0;
								}
								
							}
							//ytd formula end
							
							//Chart generators//
							
							/* CHART 1*/
							
							$chart1 .= "<dataset seriesName='".$row['name_element']."'>";
							$chart1 .= "<set value='".$fy1."' /><set value='".$fy2."' /><set value='".$m01."' /><set value='".$m02."' /><set value='".$m03."' /><set value='".$m04."' /><set value='".$m05."' /><set value='".$m06."' /><set value='".$m07."' /><set value='".$m08."' /><set value='".$m09."' /><set value='".$m10."' /><set value='".$m11."' /><set value='".$m12."' /><set value='".$ytd."' /><set value='".$goal."' />";
							$chart1 .= "</dataset>";
							
							/* end chart 1 */
							
							// end chart generators
							
					?>		
					<tr   >
						<td class="label"><?php echo $depth."".$row['name_element']; ?></td>
						<td >
							<?php 
							//echo number_format($fy1, 3, ".", ","); 
							//echo $fy1;
							echo numformat($fy1, $n_balance_scorecard_value, "y");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($fy2, 3, ".", ","); 
							//echo $fy2;
							echo numformat($fy2, $n_balance_scorecard_value, "y");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($jan, 3, ".", ","); 
							//echo $m01;
							echo numformat($m01, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($feb, 3, ".", ","); 
							//echo $m02;
							echo numformat($m02, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($mar, 3, ".", ","); 
							//echo $m03;
							echo numformat($m03, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($apr, 3, ".", ","); 
							//echo $m04;
							echo numformat($m04, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($may, 3, ".", ","); 
							//echo $m05;
							echo numformat($m05, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($jun, 3, ".", ","); 
							//echo $m06;
							echo numformat($m06, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($jul, 3, ".", ","); 
							//echo $m07;
							echo numformat($m07, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($aug, 3, ".", ","); 
							//echo $m08;
							echo numformat($m08, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($sep, 3, ".", ","); 
							//echo $m09;
							echo numformat($m09, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($oct, 3, ".", ","); 
							//echo $m10;
							echo numformat($m10, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($nov, 3, ".", ","); 
							//echo $m11;
							echo numformat($m11, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($dec, 3, ".", ","); 
							//echo $m12;
							echo numformat($m12, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($ytd, 3, ".", ","); 
							//echo $ytd;
							echo numformat($ytd, $n_balance_scorecard_value, "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($goal, 3, ".", ","); 
							//echo $goal;
							echo numformat($goal, $n_balance_scorecard_value, "y");
							?>
						</td>
					</tr>
					<?php
							
                    	}
                    ?>
                    
                    	
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
	// Plants
	var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSColumn2D.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='<?php echo $n_balance_scorecard_value; ?>' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
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