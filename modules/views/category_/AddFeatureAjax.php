<?php

include "../../common/top_ajax.php";
include 'config.php';


$action = $_REQUEST['action'];
$db_table_feature = "product_features";
if ($action == "Insert") {

    $title = $_REQUEST['title'];
    $cat_id = $_REQUEST['cat'];
    $_REQUESTf = array();
    $_REQUESTf['title'] = $title;
    $_REQUESTf['cat_id'] = $cat_id;
    $_REQUESTf['action'] = $action;
    $Savecols = array('title', 'cat_id');
    $save_ob = new saveform($db_table_feature, $_REQUESTf, $Savecols, "id", $order_field, $map_field, '', false, false);
    $insert_id = $save_ob->getInsertId();



    $field_ob = new field();
    $field_ob->SetIdField('type' . $insert_id);
    $field_ob->SetNameField('type');
    $field_ob->SetCssClass('nonedisplay');
    $field_ob->SetTypeField('select');
    $field_ob->SetTable();
    // $field_ob->SetRequiredField($this->requireds[$col]);
    $field_ob->SetTname('type');
    $field_ob->SetTvalue('id');
    $field_ob->SetValueField($t_value);
    $field_ob->setWhere();
    $field_ob->SetExtra($extra);

    if ($insert_id != "") {
        $get_value_type = $fpdo->from($db_table_feature)->where("id", $insert_id)->fetch();
        $row = "<tr id='f_" . $insert_id . "'>"
                . "<td><input id='title' name='title' value='" . $title . "' type='text' required='' size='' class=' form-control' readonly='readonly'></td>"
                . "<td>" . $field_ob->getField() ."<span id='sp".$insert_id."'>". $get_value_type['type'] . "</span></td>"
                . '<td>' . $get_value_type['plus'] . "</td>"
                . "<td>" . $get_value_type['is_main'] . "</td>"
                . "<td><a href='javascript:;' data-id='" . $insert_id . "' class='editFeature'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></td>"
                . "<td><a href='javascript:;' data-id='" . $insert_id . "' class='DeleteFeature'><i class='fa fa-times' aria-hidden='true' ></i></a></td></tr>";
        echo json_encode($row);
    }
} elseif ($action == "Edit") {
    $id = $_REQUEST['id'];
    $title = $_REQUEST['title'];
     $type = $_REQUEST['type'];
    $_REQUESTf = array();
    $_REQUESTf['id'] = $id;
    $_REQUESTf['title'] = $title;
     $_REQUESTf['type'] = $type;
    $_REQUESTf['action'] = $action;

    $Savecols = array('title','type');
    $save_ob = new saveform($db_table_feature, $_REQUESTf, $Savecols, "id", $order_field, $map_field, '', false, false);
    $get_values=$fpdo->from($db_table_feature)->where('id',$id)->fetch();
    echo json_encode(array($get_values['type']));
} elseif ($action == "Delete") {
    $id = $_REQUEST['id'];
    $title = $_REQUEST['title'];
    
    $_REQUESTf = array();
    $_REQUESTf['id'] = $id;
    $_REQUESTf['title'] = $title;
    $_REQUESTf['action'] = $action;

    $Savecols = array('title');
    $query = $fpdo->deleteFrom($db_table_feature)->where("id='$id'")->execute();
    echo json_encode($id);
}
