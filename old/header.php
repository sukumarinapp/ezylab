<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$role = $_SESSION["role"];
$user = $_SESSION["user"];
$user_name = $_SESSION["user_name"];
$email = $_SESSION["user_email"];
$picture = $_SESSION["picture"];
$access_token = $_SESSION["access_token"];
ValidateAccessToken($user_id, $access_token);
//if(!is_connected()){
//    header("location:logout");
//    exit;
//}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>
        <?= TITLE; ?>
    </title>
    <meta content="<?= KEYWORDS; ?>" name="description">
    <meta content="<?= KEYWORDS; ?>" name="author">
    <link rel="shortcut icon" type="image/png" href="<?= FAVICON; ?>" />
    <!-- =============== VENDOR STYLES ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="<?php echo VENDOR; ?>font-awesome/css/font-awesome.css">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="<?php echo VENDOR; ?>simple-line-icons/css/simple-line-icons.css">
    <!-- ANIMATE.CSS-->
    <link rel="stylesheet" href="<?php echo VENDOR; ?>animate.css/animate.css">
    <!-- WHIRL (spinners)-->
    <link rel="stylesheet" href="<?php echo VENDOR; ?>whirl/dist/whirl.css">
    <!-- =============== PAGE VENDOR STYLES ===============-->
    <!-- Datatables-->
    <link rel="stylesheet" href="<?php echo VENDOR; ?>datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?php echo VENDOR; ?>datatables.net-keytable-bs/css/keyTable.bootstrap.css">
    <link rel="stylesheet" href="<?php echo VENDOR; ?>datatables.net-responsive-bs/css/responsive.bootstrap.css">
    <!-- SELECT2-->
    <link rel="stylesheet" href="<?php echo VENDOR; ?>select2/dist/css/select2.css">
    <link rel="stylesheet" href="<?php echo VENDOR; ?>dropzone/dist/dropzone.css">

    <link rel="stylesheet" href="<?php echo VENDOR; ?>%40ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css">

    <link rel="stylesheet" href="<?php echo VENDOR; ?>bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo VENDOR; ?>bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" href="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.css">
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="<?php echo VENDOR; ?>loaders.css/loaders.css">
    <link rel="stylesheet" href="<?php echo CSS; ?>bootstrap.css" id="bscss">
    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="<?php echo CSS; ?>app.css" id="maincss">
    <link rel="stylesheet" href="<?php echo CSS; ?>theme-f.css" id="maincss">
    <link rel="stylesheet" href="<?php echo CSS; ?>bootstrap-tokenfield.css">
    <!-- JQUERY-->
    <!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>-->
    <script src="<?php echo JS; ?>jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />  
      

    

    <!--    <script src="-->
    <?php //echo VENDOR; ?><!--jquery/dist/jquery.min.js"></script>-->
    <script src="<?php echo JS; ?>jquery.jkey.js"></script>
    <script>
        var user_id = "<?= $user_id; ?>";

        $(document).ready(function () {
            setInterval(function () {
                $.ajax({
                    type: 'POST',
                    url: 'GetNotification.php',
                    data: {
                        user_id: user_id
                    },
                    success: function (data) {
                        $("noti_data").html(data);
                    }
                });
            }, 60000);
        });

        function UpdateNotification() {
            $.ajax({
                type: 'POST',
                url: 'UpdateNotification.php',
                data: {
                    user_id: user_id
                },
                success: function (data) {
                }
            });
        }

        function ProceedLock(e, user_id, user_name, access_token, url) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "ProceedLock.php",
                data: {
                    user_id: user_id,
                    user_name: user_name,
                    access_token: access_token,
                    url: url
                },
                success: function (response) {
                    if (response == '1') {
                        window.location.href = "lock.php?uID=" + user_id;

                    } else {
                        alert('Oops  Could not lock the Screen');

                    }
                }
            });
        }

        function toggleFullScreen(elem) {
            if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
                if (elem.requestFullScreen) {
                    elem.requestFullScreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullScreen) {
                    elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        }



        /*        setInterval(function() {
                    $.ajax({
                        url: "https://cms.example.com/ping",
                    })
                        .fail(function( data ) {
                            window.location.href = "logout";
                            // remember do to something smart which shows the error just once
                            // instead of every five seconds. Increasing the interval every
                            // time it fails seems a good start.
                        });
                }, 30*1000);*/
    </script>
    <style>
        @keyframes pulse {
            0% {
                font-size: 1em;
            }

            50% {
                font-size: 1.2em;
            }

            100% {
                font-size: 1em;
            }
        }

        .blood-icon {
            animation: pulse 1s infinite;
        }

        @keyframes rotate {
            0% {
                font-size: 1em;
            }

            50% {
                font-size: 1.2em;
            }

            100% {
                font-size: 1em;
            }
        }

        .needle-icon {
            animation: rotate 2s linear infinite;
        }

        @keyframes ambulance-animation {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .ambulance-icon {
            animation: ambulance-animation 1s linear infinite;
        }

        @keyframes settings-animation {
            0% {
                font-size: 1em;
            }

            50% {
                font-size: 1.2em;
            }

            100% {
                font-size: 1em;
            }
        }

        .settings-icon {
            animation: settings-animation 2s linear infinite;
        }

        @keyframes nurse-animation {
            0% {
                font-size: 1em;
            }

            50% {
                font-size: 1.2em;
            }

            100% {
                font-size: 1em;
            }
        }

        .nurse-icon {
            animation: nurse-animation 1s ease-in-out infinite;
        }

        @keyframes rupee-animation {
            0% {
                font-size: 1em;
            }

            50% {
                font-size: 1.2em;
            }

            100% {
                font-size: 1em;
            }
        }

        .rupee-icon {
            animation: rupee-animation 1s linear infinite;
        }

        @keyframes patient-animation {
            0% {
                font-size: 1em;
            }

            50% {
                font-size: 1.2em;
            }

            100% {
                font-size: 1em;
            }
        }

        .patient-icon {
            animation: patient-animation 1s ease-in-out infinite;
        }

        @keyframes clipboard-animation {
            0% {
                font-size: 1em;
            }

            50% {
                font-size: 1.2em;
            }

            100% {
                font-size: 1em;
            }
        }

        .fa-clipboard {
            animation: clipboard-animation 2s infinite;
        }

        @keyframes finance-animation {
            0% {
                font-size: 1em;
            }

            50% {
                font-size: 1.2em;
            }

            100% {
                font-size: 1em;
            }
        }

        .finance-icon {
            animation: finance-animation 2s infinite linear;
        }

        @keyframes bitcoin-animation {
            0% {
                font-size: 1em;
            }

            50% {
                font-size: 1.2em;
            }

            100% {
                font-size: 1em;
            }
        }

    /* Apply the animation to the icon */
    .bitcoin-icon {
      animation: bitcoin-animation 1s linear infinite;
    }
    </style>
</head>

<body id="short_cut_menu" >

    <script>

        $('#short_cut_menu').jkey('alt+h', function (key) {
            window.location.href = "Dashboard";
        });

        $('#short_cut_menu').jkey('alt+u', function (key) {
            window.location.href = "Users";
        });

        $('#short_cut_menu').jkey('alt+d', function (key) {
            window.location.href = "Doctors";
        });

        $('#short_cut_menu').jkey('alt+p', function (key) {
            window.location.href = "Patient";
        });

        $('#short_cut_menu').jkey('alt+f', function (key) {
            window.location.href = "Payments";
        });

        $('#short_cut_menu').jkey('alt+t', function (key) {
            window.location.href = "TestEntry";
        });
    </script>

    <div class="wrapper">
        <!-- top navbar-->
        <header class="topnavbar-wrapper">
            <!-- START Top Navbar-->
            <nav class="navbar topnavbar">
                <!-- START navbar header-->
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        <div class="brand-logo">
                            <img class="img-fluid" src="
                        <?php echo OrgInfo()['logo'] . '?' . rand(0, 100); ?>" alt="App Logo"
                                style="width: 65px;height: 34px">
                            <!--                        <span-->
                            <!--                            style="font-size:24px;font-weight:bold;font-family: 'Matura MT Script Capitals';color: #fff !important;"> &nbsp;--><? //= TITLE; ?><!--</span>-->
                        </div>
                        <div class="brand-logo-collapsed">
                            <img class="img-fluid" src="
                        <?php echo OrgInfo()['logo'] . '?' . rand(0, 100); ?>" alt="App Logo"
                                style="width: 65px;height: 34px">
                            <!--                                                <span style="font-size:24px;font-weight:bold;font-family: 'Matura MT Script Capitals';color: #fff !important;"> &nbsp;-->
                            <? //= TITLE; ?><!--</span>-->
                        </div>
                    </a>
                </div>
                <!-- END navbar header-->
                <!-- START Left navbar-->
                <ul class="navbar-nav mr-auto flex-row">
                    <li class="nav-item">
                        <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                        <a class="nav-link d-none d-md-block d-lg-block d-xl-block" href="#" data-trigger-resize=""
                            data-toggle-state="aside-collapsed">
                            <em class="fa fa-navicon"></em>
                        </a>
                        <!-- Button to show/hide the sidebar on mobile. Visible on mobile only.-->
                        <a class="nav-link sidebar-toggle d-md-none" href="#" data-toggle-state="aside-toggled"
                            data-no-persist="true">
                            <em class="fa fa-navicon"></em>
                        </a>
                    </li>
                    <!-- START User avatar toggle-->
                    <li class="nav-item d-none d-md-block">
                        <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                        <a class="nav-link" id="user-block-toggle" href="#user-block" data-toggle="collapse">
                            <em class="icon-user"></em>
                        </a>
                    </li>
                    <!-- END User avatar toggle-->
                    <!-- START lock screen-->
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link" href="#"
                            onclick="ProceedLock(event,'<?= EncodeVariable($user_id); ?>','<?= $user_name; ?>','<?= $access_token; ?>','<?= $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>');"
                            title="Lock screen">
                            <em class="icon-lock"></em>
                        </a>
                    </li>
                    <!-- END lock screen-->
                </ul>
                <!-- END Left navbar-->
                <!-- START Right Navbar-->
                <ul class="navbar-nav flex-row">
                    <!-- Fullscreen (only desktops)-->
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link" href="#" onclick="toggleFullScreen(document.body)" title="Full Screen"
                            data-toggle-fullscreen="">
                            <em class="fa fa-expand"></em>
                        </a>
                    </li>
                    <!--                 START Alert menu-->
                    <li class="nav-item dropdown dropdown-list">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                            onclick="UpdateNotification();" data-toggle="dropdown">
                            <em class="icon-bell"></em>
                            <span class="badge badge-danger">
                                <?= GetNotificationCount($user_id, $role_id); ?>
                            </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right animated flipInX">
                            <div class="dropdown-item">
                                <div class="list-group">
                                    <noti_data></noti_data>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="Calender" href="calender">
                            <em class="icon-calendar"></em>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" title="Settings" data-toggle-state="offsidebar-open"
                            data-no-persist="true">
                            <em class="icon-settings"></em>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="LogOut" href="logout">
                            <em class="icon-logout"></em>
                        </a>
                    </li>
                    <!-- END Alert menu-->
                    <!-- START Offsidebar button-->
                    <!-- END Offsidebar menu-->
                </ul>
                <!-- END Right Navbar-->
            </nav>
            <!-- END Top Navbar-->
        </header>