<?php
include_once 'booster/bridge.php';
$id = $_POST['id'];
$status = $_POST['status'];
if ($status == '0') {
    $status_value = '1';
} else {
    $status_value = '0';
}

$update = Update('macho_ip_tracking', 'id', $id, array(
    'blocked' => Filter($status_value),
));
echo $status_value;
