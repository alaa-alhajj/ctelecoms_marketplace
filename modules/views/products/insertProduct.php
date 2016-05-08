<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';

if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols);
    $insert_id = $save_ob->getInsertId();
    if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } else {
        $utils->redirect($pageProductFeatures . "?id=" . $insert_id);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li class="active-menue"><a href="#">Product Data</a></li>
    <li><a href="#">Features</a></li>
    <li><a href="">Photos</a></li>
    <li><a href="#">Pricing</a></li>
    <li><a href="#">Add-ons</a></li>
    <li><a href="#">Related Products</a></li>
    <li><a href="#">Required Fields</a></li>
    <li><a href="#">FAQ</a></li>
    <li><a href="#">SEO</a></li>
</ul>';
$form = new GenerateFormField();
$form->setColumns($cols);
$form->setTypes($types);
$form->setExtendTables($source);
$form->setRequireds($required);
$form->setIdForm("Products");
$form->setCountCell(1);
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setAsForm(true);

echo $form->getForm('Insert');
//echo '<div class="box box-danger form-horizontal"><div class="box-body"><form method="post" class="" name="" id=""><input type="hidden" name="backLink" value="http://localhost/ctelecoms_marketplace/modules/views/category/listCategories.php"><div class="Form-Field form-generate-voila "><div class=""><div class="form-group"><label class="col-sm-2 control-label"><span class="red  required">* </span>title: </label><div class="col-sm-10"><input id="title" name="title" value="" type="text" required="" size="" class=" form-control" placeholder=""></div></div><div class="hr"><hr></div></div>  <div class="col-sm-12">   <input type="hidden" value="Insert" name="action" id="action"><button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;<input type="submit" class="btn btn-submit" value="Save & Continue">  </div> </div></form></div></div>';

include_once '../../common/footer.php';
