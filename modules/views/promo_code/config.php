<?php
$db_table="promo_codes";

$LPP = 8;

$cols=array('customer_id','code','start_date','end_date','discount_percentage');
$cols_ins=array('customer_code','code','start_date','end_date','discount_percentage');
$Savecols=array('customer_id','code','start_date','end_date','discount_percentage');
$colsUpdate= array('customer_id','code','start_date','end_date','discount_percentage');

$types=array('customer_code'=>"text",'discount_percentage'=>'number','start_date'=>'date','end_date'=>'date','code'=>'code');
$source=array('customer_id' => array('customers', 'id', 'name'));
$required=array("title"=>"required");
$source2=array('customer_code' => array('customers', 'id', 'name'));
$pageList="listPromoCode.php";
$pageListHref="'".$pageList."'";
$pageInsert="insertPromo.php";
$pageUpdate="updatePromo.php";
$pagePromoOfferProducts="insertPromoOfferProducts.php";

?>
