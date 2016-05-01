<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
$id = $_REQUEST['pro_id'];

$values1 = $fpdo->from($db_pro_FAQ)->where('product_id', $_REQUEST['pro_id'])->fetch();

if (isset($_REQUEST) && $_REQUEST['action'] == "Insert") {

    $_REQUEST['product_id'] = $id;

    $save_ob = new saveform($db_pro_FAQ, $_REQUEST, $Savecols_FAQ, 'id', '', '', '', false);


    if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } elseif ($_REQUEST['InsertNew'] != "") {
        $utils->redirect($pageProductFAQ . "?pro_id=" . $id);
    } else {
        $utils->redirect($pageProductSEO . "?pro_id=" . $id);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Product Data</a></li>
    <li><a href="#">Features</a></li>
    <li><a href="">Photos</a></li>
    <li><a href="#">Pricing</a></li>
    <li><a href="#">Add-ons</a></li>
    <li><a href="#">Related Products</a></li>
    <li><a href="#">Required Fields</a></li>
    <li class="active-menue"><a href="#">FAQ</a></li>
    <li><a href="#">SEO</a></li>
</ul>';


$form = new GenerateFormField();
$form->setColumns($cols_faq);
$form->setTypes($types_faq);
$form->setExtendTables($source);
$form->setRequireds($required);
//$form->setSkipBtn(true, $pageProductSEO . "?pro_id=" . $_REQUEST['pro_id']);
$form->setAddBtn(true, "", "Save & Insert New", "btn btn-new");
$form->setBackBtn(true);
$form->setSkipBtn(true, $pageProductSEO . "?pro_id=" . $_REQUEST['pro_id']);
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setCountCell(1);
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');

$form->setAsForm(true);
echo $form->getForm('Insert');
if ($values1['id'] != "") {

    $get_faqs = $fpdo->from($db_pro_FAQ)->where('product_id', $_REQUEST['pro_id'])->fetchAll();
    $faq = ' <div class="box box-danger form-horizontal"><div class="box-body">  <div class="panel-group" id="accordion">
        <div class="overlay">
    
</div>';

    $i = 1;
    foreach ($get_faqs as $faq_data) {

        $field_ob = new field();
        $field_ob->SetIdField("question" . $faq_data['id']);
        $field_ob->SetNameField("question");
        $field_ob->SetCssClass("");
        $field_ob->SetRequiredField("");
        $field_ob->SetValueField($faq_data['question']);
        $field_ob->SetTypeField("SimpleTextEditor");
        $field_ob->SetTable("product_faq");
        $field_ob->SetTname("question");
        $field_ob->SetTvalue("id");
        $field_ob->setWhere("");
        $field_ob->setWithAdd(false);
        $field_ob->SetExtra();
        $Question = "" . $field_ob->getField() . "";


        $field_ob2 = new field();
        $field_ob2->SetIdField("answer" . $faq_data['id']);
        $field_ob2->SetNameField("answer");
        $field_ob2->SetCssClass("");
        $field_ob2->SetRequiredField("");
        $field_ob2->SetValueField($faq_data['answer']);
        $field_ob2->SetTypeField("SimpleTextEditor");
        $field_ob2->SetTable("product_faq");
        $field_ob2->SetTname("answer");
        $field_ob2->SetTvalue("id");
        $field_ob2->setWhere("");
        $field_ob2->setWithAdd(false);
        $field_ob2->SetExtra();
        $answer = "" . $field_ob2->getField() . "";

      /*  if ($i === 1) {
            $class = 'in';
        } else {
            $class = "";
        }
       
       */
        $faq.='<div class="panel panel-default panel-faq faq_' . $faq_data['id'] . '">
                        <div class="faq-pointer">
                           
                                <div class="panel-heading">
                                 <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#' . $faq_data['id'] . '">
                                    <h4 class="panel-title">
                                       ' . strip_tags($faq_data['question']) . '
                                        
                                    </h4>
                                     </a>
                                     <a class="accordion-toggle pull-right faq-a" data-toggle="collapse" data-parent="#accordion" href="#' . $faq_data['id'] . '">
                                  
                                     
                                        <i class="indicator glyphicon glyphicon-triangle-bottom "></i>
                               
                                     </a>
                                     <a href="javascript:;" class="remove-FAQ pull-right" data-id="' . $faq_data['id'] . '"> <i class="fa fa-times " ></i></a>
                                     
                                </div>
                           
                        </div>
                        <div id="' . $faq_data['id'] . '" class="panel-collapse collapse faqctel ' . $class . '">
                            <div class="panel-body"  >
<label>Question:</label>                           
' . $Question . '
    <div class="col-sm-12 nopadding"><hr></div>
    <label>Answer:</label>  
                                ' . $answer . '
                                    <div class="col-sm-12 nopadding" style="margin-top:20px"><a href="javascript:;" class="btn btn-submit saveFAQ" data-id="' . $faq_data['id'] . '">Update</a></div>
                            </div>
                        </div>
                    </div>';
        $i++;
    }

    $faq.='</div>';
    /*   $faq.='<div class="hr"><hr></div>';
      $faq.='<div class="col-sm-12"> '
      . '<button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;'
      . '<a href="'.$pageProductSEO . "?pro_id=" . $id.'" class="btn btn-back ">Skip</a>&nbsp;'
      . '<a href="'.$pageList . '" class="btn btn-new ">Close</a>&nbsp;'
      . '<a href="'.$pageProductSEO . "?pro_id=" . $id.'" class="btn btn-submit ">Continue</a>&nbsp;'; */
    $faq.='</div></div>';
    echo $faq;
}

//echo '<div class="box box-danger form-horizontal"><div class="box-body"><form method="post" class="" name="" id=""><input type="hidden" name="backLink" value="http://localhost/ctelecoms_marketplace/modules/views/category/listCategories.php"><div class="Form-Field form-generate-voila "><div class=""><div class="form-group"><label class="col-sm-2 control-label"><span class="red  required">* </span>title: </label><div class="col-sm-10"><input id="title" name="title" value="" type="text" required="" size="" class=" form-control" placeholder=""></div></div><div class="hr"><hr></div></div>  <div class="col-sm-12">   <input type="hidden" value="Insert" name="action" id="action"><button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;<input type="submit" class="btn btn-submit" value="Save & Continue">  </div> </div></form></div></div>';

include_once '../../common/footer.php';
