<?php

include '../../common/header.php';
include 'config.php';
$id=$_REQUEST['off'];
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $Related_ids="";
   foreach ($_REQUEST['Slectedproduct'] as $product_id) {
       $Related_ids.=$product_id.",";
      
   }
   $query = $fpdo->update($db_table)->set(array('`product_ids`' => $Related_ids))->where("id='$id'");
    $exec = $query->execute();

  echo '<script>notificationMessage(true);
        </script>';
    if($Related_ids !=""){
    $utils->redirect($pageList);
    }else{

       echo '<script>notificationMessage(false);
        </script>';
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Offer Info</a></li>
    <li class="active-menue"><a href="#">Offer Products</a></li>
    
</ul>';





$get_From_products = $query = $fpdo->from('products')
        ->fetchAll();
$select_products = "";
$select_products.='<div style="position:relative"><select class="" name="AllAddOns" id="AllAddOns" style="width: 100%;">';
foreach ($get_From_products as $pro_desc) {
    $pro_id = $pro_desc['id'];
    $select_products.="<option value='$pro_id' > " . $pro_desc['title'] . '</option>';
}
$select_products.="</select></div>";
$AllProducts.="<div class='row search-style'>"
        . "<div class='col-sm-10'>".$select_products."</div>"
        . '<div class="col-sm-2"><button type="button" id="buttonSearchAddOns" data-place="Search Offer Products" class="btn btn-submit"><span class="fa fa-search"></span></button></div>'
        . "</div><div class='AddAddOnsTo div-search'>";
$productsA = $query = $fpdo->from('offers')->where("id='".$_REQUEST['off']."'")->fetch();

$addOns_array=  explode(',', rtrim($productsA['product_ids'],','));
foreach($addOns_array as $addOns){
        if($addOns !=""){
     $productsName = $query = $fpdo->from('products')->where("id='" . $addOns . "'")->fetch();
        $AllProducts.="<div id='AddonsSelect$addOns' data-id='$addOns'  >" . $productsName['title'] . ""
                . "<a data-id='$addOns' href='javascript:;'  class='remove-AddOns'><i class='fa fa-times'></i></a>"
                . "<input type='hidden' value='" . $addOns . "' name='Slectedproduct[]'></div>";
        }
}
$AllProducts.= "</div>";



$form = new GenerateFormField();
$form->setColumns();
$form->setTypes();
$form->setExtendTables($source);
$form->setRequireds($required);

$form->setCountCell(1);
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');
$form->setAsForm(true);
$form->setSkipBtn(true, $pageList);
$form->setBackBtn(true);
$form->setAppendToForm($AllProducts);
echo $form->getForm('Insert');
include_once '../../common/footer.php';
