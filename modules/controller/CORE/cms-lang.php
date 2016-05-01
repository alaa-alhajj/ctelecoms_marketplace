<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Roles
 *
 * @author Ahmad mahmoud
 */
class cms_lang extends utils {

    protected $fpdo;

    function __construct() {
        global $fpdo;
        $this->fpdo = & $fpdo;
    }
	
	
	function getDefaultLang() {
		$row = $this->fpdo->from('cms_langs')->where(" is_default='1' ")->fetch();
		$def_lang = $row['lang'];
		return $def_lang;
	}
  
	function getAllLangs(){
		$query = $this->fpdo->from('cms_langs')->where(" active='1' ")->fetchAll();
		$langs_arr=array();
		foreach($query as $row){
			$lang = $row['lang'];
			array_push($langs_arr,$lang);
		}
		return $langs_arr;
	}

}