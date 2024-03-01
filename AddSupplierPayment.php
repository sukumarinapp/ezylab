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

$today = date("Y-m-d");
$created = date("Y-m-d H:i:s");
$updated = date("Y-m-d H:i:s");

$FarmerId = DecodeVariable($_GET['fID']);
$CustomerData = SelectParticularRow('macho_farmers', 'id', $FarmerId);
$CustomerAccountPayment = FarmerAccountPayment($FarmerId);

if (isset($_POST['add_submit'])) {

    if ($_POST['type'] == 'Credit') {
        $payment_method = Filter($_POST['payment_method']);
        $reference_no = Filter($_POST['reference_no']);
        if ($payment_method == 'Cash') {
            $status = 1;
            $collected_date = to_sql_date($_POST['created']);
        } else {
            $status = 0;
            $collected_date = '0000-00-00';
        }
    } else {
        $payment_method = 'Cash';
        $reference_no = '';
        $status = 1;
        $collected_date = to_sql_date($_POST['created']);
    }

    $insert_query = Insert('macho_farmer_payments', array(
        'farmer_id' => Filter($FarmerId),
        'type' => Filter($_POST['type']),
        'payment_method' => Filter($payment_method),
        'reference_no' => Filter($_POST['reference_no']),
        'description' => Filter($_POST['description']),
        'amount' => Filter($_POST['amount']),
        'created' => to_sql_date($_POST['created']),
        'status' => $status,
        'collected_date' => $collected_date
    ));

    if (is_int($insert_query)) {

        if ($_POST['type'] == 'Credit') {
            if ($_POST['payment_method'] == 'Cash') {
                $saving_account = '1';
                $account_id = '4';
                $type = 'Expense';

                $company_revenue_sql = Insert('macho_revenue', array(
                    'account_id' => $account_id,
                    'saving_account' => $saving_account,
                    'customer_id' => $FarmerId,
                    'type' => $type,
                    'pay_for' => Filter($_POST['description']),
                    'payment_method' => Filter($payment_method),
                    'reference_no' => Filter($_POST['reference_no']),
                    'amount' => Filter($_POST['amount']),
                    'entry_date' => to_sql_date($_POST['created']),
                    'modified_date' => to_sql_date($_POST['created'])
                ));
            }
        }

        if ($_POST['type'] == 'Credit') {
            $notes = 'Supplier Payment Rs.' . $_POST['amount'] . ' credited details added by ' . $user_name;
        } else {
            $notes = 'Supplier Payment Rs.' . $_POST['amount'] . ' debited details added by ' . $user_name;
        }
        $user_role_id = GetRoleOfUser($user_id);
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $user_role_id, $receive_role_id, $receive_id);

        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}
?>
<?php include ("css.php"); ?>
<title>Add Supplier Payment</title>
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
	  
        <div class="content-heading"></div>
        <div class="row">
            <div class="col-xl-12">
                <form method="post" action="" enctype="multipart/form-data">
                    <!-- START card-->
                    <div class="card card-default">
                        <div class="card-header">
                            <h5 class="font-16 m-1">Add Supplier Payment</h5>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Supplier ID</label>

                                            <input type="hidden" name="fID" id="fID"
                                                   value="<?= $_GET['fID']; ?>">
                                            <input type="text" class="form-control" name="customer_no"
                                                   id="customer_no"
                                                   value="<?php echo $CustomerData['F_code']; ?>"
                                                   maxlength="100"
                                                   tabindex="1" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Supplier Mobile</label>

                                            <input type="text" class="form-control" name="customer_mobile"
                                                   id="customer_mobile"
                                                   value="<?php echo $CustomerData['mobile']; ?>"
                                                   maxlength="100" tabindex="3" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Credit Amount</label>

                                            <input type="text" class="form-control" name="credit_amount"
                                                   id="credit_amount"
                                                   value="<?php echo $CustomerAccountPayment['CreditAmount']; ?>"
                                                   maxlength="100" tabindex="3" readonly>
                                        </div>
                                        <!--                                                    <div class="form-group">-->
                                        <!--                                                        <label class="col-sm-4 control-label">UnCreditable Amount</label>-->
                                        <!---->
                                        <!--                                                        <div class="col-sm-8">-->
                                        <!--                                                            <input type="text" class="form-control" name="uncredit_amount"-->
                                        <!--                                                                   id="uncredit_amount"-->
                                        <!--                                                                   value="-->
                                        <?php //echo $CustomerAccountPayment['UnCreditAmount']; ?><!--"-->
                                        <!--                                                                   maxlength="100" tabindex="5" readonly>-->
                                        <!--                                                        </div>-->
                                        <!--                                                    </div>-->
                                        <div class="form-group">
                                            <label class="control-label">Account Type *</label>

                                            <select name="type" id="type" class="form-control"
                                                    onchange="check_amount()"
                                                    tabindex="7" required>
                                                <option value="">Select</option>
                                                <option value="Credit">Credit</option>
                                                <option value="Debit">Debit</option>
                                            </select>
                                        </div>
                                        <div class="form-group payment_tab" style="display: none">
                                            <label class="control-label">Payment Method *</label>

                                            <select name="payment_method" id="payment_method"
                                                    class="form-control"
                                                    tabindex="9">
                                                <option value="">Select</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Online Payment">Online Payment</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Demand Draft">Demand Draft</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Amount *</label>

                                            <input name="amount" id="amount" type="text"
                                                   onkeyup="check_amount()"
                                                   class="form-control" maxlength="100" tabindex="11"
                                                   onkeypress="return isNumberDecimalKey(event)" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Supplier Name</label>

                                            <input type="text" class="form-control" name="customer_name"
                                                   id="customer_name"
                                                   value="<?php echo $CustomerData['F_name']; ?>"
                                                   maxlength="100"
                                                   tabindex="2" readonly>

                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Debit Amount</label>

                                            <input type="text" class="form-control" name="debit_amount"
                                                   id="debit_amount"
                                                   value="<?php echo $CustomerAccountPayment['DebitAmount']; ?>"
                                                   maxlength="100" tabindex="4" readonly>

                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Balance Amount</label>

                                            <input type="text" class="form-control" name="balance_amount"
                                                   id="balance_amount"
                                                   value="<?php echo $CustomerAccountPayment['BalanceAmount']; ?>"
                                                   maxlength="100" tabindex="6" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Description</label>

                                            <input name="description" id="description" type="text"
                                                   class="form-control" maxlength="100" tabindex="8">
                                        </div>
                                        <div class="form-group payment_tab" style="display: none">
                                            <label class="control-label">Reference No.</label>

                                            <input name="reference_no" id="reference_no" type="text"
                                                   class="form-control" tabindex="10">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Date *</label>

                                            <input name="created" id="created" type="date"
                                                   class="form-control" value="<?= date('Y-m-d'); ?>"
                                                   tabindex="12"
                                                   required>
                                        </div>
                                    </div>
                                </div>


                                <div class="hr-line-dashed"></div>

                                <div align="right">
                                    <button class="btn btn-sm btn-primary m-t-n-xs" type="submit"
                                            name="add_submit"
                                            tabindex="20">
                                        <strong>Submit</strong>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
<script>
    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function check_amount() {
        var type = $('#type').val();
        var pay_amount = $('#amount').val();
        var account_amount = $('#balance_amount').val();
        var customer_name = $('#customer_name').val();
        var description = $('#description').val();
        if (type == 'Debit') {
            if (pay_amount != '') {
                if (parseFloat(pay_amount) > parseFloat(account_amount)) {
                    swal("Amount cannot be greater than Balance Amount");
                    $('#amount').val("0");
                }
            }
            $('.payment_tab').hide();
        } else {
            if (description == "") {
                $('#description').val(customer_name + ' Bill Payments');
            }
            $('.payment_tab').show();
        }
    }

    window.onload = function () {

        if (document.getElementById('insert_success')) {
            swal({
                title: "Success",
                text: "Payment Details Added Successfully!",
                type: "success",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "OK",
                closeOnConfirm: false
            });
            location.href = "SupplierAccount";
        }

        if (document.getElementById('insert_failure')) {
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