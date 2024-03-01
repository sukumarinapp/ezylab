<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
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
<!-- Main section-->
<section class="section-container no-print">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div>Invoice Bill
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
                                        onClick="location.href='AddBill';"><i class="fa fa-plus"></i>
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
                                        <th>Patient ID</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Amount</th>
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
                                        foreach ($BillResult as $BillData) {
                                            $patient_id = $BillData['patient_id'];
                                            $PatientInfo = SelectParticularRow('macho_patient', 'id', $patient_id);

                                            if ($BillData['ref_prefix'] == 'Dr.') {
                                                $reference = DoctorName($BillData['reference']);
                                            } else {
                                                $reference = $BillData['reference'];
                                            }
                                            ?>
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
                                                    <?= $PatientInfo['P_code']; ?>
                                                </td>
                                                <td>
                                                    <?= $PatientInfo['P_name']; ?>
                                                </td>
                                                <td>
                                                    <?= $reference; ?>
                                                </td>
                                                <td>
                                                    <?= $BillData['net_amount']; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <?php
                                                        if ($PageAccessible['is_modify'] == 1) { ?>
                                                            <button class="btn btn-info"
                                                                onclick="PrintBill(<?= $BillData['id']; ?>);" title="POS Receipt"><i
                                                                    class="fa fa fa-copy"></i>
                                                            </button>
                                                            <button class="btn btn-success"
                                                                onClick="window.open('InvoicePDF?bID=<?= EncodeVariable($BillData['id']); ?>');"
                                                                title="View"><i class="fa fa-search-plus"></i>
                                                            </button>
                                                        <?php }
                                                        if ($PageAccessible['is_delete'] == 1) { ?>
                                                            <button class="btn btn-danger" title="Delete"
                                                                onclick="Delete(<?= $BillData['id']; ?>,'<?= $BillData['billnum']; ?>');">
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
                        url: "DeleteBill.php",
                        data: {
                            id: id,
                            bill_no: bill_no
                        },
                        success: function (response) {
                            if (response == '1') {
                                swal("Deleted!", "Selected Bill Data has been deleted!", "success");
                                setTimeout(function () {
                                    window.location.href = 'InvoiceBill';
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

    function PrintBill(id) {

        $.ajax({
            type: 'POST',
            url: 'PrintReceiptData.php',
            data: {
                id: id
            },
            success: function (response) {
                $.ajax({
                    type: 'POST',
                    url: 'http://localhost/lims/POSReceipt.php',
                    data: {
                        print_data: response
                    },
                    success: function (data) {
                    }
                });

                location.href = "InvoiceBill";
            }
        });
    }
</script>
</body>

</html>