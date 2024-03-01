<?php
include_once 'booster/bridge.php';

exit;
error_reporting(0);
include_once 'booster/vendor/autoload.php';

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

$html = '
<html>
<head>
 <title>' . $title . '</title>
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link rel="shortcut icon" href="http://sriganeshgroups.com/sg/logo/favicon.jpg"/>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<style>
    body {
        margin-top: 20px;
        margin-right: 20px !important;
    }
</style>
</head>
<body>
<div class="col-md-9 col-md-offset-3" align="center" style="border: none!important;">
    <A><img src="logo/bill_logo.png" class="responsive" height="75px"
            style="position:absolute; text-align: center!important;"/>
    </A>
</div><br>
<div class="container" align="center">
    <div class="row" align="center">
        <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3" align="center">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table border="0" class="table" width="100%" align="center">
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2" style="font-weight: bold;font-size: 18px"><address>&nbsp;' . $title . '&nbsp;</address></td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>';
if (trim($from_date) !== "0" && trim($todate) !== "0") {
    $html .= '<tr>
                            <td align="left">From Date:&nbsp;&nbsp;' . date("d-m-Y", strtotime($from_date)) . '&nbsp;</td>
                            <td align="right">To Date:&nbsp;&nbsp;' . date("d-m-Y", strtotime($todate)) . '&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>';
}
$html .= '</table>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-hover" width="100%" align="center">
                        <thead>
                        <tr>';
for ($i = 0; $i < $thead_data_count; $i++) {
    $html .= '<th style="text-align:left">&nbsp;' . $thead_data[$i] . '&nbsp;</th>';
}
$html .= '</tr>
                        </thead>

                        <tbody>
                        <tr>';
for ($i = 0; $i < $thead_data_count; $i++) {
    $html .= '<td>&nbsp;</td>';
}
$html .= '</tr>';

for ($d = 0; $d < $tbody_data_count; $d++) {
    if ($tr_count == 0) {
        $html .= '<tr>';
    }
    $html .= '<td>&nbsp;' . $tbody_data[$d] . '&nbsp;</td>';

    $tr_count = $tr_count + 1;
    if ($tr_count == $thead_data_count) {
        $tr_count = 0;
        $html .= '</tr>';
    }
}

if ($tfoot_data_count !== "0") {
    $tr_count = 0;
    for ($d = 0; $d < $tfoot_data_count; $d++) {
        if ($tr_count == 0) {
            $html .= '<tr>';
        }
        $html .= '<td><strong>&nbsp;' . $tfoot_data[$d] . '&nbsp;</strong></td>';

        $tr_count = $tr_count + 1;
        if ($tr_count == $thead_data_count) {
            $tr_count = 1;
            $html .= '</tr>';
        }
    }
}

$html .= '</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>';

$content = ob_get_clean();
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$content = $mpdf->Output("", "S");
ob_start();
header("Content-Type:application/pdf");
header('Content-Disposition: inline; Report.pdf');
echo $content;
