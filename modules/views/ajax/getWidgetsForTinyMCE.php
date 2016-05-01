<?php

include "../../common/top_ajax.php";
$query = $fpdo->from('cms_widgets')->orderBy('title asc')->fetcahAll();
$json=array();
if (count($query) > 0) {
    foreach ($query as $row) {
        $json_item=array("text"=>$row["title"],"value"=>$row["id"]);
        array_push($json, $json_item);
    }
}
echo json_encode($json);