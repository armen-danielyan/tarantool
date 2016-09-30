<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Assumptions</title>
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
    //var table1 = $('#tb1').tabelize({
	// OPTIONS HERE
	//});
});

$(document).on('keyup', '.numeric-only', function(event) {
   var v = this.value;
   if($.isNumeric(v) === false) {
        //chop off the last char entered
        this.value = this.value.slice(0,-1);
   }
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('assumptions'); ?>">
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
                            
                            <select name="customer" onchange="this.form.submit();" class="ddown1" title="Select Customer">
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
                            
                            <select name="project" onchange="this.form.submit();" class="ddown1" title="Select Project">
                            <?php
                                foreach($form_project as $row)
                                {
                                    $depth = '';
                                    for($i=1; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($project == $row['element']){ $n_project = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
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
                                <?php //echo mailto("?to=&subject=&body=".site_url('efficiency_resources')."/info/".$n_year."/".url_title($n_month, '_')."/".url_title($n_receiver, '_'), "<span class='ui-icon ui-icon-mail-closed left' ></span>", array("title" =>"Share this page via email")); 
                                ?>
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Assumptions</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
            </div>
            <div class="content_div" >
                
                <table id="tb1" class="avtable_2 controller">
                	
                    <?php
                    	$depth_stack = array();
                    	foreach($value_elements_set_alias as $row)
                    	{
                    		$a = 0;
							$depth = '';
							$true_depth = 1;
							$sub_depth = 1;
							$sign = '';
							
                            for($z=1; $z<$row['depth']; $z++)
                            {
                                $depth .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                                $true_depth += 1;
                            }
							
							if($row['number_children'] != 0)
							{
								//$depth .= "<span class='ui-icon ui-icon-squaresmall-plus' style='display:inline-block !important; vertical-align: text-bottom !important;'></span> ";
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
							
							
							foreach($tc_cells as $drow1)
							{
								$paths = explode(",", $drow1['path']);
								if($row['element'] == $paths['2'])
								{
									$a = $drow1['value'];
								}
								
							}
							
							
							//add $ sign to specific rows
							
							$font = "";
							
							//if($a == 0)
							//{
								//do nothing
							//} 
							//else
							//{
					?>		
					<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
						<td class="label" <?php echo $font; ?> ><?php echo $depth."".$row['name_element']; ?></td>
						
                    	<td  <?php echo $font; ?> >
                    		<?php
                    			if($row['number_children'] == 0)
								{
									if(
									$row['name_element'] == "Labor rate fully loaded"
									|| $row['name_element'] == "Annual Value = (1 * 2 * 3 * 4)"
									|| $row['name_element'] == "Annual Value = (1 * 2 * 3* 4)"
									|| $row['name_element'] == "Annual Value = (1 * 2 * 3) * 12 months"
									|| $row['name_element'] == "Annual hours spent reviewing DHR for final release"
									|| $row['name_element'] == "Production units per year"
									|| $row['name_element'] == "Current yield loss %"
									|| $row['name_element'] == "Yield improvement % = (3*4)"
									|| $row['name_element'] == "Annual Yield Improvement Value = (1 * 2 * 5)"
									|| $row['name_element'] == "% inventory carrying cost for administration, material movement, storage, etc."
									|| $row['name_element'] == "One time reduction in Inventory = 1 * 3"
									|| $row['name_element'] == "Carrying cost reduction = 4 * 2"
									|| $row['name_element'] == "Avg hours per DHR or Shop Traveler spent reviewing"
									|| $row['name_element'] == "Annual Value = (1 + 2) * 3) * 12 months"
									|| $row['name_element'] == "Expected DHR or Shop Traveler per year"
									|| $row['name_element'] == "Annual Value = (1 * 2)"
									|| $row['name_element'] == "Annual Value = (1 * 2 * 3* 4) * 12 months"
									|| $row['name_element'] == "Annual Value = (1 * 2 * 3)"
									|| $row['name_element'] == "Annual Yield Improvement Value = (1 * 2 )"
									|| $row['name_element'] == "Annual Yield Improvement Value = (1 * 2)"
									|| $row['name_element'] == "Annual Value"
									|| $row['name_element'] == "Annual Value = 1 + 2 + 3"
									|| $row['name_element'] == "Annual Value = 1 + 2"
									)
									{
										echo number_format($a, 2, ".", "");
									} else {
							?>
							<input name="var_<?php echo $row['element']; ?>" type="text" id="var_<?php echo $row['element']; ?>" value="<?php echo number_format($a, 2, ".", ""); ?>" class="numeric-only text_right">
							<?php
									}
								}
                    		?>
                    	</td>
						
					</tr>
					<?php
							//}
                    	}
                    ?>
                    <tr>
                    	<td>&nbsp;</td>
                    	<td>
                    		<input name="Update" type="submit" id="Update" value="Update" class="obutton1" />
                    		</td>
                    </tr>
                    	
                </table>
                </form>
            </div>
            
        </td>
    </tr>
    <tr>
        <td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
    </tr>
</table>

<script type="text/javascript">

    
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