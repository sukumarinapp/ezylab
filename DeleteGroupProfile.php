<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user = $_SESSION["user"];

$profile_id = $_POST['profile_id'];
$profile_name = $_POST['profile_name'];

$Delete_Sql = DeleteRow('macho_test_category', 'id', $profile_id);
$Delete_Sql2 = DeleteRow('macho_profile_tests', 'profile_id', $profile_id);

if ($Delete_Sql2) {
    $notes = $profile_name .' Details Deleted by ' . $user;
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
    echo '1';
} else {
    echo '0';
}