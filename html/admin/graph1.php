<!doctype html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location:authentication-login.php");
}
$id = $_SESSION["id"];
$nselfCount = $_SESSION["nselfCount"];
$nselfPerform = $_SESSION["nselfPerform"];
?>


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>業務一把罩</title>
    <link rel="shortcut icon" type="image/png" href="../../assets/images/logos/logo-sm.png" />
    <link rel="stylesheet" href="../../assets/css/styles.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
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
                <button onclick="window.location.href='index11.php'" type="button" class="btn btn-warning btn-lg"><i
                        class="ti ti-chevron-left"></i>Go Back</button>
                <button onclick="window.location.href='graph2.php'" type="button" class="btn btn-info btn-lg"
                    style="float: right;">客戶關係圖（被保人為自己）<i class="ti ti-chevron-right"></i></button>
                <div class="col-lg-12 d-flex align-items-stretch">
                    <div class="card w-100">
                        <div class="card-body">
                            <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                                <div class="mb-3 mb-sm-0">
                                    <h5 class="card-title fw-semibold"><b>業務員: <u>
                                                <?php echo $id ?>
                                            </u></b></h5>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card overflow-hidden">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title mb-9 fw-semibold">為<b><u><span
                                                        style="color:red;">別人</span></u></b>買的保單數</h5>
                                        <h3 class="card-title mb-9 fw-semibold" id="nselfCount"
                                            style="font-size: 300%;">
                                            <?php echo $nselfCount . "<br>" . '($' . $nselfPerform . ')'; ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <img id="graphImage" src="../../assets/images/1.1/graph1_<?php echo $id; ?>.png"
                                alt="Graph Image1">
                        </div>
                    </div>
                </div>
            </div>
</body>

</html>