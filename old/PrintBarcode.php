<?php
include_once 'booster/bridge.php';
include("booster/classZebra.php");

$print_data = $_REQUEST['print_data'];

$print_data = stripslashes($print_data);

$print_data_array = array();

$print_data_array = json_decode($print_data);


$product_name = $print_data_array[0]->product_name;

$product_code = $print_data_array[0]->product_code;

$product_lang_name = $print_data_array[0]->product_lang_name;

$host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);

$printer_name = BCPRINTER;
$hostPrinter = "\\$host_name$printer_name";
$speedPrinter = 4;
$darknessPrint = 5;
$labelSize = array(300, 15);
$referencePoint = array(223, 15);

$z = new ZebraPrinter($hostPrinter, $speedPrinter, $darknessPrint, $labelSize, $referencePoint);

$z->writeLabel("MANDI", 540, 135, 3);
$z->writeLabel("MANDI", 130, 135, 3);

$z->writeLabel($product_name, 550, 100, 2);
$z->writeLabel($product_name, 150, 100, 2);

$z->writeLabel($product_lang_name, 550, 75, 2);
$z->writeLabel($product_lang_name, 150, 75, 2);

$z->setBarcode(1, 570, 60, $product_code); #1 -> cod128
$z->setBarcode(1, 170, 60, $product_code); #1 -> cod128
$z->writeLabel($product_code, 540, 10, 2);
$z->writeLabel($product_code, 130, 10, 2);

$z->setLabelCopies(1);
$z->print2zebra();
