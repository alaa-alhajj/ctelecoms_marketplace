<?php
include('../../../view/common/top_ajax.php');

$product_id = $_REQUEST['product'];
$get_data=$_REQUEST['get_data'];
 $get_details=$fpdo->from('products')->where("id ='$product_id'")->fetch();
  
if($get_data === "resources"){
     echo $get_details['resources'];
}else{
    echo 'a';
}



?>