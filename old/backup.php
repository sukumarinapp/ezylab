<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$database = 'dream_lims';
$user = 'root';
$pass = '';
$host = 'localhost';
$file_name = date("Y-m-d");
$dir = dirname(__FILE__) . '/backup-'.$file_name.'.sql';

echo "<h3>Backing up database to `<code>{$dir}</code>`</h3>";

exec("mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$dir} 2>&1", $output);

//var_dump($output);

//header("location: Dashboard");

?>