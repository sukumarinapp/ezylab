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

$PageAccessible = IsPageAccessible($user_id, 'InvoiceBill');
$created = date("Y-m-d H:i:s");
$updated = date("Y-m-d H:i:s");
$patient_id = 0;

if (isset($_GET['patient_id'])) {
    $patient_id = DecodeVariable($_GET['patient_id']);
} else {
    $patient_id = 0;
}

if (isset($_POST['search'])) {
    $patient_id = Filter($_POST['patient_id']);
}


if (isset($_POST['add_patient'])) {

    $insert_patient = Insert(
        'macho_patient',
        array(
            'P_code' => Filter($_POST['P_code']),
            'prefix' => Filter($_POST['prefix']),
            'P_name' => Filter($_POST['P_name']),
            'address' => Filter($_POST['address']),
            'mobile' => Filter($_POST['mobile']),
            'email' => Filter($_POST['email']),
            'age' => Filter($_POST['age']),
            'age_type' => Filter($_POST['age_type']),
            'gender' => Filter($_POST['gender']),
            'height' => Filter($_POST['height']),
            'weight' => Filter($_POST['weight']),
            'blood_group' => Filter($_POST['blood_group']),
            'bp' => Filter($_POST['bp']),
            'created' => $created,
            'modified' => $updated
        )
    );

    if (is_int($insert_patient)) {
        $P_id = EncodeVariable($insert_patient);
        $notes = $_POST['P_name'] . ' patient details added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo "<script>window.location.href='PatientEntry?patient_id='.$P_id;</script>";
        exit;
    }
}
?>
<?php include ("headercss.php"); ?>
<title>Patient Bill</title>
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
			
            <h6>Patient Bill</h6>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <!-- START card-->
                <div class="card card-default">
                    <div class="card-header">
                        <div class="text-sm"></div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="patient_id" class="control-label">Patient ID</label>
                                        <select class="form-select" name="patient_id" id="patient_id"
                                            tabindex="1">
                                            <option value="0">Select Patient</option>
                                            <option value="new">New Patient</option>
                                            <?php
                                            $patientQuery = "SELECT * FROM macho_patient ORDER BY P_code DESC ";
                                            $patientResult = GetAllRows($patientQuery);
                                            foreach ($patientResult as $patientData) {
                                                echo "<option ";
                                                if ($patient_id == $patientData['id'])
                                                    echo " selected ";
                                                echo "value='" . $patientData['id'] . "'>" . $patientData['P_code'] . ' - ' . $patientData['prefix'] . $patientData['P_name'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="add" class="control-label">&nbsp;&nbsp;&nbsp;</label><br>
                                        <input class="btn btn-info form-control" type="submit" name="search"
                                            value="Search" tabindex="2" />
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php
                        if ($patient_id != 0) {
                            ?>
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Bill Date</label>
                                            <input value="<?php echo date("d-m-Y"); ?>" type="text" name="bill_date"
                                                class="form-control" id="bill_date" tabindex="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Bill No.</label>
                                            <input maxlength="50" id="bill_num" type="text" class="form-control"
                                                value="<?php echo GetBillNumber(); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label">Referred By</label>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <select class="form-control" name="ref_prefix" id="ref_prefix"
                                                        onchange="GetDoctorData();" tabindex="3" required>
                                                        <option value="Self.">Self.</option>
                                                        <option value="Dr.">Dr.</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="reference" id="reference"
                                                        tabindex="3" required>
                                                        <option value="Self">Self</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="product_id" class="control-label">Description</label>
                                            <select class="form-control" name="description" id="description">
                                                <option value="Consultation">Consultation</option>
                                                <option value="Pharmacy">Pharmacy</option>
                                            </select>
                                            <!-- <input type="text" id="description" class="form-control"
                                                name="description" maxlength="100"/> -->
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-1">
                                        <div class="form-group">
                                            <label style="text-align: right" for="rate" class="control-label">Rate</label>
                                            <input style="text-align: right" required="required" type="text" maxlength="6"
                                                size="4" name="rate" id="rate_id" class="form-control Number"
                                                placeholder="">
                                        </div>
                                    </div> -->
                                    <!--                                    <div class="col-md-1">-->
                                    <!--                                        <div class="form-group">-->
                                    <!--                                            <label for="gst"-->
                                    <!--                                                   class="control-label">GST%</label>-->
                                    <input type="hidden" name="gst_amount" id="gst_amount" value="">
                                    <input readonly required="required" type="hidden" maxlength="2" size="2" name="gst"
                                        id="gst" class="form-control Number" placeholder="">
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->
                                    <!-- <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="quantity" class="control-label">Qty</label>
                                            <input required="required" type="text" maxlength="2" size="2" pattern="\d*"
                                                name="quantity" id="quantity" class="form-control Number"
                                                onkeypress="return isNumberKey(event)" onkeyup="calculate_amount()"
                                                tabindex="4">
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="uom" class="control-label">Unit</label>
                                            <input required="required" type="text" maxlength="2" size="2"
                                                name="uom" id="uom" class="form-control Number" placeholder="">
                                        </div>
                                    </div> -->

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label style="text-align: right" for="amount"
                                                class="control-label">Amount</label>
                                            <input style="text-align: right" required="required" type="text" pattern="\d*"
                                                maxlength="20" size="6" name="amount" id="amount"
                                                class="form-control Number" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="add" class="control-label">&nbsp;</label>
                                            <input onclick="add_row()" class="btn btn-info form-control" type="button"
                                                id="add" value="Add" tabindex="6" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr style="background-color: #81888c;color:white">
                                                <td style="width: 20px" class="text-center">
                                                    S.No
                                                </td>
                                                <td style='text-align: center'>
                                                    Description
                                                </td>

                                                <!-- <td class="text-right">
                                                    Rate
                                                </td> -->
                                                <!--                                            <td class="text-right">-->
                                                <!--                                                GST%-->
                                                <!--                                            </td>-->
                                                <!-- <td class="text-right">
                                                    Qty
                                                </td> -->

                                                <td class="text-center">
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

                            <!-- <div class="row"> -->
                            <!--                                <div class="col-md-2">-->
                            <!--                                    <label class="control-label">CGST</label>-->
                            <!--                                </div>-->
                            <!--                                <div class="col-md-3 pull-right">-->
                            <input style="text-align: right" readonly type="hidden" name="cgst" id="cgst"
                                class="form-control">
                            <!--                                </div>-->
                            <!-- <div class="col-md-2">
                                    <label class="control-label">Home Visiting</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" type="text" name="home_visit" id="home_visit"
                                        class="form-control" onkeypress="return isNumberDecimalKey(event)"
                                        onkeyup="calculate_net_amount()">
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Total</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" readonly type="text" name="total_amount"
                                        id="total_amount" class="form-control">
                                </div>
                            </div> -->
                            <div class="row">
                                <!--                                <div class="col-md-2">-->
                                <!--                                    <label class="control-label">SGST</label>-->
                                <!--                                </div>-->
                                <!--                                <div class="col-md-3 pull-right">-->
                                <input style="text-align: right" readonly type="hidden" name="sgst" id="sgst"
                                    class="form-control">
                                <!--                                </div>-->
                                <!--                                <div class="col-md-2">&nbsp;</div>-->

                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label">Payment Method</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <select name="payment_method" id="payment_method" class="form-control">
                                        <option value="Cash">Cash</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Debit Card">Debit Card</option>
                                        <option value="Online Payment">Online Payment</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Demand Draft">Demand Draft</option>
                                    </select>
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Net Total</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input style="text-align: right" readonly type="text" name="net_amount" id="net_amount"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label">Reference No.</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input type="text" name="reference_no" id="reference_no" class="form-control"
                                        maxlength="100">
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Pay Amount</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input type="text" onkeypress="return isNumberDecimalKey(event)" name="pay_amount"
                                        id="pay_amount" class="form-control" value="0.00">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label">Created By</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input type="text" name="created_by" id="created_by" class="form-control"
                                        value="<?= $user; ?>" maxlength="100">
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label class="control-label">Balance Amount</label>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <input type="text" readonly name="balance_amount" id="balance_amount"
                                        class="form-control">
                                </div>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-labeled btn-secondary" type="button"
                                                onclick="location.href='InvoiceBill';">
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
                        <?php } ?>
                    </div>
                </div>
                <!-- END card-->
            </div>
        </div>
    </div>
</section>
</div>

<div class="modal fade" id="add_patient" tabindex="-1" role="dialog" aria-labelledby="myModalLabelLarge"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabelLarge">Create New Patient</h4>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="" enctype="multipart/form-data">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Patient ID</label>
                                                <input type="text" class="form-control" name="P_code" id="P_code"
                                                    value="<?php echo GetpatientCode(); ?>" readonly tabindex="1">
                                            </div>
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select class="form-control" name="gender" id="gender" required
                                                    tabindex="3">
                                                    <option>Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Trans Gender">Trans Gender</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Height</label>
                                                <input type="text" class="form-control" name="height" id="height"
                                                    maxlength="100" tabindex="5">
                                            </div>
                                            <div class="form-group">
                                                <label>Blood Group</label>
                                                <select class="form-control" id="blood_group" name="blood_group"
                                                    tabindex="7">
                                                    <option value="0">Enter Blood Group</option>
                                                    <?php
                                                    $BGQuery = "SELECT blood_group,symbol FROM macho_bloodgroup ORDER BY blood_group";
                                                    $BGResult = GetAllRows($BGQuery);
                                                    $BGCounts = count($BGResult);
                                                    if ($BGCounts > 0) {
                                                        foreach ($BGResult as $BGData) {
                                                            echo '<option ';
                                                            echo ' value="' . $BGData['symbol'] . '">' . $BGData['blood_group'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Mobile </label>
                                                <input type="text" class="form-control" name="mobile" id="mobile"
                                                    onkeypress="return isNumberKey(event)" maxlength="100" tabindex="9">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <select class="form-control" name="prefix" id="prefix"
                                                            tabindex="2" required>
                                                            <option value="Mr. ">Mr.</option>
                                                            <option value="Mrs. ">Mrs.</option>
                                                            <option value="Miss. ">Miss.</option>
                                                            <option value="Ms. ">Ms.</option>
                                                            <option value="Master. ">Master.</option>
                                                            <option value="Baby. ">Baby.</option>
                                                            <option value="Selvi. ">Selvi.</option>
                                                            <option value="Sr. ">Sr.</option>
                                                            <option value="Rev.Fr. ">Rev.Fr.</option>
                                                            <option value="Dr. ">Dr.</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="P_name"
                                                            id="P_name" maxlength="100" tabindex="2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Age</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="age" id="age"
                                                            maxlength="100" tabindex="4">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <select class="form-control" tabindex="4" id="age_type"
                                                            name="age_type">
                                                            <option value="Yrs">Years</option>
                                                            <option value="Mths">Months</option>
                                                            <option value="Wks">Weeks</option>
                                                            <option value="Days">Days</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Weight</label>
                                                <input type="text" class="form-control" name="weight" id="weight"
                                                    maxlength="100" tabindex="6">
                                            </div>
                                            <div class="form-group">
                                                <label>Blood Pressure</label>
                                                <input type="text" class="form-control" name="bp" id="bp"
                                                    maxlength="100" tabindex="8">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                    maxlength="100" tabindex="10">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea class="form-control" name="address" id="address"
                                                    maxlength="100" tabindex="11"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-primary" type="submit" name="add_patient"
                                                tabindex="12">
                                                Save
                                            </button>
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END card-->
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

	 
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

    $('#patient_id').change(function () {
        var title = $(this).val();
        if (title == 'new') {
            $('#add_patient').modal('show');
        }
    });
</script>
<script>
    function GetDoctorData() {
        var ref_prefix = $('#ref_prefix').val();
        $.ajax({
            url: "GetDoctorData.php",
            type: "post",
            data: { ref_prefix: ref_prefix },
            success: function (data) {
                $('#reference').html(data);
            }
        });
    }

    function calculate_amount() {
        // var rate = $('#rate_id').val();

        // var quantity = $('#quantity').val();

        var product_gst = $('#gst').val();
        if (isNaN(product_gst)) product_gst = 0.0;

        // if (quantity != "" && rate != "") {
        //     var amount = rate * quantity;

        //     $('#amount').val(amount);
        // } else {
        //     $('#amount').val("");
        // }

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

            var item_name = $("#description").val();
            if (item_name == "") {
                swal("Please Enter Description");
            }

            //var item_rate = $('#rate_id').val();

            var item_gst = $('#gst').val();
            var item_gst_amount = $('#gst_amount').val();

            //var item_quantity = $('#quantity').val();
            //var item_uom = $('#uom').val();


            var amount2 = amount;

            $('#addr' + i).html("<td style='text-align: center' class='serial_num'><span class='sl_no'>" + (num + 1) + "</span></td>"
                + "<td style='text-align: center'><input value='" + i + "' name='item_id[]' type='hidden'>"
                + "<input value='" + item_name + "' name='item_name[]' type='hidden'>"
                + "<input value='" + item_gst + "' name='item_gst2[]' type='hidden'>"
                + "<input value='" + item_gst_amount + "' name='item_gst_amount[]' type='hidden'>"
                + "<input value='" + amount + "' name='item_amount[]' type='hidden'>"
                + item_name + "</td>"
                //+ "<td style='text-align: right'>" + item_rate + "</td>"
                //            + "<td style='text-align: right'>" + item_gst + " %</td>"
                //+ "<td style='text-align: right'>" + item_quantity + " " + item_uom + "</td>"
                + "<td style='text-align: center'>" + amount + "</td>"
                + "<td width='50px' style='text-align: center' valign='middle'><button title='Remove' class='btn btn-info btn-danger fa fa-remove' onclick='delete_row(" + i + ")'></button></td>");
            $('#tab_logic').append('<tr class="row_class" id="addr' + (i + 1) + '"></tr>');
            i++;
            set_fix();
        } else {
            swal("Please Enter Description");
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
        var net_amount = 0.0;
        var item_id = $('input[name="item_id[]"]');
        var item_amount = $('input[name="item_amount[]"]');
        // var item_gst_amount2 = $('input[name="item_gst_amount[]"]');
        var item_id_length = item_id.length;
        //var gst_amount = 0.0;
        for (var j = 0; j < item_id_length; j++) {
            var itm_amt = 0;
            //var item_gst_amount = 0;
            if (item_amount.eq(j).val() != undefined) {
                itm_amt = parseFloat(item_amount.eq(j).val())
                // if (item_gst_amount2.eq(j).val() != undefined) {
                //     item_gst_amount = parseFloat(item_gst_amount2.eq(j).val())
                //     gst_amount = gst_amount + item_gst_amount;
                // }
                net_amount = net_amount + itm_amt;
            }
        }
        // var gst_tax = (gst_amount / 2);
        // $('#cgst').val(gst_tax);
        // $('#sgst').val(gst_tax);
        // $('#total_amount').val(net_amount);
        //$('#net_amount').val(+net_amount + +gst_amount);
        $('#net_amount').val(net_amount);
        $("#item_id").val('');
        $("#description").val('');
        // $('#rate_id').val("");
        //$('#gst').val("");
        //$('#quantity').val("");
        //$('#uom').val("");
        $('#amount').val("");
    }

    function calculate_net_amount() {
        var home_visit = $('#home_visit').val();
        var cgst = $('#cgst').val();
        var sgst = $('#sgst').val();
        var total_amount = $('#total_amount').val();

        var net_amount = +home_visit + +cgst + +sgst + +total_amount;
        $('#net_amount').val(net_amount);
    }

    $("input[name='pay_amount']").keyup(function () {
        var pay_amount = $('#pay_amount').val();
        var net_amount = $('#net_amount').val();

        $('#balance_amount').val(pay_amount - net_amount);
    });

    function submit_data() {
        var net_amount = parseFloat($('#net_amount').val());
        if (isNaN(net_amount) || net_amount < 0) {
            swal("Net Total should be greater than zero");
            return;
        } else {
            var bill_num = $('#bill_num').val();
            var bill_date = $('#bill_date').val();
            var patient_id = $('#patient_id').val();
            var ref_prefix = $('#ref_prefix').val();
            var reference = $('#reference').val();
            var total_amount = DecimalPoint($('#total_amount').val());
            var cgst_tax = DecimalPoint($('#cgst').val());
            var sgst_tax = DecimalPoint($('#sgst').val());
            var home_visit = DecimalPoint($('#home_visit').val());
            var payment_method = $('#payment_method').val();
            var reference_no = $('#reference_no').val();
            var pay_amount = $('#pay_amount').val();

            $("#save_button").prop("disabled", true);

            var item_id = $('input[name="item_id[]"]');
            var item_name = $('input[name="item_name[]"]');
            //var item_uom2 = $('input[name="item_uom[]"]');
            //var item_quantity = $('input[name="item_quantity[]"]');
            //var item_rate = $('input[name="item_rate[]"]');
            //var item_gst2 = $('input[name="item_gst2[]"]');
            var item_amount = $('input[name="item_amount[]"]');

            var item_id_length = item_id.length;

            var sales = new Array();

            for (var j = 0; j < item_id_length; j++) {
                var item_amount2 = item_amount.eq(j).val();
                if (item_amount2 != 0) {

                    var record = {
                        'item_id': item_id.eq(j).val(),
                        'item_name': item_name.eq(j).val(),
                        'item_amount': item_amount2
                    };
                    sales.push(record);
                }
            }

            var sales_data = JSON.stringify(sales);

            $.ajax({
                type: 'POST',
                url: 'SaveBill.php',
                data: {
                    bill_num: bill_num,
                    bill_date: bill_date,
                    patient_id: patient_id,
                    ref_prefix: ref_prefix,
                    reference: reference,
                    sales: sales_data,
                    amount: total_amount,
                    net_amount: net_amount,
                    cgst: cgst_tax,
                    sgst: sgst_tax,
                    home_visit: home_visit,
                    pay_amount: pay_amount,
                    payment_method: payment_method,
                    reference_no: reference_no
                },
                success: function (response) {

                    $('#cgst').val("");
                    $('#sgst').val("");
                    $('#total_amount').val("");
                    $('#home_visit').val("");
                    $('#net_amount').val("");
                    $('#pay_amount').val("");
                    $('#balance_amount').val("");
                    for (var j = 0; j < i; j++) {
                        $("#addr" + (j)).html('');
                    }
                    i = 0;
                    $("#save_button").prop("disabled", false);
                    location.href = "InvoiceBill";
                }
            });
        }
    }
</script>
</body>

</html>