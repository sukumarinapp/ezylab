<?php
include_once 'booster/bridge.php';
IsAjaxRequest();
$id = Filter($_POST['id']);
$GSTTypeData = SelectParticularRow('macho_gst_types', 'id', $id);
$today = date("Y-m-d");
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="GSTType">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">Description</label>
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                        <input class="form-control" type="text" name="description" id="description" maxlength="100"
                               value="<?php echo $GSTTypeData['description']; ?>" tabindex="1">
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

