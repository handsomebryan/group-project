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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#searchButton").click(function () {
                var id = $("#idInput").val();
                $.ajax({
                    url: '../get/getCRelation.php',
                    type: 'get',
                    data: { id: id },
                    success: function (response) {
                        $("#graphContainer").html(response);
                    }
                });
            });
        });
        $(document).ready(function () {
            $('.zoomable').click(function () {
                $(this).toggleClass('zoom');
            });
        });
    </script>
    <style>
        .graphContainer {
            overflow: auto;
            max-height: 500px;
        }

        .graphContainer img.zoomable {
            width: 100%;
            height: auto;
            transition: transform 0.25s ease;
            cursor: zoom-in;
        }

        img.zoom {
            transform: scale(2);
            cursor: zoom-out;
        }
    </style>


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
                                        <h5 class="card-title fw-semibold">業務員的銷售業績</h5>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="idInput"
                                                placeholder="業務員序號(後5碼)">
                                            <button id="searchButton" type="button"
                                                class="btn btn-outline-primary">Search</button>
                                            <button id="resetButton" type="button"
                                                class="btn btn-outline-danger">Reset</button>
                                        </div>
                                        <div id="graphContainer">
                                            <img id="graphImage" src="" alt="">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card overflow-hidden">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-9 fw-semibold">Yearly Breakup</h5>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h4 class="fw-semibold mb-3">$36,358</h4>
                                                <div class="d-flex align-items-center mb-3">
                                                    <span
                                                        class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-arrow-up-left text-success"></i>
                                                    </span>
                                                    <p class="text-dark me-1 fs-3 mb-0">+9%</p>
                                                    <p class="fs-3 mb-0">last year</p>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-4">
                                                        <span
                                                            class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                                                        <span class="fs-2">2023</span>
                                                    </div>
                                                    <div>
                                                        <span
                                                            class="round-8 bg-light-primary rounded-circle me-2 d-inline-block"></span>
                                                        <span class="fs-2">2023</span>
                                                    </div>
                                                </div>
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
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row alig n-items-start">
                                            <div class="col-8">
                                                <h5 class="card-title mb-9 fw-semibold"> Monthly Earnings </h5>
                                                <h4 class="fw-semibold mb-3">$6,820</h4>
                                                <div class="d-flex align-items-center pb-1">
                                                    <span
                                                        class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-arrow-down-right text-danger"></i>
                                                    </span>
                                                    <p class="text-dark me-1 fs-3 mb-0">+9%</p>
                                                    <p class="fs-3 mb-0">last year</p>
                                                </div>
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
                                    <div id="earning"></div>
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