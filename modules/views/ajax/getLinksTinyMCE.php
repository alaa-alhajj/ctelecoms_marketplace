<?php

include "../../common/top_ajax.php";
$json = array();
$query = $fpdo->from('cms_pages')->select("concat('##site_pref####site_lang##/page',id,'/TitlePage') as link")->orderBy('title asc')->fetchAll();
foreach ($query as $row) {
    $selectoption = array();
    $selectoption['title'] = $row['title'];
    $selectoption['value'] = $row['link'];
    array_push($json, $selectoption);
}

echo json_encode($json);
