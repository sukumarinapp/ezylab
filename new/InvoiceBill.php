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
$start_date = date("Y-m-01");
$end_date = date("Y-m-d");

if (isset($_POST['search'])) {
    $start_date = date("Y-m-d", strtotime($_POST['startdate']));
    $end_date = date("Y-m-d", strtotime($_POST['enddate']));
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
<?php include ("headercss.php"); ?>
<title>IP Block List</title>
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
	  
	  
	  
            <h6>Invoice Bill</h6>
        </div>
        <!-- start  -->
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <?php if ($PageAccessible['is_write'] == 1) { ?>
                                    <button type="button" class="btn btn-sm btn-white" title="New Entry"
                                        onClick="location.href='AddBill';"><i class="fa fa-plus"></i>
                                        New Entry
                                    </button>
                                <?php } ?>
                            </div>
                            <div class="col-md-10">
                                <?php if ($PageAccessible['is_read'] == 1) { ?>
                                    <form class="form mt-4 mt-lg-0" method="post" action="">
                                        <table class="table table-borderless">

                                            <thead>
                                                <tr>
                                                    <th>From Date</th>
                                                    <th><input type="date" class="form-control" id="startdate"
                                                            name="startdate" value="<?= $start_date; ?>"></th>
                                                    <th>To Date</th>
                                                    <th><input type="date" class="form-control" id="enddate" name="enddate"
                                                            max="<?= date("Y-m-d"); ?>" value="<?= $end_date; ?>">
                                                    </th>
                                                    <th>
                                                        <button type="submit" name="search" class="btn btn-primary"><em
                                                                class="fa fa-search"></em></button>
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
                            <table id="example2" class="table table-striped my-4 w-100" id="datatable2">
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
                                                                onclick="PrintBill(<?= $BillData['id']; ?>);" title="POS Receipt"><em
                                                                    class="fa fa fa-copy"></em>
                                                            </button>
                                                            <button class="btn btn-success"
                                                                onClick="window.open('InvoicePDF?bID=<?= EncodeVariable($BillData['id']); ?>');"
                                                                title="View"><em class="fa fa-eye"></em>
                                                            </button>
                                                        <?php }
                                                        if ($PageAccessible['is_delete'] == 1) { ?>
                                                            <button class="btn btn-danger" title="Delete"
                                                                onclick="Delete(<?= $BillData['id']; ?>,'<?= $BillData['billnum']; ?>');">
                                                                <em class="fa fa-trash"></em></button>
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

     function Delete(id,bill_no ) {
        swal({
            title: 'Are you sure?',
            text: "You will not be able to recover this Customer Entry!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then(function(result) {
            if(result.value){
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
                                    window.location.href = 'InvoiceBill';
                            } else {
                                swal({
                                    title: "Oops!",
                                    text: "Something Wrong...",
                                    type: "error"
                                });
                            }
                        }
                });
            }else{
                swal("Cancelled", "Your Entry Data is safe :)", "error");
            }
        })
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