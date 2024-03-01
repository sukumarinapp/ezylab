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
$start_date = date("01-m-Y");
$end_date = date("d-m-Y");

if (isset($_POST['search'])) {
    $start_date = date("d-m-Y", strtotime($_POST['startdate']));
    $end_date = date("d-m-Y", strtotime($_POST['enddate']));
}
?>
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #section-to-print,
        #section-to-print * {
            visibility: visible;
        }

        #section-to-print {
            position: absolute;
            left: 0;
            top: 0;
        }

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>
<?php include ("css.php"); ?>
<title>Billing</title>
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
	  
            <div>Billing
                <small></small>
            </div>
        </div>
        <!-- start  -->
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if ($PageAccessible['is_write'] == 1) { ?>
                                    <button type="button" class="btn btn-sm btn-white" title="New Entry"
                                        onClick="location.href='AddCustomerBill';"><i class="fa fa-plus"></i>
                                        New Entry
                                    </button>
                                <?php } ?>
                            </div>
                            <div class="col-md-6">
                                <?php if ($PageAccessible['is_read'] == 1) { ?>
                                    <form class="form mt-4 mt-lg-0" method="post" action="">
                                        <table class="table table-borderless">

                                            <thead>
                                                <tr>
                                                    <th><input type="text" class="form-control" id="startdate"
                                                            name="startdate" value="<?= $start_date; ?>"></th>
                                                    <th>to</th>
                                                    <th><input type="text" class="form-control" id="enddate" name="enddate"
                                                            max="<?= date("d-m-Y"); ?>" value="<?= $end_date; ?>">
                                                    </th>
                                                    <th>
                                                        <button type="submit" name="search" class="btn btn-primary"><i
                                                                class="fa fa-search"></i></button>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <!-- form-group -->

                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped my-4 w-100" id="datatable2">
                                <thead>
                                    <tr>
                                        <th width="20">#</th>
                                        <th>Date</th>
                                        <th>Bill No.</th>
                                        <th>Patient Name</th>
                                        <th>Patient ID</th>
                                        <th>Doctor</th>
                                        <th>Total Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 0;
                                    $start_date = to_sql_date($start_date);
                                    $end_date = to_sql_date($end_date);
                                    $BillQuery = "SELECT * FROM macho_billing WHERE bill_date>='$start_date' AND bill_date<='$end_date' ORDER BY id DESC ";
                                    $BillResult = GetAllRows($BillQuery);
                                    $BillCounts = count($BillResult);
                                    if ($BillCounts > 0) {
                                        foreach ($BillResult as $BillData) { ?>
                                            <tr>
                                                <td width="20">
                                                    <?= ++$no; ?>
                                                </td>
                                                <td>
                                                    <?= from_sql_date($BillData['bill_date']); ?>
                                                </td>
                                                <td>
                                                    <?= $BillData['billnum']; ?>
                                                </td>
                                                <td>
                                                    <?= CustomerName($BillData['customer_id']); ?>
                                                </td>
                                                <td>
                                                    <?= $BillData['net_total']; ?>
                                                </td>
                                                <td>
                                                    <?= $BillData['p_balance_amount']; ?>
                                                </td>
                                                <td>
                                                    <?= $BillData['t_balance_amount']; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <?php
                                                        if ($PageAccessible['is_modify'] == 1) { ?>
                                                            <!--                                                                <button class="btn btn-success"-->
                                                            <!--                                                                        onClick="window.open('CustomerBillPDF?cID=<? //= EncodeVariable($BillData['id']); ?>//');"
//                                                                        title="View"><i class="fa fa-search-plus"></i>
//                                                                </button>-->
                                                            <button class="btn btn-success"
                                                                onClick="PrintCustomerBill(<?= $BillData['id']; ?>);"
                                                                title="View"><i class="fa fa-search-plus"></i>
                                                            </button>
                                                            <!--                                                                <button type="button" title="Customer Bill Details"-->
                                                            <!--                                                                        onclick="window.open('bill?id=<? //= EncodeVariable($BillData['id']); ?>//');"
//                                                                        class="btn btn-sm btn-info"><em
//                                                                        class="fa fa-print"></em></button>-->
                                                        <?php }
                                                        if ($PageAccessible['is_delete'] == 1) { ?>
                                                            <button class="btn btn-danger" title="Delete"
                                                                onclick="Delete(<?= $BillData['id']; ?>,'<?= $BillData['bill_no']; ?>');">
                                                                <i class="fa fa-trash-o"></i></button>
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
<?php include_once 'footer.php' ?>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 text-center" id="section-to-print">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input onclick="window.print()" class="btn btn-success" type="submit" name="proceed" value="Print" />
                <button type="button" class="btn btn-success" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
</div>
	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
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
</script>
<script>

    function Delete(id, bill_no) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Customer Entry!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: "DeleteCustomerEntry.php",
                        data: {
                            id: id,
                            bill_no: bill_no
                        },
                        success: function (response) {
                            if (response == '1') {
                                swal("Deleted!", "Selected Customer Entry Data has been deleted!", "success");
                                setTimeout(function () {
                                    window.location.href = 'CustomerBill';
                                }, 5000);
                            } else {
                                swal({
                                    title: "Oops!",
                                    text: "Something Wrong...",
                                    type: "error"
                                });
                            }
                        }
                    });

                } else {
                    swal("Cancelled", "Your Entry Data is safe :)", "error");
                }
            });
    }

    function PrintCustomerBill(id) {
        $.ajax({
            url: "bill2.php",
            type: "get",
            data: { cID: id },
            success: function (html) {
                $('#section-to-print').html(html);
                $('#myModal').modal('show');
            }
        });
    }

    $('#myModal').on('hidden.bs.modal', function () {
        window.location.href = "CustomerBill";
    });
</script>
</body>

</html>