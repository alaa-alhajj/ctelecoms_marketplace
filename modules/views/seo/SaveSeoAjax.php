<?php

include "../../common/top.php";
include 'config.php';

$page_id = $_REQUEST['page_id'];

$fpdo->update($dbtable_seo)->set(array('seo_title' => $_REQUEST['seo_title'], "seo_keywords" => $_REQUEST['seo_keywords'], "seo_description" => $_REQUEST['seo_description'], "seo_img" => $_REQUEST['seo_img']))->where('id', $page_id)->execute();

echo json_encode($page_id);
