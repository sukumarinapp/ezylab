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

$PageAccessible = IsPageAccessible($user_id, $page);
$today = date("Y-m-d");
$created = date("Y-m-d H:i:s");
$modified = date("Y-m-d H:i:s");

if (isset($_POST['submit'])) {

    $insert_gst_type = Insert('macho_gst_types', array(

        'description' => Filter($_POST['description']),
        'created' => $created,
        'modified' => $modified

    ));

    if (is_int($insert_gst_type)) {

        $notes = $_POST['description'] . ' GST Type added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

if (isset($_POST['update'])) {
    $gst_type_id = Filter($_POST['id']);
    $update = Update('macho_gst_types', 'id', $gst_type_id, array(
        'description' => Filter($_POST['description']),
        'modified' => $modified
    ));
    if ($update) {

        $notes = $_POST['description'] . ' GST Type modified by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="update_success"></span>';
    } else {
        echo '<span  id="update_failure"></span>';
    }
}
?>
<?php include ("css.php"); ?>
<title>GST Types</title>
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
            <div>GST Types
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
                                <button class="btn btn-labeled btn-secondary" type="button" data-toggle="modal"
                                        data-target="#add_gst_type">Create New
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
                                <th class="thead_data">Description</th>
                                <th class="thead_data">Created</th>
                                <th class="thead_data">Last Modified</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 0;
                            $GSTTypeQuery = "SELECT * FROM macho_gst_types ORDER BY id";
                            $GSTTypeResult = GetAllRows($GSTTypeQuery);
                            $GSTTypeCounts = count($GSTTypeResult);
                            if ($GSTTypeCounts > 0) {
                                foreach ($GSTTypeResult as $GSTTypeData) { ?>
                                    <tr>
                                        <td class="tbody_data"><?= ++$no; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $GSTTypeData['description']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $GSTTypeData['created']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $GSTTypeData['modified']; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                                    <button class="btn btn-info" type="button"
                                                            onclick="ModalEdit(<?php echo $GSTTypeData['id']; ?>);">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </button>
                                                <?php }
                                                if ($PageAccessible['is_delete'] == 1) { ?>
                                                    <button class="btn btn-danger" type="button"
                                                            onclick="Delete('macho_gst_types','id',<?php echo $GSTTypeData['id']; ?>);">
                                                        <i class="fa fa-trash-o"></i>
                                                        Delete
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
                <!-- END card-->
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php'; ?>
</div>
<div class="modal fade" id="add_gst_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Create New GST Type</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="GSTType">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="col-form-label">Description </label>
                                        <input class="form-control" type="text" name="description" id="description"
                                               maxlength="100"
                                               tabindex="1" required>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-primary" type="submit" name="submit" tabindex="3">
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
<div class="modal fade" id="edit_gst_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update GST Type Details</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
        </div>
    </div>
</div>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
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
            url: "EditGSTType.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_gst_type').modal('show');
            }
        });
    }

    function Delete(table, key, id) {
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
                        url: "Delete.php",
                        data: {
                            table: table,
                            key: key,
                            id: id
                        },
                        success: function (response) {
                            if (response == '1') {
                                swal("Deleted!", "Selected Data has been deleted!", "success");
                                location.href = "GSTType";
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

    window.onload = function () {

        if (document.getElementById('insert_success')) {
            swal("Success!", "New GST Type has been Added!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "GST Type has been Updated!", "success");
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
</body>
</html>