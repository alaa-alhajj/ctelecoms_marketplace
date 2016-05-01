<?php
include '../../common/header.php';
include 'config.php';

$listTable = $voiControl->ObListTable();
$utiles = $voiControl->ObUtils();
$is_static = $_REQUEST['is_static'];

$module_id = $_REQUEST['module_id'];

$query = $fpdo->from('cms_modules')->where(" id='$module_id' ")->fetchAll();
foreach($query as $row){
	$module_id = $row['id'];
	$item_wid_id = $row['item_wid_id'];
	$all_wid_id = $row['all_wid_id'];
	$module_title = $row['title'];
}


if($_REQUEST['Next']){
	$viewAll_temp = $_REQUEST['viewAll_temp'];
	$view_temp = $_REQUEST['view_temp'];

	if($viewAll_temp && !$all_wid_id){
		$values = array('module_id'=>$module_id,'title'=>$module_title.' - list all items page','action'=>'Insert');
		$save_ob = new saveform('cms_widgets', $values, array('module_id','title'),'id', '');
		
		$all_wid_id=$save_ob->getInsertId();
		$values = array('template_id'=>$viewAll_temp,'widget_id'=>$all_wid_id,'action'=>'Insert');
		$save_ob = new saveform('cms_widget_settings', $values, array('template_id','widget_id'),'id', '');
		
		$_REQUST['all_wid_id']=$all_wid_id;
		$_REQUST['id']=$module_id;
		$_REQUST['action']='Edit';
		$save_ob = new saveform('cms_modules', $_REQUST, array('all_wid_id','id'),'id', '');
	}elseif($all_wid_id){
		
		$wid_settings_id = $utils->lookupField('cms_widget_settings', 'id', 'id', ''," widget_id='$all_wid_id' ");
		$values = array('id'=>$wid_settings_id,'template_id'=>$viewAll_temp,'widget_id'=>$all_wid_id,'action'=>'Edit');
		$save_ob = new saveform('cms_widget_settings', $values, array('template_id','widget_id'),'id', '');
	}elseif(!$viewAll_temp){
		$_REQUST['all_wid_id']=0;
		$_REQUST['id']=$module_id;
		$_REQUST['action']='Edit';
		$save_ob = new saveform('cms_modules', $_REQUST, array('all_wid_id','id'),'id', '');
	}
	
	if($view_temp!=$view_temp_id && $view_temp){
		$values = array('module_id'=>$module_id,'title'=>$module_title.' - view item details page','action'=>'Insert');
		$save_ob = new saveform('cms_widgets', $values, array('module_id','title'),'id', '');
		$item_wid_id=$save_ob->getInsertId();
		
		$values = array('template_id'=>$view_temp,'widget_id'=>$item_wid_id,'action'=>'Insert');
		$save_ob = new saveform('cms_widget_settings', $values, array('template_id','widget_id'),'id', '');
		
		$_REQUST['item_wid_id']=$item_wid_id;
		$_REQUST['id']=$module_id;
		$_REQUST['action']='Edit';
		$save_ob = new saveform('cms_modules', $_REQUST, array('item_wid_id','id'),'id', '');
	}elseif($item_wid_id){
		
		$wid_settings_id = $utiles->lookupField('cms_widget_settings', 'id', 'id', ''," widget_id='$item_wid_id' ");
		$values = array('id'=>$wid_settings_id,'template_id'=>$view_temp,'widget_id'=>$item_wid_id,'action'=>'Edit');
		$save_ob = new saveform('cms_widget_settings', $values, array('template_id','widget_id'),'id', '');
	}elseif(!$view_temp){
		$_REQUST['item_wid_id']=0;
		$_REQUST['id']=$module_id;
		$_REQUST['action']='Edit';
		$save_ob = new saveform('cms_modules', $_REQUST, array('item_wid_id','id'),'id', '');
	}
	
	if(!$view_temp && !$viewAll_temp){
		$utils->redirect("listModules.php?&cmsMID=".$_REQUEST['cmsMID']);
	}else{
		$utils->redirect('select-temp-fields.php?module_id='.$module_id);
	}
	
}


$viewAll_temp_id = $utils->lookupField('cms_widget_settings', 'id', 'template_id', ''," widget_id='$all_wid_id' ");
$view_temp_id = $utils->lookupField('cms_widget_settings', 'id', 'template_id', ''," widget_id='$item_wid_id' ");


echo "<div class='col-sm-12 nopadding' align='center'>";
echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
echo "<div class='col-sm-5 wizerd-block' align='center'>
		<div class='col-sm-12 block-title'>Please select the template you want to connect with list all items page</div>";
		echo "<div class='col-sm-12 temp_item'>";
			echo "<div class='col-sm-4'><input type='radio' checked value='0' name='viewAll_temp'/>".$utiles->ViewPhotos($row['photo'], 1, 1, 75, 75).'</div>';
			echo "<div class='col-sm-8'>Not connected</div>";
		echo "</div>";
		$query = $fpdo->from('cms_templates')->fetchAll();
		foreach($query as $row){
			if($row['id']==$viewAll_temp_id){$checked='checked';}else{$checked='';}
			echo "<div class='col-sm-12 temp_item'>";
				echo "<div class='col-sm-4'><input type='radio' $checked value='".$row['id']."' name='viewAll_temp'/>".$utiles->ViewPhotos($row['photo'], 1, 1, 75, 75).'</div>';
				echo "<div class='col-sm-8'>".$row['title'].'</div>';
			echo "</div>";
		}

echo "</div>";
echo "<div class='col-sm-2'></div>";
echo "<div class='col-sm-5 wizerd-block' align='center'>
		<div class='col-sm-12 block-title'>Please select the template you want to connect with items details page</div>";
		
		echo "<div class='col-sm-12 temp_item'>";
			echo "<div class='col-sm-4'><input type='radio' checked value='0' name='view_temp'/>".$utiles->ViewPhotos(0, 1, 1, 75, 75).'</div>';
			echo "<div class='col-sm-8'>Not connected</div>";
		echo "</div>";
		
		$query = $fpdo->from('cms_templates')->fetchAll();
		foreach($query as $row){
			if($row['id']==$view_temp_id){$checked='checked';}else{$checked='';}
			echo "<div class='col-sm-12 temp_item'>";
				echo "<div class='col-sm-4'><input type='radio' $checked value='".$row['id']."' name='view_temp'/>".$utiles->ViewPhotos($row['photo'], 1, 1, 75, 75).'</div>';
				echo "<div class='col-sm-8'>".$row['title'].'</div>';
			echo "</div>";
		}
echo "</div>";
echo "</div>";
echo "<input type='hidden' value='$module_id' name='module_id'/>";
echo "<input type='submit' value='next' name='Next'/>";
echo"</form>";
include_once '../../common/footer.php';
?>