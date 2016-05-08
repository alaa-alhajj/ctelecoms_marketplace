<?php session_start();

$shopping_cart = $_SESSION['Shopping_Cart'];
echo $_SESSION['PROMO_CODE'];
global $utils;

if (isset($_REQUEST,$_REQUEST['payment_type'])){
    
    $payment_type=$_REQUEST['payment_type'];
    echo "<hr>";
    echo "PROMO_CODE = ".$_SESSION['PROMO_CODE'];
    echo "<hr>";
                foreach ($shopping_cart as $key => $product) {
                    print_r($product);
                    
                    $product_id = $product['pro_id'];
                    $duration_id = $product['duration_id'];
                    $group_id = $product['group_id'];
                    $get_pro_name = $this->fpdo->from("products")->where("id='$product_id'")->fetch();
                    
                    $get_dynamic_id = $this->fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
                    $dynamic_id = $get_dynamic_id['id'];
                    $get_price = $this->fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration_id' and `group_id`='$group_id'")->fetch();
                    
                    echo "product price info : <br/>";
                    print_r($get_price);

                    $check_offers = $this->fpdo->from('offers')->where("product_ids like '%" . $product_id . ",%'")->fetch();
                    $price_offer = ((($get_price['value']) - ($get_price['value']*($check_offers['discount_percentage'] / 100)) ));
                    echo "<br/>after offer = $price_offer  ";


                    if ($_SESSION['PROMO_CODE'] != "") {
                        $get_promo_discount = $this->fpdo->from('promo_codes')->where("id='" . $_SESSION['PROMO_CODE'] . "'")->fetch();
                        $price_after_promo = ((($price_offer) - ($price_offer*($get_promo_discount['discount_percentage'] / 100)) ));
                    } else {
                        $price_after_promo = $price_offer;
                    }
                    echo "<br/>after offer and promo = $price_after_promo <br/> ";

                    echo "<hr>";
                }
    
    
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
                        $type_name=$type['name'];
                        $checked='';
                        if($i==0){
                            $checked=" checked ";
                        }
                        echo '  <div class="radio">
                                    <input type="radio" name="payment_type" value="'.$type_name.'" '.$checked.'>'.$type_name.'.
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
