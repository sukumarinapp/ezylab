<?php
ini_set('max_execution_time', 1200);
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/booster/vendor/autoload.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '005EFF'),
        'size'  => 15,
        'name'  => 'Verdana'
    ));
$posArray = array(
    'font'  => array(
        'color' => array('rgb' => '00FF00')
    ));
$negArray = array(
    'font'  => array(
        'color' => array('rgb' => 'FF0000'),
    ));

include_once 'booster/bridge.php';

$start_date = date("Y-m-d", strtotime($_GET['startdate']));
$end_date = date("Y-m-d", strtotime($_GET['enddate']));

// Set document properties
$objPHPExcel->getProperties()->setCreator("DreamApps")
    ->setLastModifiedBy("DreamApps")
    ->setTitle("Finance Report")
    ->setSubject("Office 2007 XLSX  Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A3', 'From Date')
    ->setCellValue('E3', 'To Date');
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A5', 'SI NO')
    ->setCellValue('B5', 'Date')
    ->setCellValue('C5', 'Description')
    ->setCellValue('D5', 'Payment Method')
    ->setCellValue('E5', 'Reference No.')
    ->setCellValue('F5', 'Income')
    ->setCellValue('G5', 'Expense');

// Miscellaneous glyphs, UTF-8

$objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
$objPHPExcel->getActiveSheet()->getCell('B1')->setValue("Finance Report");
$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B3', date("d-m-Y", strtotime($start_date)))
    ->setCellValue('F3', date("d-m-Y", strtotime($end_date)));

$j = 1;
$k = 7;
$income=0;
$expense=0;
$FinanceData = GetAllRows("SELECT * FROM macho_revenue WHERE entry_date>='$start_date' AND entry_date<='$end_date' ORDER BY id DESC ");
foreach ($FinanceData as $FinanceValues) {

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $k, $j)
        ->setCellValue('B' . $k,  date("d-m-Y", strtotime($FinanceValues['entry_date'])))
        ->setCellValue('C' . $k,  $FinanceValues['pay_for'])
        ->setCellValue('D' . $k,  $FinanceValues['payment_method'])
        ->setCellValue('E' . $k,  $FinanceValues['reference_no']);

    if($FinanceValues['type']=="Income"){
        $income = $income + $FinanceValues['amount'];
        $objPHPExcel->setActiveSheetIndex(0)

            ->setCellValue('F' . $k, $FinanceValues['amount']);
    }
    if($FinanceValues['type']=="Expense") {
        $expense = $expense + $FinanceValues['amount'];
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G' . $k, $FinanceValues['amount']);
    }
    $k++;
    $j++;
}
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('E' . ($k+1), 'Total')
    ->setCellValue('F' .( $k+1), $income)
    ->setCellValue('G' .( $k+1), $expense)
    ->setCellValue('F' . ($k+2), 'Profit Amount');
if (($income - $expense)>=0) {
    $objPHPExcel->getActiveSheet()->getStyle('G' . ($k + 2))->applyFromArray($posArray);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('G' . ($k + 2), ($income - $expense));
}else {
    $objPHPExcel->getActiveSheet()->getStyle('G' . ($k + 2))->applyFromArray($negArray);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('G' . ($k + 2), ($income - $expense));
}
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Finance Report-' . $date);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Finance Report.xls"');
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
