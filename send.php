<?php require_once('helpers/function.php'); ?>
<?php require_once('helpers/session.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php component('head'); ?>

<?php
  if (!isset($_SESSION['auth'])) {
    $_SESSION['error'][] = "Please login first.";
    redirect('login.php');
  }
?>

<?php 
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

</head>
<div class="nav">
	<a class="button btn btn-success btn-ghost newq" href="messages.php">Messages</a>
	<a class="button btn btn-primary btn-ghost newq" href="send.php">Send Message</a>
	<a class=" btn btn-default btn-ghost skip" href="./controllers/AuthController.php?logout">Logout</a>
</div>
<body class="hack dark">
	<div class="grid main-form">
    <form class="form" method="POST" action="./controllers/MessageController.php">
      <fieldset class="form-group">
        <label for="username">Title:</label>
        <input id="title" name="title" type="text" placeholder="" class="form-control">
      </fieldset>
      <fieldset class="form-group">
        <label for="recipient">To:</label>
        <select id="recipient" name="recipient" class="form-control">
          <option value="1">Administrator</option>
          <option value="2">Network Manager</option>
          <option value="3">IT Support</option>
          <option value="4">Coworker</option>
        </select>
      </fieldset>
      <fieldset class="form-group form-textarea">
        <label for="message">Message:</label>
        <textarea id="message" rows="5" class="form-control" name="message"></textarea>
      </fieldset>
      <div class="form-actions">
        <input type="submit" class="btn btn-primary btn-block btn-ghost" name="send" />
      </div>
    </form>
	</div>
	<div class="footer">
		O le ale strontos, vi gaskar magheda
	</div>
</body>
</html>
