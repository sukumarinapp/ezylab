<?php
include_once 'booster/bridge.php';
$send_id = $_POST['id'];
$otp = GenerateOtp();

$UserData = UserInfo($send_id);
$mobile = $UserData['mobile'];
$url = SITEURL . 'VerifyOTP.php?uId=' . EncodeVariable($send_id);

$sql2 = "UPDATE macho_users SET reset_key='$otp' WHERE id='$send_id'";
$result2 = mysqli_query($GLOBALS['conn'], $sql2) or die(mysqli_error($GLOBALS['conn']));
if ($result2) {
    $email = GetUserEmail($send_id);
    SendEmail($email,  'Password Reset OTP',"We 've Resent this message because you requested that your OTP No : $otp be reset.please visit our site : $url ");
    SendSMS($mobile, "We 've Resent this message because you requested that your OTP No : $otp be reset.please visit our site : $url ");
    echo '1';
} else {
    echo '0';
}