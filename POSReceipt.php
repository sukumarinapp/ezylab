<?php
include_once 'booster/bridge.php';
require_once(dirname(__FILE__) . "/escpos/autoload.php");
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\escpos\PrintConnectors\WindowsPrintConnector;

$connector = new WindowsPrintConnector('POS58');
$printer = new Printer($connector);
date_default_timezone_set('Asia/Kolkata');

/* A wrapper to do organise item names & prices into columns */

class items
{
    private $name;
    //private $rate;
    //private $qty;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this->name = $name;
        //        $this->rate = $rate;
//        $this->qty = $qty;
        $this->price = $price;
        $this->dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 20;
        //        $secondleftCols = 8;
//        $thirdleftCols = 8;
        if ($this->dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this->name, $leftCols);
        //        $secondleft = str_pad($this->rate, $secondleftCols);
//        $thirdleft = str_pad($this->qty, $thirdleftCols);

        $sign = ($this->dollarSign ? 'Rs. ' : '');
        $right = str_pad($sign . $this->price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}

class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this->name = $name;
        $this->price = $price;
        $this->dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 20;
        if ($this->dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this->name, $leftCols);

        $sign = ($this->dollarSign ? 'Rs. ' : '');
        $right = str_pad($sign . $this->price, $rightCols, ' ', STR_PAD_RIGHT);
        return "$left$right\n";
    }
}

$print_data = $_REQUEST['print_data'];
$print_data = stripslashes($print_data);
$print_data_array = array();
$print_data_array = json_decode($print_data);
$things = array();
$items = array();
$i = 2;
/* Date is kept the same for testing */
$date = date('l jS \of F Y h:i A');
//$date = "Monday 6th of April 2015 02:56:25 PM";

$things = $print_data_array[0];
$patient = $things[0]->patient;
$billnum = $things[0]->billnum;
$bill_date = $things[0]->bill_date;
$net_amount = $things[0]->net_amount;
$bill_description = $things[0]->bill_description;
$amount = $things[0]->amount;

$total = new item('Total', $net_amount, true);


$title = new items("Description", "Amount");
$value = new items($bill_description, $amount);

/* Start the printer */
//$logo = EscposImage::load("logo/1.jpg", false);
$printer = new Printer($connector);
/* Print top logo */
$printer->setJustification(Printer::JUSTIFY_CENTER);
//$printer -> graphics($logo);

/* Name of shop */
$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer->text("LIMS\n");
//$printer->setUnderline(true);
$printer->selectPrintMode();
$printer->text("Nagercoil\n");
$printer->text("Tel: 04652-123456\n");
$printer->text("-------------------------------\n");
$printer->feed();

/* Title of receipt */
$printer->setEmphasis(true);
$printer->setEmphasis(false);
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->setEmphasis(true);
$printer->text("Patient:" . $patient . "\n");
$printer->text("Bill#:" . $billnum . "             Date:" . date("d/m/Y", strtotime($bill_date)) . "\n");
$printer->setJustification(Printer::JUSTIFY_RIGHT);
//$printer->setEmphasis(true);
$printer->setEmphasis(false);

/* Items */
$printer->setJustification(Printer::JUSTIFY_LEFT);
//$printer -> setEmphasis(true);
//$printer -> text(new item('', '$'));
//$printer -> setEmphasis(false);
$printer->text("-------------------------------\n");

    $printer->text($title);
    $printer->text('');
    $printer->text($value);

$printer->text("-------------------------------\n");

$printer->feed();
$printer->setEmphasis(true);
$printer->setEmphasis(false);
/* Tax and total */
$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer->feed(1);
$printer->text($total);
$printer->selectPrintMode();

/* Footer */
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("Thank you\n");
$printer->feed();
$printer->text($date . "\n");
$printer->feed(3);
//foreach (array(512, 256, 128, 64) as $width) {
//    $printer->setPrintWidth($width);
//}
/* Cut the receipt and open the cash drawer */
//$printer -> cut();
//$printer -> pulse();

$printer->close();