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

$start_date = FirstRowDate('macho_revenue', 'id', 'entry_date');
$end_date = date("d-m-Y");

if (isset($_POST['add_submit'])) {
    $start_date = date("d-m-Y", strtotime($_POST['startdate']));
    $end_date = date("d-m-Y", strtotime($_POST['enddate']));
}

$from_date = to_sql_date($start_date);
$to_date = to_sql_date($end_date);

function GetInvestmentAccountValue($from_date, $to_date)
{
    $investment_id = 1;
    $coh_income = 0;
    $bank_income = 0;
    $coh_expense = 0;
    $bank_expense = 0;
    $values = array();

    $from_date = to_sql_date($from_date);
    $to_date = to_sql_date($to_date);
    $FinanceQuery = "SELECT * FROM  macho_revenue WHERE account_id='$investment_id' AND entry_date>='$from_date' AND entry_date<='$to_date' ORDER BY entry_date DESC ";
    $FinanceResult = GetAllRows($FinanceQuery);
    foreach ($FinanceResult as $FinanceData) {
        if ($FinanceData['type'] == 'Income') {
            if ($FinanceData['saving_account'] == '12') {
                $coh_income = $coh_income + $FinanceData['amount'];
            } else {
                $bank_income = $bank_income + $FinanceData['amount'];
            }
        } else {
            if ($FinanceData['saving_account'] == '12') {
                $coh_expense = $coh_expense + $FinanceData['amount'];
            } else {
                $bank_expense = $bank_expense + $FinanceData['amount'];
            }
        }
    }

    $coh_value = $coh_income - $coh_expense;
    $bank_value = $bank_income - $bank_expense;

    $values['coh_value'] = $coh_value;
    $values['bank_value'] = $bank_value;
    $values['total_value'] = $coh_value + $bank_value;
    return $values;
}

$investment_data = GetInvestmentAccountValue($from_date, $to_date);

$TotalPurchaseAmount = 0;
$PurchaseQuery = "SELECT net_amount FROM macho_purchase WHERE po_date>='$from_date' AND po_date<='$to_date' ORDER BY id DESC ";
$PurchaseResult = GetAllRows($PurchaseQuery);
$PurchaseCounts = count($PurchaseResult);
if ($PurchaseCounts > 0) {
    foreach ($PurchaseResult as $PurchaseData) {
        $PurchaseAmount = $PurchaseData['net_amount'];
        $TotalPurchaseAmount = $TotalPurchaseAmount + $PurchaseAmount;
    }
}

function GetBillSalesTargetAmount($bill_id)
{
    $total = 0;
    $BillingQuery = "SELECT a.item_id,a.quantity,b.sales_rate FROM macho_bill_items a,macho_products b WHERE a.bill_id='$bill_id' AND b.id=a.item_id ORDER BY a.id DESC";
    $BillingResult = GetAllRows($BillingQuery);
    $BillingCounts = count($BillingResult);
    if ($BillingCounts > 0) {
        foreach ($BillingResult as $BillingData) {
            $sales_rate = $BillingData['sales_rate'] * $BillingData['quantity'];
            $total = $total + $sales_rate;
        }
    }
    return $total;
}

$TotalSalesTargetAmount = 0;
$TotalSalesAmount = 0;
$SalesQuery = "SELECT id,net_amount FROM macho_billing WHERE bill_date>='$from_date' AND bill_date<='$to_date' ORDER BY id DESC ";
$SalesResult = GetAllRows($SalesQuery);
$SalesCounts = count($SalesResult);
if ($SalesCounts > 0) {
    foreach ($SalesResult as $SalesData) {
        $SalesTargetAmount = GetBillSalesTargetAmount($SalesData['id']);
        $TotalSalesTargetAmount = $TotalSalesTargetAmount + $SalesTargetAmount;

        $SalesAmount = $SalesData['net_amount'];
        $TotalSalesAmount = $TotalSalesAmount + $SalesAmount;
    }
}

$total_income = 0;
$total_expense = 0;

$FinanceQuery = "SELECT * FROM  macho_revenue WHERE account_id<>'1' AND entry_date>='$from_date' AND entry_date<='$to_date' ORDER BY entry_date DESC ";
$FinanceResult = GetAllRows($FinanceQuery);
foreach ($FinanceResult as $FinanceData) {
    if ($FinanceData['type'] == 'Income') {
        $total_income = $total_income + $FinanceData['amount'];
    } else {
        $total_expense = $total_expense + $FinanceData['amount'];
    }
}

$total_profit = $TotalSalesAmount - $total_expense;

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

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
	  
            <div class="card-header">
                <?php if ($PageAccessible['is_write'] == 1) { ?>
                    <br><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div style="float: right!important;">
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
                                            <button type="submit" name="add_submit" class="btn btn-success"
                                                    title="Search">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="text-sm"></div>
            </div>

            <div class="card-body">

                <br>

                <div class="row">
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-11">
                                <p class="lead text-center bg-warning btn text-info center-block">Cash on Hand
                                    <br>Rs.<?= ConvertMoneyFormat2($investment_data['coh_value']); ?></p>
                            </div>
                            <div class="col-xs-1">
                                <div class="row">
                                    <p class="btn"><span class="glyphicon glyphicon-arrow-right"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <p class="lead text-center bg-info btn text-info center-block">
                            Investment<br>Rs.<?= ConvertMoneyFormat2($investment_data['total_value']); ?></p>

                        <div class="row">
                            <div class="col-xs-4 text-center">
                                &nbsp;
                            </div>
                            <div class="col-xs-4 text-center">
                                <p class="btn"><span class="glyphicon glyphicon-arrow-down"></span>
                            </div>
                            <div class="col-xs-4 text-center">
                                &nbsp;

                            </div>
                        </div>

                    </div>
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-1">
                                <div class="row">
                                    <p class="btn"><span class="glyphicon glyphicon-arrow-left"></span>
                                </div>
                            </div>
                            <div class="col-xs-11">
                                <p class="lead text-center bg-warning btn text-info center-block">Bank
                                    Account<br>Rs.<?= ConvertMoneyFormat2($investment_data['bank_value']); ?>
                                </p>


                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-xs-4">
                        &nbsp;
                    </div>
                    <div class="col-xs-4">
                        <p class="lead text-center bg-info btn text-info center-block">Purchase
                            Value<br>Rs.<?= ConvertMoneyFormat2($TotalPurchaseAmount); ?></p>

                        <div class="row">
                            <div class="col-xs-4 text-center">
                                &nbsp;
                            </div>
                            <div class="col-xs-4 text-center">
                                <p class="btn"><span class="glyphicon glyphicon-arrow-down"></span>
                            </div>
                            <div class="col-xs-4 text-center">
                                &nbsp;

                            </div>
                        </div>

                    </div>
                    <div class="col-xs-4">
                        &nbsp;
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-xs-4">
                        &nbsp;
                    </div>
                    <div class="col-xs-4">
                        <p class="lead text-center bg-info btn text-info center-block">Sales Target
                            Value<br>Rs.<?= ConvertMoneyFormat2($TotalSalesTargetAmount); ?></p>

                        <div class="row">
                            <div class="col-xs-4 text-center">
                                &nbsp;
                            </div>
                            <div class="col-xs-4 text-center">
                                <p class="btn"><span class="glyphicon glyphicon-arrow-down"></span>
                            </div>
                            <div class="col-xs-4 text-center">
                                &nbsp;

                            </div>
                        </div>

                    </div>
                    <div class="col-xs-4">
                        &nbsp;
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-xs-4">
                        &nbsp;
                    </div>
                    <div class="col-xs-4">
                        <p class="lead text-center bg-info btn text-info center-block">Sales
                            Value<br>Rs.<?= ConvertMoneyFormat2($TotalSalesAmount); ?></p>

                        <div class="row">
                            <div class="col-xs-4 text-center">
                                &nbsp;
                            </div>
                            <div class="col-xs-4 text-center">
                                <p class="btn"><span class="glyphicon glyphicon-arrow-down"></span>
                            </div>
                            <div class="col-xs-4 text-center">
                                &nbsp;

                            </div>
                        </div>

                    </div>
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-1">
                                <div class="row">
                                    <p class="btn"><span class="glyphicon glyphicon-arrow-left"></span>
                                </div>
                            </div>
                            <div class="col-xs-11">
                                <p class="lead text-center bg-danger btn text-info center-block">Loss of Sales
                                    Target<br>Rs.<?= ConvertMoneyFormat2($TotalSalesTargetAmount - $TotalSalesAmount); ?>
                                </p>


                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-xs-4">
                        &nbsp;
                    </div>
                    <div class="col-xs-4">
                        <p class="lead text-center bg-info btn text-info center-block">
                            Expense<br>Rs.<?= ConvertMoneyFormat2($total_expense); ?></p>

                        <div class="row">
                            <div class="col-xs-4 text-center">
                                &nbsp;
                            </div>
                            <div class="col-xs-4 text-center">
                                <p class="btn"><span class="glyphicon glyphicon-arrow-down"></span>
                            </div>
                            <div class="col-xs-4 text-center">
                                &nbsp;

                            </div>
                        </div>

                    </div>
                    <div class="col-xs-4">
                        &nbsp;
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-xs-4">
                        &nbsp;
                    </div>
                    <div class="col-xs-4">
                        <p class="lead text-center bg-<?= ($total_profit < 0 ? 'danger' : 'success'); ?> btn text-success center-block"> <?= ($total_profit < 0 ? 'Loss Amount' : 'Profit Amount'); ?>
                            <br>Rs.<?= ConvertMoneyFormat2($total_profit); ?>
                        </p>

                    </div>
                    <div class="col-xs-4">
                        &nbsp;
                    </div>
                </div>
                <br>
                <br>
                <br>
            </div>
        </div>
    </div>
</section>	  <?php include_once 'footer.php'; ?>
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
</body>
</html>