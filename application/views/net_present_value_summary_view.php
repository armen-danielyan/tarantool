<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Net Present Value Summary</title>
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
        active: 3
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
            
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))
				." > <span class='orange'>Net Present Value Summary</span> > "
				.anchor('net_present_value_npv', 'NPV', array('title' => 'Go to NPV'))." > "
				.anchor('net_present_value_3_year', '3 Year', array('title' => 'Go to 3 Year'))." > "
				.anchor('net_present_value_monthly', 'Monthly', array('title' => 'Go to Monthly'));
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Net Benefit</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"></div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Return on Investment (ROI)</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer2" class="chart1"></div></td>
                    	</tr>
                    </table>
                </div>
                <div class="tabbertab">
                    <h3>Payback Years</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer3" class="chart1"></div></td>
                    	</tr>
                    </table>
                    
                </div>
            </div>
            <div class="content_div" >
            	
				
                <table id="tb1" class="avtable_2">
                	<tr>
						<td>&nbsp;</td>
                    	<td class="thead">Net Benefit</td>
						<td class="thead">Return on Investment (ROI)</td>
						<td class="thead togrange">Payback Years</td>
					</tr>
						<?php
							$a = $b = $c = 0;
							$chart1 = $chart2 = $chart3 = '';
							foreach($process_all as $row)
							{
								$a = $b = $c = 0;
								foreach($table_data as $drow)
								{
									$paths = explode(",", $drow['path']);
									
									if($account_element_ce_9020 == $paths[0] && $row['element'] == $paths[1])
									{
										$a = $drow['value'];
									}
									if($account_element_ce_9030 == $paths[0] && $row['element'] == $paths[1])
									{
										$b = $drow['value'];
									}
									if($account_element_ce_9040 == $paths[0] && $row['element'] == $paths[1])
									{
										$c = $drow['value'];
									}
									
								}
								$chart1 .= "<set label='".substr($row['name_element'], 0, 4)."' value='".$a."' />";
								$chart2 .= "<set label='".substr($row['name_element'], 0, 4)."' value='".($b*100)."' />";
								$chart3 .= "<set label='".substr($row['name_element'], 0, 4)."' value='".$c."' />";
						?>		
					<tr>
						<td class="label"><?php echo $row['name_element']; ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($a, 0, ".", ","); ?></td>
						<td ><?php echo number_format($b*100, 0, ".", ","); ?>%</td>
						<td ><?php echo number_format($c, 2, ".", ","); ?></td>
					</tr>
						<?php		
							}
						?>
					
					
					
                </table>
                
                
                
            </div>
            
        </td>
    </tr>
    <tr>
        <td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
    </tr>
</table>
<script type="text/javascript">
	
	var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='' bgColor='FFFFFF' decimals='2' showBorder='0' canvasBorderAlpha='0' xAxisName='' yAxisName='' showValues='0' numberSuffix='' stack100Percent='0'>"+"<?php echo $chart1; ?>"+"</chart>");
    myChart1.render("chartContainer1");
    
    var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_2", "600", "300", "0", "1");
    myChart2.setXMLData("<chart caption='' bgColor='FFFFFF' decimals='2' showBorder='0' canvasBorderAlpha='0' xAxisName='' yAxisName='Percent' showValues='0' numberSuffix='' stack100Percent='1'>"+"<?php echo $chart2; ?>"+"</chart>");
    myChart2.render("chartContainer2");
    
    var myChart3 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_3", "600", "300", "0", "1");
    myChart3.setXMLData("<chart caption='' bgColor='FFFFFF' decimals='2' showBorder='0' canvasBorderAlpha='0' xAxisName='' yAxisName='' showValues='0' numberSuffix='' stack100Percent='0'>"+"<?php echo $chart3; ?>"+"</chart>");
    myChart3.render("chartContainer3");
</script>
<?php
	//cell replace code
	
	//$update_cube = $this->jedox->cell_replace($database_1, $cube_1, $new_area, $gmp);
	//print_r ($database_1."---".$cube_1."---".$new_area."----".$gmp);
	//$this->jedoxapi->traceme($process_all, "process");
	//$this->jedoxapi->traceme($table_data, "table data");
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