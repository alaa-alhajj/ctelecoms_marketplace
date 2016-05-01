<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
define("customer_code","Customer");
$textjs = new textext_js();
echo $textjs->get_header();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {


    $vals = explode(',', $textjs->getForma('customers', 'id', 'name', $_REQUEST['customer_code']));
    $customer = "";
    if (count($vals) > 0) {
        foreach ($vals as $v) {
            $customer.=$v . ",";
        }
    }
    $_REQUEST['customer_id'] = $customer;
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols);
    $insert_id = $save_ob->getInsertId();
    $utils->redirect($pagePromoOfferProducts . "?promo=" . $insert_id);
}
echo $path = '<ul id="breadcrumbs-one">
    <li class="active-menue"><a href="#">Promo Code</a></li>
    <li><a href="#">Offer Products</a></li>
    
</ul>';
$form = new GenerateFormField();
$form->setColumns($cols_ins);
$form->setTypes($types);
$form->setExtendTables($source);
$form->setRequireds($required);
$form->setCountCell(1);
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');
$form->setAsForm(true);

echo $form->getForm('Insert');
echo $textjs->get_script('[name="customer_code"]', '../ajax/AsTags.php', 'customers', 'id', 'name', '', 'true');
//echo '<div class="box box-danger form-horizontal"><div class="box-body"><form method="post" class="" name="" id=""><input type="hidden" name="backLink" value="http://localhost/ctelecoms_marketplace/modules/views/category/listCategories.php"><div class="Form-Field form-generate-voila "><div class=""><div class="form-group"><label class="col-sm-2 control-label"><span class="red  required">* </span>title: </label><div class="col-sm-10"><input id="title" name="title" value="" type="text" required="" size="" class=" form-control" placeholder=""></div></div><div class="hr"><hr></div></div>  <div class="col-sm-12">   <input type="hidden" value="Insert" name="action" id="action"><button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;<input type="submit" class="btn btn-submit" value="Save & Continue">  </div> </div></form></div></div>';

include_once '../../common/footer.php';
