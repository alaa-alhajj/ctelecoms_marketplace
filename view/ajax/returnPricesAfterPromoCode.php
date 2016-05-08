<?php
include('../../view/common/top.php');
@session_start();
$code = $_REQUEST['code'];
$customer_id=$_SESSION['CUSTOMER_ID'];


$check_code=$fpdo->from("promo_codes")->where("customer_id='$customer_id' and code='$code'")->fetch();
if($check_code['id']!=""){
    echo json_encode(array(1,$check_code['discount_percentage'],$check_code['product_ids']));
}else{
      echo json_encode(array(0,"Code is wrong"));
}	




?>