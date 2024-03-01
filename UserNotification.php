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

$today = date("Y-m-d");
$from_date = date('Y-m-01');
$to_date = date("Y-m-d");
if (isset($_POST['search'])) {
    $from_date = to_sql_date($_POST['from_date']);
    $to_date = to_sql_date($_POST['to_date']);
}
?><?php include ("css.php"); ?>
<title>Notification</title>
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
        <div class="content-heading">Notification
            <?php if ($PageAccessible['is_read'] == 1) { ?>
            <div class="ml-auto">
                <form action="UserNotification" method="post">
                    <div class="row" align="right" style="float: right">
                        <div class="col-xs-6">
                            <div class="input-group">
                                <input type="date" class="form-control" id="startdate" name="from_date"
                                       value="<?php echo $from_date; ?>"
                                       tabindex="1" required>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                                <input type="date" class="form-control" id="enddate" name="to_date"
                                       value="<?php echo $to_date; ?>"
                                       max="<?php echo date("Y-m-d"); ?>"
                                       tabindex="2" required>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit" name="search" title="Search"
                                                tabindex="3"><i class="fa fa-search"></i></button>
                                    </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <!-- Team messages-->
                    <div class="card-body">
                        <table class="table table-striped table-hover w-100" id="datatable1">
                            <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $NotificationSql = "SELECT * FROM macho_notifications WHERE receive_role_id='$role_id' AND receive_id='$user_id' AND created>='$from_date' AND created<='$to_date' ORDER BY id DESC";
                            $NotificationResult = GetAllRows($NotificationSql);
                            $NotificationCounts = count($NotificationResult);
                            if ($NotificationCounts > 0) {
                                foreach ($NotificationResult as $NotificationData) {
                                    $SenderData = UserInfo($NotificationData['sender_id']);
                                    $sender_name = $SenderData['name']; ?>
                                    <tr>
                                        <td>
                                            <!-- START list group-->
                                            <div class="list-group" data-height="180" data-scrollable="">
                                                <!-- START list group item-->
                                                <div class="list-group-item list-group-item-action">
                                                    <div class="media">
                                                        <img class="align-self-start mx-2 circle thumb32"
                                                             src="<?php if ($SenderData['avatar'] != '') {
                                                                 echo $SenderData['avatar'];
                                                             } else {
                                                                 echo 'profile_pic/default.png';
                                                             } ?>" alt="Image">

                                                        <div class="media-body text-truncate">
                                                            <p class="mb-1">
                                                                <strong class="text-primary">
                                                                    <span><?= $sender_name; ?></span>
                                                                </strong>
                                                            </p>

                                                            <p class="mb-1 text-sm"><?= $NotificationData['notes']; ?></p>
                                                        </div>
                                                        <div class="ml-auto">
                                                            <small
                                                                class="text-muted ml-2"><?= TimeAgo($NotificationData['date_time']); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- END list group item-->
                                            </div>
                                            <!-- END list group-->
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if ($PageAccessible['is_delete'] == 1) { ?>
                                                    <button class="btn btn-danger" type="button"
                                                            onclick="Delete('macho_notifications','id',<?php echo $NotificationData['id']; ?>);">
                                                        <i class="fa fa-trash-o"></i>
                                                        Delete
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- End Team messages-->
                </div>
            </div>
        </div>
    </div>
</section>
	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
<script>

    $("#enddate").change(function () {
        var startDate = document.getElementById("startdate").value;
        var endDate = document.getElementById("enddate").value;
        if ((Date.parse(endDate) < Date.parse(startDate))) {
            swal("End date should be greater than Start date!");
            document.getElementById("enddate").value = startDate;
        }
    });

    function Delete(table, key, id) {
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Entry!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'Yes!',
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: "Delete.php",
                        data: {
                            table: table,
                            key: key,
                            id: id
                        },
                        success: function (response) {
                            if (response == '1') {
                                swal("Deleted!", "Selected Notification Data has been deleted!", "success");
                                location.href = "UserNotification";
                            } else {
                                swal({
                                    title: "Oops!",
                                    text: "Something Wrong...",
                                    imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
                                });
                            }
                        }
                    });

                } else {
                    swal("Cancelled", "Your Entry Data is safe :)", "error");
                }
            });
    }
</script>
</body>
</html>