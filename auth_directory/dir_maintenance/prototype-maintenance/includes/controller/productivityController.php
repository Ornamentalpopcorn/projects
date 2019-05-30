<?Php
  // error_reporting(0);
  // include('../../../../../connection.php');
  header('Access-Control-Allow-Origin: *');
  session_start();

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

  spl_autoload_register(function ($class_name) {

      $fileName = stream_resolve_include_path('../model/class/'.$class_name . '.php');
      if ($fileName !== false) include $fileName;

  });

  // function autoload($className)
  spl_autoload_register(function ($class_name) {
     $class_name = ltrim("..\\model\\class\\src\\" . $class_name, '\\');
     $fileName  = '';
     $namespace = '';
     if ($lastNsPos = strrpos($class_name, '\\')) {
         $namespace = substr($class_name, 0, $lastNsPos);
         $class_name = substr($class_name, $lastNsPos + 1);
         $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
     }
     $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

     require $fileName;
  });



if (isset($_POST['key'])) {

    $result;
    if ($_POST['key'] == 'productivity') {

            if ($_POST['action'] == "select data") {
                  $obj = new Productivity();
                  $obj->data_type = $_POST['datasource'] ;
                  $result = $obj->checkStepsList();

            } elseif ($_POST['action'] == "create new source") {
                  $obj = new Productivity();
                  $obj->source = $_POST['source'];
                  $obj->source_name = $_POST['sourcetitle'];
                  $result = $obj->createNewSource($_SESSION['auth_usercode']);

            } elseif ($_POST['action'] == "add word") {
                  $obj = new Productivity();
                  $obj->word = str_replace("MORF", "FROM", $_POST['word']); // return to original word
                  $result = $obj->addToTxtFile($_SESSION['auth_usercode']);

            } elseif ($_POST['action'] == "search phrase") {
                  $obj = new Productivity();
                  $obj->source = $_POST['source'];
                  $obj->phrase = str_replace("MORF", "FROM", $_POST['phrase']); // return to original word
                  $result = $obj->searchPhrase();

            } elseif ($_POST['action'] == "format phrases") {
                  $obj = new Productivity();
                  $obj->phrase = str_replace("MORF", "FROM", $_POST['phrase']); // return to original word
                  $result = $obj->formatPhrase();

            } elseif ($_POST['action'] == "display source") {
                  $obj = new Productivity();
                  $result = $obj->displaySourceList();

            } elseif ($_POST['action'] == "display value") {
                  $obj = new Productivity();
                  $result = $obj->displayResult($_POST['md'], $_POST['month'], $_POST['query'] );

            } elseif ($_POST['action'] == "apply changes to productivity") {
                  $obj = new Productivity();
                  $result = $obj->applyChanges($_POST['mdcode'], $_POST['date'], $_POST['amount'], $_POST['type'] );

            //NOTE::******************STEP 3
            //NOTE::******************STEP 3
            } elseif ($_POST['action'] == "save datasource") {
                  $obj = new Productivity();
                  $obj->source_title = $_POST['sourcename'];
                  $obj->source_type = $_POST['sourcetype'];
                  $obj->source = $_POST['sql'];

                  $result = $obj->saveSource();

            } elseif ($_POST['action'] == "edit source") {
                  $obj = new Productivity();
                  $obj->source_id = $_POST['sourceid'];
                  $result = $obj->displaySourceInfo();

            } elseif ($_POST['action'] == "apply to report") {
                  $obj = new Productivity();
                  $obj->source_type = $_POST['sourcetype'];
                  $obj->source = $_POST['sql'];
                  $result = $obj->applyToReport();

            } elseif ($_POST['action'] == "edit sales source") {
                  $obj = new Productivity();
                  $obj->source_type = $_POST['sourcetype'];
                  $result = $obj->getSalesSource();
            }

    } else {

      $result = "<h3><center>INVALID CREDENTIALS PASSED!</center></h3>";

    }// if post key

    echo $result;

} else {

  echo "<h3><center>INVALID CREDENTIALS PASSED!</center></h3>";

} // if isset


?>
