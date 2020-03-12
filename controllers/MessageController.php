<?php
    require_once('include.php');
    require_once('../helpers/session.php');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["send"])) {
            $sender_id = $_SESSION['user_id'];
            $recipient_id = $_POST['recipient'];
            $title = $_POST['title'];
            $message = $_POST['message'];

            $query = "INSERT INTO communications (sender_id, recipient_id, title, message) VALUES('$sender_id', '$recipient_id', '$title', '$message')";

            if ($connection->query($query) === TRUE) {
                $_SESSION['message'][] = "Message has been delivered";
            }
            else {
                $_SESSION['error'][] = "Failed upon sending your message";
            }

            header('Location: ../send.php');

        }
    }
?>