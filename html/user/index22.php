<!doctype html>
<html lang="en">
<?php
session_start();


if (!isset($_SESSION["username"])) {
  header("location:authentication-login.php");
}

?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>業務一把罩</title>
  <link rel="shortcut icon" type="image/png" href="../../assets/images/logos/logo-sm.png" />
  <link rel="stylesheet" href="../../assets/css/styles.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Wait for the DOM content to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
      var salesChart;

      // Fetch years from server
      fetch('../getYears.php')
        .then(response => response.json())
        .then(years => {
          var select = document.getElementById('yearDropdown');
          years.forEach(function (year) {
            var option = document.createElement('option');
            option.text = year;
            option.value = year;
            select.add(option);
          });
        })
        .catch(error => console.error('Error:', error));

      var quarterDropdown = document.getElementById('quarterDropdown');
      var monthDropdown = document.getElementById('monthDropdown');

      // Disable month dropdown if quarter is selected
      quarterDropdown.addEventListener('change', function () {
        monthDropdown.disabled = !!this.value;
      });

      // Disable quarter dropdown if month is selected
      monthDropdown.addEventListener('change', function () {
        quarterDropdown.disabled = !!this.value;
      });

      // search button click
      document.getElementById('searchButton').addEventListener('click', function () {
        fetchData();
      });

      // reset button click
      document.getElementById('resetButton').addEventListener('click', function () {
        resetForm();
      });

      //fetch data based on selected filters
      function fetchData() {
        // Get values
        var username = '<?php echo $_SESSION["username"]; ?>';
        var year = document.getElementById('yearDropdown').value;
        var quarter = document.getElementById('quarterDropdown').value;
        var month = document.getElementById('monthDropdown').value;

        // Construct the query URL with selected parameters
        var url = `getCASData.php`;
        var queryParams = [];
        if (username) queryParams.push(`id=${username}`);
        if (year) queryParams.push(`year=${year}`);
        if (quarter) queryParams.push(`quarter=${quarter}`);
        if (month) queryParams.push(`month=${month}`);

        // Fetch data and update the chart
        if (queryParams.length > 0) {
          url += '?' + queryParams.join('&');
          fetch(url)
            .then(response => response.json())
            .then(data => {
              updateChart(data); // Update chart 
            })
            .catch(error => console.error('Fetch error:', error));
        } else {
          alert("Please enter a User ID or select a Year.");
        }
      }

      // reset the form and chart
      function resetForm() {
        // Reset dropdowns and input field
        document.getElementById('yearDropdown').selectedIndex = 0;
        quarterDropdown.selectedIndex = 0;
        monthDropdown.selectedIndex = 0;
        quarterDropdown.disabled = false;
        monthDropdown.disabled = false;

        // Destroy the current chart
        if (salesChart) {
          salesChart.destroy();
        }
      }

      //create or update sales chart
      function updateChart(data) {
        var ctx = document.getElementById('salesChart').getContext('2d');

        // Destroy old chart
        if (salesChart) {
          salesChart.destroy();
        }

        // Create a new bar chart with fetched data
        salesChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.user.map(d => d.Date || `${d.Year}-${d.Month}`),
            datasets: [{
              label: 'ID: ' + (data.user.length > 0 ? data.user[0].username : 'N/A') + '(YOU)' + '',
              data: data.user.map(d => d.TotalSales),
              backgroundColor: 'rgba(255, 99, 132, 0.2)',
              borderColor: 'rgba(255, 99, 132, 1)',
              borderWidth: 1
            }, {
              label: 'TOP SALESPERSON 1' + '',
              data: data.T1.map(d => d.TotalSales),
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }, {
              label: 'TOP SALESPERSON 2' + '',
              data: data.T2.map(d => d.TotalSales),
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              borderColor: 'rgba(75, 192, 192, 1)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      }
    });
  </script>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="../../assets/images/logos/logo.png" width="180" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu"><b>業務員&關係客戶分析</b></span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.html" aria-expanded="false">
                <span>
                  <i class="ti ti-chart-dots-3"></i>
                </span>
                <span class="hide-menu">業務員&關係客戶群組</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.html" aria-expanded="false">
                <span>
                  <i class="ti ti-affiliate"></i>
                </span>
                <span class="hide-menu">業務員&招攬業務員關係群組</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu"><b>銷售業績分析</b></span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index21.php" aria-expanded="false">
                <span>
                  <i class="ti ti-chart-arrows-vertical"></i>
                </span>
                <span class="hide-menu">表現最佳的保險產品</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index22.php" aria-expanded="false">
                <span>
                  <i class="ti ti-brand-cashapp"></i>
                </span>
                <span class="hide-menu">業務員的銷售業績</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu"><b>客戶性別年齡分析</b></span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.html" aria-expanded="false">
                <span>
                  <i class="ti ti-gender-bigender"></i>
                </span>
                <span class="hide-menu">客戶性別年齡分佈</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu"><b>關係分析</b></span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.html" aria-expanded="false">
                <span>
                  <i class="ti ti-briefcase"></i>
                </span>
                <span class="hide-menu">業務員與保險關係</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu"><b>客戶互動</b></span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.html" aria-expanded="false">
                <span>
                  <i class="ti ti-calendar-time"></i>
                </span>
                <span class="hide-menu">業務員與客戶聯繫及拜訪頻率</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu"><b>產品推薦介面</b></span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./indexre.php" aria-expanded="false">
                <span>
                  <i class="ti ti-compass"></i>
                </span>
                <span class="hide-menu">客戶產品推薦</span>
              </a>
            </li>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <h5>Welcome back!
                <?php echo $_SESSION["username"] ?>
              </h5>
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="../../assets/images/profile/user-1.jpg" alt="" width="35" height="35"
                    class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-mail fs-6"></i>
                      <p class="mb-0 fs-3">My Account</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-list-check fs-6"></i>
                      <p class="mb-0 fs-3">My Task</p>
                    </a>
                    <a href="../logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
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
                      <select id="yearDropdown" class="form-select ">
                        <option value="">Select Year</option>
                      </select>
                      <select id="quarterDropdown" class="form-select ">
                        <option value="">Select Quarter</option>
                        <option value="1">Q1</option>
                        <option value="2">Q2</option>
                        <option value="3">Q3</option>
                        <option value="4">Q4</option>
                      </select>
                      <select id="monthDropdown" class="form-select ">
                        <option value="">Select Month</option>
                        <option value="1">Jan</option>
                        <option value="2">Feb</option>
                        <option value="3">Mar</option>
                        <option value="4">Apr</option>
                        <option value="5">May</option>
                        <option value="6">Jun</option>
                        <option value="7">Jul</option>
                        <option value="8">Aug</option>
                        <option value="9">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
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
        </div>
      </div>
      <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
      <script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
      <script src="../../assets/js/sidebarmenu.js"></script>
      <script src="../../assets/js/app.min.js"></script>
      <script src="../../assets/libs/simplebar/dist/simplebar.js"></script>
      <script src="../../assets/js/dashboard.js"></script>
</body>

</html>