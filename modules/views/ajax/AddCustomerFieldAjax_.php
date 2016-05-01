<?php

include "../../common/top_ajax.php";
$action = $_REQUEST['action'];
$db_table_feature = "customer_fields";
if ($action == "Insert") {

    $title = $_REQUEST['title'];

    $_REQUESTf = array();
    $_REQUESTf['title'] = $title;

    $_REQUESTf['action'] = $action;
    $Savecols = array('title');
    $save_ob = new saveform($db_table_feature, $_REQUESTf, $Savecols, "id", $order_field, $map_field, '', false, false);
    $insert_id = $save_ob->getInsertId();

    if ($insert_id != "") {

        $row = "<tr id='f_" . $insert_id . "' data-id='" . $insert_id . "' class='Fieldtr'><td>"
                . "
        <input id='title' name='title' value='" . $title . "' type='text' required='' size='' class=' form-control ' readonly='readonly'>"
                . "</td><td><a href='javascript:;' data-id='" . $insert_id . "' class='editCustomerField' data-ajax='../../views/ajax/AddCustomerFieldAjax.php'>"
                . "<i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>"
                . "</td>"
                . "<td><a href='javascript:;' data-id='" . $title . "' class='DeleteCustomerField' data-ajax='../../views/ajax/AddCustomerFieldAjax.php'>"
                . "<i class='fa fa-times' aria-hidden='true' ></i></a>"
                . "</td>"
                . "</tr>";
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
    $save_ob = new saveform($db_table_feature, $_REQUESTf, $Savecols, "id", $order_field, $map_field, '', false, false);
    echo json_encode($id);
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
