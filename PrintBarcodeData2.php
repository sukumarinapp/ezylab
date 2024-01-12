<?php
include_once 'booster/bridge.php';
$product_data = $_REQUEST['pID'];
$product_data = stripslashes($product_data);
$product_data_array = array();
$product_data_array = json_decode($product_data);

$things = array();
$values = array();
$response = array();

for ($i = 0; $i < count($product_data_array); $i++) {
    $things = explode(",", $product_data_array[$i]);
    $product_id = $things[0];
    $ProductsData = SelectParticularRow('macho_master_products', 'id', $product_id);
    $values[$i]['product_name'] = $ProductsData['product_name'];
    $values[$i]['product_code'] = $ProductsData['product_code'];
    $values[$i]['product_lang_name'] = $ProductsData['product_lang_name'];
}
array_push($response, $values);
echo(json_encode($response));
exit;