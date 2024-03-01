<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
$UserID = DecodeVariable($_GET['uID']);
$UserData = UserInfo($UserID);
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div><?php echo $UserData['prefix'] . ' ' . $UserData['name']; ?>
                <small></small>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="row">
                <div class="col-xl-12">
                    <form method="post" action="EditAccess" id="save_form">
                        <!-- START card-->
                        <div class="card card-default">
                            <div class="card-header">
                                <div class="card-title">Permissions
                                </div>
                                <div class="text-sm"></div>
                            </div>
                            <div class="card-body">
                                <div class="container-md">
                                    <!-- Checkout Process-->
                                    <div id="accordion">
                                        <?php
                                        $no = 0;
                                        $Query = "SELECT id,menu_name,is_dropdown,menu_url FROM macho_menu WHERE is_parent='0' AND is_enable='1' ORDER BY id ";
                                        $Result = GetAllRows($Query);
                                        $Counts = count($Result);
                                        if ($Counts > 0) {
                                            foreach ($Result as $Data) {
                                                $MainMenuID = $Data['id'];
                                                $UserMainMenuData = CheckUserMenu($UserID, $MainMenuID); ?>
                                                <div class="card b mb-2">
                                                    <div class="card-header">
                                                        <h4 class="card-title"><a
                                                                    class="<?php if ($UserMainMenuData['menu_id']) {
                                                                        echo 'text-danger';
                                                                    } else {
                                                                        echo 'text-inherit';
                                                                    } ?>"
                                                                    data-toggle="collapse"
                                                                    data-parent="#accordion"
                                                                    href="#acc1collapse<?= $MainMenuID; ?>"><?php echo ++$no . '. ' . $Data['menu_name']; ?></a>
                                                        </h4>
                                                    </div>
                                                    <div class="collapse" id="acc1collapse<?= $MainMenuID; ?>">
                                                        <div class="card-body" id="collapse<?= $MainMenuID; ?>">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <?php if ($Data['is_dropdown'] == 0) { ?>
                                                                        <div class="form-group mb-4">
                                                                            <div class="d-flex">
                                                                                <label class="c-checkbox">
                                                                                    <input id="menu_id_<?= $MainMenuID; ?>"
                                                                                           name="menu_id[]"
                                                                                           type="checkbox"
                                                                                           onclick="CheckAll(<?= $MainMenuID; ?>);"
                                                                                           value="<?php echo $MainMenuID; ?>" <?php if ($UserMainMenuData['menu_id']) echo 'checked'; ?>>
                                                                                    <span class="fa fa-check"></span><?php echo $Data['menu_name'] ?>
                                                                                </label>
                                                                                <div class="ml-auto">
                                                                                    <label class="c-checkbox">
                                                                                        <input type="checkbox"
                                                                                               name="is_read[<?= $MainMenuID; ?>]"
                                                                                               id="is_read_<?= $MainMenuID; ?>" <?php if ($UserMainMenuData['is_read'] == '1') echo 'checked'; ?>>
                                                                                        <span class="fa fa-check"></span>Read</label>
                                                                                    <label class="c-checkbox">
                                                                                        <input type="checkbox"
                                                                                               name="is_write[<?= $MainMenuID; ?>]"
                                                                                               id="is_write_<?= $MainMenuID; ?>" <?php if ($UserMainMenuData['is_write'] == '1') echo 'checked'; ?>>
                                                                                        <span class="fa fa-check"></span>Write</label>
                                                                                    <label class="c-checkbox">
                                                                                        <input type="checkbox"
                                                                                               name="is_modify[<?= $MainMenuID; ?>]"
                                                                                               id="is_modify_<?= $MainMenuID; ?>" <?php if ($UserMainMenuData['is_modify'] == '1') echo 'checked'; ?>>
                                                                                        <span class="fa fa-check"></span>Modify</label>
                                                                                    <label class="c-checkbox">
                                                                                        <input type="checkbox"
                                                                                               name="is_delete[<?= $MainMenuID; ?>]"
                                                                                               id="is_delete_<?= $MainMenuID; ?>" <?php if ($UserMainMenuData['is_delete'] == '1') echo 'checked'; ?>>
                                                                                        <span class="fa fa-check"></span>Delete</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } else {

                                                                        $SubQuery = "SELECT id,menu_name,menu_url,is_dropdown FROM macho_menu WHERE is_parent='$MainMenuID' ORDER BY id ";
                                                                        $SubResult = GetAllRows($SubQuery);
                                                                        $SubCounts = count($SubResult);
                                                                        if ($SubCounts > 0) {
                                                                            foreach ($SubResult as $SubData) {
                                                                                $SubMenuID = $SubData['id'];
                                                                                $UserSubMenuData = CheckUserMenu($UserID, $SubMenuID);
                                                                                if ($SubData['is_dropdown'] == 0) { ?>
                                                                                    <div class="form-group mb-4">
                                                                                        <div class="d-flex">
                                                                                            <label class="c-checkbox">
                                                                                                <input type="checkbox"
                                                                                                       name="menu_id[]"
                                                                                                       id="menu_id_<?= $SubMenuID; ?>"
                                                                                                       onclick="CheckAll(<?= $SubMenuID; ?>);"
                                                                                                       value="<?php echo $SubMenuID; ?>" <?php if ($UserSubMenuData['menu_id']) echo 'checked'; ?>>
                                                                                                <span class="fa fa-check"></span><?php echo $SubData['menu_name'] ?>
                                                                                            </label>
                                                                                            <div class="ml-auto">
                                                                                                <label class="c-checkbox">
                                                                                                    <input type="checkbox"
                                                                                                           name="is_read[<?= $SubMenuID; ?>]"
                                                                                                           id="is_read_<?= $SubMenuID; ?>" <?php if ($UserSubMenuData['is_read'] == '1') echo 'checked'; ?>>
                                                                                                    <span class="fa fa-check"></span>Read</label>
                                                                                                <label class="c-checkbox">
                                                                                                    <input type="checkbox"
                                                                                                           name="is_write[<?= $SubMenuID; ?>]"
                                                                                                           id="is_write_<?= $SubMenuID; ?>" <?php if ($UserSubMenuData['is_write'] == '1') echo 'checked'; ?>>
                                                                                                    <span class="fa fa-check"></span>Write</label>
                                                                                                <label class="c-checkbox">
                                                                                                    <input type="checkbox"
                                                                                                           name="is_modify[<?= $SubMenuID; ?>]"
                                                                                                           id="is_modify_<?= $SubMenuID; ?>" <?php if ($UserSubMenuData['is_modify'] == '1') echo 'checked'; ?>>
                                                                                                    <span class="fa fa-check"></span>Modify</label>
                                                                                                <label class="c-checkbox">
                                                                                                    <input type="checkbox"
                                                                                                           name="is_delete[<?= $SubMenuID; ?>]"
                                                                                                           id="is_delete_<?= $SubMenuID; ?>" <?php if ($UserSubMenuData['is_delete'] == '1') echo 'checked'; ?>>
                                                                                                    <span class="fa fa-check"></span>Delete</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <div class="form-group mb-4">
                                                                                        <div class="d-flex">
                                                                                            <label class="c-checkbox">
                                                                                                <input type="checkbox"
                                                                                                       id="sub_menu_id_<?= $SubMenuID; ?>"
                                                                                                       onclick="CheckSubMenu(<?= $SubMenuID; ?>);" <?php if ($UserSubMenuData['menu_id']) echo 'checked'; ?>>
                                                                                                <span class="fa fa-check"></span><?php echo $SubData['menu_name'] ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php
                                                                                    $SecondSubQuery = "SELECT id,menu_name,menu_url FROM macho_menu WHERE is_parent='$SubMenuID' ORDER BY id ";
                                                                                    $SecondSubResult = GetAllRows($SecondSubQuery);
                                                                                    $SecondSubCounts = count($SecondSubResult);
                                                                                    if ($SecondSubCounts > 0) {
                                                                                        foreach ($SecondSubResult as $SecondSubData) {
                                                                                            $SecondSubMenuID = $SecondSubData['id'];
                                                                                            $UserSecondSubMenuData = CheckUserMenu($UserID, $SecondSubMenuID);
                                                                                            ?>
                                                                                            <div class="form-group mb-4 second_sub_menu_div_<?= $SubMenuID; ?>"
                                                                                                 style="display: <?php if ($UserSubMenuData['menu_id']) {
                                                                                                     echo 'block';
                                                                                                 } else {
                                                                                                     echo 'none';
                                                                                                 } ?>">
                                                                                                <input type="hidden"
                                                                                                       class="second_sub_menu_data_<?= $SubMenuID; ?>"
                                                                                                       value="<?= $SecondSubMenuID; ?>">
                                                                                                <div class="d-flex">
                                                                                                    <label><?= space(5); ?></label><label
                                                                                                            class="c-checkbox">
                                                                                                        <input type="checkbox"
                                                                                                               name="menu_id[]"
                                                                                                               id="menu_id_<?= $SecondSubMenuID; ?>"
                                                                                                               onclick="CheckAll(<?= $SecondSubMenuID; ?>);"
                                                                                                               value="<?php echo $SecondSubMenuID; ?>" <?php if ($UserSecondSubMenuData['menu_id']) echo 'checked'; ?>>
                                                                                                        <span class="fa fa-check"></span><?php echo $SecondSubData['menu_name'] ?>
                                                                                                    </label>
                                                                                                    <div class="ml-auto">
                                                                                                        <label class="c-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                   name="is_read[<?= $SecondSubMenuID; ?>]"
                                                                                                                   id="is_read_<?= $SecondSubMenuID; ?>" <?php if ($UserSecondSubMenuData['is_read'] == '1') echo 'checked'; ?>>
                                                                                                            <span class="fa fa-check"></span>Read</label>
                                                                                                        <label class="c-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                   name="is_write[<?= $SecondSubMenuID; ?>]"
                                                                                                                   id="is_write_<?= $SecondSubMenuID; ?>" <?php if ($UserSecondSubMenuData['is_write'] == '1') echo 'checked'; ?>>
                                                                                                            <span class="fa fa-check"></span>Write</label>
                                                                                                        <label class="c-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                   name="is_modify[<?= $SecondSubMenuID; ?>]"
                                                                                                                   id="is_modify_<?= $SecondSubMenuID; ?>" <?php if ($UserSecondSubMenuData['is_modify'] == '1') echo 'checked'; ?>>
                                                                                                            <span class="fa fa-check"></span>Modify</label>
                                                                                                        <label class="c-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                   name="is_delete[<?= $SecondSubMenuID; ?>]"
                                                                                                                   id="is_delete_<?= $SecondSubMenuID; ?>" <?php if ($UserSecondSubMenuData['is_delete'] == '1') echo 'checked'; ?>>
                                                                                                            <span class="fa fa-check"></span>Delete</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <div class="clearfix">
                                    <div class="float-right">
                                        <input type="hidden" name="UserID" id="UserID" value="<?php echo $UserID; ?>">
                                        <button class="btn btn-secondary" type="button"
                                                onclick="location.href='Users';">Back to List</button>
                                        <button class="btn btn-primary" type="submit" name="submit" id="save_button"
                                                onclick="form_submit(event);">Save Changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END card-->
                    </form>
                </div>
            </div>
            <div class="card-body loader-demo d-flex align-items-center justify-content-center">

                <div id="loader">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php' ?>
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
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>


<script>
    function CheckSubMenu(id) {
        var second_sub_menu_data = new Array();
        $('.second_sub_menu_data_' + id).each(function () {
            second_sub_menu_data.push($(this).val());
        });
        var i;
        var menu_id;
        if ($('#sub_menu_id_' + id).prop("checked")) {
            $('.second_sub_menu_div_' + id).show();
            for (i = 0; i < second_sub_menu_data.length; i++) {
                menu_id = second_sub_menu_data[i];
                $('#menu_id_' + menu_id).prop('checked', true);
                $('#is_read_' + menu_id).prop('checked', true);
                $('#is_write_' + menu_id).prop('checked', true);
                $('#is_modify_' + menu_id).prop('checked', true);
                $('#is_delete_' + menu_id).prop('checked', true);
            }
        } else {
            $('.second_sub_menu_div_' + id).hide();
            for (i = 0; i < second_sub_menu_data.length; i++) {
                menu_id = second_sub_menu_data[i];
                $('#menu_id_' + menu_id).prop('checked', false);
                $('#is_read_' + menu_id).prop('checked', false);
                $('#is_write_' + menu_id).prop('checked', false);
                $('#is_modify_' + menu_id).prop('checked', false);
                $('#is_delete_' + menu_id).prop('checked', false);
            }
        }
    }

    function CheckAll(id) {
        if ($('#menu_id_' + id).prop("checked")) {
            $('#is_read_' + id).prop('checked', true);
            $('#is_write_' + id).prop('checked', true);
            $('#is_modify_' + id).prop('checked', true);
            $('#is_delete_' + id).prop('checked', true);

        } else {
            $('#is_read_' + id).prop('checked', false);
            $('#is_write_' + id).prop('checked', false);
            $('#is_modify_' + id).prop('checked', false);
            $('#is_delete_' + id).prop('checked', false);
        }

    }


    function form_submit(e) {
        e.preventDefault();
        $('#save_button').prop('disabled', true);
        $('#loader').addClass('pacman');
        var formData = new FormData($("#save_form")[0]);
        $.ajax({
            type: 'post',
            url: 'UpdateUserAccess.php',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (response) {
                $('#loader').removeClass('pacman');
                if (response == '1') {
                    swal({
                            title: "Success",
                            text: "Permission Updated Successfully!",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonClass: "btn-success",
                            confirmButtonText: "Ok",
                            closeOnConfirm: false
                        },
                        function () {
                            location.href = "Users";
                        });
                } else {
                    swal({
                        title: "Oops!",
                        text: response,
                        imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
                    });
                    $('#save_button').prop('disabled', false);
                }
            }
        });
    }
</script>
</body>
</html>