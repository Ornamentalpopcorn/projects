<?Php

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

require("includes/autoloader.php");

$keyword = strval($_POST['query']);
$search_param = "$keyword%";

$productivity_class = new Productivity();

echo json_encode($productivity_class->selectReferenceList($search_param) ) ;
exit();
 
?>
