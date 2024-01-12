<?php
session_start();
include_once 'booster/bridge.php';
IsAjaxRequest();
$user_id = DecodeVariable($_POST["user_id"]);
$user_name = $_POST["user_name"];
$access_token = $_POST["access_token"];
$url = $_POST["url"];
$created = date("Y-m-d");

$sql2 = "INSERT INTO `macho_session_brokedup` (`login_id`, `username`, `access_token`, `current_url`, `created`)
                                            VALUES ('$user_id', '$user_name', '$access_token', '$url', '$created');";
$result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
if ($result2) {
    session_unset();
    session_destroy();
    echo '1';
} else {
    echo '0';
}