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

$PageAccessible = IsPageAccessible($user_id, 'Patient');
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
<!-- Main section-->
<style>
    .fcheckbox-inline {
        /* border: solid 5px #F00; */
        padding: 2px 10px 5px;
        display: inline-block;
        position: relative;
    }

    .checkbox-inline label {
        display: block;
        white-space: nowrap;
    }

    .row-padded {
        background-color: #F7F7F7;
        padding: 1px;
        margin: 4px;
        border: 1px solid #DDD;
    }
</style>   
<?php include ("css.php"); ?>
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
            <div>Patient Entry</div>
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
                                        <select class="form-control select2" name="patient_id" id="patient_id"
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Entry Date</label>
                                        <input value="<?php echo date("d-m-Y"); ?>" type="text" name="bill_date"
                                        class="form-control" id="bill_date" tabindex="1">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Entry Time</label>
                                        <input maxlength="50" name="entry_time" id="entry_time" type="time"
                                        class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Bill No.</label>
                                        <input maxlength="50" id="bill_num" type="text" class="form-control"
                                        value="<?php echo GetBillNo(); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Referred By</label>

                                        <div class="row">
                                            <div class="col-md-5">
                                                <select class="form-control" name="ref_prefix" id="ref_prefix"
                                                onchange="GetDoctorData();" tabindex="3" required>
                                                <option value="Self.">Self.</option>
                                                <option value="Dr.">Dr.</option>
                                            </select>
                                        </div>
                                        <div class="col-md-7">
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
                        <div class="col-md-10">
                            <div class="form-group">
                                <select class="form-control select2" name="test_name" id="test_name"
                                onchange='GetTestData();'>
                                <option>Enter Test</option>
                                <?php
                                $TestCategoryQuery = "SELECT id,category_name,type FROM macho_test_category ORDER BY id";
                                $TestCategoryResult = GetAllRows($TestCategoryQuery);
                                foreach ($TestCategoryResult as $TestCategoryData) {
                                    $CategoryID = $TestCategoryData['id'];
                                    if($TestCategoryData['type']=="group"){
                                        echo "<option value='CategoryID_" . $CategoryID . "'>" . $TestCategoryData['category_name'] . "</option>";
                                    }
                                    $TestTypeQuery = "SELECT * FROM macho_test_type WHERE test_category='$CategoryID' ORDER BY id";
                                    $TestTypeResult = GetAllRows($TestTypeQuery);
                                    foreach ($TestTypeResult as $TestTypeData) {
                                        $test_id = $TestTypeData['id'];
                                        echo "<option  value='test_id_" . $TestTypeData['id'] . "'>" . $TestTypeData['test_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn btn-info form-control" type="button" id="testopen"
                            tabindex="2">Lab </button>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                            <label style="text-align: right;display:none" for="rate"
                            class="control-label">Rate</label>
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

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="add" class="control-label">&nbsp;</label>
                                        <input onclick="add_row()" class="btn btn-info form-control" type="button"
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
                            <!--                                <div class="col-md-2">-->
                                <!--                                    <label class="control-label">CGST</label>-->
                                <!--                                </div>-->
                                <!--                                <div class="col-md-3 pull-right">-->
                                    <input style="text-align: right" readonly type="hidden" name="cgst" id="cgst"
                                    class="form-control">
                                    <!--                                </div>-->
                                    <div class="col-md-2">
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
                                </div>
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
                                                id="pay_amount" class="form-control">
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
                                                        onclick="location.href='PatientEntry';">
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
                            <?php } ?>
                        </div>
                    </div>
                    <!-- END card-->
                </div>
            </div>
        </div>
    </section>
    <!-- Page footer-->
    <?php include_once 'footer.php'; ?>
</div>

<div class="modal fade" id="add_patient" tabindex="-1" role="dialog" aria-labelledby="myModalLabelLarge"
aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabelLarge">Create New Patient</h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
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
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
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

<div class="modal fade" id="testpopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabelLarge"
aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">

            </h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xl-12">
                    <form method="post" action="" enctype="multipart/form-data">
                        <!-- START card-->
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-departmenttest-tab" data-toggle="pill"
                                href="#pills-departmenttest" role="tab" aria-controls="pills-departmenttest"
                                aria-selected="false">Department</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-profiletest-tab" data-toggle="pill"
                                href="#pills-profiletest" role="tab" aria-controls="pills-profiletest"
                                aria-selected="false">Profile</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">

                            <div class="tab-pane fade show active" id="pills-departmenttest" role="tabpanel"
                            aria-labelledby="pills-departmenttest-tab">

                            <div class="row" style="height:200px;overflow-y:auto">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php   
                                        $sql = "SELECT * from macho_test_category  where type='single' order by id";
                                        $result = mysqli_query($GLOBALS['conn'], $sql);
                                        $rowcount=mysqli_num_rows($result);
                                        ?>
                                        <select class="form-control" name="test_name" id="department_name"
                                        size="<?php echo $rowcount ?>" onchange='show_dept_test()'>
                                        <?php

                                        while($row = mysqli_fetch_assoc($result)){
                                            ?>
                                            <option value="<?php echo $row['id'] ?>">
                                                <?php echo $row['category_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                $result = mysqli_query($GLOBALS['conn'], $sql);
                                while($row = mysqli_fetch_assoc($result)){
                                  $dept_id = $row['id'];
                                  ?>
                                  <div style="display:none" class="col-md-8 dept_test"
                                  id="dept_test_<?php echo $row['id'] ?>">
                                  <div class="checkbox-inline">
                                    <?php
                                    $sql = "SELECT * from macho_test_type  where test_category=$dept_id  order by id";
                                    $result1 = mysqli_query($GLOBALS['conn'], $sql);
                                    $test_category = -1;
                                    while($row1 = mysqli_fetch_assoc($result1)){
                                        ?>

                                        <div class="row row-padded">
                                            <label class="form-check-label col-sm-9"
                                            for="<?php echo $row1['id'] ?>"><?php echo $row1['test_name'] ?></label>
                                            <div class="col-sm-1">
                                                <input type="checkbox" data-test_id="<?php echo $row1['id'] ?>" data-test_name_price="<?php echo $row1['test_name']."  ".$row1["price"] ?>" data-test_name="<?php echo $row1["test_name"] ?>" data-test_price="<?php echo $row1["price"] ?>" data-test_type="test" data-test_category="<?php echo $row1["test_category"] ?>" id="<?php echo $row1['id'] ?>" class="poptest">
                                            </div>
                                        </div>
                                        <?php 

                                    } 
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-profiletest" role="tabpanel"
                aria-labelledby="pills-profiletest-tab">

                <div class="checkbox-inline" style="height:200px;overflow-y:auto">
                    <?php
                    $sql = "SELECT * from macho_test_category where type='group' order by id";
                    $result = mysqli_query($GLOBALS['conn'], $sql);
                    while($row = mysqli_fetch_assoc($result)){
                        ?>
                        <div class="row row-padded">
                            <label class="form-check-label col-sm-11 "
                            for="profile_<?php echo $row['id'] ?>"><?php echo $row['category_name'] ?></label>
                            <div class="col-sm-1">
                                <input class="poptest" data-test_id="profile_<?php echo $row['id'] ?>" data-test_name_price="<?php echo $row['category_name']."  ".$row["amount"] ?>" data-test_name="<?php echo $row["category_name"] ?>" data-test_price="<?php echo $row["amount"] ?>" data-test_type="group" data-test_category="<?php echo $row["id"] ?>" type="checkbox" id="profile_<?php echo $row['id'] ?>">
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
            <div style="margin-top:10px;margin-bottom:10px">
                <input class="form-control" id="tokenfield" value="" />

            </div>

        </div>
        <div class="card-footer">
            <div class="clearfix">


                <button class="btn btn-secondary" type="button" data-dismiss="modal">
                    Close
                </button>
                <button class="btn btn-primary float-lg-right" onclick="add_rows()" type="button" tabindex="12">
                    Add
                </button>
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

<div class="modal fade" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation Alert </h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="view_body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" onclick="location.href='PatientEntry';">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
   
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<script type="text/javascript" src="<?php echo JS; ?>bootstrap-tokenfield.js"></script>


<script>
    $('#tokenfield').tokenfield();

    var i = 0;
    var tokenarr = [];

    $('#tokenfield').on('tokenfield:removedtoken', function (e) {
    //alert('Token removed! Token value was: ' + e.attrs.value);
        var test_id = e.attrs.value;
        const idxObj = tokenarr.findIndex(object => {
          return object.value === test_id;
      });
        tokenarr.splice(idxObj, 1);
        $("#"+test_id).prop("checked",false);
    });

    $('.poptest').click(function() {
        var test_id = $(this).attr("data-test_id");
        var test_name_price = $(this).attr("data-test_name_price");
        var test_name = $(this).attr("data-test_name");
        var test_price = $(this).attr("data-test_price");
        var test_type = $(this).attr("data-test_type");
        var test_category= $(this).attr("data-test_category");
        if(this.checked){
            tokenarr.push({value : test_id , label : test_name_price , testname : test_name , testprice : test_price , testtype : test_type , testcategory : test_category});
        }else{
            const idxObj = tokenarr.findIndex(object => {
              return object.value === test_id;
          });
            tokenarr.splice(idxObj, 1);
        }
        $('#tokenfield').tokenfield('setTokens', tokenarr);
    });

    function add_rows(){
        i = 0 ;
        $("#tbody_data").html("<tr class='row_class' id='addr0'></tr>");
        $.each(tokenarr, function(index, value) {
          var test_id = value.value;
          var test_type = value.testtype;
          var test_name = value.testname;
          if(test_type == "group"){
            test_id = test_id.substring(8);
          }
          var test_price = value.testprice;
          var test_category = value.testcategory;
          var test_gst = parseFloat('0');
          var test_gst_amount = "";
          var test_quantity = 1;
          var test_uom = "LF";
          
          $('#addr' + i).html("<td style='text-align: center' class='serial_num'><span class='sl_no'>" + (i + 1) +
            "</span></td>" +
            "<td style='text-align: left'><input value='" + test_id + "' name='item_id[]' type='hidden'>" +
            "<input value='" + test_type + "' name='item_type[]' type='hidden'>" +
            "<input value='" + test_name + "' name='item_name[]' type='hidden'>" +
            "<input value='" + test_category + "' name='item_category[]' type='hidden'>" +
            "<input value='" + test_price + "' name='item_rate[]' type='hidden'>" +
            "<input value='" + test_gst + "' name='item_gst2[]' type='hidden'>" +
            "<input value='" + test_gst_amount + "' name='item_gst_amount[]' type='hidden'>" +
            "<input value='" + test_quantity + "' name='item_quantity[]' type='hidden'>" +
            "<input value='" + test_uom + "' name='item_uom[]' type='hidden'>" +
            "<input value='" + test_price + "' name='item_amount[]' type='hidden'>" +
            test_name + "</td>" +
            "<td style='text-align: right'>" + test_price + "</td>" +
            "<td width='50px' style='text-align: center' valign='middle'><button title='Remove' class='btn btn-info btn-danger fa fa-remove' onclick='delete_row(" +
            i + ")'></button></td>");
          $('#tab_logic').append('<tr class="row_class" id="addr' + (i + 1) + '"></tr>');
          i++;
      });
      set_fix();
      home_visit=0;
      var home_visit = $('#home_visit').val().toString().trim();
      var total_amount = $('#total_amount').val();
      net_amount = total_amount;
      if(home_visit != ""){
        net_amount = parseInt(home_visit) + parseInt(total_amount);
      }
      $('#net_amount').val(net_amount);
      $("#testpopup").modal("hide");
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function DecimalPoint(x) {
        return Number.parseFloat(x).toFixed(2);
    }

    $('#patient_id').change(function() {
        var title = $(this).val();
        if (title == 'new') {
            $('#add_patient').modal('show');
        }
    });

    function show_dept_test() {
        var dept_id = $("#department_name").val();
        $(".dept_test").hide();
        $("#dept_test_" + dept_id).show();
    //alert(dept_id);
    }

    $('#testopen').click(function() {
        $('#testpopup').modal('show');
    });

    $(document).ready(function() {
        $("#multipletest").select2({
            closeOnSelect: false,
            placeholder: "Placeholder",
        // allowHtml: true,
            allowClear: true,
        tags: true // создает новые опции на лету
    });
    });
</script>
<script>
    function GetDoctorData() {
        var ref_prefix = $('#ref_prefix').val();
        $.ajax({
            url: "GetDoctorData.php",
            type: "post",
            data: {
                ref_prefix: ref_prefix
            },
            success: function(data) {
                $('#reference').html(data);
            }
        });
    }

    function GetTestData() {
        var test_ID = $('#test_name').val();

        $.ajax({
            url: "GetTestData.php",
            type: "post",
            data: {
                test_ID: test_ID
            },
            success: function(data) {

                if (data != 0) {
                    var TestData = JSON.parse(data);

                    $('#item_type').val(TestData[0]["item_type"]);
                    $('#item_id').val(TestData[0]["test_ID"]);
                    $('#description').val(TestData[0]["test_name"]);
                    $('#item_category').val(TestData[0]["test_category"]);
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

            if (item_type == 'test') {
                var item_id = $("#item_id").val();
                if (duplicate_check(item_id)) {
                    return;
                }
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

        // if (item_type == 'Category') {
        //     load_bill_products(i, num, item_id);
        //     return;
        // }

            $('#addr' + i).html("<td style='text-align: center' class='serial_num'><span class='sl_no'>" + (num + 1) +
                "</span></td>" +
                "<td style='text-align: left'><input value='" + item_id + "' name='item_id[]' type='hidden'>" +
                "<input value='" + item_type + "' name='item_type[]' type='hidden'>" +
                "<input value='" + item_name + "' name='item_name[]' type='hidden'>" +
                "<input value='" + item_category + "' name='item_category[]' type='hidden'>" +
                "<input value='" + item_rate + "' name='item_rate[]' type='hidden'>" +
                "<input value='" + item_gst + "' name='item_gst2[]' type='hidden'>" +
                "<input value='" + item_gst_amount + "' name='item_gst_amount[]' type='hidden'>" +
                "<input value='" + item_quantity + "' name='item_quantity[]' type='hidden'>" +
                "<input value='" + item_uom + "' name='item_uom[]' type='hidden'>" +
                "<input value='" + amount + "' name='item_amount[]' type='hidden'>" +
                item_name + "</td>" +
                "<td style='text-align: right'>" + amount + "</td>" +
                "<td width='50px' style='text-align: center' valign='middle'><button title='Remove' class='btn btn-info btn-danger fa fa-remove' onclick='delete_row(" +
                i + ")'></button></td>");
            $('#tab_logic').append('<tr class="row_class" id="addr' + (i + 1) + '"></tr>');
            i++;
            set_fix();
        } else {
            swal("Please Enter Description");
            $("#code-scan").focus();
            return;
        }
    }

    function load_bill_products(table_id, sl_no, test_category) {

        $.ajax({
            type: 'POST',
            url: 'GetTestCategoryData.php',
            data: {
                table_id: table_id,
                sl_no: sl_no,
                test_category: test_category
            },
            success: function(response) {
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
        var gst_tax = (gst_amount / 2);
        $('#cgst').val(gst_tax);
        $('#sgst').val(gst_tax);
        $('#total_amount').val(net_amount);
        $('#net_amount').val(net_amount);
        $("#item_id").val('');
        $("#description").val('');
        $('#rate_id').val("");
        $('#gst').val("");
        $('#quantity').val("");
        $('#uom').val("");
        $('#amount').val("");
        $("#code-scan").val("");
        $("#code-scan").focus();
    }

    function calculate_net_amount() {
        var home_visit = $('#home_visit').val();
        var total_amount = $('#total_amount').val();
        var net_amount = total_amount;
        if(home_visit != ""){
            net_amount = parseInt(home_visit) + parseInt(total_amount);
        }
        $('#net_amount').val(net_amount);
    }

    $("input[name='pay_amount']").keyup(function() {
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
            var entry_time = $('#entry_time').val();
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
            var item_type = $('input[name="item_type[]"]');
            var item_category = $('input[name="item_category[]"]');
            var item_name = $('input[name="item_name[]"]');
            var item_uom2 = $('input[name="item_uom[]"]');
            var item_quantity = $('input[name="item_quantity[]"]');
            var item_rate = $('input[name="item_rate[]"]');
            var item_gst2 = $('input[name="item_gst2[]"]');
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
                        'item_gst': item_gst2.eq(j).val(),
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
                url: 'SavePatientEntry.php',
                data: {
                    bill_num: bill_num,
                    bill_date: bill_date,
                    patient_id: patient_id,
                    ref_prefix: ref_prefix,
                    reference: reference,
                    entry_time: entry_time,
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
                success: function(response) {
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
                //location.href = "PatientEntry";
                    $('#view_body').html(response);
                    $('#modal_view').modal('show');
                }
            });
        }
    }

    function PrintBill(id) {

        $.ajax({
            type: 'POST',
            url: 'PrintBillData.php',
            data: {
                id: id
            },
            success: function(response) {
                $.ajax({
                    type: 'POST',
                    url: 'http://localhost/lims/POSBILL.php',
                    data: {
                        print_data: response
                    },
                    success: function(data) {}
                });

                location.href = "Patient";
            }
        });
    }
</script>
</body>

</html>