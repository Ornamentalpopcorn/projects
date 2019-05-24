<?php

define ("DB_SERVER", "localhost"); // set database host
define ("DB_USER", "epasadil_admin"); // set database user
define ("DB_PASS","Pr0+0c01$"); // set database password
define ("DB_NAME","epasadil_smpp"); // set database name


$mysqli = new mysqli('localhost', 'epasadil_admin', 'Pr0+0c01$','epasadil_smpp');


$link =  $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'dev_smpp';
// $server = 'localhost';
// $username = 'epasadil_admin';
// $password = 'Pr0+0c01$';
// $dbname = 'epasadil_smpp';


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

// $server = 'localhost';
// $username = 'epasadil_admin';
// $password = 'Pr0+0c01$';
// $dbname = 'epasadil_smpp';
// $charset = 'utf8';
// $options = array(
//     PDO::ATTR_PERSISTENT  => true,
// );
//
// try {
//   $pdoConn = new PDO("mysql:host={$server};dbname={$dbname};charset={$charset}",
//                          $username,
//                          $password,
//                          $options);
//   $pdoConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//
//
// } catch (PDOException $e) {
//
//   throw new Exception("Connection failed: ". $e->getMessage());
//
// }


?>
