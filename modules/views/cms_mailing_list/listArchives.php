<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';
if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_mailing_archive,$_REQUEST,$ma_cols);
    $utils->redirect($pageListArchives);
}

$listTable = $voiControl->ObListTable();
$listTable->_table($db_mailing_archive);
$listTable->_columns($ma_cols);
$listTable->_Types($ma_types);
$listTable->_source($ma_source);
$listTable->setExtendTables($ma_extend);
$listTable->_class('table table-striped');
$listTable->setOrderBy("id DESC");
$listTable->setExtraLinks(array(array('More Details',$utils->icons->ico['list'],'showReports.php',array('archive_id'=>'id'),'')));
$listTable->_special(false);
$listTable->_active(false);

$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
//$ob_roles->getEditRole($grp_id,$listTable,$module_id,$pageUpdateMsg);
$listTable->_PageInsert($pageInsertMsg);
$listTable->_PageList($pageListArchives);
$listTable->setFilter(array(
    array("msg_id", "text")
));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");

echo $utils->make_tag_html($listTable->GetListTable(),'div','form-itemdetails');


include_once '../../common/footer.php';
