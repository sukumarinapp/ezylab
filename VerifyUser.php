<?php
include_once 'booster/bridge.php';
$error = '';
if (isset($_POST['login_submit'])) {
    $user = Filter($_POST['username']);

    $sql = "SELECT * FROM macho_users WHERE username='$user' AND status=1 AND login_status=1";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $count = mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);
    if ($count > 0) {
        $user_id = $row['id'];
        header("location:VerifyOTP?uId=" . EncodeVariable($user_id));
        exit;
    } else {
        $error = 'Username is not a valid one';
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
                        <label class="text-muted" for="resetInputEmail1">User Name</label>
                        <div class="input-group with-focus">
                            <input class="form-control border-right-0" id="username" name="username" type="text" placeholder="Enter User Name" autocomplete="off" required>
                            <div class="input-group-append">
                                <span class="input-group-text fa fa-envelope text-muted bg-transparent border-left-0"></span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block" type="submit" name="login_submit">Submit</button>
                    <br>
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