<?php

include 'config.php';
include '../../common/header.php';


$textjs = new textext_js();
echo $textjs->get_header();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols, 'id');
    if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } else {
        $utils->redirect($pageInsertPhotos . "?id=" . $_REQUEST['id']);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li class="active-menue"><a href="#">Category Info</a></li>
     <li><a href="#">Category Photos</a></li>
    <li><a href="#">Category Features</a></li>
     <li><a href="#">SEO</a></li>
</ul>';
$form = new GenerateFormField();
$values = $fpdo->from($db_table)->where('id', $_REQUEST['id'])->fetch();
$form->setColumns($colsUpdate);
$form->setTypes($types);
$form->setValues($values);
$form->setRequireds();
$form->setExtendTables($source);
$form->setClasses();
$form->setSubmitName('Save & Continue');
$form->setSkipBtn(true, $pageInsertPhotos . "?id=" . $_REQUEST['id']);
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setCountCell(1);
$form->setCellClassWithCount('col-sm-12');
echo $utils->make_tag_html($form->getForm('Edit'), 'div', 'form-itemdetails');

include_once '../../common/footer.php';
