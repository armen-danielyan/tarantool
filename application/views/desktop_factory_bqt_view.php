<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | Desktop Factory BQT</title>
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
            
        </td>
        <td class="tborder" onclick="tshowhide();" rowspan="2" title="Click to show/hide side panel.">
            <img id="togme" src="<?php echo base_url(); ?>assets/images/bar1.png" />
        </td>
        <td class="tcontent" rowspan="2">
            <?php
			$breadcrumb = anchor('home', 'Home', array('title' => 'Go to Home'))." > <span class='orange'>Desktop Factory BQT</span>";
			$this->load->view("header", array("breadcrumb" => $breadcrumb)); 
			?>
            <div class="advance_details">
                <?php
					// echo "";
                ?>
            </div>
            
            
            <div class="content_div" >
                
                <form id="form1" name="form1" method="post" action="<?php echo site_url('desktop_factory_bqt'); ?>">
                	<table id="tb1" class="avtable_2">
                		<tr>
                			<td class="thead">Product</td>
                			<td class="thead">Test</td>
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
			                            for($i=0; $i<$row['depth']; $i++)
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
                    			<select name="quality_test"  class="ddown1" title="Select Quality Test">
			                    <?php
			                        foreach($form_quality_test as $row)
			                        {
			                            $depth = '';
			                            for($i=1; $i<$row['depth']; $i++)
			                            {
			                                $depth .= '&nbsp;&nbsp;';
			                            }
			                    ?>  
			                        <option value="<?php echo $row['element']; ?>" <?php if($quality_test == $row['element']){ $n_quality_test = $row['name_element']; ?>selected="selected"<?php } ?> ><?php echo $depth.$row['name_element']; ?></option>
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
			                            for($i=0; $i<$row['depth']; $i++)
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
                			<td class="thead">Value 1</td>
                			<td class="thead">Value 2</td>
                			<td class="thead">Value 3</td>
                			<td>&nbsp;</td>
                		</tr>
                    	<tr>
                    		<td>
                    			<input type="text" id="quality_value1" name="quality_value1" class="ddown1" />
                    		</td>
                    		<td>
                    			<input type="text" id="quality_value2" name="quality_value2" class="ddown1" />
                    		</td>
                    		<td>
                    			<input type="text" id="quality_value3" name="quality_value3" class="ddown1" />
                    		</td>
                    		<td>&nbsp;</td>
                    	</tr>
                    	<tr>
                    		<td colspan="4" class="thead">
                    			Comment
                    		</td>
                    	</tr>
                    	<tr>
                    		<td colspan="4" class="center">
                    			<textarea name="quality_value4" id="quality_value4" style="height: 200px; width: 350px;"></textarea>
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