<?php
  session_start();
  include('../connection.php');
  if(!isset($_SESSION['authUser'])){
    header('Location:../logout.php');
  }

  //===================================================
  // === Page Identifier Session Setting 11/12/2018 ===
  //===================================================
  $_SESSION['page'] = "main_1";

  //************************** VALIDATE PERMISSION CLASS
  //************************** VALIDATE PERMISSION CLASS
  require 'validate_permission/ValidatePermission.php';
  $validate = new ValidatePermission();
  $permission_list = $validate->listPermission($_SESSION['auth_usercode']); // save list of permission to session

  // echo "<pre>";
  // print_r($permission_list);
  // print_r($_SESSION);
  // echo "</pre>";

  //************************** VALIDATE PERMISSION CLASS
  //************************** VALIDATE PERMISSION CLASS

?>
<!DOCTYPE html>
<html>
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?..."></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-125568234-1');
  </script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="../dist/img/BK LOGO.png">
  <title>Sales and MD Productivity | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../dependencies/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../dependencies/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../dependencies/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../dependencies/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../dependencies/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../dependencies/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo" style="background-color: #256188;">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="../dist/img/LOGO_MINI.png"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg" align="center">
        <img src="../dist/img/SMPP LOGO v2.png">
      </span>

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a class="pull-left" data-toggle="push-menu" role="button" style="margin-left: 10px;margin-top: 15px;color: #FFFFFF;cursor: pointer;"><i class="fa fa-bars"></i> </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="notifications-menu">
            <a href="https://sites.google.com/bellkenzpharma.com/smpp-user-manual/home" target="_blank" class="dropdown-toggle"><i class="fa fa-book"></i> User's Manual</a>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../dist/img/admin.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['authUser'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../dist/img/admin.png" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['authUser'];?>
                  <small><?php echo $_SESSION['authRole'];?></small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="../logout.php" class="btn btn-warning btn-flat"><i class="fa fa-sign-out-alt"></i> Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel (optional) -->


      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active"><a href="index.php" ><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a></li>

        <?Php

        $checkPermission = $validate->checkPermission("1", $_SESSION['auth_usercode']) ;


        ?>

       <?Php // if ($checkPermission) { ?>

            <li class="treeview">
              <a href="#"><i class="fa fa-folder"></i> <span>Doctor-Drugstore Directory</span><span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span></a>
              <ul class="treeview-menu" style="display:none;">
                <li><a href="dir_drugstore_tracking/per_kass.php"><i class="fa fa-users"></i> Per Representative</a></li>
                <li><a href="dir_drugstore_tracking/per_md.php"><i class="fa fa-user-md"></i> Per MD</a></li>
                <li><a href="dir_drugstore_tracking/per_area.php"><i class="fa fa-map-marker-alt"></i> Per Area</a></li>
                <li><a href="dir_drugstore_tracking/per_product.php"><i class="fa fa-shopping-cart"></i> Per Product</a></li>
                <li><a href="dir_drugstore_tracking/per_account.php"><i class="fa fa-list-alt"></i> Per Account</a></li>
                <?Php // if($_SESSION['authPosition'] == "admin" ) { ?>
                <li><a href="dir_drugstore_tracking/computation.php"><i class="fa fa-list-alt"></i> Sales Data Fetching</a></li>
                <?Php // } ?>

              </ul>
            </li>

      <?php // } ?>
      <li  >
        <a href="dir_productivity/productivity_report.php"><i class="far fa-chart-bar"></i> <span> Productivity</span><span class="pull-right-container"></span></a>
      </li>
      <li>
        <a href="dir_maintenance/prototype/"><i class="fas fa-robot"></i> <span> Productivity Maintenance <br>Prototype</span><span class="pull-right-container"></span></a>
      </li>

      <li class="treeview menu-open">
          <a href="#"><i class="fa fa-sliders-h"></i> <span>Maintenance</span><span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span></a>
          <ul class="treeview-menu" style="display:block;">
            <li class="treeview">
              <a href="#"><i class="fa fa-users-cog"></i> User Maintenance
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="dir_maintenance/users_maintenance/user_management.php"><i class="fa fa-user-cog"></i> User Management</a></li>
                <li><a href="dir_maintenance/users_maintenance/user_management.php"><i class="fa fa-user-tag"></i> Roles Management</a></li>
                <li><a href="dir_maintenance/users_maintenance/user_management.php"><i class="fa fa-user-check"></i> Permissions Management</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-map-marker-alt"></i> Area Maintenance
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="dir_maintenance/lba_maintenance_new/territory-management.php"><i class="fa fa-map-marked-alt"></i>Territory Management</a></li>
                <li><a href="dir_maintenance/lba_maintenance_new/lba-management.php"><i class="fa fa-map-marker-alt"></i> LBA Management</a></li>
                <li><a href="dir_maintenance/lba_maintenance/district-management.php"><i class="fa fa-map-marker-alt"></i> District Management</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-users"></i> Field Force
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="dir_maintenance/field_force_maintenance/territory-configuration.php"><i class="fa fa-map-marker-alt"></i>Territory Config</a></li>
                <li><a href="dir_maintenance/field_force_maintenance/med-rep-territory-config.php"><i class="fa fa-user-plus"></i> KASS/SAR Area</a></li>
                <li><a href="dir_maintenance/field_force_maintenance/manager-territory-config.php"><i class="fa fa-user-plus"></i> Manager Area</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-money-check"></i> Booked Sales Data
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="dir_maintenance/import-data-center/import-booked-sales-history.php"><i class="fa fa-history"></i> Import Data History</a></li>
                <li><a href="dir_maintenance/booked_sales_management/booked_accounts.php"><i class="fa fa-address-book"></i> Direct/Booked Accounts</a></li>
                <li><a href="dir_maintenance/productivity/accounts.php"><i class="fas fa-briefcase-medical"></i> Dispensing & Tagged Accts</a></li>
              </ul>
            </li>
            <li><a href="dir_maintenance/booked_sales_management/product_list.php"><i class="fa fa-th-list"></i> Product Maintenance</a></li>
            <li><a href="dir_maintenance/branches_management/index.php"><i class="fa fa-sitemap"></i> Branches Management</a></li>
            <li class="treeview">
              <a href="#"><i class="fa fa-money-check"></i> Off-take & Stock Transfer
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li ><a href="dir_maintenance/offtake_stock_transfer/index.php"><i class="fa fa-cloud-upload-alt"></i> Import Data</a></li>
              </ul>
            </li>
  		      <li class="treeview">
              <a href="#"><i class="fa fa-money-check"></i> Actual Data
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li><a href="dir_maintenance/actual_data/index.php"><i class="fa fa-cloud-upload-alt"></i> Import Data</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-money-check"></i> Actual Senior Citizen Data
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li><a href="dir_maintenance/actual_senior_data/index.php"><i class="fa fa-cloud-upload-alt"></i> Import Data</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-money-check"></i> EMR Data
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li><a href="dir_maintenance/emr_data/index.php"><i class="fa fa-cloud-upload-alt"></i> Import Data</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fas fa-file-import"></i> Productivity Computation
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu"  >
                <li><a href="dir_maintenance/productivity/dispensing_accounts.php"><i class="fas fa-briefcase-medical"></i> Dispensing</a></li>
                <li><a href="dir_maintenance/productivity/tagged_accounts_list.php"><i class="fas fa-briefcase-medical"></i> Tagged Accounts List</a></li>

                <li class="treeview">
                  <a href="#"><i class="fa fa-folder"></i> <span>MDC</span><span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span></a>
                  <ul class="treeview-menu" style="display:none;">
                    <li><a href="dir_maintenance/productivity/mdc.php"><i class="fas fa-hospital-alt"></i> MDC Share & <br>Other Area</a></li>
                    <li><a href="dir_maintenance/productivity/mdc_view_report.php"><i class="fas fa-hospital-alt"></i> MDC % Adjustment <br>Productivity Report</a></li>
                    <!-- <li><a href="dir_maintenance/productivity/other_area_sales.php"><i class="fas fa-hospital-alt"></i> Other Area Sales Report </a></li> -->
                    <li><a href="dir_maintenance/productivity/area_summary.php"><i class="fas fa-hospital-alt"></i> Area Sales <br>Summary Report </a></li>

                  </ul>
                </li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-cloud-upload-alt"></i> Import Data Center
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="dir_maintenance/import-data-center/import-booked-sales-raw-data.php"><i class=" fa fa-list-alt"></i> Booked Sales Raw Data</a></li>
              </ul>
            </li>
          </ul>
        </li>


      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer-alt text-green"></i> Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">


      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">

        <?Php

        $checkPermission = $validate->checkPermission("1", $_SESSION['auth_usercode']) ;
        // echo "<pre>";
        // echo $checkPermission;
        // print_r($_SESSION);
        // echo "</pre>";
        ?>


        <div class="col-lg-12 col-md-12 col-sm-12">
          <img src="../dist/img/SMPP_LOGO.png" class="img-fluid" style="max-width:100% !important;">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <h1 class="text-blue" style="font-size: 24px;"><b>WELCOME!</b></h1>
          <h2 style="padding-left:15px !important;font-size: 20px;"><i class="fa fa-caret-right text-blue" style="margin-right:10px !important;"></i>To view the user manual click <a href="https://sites.google.com/bellkenzpharma.com/smpp-user-manual/home" target="_blank">here</a></h2>
          <h2 style="padding-left:15px !important;font-size: 20px;"><i class="fa fa-caret-right text-blue" style="margin-right:10px !important;"></i>To reports issues with the system or to suggest improvements, click <a href="https://sites.google.com/bellkenzpharma.com/smpp-user-manual/bug-report" target="_blank">here</a></h2>
        </div>
      </div>
      <!-- /.row (main row) -->



    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.0
    </div>
    <strong>Copyright &copy; 2018 <a href="#">Sales & MD Productivity Portal</a>.</strong> All rights
    reserved.
  </footer>


</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../dependencies/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../dependencies/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="../dependencies/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="../dependencies/raphael/raphael.min.js"></script>
<script src="../dependencies/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="../dependencies/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="../dependencies/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../dependencies/moment/min/moment.min.js"></script>
<script src="../dependencies/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="../dependencies/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="../dependencies/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../dependencies/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
</body>
</html>
