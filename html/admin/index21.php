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
    document.addEventListener('DOMContentLoaded', function () {
      var salesChart; // Variable for the chart instance

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

        // Fetch quarters
        fetch(`../get/getQuarter.php?year=${year}`)
          .then(response => response.json())
          .then(quarters => {
            var select = document.getElementById('quarterDropdown');
            select.innerHTML = '<option value="">季度</option>'; // Clear the dropdown
            quarters.forEach(function (quarter) {
              var option = document.createElement('option');
              option.text = quarter;
              option.value = quarter;
              select.add(option);
            });
          })
          .catch(error => console.error('Error:', error));

        // Fetch months
        fetch(`../get/getMonth.php?year=${year}`)
          .then(response => response.json())
          .then(months => {
            var select = document.getElementById('monthDropdown');
            select.innerHTML = '<option value="">月份</option>'; // Clear the dropdown
            months.forEach(function (month) {
              var option = document.createElement('option');
              option.text = month;
              option.value = month;
              select.add(option);
            });
          })
          .catch(error => console.error('Error:', error));
      });

      var quarterDropdown = document.getElementById('quarterDropdown');
      var monthDropdown = document.getElementById('monthDropdown');

      quarterDropdown.addEventListener('change', function () {
        monthDropdown.disabled = !!this.value;
        monthDropdown.value = '';
      });

      monthDropdown.addEventListener('change', function () {
        quarterDropdown.disabled = !!this.value;
        quarterDropdown.value = '';
      });


      document.getElementById('searchButton').addEventListener('click', function () {
        var year = document.getElementById('yearDropdown').value;
        var quarter = document.getElementById('quarterDropdown').value;
        var month = document.getElementById('monthDropdown').value;
        var isSpecificMonth = !!month;

        var url = `../get/getBSData.php?year=${year}`;
        if (quarter) {
          url += `&quarter=${quarter}`;
        } else if (month) {
          url += `&month=${month}`;
        }

        fetch(url)
          .then(response => response.json())
          .then(data => updateChart(data, isSpecificMonth))
          .catch(error => console.error('Error:', error));
      });

      document.getElementById('resetButton').addEventListener('click', function () {
        document.getElementById('yearDropdown').selectedIndex = 0;
        document.getElementById('quarterDropdown').innerHTML = '<option value="">Select Quarter</option>';
        document.getElementById('monthDropdown').innerHTML = '<option value="">Select Month</option>';
        document.getElementById('quarterDropdown').innerHTML = '<option value="">Select Quarter</option>';
        document.getElementById('monthDropdown').innerHTML = '<option value="">Select Month</option>';
        quarterDropdown.selectedIndex = 0;
        monthDropdown.selectedIndex = 0;
        quarterDropdown.disabled = false;
        monthDropdown.disabled = false;
        if (salesChart) {
          salesChart.destroy();
        }
      });

      function updateChart(data) {
        var ctx = document.getElementById('salesChart').getContext('2d');
        if (salesChart) {
          salesChart.destroy();
        }

        var specificColors = ['#ed5739', '#64b579', '#a46ce0'];

        //  unique labels for the x-axis
        var labels = [...new Set(data.map(item => item[1]))];

        var datasets = [];
        var groupedData = data.reduce(function (acc, item) {
          if (!acc[item[0]]) {
            acc[item[0]] = Array(labels.length).fill(0);
          }
          var index = labels.indexOf(item[1]);
          acc[item[0]][index] = item[2];
          return acc;
        }, {});

        Object.keys(groupedData).forEach(function (key, index) {
          datasets.push({
            label: key, // 商品中文名稱(商品英文代碼)
            data: groupedData[key],
            borderColor: specificColors[index % specificColors.length],
            fill: false
          });
        });

        salesChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: labels,
            datasets: datasets
          },
          options: {
            aspectRatio: 3,
            scales: {
              x: {
                title: {
                  display: true,
                  text: '日期',
                  color: 'black',
                  weight: 'bold'
                }
              },
              y: {
                title: {
                  display: true,
                  text: '銷售額',
                  color: 'black',
                  weight: 'bold'
                },
                beginAtZero: true
              },
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
          <img src="../../assets/images/logos/logo.png" width="180" alt="" />
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
              <a class="sidebar-link" href="./index11.php" aria-expanded="false">
                <span>
                  <i class="ti ti-chart-dots-3"></i>
                </span>
                <span class="hide-menu">業務員&關係客戶群組</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index12.php" aria-expanded="false">
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
      <!--  Header End -->

      <div class="container-fluid">
        <!--  Row 1 -->
        <div class="row">
          <div class="col-lg-10 d-flex align-items-strech">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">表現最佳的保險產品</h5>
                  </div>
                </div>
                <div class="form-inline">
                  <div class="input-group">
                    <select id="yearDropdown" class="form-select ">
                      <option value="">年份</option>
                    </select>
                    <select id="quarterDropdown" class="form-select ">
                      <option value="">季度</option>
                    </select>
                    <select id="monthDropdown" class="form-select ">
                      <option value="">月份</option>
                    </select>
                    <button id="searchButton" type="button" class="btn btn-outline-primary">搜尋</button>
                    <button id="resetButton" type="button" class="btn btn-outline-danger">重設</button>
                  </div>
                </div>
                <canvas id="salesChart"></canvas>
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