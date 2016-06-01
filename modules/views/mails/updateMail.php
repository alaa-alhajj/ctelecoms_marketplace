<?php include 'config.php';
include '../../common/header.php';
define("customer_code","Customer");

$textjs = new textext_js();
echo $textjs->get_header();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {

    $save_ob = new saveform($db_table, $_REQUEST, $cols_ins, 'id');
    
    $utils->redirect($pageList);
}



$valuesCountry = array();
$get_product_ids = $fpdo->from($db_table)->where('id', $_REQUEST['id'])->fetch();
$product_ids_array=  explode(',', rtrim($get_product_ids['product_ids'],','));

foreach ($product_ids_array as $c) {
    
    array_push($valuesCountry, $c);
}
$valuesCountry1 = implode(',', $valuesCountry);

$v1 = $textjs->getForma('customers', 'name', 'id', $get_product_ids['customer_id'], true);

$form = new GenerateFormField();
$values = $fpdo->from($db_table)->where('id', $_REQUEST['id'])->fetch();
$form->setColumns($cols_ins);
$form->setTypes($types);
$form->setValues($values);
$form->setRequireds($required);
$form->setExtendTables($source2);
$form->setClasses();



$form->setCountCell(1);
$form->setCellClassWithCount('col-sm-12');
echo $utils->make_tag_html($form->getForm('Edit'), 'div', 'form-itemdetails');
echo $textjs->get_script('#customer_code', '../ajax/AsTags.php', 'customers', 'id', 'name', $v1, 'true');
include_once '../../common/footer.php';
