<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);
$today = date("Y-m-d");
$created = date("Y-m-d h:i:sa");
$modified = date("Y-m-d h:i:sa");

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
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div>Profile
                <small></small>
            </div>
            <div class="ml-auto">
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <!-- START card-->
                <div class="card card-default">
                    <div class="card-header">
                        <?php if ($PageAccessible['is_write'] == 1) { ?>
                            <div class="card-title pull-right">
                                <button class="btn btn-labeled btn-secondary" type="button"
                                    onClick="location.href='AddGroupProfile';">Create New
                                    <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                                    </span></button>
                            </div>
                        <?php } ?>
                        <div class="text-sm"></div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped my-4 w-100" id="datatable1">
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
                                                        <button class="btn btn-info" type="button"
                                                            onClick="window.open('UpdateGroupProfile?cID=<?= EncodeVariable($TestCategoryData['id']); ?>');">
                                                            <i class="fa fa-edit"></i> Update
                                                        </button>
                                                    <?php }
                                                    if ($PageAccessible['is_delete'] == 1) { ?>
                                                        <button class="btn btn-danger" type="button" title="Delete"
                                                            onclick="Delete('<?= $TestCategoryData['id'];?>','<?= $TestCategoryData['category_name'];?>');">
                                                            <i class="fa fa-trash-o"></i> Delete</button>
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
                <!-- END card-->
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php'; ?>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.min.js"></script>
<script src="<?php echo JS; ?>jquery.redirect.js"></script>
<!-- BOOTSTRAP-->
<script src="<?php echo VENDOR; ?>popper.js/dist/umd/popper.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap/dist/js/bootstrap.js"></script>
<!-- STORAGE API-->
<script src="<?php echo VENDOR; ?>js-storage/js.storage.js"></script>
<!-- JQUERY EASING-->
<script src="<?php echo VENDOR; ?>jquery.easing/jquery.easing.js"></script>
<!-- ANIMO-->
<script src="<?php echo VENDOR; ?>animo/animo.js"></script>
<!-- SCREENFULL-->
<script src="<?php echo VENDOR; ?>screenfull/dist/screenfull.js"></script>
<!-- LOCALIZE-->
<script src="<?php echo VENDOR; ?>jquery-localize/dist/jquery.localize.js"></script>

<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<script src="<?php echo VENDOR; ?>bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<!-- Datatables-->
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
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
            title: "Are you sure?",
            text: "You will not be able to recover this Entry!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Yes!',
            cancelButtonText: "No!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
            function (isConfirm) {
                if (isConfirm) {
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

                } else {
                    swal("Cancelled", "Your Entry Data is safe :)", "error");
                }
            });
    }
</script>
</body>

</html>