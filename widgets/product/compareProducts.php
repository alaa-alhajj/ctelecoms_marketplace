<?php
 $compareIDs=$_SESSION['compareIDs'];
 ?>

<?php
 global $utils;
 //print_r($compareIDs); 
 //echo "<hr>";
 $res=checkCompareAbility($this->fpdo,$compareIDs); 
 $compare_status=$res['compareStatus'];
 if($compare_status){
            //get products information  
             $productslist=getProductsInformation($this->fpdo,$compareIDs);
             
            //------------------------
             //get compared features 
             $cat_id=$res['cat_id'];
             $featureList='';
             $featureList.=getFeatureValueForProducts($this->fpdo,$compareIDs,$cat_id);
             //------------------------
             // get features that relate to sub categories ids
             $sub_Cat_ids= getSubCat_IDs_FromComparedProducts($this->fpdo,$compareIDs);
             $featureList.=getFeatureValueForProductsBySubCat_id($this->fpdo,$compareIDs,$sub_Cat_ids);
         

     ?>

     <div class="col-sm-12 nopadding">
         <div class="table-responsive">
             <table class="table table-bordered table-hover table-striped">
                 <thead>
                 <tr>
                     <th width="150px"></th>
                     <?php echo $productslist;?>
                 </tr>
                 </thead>
                 <tbody>
                     <?=$featureList?>
                 </tbody>

             </table>
         </div>

     </div>
<?php
 }else{
  ?>
    <div class="alert alert-danger">Sorry, You can't compare these products.</div>
<?php
 }
 ?>

<?php
    //function here
    // check Compare Ability for products ids
   function checkCompareAbility($fpdo,$products_ids) {
        
        $cat_ids=array();
        foreach ($products_ids as $id) {
            $product_info = $fpdo->from("products")->where("id='$id'")->fetch();
            $cat_id=$product_info['cat_id'];
            $cat_ids[]=$cat_id;
        }
        //print_r($cat_ids);
        $compareStatus=FALSE;
        if ((count(array_unique($cat_ids)) === 1) &&  (count($cat_ids) > 0)) {
            $compareStatus=True;
             return array('compareStatus'=>$compareStatus,'cat_id'=>$cat_ids[0]);
       }
       
       return array('compareStatus'=>$compareStatus,'cat_id'=>'');
   }
   
   function getProductsInformation($fpdo,$compareIDs){
            $productslist='';
            foreach ($compareIDs as $product_id) {
                $get_product_details = $fpdo->from("products")->where("id='$product_id'")->fetch();
                //print_r($get_product_details);
                $product_name=$get_product_details['title'];
                //get product photo
                $photos = explode(',', $get_product_details['photos']);
                $pro_photo=$photos[0];
                if($pro_photo && $pro_photo!='' ){
                    global $utils;
                  
                    $product_photo = $utils->viewPhoto($pro_photo, 'crop', 200, 200, 'img', 1, $_SESSION['dots'],1, '', 'img-responsive');
                    //$product_photo = "<img src='"._Include."css/images/no_photo.png' width='200px' heigh='300px'>";
                }

                //generate compare table head 
                $productslist.="
                               <th>
                                    $product_photo
                                    <div><h2>  $product_name </div></div>
                                </th>
                   ";

             }
             return $productslist;
   }
   
    //function here
   function getFeatureValueForProducts($fpdo,$products_ids,$cat_id) {
        
       $features = $fpdo->from("product_features")->where("cat_id='$cat_id'")->fetchAll();
       //print_r($features);
       $featuresList='';
        foreach ($features as $feature) {
            $featuresList.='<tr>';
            $feature_id=$feature['id'];
            $feature_title=$feature['title'];
            $featuresList.='<td> <strong> '.$feature_title.' </strong></td>';
            
            foreach ($products_ids as $pro_id) {
                    $features_values = $fpdo->from("product_features_values")->where("product_id='$pro_id' and feature_id = '$feature_id'")->fetch();
                    $value=$features_values['value'];
                    //generate compare table head 
                     $featuresList.="<td>$value</td> ";
            }
            $featuresList.='</tr>';
        }
       
       return $featuresList;
   }
   function getSubCat_IDs_FromComparedProducts($fpdo,$products_ids) {
            $sub_cat_ids=array();
            foreach ($products_ids as $product_id) {
                 $get_product_details = $fpdo->from("products")->where("id='$product_id'")->fetch(); 
                 $sub_cat_id=$get_product_details['sub_cat_id'];
                 if(!in_array($sub_cat_id, $sub_cat_ids)){
                     $sub_cat_ids[]=$get_product_details['sub_cat_id'];
                 }
            }
            return $sub_cat_ids;
   }
   
   function getFeatureValueForProductsBySubCat_id($fpdo,$products_ids,$sub_cat_ids) {
        $featuresList='';
        
        //print_r($sub_cat_ids);
       foreach ($sub_cat_ids as $sub_cat_id) {  //sub category id
                    $features = $fpdo->from("product_features")->where("sub_cat_id='$sub_cat_id'")->fetchAll();
                    //print_r($features);
                    foreach ($features as $feature) {
                     $featuresList.='<tr>';
                     $feature_id=$feature['id'];
                     $feature_title=$feature['title'];
                     $featuresList.='<td> <strong> '.$feature_title.' </strong></td>';

                     foreach ($products_ids as $pro_id) {
                             $features_values = $fpdo->from("product_features_values")->where("product_id='$pro_id' and feature_id = '$feature_id'")->fetch();
                             $value=$features_values['value'];
                             //generate compare table head 
                              $featuresList.="<td>$value</td> ";
                     }
                     $featuresList.='</tr>';
                    }
       }   
       return $featuresList;
   }
?>