<?Php

class Productivity extends ChrisKonnertz\StringCalc\StringCalc implements ProductivityInterface
{
  use ProductivityCommand;
  /*NOTE: CLASSES THAT WILL BE STORED IN **TRAITS**
  WILL BE USUALLY GENERAL STUFF LIKE DISPLAYING INFORMATION
  THAT HAS LESS CONCERN IN COMPUTATION
  */
  public $crediting_date = "2018-01-01";
  public $data_value;
  public $id;
  public $data_id;
  public $data_type;
  public $data_parameter;
  public $data_parameter_constraint;

  public $data_source;
  public $data_table;
  public $data_action;

  public $source_name;

  public function computeResult()
  {
    global $conn_pdo;
    try {
       $equation;

       $sql = "SELECT *
       FROM creation_source_list_data
       WHERE 1=1
             AND source_id = '5'
             -- AND data_type IN ('source', 'data')
       ORDER BY id ASC
       ";
       $data = $this->querySelect($sql) ;
       if ($data) {
           foreach ($data as $row) {
                $this->id = $row['id'] ;
                $this->data_value = $row['data_value'] ;
                $this->data_id = $row['data_id'] ;
                $this->data_type = $row['data_type'] ;
                $this->data_parameter = $row['data_parameter'] ;
                $this->data_parameter_constraint = $row['data_parameter_constraint'] ;

                if ($this->data_type == "source") {
                          $this->sourceData() ;
                } elseif ($this->data_type == "data") {

                }

                $equation[] = array(
                    "type" => $this->data_type,
                    "value" => $this->data_value
                );
           }

           // NOTE: DO OPERATION BASED ON CREATED SET OF DATA SOURCES
          $this->performEquation($equation);

       }
       return 1;

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }

  }

  public function performEquation($array)
  {
    global $conn_pdo;
    try {

      foreach ($array as $ar) {

          $val1 = rand(1,10);
          $val2 = rand(2,5);

          $this->calculate("$val1 + $val2");

          // echo $ar['type'] . " | " . $ar['value'];
          // echo "<br>";

      }
      return 1;

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }


  // public function checkFetchData($id, $data_id, $data_parameter, $data_parameter_constraint, $data_value)
  public function sourceData()
  {
    global $conn_pdo;
    try {

        $sql = "SELECT id, data_id, data_value
        FROM creation_source_list_data
        WHERE 1=1
              AND source_id = '$this->data_value'
        ";

        $data = $this->querySelect($sql);
        foreach ($data as $row) {
            $this->fetchSalesData($row['id'], $row['data_id'], $row['data_value']) ;
        } // foreach data

        return 1;

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }

  public function fetchSalesData($id, $data_id, $sale_type)
  {
    global $conn_pdo;
    try {
       $sql = $this->buildQuery($this->data_value, $id, $data_id, $sale_type, $this->crediting_date);
              $this->prepareData($this->data_value, $id, $sql);
       return 1;

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }

  public function prepareData($source_id, $given_id, $sql)
  {
    global $conn_pdo;
    try {
      $data_sales = $this->querySelect($sql);
      if ($data_sales) {

          $sql = "INSERT INTO creation_source_list_sales
          (
            given_id,
            source_id,
            sale_type,
            -- md_code,
            -- branch_code,
            -- lba_rebate_code,
            -- product_code,
            -- product_segment,
            -- crediting_date,
            -- quantity,
            total_amount
          )
          VALUES
          (
            :given_id,
            :source_id,
            :sale_type,
            -- :md_code,
            -- :branch_code,
            -- :lba_rebate_code,
            -- :product_code,
            -- :product_segment,
            -- :crediting_date,
            -- :quantity,
            :total_amount
          )
          ";
         $stmt_insert = $conn_pdo->prepare($sql);
         $type = "whole";
         foreach ($data_sales as $row_sales) {
            $total_amount = $row_sales['amt'] ;
            if (!$total_amount) $total_amount = 0;

            $stmt_insert->bindValue(":given_id" , $given_id, PDO::PARAM_STR);
            $stmt_insert->bindValue(":source_id" , $source_id, PDO::PARAM_STR);
            $stmt_insert->bindValue(":sale_type" , $type, PDO::PARAM_STR);
            $stmt_insert->bindValue(":total_amount" , $total_amount, PDO::PARAM_STR);
            $stmt_insert->execute();
         }
      }
      return 1;

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }

  } // function end fetchSalesData



}

?>
