<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols, 'id', '', '', '', false);
    $insert_id = $save_ob->getInsertId();
  
    if ($_REQUEST['feature_type'][0] === 1 || $_REQUEST['feature_type'][0] === '1') {
     /* $get_features=$fpdo->from("product_features")->where("cat_id='".$_REQUEST['cat_id']."' and sub_cat_id='0'")->fetchALL();
      foreach($get_features as $sub_features){
          $get_maxOrder=$fpdo->from("product_features")->select("max(item_order) as max")->fetch();
          $order=$get_maxOrder['max']+1;
            $fpdo->insertInto('product_features')->values(array('`item_order`' => $order, '`title`' => $sub_features['title'],'`type`'=>$sub_features['type'],'`plus`'=>$sub_features['plus'],'`is_main`'=>$sub_features['is_main'],'`sub_cat_id`'=>$insert_id))->execute();
      }
      
      */
        $utils->redirect($pageList);
    } elseif ($_REQUEST['feature_type'][0] === 2 || $_REQUEST['feature_type'][0] === '2') {
      
        $utils->redirect($pageInsertFeature . "?sub_cat_id=" . $insert_id);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li class="active-menue"><a href="#">Sub Category Info</a></li>
    
    <li><a href="#">Sub Category Features</a></li>
   
    
</ul>';
$form = new GenerateFormField();
$form->setColumns($cols);
$form->setTypes($types);
$form->setExtendTables($source);
$form->setRequireds($required);

$form->setCountCell(1);
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');
//$form->setSaveCloseBtn(true, 'Save & Close');
$form->setAsForm(true);

echo $form->getForm('Insert');
//echo '<div class="box box-danger form-horizontal"><div class="box-body"><form method="post" class="" name="" id=""><input type="hidden" name="backLink" value="http://localhost/ctelecoms_marketplace/modules/views/category/listCategories.php"><div class="Form-Field form-generate-voila "><div class=""><div class="form-group"><label class="col-sm-2 control-label"><span class="red  required">* </span>title: </label><div class="col-sm-10"><input id="title" name="title" value="" type="text" required="" size="" class=" form-control" placeholder=""></div></div><div class="hr"><hr></div></div>  <div class="col-sm-12">   <input type="hidden" value="Insert" name="action" id="action"><button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;<input type="submit" class="btn btn-submit" value="Save & Continue">  </div> </div></form></div></div>';

include_once '../../common/footer.php';
