<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
$USERID = DecodeVariable($_GET['uId']);
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div><?= UserName($USERID); ?>
                <small></small>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped my-4 w-100">
                        <thead>
                        <tr>
                            <th width="20px"><input type="checkbox" id="select_all"
                                                    name="select_all"
                                                    title="Select All"
                                                    onclick="CheckAll();"></th>
                            <th>Revenue Date</th>
                            <th>Description</th>
                            <th class="text-center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $RevenueQuery = "SELECT * FROM macho_staff_revenue WHERE user_id='$USERID' AND paid_status='0' ORDER BY id DESC ";
                        $RevenueResult = GetAllRows($RevenueQuery);
                        $RevenueCounts = count($RevenueResult);
                        if ($RevenueCounts > 0) {
                            foreach ($RevenueResult as $RevenueData) {
                                $revenue_id = $RevenueData['id'];
                                ?>
                                <tr>
                                    <td><input type="checkbox" class="chk"
                                               id="revenue_id<?= $revenue_id; ?>" name="revenue_id"
                                               value="<?= $RevenueData['id']; ?>"
                                               onclick="set_fix();"></td>
                                    <td><input type="text" class="form-control"
                                               id="revenue_date<?= $revenue_id; ?>"
                                               name="revenue_date"
                                               value="<?= from_sql_date($RevenueData['revenue_date']); ?>"
                                               disabled></td>
                                    <td><input type="text" class="form-control"
                                               id="revenue_description<?= $revenue_id; ?>"
                                               name="revenue_description"
                                               value="<?= $RevenueData['description']; ?>"
                                               disabled></td>
                                    <td><input type="text" class="form-control"
                                               id="revenue_amount<?= $revenue_id; ?>"
                                               name="revenue_amount"
                                               value="<?= $RevenueData['amount']; ?>"
                                               disabled></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td class="text-center" colspan="4"> No Data Available ... </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Earning Amount</label>
                                <input type="text" class="form-control" name="earning_amount" id="earning_amount"
                                       value="<?php echo GetUserEarningRevenue($USERID); ?>" disabled tabindex="1">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Balance Amount</label>
                                <input type="text" class="form-control" name="balance_amount" id="balance_amount"
                                       value="<?php echo GetUserPendingRevenue($USERID); ?>" disabled tabindex="3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Received Amount</label>
                                <input type="text" class="form-control" name="received_amount" id="received_amount"
                                       value="<?php echo GetUserReceivedRevenue($USERID); ?>" disabled tabindex="2">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Pay Amount</label>
                                <input type="text" class="form-control" name="pay_amount" id="pay_amount"
                                       value="" disabled tabindex="4">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="payment_tab" style="display: none">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-control">
                                    <option value="Cash">Cash</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Debit Card">Debit Card</option>
                                    <option value="Online Payment">Online Payment</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Demand Draft">Demand Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Reference No.</label>
                                <input type="text" class="form-control" name="reference_no" id="reference_no"
                                       value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                        <div class="float-right">
                            <button type="button" class="btn btn-default"
                                    onClick="document.location.href='StaffRevenue';">Cancel
                            </button>
                            <button class="btn btn-primary" type="button" id="save_button"
                                    onclick="form_submit(<?= $USERID; ?>);">Pay Now
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
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
<script>
    function CheckAll() {
        if ($('#select_all').prop("checked")) {
            $('.chk').prop('checked', true);
        } else {
            $('.chk').prop('checked', false);
        }

        set_fix();
    }

    function set_fix() {

        var revenue_id = new Array();
        $(".chk:checked").each(function () {
            revenue_id.push($(this).val());
        });

        var total_amount = 0;
        for (var i = 0; i < revenue_id.length; i++) {
            var id = revenue_id[i];
            total_amount = +total_amount + +$('#revenue_amount' + id).val();
        }

        $('#pay_amount').val(total_amount);

        if ($('#pay_amount').val() << 0) {
            $('#payment_tab').show();
        } else {
            $('#payment_tab').hide();
        }
    }

    function form_submit(user_id) {

        var pay_amount = $('#pay_amount').val();

        if (pay_amount <= 0) {
            swal("Pay Amount should be greater than zero");
            return;
        }

        var payment_method = $('#payment_method').val();

        var reference_no = $('#reference_no').val();

        var revenue_id = new Array();
        $(".chk:checked").each(function () {
            revenue_id.push($(this).val());
        });

        var obj = new Array();
        for (var i = 0; i < revenue_id.length; i++) {
            var id = revenue_id[i];

            obj[i] = id + ',' + $('#revenue_amount' + id).val();

        }

        var save_data = JSON.stringify(obj);

        $('#save_button').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: "saveUserPayment.php",
            data: {
                user_id: user_id,
                payment_method: payment_method,
                reference_no: reference_no,
                pay_amount: pay_amount,
                save_data: save_data
            },
            success: function (response) {

                if (response == '1') {
                    swal({
                            title: "Success",
                            text: "Amount has been has Paid Successfully!",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonClass: "btn-success",
                            confirmButtonText: "OK",
                            closeOnConfirm: false
                        },
                        function () {
                            location.href = "StaffRevenue";
                        });
                } else {
                    swal({
                        title: "Oops!",
                        text: response,
                        imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
                    });
                    $('#save_button').prop('disabled', false);
                }
            }
        });
    }
</script>
</body>
</html>