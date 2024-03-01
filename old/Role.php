<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, 'Payments');
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
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
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
</body>
</html>