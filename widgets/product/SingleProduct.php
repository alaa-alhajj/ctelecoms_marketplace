<?php
@session_start();
$page_id = $_REQUEST['id'];
$get_product = $this->fpdo->from('products')->where("page_id=$page_id")->fetch();
$product_id = $get_product['id'];
$category_name = $this->fpdo->from('product_category')->where("id", $get_product['cat_id'])->fetch();
$pro_photo = $this->viewPhoto($get_product['photos'], 'crop', 228, 323, 'img', 1, $_SESSION['dots'], 1, '', 'img-responsive');
$photos_array = explode(',', $get_product['photos']);
$get_dynamic_price = $this->fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
$dynamic_price_id = $get_dynamic_price['id'];
$get_groups = explode(',', rtrim($get_dynamic_price['group_ids'], ','));
if ($get_dynamic_price['type_id'] === '2') {
    $groups_select = "<select name='groups' id='groups' class='form-control ProductGroups groups_cart_$product_id ProductGroups_$product_id' data-type='group' data-dynamic='" . $dynamic_price_id . "' data-product='" . $product_id . "'>";
    foreach ($get_groups as $groups) {
        $get_title_g = $this->fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
        $get_unit_name = $this->fpdo->from('pro_price_units')->where("id", $get_dynamic_price['unit_id'])->fetch();
        $groups_select.="<option value='" . $groups . "'>" . $get_title_g['title'] . "</option>";
    }
    $groups_select.="</select>";
} elseif ($get_dynamic_price['type_id'] === '1') {
    foreach ($get_groups as $groups) {
        $get_groupName = $this->fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
        $get_unit_name = $this->fpdo->from('pro_price_units')->where("id", $get_dynamic_price['unit_id'])->fetch();
        $get_max_min = $this->fpdo->from('pro_price_groups')
                        ->select("max(title) as maxval,min(title) as minval")->where('id in (' . rtrim($get_dynamic_price['group_ids'], ',') . ')')->fetch();
        $groups_select = "<input type='number' max='" . $get_max_min['maxval'] . "' min='" . $get_max_min['minval'] . "' value='" . $get_groupName['title'] . "' name='groups_cart' class='form-control groups_cart groups_cart_" . $product_id . "' data-duration='$duration_id' data-dynamic='$dynamic_price_id' data-product='$product_id' data-type='unit' >";
    }
}

$get_durations = explode(',', rtrim($get_dynamic_price['duration_ids'], ','));
$durations_select = "<select name='durations' id='durations' class='form-control ProductDurations ProductDurations_$product_id' data-dynamic='" . $dynamic_price_id . "' data-product='" . $product_id . "'>";

foreach ($get_durations as $duration) {
    $get_title_du = $this->fpdo->from('pro_price_duration')->where("id='$duration'")->fetch();
    $durations_select.="<option value='" . $duration . "'>" . $get_title_du['title'] . "</option>";
}
$durations_select.="</select>";
$Check_product = $_SESSION['Shopping_Cart'];
if ($Check_product[$product_id] != "") {
    $show_add = 'display-none';
    $show_remove = '';
} else {
    $show_add = '';
    $show_remove = 'display-none';
}
$compare_session = $_SESSION['compareIDs'];
if ($compare_session[$product_id] != "") {
    $showAddToCompare = 'display-none';
    $removeFromCompare = "";
} else {
    $showAddToCompare = '';
    $removeFromCompare = 'display-none';
}
$slider = '<div id="owl-demo" class="owl-carousel ">';
foreach ($photos_array as $photo_slide) {
    $photo_Slide = $this->viewPhoto($photo_slide, 'crop', 87, 73, 'img', 1, $_SESSION['dots'], 1, '', 'img-responsive');
    $slider.="<div class='item'>" . $photo_Slide;
    $slider.="</div>";
}
$slider.="</div>";


$imgs = explode(',', $get_product['photos']);
$gal = '<div   class="html5gallery non-dis' . $product_id . '" data-skin="light" data-autoslide="true" data-shownumbering="true"  data-numberingformat="%NUM of %TOTAL | " data-responsive="true"  data-html5player="true"  data-width="100" data-height="100" >';
foreach ($imgs as $value) {
    $img1 = str_replace('background-image:url(', '', str_replace(');', '', $this->viewPhoto($value, 'crop', 1000, 1000, 'css', 1, $_SESSION['dots'], 0, 1)
            )
    );
    $img2 = $this->viewPhoto($value, 'crop', 240, 200, 'img', 1, $_SESSION['dots'], 0, 1, 'img-responsive');




    $gal.='<a href="' . $img1 . '">' . $img2 . '</a>';
}

$gal.='</div>';
?>
<input type="hidden" id="product_id" value="<?= $product_id ?>" >
<input type="hidden" id="customer_login" value="<?= $_SESSION['CUSTOMER_ID'] ?>" >
<div class="row">
    <!--<div class="col-sm-3">
<?= $this->getCategoriesMenu(); ?>

    </div>-->
    <div class="col-sm-12">
        <div class="row single-pro">
            <div class="col-sm-6">
                <div class="single-photo">
    <?= $pro_photo; ?>
                </div>
            </div>

            <div class="col-sm-6">
                <h1><?= $get_product['title']; ?></h1>
                <p class="single-brief"><?= strip_tags($get_product['brief']); ?></p>

                <div class="AddedToCart <?= $show_add ?>">
                    <div class='row '>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-3 nopadding"><label >Duration</label></div>
                                <div class="col-xs-9 nopadding"> <?= $durations_select; ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 price-color ">
                            <div class="fl-right">PRICE   <span class="product_price_<?= $product_id ?>  relative price"></span></div>
                        </div>

                    </div>

                    <div class='row loadImgAdd rel-div top-20'>

                        <div class="col-sm-6 nopadding">
                            <div class="col-xs-5 nopadding"><label>   <?= $get_unit_name['title']; ?> Number</label></div>
                            <div class="col-xs-7 nopadding "> <?= $groups_select; ?></div>
                        </div>
                        <div class="col-sm-6 nopadding">
                            <div class="fl-right">
                                <a href='javascript:;' class="addToCart single-addToCart" data-id="<?= $product_id ?>" data-single='single'><span> Add to Cart</span> <i class="demo-icon iconc-cart-plus icon-style-single "></i> </div>
                        </div>
                    </div>

                </div>
                <div class="RemovedFromCart <?= $show_remove ?> loadImgAdd rel-div">
                    <div class='row row-nomargin '>
                        <div class="col-sm-12 ">
                            <div class="fl-right">
                                <a href='javascript:;' class="RemovefromCartSingle single-addToCart" data-id="<?= $product_id ?>"> <span> Remove From Cart</span> <i class="demo-icon iconc-cart icon-style-single"></i></a></div>
                        </div></div>
                </div>
                <div class="load-img" style="display:none" data-id="<?= $product_id ?>">
                    <img src="<?= _ViewIMG ?>loading.gif" alt="" class="img-responsive" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">

<?= $slider ?>

            </div>
        </div>
        <div class='clear'></div>
        <div class='block' >
            <div id="Details" class="rel-div">
                <div class="load-img-details" style="display:none" data-id="<?= $product_id ?>">
                    <img src="<?= _ViewIMG ?>loading.gif" alt="" class="img-responsive" />
                </div>
                <ul class="resp-tabs-list hor_1 product_details">
                    <li data-id="<?= $product_id ?>" data-details='overview' data-request="Details_1" class='GetProductDetails'>Overview</li>
                    <li data-id="<?= $product_id ?>" data-details='features' data-request="Details_2" class='GetProductDetails'>Features</li>
                    <li data-id="<?= $product_id ?>" data-details='resources' data-request="Details_3" class='GetProductDetails'>Resources</li>
                    <li data-id="<?= $product_id ?>" data-details='faq' data-request="Details_4" class='GetProductDetails'>FAQ</li>
                    <li data-id="<?= $product_id ?>" data-details='review' data-request="Details_5" class='GetProductDetails'>Review</li>
                    <li data-id="<?= $product_id ?>" data-details='addons' data-request="Details_6" class='GetProductDetails'>Add-ons</li>
                </ul>


                <div class="resp-tabs-container hor_1 ">


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


                    </div>
                </div>
            </div>
        </div>

    </div>

</div>


<?php
$widgets = new widgets();
$widgets->printWidget(31);
?>

<script>
    var prev = '';
    $('.lab_btn').click(function() {
        $id = $(this).attr('data-id');
        if (prev != $id) {
            $('.lab_cls').hide();
            $('#labI_' + $id).show();
            prev = $id;
        }
    });
</script>
<? include '../../widgets/modal/AddReview.php'; ?>