<?php  include '../../../view/common/top_ajax2.php';

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

if($foundProduct){
    echo "Product removed successfully.";
}else{
    echo "Product don't found in cart.";
}

 print_r($shopping_cart);