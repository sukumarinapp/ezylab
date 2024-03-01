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
$created = date("Y-m-d H:i:s");
$modified = date("Y-m-d H:i:s");
$created_date = date("Y-m-d");

// echo $colour;die;

?>
<!doctype html>
<html lang="en">

<head>
<?php include ("headercss.php"); ?>
<title>Patient</title>
</head>
<?php
$validation = false;
$today = date("Y-m-d");

$sql = "select * from  software_validation where from_date <= '$today' and to_date >= '$today'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    $validation = true;
}

$theme = "SELECT * FROM macho_users WHERE id ='$user_id'";
$TestTypeResult = mysqli_query($GLOBALS['conn'], $theme) or die(mysqli_error($GLOBALS['conn']));
$TestTypeData = mysqli_fetch_assoc($TestTypeResult);
$colour = $TestTypeData['colour'];

?>

<body class="bg-theme bg-<?php echo $colour ?>">
<!-- <body class="bg-theme bg-theme2"> -->
    <?php
    if (isset($_POST['add_patient'])) {

    $insert_patient = Insert('macho_patient', array(
        'P_code' => Filter($_POST['P_code']),
        'prefix' => Filter($_POST['prefix']),
        'P_name' => Filter($_POST['P_name']),
        'address' => Filter($_POST['address']),
        'mobile' => Filter($_POST['mobile']),
        'email' => Filter($_POST['email']),
        'dob' => to_sql_date($_POST['dob']),
        'age' => Filter($_POST['age']),
        'age_type' => Filter($_POST['age_type']),
        'gender' => Filter($_POST['gender']),
        'blood_group' => Filter($_POST['blood_group']),
        'ob_number' => Filter($_POST['ob_number']),
        'room_number' => Filter($_POST['room_number']),
        'id_card_type' => Filter($_POST['id_card_type']),
        'id_number' => Filter($_POST['id_number']),
        'created' => $created,
        'modified' => $modified
    ));

    if (is_int($insert_patient)) {

        $notes = $_POST['P_name'] . ' patient details added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

if (isset($_POST['update'])) {


    $patient_id = Filter($_POST['patient_id']);

    $update = Update('macho_patient', 'id', $patient_id, array(
        'prefix' => Filter($_POST['prefix']),
        'P_name' => Filter($_POST['P_name']),
        'address' => Filter($_POST['address']),
        'mobile' => Filter($_POST['mobile']),
        'email' => Filter($_POST['email']),
        'dob' => to_sql_date($_POST['dob']),
        'age' => Filter($_POST['age']),
        'age_type' => Filter($_POST['age_type']),
        'gender' => Filter($_POST['gender']),
        'blood_group' => Filter($_POST['blood_group']),
        'ob_number' => Filter($_POST['ob_number']),
        'room_number' => Filter($_POST['room_number']),
        'id_card_type' => Filter($_POST['id_card_type']),
        'id_number' => Filter($_POST['id_number']),
        'modified' => $modified
    ));

    if ($update) {

        $notes = $_POST['P_name'] . ' patient details modified by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="update_success"></span>';
    } else {
        echo '<span  id="update_failure"></span>';
    }
}
?>
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
            <h6>Patients</h6>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Patients Report','0','0');"><i class="fa fa-print"></i> Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Patients Report','0','0');"><i
                                class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Patients Report','0','0');"><i
                                class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title pull-right">
                <?php if ($validation) { ?>
                    <?php if ($PageAccessible['is_write'] == 1) { ?>
                        <button class="btn btn-labeled btn-danger float-end" type="button" title="Add Patient"
                                data-bs-toggle="modal"
                                data-bs-target="#add_patient">
                            Add New
                            <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                           </span></button>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th width="20px" class="thead_data">#</th>
                            <th class="thead_data">ID</th>
                            <th class="thead_data">Name</th>
                            <th class="thead_data">Gender</th>
                            <th class="thead_data">Age</th>
                            <th class="thead_data">Mobile</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $patientQuery = "SELECT * FROM macho_patient ORDER BY P_code DESC ";
                        $patientResult = GetAllRows($patientQuery);
                        $patientCounts = count($patientResult);
                        if ($patientCounts > 0) {
                            foreach ($patientResult as $patientData) { ?>
                                <tr>
                                    <td width="20" class="tbody_data"><?= ++$no; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $patientData['P_code']; ?></td>
                                    <td class="tbody_data">
                                        &nbsp;<?= $patientData['prefix'] . $patientData['P_name']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $patientData['gender']; ?></td>
                                    <td class="tbody_data">
                                        &nbsp;<?= $patientData['age'] . ' ' . $patientData['age_type']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $patientData['mobile']; ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($PageAccessible['is_write'] == 1) { ?>
                                                <button class="btn btn-primary" type="button"
                                                        title="Patient Log"
                                                        onClick="document.location.href='PatientLog?patient_id=<?php echo EncodeVariable($patientData['id']); ?>'">
                                                    <em class="fa fa-address-book"></em>
                                                </button>
                                                <?php if ($validation) { ?>
                                                <button class="btn btn-success" type="button"
                                                        title="Patient Entry"
                                                        onClick="document.location.href='PatientEntry?patient_id=<?php echo EncodeVariable($patientData['id']); ?>'">
                                                    <em class="fa fas fa-flask"></em>
                                                </button>
                                                <?php } ?>
                                            <?php }
                                            if ($PageAccessible['is_modify'] == 1) { ?>
                                                <button class="btn btn-info" type="button" title="Edit"
                                                        onclick="ModalEdit(<?= $patientData['id']; ?>);">
                                                    <em class="fa fa-edit"></em>
                                                </button>
                                            <?php }
                                            if ($PageAccessible['is_delete'] == 1) { ?>
                                                <button class="btn btn-danger" type="button" title="Delete"
                                                        onclick="Delete(<?php echo $patientData['id']; ?>,'<?= $patientData['P_name']; ?>');">
                                                    <em class="fa fa-trash"></em>
                                                </button>
                                            <?php } ?>
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
        </div>
        </div>
    </div>
</section>
</div>

<div class="modal fade" id="edit_patient" tabindex="-1" role="dialog" aria-labelledby="myModalLabelLarge"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabelLarge">Edit Patient Details</h4>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
        </div>
    </div>
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
                        <form method="post" action="Patient" enctype="multipart/form-data">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Patient ID</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="P_code"
                                                       id="P_code" value="<?php echo GetpatientCode(); ?>"
                                                       readonly
                                                       tabindex="1">
                                            </div>
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select required class="form-control" name="gender" id="gender" required
                                                        tabindex="3">
                                                    <option value="">Select Gender</option>
                                                    <option selected value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Trans Gender">Trans Gender</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                               <label>Mobile </label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="mobile"
                                                       id="mobile" onkeypress="return isNumberKey(event)"
                                                       maxlength="10" 
                                                       tabindex="11">
                                            </div>
                                            <div class="form-group">
                                                <label>OP Number</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="ob_number"
                                                       id="ob_number"
                                                       maxlength="100"
                                                       tabindex="7">
                                            </div>
                                            <div class="form-group">
                                                <label>ID Card</label>
                                                <select class="form-control" tabindex="9"
                                                        id="id_card_type"
                                                        name="id_card_type">
                                                    <option value="">Select Identity Card Type</option>
                                                    <option value="Aadhaar card">Aadhaar card</option>
                                                    <option value="Driving licence">Driving licence</option>
                                                    <option value="Electoral Photo Identity Card">Electoral Photo
                                                        Identity Card
                                                    </option>
                                                    <option value="passport no">passport</option>
                                                    <option value="Permanent account number">Permanent account number
                                                    </option>
                                                    <option value="Ration card">Ration card</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input type="date"
                                                       class="form-control"
                                                       name="dob"
                                                       id="dob" autocomplete="off"
                                                       maxlength="100" 
                                                       tabindex="5">
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <select class="form-control" name="prefix" id="prefix"
                                                                tabindex="2"
                                                                required>
                                                            <option value="Mr.">Mr.</option>
                                                            <option value="Mrs.">Mrs.</option>
                                                            <option value="Miss.">Miss.</option>
                                                            <option value="Ms.">Ms.</option>
                                                            <option value="Master.">Master.</option>
                                                            <option value="Baby.">Baby.</option>
                                                            <option value="Selvi.">Selvi.</option>
                                                            <option value="Sr.">Sr.</option>
                                                            <option value="Rev.Fr.">Rev.Fr.</option>
                                                            <option value="Dr.">Dr.</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="P_name"
                                                               id="P_name"
                                                               maxlength="100" required
                                                               tabindex="2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Blood Group</label>
                                                <select class="form-control" id="blood_group" name="blood_group"
                                                        tabindex="4">
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
                                                <label>Age</label>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="text"  
                                                               class="form-control number"
                                                               name="age"
                                                               id="age"
                                                               maxlength="2"
                                                               tabindex="6">
                                                    </div>
                                                    <div class="col-md-6">
                                                    
                                                        <select class="form-control"
                                                                tabindex="6" id="age_type"
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
                                                <label>Room Number</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="room_number"
                                                       id="room_number"
                                                       maxlength="100"
                                                       tabindex="8">
                                            </div>
                                            <div class="form-group">
                                                <label>ID Number</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="id_number"
                                                       id="id_number"
                                                       maxlength="100"
                                                       tabindex="10">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email"
                                                       class="form-control"
                                                       name="email"
                                                       id="email"
                                                       maxlength="100"
                                                       tabindex="12">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea class="form-control"
                                                          name="address"
                                                          id="address"
                                                          maxlength="100"
                                                          tabindex="13"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-primary" type="submit" name="add_patient"
                                                    tabindex="14">
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

    $(document).ready(function(){
      $("#prefix").on('change',function(){
        var prefix = $(this).val();
        if(prefix == "Mr." || prefix == "Master." || prefix == "Rev.Fr."){
          $("#gender").val("Male");
        }else if(prefix == "Mrs." || prefix == "Miss." || prefix == "Ms." || prefix == "Selvi." || prefix == "Sr."){
          $("#gender").val("Female");
        }else{
          $("#gender").val("");
        }
      });
    });
    
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
        $('#dob').datepicker({
            format:'dd-mm-yyyy',
            autoclose: true
        });
    });

    $("#dob").change(function () {
        var dob= $("#dob").val();

        $.ajax({
            url: "GetAge.php",
            type: "post",
            data: {birth_date: dob},
            success: function (data) {
                if (data == 0) {
                    swal('Undefined Value : ' + birth_date);
                } else {
                    var BirthData = JSON.parse(data);
            
                    $('#age').val(BirthData [0]["age"]);
                    $('#age_type').val(BirthData [0]["age_type"]);
                 
                }
            }
        });
    });

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

    function ModalEdit(id) {
        $.ajax({
            type: "POST",
            url: "Editpatient.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_patient').modal('show');
            }
        });
    }

    function Delete(id, patientname) {
        swal({
          title: 'Are you sure?',
          text: "You will not be able to recover this Entry!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!'
      }).then(function(result) {
        if(result.value){
          $.ajax({
            type: "POST",
            url: "Deletepatient.php",
            data: {
                id: id,
                P_name: patientname
            },
            success: function (response) {
                if (response == '1') {
                    swal("Deleted!", "Selected patient Data has been deleted!", "success");
                    location.href = "Patient";
                } else {
                    swal({
                        title: "Oops!",
                        tepxt: "Something Wrong...",
                        imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
                    });
                }
            }
        });
      }else{
        swal("Cancelled", "Your Entry Data is safe :)", "error");
    }
})
  }
</script>
<script>
    window.onload = function () {
        if (document.getElementById('insert_success')) {
            swal("Success!", "New Patient details Added Successfully!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                type: "error"
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "Patient Details has been Updated!", "success");
        }

        if (document.getElementById('update_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }
    }

    $('.number').keypress(function (event) {
        var keycode = event.which;
        if (!(event.shiftKey == false && (keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
        event.preventDefault();
        }
    });
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