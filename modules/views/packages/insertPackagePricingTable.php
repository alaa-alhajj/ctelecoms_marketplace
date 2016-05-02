<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
$id = $_REQUEST['id'];
$get_pricing = $fpdo->from($db_table_dynamic_price)->where("product_id='$id'")->fetch();

$values1 = $fpdo->from($db_product_price_value)->where('dynamic_price_id', $get_pricing['id'])->fetch();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    if ($values1['id'] != "") {
        $query = $fpdo->deleteFrom($db_product_price_value)->where('dynamic_price_id', $get_pricing['id'])->execute();
    }
    foreach ($_REQUEST['PricingTableAdd'] as $value) {

        $_REQUEST['value'] = $val = $_REQUEST['input_' . $value];
        $get_ids = explode("_", $value);
        $_REQUEST['dynamic_price_id'] = $dynamic_pric_id = $get_ids[0];
        $_REQUEST['duration_id'] = $duration_id = $get_ids[1];
        $_REQUEST['group_id'] = $group_id = $get_ids[2];

        $save_ob = new saveform($db_product_price_value, $_REQUEST, $saveCols_product_price_values,'id','','','',false);
        if ($_REQUEST['saveClose'] != "") {
            $utils->redirect($pageList);
        } else {
            $utils->redirect($pageProductAddOns . "?id=" . $_REQUEST['id']);
        }
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Package Data</a></li>
    <li><a href="#">Package Products</a></li>
    <li><a href="#">Features</a></li>
    <li><a href="">Photos</a></li>
    <li   class="active-menue"><a href="#">Pricing</a></li>
    <li><a href="#">Add-ons</a></li>
    <li><a href="#">Related Products</a></li>
    <li><a href="#">Required Fields</a></li>
    <li><a href="#">FAQ</a></li>
    <li><a href="#">SEO</a></li>
</ul>';


$table.='<table id="table_pricing_product" class="table table-striped  table table-bordered table-hover">
<thead>
<tr>
<th></th>
';
$groups = explode(",", rtrim($get_pricing['group_ids'], ","));
$unit_id = $get_pricing['unit_id'];
$unit_name = $fpdo->from('pro_price_units')->where("id='$unit_id'")->fetch();
$gr = 0;
foreach ($groups as $group_id) {
    $get_group_name = $fpdo->from('pro_price_groups')->where("id='$group_id'")->fetch();

    $table.="<th data-id='" . $get_group_name['id'] . "'>" . $get_group_name['title'] . " " . $unit_name['title'] . "</th>";

    $gr++;
}
$table.='</tr>
</thead>
<tbody id="" class="sortable ui-sortable">';

$durations = explode(',', rtrim($get_pricing['duration_ids'], ','));
foreach ($durations as $duration_id) {
    $get_duration_name = $fpdo->from('pro_price_duration')->where("id='$duration_id'")->fetch();

    $table.="<tr ><td data-id='" . $get_duration_name['id'] . "'>" . $get_duration_name['title'] . "</td>";
    foreach ($groups as $group_du) {

        $get_group_name = $fpdo->from('product_dynamic_price')
                        ->where("product_id='$id' and duration_ids like '%$duration_id,%' and group_ids like '%$group_du,%'")->fetch();
        $value_name = $get_pricing['id'] . "_" . $duration_id . "_" . $group_du;
        $get_values_prices = $fpdo->from($db_product_price_value)->where("duration_id='$duration_id' and group_id='$group_du' and dynamic_price_id='" . $get_pricing['id'] . "'")->fetch();
        $table.= "<td data-duration='$duration_id' data-group='$group_du' data-dynamic='" . $get_pricing['id'] . "'><input id='title' name='input_" . $value_name . "' value='" . $get_values_prices['value'] . "' type='number' required='' size='' class=' form-control' ><input type='hidden' value='" . $value_name . "' name='PricingTableAdd[]'></td>";
    }

    $table.= "</tr>";
}

$table.='</tbody>
</table>';



$form = new GenerateFormField();
$form->setColumns();
$form->setTypes();
$form->setExtendTables();
$form->setRequireds($required);
$form->setCountCell(1);
$form->setIdForm('PricingProductTable');
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setAsForm(true);
$form->setClassMain("col-sm-12");
$form->setSubMain(array('col-sm-12 float-pricing', 'red', 'col-sm-12'));
$form->setAppendToForm($table);
echo $form->getForm('Insert');
include_once '../../common/footer.php';
