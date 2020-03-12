<?php
    require_once('include.php');
    require_once('../helpers/session.php');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["login"])) {
            if (!empty($_POST['csrf_token'])) {
                if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                    $_SESSION['error'][] = "Invalid CSRF token";
                }
                header('Location: ../login.php');
            }
            $username = $_POST["username"];
            $password = $_POST["password"];

            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = $connection->query($query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $_SESSION['auth'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['id'];

                $user = $row['username'];
                $_SESSION['message'][] = "Welcome back, $user!";

                if ($row['role'] === "admin") {
                    setcookie("is_admin", "true", time()+3600, "/");
                }
                else if ($row['role'] === "user") {
                    setcookie("is_admin", "false", time()+3600, "/");
                }

                header('Location: ../send.php');
            }
            else {
                $_SESSION['error'][] = "Incorrect username or password, please check";
                header('Location: ../login.php');
            }
        }
    }
    else if ($_SERVER["REQUEST_METHOD"] === "GET") {
        if (isset($_GET['logout'])) {
            session_destroy();
            header('Location: ../login.php');
        }
    }
?>