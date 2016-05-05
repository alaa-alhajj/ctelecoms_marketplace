<?
//----------Define keys -------------------------------------
$query = $fpdo->from('langs_keys')->fetchAll();
foreach($query as $row){
	$l_key = $row['l_key'];
	$lang = $row['lang_'.$pLang];
	define($l_key,$lang);
}

?>