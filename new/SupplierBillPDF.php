<?php
include_once 'booster/bridge.php';

//include 'barcode/BarcodeGenerator.php';

//include 'barcode/BarcodeGeneratorHTML.php';

$OrgInfo = OrgInfo('1');
$id = DecodeVariable($_GET['fID']);;
$BillData = SelectParticularRow('macho_farmer_bill', 'id', $id);

$farmer_id = $BillData['farmer_id'];

$FarmerInfo = SelectParticularRow('macho_farmers', 'id', $farmer_id);

$Query = "SELECT a.*,b.product_name,b.product_lang_name FROM macho_farmer_bill_items a,macho_master_products b WHERE a.bill_id='$id' AND b.id=a.item_id ORDER BY a.id DESC";
$Result = GetAllRows($Query);

//$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
?>
<html>
<head>
    <title>Bill Receipt</title>
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet"
          id="bootstrap-css">
    <link rel="shortcut icon" href="<?= FAVICON; ?>"/>
    <script type="text/javascript" src="<?= JS; ?>jquery-3.2.1.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<!--    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>-->
</head>
<body onload="window.print()">

<table border="0" align="center" width="400px">
    <tbody>
    <tr>
        <td colspan="2" align="center">
            <table border="0" align="center" width="700px">
                <tbody>
                <tr>
                    <td colspan="2">
                        <table border="0" width="700px" align="center">
                            <tr align="center" style="text-align: center">
                                <td style="text-align: center"><b><?= $OrgInfo['name']."</b><br>"; ?><?= nl2br($OrgInfo['address'])."<br>"; ?>
                                    <?= $OrgInfo['mobile']."<br>"; ?><?= $OrgInfo['email']; ?></td>
                            </tr>
                        </table>
                        <table border="0" width="700px" align="center" class="table">
                            <tr>
                                <td width="40%" align="left"><b style="font-size: 22px!important;font-weight: bold;"><?= $FarmerInfo['F_name']."</b><br>"; ?><?= nl2br($FarmerInfo['address1'])."<br>"; ?>
                                    <?= $FarmerInfo['mobile']."<br>"; ?><?= $FarmerInfo['email']; ?></td>

                                <td width="40%" align="right" style="font-size: 22px!important;font-weight: bold;"><k style="width:120px!important;">Dated</k> : <?php echo from_sql_date($BillData['bill_date']); ?>
                                    <br/><k style="width:120px!important;">Bill No</k> : <?php echo $BillData['bill_no']; ?></td>
                            </tr>
                        </table>
                        <div>
                            <table border="0" width="700px" align="center" class="table table-bordered">
                                <tr style="font-weight: bold">
                                    <td style="text-align: center">#</td>
                                    <td style="text-align: center">Rate</td>
                                    <td style="text-align: center">GST %</td>
                                    <td style="text-align: center" colspan="2">Particulars</td>
                                    <td style="text-align: center">Qty</td>
                                    <td style="text-align: center">Amount</td>
                                </tr>
                                <?php
                                $i = 1;
                                foreach ($Result as $Data) {
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><?php echo $i; ?></td>
                                        <td style="text-align: center"><?php echo $Data['rate']; ?></td>
                                        <td style="text-align: center"><?php echo $Data['gst'].'%'; ?></td>
                                        <td style="text-align: center" colspan="2"><?php echo $Data['product_name']; ?></td>
                                        <td style="text-align: center;font-size: 22px!important;"><?php echo $Data['qty'].$Data['uom']; ?></td>
                                        <td style="text-align: center;font-size: 22px!important;"><?php echo $Data['amount']; ?></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                                <tr>
                                    <td colspan="6">&nbsp;</td>
                                    <td style="text-align: center;font-size: 22px!important;"><?= ConvertMoneyFormat($BillData['total_amount']);?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Advance</td>
                                    <td colspan="2" style="text-align: center"><?= ConvertMoneyFormat($BillData['advance_amount']);?></td>
                                    <td>Expense</td>
                                    <td><?= ConvertMoneyFormat($BillData['expense_amount']);?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Tempo Rent</td>
                                    <td colspan="2" style="text-align: center"><?= ConvertMoneyFormat($BillData['rent_amount']);?></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Labour Charges</td>
                                    <td colspan="2" style="text-align: center"><?= ConvertMoneyFormat($BillData['labour_amount']);?></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Commission Amount</td>
                                    <td colspan="2" style="text-align: center"><?= ConvertMoneyFormat($BillData['percentage_amount']);?></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;font-weight: bold" colspan="6">Net Total</td>
                                    <td style="text-align: center;font-size: 22px!important;font-weight: bold">Rs.<?= ConvertMoneyFormat($BillData['net_amount']);?></td>
                                </tr>
<!--                                <tr><td colspan="6" style="text-align: center!important;">--><?php //echo $generator->getBarcode($BillData['bill_no'], $generator::TYPE_CODE_128);?><!--</td>-->
<!--                                </tr>-->
                            </table><br>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>