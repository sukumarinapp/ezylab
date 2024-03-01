<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);
$created = date("Y-m-d H:i:s");
$updated = date("Y-m-d H:i:s");

if (isset($_POST['add_farmer'])) {

    $insert_farmer = Insert('macho_farmers', array(
        'F_code' => Filter($_POST['F_code']),
        'F_name' => Filter($_POST['F_name']),
        'T_name' => Filter($_POST['T_name']),
        'address1' => Filter($_POST['address1']),
        'address2' => Filter($_POST['address2']),
        'address3' => Filter($_POST['address3']),
        'pincode' => Filter($_POST['pincode']),
        'phone' => Filter($_POST['phone']),
        'mobile' => Filter($_POST['mobile']),
        'email' => Filter($_POST['email'])
    ));

    if (is_int($insert_farmer)) {
        $notes = $_POST['F_name'] . ' Supplier details added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

if (isset($_POST['edit_farmer'])) {

    $farmer_id = Filter($_POST['farmer_id']);

    $update = Update('macho_farmers', 'id', $farmer_id, array(
        'F_code' => Filter($_POST['F_code']),
        'F_name' => Filter($_POST['F_name']),
        'T_name' => Filter($_POST['T_name']),
        'address1' => Filter($_POST['address1']),
        'address2' => Filter($_POST['address2']),
        'address3' => Filter($_POST['address3']),
        'pincode' => Filter($_POST['pincode']),
        'phone' => Filter($_POST['phone']),
        'mobile' => Filter($_POST['mobile']),
        'email' => Filter($_POST['email'])
    ));
    if ($update) {
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
            <div>Supplier
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Supplier Report','0','0');"><i class="fa fa-print"></i> Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Supplier Report','0','0');"><i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Supplier Report','0','0');"><i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <?php if ($PageAccessible['is_write'] == 1) { ?>
                        <button type="button" class="btn btn-sm btn-white" title="Add Supplier"
                                data-toggle="modal" data-target="#add_farmer"><i class="fa fa-plus"></i>
                            Add
                            Supplier
                        </button>
                    <?php } ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped my-4 w-100" id="datatable1">
                            <thead>
                            <tr>
                                <th width="20px" class="thead_data">#</th>
                                <th class="thead_data">Code</th>
                                <th class="thead_data">Name</th>
                                <th class="thead_data">T.Name</th>
                                <th class="thead_data">Address1</th>
                                <th class="thead_data">Address2</th>
                                <th class="thead_data">Address3</th>
                                <th class="thead_data">Pincode</th>
                                <th class="thead_data">Phone</th>
                                <th class="thead_data">Mobile</th>
                                <th class="thead_data">Email</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $no = 0;
                            $FarmerQuery = "SELECT * FROM macho_farmers ORDER BY F_code DESC ";
                            $FarmerResult = GetAllRows($FarmerQuery);
                            $FarmerCounts = count($FarmerResult);
                            if ($FarmerCounts > 0) {
                                foreach ($FarmerResult as $FarmerData) { ?>
                                    <tr>
                                        <td width="20" class="tbody_data"><?= ++$no; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $FarmerData['F_code']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $FarmerData['F_name']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $FarmerData['T_name']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $FarmerData['address1']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $FarmerData['address2']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $FarmerData['address3']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $FarmerData['pincode']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $FarmerData['phone']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $FarmerData['mobile']; ?></td>
                                        <td class="tbody_data">&nbsp;<?= $FarmerData['email']; ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <?php
                                                if ($PageAccessible['is_modify'] == 1) { ?>
                                                    <button class="btn btn-primary"
                                                            onclick="ModalEdit(<?= $FarmerData['id']; ?>);"
                                                            title="Update"><i class="fa fa-pencil"></i>
                                                    </button>
                                                <?php }
                                                if ($PageAccessible['is_delete'] == 1) { ?>
                                                    <button class="btn btn-danger" title="Delete"
                                                            onclick="Delete(<?= $FarmerData['id']; ?>,'<?= $FarmerData['F_name']; ?>');">
                                                        <i class="fa fa-trash-o"></i></button>
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
            </div>
        </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php' ?>
</div>
<!-- sample modal content -->
<div id="add_farmer" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
     aria-labelledby="myCenterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myCenterModalLabel">Supplier Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text"
                                       class="form-control"
                                       name="F_code"
                                       id="F_code" value="<?php echo GetFarmerCode(); ?>"
                                       readonly
                                       tabindex="1">
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text"
                                       class="form-control"
                                       name="F_name"
                                       id="F_name"
                                       maxlength="100"
                                       tabindex="2">
                            </div>
                            <div class="form-group">
                                <label>T.Name</label>
                                <input type="text"
                                       class="form-control"
                                       name="T_name"
                                       id="T_name"
                                       maxlength="100"
                                       tabindex="3">
                            </div>
                            <div class="form-group">
                                <label>Address1</label>
                                <input type="text"
                                       class="form-control"
                                       name="address1"
                                       id="address1"
                                       maxlength="100"
                                       tabindex="4">
                            </div>
                            <div class="form-group">
                                <label>Address2</label>
                                <input type="text"
                                       class="form-control"
                                       name="address2"
                                       id="address2"
                                       maxlength="100"
                                       tabindex="5">
                            </div>
                            <div class="form-group">
                                <label>Address3</label>
                                <input type="text"
                                       class="form-control"
                                       name="address3"
                                       id="address3"
                                       maxlength="100"
                                       tabindex="6">
                            </div>
                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text"
                                       class="form-control"
                                       name="pincode"
                                       id="pincode" onkeypress="return isNumberKey(event)"
                                       maxlength="100"
                                       tabindex="7">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text"
                                       class="form-control"
                                       name="phone"
                                       id="phone" onkeypress="return isNumberKey(event)"
                                       maxlength="100"
                                       tabindex="8">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile </label>
                                <input type="text"
                                       class="form-control"
                                       name="mobile"
                                       id="mobile" onkeypress="return isNumberKey(event)"
                                       maxlength="100"
                                       tabindex="9">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email"
                                       class="form-control"
                                       name="email"
                                       id="email"
                                       maxlength="100"
                                       tabindex="10">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-primary m-t-n-xs" type="submit" name="add_farmer"
                            tabindex="11">
                        <strong>Save</strong>
                    </button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- sample modal content -->
<div id="edit_farmer" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
     aria-labelledby="myCenterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myCenterModalLabel">Edit Supplier Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body" id="edit_body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-primary m-t-n-xs" type="submit" name="edit_farmer"
                            tabindex="12">
                        <strong>Update</strong>
                    </button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
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
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>

<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<!-- Datatables-->
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
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
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function ModalEdit(id) {
        $.ajax({
            type: "POST",
            url: "EditFarmer.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_farmer').modal('show');
            }
        });
    }

    function Delete(id, farmername) {
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Supplier!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: "DeleteFarmer.php",
                        data: {
                            id: id,
                            farmername: farmername
                        },
                        success: function (response) {
                            if (response == '1') {
                                swal("Deleted!", "Selected Supplier Data has been deleted!", "success");
                                setTimeout(function () {
                                    window.location.href = 'Suppliers';
                                }, 5000);
                            } else {
                                swal({
                                    title: "Oops!",
                                    text: "Something Wrong...",
                                    type: "error"
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
            swal("Success!", "New Supplier details Added Successfully!", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                type: "error"
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "Supplier Details Modified Successfully!", "success");
        }

        if (document.getElementById('update_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                type: "error"
            });
        }

    }


</script>
</body>

</html>