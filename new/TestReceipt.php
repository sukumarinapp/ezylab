<?php
error_reporting(0);
session_start();
include_once 'booster/bridge.php';
include_once 'booster/vendor/autoload.php';

$user_id = $_SESSION["user_id"];
$access_token = $_SESSION["access_token"];
ValidateAccessToken($user_id, $access_token);

$id = DecodeVariable($_GET['bID']);
$header = $_GET['header'];

$OrgInfo = OrgInfo('1');
$BillData = SelectParticularRow('patient_entry', 'id', $id);
$patient_id = $BillData['patient_id'];
if ($BillData['ref_prefix'] == 'Dr.') {
    $reference = DoctorName($BillData['reference']);
} else {
    $reference = $BillData['reference'];
}

$PatientInfo = SelectParticularRow('macho_patient', 'id', $patient_id);
//$birthData = GetAge($PatientInfo['dob']);
//$age = $birthData['age'];
$age = $PatientInfo['age'];
$age_type = $birthData['age_type'];


$TermsQuery = "SELECT * FROM macho_terms ORDER BY id";
$TermsResult = GetAllRows($TermsQuery);

$i = 1;

$html = '
<html>
<head>
<title>LABORATORY REPORT</title>
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link rel="shortcut icon" href="' . LOGO . '"/>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"><s/script>
</head>
<body>
<table border="0" width="100%" align="center" class="table">
<tr align="center">
<td colspan="2" style="text-align:center">';
?>
<?php
if($header == 1){
    $html = $html . '<img src="logo/header1.jpg" />';
}
?>
<?php
if($header == 0){
    $html = $html . '<h2 style="color:black;font-weight: bold;text-align:left">' . $OrgInfo['name'] . '</h2>
    ' . WordReplace($OrgInfo['address']) . '<br>';
    if(trim($OrgInfo['email'])!="") $html = $html . 'Email:' . $OrgInfo['email'] . '<br>';
    if(trim($OrgInfo['mobile'])!="") $html = $html . 'Contact:' . $OrgInfo['mobile'] . '<br>';
}
?>
<?php
$html = $html . '</td>
</tr>
</table>

<table border="1" class="table table-hover table-bordered" width="100%" align="center">
<tr>
<td align="left" width="15%">Patient ID</td>
<td align="left" width="35%">' . $PatientInfo['P_code'] . '</td>
<td align="left" width="25%">Sample Id '.$BillData['bill_no'].'</td><td width="25%">';
?>
<?php
$html =$html .'<img src="barcode/barcode.php?text='.$BillData['bill_no'].'&codetype=codabar&orientation=horizontal&size=20&print='.$BillData['bill_no'].'" /></td>';
?>
<?php

$html =$html .'</tr>';
?>
<?php
$created = $BillData['created'];
$created = explode(" ",$created);
$html =$html .'<tr>
<td align="left">Patient Name</td>
<td align="left">' . $PatientInfo['prefix'] . $PatientInfo['P_name'] . '</td>
<td align="left">Sample Collected On</td>
<td align="left">' . date("d-m-Y",strtotime($created[0])) .'&nbsp;'. date("h:i:A",strtotime($created[1])) . '</td>
</tr>';
?>
<?php
$modified = $BillData['modified'];
$modified = explode(" ",$modified);
$html =$html .'<tr>
<td align="left">Age/Sex</td>
<td align="left">' . $age . $age_type . ' ' . $PatientInfo['gender'] . '</td>
<td align="left">Report Released On</td>
<td align="left">' . date("d-m-Y",strtotime($modified[0])) .'&nbsp;'. date("h:i:A",strtotime($modified[1])) . '</td>
</tr>
<tr>
<td align="left">Ref.By</td>
<td align="left">' . $reference . '</td>
<td align="left">'. ucwords($PatientInfo['id_card_type']) .'</td>
<td align="left">'. $PatientInfo['id_number'] .'</td>
</tr>
</table>
</div>
</div>

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12">
<table border="1" class="table table-striped table-bordered" width="100%">
<thead>
<tr><th style="text-align:center" colspan="7"><h4>Laboratory Report</h4></th></tr>
<tr>
<th style="background-color:#F5F5F5;font-size:14px;" colspan="2">Sample Type</th>
<th style="background-color:#F5F5F5;font-size:14px;" colspan="2">INVESTIGATION</th>
<th style="background-color:#F5F5F5;text-align:left;font-size:14px" colspan="2">RESULT</th>
<th style="background-color:#F5F5F5;text-align:left;font-size:14px">UNITS</th>
<th style="background-color:#F5F5F5;text-align:left;font-size:14px" colspan="2">NORMAL RANGE</th>
</tr>
</thead>

<tbody>';
$TestEntryQuery = "SELECT DISTINCT dept_id FROM test_entry WHERE entry_id='$id' ORDER BY dept_id";
$TestEntryResult = GetAllRows($TestEntryQuery);
foreach ($TestEntryResult as $TestEntryData) {
    $dept_id = $TestEntryData['dept_id'];
    /*if(trim(DepartmentName($dept_id)) != ""){
        $html .= '<tr>
        <td colspan="7" style="text-align:center;font-size: bold;height: 35px"><strong>' . DepartmentName($dept_id) . '</strong></td>
        </tr>';
    }*/

    $TestEntryQuery2 = "SELECT DISTINCT test_category FROM test_entry WHERE entry_id='$id' AND dept_id='$dept_id' ORDER BY test_id";
    $TestEntryResult2 = GetAllRows($TestEntryQuery2);
    foreach ($TestEntryResult2 as $TestEntryData2) {
        $test_category = $TestEntryData2['test_category'];

        if(trim(TestCategoryName($test_category))!=""){
            $html .= '<tr>
            <td colspan="7" style="text-align: left;font-size: bold;"><strong>'. TestCategoryName($test_category) . '</strong></td>
            </tr>';
        }

        $TestEntryQuery3 = "SELECT * FROM test_entry WHERE entry_id='$id' AND dept_id='$dept_id' AND test_category='$test_category'  ORDER BY id";
        $TestEntryResult3 = GetAllRows($TestEntryQuery3);
        
        $sub_heading = "";
        foreach ($TestEntryResult3 as $TestEntryData3) {

            $TestID = $TestEntryData3['test_id'];

            $TestTypeData = SelectParticularRow('macho_test_type', 'id', $TestID);
            $type_test = $TestTypeData['type_test'];

            if($sub_heading != $TestEntryData3['sub_heading'] && trim($TestEntryData3['sub_heading']) != ""){
                $html .= '<tr>
                <td style="font-weight:bold;text-align:left;height: 25px;" colspan="7">' . $TestEntryData3['sub_heading'] . '</td>
                </tr>';
            }

            if ($type_test == 'Normal') {

                $html .= '<tr>
                <td style="text-align:left;height: 25px" colspan="2">' . str_replace("^","",$TestTypeData['sample_type']) . '</td>;
                <td style="text-align:left;height: 25px" colspan="2">' . str_replace("^","",$TestTypeData['test_name']) . '</td>';

                  $html .= '<td colspan="2" style="';
       
                   if(($TestEntryData3['test_result'] < $TestTypeData['lower_limit'])  || ($TestEntryData3['test_result'] > $TestTypeData['upper_limit'])){
                     $html .= 'background-color:#F0FFF0;';
                  }
                 $html .=  'text-align:left;height: 25px;" >' . $TestEntryData3['test_result'] . '</td>

                <td style="text-align:left;height: 25px;" >' . $TestTypeData['units'] . '</td>

               <td colspan="2" style="text-align:left;height: 25px;" >' . $TestTypeData['lower_limit'] .' - ' . $TestTypeData['upper_limit'] . '</td>
               </tr>';

               if($TestTypeData['method']!=""){
                $html .= '<tr>
                <th style="font-size:12px;" colspan="2">Method</th>
                <td style="text-align:left;height: 25px" colspan="9">' . str_replace("^","",$TestTypeData['method']) . '</td></tr>';
               }

               if($TestTypeData['show_critical_info'] == 1 && $TestTypeData['critical_info'] != ""){
                $html .= '<tr><td colspan="5"></td><td width="20%" colspan="2" style="font-weight:bold">Reference Value</td></tr>';
                $html .= '<tr><td colspan="5"></td><td width="20%" colspan="2">'.nl2br(str_replace("^","",$TestTypeData['critical_info'])).'</td></tr>';
            }
            if($TestTypeData['show_interpretation'] == 1 && $TestTypeData['interpretation'] != ""){
                $html .= '<tr><td colspan="7" style="font-weight:bold">Interpretation</td></tr>';
                $html .= '<tr><td colspan="7">'.str_replace("^","",$TestTypeData['interpretation']).'</td></tr>';
            }


        } elseif ($type_test == 'Sub Heading') {


            $html .= '<tr>
            <td style="text-align:left;height: 25px" colspan="2">' . str_replace("^","",$TestTypeData['sample_type']) . '</td>
            <td style="text-align:left;height: 25px" colspan="2">' . str_replace("^","",$TestTypeData['test_name']) . '</td>
            <td style="text-align:left;height: 25px;" colspan="2">' . $TestEntryData3['sub_head'] . '</td>
            <td style="text-align:left;height: 25px;" >' . $TestTypeData['units'] . '</td>
            <td colspan="2" style="text-align:left;height: 25px;" >' . $TestTypeData['lower_limit'] .' - ' . $TestTypeData['upper_limit'] . ' </td>
            </tr>';

            if($TestTypeData['show_critical_info'] == 1 && $TestTypeData['critical_info'] != ""){
                $html .= '<tr><td colspan="5"></td><td colspan="2" style="font-weight:bold">Reference Value</td></tr>';
                $html .= '<tr><td colspan="5"></td><td colspan="2">'.nl2br(str_replace("^","",$TestTypeData['critical_info'])).'</td></tr>';
            }
            if($TestTypeData['show_interpretation'] == 1 && $TestTypeData['interpretation'] != ""){
                $html .= '<tr><td colspan="7" style="font-weight:bold">Interpretation</td></tr>';
                $html .= '<tr><td colspan="7">'.str_replace("^","",$TestTypeData['interpretation']).'</td></tr>';
            }

            $sub_heading = $TestEntryData3['sub_heading'];

        } elseif ($type_test == 'Table') {

            $html .= '<tr>
            <td style="text-align:left;height: 25px;" colspan="2">' . str_replace("^","",$TestTypeData['sample_type']) . '</td>
            <td style="text-align:left;height: 25px;" colspan="2">' . str_replace("^","",$TestTypeData['test_name']) . '</td>
            <td style="text-align:left;height: 25px;" colspan="2">' . str_replace("^","",$TestEntryData3['test_result']) . '</td>
            <td style="text-align:left;height: 25px;" >&nbsp;' . $TestTypeData['units'] . '&nbsp;</td>
            <td style="text-align:left;height: 25px;" colspan="2">&nbsp;&nbsp;</td>
            </tr>';

            if($TestTypeData['show_critical_info'] == 1 && $TestTypeData['critical_info'] != ""){
                $html .= '<tr><td colspan="5"></td><td colspan="2" style="font-weight:bold">Reference Value</td></tr>';
                $html .= '<tr><td colspan="5"></td><td colspan="2">'.nl2br(str_replace("^","",$TestTypeData['critical_info'])).'</td></tr>';
            }
            if($TestTypeData['show_interpretation'] == 1 && $TestTypeData['interpretation'] != ""){
                $html .= '<tr><td colspan="7" style="font-weight:bold">Interpretation</td></tr>';
                $html .= '<tr><td colspan="7">'.str_replace("^","",$TestTypeData['interpretation']).'</td></tr>';
            }
        } elseif ($type_test == 'Date') {

            $html .= '<tr>
            <td style="text-align:left;height: 25px;" colspan="2">&nbsp;' . str_replace("^","",$TestTypeData['sample_type']) . '&nbsp;</td>
            <td style="text-align:left;height: 25px;" colspan="2">&nbsp;' . str_replace("^","",$TestTypeData['test_name']) . '&nbsp;</td>
            <td style="text-align:left;height: 25px;" colspan="5">&nbsp;' . from_sql_date($TestEntryData3['date']) . '&nbsp;</td>
            </tr>';

            if($TestTypeData['show_critical_info'] == 1 && $TestTypeData['critical_info'] != ""){
                $html .= '<tr><td colspan="5"></td><td colspan="2" style="font-weight:bold">Reference Value</td></tr>';
                $html .= '<tr><td colspan="5"></td><td colspan="2">'.nl2br(str_replace("^","",$TestTypeData['critical_info'])).'</td></tr>';
            }
            if($TestTypeData['show_interpretation'] == 1 && $TestTypeData['interpretation'] != ""){
                $html .= '<tr><td colspan="7" style="font-weight:bold">Interpretation</td></tr>';
                $html .= '<tr><td colspan="7">'.str_replace("^","",$TestTypeData['interpretation']).'</td></tr>';
            }
        } elseif ($type_test == 'Time') {

            $html .= '<tr>
            <td style="text-align:left;height: 25px;" colspan="2">&nbsp;' . $TestTypeData['sample_type'] . '&nbsp;</td>
            <td style="text-align:left;height: 25px;" colspan="2">&nbsp;' . $TestTypeData['test_name'] . '&nbsp;</td>
            <td style="text-align:left;height: 25px;" colspan="5">&nbsp;' . $TestEntryData3['time'] . '&nbsp;</td>
            </tr>';
            if($TestTypeData['show_critical_info'] == 1 && $TestTypeData['critical_info'] != ""){
                $html .= '<tr><td colspan="5"></td><td colspan="2" style="font-weight:bold">Reference Value</td></tr>';
                $html .= '<tr><td colspan="5"></td><td colspan="2">'.nl2br(str_replace("^","",$TestTypeData['critical_info'])).'</td></tr>';
            }
            if($TestTypeData['show_interpretation'] == 1 && $TestTypeData['interpretation'] != ""){
                $html .= '<tr><td colspan="7" style="font-weight:bold">Interpretation</td></tr>';
                $html .= '<tr><td colspan="7">'.str_replace("^","",$TestTypeData['interpretation']).'</td></tr>';
            }
        } elseif ($type_test == 'Image') {

            $html .= '<tr>
            <td style="text-align:left;height: 25px;" colspan="7">&nbsp;' . $TestTypeData['sample_type'] . '&nbsp;</td>
            <td style="text-align:left;height: 25px;" colspan="7">&nbsp;' . $TestTypeData['test_name'] . '&nbsp;</td>
            </tr>';

            $TestDocQuery = "SELECT * FROM test_documents WHERE entry_id='$id' AND test_id='$TestID' ORDER BY document_id  DESC";
            $TestDocResult = GetAllRows($TestDocQuery);
            foreach ($TestDocResult as $TestDocData) {

                $html .= '<tr>
                <td colspan="7" style=""><img src="' . $TestDocData['file_url'] . '"  width="250px" height="100px"/></td>
                </tr>';
            }

        }
        $sub_heading = $TestEntryData3['sub_heading'];

    }
}
}
$html .= '</tbody>
</table>
<table border="0" class="table" width="100%" align="center">
<tr>
<td colspan="7" style="text-align: center;">&nbsp;&nbsp;</td>
</tr>
<tr>
<td colspan="7" border="0" style="text-align: right;"><strong>&nbsp;Authorized Signatory&nbsp;</strong></td>
</tr>
<tr>
<td colspan="7" style="text-align: center;">End Of Report</td>
</tr>
<tr>
<td colspan="7" style="text-align: right;"><strong>&nbsp;&nbsp;</strong></td>
</tr>
<tr border="0">
<td colspan="7" border="0"><strong>&nbsp;&nbsp;</strong></td>
</tr>
<tr>
<td colspan="7" border="0"><strong>&nbsp;&nbsp;</strong></td>
</tr>
<tr>
<td colspan="7" border="0"><strong>&nbsp;&nbsp;</strong></td>
</tr>
</table>


<table class="table table-striped table-bordered" width="100%" align="center">
<tr>
<td colspan="7" border="0" style="border:none;text-align: center;">Wish you a Speedy Recovery, Thank You !</td>
</tr>
<tr>
<td colspan="7" border="0" style="border:none;text-align: center;">Home Collection Available</td>
</tr>
</table>

</body>
</html>';

$content = ob_get_clean();
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$pdfname = $PatientInfo['P_code'] . '-'.$PatientInfo['P_name'].'.pdf';
$content = $mpdf->Output($pdfname, 'I');
ob_start();
header("Content-Type:application/pdf");
header('Content-Disposition: inline; Report.pdf');
echo $content;