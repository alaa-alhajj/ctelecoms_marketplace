<?php
session_start();
//error_reporting(0);
include_once ("config.php");
$pLang = 'en';
$_SESSION['pLang'] = 'en';
$_SESSION['_PREF'] = _PREF;
$dots = $_SESSION['dots'] = '';
$HP = 1;

include_once 'view/controller/include.inc.php';

$voiControl = new VoilaController();
$utils = new utils();
$widgets = new widgets();
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
        <title>Ctelecom Marketplace</title> 
        <link href="<?php echo _Include ?>css/ctelecom-style.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo _Include ?>css/ctelecom-style_haider.css" rel="stylesheet" type="text/css"/>
        <script src="<?php echo _Include ?>js/jquery.min.1.11.0.js" type="text/javascript"></script>
        <script src="<?php echo _Include ?>js/jquery-ui.min.js" type="text/javascript"></script>
        <link href="<?php echo _Include ?>bxslider/jquery.bxslider.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>  

        <?php $widgets->printWidget(25); ?>
        <?php $widgets->printWidget(28); ?>
        <?php $widgets->printWidget(29); ?>
        <?php $widgets->printWidget(30); ?>
        <?php $widgets->printWidget(32); ?>
        <?php $widgets->printWidget(31); ?>
    </div>
    <?php $widgets->printWidget(33); ?>



<script>
    var _PREF = '<?php echo _PREF ?>';
    var _SITE = '<?php echo _SITE ?>';
    var _MODULES_FOLDER = '<?php echo MODULES_FOLDER ?>';

</script>		
<script src="<?php echo _Include ?>dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo _Include ?>libs/jquery.bxslider/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?php echo _Include ?>libs/jquery.bxslider/jquery.bxslider.js" type="text/javascript"></script> 
<script src="<?php echo _Include ?>libs/jquery-visible-master/jquery.visible.js" type="text/javascript"></script> 
<script src="<?php echo _Include ?>js/jquery.cslide.js" type="text/javascript"></script>
<script src="<?php echo _Include ?>js/owl.carousel.min.js" type="text/javascript"></script>  
<script src="<?php echo _Include ?>js/marketplace_script.min.js" type="text/javascript"></script>   
<script src="<?php echo _Include ?>libs/html5lightbox/html5lightbox.js" type="text/javascript"></script>
<script src="<?php echo _Include ?>js/easyResponsiveTabs.js" type="text/javascript"></script>
<script src="<?php echo _Include ?>js/star-rating.min.js" type="text/javascript"></script>
<script src="<?php echo _Include ?>js/ctelecoms.script_haider.js" type="text/javascript"></script>
<script src="<?php echo _Include ?>js/marketplace_script_home.min.js" type="text/javascript"></script>
<script src="<?php echo _Include ?>bxslider/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?php echo _Include ?>js/wow.min.js" type="text/javascript"></script>   
</body>
</html>