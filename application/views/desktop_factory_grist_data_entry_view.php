<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Desktop Factory Grist Data Entry</title>
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

function grist()
{
	//alert("grist called!");
	var q1 = document.getElementById( "quality_test1" ).value;
	var q2 = document.getElementById( "quality_test2" ).value;
	var q3 = document.getElementById( "quality_test3" ).value;
	var q4 = document.getElementById( "quality_test4" ).value;
	var q5 = document.getElementById( "quality_test5" ).value;
	var q6 = document.getElementById( "quality_test6" ).value;
	var q7 = document.getElementById( "quality_test7" ).value;
	
	var tot = 0;
	//alert(q1);
	var q1a = 0;
	var q1b = 0;
	var q2a = 0;
	var q2b = 0;
	var q3a = 0;
	var q3b = 0;
	var q4a = 0;
	var q4b = 0;
	var q5a = 0;
	var q5b = 0;
	var q6a = 0;
	var q6b = 0;
	var q7a = 0;
	var q7b = 0;
	
	
	//sample
	if(q1 != '')
	{
		//alert("is a number! do math!");
		q1a = q1 - 552;
		tot += q1a;
		$( "#q1a" ).html( q1a.toLocaleString() );
	} else {
		$( "#q1a" ).html( "" );
	}
	
	if(q2 != '')
	{
		//alert("is a number! do math!");
		q2a = q2 - 752;
		tot += q2a;
		$( "#q2a" ).html( q2a.toLocaleString() );
	} else {
		$( "#q2a" ).html( "" );
	}
	
	if(q3 != '')
	{
		//alert("is a number! do math!");
		q3a = q3 - 502;
		tot += q3a;
		$( "#q3a" ).html( q3a.toLocaleString() );
	} else {
		$( "#q3a" ).html( "" );
	}
	
	if(q4 != '')
	{
		//alert("is a number! do math!");
		q4a = q4 - 466;
		tot += q4a;
		$( "#q4a" ).html( q4a.toLocaleString() );
	} else {
		$( "#q4a" ).html( "" );
	}
	
	if(q5 != '')
	{
		//alert("is a number! do math!");
		q5a = q5 - 414;
		tot += q5a;
		$( "#q5a" ).html( q5a.toLocaleString() );
	} else {
		$( "#q5a" ).html( "" );
	}
	
	if(q6 != '')
	{
		//alert("is a number! do math!");
		q6a = q6 - 412;
		tot += q6a;
		$( "#q6a" ).html( q6a.toLocaleString() );
	} else {
		$( "#q6a" ).html( "" );
	}
	
	if(q7 != '')
	{
		//alert("is a number! do math!");
		q7a = q7 - 284;
		tot += q7a;
		$( "#q7a" ).html( q7a.toLocaleString() );
	} else {
		$( "#q7a" ).html( "" );
	}
	
	//grist %
	if(q1 != '')
	{
		q1b = (q1a / tot) * 100;
		$("#q1b").html( q1b.toFixed(2) + "%" );
	} else 
	{
		$("#q1b").html( "" );
	}
	
	if(q2 != '')
	{
		q2b = (q2a / tot) * 100;
		$("#q2b").html( q2b.toFixed(2) + "%" );
	} else 
	{
		$("#q2b").html( "" );
	}
	
	if(q3 != '')
	{
		q3b = (q3a / tot) * 100;
		$("#q3b").html( q3b.toFixed(2) + "%" );
	} else 
	{
		$("#q3b").html( "" );
	}
	
	if(q4 != '')
	{
		q4b = (q4a / tot) * 100;
		$("#q4b").html( q4b.toFixed(2) + "%" );
	} else 
	{
		$("#q4b").html( "" );
	}
	
	if(q5 != '')
	{
		q5b = (q5a / tot) * 100;
		$("#q5b").html( q5b.toFixed(2) + "%" );
	} else 
	{
		$("#q5b").html( "" );
	}
	
	if(q6 != '')
	{
		q6b = (q6a / tot) * 100;
		$("#q6b").html( q6b.toFixed(2) + "%" );
	} else 
	{
		$("#q6b").html( "" );
	}
	
	if(q7 != '')
	{
		q7b = (q7a / tot) * 100;
		$("#q7b").html( q7b.toFixed(2) + "%" );
	} else 
	{
		$("#q7b").html( "" );
	}
	
	$( "#qtot" ).html( tot.toLocaleString() );
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
            
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Desktop Factory Grist Data Entry</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
                <?php
					// echo "";
                ?>
            </div>
            
            
            <div class="content_div" >
                
                <form id="form1" name="form1" method="post" action="<?php echo site_url('desktop_factory_grist_data_entry'); ?>">
                	<table id="tb1" class="avtable_2">
                		<tr>
                			<td class="thead">Product</td>
                			<td class="thead">Source</td>
                			<td class="thead">Date</td>
                			<td>&nbsp;</td>
                			<td>&nbsp;</td>
                		</tr>
                		<tr>
                			
                			<td>
                				<select name="product"  class="ddown1" title="Select Product">
			                    <?php
			                        foreach($form_product as $row)
			                        {
			                            $depth = '';
			                            for($i=1; $i<$row['depth']; $i++)
			                            {
			                                $depth .= '&nbsp;&nbsp;';
			                            }
			                    ?>  
			                        <option value="<?php echo $row['element']; ?>" <?php if($product == $row['element']){ $n_product = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
			                    <?php   
			                        }
			                    ?>
			                    </select>
                			</td>
                			
                			<td>
                				<select name="source"  class="ddown1" title="Select Source">
			                    <?php
			                        foreach($form_source as $row)
			                        {
			                            $depth = '';
			                            for($i=1; $i<$row['depth']; $i++)
			                            {
			                                $depth .= '&nbsp;&nbsp;';
			                            }
			                    ?>  
			                        <option value="<?php echo $row['element']; ?>" <?php if($source == $row['element']){ $n_source = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
			                    <?php   
			                        }
			                    ?>
			                    </select>
                			</td>
                			<td>
                				<select name="month"  class="ddown1" title="Select Month">
			                    <?php
			                        foreach($form_month as $row)
			                        {
			                            $depth = '';
			                            for($i=2; $i<$row['depth']; $i++)
			                            {
			                                $depth .= '&nbsp;&nbsp;';
			                            }
			                    ?>  
			                        <option value="<?php echo $row['element']; ?>" <?php if($month == $row['element']){ $n_month = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
			                    <?php   
			                        }
			                    ?>
			                  </select><br />
			                    <select name="year"  class="ddown1" title="Select Year">
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
                			</td>
                			<td>&nbsp;</td>
                			<td>&nbsp;</td>
                		</tr>
                		<tr>
                			<td class="thead">Grist</td>
                			<td class="thead">Seive Weight</td>
                			<td class="thead">Sample + Seive</td>
                			<td class="thead">Sample</td>
                			<td class="thead">Grist</td>
                		</tr>
                		
                    	<tr>
                    		<td>10</td>
                    		<td>552</td>
                    		<td>
                    			<input type="text" id="quality_test1" name="quality_test1" value="" class="ddown1 numeric-only" onchange="grist();" />
                    		</td>
                    		<td id="q1a"></td>
                    		<td id="q1b"></td>
                    	</tr>
                    	
                    	<tr>
                    		<td>14</td>
                    		<td>752</td>
                    		<td>
                    			<input type="text" id="quality_test2" name="quality_test2" value="" class="ddown1" onchange="grist();" />
                    		</td>
                    		<td id="q2a"></td>
                    		<td id="q2b"></td>
                    	</tr>
                    	
                    	<tr>
                    		<td>18</td>
                    		<td>502</td>
                    		<td>
                    			<input type="text" id="quality_test3" name="quality_test3" value="" class="ddown1" onchange="grist();" />
                    		</td>
                    		<td id="q3a"></td>
                    		<td id="q3b"></td>
                    	</tr>
                    	
                    	<tr>
                    		<td>30</td>
                    		<td>466</td>
                    		<td>
                    			<input type="text" id="quality_test4" name="quality_test4" value="" class="ddown1" onchange="grist();" />
                    		</td>
                    		<td id="q4a"></td>
                    		<td id="q4b"></td>
                    	</tr>
                    	
                    	<tr>
                    		<td>60</td>
                    		<td>414</td>
                    		<td>
                    			<input type="text" id="quality_test5" name="quality_test5" value="" class="ddown1" onchange="grist();" />
                    		</td>
                    		<td id="q5a"></td>
                    		<td id="q5b"></td>
                    	</tr>
                    	
                    	<tr>
                    		<td>100</td>
                    		<td>412</td>
                    		<td>
                    			<input type="text" id="quality_test6" name="quality_test6" value="" class="ddown1" onchange="grist();" />
                    		</td>
                    		<td id="q6a"></td>
                    		<td id="q6b"></td>
                    	</tr>
                    	
                    	<tr>
                    		<td>Pan</td>
                    		<td>284</td>
                    		<td>
                    			<input type="text" id="quality_test7" name="quality_test7" value="" class="ddown1" onchange="grist();" />
                    		</td>
                    		<td id="q7a"></td>
                    		<td id="q7b"></td>
                    	</tr>
                    	
                    	<tr>
                    		<td>&nbsp;</td>
                    		<td>&nbsp;</td>
                    		<td class="thead">Total</td>
                    		<td id="qtot"></td>
                    		<td>&nbsp;</td>
                    	</tr>
                    	<tr>
                    		<td colspan="5" class="thead">
                    			Comment
                    		</td>
                    	</tr>
                    	<tr>
                    		<td colspan="5" class="center">
                    			<textarea name="quality_test8" id="quality_test8" style="height: 200px; width: 350px;"></textarea>
                    		</td>
                    	</tr>
                    	<tr>
                    		<td colspan="4">
                    			
                    			<input name="bf" type="submit" id="bf" value="Submit Data" class="obutton1" />
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