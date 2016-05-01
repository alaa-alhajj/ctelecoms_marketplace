<?php

include dirname(__FILE__)."/SQLCORE/FluentPDO.php";

$pdo = new PDO("mysql:host=".DATABASE_URL.";dbname=".DATABASE_NAME, DATABASE_USER, DATABASE_PASS); 

$pdo->exec("SET NAMES 'utf8';");
$pdo->exec("SET SESSION query_cache_type = OFF;");

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

$fpdo = new FluentPDO($pdo);
