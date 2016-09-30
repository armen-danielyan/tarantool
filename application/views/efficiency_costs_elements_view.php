<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Efficiency Costs Elements</title>
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
    $('tr.tmain td').attr('title', 'Click to Expand/Collapse');
    $('tr.tmain td.label').each(function (){
    	var new_label = "<span class='ui-icon ui-icon-squaresmall-plus left'></span> "+$(this).html();
    	$(this).html(new_label);
    });
    
    $('#tb1 tr.tmain').click( function() {
        var wcell = this.rowIndex;
        $("#tb1 tr:eq("+wcell+")").nextAll('tr').each( function() {
            if ($(this).hasClass('tmain') || $(this).hasClass('tmain1')) {
                return false;
            }
            $(this).toggle();
        });
    });
    
    $('#tb1 tr.tmain').each(function() {
        $(this).nextAll('tr').each( function() {
            if ($(this).hasClass('tmain') || $(this).hasClass('tmain1')) {
                return false;
            }
            $(this).toggle();
        });
    });
    
    $.ajaxSetup({
	  beforeSend: function() {
	     $('#loader').show();
	  },
	  complete: function(){
	     $('#loader').hide();
	  },
	  success: function() {}
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



function get_ae(a_element, target)
{
	var targetloc = $("#" + target).html();
	if(targetloc == ''){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('efficiency_costs_elements'); ?>/get_ae",
			data: { account_element: a_element, year: <?php echo $year; ?>, month: <?php echo $month; ?>, version: <?php echo $version; ?> }
			}).done(function( html ) {
				$("#" + target).html(html);
		});
	} else {
		$("#" + target).html('');
	}
	
	
}

function get_rp(a_element, r_element, target)
{
	var targetloc = $("#" + target).html();
	if(targetloc == ''){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('efficiency_costs_elements'); ?>/get_rp",
			data: { account_element: a_element, receiver: r_element ,year: <?php echo $year; ?>, month: <?php echo $month; ?>, version: <?php echo $version; ?> }
			}).done(function( html ) {
				$("#" + target).html(html);
		});
	} else {
		$("#" + target).html('');
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
            <div id="filter_menu">
                <h3>Filters</h3>
                <div>
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('efficiency_costs_elements'); ?>">
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
                            <select name="version" onchange="this.form.submit();" class="ddown1" title="Select Resource">
                            <?php
                                foreach($form_version as $row)
                                {
                                    $depth = '';
                                    $depth_fix = $row['depth'] - 1; // the value to subtact here depends on the depth of the first element when filtered
                                    for($i=0; $i<$depth_fix; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($version == $row['element']){ $n_version = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?> vs Actual</option>
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
                                <?php //echo mailto("?to=&subject=&body=".site_url('efficiency_costs')."/info/".$n_year."/".url_title($n_month, '_')."/".url_title($n_receiver, '_')."", "<span class='ui-icon ui-icon-mail-closed left' ></span>", array("title" =>"Share this page via email")); 
                                ?>
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Costs Elements</span>";
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
            	<div style="width: 1000px;">
            		<div class="con250">
            			<strong>Cost Elements</strong>
            		</div>
            		<div class="con250">
            			&nbsp;
            		</div>
            		<div class="con150 center tvhead">
            			Actual
            		</div>
            		<div class="con150 center tvhead">
            			<?php
            				echo $this->jedoxapi->get_name($version_name, $version);
            			?>
            		</div>
            		<div class="con150 center tvhead">
            			Variance
            		</div>
            		<div class="clearfix"></div>
            	</div>
            	
            	<?php
            		foreach($base_load as $mrow)
            		{
            			//Get Name of element
            	?>
            	<div>
            		<div class="con250 bggray">
            			<?php 
            			if($mrow['number_children'] > 0)
            			{
            		?>
						<a href="#" class="dvlink" onclick="get_ae('<?php echo $mrow['element']; ?>', '<?php echo "ae".$mrow['element']; ?>'); return false;" title="Click to Expand/Collapse" ><span class="ui-icon ui-icon-squaresmall-plus left"></span><?php echo $mrow['name_element']; ?></a>
					<?php
            			} else {
            				echo $mrow['name_element']; 
            			}
						?>
            		</div>
            		<div class="con250 bggray">
            			<?php
            				//a base load node will always start with RP. how to chain this?
            				//get actual value and set 1st var
            				$a1 = 0;
            				foreach($table_base as $avalrow)
            				{
            					$apath = explode(",", $avalrow['path']);
								if($apath[0] == $version_actual_area && $apath[4] == $mrow['element'])
								{
									$a1 = $avalrow['value'];
									foreach($receiver_RP as $rprow)
									{
										if($apath[5] == $rprow['element'])
										{
											$rpname = $rprow['name_element'];
											if($rprow['number_children'] > 0)
											{
												//has child. make link.
						?>
							<a href="#" class="dvlink" onclick="get_rp('<?php echo $mrow['element']; ?>', '<?php echo $rprow['element'] ?>', '<?php echo "rp".$mrow['element']."ae".$mrow['element']; ?>'); return false;" title="Click to Expand/Collapse"><span class="ui-icon ui-icon-squaresmall-plus left"></span><?php echo $rpname; ?></a>
						<?php
											} else {
												echo $rpname;
											}
										}
									}
								}
								
            				}
            				//echo $receiver_RP[0]['name_element'];
            			?>
            		</div>
            		<div class="con150 center bggray">
            			<?php
            				// show 1st var
							echo CUR_SIGN." ".number_format($a1, 0, '.', ',');
            			?>
            		</div>
            		<div class="con150 center bggray">
            			<?php
            				//get plan/target value and set 2nd var
            				$a2 = 0;
            				foreach($table_base as $bvalrow)
            				{
            					$bpath = explode(",", $bvalrow['path']);
								if($bpath[0] == $version && $apath[4] == $mrow['element'])
								{
									$a2 = $bvalrow['value'];
									
								}
								
            				}
							echo CUR_SIGN." ".number_format($a2, 0, '.', ',');
            			?>
            		</div>
            		<div class="con150 center bggray">
            			<?php
            				$aresult = $a2-$a1;
							if($aresult < 0){
								echo "<span class='redcolor'>".CUR_SIGN." ".number_format($aresult, 0, '.', ',')."</span>";
							}
							else
							{
								echo CUR_SIGN." ".number_format($aresult, 0, '.', ',');
							}
            			?>
            		</div>
            		<div class="clearfix"></div>
            		<div id="<?php echo "rp".$mrow['element']."ae".$mrow['element']; ?>"></div>
            		<div class="clearfix"></div>
            		<div id="<?php echo "ae".$mrow['element']; ?>"></div>
            		<div class="clearfix"></div>
            	</div>
            	<?php
            		}
            	?>
            	
            </div>
            
        </td>
    </tr>
    <tr>
        <td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
    </tr>
</table>
<script>
    var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='Primary Cost' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1xml; ?>"+"</chart>");
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
<div id="dialog-message" title="Chart Pinned" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
		<span id="pnchart"></span> now pinned to your home.
	</p>
</div>
<div id="loader" style="background: #999999; display: none; left: 50%; padding: 3px; position: fixed; top: 50%; z-index: 999;">loading...</div>
</body>
</html>