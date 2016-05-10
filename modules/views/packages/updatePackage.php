<?php include 'config.php';
include '../../common/header.php';


$textjs = new textext_js();
echo $textjs->get_header();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols, 'id');
      if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } else {
    $utils->redirect($pageInsertResources."?id=".$_REQUEST['id']);
    }
}
$values = $fpdo->from($db_table)->where('id', $_REQUEST['id'])->fetch();
echo $path = '<ul id="breadcrumbs-one">
    <li class="active-menue"><a href="#">Package Data</a></li>
     <li><a href="">Resources</a></li>
    <li><a href="#">Package Products</a></li>
    <li><a href="#">Features</a></li>
    <li><a href="">Photos</a></li>
    <li><a href="#">Pricing</a></li>
    <li><a href="#">Add-ons</a></li>
    <li><a href="#">Related Products</a></li>
    <li><a href="#">Required Fields</a></li>
    <li><a href="#">FAQ</a></li>
    <li><a href="#">SEO</a></li>
</ul><input type="hidden" id="request_cat_id" value="'.$values['sub_cat_id'].'">';
$form = new GenerateFormField();

$form->setColumns($cols);
$form->setTypes($types);
$form->setValues($values);
$form->setRequireds();
$form->setExtendTables($source);
$form->setClasses();
$form->setSubmitName('Save & Continue');
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setCountCell(1);
$form->setCellClassWithCount('col-sm-12');
echo $utils->make_tag_html($form->getForm('Edit'), 'div', 'form-itemdetails');

include_once '../../common/footer.php';
