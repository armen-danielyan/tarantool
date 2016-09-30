<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Efficiency Operations</title>
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
    
    $('#tb1').remove().insertAfter($('#data_tables'));
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('efficiency_operations_v2'); ?>">
                        
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
                                <option value="<?php echo $row['element']; ?>" <?php if($version == $row['element']){ $n_version = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        <div class="filter_div">
                            
                            <select name="year_month_time" onchange="this.form.submit();" class="ddown1" title="Select Date">
                            <?php
                                foreach($form_year_month_time as $row)
                                {
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($year_month_time == $row['element']){ $n_year_month_time = $row['name_element']; ?>selected="selected"<?php } ?> <?php if($row['number_children'] > 0){	echo "disabled"; } ?>><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        <div class="filter_div">
                            
                            <select name="shift" onchange="this.form.submit();" class="ddown1" title="Select Shift">
                            <?php
                                foreach($form_shift as $row)
                                {
                                    $depth = '';
                                    for($i=1; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;'; 
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($shift == $row['element']){ $n_shift = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        <div class="filter_div">
                            <select name="equipment" onchange="this.form.submit();" class="ddown1" title="Select Equipment">
                            <?php
                                foreach($form_equipment as $row)
                                {
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($equipment == $row['element']){ $n_equipment = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
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
                    </form>
                </div>
            </div>
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Operations</span> > ".anchor('efficiency_operations_details_v2', 'Details', array('title' => 'View Efficiency Operations Details'));
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
                <?php 
                if($this->jedox->page_permission($jedox_user_details['group_names'], "efficiency_operations_details"))
                {
                    //echo anchor("efficiency_operations_details_v2", "<span class='ui-icon ui-icon-search right' ></span>", array('title' => 'View Efficiency Operations Details')); 
                }
                ?>
            </div>
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Produced Quantity (Six Day Movement)</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" ></div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Rejected Quantity (Six Day Movement)</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer2" ></div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Downtime (Six Day Movement)</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer3" ></div></td>
                    	</tr>
                    </table>
                </div>
                
            </div>
            
            <div class="content_div" >
                <div class="clearfix" id="data_tables"></div>
                <table id="tb2" class="avtable_2">
                	<tr>
						<td>&nbsp;</td>
                    	<td class="thead">Total QTY</td>
						<td class="thead">Rejected Quantity</td>
						<td class="thead">First Pass</td>
						<td class="thead">Rejection</td>
						<td class="thead">Cost of Rejected</td>
						<td class="thead">Quality</td>
						<td class="thead">Total Down Time</td>
						<td class="thead">Cost of Downtime</td>
					</tr>
					<?php
						//$count = 0;
						//intial table data.
						$it1 = 0;
						$it2 = 0;
						$it3 = 0;
						$it4 = 0;
						foreach ($product_base_alias as $row)
						{
							
							$depth = '';
							for($i=0; $i<$row['depth']; $i++)
                            {
                                $depth .= '&nbsp;&nbsp;';
                            }
							foreach ($table_data as $tvals)
							{
								$paths = explode(",", $tvals['path']);
								if($paths[2] == $operation_value_qp && $paths[5] == $row['element'])
								{
									$a1 = $tvals['value'];
								}
								if($paths[2] == $operation_value_kq && $paths[5] == $row['element'])
								{
									$a2 = $tvals['value'];
								}
								if($paths[2] == $operation_value_cq && $paths[5] == $row['element'])
								{
									$a5 = $tvals['value'];
								}
								if($paths[2] == $operation_value_kt && $paths[5] == $row['element'])
								{
									$a7 = $tvals['value'];
								}
								if($paths[2] == $operation_value_kt11 && $paths[5] == $row['element'])
								{
									$a8 = $tvals['value'];
								}
								if($paths[2] == $operation_value_ct && $paths[5] == $row['element'])
								{
									$a9 = $tvals['value'];
								}
								
							}
							
							$a3 = $a1 - $a2;
							$a4 = 0;
							$a6 = 0;
							if($a1 != 0)
							{
								$a4 = ($a2/$a1)*100;
								$a6 = ($a3/$a1)*100;
							}
							$a78 = $a7 - $a8;
							/*
							if($count == 0)
							{
								
								$it2 = ((480-$a78)/480)*100;
								$it3 = (0.04/( (480-$a78) / $a3 ) )*100;
								$it4 = ($a3/$a1)*100;
								$it1 = $it2*$it3*$it4/10000;
							}
							$count += 1;
							*/
							
					?>
					<tr>
						<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
						<td><?php echo number_format($a1, 0, ".", ","); ?></td>
						<td><?php echo number_format($a2, 0, ".", ","); ?></td>
						<td><?php echo number_format($a3, 0, ".", ","); ?></td>
						<td><?php echo number_format($a4, 2, ".", ","); ?>%</td>
						<td><?php echo CUR_SIGN." ".number_format($a5, 0, ".", ","); ?></td>
						<td><?php echo number_format($a6, 2, ".", ","); ?>%</td>
						<td><?php echo number_format($a78, 2, ".", ","); ?></td>
						<td><?php echo CUR_SIGN." ".number_format($a9, 2, ".", ","); ?></td>
					</tr>
					<?php
						}
					?>
                </table>
                <?php
                foreach ($table_data1 as $tvals)
				{
					$paths = explode(",", $tvals['path']);
					if($paths[2] == $operation_value_oee)
					{
						$it1 = $tvals['value'];
					}
					if($paths[2] == $operation_value_oeea)
					{
						$it2 = $tvals['value'];
					}
					if($paths[2] == $operation_value_oeep)
					{
						$it3 = $tvals['value'];
					}
					if($paths[2] == $operation_value_oeeq)
					{
						$it4 = $tvals['value'];
					}
				}
                ?>
                <table id="tb1" class="avtable_2">
                	<tr>
                		<td class="thead" >Overhaul Equipment Efficiency (OEE)</td>
                		<td class="thead">Availability</td>
                		<td class="thead">Performance</td>
                		<td class="thead">Quality</td>
                	</tr>
                	<tr>
                    	
						<td ><?php echo number_format($it1, 0, ".", ","); ?>%</td>
						
						<td><?php echo number_format($it2, 0, ".", ","); ?>%</td>
						
						<td><?php echo number_format($it3, 0, ".", ","); ?>%</td>
						
						<td><?php echo number_format($it4, 0, ".", ","); ?>%</td>
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

var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_1", "600", "350", "0", "1");
myChart1.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='Day' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
myChart1.render("chartContainer1");

var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_2", "600", "350", "0", "1");
myChart2.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='Day' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2; ?>"+"</chart>");
myChart2.render("chartContainer2");

var myChart3 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_3", "600", "350", "0", "1");
myChart3.setXMLData("<chart caption='' labelDisplay='wrap' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='Day' yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart3; ?>"+"</chart>");
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
<div id="dialog-message" title="Chart Pinned" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
		<span id="pnchart"></span> now pinned to your home.
	</p>
</div>
</body>
</html>