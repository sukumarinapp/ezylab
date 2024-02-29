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
   //ValidateAccessToken($user_id, $access_token);
   $page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

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
<title>Add New User Details</title>
</head>
<body class="bg-theme bg-<?php echo $colour ?>">
    <?php 
if (isset($_POST['submit'])) {

    $photo = $_FILES['photo']['name'];

    $insert_macho_user = Insert('macho_users', array(
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
        'login_status' => Filter($_POST['login_status']),
        'editby' => $user_id
    ));
    if (is_int($insert_macho_user)) {
        $last_insert_id = $insert_macho_user;

        if (trim($photo) != "") {
            $ext = pathinfo($photo, PATHINFO_EXTENSION);
            $profile_pic = $last_insert_id . "." . $ext;
            $move_path = "profile_pic/";
            $move_path = $move_path . $profile_pic;
            $target_path = SITEURL . "profile_pic/";
            $target_path = $target_path . $profile_pic;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $move_path)) {
                $update = Update('macho_users', 'id', $last_insert_id, array(
                    'avatar' => $target_path,
                ));
            }
        }

        UserPageAcceses($last_insert_id, $_POST['role_id']);

        $notes = $_POST['name'] . ' User details added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
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
	  
        <div class="content-heading">Add New User Details</div>
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
                                                    <option value="Mr. ">Mr.</option>
                                                    <option value="Miss. ">Miss.</option>
                                                    <option value="Mrs. ">Mrs.</option>
                                                </select>
                                            </div>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" name="name" id="name"
                                                       maxlength="200" tabindex="2" required>
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
                                                echo "<option value='" . $RoleData['id'] . "'>" . $RoleData['role'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Gender *</label>
                                        <select class="form-control" name="gender" id="gender" required tabindex="5">
                                            <option>Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Trans Gender">Trans Gender</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Mobile *</label>
                                                <input class="form-control" type="text" name="mobile" id="mobile"
                                                       maxlength="20"
                                                       onkeypress="return isNumberKey(event)" tabindex="7" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Phone</label>
                                                <input class="form-control" type="text" name="phone" id="phone"
                                                       maxlength="20"
                                                       onkeypress="return isNumberKey(event)" tabindex="8">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Email</label>
                                        <input class="form-control" type="email" name="email" id="email"
                                               maxlength="250" tabindex="10">
                                    </div>
                                    
                                    <div id="share_tab" style="display:none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Share Percentage</label>
                                                    <input class="form-control" type="text" name="salary_percentage"
                                                           id="salary_percentage" maxlength="20"
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
                                                        <option value="1">Days</option>
                                                        <option value="2">Weeks</option>
                                                        <option value="3">Months</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Login Priority </label>
                                        <select class="form-control" name="login_status" id="login_status"
                                                tabindex="16">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
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
                                                        src="profile_pic/default.png" alt=""
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
                                               id="dob" tabindex="6">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Aadhar No</label>
                                        <input class="form-control" type="text" name="aadhar_no" id="aadhar_no"
                                               maxlength="30"
                                               onkeypress="return isNumberKey(event)" tabindex="9">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Address</label>
                                        <textarea class="form-control" name="address" id="address" rows="9"
                                                  maxlength="250"
                                                  tabindex="11"></textarea>
                                    </div>
                                    <div style="height: 10px!important;">&nbsp;</div>
                                    <div class="form-group">
                                        <label class="col-form-label">Service on *</label>
                                        <input class="form-control" type="text" name="service_from" id="service_from"
                                               data-date-format="dd-mm-yyyy"
                                               tabindex="19"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="login_tab" style="display:none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">User Name *</label>
                                        <input class="form-control" type="text" name="username" id="username"
                                               maxlength="250" tabindex="17" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Password *</label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="password"
                                                       name="password" value=""
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Service Details</label>
                                        <textarea class="form-control" name="about" id="about" rows="6"
                                                  tabindex="20"></textarea>
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
                                    <button class="btn btn-labeled btn-success" type="submit" name="submit"
                                            tabindex="21">
                           <span class="btn-label"><i class="fa fa-check"></i>
                           </span>Save
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
</div>

   <?php include ("js.php"); ?>

<script>
    $(function () {
        //Date picker
        $('#dob').datepicker({
            autoclose: true
        });

        $('#service_from').datepicker({
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

        if (document.getElementById('insert_success')) {
            swal("Success!", "User Details has been Added!", "success");
            location.href = "Users";
        }

        if (document.getElementById('insert_failure')) {
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