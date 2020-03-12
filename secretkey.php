<?php require_once('helpers/function.php'); ?>
<?php require_once('helpers/session.php'); ?>
<?php
	if (!isset($_SESSION['auth'])) {
		$_SESSION['error'][] = "Please login first.";
		redirect('login.php');
	}
	else {
		if ($_COOKIE['is_admin'] == "false") {
			echo "<script>alert(\"Only user with admin privileges can access this page.\\r\\nThis incident has been reported.\"); document.location='send.php'</script>";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php component('head'); ?>
</head>
<div class="nav">
	<a class="button btn btn-success btn-ghost newq" href="secretkey.php">Decrypt Code</a>
	<a class="button btn btn-info btn-ghost twitter" href="robots.txt">Readme</a>
	<a class=" btn btn-default btn-ghost skip" href="login.php">Admin Access</a>
</div>
<body class="hack dark">
	<div class="grid main-form">
		<form>
			<fieldset class="form-group form-success">
				<label for="code">Decrypted:</label>
				<input id="code" name="code" type="text" placeholder="" class="form-control">
				<div class="help-block">In this format: [a-z0-9]+</div>
				<div class="help-block" id="encrypted-block">Encrypted: </div>
			</fieldset>
			<a class="button btn btn-info btn-ghost twitter" id="btn-unlock">Unlock</a>
		</form>
	</div>
	<div class="footer">
		Sst. Listen for the song of the sea, aye?
	</div>
</body>
<script>
var encrypted_message = "";
$(document).ready(function() {
	$.ajax({url: "controllers/CryptoController.php?getChallenge", success: function(result){
		encrypted_message = result;
		$("#encrypted-block").html('Encrypted: ' + encrypted_message);
	}});
	$("#btn-unlock").click(function(){
		$.post("controllers/CryptoController.php",
		{
			activation_code: $('#code').val(),
		},
		function(data, status){
			alert("Response: " + data);
		});
	});
});
</script>
</html>
