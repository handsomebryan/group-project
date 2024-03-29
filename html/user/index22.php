<!doctype html>
<html lang="en">
<?php
session_start();


if (!isset ($_SESSION["username"])) {
  header("location:authentication-login.php");
}

?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>業務一把罩</title>
  <link rel="shortcut icon" type="image/png" href="../../assets/images/logos/logo-sm.png" />
  <link rel="stylesheet" href="../../assets/css/styles.min.css" />
  <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0-rc.1/chartjs-plugin-datalabels.min.js"
    integrity="sha512-+UYTD5L/bU1sgAfWA0ELK5RlQ811q8wZIocqI7+K0Lhh8yVdIoAMEs96wJAIbgFvzynPm36ZCXtkydxu1cs27w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    // Wait for the DOM content to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
      var salesChart;

      // Fetch years from server
      fetch('../get/getYears.php')
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

      // Fetch quarters and months when a year is selected
      document.getElementById('yearDropdown').addEventListener('change', function () {
        var year = this.value;

        if (!year) {
          alert('Please select a year.');
          return;
        }
      });

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
        var id = '<?php echo $_SESSION["username"] ?>';
        var year = document.getElementById('yearDropdown').value;
        var quarter = document.getElementById('quarterDropdown').value;
        var month = document.getElementById('monthDropdown').value;

        // Construct the query URL with selected parameters
        var url = `../get/getCASData.php`;
        var queryParams = [];
        if (id) queryParams.push(`id=${id}`);
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
        document.getElementById('quarterDropdown').innerHTML = '<option value="">季度</option>';
        document.getElementById('monthDropdown').innerHTML = '<option value="">月份</option>';
        document.getElementById('quarterDropdown').innerHTML = '<option value="">季度</option>';
        document.getElementById('monthDropdown').innerHTML = '<option value="">月份</option>';
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
              label: '您 (業務員序號: ' + (data.user.length > 0 ? data.user[0].業務員序號.slice(-5) : 'N/A') + ')',
              data: data.user.map(d => d.TotalSales),
              backgroundColor: 'rgba(255, 99, 132, 0.2)',
              datalabels: {
                color: 'black',
                align: 'right'
              }
            }, {
              label: '銷量第一名',
              data: data.T1.map(d => d.TotalSales),
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              datalabels: {
                color: 'black',
                align: 'right'
              }
            }, {
              label: '銷量第二名',
              data: data.T2.map(d => d.TotalSales),
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              datalabels: {
                color: 'black',
                align: 'right'
              }
            }]
          },
          plugins: [ChartDataLabels],
          options: {
            indexAxis: 'y',
            aspectRatio: 1,
            scales: {
              x: {
                title: {
                  display: true,
                  text: '銷售額',
                  color: 'black',
                  weight: 'bold'
                }
              },
              y: {
                title: {
                  display: true,
                  text: '日期',
                  color: 'black',
                  weight: 'bold'
                },
                beginAtZero: true,
              }
            }
          }
        });
      }
    });
  </script>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <img src="../../assets/images/logos/logo.png" width="180" alt="" />
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
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
              <a class="sidebar-link" href="./index3.php" aria-expanded="false">
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
              <a class="sidebar-link" href="./index4.php" aria-expanded="false">
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
              <a class="sidebar-link" href="./index5.php" aria-expanded="false">
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
      </div>
    </aside>
    <div class="body-wrapper">
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
              <h5>歡迎回來！
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
                    <a href="../logout.php" class="btn btn-outline-danger mx-3 mt-2 d-block">登出</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <div class="container-fluid">
        <div class="row">
          <div class="d-flex align-items-strech">
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
                        <option value="">年份</option>
                      </select>
                      <select id="quarterDropdown" class="form-select ">
                        <option value="">季度</option>
                        <option value="1">第一季度</option>
                        <option value="2">第二季度</option>
                        <option value="3">第三季度</option>
                        <option value="4">第四季度</option>
                      </select>
                      <select id="monthDropdown" class="form-select ">
                        <option value="">月份</option>
                        <option value="1">1月</option>
                        <option value="2">2月</option>
                        <option value="3">3月</option>
                        <option value="4">4月</option>
                        <option value="5">5月</option>
                        <option value="6">6月</option>
                        <option value="7">7月</option>
                        <option value="8">8月</option>
                        <option value="9">9月</option>
                        <option value="10">10月</option>
                        <option value="11">11月</option>
                        <option value="12">12月</option>
                      </select>
                      <button id="searchButton" type="button" class="btn btn-outline-primary">搜尋</button>
                      <button id="resetButton" type="button" class="btn btn-outline-danger">重設</button>
                    </div>
                  </div>
                </div>
                <canvas id="salesChart"></canvas>
              </div>
            </div>
          </div>
        </div>
</body>
</html>