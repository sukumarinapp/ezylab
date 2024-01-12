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


if (isset($_POST['add_submit'])) {
    $start_date = date("d-m-Y", strtotime($_POST['startdate']));
    $end_date = date("d-m-Y", strtotime($_POST['enddate']));
}
?>
<?php include ("css.php"); ?>
<title>Dashtrans</title>
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
      <div class="page-content">
      <div class="page-content">
      <div class="page-content">
        <div class="content-heading">
            <div>Capital Account
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Capital Account Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Capital Account Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Capital Account Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
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

                        $table_row_class = array("1" => "table-primary", "2" => "table-success", "3" => "table-warning", "4" => "table-danger", "5" => "table-info", "6" => "table-active", "7" => "table-primary", "8" => "table-success", "10" => "table-warning", "11" => "table-danger", "9" => "table-info");
                        $AccountArray = array('1', '2', '3', '4', '5', '6', '7', '8', '10', '11', '9');
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
                            $FinanceQuery = "SELECT * FROM macho_revenue WHERE account_id='$AccountID' AND entry_date>='$start_date' AND entry_date<='$end_date' ORDER BY id DESC ";
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
                                <td class="tbody_data" style="font-weight: bold">&nbsp;<?= ConvertMoneyFormat2($AccountIncome[$AccountID]);?></td>
                                <td class="tbody_data" style="font-weight: bold">&nbsp;<?= ConvertMoneyFormat2($AccountExpense[$AccountID]);?></td>
                            </tr>
                            <tr class="<?= $table_row_class[$AccountID]; ?>">
                                <td class="tbody_data" style="text-align: right">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                                <td class="tbody_data">&nbsp;</td>
                            </tr>
                     <?php   }
                        $cash_deposit = BankDeposit($start_date, $end_date);
                        $CashonHand = CashonHand($start_date, $end_date);
                        $cash_deposit_amount = $cash_deposit['BalanceAmount'];
                        $profit = $income - $expense;
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
                            <td style="text-align: center;font-weight: bolder;font-size: large" class="tfoot_data">&nbsp;<?= ($profit < 0 ? 'Loss Amount' : 'Profit Amount'); ?></td>
                            <td style="text-align: center;font-weight: bolder;font-size:large;color: <?php echo($profit < 0 ? '#FF0000' : '#008d4c'); ?>" class="tfoot_data">&nbsp;Rs.<?php echo ConvertMoneyFormat2($profit); ?></td>
                        </tr>
                        <tr class="table-active">
                            <td style="text-align: center;font-weight: bolder;" class="tfoot_data">&nbsp;Bank Deposit</td>
                            <td style="text-align: center;font-weight: bolder;" class="tfoot_data">&nbsp;Rs.<?= ConvertMoneyFormat($cash_deposit_amount); ?></td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td class="tfoot_data">&nbsp;</td>
                            <td style="text-align: center;font-weight: bolder;" class="tfoot_data">&nbsp;Cash On Hand</td>
                            <td style="text-align: center;font-weight: bolder;" class="tfoot_data">&nbsp;Rs.<?php echo ConvertMoneyFormat2($CashonHand); ?></td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
</script>
</body>
</html>