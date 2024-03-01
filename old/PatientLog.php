<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, 'Patient');

$patient_id = DecodeVariable($_GET['patient_id']);
$PatientInfo = SelectParticularRow('macho_patient', 'id', $patient_id);

?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div>
                <?= $PatientInfo['prefix'] . $PatientInfo['P_name']; ?>
                <small>
                    <?= $PatientInfo['P_code']; ?>
                </small>
            </div>
        </div>
        <div role="tabpanel">
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="#home1" aria-controls="home1" role="tab" data-toggle="tab">Patient
                        Log</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#profile1" aria-controls="profile1" role="tab" data-toggle="tab">Payment
                        Receipt</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="home1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped my-4 w-100" id="datatable1">
                            <thead>
                                <tr>
                                    <th width="20">#</th>
                                    <th>Date</th>
                                    <th>Bill No.</th>
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
                                $BillQuery = "SELECT * FROM patient_entry WHERE patient_id=$patient_id ORDER BY id DESC ";
                                $BillResult = GetAllRows($BillQuery);
                                $BillCounts = count($BillResult);
                                if ($BillCounts > 0) {
                                    foreach ($BillResult as $BillData) {
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
                                                    if ($PageAccessible['is_write'] == 1) {
                                                        if ($BillData['test_status'] == '1') {
                                                            ?>
                                                            <button class="btn btn-info" title="Test Receipt"
                                                                onClick="window.open('TestReceipt?bID=<?= EncodeVariable($BillData['id']); ?>');">
                                                                <i class="fa fa-heartbeat"></i></button>
                                                            <?php
                                                        }
                                                    } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                                $BillQuery2 = "SELECT * FROM macho_billing WHERE patient_id=$patient_id ORDER BY id DESC ";
                                $BillResult2 = GetAllRows($BillQuery2);
                                $BillCounts2 = count($BillResult2);
                                if ($BillCounts2 > 0) {
                                    foreach ($BillResult2 as $BillData2) {
                                        $BillId2 = $BillData2['id'];
                                        $BillAmount2 = $BillData2['net_amount'];
                                        $PaidAmount2 = GetCustomerPaidAmount($patient_id, $BillId2, $bill_type2);
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
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="profile1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped my-4 w-100" id="datatable2">
                            <thead>
                                <tr>
                                    <th width="20">#</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 0;
                                $BillQuery = "SELECT * FROM macho_customer_payments WHERE customer_id=$patient_id AND type ='Debit' ORDER BY id DESC ";
                                $BillResult = GetAllRows($BillQuery);
                                $BillCounts = count($BillResult);
                                if ($BillCounts > 0) {
                                    foreach ($BillResult as $BillData) {
                                        if ($BillData['bill_id'] == 0) {
                                            $description = $BillData['description'];
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
    });
</script>
</body>

</html>