<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Calculate Target</title>
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
        active: 4
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

function grun(year, month, loc){
	var myrunset = {Year: year, Month: month};
	
	$.ajax({
		url:"<?php echo site_url('calculate_target/grun'); ?>",
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

function grun1(year, month, loc){
	var myrunset = {Year: year, Month: month};
	
	$.ajax({
		url:"<?php echo site_url('calculate_target/grun1'); ?>",
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

function grun2(year, month, loc){
	var myrunset = {Year: year, Month: month};
	
	$.ajax({
		url:"<?php echo site_url('calculate_target/grun2'); ?>",
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

function grun3(year, month, loc){
	var myrunset = {Year: year, Month: month};
	
	$.ajax({
		url:"<?php echo site_url('calculate_target/grun3'); ?>",
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
		url:"<?php echo site_url('calculate_target/gstatus'); ?>",
		//type: "post",
	    //data: mydataset,
		success:function(result){
			$(loc).html(result);
			
			if(result == "Completed" || result == "Completed with Warnings" || result == "Completed successfully")
			{
				document.getElementById("Action").style.display = "";
			}
		}
	});
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('calculate_target'); ?>">
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Calculate Target</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
                <?php
                
                ?>
            </div>
            
            
            <div class="content_div" >
                
                
                <?php
                if($step == 1)
                {
                ?>
				<table id="tb1" class="avtable_2">
					<tr class="tmain">
						<td class="label">Calculate Consumption Factor</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a id="r_etl" onclick="grun('<?php echo $pass_year; ?>', '<?php echo $pass_month; ?>', '#etlstatus'); return false;" href="#">Run ETL</a>
							<a style="display:none;" id="s_etl" onclick="gstat('', '#etlstatus'); return false;" href="#">Update Status</a>
							
						</td>
					</tr>
					
				</table>
				<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
				<input name="Action" style="display:none;" type="submit" id="Action"  value="Copy Target" class="obutton1" alt="Copy Target" title="Copy Target" />
				<?php
                }
                ?>
                
                <?php
                if($step == 2)
                {
                ?>	
				
				<table id="tb2" class="avtable_2">	
					<tr class="tmain">
							<td class="label">Calculate Secondary Target</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a id="r_etl" onclick="grun1('<?php echo $pass_year; ?>', '<?php echo $pass_month; ?>', '#etlstatus'); return false;" href="#">Run ETL</a>
							<a style="display:none;" id="s_etl" onclick="gstat('', '#etlstatus'); return false;" href="#">Update Status</a>
							
						</td>
					</tr>
					
				</table>
				<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
				<input name="Action" style="display:none;" type="submit" id="Action"  value="Continue" class="obutton1" alt="Continue" title="Continue" />
				
				<?php	
                }
                ?>
                
                <?php
                if($step == 3)
                {
                ?>	
                
                <table id="tb3" class="avtable_2">	
					<tr class="tmain">
							<td class="label">Calculate Primary Target</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a id="r_etl" onclick="grun2('<?php echo $pass_year; ?>', '<?php echo $pass_month; ?>', '#etlstatus'); return false;" href="#">Run ETL</a>
							<a style="display:none;" id="s_etl" onclick="gstat('', '#etlstatus'); return false;" href="#">Update Status</a>
							
						</td>
					</tr>
					
				</table>
				<input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
				<input name="Action" style="display:none;" type="submit" id="Action"  value="Calculate Material" class="obutton1" alt="Calculate Material" title="Calculate Material" />
				
                
                <?php	
                }
                ?>
                
                <?php
                if($step == 4)
                {
                ?>	
                <table id="tb4" class="avtable_2">	
					<tr class="tmain">
						<td class="label">Calculate Material</td>
                		<td colspan="2">
                			<?php
                			echo "<iframe class=\"embed\" src=\"../assets/calculate_rates/calc_raw_mat.php?version=".$version_sim."&year=".$year."&month=".$month."&verbose=0\"></iframe></td>\r\n";
                			?>
                		</td>
                	</tr>
                	<tr class="tmain">
							<td class="label">Prepare Reports</td>
						<td class="label"><div id="etlstatus"></div></td>
						<td class="label">
							<a id="r_etl" onclick="grun3('<?php echo $pass_year; ?>', '<?php echo $pass_month; ?>', '#etlstatus'); return false;" href="#">Run ETL</a>
							<a style="display:none;" id="s_etl" onclick="gstat('', '#etlstatus'); return false;" href="#">Update Status</a>
							
						</td>
					</tr>
                	
                </table>
                <input name="step"        type="hidden" id="step"    value="<?php echo $step; ?>" />
                
                
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
</form>
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