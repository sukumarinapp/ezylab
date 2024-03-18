<?php
session_start();
include_once 'booster/bridge.php';
$id = $_POST['id'];
$sql="delete from test_entry where entry_id=$id";
mysqli_query($GLOBALS['conn'], $sql);
$sql="delete from patient_entry where id=$id";
mysqli_query($GLOBALS['conn'], $sql);
$sql="delete from macho_bill_items where bill_id=$id";
mysqli_query($GLOBALS['conn'], $sql);
echo '1';
