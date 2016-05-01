<?php

include '../../common/header.php';
include 'config.php';

$textjs = new textext_js();
echo $textjs->get_header();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols, "id", $order_field, $map_field);
    $utils->redirect($pageList);
}

$form = new GenerateFormField();
$values = $fpdo->from($db_table)->where('id', $_REQUEST['id'])->fetch();

$form->setColumns($cols);
$form->setTypes($types);
$form->setValues($values);
$form->setRequireds($required);
$form->setExtendTables($source);
$form->setExtra($plus);
$form->setClasses('');
$form->setCountCell(1);
$form->setCellClassWithCount('col-sm-12');
echo $ob_roles->getUpdateRole($grp_id, $form, $module_id);

include_once '../../common/footer.php';
?>