<div class="row row-nomargin">
    <div class="col-xs-3">
        <ul id="shopping-cart">
            <li class="active-shopping"><a href="#">Shopping Cart</a></li>
            <li><a href="#">Checkout</a></li>
            <li><a href="#">Payment</a></li>
            <li><a href="#">Order Details</a></li>

        </ul>
    </div>
    <div class="col-xs-9">
        <?
        $shopping_cart = $_SESSION['Shopping_Cart'];
        // print_r($shopping_cart);
        global $utils;
        ?>

        <table ID="list" width='100%' class="table table-bordered table-valign-middle table-striped tableCartDesktop">
            <thead>
                <tr>
                    <th></th>
                    <th>Item</th>
                   
                    <th>Users</th>
                    <th>Price</th>                
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?
                $total_price = 0;
                foreach ($shopping_cart as $key => $product) {
                    $product_id = $product['pro_id'];
                    $duration_id = $product['duration_id'];
                    $group_id = $product['group_id'];
                    $get_pro_name = $this->fpdo->from("products")->where("id='$product_id'")->fetch();
                    $photos = explode(',', $get_pro_name['photos']);
                    $product_photo = $utils->viewPhoto($photos[2], 'crop', 50, 50, 'img', 1, $_SESSION['dots'], 1, '');

                    $get_dynamic_id = $this->fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
                    $dynamic_id = $get_dynamic_id['id'];
                    $get_price = $this->fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration_id' and `group_id`='$group_id'")->fetch();
                    $get_groups = explode(',', rtrim($get_dynamic_id['group_ids'], ','));
                    $groups_select = "<select name='groups_cart' id='groups_cart' data-duration='$duration_id' data-dynamic='$dynamic_id' data-product='$product_id'>";
                    foreach ($get_groups as $groups) {
                        $get_title_g = $this->fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
                        $get_unit_name = $this->fpdo->from('pro_price_units')->where("id", $get_dynamic_price['unit_id'])->fetch();
                        $groups_select.="<option value='" . $groups . "'>" . $get_title_g['title'] . "</option>";
                    }
                    $groups_select.="</select>";
                    print_r($product);
                    echo "<tr id='$product_id'>";
                    echo "<td>" . $product_photo . "</td>";
                    echo "<td><a href='$link23'>" . $get_pro_name['title'] . "</a></td>";
                    echo "<td>$groups_select</td>";
                    echo "<td id='price_".$product_id."'>" . $get_price['value'] . "</td>";
                    echo "<td><a href='javascript:void(0);' class='RemovefromCart' data-id='$product_id' data-remove='tr'><span class='fa fa-remove' ></span></a></td>";
                    echo "</tr>";
                    $total_price+=$get_price['value'];
                }
                echo "<tr><td colspan='6' ><b>Total Price:</b> $<span class='TotalPriceCart'>$total_price<span></td></tr>";
                $res2.="<div class='pro-offer row'>";


                $res2.="</div>";
                ?>

            </tbody>
        </table>
        <div class="tableCartResponsive col-sm-12 nopadding">
            <?= $res2 ?>
        </div>

    </div>
</div>
