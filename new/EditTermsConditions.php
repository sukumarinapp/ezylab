<?php
session_start();
include_once 'booster/bridge.php';
IsAjaxRequest();
$id = Filter($_POST['id']);

$TermsData = SelectParticularRow('macho_terms', 'id', $id);
?>

<div class="row">
    <div class="col-xl-12">
        <form method="post" action="TermsConditions">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">Description</label>
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                     <textarea class="form-control" rows="5" name="description" id="description"
                               maxlength="500"><?php echo $TermsData['description']; ?></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="clearfix">
                        <div class="float-right">
                            <button class="btn btn-warning" type="submit" name="update" tabindex="3">Save Changes
                            </button>
                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
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