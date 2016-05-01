<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';

if (isset($_REQUEST) && $_REQUEST['action'] == 'Delete') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols);
    $utils->redirect($pageList);
}

$listTable = $voiControl->ObListTable();
$listTable->_table($db_table);
$listTable->_columns(array('title', 'gov_id', 'cat_id'));

$listTable->_source(array("gov_id" => array($db_discover_gov, "id", "name_ar"), "cat_id" => array($db_dicover_category, "id", "title")));
$listTable->_class('table table-striped');
$listTable->_edit($pageUpdate, array("id"));
$listTable->setOrderBy("id desc");
//$listTable->setExtraLinks(array(array('Payments',$utils->icons->ico['list'],'../customer_payments/listCustomer_payments.php',array('customer_id'=>'customer_id'),'')));
$listTable->_special(false);
$listTable->_active(true);

$listTable->setFilter(array(
    array("title", "text")
));
$listTable->_condition($listTable->getWhereFromFilter($conditions, $conditionsGet));
$listTable->setLimit("$start,$LPP");
$listTable->_PageInsert($pageInsert);
$listTable->_PageList($pageList);
$listTable->_ModulesReleated(array(16, 17));

echo $utils->make_tag_html($listTable->GetListTable(), 'div', 'form-itemdetails');

?>

<?
include_once '../../common/footer.php';
