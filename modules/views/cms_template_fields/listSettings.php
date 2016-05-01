<?php
include 'config.php';
include '../../common/header.php';


$listTable = $voiControl->ObListTable();

if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_settings_table,$_REQUEST,$settings_cols);
    $utils->redirect($activities_type);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
	
    $save_ob = new saveform($db_settings_table, $_REQUEST, $settings_cols,'id');
    $utils->redirect($listTable->getLinkBackGridList());
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_settings_table, $_REQUEST, $settings_cols);
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_settings_table);
$listTable->_columns($settings_cols);
$listTable->_Types($settings_types);

$listTable->_class('table table-striped');
$listTable->setOrderBy("id");
$listTable->IsGridList(true);
$listTable->_special(false);
$listTable->_active(false);
$conditionsGet=array('field_id'=>'field_id');
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");
$listTable->setParentAttr(array('field_id'));


$field_id = $_REQUEST['field_id'];
$temp_id = $utils->lookupField('cms_template_fields', 'id', 'template_id', $field_id);
$listTable->setBackBtn('listFields.php?temp_id='.$temp_id);

$ob_roles->getEditRole($grp_id,$listTable,$module_id);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
$listTable->_PageList($pageList);
$listTable->setExtra(array('resize_type'=>array('crop','resize')));
echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id),'div','form-itemdetails');




include_once '../../common/footer.php';?>
