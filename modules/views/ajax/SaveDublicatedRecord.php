<?php

include "../../common/top_ajax.php";
$module_id=$_REQUEST['module'];
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

$cols_req=rtrim($_REQUEST['cols'],',');
$cols = explode(',', $cols_req);

$get_values = $fpdo->from($table)->where("id='$id'")->fetch();
$get_values['id'] = "";
 $get_values['action'] = 'Insert';
if ($module_lang_type == 'Table') {
    $get_values['lang'] = $cmsMlang;
}
$save_ob = new saveform($table, $get_values, $cols, "id", $order_field, $map_field,'',true,false);
$insert_id = $save_ob->getInsertId();


 $get_page_inserted=$fpdo->from($table)->where("id='$insert_id'")->fetch();
 $page_id_inserted=$get_page_inserted['page_id'];
 if($page_id_inserted !=""){
      $get_page_for_dub=$fpdo->from($table)->where("id='$id'")->fetch();
    $get_seo_details=$fpdo->from('cms_pages')->where("id='".$get_page_for_dub['page_id']."'")->fetch();
    
    $fpdo->update('cms_pages')->set(array('`seo_description`' => $get_seo_details['seo_description'],'`seo_keywords`'=>$get_seo_details['seo_keywords'],'`seo_img`'=>$get_seo_details['seo_img']))->where("id='".$get_page_inserted['page_id']."'")->execute();
 }


//$insert_id=$fpdo->insertInto($table)->values($get_values)->execute();
echo json_encode($insert_id);
