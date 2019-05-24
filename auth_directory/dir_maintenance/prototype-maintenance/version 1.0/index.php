<?php
  session_start();
  // include('../../../connection.php');
  if(!isset($_SESSION['authUser'])){
    header('Location:../../../logout.php');
  }

  $server = 'localhost';
  $username = 'epasadil_admin';
  $password = 'Pr0+0c01$';
  $dbname = 'epasadil_dev-smpp-db';

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
  $_SESSION['page'] = "pc_3.1";



  // include 'includes/model/class/ApplyRules.php';
  // include 'includes/model/class/ComputeSpecificCondition.php';
  require("includes/autoloader.php");

  $productivity_class = new Productivity();
  $stringCalc = new ChrisKonnertz\StringCalc\StringCalc();


  // echo $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

  $result = $stringCalc->calculate("(2+1) - (5-1.3)");

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
    <?php include("includes/view/main_content.php"); ?>
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

<!-- MODAL -->
<!-- MODAL -->
<!-- MODAL -->

<div id="viewDataSource" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
    data-backdrop="static"   > <div class="modal-dialog"  style="width:90%; overflow-y: auto;">
      <div class="modal-content"   >

        <div class="modal-header" style="background-color:#3c8dbc  ;">


          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style='color:white;'>×</button>
          <h4 class="modal-title" id="myModalLabel" style='color: white !important'><i class="fas fa-info"></i> Add Data Sources</h4>

        </div>

        <div class="modal-body"  style="max-height:80vh;overflow-y: auto;overflow-x:hidden;">

            <div class="col-md-3" id="sourceList">

                <?Php echo $productivity_class->displaySourceList(); ?>

            </div>

            <div class="col-md-9" id="editList">

                <input type="text" id="sourceTitle" class="form-control" name="source-name" value="" required="required" placeholder="Enter Source Title...">

                <textarea class="form-control input-disabled" name="name" rows="6" cols="120" readonly style="margin-bottom:10px" id="sqlText"></textarea>

                <div class="row">

                    <div class="form-group col-md-3">
                      <label for="dataSource">Data Source</label>
                      <select class="form-control" id="dataSourceList">
                        <option selected disabled value="">Select Data Source..</option>
                        <?Php echo $productivity_class->selectDataSourcesList(); ?>
                      </select>

                    </div>


                    <div class="form-group col-md-9" id="addParameterDiv">
                      <label for="">Selection</label>

                      <div class="clearfix"></div>

                      <div class="col-md-3">

                      <!-- <select class="form-control" id="sourceType">
                        <option selected disabled value="">Source..</option>
                        <option value="input">Input</option>
                        <option value="source" selected>Source</option>
                      </select> -->


                      <select class="form-control" id="dataAction">
                        <option selected disabled value="">Source..</option>
                        <option value="select">select</option>
                        <option value="where">Where</option>
                        <option value="in">In</option>
                        <option value="not in">Not In</option>
                        <option value="=">Equal</option>
                        <option value="!=">Not Equal</option>
                        <option value=">=">Greater Than Or Equal To</option>
                        <option value="<=">Less Than Or Equal To</option>
                        <option value="group by">Group By</option>
                        <option value="and">And</option>
                        <option value="or">Or</option>
                      </select>
                       <!-- <input class="form-control" placeholder="Selection Source" id="selectionDataList"> -->

                      </div>
                      <div class="col-md-9">


                          <div id="defaultSelect">
                              <!-- <select style="width: 50%" id="selectionSources" class="js-example-responsive form-control col-md-10" name="selection[]" multiple="multiple">
                                <option value="AL">SUM</option>
                                <option value="WY">COUNT</option>
                                <option value="WY">TOTAL_AMOUNT</option>
                              </select> -->

                              <input class="form-control" style="width: 400px;" id="selectionSources">

                          </div>

                          <div id="defaultSelect2" style="display:none">
                              <input type="text" id="selectionInput" class="form-control" name="" value="" placeholder="Input Source...">
                          </div>
                          <a href="#"  class="btn btn-primary btn-xs" id="addParameter"><i class="fas fa-plus"></i> Add Parameter</a>
                          <a href="#"  class="btn btn-danger btn-xs" id="deleteParameter"><i class="fas fa-minus"></i> Delete Parameter</a>

                      </div>


                    </div>


                </div>

                <?Php

                $sql = "TRUNCATE creation_source_list_sales;";
                $stmt = $conn_pdo->prepare($sql);
                $stmt->execute();
                $sql = "DELETE FROM creation_source_breakdown WHERE upload_status = 0";
                $stmt = $conn_pdo->prepare($sql);
                $stmt->execute();

                // $productivity_class->computeResult(); ?>


            </div>


        </div>

        <div class="modal-footer">
          <div class="col-md-9">

              <!-- <select style="width:100%" class="form-control js-states" id="referenceSources" > -->
              <input  style="width: 500px !important" class="form-control js-states" id="referenceSources" >
                <!-- <option selected disabled value="">Check Reference..</option> -->
                <?Php // echo $productivity_class->selectReferenceList();
                ?>
              <!-- </select> -->
          </div>

          <a href="#" id="createSource" class="btn btn-success pull-right" name="button"><i class="fas fa-plus"></i> Create Source</a>

        </div>
    </div>
  </div>
</div>

<!-- MODAL -->
<!-- MODAL -->

<!-- AUTOCOMPLETE -->
<!-- AUTOCOMPLETE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js"></script>
<!-- AUTOCOMPLETE -->
<!-- AUTOCOMPLETE -->


<script>

$(document).ready(function(){

  var key = 'productivity'

    var options  = {
          data : [
            "SUM(enter value...)",
            "MD CODE",
            "MD NAME",
            "ACCOUNT CODE",
            "STATUS CODE",
            "CLASS",
            "BRANCH CODE",
            "BRANCH NAME",
            "PRODUCT CODE",
            "PRODUCT NAME",
            "LBA REBATE CODE"
          ]
    } ;

    $("#selectionSources").easyAutocomplete(options);



  var options = {
  url: "referenceSources.php",
  getValue: "name",
  placeholder: "Check Reference..",
  requestDelay: 500,
  list: {
    match: {
      enabled: true
    },
    showAnimation: {
      type: "fade", //normal|slide|fade
      time: 250,
      callback: function() {}
    },
  }
  };

  $("#referenceSources").easyAutocomplete(options);



  $("#sourceType").change(function(){

      if ($(this).val() == "input") {
            $("#defaultSelect").css("display","none")
            $("#defaultSelect2").css("display","block")
            $("#defaultSelect2").val('')
      } else if ($(this).val() == "source") {
            $("#defaultSelect").css("display","block")
            $("#defaultSelect2").css("display","none")
      }

  })

  $("#dataType").change(function() {

      var dataType = $(this).val()
      var action = 'select data'

      $.ajax({
        type: "POST",
        url: "includes/controller/productivityController.php",
        data: {
          action: action,
          key: key,
          datasource: dataType
        },
        cache: false,
        beforeSend: function() {
          $("#dataType").prop("disabled", true)

          $("#dataList").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');

        },
        success: function (data) {

          $("#dataList").html(data)
          $("#dataType").prop("disabled", false)
        },
        error: function(err) {
        }
      });


  }) // #dataType end clause

  $(".sourceList").click(function(){

        var source_id = $(this).data('id')
        alert(source_id)

  }) // sourceList

  $("#deleteParameter").click(function(){

      var action = "delete parameter"

      $.ajax({
        type: "POST",
        url: "includes/controller/productivityController.php",
        data: {
          action: action,
          key: key
        },
        cache: false,
        beforeSend: function() {

          // $("#addParameterDiv").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');

        },
        success: function (data) {

          $("#sqlText").val(data)
        },
        error: function(err) {
        }
      });


  })

  $("#addParameter").click(function(){

      // var sourceType = $("#sourceType").val()
      // if (sourceType == "input") {
      //   var dataSource = $("#selectionInput").val()
      // } else {
      //   var dataSource = $("#selectionSources").val()
      // }

      var dataSource = $("#selectionSources").val()
      var dataTable = $("#dataSourceList").val()
      var dataAction = $("#dataAction").val()

      if (!dataTable) {
          alert('Please Select Data Source!')
          return 0;
      } else if (!dataSource.trim()) {
          alert('Selection Cannot be Blank!')
          return 0;
      } else if (!dataAction) {
          alert('Please Select Action!')
          return 0;

      }
      // alert(sourceType + " " + dataSource)

      var action = "add parameter"
      $.ajax({
        type: "POST",
        url: "includes/controller/productivityController.php",
        data: {
          action: action,
          key: key,
          datasource : dataSource,
          datatable : dataTable,
          dataaction : dataAction
        },
        cache: false,
        beforeSend: function() {

          // $("#addParameterDiv").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');

        },
        success: function (data) {

          $("#selectionSources").val('')
          $("#sqlText").val(data)
        },
        error: function(err) {
        }
      });

  })

  $("#createSource").click(function(){
      var sourceTitle = $("#sourceTitle").val()
      if (!sourceTitle.trim()) {
            alert('Source Title Cannot be blank!')
            return 0
      }
      var action = "create new source"
      $.ajax({
        type: "POST",
        url: "includes/controller/productivityController.php",
        data: {
          action: action,
          key: key,
          sourcetitle : sourceTitle
        },
        cache: false,
        beforeSend: function() {

          // $("#addParameterDiv").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');

        },
        success: function (data) {

           if (data == 1) {
             return 'SUCCESS! Source Has been Created!'
           } else if (data == 0){
             return 'FAILED! Cannot Input Data Source With the same name!'
           } else {
             return 'ERROR! Something Went Wrong! Please Try Again!'
           }
        },
        error: function(err) {
        }
      });

  })

  // $.ajax({
  //   type: "POST",
  //   url: "index2.php",
  //   data: {
  //     key: key
  //   },
  //   cache: false,
  //   beforeSend: function() {
  //
  //   },
  //   success: function (data) {
  //
  //     $("#defaultSelect").html(data)
  //   },
  //   error: function(err) {
  //   }
  // });

})


</script>


</body>
</html>

<style media="screen">

textarea {
    margin-top:5px;
    background-color:#fff !important;
}

.select2-container--default .select2-selection--multiple {
    background-color: white;
    border: 1px solid #d2d6de !important;
    border-radius: 0px !important;
    cursor: text;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #000000 !important;
    border: 1px solid #aaa;
    border-radius: 4px;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px;
}

ul.ui-autocomplete, div .eac-item{
    z-index: 9999 !important,
    position: absolute
}


</style>
