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
$listTable->_columns(array('id','activities_type','title','start_date','end_date'));
$listTable->_source(array('activities_type'=>array("0"=>"activities_types","1"=>"id","2"=>"name_en")));
$listTable->_class('table table-striped');
$listTable->setOrderBy("id");
$listTable->_special(false);
$listTable->_active(true);

$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
$ob_roles->getEditRole($grp_id,$listTable,$module_id,$pageUpdate);
$listTable->_PageInsert($pageInsert);
$listTable->_PageList($pageList);
$listTable->setFilter(array(
    array("title", "text")
));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");

echo $utils->make_tag_html($listTable->GetListTable(),'div','form-itemdetails');


include_once '../../common/footer.php';
