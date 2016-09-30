<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>proEO | ETL</title>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/licensedCharts/FusionCharts_XT/Charts/FusionCharts.js"></script>
<link href='http://fonts.googleapis.com/css?family=Cuprum:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/smoothness/jquery-ui-1.9.1.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" media="screen" />
<script type="text/javascript">
$(document).ready(function() {

	$("#Project").change(function() {
		var val = $(this).val();
		<?php
			$step = 0;
			foreach($this->etlapi->GetProjects() as $row){
				if($step > 0){ echo " else ";}
				$step += 1;
		?>if(val == "<?php echo $row; ?>"){
			$("#Job").html('<?php foreach($this->etlapi->GetJobs($row) as $rows){ ?><option value="<?php echo $rows; ?>"><?php echo $rows; ?></option><?php } ?>');
		}
		<?php
			}
		?> else {
			$("#Job").html('<option value="">---</option>');
		}
		
	});
	

});
function gstat(){
	$.ajax({
		url:"<?php echo site_url('etl/gstatus'); ?>",
		success:function(result){
		$("#emess").html(result);
	}});
}



</script>
</head>

<body>

<form id="Execute_ETL_Job" action="<?php echo site_url('etl/execute'); ?>" method="post">
	<input type="hidden" id="task_type" name="task_type" value="Execute ETL Job">
	<select id="Project" name="Project">
		<option value="">Select Project</option>
		<?php
			foreach($this->etlapi->GetProjects() as $row){
		?>
			<option value="<?php echo $row; ?>"><?php echo $row; ?></option>
		<?php		
			}
		?>
	</select><br />
	<select id="Job" name="Job">
		<option value="">---</option>
	</select><br />
	Execute Etl Job <input type="submit" value="start">
</form>
<a href="#" onclick="gstat(); return false;" >Get Status</a> 
<span id="emess"><?php echo $edata; ?></span>

<script type="text/javascript">
	var frm = $('#Execute_ETL_Job');
	frm.submit(function () {
		$.ajax({
			type: frm.attr('method'),
			url: frm.attr('action'),
			data: frm.serialize(),
			success: function (result) {
				$("#emess").html(result);
			}
		});
		event.preventDefault();
	});
</script>

<?php
	//echo $this->etlapi->getStatus();
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