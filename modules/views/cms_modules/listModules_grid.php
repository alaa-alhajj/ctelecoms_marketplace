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


if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, $colsUpdate);
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_table);
$listTable->_columns(array('full_name','username','email','grp_id'));
$listTable->_source(array('grp_id'=>array("0"=>"cms_groups","1"=>"id","2"=>"title")));
$listTable->_Types($types);
$listTable->_class('table table-striped');
$listTable->setOrderBy("id");
$listTable->IsGridList(true);
$listTable->_special(false);
$listTable->_active(true);
$listTable->_PageList($pageList);
$listTable->setExtendTables($source);
$listTable->setFilter(array( array("title", "text")));
if($_REQUEST['grp_id']){
    echo $conditions=" grp_id = ".$_REQUEST['grp_id'];
}else{
    $conditions='';
}
$listTable->_condition($conditions);
$listTable->setLimit("$start,$LPP");

$ob_roles->getEditRole($grp_id,$listTable,$module_id);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
$listTable->_PageInsert($pageInsert);
$listTable->_PageList($pageList);

echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id),'div','form-itemdetails');


include_once '../../common/footer.php';
?>