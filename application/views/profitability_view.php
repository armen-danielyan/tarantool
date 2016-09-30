<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Profitability</title>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jQueryRotate.2.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/FusionCharts.js"></script>
<link href='http://fonts.googleapis.com/css?family=Cuprum:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/smoothness/jquery-ui-1.9.1.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/style.css" type="text/css" media="screen" />

<script type="text/javascript" >
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
	
	$.fn.switchColumns = function ( col1, col2 ) {
		var $this = this,
			$tr = $this.find('tr');
	
		$tr.each(function(i, ele){
			var $ele = $(ele),
				$td = $ele.find('td'),
				$tdt;
			
			$tdt = $td.eq( col1 ).clone();
			$td.eq( col1 ).html( $td.eq( col2 ).html() );
			$td.eq( col2 ).html( $tdt.html() );
		});
	};
	
	$('#tb1').switchColumns( 1,2 );
	$('#tb1').switchColumns( 2,3 );
	$('#tb1').switchColumns( 6,7 );
	$('#tb1').switchColumns( 7,8 );
	
	$('#chartContainer5').rotate(90);
	$('#chartContainer6').rotate(90);
	$('#chartContainer7').rotate(90);
	
	//$('#showhide').toggle( function() {$('#main_content').css('left', '200px')}, function() {$('#main_content').css('left', '0')} );
	
});

function ddown(val)
{
	document.getElementById('receiver').value = val;
	document.forms["param"].submit();
}
function ddown1(val)
{
	document.getElementById('customer').value = val;
	document.forms["param"].submit();
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
                    <form id="param" name="param" method="post" action="<?php echo site_url('profitability'); ?>">
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
                        
                        <select name="customer" onchange="this.form.submit();" id="customer" class="ddown1" title="Select Customer">
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
                        
                        <select name="receiver" onchange="this.form.submit();" id="receiver" class="ddown1" title="Select Product">
                        <?php
                            foreach($form_receiver as $row)
                            {
                                $depth = '';
                                for($i=1; $i<$row['depth']; $i++)
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
                    <div class="clearfix"></div>
                    
                    <div class="filter_div">
                        <div class="left">
                            <a href="#" onclick="javascript:window.print();" title="Print This Page"><span class="ui-icon ui-icon-print left" ></span></a> | 
                        </div>
                        <div class="left">
                            <?php echo mailto("?to=&subject=&body=".site_url('profitability')."/info/".$n_year."/".url_title($n_month, '_')."/".url_title($n_customer, '_')."/".url_title($n_receiver, '_')."", "<span class='ui-icon ui-icon-mail-closed left' ></span>", array("title" =>"Share this page via email")); ?>
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Profitability</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
			<div class="tabber">
				<div class="tabbertab">
					<h3>Revenue and Margin per Product</h3>
					<table>
						<tr>
							<td class="text_right">
								<?php
									if($this->proeo_model->check_chart($jedox_user_details['name'], $this->session->userdata('jedox_db'), 'Profitability - Revenue and Margin per Product', 'profitability/chart1') == 0)
									{
								?>
								<a id="chartpin1" title="Pin Chart to Home" onclick="pinme('Profitability - Revenue and Margin per Product', 'profitability/chart1', 'profitability', 'chartpin1');"><span class="ui-icon ui-icon-pin-w right" style="margin-right: .3em;" ></span></a>
								<?php
									}
								?>
							</td>
							<td class="text_right">
								<?php
									if($this->proeo_model->check_chart($jedox_user_details['name'], $this->session->userdata('jedox_db'), 'Profitability - Revenue and Margin per Product Ranking', 'profitability/chart9') == 0)
									{
								?>
								<a id="chartpin9" title="Pin Chart to Home" onclick="pinme('Profitability - Revenue and Margin per Product Ranking', 'profitability/chart9', 'profitability', 'chartpin9');"><span class="ui-icon ui-icon-pin-w right" style="margin-right: .3em;" ></span></a>
								<?php
									}
								?>
							</td>
						</tr>
						<tr>
							<td valign="top"><div id="chartContainer1" class="chart1"></div><div class="center"><br /><strong>Bubble size and color reflects Actual Gross Margin Percentage</strong></div></td>
							<td valign="top"><div id="chartContainer9" class="chart3"></div><div class="center"><br /><strong>Actual Gross Margin</strong></div></td>
						</tr>
					</table>
				</div>
				<div class="tabbertab">
					<h3>Revenue and Margin per Customer</h3>
					<table>
						<tr>
							<td class="text_right">
								<?php
									if($this->proeo_model->check_chart($jedox_user_details['name'], $this->session->userdata('jedox_db'), 'Profitability - Revenue and Margin per Customer', 'profitability/chart4') == 0)
									{
								?>
								<a id="chartpin4" title="Pin Chart to Home" onclick="pinme('Profitability - Revenue and Margin per Customer', 'profitability/chart4', 'profitability', 'chartpin4');"><span class="ui-icon ui-icon-pin-w right" style="margin-right: .3em;" ></span></a>
								<?php
									}
								?>
							</td>
							<td class="text_right">
								<?php
									if($this->proeo_model->check_chart($jedox_user_details['name'], $this->session->userdata('jedox_db'), 'Profitability - Revenue and Margin per Customer Ranking', 'profitability/chart10') == 0)
									{
								?>
								<a id="chartpin10" title="Pin Chart to Home" onclick="pinme('Profitability - Revenue and Margin per Customer Ranking', 'profitability/chart10', 'profitability', 'chartpin10');"><span class="ui-icon ui-icon-pin-w right" style="margin-right: .3em;" ></span></a>
								<?php
									}
								?>
							</td>
						</tr>
						<tr>
							<td valign="top"><div id="chartContainer4" class="chart1"></div><div class="center"><br /><strong>Bubble size and color reflects Actual Gross Margin Percentage</strong></div></td>
							<td valign="top"><div id="chartContainer10" class="chart3"></div></div><div class="center"><br /><strong>Actual Gross Margin</strong></div></td>
						</tr>
					</table>
				</div>
				<div class="tabbertab">
					<h3>Waterfall Analysis</h3>
					<table>
						<tr>
							<td class="text_right">
								<?php
									if($this->proeo_model->check_chart($jedox_user_details['name'], $this->session->userdata('jedox_db'), 'Profitability - Waterfall Analysis', 'profitability/chart5') == 0)
									{
								?>
								<a id="chartpin5" title="Pin Chart to Home" onclick="pinme('Profitability - Waterfall Analysis', 'profitability/chart5', 'profitability', 'chartpin5');"><span class="ui-icon ui-icon-pin-w right" style="margin-right: .3em;" ></span></a>
								<?php
									}
								?>
							</td>
							<td class="text_right">
								<?php
									if($this->proeo_model->check_chart($jedox_user_details['name'], $this->session->userdata('jedox_db'), 'Profitability - Waterfall Analysis Ranking', 'profitability/chart8') == 0)
									{
								?>
								<a id="chartpin8" title="Pin Chart to Home" onclick="pinme('Profitability - Waterfall Analysis Ranking', 'profitability/chart8', 'profitability', 'chartpin8');"><span class="ui-icon ui-icon-pin-w right" style="margin-right: .3em;" ></span></a>
								<?php
									}
								?>
							</td>
						</tr>
						<tr>
							<td valign="top"><div id="chartContainer5" class="chart2"></div></div><div class="center"><br /><strong>Actual</strong></div></td>
							<td valign="top"><div id="chartContainer8" class="chart4"></div></div><div class="center"><br /><strong>Variance</strong></div></td>
						</tr>
					</table>
					
				</div>
				<!--
				<div class="tabbertab">
					<h3>Actual Gross Margin By Customer</h3>
					<div id="chartContainer2" class="chart1"></div>
				</div>
				<div class="tabbertab">
					<h3>Actual Gross Margin By Product</h3>
					<div id="chartContainer3" class="chart1"></div>
				</div>
				-->
			</div>
			
			<div class="content_div">
				<table class="avtable_2" id="tb1">
					<tr>
						<td>&nbsp;</td>
						<td class="thead">Plan</td>
						<td class="thead">Actual</td>
						<td class="thead">Target</td>
						<td class="thead">A/T Var.</td>
						<td>&nbsp;</td>
						<td class="thead">Pln.Mar.%</td>
						<td class="thead">Act.Mar.%</td>
						<td class="thead">Tar.Mar.%</td>
					</tr>
					<tr >
						<td class="label2">Sales Quantity</td>
						<?php echo $sq; ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="label2">Sales Price</td>
						<?php echo $sp; ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tmain">
						<td class="label">Gross Revenue</td>
						<?php echo $gr; ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="label2">Discounts</td>
						<?php echo $dc; ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tmain">
						<td class="label">Net Revenue</td>
						<?php echo $nr; ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="label2">Raw Material</td>
						<?php echo $rm; ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tmain">
						<td class="label">Product Margin</td>
						<?php echo $pm; ?>
						<td>&nbsp;</td>
						<?php echo $p_pm; ?>
					</tr>
					<tr>
						<td class="label2">Proportional Cost</td>
						<?php echo $pc; ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tmain">
						<td class="label">Contribution Margin</td>
						<?php echo $cm; ?>
						<td>&nbsp;</td>
						<?php echo $p_cm; ?>
					</tr>
					<tr>
						<td class="label2">Fixed Cost</td>
						<?php echo $fc; ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tmain">
						<td class="label">Gross Margin</td>
						<?php echo $gm; ?>
						<td>&nbsp;</td>
						<?php echo $p_gm; ?>
					</tr>
				</table>
				
			</div>
			
		</td>
	</tr>
	<tr>
		<td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
	</tr>
</table>

<?php
	if($chart1 != '')
	{
?>
<script type="text/javascript">
      var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Bubble.swf", "chartId_1", "600", "300", "0", "1");

      myChart1.setXMLData("<?php echo $chart1; ?>");

      myChart1.render("chartContainer1");
</script>	
<?php
	}
?>

<?php
	if($chart2 != '')
	{
?>
<script type="text/javascript">
      var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_2", "600", "300", "0", "1");

      myChart2.setXMLData("<chart caption='Actual Gross Margin By Customer' xAxisName=''  yAxisName='' showValues='1' numberPrefix='' >"+"<?php echo $chart2; ?>"+"</chart>");

      //myChart2.render("chartContainer2");
</script>	
<?php
	}
?>

<?php
	if($chart3 != '')
	{
?>
<script type="text/javascript">
      var myChart3 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_3", "600", "300", "0", "1");

      myChart3.setXMLData("<chart caption='Actual Gross Margin By Product' xAxisName=''  yAxisName='' showValues='1' numberPrefix='' >"+"<?php echo $chart3; ?>"+"</chart>");

      //myChart3.render("chartContainer3");
</script>	
<?php
	}
?>

<?php
	if($chart4 != '')
	{
?>
<script type="text/javascript">
      var myChart4 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Bubble.swf", "chartId_4", "600", "300", "0", "1");

      myChart4.setXMLData("<?php echo $chart4; ?>");

      myChart4.render("chartContainer4");
</script>	
<?php
	}
?>

<?php
	if($chart5 != '')
	{
?>
<script type="text/javascript">
      var myChart5 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/PowerCharts_XT/Charts/Waterfall2D.swf", "chartId_5", "350", "350", "0", "1");

      myChart5.setXMLData("<chart caption='' baseFontColor='000000' numberPrefix='' bgColor='FFFFFF' showBorder='0' showAlternateHGridColor='0' divLineAlpha='0' canvasBorderAlpha='0' labelDisplay='ROTATE' showYAxisValues='0' rotateValues='1' showToolTip='0' positiveColor='0066FF' negativeColor='FF6600' showSumAtEnd='0' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0'>"+"<?php echo $chart5; ?>"+"</chart>");

      myChart5.render("chartContainer5");
</script>	
<?php
	}
?>

<?php
	if($chart8 != '')
	{
?>
<script type="text/javascript">
      var myChart8 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Bar2D.swf", "chartId_8", "600", "370", "0", "1");

      myChart8.setXMLData("<chart caption='' baseFontColor='000000' xAxisName=''  yAxisName='' showValues='1' numberPrefix='' bgColor='FFFFFF' showBorder='0' divLineAlpha='0' canvasBorderAlpha='0' showYAxisValues='0' alternateVGridAlpha='0' positiveColor='0066FF' negativeColor='FF6600' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0'>"+"<?php echo $chart8; ?>"+"<trendLines><line startValue='0' color='336699' displayvalue=' ' /></trendLines></chart>");

      myChart8.render("chartContainer8");
</script>	
<?php
	}
?>

<?php
	if($table1_top10 != '')
	{
?>
<script type="text/javascript">
      var myChart9 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Bar2D.swf", "chartId_9", "600", "<?php echo $table1_top10_count*40; ?>", "0", "1");

      myChart9.setXMLData("<chart caption='' baseFontColor='000000' xAxisName=''  yAxisName='' showValues='1' numberPrefix='' bgColor='FFFFFF' showBorder='0' divLineAlpha='0' canvasBorderAlpha='0' showYAxisValues='0' alternateVGridAlpha='0' positiveColor='0066FF' negativeColor='FF6600' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0'>"+"<?php echo $table1_top10; ?>"+"<trendLines><line startValue='0' color='336699' displayvalue=' ' /></trendLines></chart>");

      myChart9.render("chartContainer9");
</script>	
<?php
	}
?>

<?php
	if($table4_top10 != '')
	{
?>
<script type="text/javascript">
      var myChart10 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Bar2D.swf", "chartId_10", "600", "<?php echo $table4_top10_count*40; ?>", "0", "1");

      myChart10.setXMLData("<chart caption='' baseFontColor='000000' xAxisName=''  yAxisName='' showValues='1' numberPrefix='' bgColor='FFFFFF' showBorder='0' divLineAlpha='0' canvasBorderAlpha='0' showYAxisValues='0' alternateVGridAlpha='0' positiveColor='0066FF' negativeColor='FF6600' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0'>"+"<?php echo $table4_top10; ?>"+"<trendLines><line startValue='0' color='336699' displayvalue=' ' /></trendLines></chart>");

      myChart10.render("chartContainer10");
</script>	
<?php
	}
?>
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
