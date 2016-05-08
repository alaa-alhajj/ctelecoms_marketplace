<?php
include('../../view/common/top.php');
@session_start();
$duration = $_REQUEST['duration'];
$group = $_REQUEST['group'];
$dynamic_id = $_REQUEST['dynamic'];
$product_id=$_REQUEST['product_id'];
$get_price = $fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration' and `group_id`='$group'")->fetch();
$value = $get_price['value'];
$shopping_cart = $_SESSION['Shopping_Cart'];
$pr = $shopping_cart[$product_id];
$pr['group_id'] = $group;
$shopping_cart[$product_id]=$pr;
$_SESSION['Shopping_Cart']=$shopping_cart;
echo json_encode(array($value));
?>