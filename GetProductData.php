<?php
include_once 'booster/bridge.php';

$response = array();

$result = array();

$product_id = $_POST['product_id'];

$ProductsData = SelectParticularRow('macho_products', 'id', $product_id);
$Product_tax_account = $ProductsData['tax_account'];

$ProductTaxData = SelectParticularRow('macho_tax_accounts', 'id', $Product_tax_account);

$response['product_parent_id'] = $ProductsData['id'];
$response['product_id'] = $ProductsData['id'];
$response['product_code'] = $ProductsData['product_code'];
$response['product_name'] = $ProductsData['product_name'];
$response['product_category'] = $ProductsData['product_category'];
$response['category_name'] = ProductCategoryName($ProductsData['product_category']);
$response['product_qty'] = $ProductsData['product_qty'];
$response['product_stock_qty'] = ProductStock($product_id);
$response['product_uom'] = $ProductsData['product_uom'];
$response['product_hsn_sac'] = $ProductsData['hsn_sac'];
$response['product_tax_account'] = $ProductsData['tax_account'];
$response['product_tax_name'] = $ProductTaxData['tax_name'];
$response['product_tax_percentage'] = $ProductTaxData['percentage'];
$response['product_purchase_rate'] = $ProductsData['purchase_rate'];
$response['product_purchase_tax_percentage'] = $ProductsData['purchase_tax_percentage'];
$response['product_purchase_tax_amount'] = $ProductsData['purchase_tax_amount'];
$response['product_purchase_net_amount'] = $ProductsData['purchase_net_amount'];
$response['product_sales_rate'] = $ProductsData['sales_rate'];
$response['product_sales_tax_percentage'] = $ProductsData['sales_tax_percentage'];
$response['product_sales_tax_amount'] = $ProductsData['sales_tax_amount'];
$response['product_sales_net_amount'] = $ProductsData['sales_net_amount'];
$response['product_mrp'] = $ProductsData['product_mrp'];
$response['product_discount'] = $ProductsData['product_discount'];
$response['product_location'] = $ProductsData['product_location'];
$response['product_barcode_type'] = $ProductsData['barcode_type'];
$response['product_created'] = $ProductsData['product_created'];
$response['product_modified'] = $ProductsData['product_modified'];
array_push($result, $response);
echo(json_encode($result));
exit;