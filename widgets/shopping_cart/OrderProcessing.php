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
    $pro_items_info="";
    $total_price_before_discount=0;
    $allPros_total_discount=0;
    $total_price=0;
    
    foreach ($shopping_cart as $key => $product) {
        //print_r($product);

        $product_id = $product['pro_id'];
        
        
        $duration_id = $product['duration_id'];
        $group_id = $product['group_id'];
        $qty = $product['qty'];
        $renew_session=$_SESSION['Renew_Products'];
        if($renew_session[$product_id]!=""){
            $purchase_id=$renew_session[$product_id]['purchase_id'];
             $this->fpdo->update("purchase_order_products")->set(array('is_renew' =>'1'))->where('id', $purchase_id)->execute();
        }
        
        $get_pro_name = $this->fpdo->from("products")->where("id='$product_id'")->fetch();

        $get_dynamic_id = $this->fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
        if($get_dynamic_id['type_id']==='1'){
            $unit_value= $product['unit_value'];
        }else{
            $unit_value="";
        }
        $dynamic_id = $get_dynamic_id['id'];
        $get_price = $this->fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration_id' and `group_id`='$group_id'")->fetch();
        $product_price_id=$get_price['id']; //product_price_id
        //echo "product price info : <br/>";
        
        $check_offers = $this->fpdo->from('offers')->where("product_ids like '%" . $product_id . ",%'")->fetch();
        $price_offer = ((($get_price['value']) - ($get_price['value']*($check_offers['discount_percentage'] / 100)) ));
        $real_price=$get_price['value'];//product price with discount
       // echo "<br/>after offer = $price_offer  ";
        $offer_discount=0;
        $offer_discount+=$check_offers['discount_percentage']; 
        $promo_discount=0;
        // $price_after_promo is final product 
        if ($_SESSION['PROMO_CODE'] != "") {
            $get_promo_discount = $this->fpdo->from('promo_codes')->where("id='" . $_SESSION['PROMO_CODE'] . "'")->fetch();
            $price_after_promo = ((($price_offer) - ($price_offer*($get_promo_discount['discount_percentage'] / 100)) ));
            $promo_discount+=$get_promo_discount['discount_percentage'];
        } else {
            $price_after_promo = $price_offer;
        }
        //--------------------------------------------
        //caluclate order information
        $qty=1;
        $price_after_offer=$real_price-($real_price*$offer_discount/100);
        $price_after_offer_and_promo= $price_after_offer - ($price_after_offer * $promo_discount/100);
        $price_after_discount= $price_after_offer_and_promo;
        $total_withDiscount=$qty * $price_after_discount;
        $total_withOutDiscount=$qty* $real_price;
        $pro_Discount_val=$total_withOutDiscount-$total_withDiscount;
        
        $total_price_before_discount+=$total_withOutDiscount;
        $allPros_total_discount+=$pro_Discount_val;
        $total_price+=$total_withDiscount;
        
        $product_title=$get_pro_name['title'];
        $pro_items_info.=" <tr>
                                <td>$product_title</td>
                                <td>" .number_format($total_withOutDiscount,2,'.',',') . "$</td>
                                <td>$pro_Discount_val$</td>
                                <td>" .number_format($total_withDiscount,2,'.',',') . "$</td>
                             </tr>";
        //----------------------------------------------- 
        //echo "<br/> price after offer and promo = $price_after_promo <br/> ";
        //save products in purchase order
        $insert_id = $this->fpdo->insertInto('purchase_order_products')->values(array('purchase_order_id'=>$order_insert_id,'product_id'=>$product_id,'product_price_id'=>$product_price_id,'product_price'=>$real_price,'offer_discount'=>$offer_discount,'promo_discount'=>$promo_discount,'unit_value'=>$unit_value))->execute();
        
    }
    
    //-------------- Send email to admin -----------------
     $customer_id=$_SESSION['CUSTOMER_ID'];
     $customer_info = $this->fpdo->from("customers")->where("id='$customer_id'")->fetch();
     $payment_type_info=$this->fpdo->from("payment_types")->where("id='$payment_type'")->fetch();        
     $order_details="";
     $order_details.="<div class='box-body'>
                        <table class='table table-bordered po-table' style='width: 50%;'>
                            <tbody>
                                <tr><td>Customer:</td><td>".$customer_info['name']."</td></tr>
                                <tr><td>Payment Type:</td><td>".$payment_type_info['name']."</td></tr>
                                <tr><td>Order Date:</td><td>".date('Y-m-d')."</td></tr>
                            </tbody>
                        </table>
                        <div class='hr'><hr></div>
                        <table class='table table-bordered po-table'>
                            <thead>
                                <tr><th>Product Name</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Price after discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                ".$pro_items_info."
                                <tr></tr>    
                                <tr><td colspan='3' style='text-align:left'><b>Total Price</b></td> <td>".number_format($total_price_before_discount,2,'.',',')."</td> </tr>
                                <tr><td colspan='3' style='text-align:left'><b>Discount</b></td><td>".number_format($allPros_total_discount,2,'.',',')."</td></tr>
                                <tr><td colspan='3' style='text-align:left'><b>Final Price</b></td><td>".number_format($total_price,2,'.',',')."</td></tr>
                            </tbody>
                        </table>
                    </div>";
        //send new customer notification to admin
        //get recipient_email
        $mail_dts=$this->fpdo->from('mails')->where('id=5')->fetch();
        $recipient_email=$mail_dts['recipient_email'];
        $tags = array("{customer-name}" => $full_name, '{purchase-order-date}' => date("Y-m-d h:i:s"), '{purchase-order-details}' =>$order_details);
        $utils->sendMailC("info@voitest.com", $recipient_email, "New purchase order notification", "", 5, $tags);
    //----------------------------------------------------
   
    //generate page related with this purchase order.
    $static_widget_id= 21;
    $page_id = $this->fpdo->insertInto('cms_pages')->values(array('html'=>"##wid_start## ##wid_id_start##$static_widget_id##wid_id_end## ##wid_end##",'type'=>"generated",'lang'=>$pLang,'hidden'=>'1'))->execute();
    //add generate page_id to purchase order 
    $query = $this->fpdo->update("purchase_order")->set(array('page_id' =>$page_id))->where('id', $order_insert_id)->execute();
        
     $_SESSION['OrderID']=$order_insert_id;
     unset($_SESSION['Shopping_Cart']);
     $utils->redirect(_PREF.$pLang."/page68/OrderDetails");
}
         
?>