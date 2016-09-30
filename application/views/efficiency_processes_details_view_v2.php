<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Efficiency Processes Details</title>
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('efficiency_processes_details_v2'); ?>">
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
                                foreach($form_month as $row)
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
                                foreach($form_receiver as $row)
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
                            <div class="left">
                                <?php //echo mailto("?to=&subject=&body=".site_url('efficiency_processes_details')."/info/".$n_year."/".url_title($n_month, '_')."/".url_title($n_receiver, '_')."", "<span class='ui-icon ui-icon-mail-closed left' ></span>", array("title" =>"Share this page via email")); 
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > ".anchor('efficiency_processes_v2', 'Processes', array('title' => 'View Efficiency Processes'))." > <span class='orange'>Details</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Cost</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" ></div></td>
                    	</tr>
                    </table>
                </div>
                <div class="tabbertab">
                    <h3>Consumption Quantities</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer2" ></div></td>
                    	</tr>
                    </table>
                </div>
                <div class="tabbertab">
                    <h3>Cost Recovery</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer3" ></div></td>
                    	</tr>
                    </table>
                </div>
            </div>
            
            <div class="content_div">
                
                <table id="tb1" class="avtable_2 controller">
                	<tr data-level="header" class="header">
						<td>&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td class="center" colspan="4">Total Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="center" colspan="3">Fixed Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="center" colspan="3">Proportional Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="center" colspan="4">Consumption/Output</td>
					</tr>
					<tr data-level="header" class="header">
						<td>&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead" style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td class="thead">Actual</td>
						<td class="thead">Target</td>
						<td class="thead">Plan</td>
						<td class="thead">Act. Tar. Variance</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead" style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead">Actual</td>
						<td class="thead">Target</td>
						<td class="thead">Plan</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead" style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead">Actual</td>
						<td class="thead">Target</td>
						<td class="thead">Plan</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead" style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td class="thead">Actual</td>
						<td class="thead">Plan</td>
						<td class="thead">Act. Plan Variance</td>
						<td class="thead">UoM</td>
					</tr>
                    <?php
                    $depth_stack = array();
					$depth_stack[] = "1_1";
					$depth_stack[] = "1_2";
					
                    $a1 = $b1 = $c1 = $d1 = $e1 = $f1 = $g1 = $h1 = $i1 = $j1 = $k1 = $l1 = $m1 = 0;
					$a2 = $b2 = $c2 = $d2 = $e2 = $f2 = $g2 = $h2 = $i2 = $j2 = $k2 = $l2 = $m2 = 0;
					//secondary cost
                    foreach ($table1a_data as $row) {
                        $paths = explode(",", $row['path']);
						if($version_a == $paths[0])
						{
							$a1 = $row['value'];
						}
						if($version_t == $paths[0])
						{
							$b1 = $row['value'];
						}
						if($version_p == $paths[0])
						{
							$c1 = $row['value'];
						}
						if($version_AT == $paths[0])
						{
							$d1 = $row['value'];
						}
                    }
                    foreach ($table1b_data as $row) {
                        $paths = explode(",", $row['path']);
						if($version_a == $paths[0])
						{
							$e1 = $row['value'];
						}
						if($version_t == $paths[0])
						{
							$f1 = $row['value'];
						}
						if($version_p == $paths[0])
						{
							$g1 = $row['value'];
						}
                    }
					foreach ($table1c_data as $row) {
                        $paths = explode(",", $row['path']);
						if($version_a == $paths[0])
						{
							$h1 = $row['value'];
						}
						if($version_t == $paths[0])
						{
							$i1 = $row['value'];
						}
						if($version_p == $paths[0])
						{
							$j1 = $row['value'];
						}
                    }
					foreach ($table1d_data as $row) {
                        $paths = explode(",", $row['path']);
						if($version_a == $paths[0])
						{
							$k1 = $row['value'];
						}
						if($version_p == $paths[0])
						{
							$l1 = $row['value'];
						}
						if($version_AP == $paths[0])
						{
							$m1 = $row['value'];
						}
                    }
					$arrow = $arrow1 = $arrow2 = ''; // initialize arrow handlers
					
					if($b1 != 0 && $d1 < 0)
					{
						$atest = abs( ($b1-$a1) / $b1);
						if($atest > 0.05 && $atest < 0.1)
						{
							$arrow1 = "<img src='".base_url()."assets/images/amber_up.png' />";
						}
						if($atest >= 0.1)
						{
							$arrow1 = "<img src='".base_url()."assets/images/red_up.png' />";
						}
					}
					
					//recovery
					foreach ($table3a_data as $row) {
                        $paths = explode(",", $row['path']);
						if($version_a == $paths[0])
						{
							$a2 = $row['value'];
						}
						if($version_t == $paths[0])
						{
							$b2 = $row['value'];
						}
						if($version_p == $paths[0])
						{
							$c2 = $row['value'];
						}
						if($version_AT == $paths[0])
						{
							$d2 = $row['value'];
						}
                    }
                    foreach ($table3b_data as $row) {
                        $paths = explode(",", $row['path']);
						if($version_a == $paths[0])
						{
							$e2 = $row['value'];
						}
						if($version_t == $paths[0])
						{
							$f2 = $row['value'];
						}
						if($version_p == $paths[0])
						{
							$g2 = $row['value'];
						}
                    }
					foreach ($table3c_data as $row) {
                        $paths = explode(",", $row['path']);
						if($version_a == $paths[0])
						{
							$h2 = $row['value'];
						}
						if($version_t == $paths[0])
						{
							$i2 = $row['value'];
						}
						if($version_p == $paths[0])
						{
							$j2 = $row['value'];
						}
                    }
					foreach ($table3d_data as $row) {
                        $paths = explode(",", $row['path']);
						if($version_a == $paths[0])
						{
							$k2 = $row['value'];
						}
						if($version_p == $paths[0])
						{
							$l2 = $row['value'];
						}
						if($version_AP == $paths[0])
						{
							$m2 = $row['value'];
						}
                    }
					
					if($b2 != 0 && $d2 > 0)
					{
						$atest = abs( ($b2-$a2) / $b2);
						if($atest > 0.05 && $atest < 0.1)
						{
							$arrow2 = "<img src='".base_url()."assets/images/amber_down.png' />";
						}
						if($atest >= 0.1)
						{
							$arrow2 = "<img src='".base_url()."assets/images/red_down.png' />";
						}
					}
					
                    ?>
                    <tr class="tmain" data-level="1" id="level_1_1">
						<td class="label"><span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span> Secondary Cost</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;"><?php echo $arrow1; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($a1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($b1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($c1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($d1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($e1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($f1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($g1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($h1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($i1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($j1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo number_format($k1, 0, ".", ","); ?></td>
                    	<td><?php echo number_format($l1, 0, ".", ","); ?></td>
                    	<td><?php echo number_format($m1, 0, ".", ","); ?></td>
						<td>&nbsp;</td>
					</tr>
                    <?php
                    	//secondary drilldown
                    	foreach($sender_dd_alias as $row)
                    	{
                    		$a = $b = $c = $d = $e = $f = $g = $h = $i = $j = $k = $l = $m = 0;
                    		$arrow = '';
							$depth = '';
							$true_depth = 2; 
							$sub_depth = 1;
							$uom = '';
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
                            
                    		foreach ($table2a_data as $drow) {
								$paths = explode(",", $drow['path']);
								if($version_a == $paths[0] && $row['element'] == $paths[4])
								{
									$a = $drow['value'];
								}
								if($version_t == $paths[0] && $row['element'] == $paths[4])
								{
									$b = $drow['value'];
								}
								if($version_p == $paths[0] && $row['element'] == $paths[4])
								{
									$c = $drow['value'];
								}
								if($version_AT == $paths[0] && $row['element'] == $paths[4])
								{
									$d = $drow['value'];
								}
								
							}
							foreach ($table2b_data as $drow) {
								$paths = explode(",", $drow['path']);
								if($version_a == $paths[0] && $row['element'] == $paths[4])
								{
									$e = $drow['value'];
								}
								if($version_t == $paths[0] && $row['element'] == $paths[4])
								{
									$f = $drow['value'];
								}
								if($version_p == $paths[0] && $row['element'] == $paths[4])
								{
									$g = $drow['value'];
								}
							}
							foreach ($table2c_data as $drow) {
								$paths = explode(",", $drow['path']);
								if($version_a == $paths[0] && $row['element'] == $paths[4])
								{
									$h = $drow['value'];
								}
								if($version_t == $paths[0] && $row['element'] == $paths[4])
								{
									$i = $drow['value'];
								}
								if($version_p == $paths[0] && $row['element'] == $paths[4])
								{
									$j = $drow['value'];
								}
							}
							foreach ($table2d_data as $drow) {
								$paths = explode(",", $drow['path']);
								if($version_a == $paths[0] && $row['element'] == $paths[4])
								{
									$k = $drow['value'];
								}
								if($version_p == $paths[0] && $row['element'] == $paths[4])
								{
									$l = $drow['value'];
								}
								if($version_AP == $paths[0] && $row['element'] == $paths[4])
								{
									$m = $drow['value'];
								}
							}
							
							foreach($cells_sender_attributes as $drow)
							{
								$paths = explode(",", $drow['path']);
								if($row['element'] == $paths[1])
								{
									$uom = $drow['value'];
								}
							}
							
							if($b != 0 && $d < 0)
							{
								$atest = abs( ($b-$a) / $b);
								if($atest > 0.05 && $atest < 0.1)
								{
									$arrow = "<img src='".base_url()."assets/images/amber_up.png' />";
								}
								if($atest >= 0.1)
								{
									$arrow = "<img src='".base_url()."assets/images/red_up.png' />";
								}
							}
							
							// remove 0 lines
							if($a == 0 && $b == 0 && $c == 0 && $d == 0 && $e == 0 && $f == 0 && $g == 0 && $h == 0 && $i == 0 && $j == 0 && $k == 0 && $l == 0 && $m == 0)
							{
								//do nothing
							} 
							else
							{
					?>		
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
						<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;"><?php echo $arrow; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($a, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($b, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($c, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($d, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($e, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($f, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($g, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($h, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($i, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($j, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo number_format($k, 0, ".", ","); ?></td>
                    	<td><?php echo number_format($l, 0, ".", ","); ?></td>
                    	<td><?php echo number_format($m, 0, ".", ","); ?></td>
						<td><?php echo $uom; ?></td>
					</tr>	
					<?php
							}	
                    	}
                    ?>
                    <tr class="tmain" data-level="1" id="level_1_2">
						<td class="label"><span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span> Recovery</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;"><?php echo $arrow2; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(($a2)*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(($b2)*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(($c2)*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(($d2)*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format(($e2)*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(($f2)*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(($g2)*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format(($h2)*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(($i2)*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format(($j2)*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo number_format(($k2)*-1, 0, ".", ","); ?></td>
                    	<td><?php echo number_format(($l2)*-1, 0, ".", ","); ?></td>
                    	<td><?php echo number_format(($m2)*-1, 0, ".", ","); ?></td>
						<td>&nbsp;</td>
					</tr>
                    
                    <?php
                    	//recovery drilldown
                    	foreach($receiver_dd_alias as $row)
                    	{
                    		$a = $b = $c = $d = $e = $f = $g = $h = $i = $j = $k = $l = $m = 0;
                    		$arrow = '';
							$depth = '';
							$true_depth = 2; 
							$sub_depth = 1;
							$uom = '';
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
                            
                    		foreach ($table4a_data as $drow) {
								$paths = explode(",", $drow['path']);
								if($version_a == $paths[0] && $row['element'] == $paths[5])
								{
									$a = $drow['value'];
								}
								if($version_t == $paths[0] && $row['element'] == $paths[5])
								{
									$b = $drow['value'];
								}
								if($version_p == $paths[0] && $row['element'] == $paths[5])
								{
									$c = $drow['value'];
								}
								if($version_AT == $paths[0] && $row['element'] == $paths[5])
								{
									$d = $drow['value'];
								}
								
							}
							foreach ($table4b_data as $drow) {
								$paths = explode(",", $drow['path']);
								if($version_a == $paths[0] && $row['element'] == $paths[5])
								{
									$e = $drow['value'];
								}
								if($version_t == $paths[0] && $row['element'] == $paths[5])
								{
									$f = $drow['value'];
								}
								if($version_p == $paths[0] && $row['element'] == $paths[5])
								{
									$g = $drow['value'];
								}
							}
							foreach ($table4c_data as $drow) {
								$paths = explode(",", $drow['path']);
								if($version_a == $paths[0] && $row['element'] == $paths[5])
								{
									$h = $drow['value'];
								}
								if($version_t == $paths[0] && $row['element'] == $paths[5])
								{
									$i = $drow['value'];
								}
								if($version_p == $paths[0] && $row['element'] == $paths[5])
								{
									$j = $drow['value'];
								}
							}
							foreach ($table4d_data as $drow) {
								$paths = explode(",", $drow['path']);
								if($version_a == $paths[0] && $row['element'] == $paths[5])
								{
									$k = $drow['value'];
								}
								if($version_p == $paths[0] && $row['element'] == $paths[5])
								{
									$l = $drow['value'];
								}
								if($version_AP == $paths[0] && $row['element'] == $paths[5])
								{
									$m = $drow['value'];
								}
							}
							
							foreach($cells_receiver_attributes as $drow)
							{
								$paths = explode(",", $drow['path']);
								//if($row['element'] == $paths[1])
								//{
								//	$uom = $drow['value'];
								//}
								
								if($receiver == $paths[1])
								{
									$uom = $drow['value'];
								}
								
								
							}
							
							if($b != 0 && $d > 0)
							{
								$atest = abs( ($b-$a) / $b);
								if($atest > 0.05 && $atest < 0.1)
								{
									$arrow = "<img src='".base_url()."assets/images/amber_down.png' />";
								}
								if($atest >= 0.1)
								{
									$arrow = "<img src='".base_url()."assets/images/red_down.png' />";
								}
							}
							
							// remove 0 lines
							if($a == 0 && $b == 0 && $c == 0 && $d == 0 && $e == 0 && $f == 0 && $g == 0 && $h == 0 && $i == 0 && $j == 0 && $k == 0 && $l == 0 && $m == 0)
							{
								//do nothing
							} 
							else
							{
					?>		
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
						<td class="label"><?php echo $depth."".$row['name_element'] ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;"><?php echo $arrow; ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($a*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($b*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($c*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($d*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($e*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($f*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($g*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($h*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($i*-1, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($j*-1, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo number_format($k*-1, 0, ".", ","); ?></td>
                    	<td><?php echo number_format($l*-1, 0, ".", ","); ?></td>
                    	<td><?php echo number_format($m*-1, 0, ".", ","); ?></td>
						<td><?php echo $uom; ?></td>
					</tr>	
					<?php
							}	
                    	}
                    ?>
                    
                    <tr class="tmain1 header" data-level="1" >
						<td class="label">Under/Over Recovery</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
                    	<td><?php echo CUR_SIGN." ".number_format($a1-$a2, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($b1-$b2, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($c1-$c2, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($d1-$d2, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($e1-$e2, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($f1-$f2, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($g1-$g2, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td><?php echo CUR_SIGN." ".number_format($h1-$h2, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($i1-$i2, 0, ".", ","); ?></td>
                    	<td><?php echo CUR_SIGN." ".number_format($j1-$j2, 0, ".", ","); ?></td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td style="min-width:20px !important; text-align:center !important;">&nbsp;</td>
						<td>&nbsp;</td>
                    	<td>&nbsp;</td>
                    	<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
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
    
    var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_2", "600", "300", "0", "1");
    myChart2.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2; ?>"+"</chart>");
    myChart2.render("chartContainer2");
    
    var myChart3 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/MSLine.swf", "chartId_3", "600", "300", "0", "1");
    myChart3.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart3; ?>"+"</chart>");
    myChart3.render("chartContainer3");
    
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