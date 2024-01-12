<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);

if (isset($_POST['add_submit'])) {
    $created = date("Y-m-d");
    $receive_data = implode('~', $_POST['select2-multiple']);
    $description = $_POST['description'];
    $file_name = $_FILES['file']['name'];
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];

    $sql = "SELECT MAX(id) as id FROM macho_documents ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $counts = $data['id'];
    $file_id = $counts + 1;

    if (trim($file_name) != "") {
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name = $file_id . "." . $ext;
        $move_path = "Files/";
        $move_path = $move_path . $file_name;
        $target_path = SITEURL . "Files/";
        $target_path = $target_path . $file_name;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $move_path)) {
            $receive_array = array($receive_data);
            $things = array();
            for ($fl = 0; $fl < count($receive_array); $fl++) {
                $things = explode("~", $receive_array[$fl]);
                for ($sl = 0; $sl < count($things); $sl++) {
                    $receive_id = $things[$sl];
                    $insert_file = Insert('macho_documents', array(
                        'sender_id' => Filter($user_id),
                        'receive_id' => Filter($receive_id),
                        'description' => Filter($description),
                        'file_name' => Filter($file_name),
                        'file_url' => Filter($target_path),
                        'file_type' => Filter($file_type),
                        'file_size' => Filter($file_size),
                        'create_date' => $created
                    ));

                    $notes = $user . '  has Sent to ' . $description . ' Documents!....';
                    $receive_role_id = GetRoleOfUser($receive_id);
                    InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
                }
            }
            echo '<span id="insert_success"></span>';
        } else {
            echo '<span  id="insert_failure"></span>';
        }

    }

}

?>
<!-- Main section-->
<section class="section-container no-print">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">File Manager</div>
        <div class="card">
            <div class="card-header">
                <?php if ($PageAccessible['is_write'] == 1) { ?>
                    <div class="card-title pull-right">
                        <button class="btn btn-labeled btn-secondary" type="button"
                                data-toggle="modal" data-target="#add_new">
                            Add New File
                            <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                           </span></button>
                    </div>
                <?php } ?>
                <div class="text-sm"></div>
            </div>
            <div class="card-body">
                <div role="tabpanel">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item" role="presentation"><a class="nav-link active" href="#received_files"
                                                                    aria-controls="received_files"
                                                                    role="tab"
                                                                    data-toggle="tab">Received Files</a>
                        </li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#sent_files"
                                                                    aria-controls="sent_files"
                                                                    role="tab"
                                                                    data-toggle="tab">Sent Files</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="received_files" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-striped my-4 w-100" id="datatable1">
                                        <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Created By</th>
                                            <?php if ($PageAccessible['is_read'] == 1) { ?>
                                                <th>Action</th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $no = 0;
                                        $ReceiveQuery = "SELECT * FROM macho_documents WHERE receive_id= '$user_id' ORDER BY create_date DESC ";
                                        $ReceiveResult = GetAllRows($ReceiveQuery);
                                        $ReceiveCounts = count($ReceiveResult);
                                        if ($ReceiveCounts > 0) {
                                            foreach ($ReceiveResult as $ReceiveData) {
                                                ?>
                                                <tr>
                                                    <td><?php echo ++$no; ?></td>
                                                    <td><?php echo from_sql_date($ReceiveData['create_date']); ?></td>
                                                    <td><?php echo $ReceiveData['description']; ?></td>
                                                    <td><?php echo strtoupper(pathinfo($ReceiveData['file_name'], PATHINFO_EXTENSION)); ?></td>
                                                    <td><?php echo UserName($ReceiveData['sender_id']); ?></td>
                                                    <?php if ($PageAccessible['is_read'] == 1) { ?>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-sm btn-success" type="button"
                                                                        title="View / Download"
                                                                        onclick="window.open('<?php echo $ReceiveData['file_url']; ?>', '_blank');">
                                                                    <em class="fa fa-download"></em> View / Download
                                                                </button>
                                                            </div>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php
                                            }
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="sent_files" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-striped my-4 w-100" id="datatable2">
                                        <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Receive By</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $si_no = 0;
                                        $SentQuery = "SELECT * FROM macho_documents WHERE sender_id= '$user_id' ORDER BY create_date DESC ";
                                        $SentResult = GetAllRows($SentQuery);
                                        $SentCounts = count($SentResult);
                                        if ($SentCounts > 0) {
                                            foreach ($SentResult as $SentData) {
                                                ?>
                                                <tr>
                                                    <td><?php echo ++$si_no; ?></td>
                                                    <td><?php echo from_sql_date($SentData['create_date']); ?></td>
                                                    <td><?php echo $SentData['description']; ?></td>
                                                    <td><?php echo strtoupper(pathinfo($SentData['file_name'], PATHINFO_EXTENSION)); ?></td>
                                                    <td><?php echo UserName($SentData['receive_id']); ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <?php if ($PageAccessible['is_read'] == 1) { ?>
                                                                <button type="button" title="View / Download"
                                                                        onclick="window.open('<?php echo $SentData['file_url']; ?>', '_blank');"
                                                                        class="btn btn-sm btn-success"><em
                                                                        class="fa fa-download"></em> View / Download</button>
                                                            <?php }
                                                            if ($PageAccessible['is_delete'] == 1) { ?>
                                                                <button class="btn btn-sm btn-danger" type="button"
                                                                        title="Delete"
                                                                        onclick="Delete(<?php echo $SentData['id']; ?>);">
                                                                    <em class="fa fa-trash-o"></em>
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
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php'; ?>
</div>

<div class="modal fade" id="add_new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Upload New Files</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="FileManager" enctype="multipart/form-data">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="col-form-label">Sent To</label>
                                        <select class="form-control" name="select2-multiple[]" id="receive_id"
                                                tabindex="1" multiple required>
                                            <option>&nbsp;</option>
                                            <?php
                                            $UserQuery = 'SELECT id,concat(prefix," ",name) as name FROM macho_users ORDER BY id DESC ';
                                            $UserResult = GetAllRows($UserQuery);
                                            $UserCounts = count($UserResult);
                                            if ($UserCounts > 0) {
                                                foreach ($UserResult as $UserData) {
                                                    echo "<option value='" . $UserData['id'] . "'>" . $UserData['name'] . "</option>";
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Description</label>
                                        <input type="text" id="description" name="description"
                                               class="form-control" autocomplete="off"
                                               placeholder="Enter Description here... " maxlength="100"
                                               tabindex="2" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">File</label>
                                        <input class="form-control filestyle" type="file"
                                               id="file"
                                               name="file" data-input="false"
                                               data-classbutton="btn btn-secondary"
                                               data-classinput="form-control inline"
                                               data-text="Upload Documents"
                                               data-icon="&lt;span class='fa fa-upload mr'&gt;&lt;/span&gt;"
                                               tabindex="3">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-right">
                                            <button class="btn btn-primary" type="submit" name="add_submit"
                                                    tabindex="4">
                                                Upload
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
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.min.js"></script>
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
<!-- Datatables-->
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>

<script>
    $(document).ready(function () {
        $('#datatable1').DataTable();

        $('#datatable2').DataTable();

    });

    function Delete(id) {
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
                        url: "DeleteDocuments.php",
                        data: {
                            id: id
                        },
                        success: function (response) {
                            if (response == '1') {
                                swal("Deleted!", "Selected  Data has been deleted!", "success");
                                location.href = "FileManager";
                            } else {
                                swal({
                                    title: "Oops!",
                                    text: "Something Wrong...",
                                    imageUrl: 'plugins/bootstrap-sweetalert/assets/error_icon.png'
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
            swal("Success!", "File has been Upload Successfully!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'plugins/bootstrap-sweetalert/assets/error_icon.png'
            });
        }
    }
</script>
</body>
</html>