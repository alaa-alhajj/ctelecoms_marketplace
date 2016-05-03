<?php

include '../../common/header.php';
include 'config.php';
include '../../common/pn.php';

if (isset($_REQUEST) && $_REQUEST['action'] == 'Delete') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols);
    $utils->redirect($pageList);
}


$listTable = $voiControl->ObListTable();
if ($module_lang_type == 'Table') {
    echo $utils->lang_switcher();


    $conditions = " `lang`='$cmsMlang' $where2";
    $listTable->_condition($listTable->getWhereFromFilter($conditions, $conditionsGet));
}
if ($_REQUEST['cmsMID'] === '104' || $_SESSION['cmsMID']==='104') {
    $conditions = " product_id='0'";
    $listTable->_condition($listTable->getWhereFromFilter($conditions, $conditionsGet));
}
$listTable->_table($db_table);
$listTable->_columns($listCols);
$listTable->_Types($types);
$listTable->_source($sourceCols);
$listTable->_seo_page('page_id');
$listTable->_class('table table-striped');
$listTable->_edit($pageUpdate, array("id"));
$listTable->setParentAttr(array('cmsMID'));

if ($_REQUEST['cmsMID'] === '100' || $_SESSION['cmsMID']==='100') {
    $cols=array("name","password","customer_group");
    $listTable->setExtraLinks(array(array('Purchase Orders', $utils->icons->ico['list'], '../purchase_orders/listPurchaseOrder.php', array('customer' => 'name'), '')));
}
$listTable->_dublicate($db_table, 'id', $pageUpdate, $cols, $_SESSION['cmsMID'], 'true');
if ($has_order) {
    $listTable->setOrderBy("item_order DESC");
    $listTable->_order(true, 'item_order');
} else {
    $listTable->setOrderBy("id DESC");
}


$listTable->_special(false);
$listTable->_active(false);
$listTable->setLimit("$start,$LPP");
$listTable->_PageList($pageList);
$listTable->_PageInsert($pageInsert);
$ob_roles->getEditRole($grp_id, $listTable, $module_id, $pageUpdate);
$ob_roles->getDeleteRole($grp_id, $listTable, $module_id);
if ($is_gridlist) {
    $listTable->IsGridList(true);
}



echo $utils->make_tag_html($ob_roles->getListRole($grp_id, $listTable, $module_id), 'div', 'form-itemdetails');


include_once '../../common/footer.php';
?>