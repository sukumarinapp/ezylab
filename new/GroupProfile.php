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

?>
<!doctype html>
<html lang="en">
<head>
    <?php include ("headercss.php"); ?>
    <title>Profile</title>
</head>
<body class="bg-theme bg-theme2">
    <?php 
    if (isset($_POST['submit'])) {

    $insert_test_category = Insert('macho_test_category', array(

        'dept_id' => Filter($_POST['dept_id']),
        'category_name' => Filter($_POST['category_name']),
        'description' => Filter($_POST['description']),
        'created' => $created,
        'modified' => $modified

    )
);

    if (is_int($insert_test_category)) {

        $notes = $_POST['category_name'] . ' Test Category added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

if (isset($_POST['update'])) {
    $category_id = Filter($_POST['id']);
    $update = Update('macho_test_category', 'id', $category_id, array(
        'dept_id' => Filter($_POST['dept_id']),
        'category_name' => Filter($_POST['category_name']),
        'description' => Filter($_POST['description']),
        'modified' => $modified
    )
);
    if ($update) {

        $notes = $_POST['category_name'] . ' Test Category modified by ' . $user;
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
            <h6>Profile</h6>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <!-- START card-->
                <div class="card card-default">
                    <div class="card-header">
                        <?php if ($PageAccessible['is_write'] == 1) { ?>
                            <div class="card-title pull-right">
                                <button class="btn btn-labeled float-end btn-danger" type="button"
                                onClick="location.href='AddGroupProfile';">Create New
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
                                        <th class="thead_data">Profile Name</th>
                                        <th class="thead_data">Description</th>
                                        <th class="thead_data">Amount</th>
                                        <th class="thead_data">Created</th>
                                        <th class="thead_data">Last Modified</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    $TestCategoryQuery = "SELECT * FROM macho_test_category WHERE type='group' ORDER BY id";
                                    $TestCategoryResult = GetAllRows($TestCategoryQuery);
                                    $TestCategoryCounts = count($TestCategoryResult);
                                    if ($TestCategoryCounts > 0) {
                                        foreach ($TestCategoryResult as $TestCategoryData) { ?>
                                            <tr>
                                                <td class="tbody_data">
                                                    <?= ++$no; ?>
                                                </td>
                                                <td class="tbody_data">&nbsp;
                                                    <?= $TestCategoryData['category_name']; ?>
                                                </td>
                                                <td class="tbody_data">&nbsp;
                                                    <?= $TestCategoryData['description']; ?>
                                                </td>
                                                <td class="tbody_data">&nbsp;
                                                    <?= $TestCategoryData['amount']; ?>
                                                </td>
                                                <td class="tbody_data">&nbsp;
                                                    <?= $TestCategoryData['created']; ?>
                                                </td>
                                                <td class="tbody_data">&nbsp;
                                                    <?= $TestCategoryData['modified']; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                                            <button class="btn btn-sm btn-info" type="button"
                                                            onClick="window.open('UpdateGroupProfile?cID=<?= EncodeVariable($TestCategoryData['id']); ?>');">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_delete'] == 1) { ?>
                                                        <button class="btn btn-sm btn-danger" type="button" title="Delete"
                                                        onclick="Delete('<?= $TestCategoryData['id'];?>','<?= $TestCategoryData['category_name'];?>');">
                                                        <i class="fa fa-trash"></i></button>
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
            url: "EditTestCategory.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_test_category').modal('show');
            }
        });
    }



     function Delete(profile_id, profile_name) {
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
            url: "DeleteGroupProfile.php",
            data: {
                profile_id: profile_id,
                profile_name: profile_name
            },
            success: function (response) {
                if (response == '1') {
                    swal("Deleted!", "Selected Data has been deleted!", "success");
                    location.href = "GroupProfile";
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