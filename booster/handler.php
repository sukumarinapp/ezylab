<?php
use PHPMailer\PHPMailer\PHPMailer;

function GenerateOtp()
{
    $pass = mt_rand(100000, 999999);
    return $pass;
}

function validUserName($username)
{
    $sql2 = "select id from macho_users WHERE username='$username' AND login_status='1'";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
    $count = mysqli_num_rows($result2);
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}

function EncodePass($str)
{
    for ($i = 0; $i < 5; $i++) {
        $str = strrev(base64_encode($str));
    }

    return $str;
}

//function to decrypt the string

function DecodePass($str)
{
    for ($i = 0; $i < 5; $i++) {
        $str = base64_decode(strrev($str));
    }

    return $str;
}

function EncodeVariable($id)
{
    $id_str = (string) $id;
    $offset = rand(0, 9);
    $encoded = chr(79 + $offset);
    for ($i = 0, $len = strlen($id_str); $i < $len; ++$i) {
        $encoded .= chr(65 + $id_str[$i] + $offset);
    }
    return $encoded;
}

function DecodeVariable($encoded)
{
    $offset = ord($encoded[0]) - 79;
    $encoded = substr($encoded, 1);
    for ($i = 0, $len = strlen($encoded); $i < $len; ++$i) {
        $encoded[$i] = ord($encoded[$i]) - $offset - 65;
    }
    return (int) $encoded;
}

function IsAjaxRequest()
{

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):

        return true;

    else:

        header("location:404");
        exit;

    endif;

}

function GetAllRows($sql)
{
    $values = array();
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    while ($data = mysqli_fetch_assoc($result)) {
        $values[] = $data;
    }
    return $values;
}


function Insert($table, $fields = array())
{

    if (count($fields)) {

        $keys = array_keys($fields);


        $sql = "INSERT INTO " . $table . " (`" . implode('`, `', $keys) . "`) VALUES('" . implode("', '", $fields) . "')";
        $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
        $insert_id = mysqli_insert_id($GLOBALS['conn']);
        if ($result) {
            return $insert_id;
        }
        return false;
    }
    return false;

}


function Update($table, $key, $id, $fields)
{

    $set = '';
    $x = 1;
    $insert_string = '';

    foreach ($fields as $name => $value) {
        $insert_string .= 's';


        $set .= $name . "='" . $value . "'";
        if ($x < count($fields)) {
            $set .= ', ';
        }
        $x++;
    }
    $sql = 'UPDATE ' . $table . ' SET ' . $set . ' WHERE ' . $key . '=' . $id;
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    if ($result) {
        return true;
    }
    return false;
}


function DeleteRow($table, $key, $id)
{
    $sql = "DELETE FROM $table WHERE $key = $id";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    if ($result) {
        return true;
    }
    return false;
}


function MaxId($table, $column, $value)
{

    $sql = 'SELECT MAX(id) as id FROM ' . $table . ' WHERE ' . $column . ' =' . $value . '';
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['id'];

}


function SelectParticularRow($table, $column, $value)
{
    $sql = "SELECT * FROM $table WHERE $column = $value";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data;
}

function FirstRowDate($table, $primary_key, $column)
{
    $today = date("Y-m-d");

    $sql = "SELECT MIN($primary_key) as id FROM $table ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $min_id = $data['id'];
    if ($min_id != '') {
        $sql2 = "SELECT $column FROM $table WHERE $primary_key ='$min_id'";
        $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
        $data2 = mysqli_fetch_assoc($result2);
        $first_created_date = $data2[$column];
    } else {
        $first_created_date = date('Y-m-d', strtotime('-30 day', strtotime($today)));
    }
    return from_sql_date($first_created_date);
}

function GetEntryCounts($sql)
{
    $result = mysqli_query($GLOBALS['conn'], $sql);
    $count = mysqli_num_rows($result);
    return $count;
}

function columnSort($unsorted, $column)
{
    $sorted = $unsorted;
    for ($i = 0; $i < sizeof($sorted) - 1; $i++) {
        for ($j = 0; $j < sizeof($sorted) - 1 - $i; $j++)
            if ($sorted[$j][$column] < $sorted[$j + 1][$column]) {
                $tmp = $sorted[$j];
                $sorted[$j] = $sorted[$j + 1];
                $sorted[$j + 1] = $tmp;
            }
    }
    return $sorted;
}

function GetIPAddress()
{
    $mainIp = '';
    if (getenv('HTTP_CLIENT_IP'))
        $mainIp = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $mainIp = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $mainIp = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $mainIp = getenv('REMOTE_ADDR');
    else
        $mainIp = 'UNKNOWN';
    return $mainIp;
}


function GetClientGeoDetails()
{
    $ip = GetIPAddress();
    $url = "http://ipinfo.io/$ip";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $data = curl_exec($ch);
    curl_close($ch);

    $location = json_decode($data);
    return $location;
}

function Filter($string)
{
    $string = trim($string);
    //$string = escapeshellcmd($string);
    //$string = stripslashes(strip_tags(htmlspecialchars($string)));
    $string = mysqli_real_escape_string($GLOBALS['conn'], $string);
    return $string;
}


function WordReplace($Word)
{
    $description = preg_replace('/\r?\n|\r/', '<br/>', $Word);
    $description = str_replace(array("\r\n", "\r", "\n"), "<br/>", $description);
    $description = str_ireplace('<br/>', ' ', $description);
    $data = nl2br($description);
    return $data;
}

function WordCount($input)
{
$input = str_replace(","," ",$input);
$res = preg_split('/\s+/', $input);
$count = count($res);
return $count;
}
function WordReplace2($Word)
{
    $Word = str_replace("&nbsp;", "", $Word);
    if (preg_match("%>%", "$Word")) {
        $value = explode(">", $Word);
        $replace_word = explode("</label", $value[1]);
        $replace_word = $replace_word[0];
    } else {
        $replace_word = $Word;
    }

    return $replace_word;
}

function errorHandler($errno, $errstr, $errfile, $errline)
{
    return;
    $query = "INSERT INTO macho_error_log (severity, message, filename, lineno, time) ";
    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
        case E_DEPRECATED:
        case E_USER_DEPRECATED:
        case E_STRICT:
            $severity = 'NOTICE';
            $query .= "VALUES ('$severity', '$errstr', '$errfile', '$errline', NOW())";
            $result = mysqli_query($GLOBALS['conn'], $query) or die(mysqli_error($GLOBALS['conn']));
            break;

        case E_WARNING:
        case E_USER_WARNING:
            $severity = 'WARNING';
            $query .= "VALUES ('$severity', '$errstr', '$errfile', '$errline', NOW())";
            $result = mysqli_query($GLOBALS['conn'], $query) or die(mysqli_error($GLOBALS['conn']));
            break;

        case E_ERROR:
        case E_USER_ERROR:
            $severity = 'FATAL';
            $query .= "VALUES ('$severity', '$errstr', '$errfile', '$errline', NOW())";
            $result = mysqli_query($GLOBALS['conn'], $query) or die(mysqli_error($GLOBALS['conn']));
            exit("FATAL error $errstr at $errfile:$errline");

        default:
            exit("Unknown error at $errfile:$errline");
    }
}


set_error_handler("errorHandler");

function getDatesFromRange($start, $end, $format = 'Y-m-d')
{

    // Declare an empty array
    $array = array();

    // Variable that store the date interval
    // of period 1 day
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    // Use loop to store date into array
    foreach ($period as $date) {
        $array[] = $date->format($format);
    }

    // Return the array elements
    return $array;
}

function from_sql_date($input)
{
    if ($input == "") {
        return "";
    } elseif ($input == "0000-00-00") {
        return "";
    } else {
        $date = date("d", strtotime($input));
        $month = date("m", strtotime($input));
        $year = date("Y", strtotime($input));
        $new_date = $date . "-" . $month . "-" . $year;
        return date("d-m-Y", strtotime($new_date));
    }
}

function to_sql_date($input)
{
    if ($input == "") {
        return "";
    } elseif ($input == "0000-00-00") {
        return "";
    } else {
        $date = date("d", strtotime($input));
        $month = date("m", strtotime($input));
        $year = date("Y", strtotime($input));
        $new_date = $date . "-" . $month . "-" . $year;
        return date("Y-m-d", strtotime($new_date));
    }
}

function CheckDuration($StartDate, $EndDate)
{
    $StartDate = strtotime($StartDate);
    $EndDate = strtotime($EndDate);

    $strTime = array("second", "minute", "hour", "day", "month", "year");
    $length = array("60", "60", "24", "30", "12", "10");

    $diff = $EndDate - $StartDate;
    for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
        $diff = $diff / $length[$i];
    }

    $diff = round($diff);
    return $diff . " " . $strTime[$i] . "(s) after ";
}

function TimeAgo($timestamp)
{
    $time_ago = strtotime($timestamp);
    $current_time = strtotime(date("Y-m-d h:i:s"));

    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes = round($seconds / 60); // value 60 is seconds
    $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
    $days = round($seconds / 86400); //86400 = 24 * 60 * 60;
    $weeks = round($seconds / 604800); // 7*24*60*60;
    $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
    $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
    if ($seconds <= 60) {
        if ($seconds == 1) {
            return "one sec ago";
        } else {
            return "$seconds sec(s) ago";
        }
    } else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "one min ago";
        } else {
            return "$minutes min(s) ago";
        }
    } else if ($hours <= 24) {
        if ($hours == 1) {
            return "an hr ago";
        } else {
            return "$hours hrs ago";
        }
    } else if ($days <= 7) {
        if ($days == 1) {
            return "one day ago";
        } else {
            return "$days days ago";
        }
    } else if ($weeks <= 4.3) //4.3 == 52/12
    {
        if ($weeks == 1) {
            return "a week ago";
        } else {
            return "$weeks weeks ago";
        }
    } else if ($months <= 12) {
        if ($months == 1) {
            return "a month ago";
        } else {
            return "$months months ago";
        }
    } else {
        if ($years == 1) {
            return "one year ago";
        } else {
            return "$years years ago";
        }
    }
}

function GetAge($birthDate)
{
    $today = date("Y-m-d");
    $birthDate = to_sql_date($birthDate);
    $StartDate = strtotime($birthDate);
    $EndDate = strtotime($today);
    $values = array();

    $strTime = array("Second", "Minute", "Hour", "Days", "Months", "Years");
    $length = array("60", "60", "24", "30", "12", "10");

    $diff = $EndDate - $StartDate;
    for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
        $diff = $diff / $length[$i];
    }
    $diff = round($diff);
    $values['age'] = $diff;
    $values['age_type'] = $strTime[$i];

    return $values;
}

function space($limit)
{
    for ($i = 0; $i < $limit; $i++) {
        echo "&nbsp;";
    }
}

function ConvertDec2Fraction($f)
{
    $base = floor($f);
    if ($base) {
        $out = $base . ' ';
        $f = $f - $base;
    }
    if ($f != 0) {
        $d = 1;
        while (fmod($f, 1) != 0.0) {
            $f *= 2;
            $d *= 2;
        }
        $n = sprintf('%.0f', $f);
        $d = sprintf('%.0f', $d);
        $out .= $n . '/' . $d;
    }
    return $out;
}

function ConvertMoneyFormat($input)
{
    if ($input == 0) {
        return 0;
    } else {
        $MoneyFormat = number_format((float) ($input), 2, '.', '');
        return $MoneyFormat;
    }
}

function ConvertMoneyFormat2($input)
{
    if ($input == 0) {
        return 0;
    } else {
        $MoneyFormat = number_format((float) ($input), 2, '.', ',');

        return $MoneyFormat;
    }
}

function Convert_Amount_In_Words($number)
{
    if ($number != 0) {
        $no = round($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '',
            '1' => 'One',
            '2' => 'Two',
            '3' => 'Three',
            '4' => 'Four',
            '5' => 'Five',
            '6' => 'Six',
            '7' => 'Seven',
            '8' => 'Eight',
            '9' => 'Nine',
            '10' => 'Ten',
            '11' => 'Eleven',
            '12' => 'Twelve',
            '13' => 'Thirteen',
            '14' => 'Fourteen',
            '15' => 'Fifteen',
            '16' => 'Sixteen',
            '17' => 'Seventeen',
            '18' => 'Eighteen',
            '19' => 'Nineteen',
            '20' => 'Twenty',
            '30' => 'Thirty',
            '40' => 'Forty',
            '50' => 'Fifty',
            '60' => 'Sixty',
            '70' => 'Seventy',
            '80' => 'Eighty',
            '90' => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else
                $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';
        $points = $points . " Paise";
        if ($point == 0) {
            $values = $result . "Rupees  " . " only";
        } else {
            $values = $result . "Rupees  " . $points . " only";
        }
    } else {
        $values = "Zero Rupees only";
    }
    return $values;
}

function GetTaxableValue($Tax, $TaxableAmount)
{
    $values = array();
    $sgst = ($Tax / 2);
    $cgst = ($Tax / 2);
    $sgst_amount = ConvertMoneyFormat(($TaxableAmount * $sgst) / 100);
    $cgst_amount = ConvertMoneyFormat(($TaxableAmount * $cgst) / 100);
    $tax_amount = ConvertMoneyFormat($sgst_amount + $cgst_amount);
    $values['sgst_tax'] = $sgst . ' %';
    $values['cgst_tax'] = $cgst . ' %';
    $values['sgst_amount'] = $sgst_amount;
    $values['cgst_amount'] = $cgst_amount;
    $values['tax_amount'] = $tax_amount;
    return $values;
}

function SendEmail($to = null, $subject = null, $messageBody = null)
{
    if ($to && $subject && $messageBody) {
        include_once 'vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true; // authentication enabled
        $mail->IsHTML(true); //turn on to send html email
        $mail->Host = "sg2plcpnl0153.prod.sin2.secureserver.net";
        $mail->Port = 465;
        $mail->Username = 'info@msdtraders.co.in';
        $mail->Password = '1qaz0okm';
        $mail->SetFrom("info@msdtraders.co.in", "msdtraders");
        $mail->Subject = $subject;
        $mail->MsgHTML($messageBody);
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPDebug = 0;

        $mail->AddAddress($to);
        if ($mail->Send()) {
            return "Mail Sent";
        } else {
            return "Mail Not Sent";
        }
    } else {
        return "";
    }
}

//function SendSMS($mobile, $message)
//{
//    $params = array(
//        "user" => "sriganeshgroups",
//        "pass" => "1qaz0okm",
//        "sender" => "GANESH",
//        "phone" => $mobile,
//        "text" => $message,
//        "priority" => "ndnd",
//        "stype" => "normal",
//    );
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, "http://bhashsms.com/api/sendmsg.php");
//    curl_setopt($ch, CURLOPT_POST, 1);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $sms_response = curl_exec($ch);
//    curl_close($ch);
//    return $sms_response;
//}

//function CheckSMSBalance()
//{
//    $params = array(
//        "user" => "sriganeshgroups",
//        "pass" => "1qaz0okm"
//    );
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, "http://bhashsms.com/api/checkbalance.php");
//    curl_setopt($ch, CURLOPT_POST, 1);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $sms_response = curl_exec($ch);
//    curl_close($ch);
//    return $sms_response;
//}

function IpVerification($ip_details)
{
    $date_time = date("Y-m-d H:i:s");
    $sql = "select blocked from macho_ip_tracking WHERE ip_addr='$ip_details->ip'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $track_count = mysqli_num_rows($result);
    if ($track_count > 0) {
        $tracking = mysqli_fetch_assoc($result);
        $block_status = $tracking['blocked'];
        if ($block_status == 1) {
            return false;
        } else {
            return true;
        }
    } else {
        $location = explode(',', $ip_details->loc);
        $lat = $location[0];
        $lang = $location[1];
        $sql1 = "INSERT INTO `macho_ip_tracking` (`ip_addr`, `city`, `state`, `country`, `lat`, `lang`, `postal`, `blocked`, `created`)
                                          VALUES ('$ip_details->ip', '$ip_details->city', '$ip_details->region', '$ip_details->country', '$lat', '$lang', '$ip_details->postal', '0', '$date_time');";
        $result2 = mysqli_query($GLOBALS['conn'], $sql1) or die(mysqli_error($GLOBALS['conn']));
        if ($result2) {
            return true;
        } else {
            return false;
        }
    }
}


function IsTemporaryBlocked($ip)
{
    $today = date("Y-m-d");
    $date_time = date('Y-m-d H:i:s', time());
    $change_status = 0;
    $sql = "select expire_time,block_status from macho_user_login_attempts WHERE login_date='$today' AND ip_addr='$ip' ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $count = mysqli_num_rows($result);
    $block_check = mysqli_fetch_assoc($result);
    $expire_time = $block_check['expire_time'];
    $block_status = $block_check['block_status'];
    if ($count > 0) {
        if ($block_status == 1) {
            if ($date_time > $expire_time) {
                $sql2 = "UPDATE macho_user_login_attempts SET user_attempt='$change_status',block_status='$change_status' WHERE ip_addr='$ip' AND login_date='$today'";
                $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
                return false;
            } else {
                return true;

            }
        } else {
            return false;
        }
    } else {
        return false;
    }

}


function UserLoginValidation($ip)
{
    $today = date("Y-m-d");
    $date_time = date('Y-m-d H:i:s', time());
    $block_status = 1;
    $sql = "select user_attempt from  macho_user_login_attempts WHERE login_date='$today' AND ip_addr='$ip'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $count = mysqli_num_rows($result);
    $entry = mysqli_fetch_assoc($result);
    if ($count == 0) {
        $sql2 = "INSERT INTO macho_user_login_attempts(ip_addr,last_login_entry,login_date) VALUES('$ip','$date_time','$today')";
        $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
        return true;
    } else {
        $exist_attempt = $entry['user_attempt'];
        $last_login_entry = $date_time = date('Y-m-d H:i:s', time());
        ;
        $last_login_entry = strtotime($last_login_entry);
        $last_login_entry = strtotime("+30minute", $last_login_entry);
        $last_login_entry = date('Y-m-d H:i:s', $last_login_entry);
        if ($exist_attempt >= 3) {
            $sql1 = "UPDATE macho_user_login_attempts SET block_status='$block_status',expire_time='$last_login_entry' WHERE ip_addr='$ip' AND login_date='$today' ";
            $result1 = mysqli_query($GLOBALS['conn'], $sql1) or die(mysqli_error($GLOBALS['conn']));
            return false;
        } else {
            $sql2 = "UPDATE macho_user_login_attempts SET last_login_entry='$date_time' WHERE ip_addr='$ip' AND login_date='$today'";
            $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
            return true;
        }


    }

}

function SuccessfulLogin($ip)
{
    $today = date("Y-m-d");
    $attempt = 0;
    $sql2 = "UPDATE macho_user_login_attempts SET user_attempt='$attempt',block_status='$attempt' WHERE ip_addr='$ip' AND login_date='$today'";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
}

function FailureLogin($ip)
{
    $today = date("Y-m-d");
    $sql = "select user_attempt from  macho_user_login_attempts WHERE login_date='$today' AND ip_addr='$ip'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $entry = mysqli_fetch_assoc($result);
    $update_attempt = $entry['user_attempt'] + 1;
    $sql2 = "UPDATE macho_user_login_attempts SET user_attempt='$update_attempt' WHERE ip_addr='$ip' AND login_date='$today'";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
}


function BlockedDuration($ip)
{
    $today = date("Y-m-d");
    $date_time = date('Y-m-d H:i:s', time());
    $sql = "select user_attempt from  macho_user_login_attempts WHERE login_date='$today' AND ip_addr='$ip'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $entry = mysqli_fetch_assoc($result);
    $expire_time = $entry['expire_time'];
    $required_time = CheckDuration($date_time, $expire_time);
    $required_time = str_replace("after", "", $required_time);
    // $required_time = $date_time->getTimestamp() - $expire_time->getTimestamp();
    return $required_time;
}

function GetAccessToken()
{
    $rand_var = GenerateOtp();
    $token = EncodePass($rand_var);
    return $token;
}


function UpdateAccessToken($id, $acces_token)
{
    $acces_token = trim($acces_token);
    $sql2 = "UPDATE macho_users SET access_token='$acces_token' WHERE id='$id'";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
    if ($result2) {
        return true;
    } else {
        return false;
    }
}

function ValidateAccessToken($user_id, $access_token)
{
    return true;
    $sql = "select id from  macho_users WHERE id='$user_id' AND access_token='$access_token'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $count = mysqli_num_rows($result);
    if ($count == 0) {
        header("location:404");
        exit;
    } else {
        return true;
    }
}

function LogEntry($user_id, $geo_details)
{

    $sql = "SELECT confirmation FROM macho_ip_tracking WHERE ip_addr='$geo_details->ip'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $row = mysqli_fetch_assoc($result);
    $confirmation = $row['confirmation'];
    $email = GetUserEmail($user_id);
    if ($confirmation == 0) {
        //SendEmail($email);
    }
    $location = explode(',', $geo_details->loc);
    $lat = $location[0];
    $lang = $location[1];
    $datetime = date("Y-m-d H:i:s");
    $created = date("Y-m-d");
    $sql2 = "INSERT INTO `macho_entry_log` (`login_id`, `in_time`,`ip_addr`, `city`, `state`, `country`, `lat`, `lang`, `postal`, `created`)
                                    VALUES ('$user_id', '$datetime','$geo_details->ip', '$geo_details->city', '$geo_details->region', '$geo_details->country', '$lat', '$lang', '$geo_details->postal', '$created');";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
    if ($result2) {
        return true;
    } else {
        return false;
    }
}

function LogOutEntry($user_id)
{

    $max_id = MaxId('macho_entry_log', 'login_id', $user_id);
    $datetime = date("Y-m-d H:i:s");
    $sql2 = "UPDATE macho_entry_log SET out_time='$datetime' WHERE id='$max_id'";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
    if ($result2) {
        return true;
    } else {
        return false;
    }
}

function LogInTime($user_id)
{
    $max_id = MaxId('macho_entry_log', 'login_id', $user_id);
    $sql = "SELECT in_time FROM macho_entry_log WHERE id ='$max_id'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $LogInTime = $data['in_time'];
    return $LogInTime;
}

function LastLogInData($user_id, $date)
{
    $sql = "SELECT * FROM macho_entry_log WHERE id =(SELECT MAX(id) as log_id FROM macho_entry_log WHERE login_id='$user_id' AND created='$date')";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data;
}

function UserLogInCount($user_id, $date)
{
    $sql4 = "SELECT count(id) as user_count FROM macho_entry_log WHERE login_id='$user_id' AND created='$date'";
    $result4 = mysqli_query($GLOBALS['conn'], $sql4);
    $row4 = mysqli_fetch_assoc($result4);
    $user_count = $row4['user_count'];
    return $user_count;
}

function BrokedSession($user_id, $user_name, $access_token, $url)
{
    $datetime = date("Y-m-d H:i:s");
    $sql2 = "INSERT INTO `macho_session_brokedup` (`login_id`, `username`, `access_token`, `current_url`, `created`)
                                            VALUES ('$user_id', '$user_name', '$access_token', '$url', '$datetime');";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));

}


function ParameterValue($Parameter)
{
    $sql = "SELECT value FROM macho_web_config WHERE parameter ='$Parameter' ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['value'];
}

function GetUserEmail($user_id)
{

    $sql2 = "select email from macho_users WHERE id='$user_id'";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
    $row = mysqli_fetch_assoc($result2);
    return $row['email'];
}

function GetUserMobile($user_id)
{

    $sql2 = "select mobile from macho_users WHERE id='$user_id'";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
    $row = mysqli_fetch_assoc($result2);
    return $row['mobile'];

}

function RoleName($RoleId)
{
    $sql = "SELECT role FROM macho_role WHERE id ='$RoleId'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['role'];
}

function GetRoleOfUser($UserId)
{
    $sql = "SELECT role_id FROM macho_users WHERE id ='$UserId'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['role_id'];
}

function UserPageAcceses($UserID, $RoleId)
{
    $created = date("Y-m-d H:i:s");
    $modified = date("Y-m-d H:i:a");
    $is_added = '0';

    $RoleId = explode('~', $RoleId);
    foreach ($RoleId as $Role):
        $Query = "SELECT * FROM macho_role_menu_acceses WHERE role_id='$Role' ORDER BY id ";
        $Result = GetAllRows($Query);
        $Counts = count($Result);
        if ($Counts > 0):
            foreach ($Result as $Data):
                Insert(
                    'macho_user_page_acceses',
                    array(
                        'user_id' => $UserID,
                        'is_parent' => $Data['is_parent'],
                        'menu_id' => $Data['menu_id'],
                        'menu_icon' => $Data['menu_icon'],
                        'menu_name' => $Data['menu_name'],
                        'is_dropdown' => $Data['is_dropdown'],
                        'menu_url' => $Data['menu_url'],
                        'is_write' => $Data['is_write'],
                        'is_read' => $Data['is_read'],
                        'is_delete' => $Data['is_delete'],
                        'is_modify' => $Data['is_modify'],
                        'is_enable' => $Data['is_enable'],
                        'is_added' => $is_added,
                        'created' => $created,
                        'modified' => $modified
                    )
                );
            endforeach;
        endif;
    endforeach;
}

function CheckUserMenu($UserID, $MenuID)
{
    $sql = "select menu_id,is_write,is_read,is_delete,is_modify from macho_user_page_acceses WHERE user_id='$UserID' AND menu_id='$MenuID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $count = mysqli_num_rows($result);
    $data = mysqli_fetch_assoc($result);
    if ($count == 0) {
        return false;
    } else {
        return $data;
    }
}

function CheckRoleMenu($RoleID, $MenuID)
{
    $sql = "select menu_id,is_write,is_read,is_delete,is_modify from macho_role_menu_acceses WHERE role_id='$RoleID' AND menu_id='$MenuID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $count = mysqli_num_rows($result);
    $data = mysqli_fetch_assoc($result);
    if ($count == 0) {
        return false;
    } else {
        return $data;
    }
}

function IsPageAccessible($user_id, $Page)
{
    $PageSql = "select is_write,is_read,is_delete,is_modify from macho_user_page_acceses WHERE user_id='$user_id' AND menu_url='$Page'";
    $PageResult = mysqli_query($GLOBALS['conn'], $PageSql) or die(mysqli_error($GLOBALS['conn']));
    $PageCount = mysqli_num_rows($PageResult);
    $PageData = mysqli_fetch_assoc($PageResult);
    if ($PageCount == 0) {
        echo '<script>location.href="404";</script>';
        exit;
    } else {
        return $PageData;
    }

}

function InsertNotification($notes, $sender_id, $send_role_id, $receive_role_id, $receive_id)
{
    $date_time = date("Y-m-d h:i:s");
    $created = date("Y-m-d");
    $sql2 = "INSERT INTO macho_notifications(notes,sender_id,send_role_id,receive_role_id,receive_id,date_time,created)
VALUES('$notes','$sender_id','$send_role_id','$receive_role_id','$receive_id','$date_time','$created')";
    $result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
}

function GetNotificationCount($receive_id, $receive_role_id)
{
    $notification_sql = "SELECT id FROM macho_notifications WHERE receive_role_id='$receive_role_id' AND receive_id='$receive_id' AND view='0'";
    $notification_result = mysqli_query($GLOBALS['conn'], $notification_sql) or die(mysqli_error($GLOBALS['conn']));
    $notification_count = mysqli_num_rows($notification_result);
    return $notification_count;
}

function OrgInfo()
{
    $sql = "SELECT * FROM macho_info WHERE id='1'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data;
}

function UserInfo($UserID)
{
    $sql = "SELECT * FROM macho_users WHERE id='$UserID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data;
}

function UserName($UserID)
{
    $sql = "SELECT prefix,name FROM macho_users WHERE id='$UserID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $name = $data['prefix'] . $data['name'];
    return $name;
}

function SupplierInfo($SupplierId)
{
    $sql = "SELECT * FROM macho_product_suppliers WHERE id='$SupplierId'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data;
}

function GetSupplierNo()
{
    $sql = "select COUNT(id) as supplier_no from macho_product_suppliers ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $counts = $data['supplier_no'];
    $supplier_no = $counts + 1;
    if ($supplier_no < 10) {
        $supplier_no = "000" . $supplier_no;
    } elseif ($supplier_no < 100) {
        $supplier_no = "00" . $supplier_no;
    } elseif ($supplier_no < 1000) {
        $supplier_no = "0" . $supplier_no;
    }

    $prefix = strtoupper('HS-SUP');

    $supplier_no = $prefix . $supplier_no;

    $Count2 = GetEntryCounts("SELECT id FROM macho_product_suppliers WHERE supplier_no='$supplier_no'");

    if ($Count2 == 0) {
        return $supplier_no;
    } else {

        $supplier_no = str_replace($prefix, $prefix . "0", $supplier_no);
        return $supplier_no;
    }
}

function GetpatientCode()
{
    $sql = "SELECT count(id) as patient_code FROM macho_patient ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $counts = $data['patient_code'];
    $patient_code = $counts + 1;
    if ($patient_code < 10) {
        $patient_code = "000" . $patient_code;
    } elseif ($patient_code < 100) {
        $patient_code = "00" . $patient_code;
    } elseif ($patient_code < 1000) {
        $patient_code = "0" . $patient_code;
    }

    $prefix = 'HCDC-';

    $patient_code = $prefix . $patient_code;

    $Count2 = GetEntryCounts("SELECT id FROM macho_patient WHERE P_code='$patient_code'");

    if ($Count2 == 0) {
        return $patient_code;
    } else {

        $patient_code = str_replace($prefix, $prefix . "0", $patient_code);
        return $patient_code;
    }
}

function GetTestCode()
{
    $sql = "SELECT count(id) as test_code FROM macho_test_type ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $counts = $data['test_code'];
    $test_code = $counts + 1;
    if ($test_code < 10) {
        $test_code = "000" . $test_code;
    } elseif ($test_code < 100) {
        $test_code = "00" . $test_code;
    } elseif ($test_code < 1000) {
        $test_code = "0" . $test_code;
    }

    $prefix = 'L-T';

    $test_code = $prefix . $test_code;

    $Count2 = GetEntryCounts("SELECT id FROM macho_test_type WHERE test_code='$test_code'");

    if ($Count2 == 0) {
        return $test_code;
    } else {

        $test_code = str_replace($prefix, $prefix . "0", $test_code);
        return $test_code;
    }
}


function GetBillNumber()
{
    $today = date("Y-m-d");
    $sql = "SELECT count(id) as billnum FROM macho_billing ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $counts = $data['billnum'];
    $bill_no = $counts + 1;
    if ($bill_no < 10) {
        $bill_no = "000" . $bill_no;
    } elseif ($bill_no < 100) {
        $bill_no = "00" . $bill_no;
    } elseif ($bill_no < 1000) {
        $bill_no = "0" . $bill_no;
    }

    $year = date("Y", strtotime($today));
    $prefix = 'L-I/' . $year . '/';

    $bill_no = $prefix . $bill_no;

    $Count2 = GetEntryCounts("SELECT id FROM macho_billing WHERE billnum ='$bill_no'");

    if ($Count2 == 0) {
        return $bill_no;
    } else {

        $bill_no = str_replace($prefix, $prefix . "0", $bill_no);
        return $bill_no;
    }
}

function GetBillNo()
{
    $today = date("Y-m-d");
    $sql = "SELECT count(id) as billnum FROM patient_entry ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $counts = $data['billnum'];
    $bill_no = $counts + 1;
    if ($bill_no < 10) {
        $bill_no = "000" . $bill_no;
    } elseif ($bill_no < 100) {
        $bill_no = "00" . $bill_no;
    } elseif ($bill_no < 1000) {
        $bill_no = "0" . $bill_no;
    }

    $year = date("Y", strtotime($today));
    $prefix = 'L-B/' . $year . '/';

    $bill_no = $prefix . $bill_no;

    $Count2 = GetEntryCounts("SELECT id FROM patient_entry WHERE bill_no ='$bill_no'");

    if ($Count2 == 0) {
        return $bill_no;
    } else {

        $bill_no = str_replace($prefix, $prefix . "0", $bill_no);
        return $bill_no;
    }
}

function GetProductCode()
{
    $sql = "select count(id) as product_code from macho_master_products ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $counts = $data['product_code'];
    $product_code = $counts + 1;
    if ($product_code < 10) {
        $product_code = "000" . $product_code;
    } elseif ($product_code < 100) {
        $product_code = "00" . $product_code;
    } elseif ($product_code < 1000) {
        $product_code = "0" . $product_code;
    }

    $product_code = "L-P" . $product_code;

    $Count2 = GetEntryCounts("SELECT id FROM macho_master_products WHERE product_code='$product_code'");

    if ($Count2 == 0) {
        return $product_code;
    } else {
        $product_code = str_replace("L-P", "L-P0", $product_code);
        return $product_code;
    }
}

function ProductCodeExists($product_code)
{
    $sql = "SELECT product_code FROM macho_products WHERE product_code = '$product_code'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        return true;
    }
    return false;
}

function CustomerName($CustomerId)
{
    $sql = "SELECT prefix,P_name FROM macho_patient WHERE id='$CustomerId'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $name = $data['prefix'] . $data['P_name'];
    return $name;
}


function ProductName($product_id)
{
    $sql = "SELECT product_name FROM macho_products WHERE id ='$product_id'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['product_name'];
}

function ProductCategoryName($product_category)
{
    $sql = "SELECT category_name FROM macho_product_category WHERE id ='$product_category'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['category_name'];
}

function ProductStock($ProductID)
{
    $product_qty = 0;
    $ProductsQuery = "SELECT item_qty FROM macho_products WHERE parent_id='$ProductID' ORDER BY product_code DESC ";
    $ProductsResult = GetAllRows($ProductsQuery);
    $ProductsCounts = count($ProductsResult);
    if ($ProductsCounts > 0) {
        foreach ($ProductsResult as $ProductsData) {
            $product_qty = $product_qty + $ProductsData['item_qty'];
        }
    }

    return $product_qty;
}

function GetMAXProductID()
{
    $sql = "select MAX(id) as product_id from macho_products ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $product_id = $data['product_id'];
    return $product_id;
}

function DepartmentName($DeptID)
{
    $sql = "SELECT dept_name FROM departments WHERE id ='$DeptID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['dept_name'];
}

function DoctorName($DocID)
{
    $sql = "SELECT prefix,d_name FROM doctors WHERE id ='$DocID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['prefix'] . $data['d_name'];
}

function TestCategoryName($CategoryID)
{
    $sql = "SELECT category_name FROM macho_test_category WHERE id ='$CategoryID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['category_name'];
}
function TaxAccountName($TaxID)
{
    $sql = "SELECT tax_name FROM macho_tax_accounts WHERE id ='$TaxID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['tax_name'];
}

function TaxPercentage($TaxID)
{
    $sql = "SELECT percentage FROM macho_tax_accounts WHERE id ='$TaxID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['percentage'];
}

function StockValue()
{
    $total_value = 0;
    $ProductQuery = "SELECT quantity,rate FROM macho_products WHERE quantity<>'0' ORDER BY product_code";
    $ProductResult = GetAllRows($ProductQuery);
    $ProductCounts = count($ProductResult);
    if ($ProductCounts > 0) {
        foreach ($ProductResult as $ProductData) {
            $product_price = $ProductData['quantity'] * $ProductData['rate'];
            $total_value = $total_value + $product_price;
        }
    }

    return ConvertMoneyFormat2($total_value);
}

function GetEntryDate($date, $duration_type)
{
    switch ($duration_type) {

        case 1:

            $nextDate = date('Y-m-d', strtotime('+1 day', strtotime($date)));
            return $nextDate;

            break;

        case 2:

            $nextDate = date('Y-m-d', strtotime('+1 week', strtotime($date)));
            return $nextDate;

            break;

        case 3:

            $currentMonth = date("m", strtotime($date));

            $nextMonth = date('m', strtotime('+1 month', strtotime($date)));

            if ($nextMonth == 01) {
                $nextDate = date('Y-m-d', strtotime('+1 month', strtotime($date)));
            } else {
                if ($currentMonth == $nextMonth - 1) {

                    $nextDate = date('Y-m-d', strtotime('+1 month', strtotime($date)));

                } else {

                    $nextDate = date('Y-m-d', strtotime("last day of next month", strtotime($date)));
                }
            }

            return $nextDate;

            break;
    }
}

function OrgProfit($start_date, $end_date)
{

    $income = 0;
    $expense = 0;
    //$total_purchase_amount = 0;

    // $SalesQuery = "SELECT id FROM macho_billing WHERE bill_date>='$start_date' AND bill_date<='$end_date' ORDER BY id DESC ";
    // $SalesResult = GetAllRows($SalesQuery);
    // $SalesCounts = count($SalesResult);
    // if ($SalesCounts > 0) {
    //     foreach ($SalesResult as $SalesData) {
    //         $bill_id = $SalesData['id'];
    //         $total_purchase_amount = $total_purchase_amount + GetBillPurchaseAmount($bill_id);
    //     }
    // }

    $FinanceQuery = "SELECT *  FROM macho_revenue WHERE entry_date>='$start_date' AND entry_date<='$end_date' ORDER BY id DESC ";
    $FinanceResult = GetAllRows($FinanceQuery);
    $FinanceCounts = count($FinanceResult);
    if ($FinanceCounts > 0) {
        foreach ($FinanceResult as $FinanceData) {
            if ($FinanceData['type'] == "Income") {
                $income = $income + $FinanceData['amount'];
            }

            if ($FinanceData['type'] == "Expense") {
                $expense = $expense + $FinanceData['amount'];
            }
        }
    }

    $profit = $income - $expense;

    //$profit = ConvertMoneyFormat($profit - $total_purchase_amount);

    return $profit;
}

function GetUserEarningRevenue($user_id)
{
    $revenue_amount = 0;
    $Query = "SELECT amount FROM macho_staff_revenue WHERE user_id='$user_id'";
    $Result = GetAllRows($Query);
    foreach ($Result as $Data) {
        $revenue_amount = $revenue_amount + $Data['amount'];
    }
    return $revenue_amount;
}

function GetUserReceivedRevenue($user_id)
{
    $revenue_amount = 0;
    $Query = "SELECT amount FROM macho_staff_revenue WHERE user_id='$user_id' AND paid_status='1'";
    $Result = GetAllRows($Query);
    foreach ($Result as $Data) {
        $revenue_amount = $revenue_amount + $Data['amount'];
    }
    return $revenue_amount;
}

function GetUserPendingRevenue($user_id)
{
    $revenue_amount = 0;
    $Query = "SELECT amount FROM macho_staff_revenue WHERE user_id='$user_id' AND paid_status='0'";
    $Result = GetAllRows($Query);
    foreach ($Result as $Data) {
        $revenue_amount = $revenue_amount + $Data['amount'];
    }
    return $revenue_amount;
}

function StaffRevenue()
{
    $today = date("Y-m-d");

    $created = date("Y-m-d");

    $UserQuery = "SELECT id,prefix,name,salary_duration_type,salary_percentage,service_from FROM macho_users WHERE salary_mode='1' AND status='1' ORDER BY id ";

    $UserResult = GetAllRows($UserQuery);

    $UserCounts = count($UserResult);

    if ($UserCounts > 0) {

        foreach ($UserResult as $UserData) {

            $user_id = $UserData['id'];

            $staff_name = $UserData['prefix'] . $UserData['name'];

            $duration_type = $UserData['salary_duration_type'];

            $salary_percentage = $UserData['salary_percentage'];

            $revenue_count = GetEntryCounts("SELECT id FROM macho_revenue ");

            for ($i = 1; $i < $revenue_count; $i++) {

                $Query = "SELECT id FROM macho_staff_revenue WHERE user_id='$user_id'";

                $count = GetEntryCounts($Query);

                if ($count == 0) {

                    $revenue_date = GetEntryDate($UserData['service_from'], $duration_type);

                    $duration = round((strtotime($revenue_date) - strtotime($UserData['service_from'])) / (60 * 60 * 24));

                    $profit_amount = OrgProfit($UserData['service_from'], $revenue_date);

                    $revenue = $profit_amount * ($salary_percentage / 100);

                    $one_day_revenue = $revenue / 30;

                    $revenue_amount = ConvertMoneyFormat($duration * $one_day_revenue);

                } else {

                    $MaxID = MaxId('macho_staff_revenue', 'user_id', $user_id);

                    $RunQuery = "SELECT revenue_date FROM macho_staff_revenue WHERE id='$MaxID'";

                    $result = mysqli_query($GLOBALS['conn'], $RunQuery) or die(mysqli_error($GLOBALS['conn']));

                    $row = mysqli_fetch_assoc($result);

                    $revenue_date = GetEntryDate($row['revenue_date'], $duration_type);

                    $profit_amount = OrgProfit($row['revenue_date'], $revenue_date);

                    $revenue = $profit_amount * ($salary_percentage / 100);

                    $revenue_amount = ConvertMoneyFormat($revenue);
                }


                if ($revenue_amount < 0) {
                    $revenue_amount = 0;
                }

                $description = $staff_name . ' Share amount Rs. ' . $revenue_amount . ' on ' . $revenue_date;

                $entry_counts = GetEntryCounts("SELECT id FROM macho_staff_revenue WHERE revenue_date='$revenue_date'");

                if ($entry_counts == 0) {
                    Insert(
                        'macho_staff_revenue',
                        array(
                            'user_id' => $user_id,
                            'revenue_date' => to_sql_date($revenue_date),
                            'description' => $description,
                            'amount' => $revenue_amount,
                            'created' => to_sql_date($today),
                        )
                    );
                }

            }
        }
    }
}

function GetPurchaseAmount($purchase_rate, $purchase_tax_percentage)
{
    $Data = array();

    $purchase_net_amount = $purchase_rate * (100 / (100 + $purchase_tax_percentage));
    $purchase_tax_amount = $purchase_rate - $purchase_net_amount;

    $Data['purchase_tax_amount'] = ConvertMoneyFormat($purchase_tax_amount);
    $Data['purchase_net_amount'] = ConvertMoneyFormat($purchase_net_amount);
    return $Data;
}

function GetSalesAmount($sales_rate, $sales_tax_percentage)
{
    $Data = array();

    $sales_net_amount = $sales_rate * (100 / (100 + $sales_tax_percentage));
    $sales_tax_amount = $sales_rate - $sales_net_amount;

    $Data['sales_tax_amount'] = ConvertMoneyFormat($sales_tax_amount);
    $Data['sales_net_amount'] = ConvertMoneyFormat($sales_net_amount);
    return $Data;
}

//function is_connected() {
//    $connected = @fsockopen("www.google.com", 80); //website, port  (try 80 or 443)
//    if ($connected){
//        fclose($connected);
//        return true;
//    }
//    return false;
//}

function GetBillPurchaseAmount($bill_id)
{
    $bill_total = 0;
    $BillingQuery = "SELECT a.item_id,a.qty,b.rate FROM macho_customer_bill_items a,macho_products b WHERE a.bill_id='$bill_id' AND b.id=a.item_id ORDER BY a.id DESC";
    $BillingResult = GetAllRows($BillingQuery);
    $BillingCounts = count($BillingResult);
    if ($BillingCounts > 0) {
        foreach ($BillingResult as $BillingData) {
            $purchase_rate = $BillingData['rate'] * $BillingData['qty'];
            $bill_total = $bill_total + $purchase_rate;
        }
    }

    // $BillingQuery2 = "SELECT a.id,b.product_id,b.replaced_quantity,c.purchase_rate FROM macho_sales_return a,macho_sales_return_items b,macho_products c WHERE a.bill_id='$bill_id' AND b.sales_return_id=a.id AND c.id=b.product_id ORDER BY b.product_id DESC";
    // $BillingResult2 = GetAllRows($BillingQuery2);
    // $BillingCounts2 = count($BillingResult2);
    // if ($BillingCounts2 > 0) {
    //     foreach ($BillingResult2 as $BillingData2) {
    //         $purchase_rate = $BillingData2['purchase_rate'] * $BillingData2['replaced_quantity'];
    //         $bill_return_total = $bill_return_total + $purchase_rate;
    //     }
    // }
    $purchase_amount = $bill_total;
    return $purchase_amount;
}

function GetSalesReturnQty($bill_id, $product_id)
{
    $replaced_quantity = 0;
    $Sql = "select a.id,b.replaced_quantity from macho_sales_return a,macho_sales_return_items b WHERE a.bill_id='$bill_id' AND b.sales_return_id=a.id AND b.product_id='$product_id'";
    $Result = mysqli_query($GLOBALS['conn'], $Sql) or die(mysqli_error($GLOBALS['conn']));
    $Count = mysqli_num_rows($Result);
    if ($Count == 0) {
        $replaced_quantity = 0;
    } else {
        foreach ($Result as $Data) {
            $replaced_quantity = $replaced_quantity + $Data['replaced_quantity'];
        }
    }
    return $replaced_quantity;
}

function GetPurchaseReturnQty($po_id)
{
    $replaced_quantity = 0;
    $Sql = "select a.id,b.replaced_quantity from macho_purchase_return a,macho_purchase_return_items b WHERE a.purchase_id='$po_id' AND b.purchase_return_id=a.id ";
    $Result = mysqli_query($GLOBALS['conn'], $Sql) or die(mysqli_error($GLOBALS['conn']));
    $Count = mysqli_num_rows($Result);
    if ($Count == 0) {
        $replaced_quantity = 0;
    } else {
        foreach ($Result as $Data) {
            $replaced_quantity = $replaced_quantity + $Data['replaced_quantity'];
        }
    }
    return $replaced_quantity;
}

function GetCustomerPaidAmount($CustomerId, $BillId, $bill_type)
{
    $PaidAmount = 0;
    $BillQuery = "SELECT amount FROM macho_customer_payments WHERE customer_id = '$CustomerId' AND bill_id='$BillId' AND bill_type='$bill_type' AND status=1";
    $BillResult = GetAllRows($BillQuery);
    foreach ($BillResult as $BillData) {
        $PaidAmount = $PaidAmount + $BillData['amount'];
    }
    $PaidAmount = ($PaidAmount);
    return $PaidAmount;
}

function GetCustomerBillPendingCount($CustomerId)
{
    $Invoice_sql = "SELECT * FROM patient_entry WHERE patient_id='$CustomerId' AND bill_status='0'";
    $Invoice_result = mysqli_query($GLOBALS['conn'], $Invoice_sql) or die(mysqli_error($GLOBALS['conn']));
    $Invoice_count = mysqli_num_rows($Invoice_result);

    $Invoice_sql2 = "SELECT * FROM macho_billing WHERE patient_id='$CustomerId' AND bill_status='0'";
    $Invoice_result2 = mysqli_query($GLOBALS['conn'], $Invoice_sql2) or die(mysqli_error($GLOBALS['conn']));
    $Invoice_count2 = mysqli_num_rows($Invoice_result2);

    $Invoice_count = $Invoice_count + $Invoice_count2;

    return $Invoice_count;
}

function GetPurchasePendingCount($SupplierId)
{
    $Purchase_sql = "SELECT * FROM macho_purchase WHERE supplier_id='$SupplierId' AND status='1' AND bill_status='0'";
    $Purchase_result = mysqli_query($GLOBALS['conn'], $Purchase_sql) or die(mysqli_error($GLOBALS['conn']));
    $Purchase_count = mysqli_num_rows($Purchase_result);
    return $Purchase_count;
}

function AccountName($AccountID)
{
    $sql = "SELECT name FROM macho_account WHERE id ='$AccountID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    return $data['name'];
}

function BankDeposit($from_date, $to_date)
{
    $values = array();
    $income = 0;
    $expense = 0;
    $AccountID = '9';
    $FinanceQuery = "SELECT * FROM macho_revenue WHERE (account_id='$AccountID' OR saving_account='$AccountID') AND entry_date>='$from_date' AND entry_date<='$to_date' ORDER BY entry_date DESC ";
    $FinanceResult = GetAllRows($FinanceQuery);
    foreach ($FinanceResult as $FinanceData) {
        if ($FinanceData['type'] == 'Income') {
            $income = $income + $FinanceData['amount'];
        } else {
            $expense = $expense + $FinanceData['amount'];
        }
    }

    $values['IncomeAmount'] = $income;
    $values['ExpenseAmount'] = $expense;
    $values['BalanceAmount'] = $income - $expense;
    return $values;
}

function CashonHand($from_date, $to_date)
{
    $income = 0;
    $expense = 0;
    $AccountArray = array('1', '2', '3', '4', '5', '6', '7', '8', '10', '11');
    foreach ($AccountArray as $AccountID) {
        $FinanceQuery = "SELECT * FROM  macho_revenue WHERE account_id='$AccountID' AND entry_date>='$from_date' AND entry_date<='$to_date' AND saving_account='12' ORDER BY entry_date DESC ";
        $FinanceResult = GetAllRows($FinanceQuery);
        foreach ($FinanceResult as $FinanceData) {
            if ($FinanceData['type'] == 'Income') {
                $income = $income + $FinanceData['amount'];
            } else {
                $expense = $expense + $FinanceData['amount'];
            }
        }
    }

    $cash_in_hand = $income - $expense;
    return $cash_in_hand;
}

function InvestmentAmount($from_date, $to_date)
{
    $income = 0;
    $expense = 0;
    $FinanceQuery = "SELECT * FROM  macho_revenue WHERE account_id='1' AND entry_date>='$from_date' AND entry_date<='$to_date' ORDER BY entry_date DESC ";
    $FinanceResult = GetAllRows($FinanceQuery);
    foreach ($FinanceResult as $FinanceData) {
        if ($FinanceData['type'] == 'Income') {
            $income = $income + $FinanceData['amount'];
        } else {
            $expense = $expense + $FinanceData['amount'];
        }
    }

    $profit = $income - $expense;
    return $profit;
}

function GetBillID()
{
    $sql = "SELECT Max(id) as bill_id FROM macho_billing ";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $counts = $data['bill_id'];
    $bill_id = $counts + 1;
    return $bill_id;
}

function GetReceivedPurchaseQty($purchase_id, $po_row_id)
{
    $received_quantity = 0;

    $Sql = "SELECT received_quantity FROM macho_grn_items WHERE purchase_id='$purchase_id' AND po_row_id='$po_row_id'";
    $Result = mysqli_query($GLOBALS['conn'], $Sql) or die(mysqli_error($GLOBALS['conn']));
    $Count = mysqli_num_rows($Result);
    if ($Count == 0) {
        $received_quantity = 0;
    } else {
        foreach ($Result as $Data) {
            $received_quantity = $received_quantity + $Data['received_quantity'];
        }
    }

    return $received_quantity;
}

function CustomerAccountPayment($CustomerId)
{
    $values = array();
    $TotalCreditAmount = 0;
    $TotalUnCreditAmount = 0;
    $TotalDebitAmount = 0;
    $TotalOpeningBalance = 0;

    $Query = "SELECT id,type,amount,status FROM macho_customer_payments WHERE customer_id='$CustomerId' ORDER BY id DESC ";
    $Result = GetAllRows($Query);
    $Counts = count($Result);
    if ($Counts > 0) {
        foreach ($Result as $Data) {
            if ($Data['type'] == 'Credit') {
                if ($Data['status'] == '1') {
                    $TotalCreditAmount = $TotalCreditAmount + $Data['amount'];
                } else {
                    $TotalUnCreditAmount = $TotalUnCreditAmount + $Data['amount'];
                }
            } elseif ($Data['type'] == 'OpeningBalance') {
                $TotalOpeningBalance = $TotalOpeningBalance + $Data['amount'];
            } else {
                $TotalDebitAmount = $TotalDebitAmount + $Data['amount'];
            }
        }
    }
    $values['CreditAmount'] = $TotalCreditAmount;
    $values['UnCreditAmount'] = $TotalUnCreditAmount;
    $values['DebitAmount'] = $TotalDebitAmount;
    $values['OpeningBalance'] = $TotalOpeningBalance;
    $values['BalanceAmount'] = $TotalCreditAmount - $TotalDebitAmount;
    return $values;
}

function CustomerBillNo($BillID)
{
    $sql = "SELECT bill_no FROM patient_entry WHERE id='$BillID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $bill_no = $data['bill_no'];
    return $bill_no;
}

function PatientBillNo($BillID)
{
    $sql = "SELECT billnum FROM macho_billing WHERE id='$BillID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $data = mysqli_fetch_assoc($result);
    $bill_no = $data['billnum'];
    return $bill_no;
}