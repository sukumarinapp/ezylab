<?php
session_start();
include_once 'booster/bridge.php';
IsAjaxRequest();

$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user = $_SESSION["user"];

$staff_id = Filter($_POST['user_id']);
$payment_method = Filter($_POST['payment_method']);
$reference_no = Filter($_POST['reference_no']);
$pay_amount = Filter($_POST['pay_amount']);
$save_data = $_POST['save_data'];
$entry_date = date("Y-m-d");
$type = 'Expense';
$account_id = 8;
$pay_for = UserName($staff_id) . ' Share Amount';
if ($payment_method == 'Cash') {
    $saving_account = 12;
} else {
    $saving_account = 9;
}

$insertRevenue = Insert('macho_revenue', array(
    'account_id' => $account_id,
    'saving_account' => $saving_account,
    'type' => $type,
    'pay_for' => $pay_for,
    'payment_method' => $payment_method,
    'reference_no' => $reference_no,
    'amount' => $pay_amount,
    'entry_date' => $entry_date,
    'modified_date' => $entry_date
));

if (is_int($insertRevenue)) {

    $save_data = stripslashes($save_data);
    $save_data_array = array();
    $save_data_array = json_decode($save_data);
    $things = array();
    for ($i = 0; $i < count($save_data_array); $i++) {
        $things = explode(",", $save_data_array[$i]);
        $revenue_id = $things[0];
        $revenue_amount = $things[1];
        $paid_status = 1;

        Update('macho_staff_revenue', 'id', $revenue_id, array(
            'paid_status' => $paid_status,
            'paid_date' => $entry_date
        ));
    }

    $notes = UserName($staff_id) . ' Share Amount Rs. ' . $pay_amount . ' given  by ' . $user;
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

    echo '1';
}