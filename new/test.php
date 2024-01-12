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
$BillData = SelectParticularRow('patient_entry', 'id', $id);
$patient_id = $BillData['patient_id'];

$PatientInfo = SelectParticularRow('macho_patient', 'id', $patient_id);


$TestQuery = "SELECT * FROM macho_bill_items WHERE bill_id='$id' ORDER BY id DESC";
$TestResult = GetAllRows($TestQuery);

$TestEntryQuery = "SELECT * FROM test_entry WHERE entry_id='$id' ORDER BY id DESC";
$TestEntryResult = GetAllRows($TestEntryQuery);

$TermsQuery = "SELECT * FROM macho_terms ORDER BY id";
$TermsResult = GetAllRows($TermsQuery);

ob_start();

$content = ob_get_clean();
$mpdf = new \Mpdf\Mpdf();
$mpdf->defaultheaderline = 0;
$mpdf->SetHeader('|TEST RECEIPT|');

$mpdf->debug = true;
$mpdf->AliasNbPages();
$mpdf->SetAutoPageBreak(true, 15);
$mpdf->AddPage();
$mpdf->SetFillColor(239, 239, 239);

$mpdf->Cell(180, 30, '', 0, 1, 'L', false);

$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(180, 4, $OrgInfo['name'], 'LRT', 1, 'C', false);
$mpdf->Cell(180, 4, WordReplace($OrgInfo['address']), 'LR', 1, 'C', false);
$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(180, 4, $OrgInfo['state'], 'LR', 1, 'C', false);
$mpdf->Cell(180, 4, 'Email: ' . $OrgInfo['email'], 'LR', 1, 'C', false);
$mpdf->Cell(180, 4, 'Contact: ' . $OrgInfo['land_line'] . ',' . $OrgInfo['mobile'], 'LRB', 1, 'C', false);

$mpdf->SetFont('Arial', 'B', 8);
$mpdf->Cell(90, 4, '', 'LRT', 0, 'L', false);
$mpdf->Cell(90, 4, '', 'LRT', 1, 'L', false);
$mpdf->Cell(45, 4, 'Patient ID:', 'L', 0, 'L', false);
$mpdf->Cell(45, 4, $PatientInfo['P_code'], 'R', 0, 'L', false);
$mpdf->Cell(45, 4, 'Entry ID: ', 'L', 0, 'L', false);
$mpdf->Cell(45, 4, $BillData['bill_no'], 'R', 1, 'L', false);
$mpdf->Cell(45, 4, 'Name: ', 'L', 0, 'L', false);
$mpdf->Cell(45, 4, $PatientInfo['prefix'] . $PatientInfo['P_name'], 'R', 0, 'L', false);
$mpdf->Cell(45, 4, 'Sample Collected On', 'L', 0, 'L', false);
$mpdf->Cell(45, 4, from_sql_date($BillData['entry_date']), 'R', 1, 'L', false);
$mpdf->Cell(45, 4, 'Age/Sex: ', 'L', 0, 'L', false);
$mpdf->Cell(45, 4, $PatientInfo['age'] . $PatientInfo['age_type'] . '/' . $PatientInfo['gender'], 'R', 0, 'L', false);
$mpdf->Cell(45, 4, 'Report Released On', 'L', 0, 'L', false);
$mpdf->Cell(45, 4, $BillData['modified'], 'R', 1, 'L', false);
$mpdf->Cell(45, 4, 'Ref.By: ', 'LB', 0, 'L', false);
$mpdf->Cell(45, 4, $PatientInfo['ref_prefix'] . $PatientInfo['reference'], 'RB', 0, 'L', false);
$mpdf->Cell(45, 4, 'Page No', 'LB', 0, 'L', false);
$mpdf->Cell(45, 4, '', 'RB', 1, 'L', false);


$mpdf->SetFont('Arial', '', 8);
$mpdf->Cell(10, 8, 'SI. NO', 1, 0, 'C', false);
$mpdf->Cell(80, 8, 'Test Name ', 1, 0, 'C', false);
$mpdf->Cell(30, 8, 'Result', 1, 0, 'C', false);
$mpdf->Cell(20, 8, 'Unit ', 1, 0, 'C', false);
$mpdf->Cell(20, 8, 'Lower Limit ', 1, 0, 'C', false);
$mpdf->Cell(20, 8, 'Upper Limit', 1, 1, 'C', false);
$mpdf->SetFont('Arial', 'B', 8);

$i = 1;
foreach ($TestResult as $TestData) {
    $TestID = $TestData['item_id'];

    $TestTypeData = SelectParticularRow('macho_test_type', 'id', $TestID);
    $type_test = $TestTypeData['type_test'];

    $TestEntryQuery = "SELECT * FROM test_entry WHERE entry_id ='$id' AND test_id='$TestID'";
    $TestEntryResult = mysqli_query($GLOBALS['conn'], $TestEntryQuery) or die(mysqli_error($GLOBALS['conn']));
    $TestEntryData = mysqli_fetch_assoc($TestEntryResult);

    if ($type_test == 'Normal') {
        $mpdf->Cell(10, 8, $i, 'LR', 0, 'C', false);
        $mpdf->Cell(80, 8, $TestTypeData['test_name'] . '-' . $TestTypeData['test_code'], 'LR', 0, 'C', false);
        $mpdf->Cell(30, 8, $TestEntryData['test_result'], 'LR', 0, 'C', false);
        $mpdf->Cell(20, 8, $TestTypeData['units'], 'LR', 0, 'C', false);
        $mpdf->Cell(20, 8, $TestTypeData['lower_limit'], 'LR', 0, 'C', false);
        $mpdf->Cell(20, 8, $TestTypeData['upper_limit'], 'LR', 1, 'C', false);
    } elseif ($type_test == 'Sub Heading') {
        $mpdf->Cell(10, 8, $i, 'LR', 0, 'C', false);
        $mpdf->Cell(80, 8, $TestTypeData['test_name'] . '-' . $TestTypeData['test_code'], 'LR', 0, 'C', false);
        $mpdf->Cell(90, 8, $TestEntryData['sub_head'], 'LR', 1, 'C', false);
    } elseif ($type_test == 'Paragraph') {
        $mpdf->Cell(10, 8, $i, 'LR', 0, 'C', false);
        $mpdf->Cell(80, 8, $TestTypeData['test_name'] . '-' . $TestTypeData['test_code'], 'LR', 0, 'C', false);
        $mpdf->Cell(90, 8, $TestEntryData['paragraph'], 'LR', 1, 'C', false);
    } elseif ($type_test == 'Table') {
        $mpdf->Cell(10, 8, $i, 'LR', 0, 'C', false);
        $mpdf->Cell(80, 8, $TestTypeData['test_name'] . '-' . $TestTypeData['test_code'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['head_1'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['head_2'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['head_3'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['head_4'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['head_5'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['head_6'], 'LR', 1, 'C', false);

        $mpdf->Cell(10, 8, '', 'LR', 0, 'C', false);
        $mpdf->Cell(80, 8, '', 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['result_1'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['result_2'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['result_3'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['result_4'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['result_5'], 'LR', 0, 'C', false);
        $mpdf->Cell(15, 8, $TestEntryData['result_6'], 'LR', 1, 'C', false);
    } elseif ($type_test == 'Date') {
        $mpdf->Cell(10, 8, $i, 'LR', 0, 'C', false);
        $mpdf->Cell(80, 8, $TestTypeData['test_name'] . '-' . $TestTypeData['test_code'], 'LR', 0, 'C', false);
        $mpdf->Cell(90, 8, from_sql_date($TestEntryData['date']), 'LR', 1, 'C', false);
    } elseif ($type_test == 'Time') {
        $mpdf->Cell(10, 8, $i, 'LR', 0, 'C', false);
        $mpdf->Cell(80, 8, $TestTypeData['test_name'] . '-' . $TestTypeData['test_code'], 'LR', 0, 'C', false);
        $mpdf->Cell(90, 8, from_sql_date($TestEntryData['time']), 'LR', 1, 'C', false);
    } else {
        $mpdf->Cell(10, 8, $i, 'LR', 0, 'C', false);
        $mpdf->Cell(80, 8, $TestTypeData['test_name'] . '-' . $TestTypeData['test_code'], 'LR', 0, 'C', false);
        $mpdf->Cell(90, 8, '', 'LR', 1, 'C', false);

        $TestDocQuery = "SELECT * FROM test_documents WHERE entry_id='$id' AND test_id='$TestID' ORDER BY document_id  DESC";
        $TestDocResult = GetAllRows($TestDocQuery);
        foreach ($TestDocResult as $TestDocData) {
            $mpdf->Image($TestDocData['file_url'], 80, 80, 80);
        }
    }

    $mpdf->Cell(180, 1, '', 'LRB', 1, 'C', false);

    $i++;
}


$mpdf->SetFont('Arial', 'B', 6);
$mpdf->Cell(180, 4, "For " . $OrgInfo['name'], 'LR', 1, 'R', false);
$mpdf->SetFont('Arial', '', 6);
$mpdf->Cell(90, 10, "", 'L', 0, 'L', false);
$mpdf->Cell(90, 10, "", 'R', 1, 'L', false);
$mpdf->Cell(90, 4, "", 'LB', 0, 'LB', false);
$mpdf->Cell(90, 4, "Authorised Signatory", 'RB', 1, 'R', false);


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

$content = $mpdf->Output('', 'S');
ob_start();
header("Content-Type:application/pdf");
header('Content-Disposition: inline; Bill Receipt.pdf');
echo $content;