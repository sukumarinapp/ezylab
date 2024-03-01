<?php
include_once 'booster/bridge.php';
IsAjaxRequest();
$id = Filter($_POST['id']);
$holiday = SelectParticularRow('macho_holiday', 'id', $id);
$today = date("Y-m-d");
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="Holiday">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">Description</label>
                        <input type="hidden" name="id" id="id" value="<?php echo $holiday['id']; ?>">
                        <input class="form-control" type="text" name="description" id="description" maxlength="100"
                               value="<?php echo $holiday['description']; ?>" tabindex="1">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Start Date</label>
                        <input class="form-control" type="text" name="start_date" id="start_date2"
                               value="<?php echo from_sql_date($holiday['start_date']); ?>"
                               min="<?php echo from_sql_date($today); ?>" tabindex="2">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">End Date</label>
                        <input class="form-control" type="text" name="end_date" id="end_date2"
                               value="<?php echo from_sql_date($holiday['end_date']); ?>"
                               min="<?php echo from_sql_date($today); ?>" tabindex="3">
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
<script>
    $(function () {
        //Date picker
        $('#start_date2').datepicker({
            autoclose: true
        });

        $('#end_date2').datepicker({
            autoclose: true
        });
    });
</script>

