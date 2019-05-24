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
  <title>Sales and MD Productivity | Data Import</title>
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

  <!-- Bootstrap Selectpicker -->
  <link rel="stylesheet" href="../../../plugins/bootstrap-select/css/bootstrap-select.min.css">
  <!-- Check Boxes and Radio Buttons -->
  <link rel="stylesheet" href="../../../plugins/iCheck/all.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- Jquery File Upload -->
  <link rel="stylesheet" href="../../../plugins/jquery-fileupload/css/jquery.fileupload.css">
  <link rel="stylesheet" href="../../../plugins/jquery-fileupload/css/jquery.fileupload-ui.css">
  <!-- CSS adjustments for browsers with JavaScript disabled -->
  <noscript><link rel="stylesheet" href="../../../plugins/jquery-fileupload/css/jquery.fileupload-noscript.css"></noscript>
  <noscript><link rel="stylesheet" href="../../../plugins/jquery-fileupload/css/jquery.fileupload-ui-noscript.css"></noscript>

  <!-- jQuery 3 -->
  <script src="../../../dependencies/jquery/dist/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="../../../dependencies/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.7 -->
  <script src="../../../commons/js/commons.js"></script>
  <script src="../../../commons/js/fieldmaster.js"></script>
  <style type="text/css">
    .fileinput-button {
      position: relative;
      overflow: hidden;
      display: inline-block;
    }
    .fileinput-button input {
      position: absolute;
      top: 0;
      right: 0;
      margin: 0;
      opacity: 0;
      -ms-filter: 'alpha(opacity=0)';
      font-size: 200px !important;
      direction: ltr;
      cursor: pointer;
    }

    /* Fixes for IE < 8 */
    @media screen\9 {
      .fileinput-button input {
        filter: alpha(opacity=0);
        font-size: 100%;
        height: 100%;
      }
    }
    .overflow_errors{
      max-height: 60px;
      overflow-y: auto;
    }
  </style>
</head>
<input type="hidden" name="userid" id="user_id" value="<?php echo $_SESSION['auth_usercode'];?>">
