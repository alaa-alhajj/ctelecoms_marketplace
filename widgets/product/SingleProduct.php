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
        <div class='row row-nomargin'>
            <div class="col-sm-6 nopadding"><span class='fontsize'>Duration &nbsp;</span><?= $durations_select; ?></div>
            <div class="col-sm-6 nopadding"><span class='fontsize'>Number of users &nbsp;</span> <?= $groups_select; ?></div>
        </div>

        <div class='row row-nomargin product-price'>

            <div class="col-sm-6">Price <span id="product_price"> </span></div>
            <div class="col-sm-6 cart-button"><a href='javascript:;'><i class="fa fa-cart-plus" aria-hidden="true"></i> Add to Cart</a></div>
            <div class="col-sm-12 margTop20 freeTrial-button"><a href=''>Free Trial</a></div>
        </div>

    </div>
    <div class='clear'></div>
    <div class='block'>
        <div id="Details">
            <ul class="resp-tabs-list hor_1 product_details">
                <li>Overview</li>
                <li data-id="<?= $product_id ?>" data-details='features' class='GetProductDetails'>Features</li>
                <li data-id="<?= $product_id ?>" data-details='resources' class='GetProductDetails'>Resources</li>
                <li data-id="<?= $product_id ?>" data-details='faq' class='GetProductDetails'>FAQ</li>
                <li data-id="<?= $product_id ?>" data-details='review' class='GetProductDetails'>Review</li>
                <li data-id="<?= $product_id ?>" data-details='addons' class='GetProductDetails'>Add-ons</li>
            </ul>
            <div class="resp-tabs-container hor_1">
                <div>  <!--Overview-->
                    <p>

                    <div id="ChildVerticalTab_1">
                        <div class="resp-tabs-container ver_1">
                            <div>
                                <p>
                                    product Category: <?= $category_name['title']; ?> 
                                </p>
                            </div>
                            <div>
                                <p>
                                    Product Brief: <?= strip_tags($get_product_details['brief']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id='features'> <!-- Features -->
                    <div class='row row-nomargin'>
                        <?
                        $get_features = $this->fpdo->from('product_features_values')->where("product_id='$product_id'")->fetchAll();
                        $table_features = "<table class='table table-bordered table-hover'>"
                                . "<tbody";
                        foreach ($get_features as $pro_features) {
                            $get_feature_name = $this->fpdo->from("product_features")->where("id", $pro_features['feature_id'])->fetch();
                            $table_features.="<tr>";
                            $table_features.="<td>" . $get_feature_name['title'] . "</td>"
                                    . "<td>" . $pro_features['value'] . "</td>";
                            $table_features.="</tr>";
                        }
                        $table_features.="</tbody></table>";
                        echo $table_features;
                        ?>
                    </div>
                </div>
                <div id='resources'>
                    <p><?= $get_product_details['resources'] ?></p>
                </div>
                <div id='faq'>
                    <div class='col-sm-12 nopadding'>
                        <h4 class='color-faq pull-left'>Frequently asked questions</h4>
                        <a href="#" class='pull-right '><i class="fa fa-plus  "></i> Show All</a>
                    </div>
                    <div class="panel-group" id="accordion">
                        <?
                        $get_Faqs = $this->fpdo->from('product_faq')->where("product_id='$product_id'")->fetchAll();
                        $faq = "";
                        $i_f = 1;
                        foreach ($get_Faqs as $faqD) {
                            $class = "";
                            if ($i_f === 1) {
                                //  $class="in";
                            }
                            $i_f++;

                            $faq.='<div class="panel panel-default panel-faq changestyle-accordion">'
                                    . ' <div class="faq-pointer">'
                                    . ' <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#' . $faqD['id'] . '">'
                                    . '<div class="panel-heading ">'
                                    . ' <h4 class="panel-title">'
                                    . strip_tags($faqD['question'])
                                    . ' <i class="fa fa-plus  pull-right"></i>'
                                    . ' </h4>'
                                    . '</div></a></div>'
                                    . '<div id="' . $faqD['id'] . '" class="panel-collapse collapse faqctel panel-border ' . $class . '">'
                                    . '<div class="panel-body"  >'
                                    . $faqD['answer']
                                    . '</div></div></div>';
                        }
                        echo $faq;
                        ?>
                        <div class='row row-nomargin allfaq-button'>
                            <a href='' class='btn btn-default'>SEE ALL FAQs</a>
                        </div>
                    </div>
                </div>
                <div id='review'>
                    <div class="row row-nomargin margbtm20">
                        <div class="col-sm-6">
                            <a href='javascript:;' class='btn btn-primary write-review'>Write a review</a>
                            <a href='' class='btn btn-primary'>Show All Reviews</a>
                        </div>

                    </div>


                    <div class="row row-nomargin">
                        <div class='col-sm-6'>
                            Select a row below to filter reviews.
                        </div>
                        <div class='col-sm-6 row-stars'>
                            <div class='over-rating'> Overall</div> <div> 
                                <input class="rb-rating"></div>
                        </div>
                    </div>



                    <div class="rating-bars">
                        <?
                        $get_5_rating = $this->fpdo->from('customer_rating')
                                        ->select("count(id) as count_five")
                                        ->where("product_id='$product_id' and rate_id='5'")->fetch();
                        $width_5 = $get_5_rating['count_five'] / 100;
                        $count5 = $get_5_rating['count_five'];

                        $get_4_rating = $this->fpdo->from('customer_rating')
                                        ->select("count(id) as count_four")
                                        ->where("product_id='$product_id' and rate_id='4'")->fetch();
                        $width_4 = $get_4_rating['count_four'] / 100;
                        $count4 = $get_4_rating['count_four'];
                        $get_3_rating = $this->fpdo->from('customer_rating')
                                        ->select("count(id) as count_three")
                                        ->where("product_id='$product_id' and rate_id='3'")->fetch();
                        $width_3 = $get_3_rating['count_three'] / 100;
                        $count3 = $get_3_rating['count_three'];
                        $get_2_rating = $this->fpdo->from('customer_rating')
                                        ->select("count(id) as count_tow")
                                        ->where("product_id='$product_id' and rate_id='2'")->fetch();
                        $width_2 = $get_2_rating['count_tow'] / 100;
                        $count2 = $get_2_rating['count_tow'];
                        $get_1_rating = $this->fpdo->from('customer_rating')
                                        ->select("count(id) as count_one")
                                        ->where("product_id='$product_id' and rate_id='1'")->fetch();
                        $width_1 = $get_1_rating['count_one'] / 100;
                        $count1 = $get_1_rating['count_one'];
                        ?>
                        <div class="row row-nomargin">
                            <div class="col-sm-6">
                                <div class="row row-nomargin">
                                    <div class="col-xs-2"> 
                                        <div class="number-rate">5 <i class="fa fa-star" aria-hidden="true"></i> </div>
                                    </div>
                                    <div class="col-xs-9 nopadding"> 
                                        <div class="bar-r">
                                            <div class="bar-rating rate_5" style="width:<?= $width_5 ?>px !important"></div>
                                        </div>

                                    </div>
                                    <div class="col-xs-1 count_5"><?= $count5 ?> </div>
                                </div>

                                <div class="row row-nomargin margTop10">
                                    <div class="col-xs-2"> 
                                        <div class="number-rate ">4 <i class="fa fa-star" aria-hidden="true"></i> </div>
                                    </div>
                                    <div class="col-xs-9 nopadding"> 
                                        <div class="bar-r">
                                            <div class="bar-rating rate_4" style="width:<?= $width_4 ?>px !important"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 count_4"><?= $count4 ?> </div>
                                </div>

                                <div class="row row-nomargin margTop10">
                                    <div class="col-xs-2"> 
                                        <div class="number-rate">3 <i class="fa fa-star" aria-hidden="true"></i> </div>
                                    </div>
                                    <div class="col-xs-9 nopadding"> 
                                        <div class="bar-r">
                                            <div class="bar-rating rate_3" style="width:<?= $width_3 ?>px !important"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 count_3"><?= $count3 ?> </div>
                                </div>

                                <div class="row row-nomargin margTop10">
                                    <div class="col-xs-2"> 
                                        <div class="number-rate">2 <i class="fa fa-star" aria-hidden="true"></i> </div>
                                    </div>
                                    <div class="col-xs-9 nopadding"> 
                                        <div class="bar-r">
                                            <div class="bar-rating rate_2" style="width:<?= $width_2 ?>px !important"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 count_2"><?= $count2 ?> </div>
                                </div>

                                <div class="row row-nomargin margTop10">
                                    <div class="col-xs-2"> 
                                        <div class="number-rate">1 <i class="fa fa-star" aria-hidden="true"></i> </div>
                                    </div>
                                    <div class="col-xs-9 nopadding"> 
                                        <div class="bar-r">
                                            <div class="bar-rating rate_1" style="width:<?= $width_1 ?>px !important"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 count_1"><?= $count1 ?> </div>
                                </div>

                            </div>
                        </div>

                    </div>





                </div>
                <div id='addons'>
                    <p>
                    <div class='row row-nomargin nopadding'>
                        <?
                        $addons_products = explode(',', rtrim($get_product_details['add_ons_pro_ids'], ','));
                      
                        $add = 0;
                        $addOn_photo = "";
                        $addon_details = "";
                        foreach ($addons_products as $product_addOn) {
                            if ($add <= 6 && $product_addOn != "") {

                                $get_pro = $this->fpdo->from('products')->where("id='$product_addOn'")->fetch();
                                $photos = explode(',', $get_pro['photos']);
                                $addOn_photo = $this->viewPhoto($photos[0], 'crop', 200, 200, 'img', 1, $_SESSION['dots'], 1, '', 'img-responsive width100');

                                $addon_details.= '
                                       <div class="col-sm-6 col-md-4">
                                       <div class="thumbnail">
                                       ' . $addOn_photo . '
                                        <div class="caption">
                                       <h3>' . $get_pro['title'] . '</h3>
                                           <p>test</p>
                                       <p> <a href="#" class="btn btn-default" role="button">MORE</a></p>
                                      </div>
                                           </div>
                                               </div>';
                            }
                            $add++;
                        }

                        echo $addon_details;
                        ?>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?
if($get_product_details['related_pro_ids'] !=""){
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
<?}?>
</div>

<? include '../../widgets/modal/AddReview.php'; ?>