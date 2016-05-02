<?php  include '../../../view/common/top_ajax2.php';

$pro_id=$_REQUEST['pro_id'];
$qty=$_REQUEST['qty'];

$shopping_cart=$_SESSION['Shopping_Cart'];
$changeStatus=FALSE;

foreach ($shopping_cart as $key => $product) {
    if ($product['pro_id']==$pro_id){
        $product['qty']=$qty; //change product QTY
        $shopping_cart[$key]=$product;
        $changeStatus=TRUE;
    }
}
//save change in session
$_SESSION['Shopping_Cart']=$shopping_cart;

if($changeStatus){
    echo "Product QTY changed successfully.";
}else{
    echo "Product don't found in cart.";
}

