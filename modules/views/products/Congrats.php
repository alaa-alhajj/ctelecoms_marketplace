<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
$id = $_REQUEST['id'];

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
