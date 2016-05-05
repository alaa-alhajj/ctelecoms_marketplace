<?php  
include('../../view/common/top.php');


$pro_id=$_REQUEST['pro_id'];
$duration_id=$_REQUEST['duration_id'];
$group_id=$_REQUEST['group_id'];
$qty=$_REQUEST['qty'];
if($qty==''){ // set qty = 1 if don't qty value don't found
    $qty=1;
}

$product=array('pro_id'=>$pro_id,'duration_id'=>$duration_id,'group_id'=>$group_id,'qty'=>$qty);

//add product to shopping Cart session
$shopping_cart=$_SESSION['Shopping_Cart'];
$shopping_cart[$pro_id]=$product;
$_SESSION['Shopping_Cart']=$shopping_cart;
print_r($shopping_cart);