<?  session_start();
// if($_SESSION["enterCMS"] == 'go'){
	include_once('../../../../config.php'); 
	include_once('../../../common/dbConnection.php'); 
	include_once('../../dbUtils.php');	
	$upfolder="../../../../uploads/attach/";
	$url=$_REQUEST['url'];
	if(!file_exists($upfolder)){
		mkdir($upfolder, 0777);
	}
	if (!empty($_FILES)) {
		$availableEx = array("pdf","doc","docx","xls","xlsx","ppt","pptx");
		$fileNameParts = explode(".",$_FILES['Filedata']['name']);
		$Name =$fileNameParts[0];
		$Ex =strtolower(end($fileNameParts));
		if(in_array($Ex,$availableEx)){
			$rund=randomStringUtil(5);
			$NewFileName=date('U').'_'.$rund.'.'.$Ex;
			$NewFileName_s=date('U').'_'.$rund.'_s.'.$Ex;
			$targetFile = $upfolder.$NewFileName;
			$tempFile = $_FILES['Filedata']['tmp_name'];
			if(move_uploaded_file($tempFile,$targetFile)){
				echo $NewFileName.'^'.$Name.'^'.$url;
			}
		}
	}
//}
?>