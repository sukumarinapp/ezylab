<?php
include_once 'booster/bridge.php';
IsAjaxRequest();

$id = $_POST['id'];
$file_url = $_POST['file_url'];
$file_path = ltrim($file_url, SITEURL);
$sql = "DELETE  FROM  test_documents WHERE document_id = '$id'";
$result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
if ($result) {
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    echo '1';
} else {
    echo '0';
}
