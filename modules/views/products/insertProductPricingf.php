<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
$id = $_REQUEST['id'];
$values1 = $fpdo->from('product_dynamic_price')->where('product_id', $_REQUEST['id'])->fetch();
$action="";
if ($values1['id'] != "") {
    $action = "Edit";
} else {
    $action = "Insert";
}
if (isset($_REQUEST) && $_REQUEST['action'] == $action) {
    $duration_ids = "";
    foreach ($_REQUEST['title_duration'] as $duration_id) {

        $duration_ids.=$duration_id . ",";
    }

    $unit_ids = "";
    foreach ($_REQUEST['title_unit'] as $unit_id) {

        $unit_ids = $unit_id;
    }
    $type_ids = "";
    foreach ($_REQUEST['title_type'] as $type_id) {

        $type_ids = $type_id;
    }

    $_REQUEST['unit_id'] = $unit_ids;
    $_REQUEST['type_id'] = $type_ids;
    $_REQUEST['duration_ids'] = $duration_ids;
    $_REQUEST['product_id'] = $id;
    if($action=="Insert"){
      
    $save_ob = new saveform($db_table_dynamic_price, $_REQUEST, $Savecols_dynamic_Pricing,'id','','','',false);
    }elseif($action=='Edit'){
           $_REQUEST['unit_id'] = $unit_ids;
    $_REQUEST['type_id'] = $type_ids;
    $_REQUEST['duration_ids'] = $duration_ids;
    $_REQUEST['product_id'] = $id;
         $save_ob = new saveform($db_table_dynamic_price, $_REQUEST, $Savecols_dynamic_Pricing,'id','','','',false);
      //   $query = $fpdo->update($db_table_dynamic_price)->set(array('unit_id' => $unit_ids,'type_id'=>$type_ids,'duration_ids'=>$duration_ids))->where("product_id='$id'");
   //$exec = $query->execute();

         }
          if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } else {
    $utils->redirect($pageProductPricing2 . "?id=" . $_REQUEST['id']);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Product Data</a></li>
    <li><a href="#">Features</a></li>
    <li><a href="">Photos</a></li>
    <li class="active-menue"><a href="#">Pricing</a></li>
    <li><a href="#">Add-ons</a></li>
    <li><a href="#">Related Products</a></li>
    <li><a href="#">Required Fields</a></li>
    <li><a href="#">FAQ</a></li>
    <li><a href="#">SEO</a></li>
</ul>';



$form1 = new GenerateFormField();
$form1->setColumns($cols_radio);
$form1->setTypes($types_radio);
$form1->setValues(array('title_unit' => $values1['unit_id']));
$form1->setExtendTables($sourc_radio);
$form1->setRequireds($required);
$form1->setCountCell(1);
$form1->setClassMain("col-sm-12");
$form1->setSubMain(array('col-sm-12 float-pricing', 'red', 'col-sm-12'));
$form1->setSubmit(false);
$form1->setAsForm(false);
$form1->setBackBtn(false);


$form2 = new GenerateFormField();
$form2->setColumns($cols_check_type);
$form2->setTypes($types_check_type);
$form2->setValues(array('title_type' => $values1['type_id']));
$form2->setExtendTables($sourc_check_type);
$form2->setRequireds($required);
$form2->setCountCell(1);
$form2->setClassMain("col-sm-12");
$form2->setSubMain(array('col-sm-12 float-pricing', 'red', 'col-sm-12'));
$form2->setSubmit(false);
$form2->setAsForm(false);
$form2->setBackBtn(false);


$form = new GenerateFormField();
$form->setColumns($cols_check);
$form->setTypes($types_check);
$form->setValues(array('title_duration' => $values1['duration_ids']));
$form->setExtendTables($sourc_check);
$form->setRequireds($required);
$form->setCountCell(1);
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');
$form->setAsForm(true);
$form->setClassMain("col-sm-12");
$form->setSubMain(array('col-sm-12 float-pricing', 'red', 'col-sm-12'));
$form->setAppendToForm($form2->getForm($action) . $form1->getForm($action));
echo $form->getForm($action);
include_once '../../common/footer.php';
