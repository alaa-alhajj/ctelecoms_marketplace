<?php include "../common/top.php";

$pLang='ar';
$_SESSION['pLang']='ar';
$_SESSION['_PREF']='/ezzati/';
$dots = $_SESSION['dots'] = '../../';
$HP=1;

include_once '../../view/controller/include.inc.php';
include_once '../../view/common/langsLabel.php';

$voiControl=new VoilaController();
$utils=new utils();
$widgets=new widgets();
$path=new path();

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
                <link href="<?php echo _Include?>css/ctelecom-style.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo _Include?>css/rtl-ahmad.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo _Include?>js/jquery.min.1.11.0.js" type="text/javascript"></script>
		<script src="<?php echo _Include?>js/jquery-ui.min.js" type="text/javascript"></script>
                <script src="<?php echo _Include?>js/easyResponsiveTabs.js" type="text/javascript"></script>
        

	   
	</head>
	<body>  
		
		<?php $widgets->printWidget(22);?>
		
		<?php $widgets->printWidget(36);?>
		<div class="path-sect container nopadding">
           <?php echo $path->getPath();?>
        </div>
		 <div class="page-body container nopadding">
			<div class="col-sm-12 nopadding">