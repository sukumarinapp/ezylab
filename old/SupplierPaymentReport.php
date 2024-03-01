<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';

$FarmerId = DecodeVariable($_GET['fID']);
$CustomerData = SelectParticularRow('macho_farmers', 'id', $FarmerId);


?>

<!-- Main section-->
<section class="section-container no-print">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div><?= $CustomerData['F_name']; ?>
                <small>Payment Report</small>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <?php //if ($PageAccessible['is_write'] == 1) { ?>
                    <button type="button" class="btn btn-sm btn-white" title="Add Supplier Payment"
                            onclick="location.href = 'AddSupplierPayment?fID=<?php echo EncodeVariable($FarmerId) ?>';"><i
                            class="fa fa-plus"></i>
                        Add Payment
                    </button>
                    <?php //} ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped my-4 w-100" id="datatable2">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Payment Method</th>
                                <th>Reference No</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Status</th>
                                <th class="no-print">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 0;
                            $uncredit_amount = 0;
                            $credit_amount = 0;
                            $debit_amount = 0;

                            $FinanceQuery = "SELECT * FROM macho_farmer_payments WHERE farmer_id='$FarmerId' ORDER BY id ";

                            $FinanceResult = GetAllRows($FinanceQuery);

                            $FinanceCounts = count($FinanceResult);

                            if ($FinanceCounts > 0) {

                                foreach ($FinanceResult as $FinanceData) {
                                    if ($FinanceData['bill_id'] == 0) {
                                        $description = $FinanceData['description'];
                                    } else {
                                        $description = 'Bill Number: ' . FarmerBillNo($FinanceData['bill_id']);
                                    }
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?php echo ++$no; ?></td>
                                        <td><?= from_sql_date($FinanceData['created']); ?></td>
                                        <td><?= $description; ?></td>
                                        <td><?= $FinanceData['payment_method']; ?></td>
                                        <td><?= $FinanceData['reference_no']; ?></td>
                                        <?php if ($FinanceData['type'] == 'Credit') {
                                            if ($FinanceData['status'] == '1') {
                                                $credit_amount = $credit_amount + $FinanceData['amount'];
                                            } else {
                                                $uncredit_amount = $uncredit_amount + $FinanceData['amount'];
                                            } ?>
                                            <td style="text-align: left"><?= $FinanceData['amount']; ?></td>
                                            <td style="text-align: left">0.00</td>
                                        <?php } else {
                                            $debit_amount = $debit_amount + $FinanceData['amount']; ?>
                                            <td style="text-align: left">0.00</td>
                                            <td style="text-align: left"><?= $FinanceData['amount']; ?></td>
                                        <?php } ?>
                                        <td><?php if ($FinanceData['status'] == '1') {
                                                echo '<span class="label label-success">COLLECTED</span>';
                                            } elseif ($FinanceData['status'] == '2') {
                                                echo '<span class="label label-default">CANCEL</span>';
                                            } else {
                                                echo '<span class="label label-warning">PENDING</span>';
                                            } ?></td>
                                        <td class="no-print">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info"
                                                        title="Payment Receipt"
                                                        onclick="window.open('FPaymentReceiptPDF?id=<?php echo EncodeVariable($FinanceData['id']) ?>','_blank');">
                                                    <i class="ico-print3"></i>&nbsp;View
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            }
                            ?>
                            </tbody>
                            <?php
                            if ($FinanceCounts > 0) { ?>
                                <tbody>
                                <tr>
                                    <th style="text-align: center">&nbsp;</th>
                                    <th style="text-align: left;font-weight: bold">Credit Amount</th>
                                    <th style="text-align: left;font-weight: bold">
                                        Rs. <?= ConvertMoneyFormat($credit_amount); ?></th>
                                    <th style="text-align: left;font-weight: bold">Debit Amount</th>
                                    <th style="text-align: left;font-weight: bold">
                                        Rs. <?= ConvertMoneyFormat($debit_amount); ?></th>
                                    <th style="text-align: left;font-weight: bold">Balance Amount</th>
                                    <th style="text-align: left;font-weight: bold" colspan="2">
                                        Rs. <?= ConvertMoneyFormat($credit_amount - $debit_amount); ?></th>
                                </tr>
                                </tbody>
                            <?php }
                            ?>
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

</body>

</html>

</body>

</html>