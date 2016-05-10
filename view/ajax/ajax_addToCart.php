<?php

include('../../view/common/top.php');


$pro_id = $_REQUEST['pro_id'];
$duration_id = $_REQUEST['duration_id'];
$group_id = $_REQUEST['group_id'];
$qty = $_REQUEST['qty'];
$add = $_REQUEST['add'];
if ($qty == '') { // set qty = 1 if don't qty value don't found
    $qty = 1;
}

$product = array('pro_id' => $pro_id, 'duration_id' => $duration_id, 'group_id' => $group_id, 'qty' => $qty);

//add product to shopping Cart session
$shopping_cart = $_SESSION['Shopping_Cart'];
$shopping_cart[$pro_id] = $product;
$_SESSION['Shopping_Cart'] = $shopping_cart;
if ($add != "") {
    $tr = "";
    $product_id = $pro_id;
    $duration_name = $fpdo->from("pro_price_duration")->where("id='$duration_id'")->fetch();
    $get_pro_name = $fpdo->from("products")->where("id='$product_id'")->fetch();
    $photos = explode(',', $get_pro_name['photos']);
    $product_photo = $utils->viewPhoto($photos[2], 'crop', 50, 50, 'img', 1, $_SESSION['dots'], 1, '');

    $get_dynamic_id = $fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
    $dynamic_id = $get_dynamic_id['id'];
    $get_price = $fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration_id' and `group_id`='$group_id'")->fetch();
    $get_groups = explode(',', rtrim($get_dynamic_id['group_ids'], ','));
    if ($get_dynamic_id['type_id'] === '2') {
        $groups_select = "<select name='groups_cart' id='groups_cart' data-duration='$duration_id' data-dynamic='$dynamic_id' data-product='$product_id' data-type='group'>";
        foreach ($get_groups as $groups) {
            $get_title_g = $fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
            $get_unit_name = $fpdo->from('pro_price_units')->where("id", $get_dynamic_id['unit_id'])->fetch();
            $selected = "";
            if ($shopping_cart[$product_id]['group_id'] === $groups) {
                $selected = "selected='selected'";
            }
            $groups_select.="<option value='" . $groups . "' $selected>" . $get_title_g['title'] ." ".$get_unit_name['title']. "</option>";
        }
        $groups_select.="</select>";
    } elseif ($get_dynamic_id['type_id'] === '1') {
        $get_groupName = $fpdo->from('pro_price_groups')->where("id='$group_id'")->fetch();
          $get_unit_name = $fpdo->from('pro_price_units')->where("id", $get_dynamic_id['unit_id'])->fetch();
        $groups_select = "<input type='text' value='" . $get_groupName['title'] . "' name='groups_cart' id='groups_cart' data-duration='$duration_id' data-dynamic='$dynamic_id' data-product='$product_id' data-type='unit' style='width:50%'>".$get_unit_name['title'];
    }
    //print_r($product);

    $check_offers = $fpdo->from('offers')->where("product_ids like '%" . $product_id . ",%'")->fetch();
    $price_offer = ($get_price['value']) - ((($get_price['value']) * ($check_offers['discount_percentage'] / 100) ));
    $total_discount+=$check_offers['discount_percentage'];

    if ($_SESSION['PROMO_CODE'] != "") {
        //    unset($_SESSION['PROMO_CODE']);
        $get_promo_discount = $fpdo->from('promo_codes')->where("id='" . $_SESSION['PROMO_CODE'] . "' and product_ids like '%" . $product_id . ",%'")->fetch();

        $price_after_promo = ($price_offer) - ((($price_offer) * ($get_promo_discount['discount_percentage'] / 100) ));
        $discount_promo = $get_promo_discount['discount_percentage'];
        $total_discount+=$discount_promo;
    } else {
        $price_after_promo = $price_offer;
    }
    $Check_AddOns_for_button = explode(',', rtrim($get_pro_name['add_ons_pro_ids'], ','));
    $show_button = 0;
    foreach ($Check_AddOns_for_button as $checkA) {
        if ($_SESSION['Shopping_Cart'][$checkA] != "") {
            
        } else {
            $show_button++;
        }
    }
    $add_ons = "";
    if ($get_pro_name['add_ons_pro_ids'] != "" && $show_button > 0) {
        $add_ons = "<a href='javascript:;' class='SelectAddons' data-id='" . $product_id . "'>Select Add-ons</a>";
    }
    $tr.= "<tr id='$pro_id'>"
            . "<td>" . $product_photo . "</td>"
            . "<td><a href='$link23'>" . $get_pro_name['title'] . " - " . $duration_name['title'] . "</a></td>"
            . "<td>" . $add_ons . "</td>"
            . "<td>$groups_select</td>"
            . "<td id='price2_" . $pro_id . "' class='rel-div'>" . number_format($get_price['value'], 2, '.', ',') . "</td>"
            . "<td class='rel-div' id='price_" . $pro_id . "' data-offer='" . $check_offers['discount_percentage'] . "' data-promo='" . $get_promo_discount['discount_percentage'] . "'>" . number_format($price_after_promo, 2, '.', ',') . "</td>"
            . "<td><a href='javascript:void(0);' class='RemovefromCart' data-id='$pro_id' data-remove='tr'><span class='fa fa-remove' ></span></a></td>"
            . "</tr>";
    $total_price+=$price_after_promo;
    $total_price_before_discount+=$get_price['value'];
    echo json_encode($tr);
}
