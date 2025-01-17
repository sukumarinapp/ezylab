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
$date = date("Y-m-d");
$type = 'Expense';
$account_id = 3;
$edit_status = 1;

$start_date = date("Y-m-01");
$end_date = date("Y-m-d");

if (isset($_POST['add_submit'])) {
    $start_date = date("Y-m-d", strtotime($_POST['startdate']));
    $end_date = date("Y-m-d", strtotime($_POST['enddate']));
}

$theme = "SELECT * FROM macho_users WHERE id ='$user_id'";
$TestTypeResult = mysqli_query($GLOBALS['conn'], $theme) or die(mysqli_error($GLOBALS['conn']));
$TestTypeData = mysqli_fetch_assoc($TestTypeResult);
$colour = $TestTypeData['colour'];
?>

<!doctype html>
<html lang="en">

<head>

<?php include ("headercss.php"); ?>
<title>Expense</title>
</head>
<body class="bg-theme bg-<?php echo $colour ?>">
    <?php
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
    )
    );
    if (is_int($insert_income)) {

        $notes = 'Expense :<br>' . $_POST['pay_for'] . 'Rs.' . $_POST['amount'] . ' Amount added by ' . $user;
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
    )
    );
    if ($update) {

        $notes = 'Expense :<br>' . $_POST['pay_for'] . 'Rs.' . $_POST['amount'] . ' Amount modified by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="update_success"></span>';
    } else {
        echo '<span  id="update_failure"></span>';
    }
}
    ?>
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
	  
            <div>Expense
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                        onclick="print_data(event,'Expense Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                        onclick="pdf_data(event,'Expense Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                        onclick="excel_data(event,'Expense Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-file-excel-o"></i> Excel
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
                                        <input type="date" name="startdate" id="startdate" class="form-control"
                                            data-date-format="dd-mm-yyyy" value="<?php echo $start_date; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="enddate" id="enddate" class="form-control"
                                            data-date-format="dd-mm-yyyy" value="<?php echo $end_date; ?>">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="add_submit" class="btn btn-success" title="Search">
                                            <em class="fa fa-search"></em>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-title pull-right">
                            <button class="btn btn-labeled btn-secondary" type="button" data-bs-toggle="modal"
                                data-bs-target="#add_expense">Create New
                                <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                                </span></button>
                        </div>
                    <?php } ?>
                    <div class="text-sm"></div>
                </div>

                <div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example2" class="table table-striped table-bordered" style="width:100%">
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
                                        <td class="tbody_data">
                                            <?php echo ++$no; ?>
                                        </td>
                                        <td class="tbody_data">&nbsp;
                                            <?php echo date("d-m-Y", strtotime($FinanceData['entry_date'])); ?>
                                        </td>
                                        <td class="tbody_data">&nbsp;
                                            <?php echo $FinanceData['pay_for']; ?>
                                        </td>
                                        <td class="tbody_data">&nbsp;
                                            <?php echo $FinanceData['payment_method']; ?>
                                        </td>
                                        <td class="tbody_data">&nbsp;
                                            <?php echo $FinanceData['reference_no']; ?>
                                        </td>
                                        <td class="tbody_data">&nbsp;
                                            <?php echo $FinanceData['amount']; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if ($FinanceData['edit_status'] == 1) {
                                                    if ($PageAccessible['is_modify'] == 1) { ?>
                                                        <button class="btn btn-info" type="button" title="Edit"
                                                            onclick="ModalEdit(<?php echo $FinanceData['id']; ?>);">
                                                            <em class="fa fa-edit"></em>
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_delete'] == 1) { ?>
                                                        <button class="btn btn-danger" type="button" title="Delete"
                                                            onclick="Delete('macho_revenue','id',<?php echo $FinanceData['id']; ?>);">
                                                            <em class="fa fa-trash"></em>
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
                                <td class="tfoot_data">&nbsp;Rs.
                                    <?php echo ConvertMoneyFormat2($total); ?>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                        </div>
                        </div>
            </div>
        </div>
    </div>
</section>
</div>
<div class="modal fade" id="add_expense" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add Expense Details</h4>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="Expense">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="col-form-label">Description</label>
                                        <input type="text" name="pay_for" id="pay_for" class="form-control"
                                            maxlength="200" tabindex="1" />
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="form-control"
                                            tabindex="2">
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
                                            maxlength="100" tabindex="3" />
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control"
                                            onkeypress="return isNumberDecimalKey(event)" tabindex="4" />
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-primary" type="submit" name="submit" tabindex="5">
                                                Save
                                            </button>
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
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
<div class="modal fade" id="edit_expense" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update Expense Details</h4>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
        </div>
    </div>
</div>	
</div>

   <?php include ("js.php"); ?>
	<script>
		$(document).ready(function() {
			$('#Transaction-History').DataTable({
				lengthMenu: [[6, 10, 20, -1], [6, 10, 20, 'Todos']]
			});
		  } );
	</script>
	<script src="assets/js/index.js"></script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script>
		new PerfectScrollbar('.product-list');
		new PerfectScrollbar('.customers-list');
	</script>

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
            url: "EditExpense.php",
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
            title: 'Are you sure?',
            text: "You will not be able to recover this Entry!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then(function(result) {
            if(result.value){
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
                            swal("Deleted!", "Selected Data has been deleted!", "success");
                            location.href = "Expense";
                        } else {
                            swal({
                                title: "Oops!",
                                text: "Something Wrong...",
                                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
                            });
                        }
                    }
                });
            }else{
                swal("Cancelled", "Your Entry Data is safe :)", "error");
            }
        })
    }
   

    window.onload = function () {

        if (document.getElementById('insert_success')) {
            swal("Success!", "New Expense Details has been Added!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "Expense has been Updated!", "success");
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