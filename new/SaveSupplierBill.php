<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user"];
$user_role = $_SESSION["role"];
$user_role_id = $_SESSION["role_id"];

$farmer_id = $_REQUEST['farmer_id'];
$farmer_name = $_REQUEST['farmer_name'];
$mobile = $_REQUEST['mobile'];
$billnum = $_REQUEST['bill_num'];
$bill_date = $_REQUEST['bill_date'];
$total = $_REQUEST['amount'];
$advance_amount = $_REQUEST['advance_amount'];
$labour_amount = $_REQUEST['labour_amount'];
$other_amount = $_REQUEST['other_amount'];
$percentage_amount = $_REQUEST['percentage_amount'];
$expense_amount = $_REQUEST['expense_amount'];
$net_amount = $_REQUEST['net_amount'];
$payment_method = $_REQUEST['payment_method'];
$reference_no = $_REQUEST['reference_no'];
$pay_amount = $_REQUEST['pay_amount'];

$status = '1';
$type = 'Expense';
$farmer_bill_type = 'Debit';
$account_id = 4;
$paying_amount = 0;
$created = date("Y-m-d H:i:s");
$created_date = date("Y-m-d");
if ($payment_method == 'Cash') {
    $saving_account = 12;
    $payment_status = 1;
} else {
    $saving_account = 9;
    //$payment_status = 0;
    $payment_status = 1;
}

$sql2 = "SELECT * FROM macho_farmers WHERE id='$farmer_id' ";
$result2 = mysqli_query($GLOBALS['conn'], $sql2);
$count2 = mysqli_num_rows($result2);
$data2 = mysqli_fetch_assoc($result2);
if ($count2 > 0) {
    $farmer_id = $data2['id'];
//    Update('macho_farmers', 'id', $farmer_id, array(
//        'F_name' => $farmer_name,
//        'modified' => $created
//    ));
} else {
    $farmer_data = Insert('macho_farmers', array(
        'F_code' => GetFarmerCode(),
        'F_name' => Filter($farmer_name),
        'mobile' => Filter($mobile),
        'created' => $created,
        'modified' => $created
    ));
    $farmer_id = $farmer_data;
}

$sales = $_REQUEST['sales'];
$sales = stripslashes($sales);
$sales_array = array();
$sales_array = json_decode($sales);
$sales_array_count = count($sales_array);

$billnum = GetFarmerBillNo();
$bill_sql = Insert('macho_farmer_bill', array(
    'bill_no' => $billnum,
    'bill_date' => to_sql_date($bill_date),
    'farmer_id' => Filter($farmer_id),
    'total_amount' => Filter($total),
    'advance_amount' => Filter($advance_amount),
    'rent_amount' => Filter($other_amount),
    'labour_amount' => Filter($labour_amount),
    'percentage_amount' => Filter($percentage_amount),
    'expense_amount' => Filter($expense_amount),
    'net_amount' => Filter($net_amount),
    'payment_method' => Filter($payment_method),
    'reference_no' => Filter($reference_no),
    'created_by' => Filter($user_id),
    'created' => Filter($created)
));

$bill_id = $bill_sql;

for ($i = 0; $i < $sales_array_count; $i++) {
    $item_id = $sales_array[$i]->item_id;
    $item_quantity = $sales_array[$i]->item_quantity;
    $item_uom = $sales_array[$i]->item_uom;
    $item_pack_capacity = $sales_array[$i]->item_pack_capacity;
    $item_pack_unit = $sales_array[$i]->item_pack_unit;
    $item_rate = $sales_array[$i]->item_rate;
    $item_gst = $sales_array[$i]->item_gst;
    $item_amount = $sales_array[$i]->item_amount;

    $ProductData = SelectParticularRow('macho_master_products', 'id', $item_id);

    Insert('macho_farmer_bill_items', array(
        'bill_id' => Filter($bill_id),
        'product_name' => Filter($ProductData['product_name']),
        'item_id' => Filter($item_id),
        'rate' => Filter($item_rate),
        'gst' => Filter($item_gst),
        'qty' => Filter($item_quantity),
        'uom' => Filter($item_uom),
        'pack_capacity' => Filter($item_pack_capacity),
        'pack_unit' => Filter($item_pack_unit),
        'amount' => Filter($item_amount),
    ));

    $item_qty = $item_quantity * $item_pack_capacity;

    $insert_product = Insert('macho_products', array(
        'parent_id' => Filter($item_id),
        'product_code' => Filter($ProductData['product_code']),
        'product_name' => Filter($ProductData['product_name']),
        'product_lang_name' => Filter($ProductData['product_lang_name']),
        'rate' => Filter($item_rate),
        'gst' => Filter($item_gst),
        'quantity' => Filter($item_quantity),
        'uom' => Filter($item_uom),
        'pack_capacity' => Filter($item_pack_capacity),
        'pack_unit' => Filter($item_pack_unit),
        'item_qty' => Filter($item_qty),
        'farmer_id' => Filter($farmer_id),
        'created' => Filter($created),
        'modified' => Filter($created),
    ));

    if (is_int($insert_product)) {
        Insert('macho_product_update_entry', array(
            'ref_id' => Filter($bill_id),
            'product_id' => Filter($insert_product),
            'product_qty' => Filter($item_quantity),
            'created_by' => Filter($user_id),
            'created' => Filter($created)
        ));
    }
}

$notes = $user_name . '  has Create New Supplier Bill (' . $billnum . ')';
$receive_id = '1';
$receive_role_id = GetRoleOfUser($receive_id);
InsertNotification($notes, $user_id, $user_role_id, $receive_role_id, $receive_id);

$BillQuery = "SELECT * FROM macho_farmer_bill WHERE farmer_id= '$farmer_id' AND payment_status ='0' ORDER BY id DESC ";
$BillResult = GetAllRows($BillQuery);
$BillCounts = count($BillResult);
if ($BillCounts > 0) {
    foreach ($BillResult as $BillData) {

        $BillId = $BillData['id'];
        $BillDate = $BillData['bill_date'];
        $BillNo = $BillData['bill_no'];
        $BillAmount = $BillData['net_amount'];
        $PaidAmount = GetFarmerPaidAmount($farmer_id, $BillId);
        $BalanceAmount = $BillAmount - $PaidAmount;

    $pay_for = '(Bill No.: ' . $BillNo . ' ) Purchase Amount';

    $pay_amount = $pay_amount - $paying_amount;
    if ($pay_amount > $BalanceAmount) {
        $paying_amount = $BalanceAmount;
    } else {
        $paying_amount = $pay_amount;
    }

    if ($pay_amount > 0) {

        $current_balance_amount = $PaidAmount + $paying_amount;
        $new_balance_amount = $BillAmount - $current_balance_amount;

        $farmer_bill_sql = Insert('macho_farmer_payments', array(
            'farmer_id' => $farmer_id,
            'bill_id' => $BillId,
            'type' => $farmer_bill_type,
            'payment_method' => $payment_method,
            'reference_no' => $reference_no,
            'amount' => $paying_amount,
            'created' => $created_date,
            'status' => $payment_status,
            'collected_date' => $created_date
        ));


        if ($BillAmount == $current_balance_amount) {

            $update = Update('macho_farmer_bill', 'id', $BillId, array(
                'payment_status' => $status
            ));
        }

        $notes = $user_name . '  has Paid Rs.' . $paying_amount . ' Bill Amount. Bill No :' . $BillNo . '. Supplier Name :' . FarmerName($farmer_id) . '';
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $user_role_id, $receive_role_id, $receive_id);
    }
}}

echo EncodeVariable($bill_id);