<?php
$db_table="product_dynamic_price";
$db_pro_Duration="pro_price_duration";
$db_pro_Type="pro_price_type";
$db_pro_Units="pro_price_units";
$db_pro_Groups="pro_price_groups";
$LPP = 8;

$cols=array('title');

$Savecols=array('title');
$colsUpdate= array('title');

$types=array('title'=>"text");
$source=array();
$required=array("title"=>"required");

$pageList="listPricing.php";

$pageInsertPricingDuration="insertPricingDuration.php";
$pageInsertPricingType="insertPricingType.php";
$pageInsertTypeHref="'".$pageInsertPricingType."'";
$pageInsertPricingMeasurement="insertPricingMeasurement.php";
$pageInsertPricingMeasurementHref="'".$pageInsertPricingMeasurement."'";
$pageInsertPricingGroups="insertPricingGroups.php";
$pageInsertPricingGroupsHref="'".$pageInsertPricingGroups."'";
$pageUpdate="updateCategory.php";
$pageInsertFeature="insertCategoryFeature.php";
$congrats="'"."Congrats.php"."'";


?>
