<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);

if (isset($_POST['submit'])) {

    $insert_uom = Insert('macho_uom', array(

        'measurement' => Filter($_POST['measurement']),
        'symbol' => Filter($_POST['symbol'])
    ));
    if (is_int($insert_uom)) {
        $notes = $_POST['measurement'] . ' Units Entry added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

if (isset($_POST['update'])) {
    $uom_id = Filter($_POST['id']);
    $update = Update('macho_uom', 'id', $uom_id, array(
        'measurement' => Filter($_POST['measurement']),
        'symbol' => Filter($_POST['symbol'])
    ));
    if ($update) {
        $notes = $_POST['measurement'] . ' Units Entry modified by ' . $user;
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
            <div>Units
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Unit Report','0','0');"><i class="fa fa-print"></i> Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Unit Report','0','0');"><i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Unit Report','0','0');"><i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <?php if ($PageAccessible['is_write'] == 1) { ?>
                    <div class="card-header">
                        <div class="card-title pull-right">
                            <button class="btn btn-labeled btn-secondary" type="button" data-toggle="modal"
                                    data-target="#add_measurement">Create New
                                <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                           </span></button>
                        </div>
                        <div class="text-sm"></div>
                    </div>
                <?php } ?>
                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="datatable1">
                        <thead>
                        <tr>
                            <th width="20px" class="thead_data">#</th>
                            <th class="thead_data">Unit</th>
                            <th class="thead_data">Symbol</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $ProductSizeQuery = 'SELECT * FROM macho_uom ORDER BY id';
                        $ProductSizeResult = GetAllRows($ProductSizeQuery);
                        $ProductSizeCounts = count($ProductSizeResult);
                        if ($ProductSizeCounts > 0) {
                            foreach ($ProductSizeResult as $ProductSizeData) {
                                ?>
                                <tr>
                                    <td class="tbody_data"><?php echo ++$no; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $ProductSizeData['measurement']; ?></td>
                                    <td class="tbody_data">&nbsp;<?php echo $ProductSizeData['symbol']; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                                <button class="btn btn-info" type="button"
                                                        onclick="ModalEdit(<?php echo $ProductSizeData['id']; ?>);">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>
                                            <?php }
                                            if ($PageAccessible['is_delete'] == 1) { ?>
                                                <button class="btn btn-danger" type="button"
                                                        onclick="Delete('macho_uom','id',<?php echo $ProductSizeData['id']; ?>);">
                                                    <i class="fa fa-trash-o"></i>
                                                    Delete
                                                </button>
                                            <?php } ?>
                                        </div>
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
</section>
<!-- Page footer-->
<?php include_once 'footer.php' ?>
</div>
<div class="modal fade" id="add_measurement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Create New Unit</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="Units">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="col-form-label">Unit </label>
                                        <input class="form-control" type="text" name="measurement" id="measurement"
                                               maxlength="50"
                                               tabindex="1" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Symbol</label>
                                        <input class="form-control" type="text" name="symbol" id="symbol"
                                               maxlength="20"
                                               tabindex="2" required>
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
<div class="modal fade" id="edit_measurement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update Unit Details</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
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
<!-- Datatables-->
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="<?php echo VENDOR; ?>select2/dist/js/select2.full.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
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
    function ModalEdit(id) {
        $.ajax({
            type: "POST",
            url: "EditUnit.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_measurement').modal('show');
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
                                swal("Deleted!", "Selected Unit Data has been deleted!", "success");
                                location.href = "Units";
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
            swal("Success!", "Unit has been Added!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "Unit has been Updated!", "success");
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