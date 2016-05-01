<?php include 'config.php';
include '../../common/header.php';


$textjs = new textext_js();
echo $textjs->get_header();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols, 'id');
    
    $utils->redirect($pageList);
}

$form = new GenerateFormField();
$values = $fpdo->from($db_table)->where('id', $_REQUEST['id'])->fetch();
$form->setColumns($colsUpdate);
$form->setTypes($types);
$form->setValues($values);
$form->setRequireds();
$form->setExtendTables($source);
$form->setClasses();
$form->setCountCell(1);
$form->setCellClassWithCount('col-sm-12');
echo $utils->make_tag_html($form->getForm('Edit'), 'div', 'form-itemdetails');

include_once '../../common/footer.php';
