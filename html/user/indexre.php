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
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var ctx = document.getElementById('myChart').getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: [],
          datasets: [{
            data: [],
            backgroundColor: ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f'],
            label: 'Purchased'
          }]
        }, options: {
          aspectRatio: 3,
        }
      });

      // Function to fetch postal codes
      function fetchPostalCodes() {
        fetch('../get/getPC.php')
          .then(response => response.json())
          .then(data => {
            populatePostalCodes(data);
          })
          .catch(error => console.error('Error:', error));
      }

      // Function to populate postal code dropdown
      function populatePostalCodes(postalCodes) {
        var postalCodeSelect = document.getElementById('postalCode');
        postalCodeSelect.innerHTML = '<option value="">郵遞區號</option>';
        postalCodes.forEach(function (code) {
          var option = document.createElement('option');
          option.value = code;
          option.textContent = code;
          postalCodeSelect.appendChild(option);
        });
      }

      // Fetch postal codes when the page loads
      fetchPostalCodes();

      document.getElementById('searchButton').addEventListener('click', function () {
        var gender = document.getElementById('gender').value;
        var postalCode = document.getElementById('postalCode').value;
        var age = document.getElementById('age').value;

        fetch(`../get/getREData.php?gender=${gender}&postalCode=${postalCode}&age=${age}`)
          .then(response => response.json())
          .then(data => updateChart(myChart, data))
          .catch(error => console.error('Error:', error));
      });

      document.getElementById('resetButton').addEventListener('click', function () {
        document.getElementById('gender').value = '';
        document.getElementById('postalCode').value = '';
        document.getElementById('age').value = '';
        updateChart(myChart, []); // Clear the chart
      });
    });

    function updateChart(chart, data) {
      chart.data.labels = data.map(row => row[0]); // Product names
      chart.data.datasets[0].data = data.map(row => row[2]); // Purchase counts
      chart.update();
    }
  </script>
</head>

<body>

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
            <div class=" d-flex align-items-strech">
              <div class="card w-100">
                <div class="card-body">
                  <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                    <div class="mb-3 mb-sm-0">
                      <h5 class="card-title fw-semibold">客戶產品推薦</h5>
                    </div>
                  </div>
                  <div class="input-group">
                    <select id="gender" class="form-select ">
                      <option value="">性別</option>
                      <option value="男">男性</option>
                      <option value="女">女性</option>
                    </select>

                    <select id="postalCode" class="form-select ">
                      <option value="">郵遞區號</option>
                    </select>
                    <input type="number" class="form-control" id="age" placeholder="年齡">

                    <button id="searchButton" type="button" class="btn btn-outline-primary">搜尋</button>
                    <button id="resetButton" type="button" class="btn btn-outline-danger">重設</button>
                  </div>
                  <canvas id="myChart"></canvas>
  </body>

</html>