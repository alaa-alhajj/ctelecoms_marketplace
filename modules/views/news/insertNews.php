<?php
include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols);
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
echo $form->getForm('Insert');

include_once '../../common/footer.php';
