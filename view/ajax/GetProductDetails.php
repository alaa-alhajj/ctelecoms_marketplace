<?php
include('../../view/common/top.php');
@session_start();
$product_id = $_REQUEST['product'];
$get_data = $_REQUEST['get_data'];
$get_details = $fpdo->from('products')->where("id ='$product_id'")->fetch();
$category_name = $fpdo->from('product_category')->where("id", $get_details['cat_id'])->fetch();
if ($get_data === "overview") {
    $over_view = "<div><p>";

    // $over_view.="product Category: " . $category_name['title'];
    //$over_view.="</p></div>";
    // $over_view .= "<div><p>";
    // $over_view.="Product Brief: " . strip_tags($get_details['brief']);
    $over_view.=strip_tags($get_details['overview']);
    $over_view.="</p></div>";
    echo $over_view;
} elseif ($get_data === "features") {
    $table_features = " <div class='row row-nomargin'>";

    $get_features = $fpdo->from('product_features_values')->where("product_id='$product_id'")->fetchAll();
    $table_features = "<table class='table table-bordered table-hover'>"
            . "<tbody";
    foreach ($get_features as $pro_features) {
        $get_feature_name = $fpdo->from("product_features")->where("id", $pro_features['feature_id'])->fetch();
        $table_features.="<tr>";
        $table_features.="<td><b>" . $get_feature_name['title'] . "</b></td>"
                . "<td>" . $pro_features['value'] . "</td>";
        $table_features.="</tr>";
    }
    $table_features.="</tbody></table>";


    $table_features.='</div>';
    echo $table_features;
} elseif ($get_data === "resources") {
    echo strip_tags($get_details['resources']);
} elseif ($get_data === "faq") {
    $faq = "";
    $faq.=" <div class='row'><div class='col-sm-12 '>";
    $faq.="<h4 class='color-faq pull-left'>Frequently asked questions</h4>";
    //   $faq.='<a href="#" class="pull-right "><i class="fa fa-plus  "></i> Show All</a>';
    $faq.= '</div></div>';
    $faq.= '<div class="panel-group" id="accordion">';

    $get_Faqs = $fpdo->from('product_faq')->where("product_id='$product_id'")->fetchAll();

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
    $faq.="<div class='row row-nomargin allfaq-button'>";
    //  $faq.="<a href='' class='btn btn-default'>SEE ALL FAQs</a>";
    $faq.="</div>";
    $faq.="</div>";
    echo $faq;
} elseif ($get_data === "review") {
    $rating_div="";
if($_SESSION['CUSTOMER_ID']!=""){
    $rating_div="<div class = 'over-rating'> Overall</div><div><input class = 'rb-rating '></div>";
}
    $reviews_det = "";
    $reviews_det.='<div class="row row-nomargin margbtm20">';
    $reviews_det.='<div class="col-sm-6">';
    $reviews_det.=" <a href='javascript:;' class='btn btn-primary write-review'>Write a review</a> &nbsp;";
    $reviews_det.="<a href='javascript:;' class='btn btn-primary' id='loadMore'>Show More Reviews</a>";
    $reviews_det.="</div></div>";
    $reviews_det.='<div class = "row row-nomargin">'
            . "<div class = 'col-sm-6'>"
            . " Select a row below to filter reviews."
            . "</div>"
            . "<div class = 'col-sm-6 row-stars'>"
            . "  "
            . ""
            . $rating_div
            . "</div></div>"
            . " <div class = 'rating-bars'>";


    $get_5_rating = $fpdo->from('customer_rating')
                    ->select("count(id) as count_five")
                    ->where("product_id='$product_id' and rate_id='5'")->fetch();
    $width_5 = $get_5_rating['count_five'] / 100;
    $count5 = $get_5_rating['count_five'];

    $get_4_rating = $fpdo->from('customer_rating')
                    ->select("count(id) as count_four")
                    ->where("product_id='$product_id' and rate_id='4'")->fetch();
    $width_4 = $get_4_rating['count_four'] / 100;
    $count4 = $get_4_rating['count_four'];
    $get_3_rating = $fpdo->from('customer_rating')
                    ->select("count(id) as count_three")
                    ->where("product_id='$product_id' and rate_id='3'")->fetch();
    $width_3 = $get_3_rating['count_three'] / 100;
    $count3 = $get_3_rating['count_three'];
    $get_2_rating = $fpdo->from('customer_rating')
                    ->select("count(id) as count_tow")
                    ->where("product_id='$product_id' and rate_id='2'")->fetch();
    $width_2 = $get_2_rating['count_tow'] / 100;
    $count2 = $get_2_rating['count_tow'];
    $get_1_rating = $fpdo->from('customer_rating')
                    ->select("count(id) as count_one")
                    ->where("product_id='$product_id' and rate_id='1'")->fetch();
    $width_1 = $get_1_rating['count_one'] / 100;
    $count1 = $get_1_rating['count_one'];


    $get_reviews = $fpdo->from('customer_reviews')
                    ->select("customers.name as cu_name,customer_reviews.review as cu_review,customer_reviews.id as re_id")
                    ->leftJoin('customers on customers.id=customer_reviews.customer_id')
                    ->where("product_id='$product_id' and active='1'")->fetchAll();

    $reviews_comments = "";
    foreach ($get_reviews as $reviews) {
        if ($reviews['re_id'] != "") {
            $reviews_comments.='<div class="row row-margin comment_user"> <div class="col-xs-1 nopadding">
                             <img src="../../view/includes/css/images/user.png" alt="' . $reviews['cu_name'] . '" class="img-responsive user-img">
                         </div>
                                    <div class="col-xs-11 nopadding">
                                        <div class="reply-txt">' . $reviews['cu_review'] . '</div>                                    
                                    </div><div class="col-sm-12"><hr></div></div>';
        }
    }
    $reviews_det.='<div class="row row-nomargin ">'
            . '<div class="col-sm-6">'
            . '<div class="row row-nomargin">'
            . '<div class="col-xs-2"> '
            . '<div class="number-rate">5 <i class="fa fa-star" aria-hidden="true"></i> </div>'
            . '</div>'
            . '<div class="col-xs-9 nopadding"> '
            . '<div class="bar-r">'
            . '<div class="bar-rating rate_5" style="width:' . $width_5 . 'px !important"></div>'
            . '</div></div>'
            . '<div class="col-xs-1 count_5">' . $count5 . ' </div>'
            . '</div>'
            . '<div class="row row-nomargin margTop10">'
            . '<div class="col-xs-2">'
            . '<div class="number-rate ">4 <i class="fa fa-star" aria-hidden="true"></i> </div>'
            . '</div>'
            . '<div class="col-xs-9 nopadding">'
            . '<div class="bar-r">'
            . '<div class="bar-rating rate_4" style="width:' . $width_4 . 'px !important"></div>'
            . ' </div> </div>'
            . '<div class="col-xs-1 count_4">' . $count4 . ' </div>'
            . '</div>'
            . '<div class="row row-nomargin margTop10">'
            . '<div class="col-xs-2"> '
            . ' <div class="number-rate">3 <i class="fa fa-star" aria-hidden="true"></i> </div>'
            . ' </div>'
            . ' <div class="col-xs-9 nopadding">'
            . '<div class="bar-r">'
            . '<div class="bar-rating rate_3" style="width:' . $width_3 . 'px !important"></div>'
            . '</div></div>'
            . '<div class="col-xs-1 count_3">' . $count3 . '</div>'
            . ' </div>'
            . '<div class="row row-nomargin margTop10">'
            . '<div class="col-xs-2">'
            . '<div class="number-rate">2 <i class="fa fa-star" aria-hidden="true"></i> </div>'
            . '</div>'
            . '<div class="col-xs-9 nopadding">'
            . '<div class="bar-r">'
            . '<div class="bar-rating rate_2" style="width:' . $width_2 . 'px !important"></div>'
            . '</div></div>'
            . ' <div class="col-xs-1 count_2">' . $count2 . ' </div>'
            . '</div>'
            . '<div class="row row-nomargin margTop10">'
            . '<div class="col-xs-2"> '
            . '<div class="number-rate">1 <i class="fa fa-star" aria-hidden="true"></i> </div>'
            . ' </div>'
            . '<div class="col-xs-9 nopadding"> '
            . '<div class="bar-r">'
            . '<div class="bar-rating rate_1" style="width:<?= $width_1 ?>px !important"></div>'
            . '</div></div>'
            . ' <div class="col-xs-1 count_1"><?= $count1 ?> </div>'
            . '</div></div></div>'
            . '<div class="col-sm-12"><hr></div>'
            . ' <div class="row row-margin comments_review">'
            . $reviews_comments
            . '</div>';
    echo $reviews_det;
} elseif ($get_data === "addons") {
   
   
    $addons_products = rtrim($get_details['add_ons_pro_ids'],',');
      $addon_details =  $utils->getProductsBoxes("id in($addons_products)", 3,'col-sm-6' );
    echo $addon_details;
}
?>