<?Php

trait ProductivityCommand
{
  // NOTE:*******************************************START***************************************************************
  // NOTE:***************************************************************************************************************
  // NOTE:****************************************VERSION 2.0************************************************************
  // NOTE:***************************************************************************************************************
  // NOTE:*******************************************************************************************************STEP 1-2
  // NOTE:*******************************************************************************************************STEP 1-2
  public function formatPhrase()
  {
    global $conn_pdo;
    try {
        $array_phrase = [];
        foreach (explode(" ", $this->phrase) as $phrase) {
             $filtered_phrase = str_replace("_", " ", $phrase );
             $filtered_phrase = str_replace("'", "", $phrase );

             $format_text = 0;

             $sql = "SELECT displayed_value
             FROM reference_productivity_intellisense
             WHERE 1=1
                  AND upload_status = 1
                  AND displayed_value = '$filtered_phrase'
             ";
             $data = $this->querySelect($sql);
             if ($data) $format_text = 1;

             $sql = "SELECT displayed_value
             FROM reference_productivity_table
             WHERE 1=1
                  AND upload_status = 1
                  AND displayed_value = '$filtered_phrase'
             ";
             $data = $this->querySelect($sql);
             if ($data) $format_text = 1;

             $sql = "SELECT displayed_value
             FROM reference_productivity_column
             WHERE 1=1
                  AND upload_status = 1
                  AND displayed_value = '$filtered_phrase'
             ";
             $data = $this->querySelect($sql);
             if ($data) $format_text = 1;

             if ($format_text) { // format text
                    $array_phrase[] = "<b style='color:#6767d2;'>" . $filtered_phrase . "</b>";
             } else $array_phrase[] = $filtered_phrase;

        }
        return implode(" ", $array_phrase);

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }
  public function searchPhrase()
  {
    global $conn_pdo;
    try {
      $phrase_array = [];

      if ($this->source == "query") {

            $sql = "SELECT DISTINCT displayed_value
            FROM reference_productivity_intellisense
            WHERE 1=1
                  AND upload_status = 1
                  AND equivalent_value LIKE '%$this->phrase%'
                    UNION
            SELECT DISTINCT displayed_value
            FROM reference_productivity_table
            WHERE 1=1
                  AND upload_status = 1
                  AND displayed_value LIKE '%$this->phrase%'
                    UNION
            SELECT DISTINCT displayed_value
            FROM reference_productivity_column
            WHERE 1=1
                  AND upload_status = 1
                  AND displayed_value LIKE '%$this->phrase%'

             ";
            $data = $this->querySelect($sql);
            if ($data) {
              foreach ($data as $row) {
                $phrase_array[] = "<a href='#' data-value='" . str_replace(" ", "_", strtoupper($row['displayed_value']) ) . "' class=\"suggested-word badge badge-secondary\">" . strtoupper(  $row['displayed_value'])  . "</a>";
              }
            }

    } else {

            $sql = "SELECT source_id, source_name
            FROM reference_source_list
            WHERE 1=1
                  AND upload_status = '1'
            GROUP BY source_id
            ";
            $data = $this->querySelect($sql);
            if ($data) {
              foreach ($data as $row) {
                $phrase_array[] = "<a href='#' data-value='" . $row['source_id'] . "' class=\"suggested-word badge badge-secondary\">" . strtoupper(  $row['source_name'])  . "</a>";
              }
            }

    }

      return implode(" ", $phrase_array);
    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }
  public function addToTxtFile($auth_code)
  {
    global $conn_pdo;
    try {

      // if ($this->word_type == "suggested") {
      //     $txt_file = fopen("../../" . $auth_code . ".txt", "w") or die('Unable to open file');
      //     fwrite($txt_file, $this->word);
      //     fclose($txt_file);
      //
      // } else {
      //
      //     if (file_exists("../../"  . $_SESSION['auth_usercode'] . ".txt")) {
      //
      //       $txt_file = file_put_contents(
      //         "../../" . $auth_code . ".txt",
      //         " " . $this->word .PHP_EOL ,
      //         FILE_APPEND | LOCK_EX
      //       );
      //
      //     } else {
            $txt_file = fopen("../../" . $auth_code . ".txt", "w") ;
            fwrite($txt_file, $this->word);
            fclose($txt_file);
      //     }
      // }


      return $this->word;

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }

  public function createNewSource($auth_code) {
    global $conn_pdo;
    try {
      $previous_word = "";
      $full_query = [];
      $array_list = array("IN", "NOT IN", "=", "!=", "=<", ">=", ">", "<") ;
      // NOTE: return 0 WOULD MEAN THAT THERE IS ALREADY A SOURCE WITH THE ENTERED TITLE AND WOULD CANCEL THE INSERT
      $sql = "SELECT source_name
      FROM reference_source_list
      WHERE source_name = '$this->source_name' ";
      $data = $this->querySelect($sql);
      if ($data) { return 0; // IF NAME ALREADY EXISTS RETURN FALSE
      } else {

        $fh = fopen("../../" . $auth_code . ".txt" ,'r');
        while ($line = fgets($fh)) {
            foreach (explode(" ", $line) as $word) {
                  $phrase = str_replace("_", " ", $word);


                  if (in_array($previous_word, $array_list) ) {
                     $word = str_replace("(", "", $word);
                     $word = str_replace(")", "", $word);
                     $word = "(" . $this->filterText($word) . ")";
                  } else {

                    $phrase = str_replace("'", "", $phrase);
                    $sql = "SELECT table_name
                    FROM reference_productivity_table
                    WHERE displayed_value = '$phrase'
                    ";
                    $data = $this->querySelect($sql);
                    if ($data) {
                      foreach ($data as $row)
                      $word = $row['table_name'];
                    }

                    $sql = "SELECT column_name
                    FROM reference_productivity_column
                    WHERE displayed_value = '$phrase'
                    ";
                    $data = $this->querySelect($sql);
                    if ($data) {
                      foreach ($data as $row)
                      $word = $row['column_name'];
                    }

                    $sql = "SELECT equivalent_value
                    FROM reference_productivity_intellisense
                    WHERE displayed_value = '$phrase'
                    ";
                    $data = $this->querySelect($sql);
                    if ($data) {
                      foreach ($data as $row)
                      $word = $row['equivalent_value'];
                    }
                  }

                  if (strpos($word, "AMOUNT") !== FALSE) $word = str_replace("AMOUNT", "TOTAL_AMOUNT", $word);

                  $previous_word = $word;
                  $word = str_replace("equals", "=", $word);
                  $full_query[] = $word;
            } // foreach line
        }
      $query = implode(" ", $full_query);
      fclose($fh);

      if ($this->source == "query") $check_syntax = $this->checkQuerySyntax($query, $query );
      else $check_syntax = 1;

      // $check_syntax=0;

       if ($check_syntax == 1) { // if SQL query is correct

             $sql = "INSERT INTO reference_source_list
             (
               source_id,
               source_type,
               source_name,
               full_query,
               upload_status
             )
             VALUES
             (
               :source_id,
               :source_type,
               :source_name,
               :full_query,
               :upload_status
             )
             ";
             $stmt_insert = $conn_pdo->prepare($sql);

             $source_id = md5( rand(0,892281) . time() . rand(0,21321) );

             $stmt_insert->bindValue(":source_id" , $source_id, PDO::PARAM_STR);
             $stmt_insert->bindValue(":source_type" , $this->source_type, PDO::PARAM_STR);
             $stmt_insert->bindValue(":source_name" , $this->source_name, PDO::PARAM_STR);
             $stmt_insert->bindValue(":full_query" , $query, PDO::PARAM_STR);
             $stmt_insert->bindValue(":upload_status" , '1', PDO::PARAM_STR);
             $stmt_insert->execute();

             unlink("../../$auth_code". ".txt");

         return 1;
       } else { // incorrect SYNTAX

         return '<center><div class="alert alert-danger" role="alert">' . $check_syntax . '</div></center>';
       }

      }

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }


  // NOTE:***************************************************************************************************************
  // NOTE:****************************************VERSION 2.0************************************************************
  // NOTE:***************************************************************************************************************
  // NOTE:***********************************************END*************************************************************
  // NOTE:*******************************************************************************************************STEP 1-2
  // NOTE:*******************************************************************************************************STEP 1-2


  // NOTE:*******************************************************************************************************STEP 3
  // NOTE:*******************************************************************************************************STEP 3
  public function displaySourceInfo()
  {
    global $conn_pdo;
    try {

      $sql = "SELECT source_name, full_query
      FROM reference_source_list
      WHERE source_id = '$this->source_id'
      ";
      $data = $this->querySelect($sql);
      foreach ($data as $row) {
         $title = $row['source_name'];
         $sql = $row['full_query'];
      }

      $txt = "<br>";
      $txt .= "<h3>EDIT DATA SOURCE</h3>";
      $txt .= '<input type="text" id="data-title" class="form-control" name="source-name" value="' . $title . '" required="required" placeholder="Source Title">';
      $txt .= "<textarea id='queryText' placeholder='Select SUM(amount), md_code FROM source_table' name='queryText' class='form-control' rows='6' cols='120'>$sql</textarea>";


      $txt .= "<div class='clearfix'></div>";
      $txt .= "<div class='form-inline' style='margin-top: 5px;'>";
      $txt .= '<input type="text" id="data-mdcode" class="form-control" name="source-name" value="" required="required" placeholder="MD CODE">';


      $txt .= '<input max="12" type="hidden" id="data-monthnum" class="form-control" name="source-name" value="" required="required" placeholder="2018 MONTH NUMBER">';

      $txt .= "<a href='#' id='data-displayvalue' class='btn btn-info btn btn-xs' style='margin:3px; float:right;'><i class='fas fa-poll-h'></i> DISPLAY VALUE</a>";
      $txt .= "<a href='#' id='data-save' class='btn btn-success btn btn-xs' style='margin:3px; float:right;'><i class='fas fa-save'></i> SAVE SOURCE</a>";
      // $txt .= "<a href='#' id='data-apply' class='btn btn-primary btn btn-xs' style='margin:3px; float:right;'><i class='fas fa-marker'></i></i> APPLY TO REPORT</a>";
      $txt .= "</div'>";
      $txt .= "<div id='displayResult'></div>";
      $txt .= "</div>";
      return $txt;

    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }
  // NOTE:*******************************************************************************************************STEP 3
  // NOTE:*******************************************************************************************************STEP 3


    public function saveSource()
    {
      global $conn_pdo;
      try {
        $parameters = array_merge([$this->source_title], [$this->source]);

        $sql = "SELECT source_name
        FROM reference_source_list
        WHERE 1=1
              AND source_name = ?
              AND full_query = ?
        ";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute($parameters);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($data) { // if source already exists
               return '<br><center><div class="alert alert-danger" role="alert">Source Already Exists!</div></center>';
        } else {

              $check_syntax = $this->checkQuerySyntax($this->source, $this->source);
              if ($check_syntax == 1) {

                  if ($this->source_type == "new") {

                        $sql = "INSERT INTO reference_source_list
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

                        $source_id = md5( rand(0,892281) . time() . rand(0,21321) );

                        $stmt_insert->bindValue(":source_id" , $source_id, PDO::PARAM_STR);
                        $stmt_insert->bindValue(":source_name" , $this->source_title, PDO::PARAM_STR);
                        $stmt_insert->bindValue(":full_query" , $this->source, PDO::PARAM_STR);
                        $stmt_insert->bindValue(":upload_status" , '1', PDO::PARAM_STR);
                        $stmt_insert->execute();

                  } else { // update

                      $source_id = $this->source_type;
                      $sql = "UPDATE reference_source_list
                      SET source_name = ?,
                          full_query = ?
                      WHERE source_id = '$source_id'
                      ";
                      $stmt = $conn_pdo->prepare($sql);
                      $stmt->execute($parameters);
                     ;
                  }

                return '<br><center><div class="alert alert-success" role="alert">Source Successfully Created!</div></center>';
              } else {
                return '<br><center><div class="alert alert-danger" role="alert">' . $check_syntax . '</div></center>';
              }





        }



        return $this->source . " " . $this->source_title;

      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }

    }

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
        $sql = "SELECT displayed_value, table_name
        FROM reference_productivity_table
        GROUP BY data_source
        ORDER BY data_source_display_text ASC
        ";
        $data = $this->querySelect($sql);
        if ($data) {
           foreach ($data as $row) {
              $option .= "<option value='" . $row['table_name'] . "'>" . ucfirst($row['displayed_value']) . "</option>";
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
        // return $stmt->fetchAll();

      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
    }

    public function displaySourceList()
    {
      global $conn_pdo;
      try {
        $source_list = "";

        $sql = "SELECT source_id, source_name
        FROM reference_source_list
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

            $txt .= "<textarea id='queryText' name='queryText' class='form-control' rows='6' cols='120'>SELECT MD_CODE, MD_NAME, SUM(TOTAL_AMOUNT) FROM salesdata_senior_by_product</textarea>";
            // $txt .= "<textarea id='demoQuery' name='demoQuery' class='form-control' rows='6' cols='120'>SELECT SUM(TOTAL AMOUNT) FROM SENIOR SALES WHERE MD CODE = ? AND MONTH = ? </textarea>";


            $txt .= "<div class='clearfix'></div>";
            $txt .= "<div class='form-inline' style='margin-top: 5px;'>";
            // $txt .= "<div class='col-md-4'>";
            $txt .= '<input type="text" id="data-mdcode" class="form-control" name="source-name" value="" required="required" placeholder="MD CODE">';
            // $txt .= "</div'>";
            // $txt .= "<div class='col-md-4'>";
            // $txt .= "<select class='form-control' name='mmm' id='data-monthnum' >";
            // $txt .= "<option disabled value='0'>Select Month (2018)..</option>";
            // for ($i=1; $i <= 12 ; $i++) {
            //   $txt .= "<option value='$i'>$i</option>";
            // }
            // $txt .= "</select>";

            $txt .= '<input max="12" type="hidden" id="data-monthnum" class="form-control" name="source-name" value="" required="required" placeholder="2018 MONTH NUMBER">';

            // $txt .= "</div'>";
            // $txt .= "<div class='col-md-4'>";
            $txt .= "<a href='#' id='data-displayvalue' class='btn btn-info btn btn-xs' style='margin:3px; float:right;'><i class='fas fa-poll-h'></i> DISPLAY VALUE</a>";
            $txt .= "<a href='#' id='data-save' class='btn btn-success btn btn-xs' style='margin:3px; float:right;'><i class='fas fa-save'></i> SAVE SOURCE</a>";
            // $txt .= "<a href='#' id='data-apply' class='btn btn-primary btn btn-xs' style='margin:3px; float:right;'><i class='fas fa-marker'></i></i> APPLY TO REPORT</a>";
            $txt .= "</div'>";
            $txt .= "<div id='displayResult'></div>";
            $txt .= "</div>";
            return $txt;

        } catch (PDOException $e) {
            throw new Exception("Connection failed: ". $e->getMessage());
        }
    }
    //NOTE: END FUNCTION selectData


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
          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if ($data) return 1;
          else return 1; // SQL is valid but just didn't return value, therefore, still push through

      } catch (PDOException $e) {
          return "-------------------------INVALID QUERY, PLEASE CHECK SYNTAX!------------------------- \n\n"  ;
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
