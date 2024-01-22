<?php
include 'dbconnect.php';

function getQueryParam($paramName) {
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$year = getQueryParam('year');
$quarter = getQueryParam('quarter');
$month = getQueryParam('month');

if (empty($year)) {
    echo json_encode(["error" => "Year is required"]);
    exit;
}

$sql = "";
if ($quarter) {
    // Quarterly condition
    $sql = "WITH TopProducts AS (
        SELECT 
            P.商品英文代碼, 
            P.商品中文名稱, 
            SUM(年化保費) as 銷售額
        FROM 
            保單資料 B
        JOIN 
            商品資料 P ON B.商品英文代碼 = P.商品英文代碼
        WHERE 
            YEAR(B.保單生效日) = '$year' AND QUARTER(B.保單生效日) = '$quarter' AND 年化保費 != 0
        GROUP BY 
            P.商品英文代碼, P.商品中文名稱 
        ORDER BY 
            銷售額 DESC
        LIMIT 3
    )
    SELECT 
        TP.商品英文代碼, 
        TP.商品中文名稱,
        DATE_FORMAT(PD.保單生效日, '%Y-%m') AS m, 
        SUM(PD.年化保費) as 銷售額
    FROM 
        保單資料 PD
    JOIN 
        TopProducts TP ON PD.商品英文代碼 = TP.商品英文代碼
    WHERE 
        YEAR(PD.保單生效日) = '$year' AND QUARTER(PD.保單生效日) = '$quarter' AND PD.年化保費 != 0
    GROUP BY 
        TP.商品英文代碼, TP.商品中文名稱, MONTH(PD.保單生效日)
    ORDER BY 
        DATE_FORMAT(PD.保單生效日, '%Y-%m'),TP.商品英文代碼;
    ";
} elseif ($month) {
    // Daily condition - Adjust the date format to show full date
    $sql = "WITH TopProducts AS (
        SELECT 
            PD.商品英文代碼,
            GD.商品中文名稱,
            SUM(PD.年化保費) as 總銷售額
        FROM 
            保單資料 PD
        JOIN 
            商品資料 GD ON PD.商品英文代碼 = GD.商品英文代碼
        WHERE 
            YEAR(PD.保單生效日) = '$year' AND MONTH(PD.保單生效日)= '$month' AND PD.年化保費 != 0
        GROUP BY 
            PD.商品英文代碼, GD.商品中文名稱
        ORDER BY 
            總銷售額 DESC
        LIMIT 3
    )
    SELECT 
        TP.商品英文代碼,
        TP.商品中文名稱,
        DATE_FORMAT(DATE(PD.保單生效日), '%Y-%m-%d') as m,
        SUM(PD.年化保費) as 銷售額
    FROM 
        保單資料 PD
    JOIN 
        TopProducts TP ON PD.商品英文代碼 = TP.商品英文代碼
    WHERE 
        YEAR(PD.保單生效日) = '$year' AND MONTH(PD.保單生效日)= '$month' AND PD.年化保費 != 0
    GROUP BY 
        TP.商品英文代碼, TP.商品中文名稱, DATE(PD.保單生效日)
    ORDER BY 
         DATE_FORMAT(DATE(PD.保單生效日), '%Y-%m-%d'),TP.商品英文代碼;";
} elseif ($year) {
    // Yearly condition
    $sql = "WITH TopProducts AS (
        SELECT 
            PD.商品英文代碼,
            SUM(PD.年化保費) AS 銷售額,
            G.商品中文名稱
        FROM 
            保單資料 PD
        JOIN 
            商品資料 G ON PD.商品英文代碼 = G.商品英文代碼
        WHERE 
            YEAR(PD.保單生效日) = '$year' AND PD.年化保費 != 0
        GROUP BY 
            PD.商品英文代碼, G.商品中文名稱
        ORDER BY 
            銷售額 DESC
        LIMIT 3
    )
    SELECT 
        TP.商品英文代碼,
        TP.商品中文名稱,
        DATE_FORMAT(PD.保單生效日, '%Y-%m') AS m,
        SUM(PD.年化保費) AS 銷售額
    FROM 
        保單資料 PD
    JOIN 
        TopProducts TP ON PD.商品英文代碼 = TP.商品英文代碼
    WHERE 
        YEAR(PD.保單生效日) = '$year' AND PD.年化保費 != 0
    GROUP BY 
        TP.商品英文代碼, TP.商品中文名稱, MONTH(PD.保單生效日)
    ORDER BY 
        DATE_FORMAT(PD.保單生效日, '%Y-%m'),TP.商品英文代碼;
    ";
}

$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $label = $row['商品中文名稱'] . '(' . $row['商品英文代碼'] . ')';
    $data[] = [$label, $row['m'], (float) $row['銷售額']];
}

echo json_encode($data);
?>
