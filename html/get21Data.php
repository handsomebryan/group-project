<?php
include 'dbconnect.php';

$year = isset($_GET['year']) ? $_GET['year'] : '';
$quarter = isset($_GET['quarter']) ? $_GET['quarter'] : '';
$month = isset($_GET['month']) ? $_GET['month'] : '';

// Modify SQL query based on whether quarter or month is selected
if ($quarter) {
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
        TP.商品英文代碼, 月份
    ";
} elseif ($month) {
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
        TP.商品英文代碼, 日期
    ORDER BY 
        TP.商品英文代碼, 日期        
    ";
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
        YEAR(保單生效日) = '$year'
    GROUP BY 
        TP.商品英文代碼, 月份
    ORDER BY 
        TP.商品英文代碼, 月份
    ";
}
$result = $conn->query($sql);
$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = [$row['商品英文代碼'], (int)$row['月份'], (float)$row['月銷售額']];
}

echo json_encode($data);
?>
