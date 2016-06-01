<?php
include('../../view/common/top.php');
@session_start();
$shop_list = "";
global $utils;
$shopping_cart = $_SESSION['Shopping_Cart'];
foreach ($shopping_cart as $key => $product) {
    $product_id = $product['pro_id'];
    $duration_id = $product['duration_id'];
    $duration_name = $fpdo->from("pro_price_duration")->where("id='$duration_id'")->fetch();
    $group_id = $product['group_id'];
    $get_pro_name = $fpdo->from("products")->where("id='$product_id'")->fetch();
    $photos = explode(',', $get_pro_name['photos']);
    $product_photo = $utils->viewPhoto($photos[0], 'crop', 50, 50, 'img', 1, $_SESSION['dots'], 1, '');
    $shop_list.='  <div class="col-sm-12">
                                        <div class="row in-cart">
                                            <div class="col-xs-3 nopadding">' . $product_photo . '</div>
                                            <div class="col-xs-7 "><span>' . $get_pro_name['title'] . '</span></div>
                                            <div class="col-xs-2 nopadding">
                                                <a href="javascript:;" class="RemovefromCart" data-id="'.$product_id.'"><i class="fa fa-times" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>';
    
      
}
  $shop_list.='<div class="col-sm-12 show-Cart-btn">
                    <a href="' . _PREF . $_SESSION['pLang'] . "/page50/Shopping-Cart" . '" class="btn-cart">Show Cart</a>
                </div>';
  if(count($_SESSION['Shopping_Cart']) > 0){
echo json_encode($shop_list);
  }else{
      $shop_list="<div class='col-sm-12'><span class='empty-cart'>You Cart is Empty<span></div>";
      echo json_encode($shop_list);
  }
?>