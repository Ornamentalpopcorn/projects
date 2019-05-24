<?php

  // ============================================
  // === Manage session conditions 11/12/2018 ===
  // ============================================
  if(isset($_SESSION['page']) && $_SESSION['page'] == "main_1"){
    $dir_locator_1 = "";
    $dir_locator_2 = "";
  }else{
    $dir_locator_1 = "../../";
    $dir_locator_2 = "../../../";
  }


// $dir_list = array( "../../" ) ;

$txt = "";
for ($i=1; $i <= 6 ; $i++) {

   $dir_list[] = $txt . "validate_permission/";
   $txt .= "../";
}

// $dir_list = array("validate_permission/", "../validate_permission/", "../../validate_permission/", "../../../validate_permission/") ;

$count = 0;
foreach ($dir_list as $dir ) {

  $file = $dir . 'ValidatePermission.php';
  // $file_headers = @get_headers($file);
  if (file_exists($file) !== false) {
      require $file;
      $count++;
  }

  if ($count) break;

}



  // function url(){
  //   return sprintf(
  //     "%s://%s%s",
  //     isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
  //     $_SERVER['SERVER_NAME'],
  //     $_SERVER['REQUEST_URI']
  //   );
  // }
  //
  // $currentDirectory = explode('/',getcwd());
  // $currentUrl = url();

?>

<header class="main-header">
  <!-- Logo -->
  <a href="http://bellkenz.com/dev-smpp/auth_directory/index.php" class="logo" style="background-color: #256188;">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><img src="https://www.bellkenz.com/dev-smpp/dist/img/LOGO_MINI.png"></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg" align="center">
      <img src="https://www.bellkenz.com/dev-smpp/dist/img/SMPP%20LOGO%20v2.png">
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
            <img src="https://www.bellkenz.com/dev-smpp/dist/img/admin.png" class="user-image" alt="User Image">
            <span class="hidden-xs"><?php echo $_SESSION['authUser'];?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <img src="https://www.bellkenz.com/dev-smpp/dist/img/admin.png" class="img-circle" alt="User Image">

              <p>
                <?php echo $_SESSION['authUser'];?>
                <small><?php echo $_SESSION['authRole'];?></small>
              </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-right">
                <a href="../../../logout.php" class="btn btn-warning btn-flat"><i class="fa fa-sign-out-alt"></i> Sign out</a>
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
    <?php $page = basename($_SERVER['PHP_SELF']); ?>
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li <?php if(isset($_SESSION['page']) && strpos($_SESSION['page'],"main_") !== false) {?> class="active" <?php } ?>><a href="http://bellkenz.com/dev-smpp/auth_directory/index.php" ><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
      <li class="treeview menu-open">
        <a href="#"><i class="fa fa-folder"></i> <span>Doctor-Drugstore Directory</span><span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span></a>
        <ul class="treeview-menu" >
          <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_drugstore_tracking/per_kass.php"><i class="fa fa-users"></i> Per Representative</a></li>
          <!-- <li><a  href="per_class.php"><i class="fa fa-angle-double-right"></i> Per Class</a></li> -->
          <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_drugstore_tracking/per_md.php"><i class="fa fa-user-md"></i> Per MD</a></li>
          <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_drugstore_tracking/per_area.php"><i class="fa fa-map-marker-alt"></i> Per Area</a></li>
          <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_drugstore_tracking/per_product.php"><i class="fa fa-pills"></i> Per Product</a></li>
          <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_drugstore_tracking/per_account.php"><i class="fa fa-list-alt"></i> Per Account</a></li>

          <?Php if ($position == "ADMIN") {  ?>
          <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_drugstore_tracking/computation.php"><i class="fa fa-list-alt"></i> Sales Data Fetching</a></li>
          <?Php }  ?>
          <!-- <li class="active"><a href="per_class.php" class="bg-blue"><i class="fa fa-angle-double-right"></i> Summary</a></li> -->
          <!-- <li><a href="#"><i class="fa fa-chart-pie"></i> Area Analysis</a></li> -->
        </ul>
      </li>
      <li>
        <a href="https://www.bellkenz.com/dev-smpp/auth_directory/dir_productivity/productivity_report.php"><i class="far fa-chart-bar"></i> <span> Productivity</span><span class="pull-right-container"></span></a>
      </li>
      <li>
        <a href="https://www.bellkenz.com/dev-smpp/auth_directory/dir_maintenance/prototype/"><i class="fas fa-robot"></i> <span> Productivity Maintenance <br>Prototype</span><span class="pull-right-container"></span></a>
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
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/users_maintenance/user_management.php"><i class="fa fa-user-cog"></i> User Management</a></li>
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/users_maintenance/role_management.php"><i class="fa fa-user-tag"></i> Roles Management</a></li>
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/users_maintenance/permission_management.php"><i class="fa fa-user-check"></i> Permissions Management</a></li>
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
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/lba_maintenance_new/territory-management.php"><i class="fa fa-map-marked-alt"></i>Territory Management</a></li>
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/lba_maintenance_new/lba-management.php"><i class="fa fa-map-marker-alt"></i> LBA Management</a></li>
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/lba_maintenance/district-management.php"><i class="fa fa-map-marker-alt"></i> District Management</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-users"></i> Field Force
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/field_force_maintenance/territory-configuration.php"><i class="fa fa-map-marker-alt"></i>Territory Config</a></li>
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/field_force_maintenance/med-rep-territory-config.php"><i class="fa fa-user-plus"></i> KASS/SAR Area</a></li>
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/field_force_maintenance/manager-territory-config.php"><i class="fa fa-user-plus"></i> Manager Area</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-money-check"></i> Booked Sales Data
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <!-- <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/booked_sales_management/index.php"><i class="fa fa-cloud-upload-alt"></i> Import Data</a></li> -->
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/import-data-center/import-booked-sales-history.php"><i class="fa fa-history"></i> Import Data History</a></li>
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/booked_sales_management/booked_accounts.php"><i class="fa fa-address-book"></i> Direct/Booked Accounts</a></li>
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/productivity/accounts.php"><i class="fas fa-briefcase-medical"></i> Dispensing & Tagged Accts</a></li>
              </ul>
            </li>
            <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/booked_sales_management/product_list.php"><i class="fa fa-th-list"></i> Product Maintenance</a></li>
            <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/branches_management/index.php"><i class="fa fa-sitemap"></i> Branches Management</a></li>
            <li class="treeview">
              <a href="#"><i class="fa fa-money-check"></i> Off-take & Stock Transfer
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li ><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/offtake_stock_transfer/index.php"><i class="fa fa-cloud-upload-alt"></i> Import Data</a></li>
              </ul>
            </li>
  		      <li class="treeview">
              <a href="#"><i class="fa fa-money-check"></i> Actual Data
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/actual_data/index.php"><i class="fa fa-cloud-upload-alt"></i> Import Data</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-money-check"></i> Actual Senior Citizen Data
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/actual_senior_data/index.php"><i class="fa fa-cloud-upload-alt"></i> Import Data</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-money-check"></i> EMR Data
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/emr_data/index.php"><i class="fa fa-cloud-upload-alt"></i> Import Data</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fas fa-file-import"></i> Productivity Computation
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu"  >
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/productivity/dispensing_accounts.php"><i class="fas fa-briefcase-medical"></i> Dispensing</a></li>
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/productivity/tagged_accounts_list.php"><i class="fas fa-briefcase-medical"></i> Tagged Accounts List</a></li>

                <li class="treeview">
                  <a href="#"><i class="fa fa-folder"></i> <span>MDC</span><span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span></a>
                  <ul class="treeview-menu" style="display:none;">
                    <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/productivity/mdc.php"><i class="fas fa-hospital-alt"></i> MDC Share & <br>Other Area</a></li>
                    <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/productivity/mdc_view_report.php"><i class="fas fa-hospital-alt"></i> MDC % Adjustment <br>Productivity Report</a></li>
                    <!-- <li><a href="dir_maintenance/productivity/other_area_sales.php"><i class="fas fa-hospital-alt"></i> Other Area Sales Report </a></li> -->
                    <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/productivity/area_summary.php"><i class="fas fa-hospital-alt"></i> Area Sales <br>Summary Report </a></li>

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
                <li><a href="http://bellkenz.com/dev-smpp/auth_directory/dir_maintenance/import-data-center/import-booked-sales-raw-data.php"><i class=" fa fa-list-alt"></i> Booked Sales Raw Data</a></li>
              </ul>
            </li>
          </ul>
        </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
