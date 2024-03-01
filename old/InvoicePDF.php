<?php
error_reporting();
session_start();
include_once 'booster/bridge.php';
include_once 'booster/vendor/autoload.php';
$user_id = $_SESSION["user_id"];
$access_token = $_SESSION["access_token"];
ValidateAccessToken($user_id, $access_token);
$id = DecodeVariable($_GET['bID']);

$OrgInfo = OrgInfo('1');
$BillData = SelectParticularRow('macho_billing', 'id', $id);
$patient_id = $BillData['patient_id'];

$PatientInfo = SelectParticularRow('macho_patient', 'id', $patient_id);

$TermsQuery = "SELECT * FROM macho_terms ORDER BY id";
$TermsResult = GetAllRows($TermsQuery);

ob_start();

$content = ob_get_clean();
$mpdf = new \Mpdf\Mpdf();
$mpdf->defaultheaderline = 0;
$mpdf->SetHeader('|BILL RECEIPT|');

$mpdf->debug = true;
$mpdf->AliasNbPages();
$mpdf->SetAutoPageBreak(true, 15);
$mpdf->AddPage();
$mpdf->SetFillColor(239, 239, 239);

$mpdf->Cell(180, 30, '', 0, 1, 'L', false);

$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(140, 4, $OrgInfo['name'], 'LRT', 0, 'C', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(40, 4, 'Bill No.', 'LRT', 1, 'L', false);
$mpdf->Cell(140, 4, WordReplace($OrgInfo['address1']), 'LR', 0, 'C', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(40, 4, $BillData['billnum'], 'LR', 1, 'L', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(140, 4, $OrgInfo['state'], 'LR', 0, 'C', false);
$mpdf->Cell(40, 4, '', 'LRB', 1, 'L', false);
$mpdf->Cell(140, 4, 'Email: ' . $OrgInfo['email'], 'LR', 0, 'C', false);
$mpdf->Cell(40, 4, 'Dated', 'LR', 1, 'L', false);
$mpdf->Cell(140, 4, 'Contact: ' . $OrgInfo['land_line'] . ',' . $OrgInfo['mobile'], 'LRB', 0, 'C', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(40, 4, from_sql_date($BillData['bill_date']), 'LRB', 1, 'L', false);

$mpdf->SetFont('Arial', 'B', 6);
$mpdf->Cell(140, 4, 'Name & Address Of Buyer/Recipient ( Billed to)', 'LRT', 0, 'L', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(40, 4, 'Payment Method', 'LRT', 1, 'L', false);
$mpdf->Cell(140, 4, $PatientInfo['prefix'] . $PatientInfo['P_name'], 'LR', 0, 'L', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(40, 4, $BillData['payment_method'], 'LRB', 1, 'L', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(140, 4, 'Patient ID: ' . $PatientInfo['P_code'], 'LR', 0, 'L', false);
$mpdf->Cell(40, 4, 'Reference No.', 'LR', 1, 'L', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(140, 4, 'Mobile: ' . $PatientInfo['mobile'], 'LRB', 0, 'L', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(40, 4, $BillData['reference_no'], 'LRB', 1, 'L', false);


$mpdf->SetFont('Arial', '', 6);
$mpdf->Cell(20, 8, 'SI. NO', 1, 0, 'C', false);
$mpdf->Cell(100, 8, 'Description ', 1, 0, 'C', false);
$mpdf->Cell(60, 8, 'Amount', 1, 1, 'C', false);
$mpdf->SetFont('Arial', 'B', 6);

$i = 1;

$mpdf->Cell(20, 8, $i, 'LR', 0, 'C', false);
$mpdf->Cell(100, 8, $BillData['bill_description'] . ' Charges', 'LR', 0, 'C', false);
$mpdf->Cell(60, 8, $BillData['net_amount'], 'LR', 1, 'C', false);

$mpdf->Cell(180, 1, '', 'LRB', 1, 'L', false);


//$mpdf->Cell(40, 8, 'CGST', 1, 0, 'C', false);
//$mpdf->Cell(80, 8, $BillData['cgst'], 1, 0, 'C', false);
// $mpdf->Cell(120, 8, '', 1, 0, 'C', false);
// $mpdf->Cell(20, 8, 'Total', 1, 0, 'C', false);
// $mpdf->Cell(40, 8, $BillData['total_amount'], 1, 1, 'C', false);

//$mpdf->Cell(40, 8, 'SGST', 1, 0, 'C', false);
//$mpdf->Cell(80, 8, $BillData['sgst'], 1, 0, 'C', false);
// $mpdf->Cell(120, 8, '', 1, 0, 'C', false);
// $mpdf->Cell(20, 8, 'Home Visit', 1, 0, 'C', false);
// $mpdf->Cell(40, 8, $BillData['home_visit'], 1, 1, 'C', false);

$mpdf->Cell(120, 8, 'Net Amount', 1, 0, 'R', false);
$mpdf->Cell(60, 8, $BillData['net_amount'], 1, 1, 'C', false);

$mpdf->SetFont('Arial', '', 6);
$mpdf->Cell(180, 4, 'Amount Chargeable (in words)', 'LR', 1, 'L', false);
$mpdf->SetFont('Arial', 'B', 8);
$mpdf->MultiCell(180, 4, 'INR ' . WordReplace(Convert_Amount_In_Words($BillData['net_amount'])), 'LR', 'L', 0, false);
$mpdf->Cell(180, 4, '', 'LRB', 1, 'L', false);

$mpdf->SetFont('Arial', 'B', 6);
$mpdf->Cell(180, 4, "For " . $OrgInfo['name'], 'LR', 1, 'R', false);
$mpdf->SetFont('Arial', '', 6);
$mpdf->Cell(90, 10, "", 'L', 0, 'L', false);
$mpdf->Cell(90, 10, "", 'R', 1, 'L', false);
$mpdf->Cell(90, 4, "", 'LB', 0, 'LB', false);
$mpdf->Cell(90, 4, "Authorized Signatory", 'RB', 1, 'R', false);


$mpdf->SetFont('Arial', 'BU', 8);
$mpdf->Cell(180, 4, "Terms & Conditions:", 'LR', 1, 'L', false);
$mpdf->SetFont('Arial', '', 8);
$no = 0;
foreach ($TermsResult as $TermsData) {
    $mpdf->Cell(180, 4, ++$no . ". " . $TermsData['description'], 'LR', 1, 'L', false);
}
$mpdf->Cell(180, 2, "", 'LRB', 1, 'L', false);

$mpdf->defaultfooterline = 0;
$mpdf->SetFooter('|This is a Computer Generated Receipt|');

$pdfname = $PatientInfo['P_code'] . ' - ' . $BillData['bill_description'] . ' Receipt.pdf';
$content = $mpdf->Output($pdfname, 'I');
ob_start();
header("Content-Type:application/pdf");
header('Content-Disposition: inline; Bill Receipt.pdf');
echo $content;