<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.1.custom.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Cuprum:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/smoothness/jquery-ui-1.9.1.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" media="screen" />
<script type="text/javascript">

$(document).ready(function() {

	$('.default-value').each(function() {
		var default_value = this.value;
		$(this).focus(function() {
			if(this.value == default_value) {
				this.value = '';
			}
		});
		$(this).blur(function() {
			if(this.value == '') {
				this.value = default_value;
			}
		});
	});
	
	$( "#dialog-name" ).dialog({
		<?php
			if(form_error('user') == '')
			{
		?>
			autoOpen: false,
		<?php	
			} else {
		?>	
			autoOpen: true,
		<?php	
			}
		?>
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	$( "#dialog-pass" ).dialog({
		<?php
			if(form_error('password') == '')
			{
		?>
			autoOpen: false,
		<?php	
			} else {
		?>	
			autoOpen: true,
		<?php	
			}
		?>
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	
	$( "#dialog-login" ).dialog({
		<?php
			if($error == '')
			{
		?>
			autoOpen: false,
		<?php	
			} else {
		?>	
			autoOpen: true,
		<?php	
			}
		?>
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	
	
});

function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

delete_cookie('altaviatabber');

</script>
<title>proEO | Login</title>
</head>

<body>
<table height="100%" width="100%" style="background-color:#EEEEEE;">
	<tr>
		<td valign="middle">
		<div class="login_fr reflect">
			<img src="<?php echo base_url(); ?>assets/images/proeo_logo.png" /><br />
			<span class="aitext">Actionable Intelligence</span>
			<form id="form1" name="form1" method="post" action="<?php echo current_url(); ?>">
				<p>
					<label>
						<input name="user" type="text" id="user" maxlength="50" value="Username" class="ddown2 center default-value" />
					</label>
				</p>
				<p>
					<label>
						
						<input name="password" type="password" id="password" maxlength="50" value="Password" class="ddown2 center default-value" />
					</label>
				</p>
				<p>
					<label>
						<select title="Select Database" class="ddown2" name="database">
							
							<option value="ProEo_Getwell_3" >Get Well (2015)</option>
							
						</select>
					</label>
				</p>
				<input name="Login" type="submit" id="Login" value="Login" class="obutton1" />
				
			</form>
			
		</div>
		
		</td>
	</tr>
</table>
<div id="dialog-name" title="Login Error">
	<span class="ui-icon ui-icon-alert red" style="float: left; margin-right: .3em;"></span><?php echo form_error("user", "<span class='error_text'>", "</span>"); ?>
</div>
<div id="dialog-pass" title="Login Error">
	<span class="ui-icon ui-icon-alert red" style="float: left; margin-right: .3em;"></span><?php echo form_error("password", "<span class='error_text'>", "</span>"); ?>
</div>
<div id="dialog-login" title="Login Error">
	<span class="ui-icon ui-icon-alert red" style="float: left; margin-right: .3em;"></span><span class="error_text"><?php echo $error; ?></span>
</div>
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
