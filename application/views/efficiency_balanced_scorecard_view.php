<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Efficiency Balanced Scorecard</title>
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
    $('#tb1').tabelize({
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('efficiency_balanced_scorecard'); ?>">
                        <div class="filter_div">
                            <select name="version" onchange="this.form.submit();" class="ddown1" title="Select Version">
                            <?php
                                foreach($form_version as $row)
                                {
                                    $depth = '';
                                    //for($i=0; $i<$row['depth']; $i++)
                                    //{
                                        //$depth .= '&nbsp;&nbsp;';
                                    //}
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($version == $row['element']){ $n_version = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
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
                            
                            <select name="resource" onchange="this.form.submit();" class="ddown1" title="Select Resource">
                            <?php
                                foreach($form_resource as $row)
                                {
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
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
                    
                </div>
            </div>
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Balanced Scorecard</span> > ".anchor('efficiency_balanced_scorecard_by_plant', 'By Plant', array('title' => 'View Efficiency Balanced Scorecard By Plant'))." > ".anchor('efficiency_balanced_scorecard_input', 'Input', array('title' => 'View Efficiency Balanced Scorecard Input'));
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            
            <div class="tabber">
                <div class="tabbertab">
                    <h3>HSE</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1">chart1 here</div></td>
                    		<td><div id="chartContainer2" class="chart1">chart2 here</div></td>
                    		<td><div id="chartContainer3" class="chart1">chart3 here</div></td>
                    	</tr>
                    	
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Cost Control</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer4" class="chart1">chart4 here</div></td>
                    		<td><div id="chartContainer4a" class="chart1">chart4a here</div></td>
                    		<td><div id="chartContainer5" class="chart1">chart5 here</div></td>
                    		<td><div id="chartContainer5a" class="chart1">chart5a here</div></td>
                    		<td><div id="chartContainer6" class="chart1">chart6 here</div></td>
                    		<td><div id="chartContainer7" class="chart1">chart7 here</div></td>
                    		<td><div id="chartContainer8" class="chart1">chart8 here</div></td>
                    	</tr>
                    </table>
                </div>
                <div class="tabbertab">
                    <h3>People</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer9" class="chart1">chart9 here</div></td>
                    		<td><div id="chartContainer10" class="chart1">chart10 here</div></td>
                    		<td><div id="chartContainer11" class="chart1">chart11 here</div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Productivity</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer12" class="chart1">chart12 here</div></td>
                    		<td><div id="chartContainer13" class="chart1">chart13 here</div></td>
                    		<td><div id="chartContainer14" class="chart1">chart14 here</div></td>
                    		<td><div id="chartContainer15" class="chart1">chart15 here</div></td>
                    		<td><div id="chartContainer16" class="chart1">chart16 here</div></td>
                    		<td><div id="chartContainer17" class="chart1">chart17 here</div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Reliability</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer18" class="chart1">chart18 here</div></td>
                    		<td><div id="chartContainer19" class="chart1">chart19 here</div></td>
                    		<td><div id="chartContainer20" class="chart1">chart20 here</div></td>
                    		<td><div id="chartContainer21" class="chart1">chart21 here</div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Quality</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer22" class="chart1">chart22 here</div></td>
                    		<td><div id="chartContainer23" class="chart1">chart23 here</div></td>
                    		<td><div id="chartContainer24" class="chart1">chart24 here</div></td>
                    		<td><div id="chartContainer25" class="chart1">chart25 here</div></td>
                    		<td><div id="chartContainer26" class="chart1">chart26 here</div></td>
                    		<td><div id="chartContainer27" class="chart1">chart27 here</div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Technology Development</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer28" class="chart1">chart28 here</div></td>
                    		<td><div id="chartContainer29" class="chart1">chart29 here</div></td>
                    		<td><div id="chartContainer30" class="chart1">chart30 here</div></td>
                    		<td><div id="chartContainer31" class="chart1">chart31 here</div></td>
                    		<td><div id="chartContainer32" class="chart1">chart32 here</div></td>
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
                    	$depth_stack = array();
						//multi series chart initializer
						$chart1 = $chart2 = $chart3 = "<categories><category label='FY".$nyear1."' /><category label='FY".$nyear2."' /><category label='Nov' /><category label='Dec' /><category label='Jan' /><category label='Feb' /><category label='Mar' /><category label='Apr' /><category label='May' /><category label='Jun' /><category label='Jul' /><category label='Aug' /><category label='Sep' /><category label='Oct' /><category label='YTD' /><category label='Goal' /></categories>";
						$chart12 = $chart13 = "<categories><category label='Nov' /><category label='Dec' /><category label='Jan' /><category label='Feb' /><category label='Mar' /><category label='Apr' /><category label='May' /><category label='Jun' /><category label='Jul' /><category label='Aug' /><category label='Sep' /><category label='Oct' /></categories>";
						//single series chart initializer
						$chart4 = $chart5 = $chart6 = $chart7 = $chart8 = $chart9 = $chart10 = $chart11 = $chart14 = $chart15 = '';
						$chart16 = $chart17 = $chart18 = $chart19 = $chart20 = $chart21 = $chart22 = $chart23 = $chart24 = $chart25 = $chart26 = $chart27 = '';
						$chart28 = $chart29 = $chart30 = $chart31 = $chart32 = '';
						$chart4a = $chart5a = '';
						
                    	foreach($balance_scorecard_value_alias as $row)
                    	{
                    		$fy1 = $fy2 = $m01 = $m02 = $m03 = $m04 = $m05 = $m06 = $m07 = $m08 = $m09 = $m10 = $m11 = $m12 =  $ytd = $goal = '';
							$fy1a = $fy2a = $m01a = $m02a = $m03a = $m04a = $m05a = $m06a = $m07a = $m08a = $m09a = $m10a = $m11a = $m12a =  $ytda = $goala = '';
                    		
							$depth = '';
							$true_depth = 1;
							$sub_depth = 1;
							$arrow1 = '';
							$arrow2 = '';
							$arrow3 = '';
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
							
							if($row['number_children'] == 0) // this will exclude posting values of non base elements.
							{
								
								foreach($table_data_fy as $drow1)
								{
									$paths = explode(",", $drow1['path']);
									if($row['element'] == $paths['3'] && $year1 == $paths['1'] && $version == $paths['0'])
									{
										$fy1 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $year2 == $paths['1'] && $version == $paths['0'])
									{
										$fy2 = $drow1['value'];
									}
									//for other version for OEE node
									if($row['element'] == $paths['3'] && $year1 == $paths['1'] && $version != $paths['0'])
									{
										$fy1a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $year2 == $paths['1'] && $version != $paths['0'])
									{
										$fy2a = $drow1['value'];
									}

								}
								
								foreach($table_data as $drow1)
								{
									$paths = explode(",", $drow1['path']);
									if($row['element'] == $paths['3'] && $month1 == $paths['2'] && $version == $paths['0'])
									{
										$m01 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month2 == $paths['2'] && $version == $paths['0'])
									{
										$m02 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month3 == $paths['2'] && $version == $paths['0'])
									{
										$m03 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month4 == $paths['2'] && $version == $paths['0'])
									{
										$m04 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month5 == $paths['2'] && $version == $paths['0'])
									{
										$m05 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month6 == $paths['2'] && $version == $paths['0'])
									{
										$m06 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month7 == $paths['2'] && $version == $paths['0'])
									{
										$m07 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month8 == $paths['2'] && $version == $paths['0'])
									{
										$m08 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month9 == $paths['2'] && $version == $paths['0'])
									{
										$m09 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month10 == $paths['2'] && $version == $paths['0'])
									{
										$m10 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month11 == $paths['2'] && $version == $paths['0'])
									{
										$m11 = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month12 == $paths['2'] && $version == $paths['0'])
									{
										$m12 = $drow1['value'];
									}
									
									// for other version for OEE node
									
									if($row['element'] == $paths['3'] && $month1 == $paths['2'] && $version != $paths['0'])
									{
										$m01a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month2 == $paths['2'] && $version != $paths['0'])
									{
										$m02a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month3 == $paths['2'] && $version != $paths['0'])
									{
										$m03a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month4 == $paths['2'] && $version != $paths['0'])
									{
										$m04a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month5 == $paths['2'] && $version != $paths['0'])
									{
										$m05a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month6 == $paths['2'] && $version != $paths['0'])
									{
										$m06a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month7 == $paths['2'] && $version != $paths['0'])
									{
										$m07a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month8 == $paths['2'] && $version != $paths['0'])
									{
										$m08a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month9 == $paths['2'] && $version != $paths['0'])
									{
										$m09a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month10 == $paths['2'] && $version != $paths['0'])
									{
										$m10a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month11 == $paths['2'] && $version != $paths['0'])
									{
										$m11a = $drow1['value'];
									}
									if($row['element'] == $paths['3'] && $month12 == $paths['2'] && $version != $paths['0'])
									{
										$m12a = $drow1['value'];
									}
									
								}
								
								foreach($table_data_goal as $drow1)
								{
									$paths = explode(",", $drow1['path']);
									if($row['element'] == $paths['3'])
									{
										$goal = $drow1['value'];
									}
								}
								
								//ytd formulas are different in all. manually detect per line based on element name
								if
								(
									$row['name_element'] == "L1 Injuries" ||
									$row['name_element'] == "L1 Spills" ||
									$row['name_element'] == "L1 PSM Incidents" ||
									$row['name_element'] == "L1 Fires" ||
									$row['name_element'] == "L1 Regulatory Inspections/Odor Complaint" ||
									
									$row['name_element'] == "L2 Injuries" ||
									$row['name_element'] == "L2 Spills" ||
									$row['name_element'] == "L2 PSM Incidents" ||
									$row['name_element'] == "L2 Fires" ||
									$row['name_element'] == "L2 Regulatory Inspections/Odor Complaint" ||
									
									$row['name_element'] == "L3 Events" ||
									
									$row['name_element'] == "Open Action Items" ||
									$row['name_element'] == "Overdue Action Items" ||
									
									$row['name_element'] == "Training Topics" ||
									$row['name_element'] == "Overdue Training Topics" ||
									
									$row['name_element'] == "MOCs Initiated" ||
									
									$row['name_element'] == "Total Cost vs Budget" ||
									$row['name_element'] == "Customer Complaints" ||
									$row['name_element'] == "L1 Valtrack" ||
									$row['name_element'] == "L2 Valtrack" ||
									$row['name_element'] == "L3 Valtrack" ||
									$row['name_element'] == "Overdue Quality Investigations" ||
									$row['name_element'] == "Process Control WO Completed" ||
									$row['name_element'] == "Process Control WO Backlog" ||
									$row['name_element'] == "Scale Up Completed" ||
									$row['name_element'] == "Formula Feedback Implemented" ||
									$row['name_element'] == "Capital Projects"
								)
								{
									$ytd = $m01 + $m02 + $m03 + $m04 + $m05 + $m06 + $m07 + $m08 + $m09 + $m10 + $m11 + $m12;
								}
								
								if
								(
									$row['name_element'] == "Production vs Budget (%)" ||
									$row['name_element'] == "Cost Per LB vs. Budget (%)" ||
									$row['name_element'] == "Utilities Cost Per LB" ||
									$row['name_element'] == "Material Efficiency" ||
									
									$row['name_element'] == "Total Headcount" ||
									$row['name_element'] == "OT%" ||
									
									$row['name_element'] == "Lb/ManHr" ||
									
									$row['name_element'] == "OTIF"
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
								if
								(
									$row['name_element'] == "L1 Injuries" ||
									$row['name_element'] == "L1 Spills" ||
									$row['name_element'] == "L1 PSM Incidents" ||
									$row['name_element'] == "L1 Fires" ||
									$row['name_element'] == "L1 Regulatory Inspections/Odor Complaint"
								)
								{
									$chart1 .= "<dataset seriesName='".$row['name_element']."'>";
									$chart1 .= "<set value='".$fy1."' /><set value='".$fy2."' /><set value='".$m01."' /><set value='".$m02."' /><set value='".$m03."' /><set value='".$m04."' /><set value='".$m05."' /><set value='".$m06."' /><set value='".$m07."' /><set value='".$m08."' /><set value='".$m09."' /><set value='".$m10."' /><set value='".$m11."' /><set value='".$m12."' /><set value='".$ytd."' /><set value='".$goal."' />";
									$chart1 .= "</dataset>";
								}
								/* end chart 1 */
								
								/* CHART 2*/
								if
								(
									$row['name_element'] == "L2 Injuries" ||
									$row['name_element'] == "L2 Spills" ||
									$row['name_element'] == "L2 PSM Incidents" ||
									$row['name_element'] == "L2 Fires" ||
									$row['name_element'] == "L2 Regulatory Inspections/Odor Complaint"
								)
								{
									$chart2 .= "<dataset seriesName='".$row['name_element']."'>";
									$chart2 .= "<set value='".$fy1."' /><set value='".$fy2."' /><set value='".$m01."' /><set value='".$m02."' /><set value='".$m03."' /><set value='".$m04."' /><set value='".$m05."' /><set value='".$m06."' /><set value='".$m07."' /><set value='".$m08."' /><set value='".$m09."' /><set value='".$m10."' /><set value='".$m11."' /><set value='".$m12."' /><set value='".$ytd."' /><set value='".$goal."' />";
									$chart2 .= "</dataset>";
								}
								/* end chart 2 */
								
								/* CHART 3*/
								if
								(
									$row['name_element'] == "Open Action Items" ||
									$row['name_element'] == "Overdue Action Items" ||
									$row['name_element'] == "MOCs Initiated"
								)
								{
									$chart3 .= "<dataset seriesName='".$row['name_element']."'>";
									$chart3 .= "<set value='".$fy1."' /><set value='".$fy2."' /><set value='".$m01."' /><set value='".$m02."' /><set value='".$m03."' /><set value='".$m04."' /><set value='".$m05."' /><set value='".$m06."' /><set value='".$m07."' /><set value='".$m08."' /><set value='".$m09."' /><set value='".$m10."' /><set value='".$m11."' /><set value='".$m12."' /><set value='".$ytd."' /><set value='".$goal."' />";
									$chart3 .= "</dataset>";
								}
								/* end chart 3 */
								
								/*CHART 4*/
								if($row['name_element'] == "Total Cost vs Budget")
								{
									//$chart4 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
									$chart4 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
									$chart4a .= "<set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' />";
								}
								
								/* end chart 4 */
								
								/*CHART 5*/
								if($row['name_element'] == "Production vs Budget (%)")
								{
									//$chart5 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
									$chart5 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
									$chart5a .= "<set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' />";
								}
								
								/* end chart 5 */
								
								/*CHART 6*/
								if($row['name_element'] == "Cost Per LB vs. Budget (%)")
								{
									$chart6 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 6 */
								
								/*CHART 7*/
								if($row['name_element'] == "Utilities Cost Per LB")
								{
									$chart7 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 7 */
								
								/*CHART 8*/
								if($row['name_element'] == "Material Efficiency")
								{
									$chart8 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 8 */
								
								/*CHART 9*/
								if($row['name_element'] == "Open Positions")
								{
									$chart9 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 9 */
								
								/*CHART 10*/
								if($row['name_element'] == "Total Headcount")
								{
									$chart10 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 10 */
								
								/*CHART 11*/
								if($row['name_element'] == "OT%")
								{
									$chart11 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 11 */
								
								/*CHART 12*/
								if($row['name_element'] == "OEE")
								{
									//$chart12 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' />";
									$chart12 .= "<dataset seriesName='".$n_version."'>";
									$chart12 .= "<set value='".$fy1."' /><set value='".$fy2."' /><set value='".$m01."' /><set value='".$m02."' /><set value='".$m03."' /><set value='".$m04."' /><set value='".$m05."' /><set value='".$m06."' /><set value='".$m07."' /><set value='".$m08."' /><set value='".$m09."' /><set value='".$m10."' /><set value='".$m11."' /><set value='".$m12."' />";
									$chart12 .= "</dataset>";
									
									$x_version = "Plan";
									if($n_version == "Plan")
									{
										$x_version = "Actual";
									}
									
									$chart12 .= "<dataset seriesName='".$x_version."'>";
									$chart12 .= "<set value='".$m01a."' /><set value='".$m02a."' /><set value='".$m03a."' /><set value='".$m04a."' /><set value='".$m05a."' /><set value='".$m06a."' /><set value='".$m07a."' /><set value='".$m08a."' /><set value='".$m09a."' /><set value='".$m10a."' /><set value='".$m11a."' /><set value='".$m12a."' />";
									$chart12 .= "</dataset>";
									
								}
								
								/* end chart 12 */
								
								/*CHART 13*/
								if($row['name_element'] == "Cycle Time")
								{
									//$chart13 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
									$chart13 .= "<dataset seriesName='".$n_version."'>";
									$chart13 .= "<set value='".$fy1."' /><set value='".$fy2."' /><set value='".$m01."' /><set value='".$m02."' /><set value='".$m03."' /><set value='".$m04."' /><set value='".$m05."' /><set value='".$m06."' /><set value='".$m07."' /><set value='".$m08."' /><set value='".$m09."' /><set value='".$m10."' /><set value='".$m11."' /><set value='".$m12."' />";
									$chart13 .= "</dataset>";
									
									$x_version = "Plan";
									if($n_version == "Plan")
									{
										$x_version = "Actual";
									}
									
									$chart13 .= "<dataset seriesName='".$x_version."'>";
									$chart13 .= "<set value='".$m01a."' /><set value='".$m02a."' /><set value='".$m03a."' /><set value='".$m04a."' /><set value='".$m05a."' /><set value='".$m06a."' /><set value='".$m07a."' /><set value='".$m08a."' /><set value='".$m09a."' /><set value='".$m10a."' /><set value='".$m11a."' /><set value='".$m12a."' />";
									$chart13 .= "</dataset>";
								}
								
								/* end chart 13 */
								
								/*CHART 14*/
								if($row['name_element'] == "Active Productivity Projects")
								{
									$chart14 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 14 */
								
								/*CHART 15*/
								if($row['name_element'] == "Projects Completed In Month")
								{
									$chart15 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 15 */
								
								/*CHART 16*/
								if($row['name_element'] == "Savings")
								{
									$chart16 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 16 */
								
								/*CHART 17*/
								if($row['name_element'] == "Lb/ManHr")
								{
									$chart17 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 17 */
								
								/*CHART 18*/
								if($row['name_element'] == "WO Overdue")
								{
									$chart18 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 18 */
								
								/*CHART 19*/
								if($row['name_element'] == "WO Completed")
								{
									$chart19 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 19 */
								
								/*CHART 20*/
								if($row['name_element'] == "WO Backlog")
								{
									$chart20 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 20 */
								
								/*CHART 21*/
								if($row['name_element'] == "Schedule Adherence")
								{
									$chart21 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 21 */
								
								/*CHART 22*/
								if($row['name_element'] == "OTIF")
								{
									$chart22 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 22 */
								
								/*CHART 23*/
								if($row['name_element'] == "Customer Complaints")
								{
									$chart23 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 23 */
								
								/*CHART 24*/
								if($row['name_element'] == "L1 Valtrack")
								{
									$chart24 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 24 */
								
								/*CHART 25*/
								if($row['name_element'] == "L2 Valtrack")
								{
									$chart25 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 25 */
								
								/*CHART 26*/
								if($row['name_element'] == "L3 Valtrack")
								{
									$chart26 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 26 */
								
								/*CHART 27*/
								if($row['name_element'] == "Overdue Quality Investigations")
								{
									$chart27 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 27 */
								
								/*CHART 28*/
								if($row['name_element'] == "Process Control WO Completed")
								{
									$chart28 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 28 */
								
								/*CHART 29*/
								if($row['name_element'] == "Process Control WO Backlog")
								{
									$chart29 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 29 */
								
								/*CHART 30*/
								if($row['name_element'] == "Scale Up Completed")
								{
									$chart30 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 30 */
								
								/*CHART 31*/
								if($row['name_element'] == "Formula Feedback Implemented")
								{
									$chart31 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 31 */
								
								/*CHART 32*/
								if($row['name_element'] == "Capital Projects")
								{
									$chart32 .= "<set label='FY".$nyear1."' value='".$fy1."' /><set label='FY".$nyear2."' value='".$fy2."' /><set label='Nov' value='".$m01."' /><set label='Dec'value='".$m02."' /><set label='Jan' value='".$m03."' /><set label='Feb' value='".$m04."' /><set label='Mar' value='".$m05."' /><set label='Apr' value='".$m06."' /><set label='May' value='".$m07."' /><set label='Jun' value='".$m08."' /><set label='Jul' value='".$m09."' /><set label='Aug' value='".$m10."' /><set label='Sep' value='".$m11."' /><set label='Oct' value='".$m12."' /><set label='YTD' value='".$ytd."' /><set label='Goal' value='".$goal."' />";
								}
								
								/* end chart 32 */
								
								
								// end chart generators
							}
					?>		
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>"  >
						<td class="label"><?php echo $depth."".$row['name_element']; ?></td>
						<td >
							<?php 
							//echo number_format($fy1, 3, ".", ","); 
							//echo $fy1;
							echo numformat($fy1, $row['name_element'], "y");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($fy2, 3, ".", ","); 
							//echo $fy2;
							echo numformat($fy2, $row['name_element'], "y");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($jan, 3, ".", ","); 
							//echo $m01;
							echo numformat($m01, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($feb, 3, ".", ","); 
							//echo $m02;
							echo numformat($m02, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($mar, 3, ".", ","); 
							//echo $m03;
							echo numformat($m03, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($apr, 3, ".", ","); 
							//echo $m04;
							echo numformat($m04, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($may, 3, ".", ","); 
							//echo $m05;
							echo numformat($m05, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($jun, 3, ".", ","); 
							//echo $m06;
							echo numformat($m06, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($jul, 3, ".", ","); 
							//echo $m07;
							echo numformat($m07, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($aug, 3, ".", ","); 
							//echo $m08;
							echo numformat($m08, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($sep, 3, ".", ","); 
							//echo $m09;
							echo numformat($m09, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($oct, 3, ".", ","); 
							//echo $m10;
							echo numformat($m10, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($nov, 3, ".", ","); 
							//echo $m11;
							echo numformat($m11, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($dec, 3, ".", ","); 
							//echo $m12;
							echo numformat($m12, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($ytd, 3, ".", ","); 
							//echo $ytd;
							echo numformat($ytd, $row['name_element'], "m");
							?>
						</td>
						<td >
							<?php 
							//echo number_format($goal, 3, ".", ","); 
							//echo $goal;
							echo numformat($goal, $row['name_element'], "y");
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
	// L1 events
	var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSColumn2D.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='L1 Events' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
    myChart1.render("chartContainer1");
    
    // L2 events
	var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSColumn2D.swf", "chartId_2", "600", "300", "0", "1");
    myChart2.setXMLData("<chart caption='L2 Events' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2; ?>"+"</chart>");
    myChart2.render("chartContainer2");
    
    // action items and moc
	var myChart3 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_3", "600", "300", "0", "1");
    myChart3.setXMLData("<chart caption='Action Items and MOCs' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart3; ?>"+"</chart>");
    myChart3.render("chartContainer3");
    
    // Total Cost vs Budget
    var myChart4 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_4", "600", "300", "0", "1");
    myChart4.setXMLData("<chart caption='Total Cost vs Budget' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart4; ?>"+"</chart>");
    myChart4.render("chartContainer4");
    
    var myChart4a = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_4a", "600", "300", "0", "1");
    myChart4a.setXMLData("<chart caption='Total Cost vs Budget - Months' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart4a; ?>"+"</chart>");
    myChart4a.render("chartContainer4a");
    
    // Production vs Budget (%)
    var myChart5 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_5", "600", "300", "0", "1");
    myChart5.setXMLData("<chart caption='Production vs Budget (%)' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart5; ?>"+"</chart>");
    myChart5.render("chartContainer5");
    
    var myChart5a = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_5a", "600", "300", "0", "1");
    myChart5a.setXMLData("<chart caption='Production vs Budget (%) - Months' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart5a; ?>"+"</chart>");
    myChart5a.render("chartContainer5a");
    
    // Cost Per LB vs. Budget (%)
    var myChart6 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_6", "600", "300", "0", "1");
    myChart6.setXMLData("<chart caption='Cost Per LB vs. Budget (%)' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart6; ?>"+"</chart>");
    myChart6.render("chartContainer6");
    
    // Utilities Cost Per LB
    var myChart7 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_7", "600", "300", "0", "1");
    myChart7.setXMLData("<chart caption='Utilities Cost Per LB' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart7; ?>"+"</chart>");
    myChart7.render("chartContainer7");
    
    // Material Efficiency
    var myChart8 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_8", "600", "300", "0", "1");
    myChart8.setXMLData("<chart caption='Material Efficiency' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart8; ?>"+"</chart>");
    myChart8.render("chartContainer8");
    
    // Open Positions
    var myChart9 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_9", "600", "300", "0", "1");
    myChart9.setXMLData("<chart caption='Open Positions' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart9; ?>"+"</chart>");
    myChart9.render("chartContainer9");
    
    // Total Headcount
    var myChart10 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_10", "600", "300", "0", "1");
    myChart10.setXMLData("<chart caption='Total Headcount' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart10; ?>"+"</chart>");
    myChart10.render("chartContainer10");
    
    // OT%
    var myChart11 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_11", "600", "300", "0", "1");
    myChart11.setXMLData("<chart caption='OT%' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart11; ?>"+"</chart>");
    myChart11.render("chartContainer11");
    
    // OEE
    var myChart12 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_12", "600", "300", "0", "1");
    myChart12.setXMLData("<chart caption='OEE' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart12; ?>"+"</chart>");
    myChart12.render("chartContainer12");
    
    // Cycle Time
    var myChart13 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_13", "600", "300", "0", "1");
    myChart13.setXMLData("<chart caption='Cycle Time' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart13; ?>"+"</chart>");
    myChart13.render("chartContainer13");
    
    // Active Productivity Projects
    var myChart14 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_14", "600", "300", "0", "1");
    myChart14.setXMLData("<chart caption='Active Productivity Projects' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart14; ?>"+"</chart>");
    myChart14.render("chartContainer14");
    
    // Projects Completed In Month
    var myChart15 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_15", "600", "300", "0", "1");
    myChart15.setXMLData("<chart caption='Projects Completed In Month' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart15; ?>"+"</chart>");
    myChart15.render("chartContainer15");
    
    // Savings
    var myChart16 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_16", "600", "300", "0", "1");
    myChart16.setXMLData("<chart caption='Savings' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart16; ?>"+"</chart>");
    myChart16.render("chartContainer16");
    
    // Lb/ManHr
    var myChart17 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_17", "600", "300", "0", "1");
    myChart17.setXMLData("<chart caption='Lb/ManHr' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart17; ?>"+"</chart>");
    myChart17.render("chartContainer17");
    
    // WO Overdue
    var myChart18 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_18", "600", "300", "0", "1");
    myChart18.setXMLData("<chart caption='WO Overdue' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart18; ?>"+"</chart>");
    myChart18.render("chartContainer18");
    
    // WO Completed
    var myChart19 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_19", "600", "300", "0", "1");
    myChart19.setXMLData("<chart caption='WO Completed' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart19; ?>"+"</chart>");
    myChart19.render("chartContainer19");
    
    // WO Backlog
    var myChart20 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_20", "600", "300", "0", "1");
    myChart20.setXMLData("<chart caption='WO Backlog' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart20; ?>"+"</chart>");
    myChart20.render("chartContainer20");
    
    // Schedule Adherence
    var myChart21 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_21", "600", "300", "0", "1");
    myChart21.setXMLData("<chart caption='Schedule Adherence' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart21; ?>"+"</chart>");
    myChart21.render("chartContainer21");
    
    // OTIF
    var myChart22 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_22", "600", "300", "0", "1");
    myChart22.setXMLData("<chart caption='OTIF' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart22; ?>"+"</chart>");
    myChart22.render("chartContainer22");
    
    // Customer Complaints
    var myChart23 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_23", "600", "300", "0", "1");
    myChart23.setXMLData("<chart caption='Customer Complaints' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart23; ?>"+"</chart>");
    myChart23.render("chartContainer23");
    
    // L1 Valtrack
    var myChart24 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_24", "600", "300", "0", "1");
    myChart24.setXMLData("<chart caption='L1 Valtrack' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart24; ?>"+"</chart>");
    myChart24.render("chartContainer24");
    
    // L2 Valtrack
    var myChart25 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_25", "600", "300", "0", "1");
    myChart25.setXMLData("<chart caption='L2 Valtrack' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart25; ?>"+"</chart>");
    myChart25.render("chartContainer25");
    
    // L3 Valtrack
    var myChart26 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_26", "600", "300", "0", "1");
    myChart26.setXMLData("<chart caption='L3 Valtrack' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart26; ?>"+"</chart>");
    myChart26.render("chartContainer26");
    
    // Overdue Quality Investigations
    var myChart27 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_27", "600", "300", "0", "1");
    myChart27.setXMLData("<chart caption='Overdue Quality Investigations' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart27; ?>"+"</chart>");
    myChart27.render("chartContainer27");
    
    // Process Control WO Completed
    var myChart28 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_28", "600", "300", "0", "1");
    myChart28.setXMLData("<chart caption='Process Control WO Completed' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart28; ?>"+"</chart>");
    myChart28.render("chartContainer28");
    
    // Process Control WO Backlog
    var myChart29 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_29", "600", "300", "0", "1");
    myChart29.setXMLData("<chart caption='Process Control WO Backlog' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart29; ?>"+"</chart>");
    myChart29.render("chartContainer29");
    
    // Scale Up Completed
    var myChart30 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_30", "600", "300", "0", "1");
    myChart30.setXMLData("<chart caption='Scale Up Completed' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart30; ?>"+"</chart>");
    myChart30.render("chartContainer30");
    
    // Formula Feedback Implemented
    var myChart31 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_31", "600", "300", "0", "1");
    myChart31.setXMLData("<chart caption='Formula Feedback Implemented' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart31; ?>"+"</chart>");
    myChart31.render("chartContainer31");
    
    // Capital Projects
    var myChart32 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_32", "600", "300", "0", "1");
    myChart32.setXMLData("<chart caption='Capital Projects' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart32; ?>"+"</chart>");
    myChart32.render("chartContainer32");
    
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