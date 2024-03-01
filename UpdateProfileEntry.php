<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user_name = $_SESSION["user"];

$profile_id = $_REQUEST['profile_id'];
$profile_name = $_REQUEST['profile_name'];
$total_amount = $_REQUEST['net_amount'];
$notes = $_REQUEST['notes'];

$created_date = date("Y-m-d");
$created = date("Y-m-d H:i:s");
$modified = date("Y-m-d H:i:s");

$sales = $_REQUEST['sales'];
$sales = stripslashes($sales);
$sales_array = array();
$sales_array = json_decode($sales);
$sales_array_count = count($sales_array);


$update = Update('macho_test_category', 'id', $profile_id, array(
    'type' => 'group',
    'category_name' => $profile_name,
    'description' => Filter($notes),
    'amount' => Filter($total_amount),
    'modified' => $modified
));

$Delete_Sql2 = DeleteRow('macho_profile_tests', 'profile_id', $profile_id);

for ($i = 0; $i < $sales_array_count; $i++) {
    $item_id = $sales_array[$i]->item_id;
    $item_category = $sales_array[$i]->item_category;
    $item_name = $sales_array[$i]->item_name;
    $item_amount = $sales_array[$i]->item_amount;

    $sql = "DELETE  FROM  macho_profile_tests WHERE profile_id = '$profile_id' AND item_id='$item_id'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));

    Insert(
        'macho_profile_tests',
        array(
            'profile_id' => Filter($profile_id),
            'item_id' => Filter($item_id),
            'item_name' => Filter($item_name),
            'test_category' => Filter($item_category),
            'amount' => Filter($item_amount),
        )
    );
}

$notes = $user_name . '  has Update ' . $profile_name. ' details';
$receive_id = '1';
$receive_role_id = GetRoleOfUser($receive_id);
InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
?>