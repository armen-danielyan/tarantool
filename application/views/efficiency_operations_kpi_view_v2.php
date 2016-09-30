<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Efficiency Operations KPI</title>
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
        active: 0
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
function pinme(name, url, link, pinid)
{
	//alert(chart_name+chart_url+chart_link);
	$.post("<?php echo site_url('home/pin_chart') ?>", { chart_name: name, chart_url: url, chart_link: link },
		function(data) {
			//alert(name+" is now pinned to your home.");
			$('#pnchart').html(name);
			$( "#dialog-message" ).dialog({
				resizable: false,
				modal: true,
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
						}
				}
			});
			document.getElementById(pinid).style.display = "none";
		}
	);
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
<?php

// native function




?>
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('efficiency_operations_kpi_v2'); ?>">
                        
                        <div class="filter_div">
                            
                            <select name="version" onchange="this.form.submit();" class="ddown1" title="Select Version">
                            <?php
                                foreach($form_version as $row)
                                {
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($version == $row['element']){ $n_version = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        
                        
                        <div class="filter_div">
                            
                            <select name="Man_KPI_Value" onchange="this.form.submit();" class="ddown1" title="Select KPI">
                            <?php
                                foreach($form_Man_KPI_Value as $row)
                                {
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($Man_KPI_Value == $row['element']){ $n_Man_KPI_Value = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
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
                                <?php 
                                	//echo mailto("?to=&subject=&body=".site_url('efficiency_operations')."/info/".$n_year."/".url_title($n_month, '_')."/".url_title($n_day, '_')."/".url_title($n_shift, '_')."/".url_title($n_version, '_')."/".url_title($n_receiver, '_')."", "<span class='ui-icon ui-icon-mail-closed left' ></span>", array("title" =>"Share this page via email")); 
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Operations KPI</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
                <?php 
                if($this->jedox->page_permission($jedox_user_details['group_names'], "efficiency_operations_details")) // need to be adjusted later
                {
                    //echo anchor("efficiency_operations_details/info/".$n_year."/".url_title($n_month, '_')."/".url_title($n_day, '_')."/".url_title($n_shift, '_')."/".url_title($n_version, '_')."/".url_title($n_receiver, '_')."", "<span class='ui-icon ui-icon-search right' ></span>", array('title' => 'View Efficiency Operations Details')); 
                }
                ?>
            </div>
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Selected KPI - Monthly (Rolling Year)</h3>
                    <table>
                    	
                    	<tr>
                    		<td>
                    			<div id="chartContainer1" >chart1 here</div>
                    		</td>
                    	</tr>
                    </table>
                </div>
                <div class="tabbertab">
                	<h3>KPI by Production Line - Monthly (Rolling Year)</h3>
                	<table>
                		<tr>
                    		<td >
								<div class="filter_div">
		                            <select name="receiver" onchange="this.form.submit();" class="ddown1" title="Select Receiver">
		                            <?php
		                                foreach($form_receiver as $row)
		                                {
		                                    $depth = '';
		                                    for($i=0; $i<$row['depth']; $i++)
		                                    {
		                                        $depth .= '&nbsp;&nbsp;';
		                                    }
		                            ?>  
		                                <option value="<?php echo $row['element']; ?>" <?php if($receiver == $row['element']){ $n_receiver = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
		                            <?php   
		                                }
		                            ?>
		                            </select>
		                        </div>
							</td>
                    	</tr>
                    	<tr>
                    		<td>
		                        <div id="chartContainer4" >chart4 here</div>
                    		</td>
                    	</tr>
                	</table>
                </div>
                <div class="tabbertab">
                    <h3>Detailed Plan/Actual for Selected Line</h3>
                    <table>
                    	<tr>
                    		<td >
								<div class="filter_div">
		                            <select name="receiver2" onchange="this.form.submit();" class="ddown1" title="Select Receiver">
		                            <?php
		                                foreach($form_receiver as $row)
		                                {
		                                    $depth = '';
		                                    for($i=0; $i<$row['depth']; $i++)
		                                    {
		                                        $depth .= '&nbsp;&nbsp;';
		                                    }
		                            ?>  
		                                <option value="<?php echo $row['element']; ?>" <?php if($receiver2 == $row['element']){ $n_receiver2 = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
		                            <?php   
		                                }
		                            ?>
		                            </select>
		                        </div>
							</td>
							<td class="text_right">
								&nbsp;
							</td>
                    	</tr>
                    	<tr>
                    		<td>
                    			<div id="chartContainer2" >chart2 here. </div>
                    		</td>
                    		<td>
                    			<div id="chartContainer5" >chart5 here. </div>
                    		</td>
                    	</tr>
                    	<tr>
                    		<td>
                    			<div class="filter_div">
		                            <select name="Man_KPI_Value2" onchange="this.form.submit();" class="ddown1" title="Select KPI">
		                            <?php
		                                foreach($form_Man_KPI_Value as $row)
		                                {
		                                    $depth = '';
		                                    for($i=0; $i<$row['depth']; $i++)
		                                    {
		                                        $depth .= '&nbsp;&nbsp;';
		                                    }
		                            ?>  
		                                <option value="<?php echo $row['element']; ?>" <?php if($Man_KPI_Value2 == $row['element']){ $n_Man_KPI_Value2 = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
		                            <?php   
		                                }
		                            ?>
		                            </select>
		                        </div>
                    		</td>
                    		<td>
                    			<div class="filter_div">
		                            <select name="Man_KPI_Value3" onchange="this.form.submit();" class="ddown1" title="Select KPI">
		                            <?php
		                                foreach($form_Man_KPI_Value as $row)
		                                {
		                                    $depth = '';
		                                    for($i=0; $i<$row['depth']; $i++)
		                                    {
		                                        $depth .= '&nbsp;&nbsp;';
		                                    }
		                            ?>  
		                                <option value="<?php echo $row['element']; ?>" <?php if($Man_KPI_Value3 == $row['element']){ $n_Man_KPI_Value3 = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
		                            <?php   
		                                }
		                            ?>
		                            </select>
		                        </div>
                    		</td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Selected KPI - Last 4 Month Comparison</h3>
                    <table>
                    	
                    	<tr>
                    		<td><div id="chartContainer3" >chart3 here</div></td>
                    	</tr>
                    </table>
                    
                </div>
                
            </div>
            
            <div class="content_div">
                <?php
                	$chart1xml = '';
					$chart2xml = '';
					$chart3xml = '';
					$chart4xml = '';
					$chart5xml = '';
                	$dt = '';
                	$thisday = date("d");
					if (($thisday == 29) || ($thisday == 30) || ($thisday == 31)){
					     $dt = " -3 days";
					}
                	// buggy date decrement. added fix to compensate exact month decrement when day lands on 29,30,31.
                	$dmonth1 = mdate("%Y%m", strtotime("-12 months".$dt));
					$dmonth2 = mdate("%Y%m", strtotime("-11 months".$dt));
					$dmonth3 = mdate("%Y%m", strtotime("-10 months".$dt));
					$dmonth4 = mdate("%Y%m", strtotime("-9 months".$dt));
					$dmonth5 = mdate("%Y%m", strtotime("-8 months".$dt));
					$dmonth6 = mdate("%Y%m", strtotime("-7 months".$dt));
					$dmonth7 = mdate("%Y%m", strtotime("-6 months".$dt));
					$dmonth8 = mdate("%Y%m", strtotime("-5 months".$dt));
					$dmonth9 = mdate("%Y%m", strtotime("-4 months".$dt));
					$dmonth10 = mdate("%Y%m", strtotime("-3 months".$dt));
					$dmonth11 = mdate("%Y%m", strtotime("-2 month".$dt));
					$dmonth12 = mdate("%Y%m", strtotime("-1 month".$dt));
                ?>
                <table id="operationsdata" class="avtable_2">
                    <tbody>
                    	<tr>
                    		<td>&nbsp;</td>
                    		<td class="thead"><?php echo $dmonth1; ?></td>
                    		<td class="thead"><?php echo $dmonth2; ?></td>
                    		<td class="thead"><?php echo $dmonth3; ?></td>
                    		<td class="thead"><?php echo $dmonth4; ?></td>
                    		<td class="thead"><?php echo $dmonth5; ?></td>
                    		<td class="thead"><?php echo $dmonth6; ?></td>
                    		<td class="thead"><?php echo $dmonth7; ?></td>
                    		<td class="thead"><?php echo $dmonth8; ?></td>
                    		<td class="thead"><?php echo $dmonth9; ?></td>
                    		<td class="thead"><?php echo $dmonth10; ?></td>
                    		<td class="thead"><?php echo $dmonth11; ?></td>
                    		<td class="thead"><?php echo $dmonth12; ?></td>
                    		<td class="thead">Fiscal Year 
                    			<select name="date_fyear" onchange="this.form.submit();" title="Select fiscal year">
	                            <?php
	                                foreach($form_date_fyear as $row)
	                                {
	                                    $depth = '';
	                                    for($i=0; $i<$row['depth']; $i++)
	                                    {
	                                        $depth .= '&nbsp;&nbsp;';
	                                    }
	                            ?>  
	                                <option value="<?php echo $row['element']; ?>" <?php if($date_fyear == $row['element']){ $n_date_fyear = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
	                            <?php   
	                                }
	                            ?>
	                            </select>
                    		</td>
                    		<td class="thead">12 Month Rolling Total</td>
                    	</tr>
                    	
                		<?php
                			$chart1xml .= "<categories>";
							$chart2xml .= "<categories>";
							$chart3xml .= "<categories>";
							$chart4xml .= "<categories>";
							$chart5xml .= "<categories>";
							
							$y_id1 = $this->jedoxapi->get_area($date_elements, $dmonth1);
							$chart1xml .= "<category label='".$dmonth1."' />";
							$chart2xml .= "<category label='".$dmonth1."' />";
							$chart4xml .= "<category label='".$dmonth1."' />";
							$chart5xml .= "<category label='".$dmonth1."' />";
							
							
							$y_id2 = $this->jedoxapi->get_area($date_elements, $dmonth2);
							$chart1xml .= "<category label='".$dmonth2."' />";
							$chart2xml .= "<category label='".$dmonth2."' />";
							$chart4xml .= "<category label='".$dmonth2."' />";
							$chart5xml .= "<category label='".$dmonth2."' />";
							
							
							$y_id3 = $this->jedoxapi->get_area($date_elements, $dmonth3);
							$chart1xml .= "<category label='".$dmonth3."' />";
							$chart2xml .= "<category label='".$dmonth3."' />";
							$chart4xml .= "<category label='".$dmonth3."' />";
							$chart5xml .= "<category label='".$dmonth3."' />";
							
							
							$y_id4 = $this->jedoxapi->get_area($date_elements, $dmonth4);
							$chart1xml .= "<category label='".$dmonth4."' />";
							$chart2xml .= "<category label='".$dmonth4."' />";
							$chart4xml .= "<category label='".$dmonth4."' />";
							$chart5xml .= "<category label='".$dmonth4."' />";
							
							
							$y_id5 = $this->jedoxapi->get_area($date_elements, $dmonth5);
							$chart1xml .= "<category label='".$dmonth5."' />";
							$chart2xml .= "<category label='".$dmonth5."' />";
							$chart4xml .= "<category label='".$dmonth5."' />";
							$chart5xml .= "<category label='".$dmonth5."' />";
							
							
							$y_id6 = $this->jedoxapi->get_area($date_elements, $dmonth6);
							$chart1xml .= "<category label='".$dmonth6."' />";
							$chart2xml .= "<category label='".$dmonth6."' />";
							$chart4xml .= "<category label='".$dmonth6."' />";
							$chart5xml .= "<category label='".$dmonth6."' />";
							
							
							$y_id7 = $this->jedoxapi->get_area($date_elements, $dmonth7);
							$chart1xml .= "<category label='".$dmonth7."' />";
							$chart2xml .= "<category label='".$dmonth7."' />";
							$chart4xml .= "<category label='".$dmonth7."' />";
							$chart5xml .= "<category label='".$dmonth7."' />";
							
							
							$y_id8 = $this->jedoxapi->get_area($date_elements, $dmonth8);
							$chart1xml .= "<category label='".$dmonth8."' />";
							$chart2xml .= "<category label='".$dmonth8."' />";
							$chart4xml .= "<category label='".$dmonth8."' />";
							$chart5xml .= "<category label='".$dmonth8."' />";
							
							
							$y_id9 = $this->jedoxapi->get_area($date_elements, $dmonth9);
							$chart1xml .= "<category label='".$dmonth9."' />";
							$chart2xml .= "<category label='".$dmonth9."' />";
							$chart3xml .= "<category label='".$dmonth9."' />";
							$chart4xml .= "<category label='".$dmonth9."' />";
							$chart5xml .= "<category label='".$dmonth9."' />";
							
							
							$y_id10 = $this->jedoxapi->get_area($date_elements, $dmonth10);
							$chart1xml .= "<category label='".$dmonth10."' />";
							$chart2xml .= "<category label='".$dmonth10."' />";
							$chart3xml .= "<category label='".$dmonth10."' />";
							$chart4xml .= "<category label='".$dmonth10."' />";
							$chart5xml .= "<category label='".$dmonth10."' />";
							
							
							$y_id11 = $this->jedoxapi->get_area($date_elements, $dmonth11);
							$chart1xml .= "<category label='".$dmonth11."' />";
							$chart2xml .= "<category label='".$dmonth11."' />";
							$chart3xml .= "<category label='".$dmonth11."' />";
							$chart4xml .= "<category label='".$dmonth11."' />";
							$chart5xml .= "<category label='".$dmonth11."' />";
							
							
							$y_id12 = $this->jedoxapi->get_area($date_elements, $dmonth12);
                			$chart1xml .= "<category label='".$dmonth12."' />";
							$chart2xml .= "<category label='".$dmonth12."' />";
							$chart3xml .= "<category label='".$dmonth12."' />";
							$chart4xml .= "<category label='".$dmonth12."' />";
							$chart5xml .= "<category label='".$dmonth12."' />";
							
							$chart1xml .= "</categories>";
							$chart2xml .= "</categories>";
							$chart3xml .= "</categories>";
							$chart4xml .= "</categories>";
							$chart5xml .= "</categories>";
							//chart2 generate
							
							foreach ($form_version as $fdatarow)
							{
								$chart2xml .= "<dataset seriesName='".$fdatarow['name_element']."'>";
								$b1 = 0;
								$b2 = 0;
								$b3 = 0;
								$b4 = 0;
								$b5 = 0;
								$b6 = 0;
								$b7 = 0;
								$b8 = 0;
								$b9 = 0;
								$b10 = 0;
								$b11 = 0;
								$b12 = 0;
								
								foreach ($chart2_data as $fvals)
								{
									$fpaths = explode(",", $fvals['path']);
									if($fpaths[2] == $y_id1 && $fpaths[0] == $fdatarow['element'])
									{
										$b1 = $fvals['value'];
									}
									if($fpaths[2] == $y_id2 && $fpaths[0] == $fdatarow['element'])
									{
										$b2 = $fvals['value'];
									}
									if($fpaths[2] == $y_id3 && $fpaths[0] == $fdatarow['element'])
									{
										$b3 = $fvals['value'];
									}
									if($fpaths[2] == $y_id4 && $fpaths[0] == $fdatarow['element'])
									{
										$b4 = $fvals['value'];
									}
									if($fpaths[2] == $y_id5 && $fpaths[0] == $fdatarow['element'])
									{
										$b5 = $fvals['value'];
									}
									if($fpaths[2] == $y_id6 && $fpaths[0] == $fdatarow['element'])
									{
										$b6 = $fvals['value'];
									}
									if($fpaths[2] == $y_id7 && $fpaths[0] == $fdatarow['element'])
									{
										$b7 = $fvals['value'];
									}
									if($fpaths[2] == $y_id8 && $fpaths[0] == $fdatarow['element'])
									{
										$b8 = $fvals['value'];
									}
									if($fpaths[2] == $y_id9 && $fpaths[0] == $fdatarow['element'])
									{
										$b9 = $fvals['value'];
									}
									if($fpaths[2] == $y_id10 && $fpaths[0] == $fdatarow['element'])
									{
										$b10 = $fvals['value'];
									}
									if($fpaths[2] == $y_id11 && $fpaths[0] == $fdatarow['element'])
									{
										$b11 = $fvals['value'];
									}
									if($fpaths[2] == $y_id12 && $fpaths[0] == $fdatarow['element'])
									{
										$b12 = $fvals['value'];
									}
									
								}
								$chart2xml .= "<set value='".$b1."' />";
								$chart2xml .= "<set value='".$b2."' />";
								$chart2xml .= "<set value='".$b3."' />";
								$chart2xml .= "<set value='".$b4."' />";
								$chart2xml .= "<set value='".$b5."' />";
								$chart2xml .= "<set value='".$b6."' />";
								$chart2xml .= "<set value='".$b7."' />";
								$chart2xml .= "<set value='".$b8."' />";
								$chart2xml .= "<set value='".$b9."' />";
								$chart2xml .= "<set value='".$b10."' />";
								$chart2xml .= "<set value='".$b11."' />";
								$chart2xml .= "<set value='".$b12."' />";
								$chart2xml .= "</dataset>";
							}
							
							 
							//chart4 genarate
							foreach($Man_KPI_Value_all_alias as $hdatarow)
							{
								$d1 = 0;
								$d2 = 0;
								$d3 = 0;
								$d4 = 0;
								$d5 = 0;
								$d6 = 0;
								$d7 = 0;
								$d8 = 0;
								$d9 = 0;
								$d10 = 0;
								$d11 = 0;
								$d12 = 0;
								
								$chart4xml .= "<dataset seriesName='".$hdatarow['name_element']."'>";
								
								foreach($chart4_data as $c4vals)
								{
									$c4paths = explode(",", $c4vals['path']);
									if($c4paths[2] == $y_id1 && $c4paths[3] == $hdatarow['element'])
									{
										$d1 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id2 && $c4paths[3] == $hdatarow['element'])
									{
										$d2 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id3 && $c4paths[3] == $hdatarow['element'])
									{
										$d3 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id4 && $c4paths[3] == $hdatarow['element'])
									{
										$d4 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id5 && $c4paths[3] == $hdatarow['element'])
									{
										$d5 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id6 && $c4paths[3] == $hdatarow['element'])
									{
										$d6 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id7 && $c4paths[3] == $hdatarow['element'])
									{
										$d7 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id8 && $c4paths[3] == $hdatarow['element'])
									{
										$d8 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id9 && $c4paths[3] == $hdatarow['element'])
									{
										$d9 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id10 && $c4paths[3] == $hdatarow['element'])
									{
										$d10 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id11 && $c4paths[3] == $hdatarow['element'])
									{
										$d11 = $c4vals['value'];
									}
									if($c4paths[2] == $y_id12 && $c4paths[3] == $hdatarow['element'])
									{
										$d12 = $c4vals['value'];
									}
									
								}
								$chart4xml .= "<set value='".$d1."' />";
								$chart4xml .= "<set value='".$d2."' />";
								$chart4xml .= "<set value='".$d3."' />";
								$chart4xml .= "<set value='".$d4."' />";
								$chart4xml .= "<set value='".$d5."' />";
								$chart4xml .= "<set value='".$d6."' />";
								$chart4xml .= "<set value='".$d7."' />";
								$chart4xml .= "<set value='".$d8."' />";
								$chart4xml .= "<set value='".$d9."' />";
								$chart4xml .= "<set value='".$d10."' />";
								$chart4xml .= "<set value='".$d11."' />";
								$chart4xml .= "<set value='".$d12."' />";
								$chart4xml .= "</dataset>";
							}
							
							
							//chart5 generate
							foreach ($form_version as $gdatarow)
							{
								$chart5xml .= "<dataset seriesName='".$gdatarow['name_element']."'>";
								$c1 = 0;
								$c2 = 0;
								$c3 = 0;
								$c4 = 0;
								$c5 = 0;
								$c6 = 0;
								$c7 = 0;
								$c8 = 0;
								$c9 = 0;
								$c10 = 0;
								$c11 = 0;
								$c12 = 0;
								
								foreach ($chart5_data as $gvals)
								{
									$gpaths = explode(",", $gvals['path']);
									if($gpaths[2] == $y_id1 && $gpaths[0] == $gdatarow['element'])
									{
										$c1 = $gvals['value'];
									}
									if($gpaths[2] == $y_id2 && $gpaths[0] == $gdatarow['element'])
									{
										$c2 = $gvals['value'];
									}
									if($gpaths[2] == $y_id3 && $gpaths[0] == $gdatarow['element'])
									{
										$c3 = $gvals['value'];
									}
									if($gpaths[2] == $y_id4 && $gpaths[0] == $gdatarow['element'])
									{
										$c4 = $gvals['value'];
									}
									if($gpaths[2] == $y_id5 && $gpaths[0] == $gdatarow['element'])
									{
										$c5 = $gvals['value'];
									}
									if($gpaths[2] == $y_id6 && $gpaths[0] == $gdatarow['element'])
									{
										$c6 = $gvals['value'];
									}
									if($gpaths[2] == $y_id7 && $gpaths[0] == $gdatarow['element'])
									{
										$c7 = $gvals['value'];
									}
									if($gpaths[2] == $y_id8 && $gpaths[0] == $gdatarow['element'])
									{
										$c8 = $gvals['value'];
									}
									if($gpaths[2] == $y_id9 && $gpaths[0] == $gdatarow['element'])
									{
										$c9 = $gvals['value'];
									}
									if($gpaths[2] == $y_id10 && $gpaths[0] == $gdatarow['element'])
									{
										$c10 = $gvals['value'];
									}
									if($gpaths[2] == $y_id11 && $gpaths[0] == $gdatarow['element'])
									{
										$c11 = $gvals['value'];
									}
									if($gpaths[2] == $y_id12 && $gpaths[0] == $gdatarow['element'])
									{
										$c12 = $gvals['value'];
									}
									
								}
								$chart5xml .= "<set value='".$c1."' />";
								$chart5xml .= "<set value='".$c2."' />";
								$chart5xml .= "<set value='".$c3."' />";
								$chart5xml .= "<set value='".$c4."' />";
								$chart5xml .= "<set value='".$c5."' />";
								$chart5xml .= "<set value='".$c6."' />";
								$chart5xml .= "<set value='".$c7."' />";
								$chart5xml .= "<set value='".$c8."' />";
								$chart5xml .= "<set value='".$c9."' />";
								$chart5xml .= "<set value='".$c10."' />";
								$chart5xml .= "<set value='".$c11."' />";
								$chart5xml .= "<set value='".$c12."' />";
								$chart5xml .= "</dataset>";
							}
							
							// table data display and chart1
                			foreach($receiver_filtered as $datarow)
                			{
                				$a1 = 0;
								$a2 = 0;
								$a3 = 0;
								$a4 = 0;
								$a5 = 0;
								$a6 = 0;
								$a7 = 0;
								$a8 = 0;
								$a9 = 0;
								$a10 = 0;
								$a11 = 0;
								$a12 = 0;
								$afiscal = 0;
								$atot = 0;
								
                				$chart1xml .= "<dataset seriesName='".$datarow['name_element']."'>";
								$chart3xml .= "<dataset seriesName='".$datarow['name_element']."'>";
								
								foreach($table_data as $tvals)
								{
									$paths = explode(",", $tvals['path']);
									if($paths[2] == $y_id1 && $paths[1] == $datarow['element'])
									{
										$a1 = $tvals['value'];
									}
									if($paths[2] == $y_id2 && $paths[1] == $datarow['element'])
									{
										$a2 = $tvals['value'];
									}
									if($paths[2] == $y_id3 && $paths[1] == $datarow['element'])
									{
										$a3 = $tvals['value'];
									}
									if($paths[2] == $y_id4 && $paths[1] == $datarow['element'])
									{
										$a4 = $tvals['value'];
									}
									if($paths[2] == $y_id5 && $paths[1] == $datarow['element'])
									{
										$a5 = $tvals['value'];
									}
									if($paths[2] == $y_id6 && $paths[1] == $datarow['element'])
									{
										$a6 = $tvals['value'];
									}
									if($paths[2] == $y_id7 && $paths[1] == $datarow['element'])
									{
										$a7 = $tvals['value'];
									}
									if($paths[2] == $y_id8 && $paths[1] == $datarow['element'])
									{
										$a8 = $tvals['value'];
									}
									if($paths[2] == $y_id9 && $paths[1] == $datarow['element'])
									{
										$a9 = $tvals['value'];
									}
									if($paths[2] == $y_id10 && $paths[1] == $datarow['element'])
									{
										$a10 = $tvals['value'];
									}
									if($paths[2] == $y_id11 && $paths[1] == $datarow['element'])
									{
										$a11 = $tvals['value'];
									}
									if($paths[2] == $y_id12 && $paths[1] == $datarow['element'])
									{
										$a12 = $tvals['value'];
									}
								}
								
								foreach($fiscal_year_data as $fiscal)
								{
									$fpath = explode(",", $fiscal['path']);
									if($fpath[1] == $datarow['element'])
									{
										$afiscal = $fiscal['value'];
									}
									
								}
								
								$chart1xml .= "<set value='".$a1."' />";
								$chart1xml .= "<set value='".$a2."' />";
								$chart1xml .= "<set value='".$a3."' />";
								$chart1xml .= "<set value='".$a4."' />";
								$chart1xml .= "<set value='".$a5."' />";
								$chart1xml .= "<set value='".$a6."' />";
								$chart1xml .= "<set value='".$a7."' />";
								$chart1xml .= "<set value='".$a8."' />";
								$chart1xml .= "<set value='".$a9."' />";
								$chart1xml .= "<set value='".$a10."' />";
								$chart1xml .= "<set value='".$a11."' />";
								$chart1xml .= "<set value='".$a12."' />";
								$chart1xml .= "</dataset>";
								$atot = $a1+$a2+$a3+$a4+$a5+$a6+$a7+$a8+$a9+$a12;
								
								$chart3xml .= "<set value='".$a9."' />";
								$chart3xml .= "<set value='".$a10."' />";
								$chart3xml .= "<set value='".$a11."' />";
								$chart3xml .= "<set value='".$a12."' />";
								$chart3xml .= "</dataset>";
								
						?>
						<tr class="tmain">
							<td class="label"><?php echo $datarow['name_element']; ?></td>
							<td><?php echo number_format($a1, 2, ".", ","); ?></td>
							<td><?php echo number_format($a2, 2, ".", ","); ?></td>
							<td><?php echo number_format($a3, 2, ".", ","); ?></td>
							<td><?php echo number_format($a4, 2, ".", ","); ?></td>
							<td><?php echo number_format($a5, 2, ".", ","); ?></td>
							<td><?php echo number_format($a6, 2, ".", ","); ?></td>
							<td><?php echo number_format($a7, 2, ".", ","); ?></td>
							<td><?php echo number_format($a8, 2, ".", ","); ?></td>
							<td><?php echo number_format($a9, 2, ".", ","); ?></td>
							<td><?php echo number_format($a10, 2, ".", ","); ?></td>
							<td><?php echo number_format($a11, 2, ".", ","); ?></td>
							<td><?php echo number_format($a12, 2, ".", ","); ?></td>
							<td><?php echo number_format($afiscal, 2, ".", ","); ?></td>
							<td><?php echo number_format($atot, 2, ".", ","); ?></td>
						</tr>
						<?php			
							}
                		?>
                    	
                    </tbody>
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
var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_1", "600", "450", "0", "1");
myChart1.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1xml; ?>"+"</chart>");
myChart1.render("chartContainer1");

var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_2", "600", "300", "0", "1");
myChart2.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2xml; ?>"+"</chart>");
myChart2.render("chartContainer2");

var myChart3 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSColumn2D.swf", "chartId_3", "600", "450", "0", "1");
myChart3.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart3xml; ?>"+"</chart>");
myChart3.render("chartContainer3");

var myChart4 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_4", "600", "450", "0", "1");
myChart4.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart4xml; ?>"+"</chart>");
myChart4.render("chartContainer4");

var myChart5 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_5", "600", "300", "0", "1");
myChart5.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart5xml; ?>"+"</chart>");
myChart5.render("chartContainer5");

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
<div id="dialog-message" title="Chart Pinned" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
		<span id="pnchart"></span> now pinned to your home.
	</p>
</div>
</body>
</html>