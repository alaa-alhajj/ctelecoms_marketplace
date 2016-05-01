<?php
include "../../common/top.php";
include 'config.php';

$page_id = $_REQUEST['page_id'];
$values = $fpdo->from($dbtable_seo)->where("id='$page_id'")->fetch();

$form = new GenerateFormField();
$form->setColumns($cols_seo);
$form->setTypes($types_seo);
$form->setValues($values);
$form->setExtendTables($source);
$form->setRequireds($required);
$form->setIdForm('seosave');
$form->setCountCell(1);
$form->setSubmit(true);
$form->setBackBtn(false);
$form->setAsForm(true);
$form->setSubMain(array('col-sm-3 float-pricing', 'red', 'col-sm-9'));
echo $ob_roles->getInsertRole($grp_id, $form, $module_id);
?>