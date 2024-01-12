<?php
ini_set('max_execution_time', 1200);
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");
include_once 'booster/bridge.php';

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/booster/vendor/autoload.php';

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
// Set document properties
$objPHPExcel->getProperties()->setCreator("DreamApps")
    ->setLastModifiedBy("DreamApps")
    ->setTitle("Products List")
    ->setSubject("Office 2007 XLSX  Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");


// Add some data
// Miscellaneous glyphs, UTF-8

$objPHPExcel->getActiveSheet()->mergeCells('C1:H1');
$objPHPExcel->getActiveSheet()->getCell('C1')->setValue('Products List');
$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A3', 'SI NO')
    ->setCellValue('B3', 'Product Name')
    ->setCellValue('C3', 'Product Code')
    ->setCellValue('D3', 'Product Category')
    ->setCellValue('E3', 'Supplier Name')
    ->setCellValue('F3', 'HSN / SAC')
    ->setCellValue('G3', 'GST %')
    ->setCellValue('H3', 'Product Qty')
    ->setCellValue('I3', 'UOM')
    ->setCellValue('J3', 'Purchase Price(Tax Inclusive)')
    ->setCellValue('K3', 'Purchase Price(Tax Exclusive)')
    ->setCellValue('L3', 'Sales Price(Tax Inclusive)')
    ->setCellValue('M3', 'Sales Price(Tax Exclusive)')
    ->setCellValue('N3', 'Product MRP')
    ->setCellValue('O3', 'Mfg. Date')
    ->setCellValue('P3', 'Exp. Date')
    ->setCellValue('Q3', 'Product Description')
    ->setCellValue('R3', 'Product Location')
    ->setCellValue('S3', 'Barcode Type');

$j = 1;
$k = 5;
$ProductQuery = "SELECT a.*,b.category_name,b.supplier_id FROM macho_products a,macho_product_category b WHERE a.status='1' AND b.id=a.product_category ORDER BY a.product_name ASC, a.product_qty DESC";
$ProductResult = GetAllRows($ProductQuery);
$ProductCounts = count($ProductResult);
if ($ProductCounts > 0) {
    foreach ($ProductResult as $ProductData) {

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $k, $j)
            ->setCellValue('B' . $k, $ProductData['product_name'])
            ->setCellValue('C' . $k, $ProductData['product_code'])
            ->setCellValue('D' . $k, $ProductData['category_name'])
            ->setCellValue('E' . $k, SupplierName($ProductData['supplier_id']))
            ->setCellValue('F' . $k, $ProductData['hsn_sac'])
            ->setCellValue('G' . $k, TaxPercentage($ProductData['tax_account']))
            ->setCellValue('H' . $k, $ProductData['product_qty'])
            ->setCellValue('I' . $k, $ProductData['product_uom'])
            ->setCellValue('J' . $k, $ProductData['purchase_rate'])
            ->setCellValue('K' . $k, $ProductData['purchase_net_amount'])
            ->setCellValue('L' . $k, $ProductData['sales_rate'])
            ->setCellValue('M' . $k, $ProductData['sales_net_amount'])
            ->setCellValue('N' . $k, $ProductData['product_mrp'])
            ->setCellValue('O' . $k, from_sql_date($ProductData['mfg_date']))
            ->setCellValue('P' . $k, from_sql_date($ProductData['exp_date']))
            ->setCellValue('Q' . $k, $ProductData['product_description'])
            ->setCellValue('R' . $k, $ProductData['product_location'])
            ->setCellValue('S' . $k, $ProductData['barcode_type']);
        $k++;
        $j++;
    }
}

$objPHPExcel->getActiveSheet()->setTitle('Products List-' . $date);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Products List.xls"');
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
