<?php
include 'dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$year = getQueryParam('year');
$quarter = getQueryParam('quarter');
$month = getQueryParam('month');

if (empty($year)) {
    echo json_encode(["error" => "Year is required"]);
    exit;
}
if ($quarter) {
    // Logic for year and quarter selection
    $sql = "WITH TopProducts AS (
                SELECT 商品英文代碼, SUM(年化保費) as 銷售額
                FROM 保單資料
                WHERE YEAR(保單生效日) = '$year' AND QUARTER(保單生效日) = '$quarter'
                GROUP BY 商品英文代碼
                ORDER BY 銷售額 DESC
                LIMIT 3
            )
            SELECT TP.商品英文代碼, MONTH(保單生效日) as m, SUM(年化保費) as 銷售額
            FROM 保單資料 PD
            JOIN TopProducts TP ON PD.商品英文代碼 = TP.商品英文代碼
            WHERE YEAR(保單生效日) = '$year' AND QUARTER(保單生效日) = '$quarter'
            GROUP BY TP.商品英文代碼, MONTH(保單生效日)
            ORDER BY TP.商品英文代碼, MONTH(保單生效日)";
} elseif ($month) {
    //  logic for month selection
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
        DAY(保單生效日) as m,
        SUM(年化保費) as 銷售額
    FROM 
        保單資料 PD
    JOIN 
        TopProducts TP ON PD.商品英文代碼 = TP.商品英文代碼
    WHERE 
        YEAR(保單生效日) = '$year'and MONTH(保單生效日) = '$month' 
    GROUP BY 
        TP.商品英文代碼, m
    ORDER BY 
        TP.商品英文代碼, m
        ";
} elseif ($quarter) {
    //logic for quarter-only selection
    $sql = "";
} elseif ($year) {
    // Logic for year-only selection
    $sql = "WITH TopProducts AS (
                SELECT 商品英文代碼, SUM(年化保費) as 銷售額
                FROM 保單資料
                WHERE YEAR(保單生效日) = '$year'
                GROUP BY 商品英文代碼
                ORDER BY 銷售額 DESC
                LIMIT 3
            )
            SELECT TP.商品英文代碼, MONTH(保單生效日) as m, SUM(年化保費) as 銷售額
            FROM 保單資料 PD
            JOIN TopProducts TP ON PD.商品英文代碼 = TP.商品英文代碼
            WHERE YEAR(保單生效日) = '$year'
            GROUP BY TP.商品英文代碼, MONTH(保單生效日)
            ORDER BY TP.商品英文代碼, MONTH(保單生效日)";
}

$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [$row['商品英文代碼'], (int) $row['m'], (float) $row['銷售額']];
}

echo json_encode($data);
?>