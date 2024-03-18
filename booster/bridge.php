<?php
date_default_timezone_set("Asia/Kolkata");
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ($_SERVER['HTTP_HOST'] == "localhost") {
    define('DBHOST', 'localhost');
    define('DBNAME', 'eazy_lab');
    define('DBUSER', 'root');
    define('DBPASS', '');
} else {
    define('DBHOST', 'localhost');
    define('DBNAME', 'dream_lims');
    define('DBUSER', 'grainbow');
    define('DBPASS', 'Grainbow123$');
}

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
include 'handler.php';

$web_config_sql = GetAllRows("SELECT parameter,value FROM macho_web_config ORDER BY id");
foreach ($web_config_sql as $web_config_data) {
    define($web_config_data['parameter'], ParameterValue($web_config_data['parameter']));
}




