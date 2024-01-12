<?php

$title = $_POST['title'];
$from_date = $_POST['from_date'];
$todate = $_POST['todate'];
$thead_data = $_POST['thead_data'];
$tbody_data = $_POST['tbody_data'];
$tfoot_data = isset($_POST['tfoot_data']) ? $_POST['tfoot_data'] : array();
$thead_data_count = count($thead_data);
$tbody_data_count = count($tbody_data);
$tfoot_data_count = count($tfoot_data);
$tr_count = 0;

?>
<html>
<head>
    <title><?= $title; ?></title>
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet"
          id="bootstrap-css">
    <link rel="shortcut icon" href="http://sriganeshgroups.com/sg/logo/favicon.jpg"/>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body onload="window.print();">
<table class="table table-striped table-bordered" border="0" align="center">
    <tbody>
    <tr>
        <td colspan="<?= $thead_data_count; ?>" style="text-align: center">
            <img src="logo/bill_logo.png" height="50"/>
        </td>
    </tr>
    <tr>
        <td colspan="<?= $thead_data_count; ?>" style="text-align: center">
            <p style="color:black;font-weight: bold"><?= $title; ?></p>
        </td>
    </tr>
    <?php if (trim($from_date) !== "0" && trim($todate) !== "0") { ?>
        <tr>
            <td colspan="<?= $thead_data_count-1; ?>" style="font-weight: bold;border-right: none!important;" align="left">From Date: <?= date("d-m-Y", strtotime($from_date)); ?></td>

            <td style="font-weight: bold;border-left: none!important;" align="right">To Date: <?= date("d-m-Y", strtotime($todate)); ?></td>
        </tr>
    <?php } ?>
    <tr style="font-weight: bold">
        <?php
        for ($i = 0; $i < $thead_data_count; $i++) {
            echo '<td style="text-align: center">' . $thead_data[$i] . '</td>';
        } ?>
    </tr>
    <?php
    for ($d = 0; $d < $tbody_data_count; $d++) {

        if ($tr_count == 0) {
            echo '<tr>';
        }
        echo '<td style="text-align: center">' . $tbody_data[$d] . '</td>';

        $tr_count = $tr_count + 1;
        if ($tr_count == $thead_data_count) {
            $tr_count = 0;
            echo '</tr>';
        }
    }
    if ($tfoot_data_count !== "0") {
        $tr_count = 0;
        for ($d = 0; $d < $tfoot_data_count; $d++) {

            if ($tr_count == 0) {
                echo '<tr>';
            }
            echo '<td style="text-align: center">' . $tfoot_data[$d] . '</td>';

            $tr_count = $tr_count + 1;
            if ($tr_count == $thead_data_count) {
                $tr_count = 0;
                echo '</tr>';
            }
        }
    } ?>
    </tbody>
</table>
</body>
</html>