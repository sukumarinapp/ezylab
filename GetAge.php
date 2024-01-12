<?php
include_once 'booster/bridge.php';
IsAjaxRequest();

$birthDate = $_POST['birth_date'];
$response = array();
$result_data = array();

$birthData = GetAge($birthDate);

$response['age'] = $birthData['age'];
$response['age_type'] = $birthData['age_type'];
array_push($result_data, $response);
echo(json_encode($result_data));
exit;
