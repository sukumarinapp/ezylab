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
<title>Products Stock</title>
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
            <div>Products Stock
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Products Stock Report','0','0');"><i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Products Stock Report','0','0');"><i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Products Stock Report','0','0');"><i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <!-- START card-->
                <div class="card card-default">
                    <div class="card-header">
                        <?php if ($PageAccessible['is_write'] == 1) { ?>
                            <div class="card-title pull-right">
                                <button class="btn btn-labeled btn-secondary" type="button" title="Stock Update"
                                        onclick="window.open('StockUpdate','_blank');"><span
                                        class="btn-label btn-label"><i class="fa fa-cart-plus"></i>
                           </span> Stock Update
                                </button>
                            </div>
                        <?php } ?>
                        <div class="text-sm"></div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped my-4 w-100" id="datatable1">
                            <thead>
                            <tr>
                                <th width="20px" class="thead_data">#</th>
                                <th class="thead_data">Name</th>
                                <th class="thead_data">Category</th>
                                <th class="thead_data">Supplier</th>
                                <th class="thead_data">Qty</th>
                                <th class="thead_data">MRP</th>
                                <th class="thead_data">Price</th>
                                <th class="thead_data">Mfg. Date</th>
                                <th class="thead_data">Exp. Date</th>
                                <th class="thead_data">Location</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 0;
                            $ProductQuery = "SELECT a.id,a.product_category,a.product_name,a.product_uom,a.product_mrp,a.sales_rate,a.product_location,a.mfg_date,a.exp_date,b.category_name,b.supplier_id FROM macho_products a,macho_product_category b WHERE a.parent_id='0' AND b.id=a.product_category ORDER BY a.product_name ";
                            $ProductResult = GetAllRows($ProductQuery);
                            $ProductEntryCounts = count($ProductResult);
                            if ($ProductEntryCounts > 0) {
                                foreach ($ProductResult as $ProductData) { ?>
                                    <tr>
                                        <td class="tbody_data"><?= ++$no; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $ProductData['product_name']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $ProductData['category_name']; ?></td>
                                        <td class="tbody_data">
                                            &nbsp;<?= SupplierName($ProductData['supplier_id']); ?></td>
                                        <td class="tbody_data">
                                            &nbsp;<?= ProductStock($ProductData['id']) . ' ' . $ProductData['product_uom']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $ProductData['product_mrp']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $ProductData['sales_rate']; ?></td>
                                        <td class="tbody_data">
                                            &nbsp;<?= from_sql_date($ProductData['mfg_date']); ?></td>
                                        <td class="tbody_data">
                                            &nbsp;<?= from_sql_date($ProductData['exp_date']); ?></td>
                                        <td class="tbody_data">&nbsp;<?= $ProductData['product_location']; ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-green" type="button" title="View"
                                                        onclick="location.href='ProductView?id=<?php echo EncodeVariable($ProductData['id']); ?>';">
                                                    <em class="fa fa-search"></em> View
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END card-->
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