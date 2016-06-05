<?php
include('../../view/common/top.php');

print_r($_REQUEST);
$customer_id=$_REQUEST['customer_id'];
$product_id=$_REQUEST['product_id'];
if($customer_id!='' && $product_id!=''){
    //insert request
    $query = $fpdo->insertInto('pro_try_free_requests‎')->values(array('customer_id'=>$customer_id,'product_id'=>$product_id,'request_date'=>date("Y-m-d")))->execute(); 
    if($query!=''){
        //send new customer notification to admin
        //get recipient_email
        $mail_dts=$fpdo->from('mails')->where('id=6')->fetch();
        $recipient_email=$mail_dts['recipient_email'];
        
        $customer_dts=$fpdo->from('customers')->where("id='$customer_id'")->fetch();
        $customerName=$customer_dts['name'];
        
        $product_dts=$fpdo->from('products')->where("id='$product_id'")->fetch();
        $productName=$product_dts['title'];
        
        $tags = array("{customer-name}" => $customerName,'product-name'=>$productName,'{free-request-datetime}' => date("Y-m-d h:i:s"));
        $utils->sendMailC("info@voitest.com", $recipient_email, "New product free request notification", "", 6, $tags);
        echo "your try free request send successfully ";
    }else{
        echo "Sorry, send your try free request failed. try later";
    }
}


?>