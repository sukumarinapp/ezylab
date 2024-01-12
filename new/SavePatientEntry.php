<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user_name = $_SESSION["user"];

$billnum = $_REQUEST['bill_num'];
$bill_date = $_REQUEST['bill_date'];
$patient_id = $_REQUEST['patient_id'];
$ref_prefix = $_REQUEST['ref_prefix'];
$reference = $_REQUEST['reference'];
$entry_time = $_REQUEST['entry_time'];
$total = $_REQUEST['amount'];
$net_amount = $_REQUEST['net_amount'];
$cgst = $_REQUEST['cgst'];
$sgst = $_REQUEST['sgst'];
$home_visit = $_REQUEST['home_visit'];
$payment_method = $_REQUEST['payment_method'];
$reference_no = $_REQUEST['reference_no'];
$pay_amount = $_REQUEST['pay_amount'];

$created_date = date("Y-m-d");
$created = date("Y-m-d H:i:s");
$modified = date("Y-m-d H:i:s");

$status = '1';
$paying_amount = 0;
$account_id = '13';
$bill_type = 'patient_entry';
$customer_bill_type = 'Debit';
$type = 'Income';
$pay_for = '(Bill No.: ' . $billnum . ' ) Patient Test Amount';

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


$bill_sql = Insert(
    'patient_entry',
    array(
        'bill_no' => Filter($billnum),
        'entry_date' => to_sql_date($bill_date),
        'entry_time' => Filter($entry_time),
        'patient_id' => Filter($patient_id),
        'ref_prefix' => Filter($ref_prefix),
        'reference' => Filter($reference),
        'total_amount' => Filter($total),
        'net_amount' => Filter($net_amount),
        'cgst' => Filter($cgst),
        'sgst' => Filter($sgst),
        'home_visit' => Filter($home_visit),
        'payment_method' => Filter($payment_method),
        'reference_no' => Filter($reference_no),
        'created_by' => Filter($user_id),
        'created' => Filter($created),
        'modified' => Filter($modified)
    )
);

$bill_id = $bill_sql;

for ($i = 0; $i < $sales_array_count; $i++) {
    $item_id = $sales_array[$i]->item_id;
    $item_type = $sales_array[$i]->item_type;
    $item_category = $sales_array[$i]->item_category;
    $item_name = $sales_array[$i]->item_name;
    $item_quantity = $sales_array[$i]->item_quantity;
    $item_uom = $sales_array[$i]->item_uom;
    $item_rate = $sales_array[$i]->item_rate;
    $item_gst = $sales_array[$i]->item_gst;
    $item_amount = $sales_array[$i]->item_amount;

    if ($item_type == 'group') {
        $ProfileQuery = "SELECT * FROM macho_profile_tests WHERE profile_id='$item_category'";
        $ProfileResult = GetAllRows($ProfileQuery);
        foreach ($ProfileResult as $ProfileData) {
            $item_id = $ProfileData['item_id'];

            $TestTypeData = SelectParticularRow('macho_test_type', 'id', $item_id);

            $sql = "DELETE  FROM  macho_bill_items WHERE bill_id = '$bill_id' AND item_id='$item_id'";
            $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));

            Insert(
                'macho_bill_items',
                array(
                    'bill_id' => Filter($bill_id),
                    'item_id' => Filter($item_id),
                    'item_type' => Filter($item_type),
                    'item_name' => Filter($TestTypeData['test_name']),
                    'test_category' => Filter($item_category),
                    'unit_price' => Filter($TestTypeData['price']),
                    'quantity' => '1',
                    'uom' => 'LS',
                    'amount' => Filter($TestTypeData['price']),
                )
            );
        }
    } elseif ($item_type == 'single') {
        $TestTypeQuery = "SELECT * FROM macho_test_type WHERE test_category='$item_category'";
        $TestTypeResult = GetAllRows($TestTypeQuery);
        foreach ($TestTypeResult as $TestTypeData) {

            $item_id = $TestTypeData['id'];

            $sql = "DELETE  FROM  macho_bill_items WHERE bill_id = '$bill_id' AND item_id='$item_id'";
            $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));

            Insert(
                'macho_bill_items',
                array(
                    'bill_id' => Filter($bill_id),
                    'item_id' => Filter($item_id),
                    'item_type' => Filter($item_type),
                    'item_name' => Filter($TestTypeData['test_name']),
                    'test_category' => Filter($item_category),
                    'unit_price' => Filter($TestTypeData['price']),
                    'quantity' => '1',
                    'uom' => 'LS',
                    'amount' => Filter($TestTypeData['price']),
                )
            );
        }
    } else {

        $sql = "select * FROM  macho_bill_items WHERE bill_id = '$bill_id' AND item_id='$item_id'";
        $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
        if(mysqli_num_rows($result)==0){
            Insert(
                'macho_bill_items',
                array(
                    'bill_id' => Filter($bill_id),
                    'item_id' => Filter($item_id),
                    'item_type' => Filter($item_type),
                    'item_name' => Filter($item_name),
                    'test_category' => Filter($item_category),
                    'unit_price' => Filter($item_rate),
                    'gst' => Filter($item_gst),
                    'quantity' => Filter($item_quantity),
                    'uom' => Filter($item_uom),
                    'amount' => Filter($item_amount),
                )
            );
        }
    }

}

$notes = $user_name . '  has Create New Bill (' . $billnum . ')';

$receive_id = '1';
$receive_role_id = GetRoleOfUser($receive_id);
InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);


if ($pay_amount > 0) {

    $customer_bill_sql = Insert(
        'macho_customer_payments',
        array(
            'customer_id' => $patient_id,
            'bill_id' => $bill_id,
            'bill_type' => $bill_type,
            'type' => $customer_bill_type,
            'payment_method' => $payment_method,
            'reference_no' => $reference_no,
            'amount' => $pay_amount,
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
            'amount' => $pay_amount,
            'entry_date' => to_sql_date($created),
            'modified_date' => to_sql_date($created),
            'bill_id' => Filter($bill_id)
        )
    );

    if ($net_amount == $pay_amount) {

        $update = Update(
            'patient_entry',
            'id',
            $bill_id,
            array(
                'bill_status' => $status
            )
        );
    }

    $notes = $user_name . '  has Collect Rs.' . $paying_amount . ' Bill Amount. Bill No :' . $billnum . '. Patient Name :' . CustomerName($patient_id) . '';
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);
}

//echo EncodeVariable($bill_id);
?>

<div class="row">
    <div class="col-md-12">
        <!-- START panel -->
        <div class="panel panel-default">
            <!-- panel body -->
            <div class="panel-body">
                <form class="form-horizontal form-bordered" action="#">
                    <p class="pb10">Your New Entry <code><?php echo $billnum; ?></code> Created Successfully!...
                    </p>

                    <div class="panel-footer">
                        <div class="form-group no-border" align="center">
                            <button type="button" class="btn btn-primary" title="Print (A4)"
                                onclick="location.href='BillPdf?bID=<?php echo EncodeVariable($bill_id); ?>';">
                                <i class="fa fa-file-pdf-o"></i> View Receipt (A4)
                            </button>
                            <button type="button" class="btn btn-success" title="Print (POS)"
                                onclick="PrintBill(<?php echo $bill_id; ?>);"><i class="fa fa-pinterest"></i> View
                                Receipt (POS)
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- panel body -->
        </div>
        <!--/ END form panel -->
    </div>
</div>