<?php

include '../../common/header.php';
include 'config_features.php';
include '../../common/pn.php';

echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Category Info</a></li>
     <li><a href="#">Category Photos</a></li>
    <li  class="active-menue"><a href="#">Category Features</a></li>
     <li><a href="#">SEO</a></li>
    
</ul>';
$listTable = $voiControl->ObListTable();

$table_name = $utils->lookupField('cms_modules', 'id', 'table_name', $table_id);
$type = $_REQUEST['type'];
$field_titel = $_REQUEST['title'];
$_REQUEST['title'] = strtolower(str_replace(' ', '_', $field_titel));
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $cat_id=  explode('=',$_REQUEST['_params'][0]);
$_REQUEST['cat_id']=$cat_id[1];
   $save_ob = new saveform($db_table, $_REQUEST, $cols_save, 'id','item_order','','',false);
   
  
      $utils->redirect($pageListF."?cat_id=".$cat_id[1]);
}
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {

    $save_ob = new saveform($db_table, $_REQUEST, $cols);
     $cat_id=  explode('=',$_REQUEST['_params'][0]);
    $utils->redirect($pageListF."?cat_id=".$cat_id[1]);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Delete') {
    $ids = $_REQUEST['DeleteRow'];


    $save_ob = new saveform($db_table, $_REQUEST, $cols, 'id');
      $cat_id=  explode('=',$_REQUEST['_params'][0]);
    $utils->redirect($pageListF."?cat_id=".$cat_id[1]);
}

$listTable->_table($db_table);
$listTable->_columns($cols);
$listTable->_Types($types);
$listTable->_source($_source);
$listTable->setExtra($extra);

$conditions = " `cat_id` ='" . $_REQUEST['cat_id'] . "' ";
//$conditionsGet=array('cat_id'=>'table_id');
$listTable->_condition($listTable->getWhereFromFilter($conditions, $conditionsGet));

$listTable->_class('table table-striped table-fileds');
$listTable->setParentAttr(array('cat_id'));
$listTable->_order(true, $order_field, $order_condition);
$listTable->IsGridList(true);
$listTable->setOrderBy("item_order asc");
$listTable->_special(false);
$listTable->_active(false);
//$listTable->setFilter(array(array("lable", "text")));
$listTable->setLimit("$start,$LPP");
$listTable->_PageList($pageListF);
$listTable->_PageInsert($pageInsert);
$listTable->setRequireds($required);
$ob_roles->getEditRole($grp_id, $listTable, $module_id);
$ob_roles->getDeleteRole($grp_id, $listTable, $module_id);
//$listTable->setBackBtn('../gen_modules/listModules.php?cmsMID=1');

echo $utils->make_tag_html($ob_roles->getListRole($grp_id, $listTable, $module_id), 'div', 'form-itemdetails');
$pageInsertSEOHref = "'" . $pageInsertSEO . "?id=" . $_REQUEST['cat_id'] . "'";
echo $add_feature='<div class="col-sm-12">  '
        . ' <input type="hidden" value="Insert" name="action" id="action">'
        . '<button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;'
        . '<input type="button"  class="btn btn-new" value="Close" onClick="location.href=' . "$pageListHref" . '">'
        . '<input type="button"  class="btn btn-submit" value="Continue" onClick="location.href=' . "$pageInsertSEOHref" . '">  </div>';

include_once '../../common/footer.php';
?>