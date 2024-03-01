<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);

$product_name = '';
$product_qty = 0;
$sales_rate = 0;

if (isset($_POST['add_submit'])) {
    $product_name = Filter($_POST['product_name']);
    //$product_qty = Filter($_POST['product_qty']);
    $sales_rate = Filter($_POST['sales_rate']);
}
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div>Products Report</div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Products Report','0','0');"><i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Products Report','0','0');"><i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Products Report','0','0');"><i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <!-- START card-->
                <div class="card card-default">
                    <div class="card-header">
                        <div class="text-sm"></div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="product_id" class="control-label">Product Name</label>
                                        <select class="form-control select2"
                                                name="product_name"
                                                id="product_name" tabindex="1">
                                            <option value=" ">All Products</option>
                                            <?php
                                            $ProductsQuery4 = 'SELECT DISTINCT product_name FROM macho_products ORDER BY product_name';
                                            $ProductsResult4 = GetAllRows($ProductsQuery4);
                                            foreach ($ProductsResult4 as $ProductsData4) {
                                                echo "<option ";
                                                if ($product_name == $ProductsData4['product_name']) echo " selected ";
                                                echo "value='" . $ProductsData4['product_name'] . "'>" . $ProductsData4['product_name'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
<!--                                        <div class="col-md-3">-->
<!--                                            <div class="form-group">-->
<!--                                                <label for="quantity"-->
<!--                                                       class="control-label">Quantity</label>-->
<!--                                                <select class="form-control"-->
<!--                                                        name="product_qty"-->
<!--                                                        id="product_qty"-->
<!--                                                        tabindex="2">-->
<!--                                                    <option value="0">All Qty</option>-->
<!--                                                    --><?php
//                                                    $ProductsQuery3 = "SELECT DISTINCT product_qty FROM macho_products ORDER BY product_qty ";
//                                                    $ProductsResult3 = GetAllRows($ProductsQuery3);
//                                                    foreach ($ProductsResult3 as $ProductData3) {
//                                                        echo "<option ";
//                                                        if ($product_qty == $ProductData3['product_qty']) echo " selected ";
//                                                        echo "value='" . $ProductData3['product_qty'] . "'> Upto " . $ProductData3['product_qty'] . "</option>";
//                                                    }
//                                                    ?>
<!--                                                </select>-->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="uom"
                                                       class="control-label">Sale Price</label>
                                                <select class="form-control"
                                                        name="sales_rate"
                                                        id="sales_rate"
                                                        tabindex="3">
                                                    <option value="0">All Price</option>
                                                    <?php
                                                    $ProductsQuery2 = "SELECT DISTINCT sales_rate FROM macho_products ORDER BY sales_rate ";
                                                    $ProductsResult2 = GetAllRows($ProductsQuery2);
                                                    foreach ($ProductsResult2 as $ProductData2) {
                                                        echo "<option ";
                                                        if ($sales_rate == $ProductData2['sales_rate']) echo " selected ";
                                                        echo "value='" . $ProductData2['sales_rate'] . "'> Upto " . $ProductData2['sales_rate'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                <div class="col-md-2">




                                            <div class="form-group">
                                                <label for="add"
                                                       class="control-label">&nbsp;&nbsp;&nbsp;</label><br>
                                                <input class="btn btn-info form-control"
                                                       type="submit" name="add_submit" value="Search" tabindex="4"/>
                                            </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-striped my-4 w-100" id="datatable1">
                            <thead>
                            <tr>
                                <th width="20px" class="thead_data">#</th>
                                <th class="thead_data">Name</th>
                                <th class="thead_data">Quantity</th>
                                <th class="thead_data">Unit Price</th>
                                <th class="thead_data">Total Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 0;
                            $total_product_qty = 0;
                            $ProductQuery = "SELECT id,product_name,product_uom,sales_rate FROM macho_products WHERE parent_id='0' ";
                            if ($product_name != '') {
                                $ProductQuery .= "  AND product_name='$product_name'";
                            }
//                            if ($product_qty != 0) {
//                                $ProductQuery .= "  AND product_qty<='$product_qty'";
//                            }
                            if ($sales_rate != 0) {
                                $ProductQuery .= "  AND sales_rate<='$sales_rate'";
                            }
                            $ProductQuery .= " ORDER BY product_name ";
                            $ProductResult = GetAllRows($ProductQuery);
                            $ProductEntryCounts = count($ProductResult);
                            if ($ProductEntryCounts > 0) {
                                foreach ($ProductResult as $ProductData) {
                                    $product_qty = ProductStock($ProductData['id']);
                                    $total_product_qty = $total_product_qty + $product_qty;
                                    ?>
                                    <tr>
                                        <td class="tbody_data"><?= ++$no; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $ProductData['product_name']; ?></td>
                                        <td class="tbody_data">
                                            &nbsp;<?= $product_qty . ' ' . $ProductData['product_uom']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $ProductData['sales_rate']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= ConvertMoneyFormat($product_qty * $ProductData['sales_rate']); ?></td>
                                    </tr>
                                <?php
                                }
                            } ?>
                            </tbody>
                            <tbody>
                            <tr style="font-weight: bold">
                                <td class="tfoot_data">&nbsp;</td>
                                <td class="tfoot_data">&nbsp;Total</td>
                                <td class="tfoot_data">&nbsp;<?= $total_product_qty . ' Nos.'; ?></td>
                                <td class="tfoot_data">&nbsp;</td>
                                <td class="tfoot_data">&nbsp;</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END card-->
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php'; ?>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.min.js"></script>
<script src="<?php echo JS; ?>jquery.redirect.js"></script>
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
<script src="<?php echo VENDOR; ?>select2/dist/js/select2.full.js"></script>
<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<script src="<?php echo VENDOR; ?>bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
<script>

    var thead_data = new Array();
    $(".thead_data").each(function () {
        thead_data.push($(this).html());
    });

    var tbody_data = new Array();
    $(".tbody_data").each(function () {
        tbody_data.push($(this).html());
    });

    var tfoot_data = new Array();
    $(".tfoot_data").each(function () {
        tfoot_data.push($(this).html());
    });

    function print_data(e, title, from_date, todate) {
        e.preventDefault();

        $.redirect("Print.php",
            {
                title: title,
                from_date: from_date,
                todate: todate,
                thead_data: thead_data,
                tbody_data: tbody_data,
                tfoot_data: tfoot_data
            }, "POST", "_blank");
    }

    function pdf_data(e, title, from_date, todate) {
        e.preventDefault();

        $.redirect("PDF.php",
            {
                title: title,
                from_date: from_date,
                todate: todate,
                thead_data: thead_data,
                tbody_data: tbody_data,
                tfoot_data: tfoot_data
            }, "POST", "_blank");
    }

    function excel_data(e, title, from_date, todate) {
        e.preventDefault();

        $.redirect("Excel.php",
            {
                title: title,
                from_date: from_date,
                todate: todate,
                thead_data: thead_data,
                tbody_data: tbody_data,
                tfoot_data: tfoot_data
            }, "POST", "_blank");
    }

</script>
</body>
</html>