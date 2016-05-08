<?php
$page_id = $_REQUEST['id'];
$get_product_id = $this->fpdo->from('products')->where("page_id='" . $_REQUEST['id'] . "'")->fetch();
$product_id = $get_product_id['id'];
$get_product_details = $this->fpdo->from("products")->where("id='$product_id'")->fetch();
$category_name = $this->fpdo->from('product_category')->where("id", $get_product_details['cat_id'])->fetch();

$get_dynamic_price = $this->fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
$dynamic_price_id = $get_dynamic_price['id'];
$get_groups = explode(',', rtrim($get_dynamic_price['group_ids'], ','));
$groups_select = "<select name='groups' id='groups' >";
foreach ($get_groups as $groups) {
    $get_title_g = $this->fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
    $get_unit_name = $this->fpdo->from('pro_price_units')->where("id", $get_dynamic_price['unit_id'])->fetch();
    $groups_select.="<option value='" . $groups . "'>" . $get_title_g['title'] . ' ' . $get_unit_name['title'] . "</option>";
}
$groups_select.="</select>";

$get_durations = explode(',', rtrim($get_dynamic_price['duration_ids'], ','));
$durations_select = "<select name='durations' id='durations'>";
foreach ($get_durations as $duration) {
    $get_title_du = $this->fpdo->from('pro_price_duration')->where("id='$duration'")->fetch();
    $durations_select.="<option value='" . $duration . "'>" . $get_title_du['title'] . "</option>";
}
$durations_select.="</select>";
?>
<input type="hidden" id="dynamic_price_id" value="<?= $dynamic_price_id ?>" >
<input type="hidden" id="product_id" value="<?= $product_id ?>" >
<input type="hidden" id="customer_login" value="<?= $product_id ?>" >
<div class="row row-nomargin">


    <div class="col-sm-6">
        <?
       // print_r($_SESSION['Shopping_Cart']);
        $Check_product = $_SESSION['Shopping_Cart'];
        // print_r( $Check_product[$product_id]) ;
          if ($Check_product[$product_id] != "") {
                $show_add='display-none';
              $show_remove='';
             
          }else{
               $show_add='';
              $show_remove='display-none';
          }
        $photos = explode(',', $get_product_details['photos']);
        $images = "";
        $i = 0;
        foreach ($photos as $pro_photo) {
            if ($i === 1) {
                $width = 350;
                $height = 350;
                $class = "flright";
            } else {
                $width = 75;
                $height = 75;
                $class = "flLeft";
            }
            $bg = $this->viewPhoto($pro_photo, 'crop', $width, $height, 'img', 1, $_SESSION['dots'], 1, '', $class);
            if ($i <= 4) {
                $images.="<div>" . $bg . "</div>";
            }
            $i++;
        }


        echo $images;
        ?>

    </div>
    <div class="col-sm-6">
        <h1>
            <?= $get_product_details['title']; ?>
        </h1>
        <p> <?= $get_product_details['brief']; ?></p>
       
            <div class="RemovedFromCart <?=$show_remove?>">
                <div class='row row-nomargin product-price'>
                    <div class="col-sm-12 cart-button"><a href='javascript:;' class="RemoveToCart"><i class="fa fa-cart-plus" aria-hidden="true"></i><span> Remove From Cart</span></a></div>
                </div>
            </div>
      
            <div class="AddedToCart <?=$show_add?>">
                <div class='row row-nomargin'>
                    <div class="col-sm-6 nopadding"><span class='fontsize'>Duration &nbsp;</span><?= $durations_select; ?></div>
                    <div class="col-sm-6 nopadding"><span class='fontsize'>Number of users &nbsp;</span> <?= $groups_select; ?></div>
                </div>

                <div class='row row-nomargin product-price'>

                    <div class="col-sm-6">Price <span id="product_price"> </span></div>
                    <div class="col-sm-6 cart-button"><a href='javascript:;' class="addToCart"><i class="fa fa-cart-plus" aria-hidden="true"></i><span> Add to Cart</span></a></div>

                </div>
            </div>
     
        <div class="col-sm-12 margTop20 freeTrial-button"><a href=''>Free Trial</a></div>
    </div>
    <div class='clear'></div>
    <div class='block'>
        <div id="Details">
            <ul class="resp-tabs-list hor_1 product_details">
                <li data-id="<?= $product_id ?>" data-details='overview' data-request="Details_1" class='GetProductDetails'>Overview</li>
                <li data-id="<?= $product_id ?>" data-details='features' data-request="Details_2" class='GetProductDetails'>Features</li>
                <li data-id="<?= $product_id ?>" data-details='resources' data-request="Details_3" class='GetProductDetails'>Resources</li>
                <li data-id="<?= $product_id ?>" data-details='faq' data-request="Details_4" class='GetProductDetails'>FAQ</li>
                <li data-id="<?= $product_id ?>" data-details='review' data-request="Details_5" class='GetProductDetails'>Review</li>
                <li data-id="<?= $product_id ?>" data-details='addons' data-request="Details_6" class='GetProductDetails'>Add-ons</li>
            </ul>
            <div class="resp-tabs-container hor_1">


                <div id='overview' class="Details_1"> <!-- Features -->
                    <p></p>
                </div>
                <div id='features' class="Details_2"> <!-- Features -->
                    <p></p>
                </div>
                <div id='resources' class="Details_3">
                    <p></p>
                </div>
                <div id='faq' class="Details_4">
                    <p></p>
                </div>
                <div id='review' class="Details_5">
                    <p></p>

                </div>
                <div id='addons' class="Details_6">
                    <p></p>

                </div>
            </div>
        </div>
    </div>
    <?
    if ($get_product_details['related_pro_ids'] != "") {
        ?>
        <div class='col-sm-12'>
            <h1>Related Products</h1>
            <?
            $product_details = "<div class='row row-nomargin nopadding'>";
            $related_products = explode(',', rtrim($get_product_details['related_pro_ids'], ','));

            foreach ($related_products as $products_related) {
                if ($products_related != "") {
                    $get_pro = $this->fpdo->from('products')->where("id='$products_related'")->fetch();
                    $photos = explode(',', $get_pro['photos']);
                    $product_photo = $this->viewPhoto($photos[0], 'crop', 200, 200, 'img', 1, $_SESSION['dots'], 1, '', 'img-responsive width100');
                    $product_details.="<div class='col-sm-4 '>" . $product_photo . ""
                            . "<div>" . $get_pro['title'] . "</div>"
                            . "<div>aaaaaa</div>"
                            . "<div class='col-sm-12 nopadding'>"
                            . "<a href='' class='more-button'>MORE</a></div>"
                            . "</div>";
                }
            }
            $product_details.="</div>";
            echo $product_details;
            ?>
        </div>
    <? } ?>
</div>

<? include '../../widgets/modal/AddReview.php'; ?>