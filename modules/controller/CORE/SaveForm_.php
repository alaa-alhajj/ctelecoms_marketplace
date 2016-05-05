<?php

@session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SaveForm
 *
 * @author mohammed
 */
class SaveForm extends utils {

    var $db_table;
    var $request;
    var $columns;
    var $field;
    var $filed_noupdate;
    var $InsertId;
    var $order_field;
    var $orderCondition;
    var $withscript;


    public function getInsertId() {
        return $this->InsertId;
    }

    public function setInsertId($InsertId) {
        $this->InsertId = $InsertId;
    }

    public function setField($field) {
        $this->field = $field;
    }

    public function getField() {
        return $this->field;
    }

    function __construct($db_table, $request, $columns, $field = 'id', $order_field = '', $map_filed = '', $orderCondition = '', $insert_page= true, $withscript = 'true') {
        parent::__construct();
        $this->db_table = $db_table;
        $this->request = $request;
        $this->columns = $columns;
        $this->field = $field;
        $this->order_field = $order_field;
        $this->map_field = $map_filed;
        $this->orderCondition = $orderCondition;
        $this->withAlert = $withscript;
        $this->insertPage = $insert_page;
        if ($this->withAlert == true) {
            echo '<script> waitingDialog.show("Working", {dialogSize: "md", progressType: "danger"});
        </script>';
        }
        if ($this->request['action'] == 'Insert') {
            return $this->_new();
        } elseif ($this->request['action'] == 'Edit') {

            return $this->_update();
        } elseif ($this->request['action'] == 'Delete') {
            return $this->_Delete();
        }
    }

    function _new() {
        $values = array();
        $success = true;
        foreach ($this->columns as $column) {
            if ($column == 'password') {
                $values["`$column`"] = addslashes(md5($this->request[$column]));
            } else {

                if ($this->request[$column] == "") {
                    $v = "";
                } elseif (is_array($this->request[$column]) == true) {
                    $v = implode(',', $this->request[$column]);
                } else {
                    $v = addslashes($this->request[$column]);
                }

                $values["`$column`"] = $v;
            }
        }
        if ($this->order_field != "") {
            $values[$this->order_field] = $this->getNewItemOrder($this->db_table, $this->order_field, $this->orderCondition);
        }
        if ($this->map_field != "") {
            $map_id = $this->fpdo->insertInto('maps')->values(array('lat' => $_REQUEST['lat'], 'lng' => $_REQUEST['lng']))->execute();
            $values["`$this->map_field`"] = $map_id;
        }
        $query = $this->fpdo->insertInto($this->db_table)->values($values);
  
        echo $query->getQuery();die();
        $exec = $this->InsertId = $query->execute();


        if ($exec == true || ( $exec >= 0 && is_int($exec))) {
            $success = true;
        } else {
            $success = false;
        }
        $item_id = $this->InsertId;

        $details_widget_id = $this->lookupField('cms_modules', 'id', 'item_wid_id', '', 'id="' . $_SESSION['cmsMID'] . '"');
        $module_lang_type = $this->lookupField('cms_modules', 'id', 'lang_type', '', 'id="' . $_SESSION['cmsMID'] . '"');
      
        if ($details_widget_id) {

            if ($this->insertPage == true) {

                $page_ids = array();
                $main_field = $this->lookupField('cms_module_fields', 'id', 'title', '', 'table_id="' . $_SESSION['cmsMID'] . '" and is_main="1"');
                $item_title = $this->request[$main_field];
                if ($module_lang_type == 'Field') {

                    foreach ($_SESSION['cms_active_langs'] as $active_lang) {
                        $item_title = $this->request[$main_field . '_' . $active_lang];

                        $insert_values = array('title' => "$item_title",
                            'seo_title' => "$item_title",
                            'module_id' => $_SESSION['cmsMID'],
                            'type' => 'generated',
                            'lang' => $active_lang,
                            'html' => '##wid_start##
										##wid_id_start##' . $details_widget_id . '##wid_id_end##
										##wid_con_start##id=' . $item_id . '##wid_con_end##
										##wid_end##');
                        $query = $this->fpdo->insertInto('cms_pages')->values($insert_values);
                       
                        $page_id = $query->execute();

                        array_push($page_ids, $page_id);
                    }
                } else {

                    $insert_values = array('title' => "$item_title",
                        'seo_title' => "$item_title",
                        'module_id' => $_SESSION['cmsMID'],
                        'type' => 'generated',
                        'lang' => $_SESSION['cmsMlang'],
                        'html' => '##wid_start##
								##wid_id_start##' . $details_widget_id . '##wid_id_end##
								##wid_con_start##id=' . $item_id . '##wid_con_end##
								##wid_end##');
                    $query = $this->fpdo->insertInto('cms_pages')->values($insert_values);
                    $page_id = $query->execute();
                    array_push($page_ids, $page_id);
                }


                $exec = $query = $this->fpdo->update($this->db_table)->set(array('page_id' => implode(',', $page_ids)))->where("id='$item_id'")->execute();
                if ($exec == true || ( $exec >= 0 && is_int($exec))) {
                    $success = true;
                } else {
                    $success = false;
                }
            }
        }
        $message = "";
        @session_start();

        if ($success) {
            $message = $this->getConstant("Success");
            $type = "success";
            $_SESSION['saveFormStatus'] = "success";
        } else {
            $message = $this->getConstant("Faild");
            $type = "error";
            $_SESSION['saveFormStatus'] = "faild";
        }
        if ($this->withAlert == true) {
            echo '<script>waitingDialog.hide();
            swal({
            title: "",
            text: "' . $message . '",
            type: "' . $type . '",
            showConfirmButton: false
            , showConfirmButton: false, timer: 2000
        });
        </script>';
        }
        return true;
    }

    function _update() {
        $values = array();
        $success = false;
        foreach ($this->columns as $column) {
            if ($this->request[$column] == "") {
                $v = '';
            } elseif (is_array($this->request[$column]) == true) {
                $v = implode(',', $this->request[$column]);
            } else {
                $v = addslashes($this->request[$column]);
            }

            $values["`$column`"] = $v;
        }
        if ($this->map_field != "") {
            $map_id = $this->fpdo->insertInto('maps')->values(array('lat' => $_REQUEST['lat'], 'lng' => $_REQUEST['lng']))->execute();
            $values["`$this->map_field`"] = $map_id;
        }

        $page_ids = $this->lookupField($this->db_table, 'id', 'page_id', $this->request[$this->field]);
        $details_widget_id = $this->lookupField('cms_modules', 'id', 'item_wid_id', '', 'id="' . $_SESSION['cmsMID'] . '"');
        $module_lang_type = $this->lookupField('cms_modules', 'id', 'lang_type', '', 'id="' . $_SESSION['cmsMID'] . '"');

        if ($details_widget_id) {
            $main_field = $this->lookupField('cms_module_fields', 'id', 'title', '', 'table_id="' . $_SESSION['cmsMID'] . '" and is_main="1"');
            $item_title = $this->request[$main_field];
            if ($module_lang_type == 'Field') {
                foreach ($_SESSION['cms_active_langs'] as $active_lang) {
                    $item_title = $this->request[$main_field . '_' . $active_lang];
                    $page_id = $this->lookupField('cms_pages', 'id', 'id', '', 'id in(' . $page_ids . ') and lang="' . $active_lang . '" ');

                    $query = $this->fpdo->update('cms_pages')->set(array('title' => $item_title, 'seo_title' => $item_title))->where('id', $page_id);
                    $query->getQuery();
                    $query->execute();
                }
            } else {
                $query = $this->fpdo->update('cms_pages')->set(array('title' => $item_title, 'seo_title' => $item_title))->where('id', $page_ids);
                $query->getQuery();
                $query->execute();
            }
        }

        $query = $this->fpdo->update($this->db_table)->set($values)->where($this->field, $this->request[$this->field]);
        // echo $query->getQuery();die();


        $exec = $query->execute();

        if ($exec == true || ( $exec >= 0 && is_int($exec))) {
            $success = true;
        } else {
            $success = false;
        }
        $message = "";
        @session_start();

        if ($success) {
            $message = $this->getConstant("Success");
            $type = "success";
            $_SESSION['saveFormStatus'] = "success";
        } else {
            $message = $this->getConstant("Faild");
            $type = "error";
            $_SESSION['saveFormStatus'] = "faild";
        }
        if ($this->withAlert == true) {
            echo '<script>waitingDialog.hide();
            swal({
            title: "",
            text: "' . $message . '",
            type: "' . $type . '",
            showConfirmButton: false
            , showConfirmButton: false, timer: 2000
        });
        </script>';
        }

        return true;
    }

    function _Delete() {

        if ($this->request['DeleteRow']) {
            $ids_arr = $this->request['DeleteRow'];
            $ids = implode(',', $this->request['DeleteRow']);
            $query = $this->fpdo->deleteFrom($this->db_table)->where("$this->field in ($ids)");
            //echo $query->getQuery();


            foreach ($ids_arr as $id) {
                $page_id = $this->lookupField($this->db_table, 'id', 'page_id', $id);
                if ($page_id) {
                    $this->fpdo->deleteFrom('cms_pages')->where("id in($page_id)")->execute();
                }
            }

            $exec = $query->execute();

            if ($exec == true || ( $exec >= 0 && is_int($exec))) {
                $success = true;
            } else {
                $success = false;
            }
            @session_start();

            if ($success) {
                $message = $this->getConstant("Success");
                $type = "success";
                $_SESSION['saveFormStatus'] = "success";
            } else {
                $message = $this->getConstant("Faild");
                $type = "error";
                $_SESSION['saveFormStatus'] = "faild";
            }
            if ($this->withAlert == true) {
                echo '<script>waitingDialog.hide();
            swal({
            title: "",
            text: "' . $message . '",
            type: "' . $type . '",
            showConfirmButton: false
            , showConfirmButton: false, timer: 2000
        });
        </script>';
            }
            return true;
        }
    }

}
