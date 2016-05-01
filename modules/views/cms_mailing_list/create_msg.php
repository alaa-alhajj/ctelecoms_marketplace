<?php
include 'config.php';
include '../../common/header.php';

if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    
    print_r($_REQUEST);
    
    $mailList->createMSG($_REQUEST);
    //$utils->redirect($pageListMsgs);
}

$form = new GenerateFormField();
$form->setColumns($mcols);
$form->setTypes($mtypes);
$form->setExtendTables($m_extend);
$form->setRequireds($required);
$form->setCountCell(1);
$form->setSubmit(true);
$form->setAsForm(true);
echo $ob_roles->getInsertRole($grp_id,$form,$module_id);
include_once '../../common/footer.php';
