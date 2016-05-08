<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
$id = $_REQUEST['id'];
$get_page_id = $fpdo->from($db_table)->where("id='$id'")->fetch();
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $query = $fpdo->update('cms_pages')->set(array('seo_title' => $_REQUEST['seo_title'], 'seo_description' => $_REQUEST['seo_description'], 'seo_keywords' => $_REQUEST['seo_keywords'], 'seo_img' => $_REQUEST['seo_img']))->where("id='" . $get_page_id['page_id'] . "'");
    $exec = $query->execute();
     echo '<script>notificationMessage(true);
        </script>';

    if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } else {
        $utils->redirect($pageCongrats);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Category Info</a></li>
     <li><a href="#">Category Photos</a></li>
    <li><a href="#">Category Features</a></li>
     <li class="active-menue"><a href="#">SEO</a></li>
</ul>';

$values1 = $fpdo->from('cms_pages')->where("id='" . $get_page_id['page_id'] . "'")->fetch();
$form = new GenerateFormField();
$form->setColumns($cols_seo);
$form->setTypes($types_seo);
$form->setValues($values1);
$form->setExtendTables($source);
$form->setRequireds($required);
$form->setBackBtn(true);
$form->setSkipBtn(true, $pageCongrats);
$form->setCountCell(1);
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');
$form->setAsForm(true);

echo $form->getForm('Insert');
//echo '<div class="box box-danger form-horizontal"><div class="box-body"><form method="post" class="" name="" id=""><input type="hidden" name="backLink" value="http://localhost/ctelecoms_marketplace/modules/views/category/listCategories.php"><div class="Form-Field form-generate-voila "><div class=""><div class="form-group"><label class="col-sm-2 control-label"><span class="red  required">* </span>title: </label><div class="col-sm-10"><input id="title" name="title" value="" type="text" required="" size="" class=" form-control" placeholder=""></div></div><div class="hr"><hr></div></div>  <div class="col-sm-12">   <input type="hidden" value="Insert" name="action" id="action"><button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;<input type="submit" class="btn btn-submit" value="Save & Continue">  </div> </div></form></div></div>';

include_once '../../common/footer.php';
