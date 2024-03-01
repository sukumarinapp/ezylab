<?php
ini_set('max_execution_time', 3000);
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/booster/vendor/autoload.php';
include 'booster/bridge.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$styleArray = array(
    'font' => array(
        'bold' => true,
        'color' => array('rgb' => '005EFF'),
        'size' => 15,
        'name' => 'Verdana'
    ));
$posArray = array(
    'font' => array(
        'color' => array('rgb' => '00FF00')
    ));
$negArray = array(
    'font' => array(
        'color' => array('rgb' => 'FF0000'),
    ));

$title = $_POST['title'];
$title2 = str_replace("Report", "", $title);
$from_date = $_POST['from_date'];
$todate = $_POST['todate'];
$thead_data = $_POST['thead_data'];
$tbody_data = $_POST['tbody_data'];
$tfoot_data = isset($_POST['tfoot_data']) ? $_POST['tfoot_data'] : array();
$thead_data_count = count($thead_data);
$tbody_data_count = count($tbody_data);
$tfoot_data_count = count($tfoot_data);
$tr_count = 0;

// Set document properties
$objPHPExcel->getProperties()->setCreator("DreamApps")
    ->setLastModifiedBy("DreamApps")
    ->setTitle($title2)
    ->setSubject("Office 2007 XLSX  Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");

if (trim($from_date) !== "0" && trim($todate) !== "0") {
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A3', 'From Date')
        ->setCellValue('E3', 'To Date');
}

$n = 5;
$l = 'A';
$columns = array();
for ($i = 0; $i < $thead_data_count; $i++) {
    $columns[$i] = $l;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($l . $n, $thead_data[$i]);
    $l++;
}
// Add some data
// Miscellaneous glyphs, UTF-8

$objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
$objPHPExcel->getActiveSheet()->getCell('B1')->setValue($title);
$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);

if (trim($from_date) !== "0" && trim($todate) !== "0") {
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B3', date("d-m-Y", strtotime($from_date)))
        ->setCellValue('F3', date("d-m-Y", strtotime($todate)));
}

$k = 7;
$j = 0;

for ($d = 0; $d < $tbody_data_count; $d++) {
    if ($tr_count == 0) {
        $j = 0;
    }

    $c = $columns[$j];

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($c . $k, WordReplace2($tbody_data[$d]));

    $tr_count = $tr_count + 1;
    if ($tr_count == $thead_data_count) {
        $tr_count = 0;
        $k++;
    }
    $j++;
}

if ($tfoot_data_count !== "0") {
    $n = $k;
    $l = 'A';
    $j = 0;
    $k = $k + 1;
    $tr_count = 0;

    for ($i = 0; $i < $thead_data_count; $i++) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($l . $n, '');
        $l++;
    }

    for ($d = 0; $d < $tfoot_data_count; $d++) {
        if ($tr_count == 0) {
            $j = 0;
        }

        $c = $columns[$j];

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($c . $k, WordReplace2($tfoot_data[$d]));

        $tr_count = $tr_count + 1;
        if ($tr_count == $thead_data_count) {
            $tr_count = 0;
            $k++;
        }
        $j++;
    }
}
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($title2 . '-' . $date);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=' . $title2 . '.xls');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
