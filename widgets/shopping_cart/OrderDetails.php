<div class="row row-nomargin">
    <div class="col-xs-3">
        <ul id="shopping-cart">
            <li ><a href="#">Shopping Cart</a></li>
            <li><a href="#">Checkout</a></li>
            <li><a href="#">Payment</a></li>
            <li class="active-shopping"><a href="#">Order Details</a></li>

        </ul>
    </div>
    <div class="col-xs-9">
        <?
        $shopping_cart = $_SESSION['Shopping_Cart'];
       //print_r($shopping_cart);
      
        global $utils;
        ?>

        <table ID="list" width='100%' class="table table-stripped table-valign-middle table-striped ShoppingCartTable">
            <thead>
                <tr>
                    <th width='60%'>Item</th>
                    <th>Price</th>
                    <th>Price after discount</th>
                    <th>Users</th>
                    <th>Total</th>     
                </tr>
            </thead>
            <tbody>
                <?
                $total_price_before_discount = 0;
                $total_discount = 0;
                
                foreach ($shopping_cart as $key => $product) {
                    $product_id = $product['pro_id'];
                    $duration_id = $product['duration_id'];
                    $group_id = $product['group_id'];
                    
                    $get_pro_name = $this->fpdo->from("products")->where("id='$product_id'")->fetch();
                    $product_title=$get_pro_name['title'];
                    $product_brief=$get_pro_name['brief'];
                    
                    
                    $get_dynamic_id = $this->fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
                    $dynamic_id = $get_dynamic_id['id'];
                    $get_price = $this->fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration_id' and `group_id`='$group_id'")->fetch();
                    $get_groups = explode(',', rtrim($get_dynamic_id['group_ids'], ','));
                     
                    $get_title_g = $this->fpdo->from('pro_price_groups')->where("id='$group_id'")->fetch();
                    $group_title=$get_title_g['title'];
                    //print_r($product);

                    $check_offers = $this->fpdo->from('offers')->where("(product_ids like '" . $product_id . ",%') || (product_ids like '," . $product_id . ",%')")->fetch();
                    $price_offer = ((($get_price['value']) - ($get_price['value']*($check_offers['discount_percentage'] / 100)) ));
                    
                    $product_discount=$get_price['value']*($check_offers['discount_percentage'] / 100);
                   

                    if ($_SESSION['PROMO_CODE'] != "") {
                        $get_promo_discount = $this->fpdo->from('promo_codes')->where("id='" . $_SESSION['PROMO_CODE'] . "'")->fetch();
                        $price_after_promo = ((($price_offer) - ($price_offer*($get_promo_discount['discount_percentage'] / 100)) ));
                        $product_discount+=$price_offer*($get_promo_discount['discount_percentage'] / 100);
                    } else {
                        $price_after_promo = $price_offer;
                    }
                    $pro_Discount_val=intval($group_title)*$product_discount;
                    $total_widthDiscount=intval($group_title)* $price_after_promo;
                    $total_widthOutDiscount=intval($group_title)* $get_price['value'];
                    echo "<tr id='$product_id'>";
                    echo "<td>
                            <strong>$product_title</strong><br/>
                            <p>$product_brief</p>    
                          </td>";
                    echo "<td>" .number_format( $get_price['value'],2,'.',',') . "$</td>";
                    echo "<td>" .number_format($price_after_promo,2,'.',',') . " $</td>";
                    echo "<td>$group_title</td>";
                    echo "<td>" .number_format($total_widthDiscount,2,'.',',') . "$</td>";
                    echo "</tr>";
                    $total_discount+=$pro_Discount_val;
                    $total_price+=$total_widthDiscount;
                    $total_price_before_discount+=$total_widthOutDiscount;
                }
                if ($_SESSION['PROMO_CODE'] != "") {
                    $class_add_promo = 'display-none';
                } else {
                    $class_promo = 'display-none';
                }

                echo "</tbody>";
                echo "<tfoot>";
                echo "<tr><td colspan='5' ><b>Total Discount:</b> $<span class='DiscountOrderCart'>".number_format($total_discount,2,'.',',')."<span></td></tr>"
                . "<tr><td colspan='5' ><b>Total Price before discount:</b> $<span class='TotalPriceBeforeDiscount'>".number_format($total_price_before_discount,2,'.',',')."<span></td></tr>"
                . "<tr><td colspan='5' ><b>Total Price after discount:</b> $<span class='TotalPriceCart'>".number_format($total_price,2,'.',',')."<span></td></tr>"
                . "</tfoot>";
                $res2.="<div class='pro-offer row'>";


                $res2.="</div>";
                ?>


        </table>
    </div>
</div>
