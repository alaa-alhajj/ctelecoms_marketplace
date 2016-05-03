<?php

include "../../common/top_ajax.php";
include 'config.php';

$utils = new utils();
$action = $_REQUEST['action'];
$db_table_feature = "product_features";
if ($action == "Insert") {

    $title = $_REQUEST['title'];
    $cat_id = $_REQUEST['cat'];
    $type = $_REQUEST['type'];
    $plus_g = $_REQUEST['plus'];
    $_REQUESTf = array();
    $_REQUESTf['title'] = $title;
    $_REQUESTf['cat_id'] = $cat_id;
    $_REQUESTf['action'] = $action;
    $_REQUESTf['type'] = $type;
    $_REQUESTf['plus'] = $plus_g;
    $Savecols = array('title', 'cat_id', 'type', 'plus');
    $save_ob = new saveform($db_table_feature, $_REQUESTf, $Savecols, "id", $order_field, $map_field, '', false, false);
    $insert_id = $save_ob->getInsertId();


  $get_fe_val = $fpdo->from($db_table_feature)->where("id", $insert_id)->fetch();
    $field_ob = new field();
    $field_ob->SetIdField('type' . $insert_id);
    $field_ob->SetNameField('type');
    $field_ob->SetCssClass('nonedisplay');
    $field_ob->SetTypeField('select');
    $field_ob->SetTable();
    // $field_ob->SetRequiredField($this->requireds[$col]);
    $field_ob->SetTname('type');
    $field_ob->SetTvalue('id');
    $field_ob->SetValueField($get_fe_val['type']);
    $field_ob->setWhere();
    $field_ob->SetExtra($extra);

    if ($insert_id != "") {
      if ($type == 'DynamicSelect') {
        $fileds = $fpdo->from('cms_module_fields')->where("table_id='" . $plus_g . "' and is_main='1'")->fetch();
        $module = $fpdo->from('cms_modules')->where('id', $plus_g)->fetch();
        $table = $module['title'];

        $plus_name = $table;
         $id_table = $plus_g;
    } else {
        $plus_name = str_replace('×', '', $plus_g);
    }
        $get_max_order = $fpdo->from($db_table_feature)->select("max(item_order) as max_ord")->fetch();
        $order_new = $get_max_order['max_ord'] + 1;
        $get_value_type = $fpdo->from($db_table_feature)->where("id", $insert_id)->fetch();
        $fpdo->update($db_table_feature)->set(array('`item_order`' => $order_new))->where("id='$insert_id'")->execute();
        
        
         $row="<tr id='f_" . $insert_id . "'>
	<td><input id='title' name='title' value='" . $title . "' type='text' required='' size='' class=' form-control' readonly='readonly'></td>
	<td data-id='" .$insert_id . "'>" . $field_ob->getField() . "<span id='sp" . $insert_id . "'>" . $get_value_type['type'] . "</span></td>
     <td id='plus_" . $insert_id . "' data-plus='" . $id_table . "'><span >" . $plus_name . '</span><input id="plus" name="" value="' .$plus_name . '" type="tags" size="" class="TagsInput tags_' . $insert_id . '" placeholder="" style="display:none">'
            . '</td>';

    $row.="<td>" . $utils->switcher($db_table_feature, $insert_id, 'is_main', $feature['is_main'], "SwitcherV") . "</td>\n";
    $row.="<td><a href='javascript:;' data-id='" . $insert_id . "' class='editFeature'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></td>
	<td><a href='javascript:;' data-id='" . $insert_id . "' class='DeleteFeature'><i class='fa fa-times' aria-hidden='true' ></i></a></td></tr>";
    
   
        echo json_encode($row);
    }
} elseif ($action == "Edit") {


    $id = $_REQUEST['id'];
    $title = $_REQUEST['title'];
    $type = $_REQUEST['type'];
    $plus = $_REQUEST['plus'];
    $_REQUESTf = array();
    $_REQUESTf['id'] = $id;
    $_REQUESTf['title'] = $title;
    $_REQUESTf['type'] = $type;
    $_REQUESTf['action'] = $action;
    $_REQUESTf['plus'] = $plus;

    $Savecols = array('title', 'type', 'plus');
    $save_ob = new saveform($db_table_feature, $_REQUESTf, $Savecols, "id", $order_field, $map_field, '', false, false);
    $get_values = $fpdo->from($db_table_feature)->where('id', $id)->fetch();
    if ($get_values['type'] == 'DynamicSelect') {
        $fileds = $fpdo->from('cms_module_fields')->where("table_id='" . $get_values['plus'] . "' and is_main='1'")->fetch();
        $module = $fpdo->from('cms_modules')->where('id', $get_values['plus'])->fetch();
        $table = $module['title'];

        $plus = $table;
    } else {
        $plus = str_replace('×', '', $get_values['plus']);
    }
    echo json_encode(array($get_values['type'], $plus));
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
