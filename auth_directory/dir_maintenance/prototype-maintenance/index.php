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


<!-- AUTOCOMPLETE -->
<!-- AUTOCOMPLETE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js"></script>
<!-- AUTOCOMPLETE -->
<!-- AUTOCOMPLETE -->


<script>

$(document).ready(function(){

  var key = 'productivity'

  var options = {
      url: "referenceSources.php",
      getValue: "name",
      placeholder: "Check Reference.. Ex. Go, Luis Raymond",
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

  //NOTE:: INTELLISENSE API
  //NOTE:: INTELLISENSE API

  var typingTimer;                //timer identifier
  var doneTypingInterval = 1000;  //time in ms, 5 second for example

  //user is "finished typing," do something
  function doneTyping (value) {

        var source = $("#dataSource").val()

        var str = value
        var phrase = ""
        if (str) {
          phrase = str.match(/\w+$/)[0];
          action = "search phrase"
          $.ajax({
            type: "POST",
            url: "includes/controller/productivityController.php",
            data: {
              action: action,
              key: key,
              phrase: phrase,
              source: source
            },
            cache: false,
            beforeSend: function() {

              $("#suggestionsTab").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-0.5px;" class="btn  btn-lrg ajax" title="Loading Suggestions..."> <i class="fa fa-spinner fa-spin "></i></button></div></center>');

            },
            success: function (data) {

              $("#suggestionsTab").html(data)
            },
            error: function(err) {
            }
          });

        } else {
            $("#suggestionsTab").html('')
        }


  }

  $("#sqlTextArea").keyup(function(e) {

    if (typingTimer) clearTimeout(typingTimer);
    if ($(this).val()) {
      typingTimer = setTimeout(doneTyping($(this).val() ), doneTypingInterval);
    }

  })

  //on keydown, clear the countdown
  $("#sqlTextArea").keydown(function(e) {
      clearTimeout(typingTimer);
  });


  //
  // $("#sqlTextArea").keyup(function(e) {
  //
  //   var str = $(this).val()
  //   var phrase = ""
  //   if (str) {
  //     phrase = str.match(/\w+$/)[0];
  //     action = "search phrase"
  //     $.ajax({
  //       type: "POST",
  //       url: "includes/controller/productivityController.php",
  //       data: {
  //         action: action,
  //         key: key,
  //         phrase: phrase
  //       },
  //       cache: false,
  //       beforeSend: function() {
  //
  //         $("#suggestionsTab").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-0.5px;" class="btn  btn-lrg ajax" title="Loading Suggestions..."> <i class="fa fa-spinner fa-spin "></i></button></div></center>');
  //
  //       },
  //       success: function (data) {
  //
  //         $("#suggestionsTab").html(data)
  //       },
  //       error: function(err) {
  //       }
  //     });
  //
  //   } else {
  //       $("#suggestionsTab").html('')
  //   }
  //
  //   if (e.keyCode == 8) {
  //       console.log('backspace')
  //   }
  //
  // })
  //NOTE:: INTELLISENSE API
  //NOTE:: INTELLISENSE API

  $("#sqlTextArea").keydown(function(e){

    try {

      var phrase = $(this).val()
      var word = ""

      // if (str) {
      if (e.keyCode == 32) { // if space has been pressed

          // word = phrase.match(/\w+$/)[0];
          var word  = $("#sqlTextArea").val()
          word = word.replace("FROM", "MORF") // hostgator blocks FROM keyword treating it as SQL injection, mask to pass through
          setTimeout(function() {
              addToTxtFile(word)
              formattedQuery(word)
          } , 500);

      }

    } catch (e) {
      return 0; // catch error
    }

  })

  $("#dataSource").change(function(){

    var source = $(this).val()


  })

  $("#sqlTextArea").blur(function(e){

    try {

        // word = phrase.match(/\w+$/)[0];
        var word  = $("#sqlTextArea").val()
        word = word.replace("FROM", "MORF") // hostgator blocks FROM keyword treating it as SQL injection, mask to pass through
        setTimeout(function() {
            addToTxtFile(word)
            formattedQuery(word)
        } , 500);

    } catch (e) {
      return 0; // catch error
    }

  })

  $(document).on('click', '#data-displayvalue', function (e) {

      // if ($('#data-mdcode').val().trim() && $('#data-monthnum').val().trim() ) {

        var dataType = $("#dataType").val()
        var md = $("#data-mdcode").val().trim()
        var month = $("#data-monthnum").val().trim()
        if (month <= 9) {
          month = "0" + month
        }

        var queryText = $("#queryText").val().trim()
        $("#data-displayvalue").attr("disabled", true)

        var action = "display value"
        $.ajax({
          type: "POST",
          url: "includes/controller/productivityController.php",
          data: {
            action: action,
            key: key,
            month: month,
            md: md,
            query: queryText
          },
          cache: false,
          beforeSend: function() {


            $("#displayResult").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');
          },
          success: function (data) {

            $("#displayResult").html(data)
            $("#data-displayvalue").attr("disabled", false)

            $("#dataTable").dataTable({
              paging: false
            })
          },
          error: function(err) {
          }
        });
      // } else {
      //   alert('Please Complete Field to display data!')
      // }


  })

  $(document).on('click', '.suggested-word', function (e) {

      var str = $("#sqlTextArea").val()
      var lastIndex = str.lastIndexOf(" ");
      var new_str = ""
      var suggested_word = $(this).data('value')
      str = str.substring(0, lastIndex);
      new_str = str + " " + suggested_word

      $("#sqlTextArea").val(new_str + " ")

      str = str.replace("_", " ")
      new_str = str + " " + suggested_word
      new_str = new_str.replace("FROM", "MORF")  // hostgator blocks FROM keyword treating it as SQL injection, mask to pass through

      setTimeout(function() {
          addToTxtFile(new_str)
          formattedQuery(new_str)
          // console.log(new_str)
      } , 500);
  })

  function formattedQuery(phrases) {
      var action ="format phrases"
      $.ajax({
        type: "POST",
        url: "includes/controller/productivityController.php",
        data: {
          action: action,
          key: key,
          phrase: phrases
        },
        cache: false,
        beforeSend: function() {

        },
        success: function (data) {

              $("#equivalentSql").html(data)
        },
        error: function(err) {
        }
      });
  }

  function addToTxtFile(word) {
        var action = "add word"
        $.ajax({
          type: "POST",
          url: "includes/controller/productivityController.php",
          data: {
            action: action,
            key: key,
            word: word
          },
          cache: false,
          beforeSend: function() {

          },
          success: function (data) {

                // $("#testText").html(data)
          },
          error: function(err) {
          }
        });

  }


  $("#dataType").change(function() {

      var dataType = $(this).val()
      var action = 'select data'

      // $.ajax({
      //   type: "POST",
      //   url: "includes/controller/productivityController.php",
      //   data: {
      //     action: action,
      //     key: key,
      //     datasource: dataType
      //   },
      //   cache: false,
      //   beforeSend: function() {
      //     $("#dataType").prop("disabled", true)
      //
      //     $("#dataList").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');
      //
      //   },
      //   success: function (data) {
      //
      //     $("#dataList").html(data)
      //     $("#dataType").prop("disabled", false)
      //   },
      //   error: function(err) {
      //   }
      // });

  }) // #dataType end clause

  $(document).on('click', '#sourceList-edit .sourceList', function (e) {
      var sourceId = $(this).data('id')
      $("#sourceType").html(sourceId)
      $("#checkReportChanges").html('')

      var query = $("#queryText-editreport").val()

      $("#queryText-editreport").val(query + " " + $(this).text() )

      // var action = "edit source"
      // $.ajax({
      //   type: "POST",
      //   url: "includes/controller/productivityController.php",
      //   data: {
      //     action: action,
      //     key: key,
      //     sourceid: sourceId
      //   },
      //   cache: false,
      //   beforeSend: function() {
      //
      //       $("#dataList").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');
      //
      //   },
      //   success: function (data) {
      //       $("#dataList").html(data); // creation succefull
      //   },
      //   error: function(err) {
      //   }
      // });

  })



  $(document).on('click', '#reportOutput-btn', function (e) {

      if (confirm('Apply Changes To Report ?')) {

              var md_code = $(this).data('mdcode')
              var date = $(this).data('date')
              var amount = $(this).data('amount')
              var data_type = $(this).data('type')

              var action = "apply changes to productivity"
              $.ajax({
                type: "POST",
                url: "includes/controller/productivityController.php",
                data: {
                  action: action,
                  key: key,
                  mdcode: md_code,
                  date: date,
                  type: data_type,
                  amount: amount
                },
                cache: false,
                beforeSend: function() {

                },
                success: function (data) {

                  // alert('Sales Successfully Updated!')
                  $("#fade-this").fadeIn()
                  $("#resulttest").html("<div id='fade-this'><center><h3><div class='alert alert-success' role='alert'>SALES SUCCESSFULLY UPDATED!</div></h3></center></center>");
                  $("#resulttest").append("&gt; <u><a target='_blank' href='https://www.bellkenz.com/dev-smpp/auth_directory/dir_productivity/productivity_report.php'>VIEW APPLIED CHANGES</a></u>")

                  setTimeout(function() {
                    $("#fade-this").fadeOut()
                  } , 5000);

                },
                error: function(err) {
                }
              });


      }

  })

  $("#createSource").click(function(){
      var source = $("#dataSource").val()
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
          source : source,
          sourcetitle : sourceTitle
        },
        cache: false,
        beforeSend: function() {

          // $("#addParameterDiv").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');

        },
        success: function (data) {
             // $("#errorMessage").html(data)
          if (data != 1) {
                  $("#errorMessage").fadeIn()
                  $("#errorMessage").html(data)
                  setTimeout(function() {
                    $("#errorMessage").fadeOut()
                  } , 5000);

          } else {

                  var action = "display source"
                  $.ajax({
                    type: "POST",
                    url: "includes/controller/productivityController.php",
                    data: {
                      action: action,
                      key: key
                    },
                    cache: false,
                    beforeSend: function() {

                        $("#sourceList").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');

                    },
                    success: function (data) {
                        $("#sourceList").html(data); // creation succefull
                    },
                    error: function(err) {
                    }
                  });


            $("#sourceList").html(source); // creation succefull
          }

        },
        error: function(err) {
        }
      });

  })

  // NOTE::*********************************ADDED IN STEP 3
  // NOTE::*********************************ADDED IN STEP 3
  $(document).on('click', '#sourceList-main .sourceList', function (e) {
      var sourceId = $(this).data('id')
      $("#sourceType").html(sourceId)
      $("#checkReportChanges").html('')

      $("#dataList").css("display","block")
      var action = "edit source"
      $.ajax({
        type: "POST",
        url: "includes/controller/productivityController.php",
        data: {
          action: action,
          key: key,
          sourceid: sourceId
        },
        cache: false,
        beforeSend: function() {

            $("#dataList").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');

        },
        success: function (data) {
            $("#dataList").html(data); // creation succefull
        },
        error: function(err) {
        }
      });

  })


  $(document).on('click', '#addNewSource', function (e) {
      $("#dataList").css("display","block")

      $("#data-title").val('')
      $("#data-mdcode").val('')
      $("#queryText").val('')
      $("#displayResult").html('')
      $("#checkReportChanges").html('')
      $("#sourceType").html('new')

      $("#dataList h3").html('ADD NEW DATA SOURCE')
  })

  $(document).on('click', '#data-save', function (e) {

      if ($("#data-title").val().trim() && $("#queryText").val().trim() ) {

          var dataSourceName = $("#data-title").val().trim()
          var sql = $("#queryText").val().trim()
          var sourceType = $("#sourceType").text()
          var sourceCategory = $("#source-category").val().trim()

          if (confirm('Save Data Source?') ) {

            $("#data-save").attr("disabled", true)
            var action = 'save datasource'
            $.ajax({
              type: "POST",
              url: "includes/controller/productivityController.php",
              data: {
                action: action,
                key: key,
                sourcename: dataSourceName,
                category: sourceCategory,
                sourcetype: sourceType,
                sql: sql
              },
              cache: false,
              beforeSend: function() {

              },
              success: function (data) {

                  $("#displayResult").fadeIn()
                  $("#displayResult").html(data)
                  if (data.includes('Source Successfully Created') ) {

                      $("#data-title").val('')
                      $("#source-category").val('')
                      $("#data-mdcode").val('')
                      $("#queryText").val('')


                      var action = "display source"
                      $.ajax({
                        type: "POST",
                        url: "includes/controller/productivityController.php",
                        data: {
                          action: action,
                          key: key
                        },
                        cache: false,
                        beforeSend: function() {

                          $("#sourceList-main").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>');

                        },
                        success: function (data) {
                          $("#sourceList-main").html('<center><a href="#" id="addNewSource" class="btn btn-primary btn btn-xs" style="margin-bottom:3px"><i class="fas fa-plus"></i> ADD DATA SOURCE</a></center>' + data); // creation succefull
                          $("#sourceList-edit").html("<br>" +data);
                        },
                        error: function(err) {
                        }
                      });


                  }

                  $("#data-save").attr("disabled", false)
                  setTimeout(function() {
                    // $("#displayResult").fadeOut()

                  } , 5000);

              },
              error: function(err) {
              }
            });

          }

      } else {
        alert('Please Complete Missing Fields!')
      }

  })

  $(document).on('click', '#data-apply', function (e) {
        var sql = $("#queryText-editreport").val().trim()
        var sourceType = $("#editApplySource").text()
        var date = $("#date").val()
        var lba_rebate = $("#lba-rebate").val().trim()

        if (sql && date && lba_rebate) {

            if (confirm('Apply To Report ?') ) {

              $("#data-apply").attr("disabled", true)
              var action = "apply to report"
              $.ajax({
                type: "POST",
                url: "includes/controller/productivityController.php",
                data: {
                  action: action,
                  key: key,
                  sourcetype: sourceType,
                  sql: sql,
                  date: date,
                  lba: lba_rebate
                },
                cache: false,
                beforeSend: function() {

                  $("#reportResultInfo").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Fetching Data"> <i class="fa fa-spinner fa-spin "></i>&nbsp; Loading...</button></div></center>')
                  $("#reportResultInfo").fadeIn()
                  $("#applyToReportResult").html('')
                },
                success: function (data) {
                    $("#data-apply").attr("disabled", false)

                    $("#reportResultInfo").html(data)
                    if (data == 1) {
                      $("#reportResultInfo").html('<br><br><center><div class="alert alert-success" role="alert">Sucessfully Applied To Report</div></center>')
                      alert('Successfully applied changes to report!')
                      $("#applyToReportResult").html("<br><br> &gt; <u><a target='_blank' href='./sales_report.php'>VIEW APPLIED CHANGES</a></u>")
                    }

                    setTimeout(function() {
                      // $("#reportResultInfo").fadeOut()
                    } , 5000);

                },
                error: function(err) {
                }
              });

            } // if apply to report


        } else {
                alert('Complete Missing Field!')
        }

  })

  $("#editSourceList a").click(function(){
      var source_type = $(this).data('id')
      $("#queryText-editreport").val('')
      $("#editSourceToApply").html('<h3>Editing Computation For: ' + source_type + ' Sales</h3> <div id="editApplySource" style="display:none">' + source_type + '</div><hr>')
      $("#editContent").css("display","block")

      var action = "edit sales source"
      $.ajax({
        type: "POST",
        url: "includes/controller/productivityController.php",
        data: {
          action: action,
          key: key,
          sourcetype: source_type
        },
        cache: false,
        beforeSend: function() {

        },
        success: function (data) {
           $("#queryText-editreport").val(data)
        },
        error: function(err) {
        }
      });
  })



}); // document ready function


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

/* #equivalentSql {
  background: lightgrey;
  border: 1px solid grey;
} */

textarea[name=queryText] {
  resize: none;
  background-color:#fff !important;
}


</style>
