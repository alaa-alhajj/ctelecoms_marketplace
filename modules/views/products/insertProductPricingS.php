<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
$id = $_REQUEST['id'];
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $group_ids = "";
    foreach ($_REQUEST['title_group'] as $group_id) {

        $group_ids.=$group_id . ",";
    }
//echo $group_ids;die();
    $_REQUESTA['group_ids'] = $group_ids;
    $query = $fpdo->update($db_table_dynamic_price)->set(array('`group_ids`' => $group_ids))->where("product_id='$id'");
    $exec = $query->execute();
    $Savecols_groups=array('group_ids');
   
     $_REQUESTA['action']='Edit';
     
  //$save_ob = new saveform($db_table_dynamic_price, $_REQUESTA, $Savecols_groups,'id','','','',false);
     echo '<script>notificationMessage(true);
        </script>';
    if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } else {
        $utils->redirect($pageProductPricingTable . "?id=" . $_REQUEST['id']);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="' . $pageInsertProduct . '">Product Data</a></li>
    <li><a href="' . $pageProductPhotos . "?id=" . $_REQUEST['id'] . '">Features</a></li>
    <li><a href="' . $pageProductPhotos . "?id=" . $_REQUEST['id'] . '">Photos</a></li>
    <li class="active-menue"><a href="' . $pageInsertProduct . '">Pricing</a></li>
    <li><a href="' . $pageInsertProduct . '">Add-ons</a></li>
    <li><a href="' . $pageInsertProduct . '">Related Products</a></li>
    <li><a href="' . $pageInsertProduct . '">Required Fields</a></li>
    <li><a href="' . $pageInsertProduct . '">FAQ</a></li>
    <li><a href="' . $pageInsertProduct . '">SEO</a></li>
</ul>';
$get = $fpdo->from('product_dynamic_price')
                ->select('pro_price_units.title as unit_title')
                ->leftJoin("pro_price_units on pro_price_units.id=product_dynamic_price.unit_id")
                ->where("product_dynamic_price.product_id='" . $_REQUEST['id'] . "'")->fetch();
$unit_name = "'" . $get['unit_title'] . "'";
$sourc_check_group = array('title_group' => array('pro_price_groups', "concat(title,' ',$unit_name)", 'id'));


$values1 = $fpdo->from('product_dynamic_price')->where('product_id', $_REQUEST['id'])->fetch();
$form = new GenerateFormField();
$form->setColumns($cols_check_group);
$form->setTypes($types_check_group);
$form->setValues(array('title_group' => $values1['group_ids']));
$form->setExtendTables($sourc_check_group);
$form->setRequireds($required);
$form->setCountCell(1);
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');
$form->setAsForm(true);
$form->setClassMain("col-sm-12");
$form->setSubMain(array('col-sm-12 float-pricing', 'red', 'col-sm-12'));
$form->setAppendToForm();
echo $form->getForm('Edit');
include_once '../../common/footer.php';

  