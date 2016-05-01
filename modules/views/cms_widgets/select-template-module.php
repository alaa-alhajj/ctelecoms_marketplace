<?php
include '../../common/header.php';
include 'config.php';
include '../../common/pn.php';
$listTable = $voiControl->ObListTable();
$utiles = $voiControl->ObUtils();

$widget_id = $_REQUEST['widget_id'];

$query = $fpdo->from('cms_widget_settings')->where(" widget_id='$widget_id' ")->fetchAll();
foreach($query as $row){
	$settings_id = $row['id'];
	$template_id = $row['template_id'];
}
$module_id = $utils->lookupField('cms_widgets','id','module_id',$widget_id);


if($_REQUEST['Next']){
	$template_id = $_REQUEST['template_id'];
	$module_id = $_REQUEST['module_id'];
	
	if($settings_id){
		$action = 'Edit';
	}else{
		$action = 'Insert';	
	}
	
	
	$_REQUEST['id']=$settings_id;
	$values = array(
				'id'=>$settings_id,
				'widget_id'=>$widget_id,
				'template_id'=>$template_id,
				'action'=>$action);
	$save_ob = new saveform('cms_widget_settings', $values, array('widget_id','template_id'),'id', '');
	
	
	$_REQUEST['id']=$widget_id;
	$values = array(
				'id'=>$widget_id,
				'module_id'=>$module_id,
				'action'=>'Edit');
	$save_ob = new saveform('cms_widgets', $values, array('module_id'),'id', '');
	
	
	$utils->redirect('select-widget-fields.php'."?widget_id=".$_REQUEST['widget_id']);
}


echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
echo "<div class='col-sm-12 nopadding' align='center'>";
echo "<div class='col-sm-5 wizerd-block' align='center'>
		<div class='col-sm-12 block-title'>Please select the template you want to connect widget with</div>";
		echo "<div class='col-sm-12 temp_item'>";
			echo "<div class='col-sm-4'><input type='radio' checked value='0' name='template_id'/>".$utiles->ViewPhotos(0, 1, 1, 75, 75).'</div>';
			echo "<div class='col-sm-8'>".$row['title'].'</div>';
		echo "</div>";
		$query = $fpdo->from('cms_templates')->fetchAll();
		foreach($query as $row){
			if($row['id']==$template_id){$checked='checked';}else{$checked='';}
			echo "<div class='col-sm-12 temp_item'>";
				echo "<div class='col-sm-4'><input type='radio' $checked value='".$row['id']."' name='template_id'/>".$utiles->ViewPhotos($row['photo'], 1, 1, 75, 75).'</div>';
				echo "<div class='col-sm-8'>".$row['title'].'</div>';
			echo "</div>";
		}
echo "</div>";
echo "<div class='col-sm-2'></div>";
echo "<div class='col-sm-5 wizerd-block' align='center'>
		<div class='col-sm-12 block-title'>Please select the module you want to connect widget with</div>";
echo $utils->createComboBoxFiltered('cms_modules','id','title',$module_id,'module_id');
echo "</div>";
echo "</div>";
echo "<input type='hidden' value='$widget_id' name='widget_id'/>";
echo "<input type='submit' value='next' name='Next'/>";
echo"</form>";


include_once '../../common/footer.php';
?>