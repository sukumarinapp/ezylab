<?php
include_once 'booster/bridge.php';
IsAjaxRequest();

$entry_id = $_POST['entry_id'];
$test_id = $_POST['test_id'];
$file_name = $_POST['file_name'];
$document_detail = $_POST['document_detail'];

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

if ($_FILES['file']['name'] != "") {
    $date = date("Y-m-d");
    $document_name = $_FILES['file']['name'];
    $document_tmp_name = $_FILES['file']['tmp_name'];
    $document_size = $_FILES['file']['size'];
    $document_file_extension = pathinfo($document_name, PATHINFO_EXTENSION);
    $insertDocument = Insert('test_documents', array(
        'entry_id' => $entry_id,
        'test_id' => $test_id,
        'description' => $file_name,
        'document_detail' => $document_detail,
        'file_type' => $document_file_extension,
        'file_size' => $document_size,
        'create_date' => $date
    ));
    if (is_int($insertDocument)) {

            $test_pic = $insertDocument . "." . $document_file_extension;
            $move_path = "test_pic/";
            $move_path = $move_path . $test_pic;
            $target_path = SITEURL . "test_pic/";
            $target_path = $target_path . $test_pic;

        if (file_exists($target_path)) {
            unlink($target_path);
        }
        if (move_uploaded_file($document_tmp_name, $move_path)) {

            $updateCustomer = Update('test_documents', 'document_id', $insertDocument, array(
                'file_url' => $target_path
            ));
        }

    }

    echo '0';
} else {
    echo '1';
}