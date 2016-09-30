<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Efficiency Costs</title>
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
    
    var table1 = $('#tb1').tabelize({
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
            <div id="filter_menu">
                <h3>Filters</h3>
                <div>
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('efficiency_costs'); ?>">
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
                            <select name="receiver" onchange="this.form.submit();" class="ddown1" title="Select Resource">
                            <?php
                                foreach($form_reciever as $row)
                                {
                                    $depth = '';
                                    $depth_fix = $row['depth'] - 1; // the value to subtact here depends on the depth of the first element when filtered
                                    for($i=0; $i<$depth_fix; $i++)
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
                        
                        <div class="filter_div">
                            <div class="left">
                                <a href="#" onclick="javascript:window.print();" title="Print This Page"><span class="ui-icon ui-icon-print left" ></span></a> 
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Costs</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="content_div">
            	<table>
					
					<tr>
						<td valign="top"><div id="chartContainer1" class="chart1"></div></td>
					</tr>
				</table>
                
            </div>
            
            <div class="content_div">
                <table id="tb1" class="avtable_2 controller">
                    <tr data-level="header" class="header">
                        <td>&nbsp;</td>
                        <td class="thead">Actual</td>
                        <td class="thead">Target</td>
                        <td class="thead">Plan</td>
                    </tr>
                    <?php
                    	$depth_stack = array();
                    	$depth_stack[] = "1_1";
						$depth_stack[] = "1_2";
						
                    	$a = $b = $c = 0;
						foreach($pc_sum as $row)
						{
							$paths = explode(",", $row['path']);
							if($paths[0] == $version_a)
							{
								$a = $row['value'];
							}
							if($paths[0] == $version_t)
							{
								$b = $row['value'];
							}
							if($paths[0] == $version_p)
							{
								$c = $row['value'];
							}
						}
						
                    ?>
                    <tr class="tmain" data-level="1" id="level_1_1" >
                    	<td class="label"><span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span>Primary Costs</td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($a), 0, '.', ',') ; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($b), 0, '.', ',') ; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($c), 0, '.', ',') ; ?></td>
                    </tr>
                   
                    <?php
                    // account elements
                    
                    foreach($account_element_elements_CE_5full_alias as $row)
                    {
                    	$a1 = $b1 = $c1 = 0;
						$depth = '';
						$true_depth = 1; 
						$sub_depth = 1;
                        for($z=1; $z<$row['depth']; $z++)
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
						
						foreach($ae_sum as $drow)
						{
							$paths = explode(",", $drow['path']);
							if($paths[0] == $version_a && $paths[4] == $row['element'])
							{
								$a1 = $drow['value'];
							}
							if($paths[0] == $version_t && $paths[4] == $row['element'])
							{
								$b1 = $drow['value'];
							}
							
							if($paths[0] == $version_p && $paths[4] == $row['element'])
							{
								$c1 = $drow['value'];
							}
						}
						if($a1 == 0 && $b1 == 0 && $c1 == 0)
						{
							// do nothing
						}
						else
						{
					?>	
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>" >
                    	<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($a1), 0, '.', ',') ; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($b1), 0, '.', ',') ; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($c1), 0, '.', ',') ; ?></td>
                    </tr>	
						
					<?php			
						}
                    }
                    //echo $ae_table;
                    ?>
                    <?php
                    	$a = $b = $c = 0;
						foreach($tc3_cells as $row)
						{
							$paths = explode(",", $row['path']);
							if($paths[0] == $version_a)
							{
								$a = $row['value'];
							}
							if($paths[0] == $version_t)
							{
								$b = $row['value'];
							}
							if($paths[0] == $version_p)
							{
								$c = $row['value'];
							}
						}
                    ?>
                    
                    <tr class="tmain" data-level="1" id="level_1_2" >
                    	<td class="label"><span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span>Secondary Costs</td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($a), 0, '.', ',') ; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($b), 0, '.', ',') ; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($c), 0, '.', ',') ; ?></td>
                    </tr>
                    
                    <?php
                    foreach($sender_elements_alias as $row)
                    {
                    	$a1 = $b1 = $c1 = 0;
						$depth = '';
						$true_depth = 1; 
						$sub_depth = 1;
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
						
						foreach($se as $drow)
						{
							$paths = explode(",", $drow['path']);
							if($paths[0] == $version_a && $paths[4] == $row['element'])
							{
								$a1 = $drow['value'];
							}
							if($paths[0] == $version_t && $paths[4] == $row['element'])
							{
								$b1 = $drow['value'];
							}
							
							if($paths[0] == $version_p && $paths[4] == $row['element'])
							{
								$c1 = $drow['value'];
							}
						}
						if($a1 == 0 && $b1 == 0 && $c1 == 0)
						{
							// do nothing
						}
						else
						{
					?>	
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>" >
                    	<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($a1), 0, '.', ',') ; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($b1), 0, '.', ',') ; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($c1), 0, '.', ',') ; ?></td>
                    </tr>	
						
					<?php
						}	
                    }
                        //echo $se_table;
                    ?>
                    <?php
                    	$a = $b = $c = 0;
						foreach($tc_sum as $row)
						{
							$paths = explode(",", $row['path']);
							if($paths[0] == $version_a)
							{
								$a = $row['value'];
							}
							if($paths[0] == $version_t)
							{
								$b = $row['value'];
							}
							if($paths[0] == $version_p)
							{
								$c = $row['value'];
							}
						}
                    ?>
                    
                    <tr class="tmain1 header" data-level="1">
                        <td class="label">Total Costs</td>
                        <td><?php echo CUR_SIGN." ".number_format(round($a), 0, '.', ',') ; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($b), 0, '.', ',') ; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(round($c), 0, '.', ',') ; ?></td>
                    </tr>
                </table>
                
            </div>
            
        </td>
    </tr>
    <tr>
        <td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
    </tr>
</table>
<script>
    var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='Total Cost' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1xml; ?>"+"</chart>");
    myChart1.render("chartContainer1");
</script>
<?php
	//$this->jedoxapi->traceme($version_a, "actual");
	//$this->jedoxapi->traceme($version_t, "target");
	//$this->jedoxapi->traceme($version_p, "plan");
	//$this->jedoxapi->traceme($ae_sum, "ae_sum");
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