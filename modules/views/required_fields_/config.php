<?php
$db_table="product_category";
$db_table_feature="product_features";
$LPP = 8;

$cols=array('title');

$Savecols=array('title');
$colsUpdate= array('title');

$types=array('title'=>"text");
$source=array();
$required=array("title"=>"required");

$pageList="listCategories.php";
$pageListHref="'".$pageList."'";
$pageInsert="insertCategory.php";
$pageUpdate="updateCategory.php";
$pageInsertFeature="insertCategoryFeature.php";

?>
