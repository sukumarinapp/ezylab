<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);
$start_date = date("01-m-Y");
$end_date = date("d-m-Y");

if (isset($_POST['add_submit'])) {
    $start_date = date("d-m-Y", strtotime($_POST['startdate']));
    $end_date = date("d-m-Y", strtotime($_POST['enddate']));
}
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div>Product Stock Entry Report
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Product Stock Entry Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Product Stock Entry Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Product Stock Entry Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <?php if ($PageAccessible['is_write'] == 1) { ?>
                    <div class="card-title pull-right">
                        <form action="" method="post" class="search-form">
                            <div class="btn-toolbar">
                                <div class="form-group">
                                    <input type="text" name="startdate" id="startdate"
                                           class="form-control" data-date-format="dd-mm-yyyy"
                                           value="<?php echo $start_date; ?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="enddate" id="enddate"
                                           class="form-control" data-date-format="dd-mm-yyyy"
                                           value="<?php echo $end_date; ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="add_submit" class="btn btn-success" title="Search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } ?>
                <div class="text-sm"></div>
            </div>
            <div class="card-body">
                <table class="table table-striped my-4 w-100" id="datatable1">
                    <thead>
                    <tr>
                        <th width="10px" class="thead_data">#</th>
                        <th class="thead_data">Date</th>
                        <th class="thead_data">Description</th>
                        <th class="thead_data">Product Name</th>
                        <th class="thead_data">Product Code</th>
                        <th class="thead_data">Quantity</th>
                        <th class="thead_data">Created By</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 0;
                    $start_date = to_sql_date($start_date);
                    $end_date = to_sql_date($end_date);
                    $ProductsQuery = "SELECT a.*,b.product_name,b.product_code,b.uom FROM macho_product_update_entry a,macho_products b WHERE a.created>='$start_date' AND a.created<='$end_date' AND b.id=a.product_id ORDER BY a.created DESC ";
                    $ProductsResult = GetAllRows($ProductsQuery);
                    $ProductsCounts = count($ProductsResult);
                    if ($ProductsCounts > 0) {
                        foreach ($ProductsResult as $ProductsData) {
                            $ref_id = $ProductsData['ref_id'];
                            if($ref_id == 0) {
                                $description = 'Stock Entry Data';
                            } elseif ($ref_id == -1) {
                                $description = 'Sales Return Data';
                            } elseif ($ref_id == -2) {
                                $description = 'Removed Bill Data';
                            } else {
                                $description = '';
                            }
                            ?>
                            <tr>
                                <td class="tbody_data"><?php echo ++$no; ?></td>
                                <td class="tbody_data">&nbsp;<?php echo date("d-m-Y", strtotime($ProductsData['created'])); ?></td>
                                <td class="tbody_data">&nbsp;<?php echo $description; ?></td>
                                <td class="tbody_data">&nbsp;<?php echo $ProductsData['product_name']; ?></td>
                                <td class="tbody_data">&nbsp;<?php echo $ProductsData['product_code']; ?></td>
                                <td class="tbody_data">&nbsp;<?php echo $ProductsData['product_qty'] . $ProductsData['uom']; ?></td>
                                <td class="tbody_data">&nbsp;<?php echo UserName($ProductsData['created_by']); ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
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
<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<script src="<?php echo VENDOR; ?>bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
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
<script>
    $(function () {
        //Date picker
        $('#startdate').datepicker({
            autoclose: true
        });

        $('#enddate').datepicker({
            autoclose: true
        });
    });

    $("#enddate").change(function () {
        var startDate = document.getElementById("startdate").value;
        var endDate = document.getElementById("enddate").value;
        if ((Date.parse(endDate) <= Date.parse(startDate))) {
            swal("End date should be greater than Start date");
            document.getElementById("enddate").value = startDate;
        }
    });
</script>
</body>
</html>