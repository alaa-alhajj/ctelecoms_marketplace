<?php
$db_table="langs_keys";
$LPP = 20;

$langs = $fpdo->from('cms_langs')->where('active', '1')->fetchAll();


$cols=array('l_key');
$Savecols=array('l_key');
$colsUpdate= array();
$types=array('l_key'=>"text");
$required=array("l_key"=>"required");



foreach($langs as $lang){
	$lang_field = 'lang_'.$lang['lang'];
	array_push($cols, $lang_field);
	array_push($Savecols, $lang_field);
	array_push($colsUpdate, $lang_field);
	 $types[$lang_field] = 'text';
	 
}

$pageList="listLang.php";
?>
