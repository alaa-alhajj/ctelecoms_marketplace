<?php

include "../../common/top_ajax.php";
$action = $_REQUEST['action'];
include '../../views/required_fields/config.php';
$db_table_feature = "customer_fields";
if ($action == "Insert") {

    $title = $_REQUEST['title'];

    $_REQUESTf = array();
    $_REQUESTf['title'] = $title;

    $_REQUESTf['action'] = $action;
    $Savecols = array('title');
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
       
 $row.="<tr id='f_" . $insert_id . "'>
	<td><input id='title' name='title' value='" . $title . "' type='text' required='' size='' class=' form-control' readonly='readonly'></td>
	<td data-id='" . $insert_id . "'>" . $field_ob->getField() ."<span id='sp".$insert_id."'>". $get_value_type['type'] . "</span></td>
     <td id='plus_" . $insert_id . "'>" . $get_value_type['plus'] . '<input id="plus" name="" value="" type="tags" size="" class="TagsInput  tagit-hidden-field" placeholder="" style="display: none;"><ul class="tagit tagit' . $insert_id . ' ui-widget ui-widget-content ui-corner-all" style="display: none;"><li class="tagit-new"><input type="text" class="ui-widget-content tags_' . $insert_id . ' ui-autocomplete-input" autocomplete="off"></li></ul>'.'</td>';
	  $row.="<td><a href='javascript:;' data-id='" . $insert_id . "' class='editCustomerField' data-ajax='../../views/ajax/AddCustomerFieldAjax.php'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></td>
	<td><a href='javascript:;' data-id='" . $insert_id . "' class='DeleteCustomerField' data-ajax='../../views/ajax/AddCustomerFieldAjax.php'><i class='fa fa-times' aria-hidden='true' ></i></a></td></tr>";

	
	
       /* $row = "<tr id='f_" . $insert_id . "' data-id='" . $insert_id . "' class='Fieldtr'><td>"
                . "
        <input id='title' name='title' value='" . $title . "' type='text' required='' size='' class=' form-control ' readonly='readonly'>"
                . "</td><td><a href='javascript:;' data-id='" . $insert_id . "' class='editCustomerField' data-ajax='../../views/ajax/AddCustomerFieldAjax.php'>"
                . "<i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>"
                . "</td>"
                . "<td><a href='javascript:;' data-id='" . $title . "' class='DeleteCustomerField' data-ajax='../../views/ajax/AddCustomerFieldAjax.php'>"
                . "<i class='fa fa-times' aria-hidden='true' ></i></a>"
                . "</td>"
                . "</tr>";*/
        echo json_encode($row);
    }
} elseif ($action == "Edit") {
    $id = $_REQUEST['id'];
    $title = $_REQUEST['title']; $type = $_REQUEST['type'];
    $plus = $_REQUEST['plus'];
    $_REQUESTf = array();
    $_REQUESTf['id'] = $id;
    $_REQUESTf['title'] = $title;
     $_REQUESTf['type'] = $type;
    $_REQUESTf['action'] = $action;
	$_REQUESTf['plus'] = $plus;

    $Savecols = array('title','type','plus');
    $save_ob = new saveform($db_table_feature, $_REQUESTf, $Savecols, "id", $order_field, $map_field, '', false, false);
    $get_values=$fpdo->from($db_table_feature)->where('id',$id)->fetch();
       if ($get_values['type'] == 'DynamicSelect') {
        $fileds = $fpdo->from('cms_module_fields')->where("table_id='" . $get_values['plus'] . "' and is_main='1'")->fetch();
        $module = $fpdo->from('cms_modules')->where('id', $get_values['plus'])->fetch();
        $table = $module['title'];

        $plus2 = $table;
    }else{
        $plus2=str_replace('×', '', $get_values['plus']);
    }
    echo json_encode(array($get_values['type'],$plus2));
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
