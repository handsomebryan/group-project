<!doctype html>
<html lang="en">
<?php
session_start();

if (!isset($_SESSION["username"]) || $_SESSION["role"] != '0') {
    header("location:../login.php");
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
    <script src="../../assets/js/sidebarmenu.js"></script>
    <script src="../../assets/js/app.min.js"></script>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var salesChart;

            var idInput = '<?php echo $_SESSION["username"]; ?>';
            fetch('../get/getCID.php?idInput=' + idInput)
                .then(response => response.json())
                .then(data => {
                    var select = document.getElementById('c_id');
                    select.innerHTML = '';
                    var totalOption = document.createElement('option');
                    totalOption.value = '';
                    totalOption.text = '最近24月總計';
                    select.add(totalOption);

                    data.forEach(function (id) {
                        var option = document.createElement('option');
                        option.value = id;
                        option.text = id;
                        select.add(option);
                    });
                })
                .catch(error => console.error('Fetch error:', error));

            function fetchData() {
                var idInput = '<?php echo $_SESSION["username"]; ?>';
                var c_id = document.getElementById('c_id').value;
                var totalStatistic = (c_id === '');
                var url = `../get/getFrequency.php`;
                var queryParams = [];
                if (idInput) queryParams.push(`idInput=${idInput}`);
                if (c_id) queryParams.push(`c_id=${c_id}`);
                if (totalStatistic) queryParams.push(`total_statistic=true`);
                if (queryParams.length > 0) {
                    url += '?' + queryParams.join('&');
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            updateChart(data);
                        })
                        .catch(error => console.error('Fetch error:', error));
                } else {
                    alert("Please enter a Salesperson ID or Customer ID.");
                }
            }

            function updateChart(data) {
                var ctx = document.getElementById('salesChart').getContext('2d');
                if (salesChart) {
                    salesChart.destroy();
                }
                var combinedDates = [...new Set([...data.visit.map(d => d.日期), ...data.contact.map(d => d.日期)])].sort();
                salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: combinedDates,
                        datasets: [{
                            label: '拜訪次數',
                            data: combinedDates.map(date => {
                                var visit = data.visit.find(d => d.日期 === date);
                                return visit ? visit.拜訪次數 : 0;
                            }),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            fill: false
                        }, {
                            label: '聯絡次數',
                            data: combinedDates.map(date => {
                                var contact = data.contact.find(d => d.日期 === date);
                                return contact ? contact.聯絡次數 : 0;
                            }),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {
                        aspectRatio: 3,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: '日期',
                                    color: 'black'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: '次數',
                                    color: 'black'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
                var totalVisit = data.visit.reduce((total, visit) => total + parseInt(visit.拜訪次數), 0);
                var totalContact = data.contact.reduce((total, contact) => total + parseInt(contact.聯絡次數), 0);

                document.getElementById('totalVisit').textContent = totalVisit;
                document.getElementById('totalContact').textContent = totalContact;
            }

            document.getElementById('searchButton').addEventListener('click', function () {
                fetchData();
            });
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
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse"
                                href="javascript:void(0)">
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
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="../../assets/images/profile/user-1.jpg" alt="" width="35" height="35"
                                        class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
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
                    <div class="col-lg-8 d-flex align-items-strech">
                        <div class="card w-100">
                            <div class="card-body">
                                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                                    <div class="mb-3 mb-sm-0">
                                        <h5 class="card-title fw-semibold">業務員與客戶聯繫及拜訪頻率</h5>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <select id="c_id" class="form-select ">
                                                <option value="">最近12月總計</option>
                                            </select>
                                            <button id="searchButton" type="button"
                                                class="btn btn-outline-primary">搜尋</button>
                                        </div>
                                    </div>
                                </div>
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card overflow-hidden">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title mb-9 fw-semibold">總拜訪次數</h5>
                                        <h3 class="card-title mb-9 fw-semibold" id="totalVisit"
                                            style="font-size: 500%;"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card overflow-hidden">
                                <div class="card-body p-4 text-center">
                                    <h5 class="card-title mb-9 fw-semibold">總聯絡次數</h5>
                                    <h3 class="card-title mb-9 fw-semibold" id="totalContact" style="font-size: 500%;">
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</body>

</html>