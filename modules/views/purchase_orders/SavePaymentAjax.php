<?php

include "../../common/top.php";
include 'config.php';

$id = $_REQUEST['id'];
$check_payment=$fpdo->from('payments')->where("po_id",$id)->fetch();
if($check_payment['id']!=""){
    $fpdo->update($dbtable_payment)->set(array('value' => $_REQUEST['value']))->where('id', $check_payment['id'])->execute();

}else{
    $query = $fpdo->insertInto($dbtable_payment)->values(array('`po_id`' => $id, '`type`' => '1', '`value`' => $_REQUEST['value']))->execute();
}

echo json_encode($id);
