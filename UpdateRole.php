<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user = $_SESSION["user"];

IsAjaxRequest();
$RoleID = $_POST['role_id'];
$role = $_POST['role'];
$rcode = $_POST['rcode'];
$menu = $_POST['menu_id'];
$is_read = $_POST['is_read'];
$is_write = $_POST['is_write'];
$is_modify = $_POST['is_modify'];
$is_delete = $_POST['is_delete'];

$created = date("Y-m-d H:i:s");
$modified = date("Y-m-d H:i:s");

function CheckMenu($UserID, $MenuID)
{
    $sql = "select menu_id from macho_user_page_acceses WHERE user_id='$UserID' AND menu_id='$MenuID'";
    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
    $count = mysqli_num_rows($result);
    $data = mysqli_fetch_assoc($result);
    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

$update = Update('macho_role', 'id', $RoleID, array(
    'role' => Filter($role),
    'rcode' => Filter($rcode),
    'modified' => $modified
));

if ($update) {
    DeleteRow('macho_role_menu_acceses', 'role_id', $RoleID);

    foreach ($menu as $MenuID) {
        $Read = (isset($is_read[$MenuID]) == 'on') ? 1 : 0;
        $Write = (isset($is_write[$MenuID]) == 'on') ? 1 : 0;
        $Modify = (isset($is_modify[$MenuID]) == 'on') ? 1 : 0;
        $Delete = (isset($is_delete[$MenuID]) == 'on') ? 1 : 0;

        $MenuData = SelectParticularRow('macho_menu', 'id', $MenuID);
        $is_parent = $MenuData['is_parent'];
        if ($is_parent == '0') {
            if (!CheckRoleMenu($RoleID, $MenuID)) {
                Insert('macho_role_menu_acceses', array(
                    'role_id' => $RoleID,
                    'is_parent' => $MenuData['is_parent'],
                    'menu_id' => $MenuData['id'],
                    'menu_icon' => $MenuData['menu_icon'],
                    'menu_name' => $MenuData['menu_name'],
                    'is_dropdown' => $MenuData['is_dropdown'],
                    'menu_url' => $MenuData['menu_url'],
                    'is_write' => $Write,
                    'is_read' => $Read,
                    'is_delete' => $Delete,
                    'is_modify' => $Modify,
                    'created' => $created,
                    'modified' => $modified
                ));
            }
        } else {
            $MainMenuData = SelectParticularRow('macho_menu', 'id', $is_parent);
            $SubMenu_parent = $MainMenuData['is_parent'];
            if ($SubMenu_parent == '0') {
                if (!CheckRoleMenu($RoleID, $is_parent)) {
                    Insert('macho_role_menu_acceses', array(
                        'role_id' => $RoleID,
                        'is_parent' => $MainMenuData['is_parent'],
                        'menu_id' => $MainMenuData['id'],
                        'menu_icon' => $MainMenuData['menu_icon'],
                        'menu_name' => $MainMenuData['menu_name'],
                        'is_dropdown' => $MainMenuData['is_dropdown'],
                        'menu_url' => $MainMenuData['menu_url'],
                        'is_write' => $Write,
                        'is_read' => $Read,
                        'is_delete' => $Delete,
                        'is_modify' => $Modify,
                        'created' => $created,
                        'modified' => $modified
                    ));
                }
            } else {
                $SubMenuData = SelectParticularRow('macho_menu', 'id', $SubMenu_parent);
                if (!CheckRoleMenu($RoleID, $SubMenu_parent)) {
                    Insert('macho_role_menu_acceses', array(
                        'role_id' => $RoleID,
                        'is_parent' => $SubMenuData['is_parent'],
                        'menu_id' => $SubMenuData['id'],
                        'menu_icon' => $SubMenuData['menu_icon'],
                        'menu_name' => $SubMenuData['menu_name'],
                        'is_dropdown' => $SubMenuData['is_dropdown'],
                        'menu_url' => $SubMenuData['menu_url'],
                        'is_write' => $Write,
                        'is_read' => $Read,
                        'is_delete' => $Delete,
                        'is_modify' => $Modify,
                        'created' => $created,
                        'modified' => $modified
                    ));
                }

                if (!CheckRoleMenu($RoleID, $is_parent)) {
                    Insert('macho_role_menu_acceses', array(
                        'role_id' => $RoleID,
                        'is_parent' => $MainMenuData['is_parent'],
                        'menu_id' => $MainMenuData['id'],
                        'menu_icon' => $MainMenuData['menu_icon'],
                        'menu_name' => $MainMenuData['menu_name'],
                        'is_dropdown' => $MainMenuData['is_dropdown'],
                        'menu_url' => $MainMenuData['menu_url'],
                        'is_write' => $Write,
                        'is_read' => $Read,
                        'is_delete' => $Delete,
                        'is_modify' => $Modify,
                        'created' => $created,
                        'modified' => $modified
                    ));
                }
            }

            if (!CheckRoleMenu($RoleID, $MenuID)) {
                Insert('macho_role_menu_acceses', array(
                    'role_id' => $RoleID,
                    'is_parent' => $MenuData['is_parent'],
                    'menu_id' => $MenuData['id'],
                    'menu_icon' => $MenuData['menu_icon'],
                    'menu_name' => $MenuData['menu_name'],
                    'is_dropdown' => $MenuData['is_dropdown'],
                    'menu_url' => $MenuData['menu_url'],
                    'is_write' => $Write,
                    'is_read' => $Read,
                    'is_delete' => $Delete,
                    'is_modify' => $Modify,
                    'created' => $created,
                    'modified' => $modified
                ));
            }
        }
    }

    $is_added = '0';
    $UsersQuery = "SELECT id FROM macho_users WHERE role_id='$RoleID' ORDER BY id ";
    $UsersResult = GetAllRows($UsersQuery);
    foreach ($UsersResult as $UsersData) {
        $UserID = $UsersData['id'];

        $DeleteSql = "DELETE FROM macho_user_page_acceses WHERE user_id = $UserID AND is_added='0'";
        $DeleteResult = mysqli_query($GLOBALS['conn'], $DeleteSql) or die(mysqli_error($GLOBALS['conn']));

        $RoleMenuQuery = "SELECT * FROM macho_role_menu_acceses WHERE role_id='$RoleID' ORDER BY id ";
        $RoleMenuResult = GetAllRows($RoleMenuQuery);
        foreach ($RoleMenuResult as $RoleMenuData) {
            $RoleMenuID = $RoleMenuData['menu_id'];
            $Read = $RoleMenuData['is_read'];;
            $Write = $RoleMenuData['is_write'];
            $Modify = $RoleMenuData['is_modify'];
            $Delete = $RoleMenuData['is_delete'];

            if (CheckMenu($UserID, $RoleMenuID)) {
                //echo "Match found";
                $UpdateSql = "UPDATE macho_user_page_acceses SET is_read='$Read' AND is_write='$Write' AND is_modify='$Modify' AND is_delete='$Delete' AND is_added='$is_added'  WHERE user_id='$UserID' AND menu_id='$RoleMenuID'";
                $UpdateResult = mysqli_query($GLOBALS['conn'], $UpdateSql) or die(mysqli_error($GLOBALS['conn']));
            } else {
                //echo "Match not found";
                Insert('macho_user_page_acceses', array(
                    'user_id' => $UserID,
                    'is_parent' => $RoleMenuData['is_parent'],
                    'menu_id' => $RoleMenuData['menu_id'],
                    'menu_icon' => $RoleMenuData['menu_icon'],
                    'menu_name' => $RoleMenuData['menu_name'],
                    'is_dropdown' => $RoleMenuData['is_dropdown'],
                    'menu_url' => $RoleMenuData['menu_url'],
                    'is_write' => $RoleMenuData['is_write'],
                    'is_read' => $RoleMenuData['is_read'],
                    'is_delete' => $RoleMenuData['is_delete'],
                    'is_modify' => $RoleMenuData['is_modify'],
                    'is_enable' => $RoleMenuData['is_enable'],
                    'is_added' => $is_added,
                    'created' => $created,
                    'modified' => $modified
                ));
            }
        }
    }

    $notes = $role . '  Role Details modified by '.$user;
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

    echo '1';
} else {
    echo 'Role does not Update';
}