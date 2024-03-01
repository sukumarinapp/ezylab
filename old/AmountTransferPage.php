<?php
include_once 'booster/bridge.php';
$account_id = Filter($_POST['account_id']);
$account_amount = Filter($_POST['account_amount']);
$from_date = from_sql_date($_POST['from_date']);
$to_date = from_sql_date($_POST['to_date']);
?>
<div class="row">
    <div class="col-xl-12">
        <?php if ($account_id == 9){ ?>
        <form method="post" action="BankAccount">
            <?php } else { ?>
            <form method="post" action="CashInHand">
                <?php } ?>
                <!-- START card-->
                <div class="card card-default">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="col-form-label">Duration</label>
                            <input type="text" name="duration" id="duration" class="form-control" maxlength="200"
                                   value="<?= $from_date . ' - ' . $to_date; ?>" tabindex="1" disabled/>
                        </div>
                        <div class="form-group">
                            <?php if ($account_id == 9) { ?>
                                <label class="col-form-label">Bank Balance</label>
                            <?php } else { ?>
                                <label class="col-form-label">Cash on Hand</label>
                            <?php } ?>
                            <input type="text" name="account_amount2" id="account_amount2" class="form-control"
                                   maxlength="200"
                                   value="<?= $account_amount; ?>" tabindex="2" disabled/>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Description</label>
                            <input type="text" name="pay_for" id="pay_for" class="form-control" maxlength="200"
                                   value="" tabindex="3"/>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Reference No.</label>
                            <input type="text" name="reference_no" id="reference_no" class="form-control"
                                   maxlength="100"
                                   value="" tabindex="4"/>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Transfer Amount</label>
                            <input type="text" name="amount" id="amount2" class="form-control"
                                   onkeyup="check_amount(<?= $account_id; ?>);"
                                   value="" onkeypress="return isNumberDecimalKey(event)" tabindex="5"/>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Transfer Account</label>
                            <select name="account_id" id="account_id" class="form-control"
                                    tabindex="6">
                                <?php if ($account_id == 9) {
                                    echo '<option value="10">Cash on Hand</option>';
                                    echo '<option value="1">Investments</option>';
                                } else {
                                    echo '<option value="9">Bank Account</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="clearfix">
                            <div class="float-right">
                                <button class="btn btn-warning" type="submit" name="save_submit" tabindex="7">Submit
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

