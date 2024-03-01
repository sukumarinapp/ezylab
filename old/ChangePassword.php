<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include "header.php";
include_once "Menu.php";
if (isset($_POST['update'])) {

    $UserData = UserInfo($user_id);
    $mobile = $UserData['mobile'];
    $exist_password = $UserData['password'];

    $current_password = EncodePass($_POST['current_password']);
    $new_password = EncodePass($_POST['new_password']);
    $confirm_password = EncodePass($_POST['confirm_password']);
    $password = $_POST['confirm_password'];
    if ($exist_password == $current_password) {
        if ($new_password == $confirm_password) {
            $update = Update('macho_users', 'id', $user_id, array(
                'password' => $confirm_password
            ));
            if ($update) {
                $message = "Your Password has been Reset.Your New Password  : $password -" . OrgInfo()['macho_name'];
                $email = GetUserEmail($user_id);
                SendEmail($email,  'Password Reset',$message);
                SendSMS($mobile, $message);
                echo '<span id="update_success"></span>';
            } else {
                echo '<span  id="update_failure"></span>';
            }
        } else {
            echo '<span  id="password_mismatch"></span>';
        }
    } else {
        echo '<span  id="password_failure"></span>';
    }
}
?>

<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <div class="card card-default">
                    <div class="card-header d-flex align-items-center">
                        <div class="d-flex justify-content-center col">
                            <div class="h4 m-0 text-center">Change Password</div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row py-4 justify-content-center">
                            <div class="col-10 col-sm-10">
                                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <div class="col-xl-10 col-md-9 col-8">
                                            <label class="text-bold col-form-label text-right"
                                                   for="inputContact1">Current Password</label>
                                            <input class="form-control" id="current_password" name="current_password"
                                                   type="password"
                                                   value="" maxlength="100"
                                                   tabindex="1">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xl-10 col-md-9 col-8">
                                            <label class="text-bold col-form-label text-right"
                                                   for="inputContact8">New Password</label>
                                            <input class="form-control" id="new_password" name="new_password"
                                                   type="password"
                                                   value="" maxlength="100"
                                                   tabindex="2">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xl-10 col-md-9 col-8">
                                            <label class="text-bold col-form-label text-right"
                                                   for="inputContact8">Confirm Password</label>
                                            <input class="form-control" id="confirm_password"
                                                   name="confirm_password"
                                                   type="password"
                                                   value="" maxlength="100"
                                                   tabindex="3">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xl-10 col-md-9 col-8">
                                            <div class="text-right">
                                                <button type="button" class="btn btn-default"
                                                        onclick="generatePassword()">Generate Password
                                                </button>
                                                <button class="btn btn-info" type="submit" name="update" tabindex="4">
                                                    Update
                                                    Password
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once "footer.php"; ?>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
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
    function generatePassword() {
        var length = 8,
            charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
            retVal = "";
        for (var i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }

        swal(retVal);
    }

    window.onload = function () {

        if (document.getElementById('password_failure')) {
            swal({
                title: "Oops!",
                text: "Your Current Password Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

        if (document.getElementById('password_mismatch')) {
            swal({
                title: "Oops!",
                text: "Confirm Password And New Password Mismatch...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "New Password has been Updated!", "success");
            location.href = "index";
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