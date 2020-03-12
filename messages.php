<?php require_once('helpers/function.php'); ?>
<?php require_once('controllers/connection.php'); ?>
<?php require_once('helpers/session.php'); ?>

<?php
  function mapRole($id) {
    if ($id == 1) 
      return "Administrator";
    else if ($id == 2) 
      return "Network Manager";
    else if ($id == 3) 
      return "IT Support";
    else if ($id == 4)
      return "Coworker";
  }
?>

<?php
  if (!isset($_SESSION['auth'])) {
    $_SESSION['error'][] = "You are not logged in.";
    // redirect('login.php');
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

<!DOCTYPE html>
<html lang="en">
<head>
<?php component('head'); ?>
</head>
<div class="nav">
	<a class="button btn btn-success btn-ghost newq" href="messages.php">Messages</a>
	<a class="button btn btn-primary btn-ghost newq" href="send.php">Send Message</a>
	<a class=" btn btn-default btn-ghost skip" href="./controllers/AuthController.php?logout">Logout</a>
</div>
<body class="hack dark">
	<div class="grid main-form">
  <?php
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM communications"; // WHERE sender_id='$user_id'";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
  ?>
    <div>
      <div class="card">
        <header class="card-header">To: <?= mapRole($row['recipient_id']) ?></header>
        <header class="card-header"><?= $row['title'] ?></header>
        <div class="card-content">
          <div class="inner"><?= $row['message'] ?></div>
        </div>
      </div>  
    </div>
  <?php
      }
    }
    else {
?>
  <div class="alert alert-warning">No messages found.</div>
<?php
    }
?>
</body>
</html>
