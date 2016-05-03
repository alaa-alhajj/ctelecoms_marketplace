<?php
include('../../../view/common/top_ajax.php');

$product_id = $_REQUEST['product'];
$customer_id = $_REQUEST['customer'];
$review = $_REQUEST['review'];

$fpdo->insertInto('customer_reviews')->values(array('`customer_id`' => $customer_id, '`product_id`' => $product_id, '`review`' => $review))->execute();
   
   echo 'Success Added Review';
?>