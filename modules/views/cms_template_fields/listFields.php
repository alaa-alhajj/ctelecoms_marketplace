<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';

$listTable = $voiControl->ObListTable();


if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols);
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_table);
$listTable->_columns($cols);
$listTable->_Types($types);

$listTable->_class('table table-striped');
$listTable->setOrderBy("id");
$listTable->IsGridList(true);
$listTable->_special(false);
$listTable->_active(false);
$listTable->setExtraLinks(array(array('Field settings',$utils->icons->ico['list'],'listSettings.php',array('field_id'=>'id'),'')));
$listTable->setFilter(array(array("title", "text")));
$conditionsGet=array('template_id'=>'temp_id');
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));

$listTable->setParentAttr(array('temp_id'));

$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
$listTable->_PageList($pageList);
$listTable->setBackBtn('../cms_templates/listTemplates.php');

echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id),'div','form-itemdetails');



include_once '../../common/footer.php';?>
