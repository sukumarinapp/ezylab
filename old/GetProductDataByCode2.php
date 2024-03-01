<?php

include_once 'booster/bridge.php';

$response = array();

$result_data = array();

$product_code = strtoupper($_POST['product_code']);

$sql = "SELECT * FROM macho_products WHERE id ='" . $product_code . "'";
$result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
$ProductsCounts = mysqli_num_rows($result);
$ProductsData = mysqli_fetch_assoc($result);
if ($ProductsCounts > 0) {

    $item_qty = $ProductsData['quantity'] * $ProductsData['pack_capacity'];
    $product_price = $ProductsData['rate'] / $item_qty;

    $response['product_id'] = $ProductsData['id'];
    $response['product_name'] = $ProductsData['product_name'];
    $response['product_code'] = $ProductsData['product_code'];
    $response['product_price'] = $product_price;
    $response['product_qty'] = $ProductsData['item_qty'];
    $response['product_stock'] = ProductStock($ProductsData['parent_id']);
    $response['product_uom'] = $ProductsData['pack_unit'];
    $response['product_gst'] = $ProductsData['gst'];
    array_push($result_data, $response);
    echo(json_encode($result_data));
    exit;
} else {
    echo '0';
}


