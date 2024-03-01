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
<title>Temporary Block List</title>
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
            <div>Temporary Block List
                <small></small>
            </div>
        </div>
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title pull-right">

                    </div>
                    <div class="text-sm"></div>
                </div>
                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="datatable1">
                        <thead>
                        <tr>
                            <th width="20px">#</th>
                            <th>IP Addr</th>
                            <th>Last Login Entry</th>
                            <th>Login Date</th>
                            <th>Expired Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $TrackQuery = "SELECT * FROM `macho_user_login_attempts` WHERE block_status='1' ORDER BY id DESC ";
                        $TrackResult = GetAllRows($TrackQuery);
                        $TrackCounts = count($TrackResult);
                        if ($TrackCounts > 0) {
                            foreach ($TrackResult as $TrackData) {
                                ?>
                                <tr>
                                    <td><?php echo ++$no; ?></td>
                                    <td><?php echo $TrackData['ip_addr']; ?></td>
                                    <td><?php echo $TrackData['last_login_entry']; ?></td>
                                    <td><?php echo $TrackData['login_date']; ?></td>
                                    <td><?php echo $TrackData['expire_time']; ?></td>
                                    <td>
                                        <div class="col-md-10">
                                            <label class="switch ">
                                                <input type="hidden" name="block_value" id="block_value"
                                                       value="<?php echo $TrackData['block_status']; ?>">

                                                <input type="checkbox" <?php if ($TrackData['block_status'] == 1) {
                                                    echo 'checked="checked"';
                                                } ?> onchange="temp_block_calling(<?php echo $TrackData['id']; ?>)">
                                                <span></span
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($PageAccessible['is_delete'] == 1) { ?>
                                                <button class="btn btn-danger" type="button"
                                                        onclick="Delete('macho_user_login_attempts','id',<?php echo $TrackData['id']; ?>);">
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
            </div>
        </div>
    </div>
</section>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
<script>
    $(function () {
        //Date picker
        $('#start_date').datepicker({
            autoclose: true
        });

        $('#end_date').datepicker({
            autoclose: true
        });
    });

    function temp_block_calling(id) {
        var block_value = $('#block_value').val();
        $.ajax({
            type: "POST",
            url: "TempblockCalling.php",
            data: {
                id: id,
                status: block_value
            },
            success: function (response) {
                $('#block_value').val(response);
                location.href = 'tempBlocked'
            }
        });
    }

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
                                swal("Deleted!", "Selected Data has been deleted!", "success");
                                location.href = "tempBlocked";
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