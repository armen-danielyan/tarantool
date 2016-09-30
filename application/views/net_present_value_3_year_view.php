<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Net Present Value 3 Year</title>
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
        active: 3
    });
    $( "#filter_menu" ).accordion({
        heightStyle: "content",
        collapsible: false
    });
    
    $( document ).tooltip({
        track: true
    });
    
    $('#npv').keypress(function(event) {
    var code = (event.keyCode ? event.keyCode : event.which);
    if (!(
            (code >= 48 && code <= 57) //numbers
            || (code == 46) //period
        )
        || (code == 46 && $(this).val().indexOf('.') != -1)
       )
        event.preventDefault();
	});
    
    $("#togmonths").click(function(){
		$(".togrange").toggle();
	});
    $(".togrange").hide();
    
    $('tr.tmain td').attr('title', 'Click to Expand/Collapse');
    $('tr.tmain td.label').each(function (){
    	var new_label = "<span class='ui-icon ui-icon-squaresmall-plus left'></span> "+$(this).html();
    	$(this).html(new_label);
    });
    
    $('#tb3 tr.tmain').click( function() {
        var wcell = this.rowIndex;
        $("#tb3 tr:eq("+wcell+")").nextAll('tr').each( function() {
            if ($(this).hasClass('tmain') || $(this).hasClass('tmain1')) {
                return false;
            }
            $(this).toggle();
        });
    });
    
    $('#tb3 tr.tmain').each(function() {
        $(this).nextAll('tr').each( function() {
            if ($(this).hasClass('tmain') || $(this).hasClass('tmain1')) {
                return false;
            }
            $(this).toggle();
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
                    <form id="form1" name="form1" method="post" action="<?php echo site_url('net_present_value_3_year'); ?>">
                        
                        <div class="filter_div">
                            
                            <select name="version" onchange="this.form.submit();" class="ddown1" title="Select TO-BE Version">
                            <?php
                                foreach($form_version as $row)
                                {
                                    $depth = '';
                                    for($i=0; $i<$row['depth']; $i++)
                                    {
                                        $depth .= '&nbsp;&nbsp;';
                                    }
                            ?>  
                                <option value="<?php echo $row['element']; ?>" <?php if($version == $row['element']){ $n_date = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                        
                        
                        
                        <div class="filter_div">
                            <div class="left">
                                <a href="#" onclick="javascript:window.print();" title="Print This Page"><span class="ui-icon ui-icon-print left" ></span></a>
                            </div>
                            <!-- 
                            <div class="left">
                                <?php echo mailto("?to=&subject=&body=".site_url('efficiency_operations_v2')."/info/".$n_year."/".url_title($n_month, '_')."/".url_title($n_day, '_')."/".url_title($n_shift, '_')."/".url_title($n_version, '_')."/".url_title($n_receiver, '_')."", "<span class='ui-icon ui-icon-mail-closed left' ></span>", array("title" =>"Share this page via email")); ?>
                            </div>
                            -->
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
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > "
				.anchor('net_present_value_summary', 'Net Present Value Summary', array('title' => 'Go to Summary'))." > "
				.anchor('net_present_value_npv', 'NPV', array('title' => 'Go to NPV'))." > "
				."<span class='orange'>3 Year</span> > "
				.anchor('net_present_value_monthly', 'Monthly', array('title' => 'Go to Monthly'));
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            
            <div class="tabber">
                <div class="tabbertab">
                    <h3>Return on Investment (ROI)</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer1" class="chart1"></div></td>
                    	</tr>
                    </table>
                    
                </div>
                <div class="tabbertab">
                    <h3>Gross Product Margin</h3>
                    <table>
                    	<tr>
                    		<td><div id="chartContainer2" class="chart1"></div></td>
                    	</tr>
                    </table>
                </div>
                
            </div>
            
            <div class="content_div" >
            	<div class="clearfix">
                		Set Monthly Discount Rate: <input type="text" onchange="this.form.submit();" value="<?php echo $npv; ?>" name="npv" id="npv" class"ddown1" /> 
					</form><br />&nbsp;
				</div>
				
                <table id="tb1" class="avtable_2" style="display: none !important;">
                	<tr>
						<td>&nbsp;</td>
                    	<td class="thead">NPV</td>
						<td class="thead">3 YEAR TOTAL</td>
						<td class="thead togrange">JAN 2015</td>
						<td class="thead togrange">FEB 2015</td>
						<td class="thead togrange">MAR 2015</td>
						<td class="thead togrange">APR 2015</td>
						<td class="thead togrange">MAY 2015</td>
						<td class="thead togrange">JUN 2015</td>
						<td class="thead togrange">JUL 2015</td>
						<td class="thead togrange">AUG 2015</td>
						<td class="thead togrange">SEP 2015</td>
						<td class="thead togrange">OCT 2015</td>
						<td class="thead togrange">NOV 2015</td>
						<td class="thead togrange">DEC 2015</td>
						<td class="thead togrange">JAN 2016</td>
						<td class="thead togrange">FEB 2016</td>
						<td class="thead togrange">MAR 2016</td>
						<td class="thead togrange">APR 2016</td>
						<td class="thead togrange">MAY 2016</td>
						<td class="thead togrange">JUN 2016</td>
						<td class="thead togrange">JUL 2016</td>
						<td class="thead togrange">AUG 2016</td>
						<td class="thead togrange">SEP 2016</td>
						<td class="thead togrange">OCT 2016</td>
						<td class="thead togrange">NOV 2016</td>
						<td class="thead togrange">DEC 2016</td>
						<td class="thead togrange">JAN 2017</td>
						<td class="thead togrange">FEB 2017</td>
						<td class="thead togrange">MAR 2017</td>
						<td class="thead togrange">APR 2017</td>
						<td class="thead togrange">MAY 2017</td>
						<td class="thead togrange">JUN 2017</td>
						<td class="thead togrange">JUL 2017</td>
						<td class="thead togrange">AUG 2017</td>
						<td class="thead togrange">SEP 2017</td>
						<td class="thead togrange">OCT 2017</td>
						<td class="thead togrange">NOV 2017</td>
						<td class="thead togrange">DEC 2017</td>
					</tr>
					<?php
						//initialize vars (a-e fixed, f-j variant)
						
						$a1 = $b1 = $c1 = $d1 = $e1 = $f1 = $g1 = $h1 = $i1 = $j1 = 0;
						$a2 = $b2 = $c2 = $d2 = $e2 = $f2 = $g2 = $h2 = $i2 = $j2 = 0;
						$a3 = $b3 = $c3 = $d3 = $e3 = $f3 = $g3 = $h3 = $i3 = $j3 = 0;
						$a4 = $b4 = $c4 = $d4 = $e4 = $f4 = $g4 = $h4 = $i4 = $j4 = 0;
						$a5 = $b5 = $c5 = $d5 = $e5 = $f5 = $g5 = $h5 = $i5 = $j5 = 0;
						$a6 = $b6 = $c6 = $d6 = $e6 = $f6 = $g6 = $h6 = $i6 = $j6 = 0;
						$a7 = $b7 = $c7 = $d7 = $e7 = $f7 = $g7 = $h7 = $i7 = $j7 = 0;
						$a8 = $b8 = $c8 = $d8 = $e8 = $f8 = $g8 = $h8 = $i8 = $j8 = 0;
						$a9 = $b9 = $c9 = $d9 = $e9 = $f9 = $g9 = $h9 = $i9 = $j9 = 0;
						$a10 = $b10 = $c10 = $d10 = $e10 = $f10 = $g10 = $h10 = $i10 = $j10 = 0;
						$a11 = $b11 = $c11 = $d11 = $e11 = $f11 = $g11 = $h11 = $i11 = $j11 = 0;
						$a12 = $b12 = $c12 = $d12 = $e12 = $f12 = $g12 = $h12 = $i12 = $j12 = 0;
						$a13 = $b13 = $c13 = $d13 = $e13 = $f13 = $g13 = $h13 = $i13 = $j13 = 0;
						$a14 = $b14 = $c14 = $d14 = $e14 = $f14 = $g14 = $h14 = $i14 = $j14 = 0;
						$a15 = $b15 = $c15 = $d15 = $e15 = $f15 = $g15 = $h15 = $i15 = $j15 = 0;
						$a16 = $b16 = $c16 = $d16 = $e16 = $f16 = $g16 = $h16 = $i16 = $j16 = 0;
						$a17 = $b17 = $c17 = $d17 = $e17 = $f17 = $g17 = $h17 = $i17 = $j17 = 0;
						$a18 = $b18 = $c18 = $d18 = $e18 = $f18 = $g18 = $h18 = $i18 = $j18 = 0;
						$a19 = $b19 = $c19 = $d19 = $e19 = $f19 = $g19 = $h19 = $i19 = $j19 = 0;
						$a20 = $b20 = $c20 = $d20 = $e20 = $f20 = $g20 = $h20 = $i20 = $j20 = 0;
						$a21 = $b21 = $c21 = $d21 = $e21 = $f21 = $g21 = $h21 = $i21 = $j21 = 0;
						$a22 = $b22 = $c22 = $d22 = $e22 = $f22 = $g22 = $h22 = $i22 = $j22 = 0;
						$a23 = $b23 = $c23 = $d23 = $e23 = $f23 = $g23 = $h23 = $i23 = $j23 = 0;
						$a24 = $b24 = $c24 = $d24 = $e24 = $f24 = $g24 = $h24 = $i24 = $j24 = 0;
						$a25 = $b25 = $c25 = $d25 = $e25 = $f25 = $g25 = $h25 = $i25 = $j25 = 0;
						$a26 = $b26 = $c26 = $d26 = $e26 = $f26 = $g26 = $h26 = $i26 = $j26 = 0;
						$a27 = $b27 = $c27 = $d27 = $e27 = $f27 = $g27 = $h27 = $i27 = $j27 = 0;
						$a28 = $b28 = $c28 = $d28 = $e28 = $f28 = $g28 = $h28 = $i28 = $j28 = 0;
						$a29 = $b29 = $c29 = $d29 = $e29 = $f29 = $g29 = $h29 = $i29 = $j29 = 0;
						$a30 = $b30 = $c30 = $d30 = $e30 = $f30 = $g30 = $h30 = $i30 = $j30 = 0;
						$a31 = $b31 = $c31 = $d31 = $e31 = $f31 = $g31 = $h31 = $i31 = $j31 = 0;
						$a32 = $b32 = $c32 = $d32 = $e32 = $f32 = $g32 = $h32 = $i32 = $j32 = 0;
						$a33 = $b33 = $c33 = $d33 = $e33 = $f33 = $g33 = $h33 = $i33 = $j33 = 0;
						$a34 = $b34 = $c34 = $d34 = $e34 = $f34 = $g34 = $h34 = $i34 = $j34 = 0;
						$a35 = $b35 = $c35 = $d35 = $e35 = $f35 = $g35 = $h35 = $i35 = $j35 = 0;
						$a36 = $b36 = $c36 = $d36 = $e36 = $f36 = $g36 = $h36 = $i36 = $j36 = 0;
						$atot = $btot = $ctot = $dtot = $etot = $ftot = $gtot = $htot = $itot = $jtot = 0;
						$anpv = $bnpv = $cnpv = $dnpv = $enpv = $fnpv = $gnpv = $hnpv = $inpv = $jnpv = 0;
						
						//1st table start
						
						//primary cost
						
						foreach($table_pc_data as $row)
						{
							$paths = explode(",", $row['path']);
							//-- year 1
							if($year_1 == $paths['1'] && $month_1 == $paths['2'])
							{
								$a1 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/$npv;
							}
							if($year_1 == $paths['1'] && $month_2 == $paths['2'])
							{
								$a2 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 2));
							}
							if($year_1 == $paths['1'] && $month_3 == $paths['2'])
							{
								$a3 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 3));
							}
							if($year_1 == $paths['1'] && $month_4 == $paths['2'])
							{
								$a4 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 4));
							}
							if($year_1 == $paths['1'] && $month_5 == $paths['2'])
							{
								$a5 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 5));
							}
							if($year_1 == $paths['1'] && $month_6 == $paths['2'])
							{
								$a6 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 6));
							}
							if($year_1 == $paths['1'] && $month_7 == $paths['2'])
							{
								$a7 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 7));
							}
							if($year_1 == $paths['1'] && $month_8 == $paths['2'])
							{
								$a8 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 8));
							}
							if($year_1 == $paths['1'] && $month_9 == $paths['2'])
							{
								$a9 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 9));
							}
							if($year_1 == $paths['1'] && $month_10 == $paths['2'])
							{
								$a10 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 10));
							}
							if($year_1 == $paths['1'] && $month_11 == $paths['2'])
							{
								$a11 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 11));
							}
							if($year_1 == $paths['1'] && $month_12 == $paths['2'])
							{
								$a12 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 12));
							}
							//--- year 2
							if($year_2 == $paths['1'] && $month_1 == $paths['2'])
							{
								$a13 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 13));
							}
							if($year_2 == $paths['1'] && $month_2 == $paths['2'])
							{
								$a14 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 14));
							}
							if($year_2 == $paths['1'] && $month_3 == $paths['2'])
							{
								$a15 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 15));
							}
							if($year_2 == $paths['1'] && $month_4 == $paths['2'])
							{
								$a16 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 16));
							}
							if($year_2 == $paths['1'] && $month_5 == $paths['2'])
							{
								$a17 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 17));
							}
							if($year_2 == $paths['1'] && $month_6 == $paths['2'])
							{
								$a18 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 18));
							}
							if($year_2 == $paths['1'] && $month_7 == $paths['2'])
							{
								$a19 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 19));
							}
							if($year_2 == $paths['1'] && $month_8 == $paths['2'])
							{
								$a20 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 20));
							}
							if($year_2 == $paths['1'] && $month_9 == $paths['2'])
							{
								$a21 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 21));
							}
							if($year_2 == $paths['1'] && $month_10 == $paths['2'])
							{
								$a22 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 22));
							}
							if($year_2 == $paths['1'] && $month_11 == $paths['2'])
							{
								$a23 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 23));
							}
							if($year_2 == $paths['1'] && $month_12 == $paths['2'])
							{
								$a24 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 24));
							}
							//-- year 3
							if($year_3 == $paths['1'] && $month_1 == $paths['2'])
							{
								$a25 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 25));
							}
							if($year_3 == $paths['1'] && $month_2 == $paths['2'])
							{
								$a26 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 26));
							}
							if($year_3 == $paths['1'] && $month_3 == $paths['2'])
							{
								$a27 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 27));
							}
							if($year_3 == $paths['1'] && $month_4 == $paths['2'])
							{
								$a28 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 28));
							}
							if($year_3 == $paths['1'] && $month_5 == $paths['2'])
							{
								$a29 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 29));
							}
							if($year_3 == $paths['1'] && $month_6 == $paths['2'])
							{
								$a30 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 30));
							}
							if($year_3 == $paths['1'] && $month_7 == $paths['2'])
							{
								$a31 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 31));
							}
							if($year_3 == $paths['1'] && $month_8 == $paths['2'])
							{
								$a32 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 32));
							}
							if($year_3 == $paths['1'] && $month_9 == $paths['2'])
							{
								$a33 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 33));
							}
							if($year_3 == $paths['1'] && $month_10 == $paths['2'])
							{
								$a34 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 34));
							}
							if($year_3 == $paths['1'] && $month_11 == $paths['2'])
							{
								$a35 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 35));
							}
							if($year_3 == $paths['1'] && $month_12 == $paths['2'])
							{
								$a36 = $row['value'];
								$atot += $row['value'];
								$anpv += $row['value']/(pow($npv, 36));
							}
							
						}
						// secondary cost
						
						foreach($table_sc_data as $row)
						{
							$paths = explode(",", $row['path']);
							//-- year 1
							if($year_1 == $paths['1'] && $month_1 == $paths['2'])
							{
								$b1 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/$npv;
							}
							if($year_1 == $paths['1'] && $month_2 == $paths['2'])
							{
								$b2 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 2));
							}
							if($year_1 == $paths['1'] && $month_3 == $paths['2'])
							{
								$b3 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 3));
							}
							if($year_1 == $paths['1'] && $month_4 == $paths['2'])
							{
								$b4 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 4));
							}
							if($year_1 == $paths['1'] && $month_5 == $paths['2'])
							{
								$b5 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 5));
							}
							if($year_1 == $paths['1'] && $month_6 == $paths['2'])
							{
								$b6 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 6));
							}
							if($year_1 == $paths['1'] && $month_7 == $paths['2'])
							{
								$b7 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 7));
							}
							if($year_1 == $paths['1'] && $month_8 == $paths['2'])
							{
								$b8 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 8));
							}
							if($year_1 == $paths['1'] && $month_9 == $paths['2'])
							{
								$b9 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 9));
							}
							if($year_1 == $paths['1'] && $month_10 == $paths['2'])
							{
								$b10 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 10));
							}
							if($year_1 == $paths['1'] && $month_11 == $paths['2'])
							{
								$b11 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 11));
							}
							if($year_1 == $paths['1'] && $month_12 == $paths['2'])
							{
								$b12 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 12));
							}
							//--- year 2
							if($year_2 == $paths['1'] && $month_1 == $paths['2'])
							{
								$b13 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 13));
							}
							if($year_2 == $paths['1'] && $month_2 == $paths['2'])
							{
								$b14 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 14));
							}
							if($year_2 == $paths['1'] && $month_3 == $paths['2'])
							{
								$b15 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 15));
							}
							if($year_2 == $paths['1'] && $month_4 == $paths['2'])
							{
								$b16 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 16));
							}
							if($year_2 == $paths['1'] && $month_5 == $paths['2'])
							{
								$b17 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 17));
							}
							if($year_2 == $paths['1'] && $month_6 == $paths['2'])
							{
								$b18 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 18));
							}
							if($year_2 == $paths['1'] && $month_7 == $paths['2'])
							{
								$b19 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 19));
							}
							if($year_2 == $paths['1'] && $month_8 == $paths['2'])
							{
								$b20 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 20));
							}
							if($year_2 == $paths['1'] && $month_9 == $paths['2'])
							{
								$b21 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 21));
							}
							if($year_2 == $paths['1'] && $month_10 == $paths['2'])
							{
								$b22 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 22));
							}
							if($year_2 == $paths['1'] && $month_11 == $paths['2'])
							{
								$b23 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 23));
							}
							if($year_2 == $paths['1'] && $month_12 == $paths['2'])
							{
								$b24 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 24));
							}
							//-- year 3
							if($year_3 == $paths['1'] && $month_1 == $paths['2'])
							{
								$b25 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 25));
							}
							if($year_3 == $paths['1'] && $month_2 == $paths['2'])
							{
								$b26 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 26));
							}
							if($year_3 == $paths['1'] && $month_3 == $paths['2'])
							{
								$b27 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 27));
							}
							if($year_3 == $paths['1'] && $month_4 == $paths['2'])
							{
								$b28 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 28));
							}
							if($year_3 == $paths['1'] && $month_5 == $paths['2'])
							{
								$b29 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 29));
							}
							if($year_3 == $paths['1'] && $month_6 == $paths['2'])
							{
								$b30 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 30));
							}
							if($year_3 == $paths['1'] && $month_7 == $paths['2'])
							{
								$b31 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 31));
							}
							if($year_3 == $paths['1'] && $month_8 == $paths['2'])
							{
								$b32 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 32));
							}
							if($year_3 == $paths['1'] && $month_9 == $paths['2'])
							{
								$b33 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 33));
							}
							if($year_3 == $paths['1'] && $month_10 == $paths['2'])
							{
								$b34 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 34));
							}
							if($year_3 == $paths['1'] && $month_11 == $paths['2'])
							{
								$b35 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 35));
							}
							if($year_3 == $paths['1'] && $month_12 == $paths['2'])
							{
								$b36 = $row['value'];
								$btot += $row['value'];
								$bnpv += $row['value']/(pow($npv, 36));
							}
							
						}
						//number of units
						
						foreach($table_nu_data as $row)
						{
							$paths = explode(",", $row['path']);
							//-- year 1
							if($year_1 == $paths['1'] && $month_1 == $paths['2'])
							{
								$c1 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_2 == $paths['2'])
							{
								$c2 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_3 == $paths['2'])
							{
								$c3 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_4 == $paths['2'])
							{
								$c4 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_5 == $paths['2'])
							{
								$c5 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_6 == $paths['2'])
							{
								$c6 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_7 == $paths['2'])
							{
								$c7 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_8 == $paths['2'])
							{
								$c8 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_9 == $paths['2'])
							{
								$c9 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_10 == $paths['2'])
							{
								$c10 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_11 == $paths['2'])
							{
								$c11 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_12 == $paths['2'])
							{
								$c12 = $row['value'];
								$ctot += $row['value'];
							}
							//--- year 2
							if($year_2 == $paths['1'] && $month_1 == $paths['2'])
							{
								$c13 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_2 == $paths['2'])
							{
								$c14 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_3 == $paths['2'])
							{
								$c15 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_4 == $paths['2'])
							{
								$c16 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_5 == $paths['2'])
							{
								$c17 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_6 == $paths['2'])
							{
								$c18 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_7 == $paths['2'])
							{
								$c19 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_8 == $paths['2'])
							{
								$c20 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_9 == $paths['2'])
							{
								$c21 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_10 == $paths['2'])
							{
								$c22 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_11 == $paths['2'])
							{
								$c23 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_12 == $paths['2'])
							{
								$c24 = $row['value'];
								$ctot += $row['value'];
							}
							//-- year 3
							if($year_3 == $paths['1'] && $month_1 == $paths['2'])
							{
								$c25 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_2 == $paths['2'])
							{
								$c26 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_3 == $paths['2'])
							{
								$c27 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_4 == $paths['2'])
							{
								$c28 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_5 == $paths['2'])
							{
								$c29 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_6 == $paths['2'])
							{
								$c30 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_7 == $paths['2'])
							{
								$c31 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_8 == $paths['2'])
							{
								$c32 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_9 == $paths['2'])
							{
								$c33 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_10 == $paths['2'])
							{
								$c34 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_11 == $paths['2'])
							{
								$c35 = $row['value'];
								$ctot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_12 == $paths['2'])
							{
								$c36 = $row['value'];
								$ctot += $row['value'];
							}
							
						}
						
						// net revenue
						
						foreach($table_nr_data as $row)
						{
							$paths = explode(",", $row['path']);
							//-- year 1
							if($year_1 == $paths['1'] && $month_1 == $paths['2'])
							{
								$d1 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/$npv;
							}
							if($year_1 == $paths['1'] && $month_2 == $paths['2'])
							{
								$d2 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 2));
							}
							if($year_1 == $paths['1'] && $month_3 == $paths['2'])
							{
								$d3 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 3));
							}
							if($year_1 == $paths['1'] && $month_4 == $paths['2'])
							{
								$d4 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 4));
							}
							if($year_1 == $paths['1'] && $month_5 == $paths['2'])
							{
								$d5 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 5));
							}
							if($year_1 == $paths['1'] && $month_6 == $paths['2'])
							{
								$d6 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 6));
							}
							if($year_1 == $paths['1'] && $month_7 == $paths['2'])
							{
								$d7 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 7));
							}
							if($year_1 == $paths['1'] && $month_8 == $paths['2'])
							{
								$d8 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 8));
							}
							if($year_1 == $paths['1'] && $month_9 == $paths['2'])
							{
								$d9 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 9));
							}
							if($year_1 == $paths['1'] && $month_10 == $paths['2'])
							{
								$d10 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 10));
							}
							if($year_1 == $paths['1'] && $month_11 == $paths['2'])
							{
								$d11 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 11));
							}
							if($year_1 == $paths['1'] && $month_12 == $paths['2'])
							{
								$d12 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 12));
							}
							//--- year 2
							if($year_2 == $paths['1'] && $month_1 == $paths['2'])
							{
								$d13 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 13));
							}
							if($year_2 == $paths['1'] && $month_2 == $paths['2'])
							{
								$d14 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 14));
							}
							if($year_2 == $paths['1'] && $month_3 == $paths['2'])
							{
								$d15 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 15));
							}
							if($year_2 == $paths['1'] && $month_4 == $paths['2'])
							{
								$d16 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 16));
							}
							if($year_2 == $paths['1'] && $month_5 == $paths['2'])
							{
								$d17 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 17));
							}
							if($year_2 == $paths['1'] && $month_6 == $paths['2'])
							{
								$d18 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 18));
							}
							if($year_2 == $paths['1'] && $month_7 == $paths['2'])
							{
								$d19 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 19));
							}
							if($year_2 == $paths['1'] && $month_8 == $paths['2'])
							{
								$d20 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 20));
							}
							if($year_2 == $paths['1'] && $month_9 == $paths['2'])
							{
								$d21 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 21));
							}
							if($year_2 == $paths['1'] && $month_10 == $paths['2'])
							{
								$d22 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 22));
							}
							if($year_2 == $paths['1'] && $month_11 == $paths['2'])
							{
								$d23 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 23));
							}
							if($year_2 == $paths['1'] && $month_12 == $paths['2'])
							{
								$d24 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 24));
							}
							//-- year 3
							if($year_3 == $paths['1'] && $month_1 == $paths['2'])
							{
								$d25 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 25));
							}
							if($year_3 == $paths['1'] && $month_2 == $paths['2'])
							{
								$d26 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 26));
							}
							if($year_3 == $paths['1'] && $month_3 == $paths['2'])
							{
								$d27 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 27));
							}
							if($year_3 == $paths['1'] && $month_4 == $paths['2'])
							{
								$d28 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 28));
							}
							if($year_3 == $paths['1'] && $month_5 == $paths['2'])
							{
								$d29 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 29));
							}
							if($year_3 == $paths['1'] && $month_6 == $paths['2'])
							{
								$d30 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 30));
							}
							if($year_3 == $paths['1'] && $month_7 == $paths['2'])
							{
								$d31 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 31));
							}
							if($year_3 == $paths['1'] && $month_8 == $paths['2'])
							{
								$d32 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 32));
							}
							if($year_3 == $paths['1'] && $month_9 == $paths['2'])
							{
								$d33 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 33));
							}
							if($year_3 == $paths['1'] && $month_10 == $paths['2'])
							{
								$d34 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 34));
							}
							if($year_3 == $paths['1'] && $month_11 == $paths['2'])
							{
								$d35 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 35));
							}
							if($year_3 == $paths['1'] && $month_12 == $paths['2'])
							{
								$d36 = $row['value'];
								$dtot += $row['value'];
								$dnpv += $row['value']/(pow($npv, 36));
							}
							
						}
						
						// total product cost
						
						foreach($table_tpc_data as $row)
						{
							$paths = explode(",", $row['path']);
							//-- year 1
							if($year_1 == $paths['1'] && $month_1 == $paths['2'])
							{
								$e1 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/$npv;
							}
							if($year_1 == $paths['1'] && $month_2 == $paths['2'])
							{
								$e2 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 2));
							}
							if($year_1 == $paths['1'] && $month_3 == $paths['2'])
							{
								$e3 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 3));
							}
							if($year_1 == $paths['1'] && $month_4 == $paths['2'])
							{
								$e4 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 4));
							}
							if($year_1 == $paths['1'] && $month_5 == $paths['2'])
							{
								$e5 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 5));
							}
							if($year_1 == $paths['1'] && $month_6 == $paths['2'])
							{
								$e6 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 6));
							}
							if($year_1 == $paths['1'] && $month_7 == $paths['2'])
							{
								$e7 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 7));
							}
							if($year_1 == $paths['1'] && $month_8 == $paths['2'])
							{
								$e8 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 8));
							}
							if($year_1 == $paths['1'] && $month_9 == $paths['2'])
							{
								$e9 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 9));
							}
							if($year_1 == $paths['1'] && $month_10 == $paths['2'])
							{
								$e10 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 10));
							}
							if($year_1 == $paths['1'] && $month_11 == $paths['2'])
							{
								$e11 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 11));
							}
							if($year_1 == $paths['1'] && $month_12 == $paths['2'])
							{
								$e12 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 12));
							}
							//--- year 2
							if($year_2 == $paths['1'] && $month_1 == $paths['2'])
							{
								$e13 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 13));
							}
							if($year_2 == $paths['1'] && $month_2 == $paths['2'])
							{
								$e14 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 14));
							}
							if($year_2 == $paths['1'] && $month_3 == $paths['2'])
							{
								$e15 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 15));
							}
							if($year_2 == $paths['1'] && $month_4 == $paths['2'])
							{
								$e16 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 16));
							}
							if($year_2 == $paths['1'] && $month_5 == $paths['2'])
							{
								$e17 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 17));
							}
							if($year_2 == $paths['1'] && $month_6 == $paths['2'])
							{
								$e18 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 18));
							}
							if($year_2 == $paths['1'] && $month_7 == $paths['2'])
							{
								$e19 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 19));
							}
							if($year_2 == $paths['1'] && $month_8 == $paths['2'])
							{
								$e20 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 20));
							}
							if($year_2 == $paths['1'] && $month_9 == $paths['2'])
							{
								$e21 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 21));
							}
							if($year_2 == $paths['1'] && $month_10 == $paths['2'])
							{
								$e22 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 22));
							}
							if($year_2 == $paths['1'] && $month_11 == $paths['2'])
							{
								$e23 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 23));
							}
							if($year_2 == $paths['1'] && $month_12 == $paths['2'])
							{
								$e24 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 24));
							}
							//-- year 3
							if($year_3 == $paths['1'] && $month_1 == $paths['2'])
							{
								$e25 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 25));
							}
							if($year_3 == $paths['1'] && $month_2 == $paths['2'])
							{
								$e26 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 26));
							}
							if($year_3 == $paths['1'] && $month_3 == $paths['2'])
							{
								$e27 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 27));
							}
							if($year_3 == $paths['1'] && $month_4 == $paths['2'])
							{
								$e28 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 28));
							}
							if($year_3 == $paths['1'] && $month_5 == $paths['2'])
							{
								$e29 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 29));
							}
							if($year_3 == $paths['1'] && $month_6 == $paths['2'])
							{
								$e30 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 30));
							}
							if($year_3 == $paths['1'] && $month_7 == $paths['2'])
							{
								$e31 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 31));
							}
							if($year_3 == $paths['1'] && $month_8 == $paths['2'])
							{
								$e32 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 32));
							}
							if($year_3 == $paths['1'] && $month_9 == $paths['2'])
							{
								$e33 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 33));
							}
							if($year_3 == $paths['1'] && $month_10 == $paths['2'])
							{
								$e34 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 34));
							}
							if($year_3 == $paths['1'] && $month_11 == $paths['2'])
							{
								$e35 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 35));
							}
							if($year_3 == $paths['1'] && $month_12 == $paths['2'])
							{
								$e36 += $row['value'];
								$etot += $row['value'];
								$enpv += $row['value']/(pow($npv, 36));
							}
							
						}
						
						// 2nd table code start ------------------------------------------------------------
						
						//primary cost
						
						foreach($table_pc1_data as $row)
						{
							$paths = explode(",", $row['path']);
							//-- year 1
							if($year_1 == $paths['1'] && $month_1 == $paths['2'])
							{
								$f1 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/$npv;
							}
							if($year_1 == $paths['1'] && $month_2 == $paths['2'])
							{
								$f2 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 2));
							}
							if($year_1 == $paths['1'] && $month_3 == $paths['2'])
							{
								$f3 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 3));
							}
							if($year_1 == $paths['1'] && $month_4 == $paths['2'])
							{
								$f4 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 4));
							}
							if($year_1 == $paths['1'] && $month_5 == $paths['2'])
							{
								$f5 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 5));
							}
							if($year_1 == $paths['1'] && $month_6 == $paths['2'])
							{
								$f6 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 6));
							}
							if($year_1 == $paths['1'] && $month_7 == $paths['2'])
							{
								$f7 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 7));
							}
							if($year_1 == $paths['1'] && $month_8 == $paths['2'])
							{
								$f8 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 8));
							}
							if($year_1 == $paths['1'] && $month_9 == $paths['2'])
							{
								$f9 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 9));
							}
							if($year_1 == $paths['1'] && $month_10 == $paths['2'])
							{
								$f10 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 10));
							}
							if($year_1 == $paths['1'] && $month_11 == $paths['2'])
							{
								$f11 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 11));
							}
							if($year_1 == $paths['1'] && $month_12 == $paths['2'])
							{
								$f12 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 12));
							}
							//--- year 2
							if($year_2 == $paths['1'] && $month_1 == $paths['2'])
							{
								$f13 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 13));
							}
							if($year_2 == $paths['1'] && $month_2 == $paths['2'])
							{
								$f14 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 14));
							}
							if($year_2 == $paths['1'] && $month_3 == $paths['2'])
							{
								$f15 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 15));
							}
							if($year_2 == $paths['1'] && $month_4 == $paths['2'])
							{
								$f16 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 16));
							}
							if($year_2 == $paths['1'] && $month_5 == $paths['2'])
							{
								$f17 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 17));
							}
							if($year_2 == $paths['1'] && $month_6 == $paths['2'])
							{
								$f18 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 18));
							}
							if($year_2 == $paths['1'] && $month_7 == $paths['2'])
							{
								$f19 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 19));
							}
							if($year_2 == $paths['1'] && $month_8 == $paths['2'])
							{
								$f20 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 20));
							}
							if($year_2 == $paths['1'] && $month_9 == $paths['2'])
							{
								$f21 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 21));
							}
							if($year_2 == $paths['1'] && $month_10 == $paths['2'])
							{
								$f22 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 22));
							}
							if($year_2 == $paths['1'] && $month_11 == $paths['2'])
							{
								$f23 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 23));
							}
							if($year_2 == $paths['1'] && $month_12 == $paths['2'])
							{
								$f24 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 24));
							}
							//-- year 3
							if($year_3 == $paths['1'] && $month_1 == $paths['2'])
							{
								$f25 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 25));
							}
							if($year_3 == $paths['1'] && $month_2 == $paths['2'])
							{
								$f26 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 26));
							}
							if($year_3 == $paths['1'] && $month_3 == $paths['2'])
							{
								$f27 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 27));
							}
							if($year_3 == $paths['1'] && $month_4 == $paths['2'])
							{
								$f28 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 28));
							}
							if($year_3 == $paths['1'] && $month_5 == $paths['2'])
							{
								$f29 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 29));
							}
							if($year_3 == $paths['1'] && $month_6 == $paths['2'])
							{
								$f30 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 30));
							}
							if($year_3 == $paths['1'] && $month_7 == $paths['2'])
							{
								$f31 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 31));
							}
							if($year_3 == $paths['1'] && $month_8 == $paths['2'])
							{
								$f32 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 32));
							}
							if($year_3 == $paths['1'] && $month_9 == $paths['2'])
							{
								$f33 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 33));
							}
							if($year_3 == $paths['1'] && $month_10 == $paths['2'])
							{
								$f34 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 34));
							}
							if($year_3 == $paths['1'] && $month_11 == $paths['2'])
							{
								$f35 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 35));
							}
							if($year_3 == $paths['1'] && $month_12 == $paths['2'])
							{
								$f36 = $row['value'];
								$ftot += $row['value'];
								$fnpv += $row['value']/(pow($npv, 36));
							}
							
						}
						// secondary cost
						
						foreach($table_sc1_data as $row)
						{
							$paths = explode(",", $row['path']);
							//-- year 1
							if($year_1 == $paths['1'] && $month_1 == $paths['2'])
							{
								$g1 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/$npv;
							}
							if($year_1 == $paths['1'] && $month_2 == $paths['2'])
							{
								$g2 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 2));
							}
							if($year_1 == $paths['1'] && $month_3 == $paths['2'])
							{
								$g3 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 3));
							}
							if($year_1 == $paths['1'] && $month_4 == $paths['2'])
							{
								$g4 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 4));
							}
							if($year_1 == $paths['1'] && $month_5 == $paths['2'])
							{
								$g5 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 5));
							}
							if($year_1 == $paths['1'] && $month_6 == $paths['2'])
							{
								$g6 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 6));
							}
							if($year_1 == $paths['1'] && $month_7 == $paths['2'])
							{
								$g7 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 7));
							}
							if($year_1 == $paths['1'] && $month_8 == $paths['2'])
							{
								$g8 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 8));
							}
							if($year_1 == $paths['1'] && $month_9 == $paths['2'])
							{
								$g9 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 9));
							}
							if($year_1 == $paths['1'] && $month_10 == $paths['2'])
							{
								$g10 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 10));
							}
							if($year_1 == $paths['1'] && $month_11 == $paths['2'])
							{
								$g11 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 11));
							}
							if($year_1 == $paths['1'] && $month_12 == $paths['2'])
							{
								$g12 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 12));
							}
							//--- year 2
							if($year_2 == $paths['1'] && $month_1 == $paths['2'])
							{
								$g13 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 13));
							}
							if($year_2 == $paths['1'] && $month_2 == $paths['2'])
							{
								$g14 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 14));
							}
							if($year_2 == $paths['1'] && $month_3 == $paths['2'])
							{
								$g15 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 15));
							}
							if($year_2 == $paths['1'] && $month_4 == $paths['2'])
							{
								$g16 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 16));
							}
							if($year_2 == $paths['1'] && $month_5 == $paths['2'])
							{
								$g17 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 17));
							}
							if($year_2 == $paths['1'] && $month_6 == $paths['2'])
							{
								$g18 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 18));
							}
							if($year_2 == $paths['1'] && $month_7 == $paths['2'])
							{
								$g19 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 19));
							}
							if($year_2 == $paths['1'] && $month_8 == $paths['2'])
							{
								$g20 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 20));
							}
							if($year_2 == $paths['1'] && $month_9 == $paths['2'])
							{
								$g21 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 21));
							}
							if($year_2 == $paths['1'] && $month_10 == $paths['2'])
							{
								$g22 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 22));
							}
							if($year_2 == $paths['1'] && $month_11 == $paths['2'])
							{
								$g23 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 23));
							}
							if($year_2 == $paths['1'] && $month_12 == $paths['2'])
							{
								$g24 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 24));
							}
							//-- year 3
							if($year_3 == $paths['1'] && $month_1 == $paths['2'])
							{
								$g25 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 25));
							}
							if($year_3 == $paths['1'] && $month_2 == $paths['2'])
							{
								$g26 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 26));
							}
							if($year_3 == $paths['1'] && $month_3 == $paths['2'])
							{
								$g27 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 27));
							}
							if($year_3 == $paths['1'] && $month_4 == $paths['2'])
							{
								$g28 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 28));
							}
							if($year_3 == $paths['1'] && $month_5 == $paths['2'])
							{
								$g29 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 29));
							}
							if($year_3 == $paths['1'] && $month_6 == $paths['2'])
							{
								$g30 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 30));
							}
							if($year_3 == $paths['1'] && $month_7 == $paths['2'])
							{
								$g31 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 31));
							}
							if($year_3 == $paths['1'] && $month_8 == $paths['2'])
							{
								$g32 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 32));
							}
							if($year_3 == $paths['1'] && $month_9 == $paths['2'])
							{
								$g33 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 33));
							}
							if($year_3 == $paths['1'] && $month_10 == $paths['2'])
							{
								$g34 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 34));
							}
							if($year_3 == $paths['1'] && $month_11 == $paths['2'])
							{
								$g35 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 35));
							}
							if($year_3 == $paths['1'] && $month_12 == $paths['2'])
							{
								$g36 = $row['value'];
								$gtot += $row['value'];
								$gnpv += $row['value']/(pow($npv, 36));
							}
							
						}
						//number of units
						
						foreach($table_nu1_data as $row)
						{
							$paths = explode(",", $row['path']);
							//-- year 1
							if($year_1 == $paths['1'] && $month_1 == $paths['2'])
							{
								$h1 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_2 == $paths['2'])
							{
								$h2 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_3 == $paths['2'])
							{
								$h3 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_4 == $paths['2'])
							{
								$h4 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_5 == $paths['2'])
							{
								$h5 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_6 == $paths['2'])
							{
								$h6 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_7 == $paths['2'])
							{
								$h7 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_8 == $paths['2'])
							{
								$h8 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_9 == $paths['2'])
							{
								$h9 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_10 == $paths['2'])
							{
								$h10 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_11 == $paths['2'])
							{
								$h11 = $row['value'];
								$htot += $row['value'];
							}
							if($year_1 == $paths['1'] && $month_12 == $paths['2'])
							{
								$h12 = $row['value'];
								$htot += $row['value'];
							}
							//--- year 2
							if($year_2 == $paths['1'] && $month_1 == $paths['2'])
							{
								$h13 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_2 == $paths['2'])
							{
								$h14 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_3 == $paths['2'])
							{
								$h15 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_4 == $paths['2'])
							{
								$h16 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_5 == $paths['2'])
							{
								$h17 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_6 == $paths['2'])
							{
								$h18 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_7 == $paths['2'])
							{
								$h19 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_8 == $paths['2'])
							{
								$h20 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_9 == $paths['2'])
							{
								$h21 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_10 == $paths['2'])
							{
								$h22 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_11 == $paths['2'])
							{
								$h23 = $row['value'];
								$htot += $row['value'];
							}
							if($year_2 == $paths['1'] && $month_12 == $paths['2'])
							{
								$h24 = $row['value'];
								$htot += $row['value'];
							}
							//-- year 3
							if($year_3 == $paths['1'] && $month_1 == $paths['2'])
							{
								$h25 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_2 == $paths['2'])
							{
								$h26 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_3 == $paths['2'])
							{
								$h27 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_4 == $paths['2'])
							{
								$h28 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_5 == $paths['2'])
							{
								$h29 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_6 == $paths['2'])
							{
								$h30 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_7 == $paths['2'])
							{
								$h31 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_8 == $paths['2'])
							{
								$h32 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_9 == $paths['2'])
							{
								$h33 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_10 == $paths['2'])
							{
								$h34 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_11 == $paths['2'])
							{
								$h35 = $row['value'];
								$htot += $row['value'];
							}
							if($year_3 == $paths['1'] && $month_12 == $paths['2'])
							{
								$h36 = $row['value'];
								$htot += $row['value'];
							}
							
						}
						
						// net revenue
						
						foreach($table_nr1_data as $row)
						{
							$paths = explode(",", $row['path']);
							//-- year 1
							if($year_1 == $paths['1'] && $month_1 == $paths['2'])
							{
								$i1 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/$npv;
							}
							if($year_1 == $paths['1'] && $month_2 == $paths['2'])
							{
								$i2 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 2));
							}
							if($year_1 == $paths['1'] && $month_3 == $paths['2'])
							{
								$i3 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 3));
							}
							if($year_1 == $paths['1'] && $month_4 == $paths['2'])
							{
								$i4 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 4));
							}
							if($year_1 == $paths['1'] && $month_5 == $paths['2'])
							{
								$i5 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 5));
							}
							if($year_1 == $paths['1'] && $month_6 == $paths['2'])
							{
								$i6 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 6));
							}
							if($year_1 == $paths['1'] && $month_7 == $paths['2'])
							{
								$i7 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 7));
							}
							if($year_1 == $paths['1'] && $month_8 == $paths['2'])
							{
								$i8 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 8));
							}
							if($year_1 == $paths['1'] && $month_9 == $paths['2'])
							{
								$i9 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 9));
							}
							if($year_1 == $paths['1'] && $month_10 == $paths['2'])
							{
								$i10 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 10));
							}
							if($year_1 == $paths['1'] && $month_11 == $paths['2'])
							{
								$i11 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 11));
							}
							if($year_1 == $paths['1'] && $month_12 == $paths['2'])
							{
								$i12 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 12));
							}
							//--- year 2
							if($year_2 == $paths['1'] && $month_1 == $paths['2'])
							{
								$i13 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 13));
							}
							if($year_2 == $paths['1'] && $month_2 == $paths['2'])
							{
								$i14 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 14));
							}
							if($year_2 == $paths['1'] && $month_3 == $paths['2'])
							{
								$i15 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 15));
							}
							if($year_2 == $paths['1'] && $month_4 == $paths['2'])
							{
								$i16 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 16));
							}
							if($year_2 == $paths['1'] && $month_5 == $paths['2'])
							{
								$i17 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 17));
							}
							if($year_2 == $paths['1'] && $month_6 == $paths['2'])
							{
								$i18 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 18));
							}
							if($year_2 == $paths['1'] && $month_7 == $paths['2'])
							{
								$i19 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 19));
							}
							if($year_2 == $paths['1'] && $month_8 == $paths['2'])
							{
								$i20 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 20));
							}
							if($year_2 == $paths['1'] && $month_9 == $paths['2'])
							{
								$i21 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 21));
							}
							if($year_2 == $paths['1'] && $month_10 == $paths['2'])
							{
								$i22 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 22));
							}
							if($year_2 == $paths['1'] && $month_11 == $paths['2'])
							{
								$i23 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 23));
							}
							if($year_2 == $paths['1'] && $month_12 == $paths['2'])
							{
								$i24 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 24));
							}
							//-- year 3
							if($year_3 == $paths['1'] && $month_1 == $paths['2'])
							{
								$i25 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 25));
							}
							if($year_3 == $paths['1'] && $month_2 == $paths['2'])
							{
								$i26 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 26));
							}
							if($year_3 == $paths['1'] && $month_3 == $paths['2'])
							{
								$i27 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 27));
							}
							if($year_3 == $paths['1'] && $month_4 == $paths['2'])
							{
								$i28 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 28));
							}
							if($year_3 == $paths['1'] && $month_5 == $paths['2'])
							{
								$i29 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 29));
							}
							if($year_3 == $paths['1'] && $month_6 == $paths['2'])
							{
								$i30 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 30));
							}
							if($year_3 == $paths['1'] && $month_7 == $paths['2'])
							{
								$i31 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 31));
							}
							if($year_3 == $paths['1'] && $month_8 == $paths['2'])
							{
								$i32 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 32));
							}
							if($year_3 == $paths['1'] && $month_9 == $paths['2'])
							{
								$i33 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 33));
							}
							if($year_3 == $paths['1'] && $month_10 == $paths['2'])
							{
								$i34 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 34));
							}
							if($year_3 == $paths['1'] && $month_11 == $paths['2'])
							{
								$i35 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 35));
							}
							if($year_3 == $paths['1'] && $month_12 == $paths['2'])
							{
								$i36 = $row['value'];
								$itot += $row['value'];
								$inpv += $row['value']/(pow($npv, 36));
							}
							
						}
						
						// total product cost
						
						foreach($table_tpc1_data as $row)
						{
							$paths = explode(",", $row['path']);
							//-- year 1
							if($year_1 == $paths['1'] && $month_1 == $paths['2'])
							{
								$j1 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/$npv;
							}
							if($year_1 == $paths['1'] && $month_2 == $paths['2'])
							{
								$j2 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 2));
							}
							if($year_1 == $paths['1'] && $month_3 == $paths['2'])
							{
								$j3 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 3));
							}
							if($year_1 == $paths['1'] && $month_4 == $paths['2'])
							{
								$j4 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 4));
							}
							if($year_1 == $paths['1'] && $month_5 == $paths['2'])
							{
								$j5 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 5));
							}
							if($year_1 == $paths['1'] && $month_6 == $paths['2'])
							{
								$j6 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 6));
							}
							if($year_1 == $paths['1'] && $month_7 == $paths['2'])
							{
								$j7 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 7));
							}
							if($year_1 == $paths['1'] && $month_8 == $paths['2'])
							{
								$j8 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 8));
							}
							if($year_1 == $paths['1'] && $month_9 == $paths['2'])
							{
								$j9 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 9));
							}
							if($year_1 == $paths['1'] && $month_10 == $paths['2'])
							{
								$j10 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 10));
							}
							if($year_1 == $paths['1'] && $month_11 == $paths['2'])
							{
								$j11 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 11));
							}
							if($year_1 == $paths['1'] && $month_12 == $paths['2'])
							{
								$j12 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 12));
							}
							//--- year 2
							if($year_2 == $paths['1'] && $month_1 == $paths['2'])
							{
								$j13 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 13));
							}
							if($year_2 == $paths['1'] && $month_2 == $paths['2'])
							{
								$j14 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 14));
							}
							if($year_2 == $paths['1'] && $month_3 == $paths['2'])
							{
								$j15 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 15));
							}
							if($year_2 == $paths['1'] && $month_4 == $paths['2'])
							{
								$j16 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 16));
							}
							if($year_2 == $paths['1'] && $month_5 == $paths['2'])
							{
								$j17 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 17));
							}
							if($year_2 == $paths['1'] && $month_6 == $paths['2'])
							{
								$j18 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 18));
							}
							if($year_2 == $paths['1'] && $month_7 == $paths['2'])
							{
								$j19 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 19));
							}
							if($year_2 == $paths['1'] && $month_8 == $paths['2'])
							{
								$j20 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 20));
							}
							if($year_2 == $paths['1'] && $month_9 == $paths['2'])
							{
								$j21 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 21));
							}
							if($year_2 == $paths['1'] && $month_10 == $paths['2'])
							{
								$j22 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 22));
							}
							if($year_2 == $paths['1'] && $month_11 == $paths['2'])
							{
								$j23 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 23));
							}
							if($year_2 == $paths['1'] && $month_12 == $paths['2'])
							{
								$j24 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 24));
							}
							//-- year 3
							if($year_3 == $paths['1'] && $month_1 == $paths['2'])
							{
								$j25 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 25));
							}
							if($year_3 == $paths['1'] && $month_2 == $paths['2'])
							{
								$j26 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 26));
							}
							if($year_3 == $paths['1'] && $month_3 == $paths['2'])
							{
								$j27 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 27));
							}
							if($year_3 == $paths['1'] && $month_4 == $paths['2'])
							{
								$j28 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 28));
							}
							if($year_3 == $paths['1'] && $month_5 == $paths['2'])
							{
								$j29 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 29));
							}
							if($year_3 == $paths['1'] && $month_6 == $paths['2'])
							{
								$j30 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 30));
							}
							if($year_3 == $paths['1'] && $month_7 == $paths['2'])
							{
								$j31 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 31));
							}
							if($year_3 == $paths['1'] && $month_8 == $paths['2'])
							{
								$j32 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 32));
							}
							if($year_3 == $paths['1'] && $month_9 == $paths['2'])
							{
								$j33 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 33));
							}
							if($year_3 == $paths['1'] && $month_10 == $paths['2'])
							{
								$j34 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 34));
							}
							if($year_3 == $paths['1'] && $month_11 == $paths['2'])
							{
								$j35 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 35));
							}
							if($year_3 == $paths['1'] && $month_12 == $paths['2'])
							{
								$j36 += $row['value'];
								$jtot += $row['value'];
								$jnpv += $row['value']/(pow($npv, 36));
							}
							
						}
						
					?>
					<tr >
						<td colspan="39" class="label">Plan Version (AS-IS)</td>
					</tr>
					<tr>
						<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Primary Cost</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($anpv, 0, ".", ","); //npv ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($atot, 0, ".", ","); //3year ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a1, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a2, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a3, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a4, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a5, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a6, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a7, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a8, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a9, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a10, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a11, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a12, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a13, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a14, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a15, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a16, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a17, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a18, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a19, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a20, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a21, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a22, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a23, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a24, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a25, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a26, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a27, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a28, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a29, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a30, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a31, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a32, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a33, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a34, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a35, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($a36, 0, ".", ","); ?></td>
					</tr>
					
					<tr>
						<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Secondary Cost</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($bnpv, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($btot, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b1, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b2, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b3, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b4, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b5, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b6, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b7, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b8, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b9, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b10, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b11, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b12, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b13, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b14, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b15, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b16, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b17, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b18, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b19, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b20, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b21, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b22, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b23, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b24, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b25, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b26, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b27, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b28, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b29, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b30, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b31, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b32, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b33, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b34, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b35, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($b36, 0, ".", ","); ?></td>
					</tr>
					
					<tr class="tmain">
						<td class="label">&nbsp;&nbsp;Total Resource Cost</td>
                    	<td ><?php echo CUR_SIGN." ".number_format(($anpv+$bnpv), 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format(($atot+$btot), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a1+$b1), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a2+$b2), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a3+$b3), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a4+$b4), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a5+$b5), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a6+$b6), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a7+$b7), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a8+$b8), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a9+$b9), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a10+$b10), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a11+$b11), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a12+$b12), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a13+$b13), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a14+$b14), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a15+$b15), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a16+$b16), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a17+$b17), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a18+$b18), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a19+$b19), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a20+$b20), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a21+$b21), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a22+$b22), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a23+$b23), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a24+$b24), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a25+$b25), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a26+$b26), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a27+$b27), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a28+$b28), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a29+$b29), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a30+$b30), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a31+$b31), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a32+$b32), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a33+$b33), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a34+$b34), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a35+$b35), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($a36+$b36), 0, ".", ","); ?></td>
					</tr>
					<tr >
						<td colspan="39" class="label">&nbsp;&nbsp;Product Profitability</td>
					</tr>
					<tr>
						<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Number of Units</td>
                    	<td ><?php echo number_format($ctot, 0, ".", ","); ?></td>
						<td ><?php echo number_format($ctot, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c1, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c2, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c3, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c4, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c5, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c6, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c7, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c8, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c9, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c10, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c11, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c12, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c13, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c14, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c15, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c16, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c17, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c18, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c19, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c20, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c21, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c22, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c23, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c24, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c25, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c26, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c27, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c28, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c29, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c30, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c31, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c32, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c33, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c34, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c35, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($c36, 0, ".", ","); ?></td>
					</tr>
					
					<tr>
						<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Net Revenue</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($dnpv, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($dtot, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d1, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d2, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d3, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d4, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d5, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d6, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d7, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d8, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d9, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d10, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d11, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d12, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d13, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d14, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d15, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d16, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d17, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d18, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d19, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d20, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d21, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d22, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d23, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d24, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d25, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d26, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d27, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d28, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d29, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d30, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d31, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d32, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d33, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d34, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d35, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($d36, 0, ".", ","); ?></td>
					</tr>
					
					<tr>
						<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Total Product Cost</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($enpv, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($etot, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e1, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e2, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e3, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e4, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e5, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e6, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e7, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e8, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e9, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e10, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e11, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e12, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e13, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e14, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e15, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e16, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e17, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e18, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e19, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e20, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e21, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e22, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e23, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e24, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e25, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e26, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e27, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e28, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e29, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e30, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e31, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e32, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e33, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e34, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e35, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($e36, 0, ".", ","); ?></td>
					</tr>
					
					<tr class="tmain">
						<td class="label">&nbsp;&nbsp;Gross Product Margin</td>
                    	<td ><?php echo CUR_SIGN." ".number_format(($dnpv-$enpv), 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format(($dtot-$etot), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d1-$e1), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d2-$e2), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d3-$e3), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d4-$e4), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d5-$e5), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d6-$e6), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d7-$e7), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d8-$e8), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d9-$e9), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d10-$e10), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d11-$e11), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d12-$e12), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d13-$e13), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d14-$e14), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d15-$e15), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d16-$e16), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d17-$e17), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d18-$e18), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d19-$e19), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d20-$e20), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d21-$e21), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d22-$e22), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d23-$e23), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d24-$e24), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d25-$e25), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d26-$e26), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d27-$e27), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d28-$e28), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d29-$e29), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d30-$e30), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d31-$e31), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d32-$e32), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d33-$e33), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d34-$e34), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d35-$e35), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($d36-$e36), 0, ".", ","); ?></td>
					</tr>
					<tr >
						<td colspan="39" class="label">&nbsp;</td>
					</tr>
					<tr >
						<td colspan="39" class="label">Investment Initiative Version (TO-BE)</td>
					</tr>
					<tr>
						<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Primary Cost</td>
                    	<!-- <td >$<?php //echo number_format(($fnpv+$ec1_data[0]['value']-$ec2_data[0]['value']), 0, ".", ","); ?></td>-->
                    	<td ><?php echo CUR_SIGN." ".number_format(($fnpv+$ec1_data[0]['value']), 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($ftot, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f1, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f2, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f3, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f4, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f5, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f6, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f7, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f8, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f9, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f10, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f11, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f12, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f13, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f14, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f15, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f16, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f17, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f18, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f19, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f20, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f21, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f22, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f23, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f24, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f25, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f26, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f27, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f28, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f29, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f30, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f31, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f32, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f33, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f34, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f35, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($f36, 0, ".", ","); ?></td>
					</tr>
					
					<tr>
						<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Secondary Cost</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($gnpv, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($gtot, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g1, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g2, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g3, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g4, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g5, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g6, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g7, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g8, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g9, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g10, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g11, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g12, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g13, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g14, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g15, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g16, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g17, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g18, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g19, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g20, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g21, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g22, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g23, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g24, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g25, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g26, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g27, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g28, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g29, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g30, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g31, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g32, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g33, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g34, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g35, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($g36, 0, ".", ","); ?></td>
					</tr>
					
					<tr class="tmain">
						<td class="label">&nbsp;&nbsp;Total Resource Cost</td>
                    	<td ><?php echo CUR_SIGN." ".number_format(($fnpv+$gnpv), 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format(($ftot+$gtot), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f1+$g1), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f2+$g2), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f3+$g3), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f4+$g4), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f5+$g5), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f6+$g6), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f7+$g7), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f8+$g8), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f9+$g9), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f10+$g10), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f11+$g11), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f12+$g12), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f13+$g13), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f14+$g14), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f15+$g15), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f16+$g16), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f17+$g17), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f18+$g18), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f19+$g19), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f20+$g20), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f21+$g21), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f22+$g22), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f23+$g23), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f24+$g24), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f25+$g25), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f26+$g26), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f27+$g27), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f28+$g28), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f29+$g29), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f30+$g30), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f31+$g31), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f32+$g32), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f33+$g33), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f34+$g34), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f35+$g35), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($f36+$g36), 0, ".", ","); ?></td>
					</tr>
					<tr >
						<td colspan="39" class="label">&nbsp;&nbsp;Product Profitability</td>
					</tr>
					<tr>
						<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Number of Units</td>
                    	<td ><?php echo number_format($htot, 0, ".", ","); ?></td>
						<td ><?php echo number_format($htot, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h1, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h2, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h3, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h4, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h5, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h6, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h7, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h8, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h9, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h10, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h11, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h12, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h13, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h14, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h15, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h16, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h17, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h18, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h19, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h20, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h21, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h22, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h23, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h24, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h25, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h26, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h27, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h28, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h29, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h30, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h31, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h32, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h33, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h34, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h35, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo number_format($h36, 0, ".", ","); ?></td>
					</tr>
					
					<tr>
						<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Net Revenue</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($inpv, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($itot, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i1, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i2, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i3, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i4, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i5, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i6, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i7, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i8, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i9, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i10, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i11, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i12, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i13, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i14, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i15, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i16, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i17, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i18, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i19, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i20, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i21, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i22, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i23, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i24, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i25, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i26, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i27, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i28, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i29, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i30, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i31, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i32, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i33, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i34, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i35, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($i36, 0, ".", ","); ?></td>
					</tr>
					
					<tr>
						<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Total Product Cost</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($jnpv, 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format($jtot, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j1, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j2, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j3, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j4, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j5, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j6, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j7, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j8, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j9, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j10, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j11, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j12, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j13, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j14, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j15, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j16, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j17, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j18, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j19, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j20, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j21, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j22, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j23, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j24, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j25, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j26, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j27, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j28, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j29, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j30, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j31, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j32, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j33, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j34, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j35, 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format($j36, 0, ".", ","); ?></td>
					</tr>
					
					<tr class="tmain">
						<td class="label">&nbsp;&nbsp;Gross Product Margin</td>
                    	<td ><?php echo CUR_SIGN." ".number_format(($inpv-$jnpv), 0, ".", ","); ?></td>
						<td ><?php echo CUR_SIGN." ".number_format(($itot-$jtot), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i1-$j1), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i2-$j2), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i3-$j3), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i4-$j4), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i5-$j5), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i6-$j6), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i7-$j7), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i8-$j8), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i9-$j9), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i10-$j10), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i11-$j11), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i12-$j12), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i13-$j13), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i14-$j14), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i15-$j15), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i16-$j16), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i17-$j17), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i18-$j18), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i19-$j19), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i20-$j20), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i21-$j21), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i22-$j22), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i23-$j23), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i24-$j24), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i25-$j25), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i26-$j26), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i27-$j27), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i28-$j28), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i29-$j29), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i30-$j30), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i31-$j31), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i32-$j32), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i33-$j33), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i34-$j34), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i35-$j35), 0, ".", ","); ?></td>
						<td class="togrange"><?php echo CUR_SIGN." ".number_format(($i36-$j36), 0, ".", ","); ?></td>
					</tr>
					
					
					
                </table>
                <?php
                	$gmp = (($inpv-$jnpv)-($dnpv-$enpv));
					$roi = 0;
					$pby = 0;
					
                	$roi_div = ($fnpv+$ec1_data[0]['value']-$ec2_data[0]['value'])-$anpv;
					if($roi_div != 0)
					{
						$roi = $gmp/$roi_div*100;
					}
					
					$pby_div = $gmp*12/36;
					if($pby_div != 0)
					{
						$pby = $roi_div/$pby_div;
					}
					
					
                ?>
                <table id="tb2" class="avtable_2" style="margin-top: 20px !important; display: none !important;">
                	<tr class="tmain">
						<td class="label">Net Benefit:</td>
                    	<td ><?php echo CUR_SIGN." ".number_format($gmp, 0, ".", ","); ?></td>
						
					</tr>
					<tr class="tmain">
						<td class="label">Return on Investment (ROI):</td>
                    	<td ><?php echo number_format($roi, 2, ".", ","); ?>%</td>
						
					</tr>
					<tr class="tmain">
						<td class="label">Payback Years:</td>
                    	<td ><?php echo number_format($pby, 2, ".", ","); ?></td>
						
					</tr>
                </table>
                
                <table id="tb3" class="avtable_2">
                	<tr>
                		<td>&nbsp;</td>
                		<td class="thead">Plan Version (Base Line)</td>
                		<td class="thead">Investment Initiative Version (TO-BE)</td>
                		
                		<td class="thead">Variance</td>
                	</tr>
                	
                	<tr class="tmain">
                		<td class="label">Total Resource Cost</td>
                		<td><?php echo CUR_SIGN." ".number_format(($atot+$btot), 0, ".", ","); //3year ?></td>
                		<td><?php echo CUR_SIGN." ".number_format(($ftot+$gtot), 0, ".", ","); //3year ?></td>
                		
                		<td><?php echo CUR_SIGN." ".number_format(($atot+$btot) - ($ftot+$gtot), 0, ".", ","); //3year ?></td>
                	</tr>
                	
                	
                	<tr>
                		<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Primary Cost</td>
                		<td><?php echo CUR_SIGN." ".number_format($atot, 0, ".", ","); //3year ?></td>
                		<td><?php echo CUR_SIGN." ".number_format($ftot, 0, ".", ","); //3year ?></td>
                		
                		<td><?php echo CUR_SIGN." ".number_format($atot - $ftot, 0, ".", ","); //3year ?></td>
                	</tr>
                	<tr>
                		<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Secondary Cost</td>
                		<td><?php echo CUR_SIGN." ".number_format($btot, 0, ".", ","); //3year ?></td>
                		<td><?php echo CUR_SIGN." ".number_format($gtot, 0, ".", ","); //3year ?></td>
                		
                		<td><?php echo CUR_SIGN." ".number_format($btot - $gtot, 0, ".", ","); //3year ?></td>
                	</tr>
                	
                	<tr class="tmain">
                		<td class="label">Gross Product Margin</td>
                		<td><?php echo CUR_SIGN." ".number_format(($dtot-$etot), 0, ".", ","); //3year ?></td>
                		<td><?php echo CUR_SIGN." ".number_format(($itot-$jtot), 0, ".", ","); //3year ?></td>
                		
                		<td><?php echo CUR_SIGN." ".number_format(($dtot-$etot) - ($itot-$jtot), 0, ".", ","); //3year ?></td>
                	</tr>
                	<tr >
						<td colspan="4" class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Product Profitability</td>
					</tr>
					<tr>
                		<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of Units</td>
                		<td><?php echo number_format($ctot, 0, ".", ","); //3year ?></td>
                		<td><?php echo number_format($htot, 0, ".", ","); //3year ?></td>
                		
                		<td><?php echo number_format($ctot - $htot, 0, ".", ","); //3year ?></td>
                	</tr>
                	<tr>
                		<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Net Revenue</td>
                		<td><?php echo CUR_SIGN." ".number_format($dtot, 0, ".", ","); //3year ?></td>
                		<td><?php echo CUR_SIGN." ".number_format($itot, 0, ".", ","); //3year ?></td>
                		
                		<td><?php echo CUR_SIGN." ".number_format($dtot - $itot, 0, ".", ","); //3year ?></td>
                	</tr>
                	<tr>
                		<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Product Cost</td>
                		<td><?php echo CUR_SIGN." ".number_format($etot, 0, ".", ","); //3year ?></td>
                		<td><?php echo CUR_SIGN." ".number_format($jtot, 0, ".", ","); //3year ?></td>
                		
                		<td><?php echo CUR_SIGN." ".number_format($etot - $jtot, 0, ".", ","); //3year ?></td>
                	</tr>
					
					
                	
                </table>
                
                <?php
                	$gmp_3years = ($itot-$jtot) - ($dtot-$etot);
                	$investment = ($ftot+$ec1_data[0]['value']) - $atot;
					$chart1 = "<set label='Net Benefit' value='".$gmp_3years."' /><set label='Investment' value='".$investment."' />";
					$chart2 = "<set label='TO-BE' value='".($itot-$jtot)."' /><set label='Base Line' value='".($dtot-$etot)."' />";
                ?>
                
            </div>
            
        </td>
    </tr>
    <tr>
        <td id="tsidebarf" class="valignbot"><?php $this->load->view("footer"); ?></td>
    </tr>
</table>
<script type="text/javascript">
	var myChart1 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_1", "600", "300", "0", "1");
    myChart1.setXMLData("<chart caption='' bgColor='FFFFFF' decimals='2' showBorder='0' canvasBorderAlpha='0' xAxisName='' yAxisName='' showValues='0' numberSuffix='' stack100Percent='0'>"+"<?php echo $chart1; ?>"+"</chart>");
    myChart1.render("chartContainer1");
    
    var myChart2 = new FusionCharts("<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/Column2D.swf", "chartId_2", "600", "300", "0", "1");
    myChart2.setXMLData("<chart caption='' bgColor='FFFFFF' decimals='2' showBorder='0' canvasBorderAlpha='0' xAxisName='' yAxisName='' showValues='0' numberSuffix='' stack100Percent='1'>"+"<?php echo $chart2; ?>"+"</chart>");
    myChart2.render("chartContainer2");

</script>
<?php
	//cell replace code
	
	$update_cube = $this->jedox->cell_replace($database_1, $cube_1, $new_area, $gmp);
	//print_r ($database_1."---".$cube_1."---".$new_area."----".$gmp);
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