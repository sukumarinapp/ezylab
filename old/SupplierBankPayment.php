<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);
$customer_id = 0;
$from_date = date("Y-m-01");
$to_date = date("Y-m-d");
if (isset($_POST['search'])) {
    $customer_id = $_POST['farmer_id'];
    $from_date = to_sql_date($_POST['startdate']);
    $to_date = to_sql_date($_POST['enddate']);
}

if (isset($_POST['update'])) {

    $farmer_bill_id = $_POST['id'];
    $entry_date = $_POST['entry_date'];
    $FarmerId = $_POST['farmer_id'];
    $farmer = $_POST['farmer'];
    $payment_method = $_POST['payment_method'];
    $reference_no = $_POST['reference_no'];
    $pay_amount = $_POST['pay_amount'];
    $description = $_POST['description'];
    $payment_status = $_POST['payment_status'];
    $collected_date = $_POST['collected_date'];
    $confirm_status = $_POST['confirm_status'];

    $user_role_id = GetRoleOfUser($user_id);
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);

    if ($confirm_status == 1) {

        $update2 = Update('macho_farmer_payments', 'id', $farmer_bill_id, array(
            'description' => $description,
            'status' => $payment_status,
            'collected_date' => to_sql_date($collected_date)
        ));

        if ($update2) {
            if ($payment_status == '1') {

                $saving_account = '9';
                $account_id = '4';
                $type = 'Expense';

                $company_revenue_sql = Insert('macho_revenue', array(
                    'account_id' => $account_id,
                    'saving_account' => $saving_account,
                    'farmer_id' => $FarmerId,
                    'type' => $type,
                    'pay_for' => $description,
                    'payment_method' => $payment_method,
                    'reference_no' => $reference_no,
                    'amount' => $pay_amount,
                    'entry_date' => to_sql_date($collected_date),
                    'modified_date' => to_sql_date($collected_date)
                ));

                $notes = $user . '  has Update ' . $payment_method . '.Reference No :' . $reference_no . '.Amount Rs.' . $pay_amount . '.Supplier Name :' . $farmer;
            } elseif ($payment_status == '2') {
                $notes = $user . '  has Update Cancel ' . $payment_method . ' Details. Reference No :' . $reference_no . '.Amount Rs.' . $pay_amount . '.Supplier Name :' . $farmer;
            }
            InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
            echo '<span id="update_success"></span>';
        } else {
            echo '<span  id="update_failure"></span>';
        }
    }
}
?>

<!-- Main section-->

<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div>Bank Payments
                <small></small>
            </div>
            <div class="ml-auto">
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <?php if ($PageAccessible['is_write'] == 1) { ?>
                            <form action="SupplierBankPayment" method="post" class="search-form">
                                <div class="btn-toolbar">
                                    <div class="form-group">
                                        <select class="form-control select2" name="farmer_id"
                                                id="farmer_id">
                                            <option value="0">All Supplier</option>
                                            <?php
                                            $CustomerQuery = 'SELECT * FROM macho_farmers ORDER BY id DESC ';
                                            $CustomerResult = GetAllRows($CustomerQuery);
                                            foreach ($CustomerResult as $CustomerData) {
                                                echo "<option ";
                                                if ($customer_id == $CustomerData['id']) echo " selected ";
                                                echo "value='" . $CustomerData['id'] . "'>" . $CustomerData['F_name'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="startdate" id="startdate"
                                               class="form-control" data-date-format="dd-mm-yyyy"
                                               value="<?php echo from_sql_date($from_date); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="enddate" id="enddate"
                                               class="form-control" data-date-format="dd-mm-yyyy"
                                               value="<?php echo from_sql_date($to_date); ?>" max="<?php echo date("Y-m-d"); ?>">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="search" class="btn btn-success" title="Search">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                    <?php } ?>
                    <div class="text-sm"></div>
                </div>

                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="datatable1">
                        <thead>
                        <tr>
                            <th width="20px" class="thead_data">#</th>
                            <th class="thead_data">Date</th>
                            <th class="thead_data">Supplier Name</th>
                            <th class="thead_data">Payment Method</th>
                            <th class="thead_data">Reference No.</th>
                            <th class="thead_data">Amount</th>
                            <th class="thead_data">Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        if ($customer_id == 0) {
                            $BillingQuery = "SELECT a.*,b.F_name FROM macho_farmer_payments a,macho_farmers b WHERE a.payment_method<>'Cash' AND a.created>='$from_date' AND a.created<='$to_date' AND b.id=a.farmer_id  ORDER BY a.id DESC ";
                        } else {
                            $BillingQuery = "SELECT a.*,b.F_name FROM macho_farmer_payments a,macho_farmers b WHERE a.payment_method<>'Cash' AND a.farmer_id= '$customer_id' AND a.created>='$from_date' AND a.created<='$to_date' AND b.id=a.farmer_id ORDER BY a.id DESC ";
                        }
                         $BillingResult = GetAllRows($BillingQuery);
                        $BillingCounts = count($BillingResult);
                        if ($BillingCounts > 0) {
                            foreach ($BillingResult as $BillingData) {
                               ?>
                                <tr>
                                    <td class="tbody_data"><?php echo ++$no; ?></td>
                                    <td><?php echo from_sql_date($BillingData['created']); ?></td>
                                    <td><?php echo $BillingData['F_name']; ?></td>
                                    <td><?php echo $BillingData['payment_method']; ?></td>
                                    <td><?php echo $BillingData['reference_no']; ?></td>
                                    <td><?php echo $BillingData['amount']; ?></td>
                                    <td><?php if ($BillingData['status'] == '1') {
                                            echo '<span class="label label-success">PAID</span>';
                                        } elseif ($BillingData['status'] == '2') {
                                            echo '<span class="label label-default">CANCEL</span>';
                                        } else {
                                            echo '<span class="label label-warning">PENDING</span>';
                                        } ?></td>
                                    <td>
                                        <div class="btn-group">
                                <?php
                                if ($BillingData['status'] == 0) {
                                    if ($PageAccessible['is_modify'] == 1) {
                                        ?>
                                                        <button class="btn btn-info" type="button" title="Update Payment Details"
                                                                onclick="EditFarmerBill(<?php echo $BillingData['id']; ?>);">
                                                            <i class="fa fa-paypal"></i> Update
                                                        </button>
                                    <?php }
                                }
                                if ($PageAccessible['is_read'] == 1) { ?>
                                    <button type="button" class="btn btn-success"
                                            title="Payment Receipt"
                                            onclick="window.open('FPaymentReceiptPDF?id=<?php echo EncodeVariable($BillingData['id']) ?>','_blank');">
                                        <i class="fa fa-print"></i>&nbsp;View
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
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php' ?>
</div>
<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Payment Details</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
        </div>
    </div>
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
<script src="<?php echo VENDOR; ?>bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<script src="<?php echo VENDOR; ?>select2/dist/js/select2.full.js"></script>

<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
<script>
    $(function () {
        //Date picker
        $('#startdate').datepicker({
            autoclose: true
        });

        $('#enddate').datepicker({
            autoclose: true
        });

        $('#collected_date').datepicker({
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

    function EditFarmerBill(id) {
        $.ajax({
            type: "POST",
            url: "UpdateFarmerBill.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#AddModal').modal('show');
            }
        });
    }


    window.onload = function () {

        if (document.getElementById('update_success')) {
            swal("Success!", "Payment Details has been Updated!", "success");
        }

        if (document.getElementById('update_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

    }
</script>
</body>
</html>