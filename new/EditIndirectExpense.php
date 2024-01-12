<?php
include_once 'booster/bridge.php';
$id = Filter($_POST['id']);
$FinanceData = SelectParticularRow('macho_revenue', 'id', $id);
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="IndirectExpense">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">Description</label>
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                        <input type="text" name="pay_for" id="pay_for" class="form-control" maxlength="200"
                               value="<?php echo $FinanceData['pay_for'];?>" tabindex="1"/>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control" tabindex="2">
                            <option value="">Select</option>
                            <option value="Cash" <?php if($FinanceData['payment_method'] == 'Cash') echo 'selected';?> >Cash</option>
                            <option value="Credit Card" <?php if($FinanceData['payment_method'] == 'Credit Card') echo 'selected';?>>Credit Card</option>
                            <option value="Debit Card" <?php if($FinanceData['payment_method'] == 'Debit Card') echo 'selected';?>>Debit Card</option>
                            <option value="Online Payment" <?php if($FinanceData['payment_method'] == 'Online Payment') echo 'selected';?> >Online Payment</option>
                            <option value="Cheque" <?php if($FinanceData['payment_method'] == 'Cheque') echo 'selected';?> >Cheque</option>
                            <option value="Demand Draft" <?php if($FinanceData['payment_method'] == 'Demand Draft') echo 'selected';?> >Demand Draft</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Reference No.</label>
                        <input type="text" name="reference_no" id="reference_no" class="form-control" maxlength="100"
                               value="<?php echo $FinanceData['reference_no'];?>" tabindex="3"/>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Amount</label>
                        <input type="text" name="amount" id="amount" class="form-control"
                               value="<?php echo $FinanceData['amount'];?>" onkeypress="return isNumberDecimalKey(event)" tabindex="4"/>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="clearfix">
                        <div class="float-right">
                            <button class="btn btn-warning" type="submit" name="Update" tabindex="5">Save Changes
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

