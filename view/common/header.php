<?php
include "../common/top.php";

$pLang = 'en';
$_SESSION['pLang'] = 'en';
$dots = $_SESSION['dots'] = '../../';
$HP = 1;

include_once '../../view/controller/include.inc.php';

$voiControl = new VoilaController();
$utils = new utils();
$widgets = new widgets();
$path = new path();
include_once 'view/common/langsLabel.php';
@session_start();

?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml"  charset="utf-8" lang='ar'>
    <head>
        <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                    <link rel="icon" href="favicon.ico" type="image/x-icon"/>
                    <link rel="icon" type="image/png" href="icon.png">
                        <?php echo $widgets->getPageSeo($_REQUEST['id']) ?>

                       
                        <link href="<?php echo _Include ?>css/ctelecom-style.css" rel="stylesheet" type="text/css"/>
                        <link href="<?php echo _Include ?>css/ctelecom-style_haider.css" rel="stylesheet" type="text/css"/>
                        <script src="<?php echo _Include ?>js/jquery.min.1.11.0.js" type="text/javascript"></script>
                        <script src="<?php echo _Include ?>js/jquery-ui.min.js" type="text/javascript"></script>
						<link rel="stylesheet" href="https://www.paytabs.com/express/express.css">
						<script src="https://www.paytabs.com/express/express_checkout_v3.js"></script>


                        </head>
                        <body>  
                            <!------------------------ Header-------------------------->
						<?php $widgets->printWidget(25);?>
						<div class="path-sect container nopadding">
           <?php echo $path->getPath();?>
        </div>