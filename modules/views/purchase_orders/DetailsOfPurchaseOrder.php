<?php

include 'config.php';
include '../../common/header.php';

$get_purchase_order = $fpdo->from('purchase_order')->select("customers.name as cuname,payment_types.name as paymentname")
                ->leftJoin("customers on customers.id=purchase_order.customer_id")
                ->leftJoin("payment_types on payment_types.id=purchase_order.payment_type")
                ->where("purchase_order.id='" . $_REQUEST['id'] . "'")->fetch();

$details = "";
$details = '<div class="box box-danger form-horizontal">'
        . '<div class="box-body">';
$details.="<table class='table table-bordered po-table'  style='width: 25%;'>"
        . "<tbody>"
        . "<tr><td>Customer:</td><td>" . $get_purchase_order['cuname'] . "</td></tr>"
        . "<tr><td>Payment Type:</td><td>" . $get_purchase_order['paymentname'] . "</td></tr>"
        . "<tr><td>Order Date:</td><td>" . $get_purchase_order['order_date'] . "</td></tr>"
        . "</tbody>"
        . "</table>";

$details.="<div class='hr'><hr></div>";
$details.="<table class='table table-bordered po-table'>"
        . "<thead>"
        . "<th>Product Name</th>"
        . "<th>Price</th>"
        . "<th>Discount</th>"
        . "<th>Price after discount</th>"
        . "</thead>"
        . "<tbody>";

$get_products = $fpdo->from('purchase_order_products')
        ->select("products.title as prtitle,purchase_order_products.product_price as price,purchase_order_products.promo_discount as promo_discount,purchase_order_products.offer_discount as offer_discount,products.id as prid,purchase_order.promo_code as promo_id")
        ->leftJoin("purchase_order on purchase_order.id = purchase_order_products.purchase_order_id")
        ->leftJoin("products on products.id=purchase_order_products.product_id")
        ->leftJoin("product_price_values on product_price_values.id=purchase_order_products.product_price_id")
        ->where("purchase_order.id='" . $_REQUEST['id'] . "'")
        ->fetchAll();
$total_before_discounts = 0;
$total_after_discounts = 0;
$total_discounts = 0;
foreach ($get_products as $products) {
    $discount = 0;
    /*
      // promo code
      $get_promo_discount = $fpdo->from('promo_codes')
      ->where("id='" . $products['promo_id'] . "'")
      ->fetch();
      $total_price = ((($products['price']) - ($get_promo_discount['discount_percentage'] / 100) ));
      $discount+=$get_promo_discount['discount_percentage'];
      //offer
      $check_offers = $fpdo->from('offers')->where("product_ids like '%" . $products['prid'] . ",%'")->fetch();
      $price = ((($total_price) - ($check_offers['discount_percentage'] / 100) ));
      $discount+=$check_offers['discount_percentage'];
     * */
$tot_disc_per_product=$products['promo_discount']+$products['offer_discount'];
     $price_after_offer=$products['price']-($products['price']*$products['offer_discount']/100);
                    $price_after_offer_and_promo= $price_after_offer - ($price_after_offer * $products['promo_discount']/100);
                    $price_after_discount= $price_after_offer_and_promo;
    $details.="<tr>";
    $details.="<td>" . $products['prtitle'] . "</td>";

    $details.="<td>" . number_format($products['price'], 2, '.', ',') . "</td>";
    $details.="<td>" . $tot_disc_per_product . "</td>";
    $details.="<td>" . number_format($price_after_discount, 2, '.', ',') . "</td>";
    $details.="</tr>";
    $total_before_discounts+=$products['price'];
    $total_after_discounts+=$price_after_discount;
    $total_discounts+=$tot_disc_per_product;
}
$details.="<tr><td colspan='3' style='text-align:right'><b>Total Price</b></td><td>" . number_format($total_before_discounts, 2, '.', ',') . "</td></tr>";
$details.="<tr><td colspan='3' style='text-align:right'><b>Discount</b></td><td>" . number_format($total_discounts, 2, '.', ',') . "</td></tr>";
$details.="<tr><td colspan='3' style='text-align:right'><b>Final Price</b></td><td>" . number_format($total_after_discounts, 2, '.', ',') . "</td></tr>";
$details.="</tbody>"
        . "</table>";


$details.='<div class="hr"><hr></div>';
$details.='<div class="col-sm-12 nopadding"> <button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp; </div>';

$details.="<div></div>";
echo $details;
include_once '../../common/footer.php';
