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

?><?php include ("css.php"); ?>
<title>Stock Update</title>
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
        <div class="content-heading">Stock Update</div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card card-default">
                    <div class="card-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Name</label>
                                        <input type="hidden" name="product_name" id="product_name" value="">
                                        <select style="width: 300px!important;"
                                                class="form-control form-control-rounded select2"
                                                name="product_id" id="product_id" onchange="GetProductData()" required
                                                tabindex="1">
                                            <option value="0">Enter Product</option>
                                            <?php
                                            $ProductsQuery = "SELECT id,product_name FROM macho_products WHERE parent_id='0' ORDER BY product_name ";
                                            $ProductsResult = GetAllRows($ProductsQuery);
                                            foreach ($ProductsResult as $ProductsData) {
                                                echo "<option value='" . $ProductsData['id'] . "'>" . $ProductsData['product_name'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="amount"
                                               class="control-label">GST %</label>
                                        <input type="text"
                                               name="gst" id="gst" class="form-control Number"
                                               style="width: 80px!important;"
                                               onkeypress="return isNumberDecimalKey(event)" tabindex="2">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="quantity"
                                               class="control-label">Buy Price</label>
                                        <input type="text"
                                               name="purchase_rate" id="purchase_rate" class="form-control Number"
                                               style="width: 80px!important;"
                                               onkeypress="return isNumberDecimalKey(event)" tabindex="3">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="uom"
                                               style="width: 80px!important;" class="control-label">Sale Price</label>
                                        <input required type="text"
                                               name="sales_rate" id="sales_rate" class="form-control Number"
                                               style="width: 80px!important;"
                                               onkeypress="return isNumberDecimalKey(event)" tabindex="4">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="rate" class="control-label">Mfg. Date</label>
                                        <input type="text"
                                               name="mfg_date" id="mfg_date" class="form-control Number"
                                               style="width: 100px!important;" autocomplete="off" tabindex="5">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="gst"
                                               class="control-label">Exp. Date</label>
                                        <input type="text"
                                               name="exp_date" id="exp_date" class="form-control Number"
                                               style="width: 100px!important;" autocomplete="off" tabindex="6">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="stock"
                                               style="width: 80px!important;float: right!important;"
                                               class="control-label">Stock Qty</label>
                                        <input type="hidden" name="uom" id="uom" value="">
                                        <input type="text"
                                               name="stock_qty" id="stock_qty" class="form-control Number"
                                               style="width: 80px!important;float: right!important;" tabindex="7"
                                               disabled>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="discount"
                                               class="control-label"> New Qty</label>
                                        <input type="hidden" name="barcode_type" id="barcode_type" value="">
                                        <input type="text"
                                               name="new_quantity" id="new_quantity" class="form-control Number"
                                               onkeyup="calculate_qty()"
                                               style="width: 80px!important;" onkeypress="return isNumberKey(event)"
                                               tabindex="8">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="discount"
                                               class="control-label"> Total Qty</label>
                                        <input type="text"
                                               name="total_qty" id="total_qty" class="form-control Number"
                                               style="width: 80px!important;" tabindex="9" disabled>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="add"
                                               class="control-label">&nbsp;</label>
                                        <input onclick="add_row()" class="btn btn-info form-control"
                                               type="button" id="add" value="Add" tabindex="10"/>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                            GST%
                                        </td>
                                        <td class="text-right">
                                            Buy Price
                                        </td>
                                        <td class="text-right">
                                            Sale Price
                                        </td>
                                        <td class="text-right">
                                            Mfg. Date
                                        </td>
                                        <td class="text-right">
                                            Exp. Date
                                        </td>
                                        <td class="text-right">
                                            Stock Qty
                                        </td>
                                        <td class="text-right">
                                            New Qty
                                        </td>
                                        <td class="text-right">
                                            Total Qty
                                        </td>
                                        <td width="50px" class="text-center">
                                            Remove
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr id='addr0'></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="card-footer">
                        <div class="clearfix">
                            <div class="float-right">

                                <button class="btn btn-labeled btn-secondary" type="button"
                                        onclick="location.href='StockList';">
                           <span class="btn-label"><i class="fa fa-arrow-left"></i>
                           </span>Back to List
                                </button>

                                <button class="btn btn-labeled btn-primary" type="submit" name="submit" id="save_button"
                                        onclick="submit_data(event);" tabindex="11">
                           <span class="btn-label"><i class="fa fa-check"></i>
                           </span>Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body loader-demo d-flex align-items-center justify-content-center">

            <div id="loader">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
</section>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
<script>
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    $(function () {
        //Date picker
        $('#mfg_date').datepicker({
            autoclose: true
        });

        $('#exp_date').datepicker({
            autoclose: true
        });
    });

    function GetProductData() {
        var product_id = $("select[name='product_id'] option:selected").val();
        $.ajax({
            url: "GetProductData.php",
            type: "post",
            data: {product_id: product_id},
            success: function (data) {
                var ProductData = JSON.parse(data);
                $('#product_name').val(ProductData [0]["product_name"]);
                $('#gst').val(ProductData [0]["product_tax_percentage"]);
                $('#purchase_rate').val(ProductData [0]["product_purchase_rate"]);
                $('#sales_rate').val(ProductData [0]["product_sales_rate"]);
                $('#uom').val(ProductData [0]["product_uom"]);
                $('#stock_qty').val(ProductData [0]["product_stock_qty"]);
                $('#barcode_type').val(ProductData [0]["product_barcode_type"]);
                $('#total_qty').focus();
                calculate_qty();
            }
        });
    }

    function calculate_qty() {
        var stock_qty = $('#stock_qty').val();
        if (isNaN(stock_qty)) stock_qty = 0;

        var new_quantity = $('#new_quantity').val();
        if (isNaN(new_quantity)) new_quantity = 0;

        var total_qty = +stock_qty + +new_quantity;
        $('#total_qty').val(total_qty);
    }

    var i = 0;

    function add_row() {

        var num = 0;
        for (var j = 0; j < i; j++) {
            if ($("#addr" + (j)).html() != undefined) {
                num++;
            }
        }

        var item_id = $("select[name='product_id'] option:selected").val();
        if (item_id == "0") {
            swal("Please Enter Product Name");
            $("#product_id").focus();
            return;
        }

        var item_name = $('#product_name').val();

        var item_gst = $('#gst').val();

        var item_purchase_rate = $('#purchase_rate').val();

        var item_sales_rate = $('#sales_rate').val();

        var item_mfg_date = $('#mfg_date').val();

        var item_exp_date = $('#exp_date').val();

        var item_uom = $('#uom').val();

        var item_stock = $('#stock_qty').val();

        var item_quantity = $('#new_quantity').val();
        if (isNaN(item_quantity)) item_quantity = 0;

        var item_total_qty = $('#total_qty').val();

        var item_barcode_type = $("#barcode_type").val();

        $('#addr' + i).html("<td style='text-align: center' class='serial_num'><span class='sl_no'>" + (num + 1) + "</span></td>"
        + "<td style='text-align: left'><input value='" + item_id + "' name='item_id[]' type='hidden'>"
        + "<input value='" + item_name + "' name='item_name[]' type='hidden'>"
        + "<input value='" + item_purchase_rate + "' name='item_purchase_rate[]' type='hidden'>"
        + "<input value='" + item_sales_rate + "' name='item_sales_rate[]' type='hidden'>"
        + "<input value='" + item_gst + "' name='item_gst[]' type='hidden'>"
        + "<input value='" + item_mfg_date + "' name='item_mfg_date[]' type='hidden'>"
        + "<input value='" + item_exp_date + "' name='item_exp_date[]' type='hidden'>"
        + "<input value='" + item_uom + "' name='item_uom[]' type='hidden'>"
        + "<input value='" + item_stock + "' name='item_stock[]' type='hidden'>"
        + "<input value='" + item_quantity + "' name='item_quantity[]' type='hidden'>"
        + "<input value='" + item_total_qty + "' name='item_total_qty[]' type='hidden'>"
        + "<input value='" + item_barcode_type + "' name='item_barcode_type[]' type='hidden'>"
        + item_name + "</td>"
        + "<td style='text-align: right'>" + item_gst + " %</td>"
        + "<td style='text-align: right'>" + item_purchase_rate + "</td>"
        + "<td style='text-align: right'>" + item_sales_rate + "</td>"
        + "<td style='text-align: right'>" + item_mfg_date + "</td>"
        + "<td style='text-align: right'>" + item_exp_date + "</td>"
        + "<td style='text-align: right'>" + item_stock + " " + item_uom + "</td>"
        + "<td style='text-align: right'>" + item_quantity + " " + item_uom + "</td>"
        + "<td style='text-align: right'>" + item_total_qty + " " + item_uom + "</td>"
        + "<td width='50px' style='text-align: center' valign='middle'><button title='Remove' class='btn btn-info btn-danger fa fa-remove' onclick='delete_row(" + i + ")'></button></td>");
        $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
        i++;
        $("#product_id").val('');
        $("#product_name").val('');
        $('#purchase_rate').val("");
        $('#sales_rate').val("");
        $('#gst').val("");
        $('#uom').val("");
        $('#mfg_date').val("");
        $('#exp_date').val("");
        $('#stock_qty').val("");
        $('#new_quantity').val("");
        $('#total_qty').val("");
        $('#barcode_type').val("");
        $("#product_id").focus();

    }

    function delete_row(row) {
        $("#addr" + (row)).remove();
        var num = 1;
        for (var j = 0; j < i; j++) {
            //console.log($("#addr"+(j)).html());
            if ($("#addr" + (j)).html() != undefined) {
                $('#addr' + j + ' .sl_no').html(num);
                num++;
            }
        }

        $("#product_id").val("");
        $("#product_id").focus();

    }

    function submit_data(e) {
        e.preventDefault();
        $('#loader').addClass('pacman');
        $("#save_button").prop("disabled", true);

        var item_id = $('input[name="item_id[]"]');
        var item_gst = $('input[name="item_gst[]"]');
        var item_purchase_rate = $('input[name="item_purchase_rate[]"]');
        var item_sales_rate = $('input[name="item_sales_rate[]"]');
        var item_mfg_date = $('input[name="item_mfg_date[]"]');
        var item_exp_date = $('input[name="item_exp_date[]"]');
        var item_quantity = $('input[name="item_quantity[]"]');
        var item_barcode_type = $('input[name="item_barcode_type[]"]');
        var item_id_length = item_id.length;
        if (item_id_length != 0) {
            var obj = new Array();
            for (var j = 0; j < item_id_length; j++) {
                var record = {
                    'item_id': item_id.eq(j).val(),
                    'item_gst': item_gst.eq(j).val(),
                    'item_purchase_rate': item_purchase_rate.eq(j).val(),
                    'item_sales_rate': item_sales_rate.eq(j).val(),
                    'item_mfg_date': item_mfg_date.eq(j).val(),
                    'item_exp_date': item_exp_date.eq(j).val(),
                    'item_quantity': item_quantity.eq(j).val(),
                    'item_barcode_type': item_barcode_type.eq(j).val()
                };
                obj.push(record);
            }
        } else {
            swal("Please Enter Product Data");
            $("#save_button").prop("disabled", false);
            return;
        }

        var save_data = JSON.stringify(obj);

        $.ajax({
            type: 'POST',
            url: 'SaveStockUpdate.php',
            data: {
                save_data: save_data
            },
            success: function (response) {
                for (var j = 0; j < i; j++) {
                    $("#addr" + (j)).html('');
                }
                i = 0;
                $('#loader').removeClass('pacman');
                $("#save_button").prop("disabled", false);

                if (response == '1') {
                    swal("Success!", "Stock Updated Successfully!", "success");
                    location.href = "StockList";
                } else {
                    swal({
                        title: "Oops!",
                        text: "Something Wrong...",
                        imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
                    });
                }
            }
        });
    }
</script>
</body>
</html>