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
//ValidateAccessToken($user_id, $access_token);


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
    <title>Department</title>
</head>

<body class="bg-theme bg-<?php echo $colour ?>">
    <?php
    if (isset($_POST['submit'])) {

        $insert_test_category = Insert('macho_test_category', array(

            'dept_id' => Filter($_POST['dept_id']),
            'type' => 'single',
            'category_name' => Filter($_POST['category_name']),
            'description' => Filter($_POST['description']),
            'created' => $created,
            'modified' => $modified

        ));

        if (is_int($insert_test_category)) {

            $notes = $_POST['category_name'].' Test Category added by ' . $user;
            $receive_id = '1';
            $receive_role_id = GetRoleOfUser($receive_id);
            InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

            echo '<span id="insert_success"></span>';
        } else {
            echo '<span id="insert_failure"></span>';
        }
    }
    if (isset($_POST['update'])) {
        $category_id = Filter($_POST['id']);
        $update = Update('macho_test_category', 'id', $category_id, array(
            'dept_id' => Filter($_POST['dept_id']),
            'type' => 'single',
            'category_name' => Filter($_POST['category_name']),
            'description' => Filter($_POST['description']),
            'modified' => $modified
        ));
        if ($update) {

            $notes = $_POST['category_name'].' Test Category modified by ' . $user;
            $receive_id = '1';
            $receive_role_id = GetRoleOfUser($receive_id);
            InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

            echo '<span id="update_success"></span>';
        } else {
            echo '<span id="update_failure"></span>';
        }
    }

    ?>
    <div class="wrapper">
        <!--sidebar wrapper -->
        <?php include ("Menu.php"); ?>
        <!--end sidebar wrapper -->
        <!--start header -->

        <?php include ("header.php"); ?>

        <div class="page-wrapper">
            <div class="page-content">

                <div class="card">
                    <div class="card-header">
                        <p class="card-title fw-bolder">Department
                            <?php if ($PageAccessible['is_write'] == 1) { ?>
                                <button type="button" class="btn btn-danger  float-end" data-bs-toggle="modal" data-bs-target="#add_test_category">Create New</button>
                            <?php } ?>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="20px" class="thead_data">ID</th>
                                        <th class="thead_data">Department Name</th>
                                        <th class="thead_data">Description</th>
                                        <th class="thead_data">Created</th>
                                        <th class="thead_data">Last Modified</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    $TestCategoryQuery = "SELECT * FROM macho_test_category WHERE type='single' ORDER BY id";
                                    $TestCategoryResult = GetAllRows($TestCategoryQuery);
                                    $TestCategoryCounts = count($TestCategoryResult);
                                    if ($TestCategoryCounts > 0) {
                                        foreach ($TestCategoryResult as $TestCategoryData) { ?>
                                            <tr>
                                                <td class="tbody_data"><?= $TestCategoryData['id'] ?></td>
                                                <td class="tbody_data">&nbsp;<?= $TestCategoryData['category_name']; ?></td>
                                                <td class="tbody_data">&nbsp;<?= $TestCategoryData['description']; ?></td>
                                                <td class="tbody_data">&nbsp;<?= $TestCategoryData['created']; ?></td>
                                                <td class="tbody_data">&nbsp;<?= $TestCategoryData['modified']; ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                                            <button class="btn btn-sm btn-info" type="button"
                                                            onclick="ModalEdit(<?php echo $TestCategoryData['id']; ?>);">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_delete'] == 1) { ?>
                                                        <button class="btn btn-sm btn-danger" type="button" 
                                                        onclick="Delete('macho_test_category','id',<?php echo $TestCategoryData['id']; ?>);">
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
                    <div class="col">
                        <!-- Button trigger modal -->

                        <!-- Modal -->
                        <div class="modal fade" id="add_test_category" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-white">Create New Department</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <form method="post" action="TestCategory">
                                                    <input type="hidden" name="dept_id" id="dept_id" value="1">
                                                    <div class="card card-default">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label class="col-form-label">Department Name </label>
                                                                <input class="form-control" type="text" name="category_name" id="category_name"
                                                                maxlength="100"
                                                                tabindex="1" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-form-label">Description </label>
                                                                <textarea class="form-control" name="description" id="description"
                                                                maxlength="100" rows="4"
                                                                tabindex="2"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <div class="clearfix">
                                                                <div class="float-right">
                                                                    <button class="btn btn-primary" id="basicAlert" type="submit" name="submit" tabindex="3">
                                                                        Save
                                                                    </button>
                                                                    <button class="btn btn-secondary" type="button" class="close" data-bs-dismiss="modal">Cancel</button>
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
                        <div class="modal fade" id="edit_test_category" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Update Department</h4>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="edit_body">
                                    </div>
                                </div>
                            </div>
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
                            location.href = "TestCategory";
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
            swal("Success!", "New Test Category has been Added!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "Test Category has been Updated!", "success");
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
