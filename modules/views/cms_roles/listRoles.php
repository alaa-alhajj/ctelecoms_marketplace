<?php
include '../../common/header.php';

$action = $_REQUEST['action'];
$user_grp_id = $_REQUEST['grp_id'];
$cols = array('role_id','grp_id');

if($action=='save'){
	$role_ids = $_REQUEST['role_id'];
	foreach($role_ids as $role_id){
		$cols_vals  = array ('action'=>'Insert','role_id'=>$role_id,'grp_id'=>$user_grp_id);
		$save_ob = new saveform('cms_module_permissions', $cols_vals, $cols,'id', '');
	}
}

echo $ob_roles->listRoles($user_grp_id);


include_once '../../common/footer.php';
?>