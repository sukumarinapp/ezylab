<?php
session_start();
include_once "booster/bridge.php";
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

$PageAccessible = IsPageAccessible($user_id, 'GroupProfile');

?>
<?php include ("headercss.php"); ?>
<title>Create New Profile</title>
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
			
            <h6>Create New Profile</h6>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <!-- START card-->
                <div class="card card-default">
                    <div class="card-header">
                        <div class="text-sm"></div>
                    </div>
                    <div class="card-body">
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select class="form-select" name="test_name" id="test_name"
                                                onchange='GetTestData();'>
                                                <option>Enter Test</option>
                                                <?php
                                                $TestCategoryQuery = "SELECT id,category_name FROM macho_test_category where type='single' ORDER BY id";
                                                $TestCategoryResult = GetAllRows($TestCategoryQuery);
                                                foreach ($TestCategoryResult as $TestCategoryData) {
                                                    $CategoryID = $TestCategoryData['id'];
                                                    echo "<option value='CategoryID_" . $CategoryID . "'>" . $TestCategoryData['category_name'] . "</option>";

                                                    $TestTypeQuery = "SELECT * FROM macho_test_type WHERE test_category='$CategoryID' ORDER BY id";
                                                    $TestTypeResult = GetAllRows($TestTypeQuery);
                                                    foreach ($TestTypeResult as $TestTypeData) {
                                                        $test_id = $TestTypeData['id'];
                                                        echo "<option  value='test_id_" . $TestTypeData['id'] . "'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $TestTypeData['test_name'] . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="product_id" class="control-label">Description</label>
                                            <input type="hidden" name="item_type" id="item_type">
                                            <input type="hidden" name="item_id" id="item_id">
                                            <input type="hidden" name="item_category" id="item_category">
                                            <input readonly type="text" id="description" class="form-control"
                                                name="description" />
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label style="text-align: right;display:none" for="rate" class="control-label">Rate</label>
                                            <input style="text-align: right" required="required" type="hidden" maxlength="6"
                                                size="4" name="rate" id="rate_id" class="form-control Number"
                                                placeholder="">
                                        </div>
                                    </div>
                                    <!--                                    <div class="col-md-1">-->
                                    <!--                                        <div class="form-group">-->
                                    <!--                                            <label for="gst"-->
                                    <!--                                                   class="control-label">GST%</label>-->
                                    <input type="hidden" name="gst_amount" id="gst_amount" value="">
                                    <input readonly required="required" type="hidden" maxlength="2" size="2" name="gst"
                                        id="gst" class="form-control Number" placeholder="">
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label style="display:none" for="quantity" class="control-label">Qty</label>
                                            <input required="required" type="hidden" maxlength="2" size="2" pattern="\d*"
                                                name="quantity" id="quantity" class="form-control Number"
                                                onkeypress="return isNumberKey(event)" onkeyup="calculate_amount()"
                                                tabindex="4">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label style="display:none" for="uom" class="control-label">Unit</label>
                                            <input readonly required="required" type="hidden" maxlength="2" size="2"
                                                name="uom" id="uom" class="form-control Number" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label style="text-align: right" for="amount"
                                                class="control-label">Amount</label>
                                            <input style="text-align: right" readonly required="required" type="text"
                                                pattern="\d*" maxlength="2" size="4" name="amount" id="amount"
                                                class="form-control Number" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-1 mt-3">
                                        <div class="form-group">
                                            <label for="add" class="control-label">&nbsp;</label>
                                            <input onclick="add_row()" class="btn btn-success" type="button"
                                                id="add" value="Add" tabindex="6" />
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
                                                    Description
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
                                    <label class="control-label">Profile Name</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" type="text" name="profile_name" id="profile_name"
                                        class="form-control">
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Total Amount</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" readonly type="text" name="total_amount"
                                        id="total_amount" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                <label class="col-form-label">Notes </label>
                                        <textarea class="form-control" name="notes" id="notes"
                                               maxlength="100" rows="5"></textarea>
                                </div>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-labeled btn-secondary" type="button"
                                                onclick="location.href='GroupProfile';">
                                                <span class="btn-label"><i class="fa fa-arrow-left"></i>
                                                </span>Back to List
                                            </button>
                                            <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                                <button class="btn btn-labeled btn-primary" type="submit" name="submit"
                                                    id="save_button" onclick="submit_data();" tabindex="9">
                                                    <span class="btn-label"><i class="fa fa-check"></i>
                                                    </span>Save
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- END card-->
            </div>
        </div>
    </div>
</section>
</div>

   <?php include ("js.php"); ?>


	<script>
		$(document).ready(function() {
			$('#Transaction-History').DataTable({
				lengthMenu: [[6, 10, 20, -1], [6, 10, 20, 'Todos']]
			});
		  } );
	</script>
	<script src="assets/js/index.js"></script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script>
		new PerfectScrollbar('.product-list');
		new PerfectScrollbar('.customers-list');
	</script>

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

    function DecimalPoint(x) {
        return Number.parseFloat(x).toFixed(2);
    }
</script>
<script>
    function GetTestData() {
        var test_ID = $('#test_name').val();
        $.ajax({
            url: "GetTestData.php",
            type: "post",
            data: { test_ID: test_ID },
            success: function (data) {
                if (data != 0) {
                    var TestData = JSON.parse(data);
                    
                    $('#item_type').val(TestData[0]["item_type"]);
                    $('#item_id').val(TestData[0]["test_ID"]);
                    $('#description').val(TestData[0]["test_name"]);
                    $('#test_category').val(TestData[0]["test_category"]);
                    $('#rate_id').val(TestData[0]["price"]);
                    $('#quantity').val('1');
                    $('#gst').val(parseFloat('0'));
                    $('#uom').val('LS');
                    $('#amount').val(TestData[0]["price"]);
                    calculate_amount();
                }
            }
        });
    }

    function calculate_amount() {
        var rate = $('#rate_id').val();

        var quantity = $('#quantity').val();

        var product_gst = $('#gst').val();
        if (isNaN(product_gst)) product_gst = 0.0;

        if (quantity != "" && rate != "") {
            var amount = rate * quantity;

            $('#amount').val(amount);
        } else {
            $('#amount').val("");
        }

        var amount2 = $('#amount').val();
        var sales_net_amount = ((amount2 * 100) / (100 + +product_gst));
        var sales_tax_amount = amount2 - sales_net_amount;
        $('#gst_amount').val(sales_tax_amount);
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

            var item_type = $('#item_type').val();

            var item_id = $("#item_id").val();
            if (duplicate_check(item_id)) {
                return;
            }
            var item_name = $("#description").val();
            if (item_name == "") {
                swal("Please Enter Description");
            }

            var item_category = $('#item_category').val();

            var item_rate = $('#rate_id').val();

            var item_gst = $('#gst').val();
            var item_gst_amount = $('#gst_amount').val();

            var item_quantity = $('#quantity').val();
            var item_uom = $('#uom').val();


            var amount2 = item_rate * item_quantity;

            if (item_type == 'Category') {
                load_bill_products(i,num,item_id);
                return;
            }

            $('#addr' + i).html("<td style='text-align: center' class='serial_num'><span class='sl_no'>" + (num + 1) + "</span></td>"
                + "<td style='text-align: left'><input value='" + item_id + "' name='item_id[]' type='hidden'>"
                + "<input value='" + item_type + "' name='item_type[]' type='hidden'>"
                + "<input value='" + item_name + "' name='item_name[]' type='hidden'>"
                + "<input value='" + item_category + "' name='item_category[]' type='hidden'>"
                + "<input value='" + item_rate + "' name='item_rate[]' type='hidden'>"
                + "<input value='" + item_gst + "' name='item_gst2[]' type='hidden'>"
                + "<input value='" + item_gst_amount + "' name='item_gst_amount[]' type='hidden'>"
                + "<input value='" + item_quantity + "' name='item_quantity[]' type='hidden'>"
                + "<input value='" + item_uom + "' name='item_uom[]' type='hidden'>"
                + "<input value='" + amount + "' name='item_amount[]' type='hidden'>"
                + item_name + "</td>"
                + "<td style='text-align: right'>" + amount + "</td>"
                + "<td width='50px' style='text-align: center' valign='middle'><button title='Remove' class='btn btn-info btn-danger' onclick='delete_row(" + i + ")'><em class='fa fa-trash'></em></</button></td>");
            $('#tab_logic').append('<tr class="row_class" id="addr' + (i + 1) + '"></tr>');
            i++;
            set_fix();
        } else {
            swal("Please Enter Description");
            $("#code-scan").focus();
            return;
        }
    }

    function load_bill_products(table_id,sl_no,test_category) {
     
        $.ajax({
            type: 'POST',
            url: 'GetTestCategoryData.php',
            data: {
                table_id: table_id,
                sl_no: sl_no,
                test_category: test_category
            },
            success: function (response) {
                $('#tab_logic').append(response);
                set_fix();
            }
        });
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
        var net_amount = 0.0;
        var item_id = $('input[name="item_id[]"]');
        var item_amount = $('input[name="item_amount[]"]');
        var item_gst_amount2 = $('input[name="item_gst_amount[]"]');
        var item_id_length = item_id.length;
        var gst_amount = 0.0;
        for (var j = 0; j < item_id_length; j++) {
            var itm_amt = 0;
            var item_gst_amount = 0;
            if (item_amount.eq(j).val() != undefined) {
                itm_amt = parseFloat(item_amount.eq(j).val())
                if (item_gst_amount2.eq(j).val() != undefined) {
                    item_gst_amount = parseFloat(item_gst_amount2.eq(j).val())
                    gst_amount = gst_amount + item_gst_amount;
                }
                net_amount = net_amount + itm_amt;
            }
        }
        $('#total_amount').val(net_amount);
        $("#code-scan").val("");
        $("#code-scan").focus();
    }

    function submit_data() {
        var net_amount = parseFloat($('#total_amount').val());
        if (isNaN(net_amount) || net_amount < 0) {
            swal("Total should be greater than zero");
            return;
        } else {
            var profile_name = $('#profile_name').val();
            if(profile_name == ""){
                swal("Please enter Profile Name");
                return;
            }
            var total_amount = $('#total_amount').val();
            var notes = $('#notes').val();
   
            $("#save_button").prop("disabled", true);

            var item_id = $('input[name="item_id[]"]');
            var item_type = $('input[name="item_type[]"]');
            var item_category = $('input[name="item_category[]"]');
            var item_name = $('input[name="item_name[]"]');
            var item_uom2 = $('input[name="item_uom[]"]');
            var item_quantity = $('input[name="item_quantity[]"]');
            var item_rate = $('input[name="item_rate[]"]');
            var item_amount = $('input[name="item_amount[]"]');

            var item_id_length = item_id.length;

            var sales = new Array();

            for (var j = 0; j < item_id_length; j++) {
                var item_amount2 = item_amount.eq(j).val();
                if (item_amount2 != 0) {

                    var record = {
                        'item_id': item_id.eq(j).val(),
                        'item_type': item_type.eq(j).val(),
                        'item_category': item_category.eq(j).val(),
                        'item_name': item_name.eq(j).val(),
                        'item_rate': item_rate.eq(j).val(),
                        'item_quantity': item_quantity.eq(j).val(),
                        'item_uom': item_uom2.eq(j).val(),
                        'item_amount': item_amount2
                    };
                    sales.push(record);
                }
            }

            var sales_data = JSON.stringify(sales);

            $.ajax({
                type: 'POST',
                url: 'SaveProfileEntry.php',
                data: {
                    profile_name: profile_name,
                    notes: notes,
                    net_amount: total_amount,
                    sales: sales_data
                },
                success: function (response) {
                    $('#total_amount').val("");
                    for (var j = 0; j < i; j++) {
                        $("#addr" + (j)).html('');
                    }
                    i = 0;
                    $("#save_button").prop("disabled", false);
                    location.href = "GroupProfile";
                }
            });
        }
    }
</script>
</body>

</html>