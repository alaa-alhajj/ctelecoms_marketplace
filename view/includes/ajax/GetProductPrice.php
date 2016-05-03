<?php
include('../../../view/common/top_ajax.php');

$duration = $_REQUEST['duration'];
$group=$_REQUEST['group'];
$dynamic_id=$_REQUEST['dynamic'];
$get_price=$fpdo->from('product_price_values')->where("`dynamic_price_id`='$dynamic_id' and `duration_id`='$duration' and `group_id`='$group'")->fetch();
$value=$get_price['value'];

echo $value." $";


?>