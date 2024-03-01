<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$database = 'dream_lims';
$user = 'grainbow';
$pass = 'Grainbow123$';
$host = 'localhost';
$file_name = date("Y-m-d");
$dir = dirname(__FILE__) . '/backup-'.$file_name.'.sql';

//echo "<h3>Backing up database to `<code>{$dir}</code>`</h3>";

exec("mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$dir} 2>&1", $output);
$file_name = date("Y-m-d").".sql";
?>
<a href="backup-<?php echo $file_name; ?>" download >Download Database Backup</a>
