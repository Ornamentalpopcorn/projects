<section class="content">
  <!-- Main row -->
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">

      <div class="box box-widget">

        <div class="box-body">

          <div class="user-block">

            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#m1">Data Sources</a></li>
              <li><a data-toggle="tab" href="#m2">Edit Productivity Report</a></li>
            </ul>

            <div class="tab-content">
              <div id="m1" class="tab-pane fade in active">
                <h3>HOME</h3>
                <p>Some content.</p>
              </div>
              <div id="m2" class="tab-pane fade">
                <h3>Menu 1</h3>
                <p>Some content in menu 1.</p>
              </div>
            </div>


            <div class="row">
              <div class="col-md-6">

                  <input  style="width: 500px !important" class="form-control js-states" id="referenceSources" >
              </div>
              <div class="col-md-6">
                  <!-- <a href="#"
                  data-toggle="modal"
                  data-target="#viewDataSource"
                  id="addDataSource" class="btn btn-primary btn btn-xs" style="float:right; margin-bottom:3px;"><i class="fas fa-plus"></i> ADD DATA SOURCE</a> -->
              </div>
            </div>
            <br>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12">

                  <select class="form-control" id="dataType" style="display:none">
                    <option disabled selected>Select Data...</option>
                    <option value="dispensing">Dispensing Sales</option>
                    <option value="tagged">Tagged Sales</option>
                    <option value="senior">MDC Senior Citizen Sales</option>
                    <option value="non-senior">MDC Non-Senior Citizen Sales</option>
                    <option value="other area with actual">Other Area Sales W/ Actual Data</option>
                    <option value="other area without actual">Other Area Sales W/O Actual Data</option>
                 </select>

                </div>
            </div>

            <div class="row">
              <div class="col-md-12">

                <?Php

                $sql = "SELECT source_type, full_query
                FROM reference_source_list
                WHERE id = '9'
                ";
                $stmt = $conn_pdo->prepare($sql);
                $stmt->execute();
                $data= $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($data as $row) {

                    $source_type = $row['source_type'];
                    if ($source_type =="source") {
                      $query = $row['full_query'];
                      if ( strpos($query, "+") !== FALSE  ) {

                          // $source_id =

                      } elseif (strpos($query, "-") !== FALSE ) {
                      } elseif (strpos($query, "/") !== FALSE ) {
                      } elseif (strpos($query, "*") !== FALSE ) {

                      }

                    }

                }
                ?>

              </div>
            </div>

          </div>

          <div class="col-md-4">

            <div id="sourceList-main" style="max-height:60vh; overflow-y:auto;">

              <center>
              <a href="#"
              id="addNewSource" class="btn btn-primary btn btn-xs" style="margin-bottom:3px"><i class="fas fa-plus"></i> ADD DATA SOURCE</a>
              </center>


              <?Php

              $sourceList = $productivity_class->displaySourceList();
              echo $sourceList;

              ?>

            </div>

          </div>

            <div class="col-md-8">

              <div id="dataList" style="display:none">

                <?Php

                $txt = "<br>";
                $txt .= "<h3>ADD NEW DATA SOURCE</h3>";
                $txt .= '<input type="text" id="data-title" class="form-control" name="source-name" value="" required="required" placeholder="Source Title">';
                $txt .= "<textarea id='queryText' placeholder='Select SUM(amount), md_code FROM source_table' name='queryText' class='form-control' rows='6' cols='120'></textarea>";



                $txt .= "<div class='clearfix'></div>";
                      $txt .= "<div class='form-inline' style='margin-top: 5px;'>";
                      $txt .= '<input type="text" id="data-mdcode" class="form-control" name="source-name" value="" required="required" placeholder="MD CODE">';


                      $txt .= '<input max="12" type="hidden" id="data-monthnum" class="form-control" name="source-name" value="" required="required" placeholder="2018 MONTH NUMBER">';

                      $txt .= "<a href='#' id='data-displayvalue' class='btn btn-info btn btn-xs' style='margin:3px; float:right;'><i class='fas fa-poll-h'></i> DISPLAY VALUE</a>";
                      $txt .= "<a href='#' id='data-save' class='btn btn-success btn btn-xs' style='margin:3px; float:right;'><i class='fas fa-save'></i> SAVE SOURCE</a>";
                      $txt .= "<a href='#' id='data-apply' class='btn btn-primary btn btn-xs' style='margin:3px; float:right;'><i class='fas fa-marker'></i></i> APPLY TO REPORT</a>";
                      $txt .= "</div'>";
                $txt .= "<div id='displayResult'></div>";
                $txt .= "</div>";
                echo $txt;

                ?>
            </div>
            <div id="sourceType" style="display:none">new</div>
            <div id="checkReportChanges"></div>
          </div>


        </div>
      </div>

    </div>
  </div>
  <!-- /.row (main row) -->

</section>

<!-- MODAL -->
<!-- MODAL -->
<!-- MODAL -->

<div id="viewDataSource" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
    data-backdrop="static"   > <div class="modal-dialog"  style="width:90%; overflow-y: auto;">
      <div class="modal-content"   >

        <div class="modal-header" style="background-color:#3c8dbc  ;">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style='color:white;'>×</button>
          <h4 class="modal-title" id="myModalLabel" style='color: white !important'><i class="fas fa-info"></i> Add Data Sources</h4>

        </div>

        <div class="modal-body"  style="max-height:80vh;overflow-y: auto;overflow-x:hidden;">

            <div class="col-md-2" id="sourceList" style="max-height:60vh; overflow-y:auto;">

                <?Php
                $sourceList = $productivity_class->displaySourceList();
                echo $sourceList;
                ?>

            </div>

            <div class="col-md-10" id="editList">

              <div class="row">
                <div class="col-md-4">
                  <input type="text" id="sourceTitle" class="form-control" name="source-name" value="" required="required" placeholder="Enter Source Title...">

                </div>
                <div class="col-md-4">
                  <select class="form-control" name="source" id="dataSource" >
                    <option disabled value="d">Select Source Type</option>
                    <option selected value="query">Free Query</option>
                    <option value="source">Data Source</option>
                  </select>
                </div>
              </div>

                <div class="row">

                  <div class="col-md-8">

                    <textarea class="form-control input-disabled" placeholder="Ex. SELECT SUM(AMOUNT) FROM TABLE " name="name" rows="6" cols="120" style="margin-bottom:10px" id="sqlTextArea"></textarea>


                  </div>

                  <div class="col-md-4 well well-sm" id="equivalentSql" style=" margin-top: 7px; font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif; ">

                    <!-- <textarea class="form-control input-disabled" name="name" rows="6" cols="120" readonly style="margin-bottom:10px" id="sqlText"></textarea> -->
                  </div>

                </div>

                <div class="row">

                    <div class="form-group col-md-6" id="suggestionsTab"  >
                        <!-- <a href='#' class="badge badge-secondary">Secondary</a>
                        <a href='#' class="badge badge-secondary">Secondary</a>
                        <a href='#' class="badge badge-secondary">Secondary</a>
                        <a href='#' class="badge badge-secondary">Secondary</a>
                        <a href='#' class="badge badge-secondary">Secondary</a>
                        <a href='#' class="badge badge-secondary">Secondary</a> -->
                    </div>

                    <div id="testText"></div>

                </div>

                <div class="row" >
                  <div class="col-md-12" id="errorMessage">
                    <!-- <div class="alert alert-danger" role="alert">
                      <strong>Oh snap!</strong> Change a few things up and try submitting again.
                    </div> -->
                  </div>
                </div>

            </div>


        </div>

        <div class="modal-footer">
          <div class="col-md-9">

              <!-- <select style="width:100%" class="form-control js-states" id="referenceSources" > -->

              <!-- <input  style="width: 500px !important" class="form-control js-states" id="referenceSources" > -->

                <!-- <option selected disabled value="">Check Reference..</option> -->
                <?Php // echo $productivity_class->selectReferenceList();
                ?>
              <!-- </select> -->
          </div>

          <a href="#" id="createSource" class="btn btn-success pull-right" name="button"><i class="fas fa-plus"></i> Create Source</a>

        </div>
    </div>
  </div>
</div>

<!-- MODAL -->
<!-- MODAL -->
