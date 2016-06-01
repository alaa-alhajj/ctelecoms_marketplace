<?php
date_default_timezone_set("Asia/Damascus");
if( $_SERVER['HTTP_HOST']=="localhost" || $_SERVER['HTTP_HOST']=="voila5"){
	define("_PREF","/ctelecoms_marketplace/");	
	define("_SITE","localhost");	
	define("DATABASE_URL","localhost");
	define("DATABASE_USER","root");
	define("DATABASE_PASS","");
	define("DATABASE_NAME","ctelecoms_marketplace");
}else{
	define("_PREF","/cms6/");
	define("_SITE","voitest.com");//Project Domain
	define("DATABASE_URL","localhost");
	define("DATABASE_USER","voitest_cms6");
	define("DATABASE_PASS","voitest_cms6123");
	define("DATABASE_NAME","voitest_cms6");
}
$PREF=_PREF;
define("_INFO","info@"._SITE);
define("_PREFIMG",_PREF."ui/");
define("_PREFICO",_PREF."modules/includes/css/images/icons/");
define("_ViewIMG",_PREF."vi/");
define("_Include",_PREF."view/includes/");
define("MODULES_FOLDER","modules");
define("site_id","5763283");
define("_LPP",10);
define("ClassMain","col-sm-6"); 
define("LabelSubCss",'col-sm-2');
define("ReqSubCss",'');
define("FIELDSubCss",'col-sm-10');
define("mail_host","mail.voilahost.com");
define("mail_email","info@voitest.com");
define("mail_password","xB00GJey3o");
define("mail_port",2525);
define("mail_auth",'SSL');

define("_V",'VOILA CMS V6.0.1');?>