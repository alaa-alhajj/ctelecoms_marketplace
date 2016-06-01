<?php
include "../../common/top.php";
include 'config.php';


$values = $fpdo->from($db_table)->where("id='0'")->fetch();

$form = new GenerateFormField();
$form->setColumns($cols_popup);
$form->setTypes($types_popup);
$form->setValues($values);
$form->setExtendTables();
$form->setRequireds($required_popup);
$form->setIdForm('Templatesave');
$form->setCountCell(1);
$form->setSubmit(true);
$form->setBackBtn(false);
$form->setAsForm(true);
$form->setSubMain(array('col-sm-3 ', 'red', 'col-sm-9'));
echo $ob_roles->getInsertRole($grp_id, $form, $module_id);
?>