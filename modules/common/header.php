<?php
include "../../common/top.php";
include_once '../../common/constants.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>VOILA CMS | Dashboard</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="../../includes/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link href="../../includes/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

        <!-- Ionicons -->

        <!-- Theme style -->
        <link rel="stylesheet" href="../../includes/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="../../includes/dist/css/skins/_all-skins.min.css">
        <!-- iCheck -->
        <link href="../../includes/plugins/iCheck/all.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="../../includes/plugins/iCheck/flat/red.css">
        <!-- Morris chart -->
        <link rel="stylesheet" href="../../includes/plugins/morris/morris.css">
        <!-- jvectormap -->
        <link href="../../includes/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="../../includes/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="../../includes/plugins/datepicker/datepicker3.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="../../includes/plugins/daterangepicker/daterangepicker-bs3.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="../../includes/plugins/choosen_select/bootstrap-chosen.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="../../includes/css/style.css">
        <link href="../../includes/plugins/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        <script src="../../includes/plugins/jQuery/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<?php include '../../common/JsIncludes.php'; ?>
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script src="http://code.highcharts.com/modules/exporting.js"></script>
        <script type="text/javascript" src="../../includes/plugins/jQuery/config.js"></script>

        <script type="text/javascript" src="../../includes/plugins/jQuery/Ctelecoms_scripts.js"></script>
        <script type="text/javascript" src="../../includes/plugins/jQuery/configTinymce.js"></script>
        <link href="../../includes/plugins/jQueryUI/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <script src="../../includes/dist/js/app.min.js" type="text/javascript"></script>
        <link href="../../includes/plugins/tagit/css/jquery.tagit.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="../../includes/plugins/jQuery/seo_script.js"></script>
        <link href="../../includes/plugins/tagit/css/tagit.ui-zendesk.css" rel="s-tylesheet" type="text/css"/>
        <link href="../../includes/File_Manager/assets/fancybox/jquery.fancybox-1.3.4.min.css" rel="stylesheet" type="text/css"/>

    </head>
    <body class="hold-transition skin-red-light sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="../home/home.php" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>V</b>CMS</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>VOILA</b> CMS</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="label label-success">4</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 4 messages</li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            <li><!-- start message -->
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="../../includes/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Support Team
                                                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li><!-- end message -->
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="../../includes/dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        AdminLTE Design Team
                                                        <small><i class="fa fa-clock-o"></i> 2 hours</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="../../includes/dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Developers
                                                        <small><i class="fa fa-clock-o"></i> Today</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="../../includes/dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Sales Department
                                                        <small><i class="fa fa-clock-o"></i> Yesterday</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="../../includes/dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Reviewers
                                                        <small><i class="fa fa-clock-o"></i> 2 days</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="#">See All Messages</a></li>
                                </ul>
                            </li>
                            <!-- Notifications: style can be found in dropdown.less -->

                            <!-- Tasks: style can be found in dropdown.less -->

                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="fa fa-user" ></span>
                                    <span class="hidden-xs"><?= $_SESSION['cms-user-full-name'] ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->

                                    <!-- Menu Body -->

                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="../../views/logout/logout.php" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->

                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
<?php include('../../common/menu.php') ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                            <?php echo $utils->getModuleName($module_id); ?>
                        <small><?php
                            echo $utils->getModuleNameOperation();
                            echo $utils->getTableTitleField($table_id);
                            ?></small>
                    </h1>
                    <ol class="breadcrumb">
<?php echo $utils->getPath(); ?>

                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php
                    @session_start();

                    $utils->checkSave($module_id);
                    ?>