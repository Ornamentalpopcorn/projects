<?Php
  // error_reporting(0);
  // include('../../../../../connection.php');
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
                  $obj->source_name = $_POST['sourcetitle'];
                  $result = $obj->createNewSource();
            } elseif ($_POST['action'] == "delete parameter") {
                  $obj = new Productivity();
                  $result = $obj->deleteSource();

            } elseif ($_POST['action'] == "add parameter") {

                  $obj = new Productivity();
                  $obj->data_source = $_POST['datasource'];
                  $obj->data_table = $_POST['datatable'];
                  $obj->data_action = $_POST['dataaction'];

                  $result = $obj->insertSource();

            } // if post action

    } else {

      $result = "<h3><center>INVALID PARAMETER PASSED!</center></h3>";

    }// if post key

    echo $result;

} else {

  echo "<h3><center>INVALID KEY PASSED!</center></h3>";

} // if isset


?>
