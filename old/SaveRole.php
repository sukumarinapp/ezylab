<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user = $_SESSION["user"];

IsAjaxRequest();
$role = $_POST['role'];
$rcode = $_POST['rcode'];
$menu = $_POST['menu_id'];
$is_read = $_POST['is_read'];
$is_write = $_POST['is_write'];
$is_modify = $_POST['is_modify'];
$is_delete = $_POST['is_delete'];

$created = date("Y-m-d h:i:sa");
$modified = date("Y-m-d h:i:sa");

$RoleSql = "SELECT id FROM macho_role WHERE role = '$role'";
$RoleResult = mysqli_query($GLOBALS['conn'], $RoleSql);
$RoleCount = mysqli_num_rows($RoleResult);
if ($RoleCount > 0) {
    echo 'Role Name Already Exists..';
} else {
    $insert_role = Insert('macho_role', array(
        'role' => Filter($role),
        'rcode' => Filter($rcode),
        'created' => $created,
        'modified' => $modified
    ));

    $RoleID = $insert_role;
    if (is_int($RoleID)) {
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
    } else {
        echo 'Role does not created...';
    }

    $notes = $role . '  Role Details Created by '.$user;
    $receive_id = '1';
    $receive_role_id = GetRoleOfUser($receive_id);
    InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

    echo '1';
}
