<div class="row row-nomargin">
    <div class="col-xs-3">
        <ul id="shopping-cart">
            <li class="active-shopping"><a href="#">Shopping Cart</a></li>
            <?
            if ($_SESSION['CUSTOMER_ID'] == "") {
                ?>
                <li><a href="#">Checkout</a></li>
            <? } ?>
            <li><a href="#">Payment</a></li>
            <li><a href="#">Order Details</a></li>

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
                    <th></th>
                    <th>Item</th>
                    <th></th>
                    <th>Group</th>
                    <th>Price</th>    
                    <th>Price after discount</th>     
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?
                $total_price_before_discount = 0;
                $total_discount = 0;
                foreach ($shopping_cart as $key => $product) {
                    $product_id = $product['pro_id'];
                    $duration_id = $product['duration_id'];
                    $duration_name = $this->fpdo->from("pro_price_duration")->where("id='$duration_id'")->fetch();
                    $group_id = $product['group_id'];
                    $get_pro_name = $this->fpdo->from("products")->where("id='$product_id'")->fetch();
                    $photos = explode(',', $get_pro_name['photos']);
                    $product_photo = $utils->viewPhoto($photos[0], 'crop', 50, 50, 'img', 1, $_SESSION['dots'], 1, '');

                    $get_dynamic_id = $this->fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
                    $dynamic_id = $get_dynamic_id['id'];
                    $get_price = $this->fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration_id' and `group_id`='$group_id'")->fetch();
                    $get_groups = explode(',', rtrim($get_dynamic_id['group_ids'], ','));
                    if ($get_dynamic_id['type_id'] === '2') {
                        $groups_select = "<select name='groups_cart' id='groups_cart' data-duration='$duration_id' data-dynamic='$dynamic_id' data-product='$product_id' data-type='group'>";
                        foreach ($get_groups as $groups) {
                            $get_title_g = $this->fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
                            $get_unit_name = $this->fpdo->from('pro_price_units')->where("id", $get_dynamic_id['unit_id'])->fetch();
                            $selected = "";
                            if ($shopping_cart[$product_id]['group_id'] === $groups) {
                                $selected = "selected='selected'";
                            }
                            $groups_select.="<option value='" . $groups . "' $selected>" . $get_title_g['title'] ." ".$get_unit_name['title']. "</option>";
                        }
                        $groups_select.="</select>";
                    } elseif ($get_dynamic_id['type_id'] === '1') {
                        $get_groupName = $this->fpdo->from('pro_price_groups')->where("id='$group_id'")->fetch();
                         $get_unit_name = $this->fpdo->from('pro_price_units')->where("id", $get_dynamic_id['unit_id'])->fetch();
                        $groups_select = "<input type='text' value='" . $get_groupName['title'] . "' name='groups_cart' id='groups_cart' data-duration='$duration_id' data-dynamic='$dynamic_id' data-product='$product_id' data-type='unit' style='width:50%'> ".$get_unit_name['title'];
                    }
                    //print_r($product);

                    $check_offers = $this->fpdo->from('offers')->where("product_ids like '%" . $product_id . ",%'")->fetch();
                    $price_offer = ($get_price['value']) - ((($get_price['value']) * ($check_offers['discount_percentage'] / 100) ));
                    $total_discount+=$check_offers['discount_percentage'];

                    if ($_SESSION['PROMO_CODE'] != "") {
                        //    unset($_SESSION['PROMO_CODE']);
                        $get_promo_discount = $this->fpdo->from('promo_codes')->where("id='" . $_SESSION['PROMO_CODE'] . "' and product_ids like '%" . $product_id . ",%'")->fetch();

                        $price_after_promo = ($price_offer) - ((($price_offer) * ($get_promo_discount['discount_percentage'] / 100) ));
                        $discount_promo = $get_promo_discount['discount_percentage'];
                        $total_discount+=$discount_promo;
                    } else {
                        $price_after_promo = $price_offer;
                    }
                  
                    $Check_AddOns_for_button = explode(',', rtrim($get_pro_name['add_ons_pro_ids'], ','));
                    $show_button=0;
                    foreach ($Check_AddOns_for_button as $checkA) {
                         if($_SESSION['Shopping_Cart'][$checkA]!=""){}
                         else{
                            $show_button++;
                        }
                    }
                   
                    $add_ons = "";
                    if ($get_pro_name['add_ons_pro_ids'] != "" && $show_button>0) {

                        $add_ons = "<a href='javascript:;' class='SelectAddons' data-id='" . $product_id . "'>Select Add-ons</a>";
                    }

                  
                    echo "<tr id='$product_id'>";
                    echo "<td>" . $product_photo . "</td>";
                    echo "<td><a href='$link23'>" . $get_pro_name['title'] . " - " . $duration_name['title'] . "</a></td>";
                    echo "<td >" . $add_ons . "</td>";
                    echo "<td class='rel-div'>$groups_select</td>";
                    echo "<td id='price2_" . $product_id . "' class='rel-div'>" . number_format($get_price['value'], 2, '.', ',') . "</td>";
                    echo "<td class='rel-div' id='price_" . $product_id . "' data-offer='" . $check_offers['discount_percentage'] . "' data-promo='" . $get_promo_discount['discount_percentage'] . "'>" . number_format($price_after_promo, 2, '.', ',') . "</td>";
                    echo "<td><a href='javascript:void(0);' class='RemovefromCart' data-id='$product_id' data-remove='tr'><span class='fa fa-remove' ></span></a></td>";
                    echo "</tr>";
                    $total_price+=$price_after_promo;
                    $total_price_before_discount+=$get_price['value'];
                }
                if ($_SESSION['PROMO_CODE'] != "") {
                    $class_add_promo = 'display-none';
                } else {
                    $class_promo = 'display-none';
                }

                echo "</tbody>";
                echo "<tfoot>";
                if ($_SESSION['CUSTOMER_ID'] != "") {
                    echo "<tr class='thanksPromoMsg $class_promo alert alert-success'><td colspan='7' >Thanks. Your promo code has been added successfully </td></tr>"
                    . "<tr class='ErrorPromoMsg display-none alert alert-danger'><td colspan='7' >Sorry, Your code is incorrect. </td></tr>"
                    . "<tr class='promoCodeTr $class_add_promo rel-div'><td></td><td>Promocode<br>If you have promocode please enter it here</td><td></td>"
                    . "<td colspan='2'><input type='text' id='promoCode-value' class='form-control'></td><td colspan='2'><button class='btn btn-default' id='applay_promocode'>applay</button></td>"
                    . "</tr>";
                }


                echo "<tr><td colspan='7' ><b>Total Discount:</b> $<span class='DiscountOrderCart'>" . number_format($total_discount, 2, '.', ',') . "<span></td></tr>"
                . "<tr><td colspan='7' ><b>Total Price before discount:</b> $<span class='TotalPriceBeforeDiscount'>" . number_format($total_price_before_discount, 2, '.', ',') . "<span></td></tr>"
                . "<tr><td colspan='7' ><b>Total Price after discount:</b> $<span class='TotalPriceCart'>" . number_format($total_price, 2, '.', ',') . "<span></td></tr>"
                . "</tfoot>";
                $res2.="<div class='pro-offer row'>";


                $res2.="</div>";
                ?>


        </table>
        <div class="tableCartResponsive col-sm-12 nopadding">
            <?= $res2;
            ?>

            <div class="row row-nomargin">
                <div class="col-sm-6 nopadding"><a href="#" class="btn btn-danger">Continue shopping</a></div>
                <div class="col-sm-6 nopadding"><a href="<?= _PREF . $_SESSION['pLang'] . "/page57/Checkout" ?>" class="btn btn-default right-button" >Checkout</a></div>
            </div>
        </div>

    </div>
</div>
<? include '../../widgets/modal/ProductsAddOns.php'; ?>