<?php
include_once 'booster/bridge.php';
IsAjaxRequest();

$id = $_POST['id'];
$DocumentData = SelectParticularRow('macho_documents', 'id', $id);
$file_name = $DocumentData['file_name'];

if (file_exists("Files/" . $file_name)) {
    unlink("Files/" . $file_name);
    $Delete_Sql = DeleteRow('macho_documents', 'id', $id);
    if ($Delete_Sql) {
        echo '1';
    } else {
        echo '0';
    }
}

