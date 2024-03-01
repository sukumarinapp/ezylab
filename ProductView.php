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


$id = DecodeVariable($_GET['id']);
$ProductData = SelectParticularRow('macho_products', 'id', $id);
$ProductName = $ProductData['product_name'];
$parent_id = $ProductData['parent_id'];
$item_barcode_type = $ProductData['barcode_type'];
$ProductQty = ProductStock($ProductData['id']);
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
        <div class="content-heading"><?php echo $ProductName; ?></div>
        <div role="tabpanel">
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item" role="presentation"><a class="nav-link active" href="#edit" aria-controls="edit"
                                                            role="tab"
                                                            data-toggle="tab">Product Details</a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="#product_update"
                                                            aria-controls="product_update"
                                                            role="tab"
                                                            data-toggle="tab">Stock Update</a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="#product_usage"
                                                            aria-controls="product_usage"
                                                            role="tab"
                                                            data-toggle="tab">Product Usage</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="edit" role="tabpanel">
                    <div class="card card-default">
                        <div class="card-header">Product Information</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form class="form-horizontal">
                                        <div class="form-group row">
                                            <div class="col-md-4">Product Name:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['product_name']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Product Category:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo ProductCategoryName($ProductData['product_category']); ?></strong>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4">Product Quantity:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductQty . ' ' . $ProductData['product_uom']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Default Purchase price</div>
                                            <div class="col-md-8">
                                                <strong>&nbsp;</strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Tax Inclusive:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['purchase_rate']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Tax :</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['purchase_tax_percentage']; ?>
                                                    %</strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Tax Amount:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['purchase_tax_amount']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Tax Exclusive:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['purchase_net_amount']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Product Location:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['product_location']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Mfg. Date:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo from_sql_date($ProductData['mfg_date']); ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Barcode Type:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['barcode_type']; ?></strong>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <form class="form-horizontal">
                                        <div class="form-group row">
                                            <div class="col-md-4">Product Code:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['product_code']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">HSN / SAC:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['hsn_sac']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Tax Account:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo TaxAccountName($ProductData['tax_account']); ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Default Sale price</div>
                                            <div class="col-md-8">
                                                <strong>&nbsp;</strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Tax Inclusive:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['sales_rate']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Tax:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['sales_tax_percentage']; ?> %</strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Tax Amount:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['sales_tax_amount']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Tax Exclusive:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['sales_net_amount']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Product MRP:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $ProductData['product_mrp']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Exp. Date:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo from_sql_date($ProductData['exp_date']); ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Status:</div>
                                            <div class="col-md-8">
                                                <div class="badge badge-info"><?php if ($ProductData['status'] == 1) {
                                                        echo 'Active';
                                                    } else {
                                                        echo 'In-Active';
                                                    }; ?></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="alert alert-default">Product Description:<br>
                                <hr>
                                <br>
                                <?= nl2br($ProductData['product_description']); ?></div>
                        </div>
                    </div>
                    <?php
                    if ($item_barcode_type == 'Multi') {
                        if ($ProductQty > 1) { ?>
                            <div class="card">
                                <div class="card-header"><h3>Product Pieces</h3></div>
                                <div class="card-body">
                                    <table class="table table-hover table-bordered table-striped my-4 w-100"
                                           id="datatable3">
                                        <thead>
                                        <tr>
                                            <th>
                                                <strong>#</strong>
                                            </th>
                                            <th>
                                                <strong>Code</strong>
                                            </th>
                                            <th>
                                                <strong>Name</strong>
                                            </th>
                                            <th>
                                                <strong>Category</strong>
                                            </th>
                                            <th>
                                                <strong>Supplier</strong>
                                            </th>
                                            <th>
                                                <strong>Qty</strong>
                                            </th>
                                            <th>
                                                <strong>Price</strong>
                                            </th>
                                            <th>
                                                <strong>Mfg. Date</strong>
                                            </th>
                                            <th>
                                                <strong>Exp. Date</strong>
                                            </th>
                                            <th>
                                                <strong>Location</strong>
                                            </th>
                                            <th class="text-center">
                                                <strong>Action</strong>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $no = 0;
                                        if ($parent_id == 0) {
                                            $ProductsQuery = "SELECT * FROM macho_products WHERE parent_id='$id' ORDER BY id DESC ";
                                        } else {
                                            $ProductsQuery = "SELECT * FROM macho_products WHERE (id='$parent_id' OR parent_id='$parent_id') AND id<>'$id' ORDER BY id DESC ";
                                        }
                                        $ProductsResult = GetAllRows($ProductsQuery);
                                        $ProductsCounts = count($ProductsResult);
                                        if ($ProductsCounts > 0) {
                                            foreach ($ProductsResult as $ProductsData) {
                                                ?>
                                                <tr>
                                                    <td><?php echo ++$no; ?></td>
                                                    <td><?php echo $ProductsData['product_code']; ?></td>
                                                    <td><?php echo $ProductsData['product_name']; ?></td>
                                                    <td><?php echo ProductCategoryName($ProductsData['product_category']); ?></td>
                                                    <td><?php echo SupplierName($ProductsData['supplier_id']); ?></td>
                                                    <td><?php echo $ProductsData['product_qty'] . $ProductsData['product_uom']; ?></td>
                                                    <td><?php echo $ProductsData['sales_rate']; ?></td>
                                                    <td><?php echo from_sql_date($ProductsData['mfg_date']); ?></td>
                                                    <td><?php echo from_sql_date($ProductsData['exp_date']); ?></td>
                                                    <td><?php echo $ProductsData['product_location']; ?></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-green" type="button" title="View"
                                                                onclick="location.href='ProductView?id=<?php echo EncodeVariable($ProductsData['id']); ?>';">
                                                            <em class="fa fa-search"></em> View
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
                <div class="tab-pane" id="product_update" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped my-4 w-100" id="datatable1">
                                <thead>
                                <tr>
                                    <th width="20px">#</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Created By</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 0;
                                $ProductUpdateQuery = "SELECT a.*,b.product_uom FROM macho_product_update_entry a,macho_products b WHERE a.product_id='$id' AND b.id=a.product_id ORDER BY a.id DESC";
                                $ProductUpdateResult = GetAllRows($ProductUpdateQuery);
                                $ProductUpdateEntryCounts = count($ProductUpdateResult);
                                if ($ProductUpdateEntryCounts > 0) {
                                    foreach ($ProductUpdateResult as $ProductUpdateData) {
                                        $ref_id = $ProductUpdateData['ref_id'];
                                        if ($ref_id == 0) {
                                            $description = 'Stock Entry Data';
                                        } elseif ($ref_id == -1) {
                                            $description = 'Sales Return Data';
                                        } elseif ($ref_id == -2) {
                                            $description = 'Removed Bill Data';
                                        } else {
                                            $Query = "SELECT grn_no FROM macho_grn WHERE id='$ref_id'";
                                            $Result = mysqli_query($GLOBALS['conn'], $Query) or die(mysqli_error($GLOBALS['conn']));
                                            $Data = mysqli_fetch_assoc($Result);
                                            $description = 'GRN No.: ' . $Data['grn_no'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo ++$no; ?></td>
                                            <td><?php echo from_sql_date($ProductUpdateData['created']); ?></td>
                                            <td><?php echo $description; ?></td>
                                            <td><?php echo $ProductUpdateData['product_qty'] . ' ' . $ProductUpdateData['product_uom']; ?></td>
                                            <td><?php echo UserName($ProductUpdateData['created_by']); ?></td>
                                        </tr>
                                    <?php
                                    }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="product_usage" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped my-4 w-100" id="datatable2">
                                <thead>
                                <tr>
                                    <th width="20px">#</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Created By</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 0;
                                $ProductUsageQuery = "SELECT a.*,b.product_uom FROM macho_product_usage a,macho_products b WHERE a.product_id='$id' AND b.id=a.product_id ORDER BY a.id DESC";
                                $ProductUsageResult = GetAllRows($ProductUsageQuery);
                                $ProductUsageEntryCounts = count($ProductUsageResult);
                                if ($ProductUsageEntryCounts > 0) {
                                    foreach ($ProductUsageResult as $ProductUsageData) {
                                        $bill_id = $ProductUsageData['bill_id'];
                                        if ($bill_id == -1) {
                                            $description = 'Removed Sales Return Data';
                                        } else {
                                            $Query = "SELECT billnum FROM macho_billing WHERE id='$bill_id'";
                                            $Result = mysqli_query($GLOBALS['conn'], $Query) or die(mysqli_error($GLOBALS['conn']));
                                            $Data = mysqli_fetch_assoc($Result);
                                            $description = 'Bill No.: ' . $Data['billnum'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo ++$no; ?></td>
                                            <td><?php echo from_sql_date($ProductUsageData['entry_date']); ?></td>
                                            <td><?php echo $description; ?></td>
                                            <td><?php echo $ProductUsageData['quantity'] . ' ' . $ProductUsageData['product_uom']; ?></td>
                                            <td><?php echo UserName($ProductUsageData['created_by']); ?></td>
                                        </tr>
                                    <?php
                                    }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row" align="center">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-primary fa fa-reply" type="button"
                                onclick="location.href = 'Products';"> Back
                        </button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</section>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
<script>
    $(document).ready(function () {
        $('#datatable1').DataTable();

        $('#datatable2').DataTable();

        $('#datatable3').DataTable();
    });
</script>
</body>
</html>