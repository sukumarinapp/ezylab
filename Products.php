<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);
$created = date("Y-m-d h:i:sa");
$modified = date("Y-m-d h:i:sa");
$created_date = date("Y-m-d");

if (isset($_POST['submit'])) {

    $insert_product = Insert('macho_master_products', array(
        'product_code' => Filter($_POST['product_code']),
        'product_name' => Filter($_POST['product_name']),
        'product_lang_name' => Filter($_POST['product_lang_name']),
        //'uom' => Filter($_POST['uom']),
        'created' => $created,
        'modified' => $modified

    ));

    if (is_int($insert_products)) {

        $notes = $_POST['product_name'] . ' Product details added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

if (isset($_POST['update'])) {
    $product_id = Filter($_POST['product_id']);

    $update = Update('macho_master_products', 'id', $product_id, array(
        'product_code' => Filter($_POST['product_code']),
        'product_name' => Filter($_POST['product_name']),
        'product_lang_name' => Filter($_POST['product_lang_name']),
        //'uom' => Filter($_POST['uom']),
        'modified' => $modified

    ));

    if ($update) {

        $notes = $_POST['product_name'] . ' (Product Code:' . $_POST['product_code'] . ') Product details modified by ' . $user;
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
<section class="section-container no-print">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div>Products
                <small></small>
            </div>
            <div class="ml-auto">
                <div class="btn-group">
                    <button class="btn btn-secondary" type="button"
                            onclick="print_data(event,'Products Report','0','0');"><i class="fa fa-print"></i> Print
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="pdf_data(event,'Products Report','0','0');"><i
                            class="fa fa-file-pdf-o"></i> PDF
                    </button>
                    <button class="btn btn-secondary" type="button"
                            onclick="excel_data(event,'Products Report','0','0');"><i
                            class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <?php if ($PageAccessible['is_write'] == 1) { ?>
                    <div style="float: left!important;">
                        <button class="btn btn-labeled btn-secondary pull-left" type="button"
                                title="Print Products Barcode" onclick="PrintBarcode2();"
                                id="print_button"><span class="btn-label btn-label"><i class="fa fa-print"></i>
                           </span> Print Products Barcode
                        </button>
<!--                        <button class="btn btn-labeled btn-secondary pull-left" type="button" title="Import Products"-->
<!--                                onclick="location.href='ImportProducts';"-->
<!--                            ><span class="btn-label btn-label"><i class="fa fa-upload"></i>-->
<!--                           </span> Import Products-->
<!--                        </button>-->
                    </div>
                <?php } ?>

                <div class="card-title pull-right">
                    <?php if ($PageAccessible['is_write'] == 1) { ?>
                        <button class="btn btn-labeled btn-secondary" type="button" title="Add Product"
                                data-toggle="modal"
                                data-target="#add_product">
                            Add New
                        <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                           </span></button>
                    <?php }
                    if ($PageAccessible['is_delete'] == 1) { ?>
                        <button class="btn btn-labeled btn-secondary pull-left" type="button" title="Delete Product"
                                onclick="Delete2();"
                                id="delete_button"><span class="btn-label btn-label"><i class="fa fa-trash-o"></i>
                           </span> Delete
                        </button>
                    <?php } ?>
                </div>
                <div class="text-sm"></div>
            </div>
            <div class="card-body">
                <div class="card-title">
                    <div class="checkbox c-checkbox">
                        <label>
                            <input type="checkbox" id="check_all" name="check_all" title="Select All">
                            <span class="fa fa-check"></span> <strong>Select All</strong></label>
                    </div>
                </div>
                <div style="overflow-x: auto!important;">
                    <table class="table table-striped my-4 w-100" id="datatable11">
                        <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th class="thead_data">#</th>
                            <th class="thead_data">Code</th>
                            <th class="thead_data">Name</th>
                            <th class="thead_data">Description</th>
<!--                            <th class="thead_data">Unit</th>-->
                            <th class="thead_data">Created</th>
                            <th class="thead_data">Modified</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $ProductsQuery = "SELECT * FROM macho_master_products ORDER BY product_code DESC ";
                        $ProductsResult = GetAllRows($ProductsQuery);
                        $ProductsCounts = count($ProductsResult);
                        if ($ProductsCounts > 0) {
                            foreach ($ProductsResult as $ProductsData) {
                                ?>
                                <tr>
                                    <td>
                                        <div class="checkbox c-checkbox">
                                            <label>
                                                <input type="checkbox" class="chk" name="product_id" id="product_id"
                                                       data-toggle="selectrow" data-target="tr"
                                                       value="<?= $ProductsData['id']; ?>">
                                                <span class="fa fa-check"></span></label>
                                        </div>
                                    </td>
                                    <td class="tbody_data"><?php echo ++$no; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $ProductsData['product_code']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $ProductsData['product_name']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $ProductsData['product_lang_name']; ?></td>
<!--                                    <td class="tbody_data">&nbsp;--><?//= $ProductsData['uom']; ?><!--</td>-->
                                    <td class="tbody_data">&nbsp;<?= $ProductsData['created']; ?></td>
                                    <td class="tbody_data">&nbsp;<?= $ProductsData['modified']; ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($PageAccessible['is_read'] == 1) { ?>
                                                <button class="btn btn-sm btn-primary" type="button"
                                                        title="Print Barcode"
                                                        onclick="PrintBarcode(<?php echo $ProductsData['id']; ?>);">
                                                    <em class="fa fa-print"></em>
                                                </button>
<!--                                                <button class="btn btn-sm btn-green" type="button" title="View"-->
<!--                                                        onclick="location.href='ProductView?id=<?php //echo EncodeVariable($ProductsData['id']); ?>//';">
//                                                    <em class="fa fa-search"></em>
//                                                </button>-->
                                            <?php }
                                            if ($PageAccessible['is_modify'] == 1) { ?>
                                                <button class="btn btn-sm btn-info" type="button" title="Edit"
                                                        onclick="ModalEdit(<?= $ProductsData['id']; ?>);">
                                                    <em class="fa fa-edit"></em>
                                                </button>
                                            <?php }
                                            if ($PageAccessible['is_delete'] == 1) { ?>
                                                <button class="btn btn-sm btn-danger" type="button" title="Delete"
                                                        onclick="Delete(<?php echo $ProductsData['id']; ?>,'<?= $ProductsData['product_name']; ?>');">
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
</section>
<!-- Page footer-->
<?php include_once 'footer.php'; ?>
</div>

<div class="modal fade" id="edit_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabelLarge"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabelLarge">Edit Product Details</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabelLarge"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabelLarge">Create New Products</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" action="Products" enctype="multipart/form-data">
                            <!-- START card-->
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Code</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="product_code"
                                                       id="product_code" value="<?php echo GetProductCode(); ?>"
                                                       readonly
                                                       tabindex="1">
                                            </div>
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="product_name"
                                                       id="product_name"
                                                       maxlength="100"
                                                       tabindex="2">
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="product_lang_name"
                                                       id="product_lang_name"
                                                       tabindex="3">
                                            </div>
<!--                                            <div class="form-group">-->
<!--                                                <label>Unit </label>-->
<!--                                                <select class="form-control"-->
<!--                                                        name="uom"-->
<!--                                                        id="uom"-->
<!--                                                        tabindex="8">-->
<!--                                                    <option value="0">Enter Unit</option>-->
<!--                                                    --><?php
//                                                    $MeasurementQuery = "SELECT * FROM macho_uom ORDER BY measurement";
//                                                    $MeasurementData = GetAllRows($MeasurementQuery);
//                                                    foreach ($MeasurementData as $Measurements) {
//                                                        echo "<option value='" . $Measurements['symbol'] . "'>" . $Measurements['measurement'] . "</option>";
//                                                    } ?>
<!--                                                </select>-->
<!--                                            </div>-->
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="clearfix">
                                            <div class="float-right">
                                                <button class="btn btn-primary" type="submit" name="submit"
                                                        tabindex="24">
                                                    Save
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
    var table = $('#datatable11').DataTable({
        'paging': true, // Table pagination
        'ordering': true, // Column ordering
        'info': true, // Bottom left status text
        responsive: true,
        // Text translation options
        // Note the required keywords between underscores (e.g _MENU_)
        oLanguage: {
            sSearch: '<em class="ion-search"></em>',
            sLengthMenu: '_MENU_ records per page',
            info: 'Showing page _PAGE_ of _PAGES_',
            zeroRecords: 'Nothing found - sorry',
            infoEmpty: 'No records available',
            infoFiltered: '(filtered from _MAX_ total records)',
            oPaginate: {
                sNext: '<em class="fa fa-caret-right"></em>',
                sPrevious: '<em class="fa fa-caret-left"></em>'
            }
        }
    });
    var info;
    var page = 1;
    var check_all_pages;

    $('#datatable11').on('page.dt', function () {
        info = table.page.info();
        page = info.page + 1;
        check_all_pages = $('#check_all').val();
        if (page <= check_all_pages) {
            $('#check_all').prop('checked', true);
        } else {
            $('#check_all').prop('checked', false);
        }
    });

    $("#check_all").click(function () {
        if ($('#check_all').prop("checked")) {
            $('.chk').prop('checked', true);
        } else {
            $('.chk').prop('checked', false);
        }
        $('#check_all').val(page);
    });
</script>
<script>
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

    function DecimalPoint(x) {
        return Number.parseFloat(x).toFixed(2);
    }

    $(function () {
        //Date picker
        $('#mfg_date').datepicker({
            autoclose: true
        });

        $('#exp_date').datepicker({
            autoclose: true
        });
    });

    function ModalEdit(id) {
        $.ajax({
            type: "POST",
            url: "EditProducts.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_product').modal('show');
            }
        });
    }



    function Delete(id,productname) {
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
                        url: "DeleteProducts.php",
                        data: {
                            id: id,
                            productname:productname
                        },
                        success: function (response) {
                            if (response == '1') {
                                swal("Deleted!", "Selected Product Data has been deleted!", "success");
                                location.href = "Products";
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

    function Delete2() {

        var product_id = new Array();
        $(".chk:checked").each(function () {
            product_id.push($(this).val());
        });

        if (product_id == "") {
            swal("Please Select Data");
            return;
        }

        var i = 0;
        var obj = new Array();
        for (i = 0; i < product_id.length; i++) {

            obj[i] = product_id[i];

        }

        var product_data = JSON.stringify(obj);

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
                    $("#delete_button").prop("disabled", true);
                    $.ajax({
                        type: "POST",
                        url: "DeleteProducts2.php",
                        data: {
                            pID: product_data
                        },
                        success: function (response) {
                            if (response == '1') {
                                swal("Deleted!", "Selected Product Data has been deleted!", "success");

                            } else {
                                swal({
                                    title: "Oops!",
                                    text: "Something Wrong...",
                                    imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
                                });
                            }

                            location.href = "Products";
                        }
                    });

                } else {
                    swal("Cancelled", "Your Entry Data is safe :)", "error");
                }
            });
    }

</script>
<script>
    function PrintBarcode(id) {

        $.ajax({
            type: 'POST',
            url: 'PrintBarcodeData.php',
            data: {
                id: id
            },
            success: function (response) {
                $.ajax({
                    type: 'POST',
                    url: 'http://localhost/mandi2/PrintBarcode.php',
                    data: {
                        print_data: response
                    },
                    success: function (data) {
                    }
                });

                location.href = "Products";
            }
        });
    }

    function PrintBarcode2() {

        var product_id = new Array();
        $(".chk:checked").each(function () {
            product_id.push($(this).val());
        });

        if (product_id == "") {
            swal("Please Select Data");
            return;
        }

        var i = 0;
        var obj = new Array();
        for (i = 0; i < product_id.length; i++) {

            obj[i] = product_id[i];

        }

        $("#print_button").prop("disabled", true);
        var product_data = JSON.stringify(obj);
        $.ajax({
            type: 'POST',
            url: 'PrintBarcodeData2.php',
            data: {
                pID: product_data
            },
            success: function (response) {
                $.ajax({
                    type: 'POST',
                    url: 'http://localhost/mandi2/PrintBarcode2.php',
                    data: {
                        print_data: response
                    },
                    success: function (data) {
                    }
                });

                location.href = "Products";
            }
        });
    }

    window.onload = function () {
        if (document.getElementById('update_success')) {
            swal("Success!", "Product Details has been Updated!", "success");
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