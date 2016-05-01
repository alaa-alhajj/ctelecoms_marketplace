<?php
session_start();
error_reporting(0);
include_once ("../config.php");
include_once 'controller/include.inc.php';
$voiControl=new VoilaController();
$utils=new utils();
$ob_roles = $voiControl->obRoles();


if($_SESSION['go-cms']=='go'){$utils->redirect("views/home/home.php");}

$error_msg = '';

if($_REQUEST['action']=='login'){
	$username_req = $_REQUEST['username'];
	$password_req = md5($_REQUEST['password']);
	
	$username =  $utils->lookupField('cms_users','username' , 'username',$username_req );
	$password =  $utils->lookupField('cms_users','password' , 'password', $password_req);
	
	$login_result = $ob_roles->login($username_req,$password_req);
	
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VOILA CMS | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="includes/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="includes/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="<?php echo $_SERVER['PHP_SELF']?>"><b>VOILA </b>CMS</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
			<input type='hidden' value='login' name='action'/>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Username" name='username'>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password"  name='password'>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                    <a href="#" class=" text-danger">I forgot my password</a>
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-danger btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

        
        
		<div class='error-message'><?php echo $login_result;?></div>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="includes/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="includes/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="includes/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
