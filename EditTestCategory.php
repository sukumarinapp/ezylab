<?php
include_once 'booster/bridge.php';
IsAjaxRequest();
$id = Filter($_POST['id']);
$TestCategoryData = SelectParticularRow('macho_test_category', 'id', $id);
$today = date("Y-m-d");
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="TestCategory">
            <input type="hidden" name="dept_id" id="dept_id" value="1">
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">Department Name</label>
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                        <input class="form-control" type="text" name="category_name" id="category_name" maxlength="100"
                               value="<?php echo $TestCategoryData['category_name']; ?>" tabindex="1">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" maxlength="500"
                               rows="4" tabindex="2"><?php echo $TestCategoryData['description']; ?></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="clearfix">
                        <div class="float-right">
                            <button class="btn btn-warning" type="submit" name="update" tabindex="3">Save Changes
                            </button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END card-->
        </form>
    </div>
</div>

