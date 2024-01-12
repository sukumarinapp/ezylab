<?php
include_once 'booster/bridge.php';
$id = Filter($_POST['id']);
$ProductSizeData = SelectParticularRow('macho_uom', 'id', $id);
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="Units">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">Unit</label>
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                        <input class="form-control" type="text" name="measurement" id="measurement" maxlength="50"
                               value="<?php echo $ProductSizeData['measurement']; ?>" tabindex="1">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Symbol</label>
                        <input class="form-control" type="text" name="symbol" id="symbol" maxlength="20"
                               value="<?php echo $ProductSizeData['symbol']; ?>" tabindex="2">
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

