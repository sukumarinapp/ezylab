<?php
include_once 'booster/bridge.php';
$id = $_POST['id'];
$status = $_POST['status'];
if ($status == '0') {
    $status_value = '1';
} else {
    $status_value = '0';
}

$update = Update('macho_user_login_attempts', 'id', $id, array(
    'block_status' => Filter($status_value),
));
echo $status_value;