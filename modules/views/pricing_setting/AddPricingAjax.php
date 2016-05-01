<?php

include "../../common/top_ajax.php";
$action = $_REQUEST['action'];
$db_table = $_REQUEST['table'];
$row_id = explode('_', $db_table);
if ($action == "Insert") {
    $title = $_REQUEST['title'];
    $cat_id = $_REQUEST['cat'];
    $_REQUESTf = array();
    $_REQUESTf['title'] = $title;

    $_REQUESTf['action'] = $action;
    $Savecols = array('title');
    $save_ob = new saveform($db_table, $_REQUESTf, $Savecols, "id", $order_field, $map_field, '', false, false);
    $insert_id = $save_ob->getInsertId();

    if ($insert_id != "") {
        $row = "<tr id='" . $row_id[2] . '_' . $insert_id . "'><td><input id='title' name='title' value='" . $title . "' type='text' required='' size='' class=' form-control' readonly='readonly'></td><td><a href='javascript:;' data-id='" . $insert_id . "' data-table='" . $db_table . "' class='editPricingSetting'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></td><td><a href='javascript:;' data-id='" . $insert_id . "' data-table='" . $db_table . "' class='DeletePricingSetting'><i class='fa fa-times' aria-hidden='true' ></i></a></td></tr>";
        echo json_encode($row);
    }
} elseif ($action == "Edit") {
    $id = $_REQUEST['id'];
    $title = $_REQUEST['title'];
    $_REQUESTf = array();
    $_REQUESTf['id'] = $id;
    $_REQUESTf['title'] = $title;
    $_REQUESTf['action'] = $action;

    $Savecols = array('title');
    $save_ob = new saveform($db_table, $_REQUESTf, $Savecols, "id", $order_field, $map_field, '', false, false);
    echo json_encode($id);
} elseif ($action == "Delete") {
    $id = $_REQUEST['id'];
    $title = $_REQUEST['title'];
    $_REQUESTf = array();
    $_REQUESTf['id'] = $id;
    $_REQUESTf['title'] = $title;
    $_REQUESTf['action'] = $action;
    $Savecols = array('title');
    $where = "";
    if ($db_table == "pro_price_duration") {
        $where.= "`duration_ids` like '%$id,%'";
    } elseif ($db_table == "pro_price_type") {
        $where.= "`type_id`='$id'";
    } elseif ($db_table == "pro_price_units") {
        $where.= "`unit_id`='$id'";
    } elseif ($db_table == "pro_price_groups") {
        $where.= "`group_ids` like '%$id,%'";
    }
    $check_id = $fpdo->from('product_dynamic_price')->where("$where")->fetch();

    if ($check_id['id'] != "") {
        $message = "Sorry you can not delete this Duration because it's related with products pricing list";
        echo json_encode(array(0, $message));
    } else {

        $query = $fpdo->deleteFrom($db_table)->where("id='$id'")->execute();
        echo json_encode(array(1, $id));
    }
}
