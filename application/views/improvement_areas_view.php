<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Improvement Areas</title>
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


</script>

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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Improvement Areas</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
			
			<div class="content_div">
            	<table>
					<tr>
						<td class="text_right">
							<?php
								//if($this->proeo_model->check_chart($jedox_user_details['name'], $this->session->userdata('jedox_db'), 'Efficiency Costs - Total Cost', 'efficiency_costs/chart1') == 0)
								//{
							?>
							<!-- <a id="chartpin1" title="Pin Chart to Home" onclick="pinme('Efficiency Costs - Total Cost', 'efficiency_costs/chart1', 'efficiency_costs', 'chartpin1');"><span class="ui-icon ui-icon-pin-w right" style="margin-right: .3em;" ></span></a> -->
							<?php
								//}
							?>
						</td>
						<td></td>
					</tr>
					<tr>
						<td valign="top"><div id="chartContainer1" class="chart1"></div></td>
					</tr>
				</table>
                
            </div>
			
			<div class="content_div">
				<table id="tb1" class="avtable_2">
					<tr>
						<td>&nbsp;</td>
                    	<td class="thead">Initial Capital Investment</td>
						<td class="thead">Net Financial Impact</td>
						<td class="thead">Strategic Importance Number</td>
					</tr>
					<?php
						foreach($process_elements_set_alias as $row)
						{
							$a = $b = $c = 0;
							foreach($table1_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($account_element_CE_9010 == $paths['0'] && $row['element'] == $paths['1'])
								{
									$a = $drow1['value'];
								}
								if($account_element_CE_9020 == $paths['0'] && $row['element'] == $paths['1'])
								{
									$b = $drow1['value'];
								}
								if($account_element_CE_9090 == $paths['0'] && $row['element'] == $paths['1'])
								{
									$c = $drow1['value'];
								}
							}
					?>
					<tr >
						<td class="label"><?php echo $row['name_element']; ?></td>
                    	<td ><?php echo number_format($a, 0, ".", ","); ?></td>
						<td ><?php echo number_format($b, 0, ".", ","); ?></td>
						<td ><?php echo number_format($c, 0, ".", ","); ?></td>
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

<?php
	if($chart1 != '')
	{
?>
<script type="text/javascript">
      var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Bubble.swf", "chartId_1", "900", "450", "0", "1");

      myChart1.setXMLData("<?php echo $chart1; ?>");

      myChart1.render("chartContainer1");
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
</body>
</html>