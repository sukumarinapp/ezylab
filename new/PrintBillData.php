<?php
include_once 'booster/bridge.php';

$bill_id = isset($_POST['id']) ? $_POST['id'] : 0;
$values = array();
$response = array();
$i = 1;

$BillData = SelectParticularRow('patient_entry', 'id', $bill_id);
$patient_id = $BillData['patient_id'];

$PatientInfo = SelectParticularRow('macho_patient', 'id', $patient_id);

$values[0]['patient'] = $PatientInfo['prefix'] . $PatientInfo['P_name'] . '-' . $PatientInfo['P_code'];
$values[0]['billnum'] = $BillData['bill_no'];
$values[0]['bill_date'] = $BillData['entry_date'];
$values[0]['total'] = $BillData['total_amount'];
$values[0]['home_visit'] = $BillData['home_visit'];
$values[0]['net_amount'] = $BillData['net_amount'];

$BillingQuery = "SELECT * FROM macho_bill_items WHERE bill_id='$bill_id' AND item_type='test' ORDER BY id DESC";
$BillingResult = mysqli_query($GLOBALS['conn'], $BillingQuery) or die(mysqli_error($GLOBALS['conn']));
while ($BillingData = mysqli_fetch_assoc($BillingResult)) {
    $values[$i]['name'] = $BillingData['item_name'];
    $values[$i]['unit_price'] = $BillingData['unit_price'];
    $values[$i]['qty'] = $BillingData['quantity'] . " " . $BillingData['uom'];
    $values[$i]['amount'] = $BillingData['amount'];
    $i++;
}

$BillingQuery1 = "SELECT DISTINCT test_category FROM macho_bill_items WHERE bill_id='$bill_id' AND item_type='single' ORDER BY id DESC";
$BillingResult1 = mysqli_query($GLOBALS['conn'], $BillingQuery1) or die(mysqli_error($GLOBALS['conn']));
while ($BillingData1 = mysqli_fetch_assoc($BillingResult1)) {

    $CategoryID = $BillingData1['test_category'];

    $amount = 0;
    $TestTypeQuery = "SELECT price FROM macho_test_type WHERE test_category='$CategoryID'";
    $TestTypeResult = GetAllRows($TestTypeQuery);
    foreach ($TestTypeResult as $TestTypeData) {
        $amount = $amount + $TestTypeData['price'];
    }

    $values[$i]['name'] = TestCategoryName($CategoryID);
    $values[$i]['unit_price'] = $amount;
    $values[$i]['qty'] = '1 LS';
    $values[$i]['amount'] = $amount;
    $i++;
}

$BillingQuery2 = "SELECT DISTINCT test_category FROM macho_bill_items WHERE bill_id='$bill_id' AND item_type='group' ORDER BY id DESC";
$BillingResult2 = mysqli_query($GLOBALS['conn'], $BillingQuery2) or die(mysqli_error($GLOBALS['conn']));
while ($BillingData2 = mysqli_fetch_assoc($BillingResult2)) {
    $CategoryID = $BillingData2['test_category'];

    $TestCategoryData = SelectParticularRow('macho_test_category', 'id', $CategoryID);

    $values[$i]['name'] = $TestCategoryData['category_name'];
    $values[$i]['unit_price'] = $TestCategoryData['amount'];
    $values[$i]['qty'] = '1 LS';
    $values[$i]['amount'] = $TestCategoryData['amount'];
    $i++;
}

array_push($response, $values);
echo (json_encode($response));
exit;