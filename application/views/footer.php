<script type="text/javascript">
$(document).ready(function() {
	$( "#dialog-copyright" ).dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	 $( "#show-copyright" )
		.click(function() {
			$( "#dialog-copyright" ).dialog( "open" );
		});
});
</script>
<div class="footer">
Database <?php echo $this->session->userdata['jedox_db']; ?><br/>
Copyright <a class="footerlink" href="http://proeo.com" title="Visit proEO.com" target="_blank">proEO, LLC</a><br />
Powered by <a class="footerlink" href="http://www.jedox.com" title="Visit jedox.com" target="_blank">Jedox</a> &amp; 
<a class="footerlink" href="http://www.fusioncharts.com" title="Visit fusioncharts.com" target="_blank">Fusion Charts</a><br />
<a id="show-copyright" href="#" onclick="return false;" class="copyright_details" title="About proEO">About proEO</a>
</div>
<div id="dialog-copyright" title="Copyright">
	<p class="center"><img src="<?php echo base_url(); ?>assets/images/proeo_logo2.png" /></p>
	<p class="center"><strong>proEO Version 2.1.00.100</strong><br />
	&copy; 2009 - 2013 proEO, LLC.  All Rights Reserved</p>

	<p class="center">proEO and the slogan Actionable Intelligence are trademarks of proEO, LLC.<br />  
Jedox and FusionCharts are trademarks of their respective holders.</p>

	<p class="center">Warning: This product is protected by international intellectual property rights and treaties.  Unauthorized reproduction or distribution of this program, or portions of the same, may result in severe civil and criminal penalties and will be prosecuted to the maximum extent possible under law.</p>
</div>
