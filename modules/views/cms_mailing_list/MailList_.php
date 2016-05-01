<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';

$listTable = $voiControl->ObListTable();

if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_table,$_REQUEST,$cols);
    $utils->redirect($pageMailing_list);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table,$_REQUEST, $Savecols);
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_table);
$listTable->_columns($cols);
$listTable->_Types($types);
$listTable->_source(array('grp_id'=>array("0"=>"mailing_groups","1"=>"id","2"=>"title_en")));
$listTable->_class('table table-striped');
$listTable->setOrderBy("id");
$listTable->IsGridList(true);
$listTable->setExtendTables($extend);
$listTable->_special(false);
$listTable->_active(true);
$listTable->setFilter(array(array("full_name", "text")));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");


$ob_roles->getEditRole($grp_id,$listTable,$module_id);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
$listTable->_PageList($pageMailing_list);
$listTable->_PageInsert($PageInsert);


echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id),'div','form-itemdetails');



include_once '../../common/footer.php';?>
