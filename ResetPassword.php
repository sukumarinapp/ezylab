<?php
include_once 'booster/bridge.php';
$error = '';
$reset_key = '';
if (isset($_GET['uId'])) {
$id = DecodeVariable($_GET['uId']);

if (isset($_POST['submit'])) {
    $new_pass = EncodePass($_POST['new_pass']);
    $confirm_password = EncodePass($_POST['confirm_password']);
    $password = $_POST['confirm_password'];
    if ($new_pass == $confirm_password) {

        $sql2 = "UPDATE macho_users SET password='$confirm_password',reset_key='$reset_key' WHERE id='$id'";
        $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));

        $UserData = UserInfo($id);
        $mobile = $UserData['mobile'];
        $message = "Your Password has been Reset.Your New Password  : $password -" . OrgInfo()['macho_name'];
        SendSMS($mobile, $message);
        header("location:index");
    } else {
        $error = 'Password does not match please try again!';
    }

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
    <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON; ?>"/>
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
                <p class="text-center py-2">Reset Your Password.</p>

                <form action="" method="post" class="mb-6" id="loginForm" novalidate>
                    <div class="form-group">
                        <div class="input-group with-focus">
                            <input class="form-control border-right-0" name="new_pass" id="new_pass"
                                   type="password" placeholder="New Password" required>

                            <div class="input-group-append">
                                <span
                                    class="input-group-text fa fa-lock text-muted bg-transparent border-left-0"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group with-focus">
                            <input class="form-control border-right-0" name="confirm_password" id="confirm_password"
                                   type="password" placeholder="Confirm Password" required>

                            <div class="input-group-append">
                                <span
                                    class="input-group-text fa fa-lock text-muted bg-transparent border-left-0"></span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-block btn-primary mt-3" type="submit" name="submit">Submit</button>
                </form>
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
<?php } ?>
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
