<?php
  session_start();
  // error_reporting(0);
  // include('../../../connection.php');
  // if(!isset($_SESSION['authUser'])){
  //   header('Location:../../../logout.php');
  // }

  $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  if (strpos($url, "localhost") !== FALSE) {

    $server = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'dev_smpp';
  } else {
    $server = 'localhost';
    $username = 'epasadil_admin';
    $password = 'Pr0+0c01$';
    $dbname = 'epasadil_dev-smpp-db';

  }



  $charset = 'utf8';
  $options = array(
      PDO::ATTR_PERSISTENT  => true,
  );

  try {

    $conn_pdo = new PDO("mysql:host={$server};dbname={$dbname};charset={$charset}",
                           $username,
                           $password,
                           $options);
    $conn_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  } catch (PDOException $e) {

    throw new Exception("Connection failed: ". $e->getMessage());

  }

  //===================================================
  // === Page Identifier Session Setting 11/12/2018 ===
  //===================================================
  // $_SESSION['page'] = "pc_3.1";



  // include 'includes/model/class/ApplyRules.php';
  // include 'includes/model/class/ComputeSpecificCondition.php';
  require("includes/autoloader.php");

  $productivity_class = new Productivity();
  $stringCalc = new ChrisKonnertz\StringCalc\StringCalc();

  if (file_exists($_SESSION['auth_usercode']. ".txt") ) unlink($_SESSION['auth_usercode']. ".txt");

  // echo $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
  $f = "(2-1)*5/2";
  // $f = "((((((35426.25 - 2386764737.9) * 0.2) / 5) - ) + 100) / 0.3) + 35426.25";
  // echo $f . "<br>br>" ;
  $result = $stringCalc->calculate($f);
  // exit();

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
  <link rel="shortcut icon" href="../../../dist/img/BK LOGO.png">
  <title>Sales and MD Productivity | Prototype</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../../dependencies/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../../dependencies/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../../dist/css/skins/skin-blue.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../../../dependencies/morris.js/morris.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../../../dependencies/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Jquery Confirm picker -->
  <link rel="stylesheet" href="../../../dependencies/jquery-confirm/jquery-confirm.min.css">
  <!-- Jquery Confirm picker -->

  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../../dependencies/bootstrap-daterangepicker/daterangepicker.css">

  <!-- DATATABLES -->
  <link rel="stylesheet" href="../../../dependencies/datatables/media/css/dataTables.bootstrap.css">
  <link href="../../../dependencies/datatables/extensions/Select/css/select.bootstrap.css" rel="stylesheet" type="text/css"/>
  <link href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>



  <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" /> -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css" rel="stylesheet">

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- AUTOCOMPLETE -->
  <!-- AUTOCOMPLETE -->
  <!-- AUTOCOMPLETE -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.themes.css">
  <!-- AUTOCOMPLETE -->
  <!-- AUTOCOMPLETE -->
  <!-- AUTOCOMPLETE -->



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- jQuery 3 -->
  <script src="../../../dependencies/jquery/dist/jquery.min.js"></script>

  <!-- jQuery UI 1.11.4 -->
  <script src="../../../dependencies/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.7 -->
  <script src="../../../dependencies/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../../../commons/js/commons.js"></script>
</head>
<input type="hidden" name="userid" id="user_id" value="<?php echo $_SESSION['auth_usercode'];?>">

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?Php require("../../templates/menu.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-cog text-green"></i> Prototype
        <small></small>

      </h1>
      <ol class="breadcrumb">
        <li><a href="http://bellkenz.com/smpp_qa/auth_directory/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Prototype</li>
      </ol>
    </section>

    <!-- Main content -->
    <?php  ?>
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">

          <div class="box box-widget">

            <div class="box-body">

              <a href="index.php" class="btn btn-primary" style="margin-bottom:5px;"><i class="fas fa-arrow-left"></i> GO BACK</a>



              <table border='1' class='table table-striped table-hover display' id='dataTable' style='text-align:center; table-layout:auto;' width='100%' >
                <thead>
                  <tr>
                    <th>MD CODE</th>
                    <th>MD NAME</th>
                    <th>CLASS</th>
                    <th>LBA REBATE</th>
                    <th>BRANCH CODE</th>
                    <th>PRODUCT CODE</th>
                    <th>SEGMENT CODE</th>
                    <th>CREDITING DATE</th>
                    <th>TOTAL AMOUNT</th>
                  </tr>
                </thead>

                <tbody>
                <?Php

                $sql = "SELECT md_code, md_name, class, lba_rebate_code, branch_code, product_code, segment_code, crediting_date, total_amount
                FROM reference_sales_source
                WHERE 1=1
                      AND upload_status = 0
                ORDER BY md_name ASC
                 ";
                $stmt = $conn_pdo->prepare($sql);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($data) {
                  $total_sales = 0;
                  foreach ($data as $row) {
                    echo "<tr>";
                    foreach ($row as $key2 => $value2) {
                       if ($key2 == "total_amount") {
                              echo "<td>" . number_format($value2,2) . "</td>";
                              $total_sales += $value2;
                       } else {
                         if ($value2) {
                                echo "<td>$value2</td>";
                         } else echo "<td><small>--</small></td>";
                       }
                    }
                    echo "</tr>";
                  }
                } // if data
                ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan='7'>&nbsp;</td>
                    <td>TOTAL SALES</td>
                    <td><?php echo number_format($total_sales,2) ; ?></td>
                  </tr>
                </tfoot>

              </table>

            </div>

          </div>
        </div>
      </div>
    </section>

    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.0
    </div>
    <strong>Copyright &copy; <?Php echo date('Y'); ?> <a href="#">Sales & MD Productivity Portal</a>.</strong> All rights
    reserved.

  </footer>

</div>
<!-- ./wrapper -->

<?Php require("../../templates/footer.php"); ?>


<!-- AUTOCOMPLETE -->
<!-- AUTOCOMPLETE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js"></script>
<!-- AUTOCOMPLETE -->
<!-- AUTOCOMPLETE -->


<script>

$(document).ready(function(){

    $("#dataTable").dataTable({
      scrollX:        true,
      scrollY:        '55vh',
      scrollCollapse: true,
      'paging'      : true,
      "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100 ] ],
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true
    })

}); // document ready function


</script>


</body>
</html>

<style media="screen">
