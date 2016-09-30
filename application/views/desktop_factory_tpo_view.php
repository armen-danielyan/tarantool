<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Desktop Factory TPO</title>
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

function tpocall(){
	//alert("tpo called");
	var a1 = parseFloat(document.getElementById( "full_weight" ).value);
	var a2 = parseFloat(document.getElementById( "water_weight" ).value);
	var a3 = parseFloat(document.getElementById( "empty_weight" ).value);
	var a4 = parseFloat(document.getElementById( "shaken_do" ).value);
	var a5 = parseFloat(document.getElementById( "temperature" ).value);
	var a6 = parseFloat(document.getElementById( "density" ).value);
	
	var a7 = 0;
	
	//initialize computed vars
	var b1 = 0;
	var b2 = 0;
	var b3 = 0;
	var b4 = 0;
	var b5 = 0;
	var b6 = 0;
	var b7 = 0;
	var b8 = 0;
	var b9 = 0;
	var b10 = 0;
	var b11 = 0;
	var b12 = 0;
	var b13 = 0;
	
	var c1 = 0;
	var c2 = 0
	var c3 = 0.0831;
	var c4 = 0;
	var c5 = 0.0001;
	var c6 = 0;
	var c7 = 0;
	var c8 = 0;
	var c9 = 0;
	var c10 = 0;
	var c11 = 32;
	var c12 = 0;
	
	
	
	b1 = (a1 - a3) / a6;
	
	b2 = (a2 - a3) / 0.998;
	
	b3 = b2 - b1;
	
	b4 = a5;
	
	b5 = a4 / 1000;
	
	b6 = b1;
	
	b7 = b3;
	
	b8 = b5 * (b6 /1000);
	
	c1 = b4 + 273.150;
	
	c2 = 1000 * Math.exp( 11.644 - ( 3703 + 237600 / c1 ) / c1 ); // prolly buggy
	
	c4 = b5 / 9.95;
	
	c6 = Math.exp( -0.589581 + ( 326.785 - 45284.1 /c1 ) / c1 );
	
	c7 = Math.exp( 3.73106 + ( 5596.17 - 1049670 / c1 ) / c1 );
	
	c8 = 1776200 * c6 / c7;
	
	c9 = c4 / ( c5 * c8 ) / 1000;
	
	c10 = ( c9 * b7 / 1000 ) / ( c3 * c1);
	
	c12 = c10 * c11 * 1000;
	
	b9 = c12;
	
	b10 = b8 + b9;
	
	b11 = b5;
	
	b12 = c12 / ( b6 / 1000 );
	
	b13 = b11 + b12;
	
	$( "#b1" ).html( b1.toLocaleString() );
	$( "#b2" ).html( b2.toLocaleString() );
	$( "#b3" ).html( b3.toLocaleString() );
	$( "#b4" ).html( b4.toLocaleString() );
	$( "#b5" ).html( b5.toLocaleString() );
	$( "#b6" ).html( b6.toLocaleString() );
	$( "#b7" ).html( b7.toLocaleString() );
	$( "#b8" ).html( b8.toLocaleString() );
	$( "#b9" ).html( b9.toLocaleString() );
	$( "#b10" ).html( b10.toLocaleString() );
	$( "#b11" ).html( b11.toLocaleString() );
	$( "#b12" ).html( b12.toLocaleString() );
	$( "#b13" ).html( b13.toLocaleString() );
	
	$( "#c1" ).html( c1.toLocaleString() );
	$( "#c2" ).html( c2.toLocaleString() );
	$( "#c3" ).html( c3.toLocaleString() );
	$( "#c4" ).html( c4.toLocaleString() );
	$( "#c5" ).html( c5.toLocaleString() );
	$( "#c6" ).html( c6.toLocaleString() );
	$( "#c7" ).html( c7.toLocaleString() );
	$( "#c8" ).html( c8.toLocaleString() );
	$( "#c9" ).html( c9.toLocaleString() );
	$( "#c10" ).html( c10.toLocaleString() );
	$( "#c11" ).html( c11.toLocaleString() );
	$( "#c12" ).html( c12.toLocaleString() );
	
	a7 = b13 * 1000;
	document.getElementById( "tpo1" ).value = a7.toLocaleString();
	document.getElementById( "tpo" ).value = a7;
	
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Desktop Factory TPO</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
                <?php
					// echo "";
                ?>
            </div>
            
            
            <div class="content_div" >
                
                <form id="form1" name="form1" method="post" action="<?php echo site_url('desktop_factory_TPO'); ?>">
                	<table id="tb1" class="avtable_2">
                		<tr>
                			<td class="thead">Product</td>
                			<td class="thead">Source</td>
                			<td class="thead">Date</td>
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
			                            for($i=3; $i<$row['depth']; $i++)
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
                		</tr>
                		<tr>
                			<td colspan="3">&nbsp;</td>
                		</tr>
                		<tr>
                			<td colspan="3" class="thead">TPO Calculation</td>
                		</tr>
                		
                		<tr>
                			<td >Full Weight</td>
                			<td >
                				<input type="text" id="full_weight" name="full_weight" value="0" class="ddown1 numeric-only" onchange="tpocall();" />
                			</td>
                			<td >g</td>
                		</tr>
                		
                		<tr>
                			<td >Water Weight</td>
                			<td >
                				<input type="text" id="water_weight" name="water_weight" value="0" class="ddown1 numeric-only" onchange="tpocall();" />
                			</td>
                			<td >g</td>
                		</tr>
                		
                		<tr>
                			<td >Empty Weight</td>
                			<td >
                				<input type="text" id="empty_weight" name="empty_weight" value="0" class="ddown1 numeric-only" onchange="tpocall();" />
                			</td>
                			<td >g</td>
                		</tr>
                		
                		<tr>
                			<td >Shaken DO</td>
                			<td >
                				<input type="text" id="shaken_do" name="shaken_do" value="0" class="ddown1 numeric-only" onchange="tpocall();" />
                			</td>
                			<td >ppb</td>
                		</tr>
                    	
                    	<tr>
                			<td >Temperature</td>
                			<td >
                				<input type="text" id="temperature" name="temperature" value="0" class="ddown1 numeric-only" onchange="tpocall();" />
                			</td>
                			<td >C</td>
                		</tr>
                		
                		<tr>
                			<td >Density</td>
                			<td >
                				<input type="text" id="density" name="density" value="0" class="ddown1 numeric-only" onchange="tpocall();" />
                			</td>
                			<td >g/ml</td>
                		</tr>
                    	
                    	<tr >
                			<td >Calculated TPO</td>
                			<td >
                				<input type="text" id="tpo1" name="tpo1" value="0" class="ddown1" disabled />
                			</td>
                			<td >&nbsp;</td>
                		</tr>
                    	<input type="hidden" id="tpo" name="tpo" value="">
                    	<tr>
                    		<td colspan="3">
                    			
                    			<input name="bf" type="submit" id="bf" value="Submit Data" class="obutton1" />
                    		</td>
                    	</tr>
                    
                    </table>
                </form>
                
                <table id="tb2" class="avtable_2"> 
                	<tr class="tmain1">
                		<td colspan="7">&nbsp;</td>
                	</tr>
            		<tr>
            			<td class="label">Fill volume</td>
            			<td id="b1" >0</td>
            			<td >ml</td>
            			<td>&nbsp;</td>
            			<td class="label">T</td>
            			<td id="c1">0</td>
            			<td >K</td>
            		</tr>
            		
            		<tr>
            			<td class="label">Total volume</td>
            			<td id="b2" >0</td>
            			<td >ml</td>
            			<td>&nbsp;</td>
            			<td class="label">Water Vapor Pressure</td>
            			<td id="c2">0</td>
            			<td >&nbsp;</td>
            		</tr>
            		
            		<tr>
            			<td class="label">Head Space volume</td>
            			<td id="b3" >0</td>
            			<td >ml</td>
            			<td>&nbsp;</td>
            			<td class="label">R</td>
            			<td id="c3">0</td>
            			<td >&nbsp;</td>
            		</tr>
            		
            		<tr>
                		<td colspan="5">&nbsp;</td>
                		<td id="c4">0</td>
            			<td >&nbsp;</td>
                	</tr>
                	
                	<tr>
            			<td class="label">Temperature</td>
            			<td id="b4">0</td>
            			<td >C</td>
            			<td>&nbsp;</td>
            			<td class="label">&nbsp;</td>
            			<td id="c5">0</td>
            			<td >&nbsp;</td>
            		</tr>
            		
            		<tr>
            			<td class="label">O2 in Liquid</td>
            			<td id="b5">0</td>
            			<td >mg/l</td>
            			<td>&nbsp;</td>
            			<td class="label">Water Density</td>
            			<td id="c6">0</td>
            			<td >&nbsp;</td>
            		</tr>
            		
            		<tr>
            			<td class="label">Volume in Liquid</td>
            			<td id="b6">0</td>
            			<td >ml</td>
            			<td>&nbsp;</td>
            			<td class="label">Henry's Law Coefficient</td>
            			<td id="c7">0</td>
            			<td >&nbsp;</td>
            		</tr>
            		
            		<tr>
            			<td class="label">Volume Headspace</td>
            			<td id="b7">0</td>
            			<td >ml</td>
            			<td>&nbsp;</td>
            			<td class="label">&nbsp;</td>
            			<td id="c8">0</td>
            			<td >&nbsp;</td>
            		</tr>
            		
            		<tr>
            			<td class="label">&nbsp;</td>
            			<td >&nbsp;</td>
            			<td >&nbsp;</td>
            			<td>&nbsp;</td>
            			<td class="label">Partial Pressure O2</td>
            			<td id="c9">0</td>
            			<td >bar</td>
            		</tr>
            		
            		<tr>
            			<td class="thead">Absolute</td>
            			<td >&nbsp;</td>
            			<td >&nbsp;</td>
            			<td>&nbsp;</td>
            			<td class="label">n</td>
            			<td id="c10">0</td>
            			<td >mol</td>
            		</tr>
            		
            		<tr>
            			<td class="label">O2 in Liqiud</td>
            			<td id="b8">0</td>
            			<td >mg</td>
            			<td>&nbsp;</td>
            			<td class="label">M</td>
            			<td id="c11">0</td>
            			<td >g/mol</td>
            		</tr>
            		
            		<tr>
            			<td class="label">O2 in Headspace</td>
            			<td id="b9">0</td>
            			<td >mg</td>
            			<td>&nbsp;</td>
            			<td class="label">m</td>
            			<td id="c12">0</td>
            			<td >mg O2 in Headspace</td>
            		</tr>
            		
            		<tr>
            			<td class="label">Total O2</td>
            			<td id="b10">0</td>
            			<td >mg</td>
            			<td>&nbsp;</td>
            			<td class="label">&nbsp;</td>
            			<td >&nbsp;</td>
            			<td >&nbsp;</td>
            		</tr>
            		
            		<tr>
                		<td colspan="7">&nbsp;</td>
                	</tr>
            		
            		<tr>
            			<td class="thead">Relative</td>
            			<td colspan="6">&nbsp;</td>
            		</tr>
            		
            		<tr>
            			<td class="label">O2 in Liquid</td>
            			<td id="b11">0</td>
            			<td >mg</td>
            			<td>&nbsp;</td>
            			<td class="label">&nbsp;</td>
            			<td >&nbsp;</td>
            			<td >&nbsp;</td>
            		</tr>
            		
            		<tr>
            			<td class="label">O2 in Headspace</td>
            			<td id="b12">0</td>
            			<td >mg</td>
            			<td>&nbsp;</td>
            			<td class="label">&nbsp;</td>
            			<td >&nbsp;</td>
            			<td >&nbsp;</td>
            		</tr>
            		
            		<tr>
            			<td class="label">Total O2</td>
            			<td id="b13">0</td>
            			<td >mg</td>
            			<td>&nbsp;</td>
            			<td class="label">&nbsp;</td>
            			<td >&nbsp;</td>
            			<td >&nbsp;</td>
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