<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Sales Plan</title>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/FusionCharts.js"></script>
<link href='http://fonts.googleapis.com/css?family=Cuprum:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/smoothness/jquery-ui-1.9.1.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/style.css" type="text/css" media="screen" />
<script type="text/javascript">
$(document).ready(function() {
	$( "#sidebar_menu" ).accordion({
		heightStyle: "content",
		collapsible: false,
		active: 1
    });
    $( "#filter_menu" ).accordion({
        heightStyle: "content",
        collapsible: false
    });
	
	$( document ).tooltip({
		track: true
	});
	
});

function submitme(){
		var arrData = myChart1.getData();
		document.form1.elements["p_jan"].value = arrData[1][2];
		document.form1.elements["p_feb"].value = arrData[2][2];
		document.form1.elements["p_mar"].value = arrData[3][2];
		document.form1.elements["p_apr"].value = arrData[4][2];
		document.form1.elements["p_may"].value = arrData[5][2];
		document.form1.elements["p_jun"].value = arrData[6][2];
		document.form1.elements["p_jul"].value = arrData[7][2];
		document.form1.elements["p_aug"].value = arrData[8][2];
		document.form1.elements["p_sep"].value = arrData[9][2];
		document.form1.elements["p_oct"].value = arrData[10][2];
		document.form1.elements["p_nov"].value = arrData[11][2];
		document.form1.elements["p_dec"].value = arrData[12][2];
		document.form1.submit();
}

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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('sales_plan'); ?>">
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
                            
                            <select name="customer" onchange="this.form.submit();" id="customer" class="ddown1" title="Select Customer">
                            <?php
                                foreach($form_customer as $row)
                                {
                                    $depth = '';
                                    for($i=2; $i<$row['depth']; $i++)
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
                            
                            <select name="receiver" onchange="this.form.submit();" id="receiver" class="ddown1" title="Select Product">
                            <?php
                                foreach($form_receiver as $row)
                                {
                                    $depth = '';
                                    for($i=3; $i<$row['depth']; $i++)
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
                        <input name="p_jan" type="hidden" id="p_jan" value="" />
                        <input name="p_feb" type="hidden" id="p_feb" value="" />
                        <input name="p_mar" type="hidden" id="p_mar" value="" />
                        <input name="p_apr" type="hidden" id="p_apr" value="" />
                        <input name="p_may" type="hidden" id="p_may" value="" />
                        <input name="p_jun" type="hidden" id="p_jun" value="" />
                        <input name="p_jul" type="hidden" id="p_jul" value="" />
                        <input name="p_aug" type="hidden" id="p_aug" value="" />
                        <input name="p_sep" type="hidden" id="p_sep" value="" />
                        <input name="p_oct" type="hidden" id="p_oct" value="" />
                        <input name="p_nov" type="hidden" id="p_nov" value="" />
                        <input name="p_dec" type="hidden" id="p_dec" value="" />
                        <div class="clearfix"></div>
                        <div class='filter_div'>
                            <div class="left">
                                <a href="#" onclick="javascript:window.print();" title="Print This Page"><span class="ui-icon ui-icon-print left" ></span></a> | 
                            </div>
                            <div class="left">
                                <?php echo mailto("?to=&subject=&body=".site_url('sales_plan')."/info/".$n_year."/".url_title($n_customer, '_')."/".url_title($n_receiver, '_')."", "<span class='ui-icon ui-icon-mail-closed left'></span>", array("title" =>"Share this page via email")); ?>
                                
                            </div>
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Sales Plan</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
			<div class="content_div">	
				<table>
					<tr>
						<td valign="top" align="right"><div id="chartContainer1" class="chart1"></div><div class="clearfix">&nbsp;</div><a href="#" onclick="submitme(); return false;" class="chart_bt">Update Data</a></td>
						<td valign="top"><div id="chartContainer2" class="chart1"></div></td>
					</tr>
				</table>
				<div class="clearfix">&nbsp;</div>
				
			</div>
			<div class="tabber">
                <div class="tabbertab">
                    <h3>Sales Quantity</h3>
                    <table class="avtable_2">
						<?php
							echo $table1;
						?>
					</table>
                </div>
                <div class="tabbertab">
                	<h3>Gross Revenue</h3>
                	<table class="avtable_2">
						<?php
							echo $table2;
						?>
					</table>
                </div>
            </div>
		</td>
	</tr>
	<tr>
		<td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
	</tr>
</table>

<script type="text/javascript">
		var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/PowerCharts_XT/Charts/DragLine.swf", "chartId_1", "600", "350", "0", "1");
		myChart1.setXMLData("<chart caption='Sales Quantity' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' setAdaptiveYMin='1' showvalues='0' xAxisName='Months' yAxisName='Sales Quantity' restoreBtnBorderColor='A2A3A0' formBtnBorderColor='A2A3A0' canvasPadding='20' dragBorderColor='666666' dragBorderThickness='3' showFormBtn='0'>"+"<?php echo $chart1; ?>"+"</chart>");
		//myChart1.setXMLUrl("<?php //echo base_url(); ?>assets/test.xml");
		myChart1.render("chartContainer1");
</script>

<script type="text/javascript">
      var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_2", "600", "350", "0", "1");

      myChart2.setXMLData("<chart caption='Gross Revenue' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='Months'  yAxisName='' showValues='0' numberPrefix='' setAdaptiveYMin='1'>"+"<?php echo $chart2; ?>"+"</chart>");

      myChart2.render("chartContainer2");
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