<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';

$listTable = $voiControl->ObListTable();

if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_table,$_REQUEST,$cols);
    $utils->redirect($pageList);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols,'id');
    $utils->redirect($listTable->getLinkBackGridList());
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols);
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_table);
$listTable->_columns(array('title'));
$listTable->_Types($types);

$listTable->_class('table table-striped');
$listTable->setOrderBy("id");
$listTable->IsGridList(true);
$listTable->setExtraLinks(array(array('Users',$utils->icons->ico['list'],'../cms_users/listUsers.php',array('grp_id'=>'id'),'')));
$listTable->setExtraLinks(array(array('Users',$utils->icons->ico['list'],'../cms_roles/listRoles.php',array('grp_id'=>'id'),'')));
$listTable->_special(false);
$listTable->_active(false);
$listTable->setFilter(array(array("title", "text")));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");


$ob_roles->getEditRole($grp_id,$listTable,$module_id);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
$listTable->_PageList($pageList);
$listTable->_PageInsert($PageInsert);


echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id),'div','form-itemdetails');



include_once '../../common/footer.php';?>
