<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);
?>
<!-- Main section-->
<section class="section-container no-print">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div>Product Type Report
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Product Type Report','0','0');"><i class="fa fa-print"></i> Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Product Type Report','0','0');"><i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Product Type Report','0','0');"><i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="datatable2">
                        <thead>
                        <tr>
                            <th width="20px" class="thead_data">#</th>
                            <th class="thead_data">Product Type</th>
                            <th class="thead_data">No. of Category</th>
                            <th class="thead_data">No. of Products</th>
                            <th class="no-print">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $total_product_category_count = 0;
                        $total_product_count = 0;
                        $ProductCategoryQuery = "SELECT * FROM macho_product_type WHERE status='1' ORDER BY id ";
                        $ProductCategoryResult = GetAllRows($ProductCategoryQuery);
                        $ProductCategoryCounts = count($ProductCategoryResult);
                        if ($ProductCategoryCounts > 0) {
                            foreach ($ProductCategoryResult as $ProductCategoryData) {
                                $ID = $ProductCategoryData['id'];
                                $product_category_count = GetEntryCounts("SELECT * FROM macho_product_category WHERE product_type='$ID' AND status='1' ");

                                $product_count = 0;
                                $sql4 = "SELECT SUM(product_qty) as product_count FROM macho_products WHERE product_type='$ID'";
                                $result4 = mysqli_query($GLOBALS['conn'], $sql4) or die(mysqli_error($GLOBALS['conn']));
                                $data4 = mysqli_fetch_assoc($result4);
                                $product_count = $product_count + $data4['product_count'];

                                $total_product_category_count = $total_product_category_count + $product_category_count;
                                $total_product_count = $total_product_count + $product_count;
                                ?>
                                <tr>
                                    <td class="tbody_data"><?php echo ++$no; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $ProductCategoryData['product_type_name']; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $product_category_count.' Nos.'; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $product_count.' Nos.'; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($PageAccessible['is_read'] == 1) { ?>
                                                <button class="btn dropdown-toggle btn-success" type="button"
                                                        data-toggle="dropdown"><em class="fa fa-search"></em> View
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="#" title="Product Category List" onclick="window.open('ProductCategoryReport?ptID=<?php echo EncodeVariable($ID); ?>');">Product Category</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" title="Products List" onclick="window.open('ProductReport?ptID=<?php echo EncodeVariable($ID); ?>');">Products</a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                        <tbody>
                        <tr style="font-weight: bold">
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;Total</td>
                            <td class="tfoot_data">&nbsp;<?= $total_product_category_count.' Nos.'; ?></td>
                            <td class="tfoot_data">&nbsp;<?= $total_product_count.' Nos.'; ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php' ?>
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

<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<!-- Datatables-->
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