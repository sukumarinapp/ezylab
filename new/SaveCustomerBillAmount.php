<?php
session_start();
include_once 'booster/bridge.php';

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user"];
$user_role = $_SESSION["role_name"];
$user_role_id = $_SESSION["role_id"];

$created = date("Y-m-d");
$date_time = date("Y-m-d H:i:s");

$bill_data = $_REQUEST['bill_data'];
$bill_data = stripslashes($bill_data);
$bill_data_array = array();
$bill_data_array = json_decode($bill_data);
$customer_id = $_REQUEST['customer_id'];
$pay_amount = $_REQUEST['pay_amount'];
//$discount_amount = $_REQUEST['discount_amount'];
$payment_method = $_REQUEST['payment_method'];
$reference_no = $_REQUEST['reference_no'];

$status = '1';
$paying_amount = 0;
$add_discount_amount = 0;
$customer_bill_type = 'Debit';
$type = 'Income';
if ($payment_method == 'Cash') {
    $payment_status = 1;
    $saving_account = 12;
} else {
    $payment_status = 0;
    $saving_account = 9;
}

$things = array();
for ($i = 0; $i < count($bill_data_array); $i++) {

    $things = explode(",", $bill_data_array[$i]);

    $bill_id = $things[0];
    $bill_type = $things[1];
    $bill_date = $things[2];
    $bill_no = $things[3];
    $bill_amount = $things[4];
    $paid_amount = $things[5];
    $balance_amount = $things[6];

    if ($bill_type == "patient_entry") {
        $pay_for = '( ' . $bill_no . ' ) Patient Test Amount';
        $account_id = '13';
    } else {
        $BillData = SelectParticularRow('macho_billing', 'id', $bill_id);
        $item_name = $BillData['bill_description'];
        if ($item_name == 'Consultation') {
            $pay_for = '( ' . $bill_no . ' ) Patient Consultation Amount';
            $account_id = '14';
        } else {
            $pay_for = '( ' . $bill_no . ' ) Patient Pharmacy Amount';
            $account_id = '15';
        }
    }

    $pay_amount = $pay_amount - $paying_amount;
    if ($pay_amount > $balance_amount) {
        $paying_amount = $balance_amount;
    } else {
        $paying_amount = $pay_amount;
    }

    if ($pay_amount > 0) {

        $current_balance_amount = $paid_amount + $paying_amount;
        $new_balance_amount = $bill_amount - $current_balance_amount;

        $farmer_bill_sql = Insert(
            'macho_customer_payments',
            array(
                'customer_id' => $customer_id,
                'bill_id' => $bill_id,
                'bill_type' => $bill_type,
                'type' => $customer_bill_type,
                'payment_method' => $payment_method,
                'reference_no' => $reference_no,
                'amount' => $paying_amount,
                'created' => $created,
                'status' => $payment_status,
                'collected_date' => $created
            )
        );

        Insert(
            'macho_revenue',
            array(
                'account_id' => $account_id,
                'saving_account' => $saving_account,
                'type' => $type,
                'customer_id' => $customer_id,
                'pay_for' => $pay_for,
                'payment_method' => Filter($payment_method),
                'reference_no' => Filter($reference_no),
                'amount' => $paying_amount,
                'entry_date' => to_sql_date($created),
                'modified_date' => to_sql_date($created),
                'bill_id' => Filter($bill_id)
            )
        );

        if ($bill_amount == $current_balance_amount) {
            if ($bill_type == "patient_entry") {
                Update(
                    'patient_entry',
                    'id',
                    $bill_id,
                    array(
                        'bill_status' => $status
                    )
                );
            } else {
                Update(
                    'macho_billing',
                    'id',
                    $bill_id,
                    array(
                        'bill_status' => $status
                    )
                );
            }
        }

        $notes = $user_name . '  has Collect Rs.' . $paying_amount . $pay_for;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $user_role_id, $receive_role_id, $receive_id);

    }

}


echo EncodeVariable($customer_id);