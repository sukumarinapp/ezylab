<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);

$report_month = date("m-Y");
function GetAccountValue($account_id, $last_date, $month, $year)
{
    $total_value = 0;
    $today = date("Y-m-d");

    for ($i = 1; $i <= $last_date; $i++) {
        $day_var = str_pad($i, 2, "0", STR_PAD_LEFT);
        $report_date = $year . "-" . $month . "-" . $day_var;
        if ($report_date <= $today) {

            $FinanceQuery = "SELECT * FROM  macho_revenue WHERE account_id='$account_id' AND entry_date>='$report_date' AND entry_date<='$report_date' ORDER BY entry_date DESC ";
            $FinanceResult = GetAllRows($FinanceQuery);
            foreach ($FinanceResult as $FinanceData) {
                $total_value = $total_value + $FinanceData['amount'];
            }
        }
    }

    return $total_value;
}

if (isset($_POST['add_submit'])) {
    $report_month = $_POST['report_month'];
}

$report_month2 = "01-" . $report_month;
$month = date("m", strtotime($report_month2));
$MONTH = date("M", strtotime($report_month2));
$year = date("Y", strtotime($report_month2));
$last_date = date("t", strtotime($report_month2));

$total_sales_amount = GetAccountValue('6', $last_date, $month, $year);
//$total_purchase_return_amount = GetAccountValue('5', $last_date, $month, $year);
$total_direct_income_amount = GetAccountValue('2', $last_date, $month, $year);
$total_indirect_income_amount = GetAccountValue('10', $last_date, $month, $year);
$total_other_income = $total_direct_income_amount + $total_indirect_income_amount;
$total_income = $total_sales_amount + $total_other_income;

//$total_purchase_amount = GetAccountValue('4', $last_date, $month, $year);
$total_sales_return_amount = GetAccountValue('7', $last_date, $month, $year);
//$total_staff_revenue_amount = GetAccountValue('8', $last_date, $month, $year);
$total_direct_expense_amount = GetAccountValue('3', $last_date, $month, $year);
$total_indirect_expense_amount = GetAccountValue('11', $last_date, $month, $year);
$total_other_expense = $total_direct_expense_amount + $total_indirect_expense_amount;
$total_expense = $total_sales_return_amount + $total_other_expense;

$profit = $total_income - $total_expense;
?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="card b mb-2">
                    <div class="card-header bb">
                        <h4 class="card-title">Monthly Profit Report</h4>
                        <br><br>
                        <?php if ($PageAccessible['is_write'] == 1) { ?>
                            <div class="card-title pull-right">
                                <form action="" method="post" class="search-form">
                                    <div class="btn-toolbar">
                                        <div class="form-group">
                                            <input type="text" class="form-control"
                                                   value="Report Month" readonly>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="report_month" id="report_month"
                                                   class="form-control"
                                                   value="<?php echo $report_month; ?>">
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
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body bt">
                                <h4 class="b0">Income</h4>
                            </div>
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>Sales Amount</td>
                                    <td>
                                        <div class="text-right text-bold">
                                            Rs.<?= ConvertMoneyFormat2($total_sales_amount) ?></div>
                                    </td>
                                </tr>
                               <!--  <tr>
                                    <td>Purchase Return</td>
                                    <td>
                                        <div class="text-right text-bold">
                                            Rs.<?= ConvertMoneyFormat2($total_purchase_return_amount) ?></div>
                                    </td>
                                </tr> -->
                                <tr>
                                    <td>Direct Income</td>
                                    <td>
                                        <div class="text-right text-bold">
                                            Rs.<?= ConvertMoneyFormat2($total_direct_income_amount) ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Indirect Income</td>
                                    <td>
                                        <div class="text-right text-bold">
                                            Rs.<?= ConvertMoneyFormat2($total_indirect_income_amount) ?></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body bt">
                                <h4 class="b0">Expense</h4>
                            </div>
                            <table class="table table-bordered">
                                <tbody>
                               <!--  <tr>
                                    <td>Purchase Amount</td>
                                    <td>
                                        <div class="text-right text-bold">
                                            Rs.<?= ConvertMoneyFormat2($total_purchase_amount) ?></div>
                                    </td>
                                </tr> -->
                                <tr>
                                    <td>Sales Return</td>
                                    <td>
                                        <div class="text-right text-bold">
                                            Rs.<?= ConvertMoneyFormat2($total_sales_return_amount) ?></div>
                                    </td>
                                </tr>
                               <!--  <tr>
                                    <td>Staff Revenue</td>
                                    <td>
                                        <div class="text-right text-bold">
                                            Rs.<?= ConvertMoneyFormat2($total_staff_revenue_amount) ?></div>
                                    </td>
                                </tr> -->
                                <tr>
                                    <td>Direct Expense</td>
                                    <td>
                                        <div class="text-right text-bold">
                                            Rs.<?= ConvertMoneyFormat2($total_direct_expense_amount) ?></div>
                                    </td>
                                </tr>
                                 <tr>
                                    <td>Indirect Expense</td>
                                    <td>
                                        <div class="text-right text-bold">
                                            Rs.<?= ConvertMoneyFormat2($total_indirect_expense_amount) ?></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="clearfix">
                                    <div class="float-right text-right">
                                        <div class="text-bold">Rs.<?= ConvertMoneyFormat2($total_income) ?></div>
                                    </div>
                                    <div class="float-left text-bold text-dark">TOTAL INCOME</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="clearfix">
                                    <div class="float-right text-right">
                                        <div class="text-bold">Rs.<?= ConvertMoneyFormat2($total_expense) ?></div>
                                    </div>
                                    <div class="float-left text-bold text-dark">TOTAL EXPENSE</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="card-body">
                                    <button class="btn btn-<?= ($profit < 0 ? 'danger' : 'success'); ?> btn-block"
                                            type="button"><h4 class="b0"><?= ($profit < 0 ? 'LOSS AMOUNT:' : 'PROFIT AMOUNT:'); ?>
                                        Rs.<?= ConvertMoneyFormat2($profit); ?></h4>
                                    </button>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php' ?>
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
<script src="<?php echo VENDOR; ?>bootstrap-datepicker/dist/js/bootstrap-monthpicker.js"></script>
<!-- Datatables-->
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
<script>
    $("#report_month").monthpicker({
        pattern: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months"
    });
</script>
</body>
</html>