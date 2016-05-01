<?  session_start();
// if($_SESSION["enterCMS"] == 'go'){
	include_once('../../../../config.php'); 
	include_once('../../../common/dbConnection.php'); 
	include_once('../../dbUtils.php');	
	$upfolder="../../../../uploads/temp/";
	if(!file_exists($upfolder)){
		mkdir($upfolder, 0777);
	}
	if (!empty($_FILES)) {
		$availableEx = array("jpg","gif","png","jpeg","pdf","doc","docx","xls","xlsx","flv","mp4","wmv","avi");
		//$videoEx = array("flv","mp4","wmv");	
		$fileNameParts = explode(".",$_FILES['Filedata']['name']);
		$Ex =strtolower(end($fileNameParts));
		if(in_array($Ex,$availableEx)){
			$rund=randomStringUtil(5);
			$NewFileName=date('U').'_'.$rund.'.'.$Ex;
			$NewFileName_s=date('U').'_'.$rund.'_s.'.$Ex;
			$targetFile = $upfolder.$NewFileName;
			$tempFile = $_FILES['Filedata']['tmp_name'];
			if(move_uploaded_file($tempFile,$targetFile)){
				echo $NewFileName;
				$wh=getimagesize($targetFile);
				if($wh[0]<80 && $wh[1]<80){
					copy($targetFile,$upfolder.$NewFileName_s);
				}else{
					resizeToFile ($targetFile, 80, 80,$upfolder.$NewFileName_s);
				}
			}
		}
	}
//}
?>