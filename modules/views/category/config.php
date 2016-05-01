<?php
$db_table="product_category";
$db_table_feature="product_features";
$LPP = 8;

$cols=array('title','brief','details');

$Savecols=array('title','brief','details');
$colsUpdate= array('title','brief','details');
$colsPhoto=array('photos');
$types=array('title'=>"text",'brief'=>'SimpleTextEditor','details'=>'SimpleTextEditor','photos'=>'photos');
$source=array();
$required=array("title"=>"required");
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


$pageList="listCategories.php";
$pageListHref="'".$pageList."'";
$pageInsert="insertCategory.php";
$pageInsertPhotos="insertCategoryPhotos.php";
$pageUpdate="updateCategory.php";
$pageInsertFeature="insertCategoryFeature.php";
$pageInsertSEO="insertCategorySEO.php";


?>
