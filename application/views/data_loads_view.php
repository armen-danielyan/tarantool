<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Data Load</title>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/FusionCharts.js"></script>
<link href='http://fonts.googleapis.com/css?family=Cuprum:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/smoothness/jquery-ui-1.9.1.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" media="screen" />
<script type="text/javascript">

$(document).ready(function() {
    $( "#sidebar_menu" ).accordion({
        active: 4,
        heightStyle: "content"
    });
    
    $( document ).tooltip({
        track: true
    });
    
    $("#dloadform").submit(function(event) {

	  /* stop form from submitting normally */
	  	event.preventDefault();
	
	  	/*clear result div*/
		$("#result").html('');
	
	  	/* get some values from elements on the page: */
	   	var values = $(this).serialize();
	
	  /* Send the data using post and put the results in a div */
	    $.ajax({
	      url: "<?php echo site_url("data_loads/execute"); ?>",
	      type: "post",
	      data: values,
	      success: function(result){
	          //$("#result").html(result);
	          var dset = eval('(' + result + ')');
	          if(dset.edata1 != '' && dset.eid1 != ''){
	          	$("#es1").html(dset.edata1);
	          	$("#es1a").html("<a href='#' onclick=\"gstat('"+dset.eid1+"', '#es1'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es1").html("");
	          	$("#es1a").html("");
	          }
	          if(dset.edata2 != '' && dset.eid2 != ''){
	          	$("#es2").html("(Receiver) "+dset.edata2);
	          	$("#es2a").html("<a href='#' onclick=\"gstat('"+dset.eid2+"', '#es2'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es2").html("");
	          	$("#es2a").html("");
	          }
	          if(dset.edata3 != '' && dset.eid3 != ''){
	          	$("#es3").html("(Sender) "+dset.edata3);
	          	$("#es3a").html("<a href='#' onclick=\"gstat('"+dset.eid3+"', '#es3'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es3").html("");
	          	$("#es3a").html("");
	          }
	          if(dset.edata4 != '' && dset.eid4 != ''){
	          	$("#es4").html(dset.edata4);
	          	$("#es4a").html("<a href='#' onclick=\"gstat('"+dset.eid4+"', '#es4'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es4").html("");
	          	$("#es4a").html("");
	          }
	          if(dset.edata5 != '' && dset.eid5 != ''){
	          	$("#es5").html(dset.edata5);
	          	$("#es5a").html("<a href='#' onclick=\"gstat('"+dset.eid5+"', '#es5'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es5").html("");
	          	$("#es5a").html("");
	          }
	          if(dset.edata6 != '' && dset.eid6 != ''){
	          	$("#es6").html(dset.edata6);
	          	$("#es6a").html("<a href='#' onclick=\"gstat('"+dset.eid6+"', '#es6'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es6").html("");
	          	$("#es6a").html("");
	          }
	          if(dset.edata7 != '' && dset.eid7 != ''){
	          	$("#es7").html(dset.edata7);
	          	$("#es7a").html("<a href='#' onclick=\"gstat('"+dset.eid7+"', '#es7'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es7").html("");
	          	$("#es7a").html("");
	          }
	          if(dset.edata8 != '' && dset.eid8 != ''){
	          	$("#es8").html(dset.edata8);
	          	$("#es8a").html("<a href='#' onclick=\"gstat('"+dset.eid8+"', '#es8'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es8").html("");
	          	$("#es8a").html("");
	          }
	          if(dset.edata9 != '' && dset.eid9 != ''){
	          	$("#es9").html(dset.edata9);
	          	$("#es9a").html("<a href='#' onclick=\"gstat('"+dset.eid9+"', '#es9'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es9").html("");
	          	$("#es9a").html("");
	          }
	          if(dset.edata10 != '' && dset.eid10 != ''){
	          	$("#es10").html(dset.edata10);
	          	$("#es10a").html("<a href='#' onclick=\"gstat('"+dset.eid10+"', '#es10'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es10").html("");
	          	$("#es10a").html("");
	          }
	          if(dset.edata11 != '' && dset.eid11 != ''){
	          	$("#es11").html(dset.edata11);
	          	$("#es11a").html("<a href='#' onclick=\"gstat('"+dset.eid11+"', '#es11'); return false;\" >Get Status</a>");
	          } else {
	          	$("#es11").html("");
	          	$("#es11a").html("");
	          }
	      },
	      error:function(){
	          $("#result").html('Sorry, An error has occurred.');
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

function gstat(pid, loc){
	
	var mydataset = {id: pid};
	
	$.ajax({
		url:"<?php echo site_url('data_loads/gstatus'); ?>",
		type: "post",
	    data: mydataset,
		success:function(result){
		$(loc).html(result);
	}});
}

</script>
<style>
	.tdmid{
		vertical-align:middle !important;
	}
</style>
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Data Load</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            
			<div class="content_div">
				
				<form id='dloadform'>
					
					<h3>Load Master Data from Load Sheets</h3><br />
					<table>
						<tr>
							<td class="tdmid"><input type="checkbox" name="account_elements" value="1"></td>
							<td width="250" class="tdmid">Account Elements</td>
							<td width="150" id="es1" class="tdmid"></td>
							<td width="150" id="es1a" class="tdmid text_right"></td>
						</tr>
						<tr>
							<td class="tdmid"><input type="checkbox" name="senders_receivers" value="1"></td>
							<td width="250" class="tdmid">Senders and Receivers </td>
							<td width="150" id="es2" class="tdmid"></td>
							<td width="150" id="es2a" class="tdmid text_right"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td width="250" class="tdmid">&nbsp;</td>
							<td width="150" id="es3" class="tdmid"></td>
							<td width="150" id="es3a" class="tdmid text_right"></td>
						</tr>
					</table>
					<br /><br />
					<h3>Load Transactional Data From Load Sheets</h3><br />
					<table>
						<tr>
							<td class="tdmid"><input type="checkbox" name="plan_cap_rates" value="1"></td>
							<td width="250" class="tdmid">Plan: Capacity and Rates</td>
							<td width="150" id="es4" class="tdmid"></td>
							<td width="150" id="es4a" class="tdmid text_right"></td>
						</tr>
						<tr>
							<td class="tdmid"><input type="checkbox" name="plan_pri_cost" value="1"></td>
							<td width="250" class="tdmid">Plan: Primary Costs</td>
							<td width="150" id="es5" class="tdmid"></td>
							<td width="150" id="es5a" class="tdmid text_right"></td>
						</tr>
						<tr>
							<td class="tdmid"><input type="checkbox" name="plan_sec_con" value="1"></td>
							<td width="250" class="tdmid">Plan: Secondary Consumption</td>
							<td width="150" id="es6" class="tdmid"></td>
							<td width="150" id="es6a" class="tdmid text_right"></td>
						</tr>
					</table>
					<br /><br />
					<h3>Load Transactional Data From Source Systems</h3><br />
					Year: <select name="year" title="Select Year">
						<?php
                            foreach($year_elements as $row)
                            {
                        ?>  
                            <option value="<?php echo $row['element']; ?>" ><?php echo $row['name_element']; ?></option>
                        <?php   
                            }
                        ?>
					</select>
					Month: <select name="month" title="Select Month">
						<option value="01">January</option>
						<option value="02">February</option>
						<option value="03">March</option>
						<option value="04">April</option>
						<option value="05">May</option>
						<option value="06">June</option>
						<option value="07">July</option>
						<option value="08">August</option>
						<option value="09">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
					</select><br /><br />
					<table>
						<tr>
							<td class="tdmid"><input type="checkbox" name="act_prim_gp" value="1"></td>
							<td width="250" class="tdmid">Actual: Primary Cost from Dynamics GP</td>
							<td width="150" id="es7" class="tdmid"></td>
							<td width="150" id="es7a" class="tdmid text_right"></td>
						</tr>
						<tr>
							<td class="tdmid"><input type="checkbox" name="act_rev_gp" value="1"></td>
							<td width="250" class="tdmid">Actual: Revenue from Dynamics GP</td>
							<td width="150" id="es8" class="tdmid"></td>
							<td width="150" id="es8a" class="tdmid text_right"></td>
						</tr>
						<tr>
							<td class="tdmid"><input type="checkbox" name="act_con_sf" value="1"></td>
							<td width="250" class="tdmid">Actual: Consumptions from Salesforce</td>
							<td width="150" id="es9" class="tdmid"></td>
							<td width="150" id="es9a" class="tdmid text_right"></td>
						</tr>
						<tr>
							<td class="tdmid"><input type="checkbox" name="act_con_ssr" value="1"></td>
							<td width="250" class="tdmid">Actual: Consumptions from SSR</td>
							<td width="150" id="es10" class="tdmid"></td>
							<td width="150" id="es10a" class="tdmid text_right"></td>
						</tr>
						<tr>
							<td class="tdmid"><input type="checkbox" name="act_con_ch" value="1"></td>
							<td width="250" class="tdmid">Actual: Consumptions from Clearing House</td>
							<td width="150" id="es11" class="tdmid"></td>
							<td width="150" id="es11a" class="tdmid text_right"></td>
						</tr>
					</table>
					<br /><br />
					<input type="submit" value="Start Data Load"> 
				</form>
				<div id="result"></div>
				
				
			</div>
            
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
</body>
</html>