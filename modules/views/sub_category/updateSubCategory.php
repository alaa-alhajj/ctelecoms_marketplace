<?php

include 'config.php';
include '../../common/header.php';


$textjs = new textext_js();
echo $textjs->get_header();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols, 'id');
       if ($_REQUEST['feature_type'][0] === 1 || $_REQUEST['feature_type'][0] === '1') {
      $get_features=$fpdo->from("product_features")->where("cat_id='".$_REQUEST['cat_id']."' and sub_cat_id='0'")->fetchALL();
         $query = $fpdo->deleteFrom('product_features')->where('sub_cat_id',  $_REQUEST['id'])->execute();
      foreach($get_features as $sub_features){
          $get_maxOrder=$fpdo->from("product_features")->select("max(item_order) as max")->fetch();
          $order=$get_maxOrder['max']+1;
            $fpdo->insertInto('product_features')->values(array('`item_order`' => $order, '`title`' => $sub_features['title'],'`type`'=>$sub_features['type'],'`plus`'=>$sub_features['plus'],'`is_main`'=>$sub_features['is_main'],'`sub_cat_id`'=>$_REQUEST['id']))->execute();
      }
        $utils->redirect($pageList);
    } elseif ($_REQUEST['feature_type'][0] === 2 || $_REQUEST['feature_type'][0] === '2') {
      
        $utils->redirect($pageInsertFeature . "?id=" . $_REQUEST['id']);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li class="active-menue"><a href="#">Sub Category Info</a></li>
  
    <li><a href="#">Sub Category Features</a></li>
  
</ul>';
$form = new GenerateFormField();
$values = $fpdo->from($db_table)->where('id', $_REQUEST['id'])->fetch();
$form->setColumns($colsUpdate);
$form->setTypes($types);
$form->setValues($values);
$form->setRequireds();
$form->setExtendTables($source);
$form->setClasses();
$form->setSubmitName('Save & Continue');
$form->setSkipBtn(true, $pageInsertPhotos . "?id=" . $_REQUEST['id']);
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setCountCell(1);
$form->setCellClassWithCount('col-sm-12');
echo $utils->make_tag_html($form->getForm('Edit'), 'div', 'form-itemdetails');

include_once '../../common/footer.php';
