<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Roles
 *
 * @author Ahmad mahmoud
 */
class roles extends utils {

    protected $fpdo;

    function __construct() {
        global $fpdo;
        $this->fpdo = & $fpdo;
    }

    public function listRoles($grp_id) {
        $query = $this->fpdo->from('cms_modules')->where(" publish=1")->fetchAll();
        $result = "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";

        foreach ($query as $row) {
            $result.="<div class='module_title'>" . $row['title'] . "</div>";
            $module_id = $row['id'];
            $query2 = $this->fpdo->from('cms_module_roles')->where(" module_id='$module_id'")->fetchAll();
            foreach ($query2 as $row2) {
                $role_id = $row2['id'];
                $role_selected = $this->fpdo->from('cms_module_permissions')->select('id')->where(" role_id='$role_id' and grp_id='$grp_id' ")->fetch();

                if ($role_selected['id']) {
                    $check_class = 'checked';
                } else {
                    $check_class = '';
                }
                $result .="<div class='role-row'><input type='checkbox' $check_class value='" . $role_id . "' name='role_id[]' />&nbsp;" . $row2['role'] . "</div>";
            }
        }

        $result .= "<input type='hidden' name='grp_id' value='$grp_id'/>";
        $result .= "<input type='hidden' name='action' value='save'/>";
        $result .= "<input type='submit' value='Save'/>";
        $result .= "</form>";

        return $result;
    }

    public function getActivModules() {
        $query = $this->fpdo->from('cms_modules')->where("`publish` = 1")->fetchAll();
        $return = array();
        foreach ($query as $row) {
            array_push($return, $row['id']);
        }
        return implode(",", $return);
    }

    public function getUserMenu($grp_id, $module_id) {
        $result = '';

        $user_roles = $this->getUserRoles($grp_id, 0);
        $query = $this->fpdo->from('cms_module_roles')->where("id in ($user_roles) and role='list' ")->fetchAll();

        foreach ($query as $row) {
            $module_id = $row['module_id'];
            $module_title = $this->lookupField('cms_modules', 'id', 'title', $module_id);
            $static_module = $this->lookupField('cms_modules', 'id', 'is_static', $module_id);
            $static_module_link = $this->lookupField('cms_modules', 'id', 'static_path', $module_id);

            if ($static_module) {
                $module_link = '../../views/' . $static_module_link . '?cmsMID=' . $module_id;
            } else {
                $module_link = '../../views/cms_modules/listModules.php?cmsMID=' . $module_id;
            }

            $result.="  <li>
						  <a href='$module_link'>
							<span>$module_title</span> 
						  </a>
						</li>";
        }
        return $result;
    }

    public function getUserRoles($grp_id, $module_id, $role = '') {

        $active_modules = $this->getActivModules();
        $grp_where_stm = "grp_id='$grp_id'";
        $return = array();

        //**If module_id is passed get this module user roles ids else get all user roles ids for all active modules**//
        if ($module_id) {
            $active_modules = $module_id;
            $module_where_stm = " and module_id = '$module_id' ";
        }

        //**This is applied when you need a specific role**//
        if ($role) {
            $role_stm = " and role='$role' ";
        }

        if ($grp_id != 1 && $grp_id != 2) {

            $query = $this->fpdo->from('cms_module_permissions')->where("$grp_where_stm")->fetchAll();

            foreach ($query as $row) {

                $role_id = $this->fpdo->from('cms_module_roles')->where(" id='" . $row['role_id'] . "' $module_where_stm $role_stm ")->fetch();
                if ($role_id['id']) {
                    array_push($return, $role_id['id']);
                }
                echo $$role_id['id'];
            }
        } elseif ($grp_id == 1 || $grp_id == 2) {

            if ($grp_id == 1) {
                $where_stm = "1=1";
            } else {

                $where_stm = " module_id in($active_modules) ";
            }
            $query = $this->fpdo->from('cms_module_roles')->where(" $where_stm ")->fetchAll();
            foreach ($query as $row) {

                $role_id = $row['id'];
                if ($role_id) {
                    array_push($return, $role_id);
                }
            }
        }


        return implode(",", $return);
    }

    public function getDeleteRole($grp_id, $object, $module_id) {
        $role_id = $this->getUserRoles($grp_id, $module_id, 'delete');
        if ($role_id) {
            return $object->_delete(true);
        } else {
            return $object->_delete(false);
        }
    }

    public function getEditRole($grp_id, $object, $module_id, $editPage = '') {
        $role_id = $this->getUserRoles($grp_id, $module_id, 'update');
        if ($role_id) {
            if ($editPage) {
                return $object->_edit($editPage, array('id'));
            } else {
                return $object->_edit(true);
            }
        } else {
            return $object->_edit(false);
        }
    }

    public function getCmsBtn($grp_id, $module_id, $insertPage = '') {
        $delete_role = $this->getUserRoles($grp_id, $module_id, 'delete');
        $insert_role = $this->getUserRoles($grp_id, $module_id, 'insert');
        $actions = array();
        if ($insert_role) {
            if ($insertPage) {
                array_push($actions, 'Add:' . $insertPage);
            } else {
                array_push($actions, 'Add');
            }
        }
        if ($delete_role) {
            array_push($actions, 'Delete');
        }
        $actions = implode(',', $actions);
        return $this->make_module_btns($actions);
    }

    public function getListRole($grp_id, $object, $module_id) {
        $role_id = $this->getUserRoles($grp_id, $module_id, 'list');
        if ($role_id) {
            return $object->GetListTable();
        } else {
            return '';
        }
    }

    public function getUpdateRole($grp_id, $object, $module_id) {
        $role_id = $this->getUserRoles($grp_id, $module_id, 'update');
        if ($role_id) {
            return $object->getForm('Edit');
        } else {
            return '';
        }
    }

    public function getInsertRole($grp_id, $object, $module_id) {
        $role_id = $this->getUserRoles($grp_id, $module_id, 'insert');
        if ($role_id) {
            return $object->getForm('Insert');
        } else {
            return '';
        }
    }

    public function checkUserLogin() {
        $log = 0;
        if (!isset($_SESSION["enterCMS"]) && $_SESSION["enterCMS"] != 'go') {
            $this->logOut();
        }
    }

    public function login($un, $pass) {
        $where_stm = " username='$un' and password='$pass' ";
        $user = $this->fpdo->from('cms_users')->select('id,grp_id,full_name')->where(" $where_stm ")->fetch();
        $user_id = $user['id'];
        $grp_id = $user['grp_id'];
        $full_name = $user['full_name'];
        if ($user_id) {
            $_SESSION['cms-user-id'] = $user_id;
            $_SESSION['cms-user-full-name'] = $full_name;
            $_SESSION['cms-grp-id'] = $grp_id;
            $_SESSION['enterCMS'] = 'go';
            $this->redirect("views/home/home.php");
        } else {
            return 'Invalid username or password';
        }
    }

    public function logOut() {
        session_destroy();
        header("Location:../../index.php");
        echo "<script>document.location='../../index.php'</script>";
        exit;
    }

}
