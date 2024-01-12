<?php
   session_start();
   include "booster/bridge.php";
   $user_id = $_SESSION["user_id"];
   $role_id = $_SESSION["role_id"];
   $role = $_SESSION["role"];
   $user = $_SESSION["user"];
   $user_name = $_SESSION["user_name"];
   $email = $_SESSION["user_email"];
   $picture = $_SESSION["picture"];
   $access_token = $_SESSION["access_token"];
   ValidateAccessToken($user_id, $access_token);
   $page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);


$FarmerId = DecodeVariable($_GET['fID']);
$CustomerData = SelectParticularRow('macho_farmers', 'id', $FarmerId);


?>
<?php include ("css.php"); ?>
<title>Payment Report</title>
</head>
<body class="bg-theme bg-theme2">
   <!--wrapper-->
   <div class="wrapper">
   <!--sidebar wrapper -->
   <?php include ("Menu.php"); ?>
   <!--end sidebar wrapper -->
   <!--start header -->
   <?php include ("header.php"); ?>
   <!--end header -->
   <!--start page wrapper -->
   <div class="page-wrapper">
      <div class="page-content">
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
</section>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>

</body>

</html>

</body>

</html>