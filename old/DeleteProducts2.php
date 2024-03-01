<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user = $_SESSION["user"];

$product_data = $_REQUEST['pID'];
$product_data = stripslashes($product_data);
$product_data_array = array();
$product_data_array = json_decode($product_data);

$things = array();


for ($i = 0; $i < count($product_data_array); $i++) {
    $things = explode(",", $product_data_array[$i]);
    
    $product_id = $things[0];
    $ProductsData = SelectParticularRow('macho_master_products', 'id', $product_id);
    $product_name = $ProductsData['product_name'];

    DeleteRow('macho_master_products', 'id', $product_id);
    
    $notes = $product_name . ' Product Details Deleted by ' . $user;
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
}

echo '1';

