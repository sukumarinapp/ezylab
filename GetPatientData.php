<?php
include_once 'booster/bridge.php';
IsAjaxRequest();

$response = array();

$result_data = array();

$P_code = $_POST['P_code'];


$patientQuery = "SELECT * FROM macho_patient WHERE id ='" . $P_code . "'";
$patientResult = mysqli_query($GLOBALS['conn'], $patientQuery) or die(mysqli_error($GLOBALS['conn']));
$patientCounts = mysqli_num_rows($patientResult);
$patientData = mysqli_fetch_assoc($patientResult);
if ($patientCounts > 0) {
    $response['patientID'] = $patientData['id'];
    $response['P_code'] = $patientData['P_code'];
    $response['P_name'] = $patientData['P_name'];
    $response['address'] = $patientData['address'];
    $response['mobile'] = $patientData['mobile'];
    $response['email'] = $patientData['email'];
    array_push($result_data, $response);
    echo(json_encode($result_data));
    exit;
} else {
    echo '0';
}