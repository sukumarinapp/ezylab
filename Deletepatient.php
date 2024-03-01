<?php
session_start();
include_once 'booster/bridge.php';
IsAjaxRequest();

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user"];
$user_role_id = $_SESSION["role_id"];

$id = Filter($_POST['id']);

$patientname = Filter($_POST['P_name']);

$Delete_sql = DeleteRow('macho_patient', 'id', $id);

if ($Delete_sql) {

    $notes = $patientname . ' details Removed from Patient list by ' . $user_name;
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $user_role_id, $receive_role_id, $receive_id);

    echo '1';
} else {
    echo '0';
}