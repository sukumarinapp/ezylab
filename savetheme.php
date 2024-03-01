<?php
session_start();
include_once 'booster/bridge.php';

$user_id = $_REQUEST['userid'];
$theme = $_REQUEST['theme'];

        $update = Update(
            'macho_users',
            'id',
            $user_id,
            array(
                'colour' => $theme
            )
        );

?>
