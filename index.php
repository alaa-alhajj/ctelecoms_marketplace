<?php
session_start();
error_reporting(0);
include_once ("config.php");
$pLang='en';
$_SESSION['pLang']='en';
$_SESSION['_PREF']=_PREF;
$dots = $_SESSION['dots'] = '';
$HP=1;

include_once 'view/controller/include.inc.php';

$voiControl=new VoilaController();
$utils=new utils();
$widgets=new widgets();
include_once 'view/common/langsLabel.php';
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="favicon.ico" type="image/x-icon"/>
		<link rel="icon" type="image/png" href="icon.png">
		<title>Ezzati</title> 
			<link rel="stylesheet" href="<?php echo _Include?>css/img-effects/demo/demo.css">
		<link rel="stylesheet" href="<?php echo _Include?>css/img-effects/effeckt.css"> 
		<link href="<?php echo _Include?>css/rtl.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo _Include?>css/rtl-ahmad.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo _Include?>js/jquery.min.1.11.0.js" type="text/javascript"></script>
		<script src="<?php echo _Include?>js/jquery-ui.min.js" type="text/javascript"></script>
        

	   
	</head>
	<body>  

		<?php $widgets->printWidget(22);?>
		<?php $widgets->printWidget(23);?>
		<?php $widgets->printWidget(24);?>
		<?php $widgets->printWidget(25);?>
		<?php $widgets->printWidget(26);?>
		<?php $widgets->printWidget(27);?>
		<?php $widgets->printWidget(30);?>
		<?php $widgets->printWidget(34);?>

			

		<script src="<?php echo _Include?>dist/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="<?php echo _Include?>libs/jquery.bxslider/jquery.bxslider.min.js" type="text/javascript"></script>
		<script src="<?php echo _Include?>libs/jquery.bxslider/jquery.bxslider.js" type="text/javascript"></script> 
		<script src="<?php echo _Include?>libs/jquery-visible-master/jquery.visible.js" type="text/javascript"></script> 
		<script src="<?php echo _Include?>js/jquery.cslide.js" type="text/javascript"></script>
		<script src="<?php echo _Include?>js/proj-script.js" type="text/javascript"></script>   
			
		
			  
	</body>
</html>