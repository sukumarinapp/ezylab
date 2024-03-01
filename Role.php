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
<title>Role</title>
</head>
<body class="bg-theme bg-<?php echo $colour ?>">
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
            <div>Role
                <small></small>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <?php if ($PageAccessible['is_write'] == 1) { ?>
                <div class="card-header">
                    <div class="card-title pull-right">
                        <button class="btn btn-labeled btn-secondary" type="button" onClick="document.location.href='AddRole'" >Add New Role
                            <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                           </span></button>
                    </div>
                    <div class="text-sm"></div>
                </div>
                <?php } ?>
                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="datatable1">
                        <thead>
                        <tr>
                            <th width="20px">#</th>
                            <th>Role Name</th>
                            <th>Role Code</th>
                            <th>Created</th>
                            <th>Last Modified</th>
                            <?php if ($PageAccessible['is_modify'] == 1) { ?>
                            <th>Action</th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $RoleQuery = "SELECT * FROM macho_role ORDER BY id";
                        $RoleResult = GetAllRows($RoleQuery);
                        $RoleCounts = count($RoleResult);
                        if ($RoleCounts > 0) {
                            foreach ($RoleResult as $RoleData) { ?>
                                <tr>
                                    <td><?php echo ++$no; ?></td>
                                    <td><?php echo $RoleData['role']; ?></td>
                                    <td><?php echo $RoleData['rcode']; ?></td>
                                    <td><?php echo $RoleData['created']; ?></td>
                                    <td><?php echo $RoleData['modified']; ?></td>
                                <?php if ($PageAccessible['is_modify'] == 1) { ?>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-info" type="button" title="Update Role"   onClick="document.location.href='EditRole?rID=<?php echo EncodeVariable($RoleData['id']); ?>'"><i class="fa fa-edit"></i> Update
                                            </button>
                                        </div>
                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>	
</div>

   <?php include ("js.php"); ?>
</body>
</html>