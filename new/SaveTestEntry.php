<?php
session_start();
include_once 'booster/bridge.php';

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user"];
$user_role = $_SESSION["role_name"];
$user_role_id = $_SESSION["role_id"];

$created = date("Y-m-d");
$date_time = date("Y-m-d H:i:s");

$test_data = $_REQUEST['test_data'];
$test_data = stripslashes($test_data);
$test_data_array = array();
$test_data_array = json_decode($test_data);
$entry_id = $_REQUEST['entry_id'];

$status = '2';
$things = array();
for ($i = 0; $i < count($test_data_array); $i++) {

    $things = explode(",", $test_data_array[$i]);

    $test_id = $things[0];
    $test_result = $things[1];
    $sub_head = $things[2];
    $paragraph = $things[3];
    $head_1 = $things[4];
    $head_2 = $things[5];
    $head_3 = $things[6];
    $head_4 = $things[7];
    $head_5 = $things[8];
    $head_6 = $things[9];
    $result_1 = $things[10];
    $result_2 = $things[11];
    $result_3 = $things[12];
    $result_4 = $things[13];
    $result_5 = $things[14];
    $result_6 = $things[15];
    $date = $things[16];
    $time = date("H:i:s");

    //    $image = $_FILES[$photo]['name'];

    $TestTypeData = SelectParticularRow('macho_test_type', 'id', $test_id);
    
    //sukumar
    $sql5 = "SELECT test_category FROM macho_bill_items where bill_id = $entry_id and item_id=$test_id";
    $result5 = mysqli_query($GLOBALS['conn'], $sql5) or die(mysqli_error($GLOBALS['conn']));
    $data5 = mysqli_fetch_assoc($result5);

    $sql6 = "SELECT sub_head FROM  macho_test_type where id = $test_id";
    $result6 = mysqli_query($GLOBALS['conn'], $sql6) or die(mysqli_error($GLOBALS['conn']));
    $data6 = mysqli_fetch_assoc($result6);
    
    $sql7 = "SELECT test_status FROM  patient_entry where id = $entry_id";
    $result7 = mysqli_query($GLOBALS['conn'], $sql7) or die(mysqli_error($GLOBALS['conn']));
    $data7 = mysqli_fetch_assoc($result7);
    //'test_category' => Filter($TestTypeData['test_category']),
    //sukumar
    if($data7['test_status'] == 2){
       echo "edit";
    }else{

    $test_entry_sql = Insert('test_entry', array(
        'entry_id' => Filter($entry_id),
        'test_id' => Filter($test_id),
        'dept_id' => Filter($TestTypeData['dept_id']),
        'sub_heading' => $data6['sub_head'],
        'test_category' => $data5['test_category'],
        'test_result' => Filter($test_result),
        'sub_head' => Filter($sub_head),
        'paragraph' => Filter($paragraph),
        'head_1' => Filter($head_1),
        'head_2' => Filter($head_2),
        'head_3' => Filter($head_3),
        'head_4' => Filter($head_4),
        'head_5' => Filter($head_5),
        'head_6' => Filter($head_6),
        'result_1' => Filter($result_1),
        'result_2' => Filter($result_2),
        'result_3' => Filter($result_3),
        'result_4' => Filter($result_4),
        'result_5' => Filter($result_5),
        'result_6' => Filter($result_6),
        'date' => to_sql_date($date),
        'time' => $time,
        'created' => $created,
        'created_by' => $user_id
    )
    );
}
}

$update = Update('patient_entry', 'id', $entry_id, array(
    'test_status' => $status,
    'modified' => $date_time
)
);
if($data7['test_status'] == 1){
echo EncodeVariable($entry_id);
}