<?php
$db_table="mails";

$LPP = 8;

$cols=array('name','email_type');
$cols_ins=array('name','email_type','username','password','recipient_email','title','description');


$types=array('name'=>"text",'email_type'=>'text','username'=>'text','password'=>'password','recipient_email'=>'text','title'=>'text','description'=>'FullTextEditor');
$source=array('customer_id' => array('customers', 'id', 'name'));
$required=array('name','email_type','username','password','title','description');

$cols_popup=array('description');
$types_popup=array('description'=>'FullTextEditor');
$required_popup=array('description');
$pageList="listMails.php";
$pageUpdate="updateMail.php";


?>
