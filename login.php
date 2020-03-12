<?php require_once('helpers/function.php'); ?>
<?php require_once('helpers/session.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php component('head'); ?>
</head>
<?php 
	if (!isset($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	$token = $_SESSION['csrf_token'];
	if (isset($_SESSION['error'])) {
		foreach ($_SESSION['error'] as $idx => $err) {
?>
<div class="alert alert-error"><?= $err ?></div>
<?php
		}
	}
	unset($_SESSION['error']);
?>
<?php 
	if (isset($_SESSION['message'])) {
		foreach ($_SESSION['message'] as $idx => $msg) {
?>
<div class="alert alert-success"><?= $msg ?></div>
<?php
		}
	}
	unset($_SESSION['message']);
?>
<div class="nav">
	<a class="button btn btn-success btn-ghost newq" href="secretkey.php">Decrypt Code</a>
	<a class="button btn btn-info btn-ghost twitter" href="robots.txt">Readme</a>
	<a class=" btn btn-default btn-ghost skip" href="login.php">Admin Access</a>
</div>
<body class="hack dark">
	<div class="grid main-form">
		<form action="controllers/AuthController.php" method="POST">
			<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>"/>
			<fieldset class="form-group form-success">
				<label for="username">USERNAME</label>
				<input id="username" name="username" type="text" placeholder="" class="form-control">
			</fieldset>
			<fieldset class="form-group form-success">
				<label for="password">PASSWORD</label>
				<input id="password" name="password" type="password" placeholder="" class="form-control">
			</fieldset>
			<br>
			<div>
				<button class="btn btn-primary btn-block btn-ghost" name="login" value="Login">Login</button>
				<div class="help-block">Only noble users are allowed to bypass access here</div>
			</div>
		</form>
	</div>
	<div class="footer">
		Valar Morghulis, ....
	</div>
</body>
</html>
