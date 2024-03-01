<?php
include_once 'booster/bridge.php';
$user_id = $_REQUEST['user_id'];
$user_role_id = GetRoleOfUser($user_id);
$today = date("Y-m-d");
$previous = date('Y-m-d', strtotime('-3 day', strtotime($today)));
$NotificationSql = "SELECT * FROM macho_notifications WHERE receive_role_id='$user_role_id' AND receive_id='$user_id' AND created>='$previous' AND created<='$today' ORDER BY id DESC";
$NotificationResult = GetAllRows($NotificationSql);

foreach ($NotificationResult as $NotificationData) {
    $SenderData = UserInfo($NotificationData['sender_id']);
    $sender_name = $SenderData['name'];
    ?>
    <div class="list-group-item list-group-item-action">
        <div class="media">
            <div class="align-self-start mr-2">
                <em class="fa fa-envelope fa-2x text-warning"></em>
            </div>
            <div class="media-body">
                <p class="m-0"><?= $NotificationData['notes']; ?></p>

                <p class="m-0 text-muted text-sm"><?= TimeAgo($NotificationData['date_time']); ?></p>
            </div>
        </div>
    </div>
<?php } ?>
<div class="list-group-item list-group-item-action">
                                              <span class="d-flex align-items-center">
                                                 <span class="text-sm" onclick="location.href = 'UserNotification';">More notifications</span>
                                                 <span
                                                     class="badge badge-danger ml-auto"><?= GetNotificationCount($user_id, $user_role_id); ?></span>
                                              </span>
</div>