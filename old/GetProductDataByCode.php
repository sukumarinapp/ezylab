<?php

include_once 'booster/bridge.php';

$response = array();

$result_data = array();

$product_code = strtoupper($_POST['product_code']);

$sql = "SELECT * FROM macho_master_products WHERE product_code ='" . $product_code . "'";
$result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
$ProductsCounts = mysqli_num_rows($result);
$ProductsData = mysqli_fetch_assoc($result);
if ($ProductsCounts > 0) {


    $response['product_id'] = $ProductsData['id'];
    $response['product_name'] = $ProductsData['product_name'];
    $response['product_lang_name'] = $ProductsData['product_lang_name'];
    $response['product_stock'] = ProductStock($ProductsData['id']);
    $response['product_uom'] = $ProductsData['uom'];
    array_push($result_data, $response);
    echo(json_encode($result_data));
    exit;
} else {
    echo '0';
}