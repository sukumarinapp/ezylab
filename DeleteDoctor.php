<?php
session_start();
include_once 'booster/bridge.php';
IsAjaxRequest();

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user"];
$user_role_id = $_SESSION["role_id"];

$id = Filter($_POST['id']);

$doctorname = Filter($_POST['d_name']);

$Delete_sql = DeleteRow('doctors', 'id', $id);

if ($Delete_sql) {

    $notes = $doctorname . ' details Removed from Doctor list by ' . $user_name;
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $user_role_id, $receive_role_id, $receive_id);

    echo '1';
} else {
    echo '0';
}