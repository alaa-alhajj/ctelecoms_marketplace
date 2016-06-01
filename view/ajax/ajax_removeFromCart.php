<?php 
include('../../view/common/top.php');
$pro_id=$_REQUEST['pro_id'];
$shopping_cart=$_SESSION['Shopping_Cart'];
$foundProduct=FALSE;

foreach ($shopping_cart as $key => $product) {
    if ($product['pro_id']==$pro_id){
        unset($shopping_cart[$key]);
        $foundProduct=TRUE;
    }
}
//save change in session
$_SESSION['Shopping_Cart']=$shopping_cart;



 echo json_encode(count($_SESSION['Shopping_Cart']));