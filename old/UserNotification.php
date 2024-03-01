<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, 'Payments');

$today = date("Y-m-d");
$from_date = date('Y-m-01');
$to_date = date("Y-m-d");
if (isset($_POST['search'])) {
    $from_date = to_sql_date($_POST['from_date']);
    $to_date = to_sql_date($_POST['to_date']);
}
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
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

<!-- Page footer-->
<?php include_once 'footer.php'; ?>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.min.js"></script>
<!-- BOOTSTRAP-->
<script src="<?php echo VENDOR; ?>popper.js/dist/umd/popper.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap/dist/js/bootstrap.js"></script>
<!-- STORAGE API-->
<script src="<?php echo VENDOR; ?>js-storage/js.storage.js"></script>
<!-- JQUERY EASING-->
<script src="<?php echo VENDOR; ?>jquery.easing/jquery.easing.js"></script>
<!-- ANIMO-->
<script src="<?php echo VENDOR; ?>animo/animo.js"></script>
<!-- SCREENFULL-->
<script src="<?php echo VENDOR; ?>screenfull/dist/screenfull.js"></script>
<!-- LOCALIZE-->
<script src="<?php echo VENDOR; ?>jquery-localize/dist/jquery.localize.js"></script>
<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<!-- Datatables-->
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
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