<?php
include 'config.php';
include '../../common/header.php';

if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
	$_REQUEST['lang']=$cmsMlang;
	array_push($Savecols,'lang');
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols);
    $utils->redirect($pageList);
}

$form = new GenerateFormField();
$form->setColumns($Savecols);
$form->setTypes($types);
$form->setExtendTables($source);
$form->setRequireds($required);
$form->setCountCell(1);
$form->setSubmit(true);
$form->setAsForm(true);
echo $ob_roles->getInsertRole($grp_id,$form,$module_id);
include_once '../../common/footer.php';
?>