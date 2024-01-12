<?php
include_once 'booster/bridge.php';
$error = '';

if (isset($_POST['login'])) {
    $geo_details = GetClientGeoDetails();
    //if (IpVerification($geo_details)) {
        //if (!IsTemporaryBlocked($geo_details->ip)) {
            //if (UserLoginValidation($geo_details->ip)) {
                $user = Filter($_POST['user']);
                if (validUserName($user)) {
                    $pass = EncodePass(Filter($_POST['pass']));
                    $sql = "SELECT * FROM macho_users WHERE username='$user' AND password='$pass' AND status=1";
                    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
                    $count = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                    $datetime = date("Y-m-d h:i:sa");
                    if ($count > 0) {
                        $reset_key = $row['reset_key'];
                        $user_id = $row['id'];
                        //if ($reset_key == -1) {
                            //header("location:VerifyOTP?uId=" . EncodeVariable($user_id));
                            //exit;
                        //}
                        //SuccessfulLogin($geo_details->ip);
                        session_start();
                        $role_id = $row['role_id'];
                        $user = $row['prefix'] . ' ' . $row['name'];
                        $user_name = $row['username'];
                        $email = $row['email'];
                        $picture = $row['avatar'];
                        $access_token = GetAccessToken();
                        $role = RoleName($role_id);

                        //UpdateAccessToken($user_id, $access_token);
                        //if (LogEntry($user_id, $geo_details)) {

                            $_SESSION["user_id"] = $user_id;
                            $_SESSION["role_id"] = $role_id;
                            $_SESSION["role"] = $role;
                            $_SESSION["user"] = $user;
                            $_SESSION["user_name"] = $user_name;
                            $_SESSION["user_email"] = $email;
                            $_SESSION["picture"] = $picture;
                            $_SESSION["access_token"] = $access_token;
                            header("location:Dashboard");
                            //exit;
                        //}
                    } else {
                        //FailureLogin($geo_details->ip);
                        $error = 'Password Wrong...!';
                    }
                } else {
                    $error = 'Username is not a valid one...';
                }
            //} else {
                //$error = 'Sorry your account is still blocked..please contact Administrator';
            //}
        //} else {
            //$error = 'Your Account has been blocked due to three failure login attempt .Please wait ' . BlockedDuration($geo_details->ip);
        //}

    //} else {
        //$error = 'Your IP Address has been blocked by Administrator';
    //}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?= TITLE; ?></title>
    <meta content="<?= KEYWORDS; ?>" name="description">
    <meta content="<?= KEYWORDS; ?>" name="author">
    <link rel="shortcut icon" type="image/png" href="<?= FAVICON; ?>"/>
    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="<?php echo VENDOR; ?>font-awesome/css/font-awesome.css">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="<?php echo VENDOR; ?>simple-line-icons/css/simple-line-icons.css">
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="<?php echo CSS; ?>bootstrap.css" id="bscss">
    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="<?php echo CSS; ?>app.css" id="maincss">
</head>

<body>
<br><br><br><br><br>

<div class="wrapper">
    <div class="block-center mt-4 wd-xl">
        <!-- START card-->
        <div class="card card-flat">
            <div class="card-header text-center bg-dark">
                <a href="#"
                   style="font-size:24px;font-weight:bold;font-family: 'Matura MT Script Capitals';color: #fff !important;"><?= TITLE; ?>
                    <!--                    <img class="block-center rounded" src="-->
                    <? //= OrgInfo()['logo'].'?'.rand(0,100); ?><!--" alt="Image"-->
                    <!--                         style="width: 85px;height: 34px">-->
                </a>
            </div>
            <div class="card-body">
                <p class="text-center py-2">SIGN IN TO CONTINUE.</p>

                <form action="" method="post" class="mb-6" id="loginForm" novalidate>
                    <div class="form-group">
                        <div class="input-group with-focus">
                            <input class="form-control border-right-0" name="user" id="user" type="text" placeholder="Username" autocomplete="off" required>

                            <div class="input-group-append">
                                <span
                                    class="input-group-text fa fa-envelope text-muted bg-transparent border-left-0"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group with-focus">
                            <input class="form-control border-right-0" name="pass" id="pass" type="password" placeholder="Password" required>

                            <div class="input-group-append">
                                <span
                                    class="input-group-text fa fa-lock text-muted bg-transparent border-left-0"></span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-block btn-primary mt-3" type="submit" name="login">Login</button>
                </form>
                <a class="text-muted" href="VerifyUser"><p class="pt-3 text-center">Forgot your password?</p></a>
                <?php
                if (!empty($error)) {

                    ?>
                    <br>
                    <div class="alert alert-danger" role="alert" id="AlertMessage">
                        <strong><?php echo $error; ?></strong></div>
                    <br>
                <?php
                }

                ?>
            </div>
        </div>
        <!-- END card-->
        <div class="p-3 text-center">
            <span class="mr-2">&copy;</span>
            <span><?php echo date('Y'); ?></span>
            <span class="mr-2">-</span>
            <span>Eazy Lab</span>
            <br>
            <span>All rights reserved.</span>
        </div>
    </div>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
<!-- BOOTSTRAP-->
<script src="<?php echo VENDOR; ?>bootstrap/dist/js/bootstrap.js"></script>
<!-- STORAGE API-->
<script src="<?php echo VENDOR; ?>js-storage/js.storage.js"></script>
<!-- PARSLEY-->
<script src="<?php echo VENDOR; ?>parsleyjs/dist/parsley.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script>
    setTimeout(function () {
        $('#AlertMessage').fadeOut('fast');
    }, 7000);
</script>
</body>
</html>
