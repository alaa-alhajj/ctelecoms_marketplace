<?php

include '../../common/header.php';
include 'config.php';
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $id = $_REQUEST['id'];
    $photo = $_REQUEST['photos'];
    //  $save_ob = new saveform($db_table, $_REQUEST, $Savecols_photo, "id='$id'");
  //  $query = $fpdo->update($db_table)->set(array('photos' => $_REQUEST['photos']))->where("id='$id'");
  //  $exec = $query->execute();
    
    $save_ob = new saveform($db_table, $_REQUEST, $cols_photo, "id", $order_field, $map_field, '', false);

  
         if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } else {
           $utils->redirect($pageProductPricing1 . "?id=" . $_REQUEST['id']);
    }
    }
   echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Product Data</a></li>
    <li><a href="#">Features</a></li>
    <li class="active-menue"><a href="">Photos</a></li>
    <li><a href="#">Pricing</a></li>
    <li><a href="#">Add-ons</a></li>
    <li><a href="#">Related Products</a></li>
    <li><a href="#">Required Fields</a></li>
    <li><a href="#">FAQ</a></li>
    <li><a href="#">SEO</a></li>
</ul>';
    $form = new GenerateFormField();
    $values = $fpdo->from($db_table)->where('id', $_REQUEST['id'])->fetch();
    $form->setColumns($cols_photo);
    $form->setTypes($types_photo);
    $form->setValues($values);
    $form->setRequireds();
    $form->setExtendTables($source);
    $form->setClasses();
    $form->setSaveCloseBtn(true, 'Save & Close');
    $form->setSubmitName('Save & Continue');
    $form->setCountCell(1);
    $form->setCellClassWithCount('col-sm-12');
    echo $utils->make_tag_html($form->getForm('Edit'), 'div', 'form-itemdetails');
    include_once '../../common/footer.php';
    