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
        $json = [];
        $sql = "SELECT md_code as m1, md_name as m2
        FROM md_profile_masterlist
        ORDER BY md_name ASC
        -- LIMIT 10";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
          foreach ($data as $row) {

              $json[] = ['code'=>$row['m1'], 'name'=>$row['m2']];
          }
        }

echo json_encode($json);

?>
