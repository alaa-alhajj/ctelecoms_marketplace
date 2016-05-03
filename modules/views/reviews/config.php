<?php

$db_table = "customer_reviews";

$LPP = 8;
$dublicated_cols=array('customer_id', 'review');
$cols = array('title', 'cat_id', 'brief', 'resources');
$Savecols = array('title', 'cat_id', 'brief', 'resources');
$colsUpdate = array('title', 'cat_id', 'brief', 'resources');
$types = array('title' => "text", 'cat_id' => 'select', 'brief' => 'SimpleTextEditor', 'resources' => 'SimpleTextEditor');
$source = array('cat_id' => array('product_category', 'title', 'id'));
$required = array("title" => "required", "cat_id" => "required");
$cols_photo = array('photos');
$types_photo = array('photos' => 'photos');
$Savecols_photo = array('photos');
$cols_check = array('title_duration');
$types_check = array('title_duration' => 'checkbox');
$sourc_check = array('title_duration' => array('pro_price_duration', 'title', 'id'));
$cols_radio = array('title_unit');
$types_radio = array('title_unit' => 'radio');
$sourc_radio = array('title_unit' => array('pro_price_units', 'title', 'id'));
$cols_check_type = array('title_type');
$types_check_type = array('title_type' => 'radio');
$sourc_check_type = array('title_type' => array('pro_price_type', 'title', 'id'));
$cols_check_group = array('title_group');
$types_check_group = array('title_group' => 'checkbox');


$Savecols_dynamic_Pricing = array('duration_ids', 'unit_id', 'type_id', 'product_id');
$Updatecols_dynamic_Pricing_group = array('group_ids');
$saveCols_product_price_values = array('dynamic_price_id', 'duration_id', 'group_id', 'value');
$cols_Req_fields = array('title_req');
$types_Req_fields = array('title_req' => 'checkbox');
$sourc_Req_fields = array('title_req' => array('customer_fields', "title", 'id'));
$cols_faq = array('question', 'answer');
$types_faq = array('question' => 'SimpleTextEditor', 'answer' => 'SimpleTextEditor');
$Savecols_FAQ = array('product_id', 'question', 'answer');
$cols_seo = array('seo_title', 'seo_description', 'seo_keywords', 'seo_img');
$types_seo = array('title' => "text",'html' => "FullTextEditor",'seo_title' => "text",'seo_description' => "textarea",'seo_keywords' => "tags",'seo_img' => "photos");

$pageList = "listProducts.php";
$pageInsertProduct = "insertProduct.php";
$pageProductFeatures = "insertProductFeatures.php";
$pageProductPhotos = "insertProductPhotos.php";
$pageProductPricing1 = "insertProductPricingf.php";
$pageProductPricing2 = "insertProductPricingS.php";
$pageProductPricingTable = "insertProductPricingTable.php";
$pageProductAddOns = "insertProductAddOns.php";
$pageProductRelated = "insertProductRelated.php";
$pageProductReq_feilds = "insertProductReqFields.php";
$pageProductFAQ = "insertProductFAQ.php";
$pageProductSEO = "insertProductSEO.php";
$pageCongrats = "Congrats.php";
$pageUpdate = "updateProduct.php";
$pageEditFaq = "editProductFAQ.php";
?>
