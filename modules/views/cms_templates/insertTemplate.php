<?php
include 'config.php';
include '../../common/header.php';
include 'documentation.php';
?>

<?
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
	
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols);
	$temp_id = $save_ob->getInsertId();
	$t_field = $temp_maker->insertTmplateFields($_REQUEST['main_html'],$_REQUEST['item_html'],$temp_id);
	$t_settings = $temp_maker->insertTemplateSettings($_REQUEST['main_html'],$_REQUEST['item_html'],$temp_id);
	$temp_maker->insertTemplateLabels($_REQUEST['main_html'],$_REQUEST['item_html']);
	
	$utils->redirect($pageList);
}

$form = new GenerateFormField();
$form->setColumns($cols);
$form->setTypes($types);
$form->setExtendTables($source);
$form->setRequireds($required);
$form->setCountCell(1);
$form->setSubmit(true);
$form->setAsForm(true);
echo $ob_roles->getInsertRole($grp_id,$form,$module_id);

include_once '../../common/footer.php';
?>