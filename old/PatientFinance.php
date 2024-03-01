<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, 'Payments');
$CustomerId = DecodeVariable($_GET['cID']);
$CustomerAccountPayment = CustomerAccountPayment($CustomerId);
$today = date("Y-m-d");
?>

<!-- Main section-->
<section class="section-container no-print">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div>
                <?= CustomerName($CustomerId); ?>
                <small></small>
            </div>
        </div>
        <!-- start  -->
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <!--                                <div class="row">-->
                    <!--                                    <div class="col-md-12">-->
                    <!--                                        <table class="table table-borderless">-->
                    <!---->
                    <!--                                            <thead>-->
                    <!--                                            <tr>-->
                    <!--                                                <th>Credit Amount: -->
                    <? //= $CustomerAccountPayment['CreditAmount']; ?><!--</th>-->
                    <!--                                                <th>Debit Amount: -->
                    <? //= $CustomerAccountPayment['DebitAmount']; ?><!--</th>-->
                    <!--                                                <th>Balance Amount: -->
                    <? //= $CustomerAccountPayment['BalanceAmount']; ?>
                    <!--                                                    <input type="hidden" name="credit_balance" id="credit_balance"-->
                    <!--                                                           value="-->
                    <? //= $CustomerAccountPayment['BalanceAmount']; ?><!--">-->
                    <!--                                                </th>-->
                    <!--                                            </tr>-->
                    <!--                                            </thead>-->
                    <!--                                        </table>-->
                    <!--                                        <!-- form-group -->
                    <!---->
                    <!--                                    </div>-->
                    <!--                                </div>-->
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped my-4 w-100" id="datatable2">
                            <thead>
                                <tr>
                                    <th width="20">#</th>
                                    <th>Date</th>
                                    <th>Bill No.</th>
                                    <th>Bill Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Balance Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 0;
                                $bill_type = "patient_entry";
                                $bill_type2 = "billing";
                                $BillQuery = "SELECT * FROM patient_entry WHERE patient_id= '$CustomerId' AND bill_status ='0' ORDER BY id ";
                                $BillResult = GetAllRows($BillQuery);
                                $BillCounts = count($BillResult);
                                if ($BillCounts > 0) {
                                    foreach ($BillResult as $BillData) {
                                        $BillId = $BillData['id'];
                                        $BillAmount = $BillData['net_amount'];
                                        $PaidAmount = GetCustomerPaidAmount($CustomerId, $BillId, $bill_type);
                                        $BalanceAmount = $BillAmount - $PaidAmount;
                                        ?>
                                        <tr>
                                            <td width="20">
                                                <?= ++$no; ?>
                                            </td>
                                            <td><input type="text" class="form-control" name="bill_date[]"
                                                    id="bill_date<?= $BillId; ?>"
                                                    value="<?= from_sql_date($BillData['entry_date']); ?>" readonly></td>
                                            <td><input type="hidden" class="form-control" name="bill_id[]"
                                                    id="bill_id<?= $BillId; ?>" value="<?= $BillId; ?>">
                                                <input type="hidden" class="form-control" name="bill_type[]"
                                                    id="bill_type<?= $BillId; ?>" value="<?= $bill_type; ?>">
                                                <input type="text" class="form-control" name="bill_no[]"
                                                    id="bill_no<?= $BillId; ?>" value="<?= $BillData['bill_no']; ?>" readonly>
                                            </td>
                                            <td><input type="text" class="form-control" name="bill_amount[]"
                                                    id="bill_amount<?= $BillId; ?>" value="<?= $BillAmount; ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="paid_amount[]"
                                                    id="paid_amount<?= $BillId; ?>" value="<?= $PaidAmount; ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="balance_amount[]"
                                                    id="balance_amount<?= $BillId; ?>" value="<?= $BalanceAmount; ?>" readonly>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                                $BillQuery2 = "SELECT * FROM macho_billing WHERE patient_id= '$CustomerId' AND bill_status ='0' ORDER BY id ";
                                $BillResult2 = GetAllRows($BillQuery2);
                                $BillCounts2 = count($BillResult2);
                                if ($BillCounts2 > 0) {
                                    foreach ($BillResult2 as $BillData2) {
                                        $BillId2 = $BillData2['id'];
                                        $BillAmount2 = $BillData2['net_amount'];
                                        $PaidAmount2 = GetCustomerPaidAmount($CustomerId, $BillId2, $bill_type2);
                                        $BalanceAmount2 = $BillAmount2 - $PaidAmount2;
                                        ?>
                                        <tr>
                                            <td width="20">
                                                <?= ++$no; ?>
                                            </td>
                                            <td><input type="text" class="form-control" name="bill_date[]"
                                                    id="bill_date<?= $BillId2; ?>"
                                                    value="<?= from_sql_date($BillData2['bill_date']); ?>" readonly></td>
                                            <td><input type="hidden" class="form-control" name="bill_id[]"
                                                    id="bill_id<?= $BillId2; ?>" value="<?= $BillId2; ?>">
                                                <input type="hidden" class="form-control" name="bill_type[]"
                                                    id="bill_type<?= $BillId2; ?>" value="<?= $bill_type2; ?>">
                                                <input type="text" class="form-control" name="bill_no[]"
                                                    id="bill_no<?= $BillId2; ?>" value="<?= $BillData2['billnum']; ?>" readonly>
                                            </td>
                                            <td><input type="text" class="form-control" name="bill_amount[]"
                                                    id="bill_amount<?= $BillId2; ?>" value="<?= $BillAmount2; ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="paid_amount[]"
                                                    id="paid_amount<?= $BillId2; ?>" value="<?= $PaidAmount2; ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="balance_amount[]"
                                                    id="balance_amount<?= $BillId2; ?>" value="<?= $BalanceAmount2; ?>"
                                                    readonly></td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Bill Amount</label>
                                <input type="text" class="form-control" name="total_bill_amount" id="total_bill_amount"
                                    value="" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Paid Amount</label>
                                <input type="text" class="form-control" name="total_paid_amount" id="total_paid_amount"
                                    value="" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Balance Amount</label>
                                <input type="text" class="form-control" name="total_balance_amount"
                                    id="total_balance_amount" value="" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Created By</label>
                                <input type="text" name="created_by" id="created_by" class="form-control"
                                    value="<?= $user; ?>" maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!--                                        <div class="form-group">-->
                            <!--                                            <label class="control-label">Discount Amount</label>-->
                            <!--                                            <input type="text" class="form-control"-->
                            <!--                                                   name="discount_amount"-->
                            <!--                                                   id="discount_amount"-->
                            <!--                                                   onkeyup="calculate_discount()"-->
                            <!--                                                   onkeypress="return isNumberDecimalKey(event)">-->
                            <!--                                        </div>-->
                            <div class="form-group">
                                <label class="control-label">Payable Amount</label>
                                <input type="text" class="form-control" name="payable_amount" id="payable_amount"
                                    value="" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Pay Amount</label>
                                <input type="hidden" class="form-control" name="total_pay_amount" id="total_pay_amount"
                                    value="">
                                <input type="text" class="form-control" name="pay_amount" id="pay_amount" value="">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-control">
                                    <option value="Cash">Cash</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Debit Card">Debit Card</option>
                                    <option value="Online Payment">Online Payment</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Demand Draft">Demand Draft</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Reference No.</label>
                                <input type="text" name="reference_no" id="reference_no" class="form-control"
                                    maxlength="100">
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="float-right">
                        <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $CustomerId; ?>">
                        <input type="hidden" name="bill_count" id="bill_count"
                            value="<?php echo GetCustomerBillPendingCount($CustomerId); ?>">
                        <input type="hidden" name="customer_name" id="customer_name"
                            value="<?php echo CustomerName($CustomerId); ?>">
                        <button class="btn btn-labeled btn-secondary" type="button" onclick="location.href='Payments';">
                            <span class="btn-label"><i class="fa fa-arrow-left"></i>
                            </span>Back to List
                        </button>
                        <button class="btn btn-labeled btn-primary" type="button" name="submit" id="save_button"
                            onclick="submit_data();" tabindex="9" disabled>
                            <span class="btn-label"><i class="fa fa-check"></i>
                            </span>Submit Payment
                        </button>
                    </div>


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
    window.onload = function () {
        var invoice_count = $('#bill_count').val();
        var customer_name = $('#customer_name').val();
        setTimeout(function () {
            swal(invoice_count + " Bill Pending for " + customer_name + "!");
        }, 2000);

        GetTotalBalanceAmount();
    }

    function DecimalPoint(x) {
        return Number.parseFloat(x).toFixed(2);
    }

    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    $(document).ready(function () {

        $('input[name^="pay_amount"]').keyup(function () {

            var rBtnVal = $(this).val();
            if (isNaN(rBtnVal) || rBtnVal <= 0) {
                $("#save_button").attr("disabled", true);
            }
            else {
                $("#save_button").attr("disabled", false);
            }
        });
    });

    $("input[name='pay_amount']").keyup(function () {
        var pay_amount = $('#pay_amount').val();
        //        var credit_balance = $('#credit_balance').val();
        var total_pay_amount = $('#payable_amount').val();
        //
        //        if (parseFloat(pay_amount) > parseFloat(credit_balance)) {
        //            swal("Pay amount cannot be greater than Credit Balance");
        //            $('#pay_amount').val("0");
        //        }
        //
        if (parseFloat(pay_amount) > parseFloat(total_pay_amount)) {
            swal("Pay amount cannot be greater than Pending Amount");
            $('#pay_amount').val("0");
        }
    });

    //    function calculate_discount() {
    //        var total_amount = $('#total_balance_amount').val();
    //        if (isNaN(total_amount)) total_amount = 0.0;
    //
    //        var bill_discount = $('#discount_amount').val();
    //        if (isNaN(bill_discount)) bill_discount = 0.0;
    //
    //
    //        var net_amount = +total_amount - +bill_discount;
    //        $('#payable_amount').val(DecimalPoint(net_amount));
    //    }


    function GetTotalBalanceAmount() {
        var bill_amount = new Array();
        $('input[name^="bill_amount"]').each(function () {
            bill_amount.push($(this).val());
        });

        var paid_amount = new Array();
        $('input[name^="paid_amount"]').each(function () {
            paid_amount.push($(this).val());
        });

        var balance_amount = new Array();
        $('input[name^="balance_amount"]').each(function () {
            balance_amount.push($(this).val());
        });

        var total_bill_amount = 0;
        for (var i = 0; i < bill_amount.length; i++) {
            total_bill_amount = +total_bill_amount + +bill_amount[i];
        }

        var total_paid_amount = 0;
        for (i = 0; i < paid_amount.length; i++) {
            total_paid_amount = +total_paid_amount + +paid_amount[i];
        }

        var total_balance_amount = 0;
        for (i = 0; i < balance_amount.length; i++) {
            total_balance_amount = +total_balance_amount + +balance_amount[i];
        }

        $('#total_bill_amount').val(DecimalPoint(total_bill_amount));
        $('#total_paid_amount').val(DecimalPoint(total_paid_amount));
        $('#total_balance_amount').val(DecimalPoint(total_balance_amount));
        $('#payable_amount').val(DecimalPoint(total_balance_amount));
        $('#total_pay_amount').val(DecimalPoint(total_balance_amount));
    }

    function submit_data() {

        $("#save_button").prop("disabled", true);
        //$('#loader').addClass('show');

        var customer_id = $('#customer_id').val();

        var pay_amount = $('#pay_amount').val();

        var payment_method = $('#payment_method').val();

        var reference_no = $('#reference_no').val();

        //        var discount_amount = $('#discount_amount').val();


        var bill_id = new Array();
        $('input[name^="bill_id"]').each(function () {
            bill_id.push($(this).val());
        });

        var obj = new Array();
        for (var i = 0; i < bill_id.length; i++) {
            var id = bill_id[i];

            obj[i] = id + ',' + $('#bill_type' + id).val() + ',' + $('#bill_date' + id).val() + ',' + $('#bill_no' + id).val() + ',' + $('#bill_amount' + id).val() + ',' + $('#paid_amount' + id).val() + ',' + $('#balance_amount' + id).val();

        }

        var bill_data = JSON.stringify(obj);

        $.ajax({
            type: 'POST',
            url: 'SaveCustomerBillAmount.php',
            data: {
                customer_id: customer_id,
                pay_amount: pay_amount,
                payment_method: payment_method,
                reference_no: reference_no,
                //                discount_amount: discount_amount,
                bill_data: bill_data
            },
            success: function (customer_id) {
                $("#save_button").prop("disabled", false);
                //$('#loader').removeClass('show');

                swal({
                    title: "Success",
                    text: "Payment Added Successfully!",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                },
                    function () {
                        location.href = "PatientFinance?cID=" + customer_id;
                    });
            }
        });
    }


</script>
</body>

</html>