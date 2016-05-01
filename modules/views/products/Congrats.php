<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
$id = $_REQUEST['pro_id'];
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {

    $get_page_id = $fpdo->from($db_table)->where("id='$id'")->fetch();
    $query = $fpdo->update('cms_pages')->set(array('seo_title' => $_REQUEST['seo_title'], 'seo_description' => $_REQUEST['seo_description'], 'seo_keywords' => $_REQUEST['seo_keywords'], 'seo_img' => $_REQUEST['seo_img']))->where("id='" . $get_page_id['id'] . "'");
    $exec = $query->execute();

    if ($exec == true || ( $exec >= 0 && is_int($exec))) {
        $success = true;
    } else {
        $success = false;
    }
    $message = "";
    @session_start();

    if ($success) {
        $message = $utils->getConstant("Success");
        $type = "success";
        $_SESSION['saveFormStatus'] = "success";
    } else {
        $message = $utils->getConstant("Faild");
        $type = "error";
        $_SESSION['saveFormStatus'] = "faild";
    }

    echo '<script>waitingDialog.hide();
            swal({
            title: "",
            text: "' . $message . '",
            type: "' . $type . '",
            showConfirmButton: false
            , showConfirmButton: false, timer: 2000
        });
        </script>';


    $utils->redirect($pageList);
}
$Congrats="<div class='col-sm-12'><h1 style='margin-top:130px;margin-bottom:60px;text-align:center'>Congratulations a new product has been entered successfully</h1></div>"
        .'<div class="col-sm-12" style="margin-bottom:130px;text-align:center">'
        . '   <a href="'.$pageList.'" class="btn btn-back " >Go to product list</a>&nbsp;'
        . '<a href="'.$pageInsertProduct.'" class="btn btn-submit">Insert another product</a>  '
        . '</div>';
$form = new GenerateFormField();
$form->setColumns();
$form->setTypes();
$form->setExtendTables($source);
$form->setRequireds($required);


$form->setBackBtn(false);
$form->setCountCell(1);
$form->setSubmit(false);

$form->setAsForm(true);
$form->setAppendToForm($Congrats);
echo $form->getForm('Insert');
//echo '<div class="box box-danger form-horizontal"><div class="box-body"><form method="post" class="" name="" id=""><input type="hidden" name="backLink" value="http://localhost/ctelecoms_marketplace/modules/views/category/listCategories.php"><div class="Form-Field form-generate-voila "><div class=""><div class="form-group"><label class="col-sm-2 control-label"><span class="red  required">* </span>title: </label><div class="col-sm-10"><input id="title" name="title" value="" type="text" required="" size="" class=" form-control" placeholder=""></div></div><div class="hr"><hr></div></div>  <div class="col-sm-12">   <input type="hidden" value="Insert" name="action" id="action"><button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;<input type="submit" class="btn btn-submit" value="Save & Continue">  </div> </div></form></div></div>';

include_once '../../common/footer.php';
