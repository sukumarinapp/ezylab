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

?><?php include ("css.php"); ?>
<title>Supplier Account</title>
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
            <div>Supplier Account
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Supplier Account Report','0','0');"><i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Supplier Account Report','0','0');"><i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Supplier Account Report','0','0');"><i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="datatable2">
                        <thead>
                        <tr>
                            <th width="20px" class="thead_data">#</th>
                            <th class="thead_data">Code</th>
                            <th class="thead_data">Name</th>
                            <th class="thead_data">Mobile</th>
                            <th class="thead_data">Credit Amount</th>
                            <th class="thead_data">Debit Amount</th>
                            <th class="thead_data">Balance Amount</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $no = 0;
                        $CustomerQuery = "SELECT * FROM macho_farmers ORDER BY id DESC ";
                        $CustomerResult = GetAllRows($CustomerQuery);
                        $CustomerCounts = count($CustomerResult);
                        if ($CustomerCounts > 0) {
                            foreach ($CustomerResult as $CustomerData) {
                                $FarmerId = $CustomerData['id'];
                                $CustomerAccountPayment = FarmerAccountPayment($FarmerId);
                                ?>
                                <tr>
                                    <td width="20" class="tbody_data">&nbsp;<?= ++$no; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerData['F_code']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerData['F_name']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerData['mobile']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerAccountPayment['CreditAmount']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerAccountPayment['DebitAmount']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $CustomerAccountPayment['BalanceAmount']; ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php
                                            if ($PageAccessible['is_read'] == 1) { ?>
                                                <button class="btn btn-success"
                                                        onClick="window.open('SupplierPaymentReport?fID=<?= EncodeVariable($FarmerId); ?>');"
                                                        title="View Profile"><i
                                                        class="mdi mdi-magnify-plus"></i> View
                                                </button>
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
</section>	  <?php include_once 'footer.php'; ?>
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
</body>

</html>