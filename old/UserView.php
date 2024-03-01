<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include 'Menu.php';
$UserID = DecodeVariable($_GET['uId']);
$UserData = UserInfo($UserID);
?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading"><?php echo $UserData['prefix'] . $UserData['name']; ?></div>
        <div class="card card-default">
            <div class="card-header">User Information
                <div class="card-title pull-right">
                    <button class="btn btn-labeled btn-secondary" type="button"
                            onclick="location.href='Users';">
                            <span class="btn-label btn-label"><i class="fa fa-reply"></i>
                           </span> Back to List</button>
                </div>
            </div>
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
                                <div class="col-md-4">Gender:</div>
                                <div class="col-md-8">
                                    <strong><?php echo $UserData['gender']; ?></strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">Date of Birth:</div>
                                <div class="col-md-8">
                                    <strong><?php echo $UserData['dob']; ?></strong>
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
                                <div class="col-md-4">Aadhar No:</div>
                                <div class="col-md-8">
                                    <strong><?php echo $UserData['aadhar_no']; ?></strong>
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
                            <div class="form-group row">
                                <div class="col-md-4">Service On</div>
                                <div class="col-md-8">
                                    <strong><?php if ($UserData['status'] == 0) {
                                            echo from_sql_date($UserData['service_from']) . ' to ' . from_sql_date($UserData['service_to']);
                                        } else {
                                            echo from_sql_date($UserData['service_from']) . ' to  till date';
                                        } ?></strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">Created</div>
                                <div class="col-md-8">
                                    <strong><?php echo $UserData['created']; ?></strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">Last Modified</div>
                                <div class="col-md-8">
                                    <strong><?php echo $UserData['modified']; ?></strong>
                                </div>
                            </div>
                            <?php if ($UserData['editby'] != 0) { ?>
                                <div class="form-group row">
                                    <div class="col-md-4">Last Modified By</div>
                                    <div class="col-md-8">
                                        <strong><?php echo UserInfo($UserData['editby'])['prefix'] . UserInfo($UserData['editby'])['name']; ?></strong>
                                    </div>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
                <div class="alert alert-default">Service Details<br>
                    <hr>
                    <br>
                    <?php echo $UserData['about']; ?></div>
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php' ?>
</div>
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
<script src="<?php echo VENDOR; ?>bootstrap-filestyle/src/bootstrap-filestyle.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
</body>
</html>