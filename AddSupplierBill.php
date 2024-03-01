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

?>
<?php include ("css.php"); ?>
<title>Supplier Bill</title>
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
        <div class="content-heading">Supplier Bill</div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Personal-Information -->
                <div class="card card-default">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Bill Date</label>
                                        <input value="<?php echo date("d-m-Y", strtotime(date('Y-m-d'))); ?>"
                                               type="text"
                                               name="bill_date" data-date-format="dd-mm-yyyy"
                                               class="form-control" id="bill_date" tabindex="1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Bill No.</label>
                                        <input maxlength="50" id="bill_num" type="text"
                                               class="form-control" value="<?php echo GetFarmerBillNo(); ?>"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Mobile</label>
                                        <select class="form-control form-control-rounded select2" name="mobile"
                                                id="mobile" onchange="GetFarmerDataByMobile()" tabindex="2" disabled>
                                            <option value="">Enter Mobile</option>
                                            <?php
                                            $FarmerQuery = 'SELECT mobile FROM macho_farmers ORDER BY id DESC ';
                                            $FarmerResult = GetAllRows($FarmerQuery);
                                            $FarmerCounts = count($FarmerResult);
                                            foreach ($FarmerResult as $FarmerData) {
                                                echo '<option value="' . $FarmerData['mobile'] . '">' . $FarmerData['mobile'] . '</option>';
                                            } ?>
                                        </select>
                                        <!--                                        <input maxlength="15" placeholder="Mobile" id="mobile" type="text"-->
                                        <!--                                               class="form-control" onkeyup="GetFarmerData()" tabindex="2">-->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Supplier Name</label>
                                        <select class="form-control form-control-rounded select2" name="farmer_name"
                                                id="farmer_name" onchange="GetFarmerData()" tabindex="3">
                                            <option value="">Enter Name</option>
                                            <option value="0">New Supplier</option>
                                            <?php
                                            $FarmerQuery = 'SELECT * FROM macho_farmers ORDER BY id DESC ';
                                            $FarmerResult = GetAllRows($FarmerQuery);
                                            $FarmerCounts = count($FarmerResult);
                                            foreach ($FarmerResult as $FarmerData) {
                                                echo '<option value="' . $FarmerData['id'] . '">' . $FarmerData['F_name'] . ' | ' . $FarmerData['F_code'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="hidden" name="my_field2" id="code-scan">
                                        <input type="hidden" name="product_code" id="product_code">
                                        <select class="form-control form-control-rounded select2"
                                                name="product_search"
                                                id="product_search"
                                                onchange="GetProductcode();">
                                            <option>Enter Product</option>
                                            <?php
                                            $ProductsQuery = "SELECT product_code,product_name FROM macho_master_products ORDER BY id DESC ";
                                            $ProductsResult = GetAllRows($ProductsQuery);
                                            foreach ($ProductsResult as $ProductsData) {
                                                echo "<option value='" . $ProductsData['product_code'] . "'>" . $ProductsData['product_name'] . ' - ' . $ProductsData['product_code'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="product_id" class="control-label">Item</label>
                                        <input readonly type="hidden" id="product_id" name="product_id"/>
                                        <input readonly type="text" id="product_name" class="form-control"
                                               name="product_name"/>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label style="text-align: right" for="rate" class="control-label">Rate</label>
                                        <input style="text-align: right" required="required" type="text"
                                               maxlength="6" size="4" onkeyup="calculate_amount()"
                                               name="rate" id="rate_id" class="form-control Number"
                                               placeholder="" onkeypress="return isNumberDecimalKey(event)">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label style="text-align: right" for="rate" class="control-label">GST</label>
                                        <select style="width: 80px!important;" class="form-control"
                                                name="gst_id"
                                                id="gst_id">
                                            <?php
                                            $TaxQuery = "SELECT * FROM macho_tax_accounts ORDER BY percentage";
                                            $TaxResult = GetAllRows($TaxQuery);
                                            foreach ($TaxResult as $TaxData) {
                                                echo "<option value='" . $TaxData['percentage'] . "'>" . $TaxData['percentage'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">


                                        <label for="stock"
                                               class="control-label">Stock</label>
                                        <input type="hidden" id="product_qty" name="product_qty" value="">
                                        <input readonly required="required" type="text"
                                               maxlength="2" size="2"
                                               name="stock" id="stock" class="form-control Number"
                                               placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="quantity"
                                               class="control-label">Qty</label>
                                        <input onkeyup="calculate_amount()"
                                               required="required" type="text"
                                               maxlength="6" size="2" pattern="\d*"
                                               name="quantity" id="quantity" class="form-control Number"
                                               onkeypress="return isNumberDecimalKey(event)" tabindex="4">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="uom"
                                               class="control-label">Unit</label>
                                        <select style="width: 80px" class="form-control"
                                                name="uom"
                                                id="uom"
                                                tabindex="8">
                                            <?php
                                            $MeasurementQuery = "SELECT * FROM macho_uom ORDER BY measurement";
                                            $MeasurementData = GetAllRows($MeasurementQuery);
                                            foreach ($MeasurementData as $Measurements) {
                                                echo "<option value='" . $Measurements['symbol'] . "'>" . $Measurements['symbol'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="quantity"
                                               class="control-label" style="width: 80px"> Capacity</label>
                                        <input required="required" type="text"
                                               maxlength="4" size="2" pattern="\d*"
                                               name="pack_capacity" id="pack_capacity" class="form-control Number"
                                               style="width: 80px"
                                               onkeypress="return isNumberDecimalKey(event)" tabindex="4">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="uom"
                                               class="control-label">Pack Unit</label>
                                        <select style="width: 80px" class="form-control"
                                                name="pack_unit"
                                                id="pack_unit"
                                                tabindex="8">
                                            <?php
                                            $MeasurementQuery = "SELECT * FROM macho_uom ORDER BY measurement";
                                            $MeasurementData = GetAllRows($MeasurementQuery);
                                            foreach ($MeasurementData as $Measurements) {
                                                echo "<option value='" . $Measurements['symbol'] . "'>" . $Measurements['symbol'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label style="text-align: right" for="amount"
                                               class="control-label">Amount</label>
                                        <input style="text-align: right" readonly required="required"
                                               type="text"
                                               pattern="\d*" maxlength="2" size="4"
                                               name="amount" id="amount" class="form-control Number"
                                               placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="add"
                                               class="control-label">&nbsp;</label>
                                        <input style="text-align: center" onclick="add_row()"
                                               class="btn btn-info form-control"
                                               type="button" id="add" value="+" tabindex="6"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered" id="tab_logic">
                                        <thead>
                                        <tr style="background-color: #81888c;color:white">
                                            <td style="width: 20px" class="text-center">
                                                S.No
                                            </td>
                                            <td style='text-align: left'>
                                                Item Name
                                            </td>
                                            <td class="text-right">
                                                Rate
                                            </td>
                                            <td class="text-right">
                                                GST (%)
                                            </td>
                                            <td class="text-right">
                                                Qty
                                            </td>
                                            <td class="text-right">
                                                Pack Capacity
                                            </td>
                                            <td class="text-right">
                                                Amount
                                            </td>
                                            <td width="50px" class="text-center">
                                                Remove
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody_data">
                                        <tr class="row_class" id='addr0'></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label">Advance</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" type="text"
                                           name="advance_amount" id="advance_amount"
                                           onkeypress="return isNumberDecimalKey(event)"
                                           class="form-control" onkeyup="calculate_expense_amount()">
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Total</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" readonly type="text"
                                           name="total_amount" id="total_amount" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label">Labour Charges</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" type="text" name="labour_amount"
                                           id="labour_amount" onkeypress="return isNumberDecimalKey(event)"
                                           class="form-control" onkeyup="calculate_expense_amount()">
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Expense</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" readonly type="text"
                                           name="expense_amount"
                                           id="expense_amount"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label">Commission</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" type="text"
                                           name="percentage_amount" id="percentage_amount"
                                           onkeypress="return isNumberDecimalKey(event)"
                                           class="form-control" onkeyup="calculate_expense_amount()">
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Net Total</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" type="text"
                                           name="net_amount" id="net_amount" readonly class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label">Tempo Rent</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" type="text"
                                           name="other_amount" id="other_amount"
                                           onkeypress="return isNumberDecimalKey(event)"
                                           class="form-control" onkeyup="calculate_expense_amount()">
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Pay Amount</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" type="text"
                                           onkeypress="return isNumberDecimalKey(event)"
                                           name="pay_amount" id="pay_amount" class="form-control">
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label">Total Qty</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input type="text"
                                           name="total_qty" id="total_qty" value="" class="form-control"
                                           readonly>
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Payment Method</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <select name="payment_method" id="payment_method" class="form-control"
                                            tabindex="7">
                                        <option value="Cash">Cash</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Debit Card">Debit Card</option>
                                        <option value="Online Payment">Online Payment</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Demand Draft">Demand Draft</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label">Created By</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input type="text"
                                           name="created_by" id="created_by" value="<?= $user; ?>" class="form-control"
                                           readonly>
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Reference No.</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" type="text"
                                           name="reference_no" id="reference_no" class="form-control"
                                           maxlength="100"
                                           tabindex="8">
                                </div>
                            </div>
                            <!--                            <div class="row">-->
                            <!---->
                            <!--                                <div class="col-md-2">-->
                            <!--                                    <label class="control-label">Received Amount</label>-->
                            <!--                                </div>-->
                            <!--                                <div class="col-md-3 pull-right">-->
                            <!--                                    <input style="text-align: right" type="text"-->
                            <!--                                           onkeypress="return isNumberDecimalKey(event)"-->
                            <!--                                           name="received_amount" id="received_amount" class="form-control">-->
                            <!--                                </div>-->
                            <!--                                <div class="col-md-2">&nbsp;</div>-->
                            <!--                                <div class="col-md-2">-->
                            <!--                                    <label class="control-label">Balance Amount</label>-->
                            <!--                                </div>-->
                            <!--                                <div class="col-md-3 pull-right">-->
                            <!--                                    <input style="text-align: right" readonly type="text"-->
                            <!--                                           name="balance_amount" id="balance_amount" class="form-control">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <br>

                            <div class="float-right">

                                <button class="btn btn-labeled btn-secondary" type="button"
                                        onclick="location.href='SupplierBill';">
                           <span class="btn-label"><i class="fa fa-arrow-left"></i>
                           </span>Back to List
                                </button>
                                <button class="btn btn-labeled btn-primary" type="submit" name="submit"
                                        id="save_button"
                                        onclick="submit_data();" tabindex="9">
                           <span class="btn-label"><i class="fa fa-check"></i>
                           </span>Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php'; ?>
</div>
<div class="modal fade" id="add_farmer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Create New Supplier</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <!-- START card-->
                        <div class="card card-default">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text"
                                           class="form-control"
                                           name="F_name"
                                           id="F_name"
                                           maxlength="100"
                                           tabindex="1">
                                </div>
                                <div class="form-group">
                                    <label>Mobile </label>
                                    <input type="text"
                                           class="form-control"
                                           name="F_mobile"
                                           id="F_mobile" onkeypress="return isNumberKey(event)"
                                           maxlength="100"
                                           tabindex="2">
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="clearfix">
                                    <div class="float-right">
                                        <button class="btn btn-primary" type="button" onclick="add_farmer();"
                                                name="add_farmer" id="submit_button" tabindex="3">
                                            Save Supplier
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END card-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
<script>
    $(function () {
        //Date picker
        $('#bill_date').datepicker({
            autoclose: true
        });
    });

    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function DecimalPoint(x) {
        return Number.parseFloat(x).toFixed(2);
    }

    var product_code = '';

    var enterPressed = 0;

    window.onkeypress = function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode === 13) {
            enterPressed++;
            if (enterPressed === 2) {
                enterPressed = 0;
                submit_data();
            }
        } else {
            enterPressed = 0;
        }
    };

    $(document).on("mouseup mousedown click mousemove", function (e) {
        enterPressed = 0;
    });

    //    $('#discount').keypress(function (event) {
    //
    //        var keycode = (event.keyCode ? event.keyCode : event.which);
    //        if (keycode == '13') {
    //            enterPressed = 0;
    //            //alert('You pressed a "enter" key in textbox');
    //            add_row();
    //        }
    //
    //    });

    $('#code-scan').BarReader({
        onScan: function ($element, code) {
            $('#code-scan').val(code);
            GetProductData();
        }
    });

    function GetProductcode() {
        product_code = $('#product_search').val();
        $('#code-scan').val(product_code);
        GetProductData();
    }

</script>
<script>

    function GetProductData() {
        product_code = $('#code-scan').val();
        var farmer_name = $('#farmer_name').val();
        if (farmer_name == "") {
            swal("please Enter Supplier Name");
            return;
        }

        $.ajax({
            url: "GetProductDataByCode.php",
            type: "post",
            data: {product_code: product_code},
            success: function (data) {

                if (data == 0) {
                    swal('Undefined Value : ' + product_code);
                } else {
                    var ProductData = JSON.parse(data);

                    $('#product_id').val(ProductData [0]["product_id"]);
                    $('#product_name').val(ProductData [0]["product_name"]);
                    $('#rate_id').val('1');
                    $('#stock').val(ProductData [0]["product_stock"]);
                    //$('#uom').val(ProductData [0]["product_uom"]);

                    $('#quantity').val("1");

                    calculate_amount();
                }
            }
        });
    }

    function add_farmer() {
        $("#submit_button").prop("disabled", true);

        var farmer_name = $('#F_name').val();
        var mobile = $('#F_mobile').val();

        $.ajax({
            type: 'POST',
            url: 'AddNewSupplier.php',
            data: {
                farmer_name: farmer_name,
                mobile: mobile
            },
            success: function (data) {
                location.href = "AddSupplierBill";
            }
        });
    }

    function GetFarmerDataByMobile() {
        var mobile = $('#mobile').val();

        $.ajax({
            url: "GetFarmerDataByMobile.php",
            type: "post",
            data: {mobile: mobile},
            success: function (data) {
                if (data != 0) {

                    var FarmerData = JSON.parse(data);
                    var F_id = FarmerData [0]["F_id"];
                    //$("#farmer_name").val(FarmerData [0]["F_name"]);

                    var RemoveSelected = $("#farmer_name")[0].innerHTML.replace('selected', '');
                    var ChangeSelected = RemoveSelected.replace(F_id + '"', F_id + '" selected');
                    $('#farmer_name').html(ChangeSelected);
                }
            }
        });
    }

    function GetFarmerData() {
        var farmer_name = $('#farmer_name').val();
        if (farmer_name == 0) {
            $('#add_farmer').modal('show');
        } else {
            $.ajax({
                url: "GetFarmerData.php",
                type: "post",
                data: {F_id: farmer_name},
                success: function (data) {
                    if (data != 0) {

                        var FarmerData = JSON.parse(data);
                        //$('#mobile').val(FarmerData [0]["mobile"]);
                        var mobile = FarmerData [0]["mobile"];

                        var RemoveSelected = $("#mobile")[0].innerHTML.replace('selected', '');
                        var ChangeSelected = RemoveSelected.replace(mobile + '"', mobile + '" selected');
                        $('#mobile').html(ChangeSelected);
                    }
                }
            });
        }
    }

    //    $("input[name='received_amount']").keyup(function () {
    //        var net_amount = $('#net_amount').val();
    //        var received_amount = $('#received_amount').val();
    //        $('#balance_amount').val(DecimalPoint(received_amount - net_amount));
    //    });

    function calculate_amount() {
        var rate = $('#rate_id').val();

        var quantity = $('#quantity').val();


        if (quantity != "" && rate != "") {
            var amount = rate * quantity;
            $('#amount').val(amount);
        } else {
            $('#amount').val("");
        }

    }
</script>
<script>
    var i = 0;

    function duplicate_check(product_id) {
        var item_id = $('input[name="item_id[]"]');
        var item_name = $('input[name="item_name[]"]');
        var item_id_length = item_id.length;
        for (var j = 0; j < item_id_length; j++) {
            if (item_id.eq(j).val() != undefined) {
                if (item_id.eq(j).val() == product_id) {
                    swal("Duplicate item " + item_name.eq(j).val());
                    return true;
                }
            }
        }
        return false;
    }

    function add_row() {
        var amount = $('#amount').val();
        if (amount != "") {
            var num = 0;
            for (var j = 0; j < i; j++) {
                if ($("#addr" + (j)).html() != undefined) {
                    num++;
                }
            }

            var item_id = $("#product_id").val();
            if (duplicate_check(item_id)) {
                return;
            }
            var item_name = $("#product_name").val();
            if (item_id == "") {
                swal("Please select Product");
            }

            var item_rate = $('#rate_id').val();

            var item_gst = $('#gst_id').val();

            var item_quantity = $('#quantity').val();
            var item_uom = $('#uom').val();
            var item_pack_capacity = $('#pack_capacity').val();
            var item_pack_unit = $('#pack_unit').val();

            var amount2 = item_rate * item_quantity;

            $('#addr' + i).html("<td style='text-align: center' class='serial_num'><span class='sl_no'>" + (num + 1) + "</span></td>"
            + "<td style='text-align: left'><input value='" + item_id + "' name='item_id[]' type='hidden'>"
            + "<input value='" + item_name + "' name='item_name[]' type='hidden'>"
            + "<input value='" + item_rate + "' name='item_rate[]' type='hidden'>"
            + "<input value='" + item_gst + "' name='item_gst[]' type='hidden'>"
            + "<input value='" + item_quantity + "' name='item_quantity[]' type='hidden'>"
            + "<input value='" + item_uom + "' name='item_uom[]' type='hidden'>"
            + "<input value='" + item_pack_capacity + "' name='item_pack_capacity[]' type='hidden'>"
            + "<input value='" + item_pack_unit + "' name='item_pack_unit[]' type='hidden'>"
            + "<input value='" + amount + "' name='item_amount[]' type='hidden'>"
            + item_name + "</td>"
            + "<td style='text-align: right'>" + item_rate + "</td>"
            + "<td style='text-align: right'>" + item_gst + "</td>"
            + "<td style='text-align: right'>" + item_quantity + " " + item_uom + "</td>"
            + "<td style='text-align: right'>" + item_pack_capacity + " " + item_pack_unit + "</td>"
                //+ "<td style='text-align: right'>" + dis + "</td>"
            + "<td style='text-align: right'>" + amount + "</td>"
            + "<td width='50px' style='text-align: center' valign='middle'><button title='Remove' class='btn btn-info btn-danger' onclick='delete_row(" + i + ")'>X</button></td>");
            $('#tab_logic').append('<tr class="row_class" id="addr' + (i + 1) + '"></tr>');
            i++;
            set_fix();
        } else {
            swal("Please Enter Product Name");
            $("#code-scan").focus();
            return;
        }
    }

    function delete_row(row) {
        $("#addr" + (row)).remove();
        var item_id = $('input[name="item_id[]"]');
        var item_id_length = item_id.length;
        var num = 1;
        for (var j = 0; j < item_id_length; j++) {
            //console.log($("#addr"+(j)).html());
            if ($("#addr" + (j)).html() != undefined) {
                $('#addr' + j + ' .sl_no').html(num);
                num++;
            }
            $("#addr" + (row)).remove();
        }
        set_fix();
    }

    function set_fix() {
        var total_amount = 0.0;
        var t_qty = 0.0;
        var t_qty_no = '0';
        var net_amount = 0.0;
        var item_id = $('input[name="item_id[]"]');
        var item_quantity = $('input[name="item_quantity[]"]');
        var item_amount = $('input[name="item_amount[]"]');


        var item_id_length = item_id.length;
        for (var j = 0; j < item_id_length; j++) {
            var itm_amt = 0;
            var itm_qty = 0;
            if (item_amount.eq(j).val() != undefined) {

                itm_amt = parseFloat(item_amount.eq(j).val())
                itm_qty = parseFloat(item_quantity.eq(j).val())

                net_amount = net_amount + itm_amt;
                t_qty_no = t_qty_no + '+' + itm_qty;
                t_qty = +t_qty + +itm_qty;

            }
        }


        $('#total_amount').val(net_amount);
        $('#total_qty').val(t_qty_no + '=' + t_qty);


        $("#product_id").val('');
        $("#product_name").val('');
        $('#rate_id').val("");
        $('#gst_id').val("");
        $('#quantity').val("");
        $('#stock').val("");
        $('#product_qty').val("");
        $('#uom').val("");
        $('#pack_capacity').val("");
        $('#pack_unit').val("");
        $('#amount').val("");
        $("#product_search").val("");
        $("#code-scan").val("");
        $("#code-scan").focus();

        calculate_expense_amount();

    }

    function calculate_expense_amount() {
        var advance_amount = $('#advance_amount').val();
        var labour_amount = $('#labour_amount').val();
        var other_amount = $('#other_amount').val();
        var percentage_amount = $('#percentage_amount').val();
        var net_amount = $('#total_amount').val();

        var expense_amount = +advance_amount + +labour_amount + +other_amount + +percentage_amount;
        $('#expense_amount').val(expense_amount);
        $('#net_amount').val(+net_amount + +expense_amount);

    }

    $("input[name='pay_amount']").keyup(function () {
        var pay_amount = $('#pay_amount').val();
        var net_amount = $('#net_amount').val();

        if (parseFloat(pay_amount) > parseFloat(net_amount)) {
            swal("Pay amount cannot be greater than Net Amount");
            $('#pay_amount').val("0");
        }
    });

    function submit_data() {
        var net_amount = parseFloat($('#net_amount').val());
        if (isNaN(net_amount) || net_amount < 0) {
            swal("Net Total should be greater than zero");
            return;
        } else {
            var bill_num = $('#bill_num').val();
            var bill_date = $('#bill_date').val();
            var bill_type = $('#bill_type').val();
            var farmer_id = $('#farmer_name').val();
            var farmer_name = $('#farmer_name').text();
            var mobile = $('#mobile').val();
            var total_amount = DecimalPoint($('#total_amount').val());
            var advance_amount = DecimalPoint($('#advance_amount').val());
            var labour_amount = DecimalPoint($('#labour_amount').val());
            var other_amount = DecimalPoint($('#other_amount').val());
            var percentage_amount = DecimalPoint($('#percentage_amount').val());
            var expense_amount = DecimalPoint($('#expense_amount').val());
            var pay_amount = $('#pay_amount').val();
            var payment_method = $('#payment_method').val();
            var reference_no = $('#reference_no').val();

            $("#save_button").prop("disabled", true);

            var item_id = $('input[name="item_id[]"]');
            var item_uom2 = $('input[name="item_uom[]"]');
            var item_quantity = $('input[name="item_quantity[]"]');
            var item_pack_capacity = $('input[name="item_pack_capacity[]"]');
            var item_pack_unit = $('input[name="item_pack_unit[]"]');
            var item_rate = $('input[name="item_rate[]"]');
            var item_gst = $('input[name="item_gst[]"]');
            var item_amount = $('input[name="item_amount[]"]');

            var item_id_length = item_id.length;

            var sales = new Array();

            for (var j = 0; j < item_id_length; j++) {
                var item_amount2 = item_amount.eq(j).val();
                if (item_amount2 != 0) {

                    var record = {
                        'item_id': item_id.eq(j).val(),
                        'item_rate': item_rate.eq(j).val(),
                        'item_gst': item_gst.eq(j).val(),
                        'item_quantity': item_quantity.eq(j).val(),
                        'item_uom': item_uom2.eq(j).val(),
                        'item_pack_capacity': item_pack_capacity.eq(j).val(),
                        'item_pack_unit': item_pack_unit.eq(j).val(),
                        'item_amount': item_amount2
                    };
                    sales.push(record);
                }
            }

            var sales_data = JSON.stringify(sales);

            $.ajax({
                type: 'POST',
                url: 'SaveSupplierBill.php',
                data: {
                    farmer_id: farmer_id,
                    farmer_name: farmer_name,
                    mobile: mobile,
                    bill_num: bill_num,
                    bill_date: bill_date,
                    sales: sales_data,
                    amount: total_amount,
                    advance_amount: advance_amount,
                    labour_amount: labour_amount,
                    other_amount: other_amount,
                    percentage_amount: percentage_amount,
                    expense_amount: expense_amount,
                    net_amount: net_amount,
                    pay_amount: pay_amount,
                    payment_method: payment_method,
                    reference_no: reference_no
                },
                success: function (bill_id) {
                    $('#payment_method').val("");
                    $('#reference_no').val("");
                    $('#total_amount').val("");
                    $('#net_amount').val("");
                    for (var j = 0; j < i; j++) {
                        $("#addr" + (j)).html('');
                    }
                    i = 0;
                    PrintBill(bill_id);
                    $("#save_button").prop("disabled", false);
                }
            });
        }
    }

    function PrintBill(id) {
        location.href = "SupplierBillPDF?fID=" + id;
//        $.ajax({
//            type: 'POST',
//            url: 'Far.php',
//            data: {
//                id: id
//            },
//            success: function (response) {
//                $.ajax({
//                    type: 'POST',
//                    url: 'http://localhost/helloshopping/app/bill.php',
//                    data: {
//                        print_data: response
//                    },
//                    success: function (data) {
//                    }
//                });
//
//                location.href = "FarmerEntry";
//            }
//        });
    }
</script>
</body>


</html>