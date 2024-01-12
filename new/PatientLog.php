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

$PageAccessible = IsPageAccessible($user_id, 'Patient');

$patient_id = DecodeVariable($_GET['patient_id']);
$PatientInfo = SelectParticularRow('macho_patient', 'id', $patient_id);

?><?php include ("css.php"); ?>
<title>Dashtrans</title>
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
                    <a class="nav-link active" href="#home1" aria-controls="home1" role="tab" data-bs-toggle="tab">Patient
                        Log</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#profile1" aria-controls="profile1" role="tab" data-bs-toggle="tab">Payment
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
                                                        <button class="btn btn-sm btn-success"
                                                            onClick="window.open('BillPdf?bID=<?= EncodeVariable($BillData['id']); ?>');"
                                                            title="View"><i class="fa fa-eye"></i>
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_write'] == 1) {
                                                        if ($BillData['test_status'] == '1') {
                                                            ?>
                                                            <button class="btn btn-sm btn-info" title="Test Receipt"
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
</div>

   <?php include ("js.php"); ?>
<script>
    $(document).ready(function () {
        $('#datatable1').dataTable();

        $('#datatable2').dataTable();
    });
</script>
</body>

</html>