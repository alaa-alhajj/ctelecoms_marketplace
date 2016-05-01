<?
session_start();
header("cache-control: no-cache"); 
require_once("../../../config.php");
require_once("../../common/dbConnection.php"); 
require_once("../dbUtils.php");
require_once("dbUtilsUpPhoto.php");
$table=$_REQUEST['table'];
if($table=='subjects_subjects'){
	if(!chechUserPermissions('enterNewSubject.php') || !chechUserPermissions('editSubject.php')){
		session_destroy();
		@header("location:/");
		exit;
	}
}
if(checkGallogin()){  
	$img_arr = array("flv","mp4","wmv","avi");	
	$ret='';
	$ph=$_POST['attachs'];
	$photos=explode(',',$ph);
	$root='../../../uploads/videos/';
	
	$photos=explode(',',$ph);
	if ($Folder = opendir($root)){
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
						//$img_name=lookupField($table,'attach','name',$file,"name!=''");
						//if($img_name=='0')
						$img_name=$file;
						$icon='../css/images/filesTypes/'.$Ex.'.png';
						if(!file_exists($icon)){$icon='../css/images/filesTypes/x.png';}
						
						$img=resizeToFile($icon, 90, 90,'../../../uploads/cash/I90_'.$Ex.'.png'); 						
						if($img)$img='../../uploads/cash/I90_'.$Ex.'.png';
						?>
                        <div class="fl"">										
                            <div class="l_attach" align="center" 
                            id="att<?=$i?>" 
                            act="<?=$inList?>" 
                            
                            onclick="addTolist2('att<?=$i?>','<?=$file?>')" 
                            style=" background-color:#<?=$bgcol?>;background-image:url(<?=$img?>)">
                            </div>
                            <div class="l_attach_title"><a href="../../uploads/videos/<?=$file?>" target="_blank">
							<?=$img_name?></a></div>
						</div><? 
					}
					$i++;			
				}
			}								
		}
				
		closedir($Folder);
	}				
	
}
?>