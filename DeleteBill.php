<?php
session_start();
include_once 'booster/bridge.php';
IsAjaxRequest();

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user"];
$user_role_id = $_SESSION["role_id"];

$id = Filter($_POST['id']);
$bill_no = Filter($_POST['bill_no']);

$entry_date = date("Y-m-d");
$created = date("Y-m-d H:i:s");
$modified = date("Y-m-d H:i:s");





$Delete_sql = DeleteRow('macho_billing', 'id', $id);

if ($Delete_sql) {
    $notes = 'Bill No.: ' . $bill_no . ' details Removed from Bill list by ' . $user_name;
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $user_role_id, $receive_role_id, $receive_id);

    echo '1';
} else {
    echo '0';
}