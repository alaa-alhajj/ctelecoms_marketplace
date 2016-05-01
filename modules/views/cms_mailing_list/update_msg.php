<?php
include 'config.php';
include '../../common/header.php';


$textjs = new textext_js();
echo $textjs->get_header();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_mailing_msg, $_REQUEST, $mcols, 'id');    
    $utils->redirect($pageListMsgs);
}

$form = new GenerateFormField();
$values = $fpdo->from($db_mailing_msg)->where('id', $_REQUEST['id'])->fetch();
$form->setColumns($mcols);
$form->setTypes($mtypes);
$form->setValues($values);
$form->setRequireds();
$form->setExtendTables($m_extend);
$form->setClasses();
$form->setCountCell(1);
$form->setCellClassWithCount('col-sm-12');
echo $ob_roles->getUpdateRole($grp_id,$form,$module_id);

include_once '../../common/footer.php';
?>
