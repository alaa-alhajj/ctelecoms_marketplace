<?php
include '../../common/header.php';
include 'config.php';

$utiles = $voiControl->ObUtils();

$module_id = $_REQUEST['module_id'];

$query = $fpdo->from('cms_modules')->where(" id='$module_id' ")->fetchAll();
foreach($query as $row){
	$module_id = $row['id'];
	$all_wid_id = $row['all_wid_id'];
	$item_wid_id = $row['item_wid_id'];
	$module_title = $row['title'];
}


$pages = $fpdo->from('cms_pages')->where(" module_id='$module_id' AND is_main='1' ")->fetchAll();
foreach($pages as $page_info){
	${'old_page_title_'.$page_info['lang']} = $page_info['title'];
	${'page_id_'.$page_info['lang']} = $page_info['id'];
}




$query = $fpdo->from('cms_widget_settings')->where(" widget_id='$all_wid_id' ")->fetchAll();
foreach($query as $row){
	$all_set_id = $row['id'];
	$viewAll_temp_id = $row['template_id'];
}
$query = $fpdo->from('cms_widget_settings')->where(" widget_id='$item_wid_id' ")->fetchAll();
foreach($query as $row){
	$item_set_id = $row['id'];
	$view_temp_id = $row['template_id'];
}
if($_REQUEST['finish']){
	$query = $fpdo->from('cms_template_fields')->where(" template_id='$viewAll_temp_id' ")->fetchAll();
	foreach($query as $row){
		$template_field_id = $row['id'];
		$module_field_id = $_REQUEST['all_module_fields_'.$template_field_id];
		if($module_field_id){
			$values = array('widget_id'=>$all_wid_id,'module_field_id'=>$module_field_id,'template_field_id'=>$template_field_id,'action'=>'Insert');
			$save_ob = new saveform('cms_widget_fields', $values, array('widget_id','module_field_id','template_field_id'),'id', '');
		}
		
	}
	
	
	$query = $fpdo->from('cms_template_fields')->where(" template_id='$view_temp_id' ")->fetchAll();
	foreach($query as $row){
		$template_field_id = $row['id'];
		$module_field_id = $_REQUEST['item_module_fields_'.$template_field_id];
		if($module_field_id){
			$values = array('widget_id'=>$item_wid_id,'module_field_id'=>$module_field_id,'template_field_id'=>$template_field_id,'action'=>'Insert');
			$save_ob = new saveform('cms_widget_fields', $values, array('widget_id','module_field_id','template_field_id'),'id', '');
		}
		
	}
	
	$filter_ids = $_REQUEST['filter_ids'];
	foreach($filter_ids as $filter_id){
		$filter_title = $_REQUEST['filter_title_'.$filter_id];
		$values = array('title'=>$filter_title,'settings_id'=>$all_set_id,'field_id'=>$filter_id,'action'=>'Insert');
		$save_ob = new saveform('cms_filter_fields', $values, array('title','settings_id','field_id'),'id', '');
	}
	
	$pagination_id = $_REQUEST['pagination_id'];
	$limit = $_REQUEST['limit'];
	$condition = $_REQUEST['condition'];
	$_REQUST['pagination_id']=$pagination_id;
	$_REQUST['limit']=$limit;
	$_REQUST['condition']=$condition;
	$_REQUST['id']=$all_set_id;
	$_REQUST['action']='Edit';
	$save_ob = new saveform('cms_widget_settings', $_REQUST, array('pagination_id','limit','condition','id'),'id', '');
	
	
	foreach($cms_active_langs as $active_lang){
		$page_id = ${'page_id_'.$active_lang};
		if($page_id){
			$_REQUST['action']=$action = 'Edit';
		}else{
			$_REQUST['action']=$action = 'Insert';
		}
		$page_title = $_REQUEST['page_title_'.$active_lang];
		if(!$page_id){$page_id=Null;}
		$values = array(
						'id'=>$page_id,
						'title'=>$page_title,
						'html'=>"<div class='col-sm-12'>##wid_start####wid_id_start##".$all_wid_id."##wid_id_end####wid_end##</div>",
						'seo_title'=>$page_title,
						'is_main'=>'1',
						'module_id'=>$module_id,
						'lang'=>$active_lang,
						'action'=>$action);
		
			$save_ob = new saveform('cms_pages', $values, array('title','html','seo_title','is_main','module_id','lang'),'id', '');
		
	}
	
	$utils->redirect('listModules.php');
	
}


echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";

if($all_wid_id){
	
	echo "<div class='col-sm-3 ' align='center'>
	
			<div class='col-sm-12 wizerd-block'>
			<div class='col-sm-12 block-title'>Please link template fields with their equivalent module fields for list all items page</div>";
	
	$query = $fpdo->from('cms_template_fields')->where(" template_id='$viewAll_temp_id' ")->fetchAll();
	echo "<table><tr><th align='center'>Template field</th><th>&nbsp;&nbsp;&nbsp;&nbsp;</th><th align='center'>Module Field</th></tr>";
	foreach($query as $row){
		$temp_field_id = $row['id'];
		$temp_field_title = $row['title'];
		$curr_module_field = $utiles->lookupField('cms_widget_fields','id','module_field_id',''," widget_id='$all_wid_id' AND  template_field_id='$temp_field_id' ");
		
		echo "<tr>
				<td>$temp_field_title<input</td>
				<td></td>
				<td>
					".$utils->createComboBoxFiltered('cms_module_fields','id','title',$curr_module_field,"all_module_fields_$temp_field_id","table_id='$module_id'")."
				</td>
				</tr>";
	}
	echo "</table>";
	echo "</div>";
	echo "</div>";

}

if($item_wid_id){
	echo "<div class='col-sm-3 ' align='center'>
			<div class='col-sm-12 wizerd-block'>
			<div class='col-sm-12 block-title'>Please link template fields with their equivalent module fields for item details page</div>";
	
	$query = $fpdo->from('cms_template_fields')->where(" template_id='$view_temp_id' ")->fetchAll();
	echo "<table><tr><th align='center'>Template field</th><th>&nbsp;&nbsp;&nbsp;&nbsp;</th><th align='center'>Module Field</th></tr>";
	foreach($query as $row){
		$temp_field_id = $row['id'];
		$temp_field_title = $row['title'];
		$curr_module_field = $utiles->lookupField('cms_widget_fields','id','module_field_id',''," widget_id='$item_wid_id' AND  template_field_id='$temp_field_id' ");
		
		echo "<tr>
				<td>$temp_field_title<input</td>
				<td></td>
				<td>
					".$utils->createComboBoxFiltered('cms_module_fields','id','title',$curr_module_field,"item_module_fields_$temp_field_id","table_id='$module_id'")."
				</td>
				</tr>";
	}
	echo "</table>";
	echo "</div>";
	echo "</div>";

}

if($all_wid_id){
	echo "<div class='col-sm-3 ' align='center'>
			<div class='col-sm-12 wizerd-block'>
			<div class='col-sm-12 block-title'>Please select the filters you want to apply for this module</div>";
	
	$query = $fpdo->from('cms_module_fields')->where(" table_id='$module_id' ")->fetchAll();
	echo "<table><tr><th align='center'>Module fields</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th align='center'>Is filter?</th></tr>";
	foreach($query as $row){
		$module_field_id = $row['id'];
		$module_field_title = $row['title'];
		
		
		$filter_title = $utiles->lookupField('cms_filter_fields','id','title',''," settings_id='$all_set_id' AND  field_id='$module_field_id' ");
		$is_selected = $utiles->lookupField('cms_filter_fields','id','field_id',''," settings_id='$all_set_id' AND  field_id='$module_field_id' ");
		if($is_selected){$checked="checked";}else{$checked="";}
		echo "<tr>
				<td>$module_field_title<input</td>
				<td></td>
				<td>
					<input type='checkbox' value='$module_field_id' $checked name='filter_ids[]'/>
					<input type='text' value='$filter_title' style='width:80px;' placeholder='Title' name='filter_title_$module_field_id'/>
				</td>
				</tr>";
	}
	echo "</table>";
	echo "</div>";
	echo "</div>";
}
if($all_wid_id){
	echo "<div class='col-sm-3 ' align='center'>
			<div class='col-sm-12 wizerd-block'>
			<div class='col-sm-12 block-title'>General settings</div>";
	

	$query = $fpdo->from('cms_widget_settings')->where(" widget_id='$all_wid_id' ")->fetchAll();
	echo "<table>
			<tr><th align='center'>Settings</th><th>&nbsp;&nbsp;&nbsp;&nbsp;</th><th align='center'</th></tr>";
	foreach($query as $row){
		
		$set_id = $row['id'];
		$pagination_id = $row['pagination_id'];
		$condition = $row['condition'];
		$limit = $row['limit'];
		
		echo "<tr><td>Pagination</td><td></td>
				<td>
					".$utils->createComboBoxFiltered('cms_pagination','id','title',$pagination_id,"pagination_id")."
				</td>
			</tr>";
		echo "<tr><td>limit</td><td></td>
				<td>
					<input type='text' value='$limit' name='limit'/>
				</td>
			</tr>";
		echo "<tr><td>Condition</td><td></td>
				<td>
					<input type='text' value='$condition' name='condition'/>
				</td>
			</tr>";
	}
	
	
	foreach($cms_active_langs as $lang){
		
	echo "<tr><td >Page title ($lang)</td><td></td><td><input type='text' value='".${'old_page_title_'.$lang}."' name='".'page_title_'.$lang."' required/></td></tr>";
	}
	
	
	echo "</table>";
	echo "</div>";
	echo "</div>";
}
echo "<div class='col-sm-12'><input type='submit' value='finish' name='finish'/></div>";
echo "<input type='hidden' value='$module_id' name='module_id'/>";
echo "</form>";

include_once '../../common/footer.php';
?>