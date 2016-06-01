<?php
$db_table="offers";
$db_table_feature="product_features";
$LPP = 8;

$cols=array('title','discount_percentage','start_date','end_date');

$Savecols=array('title','discount_percentage','start_date','end_date');
$colsUpdate= array('title','discount_percentage','start_date','end_date');

$types=array('title'=>"text",'discount_percentage'=>'number','start_date'=>'date','end_date'=>'date');
$source=array();
$required=array("title"=>"required");
$dublicated_cols=array('title','discount_percentage','start_date','end_date','product_ids');
$pageList="listOffers.php";
$pageListHref="'".$pageList."'";
$pageInsert="insertOffer.php";
$pageUpdate="updateOffer.php";
$pageInsertFeature="insertOfferProducts.php";

?>
