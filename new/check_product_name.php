<?php
include_once 'booster/bridge.php';
if (!empty($_POST["product_name"])) {
    $product_name = $_POST["product_name"];
    $product_count = GetEntryCounts("SELECT product_name FROM macho_products where product_name='$product_name'");
    if ($product_count > 0) {
        echo "<span class='status-not-available'> This Name already exists for another Product.</span>";
    }
}
?>