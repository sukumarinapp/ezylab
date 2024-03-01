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
            <div>Supplier Account
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Supplier Account Report','0','0');"><i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Supplier Account Report','0','0');"><i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Supplier Account Report','0','0');"><i
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
                            <th class="thead_data">Code</th>
                            <th class="thead_data">Name</th>
                            <th class="thead_data">Mobile</th>
                            <th class="thead_data">Credit Amount</th>
                            <th class="thead_data">Debit Amount</th>
                            <th class="thead_data">Balance Amount</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $no = 0;
                        $CustomerQuery = "SELECT * FROM macho_farmers ORDER BY id DESC ";
                        $CustomerResult = GetAllRows($CustomerQuery);
                        $CustomerCounts = count($CustomerResult);
                        if ($CustomerCounts > 0) {
                            foreach ($CustomerResult as $CustomerData) {
                                $FarmerId = $CustomerData['id'];
                                $CustomerAccountPayment = FarmerAccountPayment($FarmerId);
                                ?>
                                <tr>
                                    <td width="20" class="tbody_data">&nbsp;<?= ++$no; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerData['F_code']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerData['F_name']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerData['mobile']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerAccountPayment['CreditAmount']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerAccountPayment['DebitAmount']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerAccountPayment['BalanceAmount']; ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php
                                            if ($PageAccessible['is_read'] == 1) { ?>
                                                <button class="btn btn-success"
                                                        onClick="window.open('SupplierPaymentReport?fID=<?= EncodeVariable($FarmerId); ?>');"
                                                        title="View Profile"><i
                                                        class="mdi mdi-magnify-plus"></i> View
                                                </button>
                                            <?php
                                            } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
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