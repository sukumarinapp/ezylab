<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">Payments</div>
        <div role="tabpanel">
            <ul class="nav nav-tabs nav-justified">
                <!--                <li class="nav-item" role="presentation">-->
                <!--                    <a class="nav-link" href="#home1" aria-controls="home1"-->
                <!--                       role="tab"-->
                <!--                       data-toggle="tab">Supplier Payments</a>-->
                <!--                </li>-->
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="#profile1" aria-controls="profile1" role="tab"
                        data-toggle="tab">Patient Payments</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#profile2" aria-controls="profile2" role="tab" data-toggle="tab">Payments
                        Receipt</a>
                </li>
            </ul>
            <div class="tab-content">
                <!--                <div class="tab-pane" id="home1" role="tabpanel">-->
                <!--                    <div class="table-responsive">-->
                <!--                        <table class="table table-striped my-4 w-100" id="datatable1">-->
                <!--                            <thead>-->
                <!--                            <tr>-->
                <!--                                <th width="20">#</th>-->
                <!--                                <th>Date</th>-->
                <!--                                <th>Bill No.</th>-->
                <!--                                <th>Supplier</th>-->
                <!--                                <th>Bill Amount</th>-->
                <!--                                <th>Paid Amount</th>-->
                <!--                                <th>Balance Amount</th>-->
                <!--                                <th class="text-center">Action</th>-->
                <!--                            </tr>-->
                <!--                            </thead>-->
                <!---->
                <!--                            <tbody>-->
                <!--                            -->
                <?php
                //                            $no = 0;
//                            $BillQuery = "SELECT * FROM macho_farmer_bill WHERE payment_status ='0' ORDER BY id DESC ";
//                            $BillResult = GetAllRows($BillQuery);
//                            $BillCounts = count($BillResult);
//                            if ($BillCounts > 0) {
//                                foreach ($BillResult as $BillData) {
//                                    $FarmerId = $BillData['farmer_id'];
//                                    $BillId = $BillData['id'];
//                                    $BillAmount = $BillData['net_amount'];
//                                    $PaidAmount = GetFarmerPaidAmount($FarmerId, $BillId);
//                                    $BalanceAmount = $BillAmount - $PaidAmount;
//                                    ?>
                <!--                                    <tr>-->
                <!--                                        <td width="20">-->
                <? //= ++$no; ?><!--</td>-->
                <!--                                        <td>-->
                <? //= from_sql_date($BillData['bill_date']); ?><!--</td>-->
                <!--                                        <td>-->
                <? //= $BillData['bill_no']; ?><!--</td>-->
                <!--                                        <td>-->
                <? //= FarmerName($BillData['farmer_id']); ?><!--</td>-->
                <!--                                        <td>-->
                <? //= $BillAmount; ?><!--</td>-->
                <!--                                        <td>-->
                <? //= $PaidAmount; ?><!--</td>-->
                <!--                                        <td>-->
                <? //= $BalanceAmount; ?><!--</td>-->
                <!--                                        <td class="text-center">-->
                <!--                                            <div class="btn-group">-->
                <!--                                                -->
                <?php
                //                                                if ($PageAccessible['is_read'] == 1) { ?>
                <!--                                                    <button class="btn btn-success"-->
                <!--                                                            onClick="window.open('SupplierBillPDF?fID=-->
                <? //= EncodeVariable($BillData['id']); ?><!--');"
//                                                            title="View"><i class="fa fa-search-plus"></i>
//                                                    </button>
//                                                <?php //}
//                                                if ($PageAccessible['is_write'] == 1) { ?>
<!--                                                    <button class="btn btn-danger" title="Add Payment"-->
                <!--                                                            onClick="window.open('SupplierFinance?fID=-->
                <? //= EncodeVariable($FarmerId); ?><!--//');">
//                                                        <i class="fa fa-paypal"></i></button>
//                                                <?php
//                                                } ?>
<!--                                            </div>-->
                <!--                                        </td>-->
                <!--                                    </tr>-->
                <!--                                -->
                <?php //}
//                            } ?>
                <!--                            </tbody>-->
                <!--                        </table>-->
                <!--                    </div>-->
                <!--                </div>-->
                <div class="tab-pane active" id="profile1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped my-4 w-100" id="datatable2">
                            <thead>
                                <tr>
                                    <th width="20">#</th>
                                    <th>Date</th>
                                    <th>Bill No.</th>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Bill Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Balance Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 0;
                                $bill_type = "patient_entry";
                                $bill_type2 = "billing";
                                $BillQuery = "SELECT * FROM patient_entry WHERE bill_status	 ='0' ORDER BY id DESC ";
                                $BillResult = GetAllRows($BillQuery);
                                $BillCounts = count($BillResult);
                                if ($BillCounts > 0) {
                                    foreach ($BillResult as $BillData) {
                                        $patient_id = $BillData['patient_id'];
                                        $PatientData = SelectParticularRow('macho_patient', 'id', $patient_id);

                                        $BillId = $BillData['id'];
                                        $BillAmount = $BillData['net_amount'];
                                        $PaidAmount = GetCustomerPaidAmount($patient_id, $BillId, $bill_type);
                                        $BalanceAmount = $BillAmount - $PaidAmount;
                                        ?>
                                        <tr>
                                            <td width="20">
                                                <?= ++$no; ?>
                                            </td>
                                            <td>
                                                <?= from_sql_date($BillData['entry_date']); ?>
                                            </td>
                                            <td>
                                                <?= $BillData['bill_no']; ?>
                                            </td>
                                            <td>
                                                <?= $PatientData['P_code']; ?>
                                            </td>
                                            <td>
                                                <?= $PatientData['prefix'] . $PatientData['P_name']; ?>
                                            </td>
                                            <td>
                                                <?= $BillAmount; ?>
                                            </td>
                                            <td>
                                                <?= $PaidAmount; ?>
                                            </td>
                                            <td>
                                                <?= $BalanceAmount; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <?php
                                                    if ($PageAccessible['is_read'] == 1) { ?>
                                                        <button class="btn btn-success"
                                                            onClick="window.open('BillPdf?bID=<?= EncodeVariable($BillData['id']); ?>');"
                                                            title="View"><i class="fa fa-search-plus"></i>
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_write'] == 1) { ?>
                                                        <button class="btn btn-danger" title="Add Payment"
                                                            onClick="window.open('PatientFinance?cID=<?= EncodeVariable($patient_id); ?>');">
                                                            <i class="fa fa-paypal"></i></button>
                                                        <?php
                                                    } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                                $BillQuery2 = "SELECT * FROM macho_billing WHERE bill_status ='0' ORDER BY id DESC ";
                                $BillResult2 = GetAllRows($BillQuery2);
                                $BillCounts2 = count($BillResult2);
                                if ($BillCounts2 > 0) {
                                    foreach ($BillResult2 as $BillData2) {
                                        $patient_id2 = $BillData2['patient_id'];
                                        $PatientData2 = SelectParticularRow('macho_patient', 'id', $patient_id2);

                                        $BillId2 = $BillData2['id'];
                                        $BillAmount2 = $BillData2['net_amount'];
                                        $PaidAmount2 = GetCustomerPaidAmount($patient_id2, $BillId2, $bill_type2);
                                        $BalanceAmount2 = $BillAmount2 - $PaidAmount2;
                                        ?>
                                        <tr>
                                            <td width="20">
                                                <?= ++$no; ?>
                                            </td>
                                            <td>
                                                <?= from_sql_date($BillData2['bill_date']); ?>
                                            </td>
                                            <td>
                                                <?= $BillData2['billnum']; ?>
                                            </td>
                                            <td>
                                                <?= $PatientData2['P_code']; ?>
                                            </td>
                                            <td>
                                                <?= $PatientData2['prefix'] . $PatientData2['P_name']; ?>
                                            </td>
                                            <td>
                                                <?= $BillAmount2; ?>
                                            </td>
                                            <td>
                                                <?= $PaidAmount2; ?>
                                            </td>
                                            <td>
                                                <?= $BalanceAmount2; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <?php
                                                    if ($PageAccessible['is_read'] == 1) { ?>
                                                        <button class="btn btn-success"
                                                            onClick="window.open('InvoicePDF?bID=<?= EncodeVariable($BillData2['id']); ?>');"
                                                            title="View"><i class="fa fa-search-plus"></i>
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_write'] == 1) { ?>
                                                        <button class="btn btn-danger" title="Add Payment"
                                                            onClick="window.open('PatientFinance?cID=<?= EncodeVariable($patient_id2); ?>');">
                                                            <i class="fa fa-paypal"></i></button>
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
                <div class="tab-pane" id="profile2" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped my-4 w-100" id="datatable3">
                            <thead>
                                <tr>
                                    <th width="20">#</th>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 0;
                                $BillQuery = "SELECT * FROM macho_customer_payments WHERE type ='Debit' ORDER BY id DESC ";
                                $BillResult = GetAllRows($BillQuery);
                                $BillCounts = count($BillResult);
                                if ($BillCounts > 0) {
                                    foreach ($BillResult as $BillData) {
                                        if ($BillData['bill_type'] == 'billing') {
                                            $description = 'Bill Number: ' . PatientBillNo($BillData['bill_id']);
                                        } else {
                                            $description = 'Bill Number: ' . CustomerBillNo($BillData['bill_id']);
                                        }
                                        ?>
                                        <tr>
                                            <td width="20">
                                                <?= ++$no; ?>
                                            </td>
                                            <td>
                                                <?= from_sql_date($BillData['created']); ?>
                                            </td>
                                            <td>
                                                <?= CustomerName($BillData['customer_id']); ?>
                                            </td>
                                            <td>
                                                <?= $description; ?>
                                            </td>
                                            <td>
                                                <?= $BillData['amount']; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <?php
                                                    if ($PageAccessible['is_read'] == 1) { ?>
                                                        <button class="btn btn-success"
                                                            onClick="window.open('PaymentReceipt2?pID=<?= EncodeVariable($BillData['id']); ?>');"
                                                            title="View"><i class="fa fa-search-plus"></i> View
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
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
<script>
    $(document).ready(function () {
        $('#datatable1').dataTable();

        $('#datatable2').dataTable();

        $('#datatable3').dataTable();

    });
</script>
</body>

</html>