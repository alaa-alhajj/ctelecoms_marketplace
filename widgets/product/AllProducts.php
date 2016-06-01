
<?php
$featured_products=$this->fpdo->from("products")->where("active='1'")->orderBy("id desc")->fetchAll();
$featured_block = "";
$fade_block = 200;
foreach ($featured_products as $products) {
    $product_id = $products['id'];
    $get_dynamic_price = $this->fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
    $dynamic_price_id = $get_dynamic_price['id'];
    $get_groups = explode(',', rtrim($get_dynamic_price['group_ids'], ','));
      if ($get_dynamic_price['type_id'] === '2') {
                $groups_select = "<select style='width:65%;float:left;margin-bottom: 10px;' name='groups' id='groups' class='form-control ProductGroups groups_cart_$product_id ProductGroups_$product_id' data-type='group' data-dynamic='" . $dynamic_price_id . "' data-product='" . $product_id . "'>";
                foreach ($get_groups as $groups) {
                    $get_title_g = $this->fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
                    $get_unit_name = $this->fpdo->from('pro_price_units')->where("id", $get_dynamic_price['unit_id'])->fetch();
                    $groups_select.="<option value='" . $groups . "'>" . $get_title_g['title'] ."</option>";
                }
                $groups_select.="</select> <span style='float:right;margin-top:6px'>  " . $get_unit_name['title'] . "</span>";
            } elseif ($get_dynamic_price['type_id'] === '1') {
                foreach ($get_groups as $groups) {
                    $get_groupName = $this->fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
                    $get_unit_name = $this->fpdo->from('pro_price_units')->where("id", $get_dynamic_price['unit_id'])->fetch();
                    $get_max_min = $this->fpdo->from('pro_price_groups')
                                    ->select("max(title) as maxval,min(title) as minval")->where('id in (' . rtrim($get_dynamic_price['group_ids'], ',') . ')')->fetch();
                    $groups_select = "<input type='number' max='" . $get_max_min['maxval'] . "' min='0' value='" . $get_groupName['title'] . "' name='groups_cart' class='form-control groups_cart groups_cart_" . $product_id . "' data-duration='$duration_id' data-dynamic='$dynamic_price_id' data-product='$product_id' data-type='unit' style='width:65%;float:left;margin-bottom: 10px;'> <span style='float:right;margin-top:6px'>" . $get_unit_name['title'] . "</span>";
                }
            }
    

 

    $get_durations = explode(',', rtrim($get_dynamic_price['duration_ids'], ','));
    $durations_select = "<select name='durations' id='durations' class='form-control ProductDurations ProductDurations_$product_id' data-dynamic='" . $dynamic_price_id . "' data-product='" . $product_id . "'>";

    foreach ($get_durations as $duration) {
        $get_title_du = $this->fpdo->from('pro_price_duration')->where("id='$duration'")->fetch();
        $durations_select.="<option value='" . $duration . "'>" . $get_title_du['title'] . "</option>";
    }
    $durations_select.="</select>";
    $pro_photo = $this->viewPhoto($products['photos'], 'crop', 104, 156, 'img', 1, $_SESSION['dots'], 1, '', 'img-responsive');

    $check_session_ShoppinCart = $_SESSION['Shopping_Cart'];

    if ($check_session_ShoppinCart[$product_id] != "") {
        $button_class = "RemovefromCart";
        $cart_icon = '  <i class="demo-icon iconc-cart icon-style shopCartIcon"></i>';
    } else {
        $button_class = "addToCartSmall";
        $cart_icon = '<i class="demo-icon iconc-cart-plus icon-style shopCartIcon"></i>';
    }
    $check_session_compare = $_SESSION['compareIDs'];

    if ($check_session_compare[$product_id] != "") {
        $class_compare = 'removeFromCompare';
        $compare_icon = '<i class="demo-icon iconc-compare icon-style "></i>';
    } else {
        $class_compare = "addToCompare";
        $compare_icon = '<i class="demo-icon iconc-compare-plus icon-style "></i>';
    }


    $link = _PREF . $_SESSION['pLang'] . "/page" . $products['page_id'] . "/" . $this->rewriteFilter($products['title']);
    $featured_block.=' <div class="col-sm-4">
                        <div class="product-block product-all wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="' . $fade_block . 'ms">
                            <div class="title"><a href="' . $link . '">' . $products['title'] . '</a></div>
                            <div class="row row-nomargin product-details">
                                <div class="col-xs-6">
                                    <a href=""> ' . $pro_photo . '</a>
                                </div>
                                <div class="col-xs-6 select-price">
                                    <div class="col-xs-12 nopadding">
                                       ' . $durations_select . '
                                    </div>
                                    <div class="col-xs-12 nopadding">
                                      ' . $groups_select . '
                                    </div>
                                    <span class="product_price_' . $product_id . '  relative price"></span>
                                </div>
                                <div class="col-xs-12 nopadding">
                                    <div class="product-brief">
                                      ' . $this->limit(strip_tags($products['brief']), 66) . '
                                    
                                    </div>
                                </div>
                            <div class="product-buttons">
                                <div class="row row-nomargin">
                                    <div class="col-xs-4 nopadding block-btn">
                                        <a href="' . $link . '">
                                       <div class="icon">                                            
                                  <i class="demo-icon iconc-more1 icon-style"></i>
                                   </div>
                                     </a>
                                    </div>
                                    <div class="col-xs-4 nopadding block-btn">
                                     <a href="javascript:;" class="' . $class_compare . '"  data-id="' . $product_id . '">
                                               <div class="load-img" style="display:none; margin-top: 16px;" data-id="'.$product_id.'">
                    <img src="'._ViewIMG .'loading_small.gif" alt="" class="img-responsive" />
                </div>
                                    <div class="icon">
                                       
                                  ' . $compare_icon . ' 
                                      
                                    
                                    </div></a>
                                    </div>
                                    <div class="col-xs-4 nopadding block-btn relative" style="overflow: hidden">
                                    
                                        <a href="javascript:;" class="' . $button_class . '"  data-id="' . $product_id . '">
                                             <div class="load-img" style="display:none; margin-top: 16px;" data-id="'.$product_id.'">
                    <img src="'._ViewIMG .'loading_small.gif" alt="" class="img-responsive" />
                </div>
                                             <div class="icon">
                                            ' . $cart_icon . '
                                                </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> </div>
                    </div>';

    $fade_block = $fade_block + 200;

    // unset($_SESSION['Shopping_Cart']);
}
?>
 <div class='block top50'>
  <div class="row"><div class="col-sm-12">
                        <h1 class='h-style'><a href=''>ALL PRODUCTS</a></h1>
                    </div></div>
  <div class='row '>
<?=$featured_block?>
</div>
 </div>