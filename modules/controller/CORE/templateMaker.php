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
class tempMaker extends utils {

    protected $fpdo;

    function __construct() {
        global $fpdo;
        $this->fpdo = & $fpdo;
    }
	
	
	function getContents($str, $startDelimiter, $endDelimiter) {
		$contents = array();
		$startDelimiterLength = strlen($startDelimiter);
		$endDelimiterLength = strlen($endDelimiter);
		$startFrom = $contentStart = $contentEnd = 0;
		while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
			$contentStart += $startDelimiterLength;
			$contentEnd = strpos($str, $endDelimiter, $contentStart);
			if (false === $contentEnd) {
				break;
			}
			$contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
			$startFrom = $contentEnd + $endDelimiterLength;
		}
		return $contents;
	}
  

    public function insertTmplateFields($main_html,$item_html,$temp_id) {
		$html = $main_html.$item_html;
		$temp_fields_arr = $this->getContents($html,'$$','$$');
		$fields_arr=array();
		$i=0;
		foreach($temp_fields_arr as $temp_field){
			 array_push($fields_arr,$temp_field);
			 $query = $this->fpdo->insertInto('cms_template_fields')->values(array('title'=>$temp_field,'template_id'=>$temp_id))->execute();
			$i++;
		}
		$query = $this->fpdo->from('cms_template_fields')->where(" template_id='$temp_id' ")->fetchAll();
		foreach($query as $row){
			if(!in_array($row['title'],$fields_arr)){
				$query = $this->fpdo->deleteFrom('cms_template_fields')->where("id = ".$row['id']." ")->execute();
			}
		}
		return $i;
    }
	
	
    public function insertTemplateSettings($main_html,$item_html,$temp_id) {
		$html = $main_html.$item_html;
		$temp_fields_arr = $this->getContents($html,'@@','@@');
		$fields_arr=array();
		$i=0;
		foreach($temp_fields_arr as $temp_field){
			 array_push($fields_arr,$temp_field);
			 $query = $this->fpdo->insertInto('cms_template_settings')->values(array('replace_stm'=>$temp_field,'template_id'=>$temp_id))->execute();
			$i++;
		}
		$query = $this->fpdo->from('cms_template_settings')->where(" template_id='$temp_id' ")->fetchAll();
		foreach($query as $row){
			if(!in_array($row['replace_stm'],$fields_arr)){
				$query = $this->fpdo->deleteFrom('cms_template_settings')->where("id = ".$row['id']." ")->execute();
			}
		}
		
		return $i;
    }
	
    public function insertTemplateLabels($main_html,$item_html) {
		$html = $main_html.$item_html;
		$temp_labels_arr = $this->getContents($html,'**','**');
		$i=0;
		foreach($temp_labels_arr as $temp_label){
			 $query = $this->fpdo->insertInto('langs_keys')->values(array('l_key'=>$temp_label,'lang_en'=>$temp_label))->execute();
			$i++;
		}
		
		return $i;
    }

}