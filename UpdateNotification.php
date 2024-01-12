<?php
include_once 'booster/bridge.php';
$user_id = $_REQUEST['user_id'];
$sql = "UPDATE macho_notifications SET view = '1' WHERE receive_id='$user_id'";
$result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));