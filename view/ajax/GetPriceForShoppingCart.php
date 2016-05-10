<?php
include('../../view/common/top.php');
@session_start();
$duration = $_REQUEST['duration'];
$group = $_REQUEST['group'];
$dynamic_id = $_REQUEST['dynamic'];
$product_id = $_REQUEST['product_id'];
$type = $_REQUEST['type'];
if ($type === 'group') {
    $get_price = $fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration' and `group_id`='$group'")->fetch();

    $value = $get_price['value'];
    $group_i=$group;
} elseif ($type === 'unit') {
    $get_pro_Groups = $fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
    $groups = explode(',', rtrim($get_pro_Groups['group_ids'], ','));
    foreach ($groups as $group_id) {
        $check_group = $fpdo->from('pro_price_groups')->where("id='$group_id'")->fetch();
        if ($check_group['title'] <= $group) {
            
            $get_price = $fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration' and `group_id`='$group_id'")->fetch();
            $value = $get_price['value'];
             $group_i=$group_id;
        }
    }
}
$shopping_cart = $_SESSION['Shopping_Cart'];
$pr = $shopping_cart[$product_id];
$pr['group_id'] = $group_i;
$shopping_cart[$product_id] = $pr;
$_SESSION['Shopping_Cart'] = $shopping_cart;
echo json_encode(array($value));
?>