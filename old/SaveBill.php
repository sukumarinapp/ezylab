<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user = $_SESSION["user"];

$billnum = $_REQUEST['bill_num'];
$bill_date = $_REQUEST['bill_date'];
$patient_id = $_REQUEST['patient_id'];
$ref_prefix = $_REQUEST['ref_prefix'];
$reference = $_REQUEST['reference'];
$total = $_REQUEST['amount'];
$net_amount = $_REQUEST['net_amount'];
$cgst = $_REQUEST['cgst'];
$sgst = $_REQUEST['sgst'];
$home_visit = $_REQUEST['home_visit'];
$payment_method = $_REQUEST['payment_method'];
$reference_no = $_REQUEST['reference_no'];
$pay_amount = $_REQUEST['pay_amount'];

$created_date = date("Y-m-d");
$created = date("Y-m-d h:i:sa");
$modified = date("Y-m-d h:i:sa");

$status = '1';
$paying_amount = 0;
$customer_bill_type = 'Debit';
$type = 'Income';
$bill_type = "billing";

if ($payment_method == 'Cash') {
    $payment_status = 1;
    $saving_account = 12;
} else {
    $payment_status = 0;
    $saving_account = 9;
}

$sales = $_REQUEST['sales'];
$sales = stripslashes($sales);
$sales_array = array();
$sales_array = json_decode($sales);
$sales_array_count = count($sales_array);

for ($i = 0; $i < $sales_array_count; $i++) {
    $item_id = $sales_array[$i]->item_id;
    $item_name = $sales_array[$i]->item_name;
    $item_amount = $sales_array[$i]->item_amount;

    $billnum = GetBillNumber();

    $pay_amount = $pay_amount - $paying_amount;
    if ($pay_amount > $item_amount) {
        $paying_amount = $item_amount;
    } else {
        $paying_amount = $pay_amount;
    }

    if ($item_name == 'Consultation') {

        $pay_for = '( ' . $billnum . ' ) Patient Consultation Amount';
        $account_id = '14';
    } else {
        $pay_for = '( ' . $billnum . ' ) Patient Pharmacy Amount';
        $account_id = '15';
    }
    $bill_sql = Insert(
        'macho_billing',
        array(
            'billnum' => $billnum,
            'bill_date' => to_sql_date($bill_date),
            'bill_description' => Filter($item_name),
            'patient_id' => Filter($patient_id),
            'ref_prefix' => Filter($ref_prefix),
            'reference' => Filter($reference),
            'net_amount' => Filter($item_amount),
            'payment_method' => Filter($payment_method),
            'reference_no' => Filter($reference_no),
            'created_by' => Filter($user_id)
        )
    );

    $bill_id = $bill_sql;

    if ($pay_amount > 0) {

        Insert(
            'macho_customer_payments',
            array(
                'customer_id' => $patient_id,
                'bill_id' => $bill_id,
                'bill_type' => $bill_type,
                'type' => $customer_bill_type,
                'payment_method' => $payment_method,
                'reference_no' => $reference_no,
                'amount' => $paying_amount,
                'status' => $status,
                'created' => $created,
                'collected_date' => $created
            )
        );

        Insert(
            'macho_revenue',
            array(
                'account_id' => $account_id,
                'saving_account' => $saving_account,
                'type' => $type,
                'customer_id' => $patient_id,
                'pay_for' => $pay_for,
                'payment_method' => Filter($payment_method),
                'reference_no' => Filter($reference_no),
                'amount' => $paying_amount,
                'entry_date' => to_sql_date($created),
                'modified_date' => to_sql_date($created),
                'bill_id' => Filter($bill_id)
            )
        );

        if ($item_amount == $paying_amount) {

            $update = Update(
                'macho_billing',
                'id',
                $bill_id,
                array(
                    'bill_status' => $status
                )
            );
        }

        $notes = $user_name . '  has Collect Rs.' . $pay_amount . $pay_for;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

    }

    $notes = $user . '  has Create New Patient ' . $pay_for;
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

}

//echo EncodeVariable($bill_id);
echo '1';