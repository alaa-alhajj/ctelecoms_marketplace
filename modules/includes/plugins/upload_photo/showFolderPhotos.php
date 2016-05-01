<?
session_start();
header("cache-control: no-cache"); 
require_once("../../../config.php");
require_once("../../common/dbConnection.php"); 
require_once("../dbUtils.php");
require_once("dbUtilsUpPhoto.php");
if(
chechUserPermissions('enterNewSubject.php') || 
chechUserPermissions('editSubject.php') ||
chechUserPermissions('categories.php') ||
chechUserPermissions('editProducts.php') ||
chechUserPermissions('enterProducts.php') ||
chechUserPermissions('copyProducts.php')

){}else{
	session_destroy();
	@header("location:/");
	exit;
}
if(checkGallogin()){  
	$img_arr = array("jpg","jpeg","gif","png");
	if(isset($_POST['dir'])){
		$ret='';
		$dir=$_POST['dir'];
		$root='../../../uploads/'.$dir.'/';
		$ph=$_POST['ph'];
		$photos=explode(',',$ph);
		if($dir!='all'){
		if ($Folder = opendir($root)){
			?><div class="monthTitle cb"><?=checkIsSubjectForder($dir)?></div><?
			$i=0;
			while (false !== ($file = readdir($Folder))){
				if ($file != "." && $file != "..") {
					$fileNameParts = explode(".",$file);
					$Ex =strtolower(end($fileNameParts));
					if(in_array($Ex,$img_arr)){
						if(!is_dir($root.$file)){
						$bgcol='fff';
						$inList=0;
						if(in_array($file,$photos)){
							$bgcol='abe799';
							$inList=1;
						}
						$img=crop($root.$file, 90, 90,'../../../uploads/cash/up_'.$file); 						
						if($img)$img='../../uploads/cash/up_'.$file;?>										
                            <div class="l_img" align="center" 
                            id="<?=$dir.'_'.$i?>" 
                            act="<?=$inList?>" 
                            
                            onclick="addTolist('<?=$dir.'_'.$i?>','<?=$file?>')" 
                            style=" background-color:#<?=$bgcol?>;background-image:url(<?=$img?>)">
                            </div><? 
                            $i++;																	
						}
					}
				}								
			}					
			closedir($Folder);
		}				
		}else{
			$folders=array();
			$folders=getAllfolder();
			$i=0;
			for($f=0;$f<count($folders);$f++){				
				if($folders[$f]==''){
					$root='../../../uploads/';
					?><div class="monthTitle cb">Root</div><?
				}else{
					$root='../../../uploads/'.$folders[$f].'/';
					?><div class="monthTitle cb"><?=checkIsSubjectForder($folders[$f])?></div><?
				}
				if ($Folder = opendir($root)){
				
				while (false !== ($file = readdir($Folder))){
					if ($file != "." && $file != "..") {
						$fileNameParts = explode(".",$file);
						$Ex =strtolower(end($fileNameParts));
						if(in_array($Ex,$img_arr)){
							if(!is_dir($root.$file)){
							$bgcol='fff';
							$inList=0;
							if(in_array($file,$photos)){
								$bgcol='abe799';
								$inList=1;
							}
							$img=resizeToFile($root.$file, 90, 90,'../../../uploads/cash/up_'.$file); 						
							if($img)$img='../../uploads/cash/up_'.$file;?>										
								<div class="l_img" align="center" 
								id="<?=$dir.'_'.$i?>" 
								act="<?=$inList?>" 
								
								onclick="addTolist('<?=$dir.'_'.$i?>','<?=$file?>')" 
								style=" background-color:#<?=$bgcol?>;background-image:url(<?=$img?>)">
								</div><? 
								$i++;																	
							}
						}
					}								
				}					
				closedir($Folder);
			}
		}	
		}
	}
}
?>