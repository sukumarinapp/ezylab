<?php
include_once 'booster/bridge.php';
$today = date("Y-m-d");
$time = date("h:i:s A");
$backgroundColor = array('#f56954', '#f39c12', '#0073b7', '#00c0ef', '#00a65a', '#3c8dbc');
$borderColor = array('#f56954', '#f39c12', '#0073b7', '#00c0ef', '#00a65a', '#3c8dbc');
$events = array();
$response = array();
//$response['id'] = "";
$response['title'] = $time;
$response['start'] = $today;
$response['end'] = $today;
$response['backgroundColor'] = $backgroundColor[rand(0, 5)];
$response['borderColor'] = $borderColor[rand(0, 5)];
$response['allDay'] = true;
array_push($events, $response);

$EventQuery = "SELECT * FROM macho_holiday ORDER BY start_date DESC";
$EventResult = GetAllRows($EventQuery);
$EventCounts = count($EventResult);
if ($EventCounts > 0) {
    foreach ($EventResult as $EventData) {
        $response = array();
        $response['title'] = $EventData['description'];
        $response['start'] = $EventData['start_date'];
        //$response['end'] = $EventData['end'];
        $response['end'] = date('Y-m-d', strtotime('+1 day', strtotime($EventData['end_date'])));
        $response['allDay'] = true;
        $response['backgroundColor'] = $backgroundColor;
        $response['borderColor'] = $borderColor;
        array_push($events, $response);

    }
}
echo(json_encode($events));
exit;
