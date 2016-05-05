<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';
define('start_date', 'start date');
if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{   $ids = implode(',', $_REQUEST['DeleteRow']);
  $fpdo->deleteFrom('product_features')->where("cat_id in ($ids)")->execute();
    $save_ob=new saveform($db_table,$_REQUEST,$cols);
    $utils->redirect($pageList);
}

$listTable = $voiControl->ObListTable();
$listTable->_table($db_table);
$listTable->_columns(array('title'));

$listTable->_source();
$listTable->_class('table table-striped');
$listTable->_edit($pageUpdate, array("id"));
$listTable->setOrderBy("id");
$listTable->setExtraLinks(array(
    array('photos',$utils->icons->ico['list'],'insertCategoryPhotos.php',array('id'=>'id'),''),array('Features',$utils->icons->ico['list'],'insertCategoryFeature.php',array('cat_id'=>'id'))));
$listTable->_special(false);
$listTable->_active(false);
$listTable->_seo_page('page_id');
$listTable->setFilter(array(
    array("title", "text")
));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");
$listTable->_PageList($pageList);
$listTable->_PageInsert($pageInsert);
echo $utils->make_tag_html($listTable->GetListTable(),'div','form-itemdetails');


?>

<?
include_once '../../common/footer.php';
