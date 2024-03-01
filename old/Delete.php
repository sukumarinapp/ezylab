<?php
include_once 'booster/bridge.php';
$table = $_POST['table'];
$key = $_POST['key'];
$id = $_POST['id'];
$Delete_Sql = DeleteRow($table,$key, $id);
if($Delete_Sql){
    echo '1';
}else{
    echo '0';
}