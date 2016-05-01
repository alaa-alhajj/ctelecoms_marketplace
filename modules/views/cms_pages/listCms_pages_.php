<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';
if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_table,$_REQUEST,$cols);
    $utils->redirect($pageList);
}

$listTable = $voiControl->ObListTable();
$listTable->_table($db_table);
$listTable->_columns(array('id','title'));


$listTable->_class('table table-striped');
$listTable->_edit($pageUpdate, array("id"));
$listTable->setOrderBy("id");
$listTable->_special(false);
$listTable->_active(true);
$listTable->setFilter(array(array("title", "text")));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");


$ob_roles->getEditRole($grp_id,$listTable,$module_id,$pageUpdate);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);




echo $utils->make_tag_html($listTable->FilterTable(), 'div', 'Filter');
echo $utils->make_tag_html($ob_roles->getCmsBtn($grp_id,$module_id,$pageInsert),'div','container-button');
echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id),'div','form-itemdetails');
echo $utils->make_tag_html($listTable->create_pagination($listTable->getCount(),$pn,$LPP,$pageList."?pn=^"),'div','col-sm-12');

include_once '../../common/footer.php';
?>