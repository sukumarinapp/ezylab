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
        <div class="content-heading">Users</div>
        <div class="card">
            <?php if ($PageAccessible['is_write'] == 1) { ?>
                <div class="card-header">
                    <div class="card-title pull-right">
                        <button class="btn btn-labeled btn-secondary" type="button"
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
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning"
                                                title="Update Access Details"
                                                onClick="document.location.href='EditAccess?uID=<?php echo EncodeVariable($UserData['id']); ?>'">
                                            <i class="icon-support"></i></button>
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
</body>
</html>