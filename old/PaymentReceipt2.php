<?php
error_reporting(0);
session_start();
include_once 'booster/bridge.php';
include_once 'booster/vendor/autoload.php';
$user_id = $_SESSION["user_id"];
$user_access_token = $_SESSION["access_token"];
ValidateAccessToken($user_id, $user_access_token);

$id = DecodeVariable($_GET['pID']);

$BillData = SelectParticularRow('macho_customer_payments', 'id', $id);
$customer_id = $BillData['customer_id'];

if ($BillData['status'] == '1') {
    $status = 'COLLECTED';
} elseif ($BillData['status'] == '2') {
    $status = 'CANCEL';
} else {
    $status = 'PENDING';
}

$CompanyInfo = OrgInfo('1');

$ClientInfo = SelectParticularRow('macho_patient', 'id', $customer_id);


$no = 0;
ob_start();

$content = ob_get_clean();
$mpdf = new \Mpdf\Mpdf();
$mpdf->defaultheaderline = 0;
$mpdf->SetHeader('|PAYMENT RECEIPT|');
$mpdf->debug = true;
$mpdf->AliasNbPages();
$mpdf->SetAutoPageBreak(true, 15);
$mpdf->AddPage();
$mpdf->SetFillColor(239, 239, 239);
$mpdf->SetFont('Arial', 'B', 8);

$mpdf->Cell(180, 4, $CompanyInfo['name'], 'LRT', 1, 'C', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(180, 4, WordReplace($CompanyInfo['address']), 'LR', 1, 'C', false);
$mpdf->Cell(180, 4, 'GSTIN: ' . $CompanyInfo['gstin'], 'LR', 1, 'C', false);
$mpdf->Cell(180, 4, 'Email: ' . $CompanyInfo['email'], 'LR', 1, 'C', false);
$mpdf->Cell(180, 4, 'Contact: ' . $CompanyInfo['mobile'], 'LRB', 1, 'C', false);

$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(140, 4, $ClientInfo['P_name'], 'LRT', 0, 'L', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(40, 4, '', 'LRT', 1, 'L', false);
$mpdf->Cell(140, 4, WordReplace($ClientInfo['address']), 'LR', 0, 'L', false);
$mpdf->Cell(40, 4, '', 'LR', 1, 'L', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(140, 4, 'Patient code: ' . $ClientInfo['P_code'], 'LR', 0, 'L', false);
$mpdf->Cell(40, 4, 'Dated', 'LR', 1, 'L', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(140, 4, 'Email: ' . $ClientInfo['email'], 'LR', 0, 'L', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(40, 4, from_sql_date($BillData['created']), 'LR', 1, 'L', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(140, 4, 'Contact: ' . $ClientInfo['mobile'], 'LR', 0, 'L', false);
$mpdf->Cell(40, 4, '', 'LR', 1, 'L', false);
$mpdf->Cell(140, 4, '', 'LRB', 0, 'L', false);
$mpdf->Cell(40, 4, '', 'LRB', 1, 'L', false);

$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(10, 8, 'SI. NO', 1, 0, 'C', false);
$mpdf->Cell(85, 8, 'Particulars', 1, 0, 'C', false);
$mpdf->Cell(85, 8, '', 1, 1, 'C', false);

$mpdf->Cell(10, 8, ++$no . '.', 'LR', 0, 'C', false);
$mpdf->Cell(85, 8, 'Account Type', 'LR', 0, 'C', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(85, 8, $BillData['type'], 'R', 1, 'C', false);

if ($BillData['bill_id'] != 0) {

    if ($BillData['bill_type'] == 'billing') {
        $description = PatientBillNo($BillData['bill_id']);
    } else {
        $description = CustomerBillNo($BillData['bill_id']);
    }

    $mpdf->SetFont('Arial', '', 8);
    $mpdf->Cell(10, 8, ++$no . '.', 'LR', 0, 'C', false);
    $mpdf->Cell(85, 8, 'Bill Number', 'LR', 0, 'C', false);
    $mpdf->SetFont('Arial', 'B', 8);
    $mpdf->Cell(85, 8, $description, 'R', 1, 'C', false);
}

$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(10, 8, ++$no . '.', 'LR', 0, 'C', false);
$mpdf->Cell(85, 8, 'Payment Method', 'LR', 0, 'C', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(85, 8, $BillData['payment_method'], 'R', 1, 'C', false);

$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(10, 8, ++$no . '.', 'LR', 0, 'C', false);
$mpdf->Cell(85, 8, 'Reference Number', 'LR', 0, 'C', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(85, 8, $BillData['reference_no'], 'R', 1, 'C', false);

$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(10, 8, ++$no . '.', 'LR', 0, 'C', false);
$mpdf->Cell(85, 8, 'Amount', 'LR', 0, 'C', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(85, 8, $BillData['amount'], 'R', 1, 'C', false);

$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(10, 8, ++$no . '.', 'LR', 0, 'C', false);
$mpdf->Cell(85, 8, 'Status', 'LR', 0, 'C', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(85, 8, $status, 'R', 1, 'C', false);

$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(10, 8, ++$no . '.', 'LR', 0, 'C', false);
$mpdf->Cell(85, 8, 'Collected Date', 'LR', 0, 'C', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(85, 8, from_sql_date($BillData['collected_date']), 'R', 1, 'C', false);

$mpdf->Cell(10, 8, '', 'LRB', 0, 'C', false);
$mpdf->Cell(85, 8, '', 'LRB', 0, 'C', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(85, 8, '', 'RB', 1, 'C', false);

$mpdf->SetFont('Arial', '', 6);
$mpdf->Cell(180, 6, 'Amount in words', 'LR', 1, 'L', false);
$mpdf->SetFont('Arial', 'B', 6);
$mpdf->Cell(180, 6, 'INR ' . WordReplace(Convert_Amount_In_Words($BillData['amount'])), 'LRB', 1, 'L', false);
$mpdf->Cell(180, 6, '', 'LR', 1, 'L', false);

$mpdf->Cell(90, 4, "Patient Signature", 'L', 0, 'L', false);
$mpdf->SetFont('Arial', 'B', 6);
$mpdf->Cell(90, 4, "FOR " . $CompanyInfo['name'], 'R', 1, 'R', false);
$mpdf->SetFont('Arial', '', 6);
$mpdf->Cell(90, 10, "", 'L', 0, 'L', false);
$mpdf->Cell(90, 10, "", 'R', 1, 'L', false);
$mpdf->Cell(90, 4, "", 'LB', 0, 'LB', false);
$mpdf->Cell(90, 4, "Authorized Signatory", 'RB', 1, 'R', false);

$mpdf->defaultfooterline = 0;
$mpdf->SetFooter('|This is a Computer Generated Receipt|');

$pdfname = $ClientInfo['P_code'] . ' - PaymentReceipt.pdf';
$content = $mpdf->Output($pdfname, 'I');
ob_start();
header("Content-Type:application/pdf");
header('Content-Disposition: inline; Payment Receipt.pdf');
echo $content;