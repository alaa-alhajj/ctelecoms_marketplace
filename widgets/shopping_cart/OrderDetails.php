<div class="row row-nomargin">
    <div class="col-xs-3">
        <ul id="shopping-cart">
            <li ><a href="#">Shopping Cart</a></li>
            <?   if ($_SESSION['CUSTOMER_ID'] == "") {?>
            <li><a href="#">Checkout</a></li>
            <?}?>
            <li><a href="#">Payment</a></li>
            <li class="active-shopping"><a href="#">Order Details</a></li>
        </ul>
    </div>
    <div class="col-xs-9">
        <?
       $order_id=$_SESSION['OrderID'];
       $customer_id=$_SESSION['CUSTOMER_ID'];
        global $utils;
        $customer_info = $this->fpdo->from("customers")->where("id='$customer_id'")->fetch();
         //get order details 
        $order_info = $this->fpdo->from("purchase_order")->where("id='$order_id'")->fetch();
        $order_products_info = $this->fpdo->from("purchase_order_products")->where("purchase_order_id='$order_id'")->fetchAll();
        $payment_type_id=$order_info['payment_type'];
        $payment_type_info=$this->fpdo->from("payment_types")->where("id='$payment_type_id'")->fetch();
        //print_r($order_products_info);
        ?>
        <div class="col-sm-12">
            <div class="col-sm-6">
                <b>Order ID :</b><?=$order_info['id']?><br/>
                <b>Order Date :</b> <?=$order_info['order_date']?> <br/>
                <b>User Name :</b> <?=$customer_info['name']?><br/>
                <b>Address : </b><?=$customer_info['adress']?><br/>
                <b>Payment Type :</b><?=$payment_type_info['name']?><br/>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
        <div class="col-sm-12">
            <hr>
        </div>
        <table ID="list" width='100%' class="table table-stripped table-valign-middle table-striped ShoppingCartTable">
            <thead>
                <tr>
                    <th width='60%'>Item</th>
                    <th>Price</th>
                    <th>Price after discount</th>
                    <th>Group</th>
                   
                    <th>Total</th> 
                    <th>Discount value</th>
                    <th>Total after discount</th> 
                </tr>
            </thead>
            <tbody>
                <?
                $total_price_before_discount = 0;
                $allPros_total_discount = 0;
                
                foreach ($order_products_info as $product) { //purchase order details 
                    
                    $product_id = $product['product_id'];
                    $product_price_id = $product['product_price_id'];
                    $product_price = $product['product_price']; // price affte offers and applay promo code
                    $offer_discount=$product['offer_discount'];
                    $promo_discount=$product['promo_discount'];
                    
                    $get_pro_name = $this->fpdo->from("products")->where("id='$product_id'")->fetch();
                    $product_title=$get_pro_name['title'];
                    $product_brief=$get_pro_name['brief'];
                    
                    $get_price = $this->fpdo->from('product_price_values')->where("id = '$product_price_id' ")->fetch();
                    $group_id=$get_price['group_id']; 
                    $dynamic_price_id=$get_price['dynamic_price_id']; 
                    $dynamic_price_info = $this->fpdo->from('product_dynamic_price')->where("id='$dynamic_price_id'")->fetch();
                    $unit_id=$dynamic_price_info['unit_id'];
                    $units_info = $this->fpdo->from('pro_price_units')->where("id='$unit_id'")->fetch();
                    $units_title=$units_info['title'];
                    
                    $get_title_g = $this->fpdo->from('pro_price_groups')->where("id='$group_id'")->fetch();
                    if($product['unit_value'] !=""){
                       $group_title= $product['unit_value'];
                    }else{
                    $group_title=$get_title_g['title'];
                    }
                    
                    //print_r($product);
                    $qty=1;
                    $price_after_offer=$product_price-($product_price*$offer_discount/100);
                    $price_after_offer_and_promo= $price_after_offer - ($price_after_offer * $promo_discount/100);
                    $price_after_discount= $price_after_offer_and_promo;
                    $total_withDiscount=$qty * $price_after_discount;
                    $total_withOutDiscount=$qty* $product_price;
                    $pro_Discount_val=$total_withOutDiscount-$total_withDiscount;
                    
                    echo "<tr id='$product_id'>";
                    echo "<td>
                            <strong>$product_title</strong><br/>
                            <p>$product_brief</p>    
                          </td>";
                    echo "<td>" .number_format( $get_price['value'],2,'.',',') . "$</td>";
                    echo "<td>" .number_format($price_after_discount,2,'.',',') . " $</td>";
                    echo "<td>".$group_title." ".$units_title."</td>";
                 
                    echo "<td>" .number_format($total_withOutDiscount,2,'.',',') . "$</td>";
                    echo "<td>$pro_Discount_val$</td>";
                    echo "<td>" .number_format($total_withDiscount,2,'.',',') . "$</td>";
                    echo "</tr>";
                    $allPros_total_discount+=$pro_Discount_val;
                    $total_price+=$total_withDiscount;
                    $total_price_before_discount+=$total_withOutDiscount;
                }
                if ($_SESSION['PROMO_CODE'] != "") {
                    $class_add_promo = 'display-none';
                } else {
                    $class_promo = 'display-none';
                }

                echo "</tbody>";
                echo "<tfoot>";
                echo "<tr><td colspan='8' ><b>Total price before discount:</b> $<span class='TotalPriceBeforeDiscount'>".number_format($total_price_before_discount,2,'.',',')."<span></td></tr>"
                . "<tr><td colspan='8' ><b>Total discount:</b> $<span class='DiscountOrderCart'>".number_format($allPros_total_discount,2,'.',',')."<span></td></tr>"
                . "<tr><td colspan='8' ><b>Total price after discount:</b> $<span class='TotalPriceCart'>".number_format($total_price,2,'.',',')."<span></td></tr>"
                . "</tfoot>";
                
                ?>


        </table>

    </div>
</div>
