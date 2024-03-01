<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, $page);
StaffRevenue();

if (isset($_POST['submit'])) {

    $type = 'Expense';
    $account_id = 8;
    $entry_date = date("Y-m-d");
    $paid_status = 1;

    if ($_POST['payment_method'] == 'Cash') {
        $saving_account = 12;
    } else {
        $saving_account = 9;
    }

    $insertRevenue = Insert('macho_revenue', array(
        'account_id' => $account_id,
        'saving_account' => $saving_account,
        'type' => $type,
        'pay_for' => Filter($_POST['pay_for']),
        'payment_method' => Filter($_POST['payment_method']),
        'reference_no' => Filter($_POST['reference_no']),
        'amount' => Filter($_POST['amount']),
        'entry_date' => $entry_date,
        'modified_date' => $entry_date
    ));

    if (is_int($insertRevenue)) {

        $description = UserName($_POST['id']) . ' Salary amount Rs. ' . $_POST['amount'] . ' on ' . to_sql_date($_POST['revenue_date']);

        Insert('macho_staff_revenue', array(
            'user_id' => Filter($_POST['id']),
            'revenue_date' => to_sql_date($_POST['revenue_date']),
            'description' => $description,
            'amount' => Filter($_POST['amount']),
            'paid_status' => $paid_status,
            'paid_date' => $entry_date,
            'created' => $entry_date
        ));

        $notes = UserName($_POST['id']) . ' Salary Amount Rs. ' . $_POST['amount'] . ' given  by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

?>
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">Staff Revenue</div>
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-hover w-100" id="datatable1">
                    <thead>
                    <tr>
                        <th width="10px">
                            <strong>#</strong>
                        </th>
                        <th>
                            <strong>NAME</strong>
                        </th>
                        <th>
                            <strong>ROLE</strong>
                        </th>
                        <th class="text-center">
                            <strong>STATUS</strong>
                        </th>
                        <th>
                            <strong>EARNING AMOUNT</strong>
                        </th>
                        <th>
                            <strong>RECEIVED AMOUNT</strong>
                        </th>
                        <th>
                            <strong>BALANCE AMOUNT</strong>
                        </th>
                        <th class="text-center">
                            <strong>ACTION</strong>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 0;
                    $UserQuery = 'SELECT a.*,concat(a.prefix," ",a.name) as name,b.role FROM macho_users a,macho_role b WHERE b.id=a.role_id ORDER BY a.id DESC ';
                    $UserResult = GetAllRows($UserQuery);
                    $UserCounts = count($UserResult);
                    if ($UserCounts > 0) {
                        foreach ($UserResult as $UserData) {
                            ?>
                            <tr>
                                <td><?php echo ++$no; ?></td>
                                <td><a href="#" title="User View"
                                       onclick="location.href='UserView?uId=<?php echo EncodeVariable($UserData['id']); ?>';"><?php echo $UserData['name']; ?></a>
                                </td>
                                <td><?php echo $UserData['role']; ?></td>
                                <td class="text-center"><?php echo(($UserData['status']) == 0 ? '<span class="badge badge-danger">In-Active</span>' : '<span class="badge badge-success">Active</span>'); ?> </td>
                                <td><b>Rs. <?= ConvertMoneyFormat(GetUserEarningRevenue($UserData['id'])); ?></b></td>
                                <td><b>Rs. <?= ConvertMoneyFormat(GetUserReceivedRevenue($UserData['id'])); ?></b></td>
                                <td class="red">
                                    <b>Rs. <?= ConvertMoneyFormat(GetUserPendingRevenue($UserData['id'])); ?></b></td>
                                <td class="text-center">
                                    <?php if ($PageAccessible['is_read'] == 1) { ?>
                                        <button class="btn btn-sm btn-green" type="button" title="View Details"
                                                onclick="location.href='UserRevenueView?uId=<?php echo EncodeVariable($UserData['id']); ?>';">
                                            <em class="fa fa-search"></em>
                                        </button>
                                    <?php }
                                    if ($PageAccessible['is_write'] == 1) {
                                        if ($UserData['salary_mode'] == 1) { ?>
                                            <button class="btn btn-sm btn-info" type="button" title="Pay Now"
                                                    onclick="location.href='UserRevenuePayment?uId=<?php echo EncodeVariable($UserData['id']); ?>';">
                                                <i class="fa fa-money"></i>
                                            </button>
                                        <?php } else { ?>
                                            <button class="btn btn-sm btn-info" type="button" title="Pay Now"
                                                    onclick="pay_now(<?= $UserData['id']; ?>);">
                                                <i class="fa fa-money"></i>
                                            </button>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php
                        }
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php'; ?>
</div>
<div class="modal fade" id="payment_tab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Staff Salary </h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="payment_body">
            </div>
        </div>
    </div>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.min.js"></script>
<!-- BOOTSTRAP-->
<script src="<?php echo VENDOR; ?>popper.js/dist/umd/popper.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap/dist/js/bootstrap.js"></script>
<!-- STORAGE API-->
<script src="<?php echo VENDOR; ?>js-storage/js.storage.js"></script>
<!-- JQUERY EASING-->
<script src="<?php echo VENDOR; ?>jquery.easing/jquery.easing.js"></script>
<!-- ANIMO-->
<script src="<?php echo VENDOR; ?>animo/animo.js"></script>
<!-- SCREENFULL-->
<script src="<?php echo VENDOR; ?>screenfull/dist/screenfull.js"></script>
<!-- LOCALIZE-->
<script src="<?php echo VENDOR; ?>jquery-localize/dist/jquery.localize.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<!-- Datatables-->
<script src="<?php echo VENDOR; ?>datatables.net/js/jquery.dataTables.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo VENDOR; ?>datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
<script>
    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function pay_now(id) {
        $.ajax({
            type: "POST",
            url: "UserSalaryPayment.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#payment_body').html(response);
                $('#payment_tab').modal('show');
            }
        });
    }

    window.onload = function () {

        if (document.getElementById('insert_success')) {
            swal({
                    title: "Success",
                    text: "Amount has been has Paid Successfully!",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                },
                function () {
                    location.href = "StaffRevenue";
                });
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }
    }
</script>

</body>
</html>