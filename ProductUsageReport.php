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
?><?php include ("css.php"); ?>
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
        <div class="content-heading">
            <div>Product Usage Report
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Product Usage Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Product Usage Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Product Usage Report','<?php echo $start_date; ?>','<?php echo $end_date; ?>');">
                        <i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
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
                <table class="table table-striped my-4 w-100" id="datatable1">
                    <thead>
                    <tr>
                        <th width="10px" class="thead_data">#</th>
                        <th class="thead_data">Date</th>
                        <th class="thead_data">Description</th>
                        <th class="thead_data">Product Name</th>
                        <th class="thead_data">Product Code</th>
                        <th class="thead_data">Quantity</th>
                        <th class="thead_data">Created By</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 0;
                    $start_date = to_sql_date($start_date);
                    $end_date = to_sql_date($end_date);
                    $ProductsQuery = "SELECT a.*,b.product_name,b.product_code,b.uom FROM macho_product_usage a,macho_products b WHERE a.entry_date>='$start_date' AND a.entry_date<='$end_date' AND b.id=a.product_id  ORDER BY a.entry_date DESC ";
                    $ProductsResult = GetAllRows($ProductsQuery);
                    $ProductsCounts = count($ProductsResult);
                    if ($ProductsCounts > 0) {
                        foreach ($ProductsResult as $ProductsData) {
                            $bill_id = $ProductsData['bill_id'];
                            if($bill_id == -1) {
                                $description = 'Removed Sales Return Data';
                            } else {
                                $Query = "SELECT bill_no FROM macho_customer_bill WHERE id='$bill_id'";
                                $Result = mysqli_query($GLOBALS['conn'], $Query) or die(mysqli_error($GLOBALS['conn']));
                                $Data = mysqli_fetch_assoc($Result);
                                $description = 'Bill No.: '.$Data['bill_no'];
                            }
                            ?>
                            <tr>
                                <td class="tbody_data"><?php echo ++$no; ?></td>
                                <td class="tbody_data">&nbsp;<?php echo date("d-m-Y", strtotime($ProductsData['entry_date'])); ?></td>
                                <td class="tbody_data">&nbsp;<?php echo $description; ?></td>
                                <td class="tbody_data">&nbsp;<?php echo $ProductsData['product_name']; ?></td>
                                <td class="tbody_data">&nbsp;<?php echo $ProductsData['product_code']; ?></td>
                                <td class="tbody_data">&nbsp;<?php echo $ProductsData['quantity'] . $ProductsData['uom']; ?></td>
                                <td class="tbody_data">&nbsp;<?php echo UserName($ProductsData['created_by']); ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
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