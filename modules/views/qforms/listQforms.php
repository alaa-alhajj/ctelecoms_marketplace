<?php

include '../../common/header.php';
include 'config.php';
include '../../common/pn.php';
define('start_date', 'start date');
if (isset($_REQUEST) && $_REQUEST['action'] == 'Delete') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols);
    $utils->redirect($pageList);
}

echo $utils->lang_switcher();
$listTable = $voiControl->ObListTable();
$listTable->_table($db_table);
$listTable->_columns(array('form_name', 'send_mail_to'));
$listTable->_class('table table-striped');
$listTable->setOrderBy("id");
$listTable->setExtraLinks(array(array('Fields', $utils->icons->ico['list'], $listFields, array('form_id' => 'id'), '')));
$listTable->_special(false);
$listTable->_active(true);
$listTable->setFilter(array(array("title", "text")));
$conditions .= " `lang`='$cmsMlang' ";
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");


$listTable->_PageList($pageList);

$listTable->_PageInsert($pageInsert);
$ob_roles->getEditRole($grp_id, $listTable, $module_id, $pageUpdate);
$ob_roles->getDeleteRole($grp_id, $listTable, $module_id);



echo $utils->make_tag_html($ob_roles->getListRole($grp_id, $listTable, $module_id), 'div', 'form-itemdetails');


include_once '../../common/footer.php';
?>