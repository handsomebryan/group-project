<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$year = getQueryParam('year');
$quarter = getQueryParam('quarter');
$month = getQueryParam('month');
$id = getQueryParam('id');

$data = ['user' => [], 'T1' => [], 'T2' => []];

if ($id) {
    // Daily condition (specific month and year)
    if ($month && $year) {
        $userSql = "SELECT 
                        業務員序號,DATE(保單生效日) AS Date, SUM(年化保費) AS TotalSales
                    FROM 
                        保單資料
                    JOIN 
                        業務員保單序號 ON 保單資料.保單序號 = 業務員保單序號.保單序號
                    WHERE 
                        業務員保單序號.業務員序號 LIKE '%$id' AND YEAR(保單生效日) = '$year' AND MONTH(保單生效日) = '$month' AND 年化保費 != 0
                    GROUP BY 
                        DATE(保單生效日)";
    }
    // Monthly condition (specific year)
    elseif ($year && !$quarter) {
        $userSql = "SELECT 
                        業務員序號,YEAR(保單生效日) AS Year, MONTH(保單生效日) AS Month, SUM(年化保費) AS TotalSales
                    FROM 
                        保單資料
                    JOIN 
                        業務員保單序號 ON 保單資料.保單序號 = 業務員保單序號.保單序號
                    WHERE 
                        業務員保單序號.業務員序號 LIKE '%$id' AND YEAR(保單生效日) = '$year' AND 年化保費 != 0
                    GROUP BY 
                        YEAR(保單生效日), MONTH(保單生效日)
                    ";
    }
    // Quarterly condition
    elseif ($quarter) {
        $userSql = "SELECT 
                        業務員序號, YEAR(保單生效日) AS Year, MONTH(保單生效日) AS Month, SUM(年化保費) AS TotalSales
                    FROM 
                        保單資料
                    JOIN 
                        業務員保單序號 ON 保單資料.保單序號 = 業務員保單序號.保單序號
                    WHERE 
                        業務員保單序號.業務員序號 LIKE '%$id' AND YEAR(保單生效日) = '$year' AND QUARTER(保單生效日) = '$quarter' AND 年化保費 != 0
                    GROUP BY 
                        MONTH(保單生效日)";
    }
}

// Fetch top 1 salesperson's data excluding user-specific ID
//Daily condition (specific month and year)
if ($month && $year) {
    // Daily condition
    $top1SalesSql = "WITH SalespersonTotalSales AS (
                        SELECT 
                            sp.業務員序號, SUM(pol.年化保費) as TotalSales
                        FROM 
                            業務員保單序號 sp
                        JOIN 
                            保單資料 pol ON sp.保單序號 = pol.保單序號
                        WHERE 
                            YEAR(pol.保單生效日) = '$year' AND sp.業務員序號 NOT LIKE '%$id'
                        GROUP BY 
                            sp.業務員序號
                    ),
                    TopSalesperson AS (
                        SELECT 
                            業務員序號
                        FROM 
                            SalespersonTotalSales
                        ORDER BY 
                            TotalSales DESC
                        LIMIT 1
                    )
                    SELECT 
                        sp.業務員序號, DATE(pol.保單生效日) as Date, SUM(pol.年化保費) as TotalSales
                    FROM   
                        業務員保單序號 sp
                    JOIN 
                        保單資料 pol ON sp.保單序號 = pol.保單序號
                    JOIN 
                        TopSalesperson tp ON sp.業務員序號 = tp.業務員序號
                    WHERE 
                        YEAR(pol.保單生效日) = '$year' AND MONTH(pol.保單生效日) = '$month'
                    GROUP BY 
                        sp.業務員序號, DATE(pol.保單生效日)
                    HAVING 
                        SUM(pol.年化保費) > 0
                    ";
} elseif ($quarter && $year) {
    // Quarterly condition
    $top1SalesSql = "WITH SalespersonTotalSales AS (
                        SELECT 
                            sp.業務員序號, SUM(pol.年化保費) as TotalSales
                        FROM   
                            業務員保單序號 sp
                        JOIN 
                            保單資料 pol ON sp.保單序號 = pol.保單序號
                        WHERE 
                            YEAR(pol.保單生效日) = '$year' AND QUARTER(pol.保單生效日) = '$quarter' AND sp.業務員序號 NOT LIKE '%$id'
                        GROUP BY 
                            sp.業務員序號
                    ),
                    TopSalesperson AS (
                        SELECT 
                            業務員序號
                        FROM 
                            SalespersonTotalSales
                        ORDER BY 
                            TotalSales DESC
                        LIMIT 1
                    )
                    SELECT 
                        sp.業務員序號,YEAR(pol.保單生效日) AS Year, MONTH(pol.保單生效日) as Month, SUM(pol.年化保費) as TotalSales
                    FROM 
                        業務員保單序號 sp
                    JOIN 
                        保單資料 pol ON sp.保單序號 = pol.保單序號
                    JOIN 
                        TopSalesperson tp ON sp.業務員序號 = tp.業務員序號
                    WHERE 
                        YEAR(pol.保單生效日) = '$year' AND QUARTER(pol.保單生效日) = '$quarter'
                    GROUP BY 
                        YEAR(pol.保單生效日), MONTH(pol.保單生效日)
                    ";
} elseif ($year && !$month) {
    // Monthly condition
    $top1SalesSql = "WITH SalespersonTotalSales AS (
                        SELECT 
                            sp.業務員序號, SUM(pol.年化保費) as TotalSales
                        FROM 
                            業務員保單序號 sp
                        JOIN 
                            保單資料 pol ON sp.保單序號 = pol.保單序號
                        WHERE 
                            YEAR(pol.保單生效日) = '$year' AND sp.業務員序號 NOT LIKE '%$id'
                        GROUP BY 
                            sp.業務員序號
                    ),
                    TopSalesperson AS (
                        SELECT 
                            業務員序號
                        FROM 
                            SalespersonTotalSales
                        ORDER BY 
                            TotalSales DESC
                        LIMIT 1
                    )
                    SELECT 
                        sp.業務員序號,YEAR(pol.保單生效日) AS Year, MONTH(pol.保單生效日) as Month, SUM(pol.年化保費) as TotalSales
                    FROM 
                        業務員保單序號 sp
                    JOIN 
                        保單資料 pol ON sp.保單序號 = pol.保單序號
                    JOIN 
                        TopSalesperson tp ON sp.業務員序號 = tp.業務員序號
                    WHERE 
                        YEAR(pol.保單生效日) = '$year'
                    GROUP BY 
                        YEAR(pol.保單生效日), MONTH(pol.保單生效日)
                    ";
}
// T2 salesperson
if ($month && $year) {
    // Daily condition
    $top2SalesSql = "WITH SalespersonTotalSales AS (
                        SELECT 
                            sp.業務員序號, 
                            SUM(pol.年化保費) AS TotalSales
                        FROM 
                            業務員保單序號 sp
                        JOIN 
                            保單資料 pol ON sp.保單序號 = pol.保單序號
                        WHERE 
                            YEAR(pol.保單生效日) = '$year' AND MONTH(pol.保單生效日) = '$month' AND sp.業務員序號 NOT LIKE '%$id'
                        GROUP BY 
                            sp.業務員序號
                    ),
                    RankedSalespersons AS (
                        SELECT 
                            業務員序號, 
                            TotalSales,
                            RANK() OVER (ORDER BY TotalSales DESC) AS SalesRank
                        FROM 
                            SalespersonTotalSales
                    ),
                    SecondTopSalesperson AS (
                        SELECT 
                            業務員序號
                        FROM 
                            RankedSalespersons
                        WHERE 
                            SalesRank = 2
                    )
                    SELECT 
                        sp.業務員序號, DATE(pol.保單生效日) AS Date, SUM(pol.年化保費) AS TotalSales
                    FROM 
                        業務員保單序號 sp
                    JOIN 
                        保單資料 pol ON sp.保單序號 = pol.保單序號
                    JOIN 
                        SecondTopSalesperson tp ON sp.業務員序號 = tp.業務員序號
                    WHERE 
                        YEAR(pol.保單生效日) = '$year' AND MONTH(pol.保單生效日) = '$month'
                    GROUP BY 
                        DATE(pol.保單生效日)
                    HAVING 
                        SUM(pol.年化保費) > 0
                    ";
} elseif ($quarter && $year) {
    // Quarterly condition
    $top2SalesSql = "WITH SalespersonTotalSales AS (
                        SELECT 
                            sp.業務員序號, 
                            SUM(pol.年化保費) AS TotalSales
                        FROM 
                            業務員保單序號 sp
                        JOIN 
                            保單資料 pol ON sp.保單序號 = pol.保單序號
                        WHERE 
                            YEAR(pol.保單生效日) = '$year' AND QUARTER(pol.保單生效日) = '$quarter' AND sp.業務員序號 NOT LIKE '%$id'
                        GROUP BY 
                            sp.業務員序號
                    ),
                    RankedSalespersons AS (
                        SELECT 
                            業務員序號, 
                            TotalSales,
                            RANK() OVER (ORDER BY TotalSales DESC) AS SalesRank
                        FROM 
                            SalespersonTotalSales
                    ),
                    SecondTopSalesperson AS (
                        SELECT 
                            業務員序號
                        FROM 
                            RankedSalespersons
                        WHERE 
                            SalesRank = 2
                    )
                    SELECT 
                        sp.業務員序號, YEAR(pol.保單生效日) AS Year, MONTH(pol.保單生效日) AS Month, SUM(pol.年化保費) AS TotalSales
                    FROM 
                        業務員保單序號 sp
                    JOIN 
                        保單資料 pol ON sp.保單序號 = pol.保單序號
                    JOIN 
                        SecondTopSalesperson tp ON sp.業務員序號 = tp.業務員序號
                    WHERE 
                        YEAR(pol.保單生效日) = '$year' AND QUARTER(pol.保單生效日) = '$quarter'
                    GROUP BY 
                        YEAR(pol.保單生效日), MONTH(pol.保單生效日);
                    ";
} elseif ($year && !$month) {
    // Monthly condition
    $top2SalesSql = "WITH SalespersonTotalSales AS (
                        SELECT 
                            sp.業務員序號, 
                            SUM(pol.年化保費) AS TotalSales
                        FROM 
                            業務員保單序號 sp
                        JOIN 
                            保單資料 pol ON sp.保單序號 = pol.保單序號
                        WHERE 
                            YEAR(pol.保單生效日) = '$year' AND sp.業務員序號 NOT LIKE '%$id'
                        GROUP BY 
                            sp.業務員序號
                    ),
                    RankedSalespersons AS (
                        SELECT 
                            業務員序號, 
                            TotalSales,
                            RANK() OVER (ORDER BY TotalSales DESC) AS SalesRank
                        FROM 
                            SalespersonTotalSales
                    ),
                    SecondTopSalesperson AS (
                        SELECT 
                            業務員序號
                        FROM 
                            RankedSalespersons
                        WHERE 
                            SalesRank = 2
                    )
                    SELECT 
                        sp.業務員序號, YEAR(pol.保單生效日) AS Year, MONTH(pol.保單生效日) AS Month, SUM(pol.年化保費) AS TotalSales
                    FROM 
                        業務員保單序號 sp
                    JOIN 
                        保單資料 pol ON sp.保單序號 = pol.保單序號
                    JOIN 
                        SecondTopSalesperson tp ON sp.業務員序號 = tp.業務員序號
                    WHERE 
                        YEAR(pol.保單生效日) = '$year'
                    GROUP BY 
                        YEAR(pol.保單生效日), MONTH(pol.保單生效日);
                    ";
}

$userResult = $conn->query($userSql);
while ($row = $userResult->fetch_assoc()) {
    $data['user'][] = $row;
}
$top1Result = $conn->query($top1SalesSql);
while ($row = $top1Result->fetch_assoc()) {
    $data['T1'][] = $row;
}
$top2Result = $conn->query($top2SalesSql);
while ($row = $top2Result->fetch_assoc()) {
    $data['T2'][] = $row;
}

echo json_encode($data);
?>