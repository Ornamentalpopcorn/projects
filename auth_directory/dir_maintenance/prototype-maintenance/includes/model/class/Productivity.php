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
  public $word;
  public $phrase;
  public $source_id;
  public $source_title;
  public $source_type;
  public $source;


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

  // NOTE:: STEP 1
  // NOTE:: STEP 1
  public function applyChanges($md_code, $date, $amount, $data_type)
  {
    global $conn_pdo;
    try {
      $specific_type = "";
      if ($data_type == "dispensing") {
        $sale_type = " AND sale_type = 'DISPENSING' ";
        $type = "DISPENSING";
      } elseif ($data_type == "tagged") {
        $sale_type = " AND sale_type = 'TAGGED ACCOUNT' ";
        $type = "TAGGED ACCOUNT";
      } elseif ($data_type == "senior") {
        $sale_type = " AND sale_type IN ('ON-SITE','OFF-SITE') AND sale_specific_type IN ('SENIOR') ";
        $type = "ON-SITE";
        $specific_type = "SENIOR";
      } elseif ($data_type == "non-senior") {
        $sale_type = " AND sale_type IN ('ON-SITE','OFF-SITE') AND sale_specific_type IN ('NON-SENIOR') ";
        $type = "ON-SITE";
        $specific_type = "SENIOR";
      } elseif ($data_type == "other area with actual") {
        $sale_type = " AND sale_type = 'OTHER AREA ACTUAL' ";
        $type = "OTHER AREA ACTUAL";
      } elseif ($data_type == "other area without actual") {
        $sale_type = " AND sale_type = 'OTHER AREA NO ACTUAL' ";
        $type = "OTHER AREA NO ACTUAL";
      }

      if ($data_type == "senior") {
        $other_type = " AND sale_type IN ('ON-SITE','OFF-SITE') ";
      } elseif ($data_type == "non-senior") {
        $other_type = " AND sale_type IN ('ON-SITE','OFF-SITE') ";
      } else {
        $other_type =  $sale_type;
      }

      $sql = "DELETE FROM productivity_computed_report
      WHERE 1=1
            AND md_code = '$md_code'
            AND crediting_date = '$date'
            AND is_checked = 1
            AND is_source = 1
            $other_type";
      $stmt = $conn_pdo->prepare($sql);
      $stmt->execute();

      $sql = "UPDATE productivity_computed_report
      SET  is_checked = 0
      WHERE 1=1
            AND md_code = '$md_code'
            AND crediting_date = '$date'
            AND is_checked = 1
            $other_type
      ";
      $stmt = $conn_pdo->prepare($sql);
      $stmt->execute();

      $sql = "INSERT INTO  productivity_computed_report
      (
          md_code,
          crediting_date,
          sale_type,
          total_amount,
          is_checked,
          is_source
      )
      VALUES
      (
          :md_code,
          :crediting_date,
          :sale_type,
          :total_amount,
          :is_checked,
          :is_source
      )
      ";
      $stmt_insert = $conn_pdo->prepare($sql);

      $stmt_insert->bindValue(":md_code" , $md_code, PDO::PARAM_STR);
      $stmt_insert->bindValue(":crediting_date" , $date, PDO::PARAM_STR);
      $stmt_insert->bindValue(":sale_type" , $type, PDO::PARAM_STR);
      $stmt_insert->bindValue(":total_amount" , $amount, PDO::PARAM_STR);
      $stmt_insert->bindValue(":is_checked" , "1", PDO::PARAM_STR);
      $stmt_insert->bindValue(":is_source" , "1", PDO::PARAM_STR);
      $stmt_insert->execute();

      $sql = "DELETE FROM productivity_computed_report_specific
      WHERE 1=1
            AND md_code = '$md_code'
            AND crediting_date = '$date'
            AND is_checked = 1
            AND is_source = 1
            $sale_type
      ";
      $stmt = $conn_pdo->prepare($sql);
      $stmt->execute();

      $sql = "UPDATE productivity_computed_report_specific
      SET  is_checked = 0
      WHERE 1=1
          AND md_code = '$md_code'
          AND crediting_date = '$date'
          AND is_checked = 1
          $sale_type
      ";
      $stmt = $conn_pdo->prepare($sql);
      $stmt->execute();


      $sql = "INSERT INTO  productivity_computed_report_specific
      (
          md_code,
          crediting_date,
          sale_type,
          sale_specific_type,
          total_amount,
          is_checked,
          is_source
      )
      VALUES
      (
          :md_code,
          :crediting_date,
          :sale_type,
          :sale_specific_type,
          :total_amount,
          :is_checked,
          :is_source
      )
      ";
      $stmt_insert = $conn_pdo->prepare($sql);

      $stmt_insert->bindValue(":md_code" , $md_code, PDO::PARAM_STR);
      $stmt_insert->bindValue(":crediting_date" , $date, PDO::PARAM_STR);
      $stmt_insert->bindValue(":sale_type" , $type, PDO::PARAM_STR);
      $stmt_insert->bindValue(":sale_specific_type" , $specific_type, PDO::PARAM_STR);
      $stmt_insert->bindValue(":total_amount" , $amount, PDO::PARAM_STR);
      $stmt_insert->bindValue(":is_checked" , "1", PDO::PARAM_STR);
      $stmt_insert->bindValue(":is_source" , "1", PDO::PARAM_STR);
      $stmt_insert->execute();


      return 1;

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }

  }


  public function displayResult($md_code, $month, $sql)
  {
  global $conn_pdo;
      try {
        $txt = "<hr>";
        $txt .= "<h3><b>RESULT</b></h3>";
        $column_name = array();

        $date =   "2018-" . $month . "-01" ;

        if ($md_code) {

              if (strpos($sql, "WHERE") !== FALSE) {

                  $sql_exploded = explode("WHERE", $sql);
                  if (strpos($sql_exploded[1], "GROUP BY") !== FALSE) {

                        $sql_exploded2 = explode("GROUP BY", $sql_exploded[1]);

                        $sql = $sql_exploded[0] . " WHERE md_code = '$md_code'
                        GROUP BY crediting_date, " . $sql_exploded2[1];

                  } else {

                      $sql = $sql_exploded[0] . " WHERE " . $sql_exploded[1] . " AND md_code = '$md_code'
                      GROUP BY crediting_date
                      ";

                  }

              } else {

                  if (strpos($sql, "GROUP BY") !== FALSE) {

                      $sql_explode = explode("GROUP BY", $sql);
                      $sql = $sql_explode[0] . " WHERE 1=1
                          AND md_code = '$md_code'
                          GROUP BY crediting_date, " . $sql_explode[1] ;

                  } else {

                      $sql = $sql . " WHERE 1=1
                          AND md_code = '$md_code'
                          GROUP BY crediting_date
                          ";
                  }

              }

        } else {

              if (strpos($sql, "WHERE") !== FALSE) {

                  $sql_exploded = explode("WHERE", $sql);
                  if (strpos($sql_exploded[1], "GROUP BY") !== FALSE ) {
                    $sql_exploded2 = explode("GROUP BY", $sql_exploded[1]);

                    $sql = $sql_exploded[0] . " WHERE " . $sql_exploded2[0] . "
                    GROUP BY crediting_date, " . $sql_exploded2[1];

                  } else {

                    $sql = $sql_exploded[0] . " WHERE " . $sql_exploded[1] . " GROUP BY crediting_date ";
                  }


              } else {

                  if (strpos($sql, "GROUP BY") !== FALSE) {

                      $sql_explode = explode("GROUP BY", $sql);
                      $sql = $sql_explode[0] . " WHERE 1=1 GROUP BY crediting_date," . $sql_explode[1];

                  } else {

                      $sql = $sql . " WHERE 1=1
                      GROUP BY crediting_date
                      ";
                  }

              }

        }

        $check_syntax = $this->checkQuerySyntax($sql, $sql)  ;

        if ($check_syntax == 1 ) {
          $stmt = $conn_pdo->prepare($sql);
          $stmt->execute();
          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);



          foreach ($data as $key => $row) {

            foreach ($row as $key2 => $value) {

                // if (strpos($key2, "amount") !== FALSE || strpos($key2, "AMOUNT") !== FALSE) {
                       // $amount = $value;
                       // $txt .= strtoupper(str_replace("_", " ", $key2) ) . ": <u>" . number_format($value,2) . "</u><br>";
                // } else $txt .= strtoupper(str_replace("_", " ", $key2) ) . ": <u>" . $value . "</u><br>";

                $key_values[$key2][] = $value;

                $key_list[] = $key2;
            }
            // $txt .= "<hr>";
          }

          if ($key_list) {

                  $txt .= "<table border='1' class='table table-striped table-hover display' id='dataTable' style='text-align:center; table-layout:auto;' width='100%' >";
                  $key_list = array_unique($key_list);
                  $txt .= '<thead style="text-align:center !important; border: 1px solid #FFF;padding:4px;color:#2779aa">';
                  foreach ($key_list as $list) {
                    $txt .= "<th style='background-color: #3c8aea; color: white';>" . strtoupper(str_replace("_", " ", $list)) . "</th>";
                  } // fe
                  $txt .= "</thead>";

                  $txt .= "<tbody>";
                  $txt_array = array();
                  for ($i=0; $i <= count($key_values[$list]) ; $i++) {
                    $txt .= "<tr>";
                    $c = 0;
                    $td_text = "";
                    foreach ($key_list as $list) {

                        if (strpos($list, "amount") !== FALSE || strpos($list, "AMOUNT") !== FALSE) {
                                $td_text .= "<td>" . number_format($key_values[$list][$i],2) . "</td>";
                        } else  $td_text .= "<td>" . $key_values[$list][$i] . "</td>";

                        if (strpos($key_values[$list][$i], "2018-") !== FALSE) $c++;

                    } // fe
                    if ($c >= 1) {
                       $txt .= $td_text;
                    }
                    $txt .= "</tr>";
                  }
                  $txt .= "</tbody>";
                  $txt .= "</table>";
          } else  $txt .= "<span style='color:red'><b><center>NO RESULT TO DISPLAY!<br><small>MAKE SURE TO INCLUDED CREDITING DATE IN QUERY</small></b></center></span>";

          // foreach ($data as $key => $row) {
          //
          //   foreach ($row as $key2 => $value) {
          //
          //     if (strpos($key2, "amount") !== FALSE || strpos($key2, "AMOUNT") !== FALSE) {
          //            $amount = $value;
          //            $txt .= strtoupper(str_replace("_", " ", $key2) ) . ": <u>" . number_format($value,2) . "</u><br>";
          //     } else $txt .= strtoupper(str_replace("_", " ", $key2) ) . ": <u>" . $value . "</u><br>";
          //
          //     $key_list[] = $key2;
          //   }
          //   $txt .= "<hr>";
          // }


          $txt .= "<br><br><b>QUERY CREATED:</b> <pre>$sql</pre>";
          $txt .= "<div class='clearfix'></div>";

          return $txt;

        } else {
          return "<br><br><center style='color: red;'><b>INVALID QUERY ENTERED! PLEASE CHECK SYNTAX!</b></center>";
        }

      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  // NOTE:: STEP 1
  // NOTE:: STEP 1

  // NOTE:: STEP 3
  // NOTE:: STEP 3
  public function applyToReport()
  {
    global $conn_pdo;
    try {
<<<<<<< HEAD

        $c = 1; $pseudo_syntax = array();
        foreach (explode("::", $this->source) as $source_sql) {
              $source_sql = trim($source_sql);

              if ($source_sql[0] == "x" || $source_sql[0] == "*" || $source_sql[0] == "/" || $source_sql[0] == "+" || $source_sql[0] == "-  ") {
                $operator = $source_sql[0];
              }

              if ($source_sql[0] == "x") {     $query_list[] = ltrim($source_sql, "x");
              } elseif ($source_sql[0] == "+") $query_list[] = ltrim($source_sql, "+");
              } elseif ($source_sql[0] == "*") $query_list[] = ltrim($source_sql, "*");
              } elseif ($source_sql[0] == "-") $query_list[] = ltrim($source_sql, "-");
              } elseif ($source_sql[0] == "/") $query_list[] = ltrim($source_sql, "/");
              } else $operator = "";

              if ($c == 1) {
                     $pseudo_syntax[] = "query" . $c++ . " " . $operator;
              } else $pseudo_syntax[] = $operator . " query" . $c++ ;
              $operator_list[] = $operator;
        }
        echo  $query_list[0];
        echo "<br><br>";
        echo  $query_list[1];
        echo "<br><br>";


=======
        $full_query = $this->source;
>>>>>>> step_3
        $sql = "SELECT source_id, full_query
        FROM reference_source_list
        WHERE 1=1
              AND source_name LIKE ?
        GROUP BY source_id ";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute([$this->source . "%"]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {

          foreach ($data as $row) {
             // echo $row['source_id'] . " " .$row['full_query'];
             $this->source = $row['full_query'];
          } // foreach row

<<<<<<< HEAD
=======
        }

          $check_syntax = $this->checkQuerySyntax($this->source, $this->source);
          if ($check_syntax == 1) { // syntax is correct
                $source_sql = "";
                $filter_sql = explode("GROUP BY", $this->source);
                $source_sql = $filter_sql[0];

                if (strpos($this->source_type, "Senior") !== FALSE) {
                  $sale_type = " AND sale_type IN ('ON-SITE','OFF-SITE') AND sale_specific_type IN ('SENIOR') ";
                  $other_type = " AND sale_type IN ('ON-SITE','OFF-SITE') ";
                  $type = "ON-SITE";
                  $specific_type = "SENIOR";
                } elseif (strpos($this->source_type, "Dispensing") !== FALSE) {
                  $sale_type = " AND sale_type = 'DISPENSING' ";
                  $type = "DISPENSING";
                  $other_type = "";
                  $specific_type = "";
                }


                if (strpos($source_sql, "AMOUNT") !== FALSE || strpos($source_sql, "amount") !== FALSE) {

                  $sql = "SELECT DISTINCT md_code
                  FROM md_profile_list_by_class as b
                  WHERE 1=1
                        AND status IN ('JEDI','IPG','PADAWAN')
                        -- AND md_code = 'D-16-002031'
                  LIMIT 50
                  ";
                  $data = $this->querySelect($sql);

                  foreach ($data as $row) {
                      $md_code = $row['md_code'];

                      $md_list[] = "'$md_code'";
                      for ($i=1; $i <= 12 ; $i++) {

                          if ($i <= 9) {
                                 $date = "2018-0" . $i . "-01";
                          } else $date = "2018-$i-01";

                          if (strpos($source_sql, "WHERE") !== FALSE) {
                                 $d_sql = $source_sql . " AND md_code = '$md_code' AND crediting_date = '$date' ";
                          } else $d_sql = $source_sql . " WHERE 1=1 AND md_code = '$md_code' AND crediting_date = '$date' ";


                          // FETCH AMOUNT FROM QUERY
                          // FETCH AMOUNT FROM QUERY
                          $amount = 0;
                          $stmt = $conn_pdo->prepare($d_sql);
                          $stmt->execute();
                          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          foreach ($data as $key => $row) {
                            foreach ($row as $key2 => $value) {

                              if (strpos($key2, "amount") !== FALSE || strpos($key2, "AMOUNT") !== FALSE) {
                                     $amount = $value;
                              }
                            }
                          }

                          // FETCH AMOUNT FROM QUERY
                          // FETCH AMOUNT FROM QUERY

                                $sql = "INSERT INTO  productivity_computed_report
                                (
                                    md_code,
                                    crediting_date,
                                    sale_type,
                                    total_amount,
                                    is_checked,
                                    is_source
                                )
                                VALUES
                                (
                                    :md_code,
                                    :crediting_date,
                                    :sale_type,
                                    :total_amount,
                                    :is_checked,
                                    :is_source
                                )
                                ";
                                $stmt_insert = $conn_pdo->prepare($sql);

                                $stmt_insert->bindValue(":md_code" , $md_code, PDO::PARAM_STR);
                                $stmt_insert->bindValue(":crediting_date" , $date, PDO::PARAM_STR);
                                $stmt_insert->bindValue(":sale_type" , $type, PDO::PARAM_STR);
                                $stmt_insert->bindValue(":total_amount" , $amount, PDO::PARAM_STR);
                                $stmt_insert->bindValue(":is_checked" , "1", PDO::PARAM_STR);
                                $stmt_insert->bindValue(":is_source" , "2", PDO::PARAM_STR);
                                $stmt_insert->execute();


                                $sql = "INSERT INTO  productivity_computed_report_specific
                                (
                                    md_code,
                                    crediting_date,
                                    sale_type,
                                    sale_specific_type,
                                    total_amount,
                                    is_checked,
                                    is_source
                                )
                                VALUES
                                (
                                    :md_code,
                                    :crediting_date,
                                    :sale_type,
                                    :sale_specific_type,
                                    :total_amount,
                                    :is_checked,
                                    :is_source
                                )
                                ";
                                $stmt_insert = $conn_pdo->prepare($sql);

                                $stmt_insert->bindValue(":md_code" , $md_code, PDO::PARAM_STR);
                                $stmt_insert->bindValue(":crediting_date" , $date, PDO::PARAM_STR);
                                $stmt_insert->bindValue(":sale_type" , $type, PDO::PARAM_STR);
                                $stmt_insert->bindValue(":sale_specific_type" , $specific_type, PDO::PARAM_STR);
                                $stmt_insert->bindValue(":total_amount" , $amount, PDO::PARAM_STR);
                                $stmt_insert->bindValue(":is_checked" , "1", PDO::PARAM_STR);
                                $stmt_insert->bindValue(":is_source" , "2", PDO::PARAM_STR);
                                $stmt_insert->execute();
                      } // for i


                  } // foreach data

                  $date = '2018';
                  $sql = "DELETE FROM productivity_computed_report
                  WHERE 1=1
                        AND md_code IN (" . implode(',', $md_list) . ")
                        AND YEAR(crediting_date) = '$date'
                        AND is_checked = 1
                        AND is_source = 1
                        $other_type
                        ";
                  $stmt = $conn_pdo->prepare($sql);
                  $stmt->execute();

                  $sql = "UPDATE productivity_computed_report
                  SET  is_checked = 0
                  WHERE 1=1
                        AND md_code IN (" . implode(',', $md_list) . ")
                        AND YEAR(crediting_date) = '$date'
                        AND is_checked = 1
                        AND is_source = 0
                        $other_type
                  ";
                  $stmt = $conn_pdo->prepare($sql);
                  $stmt->execute();

                  $sql = "DELETE FROM productivity_computed_report_specific
                  WHERE 1=1
                        AND md_code IN (" . implode(',', $md_list) . ")
                        AND YEAR(crediting_date) = '$date'
                        AND is_checked = 1
                        AND is_source = 1
                        $sale_type
                  ";
                  $stmt = $conn_pdo->prepare($sql);
                  $stmt->execute();

                  $sql = "UPDATE productivity_computed_report_specific
                  SET is_checked = 0
                  WHERE 1=1
                      AND md_code IN (" . implode(',', $md_list) . ")
                      AND YEAR(crediting_date) = '$date'
                      AND is_checked = 1
                      AND is_source = 0
                      $sale_type
                  ";
                  $stmt = $conn_pdo->prepare($sql);
                  $stmt->execute();

                  // UPDATE SOURCEC TO 1
                  $sql = "UPDATE productivity_computed_report_specific
                  SET is_source = 1
                  WHERE 1=1
                      AND md_code IN (" . implode(',', $md_list) . ")
                      AND YEAR(crediting_date) = '$date'
                      AND is_source = 2
                      $sale_type
                  ";
                  $stmt = $conn_pdo->prepare($sql);
                  $stmt->execute();

                  $sql = "UPDATE productivity_computed_report
                  SET is_source = 1
                  WHERE 1=1
                        AND md_code IN (" . implode(',', $md_list) . ")
                        AND YEAR(crediting_date) = '$date'
                        AND is_checked = 1
                        AND is_source = 2
                        $other_type
                  ";
                  $stmt = $conn_pdo->prepare($sql);
                  $stmt->execute();

                  // INSERT REFERENCE SALES STEP LIST

                  $sql = "DELETE FROM reference_sales_step_list
                  WHERE 1=1
                        AND sale_type = '$this->source_type'
                  ";
                  $stmt = $conn_pdo->prepare($sql);
                  $stmt->execute();

                  $sql = "INSERT INTO reference_sales_step_list
                  (
                      sale_type,
                      step,
                      query
                  )
                  VALUES
                  (
                      :sale_type,
                      :step,
                      :query

                  )
                  ";
                  $stmt_insert = $conn_pdo->prepare($sql);

                  $stmt_insert->bindValue(":sale_type" , $this->source_type, PDO::PARAM_STR);
                  $stmt_insert->bindValue(":step" , "1", PDO::PARAM_STR);
                  $stmt_insert->bindValue(":query" , $full_query, PDO::PARAM_STR);
                  $stmt_insert->execute();

                  return 1; //SUCESSFULLY UPDATED DATA
                } else {
                    return "<br><br><center style='color: red;'><b>RETURNED QUERY VALUE SHOULD BE AN AMOUNT!</b></center>";
                }

          } else return "<br><br><center style='color: red;'><b>INVALID QUERY ENTERED! PLEASE CHECK SYNTAX!</b></center>";
>>>>>>> step_3




    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }

  // public function applyToReport()
  // {
  //   global $conn_pdo;
  //   try {
  //       $sql = "SELECT source_id, full_query
  //       FROM reference_source_list
  //       WHERE 1=1
  //             AND source_name LIKE ?
  //       GROUP BY source_id ";
  //       $stmt = $conn_pdo->prepare($sql);
  //       $stmt->execute([$this->source . "%"]);
  //       $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //       if ($data) {
  //
  //         foreach ($data as $row) {
  //            // echo $row['source_id'] . " " .$row['full_query'];
  //            $this->source = $row['full_query'];
  //         } // foreach row
  //
  //
  //
  //         $check_syntax = $this->checkQuerySyntax($this->source, $this->source);
  //         if ($check_syntax == 1) { // syntax is correct
  //               $source_sql = "";
  //               $filter_sql = explode("GROUP BY", $this->source);
  //               $source_sql = $filter_sql[0];
  //
  //               if (strpos($source_sql, "salesdata_senior_by_product") !== FALSE) {
  //                 $sale_type = " AND sale_type IN ('ON-SITE','OFF-SITE') AND sale_specific_type IN ('SENIOR') ";
  //                 $other_type = " AND sale_type IN ('ON-SITE','OFF-SITE') ";
  //                 $type = "ON-SITE";
  //                 $specific_type = "SENIOR";
  //               } elseif (strpos($source_sql, "salesdata_booked") !== FALSE) {
  //                 $sale_type = " AND sale_type = 'DISPENSING' ";
  //                 $type = "DISPENSING";
  //                 $other_type = "";
  //                 $specific_type = "";
  //               }
  //
  //               if (strpos($source_sql, "AMOUNT") !== FALSE || strpos($source_sql, "amount") !== FALSE) {
  //
  //                 $sql = "SELECT DISTINCT md_code
  //                 FROM md_profile_list_by_class as b
  //                 WHERE 1=1
  //                       AND status IN ('JEDI','IPG','PADAWAN')
  //                       AND md_code = 'G-16-002541'
  //                 ";
  //                 $data = $this->querySelect($sql);
  //
  //                 foreach ($data as $row) {
  //                     $md_code = $row['md_code'];
  //
  //                     $md_list[] = "'$md_code'";
  //                     for ($i=1; $i <= 12 ; $i++) {
  //
  //                         if ($i <= 9) {
  //                                $date = "2018-0" . $i . "-01";
  //                         } else $date = "2018-$i-01";
  //
  //                         if (strpos($source_sql, "WHERE") !== FALSE) {
  //                                $d_sql = $source_sql . " AND md_code = '$md_code' AND crediting_date = '$date' ";
  //                         } else $d_sql = $source_sql . " WHERE 1=1 AND md_code = '$md_code' AND crediting_date = '$date' ";
  //
  //                         // FETCH AMOUNT FROM QUERY
  //                         // FETCH AMOUNT FROM QUERY
  //                         $amount = 0;
  //                         $stmt = $conn_pdo->prepare($d_sql);
  //                         $stmt->execute();
  //                         $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //                         foreach ($data as $key => $row) {
  //                           foreach ($row as $key2 => $value) {
  //
  //                             if (strpos($key2, "amount") !== FALSE || strpos($key2, "AMOUNT") !== FALSE) {
  //                                    $amount = $value;
  //                             }
  //                           }
  //                         }
  //
  //
  //                         // FETCH AMOUNT FROM QUERY
  //                         // FETCH AMOUNT FROM QUERY
  //
  //                               $sql = "INSERT INTO  productivity_computed_report
  //                               (
  //                                   md_code,
  //                                   crediting_date,
  //                                   sale_type,
  //                                   total_amount,
  //                                   is_checked,
  //                                   is_source
  //                               )
  //                               VALUES
  //                               (
  //                                   :md_code,
  //                                   :crediting_date,
  //                                   :sale_type,
  //                                   :total_amount,
  //                                   :is_checked,
  //                                   :is_source
  //                               )
  //                               ";
  //                               $stmt_insert = $conn_pdo->prepare($sql);
  //
  //                               $stmt_insert->bindValue(":md_code" , $md_code, PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":crediting_date" , $date, PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":sale_type" , $type, PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":total_amount" , $amount, PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":is_checked" , "1", PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":is_source" , "2", PDO::PARAM_STR);
  //                               $stmt_insert->execute();
  //
  //
  //                               $sql = "INSERT INTO  productivity_computed_report_specific
  //                               (
  //                                   md_code,
  //                                   crediting_date,
  //                                   sale_type,
  //                                   sale_specific_type,
  //                                   total_amount,
  //                                   is_checked,
  //                                   is_source
  //                               )
  //                               VALUES
  //                               (
  //                                   :md_code,
  //                                   :crediting_date,
  //                                   :sale_type,
  //                                   :sale_specific_type,
  //                                   :total_amount,
  //                                   :is_checked,
  //                                   :is_source
  //                               )
  //                               ";
  //                               $stmt_insert = $conn_pdo->prepare($sql);
  //
  //                               $stmt_insert->bindValue(":md_code" , $md_code, PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":crediting_date" , $date, PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":sale_type" , $type, PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":sale_specific_type" , $specific_type, PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":total_amount" , $amount, PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":is_checked" , "1", PDO::PARAM_STR);
  //                               $stmt_insert->bindValue(":is_source" , "2", PDO::PARAM_STR);
  //                               $stmt_insert->execute();
  //                     } // for i
  //
  //
  //                 } // foreach data
  //
  //                 $date = '2018';
  //                 $sql = "DELETE FROM productivity_computed_report
  //                 WHERE 1=1
  //                       AND md_code IN (" . implode(',', $md_list) . ")
  //                       AND YEAR(crediting_date) = '$date'
  //                       AND is_checked = 1
  //                       AND is_source = 1
  //                       $other_type
  //                       ";
  //                 $stmt = $conn_pdo->prepare($sql);
  //                 $stmt->execute();
  //
  //                 $sql = "UPDATE productivity_computed_report
  //                 SET  is_checked = 0
  //                 WHERE 1=1
  //                       AND md_code IN (" . implode(',', $md_list) . ")
  //                       AND YEAR(crediting_date) = '$date'
  //                       AND is_checked = 1
  //                       AND is_source = 0
  //                       $other_type
  //                 ";
  //                 $stmt = $conn_pdo->prepare($sql);
  //                 $stmt->execute();
  //
  //                 $sql = "DELETE FROM productivity_computed_report_specific
  //                 WHERE 1=1
  //                       AND md_code IN (" . implode(',', $md_list) . ")
  //                       AND YEAR(crediting_date) = '$date'
  //                       AND is_checked = 1
  //                       AND is_source = 1
  //                       $sale_type
  //                 ";
  //                 $stmt = $conn_pdo->prepare($sql);
  //                 $stmt->execute();
  //
  //                 $sql = "UPDATE productivity_computed_report_specific
  //                 SET is_checked = 0
  //                 WHERE 1=1
  //                     AND md_code IN (" . implode(',', $md_list) . ")
  //                     AND YEAR(crediting_date) = '$date'
  //                     AND is_checked = 1
  //                     $sale_type
  //                 ";
  //                 $stmt = $conn_pdo->prepare($sql);
  //                 $stmt->execute();
  //
  //                 // UPDATE SOURCEC TO 1
  //                 $sql = "UPDATE productivity_computed_report_specific
  //                 SET is_source = 1
  //                 WHERE 1=1
  //                     AND md_code IN (" . implode(',', $md_list) . ")
  //                     AND YEAR(crediting_date) = '$date'
  //                     AND is_source = 2
  //                     $sale_type
  //                 ";
  //                 $stmt = $conn_pdo->prepare($sql);
  //                 $stmt->execute();
  //
  //                 $sql = "UPDATE productivity_computed_report
  //                 SET is_source = 1
  //                 WHERE 1=1
  //                       AND md_code IN (" . implode(',', $md_list) . ")
  //                       AND YEAR(crediting_date) = '$date'
  //                       AND is_checked = 1
  //                       AND is_source = 2
  //                       $other_type
  //                 ";
  //                 $stmt = $conn_pdo->prepare($sql);
  //                 $stmt->execute();
  //
  //                 return 1; //SUCESSFULLY UPDATED DATA
  //               } else {
  //                   return "<br><br><center style='color: red;'><b>RETURNED QUERY VALUE SHOULD BE AN AMOUNT!</b></center>";
  //               }
  //
  //         } else return "<br><br><center style='color: red;'><b>INVALID QUERY ENTERED! PLEASE CHECK SYNTAX!</b></center>";
  //
  //       } else return 0; // IF DATA
  //
  //
  //   } catch (PDOException $e) {
  //       throw new Exception("Connection failed: ". $e->getMessage());
  //   }
  // }

  // NOTE:: STEP 3
  // NOTE:: STEP 3

}

?>
