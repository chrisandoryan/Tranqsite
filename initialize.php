<?php 
/**
 * Initialization Script
 * ---------------------
 * This script base on Initialization Script
 * by AS14-0 (Albert Richard Sanyoto)
 * 
 * With some edited code on following function:
 *  --> Drop All Tables 
 *  --> Create All Tables 
 *  --> Insert Data Seeder 
 *  --> Add Dynamically Bind Params in Prepared Statements with MySQLi 
 */

/**
 * Initialization Script
 * ---------------------
 * Copyright © 2018 by SW16-2 (Kevin Surya Wahyudi)
 * All rights reserved.
 */

/**
 * WARNING!!!
 * Do not modify the code below unless you know how to read and write the script.
 */

/**
 * INITIALIZATION SCRIPT HTML TAGS
 * These are the essential tags to form the HTML page of the initialization report
 */

require_once('helpers/function.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Initialization Script</title>
    <link rel="stylesheet" href="<?= asset('vendor/bootstrap/css/bootstrap.min.css'); ?>">
    <script src="<?= asset('vendor/jquery/jquery-3.3.1.min.js'); ?>"></script>
    <script src="<?= asset('vendor/bootstrap/js/bootstrap.min.js'); ?>"></script>
</head>
<body class="bg-dark">
    <div class="container-fluid mt-3 mb-3">
        <header class="jumbotron text-center">
            <h1 class="title">Initialization Script</h1>
        </header>
        <article>
<?php
/**
 * DATABASE CONFIGURATION
 * This is the configuration for the mysql connection
 * The default setting should be the default configuration of MySQL in Laboratory classes
 * 
 * ALERT!!!
 * DATABASE `information_schema` MUST BE EXISTS BEFORE USING THIS SCRIPT
 */
$config = config('database');

/**
 * INITIALIZATION SCRIPT VARIABLES
 * This is the supporting variables required for this initialization script to run
 */
function create_report($title, $content){
    $report_template = 
    "<div class='card mb-3'>
        <div class='card-header'>{{title}}</div>
        <div class='card-body'>
            {{row}}
        </div>
    </div>";

    $row_template = 
    "<div class='row'>
        <div class='col-lg-12 col-md-12 col-sm-12'>
            {{body}}
        </div>
    </div>";

    $body_contents = [];
    if(is_array($content)){
        $body_contents = $content;
    }
    else{
        $body_contents[] = $content;
    }
    $report_string = str_replace("{{title}}", $title, $report_template);
    $row_string = "";
    foreach($body_contents as $body){
        $row_string .= str_replace("{{body}}", $body, $row_template);
    }
    $report_string = str_replace("{{row}}", $row_string, $report_string);
    return $report_string;
}

/**
 * PRE INTIALIZATION CHECK
 * Check whether the website has been initialized.
 * If the website has been initialized, a force command must be given for the script to reinitialize everything.
 */ 
$preflight = new mysqli($config['server'], $config['username'], $config['password'], 'information_schema');
if($preflight->connect_error){
    $message = "Pre-initialization connect failed: $preflight->connect_error";
    die(create_report("Preflight Report", $message));
}

$db = "CREATE DATABASE IF NOT EXISTS " . $config['database'];
$preflight->query($db);
$preflight->select_db($config['database']);

$initialized = false;
$force = false;

$init_var_check = "SELECT `value` FROM app_config WHERE `key` = 'initialized' AND value = 1";
$check = $preflight->query($init_var_check);
if($check){
    $result = $check->fetch_assoc();
    if($result && $result['value']){
        $initialized = true;
    }
}

$preflight->close();

if($initialized){
    $force_param = isset($_REQUEST['force']);
    if($force_param){
        $preflight_report_string = create_report("Preflight Report", "Data has been initialized. Force initialization is issued. This initialization script will re-initialize the application.");
        $force = true;
    }
    else{
        $preflight_report_string = create_report("Preflight Report", [
            "Data has been initialized. Please add force parameter if you want to re-initialize the application.", 
            "<a href='?force' class='btn btn-warning'>Force Initialization</a><a href='". url('/') . "' class='btn btn-primary'>Open Website</a>"
        ]);
    }
}
else{
    $preflight_report_string = create_report("Preflight Report", "Application is ready for initialization.");
}
echo($preflight_report_string);

/**
 * INITIALIZATION
 * Initialization will initializes everything required by the website
 * This step may only be run if the website has not been
 * initialized or it is forced to initialize
 */
if(!$initialized || $force){
    /**
     * MYSQL CONNECTION
     * This step initializes the MySQLi Instance for executing database queries.
     */
    $connection = new mysqli($config['server'], $config['username'], $config['password'], $config['database']);
    if($connection->connect_error){
        $message = "Failed to connect to database: $connection->connect_error";
        die(create_report("MySQLi Connect Report", $message));
    }
    $connection->set_charset("utf8");

    $message = "Successfully established database connection to " . $config['database'] . "@" . $config['server'] . " with username " . $config['username'];
    echo(create_report("MySQLi Connect Report", $message));
    
    /**
     * PREPARATION STEP
     * This step for preparation all the tables required for the website to work properly.
     */
    // all tables in the database
    $tables = "2 tables (users, app_config)";    

    /**
     * TABLE SCHEMA DROPPING
     * This step deleted the tables required if already exists on the database for the website to work properly.
     */

    // All query for DROP TABLE
    $drop_tables = [
        "DROP TABLE IF EXISTS users",
        "DROP TABLE IF EXISTS app_config",
    ];

    foreach($drop_tables as $drop){
        if(!$connection->query($drop)){
            $message = "Drop tables failed: $connection->error";
            die(create_report("Table Schema Dropping Report", $message));
        }
    }

    echo(create_report("Table Schema Dropping Report", "Successfully drops all $tables"));

    /**
     * TABLE SCHEMA CREATION
     * This step builds the tables required for the website to work properly.
     */

    // All query for Query CREATE TABLE
    $create_tables = [
        "CREATE TABLE `users` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `fullname` VARCHAR(255) NOT NULL,
            `username` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `password` VARCHAR(100) NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        )",
        "CREATE TABLE `app_config` (
            `key` VARCHAR(15),
            `value` BOOLEAN,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`key`)
        )",
    ];

    foreach($create_tables as $create){
        if(!$connection->query($create)){
            $message = "Table creation failed: $connection->error";
            die(create_report("Table Schema Creation Report", $message));
        }
    }

    echo(create_report("Table Schema Creation Report", "Successfully creates all $tables"));

    /**
     * DATA SEEDER
     * This step fills the database with dummy data that could be used to simulate a working website.
     */

    // All query for INSERT TABLE
    $insert_tables = [
        "INSERT INTO `users` (`fullname`, `username`, `email`, `password`) VALUES (?, ?, ?, ?)",
        "INSERT INTO `app_config` (`key`, `value`) VALUES (?, ?)",
    ];

    // Generate dummy users
    $dummy_users = [
        [ "ssss", "dummy1", "dummy1", "dummy1@dummy.com", "dummy1dummy1" ],
        [ "ssss", "dummy2", "dummy2", "dummy2@dummy.com", "dummy2dummy2" ],
        [ "ssss", "dummy3", "dummy3", "dummy3@dummy.com", "dummy3dummy3" ],
	];

    // Insert initialization key to prevent this script from re-initializing the website
    $app_config_value = [
        [ "si", "initialized", true ],
    ];

    $initialize_datas = [
        [ $insert_tables[0], $dummy_users ],
        [ $insert_tables[1], $app_config_value ],
    ];

    foreach($initialize_datas as $init){
        foreach($init[1] as $data){
            $stmt = $connection->prepare($init[0]);
            if(!$stmt){
                $message = "Statement preparation failed: $connection->error";
                die(create_report("Statement Preparation Report", $message));
            }
            $params = [];
            for($i = 0; $i < count($data); $i++){
                $params[] = &$data[$i];
            }
            call_user_func_array([$stmt, "bind_param"], $params);
            $stmt->execute();
            $stmt->close();
        }
    }

    $connection->close();

    echo(create_report("Statement Preparation Report", "Statement preparation successful"));

    echo(create_report("Table Seeder Report", [
        "User data entered (". count($dummy_users) . " data(s))", 
    ]));

    /**
     * Initialization Completed
     * This step show the random username and password from dummy data that could be used to log in to website.
     */
    $users = $dummy_users[rand(0, count($dummy_users) - 1)];
    $username = $users[2];
    echo(create_report("Initialization Completed", [
        "You can log in with this credential (username/password): $username/$username",
        "All default credentials generated through this initialization has the password set equal to the username",
        "<a href='". url('/') . "' class='btn btn-primary'>Open Website</a>"
    ]));

}
?>
    </article>
    <footer class="container-fluid text-white">
        <strong>Copyright &copy; 2018 by SW16-2 (Kevin Surya Wahyudi)<br>All rights reserved</strong> 
    </footer>
</body>
</html>
