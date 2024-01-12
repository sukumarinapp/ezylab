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

$id = DecodeVariable($_GET['uId']);
$created = base64_decode($_GET['created']);
$UserData = UserInfo($id);
?><?php include ("css.php"); ?>
<title>Log Details</title>
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
        <div class="content-heading">Log Details</div>
        <div role="tabpanel">
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item" role="presentation"><a class="nav-link active" href="#log_history"
                                                            aria-controls="edit"
                                                            role="tab"
                                                            data-toggle="tab">Log History</a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="#user_view" aria-controls="seo"
                                                            role="tab"
                                                            data-toggle="tab">User Details</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="log_history" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-12">
                            <table class="table table-striped my-4 w-100" id="datatable1">
                                <thead>
                                <tr>
                                    <th width="20px">#</th>
                                    <th>Ip Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Country</th>
                                    <th>Latitude</th>
                                    <th>Langtitude</th>
                                    <th>Postal</th>
                                    <th>LogIn Time</th>
                                    <th>LogOut Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 0;
                                $LogQuery = "SELECT * FROM macho_entry_log WHERE login_id='$id' AND created='$created' ORDER BY id DESC ";
                                $LogResult = GetAllRows($LogQuery);
                                $LogCounts = count($LogResult);
                                if ($LogCounts > 0) {
                                    foreach ($LogResult as $LogData) {
                                        ?>
                                        <tr>
                                            <td style="text-align: center"><?php echo ++$no; ?></td>
                                            <td><?php echo $LogData['ip_addr']; ?></td>
                                            <td><?php echo $LogData['city']; ?></td>
                                            <td><?php echo $LogData['state']; ?></td>
                                            <td><?php echo $LogData['country']; ?></td>
                                            <td><?php echo $LogData['lat']; ?></td>
                                            <td><?php echo $LogData['lang']; ?></td>
                                            <td><?php echo $LogData['postal']; ?></td>
                                            <td><?php echo $LogData['in_time']; ?></td>
                                            <?php if ($LogData['out_time'] == '0000-00-00 00:00:00') {
                                                echo '<td>&nbsp;</td>';
                                            } else {
                                                echo '<td>' . $LogData['out_time'] . '</td>';
                                            } ?>
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
                <div class="tab-pane" id="user_view" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <p class="lead bb"></p>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <strong><img src="<?php if ($UserData['avatar'] != '') {
                                                    echo $UserData['avatar'];
                                                } else {
                                                    echo 'profile_pic/default.png';
                                                } ?>"
                                                         class="img-thumbnail"
                                                         alt="image"
                                                         style="width: 145px!important;height: 145px!important;"></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <p class="lead bb"></p>
                                    <form class="form-horizontal">
                                        <div class="form-group row">
                                            <div class="col-md-4">Name:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $UserData['prefix'] . $UserData['name']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Role:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo RoleName($UserData['role_id']); ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Mobile:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $UserData['mobile']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Phone:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $UserData['phone']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Email:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $UserData['email']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Gender:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $UserData['gender']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Address:</div>
                                            <div class="col-md-8">
                                                <strong><?php echo $UserData['address']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">Status</div>
                                            <div class="col-md-8">
                                                <div class="badge badge-info"><?php if ($UserData['status'] == 1) {
                                                        echo 'Active';
                                                    } else {
                                                        echo 'In-Active';
                                                    }; ?></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
</body>
</html>