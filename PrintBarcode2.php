<?php
include_once 'booster/bridge.php';
include("booster/classZebra.php");

$print_data = $_REQUEST['print_data'];
$print_data = stripslashes($print_data);
$print_data_array = array();
$print_data_array = json_decode($print_data);
$things = array();
$values = array();
$data = array();
$data2 = array();

for ($fl = 0; $fl < count($print_data_array); $fl++) {
    $things = $print_data_array[$fl];
    $things_count = count($things);
    for ($sl = 0; $sl < $things_count; $sl++) {
        $values[$sl]['product_name'] = $things[$sl]->product_name;
        $values[$sl]['product_code'] = $things[$sl]->product_code;
        $values[$sl]['product_lang_name'] = $things[$sl]->product_lang_name;
    }
}

$host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$printer_name = BCPRINTER;
$hostPrinter = "\\$host_name$printer_name";
$speedPrinter = 4;
$darknessPrint = 5;
$labelSize = array(300, 15);
$referencePoint = array(223, 15);

$value_count = count($values);

for ($i = 0; $i < $value_count; $i++) {

    $z = new ZebraPrinter($hostPrinter, $speedPrinter, $darknessPrint, $labelSize, $referencePoint);

    $data['product_name'] = $values[$i]['product_name'];
    $data['product_code'] = $values[$i]['product_code'];
    $data['product_lang_name'] = $values[$i]['product_lang_name'];
    $i++;

    if (!empty($values[$i])) {
        $data2['product_name'] = $values[$i]['product_name'];
        $data2['product_code'] = $values[$i]['product_code'];
        $data2['product_lang_name'] = $values[$i]['product_lang_name'];
    } else {
        unset($data2);
    }

    $z->writeLabel("MANDI", 540, 135, 3);
    if (!empty($data2)) {
        $z->writeLabel("MANDI", 130, 135, 3);
    }

    $z->writeLabel($data['product_name'], 550, 100, 2);
    if (!empty($data2)) {
        $z->writeLabel($data2['product_name'], 150, 100, 2);
    }

    $z->writeLabel($data['product_lang_name'], 550, 75, 2);
    if (!empty($data2)) {
        $z->writeLabel($data2['product_lang_name'], 150, 75, 2);
    }

    $z->setBarcode(1, 570, 60, $data['product_code']); #1 -> cod128
    if (!empty($data2)) {
        $z->setBarcode(1, 170, 60, $data2['product_code']); #1 -> cod128
    }

    $z->writeLabel($data['product_code'], 540, 10, 2);
    if (!empty($data2)) {
        $z->writeLabel($data2['product_code'], 130, 10, 2);
    }

    $z->setLabelCopies(0);
    $z->print2zebra();
}


