<?php

include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';

if (isset($_REQUEST) && $_REQUEST['action'] == 'Delete') {

    $save_ob = new saveform($db_table, $_REQUEST, $cols);
    $utils->redirect($pageList);
}

$listTable = $voiControl->ObListTable();
$listTable->_table('purchase_order');
$listTable->_columns(array('customer_id', 'order_date', 'payment_type'));

$listTable->_source(array('customer_id' => array('customers', 'id', 'name'),'payment_type' => array('payment_types', 'id', 'name')));
$listTable->_class('table table-striped');
//$listTable->_edit($pageUpdate, array("id"));
$listTable->_delete(false);
$listTable->_Add(false);
$listTable->setOrderBy("id");
$listTable->setExtraLinks(array(array('Details', $utils->icons->ico['list'], 'DetailsOfPurchaseOrder.php', array('id' => 'id'), '')
));
$listTable->_special(false);
$listTable->_active(false);

$listTable->setFilter(array(
    array("customer", "text"),array("order_date", "date"), array("product", "text"), array("From", "date"), array("To", "date")
));

//$listTable->_condition($listTable->getWhereFromFilter($conditions, $conditionsGet));
$count = "";
if ($_REQUEST['customer'] != "" ||$_REQUEST['order_date'] != "" || $_REQUEST['product'] != "" || $_REQUEST['From'] != "" || $_REQUEST['To'] != "") {
    $listTable->_PurchaseOrderSearch(array(end(explode('.', $_REQUEST['customer'])),$_REQUEST['order_date'],end(explode('.', $_REQUEST['product'])), $_REQUEST['From'], $_REQUEST['To'], $_REQUEST['pid']));
}
$listTable->setLimit("$start,$LPP");
$listTable->_PageList($pageList);
$listTable->_PageInsert($pageInsertProduct);
echo $utils->make_tag_html($listTable->GetListTable(), 'div', '');


include_once '../../common/footer.php';
