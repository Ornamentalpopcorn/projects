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
        $syntax_to_perform = $this->translateQuery("q1", $this->source );

        if (strpos($syntax_to_perform, "INVALID") !== FALSE) { // if syntax is correct, perform query
          return '<br><center><div class="alert alert-danger" role="alert">INVALID COMBINATION OF GROUPED SALES</div></center>';
        } elseif ($syntax_to_perform) {
          $this->performComputation($syntax_to_perform);
        } else {
          return '<br><center><div class="alert alert-danger" role="alert">PLEASE CHECK QUERY COMBINATION AND/OR QUERY SYNTAX</div></center>';
        }
        // $c = 1; $pseudo_syntax = array(); $counter = 1;
        // if (strpos($this->source , "::") !== FALSE) {
        //
        //     foreach (explode("::", $this->source) as $source_sql) {
        //           $source_sql = trim($source_sql);
        //
        //           if ($source_sql[0] == "x" || $source_sql[0] == "*" || $source_sql[0] == "/" || $source_sql[0] == "+" || $source_sql[0] == "-") {
        //             $operator = $source_sql[0];
        //           }
        //
        //           if ($source_sql[0] == "x")     $query_list[] = ltrim($source_sql, "x");
        //           elseif ($source_sql[0] == "+") $query_list[] = ltrim($source_sql, "+");
        //           elseif ($source_sql[0] == "*") $query_list[] = ltrim($source_sql, "*");
        //           elseif ($source_sql[0] == "-") $query_list[] = ltrim($source_sql, "-");
        //           elseif ($source_sql[0] == "/") $query_list[] = ltrim($source_sql, "/");
        //           else {
        //             $query_list[] = $source_sql;
        //             $operator = "";
        //           }
        //
        //           // PREPARE COMPUTATION FORMULA SYNTAX
        //           $data_source_list[] = $data_source = $this->getSourceName($source_sql);
        //           $pseudo_syntax[] = str_replace( $data_source , "q" . $counter++ , $source_sql) ;
        //
        //           if ($operator) {
        //             $operator_list[] = $operator;
        //             $c = 0;
        //             $query_placement = 0;
        //             $query_syntax = "";
        //             foreach ($query_list as $query) {
        //                 ++$query_placement;
        //
        //                 if (array_key_exists($c, $operator_list)) {
        //                        $operator = $operator_list[$c];
        //                 } else $operator = "";
        //
        //                 if ($c == 0) {
        //                        $query_syntax .= $query . " " . $operator;
        //                 } else $query_syntax .= $operator . " " . $query;
        //
        //                 $this->translateQuery($query_placement, $data_source_list[$c]);
        //                 $c++;
        //             }
        //           }
        //
        //     }
        //
        // } else { // NOTE:::: NO FORMULA IS USED
        //       $this->translateQuery("q1", $this->source );
        // }
        // echo print_r($pseudo_syntax);

        // echo print_r($operator_list);
        // echo print_r($query_list);

        return 'end';

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }

  //NOTE:: ADDED IN STEP 4
  //NOTE:: ADDED IN STEP 4

  public function translateQuery($query_placement, $query)
  {
    global $conn_pdo;
    try {
    $syntax_to_perform = "";
    $query = str_replace("[[", "", $query);
    $query = str_replace("]]", "", $query);

    $sql = "SELECT source_equivalent, full_query, is_single_query
    FROM reference_source_list
    WHERE 1=1
          AND source_name LIKE ?
    GROUP BY source_id ";
    $stmt = $conn_pdo->prepare($sql);
    $stmt->execute([$query . "%"]);
    $data_query = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($data_query) {

        foreach ($data_query as $row_query) {
           $source_equivalent = $row_query['source_equivalent'] ;
        } // foreach row

        $continue = 1;
        $i = 1;
        $syntax_to_perform = $source_equivalent;
        $sub_query_placement = 1;

        foreach (explode(" ", $source_equivalent) as $source_text) {

            $sql = "SELECT source_id, full_query
            FROM reference_source_list
            WHERE 1=1
            GROUP BY source_id ";

            $data_2 = $this->querySelect($sql);
            foreach ($data_2 as $row_2) {
                $source_id = $row_2['source_id'] ;
                $sql_statement = $row_2['full_query'] ;
                if (strpos($source_text, $source_id) !== FALSE) {

                    // NOTE: DOUBLE ASTERISK (**) DENOTES RETURNED VALUE IS A SINGLE VALUE AND NOT A GROUP OF VALUES
                    // **SET VALUE = GROUP OF VALUES EX. MD 1 SALES, MD 2 SALES ETC.....
                    // ABSOLUTE VALUE = SINGLE VALUE EX. TOTAL MDC SALES

                    $position = strpos($syntax_to_perform, $source_id);
                    $is_absolute_value = $this->getSetSales($query_placement, $sub_query_placement, $source_id, $sql_statement) ;
                    if ($is_absolute_value) {
                           $syntax_to_perform = substr_replace($syntax_to_perform, "**q" . $sub_query_placement, $position, strlen($source_id));
                    } else $syntax_to_perform = substr_replace($syntax_to_perform, "q" . $sub_query_placement, $position, strlen($source_id));
                    $sub_query_placement++;

                }

            } // foreach

        }
        // echo $result = $this->performComputation($syntax_to_perform);

    } // if data query

    return $syntax_to_perform;

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }

  public function getSetSales($query_placement, $sub_query_placement, $source_id, $sql_statement)
  {
    global $conn_pdo;
    try {
      $group_by = "";

      if (strpos($sql_statement, "GROUP BY") !== FALSE) {
             $group_query = explode("GROUP BY", $sql_statement);
             $group_by = $group_query[1];
             $group_by = trim(str_replace("LIMIT 10", "", $group_by) );
             $absolute_value = 0;
      } else $absolute_value = 1;;

      $data = $this->querySelect($sql_statement) ;
      if ($data) {

          if ($sub_query_placement == 1) {

              $sql = "DELETE FROM reference_sales_step
              WHERE 1=1
                    AND sale_type = '$this->source_type'
                    -- AND step = '" . str_replace("q", "", $query_placement) . "'
                    -- AND sub_step = '" .  $sub_query_placement . "'
              ";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->execute();
          }

          foreach ($data as $key => $row) {
            foreach ($row as $key2 => $value) {
                $column_name = strtolower($key2);
                $key_values[$column_name][] = $value;
                $key_list[] = $column_name;
            }  // foreach 2
          } // foreach 1
          $key_list = array_unique($key_list);

          $new_key_list = array();
          foreach ($key_list as $key) {
             if (strpos($key, "sum") !== FALSE) {
                     $k = str_replace("sum(", "", $key);
                     $k = str_replace(")", "", $k);
                     $new_key_list[] = $k;
             } else  $new_key_list[] = $key;
          }

          $values_key_list = array();
          foreach ($key_list as $key) {
             if (strpos($key, "sum") !== FALSE) {
                     $k = str_replace("sum(", "", $key);
                     $k = str_replace(")", "", $k);
                     $values_key_list[] = ":" .$k;
             } else  $values_key_list[] = ":" . $key;
          }

          $sql = "INSERT INTO reference_sales_step
                  (
                    sale_type,
                    query,
                    step,
                    sub_step,
                    group_by,
                    " . implode(", ", $new_key_list) . "
                  )
                  VALUES
                  (
                    :sale_type,
                    :query,
                    :step,
                    :sub_step,
                    :group_by,
                    " . implode(", ", $values_key_list) . "
                  )
                  "  ;
                  $stmt_insert = $conn_pdo->prepare($sql);

                  $array_list = array();
                  for ($i=0; $i <= count($key_values[$list]) ; $i++) {
                      $arr =  array(
                          'sale_type' => $this->source_type,
                          'query' => $source_id,
                          'step' => str_replace("q", "", $query_placement),
                          'sub_step' => $sub_query_placement,
                          'group_by' => $group_by
                      );

                      foreach ($key_list as $list) {

                           $column_name = str_replace("sum(", "", $list );
                           $column_name = str_replace(")", "", $column_name);

                           if (strpos($column_name, "total_amount") !== FALSE) {
                             if ($key_values[$list][$i] > 0) $add_to_array = 1;
                             else                            $add_to_array = 0;
                           }

                           $arr2 = array(
                                $column_name => $key_values[$list][$i]
                           );
                           $arr = array_merge($arr, $arr2);
                      }
                      if ($add_to_array) {
                        $array_list[] = $arr;
                      }
                  }

                  $conn_pdo->beginTransaction();
                  foreach ($array_list as $key => $value) {
                    foreach ($value as $column_name => $column_value) {
                          $stmt_insert->bindValue(":" . $column_name , $column_value, PDO::PARAM_STR);
                    }
                    $stmt_insert->execute();
                  }
                  $conn_pdo->commit();

      }
      return $absolute_value;

    } catch (\Exception $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
    }
  }

  public function performComputation($syntax_to_perform)
  {
    global $conn_pdo;
    try {

      $perform_query = $this->ifPerformQuery();

      if ($perform_query) {
              $sales_list = array();
              $group_list = array() ;

              $sql = "SELECT sub_step, group_by
              FROM reference_sales_step
              WHERe 1=1
                    AND sale_type = '$this->source_type'
                    AND is_override = 0
              GROUP BY sub_step
              ";
              $data = $this->querySelect($sql);
              foreach ($data as $row) {
                   $sub_step = $row['sub_step'] ;
                   $grouped_by = $row['group_by'] ;

                    $sql = "SELECT *
                    FROM reference_sales_step
                    WHERE 1=1
                         AND sale_type = '$this->source_type'
                         AND sub_step = '$sub_step'
                         AND is_override = 0

                    ";
                    $data_sales = $this->querySelect($sql) ;

                    foreach ($data_sales as $row_sales) {

                        if ($grouped_by) {
                                $arr = array();
                                $group_code = array();
                                foreach ($row_sales as $key2 => $value2) {

                                  if ($key2 == "md_code" ||
                                      $key2 == "md_name" ||
                                      $key2 == "class" ||
                                      $key2 == "lba_rebate_code" ||
                                      $key2 == "branch_code" ||
                                      $key2 == "product_code" ||
                                      $key2 == "segment_code" ||
                                      $key2 == "crediting_date" ||
                                      $key2 == "total_amount"   ) {
                                      if ($value2) {
                                        $arr2 = array(
                                          $key2 => $value2
                                        );
                                        $arr = array_merge($arr, $arr2);
                                      } // if
                                  }

                                   if (strpos($grouped_by, $key2) !== FALSE ) { // if strpos
                                         $group_code[] = $value2;
                                   }
                                    // if (strpos($grouped_by, $key2) !== FALSE ) { // if strpos
                                    //      if ($value2) {
                                    //        $arr2 = array(
                                    //           $key2 => $value2
                                    //        );
                                    //        $arr = array_merge($arr, $arr2);
                                    //
                                    //      } // if
                                    //      $group_code[] = $value2;
                                    //
                                    // } else {
                                    // // } elseif ($key2 == "total_amount") {
                                    //       if ($value2) {
                                    //         $arr2 = array(
                                    //            $key2 => $value2
                                    //         );
                                    //         $arr = array_merge($arr, $arr2);
                                    //       } // if
                                    // }

                                } // foreach row sales

                                if ($group_code && implode("-",$group_code)) {
                                      $group_code = array_unique($group_code);
                                      $group_list[] = implode("-",$group_code);
                                      $sales_list[$sub_step][implode("-",$group_code)][] = $arr;
                                }

                        } else  $sales_list[$sub_step] = "**" . $row_sales['total_amount'];

                    } // data sales

              } // foreach data

              foreach ($arr as $key => $value) {
                  $column_list[] = $key;
                  $column_list_insert[] = ":" . $key;
              }

              $sql = "DELETE FROM reference_sales_source
              WHERE 1=1
                    AND sale_type = '$this->source_type'
                    AND upload_status = 0
                     ";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->execute();

              $sql = "INSERT INTO reference_sales_source
                      (
                        sale_type,
                        query,
                        " . implode(", ", $column_list) . "
                      )
                      VALUES
                      (
                        :sale_type,
                        :query,
                        " . implode(", ", $column_list_insert) . "
                      )
                      " ;
              $stmt_insert = $conn_pdo->prepare($sql);

              $group_list = array_unique($group_list);
              $conn_pdo->beginTransaction();
              foreach ($group_list as $list) {

                  $formula = $syntax_to_perform;
                  for ($i=1; $i <= substr_count($syntax_to_perform, "q") ; $i++) {
                      if (is_string($sales_list[$i]) && strpos($sales_list[$i], "**") !== FALSE) { // absolute value
                              $sales = $sales_list[$i];
                      } elseif ($sales_list[$i][$list][0]['total_amount']) {
                              $sales = $sales_list[$i][$list][0]['total_amount'];
                      } else  $sales = 0;

                      if ($sales[0] == ".")  $sales = "0" . $sales;

                      $formula = str_replace("q". $i, $sales, $formula);
                  } // for i

                  if (strpos(trim($syntax_to_perform), " ") !== FALSE) {
                    $formula = str_replace("**", "", $formula);
                    $result = $this->calculate($formula);
                  } $result = $sales;

                  foreach ($sales_list[1][$list] as $key => $value) {
                     $stmt_insert->bindValue(":sale_type" , $this->source_type, PDO::PARAM_STR);
                     $stmt_insert->bindValue(":query" , $this->source, PDO::PARAM_STR);
                     foreach ($value as $column_name => $column_value) {
                        if ($column_name == "total_amount") {
                               $stmt_insert->bindValue(":" . $column_name, $result, PDO::PARAM_STR);
                        } else $stmt_insert->bindValue(":" . $column_name, $column_value, PDO::PARAM_STR);
                        // echo $column_name . " $column_value <br>";
                     }
                     $stmt_insert->execute();
                  }

              } // foreach group list
              $conn_pdo->commit();

             return $syntax_to_perform ;
      } else return '<br><center><div class="alert alert-danger" role="alert">INVALID COMBINATION OF GROUPED SALES!</div></center>';


    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }

  // NOTE: DELETE IF NOT WILL BE USED
  // public function getSubSetSales($query_placement, $sql_statement)
  // {
  //   global $conn_pdo;
  //   try {
  //
  //     $sql = "SELECT DISTINCT source_name
  //     FROM reference_source_list
  //     WHERE 1=1
  //            AND upload_status = 1 ";
  //      $source_data = $this->querySelect($sql);
  //      if ($source_data) {
  //
  //        foreach ($source_data as $source_row) {
  //           $source_name = $source_row['source_name'];
  //
  //           if (strpos($sql_statement, $source_name) !== FALSE) {
  //
  //              $sub_query_list[] = $source_name;
  //              $sql_statement = str_replace($source_name,"" , $sql_statement);
  //
  //           }
  //
  //        }
  //
  //        if ($sub_query_list) {
  //
  //          $sub_step = 1;
  //          foreach ($sub_query_list as $query) {
  //              $key_list = array(); $array_list = array();
  //              $sql = "SELECT full_query
  //              FROM reference_source_list
  //              WHERE 1=1
  //                    AND source_name LIKE ?
  //              GROUP BY source_id ";
  //              $stmt = $conn_pdo->prepare($sql);
  //              $stmt->execute([$query . "%"]);
  //              $data_query = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //
  //                foreach ($data_query as $row_query) {
  //                   $equivalent_query = $row_query['full_query'] ;
  //                } // foreach row
  //
  //              $group_query = explode("GROUP BY", $equivalent_query);
  //              $group_by = $group_query[1];
  //
  //              $data = $this->querySelect($equivalent_query) ;
  //              if ($data) {
  //                  foreach ($data as $key => $row) {
  //
  //                    foreach ($row as $key2 => $value) {
  //
  //                        $key_values[$key2][] = $value;
  //                        $key_list[] = $key2;
  //                    }  // foreach 2
  //
  //                  } // foreach 1
  //
  //                  $sql = "DELETE FROM reference_sales_step
  //                  WHERE 1=1
  //                        AND step = '" . str_replace("q", "", $query_placement) . "'
  //                        AND sub_step = '" . str_replace("q", "", $query_placement) . ".$sub_step'
  //                   ";
  //                  $stmt = $conn_pdo->prepare($sql);
  //                  $stmt->execute();
  //
  //                  $key_list = array_unique($key_list);
  //                  for ($i=0; $i <= count($key_values[$list]) ; $i++) {
  //                    $data_set = "( '$this->source_type',
  //                                  '" . $equivalent_query . "',
  //                                  '" . str_replace("q", "", $query_placement) . "',
  //                                  '" . str_replace("q", "", $query_placement) . ".$sub_step',
  //                                  '$group_by' " ;
  //                    $c = 0;
  //
  //                    foreach ($key_list as $list) {
  //                        if ($key_values[$list][$i]) {
  //                          $data_set .= ", '" . $key_values[$list][$i] . "' ";
  //                          $c++;
  //                        }
  //                    }
  //                    $data_set .= ")";
  //                    if ($c >= 1) {
  //                      $array_list[] = $data_set ;
  //                    }
  //                  }
  //
  //                  $new_key_list = array();
  //                  foreach ($key_list as $key) {
  //                     $key = strtoupper($key);
  //                     if (strpos($key, "SUM") !== FALSE) {
  //                             $k = str_replace("SUM(", "", $key);
  //                             $k = str_replace(")", "", $k);
  //                             $new_key_list[] = $k;
  //                     } else  $new_key_list[] = $key;
  //                  }
  //
  //                   $sql = "INSERT INTO reference_sales_step
  //                   (
  //                    sale_type,
  //                    query,
  //                    step,
  //                    sub_step,
  //                    group_by,
  //                    " . implode(', ', $new_key_list) . "
  //                   )
  //                   VALUES " . implode("," , $array_list) ;
  //
  //                   $stmt = $conn_pdo->prepare($sql);
  //                   $stmt->execute();
  //              }
  //
  //              $sub_step++;
  //          } // foreach end
  //
  //        } // if sub query list
  //
  //      } // end if data
  //
  //      return true;
  //
  //   } catch (\Exception $e) {
  //     throw new Exception("Connection failed: ". $e->getMessage());
  //   }
  //
  // }

  //NOTE:: ADDED IN STEP 4
  //NOTE:: ADDED STEP 4

  // public function applyToReport()
  // {
  //   global $conn_pdo;
  //   try {
  //
  //       $c = 1; $pseudo_syntax = array();
  //       foreach (explode("::", $this->source) as $source_sql) {
  //             $source_sql = trim($source_sql);
  //
  //             if ($source_sql[0] == "x" || $source_sql[0] == "*" || $source_sql[0] == "/" || $source_sql[0] == "+" || $source_sql[0] == "-") {
  //               $operator = $source_sql[0];
  //             }
  //
  //             if ($source_sql[0] == "x")     $query_list[] = ltrim($source_sql, "x");
  //             elseif ($source_sql[0] == "+") $query_list[] = ltrim($source_sql, "+");
  //             elseif ($source_sql[0] == "*") $query_ list[] = ltrim($source_sql, "*");
  //             elseif ($source_sql[0] == "-") $query_list[] = ltrim($source_sql, "-");
  //             elseif ($source_sql[0] == "/") $query_list[] = ltrim($source_sql, "/");
  //             else {
  //               $query_list[] = $source_sql;
  //               $operator = "";
  //
  //             }
  //
  //             if ($c == 1) {
  //                    $pseudo_syntax[] = "query" . $c++ . " " . $operator;
  //             } else $pseudo_syntax[] = $operator . " query" . $c++ ;
  //
  //             if ($operator) {
  //               $operator_list[] = $operator;
  //             }
  //       }
  //
  //       $c = 0;
  //       foreach ($query_list as $query) {
  //
  //         if (array_key_exists($c, $operator_list)) {
  //                $operator = $operator_list[$c];
  //         } else $operator = "";
  //
  //         if ($c == 0) {
  //                echo $query . " " . $operator;
  //         } else echo $operator . " " . $query;
  //         $c++;
  //       }
  //       // echo print_r($operator_list);
  //       // echo print_r($query_list);
  //
  //       return 'end';
  //
  //       $full_query = $this->source;
  //
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
  //       }
  //
  //         $check_syntax = $this->checkQuerySyntax($this->source, $this->source);
  //         if ($check_syntax == 1) { // syntax is correct
  //               $source_sql = "";
  //               $filter_sql = explode("GROUP BY", $this->source);
  //               $source_sql = $filter_sql[0];
  //
  //               if (strpos($this->source_type, "Senior") !== FALSE) {
  //                 $sale_type = " AND sale_type IN ('ON-SITE','OFF-SITE') AND sale_specific_type IN ('SENIOR') ";
  //                 $other_type = " AND sale_type IN ('ON-SITE','OFF-SITE') ";
  //                 $type = "ON-SITE";
  //                 $specific_type = "SENIOR";
  //               } elseif (strpos($this->source_type, "Dispensing") !== FALSE) {
  //                 $sale_type = " AND sale_type = 'DISPENSING' ";
  //                 $type = "DISPENSING";
  //                 $other_type = "";
  //                 $specific_type = "";
  //               }
  //
  //
  //               if (strpos($source_sql, "AMOUNT") !== FALSE || strpos($source_sql, "amount") !== FALSE) {
  //
  //                 $sql = "SELECT DISTINCT md_code
  //                 FROM md_profile_list_by_class as b
  //                 WHERE 1=1
  //                       AND status IN ('JEDI','IPG','PADAWAN')
  //                       -- AND md_code = 'D-16-002031'
  //                 LIMIT 50
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
  //                     AND is_source = 0
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
  //                 // INSERT REFERENCE SALES STEP LIST
  //
  //                 $sql = "DELETE FROM reference_sales_step_list
  //                 WHERE 1=1
  //                       AND sale_type = '$this->source_type'
  //                 ";
  //                 $stmt = $conn_pdo->prepare($sql);
  //                 $stmt->execute();
  //
  //                 $sql = "INSERT INTO reference_sales_step_list
  //                 (
  //                     sale_type,
  //                     step,
  //                     query
  //                 )
  //                 VALUES
  //                 (
  //                     :sale_type,
  //                     :step,
  //                     :query
  //
  //                 )
  //                 ";
  //                 $stmt_insert = $conn_pdo->prepare($sql);
  //
  //                 $stmt_insert->bindValue(":sale_type" , $this->source_type, PDO::PARAM_STR);
  //                 $stmt_insert->bindValue(":step" , "1", PDO::PARAM_STR);
  //                 $stmt_insert->bindValue(":query" , $full_query, PDO::PARAM_STR);
  //                 $stmt_insert->execute();
  //
  //                 return 1; //SUCESSFULLY UPDATED DATA
  //               } else {
  //                   return "<br><br><center style='color: red;'><b>RETURNED QUERY VALUE SHOULD BE AN AMOUNT!</b></center>";
  //               }
  //
  //         } else return "<br><br><center style='color: red;'><b>INVALID QUERY ENTERED! PLEASE CHECK SYNTAX!</b></center>";
  //
  //   } catch (PDOException $e) {
  //       throw new Exception("Connection failed: ". $e->getMessage());
  //   }
  // }


  // NOTE:: STEP 3
  // NOTE:: STEP 3

}

?>
