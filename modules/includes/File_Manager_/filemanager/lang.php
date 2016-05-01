<?php
include_once ("../../../../config.php");
require_once '../../../common/dbConnection.php';
    $var = 'CMSLang';
    $var2 = 'VIEWLang';
    $CMSLang = '';
    $VIEWLang = '';
    $langsArray = array();
    $langsArrayView = array();
    global $langsArray, $langsArrayView, $CMSLang, $VIEWLang;

    if (isset($_REQUEST[$var])) {
        $CMSLang = $_REQUEST[$var];
    } else if (isset($_SESSION[$var])) {
        $CMSLang = $_SESSION[$var];
    }

    if (isset($_REQUEST[$var2])) {
        $VIEWLang = $_REQUEST[$var2];
    } else if (isset($_SESSION[$var2])) {
        $VIEWLang = $_SESSION[$var2];
    }

    $sql = "select * from  cms_langs where active =1 ";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    $validLang = 0;
    $validLangView = 0;
    if ($rows > 0) {
        $i = 0;
        while ($i < $rows) {
            $lang = mysql_result($res, $i, 'lang');
            $lang_name = mysql_result($res, $i, 'title');
            $dl = mysql_result($res, $i, 'default_lang');
            $type = mysql_result($res, $i, 'type');
            if ($type == 'admin') {
                if ($dl == 1) {
                    $default_lang = $lang;
                }
                if ($CMSLang == $lang) {
                    $validLang = 1;
                }
                $langsArray[] = array($lang, $lang_name);
            } else {
                if ($dl == 1) {
                    $default_lang_view = $lang;
                }
                if ($VIEWLang == $lang) {
                    $validLangView = 1;
                }
                $langsArrayView[] = array($lang, $lang_name);
            }
            $i++;
        }
    }
    if ($validLang != 1) {
        $CMSLang = $default_lang;
    }
    if ($validLangView != 1) {
        $VIEWLang = $default_lang_view;
    }
    $_SESSION[$var] = $CMSLang;
    $_SESSION[$var2] = $VIEWLang;