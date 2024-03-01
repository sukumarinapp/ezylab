<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include "header.php";
include_once "Menu.php";
include 'booster/vendor/autoload.php';
$created = date("Y-m-d h:i:sa");
$modified = date("Y-m-d h:i:sa");

if (isset($_POST['import_submit'])) {

    $File = $_FILES['import_file']['name'];
    if (trim($File) != "") {
        $extention = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);

        $inputFileName = __DIR__ . "/excel/" . '1.' . $extention;
        if (file_exists($inputFileName)) {
            unlink($inputFileName);
        }


        if (move_uploaded_file($_FILES['import_file']['tmp_name'], $inputFileName)) {

            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }

            //  Get worksheet dimensions
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            //  Loop through each row of the worksheet in turn

            $count = 0;
            for ($row = 5; $row <= $highestRow; $row++) {
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL,
                    TRUE,
                    FALSE);

                //  Insert row data array into your database of choice here
                for ($row = 6; $row <= $highestRow; $row++) {
                    $product_name = trim($objPHPExcel->getActiveSheet()->getCell('B' . $row)->getValue());
                    $product_code = trim($objPHPExcel->getActiveSheet()->getCell('C' . $row)->getValue());
                    $category_name = trim($objPHPExcel->getActiveSheet()->getCell('D' . $row)->getValue());
                    $company = trim($objPHPExcel->getActiveSheet()->getCell('E' . $row)->getValue());
                    $hsn_sac = trim($objPHPExcel->getActiveSheet()->getCell('F' . $row)->getValue());
                    $percentage = trim($objPHPExcel->getActiveSheet()->getCell('G' . $row)->getValue());
                    $item_quantity = trim($objPHPExcel->getActiveSheet()->getCell('H' . $row)->getValue());
                    $uom = trim($objPHPExcel->getActiveSheet()->getCell('I' . $row)->getValue());
                    $purchase_rate = trim($objPHPExcel->getActiveSheet()->getCell('J' . $row)->getValue());
                    $purchase_net_amount = trim($objPHPExcel->getActiveSheet()->getCell('K' . $row)->getValue());
                    $sales_rate = trim($objPHPExcel->getActiveSheet()->getCell('L' . $row)->getValue());
                    $sales_net_amount = trim($objPHPExcel->getActiveSheet()->getCell('M' . $row)->getValue());
                    $product_mrp = trim($objPHPExcel->getActiveSheet()->getCell('N' . $row)->getValue());
                    $mfg_date = trim($objPHPExcel->getActiveSheet()->getCell('O' . $row)->getValue());
                    $exp_date = trim($objPHPExcel->getActiveSheet()->getCell('P' . $row)->getValue());
                    $product_description = trim($objPHPExcel->getActiveSheet()->getCell('Q' . $row)->getValue());
                    $storage = trim($objPHPExcel->getActiveSheet()->getCell('R' . $row)->getValue());
                    $item_barcode_type = trim($objPHPExcel->getActiveSheet()->getCell('S' . $row)->getValue());

                    $mfg_date = PHPExcel_Style_NumberFormat::toFormattedString($mfg_date, PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                    $exp_date = PHPExcel_Style_NumberFormat::toFormattedString($exp_date, PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);

                    $mfg_year = date("Y", strtotime($mfg_date));
                    if ($mfg_year == '1900') {
                        $mfg_date = '0000-00-00';
                    }
                    $exp_year = date("Y", strtotime($exp_date));
                    if ($exp_year == '1900') {
                        $exp_date = '0000-00-00';
                    }

                    $sql3 = "SELECT id FROM macho_product_suppliers WHERE supplier_name='$company' ";
                    $result3 = mysqli_query($GLOBALS['conn'], $sql3);
                    $count3 = mysqli_num_rows($result3);
                    $data3 = mysqli_fetch_assoc($result3);
                    if ($count3 > 0) {
                        $company_id = $data3['id'];
                    } else {
                        $supplierData = Insert('macho_product_suppliers', array(
                            'supplier_no' => GetSupplierNo(),
                            'supplier_name' => Filter($company),
                            'created' => $created,
                            'modified' => $modified
                        ));
                        $company_id = $supplierData;
                    }

                    $sql = "SELECT id FROM macho_product_category WHERE category_name='$category_name' ";
                    $result = mysqli_query($GLOBALS['conn'], $sql);
                    $count = mysqli_num_rows($result);
                    $data = mysqli_fetch_assoc($result);
                    if ($count > 0) {
                        $category_id = $data['id'];
                        Update('macho_product_category', 'id', $category_id, array(
                            'supplier_id' => Filter($company_id),
                            'modified' => $modified
                        ));

                    } else {
                        $categoryData = Insert('macho_product_category', array(
                            'category_name' => Filter($category_name),
                            'supplier_id' => Filter($company_id),
                            'created' => $created,
                            'modified' => $modified
                        ));
                        $category_id = $categoryData;
                    }

                    $sql2 = "SELECT id FROM macho_tax_accounts WHERE percentage='$percentage' ";
                    $result2 = mysqli_query($GLOBALS['conn'], $sql2);
                    $count2 = mysqli_num_rows($result2);
                    $data2 = mysqli_fetch_assoc($result2);
                    if ($count2 > 0) {
                        $tax_account = $data2['id'];
                    } else {
                        $taxData = Insert('macho_tax_accounts', array(
                            'tax_name' => Filter('GST ' . $percentage . '%'),
                            'percentage' => Filter($percentage),
                            'created' => $created,
                            'modified' => $modified
                        ));
                        $tax_account = $taxData;
                    }

                    $purchase_net_amount = (($purchase_rate * 100) / (100 + +$percentage));
                    $purchase_tax_amount = $purchase_rate - $purchase_net_amount;

                    $sales_net_amount = (($sales_rate * 100) / (100 + +$percentage));
                    $sales_tax_amount = $sales_rate - $sales_net_amount;

                    $unit_rate = $sales_rate / $product_qty;

                    if ($status == 'ACTIVE') {
                        $product_status = 1;
                    } else {
                        $product_status = 0;
                    }

                    if ($product_code == '') {
                        $product_code = GetProductCode();
                    }

                    if ($uom == '') {
                        $uom = 'Pcs';
                    }

                    if (ProductCodeExists($product_code)) {

                        $update = Update('macho_products', 'product_code', "'" . $product_code . "'", array(
                            'product_category' => Filter($category_id),
                            'product_name' => Filter($product_name),
                            'product_uom' => Filter($uom),
                            'hsn_sac' => Filter($hsn_sac),
                            'tax_account' => Filter($tax_account),
                            'purchase_rate' => Filter($purchase_rate),
                            'purchase_tax_percentage' => Filter($percentage),
                            'purchase_tax_amount' => Filter($purchase_tax_amount),
                            'purchase_net_amount' => Filter($purchase_net_amount),
                            'sales_rate' => Filter($sales_rate),
                            'sales_tax_percentage' => Filter($percentage),
                            'sales_tax_amount' => Filter($sales_tax_amount),
                            'sales_net_amount' => Filter($sales_net_amount),
                            'product_mrp' => Filter($product_mrp),
                            'product_description' => Filter($product_description),
                            'status' => Filter($product_status),
                            'product_location' => Filter($storage),
                            'mfg_date' => to_sql_date($mfg_date),
                            'exp_date' => to_sql_date($exp_date),
                            'product_modified' => $modified
                        ));

                    } else {
                        $qty = 1;

                        if ($item_barcode_type == 'Multi') {
                            $product_qty = $qty;
                        } else {
                            $product_qty = $item_quantity;
                        }

                        $insert_products = Insert('macho_products', array(
                            'product_code' => GetProductCode(),
                            'product_name' => Filter($product_name),
                            'product_category' => Filter($category_id),
                            'hsn_sac' => Filter($hsn_sac),
                            'tax_account' => Filter($tax_account),
                            'purchase_rate' => Filter($purchase_rate),
                            'purchase_tax_percentage' => Filter($percentage),
                            'purchase_tax_amount' => Filter($purchase_tax_amount),
                            'purchase_net_amount' => Filter($purchase_net_amount),
                            'sales_rate' => Filter($sales_rate),
                            'sales_tax_percentage' => Filter($percentage),
                            'sales_tax_amount' => Filter($sales_tax_amount),
                            'sales_net_amount' => Filter($sales_net_amount),
                            'product_mrp' => Filter($product_mrp),
                            'product_description' => Filter($product_description),
                            'product_qty' => Filter($product_qty),
                            'product_uom' => Filter($uom),
                            'product_location' => Filter($storage),
                            'mfg_date' => to_sql_date($mfg_date),
                            'exp_date' => to_sql_date($exp_date),
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
                                        'product_name' => Filter($product_name),
                                        'product_category' => Filter($category_id),
                                        'hsn_sac' => Filter($hsn_sac),
                                        'tax_account' => Filter($tax_account),
                                        'purchase_rate' => Filter($purchase_rate),
                                        'purchase_tax_percentage' => Filter($percentage),
                                        'purchase_tax_amount' => Filter($purchase_tax_amount),
                                        'purchase_net_amount' => Filter($purchase_net_amount),
                                        'sales_rate' => Filter($sales_rate),
                                        'sales_tax_percentage' => Filter($percentage),
                                        'sales_tax_amount' => Filter($sales_tax_amount),
                                        'sales_net_amount' => Filter($sales_net_amount),
                                        'product_mrp' => Filter($product_mrp),
                                        'product_description' => Filter($product_description),
                                        'product_qty' => Filter($qty),
                                        'product_uom' => Filter($uom),
                                        'product_location' => Filter($storage),
                                        'mfg_date' => to_sql_date($mfg_date),
                                        'exp_date' => to_sql_date($exp_date),
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

                                        $notes = $product_name . ' Product details added by ' . $user;
                                        $receive_id = '1';
                                        $receive_role_id = GetRoleOfUser($receive_id);
                                        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $ProductQuery = "SELECT id,product_qty FROM macho_products ORDER BY id";
            $ProductResult = GetAllRows($ProductQuery);
            $ProductCounts = count($ProductResult);
            if ($ProductCounts > 0) {
                foreach ($ProductResult as $ProductData) {
                    $product_id = $ProductData['id'];
                    $product_qty = $ProductData['product_qty'];
                    if ($product_qty == 0) {
                        $sql2 = "UPDATE macho_products SET status='0' WHERE id=$product_id";
                        $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
                    } else {
                        $sql2 = "UPDATE macho_products SET status='1' WHERE id=$product_id";
                        $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
                    }
                }
            }

            echo '<span id="import_success"></span>';
        } else {
            echo '<span  id="import_failure"></span>';
        }
    }
}
?>

<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <br><br><br>
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <div class="card card-default">
                    <div class="card-header d-flex align-items-center">
                        <div class="d-flex justify-content-center col">
                            <div class="h4 m-0 text-center">Import Products</div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row py-4 justify-content-center">
                            <div class="col-10 col-sm-10">
                                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <div class="col-xl-10 col-md-9 col-8">
                                            <p class="m-0 text-center">Required Excel Document for import a File.... <a
                                                    class="ml-2"
                                                    href="ProductsExcel">Export
                                                    Products</a>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xl-10 col-md-9 col-8">
                                            <label class="text-bold col-form-label text-right"
                                                   for="inputContact8">Import File</label>
                                            <input class="form-control filestyle" type="file" accept=".xlsx,.xls"
                                                   id="import_file"
                                                   name="import_file" data-input="false"
                                                   data-classbutton="btn btn-secondary"
                                                   data-classinput="form-control inline"
                                                   data-text="Upload new picture"
                                                   data-icon="&lt;span class='fa fa-upload mr'&gt;&lt;/span&gt;">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xl-10 col-md-9 col-8">
                                            <div class="text-right">
                                                <button type="button" class="btn btn-default"
                                                        onclick="location.href = 'Products';">Cancel
                                                </button>
                                                <button class="btn btn-info" type="submit" name="import_submit"
                                                        tabindex="3">
                                                    Import
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once "footer.php"; ?>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
<!-- BOOTSTRAP-->
<script src="<?php echo VENDOR; ?>popper.js/dist/umd/popper.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap/dist/js/bootstrap.js"></script>
<!-- STORAGE API-->
<script src="<?php echo VENDOR; ?>js-storage/js.storage.js"></script>
<!-- JQUERY EASING-->
<script src="<?php echo VENDOR; ?>jquery.easing/jquery.easing.js"></script>
<!-- ANIMO-->
<script src="<?php echo VENDOR; ?>animo/animo.js"></script>
<!-- SCREENFULL-->
<script src="<?php echo VENDOR; ?>screenfull/dist/screenfull.js"></script>
<!-- LOCALIZE-->
<script src="<?php echo VENDOR; ?>jquery-localize/dist/jquery.localize.js"></script>
<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
<script>
    window.onload = function () {

        if (document.getElementById('import_success')) {
            swal("Success!", "Product Details Imported Successfully!", "success");
            location.href = "Products";
        }

        if (document.getElementById('import_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

    }
</script>

</body>
</html>