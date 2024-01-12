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

$USERID = DecodeVariable($_GET['uId']);
?><?php include ("css.php"); ?>
<title><?= UserName($USERID);?></title>
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
        <div class="content-heading">
            <div><?= UserName($USERID);?>
                <small></small>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title pull-right">
                        <button class="btn btn-labeled btn-secondary" type="button"
                                onclick="location.href='StaffRevenue';">
                            <span class="btn-label btn-label"><i class="fa fa-reply"></i>
                           </span> Back to List</button>
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
                        $RevenueQuery = "SELECT * FROM macho_staff_revenue WHERE user_id='$USERID' ORDER BY id DESC ";
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
                                Rs. <?= ConvertMoneyFormat(GetUserEarningRevenue($USERID)); ?></td>
                        </tr>
                        <tr style="font-weight: bold">
                            <td style="text-align: right" colspan="5">Received Amount</td>
                            <td style="text-align: center">
                                Rs. <?= ConvertMoneyFormat(GetUserReceivedRevenue($USERID)); ?></td>
                        </tr>
                        <tr style="font-weight: bold">
                            <th style="text-align: right;font-weight: bolder"
                                colspan="5">Balance Amount
                            </th>
                            <td style="text-align: center;color: #00FF00;">
                                Rs. <?= ConvertMoneyFormat(GetUserPendingRevenue($USERID)); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
</body>
</html>