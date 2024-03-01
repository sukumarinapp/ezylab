<?php
include_once 'booster/bridge.php';
IsAjaxRequest();
$entry_id = $_POST['id'];
$status='0';
$updateCustomer = Update('test_documents', 'entry_id', $entry_id, array(
    'delete_status' => $status
));


