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

if (isset($_POST['add_submit'])) {
    $start_date = date("Y-m-d", strtotime($_POST['startdate']));
    $end_date = date("Y-m-d", strtotime($_POST['enddate']));
}
?>

<?php include ("headercss.php"); ?>
<title>Finnance Report</title>
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
	  
			
            <div>Finance Report
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Finance Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Finance Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="window.open('FinanceExcel?startdate=<?php echo $start_date; ?>&enddate=<?php echo $end_date; ?>');">
                        <i class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <?php if ($PageAccessible['is_read'] == 1) { ?>
                    <div class="card-header no-print">
                        <div class="card-title pull-right">
                            <form action="" method="post" class="search-form">
                                <div class="btn-toolbar">
                                    <div class="form-group">
                                        <input type="date" name="startdate" id="startdate"
                                               class="form-control" data-date-format="dd-mm-yyyy"
                                               value="<?php echo $start_date; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="enddate" id="enddate"
                                               class="form-control" data-date-format="dd-mm-yyyy"
                                               value="<?php echo $end_date; ?>">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="add_submit" class="btn btn-success" title="Search">
                                            <em class="fa fa-search"></em>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="text-sm"></div>
                    </div>
                <?php } ?>
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
                            <th style="text-align: center" class="thead_data">Income</th>
                            <th style="text-align: center" class="thead_data">Expense</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $income = 0;
                        $expense = 0;
                        $start_date = to_sql_date($start_date);
                        $end_date = to_sql_date($end_date);
                        $FinanceQuery = "SELECT * FROM macho_revenue WHERE entry_date>='$start_date' AND entry_date<='$end_date' ORDER BY id DESC ";
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
                                    <?php if ($FinanceData['type'] == "Income") {
                                        $income = $income + $FinanceData['amount'];
                                        ?>
                                        <td style="text-align: center" class="tbody_data">&nbsp;<?php echo $FinanceData['amount']; ?></td>
                                        <td class="tbody_data">&nbsp;</td>
                                    <?php } ?>
                                    <?php if ($FinanceData['type'] == "Expense") {
                                        $expense = $expense + $FinanceData['amount'];
                                        ?>
                                        <td class="tbody_data">&nbsp;</td>
                                        <td style="text-align: center" class="tbody_data">&nbsp;<?php echo $FinanceData['amount']; ?></td>
                                    <?php } ?>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                        <tbody>
                        <tr style="font-weight: bold">
                            <td style="text-align: center" class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center" class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center" class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center" class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center" class="tfoot_data">&nbsp;Total</td>
                            <td style="text-align: center" class="tfoot_data">&nbsp;<?php echo ($income); ?></td>
                            <td style="text-align: center" class="tfoot_data">&nbsp;<?php echo ($expense); ?></td>
                        </tr>
                        <tr style="font-weight: bold">
                            <td style="text-align: center" class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center" class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center" class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center" class="tfoot_data">&nbsp;</td>
                            <th style="text-align: center;font-weight: bolder" class="tfoot_data">&nbsp;<?= (($income - $expense) < 0 ? 'Loss Amount' : 'Profit Amount'); ?></th>
                            <td style="text-align: center" class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center;color: <?php echo(($income - $expense) < 0 ? '#FF0000' : '#00FF00'); ?>" class="tfoot_data">&nbsp;Rs.<?php echo ConvertMoneyFormat2($income - $expense); ?></td>
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