<?php

include "../../common/top_ajax.php";
$cat_id=$_REQUEST['cat'];
$get_sub=$fpdo->from("product_sub_category")->where("cat_id='$cat_id'")->fetchAll();

  
    $sub_list="<option>------</option>";
    foreach($get_sub as $row){
        $sub_list.="<option value='".$row['id']."'>".$row['title']."</option>";
    }
    
    
    echo json_encode(array($sub_list));
    

       

