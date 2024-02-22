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


?>
<!doctype html>
<html lang="en">

<head>
<?php include ("headercss.php"); ?>
<title>Doctors</title>
</head>
<body class="bg-theme bg-theme2">
    <?php
    if (isset($_POST['add_doctor'])) {

    $insert_doctor = Insert('doctors', array(
        'prefix' => Filter($_POST['prefix']),
        'd_name' => Filter($_POST['d_name']),
        'address' => Filter($_POST['address']),
        'mobile' => Filter($_POST['mobile']),
        'email' => Filter($_POST['email']),
        'qualification' => Filter($_POST['qualification']),
        'dept_id' => Filter($_POST['dept_id']),
        'id_number' => Filter($_POST['id_number']),
        'created' => $created,
        'modified' => $modified
    ));

    if (is_int($insert_doctor)) {

        $notes = $_POST['d_name'] . ' Doctor details added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

if (isset($_POST['update'])) {

    $doctor_id = Filter($_POST['doctor_id']);

    $update = Update('doctors', 'id', $doctor_id, array(
        'prefix' => Filter($_POST['prefix']),
        'd_name' => Filter($_POST['d_name']),
        'address' => Filter($_POST['address']),
        'mobile' => Filter($_POST['mobile']),
        'email' => Filter($_POST['email']),
        'qualification' => Filter($_POST['qualification']),
        'dept_id' => Filter($_POST['dept_id']),
        'id_number' => Filter($_POST['id_number']),
        'modified' => $modified
    ));

    if ($update) {

        $notes = $_POST['d_name'] . ' doctor details modified by ' . $user;
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
	  
			
            <h6>Doctors</h6>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Doctors Report','0','0');"><i class="fa fa-print"></i> Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Doctors Report','0','0');"><i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Doctors Report','0','0');"><i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title pull-right">
                    <?php if ($PageAccessible['is_write'] == 1) { ?>
                        <button class="btn btn-labeled btn-danger float-end" type="button" title="Add Doctor"
                                data-bs-toggle="modal"
                                data-bs-target="#add_doctor">
                            Add New
                        <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                           </span></button>
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
                            <th class="thead_data">Qualification</th>
                            <th class="thead_data">Mobile</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $patientQuery = "SELECT * FROM doctors ORDER BY id DESC ";
                        $patientResult = GetAllRows($patientQuery);
                        $patientCounts = count($patientResult);
                        if ($patientCounts > 0) {
                            foreach ($patientResult as $patientData) { ?>
                                <tr>
                                    <td width="20" class="tbody_data"><?= ++$no; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $patientData['id_number']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $patientData['prefix'].$patientData['d_name']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $patientData['qualification']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $patientData['mobile']; ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                                <button class="btn btn-sm btn-info" type="button" title="Edit"
                                                        onclick="ModalEdit(<?= $patientData['id']; ?>);">
                                                    <em class="fa fa-edit"></em>
                                                </button>
                                            <?php }
                                            if ($PageAccessible['is_delete'] == 1) { ?>
                                                <button class="btn btn-sm btn-danger" type="button" title="Delete"
                                                        onclick="Delete(<?php echo $patientData['id']; ?>,'<?= $patientData['d_name']; ?>');">
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

<div class="modal fade" id="edit_doctor" tabindex="-1" role="dialog" aria-labelledby="myModalLabelLarge"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabelLarge">Edit Doctor Details</h4>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_doctor" tabindex="-1" role="dialog" aria-labelledby="myModalLabelLarge"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabelLarge">Create New Doctor</h4>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="Doctors" enctype="multipart/form-data">
                            <input type="hidden" name="dept_id" id="dept_id" value="1" />
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ID Number</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="id_number"
                                                       id="id_number"
                                                       tabindex="1">
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                <textarea rows="1" class="form-control"
                                          name="address"
                                          id="address"
                                          maxlength="100"
                                          tabindex="7"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Mobile </label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="mobile"
                                                       id="mobile" onkeypress="return isNumberKey(event)"
                                                       maxlength="10"
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
                                                            <option value="Reffer Lab. ">Reffer Lab</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="d_name"
                                                               id="d_name"
                                                               maxlength="100"
                                                               tabindex="2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Qualification</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="qualification"
                                                       id="qualification"
                                                       maxlength="100"
                                                       tabindex="4">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email"
                                                       class="form-control"
                                                       name="email"
                                                       id="email"
                                                       maxlength="100"
                                                       tabindex="6">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-primary" type="submit" name="add_doctor"
                                                    tabindex="8">
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
            autoclose: true
        });
    });

    $( "#dob" ) .change(function () {
        var dob= $("#dob").val();
        console.log(dob);

        dob = new Date(dob);

        var today = new Date();
        console.log(today);

        var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
        console.log(age);
        $('#age').val(age);
        $('#age').focus();
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
            url: "EditDoctor.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_doctor').modal('show');
            }
        });
    }

    function Delete(id, doctorname) {
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
            url: "DeleteDoctor.php",
            data: {
                id: id,
                d_name: doctorname
            },
            success: function (response) {
                if (response == '1') {
                    swal("Deleted!", "Selected Doctor Data has been deleted!", "success");
                    location.href = "Doctors";
                } else {
                    swal({
                        title: "Oops!",
                        text: "Something Wrong...",
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
            swal("Success!", "New Doctor details Added Successfully!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                type: "error"
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "Doctor Details has been Updated!", "success");
        }

        if (document.getElementById('update_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }
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