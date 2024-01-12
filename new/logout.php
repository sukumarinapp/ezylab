<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$access_token = $_SESSION["access_token"];
ValidateAccessToken($user_id, $access_token);
//LogOutEntry($user_id);
session_unset();
session_destroy();
header("location:index");