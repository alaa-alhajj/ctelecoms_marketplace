<?php

include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';

if (isset($_REQUEST) && $_REQUEST['action'] == 'Delete') {
    $ids = implode(',', $_REQUEST['DeleteRow']);
    $get_pages_ids = $fpdo->from($db_table)->where("id in ($ids)")->fetchAll();
   
    foreach ($get_pages_ids as $product_pages_ids) {
        $page_ids= $product_pages_ids['page_id'].",";
    }
      $page_ids=  rtrim($page_ids,',');
    $fpdo->deleteFrom('cms_pages')->where("id in ($page_ids)")->execute();
    $fpdo->deleteFrom('product_features_values')->where("product_id in ($ids)")->execute();
    $get_dynamic_ids = $fpdo->from('product_dynamic_price')->where("product_id in ($ids)")->fetchAll();
  
    foreach ($get_dynamic_ids as $dynamic_price_ids) {
         $dynamic_ids= $dynamic_price_ids['id'].",";
    }
     $dynamic_ids=  rtrim($dynamic_ids,',');
    $fpdo->deleteFrom('product_price_values')->where("dynamic_price_id in ($dynamic_ids)")->execute();
    $fpdo->deleteFrom('product_dynamic_price')->where("product_id in ($ids)")->execute();
    $fpdo->deleteFrom('product_faq')->where("product_id in ($ids)")->execute();
    $save_ob = new saveform($db_table, $_REQUEST, $cols);
    $utils->redirect($pageList);
}

$listTable = $voiControl->ObListTable();
$listTable->_table($db_table);
$listTable->_columns(array('title', 'cat_id','special','add_ons_only'));
$listTable->_Types(array('special'=>'flag','add_ons_only'=>'flag'));

$listTable->_source(array('cat_id' => array('product_category', 'id', 'title')));
$listTable->_class('table table-striped');
$listTable->_edit($pageUpdate, array("id"));
$listTable->setOrderBy("id");
$listTable->setExtraLinks(array(array('Package Products', $utils->icons->ico['list'], 'insertPackageProducts.php', array('pro_id' => 'id'), ''),
    array('Features', $utils->icons->ico['list'], 'insertPackageFeatures.php', array('pro_id' => 'id'), ''),
    array('Photos', $utils->icons->ico['list'], 'insertPackagePhotos.php', array('pro_id' => 'id'), '')
    , array('Pricing', $utils->icons->ico['list'], 'insertPackagePricingf.php', array('pro_id' => 'id'), '')
    , array('Add-Ons', $utils->icons->ico['list'], 'insertPackageAddOns.php', array('pro_id' => 'id'), '')
    , array('Related Products', $utils->icons->ico['list'], 'insertPackageRelated.php', array('pro_id' => 'id'), '')
    , array('Required Fields', $utils->icons->ico['list'], 'insertPackageReqFields.php', array('pro_id' => 'id'), '')
    , array('FAQs', $utils->icons->ico['list'], 'insertPackageFAQ.php', array('pro_id' => 'id'), '')
     , array('Purchase Order', $utils->icons->ico['list'], '../purchase_orders/listPurchaseOrder.php', array('product' => 'title','pid'=>'id'), '')
));
$listTable->_special(false);
$listTable->_active(false);
$listTable->_seo_page('page_id');
$listTable->_dublicate($db_table, 'id', $pageUpdate, $dublicated_cols, $_REQUEST['cmsMID'], 'true', '../../views/ajax/SaveDublicatedProduct.php');
$listTable->setFilter(array(
    array("title", "text")
));
//$listTable->_Debug(true);
$conditions='is_package ="1"';
$listTable->_condition($listTable->getWhereFromFilter($conditions, $conditionsGet));
$listTable->setLimit("$start,$LPP");
$listTable->_PageList($pageList);
$listTable->_PageInsert($pageInsertProduct);
echo $utils->make_tag_html($listTable->GetListTable(), 'div', 'form-itemdetails');


include_once '../../common/footer.php';
