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
$today = date("Y-m-d");
$created = date("Y-m-d H:i:s");
$modified = date("Y-m-d H:i:s");

$theme = "SELECT * FROM macho_users WHERE id ='$user_id'";
$TestTypeResult = mysqli_query($GLOBALS['conn'], $theme) or die(mysqli_error($GLOBALS['conn']));
$TestTypeData = mysqli_fetch_assoc($TestTypeResult);
$colour = $TestTypeData['colour'];
?>

<!doctype html>
<html lang="en">

<head>
    <?php include ("headercss.php"); ?>
    <title>Terms and Conditions</title>
</head>


<body class="bg-theme bg-<?php echo $colour ?>">
    <?php 
    if (isset($_POST['submit'])) {

    $insert_terms = Insert('macho_terms', array(

        'description' => Filter($_POST['description']),
        'created' => $created,
        'modified' => $modified

    ));

    if (is_int($insert_terms)) {

        $notes = $_POST['description'].' Terms added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

if (isset($_POST['update'])) {
    $terms_id = Filter($_POST['id']);
    $update = Update('macho_terms', 'id', $terms_id, array(
        'description' => Filter($_POST['description']),
        'modified' => $modified
    ));
    if ($update) {

        $notes = $_POST['description'].' Terms modified by ' . $user;
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
	
	<div class="page-wrapper">
			<div class="page-content">
			
            <h6>Terms & Conditions</h6>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <!-- START card-->
                <div class="card card-default">
                    <div class="card-header">
                        <?php if ($PageAccessible['is_write'] == 1) { ?>
                            <div class="card-title pull-right">
                                <button class="btn btn-labeled btn-danger" type="button" data-bs-toggle="modal"
                                        data-bs-target="#add_terms">Create New
                                <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                           </span></button>
                            </div>
                        <?php } ?>
                        <div class="text-sm"></div>
                    </div>
                    <div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example2" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th width="20px" class="thead_data">#</th>
                                <th class="thead_data">Description</th>
                                <th class="thead_data">Created</th>
                                <th class="thead_data">Last Modified</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 0;
                            $TermsQuery = "SELECT * FROM macho_terms ORDER BY id";
                            $TermsResult = GetAllRows($TermsQuery);
                            $TermsCounts = count($TermsResult);
                            if ($TermsCounts > 0) {
                                foreach ($TermsResult as $TermsData) { ?>
                                    <tr>
                                        <td class="tbody_data"><?= ++$no; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $TermsData['description']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $TermsData['created']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $TermsData['modified']; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                                    <button class="btn btn-sm btn-info" type="button"
                                                            onclick="ModalEdit(<?php echo $TermsData['id']; ?>);">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                <?php }
                                                if ($PageAccessible['is_delete'] == 1) { ?>
                                                    <button class="btn btn-sm btn-danger" type="button"
                                                            onclick="Delete('macho_terms','id',<?php echo $TermsData['id']; ?>);">
                                                        <i class="fa fa-trash"></i>
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
                <!-- END card-->
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
</div>

<div class="modal fade" id="add_terms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Create New Terms & Conditions</h4>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="TermsConditions">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="col-form-label">Description </label>
                                         <textarea class="form-control" rows="5" name="description" id="description"
                                                   maxlength="500"></textarea>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-primary" type="submit" name="submit" tabindex="3">
                                                Save
                                            </button>
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"
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
<div class="modal fade" id="edit_terms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update Terms & Conditions</h4>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
        </div>
    </div>
</div>

	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
	<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>


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
    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    $(function () {
        //Date picker
        $('#start_date').datepicker({
            autoclose: true
        });

        $('#end_date').datepicker({
            autoclose: true
        });
    });

    function ModalEdit(id) {
        $.ajax({
            type: "POST",
            url: "EditTermsConditions.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_terms').modal('show');
            }
        });
    }

    function Delete(table, key, id) {
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
            url: "Delete.php",
            data: {
                table: table,
                key: key,
                id: id
            },
            success: function (response) {
                if (response == '1') {
                    swal("Deleted!", "Selected Data has been deleted!", "success");
                    location.href = "TermsConditions";
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

    window.onload = function () {

        if (document.getElementById('insert_success')) {
            swal("Success!", "New Terms & Conditions has been Added!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "Terms & Conditions has been Updated!", "success");
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