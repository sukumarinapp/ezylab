<?php
include_once 'booster/bridge.php';
$id = $_POST['id'];
$values = array();
$response = array();

$ProductData = SelectParticularRow('macho_master_products', 'id', $id);

$values['product_name'] = $ProductData['product_name'];
$values['product_code'] = $ProductData['product_code'];
$values['product_lang_name'] = $ProductData['product_lang_name'];

array_push($response, $values);
echo(json_encode($response));
exit;