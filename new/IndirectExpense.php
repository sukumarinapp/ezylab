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

$PageAccessible = IsPageAccessible($user_id, $page);
$date = date("Y-m-d");
$type = 'Expense';
$account_id = 11;
$edit_status = 1;

$start_date = date("01-m-Y");
$end_date = date("d-m-Y");

if (isset($_POST['add_submit'])) {
    $start_date = date("d-m-Y", strtotime($_POST['startdate']));
    $end_date = date("d-m-Y", strtotime($_POST['enddate']));
}

if (isset($_POST['submit'])) {

    if ($_POST['payment_method'] == 'Cash') {
        $saving_account = 12;
    } else {
        $saving_account = 9;
    }

    $insert_income = Insert('macho_revenue', array(
        'account_id' => $account_id,
        'saving_account' => $saving_account,
        'type' => $type,
        'pay_for' => Filter($_POST['pay_for']),
        'payment_method' => Filter($_POST['payment_method']),
        'reference_no' => Filter($_POST['reference_no']),
        'amount' => Filter($_POST['amount']),
        'edit_status' => $edit_status,
        'entry_date' => $date,
        'modified_date' => $date
    ));
    if (is_int($insert_income)) {

        $notes = 'Indirect Expense :<br>'.$_POST['pay_for'].'Rs.' . $_POST['amount'] . ' Amount added by ' . $user;
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

    if ($_POST['payment_method'] == 'Cash') {
        $saving_account = 12;
    } else {
        $saving_account = 9;
    }

    $update = Update('macho_revenue', 'id', $id, array(
        'account_id' => $account_id,
        'saving_account' => $saving_account,
        'type' => $type,
        'pay_for' => Filter($_POST['pay_for']),
        'payment_method' => Filter($_POST['payment_method']),
        'reference_no' => Filter($_POST['reference_no']),
        'amount' => Filter($_POST['amount']),
        'edit_status' => $edit_status,
        'modified_date' => $date
    ));
    if ($update) {

        $notes = 'Indirect Expense :<br>'.$_POST['pay_for'].'Rs.' . $_POST['amount'] . ' Amount modified by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="update_success"></span>';
    } else {
        echo '<span  id="update_failure"></span>';
    }
}
?><?php include ("css.php"); ?>
<title>Indirect Expense</title>
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
            <div>Indirect Expense
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Indirect Expense Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Indirect Expense Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Indirect Expense Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
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
                                    data-target="#add_expense">Create New
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
                            <th class="thead_data">Amount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $total = 0;
                        $start_date = to_sql_date($start_date);
                        $end_date = to_sql_date($end_date);
                        $FinanceQuery = "SELECT * FROM macho_revenue WHERE account_id='$account_id' AND type='$type' AND entry_date>='$start_date' AND entry_date<='$end_date' ORDER BY id DESC ";
                        $FinanceResult = GetAllRows($FinanceQuery);
                        $FinanceCounts = count($FinanceResult);
                        if ($FinanceCounts > 0) {
                            foreach ($FinanceResult as $FinanceData) {
                                $total = $total + $FinanceData['amount'];
                                ?>
                                <tr>
                                    <td class="tbody_data"><?php echo ++$no; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo date("d-m-Y", strtotime($FinanceData['entry_date'])); ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $FinanceData['pay_for']; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $FinanceData['payment_method']; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $FinanceData['reference_no']; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $FinanceData['amount']; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($FinanceData['edit_status'] == 1) {
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
                            <td class="tfoot_data">&nbsp;Rs.<?php echo ConvertMoneyFormat2($total); ?></td>
                            <td>&nbsp;</td>
                        </tr>
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
<div class="modal fade" id="add_expense" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add Indirect Expense Details</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="IndirectExpense">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="col-form-label">Description</label>
                                        <input type="text" name="pay_for" id="pay_for" class="form-control"
                                               maxlength="200"
                                               tabindex="1"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="form-control" tabindex="2">
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
                                               tabindex="3"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control"
                                               onkeypress="return isNumberDecimalKey(event)" tabindex="4"/>
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
<div class="modal fade" id="edit_expense" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update Indirect Expense Details</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
        </div>
    </div>
</div>
	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
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
            url: "EditIndirectExpense.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_expense').modal('show');
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
                                location.href = "IndirectExpense";
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

    window.onload = function () {

        if (document.getElementById('insert_success')) {
            swal("Success!", "New Indirect Expense Details has been Added!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "Indirect Expense has been Updated!", "success");
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