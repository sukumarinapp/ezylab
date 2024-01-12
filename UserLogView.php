<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$id = DecodeVariable($_GET['uId']);
$created = base64_decode($_GET['created']);
$UserData = UserInfo($id);
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
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
</section>
<!-- Page footer-->
<?php include_once 'footer.php'; ?>
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
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
</body>
</html>