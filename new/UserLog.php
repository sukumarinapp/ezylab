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
?><?php include ("css.php"); ?>
<title>Users Log</title>
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
        <div class="content-heading">
            <div>Users Log
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
                            <th>Ip Address</th>
                            <th>LogIn Time</th>
                            <th>LogOut Time</th>
                            <th>LogIn Count</th>
                            <?php if ($PageAccessible['is_read'] == 1) { ?>
                                <th>Action</th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $UserQuery = "SELECT DISTINCT login_id,created FROM macho_entry_log ORDER BY id DESC ";
                        $UserResult = GetAllRows($UserQuery);
                        $UserCounts = count($UserResult);
                        if ($UserCounts > 0) {
                            foreach ($UserResult as $UserData) {
                                $user_id = $UserData['login_id'];
                                $created = $UserData['created'];
                                $LastLogInData = LastLogInData($user_id, $created);
                                $UserInfo = UserInfo($user_id);
                                ?>
                                <tr>
                                    <td style="text-align: center"><?php echo ++$no; ?></td>
                                    <td><?php echo $UserInfo['prefix'] . ' ' . $UserInfo['name']; ?></td>
                                    <td><?php echo $LastLogInData['ip_addr']; ?></td>
                                    <td><?php echo $LastLogInData['in_time']; ?></td>
                                    <?php if ($LastLogInData['out_time'] == '0000-00-00 00:00:00') {
                                        echo '<td>&nbsp;</td>';
                                    } else {
                                        echo '<td>' . $LastLogInData['out_time'] . '</td>';
                                    } ?>
                                    <td><?php echo UserLogInCount($user_id, $created); ?></td>
                                    <?php if ($PageAccessible['is_read'] == 1) { ?>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary"
                                                        title="View Details"
                                                        onclick="location.href='UserLogView?uId=<?php echo EncodeVariable($user_id); ?>&created=<?php echo base64_encode($created); ?>';">
                                                    <i class="fa fa-search"></i> View Details
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
</section>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
</body>
</html>