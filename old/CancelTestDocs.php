<?php
include_once 'booster/bridge.php';
IsAjaxRequest();
$entry_id = $_POST['id'];

$Query = "SELECT document_id,file_url FROM test_documents WHERE entry_id = '$entry_id' AND delete_status='1'";
$Result = GetAllRows($Query);
$Counts = count($Result);
if ($Counts > 0) {
    foreach ($Result as $Data) {
        $doc_id = $Data['document_id'];
        $file_url = $Data['file_url'];
        $file_path = ltrim($file_url, SITEURL);

        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                $sql = "DELETE  FROM  test_documents WHERE document_id = '$doc_id'";
                $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
            }
        }
    }
}






