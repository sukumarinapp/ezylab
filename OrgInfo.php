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
   ValidateAccessToken($user_id, $access_token);
   $page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

$PageAccessible = IsPageAccessible($user_id, 'Payments');

$theme = "SELECT * FROM macho_users WHERE id ='$user_id'";
$TestTypeResult = mysqli_query($GLOBALS['conn'], $theme) or die(mysqli_error($GLOBALS['conn']));
$TestTypeData = mysqli_fetch_assoc($TestTypeResult);
$colour = $TestTypeData['colour'];
?>
<!doctype html>
<html lang="en">

<head>
<?php include ("headercss.php"); ?>
<title>Info</title>
</head>
<body class="bg-theme bg-<?php echo $colour ?>">
    <?php 
    $OrgInfo = OrgInfo();

if (isset($_POST['update'])) {
    $macho_id = '1';
    $macho_logo = $_FILES['logo']['name'];
    $header_logo = $_FILES['header_logo']['name'];
    $footer_logo = $_FILES['footer_logo']['name'];

    if ($macho_logo != '') {

        $pic = basename($OrgInfo['logo']);
        if (file_exists("logo/" . $pic)) {
            unlink("logo/" . $pic);
            $ext1 = pathinfo($macho_logo, PATHINFO_EXTENSION);
            $profile_pic1 = $macho_id . "." . $ext1;
            $move_path1 = "logo/";
            $move_path1 = $move_path1 . $profile_pic1;
            $target_path1 = SITEURL . "logo/";
            $target_path1 = $target_path1 . $profile_pic1;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $move_path1)) {
                $update_logo = Update(
                    'macho_info',
                    'id',
                    $macho_id,
                    array(
                        'logo' => $target_path1
                    )
                );
            }
        }
    }

    if ($header_logo != '') {

        $header_logopic = basename($OrgInfo['header_logo']);
            //echo "logo/" . $header_logopic;die;
        if (file_exists("logo/" . $header_logopic)) {
            unlink("logo/" . $header_logopic);
        }

            $ext1 = pathinfo($header_logo, PATHINFO_EXTENSION);

            $header_logo1 = 'header' . $macho_id . "." . $ext1;
            $move_path1 = "logo/";
            $move_path1 = $move_path1 . $header_logo1;
            //$target_path1 = SITEURL . "logo/";
            $target_path1 =  $header_logo1;
            if (move_uploaded_file($_FILES['header_logo']['tmp_name'], $move_path1)) {
                $update_logo = Update(
                    'macho_info',
                    'id',
                    $macho_id,
                    array(
                        'header_logo' => $target_path1
                    )
                );
            }
    }

    $show_report_header=0;
    $show_receipt_header=0;
    if(isset($_POST['show_report_header'])) $show_report_header=1;
    if(isset($_POST['show_receipt_header'])) $show_receipt_header=1;
    $update = Update('macho_info','id',$macho_id,
        array(
            'receipt_header' => Filter($_POST['receipt_header']),
            'report_header' => Filter($_POST['report_header']),
            'report_footer' => Filter($_POST['report_footer']),
            'show_report_header' => $show_report_header,
            'show_receipt_header' => $show_receipt_header,
            'prefix' => Filter($_POST['prefix'])
        )
    );
    if ($update) {

        $notes = 'Organization Details modified by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="update_success"></span>';
    } else {
        echo '<span  id="update_failure"></span>';
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
        <div class="row">

            <div class="col-lg-12">
                <div class="card card-default">
                    <div class="card-header d-flex align-items-center">
                        <div class="d-flex justify-content-center col">
                            <div class="h4 m-0 text-center">Organisation Information</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row py-4 justify-content-center">
                            <div class="col-12 col-sm-10">
                                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                       <div class="form-group form-check">
                                      <input <?php if($OrgInfo['show_report_header'] == 1) echo "checked";  ?> name="show_report_header" class="form-check-input" type="checkbox" value="1" id="show_report_header" >
                                      <label class="form-check-label" for="show_report_header">
                                        Enable Report Header
                                      </label>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact6">Report Header</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <textarea class="form-control" id="report_header" name="report_header" rows="4"
                                                maxlength="1000"
                                                tabindex="6"><?php echo $OrgInfo['report_header']; ?></textarea>
                                        </div>
                                    </div>
                                      <div class="form-group form-check">
                                      <input <?php if($OrgInfo['show_receipt_header'] == 1) echo "checked";  ?> name="show_receipt_header" class="form-check-input" type="checkbox" value="1" id="show_receipt_header" >
                                      <label class="form-check-label" for="show_receipt_header">
                                        Enable Receipt Header
                                      </label>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact6">Receipt Header</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <textarea class="form-control" id="receipt_header" name="receipt_header" rows="4"
                                                maxlength="1000"
                                                tabindex="10"><?php echo $OrgInfo['receipt_header']; ?></textarea>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact6">Patient Code Prefix</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                             <input class="form-control" id="prefix" name="prefix" type="text"
                                                value="<?php echo $OrgInfo['prefix']; ?>" maxlength="5" tabindex="9">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact6">Logo</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <label for="profile_pic">
                                                <div id="image-holder"></div>
                                                <div class="form-group " id="alternative">
                                                    <img src="<?php if ($OrgInfo['logo'] != '') {
                                                        echo $OrgInfo['logo'];
                                                    } else {
                                                        echo 'logo/logo_icon.png';
                                                    }
                                                    echo '?' . rand(0, 100); ?>" class="img-thumbnail"
                                                        style="width: 160px!important;height: 160px!important;"
                                                        alt="user_image" />
                                                </div>
                                            </label>

                                            <div class="row">
                                                <div class="form-group c-upload col-md-12 col-md-12">
                                                    <input id="logo" accept="image/jpg,image/png" type="file"
                                                        name="logo" tabindex="11" />
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact6">Header Logo</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <label for="header_pic">
                                                <div id="header-image-holder"></div>
                                                <div class="form-group " id="header_alternative">
                                                    <img src="logo/<?php if ($OrgInfo['header_logo'] != '') {
                                                        echo $OrgInfo['header_logo'];
                                                    } else {
                                                        echo 'logo/logo_icon.png';
                                                    }
                                                    echo '?' . rand(0, 100); ?>" class="img-thumbnail"
                                                        style="width: 500px!important;height: 160px!important;"
                                                        alt="header_logo" />
                                                </div>
                                            </label>
                                            <div class="row">
                                                <div class="form-group c-upload col-md-12 col-md-12">
                                                    <input id="header_logo" accept="image/jpg,image/png" type="file"
                                                        name="header_logo" tabindex="11" />
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact6">Report Footer</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <textarea class="form-control" id="report_footer" name="report_footer" rows="4"
                                                maxlength="1000"
                                                tabindex="10"><?php echo $OrgInfo['report_footer']; ?></textarea>
                                        </div>
                                    </div>
                                   <!--  <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact6">Footer Logo</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <label for="footer_pic">
                                                <div id="footer-image-holder"></div>
                                                <div class="form-group " id="footer_alternative">
                                                    <img src="<?php if ($OrgInfo['footer_logo'] != '') {
                                                        echo $OrgInfo['footer_logo'];
                                                    } else {
                                                        echo 'logo/logo_icon.png';
                                                    }
                                                    echo '?' . rand(0, 100); ?>" class="img-thumbnail"
                                                        style="width: 500px!important;height: 160px!important;"
                                                        alt="footer_image" />
                                                </div>
                                            </label>
                                            <div class="row">
                                                <div class="form-group c-upload col-md-12 col-md-12">
                                                    <input id="footer_logo" accept="image/jpg,image/png" type="file"
                                                        name="footer_logo" tabindex="11" />
                                                </div>

                                            </div>
                                        </div>
                                    </div> -->
                                    <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                        <div class="text-center">
                                            <button class="btn btn-info" type="submit" name="update" tabindex="12">
                                                Update
                                            </button>
                                        </div>
                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>	 
</div>

   <?php include ("js.php"); ?>
<script>
    $(document).ready(function () {
        $("#logo").on('change', function () {
            //Get count of selected files
            var countFiles = $(this)[0].files.length;
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#image-holder");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof (FileReader) != "undefined") {
                    //loop for each file selected for uploaded.
                    for (var i = 0; i < countFiles; i++) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("<img />", {
                                "src": e.target.result,
                                "class": "thumb-image", width: "160",
                                height: "180"
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


        $("#header_logo").on('change', function () {
            //Get count of selected files
            var countFiles = $(this)[0].files.length;
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#header-image-holder");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof (FileReader) != "undefined") {
                    //loop for each file selected for uploaded.
                    for (var i = 0; i < countFiles; i++) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("<img />", {
                                "src": e.target.result,
                                "class": "thumb-image", width: "500",
                                height: "160"
                            }).appendTo(image_holder);
                        }
                        $("#header_alternative").hide();
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


        $("#footer_logo").on('change', function () {
            //Get count of selected files
            var countFiles = $(this)[0].files.length;
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#footer-image-holder");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof (FileReader) != "undefined") {
                    //loop for each file selected for uploaded.
                    for (var i = 0; i < countFiles; i++) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("<img />", {
                                "src": e.target.result,
                                "class": "thumb-image", width: "500",
                                height: "160"
                            }).appendTo(image_holder);
                        }
                        $("#footer_alternative").hide();
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
            swal("Success!", "Organization Details has been Updated!", "success");
            location.href = "OrgInfo";
        }

        if (document.getElementById('logo_update_failure')) {
            swal({
                title: "Oops!",
                text: "File too large. File must be less than 2 megabytes....",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
            location.href = "OrgInfo";
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