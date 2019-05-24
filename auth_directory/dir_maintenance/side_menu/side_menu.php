<?php $page = basename($_SERVER['PHP_SELF']); ?>
<ul class="sidebar-menu" data-widget="tree">
  <li class="header">MAIN NAVIGATION</li>
  <li><a href="../../index.php" ><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
  <li class="treeview">
    <a href="#"><i class="fa fa-folder"></i> <span>Doctor-Drugstore Directory</span><span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span></a>
    <ul class="treeview-menu">
      <li><a href="../../dir_drugstore_tracking/per_kass.php"><i class="fa fa-users"></i> Per KASS</a></li>
      <!-- <li><a  href="per_class.php"><i class="fa fa-angle-double-right"></i> Per Class</a></li> -->
      <li><a href="../../dir_drugstore_tracking/per_md.php"><i class="fa fa-user-md"></i> Per MD</a></li>
      <li><a href="../../dir_drugstore_tracking/per_area.php"><i class="fa fa-map-marker-alt"></i> Per Area</a></li>
      <li><a href="../../dir_drugstore_tracking/per_product.php"><i class="fa fa-pills"></i> Per Product</a></li>
      <li><a href="../../dir_drugstore_tracking/per_account.php"><i class="fa fa-list-alt"></i> Per Account</a></li>
      <!-- <li class="active"><a href="per_class.php" class="bg-blue"><i class="fa fa-angle-double-right"></i> Summary</a></li> -->
      <!-- <li><a href="#"><i class="fa fa-chart-pie"></i> Area Analysis</a></li> -->
    </ul>
  </li>
  <li class="treeview menu-open">
    <a href="#"><i class="fa fa-sliders-h"></i> <span>Maintenance</span><span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span></a>
    <ul class="treeview-menu" <?php if($page == 'user_management.php' || $page == 'role_management.php' || $page == 'permission_management.php') {?> style="display: block;" <?php }else {
      echo 'style="display: none;"';
    }?>>
      <li class="active treeview menu-open">
        <a href="#"><i class="fa fa-users-cog"></i> User Maintenance
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li <?php if($page == 'user_management.php') {?> class="active" <?php } ?>><a href="../../dir_maintenance/users_maintenance/user_management.php"><i class="fa fa-user-cog"></i> User Management</a></li>
          <li <?php if($page == 'role_management.php') {?> class="active" <?php } ?>><a href="../../dir_maintenance/users_maintenance/role_management.php"><i class="fa fa-user-tag"></i> Roles Management</a></li>
          <li  <?php if($page == 'permission_management.php') {?> class="active" <?php } ?>><a href="../../dir_maintenance/users_maintenance/permission_management.php"><i class="fa fa-user-check"></i> Permissions Management</a></li>
        </ul>
      </li>
    </ul>
  </li>
</ul>
