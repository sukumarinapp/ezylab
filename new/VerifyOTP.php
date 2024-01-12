<?php
include_once 'booster/bridge.php';
$error = '';
if (isset($_GET['uId'])) {
$id = DecodeVariable($_GET['uId']);

$UserData = UserInfo($id);
$mobile = $UserData['mobile'];
$reset_key = $UserData['reset_key'];
if ($reset_key == '' || $reset_key == -1) {
    $url = SITEURL . 'VerifyOTP.php?uId=' . $_GET['uId'];
    $otp = GenerateOtp();

    $sql2 = "UPDATE macho_users SET reset_key='$otp' WHERE id='$id'";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));

    $email = GetUserEmail($id);
    SendEmail($email,  'Password Reset OTP',"We 've sent this message because you requested that your OTP No : $otp be reset.please visit our site : $url ");
    SendSMS($mobile, "We 've sent this message because you requested that your OTP No : $otp be reset.please visit our site : $url ");
}
if (isset($_POST['submit'])) {
    $otp = $_POST['otp'];
    $UserData = UserInfo($id);
    $exist_otp = $UserData['reset_key'];
    if ($otp == $exist_otp) {
        header("location:ResetPassword?uId=" . $_GET['uId']);
    } else {
        $error = 'OTP does not match please Enter Valid OTP!';
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
                <p class="text-center py-2">Verify to your account.</p>

                <form action="" method="post">
                    <div class="form-group">
                        <label class="text-muted" for="resetInputEmail1">Enter OTP</label>

                        <div class="input-group with-focus">
                            <input class="form-control border-right-0" id="otp" name="otp" type="text"
                                   placeholder="Enter OTP" autocomplete="off" required>

                            <div class="input-group-append">
                                <span
                                    class="input-group-text fa fa-envelope text-muted bg-transparent border-left-0"></span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix">
                        <button class="btn btn-primary btn-block" type="submit" name="submit">Submit</button>
                    </div>
                </form>
                <a class="text-muted" href="#" id="resend_button" onclick="resend_otp(<?php echo $id; ?>);"><p
                        class="pt-3 text-center">
                        Resend OTP</p></a>

                <div class="alert alert-success" id="sucess" style="display: none">OTP has sent Again!</div>
                <div class="alert alert-danger" id="error" style="display: none">Oops! Something Wrong...</div>
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
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.min.js"></script>
<!-- BOOTSTRAP-->
<script src="<?php echo VENDOR; ?>bootstrap/dist/js/bootstrap.js"></script>
<!-- STORAGE API-->
<script src="<?php echo VENDOR; ?>js-storage/js.storage.js"></script>
<!-- PARSLEY-->
<script src="<?php echo VENDOR; ?>parsleyjs/dist/parsley.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script>
    function resend_otp(id) {
        $('#resend_button').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "ResendOTP.php",
            data: {
                id: id
            },
            success: function (response) {
                if (response == 1) {
                    $('#sucess').show('');
                    $("#sucess").fadeTo(2000, 500).slideUp(500, function () {
                        $("#sucess").slideUp(500);
                    });
                } else {
                    $('#error').show('');
                    $("#error").fadeTo(2000, 500).slideUp(500, function () {
                        $("#error").slideUp(500);
                    });
                }
                $('#resend_button').prop('disabled', false);
            }
        });
    }

    setTimeout(function () {
        $('#AlertMessage').fadeOut('fast');
    }, 7000);
</script>
</body>
</html>