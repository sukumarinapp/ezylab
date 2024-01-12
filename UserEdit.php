<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';

$UserID = DecodeVariable($_GET['uId']);
$UserData = UserInfo($UserID);
$modified = date("Y-m-d h:i:sa");

if (isset($_POST['update'])) {

    $ExistRole = GetRoleOfUser($UserID);
    if ($ExistRole != Filter($_POST['role_id'])) {
        DeleteRow('macho_user_page_acceses', 'user_id', $UserID);
        UserPageAcceses($UserID, $_POST['role_id']);
    }

    $photo = $_FILES['photo']['name'];

    $update = Update('macho_users', 'id', $UserID, array(
        'username' => Filter($_POST['username']),
        'password' => EncodePass($_POST['password']),
        'prefix' => ($_POST['prefix']),
        'name' => Filter($_POST['name']),
        'gender' => Filter($_POST['gender']),
        'dob' => to_sql_date($_POST['dob']),
        'address' => Filter($_POST['address']),
        'mobile' => Filter($_POST['mobile']),
        'phone' => Filter($_POST['phone']),
        'email' => Filter($_POST['email']),
        'aadhar_no' => Filter($_POST['aadhar_no']),
        'about' => Filter($_POST['about']),
        'role_id' => Filter($_POST['role_id']),
        'service_from' => to_sql_date($_POST['service_from']),
        'service_to' => to_sql_date($_POST['service_to']),
        'login_status' => Filter($_POST['login_status']),
        'salary_mode' => Filter($_POST['salary_mode']),
        'salary_amount' => Filter($_POST['salary_amount']),
        'salary_percentage' => Filter($_POST['salary_percentage']),
        'salary_duration_type' => Filter($_POST['salary_duration_type']),
        'status' => Filter($_POST['status']),
        'editby' => $user_id,
        'modified' => $modified
    ));
    if ($update) {

        if (trim($photo) != "") {

            $profile_pic2 = basename($UserData['avatar']);

            if (file_exists("profile_pic/" . $profile_pic2)) {
                unlink("profile_pic/" . $profile_pic2);
            }

            $ext = pathinfo($photo, PATHINFO_EXTENSION);
            $profile_pic = $UserID . "." . $ext;
            $move_path = "profile_pic/";
            $move_path = $move_path . $profile_pic;
            $target_path = SITEURL . "profile_pic/";
            $target_path = $target_path . $profile_pic;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $move_path)) {
                $update = Update('macho_users', 'id', $UserID, array(
                    'avatar' => $target_path,
                ));
            }
        }

        $notes = $_POST['name'] . ' User details modified by ' . $user;
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
        <div class="content-heading">User Information</div>
        <div class="row">
            <div class="col-xl-12">
                <form method="post" action="" enctype="multipart/form-data">
                    <!-- START card-->
                    <div class="card card-default">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Name *</label>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <select class="form-control" name="prefix" id="prefix" tabindex="1"
                                                        required>
                                                    <option
                                                        value="Mr. " <?php if ($UserData['prefix'] == 'Mr. ') echo 'selected'; ?> >
                                                        Mr.
                                                    </option>
                                                    <option
                                                        value="Miss. " <?php if ($UserData['prefix'] == 'Miss. ') echo 'selected'; ?>>
                                                        Miss.
                                                    </option>
                                                    <option
                                                        value="Mrs. " <?php if ($UserData['prefix'] == 'Mrs. ') echo 'selected'; ?>>
                                                        Mrs.
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="hidden" name="id" id="id"
                                                       value="<?php echo $_GET['uId']; ?>">
                                                <input class="form-control" type="text" name="name" id="name"
                                                       value="<?php echo $UserData['name']; ?>" maxlength="200"
                                                       tabindex="2" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Role *</label>
                                        <select id="role_id" name="role_id"
                                                class="form-control" tabindex="3" required>
                                            <option value="">Select Role</option>
                                            <?php
                                            $RoleQuery = 'SELECT id,role FROM macho_role ORDER BY id ';
                                            $RoleResult = GetAllRows($RoleQuery);
                                            foreach ($RoleResult as $RoleData) {
                                                echo "<option ";
                                                if ($UserData['role_id'] == $RoleData['id']) echo " selected ";
                                                echo "value='" . $RoleData['id'] . "'>" . $RoleData['role'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Gender *</label>
                                        <select class="form-control" name="gender" id="gender" required tabindex="5">
                                            <option>Select Gender</option>
                                            <option
                                                value="Male" <?php if ($UserData['gender'] == 'Male') echo 'selected'; ?> >
                                                Male
                                            </option>
                                            <option
                                                value="Female" <?php if ($UserData['gender'] == 'Female') echo 'selected'; ?> >
                                                Female
                                            </option>
                                            <option
                                                value="Trans Gender" <?php if ($UserData['gender'] == 'Trans Gender') echo 'selected'; ?> >
                                                Trans Gender
                                            </option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Mobile *</label>
                                                <input class="form-control" type="text" name="mobile" id="mobile"
                                                       value="<?php echo $UserData['mobile']; ?>" maxlength="20"
                                                       onkeypress="return isNumberKey(event)" tabindex="7" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Phone</label>
                                                <input class="form-control" type="text" name="phone" id="phone"
                                                       value="<?php echo $UserData['phone']; ?>" maxlength="20"
                                                       onkeypress="return isNumberKey(event)" tabindex="8">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Email</label>
                                        <input class="form-control" type="email" name="email" id="email"
                                               value="<?php echo $UserData['email']; ?>" maxlength="250" tabindex="10">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Salary Method </label>
                                        <select class="form-control" name="salary_mode" id="salary_mode"
                                                tabindex="12">
                                            <option
                                                value="0" <?php if ($UserData['salary_mode'] == '0') echo 'selected'; ?> >
                                                Salary Amount
                                            </option>
                                            <option
                                                value="1" <?php if ($UserData['salary_mode'] == '1') echo 'selected'; ?> >
                                                Share Percentage
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="salary_tab" style="display:none;">
                                        <label class="col-form-label">Salary Amount </label>
                                        <input class="form-control" type="text" name="salary_amount"
                                               id="salary_amount"
                                               value="<?php echo $UserData['salary_amount']; ?>" maxlength="20"
                                               onkeypress="return isNumberDecimalKey(event)"
                                               tabindex="13">
                                    </div>
                                    <div id="share_tab" style="display: none">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Share Percentage</label>
                                                    <input class="form-control" type="text" name="salary_percentage"
                                                           id="salary_percentage"
                                                           value="<?php echo $UserData['salary_percentage']; ?>"
                                                           maxlength="20"
                                                           onkeypress="return isNumberDecimalKey(event)"
                                                           tabindex="14">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Duration Type</label>
                                                    <select class="form-control"
                                                            tabindex="15" id="salary_duration_type"
                                                            name="salary_duration_type">
                                                        <option
                                                            value="1" <?php if ($UserData['salary_duration_type'] == '1') echo 'selected'; ?> >
                                                            Days
                                                        </option>
                                                        <option
                                                            value="2" <?php if ($UserData['salary_duration_type'] == '2') echo 'selected'; ?> >
                                                            Weeks
                                                        </option>
                                                        <option
                                                            value="3" <?php if ($UserData['salary_duration_type'] == '3') echo 'selected'; ?> >
                                                            Months
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Login Priority </label>
                                        <select class="form-control" name="login_status" id="login_status"
                                                tabindex="16">
                                            <option
                                                value="0" <?php if ($UserData['login_status'] == '0') echo 'selected'; ?> >
                                                No
                                            </option>
                                            <option
                                                value="1" <?php if ($UserData['login_status'] == '1') echo 'selected'; ?> >
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="img-thumbnail" id="image-holder"
                                                     style="display: none"></div>
                                                <div class="img-thumbnail" id="alternative"><img
                                                        src="<?php if ($UserData['avatar'] != '') {
                                                            echo $UserData['avatar'];
                                                        } else {
                                                            echo 'profile_pic/default.png';
                                                        } ?>" alt=""
                                                        style="width: 125px!important;height: 125px!important;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="form-control filestyle" type="file"
                                                       accept="image/jpg,image/png" name="photo" id="photo"
                                                       data-input="false" data-classbutton="btn btn-secondary"
                                                       data-classinput="form-control inline"
                                                       data-text="Upload new picture"
                                                       data-icon="&lt;span class='fa fa-upload mr'&gt;&lt;/span&gt;"
                                                       tabindex="4">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Date of Birth</label>
                                        <input class="form-control" type="text" data-date-format="dd-mm-yyyy" name="dob"
                                               id="dob"
                                               value="<?php echo date("d-m-Y", strtotime($UserData['dob'])); ?>"
                                               tabindex="6">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Aadhar No</label>
                                        <input class="form-control" type="text" name="aadhar_no" id="aadhar_no"
                                               value="<?php echo $UserData['aadhar_no']; ?>" maxlength="30"
                                               onkeypress="return isNumberKey(event)" tabindex="9">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Address</label>
                                        <textarea class="form-control" name="address" id="address" rows="9"
                                                  maxlength="250"
                                                  tabindex="11"><?php echo $UserData['address']; ?></textarea>
                                    </div>
                                    <div style="height: 10px!important;">&nbsp;</div>
                                    <div class="form-group">
                                        <label class="col-form-label">Status </label>
                                        <select class="form-control" name="status" id="status"
                                                tabindex="19">
                                            <option>Select Status</option>
                                            <option <?php if ($UserData['status'] == 1) echo 'selected'; ?>
                                                value="1">Active
                                            </option>
                                            <option <?php if ($UserData['status'] == 0) echo 'selected'; ?>
                                                value="0">In-Active
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="login_tab" style="display:none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">User Name *</label>
                                        <input class="form-control" type="text" name="username" id="username"
                                               value="<?php echo $UserData['username']; ?>" maxlength="250"
                                               tabindex="17" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Password *</label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="password"
                                                       name="password" value="<?php echo $UserData['password']; ?>"
                                                       maxlength="100" tabindex="18" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="button"
                                                       class="btn btn-info"
                                                       onclick="generatePassword();"
                                                       value="Generate Password"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Service on *</label>
                                        <input class="form-control" type="text" name="service_from" id="service_from"
                                               data-date-format="dd-mm-yyyy"
                                               value="<?php echo date("d-m-Y", strtotime($UserData['service_from'])); ?>"
                                               tabindex="20"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Service to </label>

                                        <div id="entry_div" style="display: none">
                                            <input class="form-control" type="text"
                                                   name="service_to" id="service_to"
                                                   data-date-format="dd-mm-yyyy"
                                                   value="<?php echo date("d-m-Y", strtotime($UserData['service_to'])); ?>"
                                                   tabindex="21">
                                        </div>
                                        <div id="empty_div" style="display: none">
                                            <input class="form-control" type="text"
                                                   name="service" id="service"
                                                   tabindex="21" value="to  till date"
                                                   disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Service Details</label>
                                        <textarea class="form-control" name="about" id="about" rows="6"
                                                  tabindex="22"><?php echo $UserData['about']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="required">* Required fields</div>
                        </div>
                        <div class="card-footer">
                            <div class="clearfix">
                                <div class="float-right">
                                    <button class="btn btn-labeled btn-secondary" type="button"
                                            onclick="location.href='Users';">
                           <span class="btn-label"><i class="fa fa-arrow-left"></i>
                           </span>Back to List
                                    </button>
                                    <button class="btn btn-labeled btn-info" type="submit" name="update"
                                            tabindex="23">
                           <span class="btn-label"><i class="fa fa-check"></i>
                           </span>Save Changes
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
<script src="<?php echo VENDOR; ?>select2/dist/js/select2.full.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-filestyle/src/bootstrap-filestyle.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
<script>
    $(function () {
        //Date picker
        $('#dob').datepicker({
            autoclose: true
        });

        $('#service_from').datepicker({
            autoclose: true
        });

        $('#service_to').datepicker({
            autoclose: true
        });
    });


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

    function generatePassword() {
        var length = 8,
            charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
            retVal = "";
        for (var i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        $('#password').val(retVal);
    }

    $(document).ready(function () {

        var salary_mode = $('#salary_mode').val();
        if (salary_mode == "1") {
            $("#share_tab").show();
            $("#salary_tab").hide();
        } else {
            $("#share_tab").hide();
            $("#salary_tab").show();
        }

        $('#salary_mode').change(function () {
            var salary_mode = $(this).val();
            if (salary_mode == "1") {
                $("#share_tab").show();
                $("#salary_tab").hide();
            }
            else {
                $("#share_tab").hide();
                $("#salary_tab").show();
            }
        });

        $('#login_status').change(function () {
            var login_status = $(this).val();
            if (login_status == "1") {
                $("#login_tab").show();
            }
            else {
                $("#login_tab").hide();
            }
        });

        var status = $('#status').val();
        if (status == "0") {
            $("#entry_div").show();
            $("#empty_div").hide();
        } else {
            $("#entry_div").hide();
            $("#empty_div").show();
        }

        $('#status').change(function () {
            var status = $(this).val();
            if (status == "0") {
                $("#entry_div").show();
                $("#empty_div").hide();
            }
            else {
                $("#entry_div").hide();
                $("#empty_div").show();
            }
        });
    });

    $(document).ready(function () {
        $("#photo").on('change', function () {
            //Get count of selected files
            var countFiles = $(this)[0].files.length;
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#image-holder");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof(FileReader) != "undefined") {
                    //loop for each file selected for uploaded.
                    for (var i = 0; i < countFiles; i++) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("<img />", {
                                "src": e.target.result,
                                "width": "125px", height: "125px"
                            }).appendTo(image_holder);
                        }
                        $("#alternative").hide();
                        image_holder.show();
                        reader.readAsDataURL($(this)[0].files[i]);
                    }
                } else {
                    alert("This browser does not support FileReader.");
                }
            } else {
                alert("Pls select only images");
            }
        });
    });

    window.onload = function () {

        if (document.getElementById('update_success')) {
            swal("Success!", "User Details has been Updated!", "success");
            var id = $('#id').val();
            location.href = "UserEdit?uId=" + id;
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