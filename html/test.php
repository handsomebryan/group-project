<div class="container-fluid">
  <!--  Row 1 -->
  <div class="row">
    <div class="col-lg-8 d-flex align-items-strech">
      <div class="card w-100">
        <div class="card-body">
          <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
            <div class="mb-3 mb-sm-0">
              <h5 class="card-title fw-semibold">業務員的銷售業績</h5>
            </div>
          </div>
          <div class="form-inline">
            <div class="form-group">
              <div class="input-group">
                <input type="text" class="form-control" id="s_id" placeholder="業務員序號(後5碼)">
                <select id="c_id" class="form-select ">
                  <option value="">Total Statistic</option>
                </select>
                <button id="searchButton" type="button" class="btn btn-outline-primary">Search</button>
                <button id="resetButton" type="button" class="btn btn-outline-danger">Reset</button>
              </div>
            </div>
          </div>
          <canvas id="salesChart" width="400" height="200"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="row">
        <div class="col-lg-12">
          <!-- Yearly Breakup -->
          <div class="card overflow-hidden">
            <div class="card-body p-4">
              <h5 class="card-title mb-9 fw-semibold">Total Visits Frequency</h5>
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 id="totalVisit"></h3>
                </div>
                <div class="col-4">
                  <div class="d-flex justify-content-center">
                    <div id="breakup"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <!-- Monthly Earnings -->
          <div class="card">
            <div class="card-body">
              <div class="row alig n-items-start">
                <div class="col-8">
                  <h5 class="card-title mb-9 fw-semibold"> Total Contacts Frequency </h5>
                  <h3 class="fw-semibold mb-3" id="totalContact"></h3>
                </div>
                <div class="col-4">
                  <div class="d-flex justify-content-end">
                    <div
                      class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                      <i class="ti ti-currency-dollar fs-6"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>