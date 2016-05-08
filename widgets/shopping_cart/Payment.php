<?php session_start();

$shopping_cart = $_SESSION['Shopping_Cart'];

global $utils;
global $pLang;

if(!isset($_SESSION['PROMO_CODE'])){
    $_SESSION['PROMO_CODE']='';
}

if (isset($_REQUEST,$_REQUEST['payment_type'])){

    $payment_type=$_REQUEST['payment_type'];
    
    //create purchase order
    $order_insert_id = $this->fpdo->insertInto('purchase_order')->values(array('customer_id'=>$_SESSION['CUSTOMER_ID'],'order_date'=>date('Y-m-d'),'payment_type'=>$payment_type,'promo_code'=>$_SESSION['PROMO_CODE']))->execute(); 
     
    foreach ($shopping_cart as $key => $product) {
        //print_r($product);

        $product_id = $product['pro_id'];
        $duration_id = $product['duration_id'];
        $group_id = $product['group_id'];
        $get_pro_name = $this->fpdo->from("products")->where("id='$product_id'")->fetch();

        $get_dynamic_id = $this->fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
        $dynamic_id = $get_dynamic_id['id'];
        $get_price = $this->fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration_id' and `group_id`='$group_id'")->fetch();
        $product_price_id=$get_price['id']; //product_price_id
        //echo "product price info : <br/>";
        

        $check_offers = $this->fpdo->from('offers')->where("product_ids like '%" . $product_id . ",%'")->fetch();
        $price_offer = ((($get_price['value']) - ($get_price['value']*($check_offers['discount_percentage'] / 100)) ));
       // echo "<br/>after offer = $price_offer  ";

          // $price_after_promo is final product 
        if ($_SESSION['PROMO_CODE'] != "") {
            $get_promo_discount = $this->fpdo->from('promo_codes')->where("id='" . $_SESSION['PROMO_CODE'] . "'")->fetch();
            $price_after_promo = ((($price_offer) - ($price_offer*($get_promo_discount['discount_percentage'] / 100)) ));
        } else {
            $price_after_promo = $price_offer;
        }
        //echo "<br/>after offer and promo = $price_after_promo <br/> ";

        
        //save products in purchase order
        $insert_id = $this->fpdo->insertInto('purchase_order_products')->values(array('purchase_order_id'=>$order_insert_id,'product_id'=>$product_id,'product_price_id'=>$product_price_id,'product_price'=>$price_after_promo))->execute(); 
    }
    
     $utils->redirect(_PREF.$pLang."/page68/OrderDetails");
    
}
         
?>


<div class="row row-nomargin">
    <div class="col-xs-3">
        <ul id="shopping-cart">
            <li><a href="#">Shopping Cart</a></li>
            <li><a href="#">Checkout</a></li>
            <li class="active-shopping"><a href="#">Payment</a></li>
            <li><a href="#">Order Details</a></li>

        </ul>
    </div>
    <div class="col-xs-9">
        <form class="form-horizontal" role="form" method="post" action="">
            <h3>Payment Method</h3>
            </ul>
            <?php
                $payment_types = $this->fpdo->from("payment_types")->where(" 1 ")->fetchAll();
                if(count($payment_types) > 0){
                    $i=0;
                    foreach ($payment_types as $type) {
                        $type_id=$type['id'];
                        $type_name=$type['name'];
                        $checked='';
                        if($i==0){
                            $checked=" checked ";
                        }
                        echo '  <div class="radio">
                                    <input type="radio" name="payment_type" value="'.$type_id.'" '.$checked.'>'.$type_name.'.
                                </div><br/>';
                        $i++;
                    }
                }
            ?>
            <hr>
            </ul>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>

    </div>
</div>
