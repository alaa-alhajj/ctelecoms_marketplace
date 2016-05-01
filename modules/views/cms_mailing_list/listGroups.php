<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';

$listTable = $voiControl->ObListTable();

if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_mailing_groups,$_REQUEST,$gcols);
    $utils->redirect($pageListGroup);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_mailing_groups, $_REQUEST, $gcols,'id');
    $utils->redirect($listTable->getLinkBackGridList());
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_mailing_groups, $_REQUEST, $gcols);
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_mailing_groups);
$listTable->_columns(array('title_en'));
$listTable->_Types($gtypes);
$listTable->_class('table table-striped');
$listTable->setOrderBy("id");
$listTable->IsGridList(true);
//$listTable->setExtraLinks(array(array('Users',$utils->icons->ico['list'],'../cms_users/listUsers.php',array('grp_id'=>'id'),'')));
$listTable->_special(false);
$listTable->_active(true);
$listTable->setFilter(array(array("title_en", "text")));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");


$ob_roles->getEditRole($grp_id,$listTable,$module_id);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
$listTable->_PageList($pageListGroup);
$listTable->_PageInsert($PageInsert);


echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id),'div','form-itemdetails');



include_once '../../common/footer.php';?>
