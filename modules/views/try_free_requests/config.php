<?php
$db_table="pro_try_free_requests‎";

$LPP = 8;

$cols=array('customer_id','product_id','request_date');
$types=array('customer_id'=>"select",'product_id'=>'select','request_date'=>'date');
$source=array('customer_id' => array('customers', 'id', 'name'),'product_id' => array('products', 'id', 'title'));
$required=array('customer_id'=>'required','product_id'=>'required','request_date'=>'required');

$pageList="free_requestsList.php";


?>
