<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';


$listTable = $voiControl->ObListTable();

if(isset($_REQUEST) && $_REQUEST['action']=='Delete'){  
    $save_ob=new saveform($db_table_types,$_REQUEST,$tcols);
    $utils->redirect($activities_type);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_table_types, $_REQUEST, $tcols,'id', 'ord');
    $utils->redirect($listTable->getLinkBackGridList());
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table_types, $_REQUEST, $tcols);
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_table_types);
$listTable->_columns(array('id','name_en','name_ar'));
$listTable->_Types($ttypes);
$listTable->_class('table table-striped');
$listTable->setOrderBy("id");
$listTable->IsGridList(true);
$listTable->_special(false);
$listTable->_active(true);


$ob_roles->getEditRole($grp_id,$listTable,$module_id);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);


$listTable->setFilter(array(array("name_en", "text")));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");

echo $utils->make_tag_html($listTable->FilterTable(), 'div', 'Filter');
echo $utils->make_tag_html($ob_roles->getCmsBtn($grp_id,$module_id),'div','container-button');
echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id),'div','form-itemdetails');
echo $utils->make_tag_html($listTable->create_pagination($listTable->getCount(),$pn,$LPP,$pageList."?pn=^"),'div','col-sm-12');

include_once '../../common/footer.php';
?>