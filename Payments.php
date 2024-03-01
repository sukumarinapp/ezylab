<?php
session_start();
include_once "booster/bridge.php";
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

$PageAccessible = IsPageAccessible($user_id, $page);

$theme = "SELECT * FROM macho_users WHERE id ='$user_id'";
$TestTypeResult = mysqli_query($GLOBALS['conn'], $theme) or die(mysqli_error($GLOBALS['conn']));
$TestTypeData = mysqli_fetch_assoc($TestTypeResult);
$colour = $TestTypeData['colour'];
?>
<!doctype html>
<html lang="en">

<head>
<?php include ("headercss.php"); ?>
<title>Payments</title>
</head>
<body class="bg-theme bg-<?php echo $colour ?>">
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
        <div class="page-content"><h6>Payments</h6></div>
        <div role="tabpanel">
            <ul class="nav nav-tabs nav-justified">
                <!--                <li class="nav-item" role="presentation">-->
                <!--                    <a class="nav-link" href="#home1" aria-controls="home1"-->
                <!--                       role="tab"-->
                <!--                       data-toggle="tab">Supplier Payments</a>-->
                <!--                </li>-->
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="#profile1" aria-controls="profile1" role="tab"
                        data-bs-toggle="tab">Patient Payments</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#profile2" aria-controls="profile2" role="tab" data-bs-toggle="tab">Payments
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
                <div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example2" class="table table-striped table-bordered" style="width:100%">
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
                                                            title="View"><em class="fa fa-eye"></em>
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_write'] == 1) { ?>
                                           <button class="btn btn-danger" title="Add Payment"
                                                    onClick="window.open('PatientFinance?cID=<?= EncodeVariable($patient_id); ?>');">
                                                    <em class="fab fa-paypal"></em></button>
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
                                                        <button class="btn btn-sm btn-success"
                                                            onClick="window.open('InvoicePDF?bID=<?= EncodeVariable($BillData2['id']); ?>');"
                                                            title="View"><i class="fa fa-eye"></i>
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_write'] == 1) { ?>
                                                        <button class="btn btn-sm btn-danger" title="Add Payment"
                                                            onClick="window.open('PatientFinance?cID=<?= EncodeVariable($patient_id2); ?>');">
                                                            <i class="fab fa-paypal"></i></button>
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
                                                        <button class="btn btn-sm btn-success"
                                                            onClick="window.open('PaymentReceipt2?pID=<?= EncodeVariable($BillData['id']); ?>');"
                                                            title="View"><i class="fa fa-eye"></i> View
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
</div>
	  
</div>

   <?php include ("js.php"); ?>


<script>
    $(document).ready(function () {
        $('#datatable1').dataTable();

        $('#datatable2').dataTable();

        $('#datatable3').dataTable();

    });
</script>
<script>
$(document).ready(function() {
	  $('#example').DataTable()
	});
	
		$(document).ready(function() {
			var table = $('#example2').DataTable( {
				lengthChange: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
		 
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
</script>
</body>

</html>