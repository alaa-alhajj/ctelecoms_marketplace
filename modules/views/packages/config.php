<?php

$db_table = "products";
$db_table_dynamic_price = "product_dynamic_price";
$db_product_price_value = "product_price_values";
$db_pro_features = "product_features";
$db_pro_Type = "pro_price_type";
$db_pro_Units = "pro_price_units";
$db_pro_Groups = "pro_price_groups";
$db_pro_FAQ = "product_faq";
$LPP = 8;
$dublicated_cols=array('title', 'cat_id','sub_cat_id', 'brief','overview', 'resources','photos','add_ons_pro_ids','package_pro_ids','related_pro_ids','customer_req_fields','is_package');

$first_save_cols=array('title', 'cat_id','sub_cat_id', 'brief', 'overview','is_package');
$cols = array('title', 'cat_id','sub_cat_id', 'brief', 'overview');
$Savecols =array('title', 'cat_id','sub_cat_id', 'brief', 'overview');
$colsUpdate = array('title', 'cat_id','sub_cat_id', 'brief', 'overview');
$types = array('title' => "text", 'cat_id' => 'select+', 'sub_cat_id' => 'select+', 'brief' => 'SimpleTextEditor', 'overview' => 'SimpleTextEditor');
$source = array('cat_id' => array('product_category', 'title', 'id'),'sub_cat_id' => array('product_sub_category', 'title', 'id'));
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
$types_check_group = array('title_group' => 'checkboxCostum');
$cols_resources=array('resources');
$types_resources=array('resources'=>'SimpleTextEditor');

$Savecols_dynamic_Pricing = array('duration_ids', 'unit_id', 'type_id', 'product_id');
$Updatecols_dynamic_Pricing_group = array('group_ids');
$saveCols_product_price_values = array('dynamic_price_id', 'duration_id', 'group_id', 'value');
$cols_Req_fields = array('title_req');
$types_Req_fields = array('title_req' => 'checkbox');
$sourc_Req_fields = array('title_req' => array('customer_fields', "title", 'id'));
$cols_faq = array('question', 'answer');
$types_faq = array('question' => 'SimpleTextEditor', 'answer' => 'SimpleTextEditor');
$required_faq= array('question', 'answer');
$Savecols_FAQ = array('product_id', 'question', 'answer');
$cols_seo = array('seo_title', 'seo_description', 'seo_keywords', 'seo_img');
$types_seo = array('title' => "text",'html' => "FullTextEditor",'seo_title' => "text",'seo_description' => "textarea",'seo_keywords' => "tags",'seo_img' => "photos");

$pageList = "listPackages.php";
$pageInsertProduct = "insertPackage.php";
$pageInsertPackageProducts = "insertPackageProducts.php";
$pageInsertResources="insertPackageResources.php";
$pageProductFeatures = "insertPackageFeatures.php";
$pageProductPhotos = "insertPackagePhotos.php";
$pageProductPricing1 = "insertPackagePricingf.php";
$pageProductPricing2 = "insertPackagePricingS.php";
$pageProductPricingTable = "insertPackagePricingTable.php";
$pageProductAddOns = "insertPackageAddOns.php";
$pageProductRelated = "insertPackageRelated.php";
$pageProductReq_feilds = "insertPackageReqFields.php";
$pageProductFAQ = "insertPackageFAQ.php";
$pageProductSEO = "insertPackageSEO.php";
$pageCongrats = "Congrats.php";
$pageUpdate = "updatePackage.php";
$pageEditFaq = "editPackageFAQ.php";
?>
