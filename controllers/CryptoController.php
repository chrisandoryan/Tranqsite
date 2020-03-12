<?php
    require_once('../helpers/crypto.php');
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['activation_code'])) {
            $activation_code = $_POST['activation_code'];
            if ($activation_code == decrypt('PPTI', 'h4nticpd1vsdgmhx4amqpml4cznii')) {
                echo 'Correct!';
            }
            else {
                echo 'Wrong!';
            }
        }
    }
    else if ($_SERVER["REQUEST_METHOD"] === "GET") {
        if (isset($_GET['getChallenge'])) {
            //encrypt(key, string)
            echo encrypt('PPTI', 's4yaanak1ndonesi4hebatd4nkuat');
        }
    }