<?php
include('../../../view/common/top_ajax.php');

$product_id = $_REQUEST['product'];
$customer_id = $_REQUEST['customer'];
$rate_id = $_REQUEST['rate'];
$check = $fpdo->from('customer_rating')->where("customer_id='$customer_id' and product_id='$product_id'")->fetch();
if ($check['id'] == "") {
    $fpdo->insertInto('customer_rating')->values(array('`customer_id`' => $customer_id, '`product_id`' => $product_id, '`rate_id`' => $rate_id))->execute();
    $get_new_count = $fpdo->from('customer_rating')
                    ->select("count(id) as count_new")
                    ->where("product_id='$product_id' and rate_id='$rate_id'")->fetch();
    echo $get_new_count['count_new'];
}
?>