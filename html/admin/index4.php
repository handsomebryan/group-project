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
    document.addEventListener('DOMContentLoaded', function () {
      var salesChart;
      document.getElementById('searchButton').addEventListener('click', function () {
        fetchData();
      });
      document.getElementById('resetButton').addEventListener('click', function () {
        resetForm();
      });
      function fetchData() {
        var id = document.getElementById('idInput').value;
        var url = `../get/getPerform.php`; // Change this line
        var queryParams = [];
        if (id) queryParams.push(`id=${id}`);
        if (queryParams.length > 0) {
          url += '?' + queryParams.join('&');
          fetch(url)
            .then(response => response.json())
            .then(data => {
              updateChart(data);
            })
            .catch(error => console.error('Fetch error:', error));
        } else {
          alert("Please enter a User ID.");
        }
      }

      function resetForm() {
        document.getElementById('idInput').value = '';
        if (salesChart) {
          salesChart.destroy();
        }
      }
      function updateChart(data) {
        var ctx = document.getElementById('salesChart').getContext('2d');
        if (salesChart) {
          salesChart.destroy();
        }
        salesChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.user.map(d => d.商品大分類),
            datasets: [{
              label: '指定業務員 (業務員序號: ' + (data.user.length > 0 ? data.user[0].業務員序號.slice(-5) : 'N/A') + ')',
              data: data.user.map(d => d.total_sales),
              backgroundColor: 'rgba(255, 99, 132, 0.2)',
              datalabels: {
                color: 'black',
                align: 'right',
                offset: 10
              }
            }, {
              label: '銷量第一名 (業務員序號: ' + (data.T1.length > 0 ? data.T1[0].業務員序號.slice(-5) : 'N/A') + ')',
              data: data.T1.map(d => d.total_sales),
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              datalabels: {
                color: 'black',
                align: 'right',
                offset: 10
              }
            }, {
              label: '銷量第二名 (業務員序號: ' + (data.T2.length > 0 ? data.T2[0].業務員序號.slice(-5) : 'N/A') + ')',
              data: data.T2.map(d => d.total_sales),
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              datalabels: {
                color: 'black',
                align: 'right',
                offset: 10
              }
            }]
          },
          plugins: [ChartDataLabels],
          options: {
            indexAxis: 'y',
            aspectRatio: 2,
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
                  text: '商品大分類',
                  color: 'black',
                  weight: 'bold'
                }
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
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="../../assets/images/logos/logo.png" width="180" alt="" />
          </a>
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
          <div class="col-lg-30 d-flex align-items-strech">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">業務員與保險關係</h5>
                  </div>
                </div>
                <div class="form-inline">
                  <div class="form-group">
                    <div class="input-group">
                      <input type="text" class="form-control" id="idInput" placeholder="業務員序號(後5碼)">
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