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

$PageAccessible = IsPageAccessible($user_id, $page);
?>

<!doctype html>
<html lang="en">

<head>

<!-- Main section-->
<style>
    .ellipsis {
        max-width: 40px;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
</style>
<?php include ("headercss.php"); ?>
<title>Reports</title>
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
        <h6>Reports</h6></div>
<div class="card">
                    <div class="card-body">

        <div role="tabpanel">
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active bg-danger" href="#home1" aria-controls="home1" role="tab" data-bs-toggle="tab">Pending
                        Test</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link bg-success" href="#profile1" aria-controls="profile1" role="tab" data-bs-toggle="tab">Completed
                        Test</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link bg-info" href="#profile2" aria-controls="profile2" role="tab"
                        data-bs-toggle="tab">Reports</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="home1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped my-4 w-100" id="datatable1">
                            <thead>
                                <tr>
                                    <th width="20">#</th>
                                    <th>Date</th>
                                    <th>Bill No.</th>
                                    <th>Patient Name</th>
                                    <th>Bill Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Balance Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 0;
                                $bill_type = "patient_entry";
                                $BillQuery = "SELECT * FROM patient_entry WHERE test_status	 ='0' ORDER BY id DESC ";
                                $BillResult = GetAllRows($BillQuery);
                                $BillCounts = count($BillResult);
                                if ($BillCounts > 0) {
                                    foreach ($BillResult as $BillData) {
                                        $patient_id = $BillData['patient_id'];
                                        $PatientData = SelectParticularRow('macho_patient', 'id', $patient_id);

                                        $BillId = $BillData['id'];
                                        $BillAmount = $BillData['net_amount'];
                                        $PaidAmount = GetCustomerPaidAmount($patient_id, $BillId, $bill_type);
                                        $BalanceAmount = $BillAmount - $PaidAmount;
                                        ?>
                                        <tr>
                                            <td width="20">
                                                <?= ++$no; ?>
                                            </td>
                                            <td>
                                                <?= from_sql_date($BillData['entry_date']); ?>
                                            </td>
                                            <td>
                                                <?= $BillData['bill_no']; ?>
                                            </td>
                                            
                                            <td>
                                                <?= $PatientData['prefix'] . $PatientData['P_name']; ?>
                                            </td>
                                            <td>
                                                <?= $BillAmount; ?>
                                            </td>
                                            <td>
                                                <?= $PaidAmount; ?>
                                            </td>
                                            <td>
                                                <?= $BalanceAmount; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <?php
                                                    if ($PageAccessible['is_read'] == 1) { ?>
                                                        <button class="btn btn-success"
                                                            onClick="window.open('BillPdf?bID=<?= EncodeVariable($BillData['id']); ?>');"
                                                            title="View"><em class="fa fa-eye"></em>
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_write'] == 1) { ?>
                                                        <button class="btn btn-info" title="Test Entry"
                                                            onClick="window.open('AddTestEntry?eID=<?= EncodeVariable($BillData['id']); ?>');">
                                                            <em class="fa fa-heartbeat"></em></button>
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
                <div class="tab-pane" id="profile1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped my-4 w-100" id="datatable2">
                            <thead>
                                <tr>
                                    <th width="20">#</th>
                                    <th>Date</th>
                                    <th>Bill No.</th>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Bill Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Balance Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 0;
                                $bill_type = "patient_entry";
                                $BillQuery = "SELECT * FROM patient_entry WHERE test_status	 ='1' ORDER BY id DESC ";
                                $BillResult = GetAllRows($BillQuery);
                                $BillCounts = count($BillResult);
                                if ($BillCounts > 0) {
                                    foreach ($BillResult as $BillData) {
                                        $patient_id = $BillData['patient_id'];
                                        $PatientData = SelectParticularRow('macho_patient', 'id', $patient_id);

                                        $BillId = $BillData['id'];
                                        $BillAmount = $BillData['net_amount'];
                                        $PaidAmount = GetCustomerPaidAmount($patient_id, $BillId, $bill_type);
                                        $BalanceAmount = $BillAmount - $PaidAmount;
                                        ?>
                                        <tr>
                                            <td width="20">
                                                <?= ++$no; ?>
                                            </td>
                                            <td>
                                                <?= from_sql_date($BillData['entry_date']); ?>
                                            </td>
                                            <td>
                                                <?= $BillData['bill_no']; ?>
                                            </td>
                                            <td>
                                                <?= $PatientData['P_code']; ?>
                                            </td>
                                            <td>
                                                <?= $PatientData['prefix'] . $PatientData['P_name']; ?>
                                            </td>
                                            <td>
                                                <?= $BillAmount; ?>
                                            </td>
                                            <td>
                                                <?= $PaidAmount; ?>
                                            </td>
                                            <td>
                                                <?= $BalanceAmount; ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane" id="profile2" role="tabpanel">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped my-4 w-100" id="datatable3">
                            <thead>
                                <tr>
                                    <th width="20">#</th>
                                    <th>Date</th>
                                    <th>Bill No.</th>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Amount</th>
                                    <th class="text-center">Action</th>
                                    <th>Header</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                $bill_type = "patient_entry";
                                $BillQuery = "SELECT * FROM patient_entry WHERE test_status ='1' ORDER BY id DESC ";
                                $BillResult = GetAllRows($BillQuery);
                                $BillCounts = count($BillResult);
                                if ($BillCounts > 0) {
                                    foreach ($BillResult as $BillData) {
                                        $patient_id = $BillData['patient_id'];
                                        $PatientData = SelectParticularRow('macho_patient', 'id', $patient_id);

                                        $BillId = $BillData['id'];
                                        $BillAmount = $BillData['net_amount'];
                                        $PaidAmount = GetCustomerPaidAmount($patient_id, $BillId, $bill_type);
                                        $BalanceAmount = $BillAmount - $PaidAmount;
                                        ?>
                                        <tr>
                                            <td width="20">
                                                <?= ++$no; ?>
                                            </td>
                                            <td>
                                                <?= from_sql_date($BillData['entry_date']); ?>
                                            </td>
                                            <td>
                                                <?= $BillData['bill_no']; ?>
                                            </td>
                                            <td>
                                                <?= $PatientData['P_code']; ?>
                                            </td>
                                            <td>
                                                <?= $PatientData['prefix'] . $PatientData['P_name']; ?>
                                            </td>
                                            <td>
                                                <?= $BillAmount; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <?php
                                                    if ($PageAccessible['is_read'] == 1) { ?>
                                                        <button class="btn btn-success" title="View"
                                                            onClick="show_header('<?= EncodeVariable($BillData['id']); ?>','<?= $BillData['id'] ?>')"><i
                                                                class="fa fa-search-plus"></i> View
                                                        </button>
                                                        <?php
                                                    } ?>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="checkbox" value="1" id="header_<?= $BillData['id']; ?>" />
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
    </div>
</div>
</section>
<!-- Page footer-->
</div>

<div class="modal fade" id="add_test_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Create New Test Type</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="TestEntry">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Test Code </label>
                                                <input class="form-control" type="text" name="test_code" id="test_code"
                                                    value="<?= GetTestCode(); ?>" maxlength="100" tabindex="1" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Price </label>
                                                <input class="form-control" type="text" name="price" id="price"
                                                    maxlength="100" onkeypress="return isNumberDecimalKey(event)"
                                                    tabindex="3" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Lower Limit </label>
                                                <input class="form-control" type="text" name="lower_limit"
                                                    id="lower_limit" maxlength="100" tabindex="5" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Method </label>
                                                <input class="form-control" type="text" name="method" id="method"
                                                    maxlength="100" tabindex="7">
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Type of Test </label>
                                                <select class="form-control" name="type_test" id="type_test"
                                                    tabindex="8">
                                                    <option value='Normal'>Normal</option>
                                                    <option value='Sub Heading'>Sub Heading</option>
                                                    <option value='Paragraph'>Paragraph</option>
                                                    <option value='Table'>Table</option>
                                                    <option value='Date'>Date</option>
                                                    <option value='Time'>Time</option>
                                                    <option value='Image'>Image</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Units </label>
                                                <select class="form-control" name="units" id="units" tabindex="9">
                                                    <?php
                                                    $MeasurementQuery = "SELECT * FROM macho_uom ORDER BY measurement";
                                                    $MeasurementData = GetAllRows($MeasurementQuery);
                                                    foreach ($MeasurementData as $Measurements) {
                                                        echo "<option value='" . $Measurements['symbol'] . "'>" . $Measurements['symbol'] . "</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Critical Info </label>
                                                <textarea class="form-control" name="critical_info" id="critical_info"
                                                    maxlength="500" rows="9" tabindex="11"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Test Name </label>
                                                <input class="form-control" type="text" name="test_name" id="test_name"
                                                    maxlength="100" tabindex="2" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Remarks </label>
                                                <input class="form-control" type="text" name="remarks" id="remarks"
                                                    maxlength="100" tabindex="4">
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Upper Limit </label>
                                                <input class="form-control" type="text" name="upper_limit"
                                                    id="upper_limit" maxlength="100" tabindex="6" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Interpretations </label>
                                                <textarea class="form-control" name="interpretation" id="interpretation"
                                                    maxlength="500" rows="9" tabindex="10"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Comments </label>
                                                <textarea class="form-control" name="comments" id="comments"
                                                    maxlength="500" rows="9" tabindex="12"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-primary" type="submit" name="submit" tabindex="13">
                                                Save
                                            </button>
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal"
                                                tabindex="4">
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
    $(document).ready(function () {
        $('#datatable1').dataTable();

        $('#datatable2').dataTable();

        $('#datatable3').dataTable();

    });

    function show_header(id,id2){
        var url = "TestReceipt?bID="+id;
        var header = "&header=0";
        const isChecked = $("#header_" + id2).is(":checked");
        if(isChecked){
            header = "&header=1";
        }
        url = url + header;
        window.open(url);
    }

    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>
<script>
$(document).ready(function() {
	  $('#example').DataTable()
	});
	
		$(document).ready(function() {
			var table = $('#example2').DataTable( {
				lengthChange: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
		 
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
</script>
</body>

</html>