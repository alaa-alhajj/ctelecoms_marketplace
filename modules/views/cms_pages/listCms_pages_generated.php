<?php
include '../../common/header.php';
include 'config2.php';
include '../../common/pn.php';
if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_table,$_REQUEST,$cols);
    $utils->redirect($pageList);
}

$listTable = $voiControl->ObListTable();
$listTable->_table($db_table);
$listTable->_columns(array('id','title'));



$listTable->setFilter(array(array("title", "text")));
echo $utils->lang_switcher();
$conditions .= " `lang`='$cmsMlang' and type='generated' ";
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));

$listTable->_class('table table-striped');
$listTable->setOrderBy("id desc");
$listTable->_special(false);
$listTable->_active(true);
$listTable->_view_page(_PREF.$cmsMlang.'/');

$listTable->setLimit("$start,$LPP");
$listTable->_PageList($pageList);



$ob_roles->getEditRole($grp_id,$listTable,$module_id,$pageUpdate);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);

$listTable->_delete(false);

echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id),'div','form-itemdetails');


include_once '../../common/footer.php';
?>