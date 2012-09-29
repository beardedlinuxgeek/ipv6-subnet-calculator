<?php

if ( isset($_POST['address']) && isset($_POST['prefix']) && isset($_POST['levels']) ) {
	
	require_once 'calculator_tools.php';
	require_once 'Network.php';
	
	$address = $_POST['address'];
	$prefix  = $_POST['prefix'];
	$levels  = $_POST['levels'];
	
	$ip = human_to_hex( $address );
	$network = new Network($ip, $prefix, $levels);
	print_html($network);
	exit();
}
	

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width">
	<title>IPv6 Subnet Calculator | Colin Robinson</title>
	<style>
	body{ font: normal normal 400 87.5%/1.5em Helvetica, 'Open Sans', Verdana, sans-serif; }
	#network{ font-family: monospace; font-size: 12px; }
	.open-children{ text-decoration: none; }
	</style>
</head>
<body>
	
	<h3>IPv6 Subnet Calculator Demo</h3>
	
	<div id="ipv6_calculator">
		
		<p>
			<label>Address:</label>
			<input type="text" id="address">
			<select id="prefix">
				<?php for ( $i = 49; $i < 64; $i++ ) : ?>
					<option value="<?php echo $i; ?>">/<?php echo $i; ?></option>
				<?php endfor; ?>
			</select>
		</p>
		
		<p>
			<label>Subnets:</label>
			<input type="text" id="subnets">
			<input type="button" value="Add Level" id="add_level">
		</p>
		
		<table id="levels">
			<tr>
				<th>Level</th>
				<th>Subnets</th>
			</tr>
		</table>
		
		<input type="button" value="Built It" id="build"> 
		
	</div>

	<div id="network"></div>
	
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>	
<script type="text/javascript">
$(function(){
	
	$("#add_level").click(function(){
		
		var subnets = parseInt( $("#subnets").val() );
		
		if ( subnets > 0 ) {
			var level = $("#levels tr").length;
			$("#levels tr:last").after("<tr><td>"+level+"</td><td>"+subnets+"</td></tr>");
		}
		
		$("#subnets").val('');
		return false;
	});
	
	
	$("#build").click(function(){
		
		var address = $("#address").val();
		var prefix = $("#prefix").val();
		var levels = new Array();
		$("#levels tr > td:nth-child(2)").each(function(){
			levels.push( $(this).text() );
		});
		
		$.post("subnet_calculator_demo.php", {address:address, prefix:prefix, levels:levels}, function(response){
			
			$("#network").html(response);
			
			$("#network > .branch").show();
			
			$("#network .open-children").click(function(){
				$(this).text('-');
				$(this).parent().children(".branch").toggle();
				return false;
			});
			
		});
	});
	
});
</script>
	
</body>
</html>