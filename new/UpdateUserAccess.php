<?php
session_start();
include_once 'booster/bridge.php';
$user_id = $_SESSION["user_id"];
$role_id = $_SESSION["role_id"];
$user = $_SESSION["user"];

IsAjaxRequest();
$UserID = $_POST['UserID'];

$RoleID = GetRoleOfUser($UserID);
$menu = $_POST['menu_id'];
$is_read = $_POST['is_read'];
$is_write = $_POST['is_write'];
$is_modify = $_POST['is_modify'];
$is_delete = $_POST['is_delete'];

$created = date("Y-m-d H:i:s");
$modified = date("Y-m-d H:i:s");

$ExistMenuID = array();
$Query = "SELECT menu_id FROM macho_role_menu_acceses WHERE role_id='$RoleID' ORDER BY id ";
$Result = GetAllRows($Query);
foreach ($Result as $Data) {
    $ExistMenuID[] = $Data['menu_id'];
}

function CheckMenu($MenuID, $ExistMenuID)
{
    if (in_array($MenuID, $ExistMenuID)) {
        $is_added = '0';
    } else {
        $is_added = '1';
    }
    return $is_added;
}

DeleteRow('macho_user_page_acceses', 'user_id', $UserID);

foreach ($menu as $MenuID) {
    $Read = (isset($is_read[$MenuID]) == 'on') ? 1 : 0;
    $Write = (isset($is_write[$MenuID]) == 'on') ? 1 : 0;
    $Modify = (isset($is_modify[$MenuID]) == 'on') ? 1 : 0;
    $Delete = (isset($is_delete[$MenuID]) == 'on') ? 1 : 0;

    $MenuData = SelectParticularRow('macho_menu', 'id', $MenuID);
    $is_parent = $MenuData['is_parent'];
    if ($is_parent == '0') {
        if (!CheckUserMenu($UserID, $MenuID)) {
            Insert('macho_user_page_acceses', array(
                'user_id' => $UserID,
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
                'is_added' => CheckMenu($MenuData['id'], $ExistMenuID),
                'created' => $created,
                'modified' => $modified
            ));
        }
    } else {
        $MainMenuData = SelectParticularRow('macho_menu', 'id', $is_parent);
        $SubMenu_parent = $MainMenuData['is_parent'];
        if ($SubMenu_parent == '0') {
            if (!CheckUserMenu($UserID, $is_parent)) {
                Insert('macho_user_page_acceses', array(
                    'user_id' => $UserID,
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
                    'is_added' => CheckMenu($MainMenuData['id'], $ExistMenuID),
                    'created' => $created,
                    'modified' => $modified
                ));
            }
        } else {
            $SubMenuData = SelectParticularRow('macho_menu', 'id', $SubMenu_parent);
            if (!CheckUserMenu($UserID, $SubMenu_parent)) {
                Insert('macho_user_page_acceses', array(
                    'user_id' => $UserID,
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
                    'is_added' => CheckMenu($SubMenuData['id'], $ExistMenuID),
                    'created' => $created,
                    'modified' => $modified
                ));
            }

            if (!CheckUserMenu($UserID, $is_parent)) {
                Insert('macho_user_page_acceses', array(
                    'user_id' => $UserID,
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
                    'is_added' => CheckMenu($MainMenuData['id'], $ExistMenuID),
                    'created' => $created,
                    'modified' => $modified
                ));
            }
        }

        if (!CheckUserMenu($UserID, $MenuID)) {
            Insert('macho_user_page_acceses', array(
                'user_id' => $UserID,
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
                'is_added' => CheckMenu($MenuData['id'], $ExistMenuID),
                'created' => $created,
                'modified' => $modified
            ));
        }
    }
}

$notes = UserName($UserID) . '  Role& Permissions Details modified by '.$user;
$receive_id = '1';
$receive_role_id = GetRoleOfUser($receive_id);
InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

echo '1';