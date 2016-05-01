<?php

function getWidgetSettings($widget_id){
	$sql = "SELECT * FROM cms_widget_settings where widget_id='$widget_id' ";
	$result = mysql_query($sql);
	$numrows = mysql_num_rows($result);
	if($numrows){
		$i=0;
		$condition = str_replace('where','',mysql_result($result,$i,'condition'));
		if($condition){$condition=' WHERE '.$condition;}
		$lpp = mysql_result($result,$i,'limit');
		$lpp2 = mysql_result($result,$i,'limit');
		if(!$lpp){
			$lpp = 3;	
		}	
		$pn = 0;
		if($_REQUEST['pn']){$pn = $_REQUEST['pn'];}
		$start = $pn*$lpp;
		$limit = " LIMIT  $start, $lpp";
		
		$template_id = mysql_result($result,$i,'template_id');
		$pagination_id = mysql_result($result,$i,'pagination_id');
		return array(
			'condition'=>$condition,
			'limit'=>$limit,
			'limit_num'=>$lpp2,
			'template_id'=>$template_id,
			'pagination_id'=>$pagination_id
		);
	}else{
		return '';
	}
}

function getTempFieldSettings($field_id){
	$sql = "SELECT * FROM cms_tem_field_settings where field_id='$field_id' ";
	$result = mysql_query($sql);
	$numrows = mysql_num_rows($result);
	if($numrows){
		$i=0;
		$limit = mysql_result($result,$i,'limit');
		$width = mysql_result($result,$i,'width');
		$height = mysql_result($result,$i,'height');
		$resize_type = mysql_result($result,$i,'resize_type');
		return array(
			'limit'=>$limit,
			'width'=>$width,
			'height'=>$height,
			'resize_type'=>$resize_type
		);
	}else{
		return '';
	}
}


function getTemplate($widget_id,$template_id){
	
	$sql="SELECT * FROM cms_templates temp left join  cms_template_settings temp_settings  on temp.id=temp_settings.template_id where temp.id='$template_id'"; 
	$result = mysql_query($sql);
	$numrows = mysql_num_rows($result);
	if($numrows){
		$i=0;
		$temp_main_html = mysql_result($result,$i,'temp.main_html');
		$temp_item_html = mysql_result($result,$i,'temp.item_html');
		while($i<$numrows){
			
			$temp_setting_id = mysql_result($result,$i,'temp_settings.id');
			$default_value = mysql_result($result,$i,'temp_settings.default_value');
			$replace_stm = mysql_result($result,$i,'temp_settings.replace_stm');
			if($replace_stm){$replace_stm='##'.$replace_stm.'##';}
			$result2 = mysql_query( "SELECT value from cms_widget_temp_settings WHERE 
			template_settings_id='$temp_setting_id' AND 
			widget_settings_id = (SELECT id from cms_widget_settings WHERE widget_id='$widget_id')");
			$numrows2 = mysql_num_rows($result2);
			if($numrows2){
				$replace_value=mysql_result($result2,0,'value');
			}else{
				$replace_value=$default_value;
			}
			
			
			$temp_main_html = str_replace($replace_stm,$replace_value,$temp_main_html);
			$temp_item_html = str_replace($replace_stm,$replace_value,$temp_item_html);
			
			$i++;
		}
	}
	
	return array(
			'main_html'=>$temp_main_html,
			'item_html'=>$temp_item_html);
}



function getWidget($widget_id,$page_id,$condition){
	
	$wid_settings = getWidgetSettings($widget_id);
	$widget_title = lookupField('cms_widgets','id','title',$widget_id);
	$module_id = lookupField('cms_widgets','id','module_id',$widget_id);
	$table_name = lookupField('cms_modules','id','table_name',$module_id);

	
	$temp_html = getTemplate($widget_id,$wid_settings['template_id']);
	$template_main_html = $temp_html['main_html'];
	$template_item_html = $temp_html['item_html'];
	
	$final_condition = $wid_settings['condition'];
	if($final_condition&&$condition){
		$final_condition = $wid_settings['condition'].' AND '.$condition;
	}elseif($condition){
		$final_condition = ' WHERE '.$condition;
	}
	
	$widget_sql = "SELECT * FROM `$table_name` ".$final_condition." ".$wid_settings['limit'];
	
	$widget_result = mysql_query($widget_sql);
	$widget_numrows = mysql_num_rows($widget_result);
	$widget_total_numrows = mysql_num_rows(mysql_query("SELECT * FROM `$table_name` ".$final_condition));
	
	
	if($widget_numrows){
		$s=0;
		while($s<$widget_numrows){
			$sql = "SELECT * FROM cms_widget_fields wid_fields, cms_template_fields temp_fields 
			WHERE wid_fields.widget_id='$widget_id' and temp_fields.id=wid_fields.template_field_id";
			$result = mysql_query($sql);
			$numrows = mysql_num_rows($result);
			if($numrows){
				$i=0;
				$item_html = $template_item_html;
				while($i<$numrows){
					$template_field_id = mysql_result($result,$i,'wid_fields.template_field_id');
					$template_field_title = mysql_result($result,$i,'temp_fields.title');
					$temp_feild_settings = getTempFieldSettings($template_field_id);
					
					$module_field_id = mysql_result($result,$i,'wid_fields.module_field_id');
					$module_field_title = lookupField('cms_module_fields','id','title',$module_field_id);
					$module_field_type = lookupField('cms_module_fields','id','type',$module_field_id);
					
					$item_val = mysql_result($widget_result,$s,$module_field_title);
					$sub_page_id = mysql_result($widget_result,$s,'page_id');
					$sub_page_title = lookupField('cms_pages','id','title',$sub_page_id);
					$sub_page_link=_PREF.$_SESSION['pLang'].'/page'.$sub_page_id.'/'.$sub_page_title;

					if($module_field_type=='photo'){
						
						if($temp_feild_settings['resize_type']=='crop'||$temp_feild_settings['resize_type']=='resize'){
							$thumb =viewPhoto($item_val,$temp_feild_settings['resize_type'],$temp_feild_settings['width'], $temp_feild_settings['height'],'css',1,$_SESSION['dots'],0);
						}else{
							$thumb =viewPhoto($item_val,'full','', '','css',1,$_SESSION['dots'],0);
						}
						$thumb =str_replace(');','',str_replace('background-image:url(','',$thumb));
						$item_val = $thumb;
					}else{
						if($temp_feild_settings['limit']){
							$item_val = limit($item_val,$temp_feild_settings['limit']);
						}
					}
					
					$item_html = str_replace("##$template_field_title##",$item_val,$item_html);
					
					$item_html = str_replace("##sub_link##",$sub_page_link,$item_html);
					
					
					
					
					
					
					
					$i++;
				}
				
				$extra_widgets_arr = getContents($item_html,'##wid_start##','##wid_end##');
	
				
				foreach($extra_widgets_arr as $widget_content){
					$sub_widget_id = getContents($widget_content,'##wid_id_start##','##wid_id_end##')[0];
					$widget_condition = getContents($widget_content,'##wid_con_start##','##wid_con_end##')[0];
					
					$con_fields_arr = getContents($widget_condition,'##','##');
					foreach($con_fields_arr as $field){
						
						$widget_condition = str_replace("##$field##",mysql_result($widget_result,$s,$field),$widget_condition);
					}
					
					$extra_widgets = getWidget($sub_widget_id,'',$widget_condition);
					$item_html = replaceContents($item_html,'##ws'.$sub_widget_id.'##','##we'.$sub_widget_id.'##',$extra_widgets);
					$item_html = str_replace('##ws'.$sub_widget_id.'##','',str_replace('##we'.$sub_widget_id.'##','',$item_html));
					

				}
				$widget_items_html .= $item_html;
			}
			$s++;
		}
		
	}
	
	$extra_widgets_arr = getContents($template_main_html,'##wid_start##','##wid_end##');
	
	
	foreach($extra_widgets_arr as $widget_content){
		
		$widget_id = getContents($widget_content,'##wid_id_start##','##wid_id_end##')[0];
		$widget_condition = getContents($widget_content,'##wid_con_start##','##wid_con_end##')[0];
		$con_fields_arr = getContents($widget_condition,'##','##');
		foreach($con_fields_arr as $field){
			$widget_condition = str_replace("##$field##",mysql_result($widget_result,0,$field),$widget_condition);
		}
		
		
		$extra_widgets = getWidget($widget_id,'',$widget_condition);
		$template_main_html = replaceContents($template_main_html,'##ws'.$widget_id.'##','##we'.$widget_id.'##',$extra_widgets);
		$template_main_html = str_replace('##ws'.$widget_id.'##','',str_replace('##we'.$widget_id.'##','',$template_main_html));
		

	}
	
	$link=_PREF.$_SESSION['pLang'].'/page'.$page_id.'/pn^/'.$widget_title;
	$main_page_title = lookupField('cms_pages','id','title',$page_id);
	$final_html = str_replace("##main_page_title##",$main_page_title,$template_main_html);
	$final_html = str_replace("##site_pref##",_PREF,$final_html);
	$final_html = str_replace("##template_items##",$widget_items_html,$final_html);
	$final_html .= getPagination($wid_settings['pagination_id'],$widget_total_numrows,$wid_settings['limit_num'],$link);
	
	if(!$final_html){
		$final_html='';
	}
	
	return $final_html;
}

function printWidget($widget_id){
	$widget = getWidget($widget_id,'','');
	return $widget = str_replace('##wid_start##','',str_replace('##wid_end##','',$widget));
}

function getPagination($paging_id,$tp,$LPP,$link){
	
	if($_REQUEST['pn']){
		$pn = $_REQUEST['pn'];
	}else{
		$pn = 0;
	}
	
	if($paging_id){
		switch($paging_id){
			case 1: 
				return createPagination_bootstrap($tp, $pn, $LPP, "$link");
			break;
			default : return '';
			break;
		}
	}else{
		return '';
	}
}

function getPage($page_id){
	$result = mysql_query("select * from cms_pages where id='$page_id' ");
	$numrows = mysql_num_rows($result);
	if($numrows){
		$page_title = mysql_result($result,$i,'title');
		$page_html = mysql_result($result,$i,'html');
		$page_type = mysql_result($result,$i,'type');
		$widget_id = mysql_result($result,$i,'widget_id');

		$widget_arr = getContents($page_html,'##wid_start##','##wid_end##');
		$final_html = $page_html;
		
		foreach($widget_arr as $widget_content){
			$widget_id = getContents($widget_content,'##wid_id_start##','##wid_id_end##')[0];
			$widget_condition = getContents($widget_content,'##wid_con_start##','##wid_con_end##')[0];
			$template_html = getWidget($widget_id,$page_id,$widget_condition);
			$final_html = str_replace('##wid_start##','',str_replace('##wid_end##','',replaceContents($final_html,'##wid_start##','##wid_end##',$template_html)));
		}
		
		
		return $final_html;
	}
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

function replaceContents($str, $needle_start, $needle_end, $replacement) {
	
	$pos = strpos($str, $needle_start);
	$start = $pos === false ? 0 : $pos + strlen($needle_start);

	$pos = strpos($str, $needle_end, $start);
	$end = $pos === false ? strlen($str) : $pos;

	return substr_replace($str, $replacement, $start, $end - $start);
}


function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}


function getFilters($fitler_id){
	
}
?>