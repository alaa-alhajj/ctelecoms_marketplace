<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';
if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_table,$_REQUEST,$cols);
    $delete_fields = array('action'=>'Delete','DeleteRow'=>$_REQUEST['DeleteRow']);
	$save_ob=new saveform('cms_widget_fields',$delete_fields,array('id'),'widget_id');
	$utils->redirect($pageList);
}

$listTable = $voiControl->ObListTable();
$listTable->_table($db_table);
$listTable->_columns(array('id','title'));


$listTable->_class('table table-striped');
$listTable->_edit($pageUpdate, array("id"));
$listTable->setOrderBy("id desc");
$listTable->_special(false);
$listTable->_active(true);
$listTable->setFilter(array(array("title", "text")));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");
$listTable->_PageList($pageList);
$listTable->setExtraLinks(array(
				array('Template',$utils->icons->ico['widget'],'select-template-module.php',array('widget_id'=>'id'))
				));
$ob_roles->getEditRole($grp_id,$listTable,$module_id,$pageUpdate);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);



$listTable->_PageInsert($pageInsert);


echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id),'div','form-itemdetails');


include_once '../../common/footer.php';
?>