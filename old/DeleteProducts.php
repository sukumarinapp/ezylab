<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user = $_SESSION["user"];

$id = $_POST['id'];
$product_name = $_POST['productname'];

$Delete_Sql = DeleteRow('macho_master_products', 'id', $id);

if ($Delete_Sql) {
    $notes = $product_name . ' Product Details Deleted by ' . $user;
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
    echo '1';
} else {
    echo '0';
}

