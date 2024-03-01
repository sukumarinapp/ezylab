<?php
include_once 'booster/bridge.php';
$id = Filter($_POST['id']);
$UserData = UserInfo($id);
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="UserChangePassword">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">Name</label>
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                        <input class="form-control" type="text" name="name" id="name" maxlength="100"
                               value="<?php echo $UserData['prefix'] . $UserData['name']; ?>" tabindex="1">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">New Password</label>
                        <input class="form-control" type="password" name="new_pass" id="new_pass" maxlength="100"
                               value="" tabindex="2">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Confirm Password</label>
                        <input class="form-control" type="password" name="confirm_password" id="confirm_password"
                               maxlength="100"
                               value="" tabindex="3">
                    </div>
                    <div id="show_tab" style="display: none">
                        <div class="form-group">
                            <label class="col-form-label">Password</label>
                            <input class="form-control" type="text" name="password" id="password" maxlength="100"
                                   value="">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="clearfix">
                        <div class="float-right">
                            <button type="button" class="btn btn-default" onclick="generatePassword()">Generate
                                Password
                            </button>
                            <button type="submit" name="update" class="btn btn-warning" tabindex="4">Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END card-->
        </form>
    </div>
</div>
