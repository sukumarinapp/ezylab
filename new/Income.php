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
$type = 'Income';
$account_id = 2;
$edit_status = 1;

$start_date = date("Y-m-01");
$end_date = date("Y-m-d");
$reference="Self";
$startdate = date("Y-m-d", strtotime(date("Y-m-01")));
$enddate = date("Y-m-d", strtotime(date("Y-m-d")));
if (isset($_POST['add_submit'])) {
    $start_date = date("Y-m-d", strtotime($_POST['startdate']));
    $end_date = date("Y-m-d", strtotime($_POST['enddate']));
    $reference = $_POST['reference'];
    $startdate = date("Y-m-d", strtotime($_POST['startdate']));
    $enddate = date("Y-m-d", strtotime($_POST['enddate']));
}

?>
<?php include ("headercss.php"); ?>
<title>Income By Reference</title>
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
            <div>Income By Reference
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                        onclick="print_data(event,'Income Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                        onclick="pdf_data(event,'Income Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                        onclick="excel_data(event,'Income Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
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
                                    <select name="reference" class="form-control">
                                        <option value="0">All</option>
                                        <option value="Self">Self</option>
                                        <?php
                                        $sql = "SELECT id,d_name FROM doctors  ORDER BY d_name";
                                        $result = GetAllRows($sql);
                                        if (count($result) > 0) {
                                            foreach ($result as $data) {
                                                echo "<option ";
                                                if($reference == $data['id']) echo " selected ";
                                                echo " value='".$data['id']."'>".$data['d_name']."</opton>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="add_submit" class="btn btn-success" title="Search">
                                            <em class="fa fa-search"></em>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                    <div class="text-sm"></div>
                </div>

                <div class="card-body">
                    <table id="example2" class="table table-striped my-4 w-100" id="datatable6">
                        <thead>
                            <tr>
                                <th width="20px" class="thead_data">#</th>
                                <th class="thead_data">Date</th>
                                <th class="thead_data">Patient</th>
                                <th class="thead_data">Bill No</th>
                                <th class="thead_data">Payment Method</th>
                                <?php if($reference == "0"){ ?>
                                <th class="thead_data">Referred By</th>
                                <?php } ?>
                                <th class="thead_data">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $total = 0;
                            if($reference == 0){
                                $FinanceQuery = "SELECT a.*,b.P_name,'Self' as d_name FROM patient_entry a,macho_patient b WHERE a.patient_id=b.id ";
                                $FinanceQuery .= " and a.reference='Self'";
                                $FinanceQuery .= " AND entry_date>='$startdate' AND entry_date<='$enddate'";
                                $FinanceQuery .= " UNION "; 
                                $FinanceQuery .= "SELECT a.*,b.P_name,c.d_name FROM patient_entry a,macho_patient b,doctors c WHERE a.reference=c.id and a.patient_id=b.id ";
                                $FinanceQuery .= " AND entry_date>='$startdate' AND entry_date<='$enddate'";
                                $FinanceQuery .= " ORDER BY id DESC ";
                            }else{
                                $FinanceQuery = "SELECT a.*,b.P_name FROM patient_entry a,macho_patient b WHERE a.patient_id=b.id ";
                                if($reference != "0") $FinanceQuery .= " and a.reference='$reference'";
                                $FinanceQuery .= " AND entry_date>='$startdate' AND entry_date<='$enddate' ORDER BY id DESC ";
                            }
                            $FinanceResult = GetAllRows($FinanceQuery);
                            $FinanceCounts = count($FinanceResult);
                            if ($FinanceCounts > 0) {
                                foreach ($FinanceResult as $FinanceData) {
                                    $total = $total + $FinanceData['total_amount'];
                                    ?>
                                    <tr>
                                        <td class="tbody_data"><?php echo ++$no; ?></td>
                                        <td class="tbody_data"><?php echo date("d-m-Y", strtotime($FinanceData['entry_date'])); ?></td>
                                        <td class="tbody_data"><?php echo $FinanceData['P_name']; ?></td>
                                        <td class="tbody_data"><?php echo $FinanceData['bill_no']; ?></td>
                                        <td class="tbody_data"><?php echo $FinanceData['payment_method']; ?></td>
                                        <?php if($reference == "0"){ ?>
                                            <th class="tbody_data"><?php echo $FinanceData['d_name']; ?></th>
                                        <?php } ?>
                                        <td class="tbody_data"><?php echo $FinanceData['total_amount']; ?></td>
                                    </tr>
                                <?php }
                            } ?>
                        </tbody>
                        <tbody>
                            <tr style="font-weight: bold">
                                <td class="tfoot_data"></td>
                                <td class="tfoot_data"></td>
                                <td class="tfoot_data"></td>
                                <td class="tfoot_data"></td>
                                <td class="tfoot_data">Total:</td>
                                <td class="tfoot_data">Rs.<?php echo ConvertMoneyFormat2($total); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<div class="modal fade" id="add_income" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add Income Details</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="Income">
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
<div class="modal fade" id="edit_income" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update Income Details</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
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

        $('#datatable666').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
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
            url: "EditIncome.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_income').modal('show');
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
                                location.href = "Income";
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
            swal("Success!", "New Income Details has been Added!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "Income has been Updated!", "success");
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