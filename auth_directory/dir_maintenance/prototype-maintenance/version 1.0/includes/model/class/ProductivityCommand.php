<?Php

trait ProductivityCommand
{
    public function buildQuery($source_id, $given_id, $data_id, $sale_type, $crediting_date)
    {
      global $conn_pdo;
      try {
        $query_condition;
        $sql;
        $m = date('F', strtotime($crediting_date) ) ;
        $y = date('Y', strtotime($crediting_date) ) ;

        if ($sale_type == "mst") {

                if ($data_id) {

                      $sql = "SELECT *
                      FROM creation_source_list_data_parameters
                      WHERE 1=1
                           AND source_id = '$source_id'
                           AND data_id = '$data_id'
                      ORDER BY id ASC
                     ";

                     $data = $this->querySelect($sql);
                     if ($data) {
                        $query_condition .= " AND ";
                        $counter = 0;
                        foreach ($data as $row) {

                                if ($counter++ >= 1) {
                                  $query_condition .= $row['data_conditional_operator'] . " ";
                                }
                                $query_condition .= $row['data_parameter'] . " ";
                                $query_condition .= $row['data_parameter_constraint'] . " ";
                                if (strpos($row['data_parameter_constraint'], "IN") !== FALSE ) {
                                       $query_condition .= "(" . $this->filterText($row['data_value']) . ") ";
                                } else $query_condition .=  $this->filterText($row['data_value']) . " ";

                        } // foreach data
                        $sql = "SELECT SUM(total_amount) as amt
                        FROM salesdata_mdc_group_by_month as a
                        INNER JOIN branches_mercurydrug as b
                              ON a.branch_code = b.branch_code
                        INNER JOIN refbrgy as c
                              ON b.brgyCode = c.brgyCode
                        INNER JOIN products_list as d
                              ON a.product_code = d.B00628_prodcode
                        WHERE 1=1
                              AND MONTHNAME(crediting_date) = '$m'
                              AND YEAR(crediting_date) = '$y'
                              $query_condition
                        ";

                     }  else {

                       $sql = "SELECT SUM(total_amount) as amt
                       FROM salesdata_mdc_group_by_month as a
                       WHERE 1=1
                             AND MONTHNAME(crediting_date) = '$m'
                             AND YEAR(crediting_date) = '$y'
                       ";

                     }

                } else { // no data ID initialized

                      $sql = "SELECT SUM(total_amount) as amt
                      FROM salesdata_mdc_group_by_month as a
                      WHERE 1=1
                            AND MONTHNAME(crediting_date) = '$m'
                            AND YEAR(crediting_date) = '$y'
                      ";

                }

        } elseif ($sale_type == "senior") {

                if ($data_id) {

                      $sql = "SELECT *
                      FROM creation_source_list_data_parameters
                      WHERE 1=1
                           AND source_id = '$source_id'
                           AND data_id = '$data_id'
                      ORDER BY id ASC
                     ";

                     $data = $this->querySelect($sql);
                     if ($data) {
                        $query_condition .= " AND ";
                        $counter = 0;
                        foreach ($data as $row) {

                                if ($counter++ >= 1) {
                                  $query_condition .= $row['data_conditional_operator'] . " ";
                                }
                                $query_condition .= $row['data_parameter'] . " ";
                                $query_condition .= $row['data_parameter_constraint'] . " ";
                                if (strpos($row['data_parameter_constraint'], "IN") !== FALSE ) {
                                       $query_condition .= "(" . $this->filterText($row['data_value']) . ") ";
                                } else $query_condition .=  $this->filterText($row['data_value']) . " ";

                        } // foreach data
                        $sql = "SELECT SUM(total_amount) as amt
                        FROM salesdata_senior_precomputed_by_branch as a
                        INNER JOIN branches_mercurydrug as b
                              ON a.branch_code = b.branch_code
                        INNER JOIN refbrgy as c
                              ON b.brgyCode = c.brgyCode
                        INNER JOIN products_list as d
                              ON a.product_code = d.B00628_prodcode
                        WHERE 1=1
                              AND month = '$m'
                              AND year = '$y'
                              $query_condition
                        ";

                     }  else {

                       $sql = "SELECT SUM(total_amount) as amt
                       FROM salesdata_senior_precomputed_by_branch as a
                       WHERE 1=1
                             AND month = '$m'
                             AND year = '$y'
                       ";

                     }

                } else { // no data ID initialized

                      $sql = "SELECT SUM(total_amount) as amt
                      FROM salesdata_senior_precomputed_by_branch as a
                      WHERE 1=1
                            AND month = '$m'
                            AND year = '$y'
                      ";
                }
        }
        return $sql;

      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
    }

    public function filterText($value)
    {
      try {
          $text_array = array();
          $text = explode("," , $value);
          foreach ($text as $txt ) {
            if (strlen($txt) ) {
              $txt = stripslashes( str_replace("'", "", $txt) );
              $text_array[] = "'" . $txt . "'";

            }
          }
          return implode("," , $text_array);

      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
    }

    public function checkReferenceToUseToGetLBA($lba)
    {
      return 1;
    }

    public function selectDataSourcesList()
    {
      global $conn_pdo;
      try {
        $option;
        $sql = "SELECT data_source, data_source_display_text
        FROM creation_data_sources
        GROUP BY data_source
        ORDER BY data_source_display_text ASC
        ";
        $data = $this->querySelect($sql);
        if ($data) {
           foreach ($data as $row) {
              $option .= "<option value='" . $row['data_source'] . "'>" . ucfirst($row['data_source_display_text']) . "</option>";
           }
        }

        return $option;

      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }

    }

    public function querySelect($sql)
    {
      global $conn_pdo;
      try {
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
    }

    public function displaySourceList()
    {
      global $conn_pdo;
      try {
        $source_list;

        $sql = "SELECT source_id, source_name
        FROM creation_source_list
        WHERE 1=1
              AND upload_status = '1'
        GROUP BY source_id
        ORDER BY source_name ASC ";
        $data = $this->querySelect($sql);
        if ($data) {

          $source_list .= "<ul class='list-group list-group-flush list-group-item-action'>";
          foreach ($data as $row) {
             $source_list .= '<li class="list-group-item">
             <a href="#" class="sourceList" style="text-decoration:none"
             data-id="' . $row['source_id'] . '"><i class="fas fa-angle-right"></i> ' . $row['source_name'] . '</a></li>';
          }
          $source_list .= "</ul>";
        }

        return $source_list;

      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
    }

    public function checkStepsList()
    {
        global $conn_pdo;
        try {
            $txt = "";
            $txt .= "<h3>EDIT COMPUTATION: " . ucfirst($this->data_type) . " </h3>" ;
            $txt .= "<hr>";


            $sql = "SELECT DISTINCT step_id
            FROM creation_steps_list
            WHERE 1=1
                  AND data_type = '$this->data_type'
            ORDER BY id ASC
            ";
            $data = $this->querySelect($sql);
            if ($data) {

                  foreach ($data as $row) {

                      $txt .= "STEP " . $row['id'] ;

                  } // foreach

            } else { // if data

                      $txt .= "<center>---NO STEPS CREDITED---</center>";

            }

            return $txt;

        } catch (PDOException $e) {
            throw new Exception("Connection failed: ". $e->getMessage());
        }
    }
    //NOTE: END FUNCTION selectData

    public function deleteSource()
    {
      global $conn_pdo;
      try {

        $sql = "DELETE FROM creation_source_breakdown
        WHERE upload_status = 0
        ORDER BY id DESC
        LIMIT 1
        ";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute();

        $sql = "SELECT data_type, data_delimiter, data_value
        FROM creation_source_breakdown
        WHERE 1=1
              AND upload_status = 0
        ORDER BY id ASC
        ";
        $data = $this->querySelect($sql) ;
        if ($data) {
          $sqlText;
           foreach ($data as $row) {
              $value = "";
              $txt = explode("_", $row['data_value']) ;
              foreach ($txt as $v) $value .= ucfirst($v) ." ";

              if ($row['data_delimiter'] == "comparison" || $row['data_delimiter'] == "expression") {
                     $sqlText .= strtoupper($row['data_type']) . " " . $value  ;
              } else $sqlText .= strtoupper($row['data_type']) . " " . $value . "\n";
           }
        }
        return "sql deleted";
        // return $sqlText;


      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
    }

    public function selectReferenceList($parameter) { //
      global $conn_pdo;
      try {
        $array_result = [];
        $counter = 1;

        $sql = "SELECT md_code as m1, md_name as m2
        FROM md_profile_masterlist
        WHERE 1=1 AND md_name LIKE ?
        GROUP BY md_name
                UNION
        SELECT product_code as m1, product_name as m2
        FROM products_list
        GROUP BY product_name
                UNION
        SELECT account_code as m1, account_name as m2
        FROM accounts_booked
                UNION
        SELECT status_code as m1, status_name as m2
        FROM accounts_dispensing_status
                UNION
        SELECT lba_rebate_code as m1, lba_name as m2
        FROM lba_list
        ";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute([$parameter]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
          foreach ($data as $row) {
                $array_result[] = [
                  'code' => $counter++,
                  'name' => $row['m1'] . " -- " . $row['m2']
                ];
          }
        }

        return $array_result;

        $sql = "SELECT md_code as m1, md_name as m2
        FROM md_profile_masterlist
        WHERE 1=1 AND md_name LIKE ?
        GROUP BY md_name
        ORDER BY md_name ASC";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute([$parameter]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
          foreach ($data as $row) {
                $array_result[] = [
                  'code' => $counter++,
                  'name' => $row['m1'] . " -- " . $row['m2']
                ];
          }
        }


        $sql = "SELECT product_code as m1, product_name as m2
        FROM products_list
        ORDER BY product_name ASC";
        $data = $this->querySelect($sql);
        if ($data) {
          foreach ($data as $row) {
              $array_result[] = [
                'code' => $counter++,
                'name' => $row['m1'] . " -- " . $row['m2']
              ];
          }
        }

        $sql = "SELECT account_code as m1, account_name as m2
        FROM accounts_booked
        ORDER BY account_name ASC";
        $data = $this->querySelect($sql);
        if ($data) {
          foreach ($data as $row) {
              $array_result[] = [
                'code' => $counter++,
                'name' => $row['m1'] . " -- " . $row['m2']
              ];
          }
        }

        $sql = "SELECT status_code as m1, status_name as m2
        FROM accounts_dispensing_status
        ORDER BY status_name ASC";
        $data = $this->querySelect($sql);
        if ($data) {
          foreach ($data as $row) {
              $array_result[] = [
                'code' => $counter++,
                'name' => $row['m1'] . " -- " . $row['m2']
              ];
          }
        }

        $sql = "SELECT lba_rebate_code as m1, lba_name as m2
        FROM lba_list
        ORDER BY lba_name ASC";
        $data = $this->querySelect($sql);
        if ($data) {
          foreach ($data as $row) {
                $array_result[] = [
                  'code' => $counter++,
                  'name' => $row['m1'] . " -- " . $row['m2']
                ];
          }
        }

        return $array_result;

      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
    }


    // public function selectReferenceList() { //
    //   global $conn_pdo;
    //   try {
    //     $referenceList;
    //
    //     $sql = "SELECT md_code as m1, md_name as m2
    //     FROM md_profile_masterlist
    //     ORDER BY md_name ASC";
    //     $data = $this->querySelect($sql);
    //     if ($data) {
    //       foreach ($data as $row) {
    //           $referenceList .= "<option value='" . $row['m1'] . "'>" . $row['m1'] . " -- " . $row['m2'] . "</option>";
    //       }
    //     }
    //
    //     $sql = "SELECT product_code as m1, product_name as m2
    //     FROM products_list
    //     ORDER BY product_name ASC";
    //     $data = $this->querySelect($sql);
    //     if ($data) {
    //       foreach ($data as $row) {
    //           $referenceList .= "<option value='" . $row['m1'] . "'>" . $row['m1'] . " -- " . $row['m2'] . "</option>";
    //       }
    //     }
    //
    //     $sql = "SELECT account_code as m1, account_name as m2
    //     FROM accounts_booked
    //     ORDER BY account_name ASC";
    //     $data = $this->querySelect($sql);
    //     if ($data) {
    //       foreach ($data as $row) {
    //           $referenceList .= "<option value='" . $row['m1'] . "'>" . $row['m1'] . " -- " . $row['m2'] . "</option>";
    //       }
    //     }
    //
    //     $sql = "SELECT status_code as m1, status_name as m2
    //     FROM accounts_dispensing_status
    //     ORDER BY status_name ASC";
    //     $data = $this->querySelect($sql);
    //     if ($data) {
    //       foreach ($data as $row) {
    //           $referenceList .= "<option value='" . $row['m1'] . "'>" . $row['m1'] . " -- " . $row['m2'] . "</option>";
    //       }
    //     }
    //
    //     $sql = "SELECT lba_rebate_code as m1, lba_name as m2
    //     FROM lba_list
    //     ORDER BY lba_name ASC";
    //     $data = $this->querySelect($sql);
    //     if ($data) {
    //       foreach ($data as $row) {
    //           $referenceList .= "<option value='" . $row['m1'] . "'>" . $row['m1'] . " -- " . $row['m2'] . "</option>";
    //       }
    //     }
    //
    //     return $referenceList;
    //
    //   } catch (PDOException $e) {
    //       throw new Exception("Connection failed: ". $e->getMessage());
    //   }
    // }

    public function checkQuerySyntax($sqlText, $sql) { //
      global $conn_pdo;
      try {

          $stmt = $conn_pdo->prepare($sql);
          $stmt->execute();
          $data= $stmt->fetchAll(PDO::FETCH_ASSOC);
          if ($data) return $sqlText;

      } catch (PDOException $e) {

          $sql = "SELECT data_type, data_delimiter, data_value
          FROM creation_source_breakdown
          WHERE 1=1
                AND upload_status = 0
          ORDER BY id ASC
          ";
          $data = $this->querySelect($sql) ;
          if ($data) {
            $sqlText;
            $sql_full_text;
             foreach ($data as $row) {
                $value = "";
                $txt = explode("_", $row['data_value']) ;
                foreach ($txt as $v) $value .= ucfirst($v) ." ";

                if ($row['data_delimiter'] == "comparison" || $row['data_delimiter'] == "expression") {
                       $sqlText .= strtoupper($row['data_type']) . " " . $value  ;
                } else $sqlText .= strtoupper($row['data_type']) . " " . $value . "\n";

                $sql_full_text .= $row['data_type'] . " " . $row['data_value'] . " ";
             }
          }

          $sql = "DELETE FROM creation_source_breakdown
          WHERE upload_status = 0
          ORDER BY id DESC
          LIMIT 1
          ";
          $stmt = $conn_pdo->prepare($sql);
          $stmt->execute();

          return "-------------------------INVALID QUERY CREATED, PLEASE CHECK SYNTAX!------------------------- \n\n" . $sql_full_text;
      }
    }

    public function createNewSource() {
      global $conn_pdo;
      try {
        // NOTE: return 0 WOULD MEAN THAT THERE IS ALREADY A SOURCE WITH THE ENTERED TITLE AND WOULD CANCEL THE INSERT
        $sql = "SELECT source_name
        FROM creation_source_list
        WHERE source_name = '$this->source_name' ";
        $data = $this->querySelect($sql);
        if ($data) {
          return 0;
        } else {

          $sql = "SELECT data_type, data_delimiter, data_value
          FROM creation_source_breakdown
          WHERE 1=1
                AND upload_status = 0
          ORDER BY id ASC
          ";
          $data = $this->querySelect($sql) ;
          if ($data) {
            $sql_full_text;
             foreach ($data as $row) {
                // $value = "";
                // $txt = explode("_", $row['data_value']) ;
                // foreach ($txt as $v) $value .= ucfirst($v) ." ";
                //
                // if ($row['data_delimiter'] == "comparison" || $row['data_delimiter'] == "expression") {
                //        $sqlText .= strtoupper($row['data_type']) . " " . $value  ;
                // } else $sqlText .= strtoupper($row['data_type']) . " " . $value . "\n";

                $sql_full_text .= $row['data_type'] . " " . $row['data_value'] . " ";
             }
          }

          $sql = "INSERT INTO creation_source_list
          (
            source_id,
            source_name,
            full_query,
            upload_status
          )
          VALUES
          (
            :source_id,
            :source_name,
            :full_query,
            :upload_status
          )
          ";
          $stmt_insert = $conn_pdo->prepare($sql);

          $source_id = md5( rand(0,892281) . time() . rand(0,37821739821) );

          $stmt_insert->bindValue(":source_id" , $source_id, PDO::PARAM_STR);
          $stmt_insert->bindValue(":source_name" , $this->source_name, PDO::PARAM_STR);
          $stmt_insert->bindValue(":full_query" , $sql_full_text, PDO::PARAM_STR);
          $stmt_insert->bindValue(":upload_status" , '1', PDO::PARAM_STR);
          $stmt_insert->execute();

          $sql = "UPDATE creation_source_breakdown
          SET source_id = '$source_id',
              upload_status = 1
          WHERE 1=1
                AND upload_status = 0 ";
          $stmt = $conn_pdo->prepare($sql);
          $stmt->execute();

          return 1;
        }

      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
    }


    public function insertSource() {
      global $conn_pdo;
      try {

        $sql = "INSERT INTO creation_source_breakdown
        (
            data_type,
            data_delimiter,
            data_value
        )
        VALUES
        (
          :data_type,
          :data_delimiter,
          :data_value
        )
        ";
        $stmt_insert = $conn_pdo->prepare($sql);

        if ($this->data_action == "select") {
          $stmt_insert->bindValue(":data_type" , $this->data_action, PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_delimiter" , "data", PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_value" , $this->data_source, PDO::PARAM_STR);
          $stmt_insert->execute();
        } elseif ($this->data_action == "where" || $this->data_action == "and" || $this->data_action =="or" ) {
          $stmt_insert->bindValue(":data_type" , $this->data_action, PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_delimiter" , "condition", PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_value" , $this->data_source , PDO::PARAM_STR);
          $stmt_insert->execute();

        } elseif ($this->data_action == "group by") {
          $stmt_insert->bindValue(":data_type" , $this->data_action, PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_delimiter" , "group", PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_value" , $this->data_source, PDO::PARAM_STR);
          $stmt_insert->execute();

        } elseif (strpos($this->data_action, "=") !== FALSE ) {
          $stmt_insert->bindValue(":data_type" , $this->data_action, PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_delimiter" , "comparison", PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_value" ,  $this->filterText( $this->data_source), PDO::PARAM_STR);
          $stmt_insert->execute();
        } elseif (strpos($this->data_action, "in") !== FALSE) {
          $stmt_insert->bindValue(":data_type" , $this->data_action, PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_delimiter" , "expression", PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_value" , "(" . $this->filterText( $this->data_source) . ")", PDO::PARAM_STR);
          $stmt_insert->execute();

        }


        //NOTE: INSERT ONLY ONCE TABLE DATA HAS ALREADY BEEN INSERTED
        $sql = "SELECT DISTINCT data_delimiter
        FROM creation_source_breakdown
        WHERE data_delimiter = 'table'
        ";
        $data = $this->querySelect($sql);
        if (!$data) {
          $stmt_insert->bindValue(":data_type" , "from", PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_delimiter" , "table", PDO::PARAM_STR);
          $stmt_insert->bindValue(":data_value" , $this->data_table, PDO::PARAM_STR);
          $stmt_insert->execute();
        }

        $sql = "SELECT data_type, data_delimiter, data_value
        FROM creation_source_breakdown
        WHERE 1=1
              AND upload_status = 0
        ORDER BY id ASC
        ";
        $data = $this->querySelect($sql) ;
        if ($data) {
          $sqlText;
          $sql_full_text;
           foreach ($data as $row) {
              $value = "";
              $txt = explode("_", $row['data_value']) ;
              foreach ($txt as $v) $value .= ucfirst($v) ." ";

              if ($row['data_delimiter'] == "comparison" || $row['data_delimiter'] == "expression") {
                     $sqlText .= strtoupper($row['data_type']) . " " . $value  ;
              } else $sqlText .= strtoupper($row['data_type']) . " " . $value . "\n";

              $sql_full_text .= $row['data_type'] . " " . $row['data_value'] . " ";
           }
        }

        // CHECKS QUERY

        return $this->checkQuerySyntax($sqlText, $sql_full_text);


      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
    }


}

?>
