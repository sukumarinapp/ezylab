<?php
include_once 'booster/bridge.php';
$entry_id= Filter($_POST['entry_id']);
$test_id = Filter($_POST['test_id']);
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="" id="save_upload" enctype="multipart/form-data">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">Document Name</label>
                        <input type="text" name="file_name" id="file_name" class="form-control" maxlength="200"
                               value="" tabindex="1"/>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">File to Upload</label>
                        <input type="file" name="file" id="file" class="form-control" tabindex="2"/>
                    </div>
                    <div class="form-group float-right">
                        <button class="btn btn-primary" type="button" id="save_doc" onclick="docupload(event,'<?php echo $entry_id; ?>','<?php echo $test_id; ?>');" > Upload
                        </button>
                    </div>
                    <br><br>

                    <table_data></table_data>
                </div>
                <div class="card-footer">
                    <div class="clearfix">
                        <div class="float-right">
                            <button class="btn btn-warning" type="button" onclick="update_document(<?= $entry_id; ?>)">Confirm
                            </button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal" onclick="update_cancel(<?= $entry_id; ?>)">
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

