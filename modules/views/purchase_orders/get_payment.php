<?php
include "../../common/top.php";
include 'config.php';

$id = $_REQUEST['id'];
$values = $fpdo->from($dbtable_payment)->where("po_id='$id'")->fetch();

$form = new GenerateFormField();
$form->setColumns($cols_payment);
$form->setTypes($types_payment);
$form->setValues($values);
$form->setExtendTables($source);
$form->setRequireds($required);
$form->setIdForm('Paymentsave');
$form->setCountCell(1);
$form->setSubmit(true);
$form->setBackBtn(false);
$form->setAsForm(true);
$form->setSubMain(array('col-sm-3 float-pricing', 'red', 'col-sm-9'));
echo $ob_roles->getInsertRole($grp_id, $form, $module_id);
?>