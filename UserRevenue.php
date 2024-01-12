<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div><?= UserName($user_id);?>
                <small></small>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Staff Revenue
                    </div>
                    <div class="text-sm"></div>
                </div>
                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="datatable1">
                        <thead>
                        <tr>
                            <th width="20px">#</th>
                            <th>Revenue Date</th>
                            <th>Description</th>
                            <th>Paid Status</th>
                            <th>Paid Date</th>
                            <th class="text-center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $RevenueQuery = "SELECT * FROM macho_staff_revenue WHERE user_id='$user_id' ORDER BY id DESC ";
                        $RevenueResult = GetAllRows($RevenueQuery);
                        $RevenueCounts = count($RevenueResult);
                        if ($RevenueCounts > 0) {
                            foreach ($RevenueResult as $RevenueData) {
                                ?>
                                <tr>
                                    <td><?php echo ++$no; ?></td>
                                    <td><?php echo from_sql_date($RevenueData['revenue_date']); ?></td>
                                    <td><?php echo $RevenueData['description']; ?></td>
                                    <td class="text-center"><?php echo(($RevenueData['paid_status']) == 0 ? '<span class="badge badge-danger">Pending</span>' : '<span class="badge badge-success">Paid</span>'); ?> </td>
                                    <td><?php echo from_sql_date($RevenueData['paid_date']); ?></td>
                                    <td class="text-center"><b>Rs. <?php echo $RevenueData['amount']; ?></b></td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                        <tbody>
                        <tr style="font-weight: bold">
                            <td style="text-align: right" colspan="5">Earning Amount</td>
                            <td style="text-align: center">
                                Rs. <?= ConvertMoneyFormat(GetUserEarningRevenue($user_id)); ?></td>
                        </tr>
                        <tr style="font-weight: bold">
                            <td style="text-align: right" colspan="5">Received Amount</td>
                            <td style="text-align: center">
                                Rs. <?= ConvertMoneyFormat(GetUserReceivedRevenue($user_id)); ?></td>
                        </tr>
                        <tr style="font-weight: bold">
                            <th style="text-align: right;font-weight: bolder"
                                colspan="5">Balance Amount
                            </th>
                            <td style="text-align: center;color: #00FF00;">
                                Rs. <?= ConvertMoneyFormat(GetUserPendingRevenue($user_id)); ?></td>
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

<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.min.js"></script>
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
<!-- Datatables-->
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
</body>
</html>