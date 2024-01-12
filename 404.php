<?php
include_once 'booster/bridge.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo TITLE; ?></title>
    <meta content="<?php echo KEYWORDS; ?>" name="description">
    <meta content="<?php echo KEYWORDS; ?>" name="author">
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
        <div class="text-center mb-4">
            <div class="text-lg mb-3">404</div>
            <p class="lead m-0">We couldn't find this page.</p>
            <p>The page you are looking for does not exists.</p>
            <br>
            <span class="input-group-btn">
               <button class="btn btn-secondary" type="button" onclick="location.href='<?php echo SITEURL .'index'; ?>';">
                   Back to Home
               </button>
            </span>
        </div>
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
</body>
</html>