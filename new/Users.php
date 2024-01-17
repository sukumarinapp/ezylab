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
<title>Users</title>
</head>
<body class="bg-theme bg-theme2">
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
        <div class="content-heading">Users</div>
        <div class="card">
            <?php if ($PageAccessible['is_write'] == 1) { ?>
                <div class="card-header">
                    <div class="card-title pull-right">
                        <button class="btn btn-labeled btn-secondary float-end" type="button"
                                onclick="location.href='AddUser';">
                            Add New User
                            <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                           </span></button>
                    </div>
                    <div class="text-sm"></div>
                </div>
            <?php } ?>
            <div class="card-body">
                <table class="table table-striped table-hover w-100" id="datatable1">
                    <thead>
                    <tr>
                        <th width="10px">
                            <strong>#</strong>
                        </th>
                        <th>
                            <strong>NAME</strong>
                        </th>
                        <th>
                            <strong>ROLE</strong>
                        </th>
                        <th>
                            <strong>MOBILE</strong>
                        </th>
                        <th>
                            <strong>EMAIL</strong>
                        </th>
                        <th>
                            <strong>SERVICE PERIOD</strong>
                        </th>
                        <th class="text-center">
                            <strong>STATUS</strong>
                        </th>
                        <th class="text-center">
                            <strong>ACTION</strong>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 0;
                    $UserQuery = 'SELECT a.*,concat(a.prefix," ",a.name) as name,b.role FROM macho_users a,macho_role b WHERE b.id=a.role_id ORDER BY a.id DESC ';
                    $UserResult = GetAllRows($UserQuery);
                    $UserCounts = count($UserResult);
                    if ($UserCounts > 0) {
                        foreach ($UserResult as $UserData) {
                            ?>
                            <tr>
                                <td><?php echo ++$no; ?></td>
                                <td><?php echo $UserData['name']; ?></td>
                                <td><?php echo $UserData['role']; ?></td>
                                <td><?php echo $UserData['mobile']; ?></td>
                                <td><?php echo $UserData['email']; ?></td>
                                <?php if ($UserData['status'] == 0) {
                                    echo '<td>' . from_sql_date($UserData['service_from']) . ' to ' . from_sql_date($UserData['service_to']) . '</td>';
                                } else {
                                    echo '<td>' . from_sql_date($UserData['service_from']) . ' to  till date</td>';
                                } ?>
                                <td class="text-center"><?php echo(($UserData['status']) == 0 ? '<span class="badge badge-danger">In-Active</span>' : '<span class="badge badge-success">Active</span>'); ?> </td>
                                <td class="text-center">
                                    <?php if ($PageAccessible['is_read'] == 1) { ?>
                                        <button class="btn btn-sm btn-green" type="button" title="View Details"
                                                onclick="location.href='UserView?uId=<?php echo EncodeVariable($UserData['id']); ?>';">
                                            <em class="fa fa-search"></em>
                                        </button>
                                    <?php }
                                    if ($PageAccessible['is_modify'] == 1) { ?>
                                        <button class="btn btn-sm btn-info" type="button" title="Update Details"
                                                onclick="location.href='UserEdit?uId=<?php echo EncodeVariable($UserData['id']); ?>';">
                                            <em class="fa fa-edit"></em>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning"
                                                title="Update Access Details"
                                                onClick="document.location.href='EditAccess?uID=<?php echo EncodeVariable($UserData['id']); ?>'">
                                            <i class="fas fa-wrench"></i></button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
</div>

   <?php include ("js.php"); ?>
</body>
</html>