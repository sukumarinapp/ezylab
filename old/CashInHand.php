<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, 'Patient');
$account_id = 12;
$date = date("Y-m-d");

    $start_date = date("01-m-Y");
    $end_date = date("d-m-Y");


if (isset($_POST['add_submit'])) {
    $start_date = date("d-m-Y", strtotime($_POST['startdate']));
    $end_date = date("d-m-Y", strtotime($_POST['enddate']));
}

if (isset($_POST['save_submit'])) {

    $type2 = 'Expense';
    $type = 'Income';
    $bank_account_id = 9;
    $indirect_expense_account = 11;
    $payment_method = 'Cash';

    $insert_query = Insert('macho_revenue', array(
        'account_id' => Filter($_POST['account_id']),
        'saving_account' => $bank_account_id,
        'type' => $type,
        'pay_for' => Filter($_POST['pay_for']),
        'payment_method' => $payment_method,
        'reference_no' => Filter($_POST['reference_no']),
        'amount' => Filter($_POST['amount']),
        'entry_date' => $date,
        'modified_date' => $date
    ));

    if (is_int($insert_query)) {

        Insert('macho_revenue', array(
            'account_id' => $indirect_expense_account,
            'saving_account' => $account_id,
            'type' => $type2,
            'pay_for' => Filter($_POST['pay_for']),
            'payment_method' => $payment_method,
            'reference_no' => Filter($_POST['reference_no']),
            'amount' => Filter($_POST['amount']),
            'entry_date' => $date,
            'modified_date' => $date
        ));

        $notes = 'Cash IN Hand Amount Rs.' . $_POST['amount'] . ' Transfer details added  by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="insert_success2"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div>Cash In Hand
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Cash In Hand Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Cash In Hand Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Cash In Hand Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <?php if ($PageAccessible['is_write'] == 1) { ?>
                        <div class="card-title pull-right">
                            <form action="" method="post" class="search-form">
                                <div class="btn-toolbar">
                                    <div class="form-group">
                                        <input type="text" name="startdate" id="startdate"
                                               class="form-control" data-date-format="dd-mm-yyyy"
                                               value="<?php echo $start_date; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="enddate" id="enddate"
                                               class="form-control" data-date-format="dd-mm-yyyy"
                                               value="<?php echo $end_date; ?>">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="add_submit" class="btn btn-success" title="Search">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                    <div class="text-sm"></div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="table-active" style="border-top: 1px solid #eee">
                            <th style="text-align: right" class="thead_data">Date</th>
                            <th class="thead_data">Description</th>
                            <th class="thead_data">Payment Method</th>
                            <th class="thead_data">Reference No.</th>
                            <th class="thead_data">Income</th>
                            <th class="thead_data">Expense</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $income = 0;
                        $expense = 0;
                        $start_date = to_sql_date($start_date);
                        $end_date = to_sql_date($end_date);

                        $table_row_class = array("1" => "table-primary", "2" => "table-success", "3" => "table-warning", "4" => "table-danger", "5" => "table-info", "6" => "table-active", "7" => "table-primary", "8" => "table-success", "10" => "table-warning", "11" => "table-danger");
                        $AccountArray = array('1', '2', '3', '4', '5', '6', '7', '8', '10', '11');
                        foreach ($AccountArray as $AccountID) {
                            ?>
                            <tr class="<?= $table_row_class[$AccountID]; ?>">
                                <td class="tbody_data" style="text-align: left;font-weight: bold">&nbsp;<?= AccountName($AccountID); ?></td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                            </tr>
                            <?php
                            $AccountIncome[$AccountID] = 0;
                            $AccountExpense[$AccountID] = 0;
                            $FinanceQuery = "SELECT * FROM macho_revenue WHERE account_id='$AccountID' AND saving_account='$account_id' AND entry_date>='$start_date' AND entry_date<='$end_date' ORDER BY id DESC ";
                            $FinanceResult = GetAllRows($FinanceQuery);
                            $FinanceCounts = count($FinanceResult);
                            if ($FinanceCounts > 0) {
                                foreach ($FinanceResult as $FinanceData) {
                                    ?>
                                    <tr class="<?= $table_row_class[$AccountID]; ?>">
                                        <td class="tbody_data" style="text-align: right">&nbsp;<?php echo date("d-m-Y", strtotime($FinanceData['entry_date'])); ?></td>
                                        <td class="tbody_data">&nbsp;<?php echo $FinanceData['pay_for']; ?></td>
                                        <td class="tbody_data">&nbsp;<?php echo $FinanceData['payment_method']; ?></td>
                                        <td class="tbody_data">&nbsp;<?php echo $FinanceData['reference_no']; ?></td>
                                        <?php if ($FinanceData['type'] == 'Income') {
                                            $AccountIncome[$AccountID] = $AccountIncome[$AccountID] + $FinanceData['amount'];
                                            $income = $income + $FinanceData['amount']; ?>
                                            <td class="tbody_data">&nbsp;<?php echo $FinanceData['amount']; ?></td>
                                            <td class="tbody_data">&nbsp;0.00</td>
                                        <?php } else {
                                            $AccountExpense[$AccountID] = $AccountExpense[$AccountID] + $FinanceData['amount'];
                                            $expense = $expense + $FinanceData['amount']; ?>
                                            <td class="tbody_data">&nbsp;0.00</td>
                                            <td class="tbody_data">&nbsp;<?php echo $FinanceData['amount']; ?></td>
                                        <?php } ?>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr class="<?= $table_row_class[$AccountID]; ?>">
                                    <td class="tbody_data" style="text-align: right">&nbsp;</td>
                                    <td class="tbody_data">&nbsp;</td>
                                    <td class="tbody_data">&nbsp;</td>
                                    <td class="tbody_data">&nbsp;</td>
                                    <td class="tbody_data">&nbsp;0.00</td>
                                    <td class="tbody_data">&nbsp;0.00</td>
                                </tr>
                         <?php } ?>
                            <tr class="<?= $table_row_class[$AccountID]; ?>">
                                <td class="tbody_data" style="text-align: right">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data" style="font-weight: bold">&nbsp;Total</td>
                                <td class="tbody_data" style="font-weight: bold">&nbsp;<?= ConvertMoneyFormat2($AccountIncome[$AccountID])?></td>
                                <td class="tbody_data" style="font-weight: bold">&nbsp;<?= ConvertMoneyFormat2($AccountExpense[$AccountID])?></td>
                            </tr>
                            <tr class="<?= $table_row_class[$AccountID]; ?>">
                                <td class="tbody_data" style="text-align: right">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                            </tr>
                       <?php }
                        $cash_in_hand = $income - $expense;
                        ?>
                        </tbody>
                        <tbody>
                        <tr class="table-active">
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center;font-weight: bolder;font-size: medium" class="tfoot_data">&nbsp;Net Total</td>
                            <td style="text-align: center;font-weight: bolder;font-size: medium" class="tfoot_data">&nbsp;Rs.<?php echo ConvertMoneyFormat2($income); ?></td>
                            <td style="text-align: center;font-weight: bolder;font-size: medium" class="tfoot_data">&nbsp;Rs.<?php echo ConvertMoneyFormat2($expense); ?></td>
                        </tr>
                        <tr class="table-active">
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center;font-weight: bolder;font-size: large" class="tfoot_data">&nbsp;Cash On Hand</td>
                            <td style="text-align: center;font-weight: bolder;font-size:large;color: <?php echo($cash_in_hand < 0 ? '#FF0000' : '#008d4c'); ?>" class="tfoot_data">&nbsp;Rs.<?php echo ConvertMoneyFormat2($cash_in_hand); ?></td>
                        </tr>
                        </tbody>
                    </table>
                            </div>
                    <?php if ($PageAccessible['is_read'] == 1) { ?>
                        <br><br>
                        <div class="row no-print">
                            <div class="col-md-5"></div>
                            <div class="col-md-2" style="text-align: center!important;">
                                <button class="btn btn-primary fa fa-exchange"
                                        title="Amount transfer"
                                        onclick="amount_transfer(<?= $account_id; ?>,'<?= $cash_in_hand; ?>','<?= $start_date; ?>','<?= $end_date; ?>');">
                                    &nbsp;Amount Transfer
                                </button>
                            </div>
                            <div class="col-md-5"></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php' ?>
</div>

<div class="modal fade" id="edit_modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Amount Transfer Details</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body2">
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

    var thead_data = new Array();
    $(".thead_data").each(function () {
        thead_data.push($(this).html());
    });

    var tbody_data = new Array();
    $(".tbody_data").each(function () {
        tbody_data.push($(this).html());
    });

    var tfoot_data = new Array();
    $(".tfoot_data").each(function () {
        tfoot_data.push($(this).html());
    });

    function print_data(e, title, from_date, todate) {
        e.preventDefault();
        $.redirect("Print.php",
            {
                title: title,
                from_date: from_date,
                todate: todate,
                thead_data: thead_data,
                tbody_data: tbody_data,
                tfoot_data: tfoot_data
            }, "POST", "_blank");
    }

    function pdf_data(e, title, from_date, todate) {
        e.preventDefault();

        $.redirect("PDF.php",
            {
                title: title,
                from_date: from_date,
                todate: todate,
                thead_data: thead_data,
                tbody_data: tbody_data,
                tfoot_data: tfoot_data
            }, "POST", "_blank");
    }

    function excel_data(e, title, from_date, todate) {
        e.preventDefault();

        $.redirect("Excel.php",
            {
                title: title,
                from_date: from_date,
                todate: todate,
                thead_data: thead_data,
                tbody_data: tbody_data,
                tfoot_data: tfoot_data
            }, "POST", "_blank");
    }

</script>
<script>
    $(function () {
        //Date picker
        $('#startdate').datepicker({
            autoclose: true
        });

        $('#enddate').datepicker({
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

    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function amount_transfer(account_id, account_amount, from_date, to_date) {
        $.ajax({
            type: "POST",
            url: "AmountTransferPage.php",
            data: {
                account_id: account_id,
                account_amount: account_amount,
                from_date: from_date,
                to_date: to_date
            },
            success: function (response) {
                $('#edit_body2').html(response);
                $('#edit_modal2').modal('show');
            }
        });
    }

    function check_amount(account_id) {
        var pay_amount = $('#amount2').val();
        var account_amount = $('#account_amount2').val();
        if (parseFloat(pay_amount) > parseFloat(account_amount)) {
            if (account_id == 9) {
                alert("Transfer amount cannot be greater than Bank Balance Amount");
            } else {
                alert("Transfer amount cannot be greater than Cash on Hand Amount");
            }
            $('#amount2').val("0");
        }
    }

    window.onload = function () {

        if (document.getElementById('insert_success2')) {
            swal("Success!", "Amount Transfer successfully...", "success");
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