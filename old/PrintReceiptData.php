<?php
include_once 'booster/bridge.php';

$bill_id = isset($_POST['id']) ? $_POST['id'] : 0;
$values = array();
$response = array();
$i = 1;

$BillData = SelectParticularRow('macho_billing', 'id', $bill_id);
$patient_id = $BillData['patient_id'];

$PatientInfo = SelectParticularRow('macho_patient', 'id', $patient_id);

$values[0]['patient'] = $PatientInfo['prefix'] . $PatientInfo['P_name'] . '-' . $PatientInfo['P_code'];
$values[0]['billnum'] = $BillData['billnum'];
$values[0]['bill_date'] = $BillData['bill_date'];
$values[0]['net_amount'] = $BillData['net_amount'];


$values[0]['bill_description'] = $BillData['bill_description'];
$values[0]['amount'] = $BillData['net_amount'];


array_push($response, $values);
echo (json_encode($response));
exit;