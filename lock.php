<?php
include_once 'booster/bridge.php';
$error = '';
$uID = Filter(DecodeVariable($_GET['uID']));
$created = date("Y-m-d");
$sql = "SELECT * FROM macho_session_brokedup WHERE id=(SELECT MAX(id) as id FROM macho_session_brokedup WHERE login_id='$uID'  AND created='$created' )";
$result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
$count = mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);
$user_id = $row['login_id'];
$username = $row['username'];
$access_token = $row['access_token'];
$url = $row['current_url'];

$role_id = GetRoleOfUser($user_id);

$user_data = UserInfo($user_id);

$picture = $user_data['avatar'];

if (isset($_POST['login'])) {
    $pass = EncodePass(Filter($_POST['password']));
    $sql2 = "SELECT * FROM macho_users WHERE username='$username' AND password='$pass'";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
    $row2 = mysqli_fetch_assoc($result2);
    if ($access_token == $row2['access_token']) {
        $role_id = $user_data['role_id'];
        $user = $user_data['prefix'] . ' ' . $user_data['name'];
        $user_name = $user_data['username'];
        $email = $user_data['email'];
        $picture = $user_data['avatar'];
        $access_token = GetAccessToken();
        $role = RoleName($role_id);
        $access_token = GetAccessToken();
        UpdateAccessToken($user_id, $access_token);
        session_start();

        $_SESSION["user_id"] = $user_id;
        $_SESSION["role_id"] = $role_id;
        $_SESSION["role"] = $role;
        $_SESSION["user"] = $user;
        $_SESSION["user_name"] = $user_name;
        $_SESSION["user_email"] = $email;
        $_SESSION["picture"] = $picture;
        $_SESSION["access_token"] = $access_token;
        header("location:" . $url);
        exit;
    } else {

        $error = 'Password Mismatch!';
        echo '<span  id="error"></span>';
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
    <div class="abs-center wd-xl">
        <!-- START card-->
        <div class="d-flex justify-content-center">
            <div class="p-2">
                <img class="img-fluid img-thumbnail rounded-circle" src="<?php if ($picture != '') {
                    echo $picture;
                } else {
                    echo 'profile_pic/default.png';
                } ?>" alt="Avatar" width="60" height="60">
            </div>
        </div>
        <div class="card b0">
            <div class="card-body">
                <p class="text-center">Please login to unlock your screen.</p>

                <form action="" method="post">
                    <div class="form-group">
                        <div class="input-group with-focus">
                            <input class="form-control border-right-0" name="password" id="password" type="password"
                                   placeholder="Password" autocomplete="off" required>

                            <div class="input-group-append">
                                <span
                                    class="input-group-text fa fa-lock text-muted bg-transparent border-left-0"></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mt-1">
                            <a class="text-muted" href="index">
                                <small>Back to Login</small>
                            </a>
                        </div>
                        <div class="ml-auto"><a href="#">
                                <button class="btn btn-sm btn-primary" type="submit" name="login">Unlock</button>
                            </a>
                        </div>
                    </div>
                </form>
                <?php
                if (!empty($error)) {

                    ?>
                    <div class="alert alert-danger" role="alert" id="AlertMessage">
                        <strong><?php echo $error; ?></strong></div>
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
    }, 5000);
</script>
</body>
</html>