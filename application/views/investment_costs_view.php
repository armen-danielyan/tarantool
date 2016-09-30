<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Investment Costs</title>
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
		active: 3
    });
    $( "#filter_menu" ).accordion({
        heightStyle: "content",
        collapsible: false
    });
	
	$( document ).tooltip({
		track: true
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('investment_costs'); ?>">
                        <div class="filter_div">
                        	<select name="version_asis" onchange="this.form.submit();" class="ddown1" title="Select 1st Version">
                            <?php
                                foreach($form_version_asis as $row)
                                {
                                    $depth = '';
                                    for($i=1; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($version_asis == $row['element']){ $n_version_asis = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        <div class="filter_div">
                        	<select name="version_tobe" onchange="this.form.submit();" class="ddown1" title="Select 2nd Version">
                            <?php
                                foreach($form_version_tobe as $row)
                                {
                                    $depth = '';
                                    for($i=1; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($version_tobe == $row['element']){ $n_version_tobe = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        <div class="filter_div">
                            <select name="year" onchange="this.form.submit();" class="ddown1" title="Select Year">
                            <?php
                                foreach($form_year as $row)
                                {
                                    $depth = '';
                                    for($i=1; $i<$row['depth']; $i++)
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
                            <select name="resource" onchange="this.form.submit();" class="ddown1" title="Select Resource">
                            <?php
                                foreach($form_resource as $row)
                                {
                                    $depth = '';
                                    //$depth_fix = $row['depth'] - 1; // the value to subtact here depends on the depth of the first element when filtered
                                    for($i=1; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($resource == $row['element']){ $n_resource = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Investment Costs</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
			
			<div class="tabber">
                <div class="tabbertab">
                    <h3>Investment Cost</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"></div></td>
                    	</tr>
                    </table>
                    
            </div>
			
			<div class="content_div">	
				<table id="tb1" class="avtable_2 controller">
					<tr data-level="header" class="header">
						<td>&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td class="thead">
                    		<?php
                    		foreach($form_version_asis as $row)
                            {
                            	if($version_asis == $row['element'])
                            	{
                            		echo $row['name_element'];
                            	}
							}
                    		?>
                    	</td>
						<td class="thead">
							<?php
                    		foreach($form_version_tobe as $row)
                            {
                            	if($version_tobe == $row['element'])
                            	{
                            		echo $row['name_element'];
                            	}
							}
                    		?>
						</td>
						<td class="thead">Variance</td>
						
					</tr>
					<?php
						// total cost
						$depth_stack = array();
						//manual entries for preset stuffs
						//$depth_stack[] = "1_1";
						$a1 = $b1 = $c1 = 0;
						foreach($table1a_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version_tobe == $paths[0])
							{
								$b1 = $row['value'];
							}
							if($version_asis == $paths[0])
							{
								$a1 = $row['value'];
							}
						}
						foreach($table1b_data as $row)
						{
							$paths = explode(",", $row['path']);
							if($version_asis == $paths[0])
							{
								$a1 += $row['value'];
							}
							if($version_tobe == $paths[0])
							{
								$b1 += $row['value'];
							}
						}
						
						
						$c1 = $b1 - $a1;
						
					?>
					<tr data-level="header" class="header">
						<td class="label">Total Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($a1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b1, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c1, 0, ".", ","); ?></td>
						
					</tr>
					<?php
						//account element drilldown
						foreach($account_element_set_alias as $row)
						{
							$a = $b = $c = 0;
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
							
							foreach($table2a_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version_tobe == $paths[0] && $row['element'] == $paths['4'])
								{
									$b = $drow1['value'];
								}
								if($version_asis == $paths[0] && $row['element'] == $paths['4'])
								{
									$a = $drow1['value'];
								}
								
							}
							
							$c = $b - $a;
							
							if($a == 0 && $b == 0 && $c == 0)
							{
								//do nothing
							} 
							else
							{
					?>		
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
						<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($a, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c, 0, ".", ","); ?></td>
					</tr>
					<?php
							}
						}
						
					?>
					<?php
						// sender dropdown
						foreach($sender_set_alias as $row)
						{
							$a = $b = $c = 0;
							$depth = '';
							$arrow = '';
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
							
							foreach($table3a_data as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($version_tobe == $paths[0] && $row['element'] == $paths['5'])
								{
									$b = $drow1['value'];
								}
								if($version_asis == $paths[0] && $row['element'] == $paths['5'])
								{
									$a = $drow1['value'];
								}
								
							}
							$c = $b - $a;
							
							if($a == 0 && $b == 0 && $c == 0 )
							{
								//do nothing
							} 
							else
							{
					?>	
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
						<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
						<td style="min-width:20px !important; text-align:center !important;"><?php echo $arrow; ?></td>
                    	<td ><?php echo CUR_SIGN." ".number_format($a, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($b, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($c, 0, ".", ","); ?></td>
					</tr>
					<?php
							}
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
    var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
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

</body>
</html>