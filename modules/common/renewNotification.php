<?php

//$file=fopen('test.txt', 'w');
//$msg='';
//check purchase order products status

$condition="id != '0'";
$r=$fpdo->from('purchase_order_products')->where($condition)->fetchAll();
foreach ($r as $row) {
    $id=$row['id'];
    $product_id=$row['product_id'];
    $purchase_order_id=$row['purchase_order_id'];
    $product_price_id=$row['product_price_id'];
    $is_renew=$row['is_renew'];
    
    //check if notify in same date 
    $lastNotifyDate=$row['last_notification'];
    //$msg.="id=$id,product_id=$product_id,purchase_order_id=$purchase_order_id,product_price_id=$product_price_id \r\n";
    $query = $fpdo->from('purchase_order')->where(array('id' =>$purchase_order_id ))->fetch();
    $customer_id=$query['customer_id'];
    $order_date=$query['order_date'];
    
    $query1 = $fpdo->from('products')->where(array('id' =>$product_id ))->fetch();
    $product_name=$query1['title'];
    //$msg.=implode(' ',$query1)." \r\n";
    $query2 = $fpdo->from('product_price_values')->where(array('id' =>$product_price_id ))->fetch();
    $duration_id=$query2['duration_id'];
    //$msg.=implode(' ',$query2)." \r\n";
    $query3 = $fpdo->from('pro_price_duration')->where(array('id' =>$duration_id ))->fetch();
    $per_month=$query3['per_month'];
    //$msg.=implode(' ',$query3)." \r\n";
    $duration_per_date=$per_month*30;
    $ProductExpiredDate = date("Y-m-d",strtotime($order_date . "+$duration_per_date day"));
    
    //$msg.="ProductExpiredDate=$ProductExpiredDate \r\n";
   
    
    if(((date("Y-m-d")) > $lastNotifyDate) && $is_renew==0){ //check we don't notify in this current date
        $res=diff_dates($ProductExpiredDate,date("Y-m-d"));
        $resArr=$res['remain_time'];  
        $remin_time_str=$res['remain_time_str'];  
        $remin_days=$resArr['days'];

        if($remin_days < 30){
            $customer=$fpdo->from('customers')->where("id = '$customer_id'")->fetch();
            $customer_name= ucwords($customer['name']);
            $email=$customer['email'];
            //send mail to customers
            $to = $email;
            global $utils;
            $tags = array("{customer_name}" => $customer_name, '{product_name}' => $product_name, '{remin_time_str}' => $remin_time_str);
            //$msg.="customer_name=$customer_name,product_name=$product_name,remin_time_str=$remin_time_str \r\n";
            $status=$utils->sendMailC("info@voitest.com", $email, $subject, "", 1, $tags);
            /************************************************************/
            $status='1';
            if($status){
                $fpdo->update("purchase_order_products")->set(array('last_notification' => date("Y-m-d")))->where('id', $id)->execute();
            }
        }
        
     //$msg.="------------------------- \r\n";
    }// end if check last notify    
   
}

//fwrite($file, $msg);
//fclose($file);

function diff_dates($date2,$date1){


        $diff = abs(strtotime($date2) - strtotime($date1));
        $days = floor(($diff)/ (60*60*24));
        $hours = floor(($diff - $days * 60*60*24)/ (60*60));
        $minutes = floor(($diff  - $days * 60*60*24 - $hours *60*60)/ (60));

         //printf("%d days, %d hours, %d minutes\n", $days, $hours, $minutes);
         $res=array();
         if($days!=0){
             $res['days']=$days;
         }
         if($hours!=0){
            $res['hours']=$hours;
         }
         if($minutes!=0){
             $res['minutes']=$minutes;
         }
    
         $result="";
         foreach ($res as $key => $value) {
             if($key=='minutes'){
                   $result.=$value." ".$key." ";
             }else{
                   $result.=$value." ".$key.", ";
             }

         }
         if($result!=""){
             $result.=" "; // $result.=" ago";
         }
        
         
 return array('remain_time'=>$res,'remain_time_str'=>$result);
}
