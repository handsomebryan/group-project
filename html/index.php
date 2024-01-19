<?php
include 'dbconnect.php';

// PHP Code for Data Processing
$data = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $year = $_POST['year'];
    $quarter = $_POST['quarter'];
    $month = $_POST['month'];

    // Replace with your SQL queries
    if (!empty($month)) {
        $sql = "WITH TopProducts AS (
    SELECT 
        商品英文代碼,
        SUM(年化保費) as 總銷售額
    FROM 
        保單資料
    WHERE 
        YEAR(保單生效日) = '$year'and MONTH(保單生效日)= '$month' 
    GROUP BY 
        商品英文代碼
    ORDER BY 
        總銷售額 DESC
    LIMIT 3
)

-- 商品每月的銷售額
SELECT 
    TP.商品英文代碼,
    DAY(保單生效日) as 日期,
    SUM(年化保費) as 日銷售額
FROM 
    保單資料 PD
JOIN 
    TopProducts TP ON PD.商品英文代碼 = TP.商品英文代碼
WHERE 
    YEAR(保單生效日) = '$year'and MONTH(保單生效日) = '$month' 
GROUP BY 
    TP.商品英文代碼, 月份, 日期
ORDER BY 
    TP.商品英文代碼, 月份, 日期;

";  // Monthly SQL
    } elseif (!empty($quarter)) {
        $sql = "WITH TopProducts AS (
          SELECT 
              商品英文代碼,
              SUM(年化保費) as 總銷售額
          FROM 
              保單資料
          WHERE 
              YEAR(保單生效日) = '$year' 
              AND QUARTER(保單生效日) = '$quarter' -- 1-4
          GROUP BY 
              商品英文代碼
          ORDER BY 
              總銷售額 DESC
          LIMIT 3
      )
      SELECT 
          TP.商品英文代碼,
          MONTH(保單生效日) as 月份,
          SUM(年化保費) as 月銷售額
      FROM 
          保單資料 PD
      JOIN 
          TopProducts TP ON PD.商品英文代碼 = TP.商品英文代碼
      WHERE 
          YEAR(保單生效日) = '$year'
          AND QUARTER(保單生效日) = '$quarter' -- 1-4
      GROUP BY 
          TP.商品英文代碼, 月份
      ORDER BY 
          TP.商品英文代碼, 月份;
      
      ";  // Quarterly SQL
    } else {
        $sql = "WITH TopProducts AS (
          SELECT 
              商品英文代碼,
              SUM(年化保費) as 總銷售額
          FROM 
              保單資料
          WHERE 
              YEAR(保單生效日) = '$year' 
          GROUP BY 
              商品英文代碼
          ORDER BY 
              總銷售額 DESC
          LIMIT 3
      )
      -- 商品每月的銷售額
      SELECT 
          TP.商品英文代碼,
          MONTH(保單生效日) as 月份,
          SUM(年化保費) as 月銷售額
      FROM 
          保單資料 PD
      JOIN 
          TopProducts TP ON PD.商品英文代碼 = TP.商品英文代碼
      WHERE 
          YEAR(保單生效日) = ''$year
      GROUP BY 
          TP.商品英文代碼, 月份
      ORDER BY 
          TP.商品英文代碼, 月份;
      
      ";  // Yearly SQL
    }

    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $data[] = array_values($row);
    }

    echo json_encode($data);
    exit; // Important to prevent further script execution
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Sales Chart</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <form id="chartForm">
        <select id="yearDropdown" name="year">
            <!-- Year options populated by PHP -->
        </select>
        <select id="quarterDropdown" name="quarter">
            <option value="">Select Quarter</option>
            <!-- Quarter options -->
        </select>
        <select id="monthDropdown" name="month">
            <option value="">Select Month</option>
            <!-- Month options -->
        </select>
        <button type="button" id="searchButton">Search</button>
        <button type="button" id="resetButton">Reset</button>
    </form>

    <div id="lineChart"></div>

    <script>
        google.charts.load('current', {'packages':['line']});
        google.charts.setOnLoadCallback(initializeChart);

        function initializeChart() {
            // JavaScript Code for Dropdown Populating and Event Listeners
        }

        function updateChart() {
            var formData = new FormData(document.getElementById('chartForm'));
            fetch('chartPage.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                drawChart(data);
            })
            .catch(error => console.error('Error:', error));
        }

        function drawChart(chartData) {
            // JavaScript Code for Chart Drawing
        }
    </script>
</body>
</html>
