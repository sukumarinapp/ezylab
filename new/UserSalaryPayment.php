<?php
include_once 'booster/bridge.php';
$USERID = Filter($_POST['id']);
$UserData = UserInfo($USERID);
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="StaffRevenue">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">Name</label>
                        <input type="hidden" name="id" id="id" value="<?php echo $USERID; ?>">
                        <input type="text" name="name" id="name" class="form-control" maxlength="100"
                               value="<?php echo $UserData['prefix'] . $UserData['name']; ?>" disabled tabindex="1"/>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Salary Amount</label>
                        <input type="text" name="salary_amount" id="salary_amount" class="form-control" maxlength="100"
                               value="<?php echo $UserData['salary_amount']; ?>" disabled tabindex="2"/>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Description</label>
                        <input type="text" name="pay_for" id="pay_for" class="form-control" maxlength="100"
                               value="" tabindex="3"/>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control" tabindex="4">
                            <option value="">Select</option>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Debit Card">Debit Card</option>
                            <option value="Online Payment">Online Payment</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Demand Draft">Demand Draft</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Reference No.</label>
                        <input type="text" name="reference_no" id="reference_no" class="form-control" maxlength="100"
                               value="" tabindex="5"/>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Amount</label>
                        <input type="text" name="amount" id="amount" class="form-control"
                               value="" onkeypress="return isNumberDecimalKey(event)" tabindex="6"/>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Revenue Date</label>
                        <input type="text" data-date-format="dd-mm-yyyy" name="revenue_date" id="revenue_date"
                               class="form-control"
                               value="<?php echo date("d-m-Y", strtotime(date('Y-m-d'))); ?>" tabindex="7"/>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="clearfix">
                        <div class="float-right">
                            <button class="btn btn-warning" type="submit" name="submit" tabindex="8">Pay Now
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
        $('#revenue_date').datepicker({
            autoclose: true
        })
    })
</script>