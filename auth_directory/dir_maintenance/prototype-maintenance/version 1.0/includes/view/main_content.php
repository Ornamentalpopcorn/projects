<section class="content">
  <!-- Main row -->
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">

      <div class="box box-widget">

        <div class="box-body">

          <div class="user-block">

            <div class="form-group">

              <select class="form-control" id="dataType">
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

          <div class="col-md-12">

            <hr>

            <div id="dataList">


            </div>

          </div>

          <a href="#"
          data-toggle="modal"
          data-target="#viewDataSource"
          id="addDataSource" class="btn btn-primary" style="float:left"><i class="fas fa-plus"></i> ADD DATA SOURCE</a>
        </div>
      </div>

    </div>
  </div>
  <!-- /.row (main row) -->


</section>
