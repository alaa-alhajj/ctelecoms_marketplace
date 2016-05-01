<?php

include "../../common/top_ajax.php";
$id = $_REQUEST['id'];
$selected=$_REQUEST['selected'];
 $query = $fpdo->update('products')->set(array('`customer_req_fields`' => $selected))->where("id='$id'")->execute();


echo json_encode($query);

