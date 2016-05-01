<?php

include "../../common/top_ajax.php";

$table = $_REQUEST['table'];
$id = str_replace(' ', '', $_REQUEST['id']);
$col = $_REQUEST['col'];
$value = $_REQUEST['value'];
$fid = $_REQUEST['fid'];
if ($value == 0 || $value == '0') {
    $newValue = '1';
    $icon = 'fa fa-check';
    $class = 'true';
} elseif ($value == 1 || $value == '1') {
    $newValue = '0';
    $icon = 'fa fa-close';
    $class = 'false';
}
$set = array('active' => '1');

$this2 = $fpdo->update($table, array($col => $newValue))->where($fid, $id)->execute();
echo json_encode(array($newValue, $icon, $class));
