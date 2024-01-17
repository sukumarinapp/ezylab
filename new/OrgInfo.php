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


?>
<!doctype html>
<html lang="en">

<head>
<?php include ("headercss.php"); ?>
<title>Info</title>
</head>
<body class="bg-theme bg-theme2">
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
        if (file_exists("logo/" . $header_logopic)) {
            unlink("logo/" . $header_logopic);
            $ext1 = pathinfo($header_logo, PATHINFO_EXTENSION);
            $header_logo1 = 'header' . $macho_id . "." . $ext1;
            $move_path1 = "logo/";
            $move_path1 = $move_path1 . $header_logo1;
            $target_path1 = SITEURL . "logo/";
            $target_path1 = $target_path1 . $header_logo1;
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
    }

    if ($footer_logo != '') {

        $footer_logopic = basename($OrgInfo['footer_logo']);
        if (file_exists("logo/" . $footer_logopic)) {
            unlink("logo/" . $footer_logopic);
            $ext1 = pathinfo($footer_logo, PATHINFO_EXTENSION);
            $footer_logopic1 = 'footer' . $macho_id . "." . $ext1;
            $move_path1 = "logo/";
            $move_path1 = $move_path1 . $footer_logopic1;
            $target_path1 = SITEURL . "logo/";
            $target_path1 = $target_path1 . $footer_logopic1;
            if (move_uploaded_file($_FILES['footer_logo']['tmp_name'], $move_path1)) {
                $update_logo = Update(
                    'macho_info',
                    'id',
                    $macho_id,
                    array(
                        'footer_logo' => $target_path1
                    )
                );
            }
        }
    }

    $update = Update('macho_info','id',$macho_id,
        array(
            'name' => Filter($_POST['name']),
            'address' => Filter($_POST['address']),
            'state' => Filter($_POST['state']),
            'email' => Filter($_POST['email']),
            'mobile' => Filter($_POST['mobile']),
            'land_line' => Filter($_POST['land_line']),
            'site_url' => Filter($_POST['site_url']),
            'reg_no' => Filter($_POST['reg_no']),
            'gstin' => Filter($_POST['gstin']),
            'bank_info' => Filter($_POST['bank_info'])
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
            <div class="col-lg-4">
                <div class="card card-default d-none d-lg-block">
                    <div class="card-header">
                        <div class="card-title text-center">Recent contacts</div>
                    </div>
                    <div class="card-body">
                        <?php
                        $machoUserQuery = 'SELECT concat(a.prefix," ",a.name) as name,a.role_id,a.avatar,b.role FROM macho_users a,macho_role b WHERE a.status="1" AND b.id=a.role_id ORDER BY a.id DESC ';
                        $machoUserResult = GetAllRows($machoUserQuery);
                        $machoUserCounts = count($machoUserResult);
                        if ($machoUserCounts > 0) {
                            foreach ($machoUserResult as $machoUserData) {
                                ?>
                                <div class="media">
                                    <img class="align-self-center mr-2 rounded-circle img-thumbnail thumb48" src="<?php if ($machoUserData['avatar'] != '') {
                                        echo $machoUserData['avatar'];
                                    } else {
                                        echo 'profile_pic/default.png';
                                    } ?>" alt="Contact">

                                    <div class="media-body py-2">
                                        <div class="text-bold">
                                            <?php echo $machoUserData['name'] ?>
                                            <div class="text-sm text-muted">
                                                <?php echo $machoUserData['role'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
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
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact1">Name</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <input class="form-control" id="name" name="name" type="text"
                                                value="<?php echo $OrgInfo['name']; ?>" maxlength="100" tabindex="1">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact2">Email</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <input class="form-control" id="email" name="email" type="email"
                                                value="<?php echo $OrgInfo['email']; ?>" maxlength="100" tabindex="2">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact3">Phone</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <input class="form-control" id="land_line" name="land_line" type="text"
                                                value="<?php echo $OrgInfo['land_line']; ?>" maxlength="100"
                                                tabindex="3">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact4">Mobile</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <input class="form-control" id="mobile" name="mobile" type="text"
                                                value="<?php echo $OrgInfo['mobile']; ?>" maxlength="100" tabindex="4">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact5">Website</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <input class="form-control" id="site_url" name="site_url" type="text"
                                                value="<?php echo $OrgInfo['site_url']; ?>" maxlength="100"
                                                tabindex="5">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact6">Address</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <textarea class="form-control" id="address" name="address" rows="4"
                                                maxlength="250"
                                                tabindex="6"><?php echo $OrgInfo['address']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact7">State</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <input class="form-control" id="state" name="state" type="text"
                                                value="<?php echo $OrgInfo['state']; ?>" maxlength="100" tabindex="7">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact8">Reg. No.</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <input class="form-control" id="reg_no" name="reg_no" type="text"
                                                value="<?php echo $OrgInfo['reg_no']; ?>" maxlength="30" tabindex="8">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact8">GSTIN</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <input class="form-control" id="gstin" name="gstin" type="text"
                                                value="<?php echo $OrgInfo['gstin']; ?>" maxlength="30" tabindex="9">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-bold col-xl-2 col-md-3 col-4 col-form-label text-right"
                                            for="inputContact6">Bank Info</label>

                                        <div class="col-xl-10 col-md-9 col-8">
                                            <textarea class="form-control" id="bank_info" name="bank_info" rows="4"
                                                maxlength="500"
                                                tabindex="10"><?php echo $OrgInfo['bank_info']; ?></textarea>
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
                                                    <img src="<?php if ($OrgInfo['header_logo'] != '') {
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
                                    </div>
                                    <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                        <div class="text-right">
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