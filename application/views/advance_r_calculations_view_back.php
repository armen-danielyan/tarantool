<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Advance R Calculations</title>
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
        active: 4
    });
    $( "#filter_menu" ).accordion({
        heightStyle: "content",
        collapsible: false
    });
    
    $( document ).tooltip({
        track: true
    });
    
    //$('#tb1 tr.tmain').click( function() {
	//	var wcell = this.rowIndex;
	//	$("#tb1 tr:eq("+wcell+")").nextAll('tr').each( function() {
	//		if ($(this).hasClass('tmain') || $(this).hasClass('tmain1')) {
	//			return false;
	//		}
	//		$(this).toggle();
	//	});
	//	
	//});
	
	//$('#tb1 tr.tmain').each(function() {
	//	$(this).nextAll('tr').each( function() {
	//		if ($(this).hasClass('tmain') || $(this).hasClass('tmain1')) {
	//			return false;
	//		}
	//		$(this).toggle();
	//	});
	//});
    function sortTable(){
	    var tbl = document.getElementById("tb1").tBodies[0];
	    var store = [];
	    for(var i=0, len=tbl.rows.length; i<len; i++){
	        var row = tbl.rows[i];
	        var sortnr = parseFloat(row.cells[2].textContent || row.cells[2].innerText);
	        if(!isNaN(sortnr)) store.push([sortnr, row]);
	    }
	    store.sort(function(x,y){
	        return y[0] - x[0];
	    });
	    for(var i=0, len=store.length; i<len; i++){
	        tbl.appendChild(store[i][1]);
	    }
	    store = null;
	}
    sortTable();
    
    $( "#dialog-form" ).dialog({
      autoOpen: false,
      width: 350,
      modal: true
    });
    var rcount = 0;
    $('#tb1 tr').each(function() {
    	rcount += 1;
    	if(rcount > 21)
    	{
    		$(this).remove();
    	}
    	
    });
    
    $("#run_etl").submit(function(event) {
	  /* stop form from submitting normally */
	  	event.preventDefault();
	  	var Description = $("#Description").val();
	  	var Date_From  = $("#Date_From").val();
	  	var Date_To   = $("#Date_To").val();
	  	var values = $(this).serialize();
	  	var errormsg = "";
	  	
		Date_From = parseInt( Date_From );
		Date_To   = parseInt( Date_To );
		
	  	//validate data;
	  	if(Description.length > 30 || Description.length < 3)
	  	{
	  		errormsg += "The lenght of Description must be between 3 and 30 characters long. ";
	  	}
	  	if(Date_From >= Date_To)
	  	{
	  		errormsg += "The Start Date " + Date_From + "and Stop Date " + Date_To + "must not be equal and the Stop Date must always be ahead of Start date. ";
	  	}
	  	
	  	if(errormsg != "")
	  	{
	  		alert(errormsg+"Please check data again.");
	  	} 
	  	else
	  	{
	  		//alert(Description+Date_From+Date_To+values);
	  		$.ajax({
	      		url: "<?php echo site_url("advance_r_calculations/execute"); ?>",
	      		type: "post",
	      		data: values,
	      		success: function(result){
	      			//alert(result);
	      		}
	  		});
	  		$( "#dialog-form" ).dialog( "close" );
	  		alert("Request in progress. Once the job is complete it will be selectable in the sidebar filters.");
	  	}
    });
    
    $( "#showform" ).on( "click", function() {
      $( "#dialog-form" ).dialog( "open" );
    });
    
});

function checkLength( o, n, min, max ) {
  if ( o.val().length > max || o.val().length < min ) {
    return false;
  } else {
    return true;
  }
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('advance_r_calculations'); ?>">
                        <!-- 
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
							<option value="<?php echo $row['element']; ?>" <?php if($version == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        -->
                        <div class="filter_div">
                            
                            <select name="equipment" onchange="this.form.submit();" class="ddown1" title="Select Equipment">
                            <?php
                                foreach($form_equipment as $row)
                                {
                                    $depth = '';
                                    for($i=1; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($equipment == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        <div class="filter_div">
                            
                            <select name="run_number" onchange="this.form.submit();" class="ddown1" title="Select Run Number">
                            <?php
                            	$runcount = 1; //initial value set to 1 to make sure its always 1 value more than the actual.
                                foreach($form_run_number as $row)
                                {
                                	$runcount += 1;
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($run_number == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
						
                        <div class="filter_div">
                            
                            <select name="mfg_kpi_1" onchange="this.form.submit();" class="ddown1" title="Select 1st Parameter">
                            <?php
                                foreach($form_mfg_kpi_1 as $row)
                                {
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>
							<option value="<?php echo $row['element']; ?>" <?php if($mfg_kpi_1 == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        <div class="filter_div">
                            
                            <select name="mfg_kpi_2" onchange="this.form.submit();" class="ddown1" title="Select 2nd Parameter">
                            <?php
                                foreach($form_mfg_kpi_2 as $row)
                                {
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>
								<option value="<?php echo $row['element']; ?>" <?php if($mfg_kpi_2 == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
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
                    
                </div>
            </div>
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Advance R Calculations</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Correlation</h3>
                    <table>
                    	<tr>
                    		<td>
                    			<div class="filter_div left">
                            
		                            <select name="m_stat1" onchange="this.form.submit();" class="ddown1" title="Select Chart 1st Parameter">
		                            <?php
		                                foreach($form_m_stat1 as $row)
		                                {
		                                    $depth = '';
		                                    for($i=0; $i<$row['depth']; $i++)
		                                    {
		                                        $depth .= '&nbsp;&nbsp;';
		                                    }
		                            ?>  
		                                <option value="<?php echo $row['element']; ?>" <?php if($m_stat1 == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
		                            <?php   
		                                }
		                            ?>
		                            </select>
		                        </div>
		                        
		                        <div class="filter_div left">
		                            
		                            <select name="m_stat2" onchange="this.form.submit();" class="ddown1" title="Select Chart 2nd Parameter">
		                            <?php
		                                foreach($form_m_stat2 as $row)
		                                {
		                                    $depth = '';
		                                    for($i=0; $i<$row['depth']; $i++)
		                                    {
		                                        $depth .= '&nbsp;&nbsp;';
		                                    }
		                            ?>  
		                                <option value="<?php echo $row['element']; ?>" <?php if($m_stat2 == $row['element']){ ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
		                            <?php   
		                                }
		                            ?>
		                            </select>
		                        </div>
                    		</td>
                    	</tr>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"></div></td>
                    	</tr>
                    </table>
                </div>
            </div>
            </form>
            <div class="content_div">
            	<?php
            		foreach ($run_number_alias_elements as $attri) {
            			foreach ($run_number_attributes_data as $aval)
            			{
            				$path = explode(",", $aval['path']);
							if($attri['element'] == $path[0])
							{
								$nname = str_replace(' ', '_', $attri['name_element']);
								${$nname} = $aval['value'];
							}
            			}
						
					}
            	?>
                <table id="tb0" class="avtable_2">
                	<tr>
                		<td class="label">Run Description:</td>
                		<td><?php echo $Description; ?></td>
                		<td class="label">Run Date:</td>
                		<td><?php echo $Run_Date; ?></td>
                		<td class="label">Run time:</td>
                		<td><?php echo $Run_Time ; ?></td>
                		<td class="label">User:</td>
                		<td><?php echo $User; ?></td>
                	</tr>
                	<tr>
                		<td class="label" >Start Date:</td>
                		<td><?php echo $Date_From; ?></td>
                		<td class="label" >Stop Date:</td>
                		<td><?php echo $Date_To; ?></td>
                		<td class="label">Average</td>
                		<td id='avg'>xxxxx</td>
                		<td class="label"><a href="#" onclick="return false;" id="showform">Run new ETL</a></td>
                		<td>&nbsp;</td>
                	</tr>
                </table>
                
                <table id="tb1" class="avtable_2">
					<tr>
						<td class="thead">Equipment</td>
						<td class="thead">Parameter 1</td>
						<td class="thead">Parameter 2</td>
						<td class="thead">Correlation</td>
					</tr>
					<?php
					$avgtot   = 0;
					$avgcount = 0;
					$avg      = 0;
					$mfg_kpi_1_base = $this->jedoxapi->dimension_elements_base( $mfg_kpi_1_alias );
					$mfg_kpi_2_base = $this->jedoxapi->dimension_elements_base( $mfg_kpi_2_alias );
					
					foreach($mfg_kpi_1_base as $row)
					{
						if($row['name_element'] != 'All')
						{
							$val = 0;
							$count = 0;
							foreach($mfg_kpi_2_base as $row1)
							{
								if($row1['name_element'] != 'All')
								{
								
									foreach($table1_data as $row2)
									{
										$paths = explode(",", $row2['path']);
										if($row['element'] == $paths[3] && $row1['element'] == $paths[4])
										{
											$val = $row2['value'];
											$count += 1;
											$avgtot += $row2['value'];
											$avgcount += 1;
										}
									}
					?>
					<tr <?php if($count == 1){ /*echo "class='tmain'";*/ } ?> >
						<td class="label"><?php echo "Equipment" ?></td>
						<td class="label"><?php echo $row['name_element']; ?></td>
						<td class="label"><?php echo $row1['name_element']; ?></td>
						<td ><?php echo number_format($val, 4, ".", ","); ?></td>
					</tr>
					<?php	
								}
							}
						}
					}
					if($avgcount != 0)
					{
						$avg = $avgtot/$avgcount;
						$avg = number_format($avg, 4, ".", ",");
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

<div id="dialog-form" title="Run New ETL">
  <form id="run_etl">
    
      <label for="Description" style="display: block">Description</label>
      <input type="text" name="Description" id="Description" value="ETL Description here" class="text ui-widget-content ui-corner-all" style="display: block; margin-bottom:12px; width:95%; padding: .4em;" maxlength="30">
      <div class="clearfix"></div>
      
      <label for="version" style="display: block">Version</label>
      
      <select name="version" title="Version" class="text ui-widget-content ui-corner-all" style="display: block; margin-bottom:12px; width:95%; padding: .4em;">
      	<?php
            foreach($form_version as $row)
            {
        ?>  
            <option value="<?php echo $row['element']; ?>"><?php echo $row['name_element']; ?></option>
        <?php   
            }
        ?>
      </select>
      <div class="clearfix"></div>
      
      <label for="Date_From" style="display: block">Start Date</label>
      <select id="Date_From" name="Date_From" title="Start date" class="text ui-widget-content ui-corner-all" style="display: block; margin-bottom:12px; width:95%; padding: .4em;">
      	<?php
      		foreach($date_base as $row)
      		{
      	?>		
			<option value="<?php echo $row['element']; ?>"><?php echo $row['name_element']; ?></option>
		<?php		
      		}
      	?>
      </select>
      <div class="clearfix"></div>
      <label for="Date_To" style="display: block">Stop Date</label>
      <select id="Date_To" name="Date_To" title="Stop date" class="text ui-widget-content ui-corner-all" style="display: block; margin-bottom:12px; width:95%; padding: .4em;">
	  <?php
      		$date_base_count = count($date_base);
			$dbc = 0;
      		foreach($date_base as $row)
      		{
      			$dbc += 1;
      	?>
			<option value="<?php echo $row['element']; ?>" <?php if($date_base_count == $dbc){ ?>selected="selected"<?php } ?> ><?php echo $row['name_element']; ?></option>
		<?php		
      		}
      	?>
      </select>
      <div class="clearfix"></div>
      
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input name="submit" type="submit" id="submit" value="Run ETL" class="right" />
    
  </form>
</div>

<script type="text/javascript">

    var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1xml; ?>"+"</chart>");
    myChart1.render("chartContainer1");
    
    $("#avg").html("<?php echo $avg; ?>");
    
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
	//echo "<pre>";
	//print_r($run_number_attributes_data);
	//echo "</pre>";
?>
<div id="dialog-message" title="Chart Pinned" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
		<span id="pnchart"></span> now pinned to your home.
	</p>
</div>
</body>
</html>