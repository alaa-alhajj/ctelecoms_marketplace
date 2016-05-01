<?php
include '../../common/header.php';
include 'config.php';
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
	if($module_lang_type=='Table'){$_REQUEST['lang']=$cmsMlang;}
    $save_ob = new saveform($db_table, $_REQUEST, $cols,"id",$order_field,$map_field,'false');
    $utils->redirect($pageList);
}

$form = new GenerateFormField();
$form->setColumns($cols);
$form->setTypes($types);
$form->setExtendTables($source);
$form->setRequireds($required);
$form->setExtra($plus);
$form->setCountCell(1);
$form->setSubmit(true);
$form->setAsForm(true);
echo $ob_roles->getInsertRole($grp_id,$form,$module_id);

include_once '../../common/footer.php';
?>