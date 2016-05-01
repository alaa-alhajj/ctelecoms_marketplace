<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
$id = $_REQUEST['pro_id'];
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $group_ids = "";
    foreach ($_REQUEST['title_group'] as $group_id) {

        $group_ids.=$group_id . ",";
    }



    $_REQUEST['group_ids'] = $group_ids;

    //  $save_ob = new saveform($db_table_dynamic_price, $_REQUEST, $Updatecols_dynamic_Pricing_group, "id='$id'");
    $query = $fpdo->update($db_table_dynamic_price)->set(array('`group_ids`' => $group_ids))->where("product_id='$id'");
    $exec = $query->execute();

    if ($exec == true || ( $exec >= 0 && is_int($exec))) {
        $success = true;
    } else {
        $success = false;
    }
    $message = "";
    @session_start();

    if ($success) {
        $message = $utils->getConstant("Success");
        $type = "success";
        $_SESSION['saveFormStatus'] = "success";
    } else {
        $message = $utils->getConstant("Faild");
        $type = "error";
        $_SESSION['saveFormStatus'] = "faild";
    }

    echo '<script>notificationMessage(true);
        </script>';
    if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } else {
        $utils->redirect($pageProductPricingTable . "?pro_id=" . $_REQUEST['pro_id']);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="' . $pageInsertProduct . '">Product Data</a></li>
    <li><a href="' . $pageProductPhotos . "?pro_id=" . $_REQUEST['pro_id'] . '">Features</a></li>
    <li><a href="' . $pageProductPhotos . "?pro_id=" . $_REQUEST['pro_id'] . '">Photos</a></li>
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
                ->where("product_dynamic_price.product_id='" . $_REQUEST['pro_id'] . "'")->fetch();
$unit_name = "'" . $get['unit_title'] . "'";
$sourc_check_group = array('title_group' => array('pro_price_groups', "concat(title,' ',$unit_name)", 'id'));


$values1 = $fpdo->from('product_dynamic_price')->where('product_id', $_REQUEST['pro_id'])->fetch();
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
