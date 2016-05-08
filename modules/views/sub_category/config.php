<?php
$db_table="product_sub_category";
$db_table_feature="product_features";
$LPP = 8;

$cols=array('title','cat_id','feature_type');

$Savecols=array('title','cat_id','feature_type');
$colsUpdate= array('title','cat_id','feature_type');
$colsPhoto=array('photos');
$types=array('title'=>"text",'brief'=>'SimpleTextEditor','details'=>'SimpleTextEditor','photos'=>'photos','cat_id'=>'select','feature_type'=>'radio');
$source = array('cat_id' => array('product_category', 'title', 'id'),'feature_type' => array('features_types', 'title', 'id'));
$required=array("title"=>"required",'cat_id','feature_type');
$cols_seo = array('seo_title', 'seo_description', 'seo_keywords', 'seo_img');
$types_seo = array('title' => "text",'html' => "FullTextEditor",'seo_title' => "text",'seo_description' => "textarea",'seo_keywords' => "tags",'seo_img' => "photos");

$cols_features=array('title','type','plus','is_main');


$extra=array(
				'photos',
				'videos',
				'text',
				'email',
				'tel',
				'number',
				'tags',
				'password',
				'select',
				'select+',
				'DynamicSelect',
				'attach',
				'checkbox',
				'map',
				'flag',
				'date',
				'datepicker',
				'timepicker',
				'FullTextEditor',
				'textarea',
				'SimpleTextEditor'
				);


$pageList="listSubCategories.php";
$pageListHref="'".$pageList."'";
$pageInsert="insertSubCategory.php";
$pageInsertPhotos="insertCategoryPhotos.php";
$pageUpdate="updateSubCategory.php";
$pageInsertFeature="insertSubCategoryFeature.php";
$pageInsertSEO="insertCategorySEO.php";


?>
