<?php
include_once 'booster/bridge.php';
IsAjaxRequest();

$response = array();

$result_data = array();

$test_ID = $_POST['test_ID'];

if (strpos($test_ID, 'test_id_') === false) {
    $CategoryID = str_replace("CategoryID_", "", $test_ID);

    $amount = 0;
    $TestTypeQuery = "SELECT price FROM macho_test_type WHERE test_category='$CategoryID'";
    $TestTypeResult = GetAllRows($TestTypeQuery);
    foreach ($TestTypeResult as $TestTypeData) {
        $amount = $amount + $TestTypeData['price'];
    }

    $TestTypeQuery = "SELECT * FROM macho_test_category WHERE id ='" . $CategoryID . "'";
    $TestTypeResult = mysqli_query($GLOBALS['conn'], $TestTypeQuery) or die(mysqli_error($GLOBALS['conn']));
    $TestTypeCounts = mysqli_num_rows($TestTypeResult);
    $TestTypeData = mysqli_fetch_assoc($TestTypeResult);
    if ($TestTypeCounts > 0) {
        $type = $TestTypeData['type'];
        if ($type == 'group') {
            $amount = $TestTypeData['amount'];
        }
        $response['item_type'] = $type;
        $response['test_ID'] = $TestTypeData['id'];
        $response['test_name'] = $TestTypeData['category_name'];
        $response['price'] = $amount;
        $response['test_category'] = $TestTypeData['id'];
        array_push($result_data, $response);
        echo (json_encode($result_data));
        exit;
    } else {
        echo '0';
    }

} else {
    $test_ID = str_replace("test_id_", "", $test_ID);
    $TestTypeQuery = "SELECT * FROM macho_test_type WHERE id ='" . $test_ID . "'";
    $TestTypeResult = mysqli_query($GLOBALS['conn'], $TestTypeQuery) or die(mysqli_error($GLOBALS['conn']));
    $TestTypeCounts = mysqli_num_rows($TestTypeResult);
    $TestTypeData = mysqli_fetch_assoc($TestTypeResult);
    if ($TestTypeCounts > 0) {
        $response['item_type'] = 'test';
        $response['test_ID'] = $TestTypeData['id'];
        $response['test_code'] = $TestTypeData['test_code'];
        $response['test_name'] = $TestTypeData['test_name'];
        $response['price'] = $TestTypeData['price'];
        $response['lower_limit'] = $TestTypeData['lower_limit'];
        $response['upper_limit'] = $TestTypeData['upper_limit'];
        $response['remarks'] = $TestTypeData['remarks'];
        $response['method'] = $TestTypeData['method'];
        $response['test_category'] = $TestTypeData['test_category'];
        $response['interpretation'] = $TestTypeData['interpretation'];
        $response['type_test'] = $TestTypeData['type_test'];
        $response['units'] = $TestTypeData['units'];
        array_push($result_data, $response);
        echo (json_encode($result_data));
        exit;
    } else {
        echo '0';
    }
}