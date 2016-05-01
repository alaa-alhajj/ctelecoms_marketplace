<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';
if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_table,$_REQUEST,$cols);
    $utils->redirect($pageMailing_list);
}

$listTable = $voiControl->ObListTable();
$listTable->_table($db_table);
$listTable->_columns($cols);
$listTable->_source(array('grp_id'=>array("0"=>"mailing_groups","1"=>"id","2"=>"title_en")));
$listTable->_class('table table-striped');
$listTable->setOrderBy("id DESC");
$listTable->_special(false);
$listTable->_active(true);

$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
$ob_roles->getEditRole($grp_id,$listTable,$module_id,$pageUpdate);
$listTable->_PageInsert($pageInsert);
$listTable->_PageList($pageMailing_list);
$listTable->setFilter(array(
    array("full_name", "text")
));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");

echo $utils->make_tag_html($listTable->GetListTable(),'div','form-itemdetails');


include_once '../../common/footer.php';
