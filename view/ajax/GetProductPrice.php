<?php
include('../../view/common/top.php');

$duration = $_REQUEST['duration'];
$group=$_REQUEST['group'];
$dynamic_id=$_REQUEST['dynamic'];
$product_id=$_REQUEST['product'];
$type=$_REQUEST['type'];
$get_price=$fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration' and `group_id`='$group'")->fetch();
if ($type === 'group') {
    $value=$get_price['value'];
}
elseif($type === 'unit'){
      $get_pro_Groups = $fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
    $groups = explode(',', rtrim($get_pro_Groups['group_ids'], ','));
    sort($groups);
    foreach ($groups as $group_id) {
        $check_group = $fpdo->from('pro_price_groups')->where("id='$group_id'")->fetch();
        if ($group <= $check_group['title']) {
            $get_price = $fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration' and `group_id`='$group_id'")->fetch();
            $value = $get_price['value'];
            break;  
        }
    }
        $get_max_min = $fpdo->from('product_price_values')->select("max(value) as maxval,min(value) as minval")->where("`dynamic_price_id`='" . $dynamic_id . "' and `duration_id`='$duration'")->fetch();
}


echo json_encode(array($value,$product_id,$get_max_min['maxval'],$get_max_min['minval'],$type));


?>