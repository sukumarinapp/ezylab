<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);
$account_id = 9;
$edit_status = 1;
$date = date("Y-m-d");

    $start_date = date("01-m-Y");
    $end_date = date("d-m-Y");


if (isset($_POST['add_submit'])) {
    $start_date = date("d-m-Y", strtotime($_POST['startdate']));
    $end_date = date("d-m-Y", strtotime($_POST['enddate']));
}

if (isset($_POST['submit'])) {

    $insert_income = Insert('macho_revenue', array(
        'account_id' => $account_id,
        'saving_account' => $account_id,
        'type' => Filter($_POST['type']),
        'pay_for' => Filter($_POST['pay_for']),
        'payment_method' => Filter($_POST['payment_method']),
        'reference_no' => Filter($_POST['reference_no']),
        'amount' => Filter($_POST['amount']),
        'edit_status' => $edit_status,
        'entry_date' => $date,
        'modified_date' => $date
    ));
    if (is_int($insert_income)) {
        if ($_POST['type'] == 'Income') {
            $notes = 'Bank Account :<br>' . $_POST['pay_for'] . ' Rs.' . $_POST['amount'] . ' credited details added by ' . $user;
        } else {
            $notes = 'Bank Account :<br>' . $_POST['pay_for'] . ' Rs.' . $_POST['amount'] . ' debited details added by ' . $user;
        }
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

if (isset($_POST['Update'])) {
    $id = Filter($_POST['id']);

    $update = Update('macho_revenue', 'id', $id, array(
        'account_id' => $account_id,
        'saving_account' => $account_id,
        'type' => Filter($_POST['type']),
        'pay_for' => Filter($_POST['pay_for']),
        'payment_method' => Filter($_POST['payment_method']),
        'reference_no' => Filter($_POST['reference_no']),
        'amount' => Filter($_POST['amount']),
        'edit_status' => $edit_status,
        'modified_date' => $date
    ));
    if ($update) {
        if ($_POST['type'] == 'Income') {
            $notes = 'Bank Account :<br>' . $_POST['pay_for'] . ' Rs.' . $_POST['amount'] . ' credited details modified by ' . $user;
        } else {
            $notes = 'Bank Account :<br>' . $_POST['pay_for'] . ' Rs.' . $_POST['amount'] . ' debited details modified by ' . $user;
        }
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="update_success"></span>';
    } else {
        echo '<span  id="update_failure"></span>';
    }
}

if (isset($_POST['save_submit'])) {

    $type2 = 'Expense';
    $type = 'Income';
    $saving_account = 12;
    $payment_method = 'Cash';

    $insert_query = Insert('macho_revenue', array(
        'account_id' => Filter($_POST['account_id']),
        'saving_account' => $saving_account,
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
            'account_id' => $account_id,
            'saving_account' => $account_id,
            'type' => $type2,
            'pay_for' => Filter($_POST['pay_for']),
            'payment_method' => $payment_method,
            'reference_no' => Filter($_POST['reference_no']),
            'amount' => Filter($_POST['amount']),
            'entry_date' => $date,
            'modified_date' => $date
        ));

        $notes = 'Bank Amount Rs.' . $_POST['amount'] . ' Transfer details added  by ' . $user;
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
            <div>Bank Account
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Bank Account Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Bank Account Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Bank Account Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
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
                        <div style="float: left!important;">
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
                        <div class="card-title pull-right">
                            <button class="btn btn-labeled btn-secondary" type="button" data-toggle="modal"
                                    data-target="#add_new">Create New
                                <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                           </span></button>
                        </div>
                    <?php } ?>
                    <div class="text-sm"></div>
                </div>

                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="datatable1">
                        <thead>
                        <tr>
                            <th width="20px" class="thead_data">#</th>
                            <th class="thead_data">Date</th>
                            <th class="thead_data">Description</th>
                            <th class="thead_data">Payment Method</th>
                            <th class="thead_data">Reference No.</th>
                            <th class="thead_data">Credit</th>
                            <th class="thead_data">Debit</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $income = 0;
                        $expense = 0;
                        $start_date = to_sql_date($start_date);
                        $end_date = to_sql_date($end_date);
                        $FinanceQuery = "SELECT * FROM macho_revenue WHERE (account_id='$account_id' OR saving_account='$account_id') AND entry_date>='$start_date' AND entry_date<='$end_date' ORDER BY id DESC ";
                        $FinanceResult = GetAllRows($FinanceQuery);
                        $FinanceCounts = count($FinanceResult);
                        if ($FinanceCounts > 0) {
                            foreach ($FinanceResult as $FinanceData) {
                                ?>
                                <tr>
                                    <td class="tbody_data"><?php echo ++$no; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo date("d-m-Y", strtotime($FinanceData['entry_date'])); ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $FinanceData['pay_for']; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $FinanceData['payment_method']; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $FinanceData['reference_no']; ?></td>
                                    <?php if ($FinanceData['type'] == 'Income') {
                                        $income = $income + $FinanceData['amount']; ?>
                                        <td class="tbody_data">&nbsp;<?php echo $FinanceData['amount']; ?></td>
                                        <td class="tbody_data">&nbsp;0.00</td>
                                    <?php } else {
                                        $expense = $expense + $FinanceData['amount']; ?>
                                        <td class="tbody_data">&nbsp;0.00</td>
                                        <td class="tbody_data">&nbsp;<?php echo $FinanceData['amount']; ?></td>
                                    <?php } ?>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($FinanceData['account_id'] == $account_id) {
                                                if ($FinanceData['edit_status'] == 1) {
                                                    if ($PageAccessible['is_modify'] == 1) { ?>
                                                        <button class="btn btn-info" type="button" title="Edit"
                                                                onclick="ModalEdit(<?php echo $FinanceData['id']; ?>);">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_delete'] == 1) { ?>
                                                        <button class="btn btn-danger" type="button" title="Delete"
                                                                onclick="Delete('macho_revenue','id',<?php echo $FinanceData['id']; ?>);">
                                                            <i class="fa fa-trash-o"></i>
                                                        </button>
                                                    <?php }
                                                }
                                            } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                        <tbody>
                        <tr style="font-weight: bold">
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;Total</td>
                            <td class="tfoot_data">&nbsp;Rs.<?php echo ConvertMoneyFormat2($income); ?></td>
                            <td class="tfoot_data">&nbsp;Rs.<?php echo ConvertMoneyFormat2($expense); ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr style="font-weight: bold">
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;Total Balance Amount</td>
                            <td class="tfoot_data">&nbsp;Rs.<?php echo ConvertMoneyFormat2($income - $expense); ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>
                    <?php if ($PageAccessible['is_read'] == 1) { ?>
                        <br>
                        <div class="row no-print">
                            <div class="col-md-5"></div>
                            <div class="col-md-2" style="text-align: center!important;">
                                <button class="btn btn-primary fa fa-exchange"
                                        title="Amount transfer"
                                        onclick="amount_transfer(<?= $account_id; ?>,'<?= $income - $expense; ?>','<?= $start_date; ?>','<?= $end_date; ?>');">
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
<div class="modal fade" id="add_new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add New Entry</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="BankAccount">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="col-form-label">Payment Type</label>
                                        <select name="type" id="type" class="form-control"
                                                tabindex="1">
                                            <option value="Income">Credit</option>
                                            <option value="Expense">Debit</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Description</label>
                                        <input type="text" name="pay_for" id="pay_for" class="form-control"
                                               maxlength="200"
                                               tabindex="2"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="form-control"
                                                tabindex="3">
                                            <option value="Cash">Cash</option>
                                            <option value="Credit Card">Credit Card</option>
                                            <option value="Debit Card">Debit Card</option>
                                            <option value="Online Payment">Online Payment</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Demand Draft">Demand Draft</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Reference No.</label>
                                        <input type="text" name="reference_no" id="reference_no" class="form-control"
                                               maxlength="100"
                                               tabindex="4"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control"
                                               onkeypress="return isNumberDecimalKey(event)" tabindex="5"/>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-primary" type="submit" name="submit" tabindex="5">
                                                Save
                                            </button>
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END card-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update Entry Details</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
        </div>
    </div>
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

    function ModalEdit(id) {
        $.ajax({
            type: "POST",
            url: "EditBankAccount.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_modal').modal('show');
            }
        });
    }

    function Delete(table, key, id) {
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Entry!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'Yes!',
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: "Delete.php",
                        data: {
                            table: table,
                            key: key,
                            id: id
                        },
                        success: function (response) {
                            if (response == '1') {
                                swal("Deleted!", "Selected  Data has been deleted!", "success");
                                location.href = "BankAccount";
                            } else {
                                swal({
                                    title: "Oops!",
                                    text: "Something Wrong...",
                                    imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
                                });
                            }
                        }
                    });

                } else {
                    swal("Cancelled", "Your Entry Data is safe :)", "error");
                }
            });
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

        if (document.getElementById('insert_success')) {
            swal("Success!", "New Entry Details has been Added!", "success");
        }

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

        if (document.getElementById('update_success')) {
            swal("Success!", "Selected Entry has been Updated!", "success");
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