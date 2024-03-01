<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user = $_SESSION["user"];

$product_data = $_REQUEST['product_data'];
$product_data = stripslashes($product_data);
$product_data_array = array();
$product_data_array = json_decode($product_data);
$created = date("Y-m-d h:i:sa");
$modified = date("Y-m-d h:i:sa");
$created_date = date("Y-m-d");
$qty = 1;

for ($i = 0; $i < count($product_data_array); $i++) {
    $item_id = $product_data_array[$i]->item_id;
    $item_name = $product_data_array[$i]->item_name;
    $item_size = $product_data_array[$i]->item_size;
    $item_color = $product_data_array[$i]->item_color;
    $item_quantity = $product_data_array[$i]->item_quantity;
    $item_uom = $product_data_array[$i]->item_uom;
    $item_purchase_rate = $product_data_array[$i]->item_purchase_rate;
    $item_sales_rate = $product_data_array[$i]->item_sales_rate;
    $item_gst = $product_data_array[$i]->item_gst;
    $item_discount = $product_data_array[$i]->item_discount;
    $item_barcode_type = $product_data_array[$i]->item_barcode_type;

    $ProductData = SelectParticularRow('macho_master_products', 'id', $item_id);
    $PurchaseTaxData = GetPurchaseAmount($item_purchase_rate, $item_gst);
    $SalesTaxData = GetSalesAmount($item_sales_rate, $item_gst);
    if ($item_barcode_type == 'Multi') {
        $product_qty = $qty;
    } else {
        $product_qty = $item_quantity;
    }

    $insert_products = Insert('macho_products', array(
        'product_code' => GetProductCode(),
        'product_name' => Filter($item_name),
        'product_category' => Filter($ProductData['product_category']),
        'product_type' => Filter($ProductData['product_type']),
        'product_brand' => Filter($ProductData['product_brand']),
        'product_size' => Filter($item_size),
        'product_color' => Filter($item_color),
        'tax_account' => Filter($ProductData['tax_account']),
        'purchase_rate' => Filter($item_purchase_rate),
        'purchase_tax_percentage' => Filter($item_gst),
        'purchase_tax_amount' => Filter($PurchaseTaxData['purchase_tax_amount']),
        'purchase_net_amount' => Filter($PurchaseTaxData['purchase_net_amount']),
        'sales_rate' => Filter($item_sales_rate),
        'sales_tax_percentage' => Filter($item_gst),
        'sales_tax_amount' => Filter($SalesTaxData['sales_tax_amount']),
        'sales_net_amount' => Filter($SalesTaxData['sales_net_amount']),
        'product_discount' => Filter($item_discount),
        'product_qty' => Filter($product_qty),
        'product_uom' => Filter($item_uom),
        'product_location' => Filter($ProductData['product_location']),
        'barcode_type' => Filter($item_barcode_type),
        'product_created' => $created,
        'product_modified' => $modified
    ));
    $product_id = $insert_products;

    if (is_int($insert_products)) {
        Insert('macho_product_update_entry', array(
            'product_id' => Filter($product_id),
            'product_qty' => Filter($product_qty),
            'created_by' => Filter($user_id),
            'created' => $created_date
        ));
        if ($item_barcode_type == 'Multi') {
            $product_qty = $item_quantity - 1;

            for ($j = 1; $j <= $product_qty; $j++) {
                $insert_products2 = Insert('macho_products', array(
                    'parent_id' => $product_id,
                    'product_code' => GetProductCode(),
                    'product_name' => Filter($item_name),
                    'product_category' => Filter($ProductData['product_category']),
                    'product_type' => Filter($ProductData['product_type']),
                    'product_brand' => Filter($ProductData['product_brand']),
                    'product_size' => Filter($item_size),
                    'product_color' => Filter($item_color),
                    'tax_account' => Filter($ProductData['tax_account']),
                    'purchase_rate' => Filter($item_purchase_rate),
                    'purchase_tax_percentage' => Filter($item_gst),
                    'purchase_tax_amount' => Filter($PurchaseTaxData['purchase_tax_amount']),
                    'purchase_net_amount' => Filter($PurchaseTaxData['purchase_net_amount']),
                    'sales_rate' => Filter($item_sales_rate),
                    'sales_tax_percentage' => Filter($item_gst),
                    'sales_tax_amount' => Filter($SalesTaxData['sales_tax_amount']),
                    'sales_net_amount' => Filter($SalesTaxData['sales_net_amount']),
                    'product_discount' => Filter($item_discount),
                    'product_qty' => Filter($qty),
                    'product_uom' => Filter($item_uom),
                    'product_location' => Filter($ProductData['product_location']),
                    'barcode_type' => Filter($item_barcode_type),
                    'product_created' => $created,
                    'product_modified' => $modified
                ));

                if (is_int($insert_products2)) {
                    $product_id2 = $insert_products2;
                    Insert('macho_product_update_entry', array(
                        'product_id' => Filter($product_id2),
                        'product_qty' => Filter($qty),
                        'created_by' => Filter($user_id),
                        'created' => $created_date
                    ));
                }
            }
        }
    }
}

$notes = 'Product details added by ' . $user;
$receive_id = '1';
$receive_role_id = GetRoleOfUser($receive_id);
InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

echo '1';