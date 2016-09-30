<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Profit Optimization</title>
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
        active: 2
    });
    $( "#filter_menu" ).accordion({
        heightStyle: "content",
        collapsible: false
    });
    
    $( document ).tooltip({
        track: true
    });
    
    $('.default-value').each(function() {
		var default_value = this.value;
		$(this).focus(function() {
			if(this.value == default_value) {
				this.value = '';
			}
		});
		$(this).blur(function() {
			if(this.value == '') {
				this.value = default_value;
			}
		});
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

function grun(version, loc){
	var myrunset = {version_sim_name: version};
	
	$.ajax({
		url:"<?php echo site_url('profit_optimization/grun'); ?>",
		type: "post",
	    data: myrunset,
		success:function(result){
			$(loc).html(result);
			
			//if(result == "Completed" || result == "Completed with Warnings")
			//{
			document.getElementById("r_etl").style.display = "none";
			document.getElementById("s_etl").style.display = "";
			//}
			//alert(result);
		}
	});
}

function gstat(pid, loc){
	
	//var mydataset = {id: pid};
	
	$.ajax({
		url:"<?php echo site_url('profit_optimization/gstatus'); ?>",
		//type: "post",
	    //data: mydataset,
		success:function(result){
			$(loc).html(result);
			
			if(result == "Completed" || result == "Completed with Warnings")
			{
				document.getElementById("result_button").style.display = "";
			}
		}
	});
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('profit_optimization'); ?>">
                        <div class="filter_div">
							<select name="version" onchange="this.form.submit();" class="ddown1" title="Select Version">
							<?php
							foreach( $form_version_1 as $row )
							{
								$selected = "";
								if( $version == $row['element'] ) { $selected = " selected"; }
								echo "<option value=\"".$row['element']."\"".$selected.">".$row['name_element']."</option>\r\n";
							}
							?>
							</select>
						</div>
                        <div class="filter_div">
                            <select name="year" onchange="this.form.submit();" class="ddown1" title="Select Year">
							<?php
							foreach( $form_year as $row)
							{
								$selected = "";
								if( $year == $row['element'] ) { $selected = " selected"; }
								echo "<option value=\"".$row['element']."\"".$selected.">".$row['name_element']."</option>\r\n";
							}
							?>
							</select>
                        </div>
                        <div class="filter_div">
                            <select name="month" onchange="this.form.submit();" class="ddown1" title="Select Month">
							<?php
							foreach( $form_months as $row)
							{
								$selected = "";
								if( $month == $row['element'] ) { $selected = " selected"; }
								echo "<option value=\"".$row['element']."\"".$selected.">".$row['name_element']."</option>\r\n";
							}
							?>
							</select>
                        </div>
					</form>
				</div>
			</div>
            
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Profit Optimization</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <?php
            if($step == 1){
            	// do/show only on step 1.
            
            ?>
            <div class="content_div">
            	<form id="form2" name="form2" method="post" action="<?php echo site_url('profit_optimization'); ?>">
            	
            	<table class="avtable_2">
            		<tr>
            			<td class="thead" >Simulation Version (Select Multiple)</td>
            		</tr>
            		<tr >
            			
            			<td style="text-align: left !important;">
            				<?php
            					$cb = 0;
            					foreach($form_version as $row)
            					{
            						$cb += 1;
									$checked = '';
									if(${'ver'.$cb} == '1')
									{
										$checked = "checked";
									}
									
            				?>
            					<input type="checkbox" name="ver<?php echo $cb; ?>" id="verid_<?php echo $cb; ?>" value="1" <?php echo $checked; ?> > <?php echo $row['name_element'] ?> <br />
            					
            				<?php		
            					}
            				?>
            				
            			</td>
            		</tr>
            		<tr>
            			<td style="text-align: center !important;">
            				<input type="hidden" name="version" value="<?php echo $version; ?>">
            				<input type="hidden" name="year" value="<?php echo $year; ?>">
            				<input type="hidden" name="month" value="<?php echo $month; ?>">
            				<input type="hidden" name="step" value="<?php echo $step; ?>">
            				<input name="simulate" type="submit" id="bf" value="Simulate" class="obutton1" />
            			</td>
            		</tr>
            	</table>
               
               </form>
               
               <form id="form3" name="form3" method="post" action="<?php echo site_url('profit_optimization'); ?>">
				<table class="avtable_2">
					<tr>
            			<td  >Create a New Version: 
            			
            				<input name="new_version" type="text" id="new_version" value="New Description" class="default-value">
            				<input type="hidden" name="version" value="<?php echo $version; ?>">
            				<input type="hidden" name="year" value="<?php echo $year; ?>">
            				<input type="hidden" name="month" value="<?php echo $month; ?>">
            			
            				<input name="Add New Version" type="submit" id="addnewver" value="Add Version" class="obutton1" />
            			</td>
            		</tr>
            		
            	</table>
               </form>
               
            </div>
            <?php 
            } // end step 1
            ?>
            <?php
            if ($step == 2)
            {
            	// do/show only on step 2
            ?>
            <div class="content_div">
            	<form id="form4" name="form4" method="post" action="<?php echo site_url('profit_optimization'); ?>">
				<table class="avtable_2">
					<tr>
            			<td  >
            				<?php echo $error; ?>
            				<input type="hidden" name="version" value="<?php echo $version; ?>">
            				<input type="hidden" name="year" value="<?php echo $year; ?>">
            				<input type="hidden" name="month" value="<?php echo $month; ?>">
            				<input type="hidden" name="step" value="<?php echo $step; ?>">
            				<input name="Calculate Rates" type="submit" id="calc rates" value="Calculate Rates" class="obutton1" />
            			</td>
            		</tr>
            		
            	</table>
            	</form>
            </div>
            <?php
            } // end step 2.
            ?>
            
            <?php
            if ($step == 3)
            {
            	// do/show only on step 3
            ?>
            <div class="content_div">
            	
				<table class="avtable_2">
					<tr>
						<td class="thead">Rates Calculation</td>
						<td class="thead" colspan="2">Status</td>
					</tr>
					<tr class="tmain">
						<td class="label">Raw Material Rates</td>
						<td class="label" colspan="2"><?php
						if( $error != "" )
						{
							echo $error;
						} else {
							echo "Successful";
						}
							//echo "<iframe class=\"embed\" src=\"../assets/calculate_rates/calc_raw_mat.php?version=".$version_sim."&year=".$year."&month=".$month."&verbose=0\"></iframe></td>\r\n";
						?></td>
					</tr>
					<tr class="tmain">
						<td class="label">Secondary Rates</td>
						<td class="label" colspan="2"><?php 
						if( $error != "" )
						{
							echo $error;
						} else {
							echo "Successful";
						}
						
			//				echo "<iframe class=\"embed\" src=\"../assets/calculate_rates/calc_rates.php?version=".$version_sim."&year=".$year."&month=".$month."&verbose=0&calc_only=1\"></iframe></td>\r\n";
						?></td>
					</tr>
            		<tr class="tmain">
						<td class="label">ETL Rules</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<?php
							$versionpass = $this->session->userdata('temp_version_noalias');
							?>
							<a href="#" onclick="grun('[<?php echo $versionpass; ?>]', '#etlstatus'); return false;" id="r_etl" >Run ETL</a>
							<a href="#" onclick="gstat('<?php //echo $id; ?>', '#etlstatus'); return false;" id="s_etl" style="display:none;" >Update Status</a>
						</td>
					</tr>
            	</table>
            </div>
            <?php
            } // end step 3.
            ?>
            
        </td>
    </tr>
    <tr>
        <td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
    </tr>
</table>

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