<?php

include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';

$listTable = $voiControl->ObListTable();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {

    $save_ob = new saveform($db_table_Fields, $_REQUEST, $fcolsSave, 'id', 'item_order');
    $utils->redirect($listTable->getLinkBackGridList());
}
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table_Fields, $_REQUEST, $fcols);
    $utils->redirect($listTable->getLinkBackGridList());
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Delete') {
    $save_ob = new saveform($db_table_Fields, $_REQUEST, $fcols, 'id');
    $utils->redirect($pageList);
}

$listTable->_table($db_table_Fields);
$listTable->_columns($fcols);
$listTable->_Types($ftypes);
$listTable->_source($_source);
$listTable->setExtra($extra);
$listTable->setParentAttr(array('form_id'));
$conditions = " `id`!='0' ";
$conditionsGet = array('form_id' => 'form_id');
$listTable->_condition($listTable->getWhereFromFilter($conditions, $conditionsGet));
$listTable->_source($_source);
$listTable->_class('table table-striped');
$listTable->_order(true, 'item_order');
$listTable->IsGridList(true);
$listTable->setOrderBy("item_order asc");
$listTable->_special(false);
$listTable->_active(false);
$listTable->setFilter(array(array("lable", "text")));
$listTable->setLimit("$start,$LPP");
$listTable->setBackBtn($pageBackList);

$ob_roles->getEditRole($grp_id, $listTable, $module_id);

$ob_roles->getDeleteRole($grp_id, $listTable, $module_id);

$listTable->_PageList($listFields);

$listTable->_PageInsert($pageInsert_);

echo $utils->make_tag_html($ob_roles->getListRole($grp_id, $listTable, $module_id), 'div', 'form-itemdetails');

include_once '../../common/footer.php';
?>