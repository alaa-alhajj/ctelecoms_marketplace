<?php

include "../../common/top_ajax.php";
@session_start();
$table = $_REQUEST['table'];

$id_field = $_REQUEST['id_field'];
$display_field = $_REQUEST['display_field'];

if ($table != "" && $id_field != "" && $display_field != "") {
    $array_json = array();

    $query=$fpdo->from($table)->where($where)->fetchAll();
  
    $num = count($query);
    if ($num > 0) {
        $i = 0;
        foreach ($query as $row) {
            
            $display=$row[$display_field];
             array_push($array_json, $display);
        }       
    }
    echo json_encode($array_json);
}

