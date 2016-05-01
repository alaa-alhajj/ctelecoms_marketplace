<?php
include "../../common/top_ajax.php";
 @session_start();
$module_id = $_REQUEST['module'];
$cms_module = $fpdo->from('cms_modules')->where(" id='$module_id' ")->fetchAll();
foreach ($cms_module as $row) {
    $db_table = $row['table_name'];
    $module_lang_type = $row['lang_type'];
    $is_gridlist = $row['is_gridlist'];
    $has_order = $row['has_order'];
}


$table = $_REQUEST['table'];
$id = $_REQUEST['id'];
$redirect = $_REQUEST['redirect'];

$cols_req = rtrim($_REQUEST['cols'], ',');
$cols = explode(',', $cols_req);

$get_values = $fpdo->from($table)->where("id='$id'")->fetch();
$get_values['id'] = "";
$get_values['action'] = 'Insert';
if ($module_lang_type == 'Table') {
    $get_values['lang'] =$_SESSION['cmsMlang']= $cmsMlang;
}

$save_ob = new saveform($table, $get_values, $cols, "", '', '', '', true, false);
$insert_id = $save_ob->getInsertId();
if ($table == 'products') {
  $get_page_inserted=$fpdo->from($table)->where("id='$insert_id'")->fetch();
    $get_page_for_dub=$fpdo->from($table)->where("id='$id'")->fetch();
    $get_seo_details=$fpdo->from('cms_pages')->where("id='".$get_page_for_dub['page_id']."'")->fetch();
    
    $fpdo->update('cms_pages')->set(array('`seo_description`' => $get_seo_details['seo_description'],'`seo_keywords`'=>$get_seo_details['seo_keywords'],'`seo_img`'=>$get_seo_details['seo_img']))->where("id='".$get_page_inserted['page_id']."'")->execute();
    $get_feature_value = $fpdo->from('product_features_values')->where("product_id='" . $id . "'")->fetchAll();
    foreach ($get_feature_value as $feature_id) {
        $feature_val = $feature_id['value'];
        $f_id = $feature_id['feature_id'];

        $fpdo->insertInto('product_features_values')->values(array('`feature_id`' => $f_id, '`product_id`' => $insert_id, '`value`' => $feature_val))->execute();
    }

    $get_dynamic_prices = $fpdo->from('product_dynamic_price')->where("product_id='" . $id . "'")->fetchAll();
    foreach ($get_dynamic_prices as $dynamic_price) {

        $inserted_dynamic_id = $fpdo->insertInto('product_dynamic_price')->values(array('`product_id`' => $insert_id, '`duration_ids`' => $dynamic_price['duration_ids'], '`unit_id`' => $dynamic_price['unit_id'], '`type_id`' => $dynamic_price['type_id'], '`group_ids`' => $dynamic_price['group_ids']))->execute();
        $dynamic_price_id = $dynamic_price['id'];
        $new_dynamic_id = $inserted_dynamic_id;
    }

    $get_prices_values = $fpdo->from('product_price_values')->where("dynamic_price_id='" . $dynamic_price_id . "'")->fetchAll();
    foreach ($get_prices_values as $prices_values) {
        $fpdo->insertInto('product_price_values')->values(array('`dynamic_price_id`' => $new_dynamic_id, '`duration_id`' => $prices_values['duration_id'], '`group_id`' => $prices_values['group_id'], '`value`' => $prices_values['value']))->execute();
    }
    
     $get_product_faqs = $fpdo->from('product_faq')->where("product_id='" . $id . "'")->fetchAll();
    foreach ($get_product_faqs as $product_faq) {
        $fpdo->insertInto('product_faq')->values(array('`product_id`' => $insert_id, '`question`' => $product_faq['question'], '`answer`' => $product_faq['answer']))->execute();
    }
    
  
 echo json_encode($insert_id);   
}

