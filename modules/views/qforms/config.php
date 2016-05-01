<?php
$db_table="qforms_qforms";
$LPP = 8;

$cols=array('form_name','send_mail_to','details','message','lang');
$Savecols=array('form_name','send_mail_to','details','message');
$colsUpdate= array('form_name','send_mail_to','details','message');

$types=array('form_name'=>"text",'details'=>"FullTextEditor",'send_mail_to'=>'text','message'=>'SimpleTextEditor','lang'=>'select');
$source=array('lang'=>array("0"=>"languages","1"=>"lang_name","2"=>"lang","type='admin'"));
$required=array("form_name"=>"required","details"=>"","send_mail_to"=>"required","message"=>"");

//Fields
$db_table_Fields="qforms_fields";
$fcols=array('form_id','field_label','type','plus','required');
$fcolsSave=array('field_label','type','plus','required','form_id');

$ftypes=array('form_id'=>'label','field_label'=>'text','type'=>'select','plus'=>'tags','required'=>'flag');
$extra=array('type'=>array('email','field','select','textarea','date'));
$length=array();
$_source=array('form_id'=>array('qforms_qforms','id','form_name',$_REQUEST['form_id']));

$ClassMainField = "row";
$classSubMain = array('col-xs-2', 'pull-left', 'col-xs-9');
$pageBackList='listQforms.php';


$pageList="listQforms.php";
$pageInsert="insertQform.php";
$pageUpdate="updateQform.php";
$listFields="listFields.php";
?>