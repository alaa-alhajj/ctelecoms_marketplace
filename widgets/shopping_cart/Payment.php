<?php session_start();

$shopping_cart = $_SESSION['Shopping_Cart'];

global $utils;
global $pLang;


$customer_id=$_SESSION['CUSTOMER_ID'];
$customer_info = $this->fpdo->from("customers")->where("id='$customer_id'")->fetch();
$customer_name=$customer_info['name'];
$customer_email=$customer_info['email'];

$products_names=array();
foreach ($shopping_cart as $key => $product) {
    //print_r($product);
    $product_id = $product['pro_id'];
    $get_pro_name = $this->fpdo->from("products")->where("id='$product_id'")->fetch();
    $product_name=$get_pro_name['title'];
    $products_names[]=$product_name;
}      

$products_names_str=  implode(',', $products_names);

$order_info = $this->fpdo->from("purchase_order")->where("id!='0'")->orderBy("id DESC")->limit('0,1')->fetch();
$lastOrder=$order_info['id'];
$newOrder=$lastOrder+1;
    
?>


<div class="row row-nomargin">
    <div class="col-xs-3">
        <ul id="shopping-cart">
            <li><a href="#">Shopping Cart</a></li>
            <?   if ($_SESSION['CUSTOMER_ID'] == "") {?>
            <li><a href="#">Checkout</a></li>
            <?}?>
            <li class="active-shopping"><a href="#">Payment</a></li>
            <li><a href="#">Order Details</a></li>

        </ul>
    </div>
    <div class="col-xs-9">
        <form class="form-horizontal" id='PaymentForm' role="form" method="post" action="<?=_PREF.$_SESSION['pLang']."/page101/orderProcessing"?>">
            <h3>Payment Method</h3>
            
            <?php
                $payment_types = $this->fpdo->from("payment_types")->where(" 1 ")->fetchAll();
                if(count($payment_types) > 0){
                    $type1=$payment_types[0];
                    //print_r($type1);
                    $type_id=$type1['id'];
                    $type_name=$type1['name'];
                    ?>
                    <div class="col-sm-6">
                        <div class="radio radio1 col-sm-12">
                            <input type="radio" name="payment_type" value="<?=$type_id?>"><?=$type_name?>.
                        </div>
                        
                        <div class='payment_type bank-type col-sm-12'>
                            <p class='brief-info'>
                                Bank information here<br>
                                Bank information here<br>
                                Bank information here<br>
                            </p>
                            <hr>
                            <button type="submit" class="btn btn-default">Submit</button>    
                        </div>
                        
                    </div>
                    <?php
                        $type2=$payment_types[1];
                        $type_id=$type2['id'];
                        $type_name=$type2['name'];
                    ?>
                    <div class="col-sm-6">
                        <div class="radio radio2 col-sm-12">
                            <input type="radio" name="payment_type" value="<?=$type_id?>"><?=$type_name?>.
                        </div>
                        <div class='col-sm-12 payment_type E-payment-type'>
                            <p class="brief-info">
                                E-payment information here<br>
                                E-payment information here<br>
                                E-payment information here<br>
                            </p>
                            <hr>
                            <!-- Button Code for PayTabs Express Checkout -->
                             <div class="PT_express_checkout"></div>
                             <script type="text/javascript">
                                 Paytabs("#express_checkout").expresscheckout({
                                     settings:{
                                         secret_key: "wjYmbTkHGPzjZ6yeQmA2oJadTNFKqvTXOSj0LDIsthLsnjGeGhh41rhUeWFEszCgrVhdzZF2gqvEJcTqtauvBMpQI5vaE7r63IjC",
                                                     merchant_id: "10011573",
                                         amount: "10.00",
                                         currency: "USD",
                                         title: "Test Express Checkout Transaction",
                                         product_names: "<?=$products_names_str?>",
                                         order_id: <?=$newOrder?>,
                                         url_redirect: "http://voitest.com/ctelecom_market/en/page101/PT2/orderProcessing",
                                         display_billing_fields: 0,
                                         display_shipping_fields: 0,
                                         display_customer_info: 0,


                                     },
                                     customer_info:{
                                         first_name: "<?=$customer_name?>",
                                         last_name: "------",
                                         phone_number: "--",
                                         country_code: "973",
                                         email_address: "<?=$customer_email?>"            
                                     },
                                     billing_address:{
                                         full_address: "Manama, Bahrain",
                                         city: "Manama",
                                         state: "Manama",
                                         country: "BHR",
                                         postal_code: "00973"
                                     },
                                     shipping_address:{
                                         shipping_first_name: "John",
                                         shipping_last_name: "Smith",
                                         full_address_shipping: "Manama, Bahrain",
                                         city_shipping: "Manama",
                                         state_shipping: "Manama",
                                         country_shipping: "BHR",
                                         postal_code_shipping: "00973"
                                     },


                                 });
                             </script>
                        </div>
                    </div>
            
                
                    <?php
                    /*
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
                                </div>';
                        $i++;
                    }*/
                }
            ?>
        </form>

    </div>
</div>
