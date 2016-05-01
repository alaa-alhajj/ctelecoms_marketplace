<?php
$db_table="cms_users";
$LPP = 8;

// types
$cols=array('full_name','username','password','email','grp_id');
$Savecols=array('full_name','username','password','email','grp_id');
$colsUpdate= array('full_name','username','email','grp_id');
$types=array('full_name'=>'text','username'=>'text','password'=>'text','email'=>'email','grp_id'=>"select");

$source=array("grp_id"=>array("0"=>"cms_groups","1"=>"title","2"=>"id"));

$required=array("full_name"=>"required",'username'=>'required','password'=>'required','email'=>'required','grp_id'=>'required');

$pageList="listUsers.php";
$pageInsert="insertUsers.php";

$user_id = '3';
?>