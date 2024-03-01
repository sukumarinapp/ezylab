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
<title>Users</title>
</head>
<body class="bg-theme bg-<?php echo $colour ?>">
    <?php
    if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $mobile = $_POST['mobile'];
    $new_pass = EncodePass($_POST['new_pass']);
    $confirm_password = EncodePass($_POST['confirm_password']);
    $password = $_POST['confirm_password'];
    $reset_key = '-1';
    if ($new_pass == $confirm_password) {

        $update = Update('macho_users', 'id', $id, array(
            'password' => $confirm_password,
            'reset_key' => $reset_key
        ));

        $email = GetUserEmail($id);
        $message = "Your Password has been Reset.Your New Password  : $password -" . OrgInfo()['name'];
        //SendSMS($mobile,$message);
        SendEmail($email,  'Password Reset',$message);
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
        <div class="content-heading">
            <div>Users
                <small></small>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="datatable1">
                        <thead>
                        <tr>
                            <th width="20px">#</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                <th>Action</th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $UserQuery = 'SELECT a.*,concat(a.prefix," ",a.name) as name,b.role FROM macho_users a,macho_role b WHERE a.status=1 AND b.id=a.role_id ORDER BY a.id DESC ';
                        $UserResult = GetAllRows($UserQuery);
                        $UserCounts = count($UserResult);
                        if ($UserCounts > 0) {
                            foreach ($UserResult as $UserData) {
                                ?>
                                <tr>
                                    <td style="text-align: center"><?php echo ++$no; ?></td>
                                    <td><?php echo $UserData['name']; ?></td>
                                    <td><?php echo $UserData['role']; ?></td>
                                    <td><?php echo $UserData['email']; ?></td>
                                    <td><?php echo $UserData['mobile']; ?></td>
                                    <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary"
                                                        title="Change Password"
                                                        onclick="PasswordReset(<?php echo $UserData['id']; ?>);"><i
                                                            class="icon-key"></i> Change Password
                                                </button>
                                            </div>
                                        </td>
                                    <?php } ?>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
</div>
<div class="modal fade" id="password_reset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Password Reset</h4>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
        </div>
    </div>
</div>
</div>

   <?php include ("js.php"); ?>
<script>
    function PasswordReset(id) {
        $.ajax({
            type: "POST",
            url: "UserPasswordReset.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#password_reset').modal('show');
            }
        });
    }

    function generatePassword() {
        var length = 8,
            charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
            retVal = "";
        for (var i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        $('#password').val(retVal);
        $("#show_tab").show();

    }

    window.onload = function () {

        if (document.getElementById('update_success')) {
            swal("Success!", "Password has been Updated Successfully!..", "success");
        }

        if (document.getElementById('update_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'plugins/bootstrap-sweetalert/assets/error_icon.png'
            });
        }
    }
</script>
</body>
</html>