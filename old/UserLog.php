<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, 'Payments');
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
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
</section>
<!-- Page footer-->
<?php include_once 'footer.php' ?>
</div>

<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
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
</body>
</html>