<?php
session_start();
include_once 'booster/bridge.php';
IsAjaxRequest();
$id = Filter($_POST['id']);

$FinanceYearData = SelectParticularRow('macho_finance_year', 'id', $id);
?>

<div class="row">
    <div class="col-xl-12">
        <form method="post" action="FinanceYear">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">Description</label>
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                        <input class="form-control" type="text" name="description" id="description"
                               value="<?php echo $FinanceYearData['description']; ?>" maxlength="100"
                               tabindex="1" required>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">From Date</label>
                        <input class="form-control" type="text" name="from_date" id="from_date"
                               data-date-format="dd-mm-yyyy" value="<?php echo from_sql_date($FinanceYearData['from_date']); ?>" required tabindex="2">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">To Date</label>
                        <input class="form-control" type="text" name="to_date" id="to_date"
                               data-date-format="dd-mm-yyyy" value="<?php echo from_sql_date($FinanceYearData['to_date']); ?>" required tabindex="3">
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