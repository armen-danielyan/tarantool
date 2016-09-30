<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Benefits Summary</title>
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

function gupdate(dvalue, dpath){
	var myrunset = {ddvalue: dvalue, ddpath: dpath};
	var appraisal = 0;
	var prevention = 0;
	var internalfailure = 0;
	var externalfailure = 0;
	
	$.ajax({
		url:"<?php echo site_url('benefits_summary/gupdate'); ?>",
		type: "post",
	    data: myrunset,
		success:function(result){
			
			//alert(result);
		}
	});
	
	//alert(nvalue);
	$('.chartme').each(function() {
		var keynum = this.value;
		var keyval = $(this).closest('tr').find('input[type=hidden]').val();
		//alert(keynum+"---"+keyval);
		if(keynum == 1)
		{
			appraisal += parseFloat(keyval);
		}
		if(keynum == 2)
		{
			prevention += parseFloat(keyval);
		}
		if(keynum == 3)
		{
			internalfailure += parseFloat(keyval);
		}
		if(keynum == 4)
		{
			externalfailure += parseFloat(keyval);
		}
	});
	
	//alert("appraisal="+appraisal+" --- prevention="+prevention+" --- internalfailure="+internalfailure+" --- externalfailure="+externalfailure);
	var chartupdate = "<set label='Appraisal <?php echo CUR_SIGN; ?>"+appraisal.toLocaleString()+"' value='"+appraisal+"' /><set label='Prevention <?php echo CUR_SIGN; ?>"+prevention.toLocaleString()+"' value='"+prevention+"' /><set label='Internal Failure <?php echo CUR_SIGN; ?>"+internalfailure.toLocaleString()+"' value='"+internalfailure+"' /><set label='External Failure <?php echo CUR_SIGN; ?>"+externalfailure.toLocaleString()+"' value='"+externalfailure+"' />";
	
	var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Pie2D.swf", "chartId_2", "600", "300", "0", "1");
    myChart2.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+chartupdate+"</chart>");
    myChart2.render("chartContainer2");
	
}



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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('benefits_summary'); ?>">
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Benefits Summary</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
            </div>
            
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Benefit Breakdown</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"></div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Cost of Quality</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer2" class="chart1"></div></td>
                    	</tr>
                    </table>
                </div>
                
            </div>
            
            <div class="content_div" >
                
                <table id="tb1" class="avtable_2 controller">
                	<tr class="tmain">
                		<td class="thead">Cost of Quality</td>
                		<td class="thead">Benefits</td>
                		<td class="thead">COGS</td>
                		<td class="thead">&nbsp;</td>
                	</tr>
                    <?php
                    	$depth_stack = array();
						//chart 1
						$qa_labor = 0;
						$material = 0;
						$ops_labor = 0;
						$overhead = 0;
						$eng_labor = 0;
						$revenue = 0;
						
						// chart 2 (on load)
						
						$appraisal = 0;
						$prevention = 0;
						$internalfailure = 0;
						$externalfailure = 0;
						
                    	foreach($benefit_element_set_alias as $row)
                    	{
                    		
							$depth = '';
							$true_depth = 1;
							$sub_depth = 1;
							$sign = '';
							
                            for($z=0; $z<$row['depth']; $z++)
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
							
							
							
							foreach($cost_of_goods_elements_set_alias as $row1)
							{
								$ddvalue = '';
								$ddpath = '';
								foreach($tc_cells2 as $drow2)
								{
									// this is the dropdown values
									$paths1 = explode(",", $drow2['path']);
									if($row['element'] == $paths1['4'] && $row1['element'] == $paths1['5'])
									{
										$ddvalue = $drow2['value'];
										$ddpath = $drow2['path'];
									}
									
								}
								
								foreach($tc_cells as $drow1)
								{
									$a = $drow1['value'];
									$paths = explode(",", $drow1['path']);
									
									if($a != 0){
										// only continue if $a has value. else show nothing.
										if($row['number_children'] != 0 && $row1['number_children'] != 0 && $row['element'] == $paths['4'] && $row1['element'] == $paths['5'])
										{
											// if the main node is not a base element... display only the row with "all elements"
											
											//$a = $drow1['value'];
											//display the row
										?>
								<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
									<td>
										&nbsp;
									</td>
									<td class="label"  ><?php echo $depth."".$row['name_element']; ?></td>
									<td class="label" ><?php echo $row1['name_element']; ?></td>
									<td><?php echo CUR_SIGN."".number_format($a, 2, ".", ","); ?></td>
								</tr>
										<?php
										} else if ($row['number_children'] == 0 && $row['element'] == $paths['4'] && $row1['number_children'] == 0 && $row1['element'] == $paths['5'])
										{
											// loop through all cg base elements before displaying row 
											//$a = $drow1['value'];
											//chart 1 var
											if($row1['name_element'] == "QA Labor")
											{
												$qa_labor += $a;
											}
											if($row1['name_element'] == "Material")
											{
												$material += $a;
											}
											if($row1['name_element'] == "Ops Labor")
											{
												$ops_labor += $a;
											}
											if($row1['name_element'] == "Overhead")
											{
												$overhead += $a;
											}
											if($row1['name_element'] == "Eng Labor")
											{
												$eng_labor += $a;
											}
											if($row1['name_element'] == "Revenue")
											{
												$revenue += $a;
											}
											
											//chart2 var
											
											if($ddvalue == 1)
											{
												$appraisal += $a;
											}
											if($ddvalue == 2)
											{
												$prevention += $a;
											}
											if($ddvalue == 3)
											{
												$internalfailure += $a;
											}
											if($ddvalue == 4)
											{
												$externalfailure += $a;
											}
											
										?>	
								<tr data-level="<?php echo $true_depth; ?>" id="level_<?php echo $true_depth."_".$sub_depth; ?>">
									<td>
										<select name="dd_<?php echo $row['element']."_".$row1['element']; ?>" onchange="gupdate(this.value, '<?php echo $ddpath; ?>');" class="ddown1 chartme">
											<option value="0" <?php if($ddvalue == 0){  ?>selected="selected"<?php } ?> >Not Applicable</option>
											<option value="1" <?php if($ddvalue == 1){  ?>selected="selected"<?php } ?> >Appraisal</option>
											<option value="2" <?php if($ddvalue == 2){  ?>selected="selected"<?php } ?> >Prevention</option>
											<option value="3" <?php if($ddvalue == 3){  ?>selected="selected"<?php } ?> >Internal Failure</option>
											<option value="4" <?php if($ddvalue == 4){  ?>selected="selected"<?php } ?> >External Failure</option>
										</select>
										<input type="hidden" name="td_<?php echo $row['element']."_".$row1['element']; ?>" value="<?php echo $a; ?>" >
									</td>
									<td class="label"  ><?php echo $depth."".$row['name_element']; ?></td>
									<td class="label" ><?php echo $row1['name_element']; ?></td>
									<td><?php echo CUR_SIGN."".number_format($a, 2, ".", ","); ?></td>
								</tr>
										<?php
										}
									
									}
									
								}
								
							}
							
							
							
							
							
							
                    	}
						
						// build xml for chart 1
						
						$chart1 = "<set label='QA Labor ".CUR_SIGN."".number_format($qa_labor, 2)."' value='".$qa_labor."' /><set label='Material ".CUR_SIGN."".number_format($material, 2)."' value='".$material."' /><set label='Ops Labor ".CUR_SIGN."".number_format($ops_labor, 2)."' value='".$ops_labor."' /><set label='Overhead ".CUR_SIGN."".number_format($overhead, 2)."' value='".$overhead."' /><set label='Eng Labor ".CUR_SIGN."".number_format($eng_labor, 2)."' value='".$eng_labor."' /><set label='Revenue ".CUR_SIGN."".number_format($revenue, 2)."' value='".$revenue."' />";
						$chart2 = "<set label='Appraisal ".CUR_SIGN."".number_format($appraisal, 2)."' value='".$appraisal."' /><set label='Prevention ".CUR_SIGN."".number_format($prevention, 2)."' value='".$prevention."' /><set label='Internal Failure ".CUR_SIGN."".number_format($internalfailure, 2)."' value='".$internalfailure."' /><set label='External Failure ".CUR_SIGN."".number_format($externalfailure, 2)."' value='".$externalfailure."' />";
						
                    ?>
                    
                    	
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
	var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Pie2D.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart1; ?>"+"</chart>");
    myChart1.render("chartContainer1");
    
    var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Pie2D.swf", "chartId_2", "600", "300", "0", "1");
    myChart2.setXMLData("<chart caption='' bgColor='FFFFFF' showBorder='0' canvasBorderAlpha='0' xAxisName='' connectNullData='0'  yAxisName='' showValues='0' setAdaptiveYMin='1' numberPrefix=''>"+"<?php echo $chart2; ?>"+"</chart>");
    myChart2.render("chartContainer2");
    
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