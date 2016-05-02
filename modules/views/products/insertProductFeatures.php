<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
$get_Cat = $fpdo->from($db_table)->where("id='" . $_REQUEST['id'] . "'")->fetch();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    // $save_ob = new saveform($db_table, $_REQUEST, $Savecols);
    $get_feature_value = $fpdo->from('product_features_values')->where("product_id='" . $_REQUEST['id'] . "'")->fetch();
    if ($get_feature_value['id'] != "") {
        $query = $fpdo->deleteFrom('product_features_values')->where('product_id', $_REQUEST['id'])->execute();
    }
    foreach ($_REQUEST['SlectedFeatures'] as $feature_id) {
        $feature_val = $_REQUEST['feature_' . $feature_id];
        if ($feature_val != "") {
            $SavecolsFeatures = array('feature_id', 'product_id', 'value');
            $_REQUESTA['action'] = 'Insert';
            $_REQUESTA['feature_id']=$feature_id;
            $_REQUESTA['product_id']=$_REQUEST['id'];
             $_REQUESTA['value']=$feature_val;
            $save_ob = new saveform('product_features_values', $_REQUESTA, $SavecolsFeatures, "id", $order_field, $map_field, '', false);
           // $query = $fpdo->insertInto('product_features_values')->values(array('`feature_id`' => $feature_id, '`product_id`' => $_REQUEST['id'], '`value`' => $feature_val));
           // $exec = $query->execute();
        }
    }

    if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } else {
        $utils->redirect($pageProductPhotos . "?id=" . $_REQUEST['id']);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Product Data</a></li>
    <li  class="active-menue"><a href="#">Features</a></li>
    <li><a href="">Photos</a></li>
    <li><a href="#">Pricing</a></li>
    <li><a href="#">Add-ons</a></li>
    <li><a href="#">Related Products</a></li>
    <li><a href="#">Required Fields</a></li>
    <li><a href="#">FAQ</a></li>
    <li><a href="#">SEO</a></li>
</ul>';

$get_features = $fpdo->from($db_pro_features)->where("cat_id='" . $get_Cat['cat_id'] . "'")->fetchAll();
$row_id = explode('_', $db_pro_Type);
$add_feature = '';
$add_feature.='
<input type="hidden" value="' . $_REQUEST['id'] . '">   
<table id="" class="table table-striped  table table-bordered table-hover">

<tbody id="" class="sortable ui-sortable">';
foreach ($get_features as $feature) {
    $get_feature_value = $fpdo->from('product_features_values')->where("product_id='" . $_REQUEST['id'] . "' and feature_id='" . $feature['id'] . "'")->fetch();

    $get_feature_type = $fpdo->from('product_features')->where("id", $feature['id'])->fetch();
    if ($get_feature_type['type'] == 'DynamicSelect') {
        $table_select = $get_feature_type['plus'];
    }
    // echo $get_feature_type['type'];
    $plus = explode(',', $get_feature_type['plus']);
    $field_ob = new field();
    $field_ob->SetIdField('title');
    $field_ob->SetNameField("feature_" . $feature['id']);
    $field_ob->SetCssClass('');
    $field_ob->SetTypeField($get_feature_type['type']);
    $field_ob->SetTable($table_select);
    // $field_ob->SetRequiredField($this->requireds[$col]);
    $field_ob->SetTname('value');
    $field_ob->SetTvalue('id');
    $field_ob->SetValueField($get_feature_value['value']);
    $field_ob->setWhere();
    $field_ob->SetExtra($plus);

    //  print_r($plus);
    $add_feature.="<tr><td><label>" . $feature['title'] . "</label><br>" . $field_ob->getField() . "<input type='hidden' value='" . $feature['id'] . "' name='SlectedFeatures[]'></td></tr>";
}
$add_feature.='</tbody>
</table>';

$add_feature.='<div class="hr"><hr></div>';


//echo $add_feature;

$form = new GenerateFormField();
$form->setColumns();
$form->setTypes();
$form->setExtendTables($source);
$form->setRequireds($required);

$form->setCountCell(1);
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');
$form->setAsForm(true);
$form->setAppendToForm($add_feature);
echo $form->getForm('Insert');
include_once '../../common/footer.php';
